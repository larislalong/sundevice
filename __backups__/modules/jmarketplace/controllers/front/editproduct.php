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

class JmarketplaceEditproductModuleFrontController extends ModuleFrontController
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
    
    protected function ajaxProcessDeleteImage()
    {
        $id_image = Tools::getValue('id_image');
        $image = new Image($id_image);
        if ($image->delete()) {
            die($this->module->l('The image has been deleted ok.', 'editproduct'));
        }
    }
    
    protected function ajaxProcessSelectAttributeGroup()
    {
        $html = '';
        $id_attribute_group = Tools::getValue('id_attribute_group');
        $options = AttributeGroup::getAttributes((int)Context::getContext()->language->id, (int)$id_attribute_group);
        foreach ($options as $option) {
            $html .= '<option value="'.$option['id_attribute'].'">'.$option['name'].'</option>';
        }
        die($html);
    }
    
    protected function ajaxProcessDeleteCombination()
    {
        $id_product = (int)Tools::getValue('id_product');
        $id_product_attribute = (int)Tools::getValue('id_product_attribute');
        $product = new Product($id_product);
        $product->deleteAttributeCombination($id_product_attribute);
    }
    
    public function postProcess()
    {
        $id_lang = (int)$this->context->language->id;
        $language = new Language($id_lang);
        
        $id_product = (int)Tools::getValue('id_product');
        $product = new Product($id_product, false, $id_lang, $this->context->shop->id);
        
        $id_seller = Seller::getSellerByCustomer((int)$this->context->cookie->id_customer);
        
        $seller = new Seller($id_seller);
        
        $params = array('id_seller' => $id_seller, 'id_product' => $id_product);
        
        Hook::exec('actionMarketplaceBeforeUpdateProduct', $params);
        
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
            $wholesale_price = (float)Tools::getValue('wholesale_price');
            $price = (float)Tools::getValue('price');
            $unit_price = (float)Tools::getValue('unit_price');
            $specific_price = (float)Tools::getValue('specific_price');
            $categories = Tools::getValue('categories');
            
            $new_manufacturer = Tools::getValue('new_manufacturer');
            $new_supplier = Tools::getValue('new_supplier');
            
            if ($name == '' || !Validate::isCatalogName($name)) {
                $this->errors[] = $this->module->l('Name product is incorrect.', 'editproduct');
            }
            
            if ($reference != '' && !Validate::isReference($reference)) {
                $this->errors[] = $this->module->l('Reference is incorrect.', 'editproduct');
            }
            
            if (Tools::strlen($reference) > 32) {
                $this->errors[] = $this->module->l('The reference must not exceed 30 characters.', 'editproduct');
            }
            
            $allow_iframe = (int)Configuration::get('PS_ALLOW_HTML_IFRAME');
            
            if (Tools::getValue('short_description_'.$this->context->language->id) != '' && !Validate::isCleanHtml(Tools::getValue('short_description_'.$this->context->language->id), $allow_iframe)) {
                $this->errors[] = $this->module->l('Short description is incorrect.', 'editproduct');
            }
            
            if (Tools::getValue('description_'.$this->context->language->id) != '' && !Validate::isCleanHtml(Tools::getValue('description_'.$this->context->language->id), $allow_iframe)) {
                $this->errors[] = $this->module->l('Description is incorrect.', 'editproduct');
            }
            
            if ($isbn != '' && !Validate::isIsbn($isbn)) {
                $this->errors[] = $this->module->l('ISBN is incorrect.', 'editproduct');
            }
            
            if ($ean13 != '' && !Validate::isEan13($ean13)) {
                $this->errors[] = $this->module->l('EAN13 is incorrect.', 'editproduct');
            }
            
            if ($upc != '' && !Validate::isUPC($upc)) {
                $this->errors[] = $this->module->l('UPC is incorrect.', 'editproduct');
            }
            
            if ($width != '' && !Validate::isFloat($width)) {
                $this->errors[] = $this->module->l('Width is incorrect.', 'editproduct');
            }
            
            if ($height != '' && !Validate::isFloat($height)) {
                $this->errors[] = $this->module->l('Height is incorrect.', 'editproduct');
            }
            
            if ($depth != '' && !Validate::isFloat($depth)) {
                $this->errors[] = $this->module->l('Depth is incorrect.', 'editproduct');
            }
            
            if ($weight != '' && !Validate::isFloat($weight)) {
                $this->errors[] = $this->module->l('Weight is incorrect.', 'editproduct');
            }
            
            if (Configuration::get('JMARKETPLACE_SHOW_WHOLESALEPRICE') == 1) {
                if ($wholesale_price < 0 || !Validate::isPrice($wholesale_price)) {
                    $this->errors[] = $this->module->l('Wholesale price is incorrect.', 'editproduct');
                }
            }
            
            if ($price < 0 || !Validate::isPrice($price)) {
                $this->errors[] = $this->module->l('Price is incorrect.', 'editproduct');
            }
            
            if ($specific_price != 0 && !Validate::isPrice($specific_price)) {
                $this->errors[] = $this->module->l('Offer price is incorrect.', 'editproduct');
            }
            
            if ($specific_price != 0 && $specific_price > $price) {
                $this->errors[] = $this->module->l('Offer price is bigger than price.', 'editproduct');
            }
            
            if (Configuration::get('JMARKETPLACE_SHOW_UNIT_PRICE') == 1) {
                if ($unit_price < 0 || !Validate::isPrice($unit_price)) {
                    $this->errors[] = $this->module->l('Unit price is incorrect.', 'editproduct');
                }
            }
            
            if ($quantity != '' && !Validate::isInt($quantity)) {
                $this->errors[] = $this->module->l('Quantity is incorrect.', 'editproduct');
            }
            
            if (($minimal_quantity != '' && !Validate::isInt($minimal_quantity)) || ($minimal_quantity != '' && $minimal_quantity < 1)) {
                $this->errors[] = $this->module->l('Minimal quantity is incorrect.', 'editproduct');
            }
            
            if (Configuration::get('JMARKETPLACE_SHOW_CATEGORIES') == 1 && !is_array($categories)) {
                $this->errors[] = $this->module->l('You must select the category default.', 'editproduct');
            }
            
            if (Configuration::get('JMARKETPLACE_SHOW_ATTRIBUTES') == 1) {
                if (Tools::getValue('combination_price') > 0) {
                    foreach (Tools::getValue('combination_price') as $combination_price) {
                        if (!Validate::isFloat($combination_price)) {
                            $this->errors[] = $this->module->l('Combination price is incorrect.', 'editproduct');
                        }
                    }
                }
                
                if (Tools::getValue('combination_weight')) {
                    foreach (Tools::getValue('combination_weight') as $combination_weight) {
                        if (!Validate::isFloat($combination_weight)) {
                            $this->errors[] = $this->module->l('Combination weight is incorrect.', 'editproduct');
                        }
                    }
                }
                
                if (Tools::getValue('combination_qty')) {
                    foreach (Tools::getValue('combination_qty') as $combination_qty) {
                        if (!Validate::isInt($combination_qty)) {
                            $this->errors[] = $this->module->l('Combination quantity is incorrect.', 'editproduct');
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
                                $this->errors[] = $this->module->l('The image format is incorrect or max size to upload is', 'editproduct').' '.ini_get('post_max_size');
                            }
                        } else {
                            $url_images[$i] = '';
                        }
                    }
                } else {
                    $this->errors[] = $this->module->l('The maxim images to upload is', 'editproduct').' '.Configuration::get('JMARKETPLACE_MAX_IMAGES');
                }
            }
            
            if ($new_manufacturer != '' && !Validate::isCatalogName($new_manufacturer)) {
                $this->errors[] = $this->module->l('Name manufacturer is incorrect.', 'editproduct');
            }
            
            if ($new_supplier != '' && !Validate::isCatalogName($new_supplier)) {
                $this->errors[] = $this->module->l('Name supplier is incorrect.', 'editproduct');
            }
            
            if (Tools::getValue('type_product') == 2) {
                $virtual_product_nb_downloable = Tools::getValue('virtual_product_nb_downloable');
                $virtual_product_expiration_date = Tools::getValue('virtual_product_expiration_date');
                $virtual_product_nb_days = Tools::getValue('virtual_product_nb_days');
                
                if ($virtual_product_nb_downloable != '' && !Validate::isInt($virtual_product_nb_downloable)) {
                    $this->errors[] = $this->module->l('Virtual product number downloable is incorrect.', 'editproduct');
                }
                
                if ($virtual_product_expiration_date != '' && !Validate::isDate($virtual_product_expiration_date) && $virtual_product_expiration_date != '0000-00-00') {
                    $this->errors[] = $this->module->l('Virtual product expiration date is incorrect.', 'editproduct');
                }
                
                if ($virtual_product_nb_days != '' && !Validate::isInt($virtual_product_nb_days)) {
                    $this->errors[] = $this->module->l('Virtual product number days is incorrect.', 'editproduct');
                }
            }
            
            if (Configuration::get('JMARKETPLACE_SHOW_ATTACHMENTS') == 1) {
                $num_attachments = count($_FILES['attachments']['name']);
                if ($num_attachments <= Configuration::get('JMARKETPLACE_MAX_ATTACHMENTS')) {
                    for ($i=1; $i<=Configuration::get('JMARKETPLACE_MAX_ATTACHMENTS'); $i++) {
                        if (!Validate::isGenericName(Tools::getValue('attachment_name_'.$i.'_'.$id_lang))) {
                            $this->errors[] = $this->module->l('Invalid attachment name for language ', 'editproduct').$language->name;
                        }

                        if (Tools::strlen(Tools::getValue('attachment_name_'.$i.'_'.$id_lang)) > 32) {
                            $this->errors[] = $this->module->l('The attachment name is too long for language ', 'editproduct').$language->name;
                        }

                        if (!Validate::isCleanHtml(Tools::getValue('attachment_description_'.$i.'_'.$id_lang))) {
                            $this->errors[] = $this->module->l('Invalid attachment description for language ', 'editproduct').$language->name;
                        }
                        
                        if (isset($_FILES['attachments']['name'][$i]) && $_FILES['attachments']['name'][$i] != "") {
                            if ($_FILES['attachments']['size'][$i] > (Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE') * 1024 * 1024)) {
                                $this->errors[] = $this->module->l('The file is too large. Maximum size allowed is ', 'editproduct').(Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE') * 1024);
                            }
                            
                            if ($_FILES['attachments']['type'][$i] > 128) {
                                $this->errors[] = $this->module->l('Invalid file extension for attachment ', 'editproduct').$_FILES['attachments']['name'][$i];
                            }
                            
                            if (!Validate::isGenericName($_FILES['attachments']['name'][$i])) {
                                $this->errors[] = $this->module->l('Invalid file name for ', 'editproduct').$_FILES['attachments']['name'][$i];
                            }
                            
                            if (Tools::strlen($_FILES['attachments']['name'][$i]) > 128) {
                                $this->errors[] = $this->module->l('The file name is too long for ', 'editproduct').$_FILES['attachments']['name'][$i];
                            }
                        }
                    }
                }
            }
            
            if (count($this->errors) > 0) {
                $this->context->smarty->assign(array('errors' => $this->errors));
            } else {
                $id_product = SellerProduct::import($_POST, $_FILES, $url_images, $this->context->language->id);

                $params = array('id_product' => $id_product);
                Hook::exec('actionMarketplaceAfterUpdateProduct', $params);
                
                if (Configuration::get('JMARKETPLACE_MODERATE_PRODUCT') || Configuration::get('JMARKETPLACE_SEND_ADMIN_PRODUCT')) {
                    $id_seller_email = false;
                    $to = Configuration::get('JMARKETPLACE_SEND_ADMIN');
                    $to_name = Configuration::get('PS_SHOP_NAME');
                    $from = Configuration::get('PS_SHOP_EMAIL');
                    $from_name = Configuration::get('PS_SHOP_NAME');
                    $template = 'base';
                    $reference = 'edit-product';
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
        
        if (Tools::isSubmit('download')) {
            $this->downloadProduct();
        }
    }

    public function initContent()
    {
        parent::initContent();
        
        if (Tools::isSubmit('action')) {
            switch (Tools::getValue('action')) {
                case 'select_attribute_group':
                    $this->ajaxProcessSelectAttributeGroup();
                    break;
                case 'delete_combination':
                    $this->ajaxProcessDeleteCombination();
                    break;
                case 'delete_image':
                    $this->ajaxProcessDeleteImage();
                    break;
            }
        }
        
        $languages = Language::getLanguages();
        $id_lang = (int)$this->context->language->id;

        if (!$this->context->cookie->id_customer) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_EDIT_PRODUCT') == 0) {
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
        
        $id_product = (int)Tools::getValue('id_product');
        
        if (SellerProduct::existAssociationSellerProduct($id_product) == $id_seller) {
            $product = new Product($id_product);
            $params = array('id_seller' => $id_seller, 'link_rewrite' => $seller->link_rewrite);
            $url_seller_profile = $this->module->getJmarketplaceLink('jmarketplace_seller_rule', $params);

            if (Configuration::get('JMARKETPLACE_SHOW_SHIP_PRODUCT') == 1) {
                if (Configuration::get('JMARKETPLACE_SHOW_MANAGE_CARRIER') == 1) {
                    $carriers = SellerTransport::getCarriers($this->context->language->id, true, $id_seller);
                } else {
                    $carriers = Carrier::getCarriers($this->context->language->id, true, false, false, null, 5);
                }

                $product_carriers = $product->getCarriers();
                $counter = 0;
                foreach ($carriers as $c) {
                    $carriers[$counter]['checked'] = 0;
                    foreach ($product_carriers as $pc) {
                        if ($c['id_reference'] == $pc['id_reference']) {
                            $carriers[$counter]['checked'] = 1;
                        }
                    }
                    $counter++;
                }

                $this->context->smarty->assign('carriers', $carriers);
            }

            if (Configuration::get('JMARKETPLACE_SHOW_IMAGES') == 1) {
                $product_images = $product->getImages((int)$this->context->language->id);
                $images = array();
                if (is_array($product_images) && count($product_images) > 0) {
                    foreach ($product_images as $pa) {
                        $image = new Image($pa['id_image']);
                        $images[] = $image;
                    }
                }

                $imageDimensions = SellerProduct::getImageDimensions();
                $dimensions = $imageDimensions['width'].'x'.$imageDimensions['height'].'px';
                $this->context->smarty->assign(array(
                    'max_images' => Configuration::get('JMARKETPLACE_MAX_IMAGES'),
                    'max_dimensions' => $dimensions,
                    'images' => $images
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

            if (Configuration::get('JMARKETPLACE_SHOW_CATEGORIES') == 1) {
                $categories = Category::getNestedCategories(null, $this->context->language->id);
                $categoryTree = '<ul id="tree1">'.CategoryTree::generateCheckboxesCategories($categories, $id_product).'</ul>';
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
                    foreach ($product->getFeatures() as $tab_products) {
                        if ($tab_products['id_feature'] == $tab_features['id_feature']) {
                            $features[$k]['current_item'] = $tab_products['id_feature_value'];
                        }
                    }

                    $features[$k]['featureValues'] = FeatureValue::getFeatureValuesWithLang((int)$this->context->language->id, (int)$tab_features['id_feature']);
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

            $attributes = $product->getAttributesResume($this->context->language->id, ':');

            if (is_array($attributes)) {
                $this->context->smarty->assign('has_attributes', 1);
            } else {
                $this->context->smarty->assign('has_attributes', 0);
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
                    'first_options' => AttributeGroup::getAttributes((int)$this->context->language->id, (int)$attribute_groups[0]['id_attribute_group']),
                    'attributes' => $attributes
                ));
            }
            
            if (Configuration::get('JMARKETPLACE_SHOW_ATTACHMENTS') == 1) {
                $info_attachments = array();
                $product_attachments = Product::getAttachmentsStatic($id_lang, $id_product);
                if (is_array($product_attachments) && count($product_attachments) > 0) {
                    $info_attachments = array();
                    foreach ($product_attachments as $pa) {
                        $attachment = new Attachment($pa['id_attachment']);
                        $info_attachments[] = $attachment;
                    }
                }
                
                $this->context->smarty->assign(array(
                    'max_attachments' => Configuration::get('JMARKETPLACE_MAX_ATTACHMENTS'),
                    'product_attachments' => $product_attachments,
                    'info_attachments' => $info_attachments
                ));
            }

            $categories = Product::getProductCategoriesFull($product->id);
            $categories_string = '';
            foreach ($categories as $c) {
                $categories_string .= $c['name'].', ';
            }
            $categories_string = Tools::substr($categories_string, 0, -2);
            
            $final_price_tax_excl = $product->getPrice(false, null, 6, null, false, false);
            $final_price_tax_incl = $product->getPrice(true, null, 6, null, false, false);

            $specificPrice = SpecificPrice::getByProductId($product->id);
            if ($specificPrice) {
                $final_price_tax_excl = $product->getPrice(false, null, 6, null, false, true);
                $final_price_tax_incl = $product->getPrice(true, null, 6, null, false, true);
                $this->context->smarty->assign(array(
                    'specific_price' => 1,
                ));
            }
            
            $this->context->smarty->assign(array(
                'final_price_tax_excl' => $final_price_tax_excl,
                'final_price_tax_incl' => $final_price_tax_incl,
            ));
            
            if (Configuration::get('PS_SSL_ENABLED') == 1) {
                $url_shop = Tools::getShopDomainSsl(true).__PS_BASE_URI__;
            } else {
                $url_shop = Tools::getShopDomain(true).__PS_BASE_URI__;
            }

            $this->context->smarty->assign(array(
                'form_edit' => $this->context->link->getModuleLink('jmarketplace', 'editproduct', array('id_product' => $id_product), true),
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
                'show_import_product' => Configuration::get('JMARKETPLACE_SELLER_IMPORT_PROD'),
                'show_orders' => Configuration::get('JMARKETPLACE_SHOW_ORDERS'),
                'show_manage_orders' => Configuration::get('JMARKETPLACE_SHOW_MANAGE_ORDERS'),
                'show_manage_carriers' => Configuration::get('JMARKETPLACE_SHOW_MANAGE_CARRIER'),
                'show_dashboard' => Configuration::get('JMARKETPLACE_SHOW_DASHBOARD'),
                'show_seller_invoice' => Configuration::get('JMARKETPLACE_SHOW_SELLER_INVOICE'),
                'show_edit_seller_account' => Configuration::get('JMARKETPLACE_SHOW_EDIT_ACCOUNT'),
                'show_contact' => Configuration::get('JMARKETPLACE_SHOW_CONTACT'),
                'show_menu_top' => Configuration::get('JMARKETPLACE_MENU_TOP'),
                'show_menu_options' => Configuration::get('JMARKETPLACE_MENU_OPTIONS'),
                'show_tabs' => Configuration::get('JMARKETPLACE_TABS'),
                'product' => $product,
                'real_quantity' => Product::getRealQuantity($id_product, 0, 0, $this->context->shop->id),
                'out_of_stock' => StockAvailable::outOfStock($id_product, $this->context->shop->id),
                'categories_string' => $categories_string,
                'categories_selected' => $categories,
                'seller_link' => $url_seller_profile,
                'languages' => $languages,
                'id_lang' => $id_lang,
                'attachment_maximun_size' => Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE'),
                'token' => Configuration::get('JMARKETPLACE_TOKEN'),
                'is_virtual' => 0,
                'seller_commission' => SellerCommission::getCommissionBySeller($id_seller),
                'fixed_commission' => Configuration::get('JMARKETPLACE_FIXED_COMMISSION'),
                'sign' => $this->context->currency->sign,
                'id_product' => $product->id,
                'image_not_available' => $url_shop.'modules/jmarketplace/views/img/image_not_available.jpg',
                'tax_commission' => Configuration::get('JMARKETPLACE_TAX_COMMISSION'),
                'PS_REWRITING_SETTINGS' => Configuration::get('PS_REWRITING_SETTINGS')
            ));

            if ($product->is_virtual == 1) {
                $id_product_download = ProductDownload::getIdFromIdProduct($product->id);
                $product_download = new ProductDownload($id_product_download);
                $filename = ProductDownload::getFilenameFromIdProduct($product->id);
                $display_filename = ProductDownload::getFilenameFromFilename($filename);
                $date_expiration = explode(' ', $product_download->date_expiration);
                if (is_array($date_expiration)) {
                    $date_expiration = $date_expiration[0];
                } else {
                    $date_expiration = '';
                }

                $this->context->smarty->assign(array(
                    'is_virtual' => $product->is_virtual,
                    'filename' => $filename,
                    'product_hash' => $product_download->getHash(),
                    'display_filename' => $display_filename,
                    'product_download_link' => $product_download->getHtmlLink(),
                    'virtual_product_nb_downloable' => $product_download->nb_downloadable,
                    'virtual_product_expiration_date' => $date_expiration,
                    'virtual_product_nb_days' => $product_download->nb_days_accessible,
                ));
            }
        } else {
            Tools::redirect($this->context->link->getModuleLink('jmarketplace', 'sellerproducts', array(), true));
        }
        
        $this->setTemplate('editproduct.tpl');
    }
    
    public function downloadProduct()
    {
        $filename = ProductDownload::getFilenameFromIdProduct(Tools::getValue('id_product'));
        $display_filename = ProductDownload::getFilenameFromFilename($filename);

        $file = _PS_DOWNLOAD_DIR_.$filename;
        $filename = $display_filename;

        $mimeType = false;

        if (empty($mimeType)) {
            $bName = basename($filename);
            $bName = explode('.', $bName);
            $bName = Tools::strtolower($bName[count($bName) - 1]);

            $mimeTypes = array(
                'ez' => 'application/andrew-inset',
                'hqx' => 'application/mac-binhex40',
                'cpt' => 'application/mac-compactpro',
                'doc' => 'application/msword',
                'oda' => 'application/oda',
                'pdf' => 'application/pdf',
                'ai' => 'application/postscript',
                'eps' => 'application/postscript',
                'ps' => 'application/postscript',
                'smi' => 'application/smil',
                'smil' => 'application/smil',
                'wbxml' => 'application/vnd.wap.wbxml',
                'wmlc' => 'application/vnd.wap.wmlc',
                'wmlsc' => 'application/vnd.wap.wmlscriptc',
                'bcpio' => 'application/x-bcpio',
                'vcd' => 'application/x-cdlink',
                'pgn' => 'application/x-chess-pgn',
                'cpio' => 'application/x-cpio',
                'csh' => 'application/x-csh',
                'dcr' => 'application/x-director',
                'dir' => 'application/x-director',
                'dxr' => 'application/x-director',
                'dvi' => 'application/x-dvi',
                'spl' => 'application/x-futuresplash',
                'gtar' => 'application/x-gtar',
                'hdf' => 'application/x-hdf',
                'js' => 'application/x-javascript',
                'skp' => 'application/x-koan',
                'skd' => 'application/x-koan',
                'skt' => 'application/x-koan',
                'skm' => 'application/x-koan',
                'latex' => 'application/x-latex',
                'nc' => 'application/x-netcdf',
                'cdf' => 'application/x-netcdf',
                'sh' => 'application/x-sh',
                'shar' => 'application/x-shar',
                'swf' => 'application/x-shockwave-flash',
                'sit' => 'application/x-stuffit',
                'sv4cpio' => 'application/x-sv4cpio',
                'sv4crc' => 'application/x-sv4crc',
                'tar' => 'application/x-tar',
                'tcl' => 'application/x-tcl',
                'tex' => 'application/x-tex',
                'texinfo' => 'application/x-texinfo',
                'texi' => 'application/x-texinfo',
                't' => 'application/x-troff',
                'tr' => 'application/x-troff',
                'roff' => 'application/x-troff',
                'man' => 'application/x-troff-man',
                'me' => 'application/x-troff-me',
                'ms' => 'application/x-troff-ms',
                'ustar' => 'application/x-ustar',
                'src' => 'application/x-wais-source',
                'xhtml' => 'application/xhtml+xml',
                'xht' => 'application/xhtml+xml',
                'zip' => 'application/zip',
                'au' => 'audio/basic',
                'snd' => 'audio/basic',
                'mid' => 'audio/midi',
                'midi' => 'audio/midi',
                'kar' => 'audio/midi',
                'mpga' => 'audio/mpeg',
                'mp2' => 'audio/mpeg',
                'mp3' => 'audio/mpeg',
                'aif' => 'audio/x-aiff',
                'aiff' => 'audio/x-aiff',
                'aifc' => 'audio/x-aiff',
                'm3u' => 'audio/x-mpegurl',
                'ram' => 'audio/x-pn-realaudio',
                'rm' => 'audio/x-pn-realaudio',
                'rpm' => 'audio/x-pn-realaudio-plugin',
                'ra' => 'audio/x-realaudio',
                'wav' => 'audio/x-wav',
                'pdb' => 'chemical/x-pdb',
                'xyz' => 'chemical/x-xyz',
                'bmp' => 'image/bmp',
                'gif' => 'image/gif',
                'ief' => 'image/ief',
                'jpeg' => 'image/jpeg',
                'jpg' => 'image/jpeg',
                'jpe' => 'image/jpeg',
                'png' => 'image/png',
                'tiff' => 'image/tiff',
                'tif' => 'image/tif',
                'djvu' => 'image/vnd.djvu',
                'djv' => 'image/vnd.djvu',
                'wbmp' => 'image/vnd.wap.wbmp',
                'ras' => 'image/x-cmu-raster',
                'pnm' => 'image/x-portable-anymap',
                'pbm' => 'image/x-portable-bitmap',
                'pgm' => 'image/x-portable-graymap',
                'ppm' => 'image/x-portable-pixmap',
                'rgb' => 'image/x-rgb',
                'xbm' => 'image/x-xbitmap',
                'xpm' => 'image/x-xpixmap',
                'xwd' => 'image/x-windowdump',
                'igs' => 'model/iges',
                'iges' => 'model/iges',
                'msh' => 'model/mesh',
                'mesh' => 'model/mesh',
                'silo' => 'model/mesh',
                'wrl' => 'model/vrml',
                'vrml' => 'model/vrml',
                'css' => 'text/css',
                'html' => 'text/html',
                'htm' => 'text/html',
                'asc' => 'text/plain',
                'txt' => 'text/plain',
                'rtx' => 'text/richtext',
                'rtf' => 'text/rtf',
                'sgml' => 'text/sgml',
                'sgm' => 'text/sgml',
                'tsv' => 'text/tab-seperated-values',
                'wml' => 'text/vnd.wap.wml',
                'wmls' => 'text/vnd.wap.wmlscript',
                'etx' => 'text/x-setext',
                'xml' => 'text/xml',
                'xsl' => 'text/xml',
                'mpeg' => 'video/mpeg',
                'mpg' => 'video/mpeg',
                'mpe' => 'video/mpeg',
                'qt' => 'video/quicktime',
                'mov' => 'video/quicktime',
                'mxu' => 'video/vnd.mpegurl',
                'avi' => 'video/x-msvideo',
                'movie' => 'video/x-sgi-movie',
                'ice' => 'x-conference-xcooltalk'
            );

            if (isset($mimeTypes[$bName])) {
                $mimeType = $mimeTypes[$bName];
            } else {
                $mimeType = 'application/octet-stream';
            }
        }

        if (ob_get_level() && ob_get_length() > 0) {
            ob_end_clean();
        }

        /* Set headers for download */
        header('Content-Transfer-Encoding: binary');
        header('Content-Type: '.$mimeType);
        header('Content-Length: '.filesize($file));
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        //prevents max execution timeout, when reading large files
        @set_time_limit(0);
        $fp = fopen($file, 'rb');

        if ($fp && is_resource($fp)) {
            while (!feof($fp)) {
                echo fgets($fp, 16384);
            }
        }

        exit;
    }
}
