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

class JmarketplaceSellerhistorycommissionsModuleFrontController extends ModuleFrontController
{
    public $ssl = true;

    public function initContent()
    {
        parent::initContent();
        
        if (!$this->context->cookie->id_customer) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_ORDERS') == 0) {
            Tools::redirect($this->context->link->getModuleLink('jmarketplace', 'selleraccount', array(), true));
        }

        $id_seller = Seller::getSellerByCustomer($this->context->cookie->id_customer);
        $is_seller = Seller::isSeller($this->context->cookie->id_customer, $this->context->shop->id);
        
        if (!$is_seller) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
        }
        
        $seller = new Seller($id_seller);
        
        if ($seller->active == 0) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
        }
        
        if (!Configuration::get('JMARKETPLACE_SHOW_ORDERS')) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
        }
       
        $orders = SellerCommissionHistory::getCommissionHistoryBySeller($id_seller, $this->context->language->id, $this->context->shop->id);
        if (is_array($orders) && count($orders) > 0) {
            foreach ($orders as $key => $o) {
                $currency_from = new Currency($o['id_currency']);
                $orders[$key]['price_tax_excl'] = Tools::displayPrice(Tools::convertPriceFull($o['price_tax_excl'], $currency_from, $this->context->currency));
                $orders[$key]['price_tax_incl'] = Tools::displayPrice(Tools::convertPriceFull($o['price_tax_incl'], $currency_from, $this->context->currency));
                $orders[$key]['unit_commission_tax_excl'] = Tools::displayPrice(Tools::convertPriceFull($o['unit_commission_tax_excl'], $currency_from, $this->context->currency));
                $orders[$key]['unit_commission_tax_incl'] = Tools::displayPrice(Tools::convertPriceFull($o['unit_commission_tax_incl'], $currency_from, $this->context->currency));
                $orders[$key]['total_commission_tax_excl'] = Tools::displayPrice(Tools::convertPriceFull($o['total_commission_tax_excl'], $currency_from, $this->context->currency));
                $orders[$key]['total_commission_tax_incl'] = Tools::displayPrice(Tools::convertPriceFull($o['total_commission_tax_incl'], $currency_from, $this->context->currency));
            }
        }
        
        $paid_commissions = SellerCommissionHistory::getCommissionsBySellerAndState(2, $id_seller);
        $total_paid_commission_tax_excl = 0;
        $total_paid_commission_tax_incl = 0;
        if (is_array($paid_commissions) && count($paid_commissions) > 0) {
            foreach ($paid_commissions as $pc) {
                $currency_from = new Currency($pc['id_currency']);
                $total_paid_commission_tax_excl = $total_paid_commission_tax_excl + Tools::convertPriceFull($pc['total_commission_tax_excl'], $currency_from, $this->context->currency);
                $total_paid_commission_tax_incl = $total_paid_commission_tax_incl + Tools::convertPriceFull($pc['total_commission_tax_incl'], $currency_from, $this->context->currency);
            }
        }
        
        $outstanding_commissions = SellerCommissionHistory::getCommissionsBySellerAndState(1, $id_seller);
        $total_outstanding_commission_tax_excl = 0;
        $total_outstanding_commission_tax_incl = 0;
        if (is_array($outstanding_commissions) && count($outstanding_commissions) > 0) {
            foreach ($outstanding_commissions as $oc) {
                $currency_from = new Currency($oc['id_currency']);
                $total_outstanding_commission_tax_excl = $total_outstanding_commission_tax_excl + Tools::convertPriceFull($oc['total_commission_tax_excl'], $currency_from, $this->context->currency);
                $total_outstanding_commission_tax_incl = $total_outstanding_commission_tax_incl + Tools::convertPriceFull($oc['total_commission_tax_incl'], $currency_from, $this->context->currency);
            }
        }
        
        $canceled_commissions = SellerCommissionHistory::getCommissionsBySellerAndState(3, $id_seller);
        $total_canceled_commission_tax_excl = 0;
        $total_canceled_commission_tax_incl = 0;
        if (is_array($canceled_commissions) && count($canceled_commissions) > 0) {
            foreach ($canceled_commissions as $cc) {
                $currency_from = new Currency($cc['id_currency']);
                $total_canceled_commission_tax_excl = $total_canceled_commission_tax_excl + Tools::convertPriceFull($cc['total_commission_tax_excl'], $currency_from, $this->context->currency);
                $total_canceled_commission_tax_incl = $total_canceled_commission_tax_incl + Tools::convertPriceFull($cc['total_commission_tax_incl'], $currency_from, $this->context->currency);
            }
        }

        $params = array('id_seller' => $seller->id, 'link_rewrite' => $seller->link_rewrite);
        $url_seller_profile = $this->module->getJmarketplaceLink('jmarketplace_seller_rule', $params);

        $this->context->smarty->assign(array(
            'show_reference' => Configuration::get('JMARKETPLACE_SHOW_REFERENCE'),
            'show_quantity' => Configuration::get('JMARKETPLACE_SHOW_QUANTITY'),
            'show_price' => Configuration::get('JMARKETPLACE_SHOW_PRICE'),
            'show_images' => Configuration::get('JMARKETPLACE_SHOW_IMAGES'),
            'show_import_product' => Configuration::get('JMARKETPLACE_SELLER_IMPORT_PROD'),
            'show_orders' => Configuration::get('JMARKETPLACE_SHOW_ORDERS'),
            'show_manage_orders' => Configuration::get('JMARKETPLACE_SHOW_MANAGE_ORDERS'),
            'show_manage_carriers' => Configuration::get('JMARKETPLACE_SHOW_MANAGE_CARRIER'),
            'show_dashboard' => Configuration::get('JMARKETPLACE_SHOW_DASHBOARD'),
            'show_seller_invoice' => Configuration::get('JMARKETPLACE_SHOW_SELLER_INVOICE'),
            'show_edit_seller_account' => Configuration::get('JMARKETPLACE_SHOW_EDIT_ACCOUNT'),
            'show_contact' => Configuration::get('JMARKETPLACE_SHOW_CONTACT'),
            'show_dashboard' => Configuration::get('JMARKETPLACE_SHOW_DASHBOARD'),
            'show_menu_top' => Configuration::get('JMARKETPLACE_MENU_TOP'),
            'show_menu_options' => Configuration::get('JMARKETPLACE_MENU_OPTIONS'),
            'orders' => $orders,
            'tax_commission' => Configuration::get('JMARKETPLACE_TAX_COMMISSION'),
            'total_paid_commission_tax_excl' => Tools::displayPrice($total_paid_commission_tax_excl, $this->context->currency->id),
            'total_paid_commission_tax_incl' => Tools::displayPrice($total_paid_commission_tax_incl, $this->context->currency->id),
            'total_outstanding_commission_tax_excl' => Tools::displayPrice($total_outstanding_commission_tax_excl, $this->context->currency->id),
            'total_outstanding_commission_tax_incl' => Tools::displayPrice($total_outstanding_commission_tax_incl, $this->context->currency->id),
            'total_canceled_commission_tax_excl' => Tools::displayPrice($total_canceled_commission_tax_excl, $this->context->currency->id),
            'total_canceled_commission_tax_incl' => Tools::displayPrice($total_canceled_commission_tax_incl, $this->context->currency->id),
            'seller_link' => $url_seller_profile,
        ));
        
        $this->setTemplate('sellerorders.tpl');
    }
}
