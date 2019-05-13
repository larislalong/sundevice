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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class JmarketplaceDashboardModuleFrontController extends ModuleFrontController
{
    public $ssl = true;
    
    public function setMedia()
    {
        parent::setMedia();
        
        $this->addJqueryUI('ui.datepicker');
        //$this->context->controller->addJS(_MODULE_DIR_.$this->module->name.'/views/js/Chart.bundle.min.js', 'all');
        $this->context->controller->addJS(_MODULE_DIR_.$this->module->name.'/views/js/dashboard.js', 'all');
    }

    public function initContent()
    {
        parent::initContent();
        
        if (!$this->context->cookie->id_customer) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_DASHBOARD') == 0) {
            Tools::redirect($this->context->link->getModuleLink('jmarketplace', 'selleraccount', array(), true));
        }
        
        $is_seller = Seller::isSeller($this->context->cookie->id_customer, $this->context->shop->id);
        if (!$is_seller) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
        }
        
        $id_seller = Seller::getSellerByCustomer($this->context->cookie->id_customer);
        $seller = new Seller($id_seller);
        
        if ($seller->active == 0) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
        }
        
        $param = array('id_seller' => $seller->id, 'link_rewrite' => $seller->link_rewrite);
        $url_seller_profile = $this->module->getJmarketplaceLink('jmarketplace_seller_rule', $param);
        
        $chart_label = '';
        $chart_data = '';
        $from = explode(' ', $seller->date_add);
        $from = $from[0].' 00:00:00';
        $to = date('Y-m-d').' 23:59:59';
        
        if (Tools::isSubmit('submitFilterDate')) {
            $from = Tools::getValue('from').' 00:00:00';
            $to = Tools::getValue('to').' 23:59:59';
        }
        
        $commissions_chart = array();
        //$current_time = 0;
        $current_date_from_time = strtotime('+0 month', strtotime($from));
        $current_date_from = date('Y-m', $current_date_from_time);
        $commissions_chart[$current_date_from] = 0;
        
        $current_date_to_time = strtotime('+0 month', strtotime($to));
        $current_date_to = date('Y-m', $current_date_to_time);
        
        $i = 1;
        if ($current_date_from != $current_date_to) {
            while ($current_date_from != $current_date_to) {
                $current_date_from_time = strtotime('+'.$i.' month', strtotime($from));
                $current_date_from = date('Y-m', $current_date_from_time);
                $commissions_chart[$current_date_from] = 0;
                $i++;
            }
        }
        
        $ids_not_in_order_states = '';
        
        $states = OrderState::getOrderStates($this->context->language->id);
        foreach ($states as $state) {
            if (Configuration::get('JMARKETPLACE_CANCEL_COMMISSION_'.$state['id_order_state']) == 1) {
                $ids_not_in_order_states .= $state['id_order_state'].',';
            }
        }

        $ids_not_in_order_states = Tools::substr($ids_not_in_order_states, 0, -1);
        
        $sales = 0;
        $num_orders = 0;
        $num_products = SellerOrderDetail::getProductQuantityBySeller($id_seller, $from, $to, $ids_not_in_order_states);
        $cart_value = 0;
        $commission = SellerCommission::getCommissionBySeller($id_seller);
        $benefits = 0;

        $seller_orders = SellerOrder::getSellerOrdersByDate($id_seller, $this->context->language->id, $from, $to, $ids_not_in_order_states);
        
        if (is_array($seller_orders) && count($seller_orders) > 0) {
            foreach ($seller_orders as $key => $o) {
                $currency_from = new Currency($o['id_currency']);
                $seller_orders[$key]['total_paid_tax_incl'] = Tools::convertPriceFull($o['total_paid_tax_incl'], $currency_from, $this->context->currency);
                $seller_orders[$key]['total_paid_tax_excl'] = Tools::convertPriceFull($o['total_paid_tax_excl'], $currency_from, $this->context->currency);
                $seller_orders[$key]['total_paid'] = Tools::convertPriceFull($o['total_paid'], $currency_from, $this->context->currency);
                
                if (Configuration::get('JMARKETPLACE_TAX_COMMISSION') == 1) {
                    $sales = $sales + $seller_orders[$key]['total_paid_tax_incl'];
                    $benefits = $benefits + $seller_orders[$key]['total_paid_tax_incl'];
                } else {
                    $sales = $sales + $seller_orders[$key]['total_paid_tax_excl'];
                    $benefits = $benefits + $seller_orders[$key]['total_paid_tax_excl'];
                }
                
                $num_orders++;
            }
        }
        
        if (Configuration::get('JMARKETPLACE_TAX_COMMISSION') == 1) {
            $field_commission = 'total_paid_tax_incl';
        } else {
            $field_commission = 'total_paid_tax_excl';
        }
        
        if (is_array($seller_orders) && count($seller_orders) > 0) {
            foreach ($seller_orders as $so) {
                $currency_from = new Currency($so['id_currency']);
                $date_add_parts = explode(' ', $so['date_add']);
                $date_add_parts = explode('-', $date_add_parts[0]);
                
                $index = $date_add_parts[0].'-'.$date_add_parts[1];
                
                if (array_key_exists($index, $commissions_chart)) {
                    $commissions_chart[$index] = ($commissions_chart[$index] + $so[$field_commission]);
                } else {
                    $commissions_chart[$index] = $so[$field_commission];
                }
            }
            
            ksort($commissions_chart);
            
            foreach ($commissions_chart as $key => $value) {
                $chart_label .= "'".$key."',";
                $chart_data .= (float)Tools::ps_round($value, 2).',';
            }
            
            $cart_value = $sales / $num_orders;
        }
        
        $from = explode(' ', $from);
        $from = $from[0];
        $to = explode(' ', $to);
        $to = $to[0];

        $orders = SellerOrder::getLastSellerOrders($id_seller, $this->context->language->id);

        if (is_array($orders) && count($orders) > 0) {
            foreach ($orders as $key => $o) {
                $currency_from = new Currency($o['id_currency']);
                $orders[$key]['total_paid_tax_incl'] = Tools::displayPrice(Tools::convertPriceFull($o['total_paid_tax_incl'], $currency_from, $this->context->currency), (int)$this->context->currency->id);
                $orders[$key]['total_paid_tax_excl'] = Tools::displayPrice(Tools::convertPriceFull($o['total_paid_tax_excl'], $currency_from, $this->context->currency), (int)$this->context->currency->id);
                $orders[$key]['total_discounts_tax_incl'] = Tools::displayPrice(Tools::convertPriceFull($o['total_discounts_tax_incl'], $currency_from, $this->context->currency), (int)$this->context->currency->id);
                $orders[$key]['total_discounts_tax_excl'] = Tools::displayPrice(Tools::convertPriceFull($o['total_discounts_tax_excl'], $currency_from, $this->context->currency), (int)$this->context->currency->id);
                $params = array('id_order' => $o['id_order']);
                $orders[$key]['link'] = $this->context->link->getModuleLink('jmarketplace', 'orders', $params, true);
            }
        }
        
        //echo '$chart_label: '.$chart_label.'<br/>';
        //echo '$chart_data: '.$chart_data.'<br/>';

        $this->context->smarty->assign(array(
            'seller_link' => $url_seller_profile,
            'show_import_product' => Configuration::get('JMARKETPLACE_SELLER_IMPORT_PROD'),
            'show_orders' => Configuration::get('JMARKETPLACE_SHOW_ORDERS'),
            'show_contact' => Configuration::get('JMARKETPLACE_SHOW_CONTACT'),
            'show_edit_seller_account' => Configuration::get('JMARKETPLACE_SHOW_EDIT_ACCOUNT'),
            'show_manage_orders' => Configuration::get('JMARKETPLACE_SHOW_MANAGE_ORDERS'),
            'show_manage_carriers' => Configuration::get('JMARKETPLACE_SHOW_MANAGE_CARRIER'),
            'show_dashboard' => Configuration::get('JMARKETPLACE_SHOW_DASHBOARD'),
            'show_seller_invoice' => Configuration::get('JMARKETPLACE_SHOW_SELLER_INVOICE'),
            'show_menu_top' => Configuration::get('JMARKETPLACE_MENU_TOP'),
            'show_menu_options' => Configuration::get('JMARKETPLACE_MENU_OPTIONS'),
            'sales' => Tools::displayPrice(Tools::convertPriceFull($sales, $this->context->currency, $this->context->currency), (int)$this->context->currency->id),
            'num_orders' => $num_orders,
            'cart_value' => Tools::displayPrice(Tools::convertPriceFull($cart_value, $this->context->currency, $this->context->currency), (int)$this->context->currency->id),
            'num_products' => $num_products,
            'commission' => $commission,
            'benefits' => Tools::displayPrice(Tools::convertPriceFull($benefits, $this->context->currency, $this->context->currency), (int)$this->context->currency->id),
            'chart_label' => $chart_label,
            'chart_data' => $chart_data,
            'currency_iso_code' => $this->context->currency->iso_code,
            'from' => $from,
            'to' => $to,
            'tax_commission' => Configuration::get('JMARKETPLACE_TAX_COMMISSION'),
            'orders' => $orders,
            'chart_js' => _MODULE_DIR_.$this->module->name.'/views/js/Chart.bundle.min.js',
        ));
        
        $this->setTemplate('dashboard.tpl');
    }
}
