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
 * Class AdminDhlOrdersController
 */
class AdminDhlOrdersController extends ModuleAdminController
{
    /**
     * AdminDhlOrdersController constructor.
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function __construct()
    {
        require_once(dirname(__FILE__).'/../../api/loader.php');
        require_once(dirname(__FILE__).'/../../classes/DhlService.php');
        require_once(dirname(__FILE__).'/../../classes/DhlLabel.php');
        require_once(dirname(__FILE__).'/../../classes/DhlOrder.php');
        require_once(dirname(__FILE__).'/../../classes/DhlCommercialInvoice.php');
        require_once(dirname(__FILE__).'/../../classes/DhlTools.php');
        require_once(dirname(__FILE__).'/../../classes/DhlOrderCarrier.php');
        require_once(dirname(__FILE__).'/../../classes/DhlLink.php');

        $this->bootstrap = true;
        $this->context = Context::getContext();
        parent::__construct();
        $this->getDHLOrderList();
        $action = Tools::getValue('action');
        /** @var Dhlexpress $module */
        $module = $this->module;
        if ('updatealltracking' == $action) {
            // Request limits to 10 the number of AWB Number we can track in one request.
            // So we call track request n times, in groups of 9 AWB Number.
            $labelPacks = DhlTools::getOrdersToTrack();
            foreach ($labelPacks as $labelsToTrack) {
                $module->updateShipmentTracking($labelsToTrack);
            }
        }
    }

    /**
     * @return string
     * @throws Exception
     * @throws SmartyException
     */
    public function renderList()
    {
        $dhlLink = new DhlLink();
        $secureKey = md5(_COOKIE_KEY_.Configuration::get('PS_SHOP_NAME'));
        $trackingUrl = $dhlLink->getBaseLink().'index.php?fc=module&module='.$this->module->name;
        $trackingUrl .= '&controller=crontracking&secure_key='.$secureKey;
        $updateTrackingUrl = $this->context->link->getAdminLink('AdminDhlOrders', true).'&action=updatealltracking';
        $this->context->smarty->assign(
            array(
                'dhl_img_path'        => $this->module->getPathUri().'views/img/',
                'dhl_tracking_url'    => $trackingUrl,
                'dhl_update_tracking' => $updateTrackingUrl,
            )
        );
        $header = $this->createTemplate('../_partials/dhl-header.tpl')->fetch();
        $tracking = $this->createTemplate('_partials/dhl-tracking.tpl')->fetch();
        $list = parent::renderList();

        return $header.$tracking.$list;
    }

    /**
     * @param bool $isNewTheme
     */
    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);
        $this->addCSS($this->module->getLocalPath().'views/css/admin.shipmentdetails.css');
    }

    /**
     * @throws PrestaShopDatabaseException
     */
    public function getDHLOrderList()
    {
        $selectFields = array(
            'a.id_order',
            'o.date_add',
            'o.reference',
            'CONCAT(LEFT(c.`firstname`, 1), ". ", c.`lastname`) AS customer',
            'os.color',
            'osl.name AS status_name',
            'dsl.name AS service_name',
            'a.id_dhl_order AS id_dhl_label',
        );
        $joins = array(
            'LEFT JOIN `'._DB_PREFIX_.'orders` o ON (o.`id_order` = a.`id_order`)',
            'LEFT JOIN `'._DB_PREFIX_.'customer` c ON (c.`id_customer` = o.`id_customer`)',
            'LEFT JOIN `'._DB_PREFIX_.'order_state` os ON (os.`id_order_state` = o.`current_state`)',
            'LEFT JOIN `'._DB_PREFIX_.'order_state_lang` osl ON (os.`id_order_state` = osl.`id_order_state`
            AND osl.`id_lang` = '.(int) $this->context->language->id.')',
            'LEFT JOIN `'._DB_PREFIX_.'carrier` ca ON (ca.`id_carrier` = o.`id_carrier`)',
            'LEFT JOIN `'._DB_PREFIX_.'carrier_lang` cal ON (cal.`id_carrier` = ca.`id_carrier`
            AND cal.`id_lang` = '.(int) $this->context->language->id.' 
            AND cal.`id_shop` = '.(int) $this->context->shop->id.')',
            'LEFT JOIN `'._DB_PREFIX_.'dhl_service` ds ON (ds.`id_dhl_service` = a.`id_dhl_service`)',
            'LEFT JOIN `'._DB_PREFIX_.'dhl_service_lang` dsl ON (dsl.`id_dhl_service` = ds.`id_dhl_service`
            AND dsl.`id_lang` = '.(int) $this->context->language->id.')',
        );
        $this->_select = implode(', ', $selectFields);
        $this->table = 'dhl_order';
        $this->_join = implode(' ', $joins);
        $this->_orderBy = 'o.date_add';
        $this->_orderWay = 'desc';
        $this->addRowAction('');
        $statuses = OrderState::getOrderStates((int) $this->context->language->id);
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
            'service_name' => array(
                'title'          => $this->module->l('DHL Service', 'AdminDhlOrders'),
                'type'           => 'select',
                'list'           => DhlService::getServicesFilters($this->context->language->id),
                'filter_key'     => 'dsl!name',
                'filter_type'    => 'string',
                'remove_onclick' => true,
            ),
            'id_dhl_label' => array(
                'title'          => $this->module->l('Details', 'AdminDhlOrders'),
                'align'          => 'text-center',
                'callback'       => 'printLabelIcons',
                'orderby'        => false,
                'search'         => false,
                'remove_onclick' => true,
                'class'          => 'fixed-width-xs',
            ),
        );
    }

    /**
     *
     * @param int $idDhlOrder
     * @return string
     * @throws Exception
     * @throws SmartyException
     */
    public function printLabelIcons($idDhlOrder)
    {
        require_once(dirname(__FILE__).'/../../classes/DhlOrder.php');
        require_once(dirname(__FILE__).'/../../classes/DhlLabel.php');

        $dhlOrder = new DhlOrder((int) $idDhlOrder);
        if (Validate::isLoadedObject($dhlOrder)) {
            $this->context->smarty->assign(
                array(
                    'id_dhl_order' => $idDhlOrder,
                    'id_order'     => $dhlOrder->id_order,
                )
            );

            return $this->createTemplate('_partials/_dhl_label_buttons.tpl')->fetch();
        } else {
            return '';
        }
    }

    /**
     * @throws Exception
     * @throws SmartyException
     */
    public function ajaxProcessExpandDhlOrder()
    {
        require_once(dirname(__FILE__).'/../../classes/DhlTracking.php');

        $idDhlOrder = Tools::getValue('id_dhl_order');
        $dhlOrder = new DhlOrder((int) $idDhlOrder);
        if (!Validate::isLoadedObject($dhlOrder)) {
            $this->ajaxDie(
                Tools::jsonEncode(
                    array(
                        'errors'  => true,
                        'message' => $this->module->l('Cannot retrieve shipment details.', 'AdminDhlOrders'),
                    )
                )
            );
        }
        /** @var Dhlexpress $module */
        $module = $this->module;
        $html = $module->getDhlShipmentDetails($dhlOrder);
        $this->ajaxDie(
            Tools::jsonEncode(
                array(
                    'errors' => false,
                    'html'   => $html,
                )
            )
        );
    }

    /**
     * @throws Exception
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws SmartyException
     */
    public function ajaxProcessUpdateTrackingStatus()
    {
        $idDhlOrder = (int) Tools::getValue('id_dhl_order');
        $dhlOrder = new DhlOrder((int) $idDhlOrder);
        if (!Validate::isLoadedObject($dhlOrder)) {
            $this->ajaxDie(
                Tools::jsonEncode(
                    array(
                        'errors'  => true,
                        'message' => $this->module->l('Cannot update tracking status.', 'AdminDhlOrders'),
                    )
                )
            );
        }
        /** @var Dhlexpress $module */
        $module = $this->module;
        $labelIds = $dhlOrder->getLabelIds();
        $ordersToTrack = array();
        if ($labelIds) {
            $labelsToTrack = array();
            foreach ($labelIds as $label) {
                $dhlLabel = new DhlLabel((int) $label['id_dhl_label']);
                $labelsToTrack[$dhlLabel->awb_number] = $dhlLabel->id;
            }
            $ordersToTrack = array(
                $dhlOrder->id_order => $labelsToTrack,
            );
        }
        $message = $module->updateShipmentTracking($ordersToTrack);
        $html = $module->getDhlShipmentDetailsTable($dhlOrder);
        $this->ajaxDie(
            Tools::jsonEncode(
                array(
                    'errors'  => false,
                    'message' => $message,
                    'html'    => $html,
                )
            )
        );
    }
}
