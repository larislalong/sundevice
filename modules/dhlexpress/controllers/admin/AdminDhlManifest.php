<?php
/**
 * 2007-2018 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2018 PrestaShop SA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

/**
 * Class AdminDhlManifestController
 */
class AdminDhlManifestController extends ModuleAdminController
{
    /**
     * AdminDhlManifestController constructor.
     * @throws PrestaShopException
     */
    public function __construct()
    {
        require_once(dirname(__FILE__).'/../../api/loader.php');
        require_once(dirname(__FILE__).'/../../classes/DhlCommercialInvoice.php');
        require_once(dirname(__FILE__).'/../../classes/HTMLTemplateDhlManifest.php');
        require_once(dirname(__FILE__).'/../../classes/DhlTools.php');
        require_once(dirname(__FILE__).'/../../classes/DhlOrder.php');
        require_once(dirname(__FILE__).'/../../classes/DhlAddress.php');
        require_once(dirname(__FILE__).'/../../classes/DhlPackage.php');
        require_once(dirname(__FILE__).'/../../classes/DhlLabel.php');
        require_once(dirname(__FILE__).'/../../classes/DhlService.php');
        require_once(dirname(__FILE__).'/../../classes/DhlExtracharge.php');
        require_once(dirname(__FILE__).'/../../classes/DhlManifest.php');
        require_once(dirname(__FILE__).'/../../classes/DhlPDFGenerator.php');

        $this->bootstrap = true;
        $this->context = Context::getContext();
        parent::__construct();
        $this->getDHLOrderList();
        $this->bulk_actions = true;
        $this->context->smarty->assign(
            array(
                'dhl_img_path' => $this->module->getPathUri().'views/img/',
            )
        );
    }

    /**
     * @return string
     * @throws Exception
     * @throws SmartyException
     */
    public function renderList()
    {
        $this->context->smarty->assign(
            array(
                'dhl_img_path' => $this->module->getPathUri().'views/img/',
            )
        );
        $header = $this->createTemplate('../_partials/dhl-header.tpl')->fetch();
        if (!($this->fields_list && is_array($this->fields_list))) {
            return false;
        }
        $this->getList($this->context->language->id);

        // If list has 'active' field, we automatically create bulk action
        if (isset($this->fields_list) &&
            is_array($this->fields_list) &&
            array_key_exists('active', $this->fields_list) &&
            !empty($this->fields_list['active'])
        ) {
            if (!is_array($this->bulk_actions)) {
                $this->bulk_actions = array();
            }

            $this->bulk_actions = array_merge(
                array(
                    'enableSelection'  => array(
                        'text' => $this->l('Enable selection'),
                        'icon' => 'icon-power-off text-success',
                    ),
                    'disableSelection' => array(
                        'text' => $this->l('Disable selection'),
                        'icon' => 'icon-power-off text-danger',
                    ),
                    'divider'          => array(
                        'text' => 'divider',
                    ),
                ),
                $this->bulk_actions
            );
        }

        $helper = new HelperList();

        // Empty list is ok
        if (!is_array($this->_list)) {
            $this->displayWarning($this->l('Bad SQL query', 'Helper').'<br />'.htmlspecialchars($this->_list_error));

            return false;
        }

        $this->setHelperDisplay($helper);
        $helper->_default_pagination = $this->_default_pagination;
        $helper->_pagination = $this->_pagination;
        $helper->tpl_vars = $this->getTemplateListVars();
        $helper->tpl_delete_link_vars = $this->tpl_delete_link_vars;
        $helper->force_show_bulk_actions = true;

        // For compatibility reasons, we have to check standard actions in class attributes
        foreach ($this->actions_available as $action) {
            if (!in_array($action, $this->actions) && isset($this->$action) && $this->$action) {
                $this->actions[] = $action;
            }
        }

        $helper->is_cms = $this->is_cms;
        $helper->sql = $this->_listsql;
        $list = $helper->generateList($this->_list, $this->fields_list);

        return $header.$list;
    }

    /**
     * @return void
     * @throws PrestaShopException
     */
    public function postProcess()
    {
        parent::postProcess();
        $vars = array();
        $submitCustomer = Tools::isSubmit('submitBulkDhlManifestCustomerdhl_order');
        $submitCarrier = Tools::isSubmit('submitBulkDhlManifestCarrierdhl_order');
        if ($submitCustomer || $submitCarrier) {
            $dhlLabelIds = Tools::getValue('dhl_labelBox');
            if ($dhlLabelIds) {
                foreach ($dhlLabelIds as $dhlLabelId) {
                    $dhlLabel = new DhlLabel((int) $dhlLabelId);
                    if ($dhlLabel && Validate::isLoadedObject($dhlLabel)) {
                        $vars[] = array(
                            'awb_number'            => $dhlLabel->awb_number,
                            'consignee_contact'     => $dhlLabel->consignee_contact,
                            'consignee_destination' => $dhlLabel->consignee_destination,
                            'total_pieces'          => $dhlLabel->total_pieces,
                            'total_weight'          => $dhlLabel->total_weight,
                            'piece_contents'        => $dhlLabel->piece_contents,
                            'shipper_reference'     => $dhlLabel->awb_number,
                        );
                    }
                }
                $dhlManifest = new DhlManifest();
                $dhlManifest->shippingDetails = $vars;
                $dhlManifest->type = $submitCarrier ? 'CA' : 'CU';
                $dhlManifest->imgPath = $this->module->getPathUri().'views/img/';
                $pdf = new PDF($dhlManifest, 'DhlManifest', $this->context->smarty);
                $pdf->pdf_renderer = new DhlPDFGenerator((bool) Configuration::get('PS_PDF_USE_CACHE'));
                $pdf->pdf_renderer->SetMargins(10, 60, 10);
                $pdf->pdf_renderer->SetFooterMargin(80);
                $pdf->pdf_renderer->SetAutoPageBreak(true, 87);
                $pdf->render(true);
            } else {
                $this->errors = $this->module->l('Please select at leat one order.', 'AdminDhlManifest');
            }
        }
    }

    /**
     *
     */
    public function getDHLOrderList()
    {
        $selectFields = array(
            'a.id_dhl_label',
            'do.id_order',
            'o.date_add AS order_date',
            'o.reference',
            'a.awb_number',
            'a.date_add AS label_date',
            'CONCAT(LEFT(c.`firstname`, 1), ". ", c.`lastname`) AS customer',
            'os.color',
            'osl.name AS status_name',
        );
        $joins = array(
            'LEFT JOIN `'._DB_PREFIX_.'dhl_order` do ON (do.`id_dhl_order` = a.`id_dhl_order`)',
            'LEFT JOIN `'._DB_PREFIX_.'orders` o ON (o.`id_order` = do.`id_order`)',
            'LEFT JOIN `'._DB_PREFIX_.'customer` c ON (c.`id_customer` = o.`id_customer`)',
            'LEFT JOIN `'._DB_PREFIX_.'order_state` os ON (os.`id_order_state` = o.`current_state`)',
            'LEFT JOIN `'._DB_PREFIX_.'order_state_lang` osl ON (os.`id_order_state` = osl.`id_order_state`
            AND osl.`id_lang` = '.(int) $this->context->language->id.')',
        );
        $this->_select = implode(', ', $selectFields);
        $this->identifier = 'id_dhl_label';
        $this->table = 'dhl_label';
        $this->_join = implode(' ', $joins);
        $this->_orderBy = 'a.date_add';
        $this->_orderWay = 'desc';
        $this->_where .= ' AND a.id_dhl_label IS NOT NULL AND a.return_label = 0';
        $statuses = OrderState::getOrderStates((int) $this->context->language->id);
        $statusesArray = array();
        foreach ($statuses as $status) {
            $statusesArray[$status['id_order_state']] = $status['name'];
        }
        $this->fields_list = array(
            'reference'   => array(
                'title'          => $this->module->l('Reference', 'AdminDhlManifest'),
                'remove_onclick' => true,
            ),
            'awb_number'  => array(
                'title'          => $this->module->l('AWB Number', 'AdminDhlManifest'),
                'havingFilter'   => true,
                'filter_key'     => 'a!awb_number',
                'filter_type'    => 'string',
                'remove_onclick' => true,
            ),
            'customer'    => array(
                'title'          => $this->module->l('Customer', 'AdminDhlManifest'),
                'havingFilter'   => true,
                'remove_onclick' => true,
            ),
            'status_name' => array(
                'title'          => $this->module->l('Status', 'AdminDhlManifest'),
                'type'           => 'select',
                'color'          => 'color',
                'list'           => $statusesArray,
                'filter_key'     => 'os!id_order_state',
                'filter_type'    => 'int',
                'order_key'      => 'status_name',
                'remove_onclick' => true,
            ),
            'order_date'  => array(
                'title'          => $this->module->l('Date of order', 'AdminDhlManifest'),
                'type'           => 'datetime',
                'filter_key'     => 'o!date_add',
                'remove_onclick' => true,
            ),
            'label_date'  => array(
                'title'          => $this->module->l('Label creation date', 'AdminDhlManifest'),
                'type'           => 'datetime',
                'filter_key'     => 'a!date_add',
                'remove_onclick' => true,
            ),
        );
    }
}
