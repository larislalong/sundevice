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

class AdminSellersController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'seller';
        $this->className = 'Seller';
        $this->lang = false;
        
        $this->context = Context::getContext();

        parent::__construct();
        
        $this->_where = 'AND a.id_shop = '.(int)$this->context->shop->id;

        $this->fields_list = array(
            'id_seller' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs'
            ),
            'name' => array(
                'title' => $this->l('Seller name'),
                'havingFilter' => true,
            ),
            'shop' => array(
                'title' => $this->l('Shop name'),
                'havingFilter' => true,
            ),
            'email' => array(
                'title' => $this->l('Email'),
                'havingFilter' => true,
            ),
            'date_add' => array(
                'title' => $this->l('Date add'),
                'type' => 'datetime',
            ),
            'date_upd' => array(
                'title' => $this->l('Date update'),
                'type' => 'datetime',
            ),
            'active' => array(
                'title' => $this->l('Enabled'),
                'align' => 'center',
                'active' => 'status',
                'type' => 'bool',
                'orderby' => false,
                'class' => 'fixed-width-sm'
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

    public function setMedia()
    {
        parent::setMedia();
        $this->context->controller->addJqueryPlugin('fancybox');
        $this->context->controller->addJS(_PS_MODULE_DIR_.'jmarketplace/views/js/back.js', 'all');
        if (Tools::isSubmit('addseller') || Tools::isSubmit('updateseller')) {
            $this->context->controller->addjQueryPlugin(array('select2'));
            $this->context->controller->addJS(_PS_MODULE_DIR_.'jmarketplace/views/js/select2call.js');
        }
    }

    public function initPageHeaderToolbar()
    {
        if (empty($this->display)) {
            $this->page_header_toolbar_btn['new_seller'] = array(
                'href' => self::$currentIndex.'&addseller&token='.$this->token,
                'desc' => $this->l('Add new seller', null, null, false),
                'icon' => 'process-icon-new'
            );
        }

        parent::initPageHeaderToolbar();
    }

    public function renderList()
    {
        $this->addRowAction('view');
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        return parent::renderList();
    }

    public function renderForm()
    {
        $image_url = false;
        $image_size = 0;
        
        if (Tools::isSubmit('updateseller')) {
            $seller = new Seller((int)Tools::getValue('id_seller'));
            $image = _PS_IMG_DIR_.'sellers/'.$seller->id_customer.'.jpg';
            if (file_exists($image)) {
                $image_url = ImageManager::thumbnail($image, $this->table.'_'.(int)$seller->id_customer.'.'.$this->imageType, 350, $this->imageType, true, true);
                $image_size = file_exists($image) ? filesize($image) / 1000 : false;
            }
        }
        
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Add/Edit Seller'),
                'icon' => 'icon-globe'
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => 'id_seller',
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Customer'),
                    'name' => 'id_customer',
                    'required' => false,
                    'class'=> 'select2',
                    'options' => array(
                        'query' => Customer::getCustomers(),
                        'id' => 'id_customer',
                        'name' => 'email'
                    )
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Name'),
                    'name' => 'name',
                    'lang' => false,
                    'col' => 3,
                    'required' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Shop'),
                    'name' => 'shop',
                    'lang' => false,
                    'col' => 3,
                    'required' => false,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Email'),
                    'name' => 'email',
                    'lang' => false,
                    'col' => 3,
                    'required' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('CIF/NIF'),
                    'name' => 'cif',
                    'lang' => false,
                    'col' => 3,
                    'required' => false,
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Language'),
                    'name' => 'id_lang',
                    'required' => false,
                    'class'=> 'select2',
                    'options' => array(
                        'query' => Language::getLanguages(),
                        'id' => 'id_lang',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Phone'),
                    'name' => 'phone',
                    'lang' => false,
                    'col' => 3,
                    'required' => false,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Fax'),
                    'name' => 'fax',
                    'lang' => false,
                    'col' => 3,
                    'required' => false,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Address'),
                    'name' => 'address',
                    'lang' => false,
                    'col' => 4,
                    'required' => false,
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Country'),
                    'name' => 'country',
                    'required' => false,
                    'class'=> 'select2',
                    'options' => array(
                        'query' => Country::getCountries($this->context->language->id, true),
                        'id' => 'name',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('State'),
                    'name' => 'state',
                    'lang' => false,
                    'col' => 4,
                    'required' => false,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Post code'),
                    'name' => 'postcode',
                    'lang' => false,
                    'col' => 4,
                    'required' => false,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('City'),
                    'name' => 'city',
                    'lang' => false,
                    'col' => 4,
                    'required' => false,
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Description'),
                    'name' => 'description',
                    'lang' => false,
                    'autoload_rte' => true,
                    'cols' => 40,
                    'rows' => 7,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Meta title'),
                    'name' => 'meta_title',
                    'lang' => false,
                    'col' => 4,
                    'required' => false,
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Meta description'),
                    'name' => 'meta_description',
                    'lang' => false,
                    'col' => 4,
                    'required' => false,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Meta keywords'),
                    'name' => 'meta_keywords',
                    'lang' => false,
                    'col' => 4,
                    'required' => false,
                ),
                array(
                    'type' => 'file',
                    'label' => $this->l('Logo or photo'),
                    'name' => 'sellerImage',
                    'image' => $image_url ? $image_url : false,
                    'size' => $image_size,
                    'display_image' => true,
                    'col' => 6,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Active'),
                    'name' => 'active',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                    'hint' => $this->l('Allow or disallow banned user.')
                ),
            )
        );

        $this->fields_form['submit'] = array(
            'title' => $this->l('Save'),
        );

        return parent::renderForm();
    }
    
    public function postProcess()
    {
        parent::postProcess();
        
        if (Tools::isSubmit('submitFilter')) {
            if (Tools::getValue('sellerFilter_id_seller') != '') {
                $this->_where .= ' AND a.id_seller = '.(int)Tools::getValue('sellerFilter_id_seller');
            }
            
            if (Tools::getValue('sellerFilter_name') != '') {
                $this->_where .= ' AND a.name LIKE "%'.pSQL(Tools::getValue('sellerFilter_name')).'%"';
            }
            
            if (Tools::getValue('sellerFilter_shop') != '') {
                $this->_where .= ' AND a.shop LIKE "%'.pSQL(Tools::getValue('sellerFilter_shop')).'%"';
            }
            
            if (Tools::getValue('sellerFilter_email') != '') {
                $this->_where .= ' AND a.email LIKE "%'.pSQL(Tools::getValue('sellerFilter_email')).'%"';
            }
            
            if (Tools::getValue('sellerFilter_active') != '') {
                $this->_where .= ' AND a.active = '.(int)Tools::getValue('sellerFilter_active');
            }
            
            $arrayDateAdd = Tools::getValue('sellerFilter_date_add');
            if ($arrayDateAdd[0] != '' && $arrayDateAdd[1] != '') {
                $this->_where .= ' AND a.date_add BETWEEN "'.pSQL($arrayDateAdd[0]).'" AND "'.pSQL($arrayDateAdd[1]).'"';
            }
            
            $arrayDateUpd = Tools::getValue('sellerFilter_date_upd');
            if ($arrayDateUpd[0] != '' && $arrayDateUpd[1] != '') {
                $this->_where .= ' AND a.date_upd BETWEEN "'.pSQL($arrayDateUpd[0]).'" AND "'.pSQL($arrayDateUpd[1]).'"';
            }
        }
        
        $this->_orderBy = 'date_upd';
        $this->_orderWay = 'DESC';
        
        if (Tools::getValue('sellerOrderway')) {
            $this->_orderBy = pSQL(Tools::getValue('sellerOrderby'));
            $this->_orderWay = pSQL(Tools::getValue('sellerOrderway'));
        }
        
        if ($this->display == 'view') {
            $id_seller = (int)Tools::getValue('id_seller');
            $seller = new Seller($id_seller);
            $start = 0;
            $limit = 5000;
            $order_by = 'date_add';
            $order_way = 'desc';
            $products = $seller->getProducts((int)$this->context->language->id, $start, $limit, $order_by, $order_way);
            
            $params = array('id_seller' => $id_seller, 'link_rewrite' => $seller->link_rewrite);
            $url_seller_profile = $this->module->getJmarketplaceLink('jmarketplace_seller_rule', $params);
            $url_seller_products = Jmarketplace::getJmarketplaceLink('jmarketplace_sellerproductlist_rule', $params);
            $param2 = array('id_seller' => $seller->id);
            $url_seller_comments = $this->context->link->getModuleLink('jmarketplace', 'sellercomments', $param2, true);
            $url_favorite_seller = $this->context->link->getModuleLink('jmarketplace', 'favoriteseller', $param2, true);
            
            if (Configuration::get('PS_SSL_ENABLED') == 1) {
                $url_shop = Tools::getShopDomainSsl(true).__PS_BASE_URI__;
            } else {
                $url_shop = Tools::getShopDomain(true).__PS_BASE_URI__;
            }

            if (file_exists(_PS_IMG_DIR_.'sellers/'.$seller->id_customer.'.jpg')) {
                $this->context->smarty->assign(array('photo' => $url_shop.'/img/sellers/'.$seller->id_customer.'.jpg'));
            } else {
                $this->context->smarty->assign(array('photo' =>  $url_shop.'/modules/jmarketplace/views/img/profile.jpg'));
            }
            
            $customer = new Customer($seller->id_customer);
            $seller_commission = SellerCommission::getRowCommissionBySeller($id_seller);
            
            $this->context->smarty->assign(array(
                'show_shop_name' => Configuration::get('JMARKETPLACE_SHOW_SHOP_NAME'),
                'show_language' => Configuration::get('JMARKETPLACE_SHOW_LANGUAGE'),
                'show_phone' => Configuration::get('JMARKETPLACE_SHOW_PHONE'),
                'show_fax' => Configuration::get('JMARKETPLACE_SHOW_FAX'),
                'show_address' => Configuration::get('JMARKETPLACE_SHOW_ADDRESS'),
                'show_country' => Configuration::get('JMARKETPLACE_SHOW_COUNTRY'),
                'show_state' => Configuration::get('JMARKETPLACE_SHOW_STATE'),
                'show_city' => Configuration::get('JMARKETPLACE_SHOW_CITY'),
                'show_postcode' => Configuration::get('JMARKETPLACE_SHOW_POSTAL_CODE'),
                'show_description' => Configuration::get('JMARKETPLACE_SHOW_DESCRIPTION'),
                'show_meta_title' => Configuration::get('JMARKETPLACE_SHOW_MTA_TITLE'),
                'show_meta_description' => Configuration::get('JMARKETPLACE_SHOW_MTA_DESCRIPTION'),
                'show_meta_keywords' => Configuration::get('JMARKETPLACE_SHOW_MTA_KEYWORDS'),
                'show_logo' => Configuration::get('JMARKETPLACE_SHOW_LOGO'),
                'show_seller_rating' => Configuration::get('JMARKETPLACE_SELLER_RATING'),
                'show_contact' => Configuration::get('JMARKETPLACE_SHOW_CONTACT'),
                'show_seller_favorite' => Configuration::get('JMARKETPLACE_SELLER_FAVORITE'),
                'url_seller_comments' => $url_seller_comments,
                'url_favorite_seller' => $url_favorite_seller,
                'followers' => $seller->getFollowers(),
                'seller' => $seller,
                'seller_commission' => $seller_commission['commission'],
                'url_seller_commission' => 'index.php?tab=AdminSellerCommissions&id_seller_commission='.(int)$seller_commission['id_seller_commission'].'&updateseller_commission&token='.Tools::getAdminToken('AdminSellerCommissions'.(int)Tab::getIdFromClassName('AdminSellerCommissions').(int)$this->context->employee->id),
                'customer' => $customer,
                'url_customer' => 'index.php?tab=AdminCustomers&id_customer='.(int)$customer->id.'&viewcustomer&token='.Tools::getAdminToken('AdminCustomers'.(int)Tab::getIdFromClassName('AdminCustomers').(int)$this->context->employee->id),
                'products' => $products,
                'url_seller_profile' => $url_seller_profile,
                'url_seller_products' => $url_seller_products,
                'payments' => SellerPayment::getPaymentsBySeller($id_seller),
                'token' => $this->token,
            ));
            
            if (Configuration::get('JMARKETPLACE_SELLER_RATING')) {
                $average = SellerComment::getRatings($id_seller);
                $averageTotal = SellerComment::getCommentNumber($id_seller);

                $this->context->smarty->assign(array(
                    'averageTotal' => (int)$averageTotal,
                    'averageMiddle' => ceil($average['avg']),
                ));
            }
            
            if (Configuration::get('JMARKETPLACE_SHOW_LANGUAGE') != 0) {
                $language = new Language($seller->id_lang);
                $this->context->smarty->assign('seller_lang', $language);
            }
        }
        
        if (Tools::isSubmit('statusseller')) {
            $id_seller = (int)Tools::getValue('id_seller');
            $seller = new Seller($id_seller);
            
            //desactivate all products when seller desactivated
            if ($seller->active == 0) {
                $this->desactivateAllProducts($seller);
            }
            
            $this->reportSellerStatusChange($seller);
        }
        
        if (Tools::isSubmit('submitAddseller')) {
            $id_customer = (int)Tools::getValue('id_customer');
            $id_seller = 0;
            
            if (Tools::getValue('id_seller')) {
                $id_seller = (int)Tools::getValue('id_seller');
                $seller = new Seller($id_seller);
                $params = array('id_seller' => $seller->id);
                Hook::exec('actionMarketplaceBeforeUpdateSeller');
            } else {
                Hook::exec('actionMarketplaceBeforeAddSeller');
            }
            
            $id_seller_by_customer = Seller::getSellerByCustomer($id_customer);
            if ($id_seller_by_customer && $id_seller_by_customer != $id_seller) {
                $this->errors[] = $this->module->l('The customer selected is seller yet.', 'AdminSellersController');
            }
            
            $seller_name = (string)Tools::getValue('name');
            $seller_shop = (string)Tools::getValue('shop');
            $seller_email = Tools::getValue('email');

            if ($_FILES['sellerImage']['name'] != "") {
                if (!Seller::saveSellerImage($_FILES['sellerImage'], $id_customer)) {
                    $this->errors[] = $this->module->l('The image seller format is incorrect.', 'AdminSellersController');
                }
            }
            
            if (Seller::existName($seller_name) > 0 && $seller->name != $seller_name) {
                $this->errors[] = $this->module->l('The name of seller already exists in our database.', 'AdminSellersController');
            }
            
            if (!isset($seller_name) || $seller_name == '') {
                $this->errors[] = $this->module->l('Invalid seller name.', 'addseller');
            }
            
            if (Seller::existEmail($seller_email) > 0  && $seller->email != $seller_email) {
                $this->errors[] = $this->module->l('The email of seller already exists in our database.', 'AdminSellersController');
            }
            
            if (!isset($seller_email) || $seller_email == '' || !Validate::isEmail($seller_email)) {
                $this->errors[] = $this->module->l('Invalid seller email.', 'AdminSellersController');
            }

            if (count($this->errors) == 0) {
                if ($id_seller != 0) {
                    $seller = new Seller($id_seller);
                } else {
                    $seller = new Seller();
                }

                $seller->id_customer = $id_customer;
                $seller->id_shop = (int)$this->context->shop->id;
                $seller->id_lang = (int)Tools::getValue('id_lang');
                $seller->name = Tools::stripslashes(trim($seller_name));
                $seller->email = pSQL($seller_email);
                $seller->link_rewrite = Seller::generateLinkRewrite($seller->name);
                $seller->shop = pSQL(Tools::stripslashes(trim($seller_shop)));
                $seller->cif = pSQL(Tools::getValue('cif'));
                $seller->phone = pSQL(Tools::getValue('phone'));
                $seller->fax = pSQL(Tools::getValue('fax'));
                $seller->address = pSQL(Tools::stripslashes(Tools::getValue('address')));
                $seller->country = pSQL(Tools::stripslashes(Tools::getValue('country')));
                $seller->state = pSQL(Tools::stripslashes(Tools::getValue('state')));
                $seller->city = pSQL(Tools::stripslashes(Tools::getValue('city')));
                $seller->postcode = pSQL(Tools::getValue('postcode'));
                $seller->description = (string)Tools::getValue('description'); //this is content html
                $seller->meta_title = pSQL(Tools::getValue('meta_title'));
                $seller->meta_description = pSQL(Tools::getValue('meta_description'));
                $seller->meta_keywords = pSQL(Tools::getValue('meta_keywords'));
                $seller->active = (int)Tools::getValue('active');

                if ($id_seller != 0) {
                    $seller->update();
                    $params = array('id_seller' => $seller->id);
                    Hook::exec('actionMarketplaceAfterUpdateSeller', $params);
                } else {
                    $seller->add();
                    $params = array('id_seller' => $seller->id);
                    Hook::exec('actionMarketplaceAfterAddSeller', $params);
                }
                
                $this->reportSellerStatusChange($seller);
            }
        }
        
        if (Tools::isSubmit('submitSellerRejection')) {
            $id_seller = (int)Tools::getValue('id_seller');
            $reasons = (string)Tools::getValue('reasons');
            $seller = new Seller($id_seller);
            $seller->active = 0;
            $seller->update();
            
            //desactivate all products when seller desactivated
            if ($seller->active == 0) {
                $this->desactivateAllProducts($seller);
            }
            
            $this->reportSellerStatusChange($seller, $reasons);
            
            $this->confirmations[] = $this->module->l('The declination message has been sent correctly.', 'AdminSellersController');
        }
        
        //enable sellers selected
        if (Tools::isSubmit('submitBulkenableSelectionseller')) {
            $sellers_selected = Tools::getValue('sellerBox');
            foreach ($sellers_selected as $id_seller) {
                $seller = new Seller($id_seller);
                $seller->active = 1;
                $seller->update();
                $this->reportSellerStatusChange($seller);
            }
        }
        
        //disable sellers selected
        if (Tools::isSubmit('submitBulkdisableSelectionseller')) {
            $sellers_selected = Tools::getValue('sellerBox');
            foreach ($sellers_selected as $id_seller) {
                $seller = new Seller($id_seller);
                $seller->active = 0;
                $seller->update();
                $this->reportSellerStatusChange($seller);
            }
        }
        
        //delete sellers selected
        if (Tools::isSubmit('submitBulkdeleteseller')) {
            $sellers_selected = Tools::getValue('sellerBox');
            foreach ($sellers_selected as $id_seller) {
                $seller = new Seller($id_seller);
                $seller->delete();
            }
        }
    }
    
    public function desactivateAllProducts($seller)
    {
        $id_products = $seller->getIdProducts();
        if (is_array($id_products) && count($id_products) > 0) {
            foreach ($id_products as $id_product) {
                $product = new Product($id_product['id_product']);
                $product->active = 0;
                $product->update();
                /*Db::getInstance()->Execute('UPDATE `'._DB_PREFIX_.'product` SET `active` = 0 WHERE id_product = '.$id_product);
                Db::getInstance()->Execute('UPDATE `'._DB_PREFIX_.'product_shop` SET `active` = 0 WHERE id_product = '.$id_product);*/
            }
        }
    }
    
    public function reportSellerStatusChange($seller, $reasons = false)
    {
        if (Configuration::get('JMARKETPLACE_SEND_SELLER_ACTIVE')) {
            $id_seller_email = false;
            $to = $seller->email;
            $to_name = $seller->name;
            $from = Configuration::get('PS_SHOP_EMAIL');
            $from_name = Configuration::get('PS_SHOP_NAME');
            
            $template = 'base';
            
            if ($seller->active == 1) {
                $reference = 'seller-activated';
                $id_seller_email = SellerEmail::getIdByReference($reference);
            } else {
                $reference = 'seller-desactivated';
                $id_seller_email = SellerEmail::getIdByReference($reference);
            }

            if ($id_seller_email) {
                $seller_email = new SellerEmail($id_seller_email, $seller->id_lang);
                $vars = array("{shop_name}", "{seller_name}", "{seller_shop}", "{reasons}");
                $values = array(Configuration::get('PS_SHOP_NAME'), $seller->name, $seller->shop, $reasons);
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
    }
}
