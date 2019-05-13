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
 * Class AdminDhlBulkLabelController
 */
class AdminDhlBulkLabelController extends ModuleAdminController
{
    /** @var DhlLogger $logger */
    private $logger;

    /** @var int $idLang */
    private $idLang;

    /**
     * AdminDhlBulkLabelController constructor.
     * @throws Exception
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws SmartyException
     */
    public function __construct()
    {
        require_once(dirname(__FILE__).'/../../api/loader.php');
        require_once(dirname(__FILE__).'/../../classes/DhlTools.php');
        require_once(dirname(__FILE__).'/../../classes/DhlLabel.php');
        require_once(dirname(__FILE__).'/../../classes/DhlOrder.php');
        require_once(dirname(__FILE__).'/../../classes/DhlService.php');
        require_once(dirname(__FILE__).'/../../classes/DhlPackage.php');
        require_once(dirname(__FILE__).'/../../classes/logger/loader.php');

        $this->bootstrap = true;
        $this->context = Context::getContext();
        parent::__construct();
        $this->idLang = $this->context->language->id;
        if (Configuration::get('DHL_ENABLE_LOG')) {
            $version = str_replace('.', '_', $this->module->version);
            $hash = Tools::encrypt(_PS_MODULE_DIR_.$this->module->name.'/logs/');
            $file = dirname(__FILE__).'/../../logs/dhlexpress_'.$hash.'.log';
            $this->logger = new DhlLogger('DHL_'.$version.'_Label', new DhlFileHandler($file));
        } else {
            $this->logger = new DhlLogger('', new DhlNullHandler());
        }
        $this->context->smarty->assign(
            array(
                'dhl_img_path' => $this->module->getPathUri().'views/img/',
            )
        );
        $header = $this->createTemplate('../_partials/dhl-header.tpl')->fetch();
        $this->content .= $header;

        if (Tools::isSubmit('submitBulkDhlLabeldhl_order')) {
            $dhlOrders = Tools::getValue('dhl_orderBox');
            if (!$dhlOrders) {
                $this->context->controller->errors[] =
                    $this->module->l('Please select at leat one shipment.', 'AdminDhlBulkLabel');
                $this->getDHLOrderList();
            } else {
                $packageTypes = DhlPackage::getPackageList();
                $defaultPackageType = (int) Configuration::get('DHL_DEFAULT_PACKAGE_TYPE');
                $weightUnit = DhlTools::getWeightUnit();
                $dimensionUnit = DhlTools::getDimensionUnit();
                $services = DhlService::getServicesByZone((int) $this->idLang);
                $shopCurrency = new Currency((int) Configuration::get('PS_CURRENCY_DEFAULT'));
                $this->context->smarty->assign(
                    array(
                        'bulk_label_step'      => 2,
                        'package_types'        => $packageTypes,
                        'default_package_type' => $defaultPackageType,
                        'weight_unit'          => $weightUnit,
                        'dimension_unit'       => $dimensionUnit,
                        'shipment_contents'    => Configuration::get('DHL_DEFAULT_SHIPMENT_CONTENT'),
                        'iso_currency'         => $shopCurrency->iso_code,
                        'dhl_services'         => $services,
                        'dhl_orders'           => $this->getOrdersDetail($dhlOrders),
                        'link'                 => $this->context->link,
                    )
                );
                $steps = $this->createTemplate('dhl-bulk-label-steps.tpl')->fetch();
                $resume = $this->createTemplate('dhl-orders-selection.tpl')->fetch();
                $prestUI = $this->getPrestUI();
                $this->content .= $steps.$resume.$prestUI;
            }
        } elseif (Tools::isSubmit('submitBulkLabelDownload')) {
            $this->downloadBulkLabelZip();
        } else {
            $this->context->smarty->assign('bulk_label_step', 1);
            $this->getDHLOrderList();
        }
    }

    /**
     * @return bool|false|string
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function renderList()
    {
        if (!($this->fields_list && is_array($this->fields_list))) {
            return false;
        }
        $this->getList($this->idLang);

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

        return $list;
    }

    /**
     *
     */
    public function initToolbar()
    {
        parent::initToolbar();
        unset($this->toolbar_btn['new']);
    }

    /**
     * @param bool $isNewTheme
     */
    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);
        $this->context->controller->addJS($this->module->getLocalPath().'views/js/admin.bulklabel.js');
    }

    /**
     * @return string
     * @throws Exception
     * @throws SmartyException
     */
    public function getPrestUI()
    {
        $this->context->controller->addJS($this->module->getLocalPath().'views/js/riot+compiler.min.js');
        $this->context->smarty->assign('ps_version', _PS_VERSION_);
        $prestUI = $this->context->smarty->fetch(
            $this->module->getLocalPath().'views/templates/admin/prestui/ps-alert.tpl'
        );
        $prestUI .= $this->context->smarty->fetch(
            $this->module->getLocalPath().'views/templates/admin/prestui/ps-form.tpl'
        );
        $prestUI .= $this->context->smarty->fetch(
            $this->module->getLocalPath().'views/templates/admin/prestui/ps-panel.tpl'
        );
        $prestUI .= $this->context->smarty->fetch(
            $this->module->getLocalPath().'views/templates/admin/prestui/ps-table.tpl'
        );
        $prestUI .= $this->context->smarty->fetch(
            $this->module->getLocalPath().'views/templates/admin/prestui/ps-tabs.tpl'
        );
        $prestUI .= $this->context->smarty->fetch(
            $this->module->getLocalPath().'views/templates/admin/prestui/ps-tags.tpl'
        );

        return $prestUI;
    }

    /**
     * @param int $idAddressDelivery
     * @return string
     */
    public function getDestinationCountry($idAddressDelivery)
    {
        $address = new Address((int) $idAddressDelivery);
        $country = new Country((int) $address->id_country, (int) $this->idLang);

        return $country->name;
    }

    /**
     * @param array $orderDetails
     * @return int
     */
    public function getProductCount($orderDetails)
    {
        $i = 0;
        foreach ($orderDetails as $orderDetail) {
            $i += $orderDetail['product_quantity'];
        }

        return $i;
    }

    /**
     * @param array $dhlOrderIds
     * @return array
     */
    public function getOrdersDetail($dhlOrderIds)
    {
        $ordersDetail = array();
        foreach ($dhlOrderIds as $dhlOrderId) {
            $dhlOrder = new DhlOrder((int) $dhlOrderId);
            $order = new Order((int) $dhlOrder->id_order);
            $currency = new Currency((int) $order->id_currency);
            $weightUnit = Configuration::get('PS_WEIGHT_UNIT', null, null, $order->id_shop);
            $dhlService = new DhlService($dhlOrder->id_dhl_service, $this->idLang);
            $ordersDetail[(int) $dhlOrderId] = array(
                'id_order'      => $order->id,
                'reference'     => $order->reference,
                'weight'        => $order->getTotalWeight().' '.Tools::strtoupper($weightUnit),
                'product_count' => $this->getProductCount($order->getOrderDetailList()),
                'total_product' => $currency->iso_code.' '.number_format($order->total_products, 2),
                'destination'   => $this->getDestinationCountry($order->id_address_delivery),
                'dhl_service'   => $dhlService->name,
            );
        }

        return $ordersDetail;
    }

    /**
     * @throws PrestaShopDatabaseException
     */
    public function getDHLOrderList()
    {
        $selectFields = array(
            'o.date_add',
            'o.reference',
            'CONCAT(LEFT(c.`firstname`, 1), ". ", c.`lastname`) AS customer',
            'os.color',
            'osl.name AS status_name',
            'dsl.name AS service_name',
            'cl.name AS country',
            'a.id_dhl_order AS id_dhl_label',
            'IF((SELECT COUNT(*) FROM '.
            _DB_PREFIX_.
            'dhl_label dl WHERE dl.id_dhl_order=a.id_dhl_order), 1, 0) AS has_label',
        );
        $joins = array(
            'LEFT JOIN `'._DB_PREFIX_.'orders` o ON (o.`id_order` = a.`id_order`)',
            'LEFT JOIN `'._DB_PREFIX_.'address` ad ON ad.id_address=o.id_address_delivery',
            'LEFT JOIN `'.
            _DB_PREFIX_.
            'country_lang` cl ON (cl.`id_country`=ad.`id_country` AND cl.`id_lang`= '.
            (int) $this->idLang.
            ')',
            'LEFT JOIN `'._DB_PREFIX_.'customer` c ON (c.`id_customer` = o.`id_customer`)',
            'LEFT JOIN `'._DB_PREFIX_.'order_state` os ON (os.`id_order_state` = o.`current_state`)',
            'LEFT JOIN `'._DB_PREFIX_.'order_state_lang` osl ON (os.`id_order_state` = osl.`id_order_state`
            AND osl.`id_lang` = '.(int) $this->idLang.')',
            'LEFT JOIN `'._DB_PREFIX_.'carrier` ca ON (ca.`id_carrier` = o.`id_carrier`)',
            'LEFT JOIN `'._DB_PREFIX_.'carrier_lang` cal ON (cal.`id_carrier` = ca.`id_carrier`
            AND cal.`id_lang` = '.(int) $this->idLang.' 
            AND cal.`id_shop` = '.(int) $this->context->shop->id.')',
            'LEFT JOIN `'._DB_PREFIX_.'dhl_service` ds ON (ds.`id_dhl_service` = a.`id_dhl_service`)',
            'LEFT JOIN `'._DB_PREFIX_.'dhl_service_lang` dsl ON (dsl.`id_dhl_service` = ds.`id_dhl_service`
            AND dsl.`id_lang` = '.(int) $this->idLang.')',
        );
        $this->_select = implode(', ', $selectFields);
        $this->identifier = 'id_dhl_order';
        $this->table = 'dhl_order';
        $this->_join = implode(' ', $joins);
        $this->_orderBy = 'o.date_add';
        $this->_orderWay = 'desc';
        $this->bulk_actions = true;
        $this->toolbar_title = $this->module->l('DHL Bulk Label - Order selection', 'AdminDhlBulkLabel');
        $statuses = OrderState::getOrderStates((int) $this->idLang);
        $statusesArray = array();
        foreach ($statuses as $status) {
            $statusesArray[$status['id_order_state']] = $status['name'];
        }
        $this->fields_list = array(
            'reference'    => array(
                'title'          => $this->module->l('Reference', 'AdminDhlOrders'),
                'remove_onclick' => true,
            ),
            'customer'     => array(
                'title'          => $this->module->l('Customer', 'AdminDhlOrders'),
                'havingFilter'   => true,
                'remove_onclick' => true,
            ),
            'status_name'  => array(
                'title'          => $this->module->l('Status', 'AdminDhlOrders'),
                'type'           => 'select',
                'color'          => 'color',
                'list'           => $statusesArray,
                'filter_key'     => 'os!id_order_state',
                'filter_type'    => 'int',
                'order_key'      => 'status_name',
                'remove_onclick' => true,
            ),
            'date_add'     => array(
                'title'          => $this->module->l('Date', 'AdminDhlOrders'),
                'type'           => 'datetime',
                'filter_key'     => 'o!date_add',
                'remove_onclick' => true,
            ),
            'country'      => array(
                'title'          => $this->module->l('Country', 'AdminDhlBulkLabel'),
                'havingFilter'   => true,
                'remove_onclick' => true,
            ),
            'service_name' => array(
                'title'          => $this->module->l('DHL Service', 'AdminDhlOrders'),
                'type'           => 'select',
                'list'           => DhlService::getServicesFilters($this->idLang),
                'filter_key'     => 'dsl!name',
                'filter_type'    => 'string',
                'remove_onclick' => true,
            ),
            'has_label'    => array(
                'title'          => $this->module->l('Has label ?', 'AdminDhlBulkLabel'),
                'type'           => 'bool',
                'class'          => 'text-center',
                'tmpTableFilter' => true,
                'remove_onclick' => true,
            ),
        );
    }

    /**
     * @return bool
     */
    public function downloadBulkLabelZip()
    {
        $dhlLabelsIds = Tools::getValue('dhl_labels_zip');
        $destination = sys_get_temp_dir();
        if ($destination && Tools::substr($destination, -1) != DIRECTORY_SEPARATOR) {
            $destination .= DIRECTORY_SEPARATOR;
        }
        $destination .= 'dhl_labels_'.date('Ymd_His').'.zip';
        $validFiles = array();
        if (is_array($dhlLabelsIds)) {
            foreach ($dhlLabelsIds as $id) {
                $dhlLabel = new DhlLabel((int) $id);
                if (Validate::isLoadedObject($dhlLabel)) {
                    $validFiles[$dhlLabel->awb_number.'.'.$dhlLabel->label_format] = $dhlLabel->label_string;
                }
            }
        }
        if (count($validFiles)) {
            $zip = new ZipArchive();
            if ($zip->open($destination, ZIPARCHIVE::CREATE) !== true) {
                $this->context->controller->errors[] = 'Cant open file';

                return false;
            }
            foreach ($validFiles as $filename => $file) {
                $zip->addFromString($filename, base64_decode($file));
            }
            $zip->close();
            header('Content-Type: application/zip');
            header('Content-Length: '.filesize($destination));
            header('Content-Disposition: attachment; filename="dhl_labels_'.date('Ymd_His').'.zip"');
            readfile($destination);
            unlink($destination);

            return true;
        } else {
            $this->context->controller->errors[] = 'No files.';

            return false;
        }
    }
}
