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

class JmarketplaceSellermessagesModuleFrontController extends ModuleFrontController
{
    public $ssl = true;
    
    public function setMedia()
    {
        parent::setMedia();
        $this->context->controller->addJS(_MODULE_DIR_.$this->module->name.'/views/js/sellermessages.js', 'all');
    }
    
    protected function ajaxProcessReadMessage()
    {
        $id_seller_incidence = (int)Tools::getValue('id_seller_incidence');
        $messages = SellerIncidence::getMessages($id_seller_incidence);
        foreach ($messages as $message) {
            if ($message['id_seller'] == 0) {
                $message = new SellerIncidenceMessage($message['id_seller_incidence_message']);
                $message->readed = 1;
                $message->update();
            }
        }
    }
    
    public function postProcess()
    {
        if (Tools::isSubmit('submitResponse')) {
            if ($_FILES['attachment']['name'] != '') {
                if (!in_array($_FILES['attachment']['type'], $this->module->mime_types)) {
                    $this->errors[] = $this->module->l('File type is incorrect.', 'contact');
                }
                
                $max_bytes_server = (int)(Tools::substr(ini_get('post_max_size'), 0, -1)) * 1024 * 1024;
                
                if ($_FILES['attachment']['size'] >= $max_bytes_server) {
                    $this->errors[] = $this->module->l('The uploaded file is too large. Maxim:', 'contact').' '.ini_get('post_max_size');
                }
            }
            
            if (count($this->errors) > 0) {
                $this->context->smarty->assign(array('errors' => $this->errors));
            } else {
                $id_seller = Seller::getSellerByCustomer($this->context->cookie->id_customer);
                $incidenceMessage = new SellerIncidenceMessage();
                $incidenceMessage->id_seller_incidence = (int)Tools::getValue('id_incidence');
                $incidenceMessage->id_customer = 0;
                $incidenceMessage->id_seller = (int)$id_seller;
                $incidenceMessage->description = (string)Tools::getValue('description'); //this is content html
                
                //file attachment
                if ($_FILES['attachment']['name'] != '') {
                    $incidenceMessage->attachment = $_FILES['attachment']['name'];
                    if (!is_dir(_PS_MODULE_DIR_.$this->module->name.'/attachment/'.$incidenceMessage->id_seller_incidence)) {
                        mkdir(_PS_MODULE_DIR_.$this->module->name.'/attachment/'.$incidenceMessage->id_seller_incidence, 0777);
                    }

                    if (file_exists(_PS_MODULE_DIR_.$this->module->name.'/attachment/'.$incidenceMessage->id_seller_incidence.'/'.$_FILES['attachment']['name'])) {
                        unlink(_PS_MODULE_DIR_.$this->module->name.'/attachment/'.$incidenceMessage->id_seller_incidence.'/'.$_FILES['attachment']['name']);
                    }

                    move_uploaded_file($_FILES['attachment']['tmp_name'], _PS_MODULE_DIR_.$this->module->name.'/attachment/'.$incidenceMessage->id_seller_incidence.'/'.$_FILES['attachment']['name']);
                }
                
                $incidenceMessage->readed = 0;
                $incidenceMessage->add();

                $incidence = new SellerIncidence($incidenceMessage->id_seller_incidence);
                $incidence->date_upd = date('Y-m-d H:i:s');
                $incidence->update();

                $id_seller_email = false;
                $template = 'base';
                $reference = 'new-response-seller';

                $id_seller_email = SellerEmail::getIdByReference($reference);

                if ($id_seller_email) {
                    $id_customer = (int)Tools::getValue('id_customer');
                    $customer = new Customer($id_customer);
                    $to = $customer->email;
                    $to_name = $customer->firstname.' '.$customer->lastname;
                    $from = Configuration::get('PS_SHOP_EMAIL');
                    $from_name = Configuration::get('PS_SHOP_NAME');
                    $seller_email = new SellerEmail($id_seller_email, $customer->id_lang);
                    $vars = array("{shop_name}", "{incidence_reference}", "{description}");
                    $values = array(Configuration::get('PS_SHOP_NAME'), $incidence->reference, nl2br($incidenceMessage->description));
                    $subject_var = $seller_email->subject;
                    $subject_value = str_replace($vars, $values, $subject_var);
                    $content_var = $seller_email->content;
                    $content_value = str_replace($vars, $values, $content_var);

                    $template_vars = array(
                        '{content}' => $content_value,
                        '{shop_name}' => Configuration::get('PS_SHOP_NAME')
                    );

                    $iso = Language::getIsoById($customer->id_lang);

                    if (file_exists(dirname(__FILE__).'/../../mails/'.$iso.'/'.$template.'.txt') && file_exists(dirname(__FILE__).'/../../mails/'.$iso.'/'.$template.'.html')) {
                        Mail::Send(
                            $customer->id_lang,
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

                    //send email (in copy) to administrator when a seller send new message
                    if (Configuration::get('JMARKETPLACE_SEND_ADMIN_INCIDENCE') == 1) {
                        $to = Configuration::get('JMARKETPLACE_SEND_ADMIN');
                        $to_name = Configuration::get('PS_SHOP_NAME');
                        $from = Configuration::get('JMARKETPLACE_SEND_ADMIN');
                        $from_name = Configuration::get('PS_SHOP_NAME');

                        $iso = Language::getIsoById($customer->id_lang);

                        if (file_exists(dirname(__FILE__).'/../../mails/'.$iso.'/'.$template.'.txt') && file_exists(dirname(__FILE__).'/../../mails/'.$iso.'/'.$template.'.html')) {
                            Mail::Send(
                                $customer->id_lang,
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

                Tools::redirect($this->context->link->getModuleLink('jmarketplace', 'sellermessages', array('confirmation' => 1), true));
            }
        }
    }

    public function initContent()
    {
        parent::initContent();
        
        if (Tools::isSubmit('action')) {
            switch (Tools::getValue('action')) {
                case 'read_message':
                    $this->ajaxProcessReadMessage();
                    break;
            }
        }
        
        if (!$this->context->cookie->id_customer) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_CONTACT') == 0) {
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
        
        if (!Configuration::get('JMARKETPLACE_SHOW_CONTACT')) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
        }

        $param = array('id_seller' => $seller->id, 'link_rewrite' => $seller->link_rewrite);
        $url_seller_profile = $this->module->getJmarketplaceLink('jmarketplace_seller_rule', $param);
        
        $incidences = SellerIncidence::getIncidencesBySeller($id_seller);

        if ($incidences != false) {
            $counter = 0;
            foreach ($incidences as $i) {
                $product = new Product((int)$i['id_product'], (int)$this->context->language->id, (int)$this->context->shop->id);
                $incidences[$counter]['product_name'] = $product->name;
                $incidences[$counter]['messages_not_readed'] = SellerIncidence::getNumMessagesNotReaded($i['id_seller_incidence'], $id_seller, false);
                $messages = SellerIncidence::getMessages((int)$i['id_seller_incidence']);
                $incidences[$counter] = array_merge($incidences[$counter], array('messages' => $messages));
                $counter++;
            }
        }
        
        if (Tools::getValue('confirmation')) {
            $this->context->smarty->assign(array('confirmation' => 1));
        }
        
        $this->context->smarty->assign(array(
            'incidences' => $incidences,
            'seller_link' => $url_seller_profile,
            'show_import_product' => Configuration::get('JMARKETPLACE_SELLER_IMPORT_PROD'),
            'show_orders' => Configuration::get('JMARKETPLACE_SHOW_ORDERS'),
            'show_manage_orders' => Configuration::get('JMARKETPLACE_SHOW_MANAGE_ORDERS'),
            'show_manage_carriers' => Configuration::get('JMARKETPLACE_SHOW_MANAGE_CARRIER'),
            'show_edit_seller_account' => Configuration::get('JMARKETPLACE_SHOW_EDIT_ACCOUNT'),
            'show_contact' => Configuration::get('JMARKETPLACE_SHOW_CONTACT'),
            'show_dashboard' => Configuration::get('JMARKETPLACE_SHOW_DASHBOARD'),
            'show_seller_invoice' => Configuration::get('JMARKETPLACE_SHOW_SELLER_INVOICE'),
            'show_menu_top' => Configuration::get('JMARKETPLACE_MENU_TOP'),
            'show_menu_options' => Configuration::get('JMARKETPLACE_MENU_OPTIONS'),
            'token' => Configuration::get('JMARKETPLACE_TOKEN'),
            'PS_REWRITING_SETTINGS' => Configuration::get('PS_REWRITING_SETTINGS'),
        ));
        
        $this->setTemplate('sellermessages.tpl');
    }
}
