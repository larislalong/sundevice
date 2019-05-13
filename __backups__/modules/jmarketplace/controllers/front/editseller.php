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

class JmarketplaceEditsellerModuleFrontController extends ModuleFrontController
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
        $id_seller = Seller::getSellerByCustomer((int)$this->context->cookie->id_customer);
        $seller = new Seller($id_seller);
        
        $params = array('id_seller' => $seller->id);
        Hook::exec('actionMarketplaceBeforeUpdateSeller');
        
        if (Tools::isSubmit('submitEditSeller')) {
            $seller_name = pSQL(Tools::getValue('name'));
            $seller_shop = (string)Tools::getValue('shop');
            $seller_email = Tools::getValue('email');
            
            if (Tools::getValue('id_lang')) {
                $id_lang = (int)Tools::getValue('id_lang');
            } else {
                $id_lang = (int)$this->context->language->id;
            }
            
            if (Seller::existName($seller_name) > 0 && $seller->name != $seller_name) {
                $this->errors[] = $this->module->l('The name of seller already exists in our database.', 'editseller');
            }
            
            if (!isset($seller_name) || $seller_name == '') {
                $this->errors[] = $this->module->l('Invalid seller name.', 'editseller');
            }
            
            if (Seller::existEmail($seller_email) > 0 && $seller->email != $seller_email) {
                $this->errors[] = $this->module->l('The email of seller already exists in our database.', 'editseller');
            }
            
            if (!isset($seller_email) || $seller_email == '' || !Validate::isEmail($seller_email)) {
                $this->errors[] = $this->module->l('Invalid seller email.', 'editseller');
            }
            
            if ($_FILES['sellerImage']['name'] != "") {
                if (!Seller::saveSellerImage($_FILES['sellerImage'], $this->context->cookie->id_customer)) {
                    $this->errors[] = $this->module->l('The image seller format is incorrect.', 'editseller');
                }
            }
            
            $allow_iframe = (int)Configuration::get('PS_ALLOW_HTML_IFRAME');
            
            if (Tools::getValue('description') != '' && !Validate::isCleanHtml(Tools::getValue('description'), $allow_iframe)) {
                $this->errors[] = $this->module->l('Seller description is incorrect.', 'editseller');
            }
            
            if (!count($this->errors)) {
                $seller->name = Tools::stripslashes(trim($seller_name));
                $seller->link_rewrite = Seller::generateLinkRewrite($seller->name);
                $seller->email = pSQL($seller_email);
                $seller->shop = Tools::stripslashes(trim($seller_shop));
                $seller->cif = pSQL(Tools::getValue('cif'));
                $seller->id_lang = $id_lang;
                $seller->phone = pSQL(Tools::getValue('phone'));
                $seller->fax = pSQL(Tools::getValue('fax'));
                $seller->address = pSQL(Tools::getValue('address'));
                $seller->country = pSQL(Tools::getValue('country'));
                $seller->state = pSQL(Tools::getValue('state'));
                $seller->city = pSQL(Tools::getValue('city'));
                $seller->postcode = pSQL(Tools::getValue('postcode'));
                $seller->description = (string)Tools::getValue('description'); //this is content html
                $seller->meta_title = pSQL(Tools::getValue('meta_title'));
                $seller->meta_description = pSQL(Tools::getValue('meta_description'));
                $seller->meta_keywords = pSQL(Tools::getValue('meta_keywords'));
                
                if (Configuration::get('JMARKETPLACE_MODERATE_SELLER')) {
                    $seller->active = 0;
                }
                
                $seller->update();
                
                $params = array('id_seller' => $seller->id);
                Hook::exec('actionMarketplaceAfterUpdateSeller', $params);
                
                if (Configuration::get('JMARKETPLACE_MODERATE_SELLER') || Configuration::get('JMARKETPLACE_SEND_ADMIN_REGISTER')) {
                    $id_seller_email = false;
                    $to = Configuration::get('JMARKETPLACE_SEND_ADMIN');
                    $to_name = Configuration::get('PS_SHOP_NAME');
                    $from = Configuration::get('PS_SHOP_EMAIL');
                    $from_name = Configuration::get('PS_SHOP_NAME');
                    $template = 'base';
                    $reference = 'edit-seller';
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
                
                $this->context->smarty->assign(array('confirmation' => 1));
            } else {
                $this->context->smarty->assign(array('errors' => $this->errors));
            }
        }
    }

    public function initContent()
    {
        parent::initContent();
        
        if (!$this->context->cookie->id_customer) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_EDIT_ACCOUNT') == 0) {
            Tools::redirect($this->context->link->getModuleLink('jmarketplace', 'selleraccount', array(), true));
        }
        
        $is_seller = Seller::isSeller($this->context->cookie->id_customer, $this->context->shop->id);
        $id_seller = Seller::getSellerByCustomer($this->context->cookie->id_customer);

        if (!$is_seller) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
        }
        
        $seller = new Seller($id_seller);
        
        if ($seller->active == 0) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
        }
        
        if (!Configuration::get('JMARKETPLACE_SHOW_EDIT_ACCOUNT')) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
        }

        $params = array('id_seller' => $seller->id, 'link_rewrite' => $seller->link_rewrite);
        $url_seller_profile = $this->module->getJmarketplaceLink('jmarketplace_seller_rule', $params);
        
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
            'show_country' => Configuration::get('JMARKETPLACE_SHOW_COUNTRY'),
            'show_state' => Configuration::get('JMARKETPLACE_SHOW_STATE'),
            'show_city' => Configuration::get('JMARKETPLACE_SHOW_CITY'),
            'show_postcode' => Configuration::get('JMARKETPLACE_SHOW_POSTAL_CODE'),
            'show_description' => Configuration::get('JMARKETPLACE_SHOW_DESCRIPTION'),
            'show_meta_title' => Configuration::get('JMARKETPLACE_SHOW_MTA_TITLE'),
            'show_meta_description' => Configuration::get('JMARKETPLACE_SHOW_MTA_DESCRIPTION'),
            'show_meta_keywords' => Configuration::get('JMARKETPLACE_SHOW_MTA_KEYWORDS'),
            'show_logo' => Configuration::get('JMARKETPLACE_SHOW_LOGO'),
            'moderate' => Configuration::get('JMARKETPLACE_MODERATE_SELLER'),
            'show_import_product' => Configuration::get('JMARKETPLACE_SELLER_IMPORT_PROD'),
            'show_orders' => Configuration::get('JMARKETPLACE_SHOW_ORDERS'),
            'show_edit_seller_account' => Configuration::get('JMARKETPLACE_SHOW_EDIT_ACCOUNT'),
            'show_contact' => Configuration::get('JMARKETPLACE_SHOW_CONTACT'),
            'show_manage_orders' => Configuration::get('JMARKETPLACE_SHOW_MANAGE_ORDERS'),
            'show_manage_carriers' => Configuration::get('JMARKETPLACE_SHOW_MANAGE_CARRIER'),
            'show_dashboard' => Configuration::get('JMARKETPLACE_SHOW_DASHBOARD'),
            'show_seller_invoice' => Configuration::get('JMARKETPLACE_SHOW_SELLER_INVOICE'),
            'show_menu_top' => Configuration::get('JMARKETPLACE_MENU_TOP'),
            'show_menu_options' => Configuration::get('JMARKETPLACE_MENU_OPTIONS'),
            'seller' => $seller,
            'seller_link' => $url_seller_profile,
            'languages' => Language::getLanguages(),
            'id_module' => Module::getModuleIdByName('jmarketplace')
        ));
        
        if (file_exists(_PS_IMG_DIR_.'sellers/'.$this->context->cookie->id_customer.'.jpg')) {
            $this->context->smarty->assign(array('photo' => $this->context->cookie->id_customer.'.jpg'));
        }

        $this->setTemplate('editseller.tpl');
    }
}
