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

class JmarketplaceSellerpaymentModuleFrontController extends ModuleFrontController
{
    public $ssl = true;
    
    public function setMedia()
    {
        parent::setMedia();
        $this->context->controller->addJS(_MODULE_DIR_.$this->module->name.'/views/js/sellerpayment.js');
    }
    
    public function postProcess()
    {
        if (Tools::isSubmit('submitPayment')) {
            $id_seller = Seller::getSellerByCustomer($this->context->cookie->id_customer);
            $paypal = pSQL(Tools::getValue('paypal'));
            $bankwire = (string)Tools::getValue('bankwire'); //this is content html
            
            if (Tools::getValue('active_paypal') == 1) {
                if (!Validate::isEmail($paypal)) {
                    $this->errors[] = $this->module->l('The email of paypal is incorrect.', 'sellerpayment');
                }
                
                if (count($this->errors) > 0) {
                    $this->context->smarty->assign(array('errors' => $this->errors));
                } else {
                    $id_seller_payment = SellerPayment::getIdByPayment((int)$id_seller, 'paypal');
                    $sellerPayment = new SellerPayment((int)$id_seller_payment);
                    $sellerPayment->account = $paypal;
                    $sellerPayment->active = 1;
                    $sellerPayment->update();
                    
                    $id_seller_payment = SellerPayment::getIdByPayment((int)$id_seller, 'bankwire');
                    $sellerPayment = new SellerPayment((int)$id_seller_payment);
                    $sellerPayment->account = $bankwire;
                    $sellerPayment->active = 0;
                    $sellerPayment->update();
                    
                    Tools::redirect($this->context->link->getModuleLink('jmarketplace', 'sellerpayment', array('confirmation' => 1), true));
                }
            } else {
                if ($bankwire == '') {
                    $this->errors[] = $this->module->l('The bankwire account is incorrect.', 'sellerpayment');
                }
                
                if (count($this->errors) > 0) {
                    $this->context->smarty->assign(array('errors' => $this->errors));
                } else {
                    $id_seller_payment = SellerPayment::getIdByPayment((int)$id_seller, 'bankwire');
                    $sellerPayment = new SellerPayment((int)$id_seller_payment);
                    $sellerPayment->account = $bankwire;
                    $sellerPayment->active = 1;
                    $sellerPayment->update();
                    
                    $id_seller_payment = SellerPayment::getIdByPayment((int)$id_seller, 'paypal');
                    $sellerPayment = new SellerPayment((int)$id_seller_payment);
                    $sellerPayment->account = $paypal;
                    $sellerPayment->active = 0;
                    $sellerPayment->update();
                    
                    Tools::redirect($this->context->link->getModuleLink('jmarketplace', 'sellerpayment', array('confirmation' => 1), true));
                }
            }
        }
        
        if (Tools::getValue('confirmation')) {
            $this->context->smarty->assign(array('confirmation' => 1));
        }
    }

    public function initContent()
    {
        parent::initContent();
        
        if (!$this->context->cookie->id_customer) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
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
            'show_seller_invoice' => Configuration::get('JMARKETPLACE_SHOW_SELLER_INVOICE'),
            'show_menu_top' => Configuration::get('JMARKETPLACE_MENU_TOP'),
            'show_menu_options' => Configuration::get('JMARKETPLACE_MENU_OPTIONS'),
            'payments' => SellerPayment::getPaymentsBySeller($id_seller),
            'seller_link' => $url_seller_profile,
            'show_paypal' => Configuration::get('JMARKETPLACE_PAYPAL'),
            'show_bankwire' => Configuration::get('JMARKETPLACE_BANKWIRE'),
        ));
        
        $this->setTemplate('sellerpayment.tpl');
    }
}
