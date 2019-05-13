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

class AdminSellerIncidencesController extends ModuleAdminController
{
    public function setMedia()
    {
        parent::setMedia();
        $this->context->controller->addjQueryPlugin(array('select2'));
        $this->context->controller->addJS(_PS_MODULE_DIR_.'jmarketplace/views/js/select2call.js');
    }

    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'seller_incidence';
        $this->className = 'SellerIncidence';
        $this->lang = false;
        $this->states_array = array();
        $this->types_array = array();
        $this->priorities_array = array();
        
        $this->context = Context::getContext();

        parent::__construct();
        
        $this->_select = 'a.reference as incidence_ref, o.reference as order_ref, CONCAT(c.`firstname`, \' \',  c.`lastname`) as customer_name, s.name as seller_name, a.date_add';
        $this->_join = 'LEFT JOIN `'._DB_PREFIX_.'customer` c ON (c.`id_customer` = a.`id_customer`)
                        LEFT JOIN `'._DB_PREFIX_.'seller` s ON (s.`id_seller` = a.`id_seller`)
                        LEFT JOIN `'._DB_PREFIX_.'orders` o ON (o.`id_order` = a.`id_order`)';
        $this->_where = 'AND a.id_shop = '.(int)$this->context->shop->id;

        $this->fields_list = array(
            'incidence_ref' => array(
                'title' => $this->l('Incidence reference'),
                'havingFilter' => true,
            ),
            'order_ref' => array(
                'title' => $this->l('Order reference'),
                'havingFilter' => true,
            ),
            'customer_name' => array(
                'title' => $this->l('Customer name'),
                'havingFilter' => true,
            ),
            'seller_name' => array(
                'title' => $this->l('Seller'),
                'havingFilter' => true,
            ),
            'date_add' => array(
                'title' => $this->l('Date add'),
            ),
            'date_upd' => array(
                'title' => $this->l('Date update'),
            )
        );
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
                'icon' => 'icon-trash'
            )
        );
    }

    public function renderList()
    {
        $this->addRowAction('view');
        //$this->addRowAction('edit');
        $this->addRowAction('delete');
        return parent::renderList();
    }
    
    public function renderForm()
    {
        $orders = array(array('id_order' => 0, 'reference' => $this->l('Not apply')));
        $orders = array_merge($orders, Order::getOrdersWithInformations());
        
        $products = array(array('id_product' => 0, 'name' => $this->l('Not apply')));
        $products = array_merge($products, Product::getSimpleProducts($this->context->language->id));
        
        $sellers = array(array('id_seller' => 0, 'name' => $this->l('Not apply')));
        $sellers = array_merge($sellers, Seller::getSellers($this->context->shop->id));
        
        $customers_array = array(array('id_customer' => 0, 'name' => $this->l('Not apply')));
        $customers = $this->getCustomers(true);
        $customer_select = array();
        foreach ($customers as $customer) {
            $customer_select[] = array('id_customer' => $customer['id_customer'], 'name' => $customer['firstname'].' '.$customer['lastname']);
        }

        $customers = array_merge($customers_array, $customer_select);
        
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Add new message'),
            ),
            'input' => array(
                    array(
                        'type' => 'select',
                        'label' => $this->l('About an order'),
                        'name' => 'id_order',
                        'required' => false,
                        'class'=> 'select2',
                        'options' => array(
                            'query' => $orders,
                            'id' => 'id_order',
                            'name' => 'reference'
                        )
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('About a product'),
                        'name' => 'id_product',
                        'required' => false,
                        'class'=> 'select2',
                        'options' => array(
                            'query' => $products,
                            'id' => 'id_product',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('For a seller'),
                        'name' => 'id_seller',
                        'required' => false,
                        'class'=> 'select2',
                        'options' => array(
                            'query' => $sellers,
                            'id' => 'id_seller',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('For a customer'),
                        'name' => 'id_customer',
                        'required' => false,
                        'class'=> 'select2',
                        'options' => array(
                            'query' => $customers,
                            'id' => 'id_customer',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Message'),
                        'name' => 'description',
                        'lang' => false,
                        'autoload_rte' => false,
                        'cols' => 20,
                        'rows' => 10,
                    ),
                    array(
                        'type' => 'file',
                        'label' => $this->l('Attachment'),
                        'name' => 'attachment',
                        'lang' => false,
                        'col' => 6,
                    ),
                                      
            )
        );

        $this->fields_form['submit'] = array(
            'title' => $this->l('Save'),
        );

        return parent::renderForm();
    }
    
    public function getCustomers($only_active = null)
    {
        return Db::getInstance()->executeS(
            'SELECT `id_customer`, `email`, `firstname`, `lastname`
            FROM `'._DB_PREFIX_.'customer`
            WHERE 1 '.Shop::addSqlRestriction(Shop::SHARE_CUSTOMER).
            ($only_active ? ' AND `active` = 1' : '').'
            ORDER BY `firstname` ASC'
        );
    }
    
    public function postProcess()
    {
        parent::postProcess();
        
        if (Tools::isSubmit('submitFilter')) {
            if (Tools::getValue('seller_incidenceFilter_incidence_ref') != '') {
                $this->_where = 'AND a.reference LIKE "%'.pSQL(Tools::getValue('seller_incidenceFilter_incidence_ref')).'%"';
            }
            
            if (Tools::getValue('seller_incidenceFilter_order_ref') != '') {
                $this->_where = 'AND o.reference LIKE "%'.pSQL(Tools::getValue('seller_incidenceFilter_order_ref')).'%"';
            }
            
            if (Tools::getValue('seller_incidenceFilter_customer_name') != '') {
                $this->_where = 'AND (c.firstname LIKE "%'.pSQL(Tools::getValue('seller_incidenceFilter_firstname')).'%" OR c.lastname LIKE "%'.pSQL(Tools::getValue('seller_incidenceFilter_customer_name')).'%")';
            }
            
            if (Tools::getValue('seller_incidenceFilter_seller_name') != '') {
                $this->_where = 'AND s.name LIKE "%'.pSQL(Tools::getValue('seller_incidenceFilter_seller_name')).'%"';
            }
            
            if (Tools::getValue('seller_incidenceFilter_id_incidence_state')) {
                $this->_where = 'AND ist.id_incidence_state = '.(int)Tools::getValue('seller_incidenceFilter_id_incidence_state');
            }
            
            if (Tools::getValue('seller_incidenceFilter_id_incidence_type')) {
                $this->_where = 'AND it.id_incidence_type = '.(int)Tools::getValue('seller_incidenceFilter_id_incidence_type');
            }
            
            if (Tools::getValue('seller_incidenceFilter_id_incidence_priority')) {
                $this->_where = 'AND ip.id_incidence_priority = '.(int)Tools::getValue('seller_incidenceFilter_id_incidence_priority');
            }
        }
        
        $this->_orderBy = 'date_upd';
        $this->_orderWay = 'DESC';
        
        if (Tools::getValue('seller_incidenceOrderby')) {
            $this->_orderBy = pSQL(Tools::getValue('seller_incidenceOrderby'));
            $this->_orderWay = pSQL(Tools::getValue('seller_incidenceOrderway'));
        }
        
        if ($this->display == 'view') {
            $incidence = new SellerIncidence((int)Tools::getValue('id_seller_incidence'));

            if (Tools::isSubmit('submitResponse')) {
                if ($_FILES['attachment']['size'] > 0) {
                    if (!in_array($_FILES['attachment']['type'], $this->module->mime_types)) {
                        $this->errors[] = $this->module->l('File type is incorrect.', 'AdminSellerIncidences');
                    }

                    $max_bytes_server = (int)(Tools::substr(ini_get('post_max_size'), 0, -1)) * 1024 * 1024;
                
                    if ($_FILES['attachment']['size'] >= $max_bytes_server) {
                        $this->errors[] = $this->module->l('The uploaded file is too large. Maxim:', 'AdminSellerIncidences').' '.ini_get('post_max_size');
                    }
                }
                
                if (count($this->errors) > 0) {
                    $this->context->smarty->assign(array('errors' => $this->errors));
                } else {
                    $incidenceMessage = new SellerIncidenceMessage();
                    $incidenceMessage->id_seller_incidence = (int)Tools::getValue('id_seller_incidence');
                    $incidenceMessage->id_customer = 0;
                    $incidenceMessage->id_seller = 0;
                    $incidenceMessage->id_employee = $this->context->employee->id;
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

                    //send email to customer
                    $id_seller_email = false;
                    $template = 'base';
                    $reference = 'new-response-seller';

                    $id_seller_email = SellerEmail::getIdByReference($reference);

                    if ($id_seller_email) {
                        $id_customer = (int)$incidence->id_customer;
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
                    }
                    
                    //send email to seller
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
                    }
                    
                    $this->context->smarty->assign(array('confirmation' => 1));
                }
            }
            
            $order_reference = 0;
            if ($incidence->id_order != 0) {
                $order = new Order($incidence->id_order);
                $order_reference = $order->reference;
                
                $this->context->smarty->assign(array(
                    'order_reference' => $order_reference,
                    'url_order' => 'index.php?tab=AdminOrders&id_order='.(int)$order->id.'&vieworder&token='.Tools::getAdminToken('AdminOrders'.(int)Tab::getIdFromClassName('AdminOrders').(int)$this->context->employee->id),
                ));
            }
            
            $product_name = 0;
            if ($incidence->id_product != 0) {
                $product = new Product($incidence->id_product, null, $this->context->language->id);
                $product_name = $product->name;
                
                $this->context->smarty->assign(array(
                    'product_name' => $product_name,
                    'url_product' => 'index.php?tab=AdminSellerProducts&id_product='.(int)$product->id.'&viewproduct&token='.Tools::getAdminToken('AdminSellerProducts'.(int)Tab::getIdFromClassName('AdminSellerProducts').(int)$this->context->employee->id),
                ));
            }
            
            if ($incidence->id_seller != 0) {
                $seller = new Seller($incidence->id_seller);
                $this->context->smarty->assign(array(
                    'seller' => $seller,
                    'url_seller' => 'index.php?tab=AdminSellers&id_seller='.(int)$seller->id.'&viewseller&token='.Tools::getAdminToken('AdminSellers'.(int)Tab::getIdFromClassName('AdminSellers').(int)$this->context->employee->id),
                ));
            }
            
            if ($incidence->id_customer != 0) {
                $customer = new Customer($incidence->id_customer);
                $this->context->smarty->assign(array(
                    'customer' => $customer,
                    'url_customer' => 'index.php?tab=AdminCustomers&id_customer='.(int)$customer->id.'&viewcustomer&token='.Tools::getAdminToken('AdminCustomers'.(int)Tab::getIdFromClassName('AdminCustomers').(int)$this->context->employee->id),
                ));
            }
            
            $messages = SellerIncidence::getMessages($incidence->id);

            $this->context->smarty->assign(array(
                'incidence' => $incidence,
                'messages' => $messages,
                'attachment_dir' => _PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->module->name.'/attachment/',
                'url_post' => self::$currentIndex.'&id_seller_incidence='.$incidence->id.'&viewseller_incidence&token='.$this->token,
                'token' => $this->token,
            ));
        }

        if (Tools::isSubmit('deleteseller_incidence')) {
            $id_seller_incidence = (int)Tools::getValue('id_seller_incidence');
            $seller_incidence = new SellerIncidence($id_seller_incidence);
            $seller_incidence->delete();
        }
        
        if (Tools::isSubmit('submitAddseller_incidence')) {
            //print_r($_POST); DIE();
            $id_order = (int)Tools::getValue('id_order');
            $id_product = (int)Tools::getValue('id_product');
            $id_seller = (int)Tools::getValue('id_seller');
            $id_customer = (int)Tools::getValue('id_customer');
            $message = (string)Tools::getValue('description');
            
            if ($id_seller == 0 && $id_customer == 0) {
                $this->errors[] = $this->module->l('To send a message you must select at least one a seller or a customer.', 'AdminSellerIncidencesController');
            }
            
            if ($id_product != 0 && $id_seller != 0) {
                $id_seller_product = Seller::getSellerByProduct($id_product);
                if ($id_seller_product != $id_seller) {
                    $this->errors[] = $this->module->l('The selected product does not belong to the selected seller.', 'AdminSellerIncidencesController');
                }
            }
            
            if ($id_order != 0 && $id_customer != 0) {
                $order = new Order($id_order);
                if ($order->id_customer != $id_customer) {
                    $this->errors[] = $this->module->l('The selected order does not belong to the selected customer', 'AdminSellerIncidencesController');
                }
            }
            
            if ($_FILES['attachment']['name'] != '') {
                if (!in_array($_FILES['attachment']['type'], $this->module->mime_types)) {
                    $this->errors[] = $this->module->l('File type is incorrect.', 'AdminSellerIncidencesController');
                }
                
                $max_bytes_server = (int)(Tools::substr(ini_get('post_max_size'), 0, -1)) * 1024 * 1024;
                
                if ($_FILES['attachment']['size'] >= $max_bytes_server) {
                    $this->errors[] = $this->module->l('The uploaded file is too large. Maxim:', 'AdminSellerIncidencesController').' '.ini_get('post_max_size');
                }
            }
            
            if (count($this->errors) > 0) {
                $this->context->smarty->assign(array('errors' => $this->errors));
            } else {
                $incidence = new SellerIncidence();
                $incidence->reference = pSQL(SellerIncidence::generateReference());
                $incidence->id_order = (int)$id_order;
                $incidence->id_product = (int)$id_product;
                $incidence->id_customer = (int)$id_customer;
                $incidence->id_seller = (int)$id_seller;
                $incidence->id_employee = (int)$this->context->employee->id;
                $incidence->id_shop = (int)$this->context->shop->id;
                $incidence->add();

                $incidenceMessage = new SellerIncidenceMessage();
                $incidenceMessage->id_seller_incidence = (int)$incidence->id;
                $incidenceMessage->id_customer = 0;
                $incidenceMessage->id_seller = 0;
                $incidenceMessage->id_employee = (int)$this->context->employee->id;
                $incidenceMessage->description = $message; //this is content html

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
                
                if ($id_seller != 0) {
                    $seller = new Seller($id_seller);

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
                    }
                }
                
                if ($id_customer != 0) {
                    $customer = new Customer($id_customer);

                    $id_seller_email = false;
                    $to = $customer->email;
                    $to_name = $customer->firstname.' '.$customer->lastname;
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
                            $vars = array("{shop_name}", "{incidence_reference}", "{description}");
                            $values = array(Configuration::get('PS_SHOP_NAME'), $incidence->reference, nl2br($incidenceMessage->description));
                        }
                    } else {
                        $order = new Order($incidence->id_order);
                        $reference = 'new-incidence';
                        $vars = array("{shop_name}", "{order_reference}", "{incidence_reference}", "{description}");
                        $values = array(Configuration::get('PS_SHOP_NAME'), $order->reference, $incidence->reference, nl2br($incidenceMessage->description));
                    }

                    $id_seller_email = SellerEmail::getIdByReference($reference);

                    if ($id_seller_email) {
                        $seller_email = new SellerEmail($id_seller_email, $customer->id_lang);
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
            }
        }
    }
}
