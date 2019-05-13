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

class JmarketplaceAddproductModuleFrontController extends ModuleFrontController
{
    public $ssl = true;
    public $categoryTree;
    public $exclude;
    
    public function setMedia()
    {
        parent::setMedia();
        $this->context->controller->addJqueryUI('ui.datepicker');
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

        $this->context->controller->addJS(_MODULE_DIR_.$this->module->name.'/views/js/addproduct.js', 'all');
        $this->context->controller->addJS(_MODULE_DIR_.$this->module->name.'/views/js/attributes.js', 'all');
    }
    
    protected function ajaxProcessSearchCategory()
    {
        $key = Tools::getValue('key');
        $search_data = '';
        
        $result_search = Db::getInstance()->ExecuteS(
            'SELECT c.id_category, cl.name
            FROM '._DB_PREFIX_.'category c
            LEFT JOIN '._DB_PREFIX_.'category_lang cl ON (cl.id_category = c.id_category AND cl.id_lang = '.(int)$this->context->language->id.')
            LEFT JOIN '._DB_PREFIX_.'seller_category sc ON (sc.id_category = c.id_category)
            WHERE cl.name LIKE "%'.pSQL($key).'%" AND c.active = 1 AND sc.id_category != ""'
        );
        
        if ($result_search) {
            foreach ($result_search as $category) {
                $search_data .= '<div class="suggest-element" id="category_'.$category['id_category'].'" data="'.$category['id_category'].'">';
                $search_data .= $category['name'];
                $search_data .= '</div>';
            }
        }
        
        die($search_data);
    }
    
    public function postProcess()
    {
        $languages = Language::getLanguages();
        $id_lang = (int)$this->context->language->id;
        $language = new Language($id_lang);
        
        $id_seller = Seller::getSellerByCustomer((int)$this->context->cookie->id_customer);
        $seller = new Seller($id_seller);
        
        $params = array('id_seller' => $seller->id);
        
        Hook::exec('actionMarketplaceBeforeAddProduct', $params);
        
        if (Tools::isSubmit('submitAddProduct')) {
            $url_images = array();
            $name = pSQL(Tools::getValue('name_'.$id_lang));
            $reference = pSQL(Tools::getValue('reference'));
            $isbn = pSQL(Tools::getValue('isbn'));
            $ean13 = pSQL(Tools::getValue('ean13'));
            $upc = pSQL(Tools::getValue('upc'));
            $width = (float)Tools::getValue('width');
            $height = (float)Tools::getValue('height');
            $depth = (float)Tools::getValue('depth');
            $weight = (float)Tools::getValue('weight');
            $quantity = (int)Tools::getValue('quantity');
            $minimal_quantity = (int)Tools::getValue('minimal_quantity');
            $available_now = pSQL(Tools::getValue('available_now'));
            $available_later = pSQL(Tools::getValue('available_later'));
            $available_date = pSQL(Tools::getValue('available_date'));
            $price = (float)Tools::getValue('price');
            $wholesale_price = (float)Tools::getValue('wholesale_price');
            $specific_price = (float)Tools::getValue('specific_price');
            $unit_price = (float)Tools::getValue('unit_price');
            $categories = Tools::getValue('categories');
            $new_manufacturer = pSQL(Tools::getValue('new_manufacturer'));
            $new_supplier = pSQL(Tools::getValue('new_supplier'));
            
            if ($name == '' || !Validate::isCatalogName($name)) {
                $this->errors[] = $this->module->l('Name product is incorrect.', 'addproduct');
            }
            
            if ($reference != '' && !Validate::isReference($reference)) {
                $this->errors[] = $this->module->l('Reference is incorrect.', 'addproduct');
            }
            
            if (Tools::strlen($reference) > 32) {
                $this->errors[] = $this->module->l('The reference must not exceed 30 characters.', 'addproduct');
            }
            
            $allow_iframe = (int)Configuration::get('PS_ALLOW_HTML_IFRAME');
            
            if (Tools::getValue('short_description_'.$this->context->language->id) != '' && !Validate::isCleanHtml(Tools::getValue('short_description_'.$this->context->language->id), $allow_iframe)) {
                $this->errors[] = $this->module->l('Short description is incorrect.', 'addproduct');
            }
            
            if (Tools::getValue('description_'.$this->context->language->id) != '' && !Validate::isCleanHtml(Tools::getValue('description_'.$this->context->language->id), $allow_iframe)) {
                $this->errors[] = $this->module->l('Description is incorrect.', 'addproduct');
            }
            
            if ($isbn != '' && !Validate::isIsbn($isbn)) {
                $this->errors[] = $this->module->l('ISBN is incorrect.', 'addproduct');
            }
            
            if ($ean13 != '' && !Validate::isEan13($ean13)) {
                $this->errors[] = $this->module->l('EAN13 is incorrect.', 'addproduct');
            }
            
            if ($upc != '' && !Validate::isUPC($upc)) {
                $this->errors[] = $this->module->l('UPC is incorrect.', 'addproduct');
            }
            
            if ($width != '' && !Validate::isFloat($width)) {
                $this->errors[] = $this->module->l('Width is incorrect.', 'addproduct');
            }
            
            if ($height != '' && !Validate::isFloat($height)) {
                $this->errors[] = $this->module->l('Height is incorrect.', 'addproduct');
            }
            
            if ($depth != '' && !Validate::isFloat($depth)) {
                $this->errors[] = $this->module->l('Depth is incorrect.', 'addproduct');
            }
            
            if ($weight != '' && !Validate::isFloat($weight)) {
                $this->errors[] = $this->module->l('Weight is incorrect.', 'addproduct');
            }
            
            if ($price < 0 || !Validate::isPrice($price)) {
                $this->errors[] = $this->module->l('Price is incorrect.', 'addproduct');
            }
            
            if (Configuration::get('JMARKETPLACE_SHOW_WHOLESALEPRICE') == 1) {
                if ($wholesale_price < 0 || !Validate::isPrice($wholesale_price)) {
                    $this->errors[] = $this->module->l('Wholesale price is incorrect.', 'addproduct');
                }
            }
            
            if ($specific_price != 0 && !Validate::isPrice($specific_price)) {
                $this->errors[] = $this->module->l('Offer price is incorrect.', 'addproduct');
            }
            
            if ($specific_price != 0 && $specific_price > $price) {
                $this->errors[] = $this->module->l('Offer price is bigger than price.', 'addproduct');
            }
            
            if (Configuration::get('JMARKETPLACE_SHOW_UNIT_PRICE') == 1) {
                if ($unit_price < 0 || !Validate::isPrice($unit_price)) {
                    $this->errors[] = $this->module->l('Unit price is incorrect.', 'addproduct');
                }
            }
            
            if ($quantity != '' && !Validate::isInt($quantity)) {
                $this->errors[] = $this->module->l('Quantity is incorrect.', 'addproduct');
            }
            
            if (($minimal_quantity != '' && !Validate::isInt($minimal_quantity)) || ($minimal_quantity != '' && $minimal_quantity < 1)) {
                $this->errors[] = $this->module->l('Minimal quantity is incorrect.', 'addproduct');
            }
            
            if (Configuration::get('JMARKETPLACE_SHOW_CATEGORIES') == 1 && !is_array($categories)) {
                $this->errors[] = $this->module->l('You must select at least one category.', 'addproduct');
            }
            
            if (Configuration::get('JMARKETPLACE_SHOW_ATTRIBUTES') == 1) {
                if (Tools::getValue('combination_price') > 0) {
                    foreach (Tools::getValue('combination_price') as $combination_price) {
                        if (!Validate::isFloat($combination_price)) {
                            $this->errors[] = $this->module->l('Combination price is incorrect.', 'addproduct');
                        }
                    }
                }
                
                if (Tools::getValue('combination_weight')) {
                    foreach (Tools::getValue('combination_weight') as $combination_weight) {
                        if (!Validate::isFloat($combination_weight)) {
                            $this->errors[] = $this->module->l('Combination weight is incorrect.', 'addproduct');
                        }
                    }
                }
                
                if (Tools::getValue('combination_qty')) {
                    foreach (Tools::getValue('combination_qty') as $combination_qty) {
                        if (!Validate::isInt($combination_qty)) {
                            $this->errors[] = $this->module->l('Combination quantity is incorrect.', 'addproduct');
                        }
                    }
                }
            }
            
            if (Configuration::get('JMARKETPLACE_SHOW_IMAGES') == 1) {
                $images = count($_FILES['images']['name']);
                if ($images <= Configuration::get('JMARKETPLACE_MAX_IMAGES')) {
                    for ($i=1; $i<=Configuration::get('JMARKETPLACE_MAX_IMAGES'); $i++) {
                        if ($_FILES['images']['name'][$i] != "") {
                            if ((($_FILES['images']['type'][$i] == "image/pjpeg") ||
                                ($_FILES['images']['type'][$i] == "image/jpeg") ||
                                ($_FILES['images']['type'][$i] == "image/png")) &&
                                (($_FILES['images']['size'][$i] / 1024 /1024) < Configuration::get('PS_LIMIT_UPLOAD_IMAGE_VALUE'))) {
                                $url_images[$i] = $_FILES['images']['tmp_name'][$i];
                            } else {
                                $this->errors[] = $this->module->l('The image format is incorrect or max size to upload is', 'addproduct').' '.ini_get('post_max_size');
                            }
                        } else {
                            $url_images[$i] = '';
                        }
                    }
                } else {
                    $this->errors[] = $this->module->l('The maxim images to upload is', 'addproduct').' '.Configuration::get('JMARKETPLACE_MAX_IMAGES');
                }
            }
            
            if ($new_manufacturer != '' && !Validate::isCatalogName($new_manufacturer)) {
                $this->errors[] = $this->module->l('Name manufacturer is incorrect.', 'addproduct');
            }
            
            if ($new_supplier != '' && !Validate::isCatalogName($new_supplier)) {
                $this->errors[] = $this->module->l('Name supplier is incorrect.', 'addproduct');
            }
            
            if (Tools::getValue('type_product') == 2) {
                $virtual_product_nb_downloable = Tools::getValue('virtual_product_nb_downloable');
                $virtual_product_expiration_date = Tools::getValue('virtual_product_expiration_date');
                $virtual_product_nb_days = Tools::getValue('virtual_product_nb_days');
                
                if ($virtual_product_nb_downloable != '' && !Validate::isInt($virtual_product_nb_downloable)) {
                    $this->errors[] = $this->module->l('Virtual product number downloable is incorrect.', 'addproduct');
                }
                
                if ($virtual_product_expiration_date != '' && !Validate::isDate($virtual_product_expiration_date)) {
                    $this->errors[] = $this->module->l('Virtual product expiration date is incorrect.', 'addproduct');
                }
                
                if ($virtual_product_nb_days != '' && !Validate::isInt($virtual_product_nb_days)) {
                    $this->errors[] = $this->module->l('Virtual product number days is incorrect.', 'addproduct');
                }
            }
            
            if (Configuration::get('JMARKETPLACE_SHOW_ATTACHMENTS') == 1) {
                $num_attachments = count($_FILES['attachments']['name']);
                if ($num_attachments <= Configuration::get('JMARKETPLACE_MAX_ATTACHMENTS')) {
                    for ($i=1; $i<=Configuration::get('JMARKETPLACE_MAX_ATTACHMENTS'); $i++) {
                        if (!Validate::isGenericName(Tools::getValue('attachment_name_'.$i.'_'.$id_lang))) {
                            $this->errors[] = $this->module->l('Invalid attachment name for language ', 'addproduct').$language->name;
                        }

                        if (Tools::strlen(Tools::getValue('attachment_name_'.$i.'_'.$id_lang)) > 32) {
                            $this->errors[] = $this->module->l('The attachment name is too long for language ', 'addproduct').$language->name;
                        }

                        if (!Validate::isCleanHtml(Tools::getValue('attachment_description_'.$i.'_'.$id_lang))) {
                            $this->errors[] = $this->module->l('Invalid attachment description for language ', 'addproduct').$language->name;
                        }
                        
                        if ($_FILES['attachments']['name'][$i] != "") {
                            if ($_FILES['attachments']['size'][$i] > (Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE') * 1024 * 1024)) {
                                $this->errors[] = $this->module->l('The file is too large. Maximum size allowed is ', 'addproduct').(Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE') * 1024);
                            }
                            
                            if ($_FILES['attachments']['type'][$i] > 128) {
                                $this->errors[] = $this->module->l('Invalid file extension for attachment ', 'addproduct').$_FILES['attachments']['name'][$i];
                            }
                            
                            if (!Validate::isGenericName($_FILES['attachments']['name'][$i])) {
                                $this->errors[] = $this->module->l('Invalid file name for ', 'addproduct').$_FILES['attachments']['name'][$i];
                            }
                            
                            if (Tools::strlen($_FILES['attachments']['name'][$i]) > 128) {
                                $this->errors[] = $this->module->l('The file name is too long for ', 'addproduct').$_FILES['attachments']['name'][$i];
                            }
                        }
                    }
                }
            }
            
            if (count($this->errors) > 0) {
                $name = array();
                $short_description = array();
                $description = array();
                $meta_keywords2 = array();
                $meta_title2 = array();
                $meta_description2 = array();
                $link_rewrite2 = array();
                foreach ($languages as $language) {
                    $name[$language['id_lang']] = Tools::getValue('name_'.$language['id_lang']);
                    $short_description[$language['id_lang']] = Tools::getValue('short_description_'.$language['id_lang']);
                    $description[$language['id_lang']] = Tools::getValue('description_'.$language['id_lang']);
                    $meta_keywords2[$language['id_lang']] = Tools::getValue('meta_keywords_'.$language['id_lang']);
                    $meta_title2[$language['id_lang']] = Tools::getValue('meta_title_'.$language['id_lang']);
                    $meta_description2[$language['id_lang']] = Tools::getValue('meta_description_'.$language['id_lang']);
                    $link_rewrite2[$language['id_lang']] = Tools::getValue('link_rewrite_'.$language['id_lang']);
                    
                    if (Tools::getValue('available_now_'.$language['id_lang'])) {
                        $available_now[$language['id_lang']] = Tools::getValue('available_now_'.$language['id_lang']);
                    }
                    
                    if (Tools::getValue('available_later_'.$language['id_lang'])) {
                        $available_later[$language['id_lang']] = Tools::getValue('available_later_'.$language['id_lang']);
                    }
                }
                
                $this->context->smarty->assign(array(
                    'errors' => $this->errors,
                    'type_product' => Tools::getValue('type_product'),
                    'name' => $name,
                    'reference' => $reference,
                    'isbn' => $isbn,
                    'ean13' => $ean13,
                    'upc' => $upc,
                    'width' => $width,
                    'height' => $height,
                    'depth' => $depth,
                    'weight' => $weight,
                    'additional_shipping_cost' => Tools::getValue('additional_shipping_cost'),
                    'available_for_order' => Tools::getValue('available_for_order'),
                    'show_product_price' => Tools::getValue('show_product_price'),
                    'online_only' => Tools::getValue('online_only'),
                    'condition' => Tools::getValue('condition'),
                    'show_product_condition' => Tools::getValue('show_product_condition'),
                    'quantity' => $quantity,
                    'minimal_quantity' => $minimal_quantity,
                    'out_of_stock' => Tools::getValue('out_of_stock'),
                    'available_now' => $available_now,
                    'available_later' => $available_later,
                    'available_date' => $available_date,
                    'price' => $price,
                    'unit_price' => $unit_price,
                    'unity' => Tools::getValue('unity'),
                    'wholesale_price' => $wholesale_price,
                    'specific_price' => $specific_price,
                    'commission' => Tools::getValue('commission'),
                    'id_tax' => Tools::getValue('id_tax'),
                    'on_sale' => Tools::getValue('on_sale'),
                    'short_description' => $short_description,
                    'description' => $description,
                    'meta_keywords2' => $meta_keywords2,
                    'meta_title2' => $meta_title2,
                    'meta_description2' => $meta_description2,
                    'link_rewrite2' => $link_rewrite2,
                    'id_manufacturer' => Tools::getValue('id_manufacturer'),
                    'id_supplier' => Tools::getValue('id_supplier'),
                ));
                
                if (Tools::getValue('type_product') == 2) {
                    $this->context->smarty->assign(array(
                        'virtual_product_nb_downloable' => $virtual_product_nb_downloable,
                        'virtual_product_expiration_date' => $virtual_product_expiration_date,
                        'virtual_product_nb_days' => $virtual_product_nb_days,
                    ));
                }
            } else {
                $id_product = SellerProduct::import($_POST, $_FILES, $url_images, $id_lang);
                SellerProduct::associateSellerProduct($id_seller, $id_product);

                $params = array('id_seller' => $seller->id, 'id_product' => $id_product);
                Hook::exec('actionMarketplaceAfterAddProduct', $params);

                if (Configuration::get('JMARKETPLACE_MODERATE_PRODUCT') || Configuration::get('JMARKETPLACE_SEND_ADMIN_PRODUCT')) {
                    $id_seller_email = false;
                    $to = Configuration::get('JMARKETPLACE_SEND_ADMIN');
                    $to_name = Configuration::get('PS_SHOP_NAME');
                    $from = Configuration::get('PS_SHOP_EMAIL');
                    $from_name = Configuration::get('PS_SHOP_NAME');
                    $template = 'base';
                    $reference = 'new-product';
                    $id_seller_email = SellerEmail::getIdByReference($reference);
                    
                    if ($id_seller_email) {
                        $seller_email = new SellerEmail($id_seller_email, Configuration::get('PS_LANG_DEFAULT'));
                        $product = new Product($id_product, false, Configuration::get('PS_LANG_DEFAULT'), $this->context->shop->id);
                        $vars = array("{shop_name}", "{seller_name}", "{product_name}");
                        $values = array(Configuration::get('PS_SHOP_NAME'), $seller->name, $product->name);
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
                
                Tools::redirect($this->context->link->getModuleLink('jmarketplace', 'sellerproducts', array('confirmation' => 1), true));
            }
        }
    }

    public function initContent()
    {
        parent::initContent();
        
        if (Tools::isSubmit('action')) {
            switch (Tools::getValue('action')) {
                case 'search_category':
                    $this->ajaxProcessSearchCategory();
                    break;
            }
        }
        
        $languages = Language::getLanguages();
        $id_lang = (int)$this->context->language->id;
        
        if (!$this->context->cookie->id_customer) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
        }
        
        $is_seller = Seller::isSeller($this->context->cookie->id_customer, $this->context->shop->id);
        if (!$is_seller) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
        }
        
        $id_seller = Seller::getSellerByCustomer($this->context->cookie->id_customer);
        $seller = new Seller($id_seller);
        
        if ($seller->active == 0) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
        }
        
        $params = array('id_seller' => $seller->id, 'link_rewrite' => $seller->link_rewrite);
        $url_seller_profile = $this->module->getJmarketplaceLink('jmarketplace_seller_rule', $params);
        
        if (Configuration::get('JMARKETPLACE_SHOW_IMAGES') == 1) {
            $imageDimensions = SellerProduct::getImageDimensions();
            $dimensions = $imageDimensions['width'].'x'.$imageDimensions['height'].'px';
            $this->context->smarty->assign(array(
                'max_images' => Configuration::get('JMARKETPLACE_MAX_IMAGES'),
                'max_dimensions' => $dimensions,
            ));
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_TAX') == 1) {
            $tax_rule_groups = TaxRulesGroup::getTaxRulesGroups(true);
            
            if (is_array($tax_rule_groups) && count($tax_rule_groups) > 0) {
                foreach ($tax_rule_groups as $key => $value) {
                    $tax_rules = TaxRule::getTaxRulesByGroupId($this->context->language->id, $value['id_tax_rules_group']);
                    if (is_array($tax_rules) && count($tax_rules) > 0) {
                        foreach ($tax_rules as $tr) {
                            if ($this->context->cart->id_address_delivery != 0) {
                                $seller_address = new Address($this->context->cart->id_address_delivery);
                                if ($seller_address->id_country == $tr['id_country']) {
                                    $tax_rule_groups[$key]['rate'] = $tr['rate'];
                                }
                            } else {
                                if (Configuration::get('PS_COUNTRY_DEFAULT') == $tr['id_country']) {
                                    $tax_rule_groups[$key]['rate'] = $tr['rate'];
                                }
                            }
                        }
                    }
                }
            }
            
            $this->context->smarty->assign('taxes', $tax_rule_groups);
        }

        if (Configuration::get('JMARKETPLACE_SHOW_SHIP_PRODUCT') == 1) {
            if (Configuration::get('JMARKETPLACE_SHOW_MANAGE_CARRIER') == 1) {
                $carriers = SellerTransport::getCarriers($this->context->language->id, true, $id_seller);
            } else {
                $carriers = Carrier::getCarriers($this->context->language->id, true, false, false, null, 5);
            }
            
            $this->context->smarty->assign('carriers', $carriers);
        }

        if (Configuration::get('JMARKETPLACE_SHOW_CATEGORIES') == 1) {
            $categories = Category::getNestedCategories(null, $this->context->language->id);
            $categoryTree = '<ul id="tree1">'.CategoryTree::generateCheckboxesCategories($categories).'</ul>';
            $this->context->smarty->assign('categoryTree', $categoryTree);
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_SUPPLIERS') == 1) {
            $this->context->smarty->assign('suppliers', Supplier::getSuppliers());
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_MANUFACTURERS') == 1) {
            $this->context->smarty->assign('manufacturers', Manufacturer::getManufacturers());
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_FEATURES') == 1) {
            $features = Feature::getFeatures($this->context->language->id, (Shop::isFeatureActive() && Shop::getContext() == Shop::CONTEXT_SHOP));

            foreach ($features as $k => $tab_features) {
                $features[$k]['current_item'] = false;
                $features[$k]['val'] = array();

                $custom = true;

                $features[$k]['featureValues'] = FeatureValue::getFeatureValuesWithLang($this->context->language->id, (int)$tab_features['id_feature']);
                if (count($features[$k]['featureValues'])) {
                    foreach ($features[$k]['featureValues'] as $value) {
                        if ($features[$k]['current_item'] == $value['id_feature_value']) {
                            $custom = false;
                        }
                    }
                }

                if ($custom) {
                    $features[$k]['val'] = FeatureValue::getFeatureValueLang($features[$k]['current_item']);
                }
            }

            $this->context->smarty->assign('features', $features);
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_ATTRIBUTES') == 1) {
            $attribute_groups = AttributeGroup::getAttributesGroups($this->context->language->id);
            if (count($attribute_groups) > 0) {
                $counter = 0;
                foreach ($attribute_groups as $ag) {
                    $attribute_groups[$counter]['options'] = AttributeGroup::getAttributes($this->context->language->id, $ag['id_attribute_group']);
                    $counter++;
                }
            }
            
            $this->context->smarty->assign(array(
                'attribute_groups' => $attribute_groups,
                'first_options' => AttributeGroup::getAttributes($this->context->language->id, $attribute_groups[0]['id_attribute_group']),
            ));
        }
        
        if (Configuration::get('PS_SSL_ENABLED') == 1) {
            $url_shop = Tools::getShopDomainSsl(true).__PS_BASE_URI__;
        } else {
            $url_shop = Tools::getShopDomain(true).__PS_BASE_URI__;
        }

        $this->context->smarty->assign(array(
            'show_reference' => Configuration::get('JMARKETPLACE_SHOW_REFERENCE'),
            'show_isbn' => Configuration::get('JMARKETPLACE_SHOW_ISBN'),
            'show_ean13' => Configuration::get('JMARKETPLACE_SHOW_EAN13'),
            'show_upc' => Configuration::get('JMARKETPLACE_SHOW_UPC'),
            'show_width' => Configuration::get('JMARKETPLACE_SHOW_WIDTH'),
            'show_height' => Configuration::get('JMARKETPLACE_SHOW_HEIGHT'),
            'show_depth' => Configuration::get('JMARKETPLACE_SHOW_DEPTH'),
            'show_weight' => Configuration::get('JMARKETPLACE_SHOW_WEIGHT'),
            'show_shipping_product' => Configuration::get('JMARKETPLACE_SHOW_SHIP_PRODUCT'),
            'show_available_order' => Configuration::get('JMARKETPLACE_SHOW_AVAILABLE_ORD'),
            'show_show_price' => Configuration::get('JMARKETPLACE_SHOW_SHOW_PRICE'),
            'show_online_only' => Configuration::get('JMARKETPLACE_SHOW_ONLINE_ONLY'),
            'show_condition' => Configuration::get('JMARKETPLACE_SHOW_CONDITION'),
            'show_pcondition' => Configuration::get('JMARKETPLACE_SHOW_PCONDITION'),
            'show_quantity' => Configuration::get('JMARKETPLACE_SHOW_QUANTITY'),
            'show_minimal_quantity' => Configuration::get('JMARKETPLACE_SHOW_MINIMAL_QTY'),
            'show_availability' => Configuration::get('JMARKETPLACE_SHOW_AVAILABILITY'),
            'show_available_now' => Configuration::get('JMARKETPLACE_SHOW_AVAILABLE_NOW'),
            'show_available_later' => Configuration::get('JMARKETPLACE_SHOW_AVAILABLE_LAT'),
            'show_available_date' => Configuration::get('JMARKETPLACE_SHOW_AVAILABLE_DATE'),
            'show_price' => Configuration::get('JMARKETPLACE_SHOW_PRICE'),
            'show_wholesale_price' => Configuration::get('JMARKETPLACE_SHOW_WHOLESALEPRICE'),
            'show_offer_price' => Configuration::get('JMARKETPLACE_SHOW_OFFER_PRICE'),
            'show_unit_price' => Configuration::get('JMARKETPLACE_SHOW_UNIT_PRICE'),
            'show_tax' => Configuration::get('JMARKETPLACE_SHOW_TAX'),
            'show_commission' => Configuration::get('JMARKETPLACE_SHOW_COMMISSION'),
            'show_on_sale' => Configuration::get('JMARKETPLACE_SHOW_ON_SALE'),
            'show_desc_short' => Configuration::get('JMARKETPLACE_SHOW_DESC_SHORT'),
            'show_desc' => Configuration::get('JMARKETPLACE_SHOW_DESC'),
            'show_meta_keywords' => Configuration::get('JMARKETPLACE_SHOW_META_KEYWORDS'),
            'show_meta_title' => Configuration::get('JMARKETPLACE_SHOW_META_TITLE'),
            'show_meta_desc' => Configuration::get('JMARKETPLACE_SHOW_META_DESC'),
            'show_link_rewrite' => Configuration::get('JMARKETPLACE_SHOW_LINK_REWRITE'),
            'show_images' => Configuration::get('JMARKETPLACE_SHOW_IMAGES'),
            'max_images' => Configuration::get('JMARKETPLACE_MAX_IMAGES'),
            'show_suppliers' => Configuration::get('JMARKETPLACE_SHOW_SUPPLIERS'),
            'show_new_suppliers' => Configuration::get('JMARKETPLACE_NEW_SUPPLIERS'),
            'show_manufacturers' => Configuration::get('JMARKETPLACE_SHOW_MANUFACTURERS'),
            'show_new_manufacturers' => Configuration::get('JMARKETPLACE_NEW_MANUFACTURERS'),
            'show_categories' => Configuration::get('JMARKETPLACE_SHOW_CATEGORIES'),
            'show_features' => Configuration::get('JMARKETPLACE_SHOW_FEATURES'),
            'show_attributes' => Configuration::get('JMARKETPLACE_SHOW_ATTRIBUTES'),
            'show_virtual' => Configuration::get('JMARKETPLACE_SHOW_VIRTUAL'),
            'show_attachments' => Configuration::get('JMARKETPLACE_SHOW_ATTACHMENTS'),
            'max_attachments' => Configuration::get('JMARKETPLACE_MAX_ATTACHMENTS'),
            'show_orders' => Configuration::get('JMARKETPLACE_SHOW_ORDERS'),
            'show_import_product' => Configuration::get('JMARKETPLACE_SELLER_IMPORT_PROD'),
            'show_edit_seller_account' => Configuration::get('JMARKETPLACE_SHOW_EDIT_ACCOUNT'),
            'show_contact' => Configuration::get('JMARKETPLACE_SHOW_CONTACT'),
            'show_manage_orders' => Configuration::get('JMARKETPLACE_SHOW_MANAGE_ORDERS'),
            'show_manage_carriers' => Configuration::get('JMARKETPLACE_SHOW_MANAGE_CARRIER'),
            'show_dashboard' => Configuration::get('JMARKETPLACE_SHOW_DASHBOARD'),
            'show_seller_invoice' => Configuration::get('JMARKETPLACE_SHOW_SELLER_INVOICE'),
            'show_menu_top' => Configuration::get('JMARKETPLACE_MENU_TOP'),
            'show_menu_options' => Configuration::get('JMARKETPLACE_MENU_OPTIONS'),
            'marketplace_theme' => Configuration::get('JMARKETPLACE_THEME'),
            'show_tabs' => Configuration::get('JMARKETPLACE_TABS'),
            'seller_link' => $url_seller_profile,
            'languages' => $languages,
            'id_lang' => $id_lang,
            'attachment_maximun_size' => Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE'),
            'token' => Configuration::get('JMARKETPLACE_TOKEN'),
            'seller_commission' => SellerCommission::getCommissionBySeller($id_seller),
            'fixed_commission' => Configuration::get('JMARKETPLACE_FIXED_COMMISSION'),
            'sign' => $this->context->currency->sign,
            'id_product' => 0,
            'has_attributes' => 0,
            'mesages_not_readed' => SellerIncidenceMessage::getNumMessagesNotReadedBySeller($id_seller),
            'image_not_available' => $url_shop.'modules/jmarketplace/views/img/image_not_available.jpg',
            'tax_commission' => Configuration::get('JMARKETPLACE_TAX_COMMISSION'),
            'PS_REWRITING_SETTINGS' => Configuration::get('PS_REWRITING_SETTINGS'),
            'errors' => $this->errors
        ));
        
        $this->setTemplate('addproduct.tpl');
    }
}
