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

include_once _PS_MODULE_DIR_ . 'blocklayeredcustom/classes/BlcFilterBlock.php';
include_once _PS_MODULE_DIR_ . 'blocklayeredcustom/classes/BlcTools.php';
class BlcFilterQuery extends ObjectModel
{
	public static function addCategoryFilter($idCategory, $id_lang, $id_shop){
		$list = BlcTools::getArrayValues(Category::getChildren($idCategory, $id_lang, true, $id_shop), 'id_category');
		$list[] = $idCategory;
		
		$sql = ' INNER JOIN '._DB_PREFIX_.'category_product cat_p ON (p.id_product = cat_p.id_product'.
		' AND (cat_p.id_category IN ('.implode(',',$list).'))) ';
		return $sql;
	}
	
	public static function addManufacturersFilter($values){
		$sql = ' AND (p.id_manufacturer IN ('.implode(',',$values).'))';
        return $sql;
	}
	
	public static function addCarriersFilter($values, $idShop){
		$sql = ' AND  (pc.id_carrier_reference IN ('.
		implode(',',$values).')) ';
        return $sql;
	}
	public static function formatFilterPriceValue($values){
		$i=0;
		$price_filter = array();
		foreach($values as $value){
			if($i==0){
				$price_filter['min'] = $value;
				$i=1;
			}else{
				$price_filter['max'] = $value;
			}
		}
		return $price_filter;
	}
	public static function addPricesFilter($values, $idShop){
		$context = Context::getContext();
		$i=0;
		$price_filter = self::formatFilterPriceValue($values);
		$id_currency = (int)$context->currency->id;
		$sql = '';
		$useRound = Configuration::get('BLC_USE_ROUNDING_TO_FILTER_PRICE');
		$priceMinColumn = $useRound?'ROUND(pap.price)':'pap.price';
		$priceMaxColumn = $useRound?'ROUND(pap.price)':'pap.price';
		if (isset($price_filter) && $price_filter)
		{
			$sql = ' INNER JOIN '._DB_PREFIX_.'blc_product_attribute_price pap
			ON
			(
				'.$priceMinColumn.' >= '.(int)$price_filter['min'].'
				AND '.$priceMaxColumn.' <= '.(int)$price_filter['max'].'
				AND pap.`id_product_attribute` = pa.`id_product_attribute`
				AND pap.`id_shop` = '.(int)$context->shop->id.'
				AND pap.`id_currency` = '.$id_currency.'
			)';
		}
        return $sql;
	}
	
	public static function addProductsFilter($values, $idShop){
		$sql = ' AND (pa.id_product IN ('.implode(',',$values).'))';
        return $sql;
	}
	
	public static function getSelectablesProduct($idLang, $idShop, $selectedFilters = array()){
		
		$sql = 'SELECT * FROM '._DB_PREFIX_.'product p '.Shop::addSqlAssociation('product', 'p').
				' LEFT JOIN '._DB_PREFIX_.'product_lang pl ON ((p.id_product=pl.id_product) AND (pl.id_lang = '.(int)$idLang.'))'.
				' WHERE (product_shop.`active` = 1) AND (product_shop.`visibility` IN ("both", "catalog"))'. BlcFilterBlock::addCategoriesRestriction().
				(isset($selectedFilters[BlcFilterBlock::BLOCK_TYPE_MANUFACTURER])?self::addManufacturersFilter($selectedFilters[BlcFilterBlock::BLOCK_TYPE_MANUFACTURER], $idShop):'');
		return Db::getInstance()->executeS($sql);
	}
	
	public static function getFilterList($combinationsData, $idLang, $triggerBlockId, $triggerBlockValues, $selectedFilters){
		$listBlock = array();
		
		foreach($combinationsData['attributes'] as $key => $value){
			$block = BlcFilterBlock::getByIdGroup($key);
			if(!empty($block)){
				$listAttribute = array();
				if(($triggerBlockId!=$block['id_blc_filter_block']) || !isset($selectedFilters[BlcFilterBlock::BLOCK_TYPE_ATTRIBUTE_GROUP][$triggerBlockId])){
					$listAttribute = $value;
				}else{
					foreach($triggerBlockValues as $value){
						$attribute = new Attribute((int)$value, $idLang);
						$listAttribute[] = array(
							'id_attribute' => $attribute->id,
							'name' => $attribute->name,
							'color' => $attribute->color,
							'id_attribute_group' => $attribute->id_attribute_group,
						);
					}
				}
				$block['selectables'] = $listAttribute;
				$listBlock[] = $block;
			}
		}
		if(!empty($combinationsData['products'])){
			$block = BlcFilterBlock::getByType(BlcFilterBlock::BLOCK_TYPE_PRODUCT);
			if(!empty($block)){
				$selectables = array();
				$list = (($triggerBlockId!=$block['id_blc_filter_block']) || !isset($selectedFilters[BlcFilterBlock::BLOCK_TYPE_PRODUCT]))?$combinationsData['products']:$triggerBlockValues;
				foreach($list as $value){
					$object = is_object($value) ? $value : new Product((int)$value, false, $idLang);
					$selectables[]= array('id_product'=> $object->id, 'name'=> $object->name);
				}
				$block['selectables'] = $selectables;
				$listBlock[] = $block;
			}
		}
		
		if(!empty($combinationsData['manufacturers'])){
			$block = BlcFilterBlock::getByType(BlcFilterBlock::BLOCK_TYPE_MANUFACTURER);
			if(!empty($block)){
				$selectables = array();
				$list = ($triggerBlockId!=$block['id_blc_filter_block'] || !isset($selectedFilters[BlcFilterBlock::BLOCK_TYPE_MANUFACTURER]))?$combinationsData['manufacturers']:$triggerBlockValues;
				foreach($list as $value){
					$id = is_array($value)?$value['id_manufacturer']:(int)$value;
					$manufacturer =new Manufacturer($id, $idLang);
					$selectables[]= array('id_manufacturer'=> $id, 'name'=> $manufacturer->name);
				}
				$block['selectables'] = $selectables;
				$listBlock[] = $block;
			}
		}
		
		if(!empty($combinationsData['carriers'])){
			$block = BlcFilterBlock::getByType(BlcFilterBlock::BLOCK_TYPE_CARRIER);
			if(!empty($block)){
				$selectables = array();
				$list = ($triggerBlockId!=$block['id_blc_filter_block'] || !isset($selectedFilters[BlcFilterBlock::BLOCK_TYPE_CARRIER]))?$combinationsData['carriers']:$triggerBlockValues;
				foreach($list as $value){
					$id = is_array($value)?$value['id_carrier']:(int)$value;
					$carrier =new Carrier($id, $idLang);
					$selectables[]= array('id_carrier'=> $id, 'name'=> $carrier->name);
				}
				$block['selectables'] = $selectables;
				$listBlock[] = $block;
			}
		}
		$otherBlock = array(BlcFilterBlock::BLOCK_TYPE_PRICE, BlcFilterBlock::BLOCK_TYPE_STOCK);
		if(BlcFilterBlock::isBuyerMember()){
			$otherBlock[] = BlcFilterBlock::BLOCK_TYPE_STATUS;
		}
		foreach($otherBlock as $typeBlock){
			$block = BlcFilterBlock::getByType($typeBlock);
			if(!empty($block)){
				$listBlock[] = $block;
			}
		}
		usort($listBlock, function($item1, $item2) {
			return $item1['position'] - $item2['position'];
		});
		return $listBlock;
	}
}
