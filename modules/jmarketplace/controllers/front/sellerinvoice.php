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

class JmarketplaceSellerinvoiceModuleFrontController extends ModuleFrontController
{
    public $ssl = true;
    
    public function setMedia()
    {
        parent::setMedia();
        $this->context->controller->addJS(_MODULE_DIR_.$this->module->name.'/views/js/sellerinvoice.js', 'all');
    }
    
    public function postProcess()
    {
        if (Tools::isSubmit('submitInvoice')) {
            $id_seller = Seller::getSellerByCustomer((int)$this->context->cookie->id_customer);
            $seller = new Seller($id_seller);
            $total_invoice = (float)Tools::getValue('total_invoice');
            $commissions = Tools::getValue('commissions');
            $active_payment = SellerPayment::getActivePaymentsBySeller($id_seller);
            $payment = (string)$active_payment['payment'];
            //$seller_invoice = $_FILES['sellerInvoice'];
            
            $exist_transfer_commission = false;
            if (is_array($commissions) && count($commissions) > 0) {
                foreach ($commissions as $id_seller_commission_history) {
                    if (SellerTransferCommission::isSellerTransferCommission($id_seller_commission_history) > 0) {
                        $exist_transfer_commission = true;
                    }
                }
            }
            
            if ($exist_transfer_commission && $total_invoice > 0) {
                $this->errors[] = Tools::displayError('This request have already sended.', 'sellerinvoice');
            }
            
            if ($total_invoice <= 0) {
                $this->errors[] = Tools::displayError('Total invoice is incorrect. Must be a positive number greater than 0.', 'sellerinvoice');
            }
            
            if ($_FILES['sellerInvoice']['name'] == '' && ($_FILES['sellerInvoice']['type'] != 'application/pdf' || $_FILES['sellerInvoice']['type'] != 'application/x-download')) {
                $this->errors[] = Tools::displayError('The invoice must be in pdf format.', 'sellerinvoice');
            }
            
            if (count($this->errors) > 0) {
                $this->context->smarty->assign(array(
                    'errors' => $this->errors,
                ));
            } else {
                $seller_transfer_invoice = new SellerTransferInvoice();
                $seller_transfer_invoice->id_seller = $id_seller;
                $seller_transfer_invoice->total = $total_invoice;
                $seller_transfer_invoice->payment = $payment;
                $seller_transfer_invoice->validate = 0;
                $seller_transfer_invoice->id_currency = $this->context->currency->id;
                $seller_transfer_invoice->conversion_rate = $this->context->currency->conversion_rate;
                $seller_transfer_invoice->add();
                
                foreach ($commissions as $id_seller_commission_history) {
                    $seller_transfer_commission = new SellerTransferCommission();
                    $seller_transfer_commission->id_seller_transfer_invoice = $seller_transfer_invoice->id;
                    $seller_transfer_commission->id_seller_commission_history = $id_seller_commission_history;
                    $seller_transfer_commission->add();
                }
                
                //save invoice
                if (file_exists(_PS_MODULE_DIR_.'jmarketplace/invoices/'.$seller_transfer_invoice->id.'.pdf')) {
                    unlink(_PS_MODULE_DIR_.'jmarketplace/invoices/'.$seller_transfer_invoice->id.'.pdf');
                }

                move_uploaded_file($_FILES['sellerInvoice']['tmp_name'], _PS_MODULE_DIR_.'jmarketplace/invoices/'.$seller_transfer_invoice->id.'.pdf');
                
                //email to admin
                if (Configuration::get('JMARKETPLACE_SEND_ADMIN_REQUEST')) {
                    $id_seller_email = false;
                    $to = Configuration::get('JMARKETPLACE_SEND_ADMIN');
                    $to_name = Configuration::get('PS_SHOP_NAME');
                    $from = Configuration::get('PS_SHOP_EMAIL');
                    $from_name = Configuration::get('PS_SHOP_NAME');

                    $template = 'base';
                    $reference = 'seller-payment-request';
                    $id_seller_email = SellerEmail::getIdByReference($reference);
                    
                    if ($id_seller_email) {
                        $seller_email = new SellerEmail($id_seller_email, Configuration::get('PS_LANG_DEFAULT'));
                        $vars = array("{shop_name}", "{seller_name}", "{amount}", "{payment}");
                        $values = array(Configuration::get('PS_SHOP_NAME'), $seller->name, Tools::displayPrice($seller_transfer_invoice->total, $this->context->currency->id), $seller_transfer_invoice->payment);
                        $subject_var = $seller_email->subject;
                        $subject_value = str_replace($vars, $values, $subject_var);
                        $content_var = $seller_email->content;
                        $content_value = str_replace($vars, $values, $content_var);

                        $template_vars = array(
                            '{content}' => $content_value,
                            '{shop_name}' => Configuration::get('PS_SHOP_NAME')
                        );

                        $iso = Language::getIsoById(Configuration::get('PS_LANG_DEFAULT'));

                        if (file_exists(dirname(__FILE__).'/../../mails/'.$iso.'/'.$template.'.txt') && file_exists(dirname(__FILE__).'/../../mails/'.$iso.'/'.$template.'.html')) {
                            Mail::Send(
                                Configuration::get('PS_LANG_DEFAULT'),
                                $template,
                                $subject_value,
                                $template_vars,
                                $to,
                                $to_name,
                                $from,
                                $from_name,
                                null,
                                null,
                                dirname(__FILE__).'/../../mails/'
                            );
                        }
                    }
                }

                Tools::redirect($this->context->link->getModuleLink('jmarketplace', 'sellerinvoice', array('confirmation' => 1), true));
            }
        }
    }

    public function initContent()
    {
        parent::initContent();

        if (!$this->context->cookie->id_customer) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_SELLER_INVOICE') == 0) {
            Tools::redirect($this->context->link->getModuleLink('jmarketplace', 'selleraccount', array(), true));
        }

        $id_seller = Seller::getSellerByCustomer((int)$this->context->cookie->id_customer);
        $is_seller = Seller::isSeller((int)$this->context->cookie->id_customer, (int)$this->context->shop->id);
        
        if (!$is_seller) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
        }

        $seller = new Seller($id_seller);
        
        $orders = SellerTransferCommission::getCommissionHistoryBySeller($id_seller, (int)$this->context->language->id, (int)$this->context->shop->id);
        $transfer_commissions = SellerTransferCommission::getTransferCommissionsBySeller($id_seller);
        
        if (is_array($orders) && count($orders) > 0) {
            foreach ($orders as $key => $o) {
                if (is_array($transfer_commissions) && count($transfer_commissions) > 0) {
                    foreach ($transfer_commissions as $tc) {
                        if ($tc['id_seller_commission_history'] == $o['id_seller_commission_history']) {
                            unset($orders[$key]);
                        }
                    }
                }
            }
            
            if (is_array($orders) && count($orders) > 0) {
                foreach ($orders as $key => $o) {
                    $currency_from = new Currency($o['id_currency']);
                    if (Configuration::get('JMARKETPLACE_TAX_COMMISSION') == 1) {
                        $orders[$key]['price'] = Tools::displayPrice(Tools::convertPriceFull($o['price_tax_incl'], $currency_from, $this->context->currency), $this->context->currency->id);
                        $orders[$key]['total_commission'] = Tools::displayPrice(Tools::convertPriceFull($o['total_commission_tax_incl'], $currency_from, $this->context->currency), $this->context->currency->id);
                        $orders[$key]['commission'] =  Tools::convertPriceFull($o['total_commission_tax_incl'], $currency_from, $this->context->currency);
                    } else {
                        $orders[$key]['price'] = Tools::displayPrice(Tools::convertPriceFull($o['price_tax_excl'], $currency_from, $this->context->currency), $this->context->currency->id);
                        $orders[$key]['total_commission'] = Tools::displayPrice(Tools::convertPriceFull($o['total_commission_tax_excl'], $currency_from, $this->context->currency), $this->context->currency->id);
                        $orders[$key]['commission'] = Tools::convertPriceFull($o['total_commission_tax_excl'], $currency_from, $this->context->currency);
                    }
                }
            }
        }
        
        $params = array('id_seller' => $seller->id, 'link_rewrite' => $seller->link_rewrite);
        $url_seller_profile = $this->module->getJmarketplaceLink('jmarketplace_seller_rule', $params);
        
        $active_payment = SellerPayment::getActivePaymentsBySeller($id_seller);

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
            'initial_price' => Tools::displayPrice(0, $this->context->currency->id),
            'orders' => $orders,
            'benefits' => SellerCommissionHistory::getCommissionsBySellerAndState(1, $id_seller),
            'sign' => $this->context->currency->sign,
            'shop_name' => Configuration::get('PS_SHOP_NAME'),
            'shop_details' => Configuration::get('PS_SHOP_DETAILS'),
            'shop_address' => Configuration::get('PS_SHOP_ADDR1'),
            'shop_code' => Configuration::get('PS_SHOP_CODE'),
            'shop_city' => Configuration::get('PS_SHOP_CITY'),
            'shop_country' => Country::getNameById($this->context->language->id, Configuration::get('PS_SHOP_COUNTRY_ID')),
            'shop_state' => State::getNameById(Configuration::get('PS_SHOP_STATE_ID')),
            'active_payment' => $active_payment['payment']
        ));
        
        if (Tools::getValue('confirmation')) {
            $this->context->smarty->assign(array('confirmation' => 1));
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_SELLER_INVOICE') == 1) {
            $total_funds = 0;
            $orders = SellerTransferCommission::getCommissionHistoryBySeller($id_seller, (int)$this->context->language->id, (int)$this->context->shop->id);
            if (is_array($orders) && count($orders) > 0) {
                foreach ($orders as $o) {
                    $currency_from = new Currency($o['id_currency']);
                    if (SellerTransferCommission::isSellerTransferCommission($o['id_seller_commission_history']) == 0) {
                        if (Configuration::get('JMARKETPLACE_TAX_COMMISSION') == 1) {
                            $total_funds = $total_funds + Tools::convertPriceFull($o['total_commission_tax_incl'], $currency_from, $this->context->currency);
                        } else {
                            $total_funds = $total_funds + Tools::convertPriceFull($o['total_commission_tax_excl'], $currency_from, $this->context->currency);
                        }
                    }
                }
            }

            $this->context->smarty->assign('total_funds', Tools::displayPrice($total_funds, $this->context->currency->id));
        }
        
        $this->setTemplate('sellerinvoice.tpl');
    }
}
