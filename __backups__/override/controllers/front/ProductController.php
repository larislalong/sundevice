<?php

include_once  _PS_OVERRIDE_DIR_. 'classes/ProductPack.php';
include_once  _PS_OVERRIDE_DIR_. 'classes/ProductSupportedLTE.php';
class ProductController extends ProductControllerCore
{
	const PACKEGING_ECOBOX = 1;
	const PACKEGING_CRYSTALS_BOX = 2;
	const PACKEGING_CUSTOM_BOX = 3;
	public function init()
    {
		$this->packegingDefinition = array(
			'PACKEGING_ECOBOX' => array(
				'code' =>self::PACKEGING_ECOBOX,
				'label' =>'Eco box',
				'image_key' =>'858733f793f2473b963ced673fc658fe_ecobox',
			),
			'PACKEGING_CRYSTALS_BOX' => array(
				'code' =>self::PACKEGING_CRYSTALS_BOX,
				'label' =>'Crystal box',
				'image_key' =>'7ef2adaae73f5c692a15bc8cb52db7ed_crystal_box',
			),
			'PACKEGING_CUSTOM_BOX' => array(
				'code' =>self::PACKEGING_CUSTOM_BOX,
				'label' =>'Custom box',
				'image_key' =>'',
			),
		);
		parent::init();
	}
    public function initContent()
    {
        parent::initContent();
        $supportedLteNetwork = array();
        $list = ProductSupportedLTE::getAllDisplayable($this->product, $this->context->language->id, $this->context->smarty);
        foreach ($list as $value) {
            $supportedLteNetwork[$value['id_attribute']] = $value['content'];
        }
        /*$included_accessories = empty($this->product->included_accessories)?Hook::exec('displayIncludedAccessories'):$this->product->included_accessories;
        $tech_specs = empty($this->product->tech_specs)?Hook::exec('displayTechSpecs'):$this->product->tech_specs;
        $what_is_model_number = empty($this->product->what_is_model_number)?Hook::exec('displayWhatIsAModelNumber'):$this->product->what_is_model_number;
        */
		
        $included_accessories = $this->product->included_accessories;
        $tech_specs = $this->product->tech_specs;
		$what_is_model_number_lte_list = ProductSupportedLTE::getAllDisplayable($this->product, $this->context->language->id, $this->context->smarty, false);
        $what_is_model_number = $this->product->what_is_model_number;
        
        $what_is_a_grade = empty($this->product->what_is_a_grade)?Hook::exec('displayWhatIsAGrade'):$this->product->what_is_a_grade;
        $available_packeging = empty($this->product->available_packeging)?Hook::exec('displayAvailablePackeging', array('id_element' => 8)):$this->product->available_packeging;
        $clear_grading_systeme = Hook::exec('displayClearGradingSysteme', array('id_element' => 6));
		$tested_phone = empty($this->product->tested_phone)?Hook::exec('displayTestedPhone'):$this->product->tested_phone;
        $productmanufacturer = Manufacturer::getProducts($this->product->id_manufacturer, $this->context->language->id, 1, 100, 'quantity');
		$link_get_custom_content = $this->context->link->getProductLink($this->product);
        if (!strpos($link_get_custom_content, '?')) {
            $link_get_custom_content .= '?ajax=1';
        } else {
            $link_get_custom_content .= '&ajax=1';
        }
		$link_get_custom_content.='&action=GetCustomContent&content_name=';
       // $this->context->smarty->assign('tested_phone', $tested_phone);
		$this->context->smarty->assign(
            array(
                'tested_phone' => $tested_phone,
                'supportedLteNetwork' => $supportedLteNetwork,
                'modelIdAttributeGroup' => ProductSupportedLTE::getModelIdAttributeGroup(),
                'gradeIdAttributeGroup' => ProductSupportedLTE::getGradeIdAttributeGroup(),
                
                'included_accessories' => $included_accessories,
                'tech_specs' => $tech_specs,
                'what_is_model_number' => $what_is_model_number,
                'what_is_model_number_lte_list' => $what_is_model_number_lte_list,
                'what_is_a_grade' => $what_is_a_grade,
                'available_packeging' => $available_packeging,
                'clear_grading_systeme' => $clear_grading_systeme,
                /*'tested_phone_content' => $this->context->smarty->fetch(_PS_THEME_DIR_.'tested_phone.tpl'),*/
                'packeging' => Hook::exec('displayPackeging', array('id_element' => 8)),
                'pack_list' => ProductPack::getAll($this->product->id, $this->context->language->id, true),
                'productmanufacturer' => $productmanufacturer,
                'link_get_custom_content' => $link_get_custom_content,
            )
        );
		
		$this->context->smarty->assign(
            array(
                'packegingDefinition' => $this->packegingDefinition
            )
        );
    }
    
    public function setMedia()
    {
        parent::setMedia();
        $this->addJS(array(
            _THEME_JS_DIR_.'additional-product.js',
            // _THEME_JS_DIR_.'packaging.js'
        ));
    }
	
	public function postProcess()
    {
        parent::postProcess();
        if (Tools::getValue('ajax')&&(Tools::getValue('action')=='ChangePackeging')) {
            $this->displayAjaxChangePackeging();
        }elseif (Tools::getValue('ajax')&&(Tools::getValue('action')=='DeleteChoosenPackeging')) {
            $this->displayAjaxDeleteChoosenPackeging();
        }elseif (Tools::getValue('ajax')&&(Tools::getValue('action')=='LearnMore')) {
            $this->displayAjaxLearnMore();
        }elseif (Tools::getValue('ajax')&&(Tools::getValue('action')=='GetCustomContent')) {
            $this->displayAjaxGetCustomContent();
        }
    }
	public function displayAjaxLearnMore()
    {
		$errors = array();
		$item = (int)Tools::getValue('item_to_load');
		$hasMore = ($item<3);
		if($item==2){
			$tested_phone = empty($this->product->tested_phone)?Hook::exec('displayTestedPhone'):$this->product->tested_phone;
			$this->context->smarty->assign('tested_phone', $tested_phone);
			$content = $this->context->smarty->fetch(_PS_THEME_DIR_.'tested_phone.tpl');
		}elseif($item==3){
			$content = Hook::exec('displayPackeging');
		}
		$this->ajaxDie(Tools::jsonEncode(array(
            'hasError' => false,
            'hasMore' => $hasMore,
            'content' => $content
			)
        ));
	}
	public function displayAjaxGetCustomContent()
    {
		$name = pSQL(Tools::getValue('content_name'));
		$this->ajaxDie(Hook::exec($name));
	}
	
    /**
     * Assign template vars related to attribute groups and colors
     */
    protected function assignAttributesGroups()
    {
        $colors = array();
        $groups = array();

        // @todo (RM) should only get groups and not all declination ?
        $attributes_groups = $this->product->getAttributesGroups($this->context->language->id);
        if (is_array($attributes_groups) && $attributes_groups) {
            $combination_images = $this->product->getCombinationImages($this->context->language->id);
            $combination_prices_set = array();
            foreach ($attributes_groups as $k => $row) {
                // Color management
                if (isset($row['is_color_group']) && $row['is_color_group'] && (isset($row['attribute_color']) && $row['attribute_color']) || (file_exists(_PS_COL_IMG_DIR_.$row['id_attribute'].'.jpg'))) {
                    $colors[$row['id_attribute']]['value'] = $row['attribute_color'];
                    $colors[$row['id_attribute']]['name'] = $row['attribute_name'];
                    if (!isset($colors[$row['id_attribute']]['attributes_quantity'])) {
                        $colors[$row['id_attribute']]['attributes_quantity'] = 0;
                    }
                    $colors[$row['id_attribute']]['attributes_quantity'] += (int)$row['quantity'];
                }
                if (!isset($groups[$row['id_attribute_group']])) {
					
                    $groups[$row['id_attribute_group']] = array(
                        'group_name' => $row['group_name'],
                        'name' => $row['public_group_name'],
                        'group_type' => $row['group_type'],
                        'default' => -1,
                    );
                }

                $groups[$row['id_attribute_group']]['attributes'][$row['id_attribute']] = $row['attribute_name'];
                $groups[$row['id_attribute_group']]['descriptions'][$row['id_attribute']] = $row['attribute_description'];
                if ($row['default_on'] && $groups[$row['id_attribute_group']]['default'] == -1) {
                    $groups[$row['id_attribute_group']]['default'] = (int)$row['id_attribute'];
                }
                if (!isset($groups[$row['id_attribute_group']]['attributes_quantity'][$row['id_attribute']])) {
                    $groups[$row['id_attribute_group']]['attributes_quantity'][$row['id_attribute']] = 0;
                }
                $groups[$row['id_attribute_group']]['attributes_quantity'][$row['id_attribute']] += (int)$row['quantity'];

                $combinations[$row['id_product_attribute']]['attributes_values'][$row['id_attribute_group']] = $row['attribute_name'];
                $combinations[$row['id_product_attribute']]['attributes'][] = (int)$row['id_attribute'];
                $combinations[$row['id_product_attribute']]['price'] = (float)Tools::convertPrice($row['price'], null, Context::getContext()->currency);
				
				if($this->context->shop->id == 2){
					$currentProductFloorPrice = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('SELECT floor_price_us AS floor_price FROM `sundev_product_attribute` WHERE id_product_attribute = "'.$row['id_product_attribute'].'"');
					// var_dump($this->product);die;
				}else{
					$currentProductFloorPrice = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('SELECT floor_price FROM `sundev_product_attribute` WHERE id_product_attribute = "'.$row['id_product_attribute'].'"');
					
					$currentProductUsp = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('SELECT usp FROM `sundev_product_attribute` WHERE id_product_attribute = "'.$row['id_product_attribute'].'"');
				
					$currentProductUsp = isset($currentProductUsp['usp'])?floatval($currentProductUsp['usp']):0;
					$combinations[$row['id_product_attribute']]['usp'] = (float)Tools::convertPrice($currentProductUsp, null, Context::getContext()->currency);
				}
				
				$currentProductFloorPrice = isset($currentProductFloorPrice['floor_price'])?floatval($currentProductFloorPrice['floor_price']):0;
                $combinations[$row['id_product_attribute']]['floor_price'] = (float)Tools::convertPrice($currentProductFloorPrice, null, Context::getContext()->currency);

                // Call getPriceStatic in order to set $combination_specific_price
                if (!isset($combination_prices_set[(int)$row['id_product_attribute']])) {
                    Product::getPriceStatic((int)$this->product->id, false, $row['id_product_attribute'], 6, null, false, false, 1, false, null, null, null, $combination_specific_price);
                    $combination_prices_set[(int)$row['id_product_attribute']] = true;
                    $combinations[$row['id_product_attribute']]['specific_price'] = $combination_specific_price;
                }
				if(isset($_GET['checkdev'])){
					var_dump($row['price'],$combinations[$row['id_product_attribute']]['price'],$combinations[$row['id_product_attribute']]['specific_price']);die;
				}
                $combinations[$row['id_product_attribute']]['ecotax'] = (float)$row['ecotax'];
                $combinations[$row['id_product_attribute']]['weight'] = (float)$row['weight'];
                $combinations[$row['id_product_attribute']]['quantity'] = (int)$row['quantity'];
                $combinations[$row['id_product_attribute']]['reference'] = $row['reference'];
                $combinations[$row['id_product_attribute']]['unit_impact'] = Tools::convertPrice($row['unit_price_impact'], null, Context::getContext()->currency);
                $combinations[$row['id_product_attribute']]['minimal_quantity'] = $row['minimal_quantity'];
                if ($row['available_date'] != '0000-00-00' && Validate::isDate($row['available_date'])) {
                    $combinations[$row['id_product_attribute']]['available_date'] = $row['available_date'];
                    $combinations[$row['id_product_attribute']]['date_formatted'] = Tools::displayDate($row['available_date']);
                } else {
                    $combinations[$row['id_product_attribute']]['available_date'] = $combinations[$row['id_product_attribute']]['date_formatted'] = '';
                }

                if (!isset($combination_images[$row['id_product_attribute']][0]['id_image'])) {
                    $combinations[$row['id_product_attribute']]['id_image'] = -1;
                } else {
                    $combinations[$row['id_product_attribute']]['id_image'] = $id_image = (int)$combination_images[$row['id_product_attribute']][0]['id_image'];
                    if ($row['default_on']) {
                        if (isset($this->context->smarty->tpl_vars['cover']->value)) {
                            $current_cover = $this->context->smarty->tpl_vars['cover']->value;
                        }

                        if (is_array($combination_images[$row['id_product_attribute']])) {
                            foreach ($combination_images[$row['id_product_attribute']] as $tmp) {
                                if ($tmp['id_image'] == $current_cover['id_image']) {
                                    $combinations[$row['id_product_attribute']]['id_image'] = $id_image = (int)$tmp['id_image'];
                                    break;
                                }
                            }
                        }

                        if ($id_image > 0) {
                            if (isset($this->context->smarty->tpl_vars['images']->value)) {
                                $product_images = $this->context->smarty->tpl_vars['images']->value;
                            }
                            if (isset($product_images) && is_array($product_images) && isset($product_images[$id_image])) {
                                $product_images[$id_image]['cover'] = 1;
                                $this->context->smarty->assign('mainImage', $product_images[$id_image]);
                                if (count($product_images)) {
                                    $this->context->smarty->assign('images', $product_images);
                                }
                            }
                            if (isset($this->context->smarty->tpl_vars['cover']->value)) {
                                $cover = $this->context->smarty->tpl_vars['cover']->value;
                            }
                            if (isset($cover) && is_array($cover) && isset($product_images) && is_array($product_images)) {
                                $product_images[$cover['id_image']]['cover'] = 0;
                                if (isset($product_images[$id_image])) {
                                    $cover = $product_images[$id_image];
                                }
                                $cover['id_image'] = (Configuration::get('PS_LEGACY_IMAGES') ? ($this->product->id.'-'.$id_image) : (int)$id_image);
                                $cover['id_image_only'] = (int)$id_image;
                                $this->context->smarty->assign('cover', $cover);
                            }
                        }
                    }
                }
            }

            // wash attributes list (if some attributes are unavailables and if allowed to wash it)
            if (!Product::isAvailableWhenOutOfStock($this->product->out_of_stock) && Configuration::get('PS_DISP_UNAVAILABLE_ATTR') == 0) {
                foreach ($groups as &$group) {
                    foreach ($group['attributes_quantity'] as $key => &$quantity) {
                        if ($quantity <= 0) {
                            unset($group['attributes'][$key]);
                        }
                    }
                }

                foreach ($colors as $key => $color) {
                    if ($color['attributes_quantity'] <= 0) {
                        unset($colors[$key]);
                    }
                }
            }
            foreach ($combinations as $id_product_attribute => $comb) {
                $attribute_list = '';
                foreach ($comb['attributes'] as $id_attribute) {
                    $attribute_list .= '\''.(int)$id_attribute.'\',';
                }
                $attribute_list = rtrim($attribute_list, ',');
                $combinations[$id_product_attribute]['list'] = $attribute_list;
            }
			
			
			if(Tools::getValue('devCheck2')){
				$product_attribute = new Product((int)$id_product_attribute);
				var_dump($combinations);die;
			}

            $this->context->smarty->assign(array(
				'inAdminGroup' => in_array(5, Customer::getGroupsStatic(Context::getContext()->cart->id_customer)) ? true : false,
                'id_shop' => $this->context->shop->id,
                'groups' => $groups,
                'colors' => (count($colors)) ? $colors : false,
                'combinations' => $combinations,
                'combinationImages' => $combination_images
            ));
        }
    }
	public function displayAjaxChangePackeging()
    {
		$errors = array();
		if (!$this->context->cart->id && isset($_COOKIE[$this->context->cookie->getName()])) {
			$this->context->cart->add();
			$this->context->cookie->id_cart = (int)$this->context->cart->id;
		}
		$fileName = "";
		$code = Tools::getValue('code');
		if(!empty($code)){
			if($code==self::PACKEGING_CUSTOM_BOX){
				if($this->pictureUpload()){
					$fileName = $this->uploadFileName;
				}
			}else{
				$definition = $this->getPackegingDefinition($code);
				$fileName = $definition['image_key'];
				$field_ids = $this->product->getCustomizationFieldIds();
				foreach ($field_ids as $field_id) {
					if ($field_id['type'] == Product::CUSTOMIZE_FILE) {
						$idCustomization = (int)$field_id['id_customization_field'];
						break;
					}
				}
				if($this->copyPackegingImage($fileName)){
					$this->context->cart->addPictureToProduct($this->product->id, $idCustomization, Product::CUSTOMIZE_FILE, $fileName);
				}else{
					$fileName = '';
				}
			}
		}
		$hasError = false;
		if(empty($fileName)){
			$hasError = true;
			$errors[]= Tools::displayError('An error occurred while saving the selected picture.');
		}
		$this->ajaxDie(Tools::jsonEncode(array(
            'hasError' => $hasError,
            'file_name' => $fileName,
			'errors' => $errors
			)
        ));
	}
	protected function copyPackegingImage($fileName)
    {
		$imagesFile = array(
			_PS_IMG_DIR_.'packeging/'.$fileName => _PS_UPLOAD_DIR_.$fileName,
			_PS_IMG_DIR_.'packeging/'.$fileName.'_small' => _PS_UPLOAD_DIR_.$fileName.'_small'
		);
		foreach($imagesFile as $source => $dest){
			if(!file_exists($source) || !@copy($source, $dest)){
				return false;
			}
		}
		return true;
	}
	public function displayAjaxDeleteChoosenPackeging()
    {
		$errors = array();
		$hasError = !$this->context->cart->deleteCustomizationToProduct($this->product->id, Tools::getValue('deletePicture'));
		if($hasError){
			$errors[]=Tools::displayError('An error occurred while deleting the selected picture.');
		}
		$this->ajaxDie(Tools::jsonEncode(array(
            'hasError' => $hasError,
            'errors' => $errors
			)
        ));
	}
	protected function getPackegingDefinition($code)
    {
		foreach($this->packegingDefinition as $definition){
			if($definition['code']==$code){
				return $definition;
			}
		}
	}
	protected function pictureUpload()
    {
		$this->uploadFileName = '';
        if (!$field_ids = $this->product->getCustomizationFieldIds()) {
            return false;
        }
        $authorized_file_fields = array();
        foreach ($field_ids as $field_id) {
            if ($field_id['type'] == Product::CUSTOMIZE_FILE) {
                $authorized_file_fields[(int)$field_id['id_customization_field']] = 'file'.(int)$field_id['id_customization_field'];
            }
        }
        $indexes = array_flip($authorized_file_fields);
        foreach ($_FILES as $field_name => $file) {
            if (in_array($field_name, $authorized_file_fields) && isset($file['tmp_name']) && !empty($file['tmp_name'])) {
                $file_name = md5(uniqid(rand(), true));
                if ($error = ImageManager::validateUpload($file, (int)Configuration::get('PS_PRODUCT_PICTURE_MAX_SIZE'))) {
                    $this->errors[] = $error;
                }

                $product_picture_width = (int)Configuration::get('PS_PRODUCT_PICTURE_WIDTH');
                $product_picture_height = (int)Configuration::get('PS_PRODUCT_PICTURE_HEIGHT');
                $tmp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS');
                if ($error || (!$tmp_name || !move_uploaded_file($file['tmp_name'], $tmp_name))) {
                    return false;
                }
                /* Original file */
                if (!ImageManager::resize($tmp_name, _PS_UPLOAD_DIR_.$file_name)) {
                    $this->errors[] = Tools::displayError('An error occurred during the image upload process.');
                }
                /* A smaller one */
                elseif (!ImageManager::resize($tmp_name, _PS_UPLOAD_DIR_.$file_name.'_small', $product_picture_width, $product_picture_height)) {
                    $this->errors[] = Tools::displayError('An error occurred during the image upload process.');
                } elseif (!chmod(_PS_UPLOAD_DIR_.$file_name, 0777) || !chmod(_PS_UPLOAD_DIR_.$file_name.'_small', 0777)) {
                    $this->errors[] = Tools::displayError('An error occurred during the image upload process.');
                } else {
					$this->uploadFileName = $file_name;
                    $this->context->cart->addPictureToProduct($this->product->id, $indexes[$field_name], Product::CUSTOMIZE_FILE, $file_name);
                }
                unlink($tmp_name);
            }
        }
        return true;
    }

}
