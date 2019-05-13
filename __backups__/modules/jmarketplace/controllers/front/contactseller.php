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

class JmarketplaceContactsellerModuleFrontController extends ModuleFrontController
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
            if ($message['id_customer'] == 0) {
                $message = new SellerIncidenceMessage($message['id_seller_incidence_message']);
                $message->readed = 1;
                $message->update();
            }
        }
    }
    
    public function postProcess()
    {
        if (Tools::isSubmit('submitAddIncidence')) {
            if ($_FILES['attachment']['name'] != '') {
                if (!in_array($_FILES['attachment']['type'], $this->module->mime_types)) {
                    $this->errors[] = $this->module->l('File type is incorrect.', 'contactseller');
                }
                
                $max_bytes_server = (int)(Tools::substr(ini_get('post_max_size'), 0, -1)) * 1024 * 1024;
                
                if ($_FILES['attachment']['size'] >= $max_bytes_server) {
                    $this->errors[] = $this->module->l('The uploaded file is too large. Maxim:', 'contactseller').' '.ini_get('post_max_size');
                }
            }
            
            if (count($this->errors) > 0) {
                $this->context->smarty->assign(array('errors' => $this->errors));
            } else {
                if (Tools::getValue('id_order_product')) {
                    $string_order_product = Tools::getValue('id_order_product');
                    $id_order_product = explode('-', $string_order_product);
                    $id_order = (int)$id_order_product[0];
                    $id_product = (int)$id_order_product[1];
                } else {
                    $id_order = 0;
                    $id_product = (int)Tools::getValue('id_product');
                }

                if ($id_product != 0) {
                    $id_seller = Seller::getSellerByProduct($id_product);
                } else {
                    $id_seller = (int)Tools::getValue('id_seller');
                }

                $seller = new Seller($id_seller);

                $incidence = new SellerIncidence();
                $incidence->reference = pSQL(SellerIncidence::generateReference());
                $incidence->id_order = (int)$id_order;
                $incidence->id_product = (int)$id_product;
                $incidence->id_customer = (int)$this->context->cookie->id_customer;
                $incidence->id_seller = (int)$id_seller;
                $incidence->id_shop = (int)$this->context->shop->id;
                $incidence->add();

                $incidenceMessage = new SellerIncidenceMessage();
                $incidenceMessage->id_seller_incidence = (int)$incidence->id;
                $incidenceMessage->id_customer = (int)$this->context->cookie->id_customer;
                $incidenceMessage->id_seller = 0;
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

                $id_seller_email = false;
                $to = $seller->email;
                $to_name = $seller->name;
                $from = Configuration::get('JMARKETPLACE_SEND_ADMIN');
                $from_name = Configuration::get('PS_SHOP_NAME');
                $template = 'base';

                if ($id_order == 0) {
                    $reference = 'new-message';
                    if ($id_product != 0) {
                        $product = new Product($id_product, false, $seller->id_lang);
                        $vars = array("{shop_name}", "{incidence_reference}", "{description}", "{product_name}");
                        $values = array(Configuration::get('PS_SHOP_NAME'), $incidence->reference, nl2br($incidenceMessage->description), $product->name);
                    } else {
                        $vars = array("{shop_name}", "{incidence_reference}", "{description}", "{product_name}");
                        $values = array(Configuration::get('PS_SHOP_NAME'), $incidence->reference, nl2br($incidenceMessage->description), "");
                    }
                } else {
                    $order = new Order($incidence->id_order);
                    $reference = 'new-incidence';
                    $vars = array("{shop_name}", "{order_reference}", "{incidence_reference}", "{description}");
                    $values = array(Configuration::get('PS_SHOP_NAME'), $order->reference, $incidence->reference, nl2br($incidenceMessage->description));
                }

                $id_seller_email = SellerEmail::getIdByReference($reference);

                if ($id_seller_email) {
                    $seller_email = new SellerEmail($id_seller_email, $seller->id_lang);
                    $subject_var = $seller_email->subject;
                    $subject_value = str_replace($vars, $values, $subject_var);
                    $content_var = $seller_email->content;
                    $content_value = str_replace($vars, $values, $content_var);

                    $template_vars = array(
                        '{content}' => $content_value,
                        '{shop_name}' => Configuration::get('PS_SHOP_NAME')
                    );

                    $iso = Language::getIsoById($seller->id_lang);

                    if (file_exists(dirname(__FILE__).'/../../mails/'.$iso.'/'.$template.'.txt') && file_exists(dirname(__FILE__).'/../../mails/'.$iso.'/'.$template.'.html')) {
                        Mail::Send(
                            $seller->id_lang,
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

                    //send email (in copy) to administrator when a incidence o message has been created
                    if (Configuration::get('JMARKETPLACE_SEND_ADMIN_INCIDENCE') == 1) {
                        $to = Configuration::get('JMARKETPLACE_SEND_ADMIN');
                        $to_name = Configuration::get('PS_SHOP_NAME');
                        $from = Configuration::get('JMARKETPLACE_SEND_ADMIN');
                        $from_name = Configuration::get('PS_SHOP_NAME');
                        $iso = Language::getIsoById($seller->id_lang);

                        if (file_exists(dirname(__FILE__).'/../../mails/'.$iso.'/'.$template.'.txt') && file_exists(dirname(__FILE__).'/../../mails/'.$iso.'/'.$template.'.html')) {
                            Mail::Send(
                                $seller->id_lang,
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

                $params = array('confirmation' => 1);
                $url_contact_seller_confirmation = $this->context->link->getModuleLink('jmarketplace', 'contactseller', $params, true);
                Tools::redirect($url_contact_seller_confirmation);
            }
        }
        
        if (Tools::isSubmit('submitResponse')) {
            if (isset($_FILES['attachment'])) {
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
                $incidenceMessage = new SellerIncidenceMessage();
                $incidenceMessage->id_seller_incidence = (int)Tools::getValue('id_incidence');
                $incidenceMessage->id_customer = (int)$this->context->cookie->id_customer;
                $incidenceMessage->id_seller = 0;
                $incidenceMessage->description = (string)Tools::getValue('description'); //this is content html

                //file attachment
                if (isset($_FILES['attachment'])) {
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
                $reference = 'new-response-customer';

                $id_seller_email = SellerEmail::getIdByReference($reference);

                if ($id_seller_email) {
                    $seller = new Seller($incidence->id_seller);
                    $to = $seller->email;
                    $to_name = $seller->name;
                    $from = Configuration::get('JMARKETPLACE_SEND_ADMIN');
                    $from_name = Configuration::get('PS_SHOP_NAME');
                    $seller_email = new SellerEmail($id_seller_email, $seller->id_lang);
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

                    $iso = Language::getIsoById($seller->id_lang);

                    if (file_exists(dirname(__FILE__).'/../../mails/'.$iso.'/'.$template.'.txt') && file_exists(dirname(__FILE__).'/../../mails/'.$iso.'/'.$template.'.html')) {
                        Mail::Send(
                            $seller->id_lang,
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

                    //send email (in copy) to administrator when a customer send new message
                    if (Configuration::get('JMARKETPLACE_SEND_ADMIN_INCIDENCE') == 1) {
                        $to = Configuration::get('JMARKETPLACE_SEND_ADMIN');
                        $to_name = Configuration::get('PS_SHOP_NAME');
                        $from = Configuration::get('JMARKETPLACE_SEND_ADMIN');
                        $from_name = Configuration::get('PS_SHOP_NAME');

                        $iso = Language::getIsoById($seller->id_lang);

                        if (file_exists(dirname(__FILE__).'/../../mails/'.$iso.'/'.$template.'.txt') && file_exists(dirname(__FILE__).'/../../mails/'.$iso.'/'.$template.'.html')) {
                            Mail::Send(
                                $seller->id_lang,
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

                Tools::redirect($this->context->link->getModuleLink('jmarketplace', 'contactseller', array('confirmation' => 1), true));
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
        
        if (!Configuration::get('JMARKETPLACE_SHOW_CONTACT')) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
        }
        
        if ($this->context->cookie->id_customer) {
            $orders = Order::getCustomerOrders($this->context->cookie->id_customer);
            $num_orders = count($orders);
            $orders_products = false;

            if ($num_orders > 0) {
                $orders_products = array();
                foreach ($orders as $o) {
                    $order = new Order($o['id_order']);
                    $products = $order->getProducts();
                    foreach ($products as $p) {
                        $orders_products[] = array(
                            'id_order_product' => $o['id_order'].'-'.$p['product_id'],
                            'order_reference' => $order->reference,
                            'order_date_add' => $order->date_add,
                            'product_name' => $p['product_name'],
                        );
                    }
                }
            }

            $incidences = SellerIncidence::getIncidencesByCustomer((int)$this->context->cookie->id_customer);

            if ($incidences != false) {
                $counter = 0;
                foreach ($incidences as $i) {
                    $incidences[$counter]['product_name'] = '';
                    if ($i['id_product'] != 0) {
                        $product = new Product((int)$i['id_product'], (int)$this->context->language->id, (int)$this->context->shop->id);
                        $incidences[$counter]['product_name'] = $product->name;
                    }
                    
                    $incidences[$counter]['messages_not_readed'] = SellerIncidence::getNumMessagesNotReaded($i['id_seller_incidence'], false, (int)$this->context->cookie->id_customer);
                    $messages = SellerIncidence::getMessages((int)$i['id_seller_incidence']);
                    $incidences[$counter] = array_merge($incidences[$counter], array('messages' => $messages));
                    $counter++;
                }
            }
            
            $url_contact_seller = $this->context->link->getModuleLink('jmarketplace', 'contactseller', array(), true);

            if (Tools::getValue('id_seller') && Tools::getValue('id_product')) {
                $id_product = (int)Tools::getValue('id_product');
                $id_seller = (int)Tools::getValue('id_seller');
                $product = new Product($id_product, false, (int)$this->context->language->id, (int)$this->context->shop->id);
                
                $this->context->smarty->assign(array(
                    'product' => $product,
                    'id_product' => $id_product,
                    'id_seller' => $id_seller
                ));
            } elseif (Tools::getValue('id_seller') && !Tools::getValue('id_product')) {
                $id_seller = (int)Tools::getValue('id_seller');
                $seller = new Seller($id_seller);

                $this->context->smarty->assign(array(
                    'id_product' => 0,
                    'id_seller' => $id_seller,
                    'seller' => $seller
                ));
            } else {
                $this->context->smarty->assign(array(
                    'id_product' => 0,
                    'id_seller' => 0,
                ));
            }
            
            $url_contact_seller = $this->context->link->getModuleLink('jmarketplace', 'contactseller', array(), true);
            
            $this->context->smarty->assign(array(
                'incidences' => $incidences,
                'orders_products' => $orders_products,
                'num_orders' => $num_orders,
                'url_contact_seller' => $url_contact_seller,
                'token' => Configuration::get('JMARKETPLACE_TOKEN'),
                'logged' => $this->context->cookie->logged,
                'PS_REWRITING_SETTINGS' => Configuration::get('PS_REWRITING_SETTINGS'),
            ));
        }
        
        if (Tools::getValue('confirmation')) {
            $this->context->smarty->assign(array('confirmation' => 1));
        }
        
        $this->setTemplate('contactseller.tpl');
    }
}
