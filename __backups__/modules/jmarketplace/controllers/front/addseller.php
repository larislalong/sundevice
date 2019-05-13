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

class JmarketplaceAddsellerModuleFrontController extends ModuleFrontController
{
    public $ssl = true;
    
    public function setMedia()
    {
        parent::setMedia();
        
        $this->context->controller->addJqueryPlugin('fancybox');
        
        if (Configuration::get('PS_JS_THEME_CACHE') == 0) {
            $this->context->controller->addJS(_MODULE_DIR_.$this->module->name.'/views/js/tinymce/tinymce.min.js');
            //$this->context->controller->addJS('https://cdn.tinymce.com/4/tinymce.min.js');

            $iso = Language::getIsoById($this->context->language->id);

            switch ($iso) {
                case 'de':
                    $this->context->controller->addJS(_MODULE_DIR_.$this->module->name.'/views/js/tinymce/langs/de.js', 'all');
                    break;
                case 'es':
                    $this->context->controller->addJS(_MODULE_DIR_.$this->module->name.'/views/js/tinymce/langs/es.js', 'all');
                    break;
                case 'ar':
                    $this->context->controller->addJS(_MODULE_DIR_.$this->module->name.'/views/js/tinymce/langs/es_AR.js', 'all');
                    break;
                case 'mx':
                    $this->context->controller->addJS(_MODULE_DIR_.$this->module->name.'/views/js/tinymce/langs/es_MX.js', 'all');
                    break;
                case 'fr':
                    $this->context->controller->addJS(_MODULE_DIR_.$this->module->name.'/views/js/tinymce/langs/fr_FR.js', 'all');
                    break;
                case 'it':
                    $this->context->controller->addJS(_MODULE_DIR_.$this->module->name.'/views/js/tinymce/langs/it.js', 'all');
                    break;
                case 'nl':
                    $this->context->controller->addJS(_MODULE_DIR_.$this->module->name.'/views/js/tinymce/langs/nl.js', 'all');
                    break;
                case 'pl':
                    $this->context->controller->addJS(_MODULE_DIR_.$this->module->name.'/views/js/tinymce/langs/pl.js', 'all');
                    break;
                case 'br':
                    $this->context->controller->addJS(_MODULE_DIR_.$this->module->name.'/views/js/tinymce/langs/pt_BR.js', 'all');
                    break;
                case 'pt':
                    $this->context->controller->addJS(_MODULE_DIR_.$this->module->name.'/views/js/tinymce/langs/pt_PT.js', 'all');
                    break;
                case 'ro':
                    $this->context->controller->addJS(_MODULE_DIR_.$this->module->name.'/views/js/tinymce/langs/ro.js', 'all');
                    break;
                case 'ru':
                    $this->context->controller->addJS(_MODULE_DIR_.$this->module->name.'/views/js/tinymce/langs/ru.js', 'all');
                    break;
                default:
                    $this->context->controller->addJS(_MODULE_DIR_.$this->module->name.'/views/js/tinymce/langs/en_GB.js', 'all');
                    break;
            }
            
            $this->context->controller->addJS(_MODULE_DIR_.$this->module->name.'/views/js/calltinymce.js', 'all');
        }
        
        $this->context->controller->addJS(_MODULE_DIR_.$this->module->name.'/views/js/addseller.js', 'all');
    }
    
    public function postProcess()
    {
        Hook::exec('actionMarketplaceBeforeAddSeller');
        
        $is_seller = Seller::isSeller($this->context->cookie->id_customer, $this->context->shop->id);
        
        if (Tools::isSubmit('submitAddSeller') && !$is_seller) {
            $seller_name = (string)Tools::getValue('name');
            $seller_shop = (string)Tools::getValue('shop');
            $seller_email = Tools::getValue('email');
            
            if (Tools::getValue('id_lang')) {
                $id_lang = (int)Tools::getValue('id_lang');
            } else {
                $id_lang = $this->context->language->id;
            }
            
            if (!Tools::getValue('conditions') && Configuration::get('JMARKETPLACE_SHOW_TERMS') == 1) {
                $this->errors[] = $this->module->l('You must agree to the terms of service before continuing.', 'addseller');
            }
            
            if (Seller::existName($seller_name) > 0) {
                $this->errors[] = $this->module->l('The name of seller already exists in our database.', 'addseller');
            }
            
            if (!isset($seller_name) || $seller_name == '') {
                $this->errors[] = $this->module->l('Invalid seller name.', 'addseller');
            }
            
            if (Seller::existEmail($seller_email) > 0) {
                $this->errors[] = $this->module->l('The email of seller already exists in our database.', 'addseller');
            }
            
            if (!isset($seller_email) || $seller_email == '' || !Validate::isEmail($seller_email)) {
                $this->errors[] = $this->module->l('Invalid seller email.', 'addseller');
            }
            
            if ($_FILES['sellerImage']['name'] != "") {
                if (!Seller::saveSellerImage($_FILES['sellerImage'], (int)$this->context->cookie->id_customer)) {
                    $this->errors[] = $this->module->l('The image seller format is incorrect.', 'addseller');
                }
            }
            
            $allow_iframe = (int)Configuration::get('PS_ALLOW_HTML_IFRAME');
            
            if (Tools::getValue('description') != '' && !Validate::isCleanHtml(Tools::getValue('description'), $allow_iframe)) {
                $this->errors[] = $this->module->l('Seller description is incorrect.', 'addseller');
            }
            
            if (!count($this->errors)) {
                $seller = new Seller();
                $seller->id_customer = (int)$this->context->cookie->id_customer;
                $seller->id_shop = (int)$this->context->shop->id;
                $seller->id_lang = $id_lang;
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
                
                if (Configuration::get('JMARKETPLACE_MODERATE_SELLER')) {
                    $seller->active = 0;
                } else {
                    $seller->active = 1;
                }
                
                $seller->add();
                
                if (Configuration::get('JMARKETPLACE_MODERATE_SELLER') || Configuration::get('JMARKETPLACE_SEND_ADMIN_REGISTER')) {
                    $id_seller_email = false;
                    $to = Configuration::get('JMARKETPLACE_SEND_ADMIN');
                    $to_name = Configuration::get('PS_SHOP_NAME');
                    $from = Configuration::get('PS_SHOP_EMAIL');
                    $from_name = Configuration::get('PS_SHOP_NAME');
                    $template = 'base';
                    $reference = 'new-seller';
                    $id_seller_email = SellerEmail::getIdByReference($reference);
                    
                    if ($id_seller_email) {
                        $seller_email = new SellerEmail($id_seller_email, Configuration::get('PS_LANG_DEFAULT'));
                        $vars = array("{shop_name}", "{seller_name}", "{seller_shop}");
                        $values = array(Configuration::get('PS_SHOP_NAME'), $seller->name, $seller->shop);
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
                
                if (Configuration::get('JMARKETPLACE_SEND_SELLER_WELCOME')) {
                    $id_seller_email = false;
                    $to = $seller->email;
                    $to_name = $seller->name;
                    $from = Configuration::get('JMARKETPLACE_SEND_ADMIN');
                    $from_name = Configuration::get('PS_SHOP_NAME');
                    $template = 'base';
                    $reference = 'welcome-seller';
                    $id_seller_email = SellerEmail::getIdByReference($reference);
                    
                    if ($id_seller_email) {
                        $seller_email = new SellerEmail($id_seller_email, $id_lang);
                        $vars = array("{shop_name}", "{seller_name}");
                        $values = array(Configuration::get('PS_SHOP_NAME'), $seller->name);
                        $subject_var = $seller_email->subject;
                        $subject_value = str_replace($vars, $values, $subject_var);
                        $content_var = $seller_email->content;
                        $content_value = str_replace($vars, $values, $content_var);

                        $template_vars = array(
                            '{content}' => $content_value,
                            '{shop_name}' => Configuration::get('PS_SHOP_NAME')
                        );

                        $iso = Language::getIsoById($id_lang);

                        if (file_exists(dirname(__FILE__).'/../../mails/'.$iso.'/'.$template.'.txt') && file_exists(dirname(__FILE__).'/../../mails/'.$iso.'/'.$template.'.html')) {
                            Mail::Send(
                                $id_lang,
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
                
                $params = array('id_seller' => $seller->id);
                Hook::exec('actionMarketplaceAfterAddSeller', $params);

                Tools::redirect($this->context->link->getPageLink('my-account', true));
            } else {
                $this->context->smarty->assign(array(
                    'errors' => $this->errors,
                    'customer_name' => Tools::getValue('name'),
                    'seller_shop' => Tools::getValue('shop'),
                    'cif' => Tools::getValue('cif'),
                    'customer_email' => Tools::getValue('email'),
                    'id_lang' => Tools::getValue('id_lang'),
                    'phone' => Tools::getValue('phone'),
                    'fax' => Tools::getValue('fax'),
                    'address' => Tools::getValue('address'),
                    'country_name' => Tools::getValue('country'),
                    'state' => Tools::getValue('state'),
                    'postcode' => Tools::getValue('postcode'),
                    'city' => Tools::getValue('city'),
                    'description' => Tools::getValue('description'),
                ));
            }
        }
    }

    public function initContent()
    {
        parent::initContent();
        
        if (!$this->context->cookie->id_customer) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
        }
        
        $is_seller = Seller::isSeller($this->context->cookie->id_customer, $this->context->shop->id);
        
        if ($is_seller) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
        }
        
        $customer = new Customer($this->context->cookie->id_customer);
        
        if (Configuration::get('JMARKETPLACE_SHOW_COUNTRY')) {
            $countries = Country::getCountries($this->context->language->id, true);
            $this->context->smarty->assign('countries', $countries);
        }
        
        $this->context->smarty->assign(array(
            'show_shop_name' => Configuration::get('JMARKETPLACE_SHOW_SHOP_NAME'),
            'show_cif' => Configuration::get('JMARKETPLACE_SHOW_CIF'),
            'show_language' => Configuration::get('JMARKETPLACE_SHOW_LANGUAGE'),
            'show_phone' => Configuration::get('JMARKETPLACE_SHOW_PHONE'),
            'show_fax' => Configuration::get('JMARKETPLACE_SHOW_FAX'),
            'show_address' => Configuration::get('JMARKETPLACE_SHOW_ADDRESS'),
            'show_country' => Configuration::get('JMARKETPLACE_SHOW_COUNTRY'),
            'show_state' => Configuration::get('JMARKETPLACE_SHOW_STATE'),
            'show_city' => Configuration::get('JMARKETPLACE_SHOW_CITY'),
            'show_postcode' => Configuration::get('JMARKETPLACE_SHOW_POSTAL_CODE'),
            'show_description' => Configuration::get('JMARKETPLACE_SHOW_DESCRIPTION'),
            'show_logo' => Configuration::get('JMARKETPLACE_SHOW_LOGO'),
            'show_terms' => Configuration::get('JMARKETPLACE_SHOW_TERMS'),
            'moderate' => Configuration::get('JMARKETPLACE_MODERATE_SELLER'),
            'show_orders' => Configuration::get('JMARKETPLACE_SHOW_ORDERS'),
            'show_edit_seller_account' => Configuration::get('JMARKETPLACE_SHOW_EDIT_ACCOUNT'),
            'show_contact' => Configuration::get('JMARKETPLACE_SHOW_CONTACT'),
            'customer_name' => $customer->firstname.' '.$customer->lastname,
            'customer_email' => $customer->email,
            'id_lang' => $this->context->language->id,
            'languages' => Language::getLanguages(),
            'id_module' => Module::getModuleIdByName('jmarketplace')
        ));
        
        if (Configuration::get('JMARKETPLACE_SHOW_TERMS') == 1) {
            $cms = new CMS(Configuration::get('JMARKETPLACE_CMS_TERMS'), $this->context->language->id);
            $cms_link = $this->context->link->getCMSLink($cms, $cms->link_rewrite, Configuration::get('PS_SSL_ENABLED'));
            
            if (!strpos($cms_link, '?')) {
                $cms_link .= '?content_only=1';
            } else {
                $cms_link .= '&content_only=1';
            }
            
            $this->context->smarty->assign(array(
                'cms_name' => $cms->meta_title,
                'cms_link' => $cms_link,
            ));
        }
        
        $this->setTemplate('addseller.tpl');
    }
}
