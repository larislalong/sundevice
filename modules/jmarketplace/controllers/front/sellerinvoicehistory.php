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

class JmarketplaceSellerinvoicehistoryModuleFrontController extends ModuleFrontController
{
    public $ssl = true;

    public function initContent()
    {
        parent::initContent();
        
        if (!$this->context->cookie->id_customer) {
            Tools::redirect($this->context->link->getPageLink('index', true));
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_SELLER_INVOICE') == 0) {
            Tools::redirect($this->context->link->getModuleLink('jmarketplace', 'selleraccount', array(), true));
        }

        $id_seller = Seller::getSellerByCustomer((int)$this->context->cookie->id_customer);
        $is_seller = Seller::isSeller((int)$this->context->cookie->id_customer, (int)$this->context->shop->id);
        $seller = new Seller($id_seller);
        
        if (!$is_seller) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
        }
        
        $transfer_funds = SellerTransferInvoice::getTransferFunsHistoryBySeller($id_seller);
        
        if (Configuration::get('PS_SSL_ENABLED') == 1) {
            $url_shop = Tools::getShopDomainSsl(true).__PS_BASE_URI__;
        } else {
            $url_shop = Tools::getShopDomain(true).__PS_BASE_URI__;
        }
        
        if (is_array($transfer_funds) && count($transfer_funds) > 0) {
            foreach ($transfer_funds as $key => $tf) {
                $transfer_funds[$key]['total'] = Tools::displayPrice($tf['total'], $this->context->currency->id);
                $transfer_funds[$key]['invoice'] = $url_shop.'modules/'.$this->module->name.'/invoices/'.$tf['id_seller_transfer_invoice'].'.pdf';
            }
        }
        
        $param = array('id_seller' => $seller->id, 'link_rewrite' => $seller->link_rewrite);
        $url_seller_profile = $this->module->getJmarketplaceLink('jmarketplace_seller_rule', $param);

        $this->context->smarty->assign(array(
            'show_import_product' => Configuration::get('JMARKETPLACE_SELLER_IMPORT_PROD'),
            'show_orders' => Configuration::get('JMARKETPLACE_SHOW_ORDERS'),
            'show_manage_orders' => Configuration::get('JMARKETPLACE_SHOW_MANAGE_ORDERS'),
            'show_manage_carriers' => Configuration::get('JMARKETPLACE_SHOW_MANAGE_CARRIER'),
            'show_dashboard' => Configuration::get('JMARKETPLACE_SHOW_DASHBOARD'),
            'show_edit_seller_account' => Configuration::get('JMARKETPLACE_SHOW_EDIT_ACCOUNT'),
            'show_contact' => Configuration::get('JMARKETPLACE_SHOW_CONTACT'),
            'show_dashboard' => Configuration::get('JMARKETPLACE_SHOW_DASHBOARD'),
            'show_seller_invoice' => Configuration::get('JMARKETPLACE_SHOW_SELLER_INVOICE'),
            'show_menu_top' => Configuration::get('JMARKETPLACE_MENU_TOP'),
            'show_menu_options' => Configuration::get('JMARKETPLACE_MENU_OPTIONS'),
            'seller_link' => $url_seller_profile,
            'transfer_funds' => $transfer_funds,
            'validate_total' => SellerTransferInvoice::getValidatePaymentBySeller($id_seller, 1),
            'no_validate_total' => SellerTransferInvoice::getValidatePaymentBySeller($id_seller, 0),
        ));

        $this->setTemplate('sellerinvoicehistory.tpl');
    }
}
