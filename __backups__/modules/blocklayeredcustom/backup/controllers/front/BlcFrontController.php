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
include_once _PS_MODULE_DIR_ . 'blocklayeredcustom/classes/BlcFilterQuery.php';
include_once _PS_MODULE_DIR_ . 'blocklayeredcustom/classes/BlcAttributesQuery.php';
class BlcFrontController
{
	const BLOCK_SEPARATOR = '-';
	const FILTER_VALUES_SEPARATOR = '_';
	
    public $module;

    public $context;

    public $local_path;

    public function __construct($module, $context, $local_path, $_path, $file)
    {
        $this->module = $module;
        $this->context = $context;
        $this->local_path = $local_path;
        $this->_path = $_path;
		$this->file = $file;
		
		$this->orderWayConst = array(
			'ORDER_WAY_ASC' =>BlcAttributesQuery::ORDER_WAY_ASC,
			'ORDER_WAY_DESC' =>BlcAttributesQuery::ORDER_WAY_DESC,
			'ORDER_WAY_NONE' =>BlcAttributesQuery::ORDER_WAY_NONE
		);
		$this->orderColumnTypeConst = array(
			'ORDER_COLUMN_TYPE_NONE' =>BlcAttributesQuery::ORDER_COLUMN_TYPE_NONE,
			'ORDER_COLUMN_TYPE_ATTRIBUTE_GROUP' =>BlcAttributesQuery::ORDER_COLUMN_TYPE_ATTRIBUTE_GROUP,
			'ORDER_COLUMN_TYPE_PRICE' =>BlcAttributesQuery::ORDER_COLUMN_TYPE_PRICE
		);
		$this->defaultOrderWay = BlcAttributesQuery::ORDER_WAY_ASC;
		$this->defaultOrderColumn = 0;
		$this->defaultOrderColumnType = BlcAttributesQuery::ORDER_COLUMN_TYPE_PRICE;
    }
	public function assignCenterConst()
    {
		$this->context->smarty->assign(
			array(
				'selectedOrderWay' => $this->defaultOrderWay,
				'selectedOrderColumn' => $this->defaultOrderColumn,
				'selectedOrderColumnType' => $this->defaultOrderColumnType,
				'orderWayConst' => $this->orderWayConst,
				'orderColumnTypeConst' => $this->orderColumnTypeConst
			)
		);
	}
    public function getBlockList($list=null, $selectedFilters = array())
    {
		if($list===null){
			$list = BlcFilterBlock::getAll(true);
		}
		$hideFilterWhenNoProduct = (int)Configuration::get('BLC_HIDE_FILTER_WHEN_NO_PRODUCT');
		$showProductQuantity = (int)Configuration::get('BLC_SHOW_PRODUCT_NUMBER');
		foreach($list as $key => $value){
			if($value['block_type']==BlcFilterBlock::BLOCK_TYPE_ATTRIBUTE_GROUP){
				$blcAttributeGroup = BlcAttributeGroup::getByIdBlockFilter($value['id_blc_filter_block']);
				$attributeGroup = new AttributeGroup($blcAttributeGroup['id_attribute_group'], $this->context->language->id);
				$list[$key]['label'] = $attributeGroup->name;
				$list[$key]['value_type'] = $attributeGroup->group_type;
				$list[$key]['selecteds'] = isset($selectedFilters[$value['block_type']][$value['id_blc_filter_block']])?$selectedFilters[$value['block_type']][$value['id_blc_filter_block']]:array();
				$list[$key]['selectables'] = isset($list[$key]['selectables'])?$list[$key]['selectables']:AttributeGroup::getAttributes($this->context->language->id, $attributeGroup->id);
				$list[$key]['selectables'] = $this->formatSelectables($list[$key]['selectables'], Attribute::$definition['table']);
			}else{
				$list[$key]['label'] = $this->module->definition->blockTypeDefinition[$value['block_type']]['label'];
				if($value['block_type']==BlcFilterBlock::BLOCK_TYPE_MANUFACTURER){
					$list[$key]['selectables'] = isset($list[$key]['selectables'])?$list[$key]['selectables']:BlcFilterBlock::getSelectableManufacturers();
					$list[$key]['selectables'] = $this->formatSelectables($list[$key]['selectables'], Manufacturer::$definition['table']);
				}elseif($value['block_type']==BlcFilterBlock::BLOCK_TYPE_CARRIER){
					$list[$key]['selectables'] = isset($list[$key]['selectables'])?$list[$key]['selectables']:BlcFilterBlock::getSelectableCarriers($this->context->language->id);
					$list[$key]['selectables'] = $this->formatSelectables($list[$key]['selectables'], Carrier::$definition['table']);
				}elseif($value['block_type']==BlcFilterBlock::BLOCK_TYPE_PRODUCT){
					$list[$key]['selectables'] = isset($list[$key]['selectables'])?$list[$key]['selectables']:BlcFilterQuery::getSelectablesProduct($this->context->language->id, $this->context->shop->id, $selectedFilters);
					$list[$key]['selectables'] = $this->formatSelectables($list[$key]['selectables'], Product::$definition['table']);
				}elseif($value['block_type']==BlcFilterBlock::BLOCK_TYPE_PRICE){
					$list[$key]['selectables'] = BlcFilterBlock::getPricesRange();
					if(isset($selectedFilters[$value['block_type']])){
						$price_filter = BlcFilterQuery::formatFilterPriceValue($selectedFilters[$value['block_type']]);
						$list[$key]['selectables']['values'][0] = $price_filter['min'];
						$list[$key]['selectables']['values'][1] = $price_filter['max'];
					}
					$list[$key]['unit'] = $this->context->currency->sign;
					$list[$key]['format'] = $this->context->currency->format;
				}
				$list[$key]['selecteds'] = isset($selectedFilters[$value['block_type']])?$selectedFilters[$value['block_type']]:array();
			}
			if(($value['block_type']!=BlcFilterBlock::BLOCK_TYPE_PRICE)&&($value['filter_type']!=BlcFilterBlock::FILTER_TYPE_DROPDOWN_LIST)){
				$list[$key]['selectables_count'] = count($list[$key]['selectables']);
				$list[$key]['show_see_more'] = true;
				if(!empty($list[$key]['selecteds'])){
					$sorter = new BlcSelectableSorter($list[$key]['selectables'], $list[$key]['selecteds']);
					$list[$key]['selectables'] = $sorter->sortList();
				}
			}
			if(empty($list[$key]['selectables']) && $hideFilterWhenNoProduct){
				unset($list[$key]);
			}
		}
		return $list;
    }
	
	public function formatSelectables($selectables, $table, $definition = array())
    {
		$definition = empty($definition)?array('title' => 'name', 'value' => 'id_'.$table):$definition;
		foreach($selectables as $key => $selectable){
			$selectables[$key]['title'] = $selectable[$definition['title']];
			$selectables[$key]['value'] = $selectable[$definition['value']];
		}
		return $selectables;
    }
	
	public function renderFilter($filterKey = '', $ajaxLoad = false, $triggerBlockId = 0, $triggerBlockValues = array())
    {
		$template = 'filter_block';
		$cacheFilterKey = $this->getCacheFiltersStringKey($filterKey).$triggerBlockId;
		$cacheId = $this->getCacheId($template.$cacheFilterKey);
		$template.='.tpl';
		$this->context->smarty->assign(
			array(
				'blfFrontAjaxUrl' => $this->module->getUrl().'blocklayeredcustom-ajax.php',
				'blcImgDir' => $this->module->getUrl().'views/img/',
				'BLOCK_SEPARATOR' => self::BLOCK_SEPARATOR,
				'ajaxLoad' => $ajaxLoad,
				'FILTER_VALUES_SEPARATOR' => self::FILTER_VALUES_SEPARATOR
			)
		);
		if (! $this->module->isCached($template, $cacheId)) {
			$limit = (int)Configuration::get('BLC_MAX_PRODUCT_ATTRIBUTES_PER_LOAD');
			$selectedFilters = $this->getFilterLeftValue($filterKey);
			$dataCombinations = BlcAttributesQuery::getCombinations($selectedFilters, $this->context->language->id, $this->context->shop->id, 0,
			$limit, $this->defaultOrderWay, $this->defaultOrderColumnType, $this->defaultOrderColumn, true, $triggerBlockId, $triggerBlockValues);
			$this->hasNoCombinations = empty($dataCombinations['combinations']);
			$listBlockFilter = $this->getBlockList($dataCombinations['listBlock'], $selectedFilters);
			$this->assignCenterConst();
			$this->assignCombinations($dataCombinations);
			$this->context->smarty->assign(
				array(
					'hasNoCombinations' => $this->hasNoCombinations,
					'hasFiltersSelected' => !empty($selectedFilters),
					'blcImgDir' => $this->module->getUrl().'views/img/',
					'templateFolder' => $this->local_path . 'views/templates/hook/',
					'groups' => $dataCombinations['groups'],
					'selectedFilters' => $this->formatSelectedsFilter($selectedFilters),
					'listBlockFilter' => $listBlockFilter,
					'filterTypeDefinition' => $this->module->definition->filterTypeDefinition,
					'filterTypeConst' => $this->module->definition->filterTypeConst,
					'maxFilterItems' => (int)Configuration::get('BLC_MAX_ITEM_PER_FILTER_GROUP'),
					'showProductQuantity' => 0
				)
			);
		}
		
		return $this->module->display($this->file, $template, $cacheId);
    }
	public function formatSelectedsFilter($selectedFilters)
    {
		$result = array();
		foreach($selectedFilters as $blockType => $values){
			$separator = ', ';
			if($blockType==BlcFilterBlock::BLOCK_TYPE_ATTRIBUTE_GROUP){
				foreach($values as $idFilterBlock => $attributes){
					$blcAttributeGroup = BlcAttributeGroup::getByIdBlockFilter($idFilterBlock);
					$attributeGroup = new AttributeGroup($blcAttributeGroup['id_attribute_group'], $this->context->language->id);
					$names = array();
					foreach($attributes as $idAttribute){
						$attribute = new Attribute($idAttribute, $this->context->language->id);
						$names[]=$attribute->name;
					}
					$result[] = array(
						'label' => $attributeGroup->name,
						'names' => implode($separator, $names),
						'idBlock' => $idFilterBlock,
						'blockType' => $blockType,
					);
				}
			}else{
				$names = array();
				if($blockType==BlcFilterBlock::BLOCK_TYPE_MANUFACTURER){
					foreach($values as $idObject){
						$object = new Manufacturer($idObject, $this->context->language->id);
						$names[]=$object->name;
					}
				}elseif($blockType==BlcFilterBlock::BLOCK_TYPE_CARRIER){
					foreach($values as $idObject){
						$object = new Carrier($idObject, $this->context->language->id);
						$names[]=$object->name;
					}
				}elseif($blockType==BlcFilterBlock::BLOCK_TYPE_PRODUCT){
					foreach($values as $idObject){
						$object = new Product($idObject, false, $this->context->language->id);
						$names[]=$object->name;
					}
				}elseif($blockType==BlcFilterBlock::BLOCK_TYPE_PRICE){
					$price_filter = BlcFilterQuery::formatFilterPriceValue($values);
					$names[]= Tools::displayPrice($price_filter['min'], $this->context->currency);
					$names[]= Tools::displayPrice($price_filter['max'], $this->context->currency);;
					$separator = ' - ';
				}
				$block = BlcFilterBlock::getByType($blockType);
				if(!empty($block)){
					$result[] = array(
						'label' => $this->module->definition->blockTypeDefinition[$blockType]['label'],
						'names' => implode($separator, $names),
						'idBlock' => (int)$block['id_blc_filter_block'],
						'blockType' => $blockType,
					);
				}
			}
		}
		return $result;
    }
	public function getCacheFiltersStringKey($selectedFiltersString)
    {
		return empty($selectedFiltersString)?'default_filter':$selectedFiltersString;
	}
	public function renderProductAttributes($selectedFiltersString, $page=1, $limit = 0, $orderWay = null, $orderColumnType = null, $orderColumn = null)
    {
		$cacheFilterKey = $this->getCacheFiltersStringKey($selectedFiltersString);
		$orderWay = ($orderWay===null)?$this->defaultOrderWay:$orderWay;
		$orderColumnType = ($orderColumnType===null)?$this->defaultOrderColumnType:$orderColumnType;
		$orderColumn = ($orderColumn===null)?$this->defaultOrderColumn:$orderColumn;
		$limit = empty($limit)?(int)Configuration::get('BLC_MAX_PRODUCT_ATTRIBUTES_PER_LOAD'):$limit;
		$start = ((int)$page-1)*$limit;
		$template = 'product_attributes_list';
		$cacheKey = $template.$cacheFilterKey.$start.$limit.$orderWay.$orderColumnType.$orderColumn;
		$cacheId = $this->getCacheId($cacheKey);
		$template.='.tpl';
		$this->hasNoCombinations = false;
		if (! $this->module->isCached($template, $cacheId)) {
			$selectedFilters = $this->getFilterLeftValue($selectedFiltersString);
			$dataCombinations = BlcAttributesQuery::getCombinations($selectedFilters, $this->context->language->id, $this->context->shop->id, $start, $limit, $orderWay, $orderColumnType, $orderColumn);
			$this->assignCombinations($dataCombinations);
			
		}
		return $this->module->display($this->file, $template, $cacheId);
    }
	public function assignCombinations($dataCombinations)
    {
		$this->context->smarty->assign(
			array(
				'PS_CATALOG_MODE'     => (bool)Configuration::get('PS_CATALOG_MODE') || (Group::isFeatureActive() && !(bool)Group::getCurrent()->show_prices),
				'combinations' => $dataCombinations['combinations'],
				'hasMoreCombinations' => $dataCombinations['hasMore'],
				'link' => $this->context->link,
				'defaultAddCartQuantity' => (int)Configuration::get('BLC_DEFAULT_ADD_TO_CART_NUMBER')
			)
		);
    }
	
	public function getCacheId($identifier)
    {
        return $this->module->smartyGetCacheId($this->module->name . $this->context->currency->sign . $identifier);
    }
	
	public function ajaxCall()
    {
        $ajaxAction = Tools::getValue('action');
		$methodName = 'ajaxProcess'.$ajaxAction;
		$methodName = is_callable(array($this, $methodName))?$methodName:
			'ajax'.ucfirst($ajaxAction);
		if (is_callable(array($this, $methodName))) {
			$this->$methodName();
		}
    }
	
	public function ajaxFilterLeft()
    {
		$selectedFiltersString = Tools::getValue('selected_filters');
		$triggerBlockId = Tools::getValue('trigger_block_id');
		$triggerBlockValuesString = Tools::getValue('trigger_block_values');
		$triggerBlockValues = explode(',', $triggerBlockValuesString);
		$content = $this->renderFilter($selectedFiltersString, true, $triggerBlockId, $triggerBlockValues);
		$this->ajaxDie(Tools::jsonEncode(array(
            'hasError' => false,
            'form' => $content
			)
        ));
    }
	
	public function ajaxSortProductAttributes()
    {
		$way = (int)Tools::getValue('way');
		$type = (int)Tools::getValue('type');
		$column = (int)Tools::getValue('column');
		$selectedFiltersString = Tools::getValue('selected_filters');
		$productAttributesListContent = $this->renderProductAttributes($selectedFiltersString, 1, 0, $way, $type, $column);
		$this->ajaxDie(Tools::jsonEncode(array(
            'hasError' => false,
            'formProductAttributes' => $productAttributesListContent,
			)
        ));
    }
	
	public function ajaxLoadMoreAttributes()
    {
		$way = (int)Tools::getValue('way');
		$type = (int)Tools::getValue('type');
		$column = (int)Tools::getValue('column');
		$page = (int)Tools::getValue('page');
		$selectedFiltersString = Tools::getValue('selected_filters');
		$productAttributesListContent = $this->renderProductAttributes($selectedFiltersString, $page, 0, $way, $type, $column);
		$this->ajaxDie(Tools::jsonEncode(array(
            'hasError' => false,
            'formProductAttributes' => $productAttributesListContent,
			)
        ));
    }
	
	public function ajaxDie($content)
    {
		die($content);
    }
	
	public function getFilterLeftValue($selectedFiltersString)
    {
		$selectedFilters = array();
		if(!empty($selectedFiltersString)){
			$tabBlocks= explode(self::BLOCK_SEPARATOR, $selectedFiltersString);
			foreach($tabBlocks as $block){
				$tabValues = explode(self::FILTER_VALUES_SEPARATOR, $block);
				$blockId = $tabValues[0];
				$blockType = $tabValues[1];
				unset($tabValues[0]);
				unset($tabValues[1]);
				$values = array();
				foreach($tabValues as $value){
					$values[] = (int)$value;
				}
				if($blockType==BlcFilterBlock::BLOCK_TYPE_ATTRIBUTE_GROUP){
					$selectedFilters[$blockType][$blockId] = $values;
				}else{
					$selectedFilters[$blockType]  = isset($selectedFilters[$blockType])?array_merge($selectedFilters[$blockType], $tabValues):$values;
				}
			}
		}
		return $selectedFilters;
    }
}

class BlcSelectableSorter
{
	public function __construct($selectables, $selecteds){
		$this->selectables = $selectables;
		$this->selecteds = $selecteds;
	}
	
	public function sortList(){
		$list = $this->selectables;
		usort($list, [$this,'compare']);
		return $list;
	}
	
	public function compare($item1, $item2){
		if(in_array($item1['value'], $this->selecteds) && !in_array($item2['value'], $this->selecteds)){
			return -1;
		}elseif(in_array($item2['value'], $this->selecteds) && !in_array($item1['value'], $this->selecteds)){
			return 1;
		}elseif(!in_array($item1['value'], $this->selecteds) && !in_array($item2['value'], $this->selecteds)){
			return 1;
		}else{
			return 0;
		}
	}
}
