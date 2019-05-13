<?php
/*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

include_once  _PS_OVERRIDE_DIR_. 'classes/ProductSupportedLTE.php';
include_once  _PS_OVERRIDE_DIR_. 'classes/SupportedLTEMarket.php';
include_once  _PS_OVERRIDE_DIR_. 'classes/ProductPack.php';
class AdminProductsController extends AdminProductsControllerCore
{

    public function __construct()
    {
        parent::__construct();
        $this->available_tabs_lang['SupportedLTENetworks'] = $this->l('Supported LTE Networks');
        $this->available_tabs_lang['PackList'] = $this->l('Pack list');
        if ($this->context->shop->getContext() != Shop::CONTEXT_GROUP) {
            $this->available_tabs ['SupportedLTENetworks'] = 15;
            $this->available_tabs ['PackList'] = 16;
        }
    }
	
	public function initContent()
    {
		$this->context->smarty->assign('supportedltenetworks_js_tpl', _PS_OVERRIDE_DIR_.'controllers/admin/templates/products/supportedltenetworks_js.tpl');
        parent::initContent();
    }
    
    public function initFormSupportedLTENetworks($obj)
    {
        $product = $obj;
        $data = $this->createTemplate($this->tpl_form);
        if (Validate::isLoadedObject($product)) {
            if ($this->product_exists_in_shop) {
                $data->assign(
                    array(
                        'preferencesLink' => $this->context->link->getAdminLink('AdminPreferences'),
                        'product' => $product,
                        'productLTEList' => $this->getModelList($product),
                    )
                );
            }else{
                $this->displayWarning($this->l('You must save the product in this shop before updating Supported LTE Networks.'));
            }
        }else{
            $this->displayWarning($this->l('You must save this product before updating Supported LTE Networks.'));
        }
        $this->tpl_form_vars['custom_form'] = $data->fetch();
    }
	public function initFormPackList($obj)
    {
        $product = $obj;
        $data = $this->createTemplate($this->tpl_form);
        if (Validate::isLoadedObject($product)) {
            if ($this->product_exists_in_shop) {
                $data->assign('selectedPacks', ProductPack::getAll($product->id, $this->context->language->id));
            }else{
                $this->displayWarning($this->l('You must save the product in this shop before updating its packs.'));
            }
        }else{
            $this->displayWarning($this->l('You must save this product before updating its packs.'));
        }
        $this->tpl_form_vars['custom_form'] = $data->fetch();
    }
    public function processPackList()
    {
		$selectedPacks = Tools::getValue('selectedPacks');
		$defaultPack = Tools::getValue('default_pack');
		ProductPack::addNew(Tools::getValue('id_product'), $selectedPacks, $defaultPack);
    }
    public function getModelList($product)
    {
        $list = ProductSupportedLTE::getAllDisplayable($product, $this->context->language->id, $this->context->smarty);
        $id_attribute_group = ProductSupportedLTE::getModelIdAttributeGroup();
        $attributes = ProductSupportedLTE::getAttributes($this->context->language->id, (int)$id_attribute_group, $product->id);
        $attributesAdded = array();
        foreach ($list as $key => $value) {
            $attributesAdded[] = $value['id_attribute'];
            //$attribute = new Attribute($value['id_attribute'], $this->context->language->id);
            //$list[$key]['attributeName'] = $attribute->name;
        }
        foreach ($attributes as $value) {
            if (!in_array($value['id_attribute'], $attributesAdded)) {
                $list[] = array(
                    'id_attribute'=>$value['id_attribute'],
                    'attributeName'=>$value['name'],
                    'content'=>'',
                    'id_product_supported_lte'=>0
                );
            }
        }
        
        return $list;
    }
    
    public function ajaxProcessGetLTEEditionForm()
    {
        if ($this->tabAccess['edit'] === '1') {
            $id_product_supported_lte = (int)Tools::getValue('id_product_supported_lte');
            if (!$this->default_form_language) {
                $this->getLanguages();
            }
            if(empty($id_product_supported_lte)){
                $markets=array();
            }else{
                $markets= SupportedLTEMarket::getAll($id_product_supported_lte);
				foreach($markets as $key => $market){
					$object = new SupportedLTEMarket($market['id_supported_lte_market']);
					$markets[$key]['market_name'] = $object->market_name;
					$markets[$key]['content'] = $object->content;
				}
            }
            $tpl = $this->createTemplate('supportedltenetworks_edition.tpl');
            $iso_tiny_mce = $this->context->language->iso_code;
            $iso_tiny_mce = (file_exists(_PS_ROOT_DIR_.'/js/tiny_mce/langs/'.$iso_tiny_mce.'.js') ? $iso_tiny_mce : 'en');
            $tpl->assign(
                array(
                    'default_form_language' => $this->default_form_language,
                    'languages' => $this->_languages,
                    'iso_tiny_mce' => $iso_tiny_mce,
					'custom_text_lang_tpl' => _PS_OVERRIDE_DIR_.'controllers/admin/templates/products/custom_text_lang.tpl',
                    'iso_tiny_mce' => $iso_tiny_mce,
                    'markets' => $markets
                )
            );
            $this->ajaxDie(
                Tools::jsonEncode(
                    array(
                        'hasError' => false,
                        'form' => $tpl->fetch()
                    )
                )
            );
        }
    }
    
    public function ajaxProcessSaveProductLte()
    {
        if ($this->tabAccess['edit'] === '1') {
            if (!$this->default_form_language) {
                $this->getLanguages();
            }
            $id_product_supported_lte = (int)Tools::getValue('id_product_supported_lte');
            $id_attribute = (int)Tools::getValue('id_attribute');
            $id_product= (int)Tools::getValue('id_product');
            $values = $this->getPostedValues();
            $errors = $this->getValidationErrors($values);
			$content = '';
            if(empty($errors)){
				SupportedLTEMarket::deleteBy($id_product_supported_lte);
				$object = new ProductSupportedLTE();
				$object->id= $id_product_supported_lte;
				$object->id_attribute= $id_attribute;
				$object->id_product= $id_product;
				if(!$object->save()){
					$errors[]=$this->l('On error occurred while saving');
				}else{
					foreach($values as $value){
						$market = new SupportedLTEMarket();
						$market->market_name= self::fillMultilangEmptyFields($value['market_name']);
						$market->content = self::fillMultilangEmptyFields($value['content']);
						$market->id_product_supported_lte= $object->id;
						$market->market_image= $value['market_image'];
						$market->save();
					}
					$dataLte = (array)$object;
					$attribute = new Attribute((int)$object->id_attribute, $this->context->language->id);
					$dataLte['id_product_supported_lte'] = $object->id;
					$dataLte['attributeName'] = $attribute->name;
					$product = new Product($id_product, false, $this->context->language->id);
					$content = ProductSupportedLTE::getContent($product, $dataLte, $this->context->language->id, $this->context->smarty);
				}
				
			}
            
            $hasError= (count($errors)>0);
            $this->ajaxDie(
                Tools::jsonEncode(
                    array(
                        'hasError' => $hasError,
                        'content' => $hasError?'':$content,
                        'message' => $hasError?'':$this->l('Save successfully'),
                        'id_product_supported_lte' => $hasError?0:$object->id,
                        'errors' => $errors
                    )
                )
            );
        }
    }
	
	public function getPostedValues()
    {
		$values = array();
		$totalMarket = (int)Tools::getValue('total_markets');
		for($i=1; $i<=$totalMarket; $i++){
			$values[$i]['market_image'] = Tools::getValue('market_image_'.$i);
			foreach ($this->_languages as $language) {
                $language = (object) $language;
				$fieldKey = 'market_name_' . $language->id_lang.'_'.$i;
                if (Tools::getIsset($fieldKey)) {
                    $values[$i]['market_name'][$language->id_lang] = Tools::getValue($fieldKey);
                }
            }
			foreach ($this->_languages as $language) {
                $language = (object) $language;
				$fieldKey = 'content_' . $language->id_lang.'_'.$i;
                if (Tools::getIsset($fieldKey)) {
                    $values[$i]['content'][$language->id_lang] = Tools::getValue($fieldKey);
                }
            }
		}
		return $values;
    }
	
	public function getValidationErrors($values)
    {
        $errors = array();
        foreach ($values as $index => $value) {
			if(self::isMultilangFieldEmpty($value, 'market_name')){
				$errors[] = sprintf(
					$this->l('Please enter a market name for line %d'),
					$index
				);
			}
			if(self::isMultilangFieldEmpty($value, 'content')){
				$errors[] = sprintf(
					$this->l('Please enter a lte networks for line %d'),
					$index
				);
			}
        }
        
        return $errors;
    }
    
    public static function fillMultilangEmptyFields($values)
    {
        $defaultValue = ((isset($values[Configuration::get('PS_LANG_DEFAULT')]) &&
                (!empty($values[Configuration::get('PS_LANG_DEFAULT')]))) ?
                $values[Configuration::get('PS_LANG_DEFAULT')] : '');
        //Recherche d'une valeur non nulle
        if (empty($defaultValue)) {
            foreach ($values as $value) {
                if (!empty($value)) {
                    $defaultValue= $value;
                    break;
                }
            }
        }
        foreach ($values as $key => $value) {
            if (empty($value)) {
                $values[$key] = $defaultValue;
            }
        }
        return $values;
    }
	public static function isMultilangFieldEmpty($values, $index)
    {
        $emptyField = true;
        foreach ($values[$index] as $value) {
            if ($value) {
                $emptyField = false;
                break;
            }
        }
        return $emptyField;
    }
	
	public function processUpdate()
    {
        $existing_product = $this->object;

        $this->checkProduct();

        if (!empty($this->errors)) {
            $this->display = 'edit';
            return false;
        }

        $id = (int)Tools::getValue('id_'.$this->table);
        /* Update an existing product */
        if (isset($id) && !empty($id)) {
            /** @var Product $object */
            $object = new $this->className((int)$id);
            $this->object = $object;

            if (Validate::isLoadedObject($object)) {
                $this->_removeTaxFromEcotax();
                $product_type_before = $object->getType();
                $this->copyFromPost($object, $this->table);
                $object->indexed = 0;

                if (Shop::isFeatureActive() && Shop::getContext() != Shop::CONTEXT_SHOP) {
                    $object->setFieldsToUpdate((array)Tools::getValue('multishop_check', array()));
                }

                // Duplicate combinations if not associated to shop
                if ($this->context->shop->getContext() == Shop::CONTEXT_SHOP && !$object->isAssociatedToShop()) {
                    $is_associated_to_shop = false;
                    $combinations = Product::getProductAttributesIds($object->id);
                    if ($combinations) {
                        foreach ($combinations as $id_combination) {
                            $combination = new Combination((int)$id_combination['id_product_attribute']);
                            $default_combination = new Combination((int)$id_combination['id_product_attribute'], null, (int)$this->object->id_shop_default);

                            $def = ObjectModel::getDefinition($default_combination);
                            foreach ($def['fields'] as $field_name => $row) {
                                $combination->$field_name = ObjectModel::formatValue($default_combination->$field_name, $def['fields'][$field_name]['type']);
                            }

                            $combination->save();
                        }
                    }
                } else {
                    $is_associated_to_shop = true;
                }

                if ($object->update()) {
                    // If the product doesn't exist in the current shop but exists in another shop
                    if (Shop::getContext() == Shop::CONTEXT_SHOP && !$existing_product->isAssociatedToShop($this->context->shop->id)) {
                        $out_of_stock = StockAvailable::outOfStock($existing_product->id, $existing_product->id_shop_default);
                        $depends_on_stock = StockAvailable::dependsOnStock($existing_product->id, $existing_product->id_shop_default);
                        StockAvailable::setProductOutOfStock((int)$this->object->id, $out_of_stock, $this->context->shop->id);
                        StockAvailable::setProductDependsOnStock((int)$this->object->id, $depends_on_stock, $this->context->shop->id);
                    }

                    PrestaShopLogger::addLog(sprintf($this->l('%s modification', 'AdminTab', false, false), $this->className), 1, null, $this->className, (int)$this->object->id, true, (int)$this->context->employee->id);
                    if (in_array($this->context->shop->getContext(), array(Shop::CONTEXT_SHOP, Shop::CONTEXT_ALL))) {
                        if ($this->isTabSubmitted('PackList')) {
                            $this->processPackList();
                        }
						if ($this->isTabSubmitted('Shipping')) {
                            $this->addCarriers();
                        }
                        if ($this->isTabSubmitted('Associations')) {
                            $this->updateAccessories($object);
                        }
                        if ($this->isTabSubmitted('Suppliers')) {
                            $this->processSuppliers();
                        }
                        if ($this->isTabSubmitted('Features')) {
                            $this->processFeatures();
                        }
                        if ($this->isTabSubmitted('Combinations')) {
                            $this->processProductAttribute();
                        }
                        if ($this->isTabSubmitted('Prices')) {
                            $this->processPriceAddition();
                            $this->processSpecificPricePriorities();
                        }
                        if ($this->isTabSubmitted('Customization')) {
                            $this->processCustomizationConfiguration();
                        }
                        if ($this->isTabSubmitted('Attachments')) {
                            $this->processAttachments();
                        }
                        if ($this->isTabSubmitted('Images')) {
                            $this->processImageLegends();
                        }

                        $this->updatePackItems($object);
                        // Disallow avanced stock management if the product become a pack
                        if ($product_type_before == Product::PTYPE_SIMPLE && $object->getType() == Product::PTYPE_PACK) {
                            StockAvailable::setProductDependsOnStock((int)$object->id, false);
                        }
                        $this->updateDownloadProduct($object, 1);
                        $this->updateTags(Language::getLanguages(false), $object);

                        if ($this->isProductFieldUpdated('category_box') && !$object->updateCategories(Tools::getValue('categoryBox'))) {
                            $this->errors[] = Tools::displayError('An error occurred while linking the object.').' <b>'.$this->table.'</b> '.Tools::displayError('To categories');
                        }
                    }

                    if ($this->isTabSubmitted('Warehouses')) {
                        $this->processWarehouses();
                    }
                    if (empty($this->errors)) {
                        if (in_array($object->visibility, array('both', 'search')) && Configuration::get('PS_SEARCH_INDEXATION')) {
                            Search::indexation(false, $object->id);
                        }

                        // Save and preview
                        if (Tools::isSubmit('submitAddProductAndPreview')) {
                            $this->redirect_after = $this->getPreviewUrl($object);
                        } else {
                            $page = (int)Tools::getValue('page');
                            // Save and stay on same form
                            if ($this->display == 'edit') {
                                $this->confirmations[] = $this->l('Update successful');
                                $this->redirect_after = self::$currentIndex.'&id_product='.(int)$this->object->id
                                    .(Tools::getIsset('id_category') ? '&id_category='.(int)Tools::getValue('id_category') : '')
                                    .'&updateproduct&conf=4&key_tab='.Tools::safeOutput(Tools::getValue('key_tab')).($page > 1 ? '&page='.(int)$page : '').'&token='.$this->token;
                            } else {
                                // Default behavior (save and back)
                                $this->redirect_after = self::$currentIndex.(Tools::getIsset('id_category') ? '&id_category='.(int)Tools::getValue('id_category') : '').'&conf=4'.($page > 1 ? '&submitFilterproduct='.(int)$page : '').'&token='.$this->token;
                            }
                        }
                    }
                    // if errors : stay on edit page
                    else {
                        $this->display = 'edit';
                    }
                } else {
                    if (!$is_associated_to_shop && $combinations) {
                        foreach ($combinations as $id_combination) {
                            $combination = new Combination((int)$id_combination['id_product_attribute']);
                            $combination->delete();
                        }
                    }
                    $this->errors[] = Tools::displayError('An error occurred while updating an object.').' <b>'.$this->table.'</b> ('.Db::getInstance()->getMsgError().')';
                }
            } else {
                $this->errors[] = Tools::displayError('An error occurred while updating an object.').' <b>'.$this->table.'</b> ('.Tools::displayError('The object cannot be loaded. ').')';
            }
            return $object;
        }
    }
	public function renderListAttributes($product, $currency)
    {
        $this->bulk_actions = array('delete' => array('text' => $this->l('Delete selected'), 'confirm' => $this->l('Delete selected items?')));
        $this->addRowAction('edit');
        $this->addRowAction('default');
        $this->addRowAction('delete');

        $default_class = 'highlighted';

        $this->fields_list = array(
            'attributes' => array('title' => $this->l('Attribute - value pair'), 'align' => 'left'),
            'price' => array('title' => $this->l('Impact on price'), 'type' => 'price', 'align' => 'left'),
            'weight' => array('title' => $this->l('Impact on weight'), 'align' => 'left'),
            'reference' => array('title' => $this->l('Reference'), 'align' => 'left'),
            'ean13' => array('title' => $this->l('EAN-13'), 'align' => 'left'),
            'upc' => array('title' => $this->l('UPC'), 'align' => 'left'),
			'active' => array('title' => $this->l('Status'), 'active' => 'status', 'type' => 'bool', 'class' => 'fixed-width-xs td_combination_status', 'align' => 'center'),
			'deactivate_price_update' => array('title' => $this->l('MAJ prix Off'), 'deactivate_price_update' => 'status', 'type' => 'bool', 'class' => 'fixed-width-xs td_combination_deactivate_price_update', 'align' => 'center')
        );

        if ($product->id) {
            /* Build attributes combinations */
            $combinations = $product->getAttributeCombinations($this->context->language->id);
            $groups = array();
            $comb_array = array();
            if (is_array($combinations)) {
                $combination_images = $product->getCombinationImages($this->context->language->id);
                foreach ($combinations as $k => $combination) {
                    $price_to_convert = Tools::convertPrice($combination['price'], $currency);
                    $price = Tools::displayPrice($price_to_convert, $currency);

                    $comb_array[$combination['id_product_attribute']]['id_product_attribute'] = $combination['id_product_attribute'];
                    $comb_array[$combination['id_product_attribute']]['attributes'][] = array($combination['group_name'], $combination['attribute_name'], $combination['id_attribute']);
                    $comb_array[$combination['id_product_attribute']]['wholesale_price'] = $combination['wholesale_price'];
                    $comb_array[$combination['id_product_attribute']]['price'] = $price;
                    $comb_array[$combination['id_product_attribute']]['weight'] = $combination['weight'].Configuration::get('PS_WEIGHT_UNIT');
                    $comb_array[$combination['id_product_attribute']]['unit_impact'] = $combination['unit_price_impact'];
                    $comb_array[$combination['id_product_attribute']]['reference'] = $combination['reference'];
                    $comb_array[$combination['id_product_attribute']]['ean13'] = $combination['ean13'];
                    $comb_array[$combination['id_product_attribute']]['upc'] = $combination['upc'];
                    $comb_array[$combination['id_product_attribute']]['id_image'] = isset($combination_images[$combination['id_product_attribute']][0]['id_image']) ? $combination_images[$combination['id_product_attribute']][0]['id_image'] : 0;
                    $comb_array[$combination['id_product_attribute']]['available_date'] = strftime($combination['available_date']);
                    $comb_array[$combination['id_product_attribute']]['default_on'] = $combination['default_on'];
                    $comb_array[$combination['id_product_attribute']]['active'] = $combination['active'];
                    $comb_array[$combination['id_product_attribute']]['deactivate_price_update'] = (isset($combination['deactivate_price_update']) and $combination['deactivate_price_update'] == 1) ? 'OUI' : 'NON';
                    if ($combination['is_color_group']) {
                        $groups[$combination['id_attribute_group']] = $combination['group_name'];
                    }
                }
            }

            if (isset($comb_array)) {
                foreach ($comb_array as $id_product_attribute => $product_attribute) {
                    $list = '';

                    /* In order to keep the same attributes order */
                    asort($product_attribute['attributes']);

                    foreach ($product_attribute['attributes'] as $attribute) {
                        $list .= $attribute[0].' - '.$attribute[1].', ';
                    }

                    $list = rtrim($list, ', ');
                    $comb_array[$id_product_attribute]['image'] = $product_attribute['id_image'] ? new Image($product_attribute['id_image']) : false;
                    $comb_array[$id_product_attribute]['available_date'] = $product_attribute['available_date'] != 0 ? date('Y-m-d', strtotime($product_attribute['available_date'])) : '0000-00-00';
                    $comb_array[$id_product_attribute]['attributes'] = $list;
                    $comb_array[$id_product_attribute]['name'] = $list;

                    if ($product_attribute['default_on']) {
                        $comb_array[$id_product_attribute]['class'] = $default_class;
                    }
                }
            }
        }

        foreach ($this->actions_available as $action) {
            if (!in_array($action, $this->actions) && isset($this->$action) && $this->$action) {
                $this->actions[] = $action;
            }
        }

        $helper = new HelperList();
        $helper->identifier = 'id_product_attribute';
        $helper->table_id = 'combinations-list';
        $helper->token = $this->token;
        $helper->currentIndex = self::$currentIndex;
        $helper->no_link = true;
        $helper->simple_header = true;
        $helper->show_toolbar = false;
        $helper->shopLinkType = $this->shopLinkType;
        $helper->actions = $this->actions;
        $helper->list_skip_actions = $this->list_skip_actions;
        $helper->colorOnBackground = true;
        $helper->override_folder = $this->tpl_folder.'combination/';

        return $helper->generateList($comb_array, $this->fields_list);
    }
	
	public function ajaxProcessCombinationStatusChange()
    {
		$idProductAttribute = (int) Tools::getValue('id_product_attribute');
		$combination = new Combination($idProductAttribute);
		$hasError = !$combination->toggleStatus();
		$this->ajaxDie(
			Tools::jsonEncode(
				array(
					'hasError' => $hasError,
					'active' => $combination->active,
				)
			)
		);
    }

    public function processProductAttribute()
    {
        // Don't process if the combination fields have not been submitted
        if (!Combination::isFeatureActive() || !Tools::getValue('attribute_combination_list')) {
            return;
        }

        if (Validate::isLoadedObject($product = $this->object)) {
            if ($this->isProductFieldUpdated('attribute_price') && (!Tools::getIsset('attribute_price') || Tools::getIsset('attribute_price') == null)) {
                $this->errors[] = Tools::displayError('The price attribute is required.');
            }
            if (!Tools::getIsset('attribute_combination_list') || Tools::isEmpty(Tools::getValue('attribute_combination_list'))) {
                $this->errors[] = Tools::displayError('You must add at least one attribute.');
            }

            $array_checks = array(
                'reference' => 'isReference',
                'supplier_reference' => 'isReference',
                'location' => 'isReference',
                'ean13' => 'isEan13',
                'upc' => 'isUpc',
                'wholesale_price' => 'isPrice',
                'price' => 'isPrice',
                'ecotax' => 'isPrice',
                'quantity' => 'isInt',
                'weight' => 'isUnsignedFloat',
                'unit_price_impact' => 'isPrice',
                'default_on' => 'isBool',
                'minimal_quantity' => 'isUnsignedInt',
                'available_date' => 'isDateFormat'
            );
            foreach ($array_checks as $property => $check) {
                if (Tools::getValue('attribute_'.$property) !== false && !call_user_func(array('Validate', $check), Tools::getValue('attribute_'.$property))) {
                    $this->errors[] = sprintf(Tools::displayError('Field %s is not valid'), $property);
                }
            }

            if (!count($this->errors)) {
                if (!isset($_POST['attribute_wholesale_price'])) {
                    $_POST['attribute_wholesale_price'] = 0;
                }
                if (!isset($_POST['attribute_price_impact'])) {
                    $_POST['attribute_price_impact'] = 0;
                }
                if (!isset($_POST['attribute_weight_impact'])) {
                    $_POST['attribute_weight_impact'] = 0;
                }
                if (!isset($_POST['attribute_ecotax'])) {
                    $_POST['attribute_ecotax'] = 0;
                }
                if (Tools::getValue('attribute_default')) {
                    $product->deleteDefaultAttributes();
                }

                // Change existing one
                if (($id_product_attribute = (int)Tools::getValue('id_product_attribute')) || ($id_product_attribute = $product->productAttributeExists(Tools::getValue('attribute_combination_list'), false, null, true, true))) {
                    if ($this->tabAccess['edit'] === '1') {
                        if ($this->isProductFieldUpdated('available_date_attribute') && (Tools::getValue('available_date_attribute') != '' &&!Validate::isDateFormat(Tools::getValue('available_date_attribute')))) {
                            $this->errors[] = Tools::displayError('Invalid date format.');
                        } else {
                            $product->updateAttribute((int)$id_product_attribute,
                                $this->isProductFieldUpdated('attribute_wholesale_price') ? Tools::getValue('attribute_wholesale_price') : null,
                                $this->isProductFieldUpdated('attribute_price_impact') ? Tools::getValue('attribute_price') * Tools::getValue('attribute_price_impact') : null,
                                $this->isProductFieldUpdated('attribute_weight_impact') ? Tools::getValue('attribute_weight') * Tools::getValue('attribute_weight_impact') : null,
                                $this->isProductFieldUpdated('attribute_unit_impact') ? Tools::getValue('attribute_unity') * Tools::getValue('attribute_unit_impact') : null,
                                $this->isProductFieldUpdated('attribute_ecotax') ? Tools::getValue('attribute_ecotax') : null,
                                Tools::getValue('id_image_attr'),
                                Tools::getValue('attribute_reference'),
                                Tools::getValue('attribute_ean13'),
                                $this->isProductFieldUpdated('attribute_default') ? Tools::getValue('attribute_default') : null,
                                Tools::getValue('attribute_location'),
                                Tools::getValue('attribute_upc'),
                                $this->isProductFieldUpdated('attribute_minimal_quantity') ? Tools::getValue('attribute_minimal_quantity') : null,
                                $this->isProductFieldUpdated('available_date_attribute') ? Tools::getValue('available_date_attribute') : null, false);
                            StockAvailable::setProductDependsOnStock((int)$product->id, $product->depends_on_stock, null, (int)$id_product_attribute);
                            StockAvailable::setProductOutOfStock((int)$product->id, $product->out_of_stock, null, (int)$id_product_attribute);
                        }
                    } else {
                        $this->errors[] = Tools::displayError('You do not have permission to add this.');
                    }
                }
                // Add new
                else {
                    if ($this->tabAccess['add'] === '1') {
                        if ($product->productAttributeExists(Tools::getValue('attribute_combination_list'))) {
                            $this->errors[] = Tools::displayError('This combination already exists.');
                        } else {
                            $id_product_attribute = $product->addCombinationEntity(
                                Tools::getValue('attribute_wholesale_price'),
                                Tools::getValue('attribute_price') * Tools::getValue('attribute_price_impact'),
                                Tools::getValue('attribute_weight') * Tools::getValue('attribute_weight_impact'),
                                Tools::getValue('attribute_unity') * Tools::getValue('attribute_unit_impact'),
                                Tools::getValue('attribute_ecotax'),
                                0,
                                Tools::getValue('id_image_attr'),
                                Tools::getValue('attribute_reference'),
                                null,
                                Tools::getValue('attribute_ean13'),
                                Tools::getValue('attribute_default'),
                                Tools::getValue('attribute_location'),
                                Tools::getValue('attribute_upc'),
                                Tools::getValue('attribute_minimal_quantity'),
                                array(),
                                Tools::getValue('available_date_attribute')
                            );
                            StockAvailable::setProductDependsOnStock((int)$product->id, $product->depends_on_stock, null, (int)$id_product_attribute);
                            StockAvailable::setProductOutOfStock((int)$product->id, $product->out_of_stock, null, (int)$id_product_attribute);
                        }
                    } else {
                        $this->errors[] = Tools::displayError('You do not have permission to').'<hr>'.Tools::displayError('edit here.');
                    }
                }
                if (!count($this->errors)) {
                    $combination = new Combination((int)$id_product_attribute);
                    $combination->setAttributes(Tools::getValue('attribute_combination_list'));
					
					if(Tools::isSubmit('deactivate_price_update')){
						$combination->deactivate_price_update = Tools::getValue('deactivate_price_update', 0);
						$combination->save();
					}

                    // images could be deleted before
                    $id_images = Tools::getValue('id_image_attr');
                    if (!empty($id_images)) {
                        $combination->setImages($id_images);
                    }

                    $product->checkDefaultAttributes();
                    if (Tools::getValue('attribute_default')) {
                        Product::updateDefaultAttribute((int)$product->id);
                        if (isset($id_product_attribute)) {
                            $product->cache_default_attribute = (int)$id_product_attribute;
                        }

                        if ($available_date = Tools::getValue('available_date_attribute')) {
                            $product->setAvailableDate($available_date);
                        } else {
                            $product->setAvailableDate();
                        }
                    }
                }
            }
        }
    }
}
