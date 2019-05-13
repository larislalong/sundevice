<?php
/**
 * 2015-2017 Crystals Services
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
 * needs please refer to http://www.crystals-services.com/ for more information.
 *
 * @author Crystals Services Sarl <contact@crystals-services.com>
 * @copyright 2015-2017 Crystals Services Sarl
 * @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 *          International Registered Trademark & Property of Crystals Services Sarl
 */

include_once _PS_MODULE_DIR_ . 'blocklayeredcustom/classes/BlcAttributeGroup.php';
include_once _PS_MODULE_DIR_ . 'blocklayeredcustom/classes/BlcFilterBlock.php';
include_once _PS_MODULE_DIR_ . 'blocklayeredcustom/classes/BlcProductIndex.php';
include_once _PS_MODULE_DIR_ . 'blocklayeredcustom/controllers/admin/BlcPriceRegenerationController.php';
class BlcAdminController
{
	const FIELD_CODE_SEPARATOR = '-';
	const CODE_SEPARATOR = '_';
    public $module;

    public $context;

    public $local_path;
	public $blcPriceRegenerationController;
    public function __construct($module, $context, $local_path, $_path)
    {
        $this->module = $module;
        $this->context = $context;
        $this->local_path = $local_path;
        $this->_path = $_path;
		$this->attributeGroupPreffix = $this->module->l('Attribute Group', __CLASS__) . ': ';
		$this->regenerationController = new BlcPriceRegenerationController($module, $context, $local_path, $_path);
    }

    public function renderForm($form, $values, $submitAction, $table, $identifier, $removeTagForm, $id = 0)
    {
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $table;
        $helper->identifier = $identifier;
        $helper->id = $id;
        $helper->module = $this->module;
        $helper->back_url = $this->module->getModuleHome();
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->submit_action = $submitAction;
        $helper->currentIndex = '';
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        
        $helper->tpl_vars = array(
            'fields_value' => $values,
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );
        $formContent = $helper->generateForm(array(
            $form
        ));
        if ($removeTagForm) {
            $formContent = $this->removeFormTag($formContent);
        }
        return $formContent;
    }

    public function removeFormTag($form)
    {
        $positionFormTagOpen = strpos($form, '<form');
        $positionFormTagOpen2 = strpos($form, '>', $positionFormTagOpen);
        $strFormTag = Tools::substr($form, $positionFormTagOpen, $positionFormTagOpen2 + 1);
        $form = str_replace(array(
            $strFormTag,
            '</form>'
        ), '', $form);
        return $form;
    }

    public function init()
    {
        $operationContent = '';
        if (Tools::getValue('blc_success')) {
            $message = $this->module->l('Save successfully', __CLASS__);
            if (! empty($message)) {
                $operationContent .= $this->module->displayConfirmation($message);
            }
        }
		if(Tools::getValue('price_regeneration')){
			$operationContent.=$this->regenerationController->init();
		}else{
			$operationContent.=$this->getHomeContent();
		}
        return $operationContent;
    }
	
	public function getHomeContent()
    {
		$formSubmitted = (bool) Tools::isSubmit('submitFilterBlockSave');
		if($formSubmitted){
			$this->processSave();
		}
		$configValues = $this->getConfigSavedValues();
		$listBlockFilter = $this->getBlockList();
		$configFormContent = $this->renderForm(
			$this->getConfigForm(),
			$configValues,
			'submitBlcConfig',
			'table_submitBlcConfig',
			'id_submitBlcConfig',
			true
		);
		$this->context->smarty->assign(array(
				'listBlockFilter' => $listBlockFilter,
				'configurationFormContent' => $configFormContent, 
				'priceRegenerationLink' => $this->module->getRegeneratePriceLink(),
				'filterTypeDefinition' => $this->module->definition->filterTypeDefinition,
				'blockTypeFilters' => $this->module->definition->blockTypeFilters
			)
		);
		
		return $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');
    }
	
	public function getConfigSavedValues()
    {
		return array(
			'BLC_USE_TAX_TO_FILTER_PRICE' => Configuration::get('BLC_USE_TAX_TO_FILTER_PRICE'),
			'BLC_USE_ROUNDING_TO_FILTER_PRICE' => Configuration::get('BLC_USE_ROUNDING_TO_FILTER_PRICE'),
			'BLC_HIDE_FILTER_WHEN_NO_PRODUCT' => Configuration::get('BLC_HIDE_FILTER_WHEN_NO_PRODUCT'),
			'BLC_SHOW_PRODUCT_NUMBER' => Configuration::get('BLC_SHOW_PRODUCT_NUMBER'),
			'BLC_DEFAULT_ADD_TO_CART_NUMBER' => Configuration::get('BLC_DEFAULT_ADD_TO_CART_NUMBER'),
			'BLC_MAX_PRODUCT_ATTRIBUTES_PER_LOAD' => Configuration::get('BLC_MAX_PRODUCT_ATTRIBUTES_PER_LOAD'),
			'BLC_DEFAULT_COMBINATIONS_REFERENCES' => Configuration::get('BLC_DEFAULT_COMBINATIONS_REFERENCES'),
			'BLC_PRICE_INDEX_AUTO_UPDATE' => Configuration::get('BLC_PRICE_INDEX_AUTO_UPDATE'),
			'BLC_MAX_ITEM_PER_FILTER_GROUP' => Configuration::get('BLC_MAX_ITEM_PER_FILTER_GROUP'),
			'BLC_CATEGORY_TO_EXCLUDE' => Configuration::get('BLC_CATEGORY_TO_EXCLUDE'),
		);
	}
	
	public function processSaveConfig()
    {
		$oldUseTax = (int)Configuration::get('BLC_USE_TAX_TO_FILTER_PRICE');
		Configuration::updateValue('BLC_USE_TAX_TO_FILTER_PRICE', (int)Tools::getValue('BLC_USE_TAX_TO_FILTER_PRICE'));
		Configuration::updateValue('BLC_USE_ROUNDING_TO_FILTER_PRICE', (int)Tools::getValue('BLC_USE_ROUNDING_TO_FILTER_PRICE'));
		Configuration::updateValue('BLC_HIDE_FILTER_WHEN_NO_PRODUCT', (int)Tools::getValue('BLC_HIDE_FILTER_WHEN_NO_PRODUCT'));
		Configuration::updateValue('BLC_SHOW_PRODUCT_NUMBER', (int)Tools::getValue('BLC_SHOW_PRODUCT_NUMBER'));
		Configuration::updateValue('BLC_DEFAULT_ADD_TO_CART_NUMBER', (int)Tools::getValue('BLC_DEFAULT_ADD_TO_CART_NUMBER'));
		Configuration::updateValue('BLC_MAX_PRODUCT_ATTRIBUTES_PER_LOAD', (int)Tools::getValue('BLC_MAX_PRODUCT_ATTRIBUTES_PER_LOAD'));
		Configuration::updateValue('BLC_DEFAULT_COMBINATIONS_REFERENCES', Tools::getValue('BLC_DEFAULT_COMBINATIONS_REFERENCES'));
		Configuration::updateValue('BLC_PRICE_INDEX_AUTO_UPDATE', (int)Tools::getValue('BLC_PRICE_INDEX_AUTO_UPDATE'));
		Configuration::updateValue('BLC_MAX_ITEM_PER_FILTER_GROUP', (int)Tools::getValue('BLC_MAX_ITEM_PER_FILTER_GROUP'));
		Configuration::updateValue('BLC_CATEGORY_TO_EXCLUDE', Tools::getValue('BLC_CATEGORY_TO_EXCLUDE'));
		$newUseTax = (int)Configuration::get('BLC_USE_TAX_TO_FILTER_PRICE');
		if($oldUseTax!=$newUseTax){
			BlcProductIndex::setDeprecated();
		}
	}
	
	public function processSave()
    {
		$this->processSaveConfig();
		$this->processSaveBlockFilter();
		$this->module->clearAllTplCache();
		$this->module->backToModuleHome('&blc_success=1');
	}
	
	public function processSaveBlockFilter()
    {
		$values = $this->getBlockFilterPostedValue();
		$filterBlock = new BlcFilterBlock();
		$blcAttributeGroup = new BlcAttributeGroup();
		foreach($values as $data){
			$filterBlock->id = (int) $data['id_blc_filter_block'];
			$filterBlock->filter_type = (int) $data['BLC_BLOCK_FILTER_FILTER_TYPE'];
			$filterBlock->block_type = (int) $data['block_type'];
			$filterBlock->position = (int) $data['BLC_BLOCK_FILTER_POSITION'];
			$filterBlock->active = (bool) $data['BLC_BLOCK_FILTER_ACTIVE'];
			$filterBlock->multiple = $this->module->definition->filterTypeDefinition[$filterBlock->filter_type]['configure_multiple']
				?(bool) $data['BLC_BLOCK_FILTER_MULTIPLE']
				:false;
			$filterBlock->save();
			if($filterBlock->block_type==BlcFilterBlock::BLOCK_TYPE_ATTRIBUTE_GROUP){
				$blcAttributeGroup->id = (int) $data['id_blc_attribute_group'];
				$blcAttributeGroup->id_attribute_group = (int) $data['id_attribute_group'];
				$blcAttributeGroup->id_blc_filter_block = (int) $filterBlock->id;
				$blcAttributeGroup->save();
			}
		}
	}
	
	public function getBlockFilterPostedValue()
    {
		$allValues = Tools::getAllValues();
		$values = array();
		foreach($allValues as $key => $value){
			if(strpos($key, 'BLC_BLOCK_FILTER')===0){
				$tab = explode(self::FIELD_CODE_SEPARATOR, $key);
				$values[$tab[1]][$tab[0]] = $value;
			}
		}
		$result = array();
		foreach($values as $code => $data){
			$result[] = $this->extractFieldsFromCode($code, $data);
		}
		return $result;
	}

    public function getBlockList()
    {
        $list = BlcFilterBlock::getAll();
		$addedAttributes = array();
		$maxPosition = 0;
		foreach($list as $key => $value){
			if($value['block_type']==BlcFilterBlock::BLOCK_TYPE_ATTRIBUTE_GROUP){
				$blcAttributeGroup = BlcAttributeGroup::getByIdBlockFilter($value['id_blc_filter_block']);
				$attributeGroup = new AttributeGroup($blcAttributeGroup['id_attribute_group'], $this->context->language->id);
				$list[$key]['label'] = $this->attributeGroupPreffix . $attributeGroup->name;
				$list[$key]['id_blc_attribute_group'] = $blcAttributeGroup['id_blc_attribute_group'];
				$list[$key]['id_attribute_group'] = $blcAttributeGroup['id_attribute_group'];
				$addedAttributes[]=$blcAttributeGroup['id_attribute_group'];
			}else{
				$list[$key]['label'] = $this->module->definition->blockTypeDefinition[$value['block_type']]['label'];
				$list[$key]['id_blc_attribute_group'] = 0;
				$list[$key]['id_attribute_group'] = 0;
			}
			$maxPosition = $list[$key]['position'];
			$list[$key]['code'] = $this->getFieldCode($list[$key]);
		}
		$attributeGroups = AttributeGroup::getAttributesGroups($this->context->language->id);
		foreach($attributeGroups as $group){
			if(!in_array($group['id_attribute_group'], $addedAttributes)){
				$maxPosition ++;
				$filter_type = ($group['is_color_group'])?BlcFilterBlock::FILTER_TYPE_COLOR:BlcFilterBlock::FILTER_TYPE_CHECKBOX;
				$newValue = array(
					'block_type' => BlcFilterBlock::BLOCK_TYPE_ATTRIBUTE_GROUP,
					'id_blc_filter_block' => 0,
					'multiple' => true,
					'filter_type' => $filter_type,
					'position' => $maxPosition,
					'active' => false,
					'label' => $this->attributeGroupPreffix . $group['name'],
					'id_blc_attribute_group' => 0,
					'id_attribute_group' => $group['id_attribute_group']
				);
				$newValue['code'] = $this->getFieldCode($newValue);
				$list[] = $newValue;
			}
		}
		return $list;
    }
	public function getFieldCode($filterBlock)
    {
		$code = self::FIELD_CODE_SEPARATOR.$filterBlock['block_type'].self::CODE_SEPARATOR.$filterBlock['id_blc_filter_block'].self::CODE_SEPARATOR.
		$filterBlock['id_blc_attribute_group'].self::CODE_SEPARATOR.$filterBlock['id_attribute_group'];
		return $code;
	}
	public function extractFieldsFromCode($code, $data)
    {
		$tab = explode(self::CODE_SEPARATOR, $code);
		$data['block_type'] = $tab[0];
		$data['id_blc_filter_block'] = $tab[1];
		$data['id_blc_attribute_group'] = $tab[2];
		$data['id_attribute_group'] = $tab[3];
		return $data;
	}
    public function includeBOCssJs()
    {
        $isBlcPage = ((Tools::getValue('module_name') == $this->module->name) ||
        (Tools::getValue('configure') == $this->module->name));
        $anchor = Tools::getValue('anchor');
        if ($isBlcPage && empty($anchor)) {
            $this->context->controller->addJquery();
            $this->context->controller->addJS($this->_path . 'views/js/back.js');
			$this->context->controller->addCSS($this->_path . 'views/css/back.css');
			if (Tools::getValue('price_regeneration')) {
                $this->context->controller->addJS($this->_path . 'views/js/ProductRegeneration.js');
                $this->context->controller->addJS($this->_path . 'views/js/RegenerationManager.js');
            }
        }
		if (!Tools::getValue('price_regeneration') && Configuration::get('BLC_PRICE_INDEX_AUTO_UPDATE') && Configuration::get('BLC_PRICE_DEPRECATED')) {
               $this->context->smarty->assign(array(
					'priceRegenerationLink' => $this->module->getRegeneratePriceLink()
				)
			);
			return $this->context->smarty->fetch($this->local_path . 'views/templates/admin/regenerate_redirect.tpl');
		}
    }
	public function getConfigForm()
    {
        $form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->module->l('Configuration', __CLASS__)
                ),
                'input' => array(
                    array(
                        'col' => 6,
                        'type' => 'switch',
                        'label' => $this->module->l('Auto update price index when necessary', __CLASS__),
                        'name' => 'BLC_PRICE_INDEX_AUTO_UPDATE',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->module->l('Enabled', __CLASS__)
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->module->l('Disabled', __CLASS__)
                            )
                        )
                    ),
					array(
                        'col' => 6,
                        'type' => 'switch',
                        'label' => $this->module->l('Use tax to filter price', __CLASS__),
                        'name' => 'BLC_USE_TAX_TO_FILTER_PRICE',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->module->l('Enabled', __CLASS__)
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->module->l('Disabled', __CLASS__)
                            )
                        )
                    ),
					array(
                        'col' => 6,
                        'type' => 'switch',
                        'label' => $this->module->l('Use rounding to filter price', __CLASS__),
                        'name' => 'BLC_USE_ROUNDING_TO_FILTER_PRICE',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->module->l('Enabled', __CLASS__)
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->module->l('Disabled', __CLASS__)
                            )
                        )
                    ),
					array(
                        'col' => 6,
                        'type' => 'switch',
                        'label' => $this->module->l('Hide filter values when no product is matching', __CLASS__),
                        'name' => 'BLC_HIDE_FILTER_WHEN_NO_PRODUCT',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->module->l('Enabled', __CLASS__)
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->module->l('Disabled', __CLASS__)
                            )
                        )
                    ),
					array(
                        'col' => 6,
                        'type' => 'switch',
                        'label' => $this->module->l('Show the number of matching products', __CLASS__),
                        'name' => 'BLC_SHOW_PRODUCT_NUMBER',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->module->l('Enabled', __CLASS__)
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->module->l('Disabled', __CLASS__)
                            )
                        )
                    ),
					array(
                        'col' => 6,
                        'type' => 'text',
                        'label' => $this->module->l('Number of product Attribute per load', __CLASS__),
                        'name' => 'BLC_MAX_PRODUCT_ATTRIBUTES_PER_LOAD'
                    ),
					array(
                        'col' => 6,
                        'type' => 'text',
                        'label' => $this->module->l('Number of elements per filter group', __CLASS__),
                        'name' => 'BLC_MAX_ITEM_PER_FILTER_GROUP'
                    ),
					array(
                        'col' => 6,
                        'type' => 'text',
                        'label' => $this->module->l('Default number of product to add to cart', __CLASS__),
                        'name' => 'BLC_DEFAULT_ADD_TO_CART_NUMBER'
                    ),
					array(
                        'col' => 7,
						'rows' => 10,
                        'type' => 'textarea',
                        'label' => $this->module->l('Default combinations references', __CLASS__),
						'desc' => sprintf($this->module->l('Please enter references of combinations to be displays when no filter is selected (separated by %s)', __CLASS__), BlcFilterBlock::COMBINATIONS_SEPARATOR),
                        'name' => 'BLC_DEFAULT_COMBINATIONS_REFERENCES'
                    ),
					array(
                        'col' => 7,
                        'type' => 'text',
                        'label' => $this->module->l('Id of categories that will not be displayed', __CLASS__),
						'desc' => sprintf($this->module->l('Please enter the id of categories that will not be displayed (separated by %s)', __CLASS__), BlcFilterBlock::COMBINATIONS_SEPARATOR),
                        'name' => 'BLC_CATEGORY_TO_EXCLUDE'
                    )
                ),
                'submit' => array(
                    'title' => $this->module->l('Save', __CLASS__)
                )
            )
        );
        return $form;
    }
}
