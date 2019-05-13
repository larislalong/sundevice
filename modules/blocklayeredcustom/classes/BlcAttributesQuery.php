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
include_once _PS_MODULE_DIR_ . 'blocklayeredcustom/classes/BlcFilterQuery.php';
class BlcAttributesQuery extends ObjectModel
{
	const ORDER_WAY_NONE = 0;
	const ORDER_WAY_ASC = 1;
	const ORDER_WAY_DESC = 2;
	
	const ORDER_COLUMN_TYPE_NONE = 0;
	const ORDER_COLUMN_TYPE_ATTRIBUTE_GROUP = 1;
	const ORDER_COLUMN_TYPE_PRICE = 2;
	const ORDER_COLUMN_TYPE_TO_ORDER = 3;
	const ORDER_COLUMN_TYPE_INVENTORY_DAY = 4;
	
	private static function getProductAttributes($id_category, $selectedFilters, $id_lang, $idShop)
    {
        if (!Combination::isFeatureActive()) {
            return array();
        }
		$productRestrict = (empty($selectedFilters) && empty($id_category))?self::getNoFilterRestrict():'';
        $sql = 'SELECT pa.*, ag.`id_attribute_group`, ag.`is_color_group`, agl.`name` AS group_name, agl.`public_name` AS public_group_name,
					a.`id_attribute`, al.`name` AS attribute_name, a.`color` AS attribute_color, product_attribute_shop.`id_product_attribute`,
					IFNULL(stock.quantity, 0) as quantity, product_attribute_shop.`price`, product_attribute_shop.`ecotax`, product_attribute_shop.`weight`,
					product_attribute_shop.`default_on`, pa.`reference`, product_attribute_shop.`unit_price_impact`,
					product_attribute_shop.`minimal_quantity`, product_attribute_shop.`available_date`, ag.`group_type`, pa.id_product, p.id_manufacturer, pl.link_rewrite,
					pc.id_carrier_reference AS id_carrier 
				FROM `'._DB_PREFIX_.'product_attribute` pa
				'.Shop::addSqlAssociation('product_attribute', 'pa').'
				'.Product::sqlStock('pa', 'pa').
				' INNER JOIN '._DB_PREFIX_.'product p ON (pa.id_product=p.id_product) '.Shop::addSqlAssociation('product', 'p').
				(!empty($id_category)?BlcFilterQuery::addCategoryFilter($id_category, $id_lang, $idShop):'').
				' INNER JOIN '._DB_PREFIX_.'product_lang pl ON (p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').')'.
				' LEFT JOIN '._DB_PREFIX_.'product_carrier pc ON (pc.id_shop = '.(int)$idShop.') '.
				(isset($selectedFilters[BlcFilterBlock::BLOCK_TYPE_PRICE])?BlcFilterQuery::addPricesFilter($selectedFilters[BlcFilterBlock::BLOCK_TYPE_PRICE], $idShop):'').
				' LEFT JOIN `'._DB_PREFIX_.'product_attribute_combination` pac ON (pac.`id_product_attribute` = pa.`id_product_attribute`)
				LEFT JOIN `'._DB_PREFIX_.'attribute` a ON (a.`id_attribute` = pac.`id_attribute`)
				LEFT JOIN `'._DB_PREFIX_.'attribute_group` ag ON (ag.`id_attribute_group` = a.`id_attribute_group`)
				LEFT JOIN `'._DB_PREFIX_.'attribute_lang` al ON (a.`id_attribute` = al.`id_attribute`)
				LEFT JOIN `'._DB_PREFIX_.'attribute_group_lang` agl ON (ag.`id_attribute_group` = agl.`id_attribute_group`)
				'.Shop::addSqlAssociation('attribute', 'a').
				' WHERE 1 AND (p.active=1) AND product_attribute_shop.`price` <> 0 AND (pa.active=1) AND al.`id_lang` = '.(int)$id_lang.'
					AND agl.`id_lang` = '.(int)$id_lang.' AND (product_shop.`visibility` IN ("both", "catalog")) '.
					(isset($selectedFilters[BlcFilterBlock::BLOCK_TYPE_CARRIER])?BlcFilterQuery::addCarriersFilter($selectedFilters[BlcFilterBlock::BLOCK_TYPE_CARRIER], $idShop):'').
					(isset($selectedFilters[BlcFilterBlock::BLOCK_TYPE_MANUFACTURER])?BlcFilterQuery::addManufacturersFilter($selectedFilters[BlcFilterBlock::BLOCK_TYPE_MANUFACTURER], $idShop):'').
					(isset($selectedFilters[BlcFilterBlock::BLOCK_TYPE_PRODUCT])?BlcFilterQuery::addProductsFilter($selectedFilters[BlcFilterBlock::BLOCK_TYPE_PRODUCT], $idShop):'').
					(isset($selectedFilters[BlcFilterBlock::BLOCK_TYPE_ATTRIBUTE_GROUP])?self::addAttributesFilter($selectedFilters[BlcFilterBlock::BLOCK_TYPE_ATTRIBUTE_GROUP], $idShop):'').
					(isset($selectedFilters[BlcFilterBlock::BLOCK_TYPE_STOCK])?self::addStockFilter($selectedFilters[BlcFilterBlock::BLOCK_TYPE_STOCK]):'').
					(isset($selectedFilters[BlcFilterBlock::BLOCK_TYPE_STATUS])?self::addStatusFilter($selectedFilters[BlcFilterBlock::BLOCK_TYPE_STATUS]):'').
					$productRestrict. BlcFilterBlock::addCategoriesRestriction().
				' GROUP BY id_attribute_group, pac.id_product_attribute
				ORDER BY ag.`position` ASC, a.`position` ASC, agl.`name` ASC';
				// var_dump($sql);die;
        return Db::getInstance()->executeS($sql);
    }
	
	private static function getNoFilterRestrict()
    {
		$idsAttributes = BlcFilterBlock::getCombinationsId();
		// $idsAttributes = empty($idsAttributes)?array(0):$idsAttributes;
		return empty($idsAttributes)?'':' AND (pa.id_product_attribute IN ('.implode(',', $idsAttributes).'))';
	}
	private static function addStockFilter($values)
    {
		if(empty($values)){
			return '';
		}
		return ' AND (stock.quantity > 0)';
	}
	private static function addStatusFilter($values)
    {
		if(empty($values)){
			return '';
		}
		return ' AND (pa.to_order > 0)';
	}
	
	public static function addAttributesFilter($values){
		if(empty($values)){
			return '';
		}
		$idProductAttributes = array();
		foreach($values as $attributes){
			$sql = 'SELECT pac.id_product_attribute FROM '._DB_PREFIX_.'product_attribute_combination pac WHERE (pac.id_attribute IN ('.
			implode(',',$attributes).'))'.
			(empty($idProductAttributes)?'':' AND (pac.id_product_attribute IN ('.implode(',',$idProductAttributes).'))').
			' GROUP BY pac.id_product_attribute';
			$products = Db::getInstance()->executeS($sql);
			if(empty($products)){
				$idProductAttributes = array();
				break;
			}else{
				$products = BlcTools::getArrayValues($products, 'id_product_attribute');
				$idProductAttributes = $products;
			}
		}
		// $idProductAttributes = empty($idProductAttributes)?array(0):$idProductAttributes;
		$sql = empty($idProductAttributes)?'':' AND (pa.id_product_attribute IN ('.implode(',',$idProductAttributes).')) ';
        return $sql;
	}
	
	public static function getCombinations($id_category, $selectedFilters, $id_lang, $idShop, $start, $limit, $orderWay, $orderColumnType, $orderColumn, $getFilterLeft = false, $triggerBlockId = 0, $triggerBlockValues = array())
    {
		$combinations = self::getProductAttributes($id_category, $selectedFilters, $id_lang, $idShop);
		$combinationsData = self::formatCombinations($combinations, $id_lang, $start, $limit);
		$combinations = $combinationsData['combinations'];
		$sorter = new BlcAttributesSorter($combinations, $id_lang, $start, $limit, $orderWay, $orderColumnType, $orderColumn);
		$list = $sorter->sortList();
		$result = array('hasMore'=>$sorter->hasMore, 'combinations'=>$list, 'groups'=>$combinationsData['groups'], 'products'=>$combinationsData['products']);
		if($getFilterLeft){
			$listBlock = (empty($selectedFilters) && empty($id_category)) ? null : BlcFilterQuery::getFilterList($combinationsData, $id_lang, $triggerBlockId, $triggerBlockValues, $selectedFilters);
			$result['listBlock'] =$listBlock;
		}
		return $result;
    }
	
	public static function formatCombinations($list, $id_lang, $start, $limit)
    {
		$productCover = null;
		$productsCoverList = null;
		$combinations = array();
		$i = 0;
		$productList = array();
		$addedCombinations = array();
		$groups = array();
		$attributes = array();
		$manufacturers = array();
		$carriers = array();
		$priceDisplay = Product::getTaxCalculationMethod((int)Context::getContext()->cookie->id_customer);
		$useTax = (!$priceDisplay || ($priceDisplay == 2));
        foreach($list as $k => $combination){
			$key = $combination['id_product_attribute'];
			if(!array_key_exists($key, $addedCombinations)){
				$addedCombinations[$key] = $i;
				$i++;
			}
			$key = $addedCombinations[$key];
			if(!isset($combinations[$key])){
				$combinations[$key] =  $combination;
				$image = Product::getCombinationImageById($combination['id_product_attribute'], $id_lang);
				if(isset($productList[$combination['id_product']])){
					$product = $productList[$combination['id_product']];
				}else{
					$product = new Product($combination['id_product'], false, $id_lang);
					$productList[$combination['id_product']] = $product;
				}
				if(empty($image)){
					if(isset($productsCoverList[$combination['id_product']])){
						$productCover = $productsCoverList[$combination['id_product']];
					}else{
						$productCover = Product::getCover($product->id);
						$productCover = new Image($productCover['id_image'], $id_lang);
						$productCover=(array)$productCover;
						$productsCoverList[$combination['id_product']] = $productCover;
					}
					$image = $productCover;
				}
				// var_dump($image);die;
				$combinations[$key]['product'] = $product;
				$combinations[$key]['id_image'] = $image['id_image'];
				$combinations[$key]['legend'] = $image['legend'];
				$combinations[$key]['name'] = $product->name;
				$combinations[$key]['link'] = Context::getContext()->link->getProductLink($product, null,null,null,$id_lang,null,
					(int)$combination['id_product_attribute'], false, false, true);
				$combinations[$key]['price'] = $product->getPrice($useTax, $combination['id_product_attribute']);
			}
			$combinations[$key]['attributes_values'][$combination['id_attribute_group']] = $combination['attribute_name'];
			$attributes[$combination['id_attribute_group']][$combination['id_attribute']] = array(
				'id_attribute' => $combination['id_attribute'],
				'name' => $combination['attribute_name'],
				'color' => $combination['attribute_color'],
				'id_attribute_group' => $combination['id_attribute_group'],
			);
			$combinations[$key]['attributes'][$combination['id_attribute_group']] = $attributes[$combination['id_attribute_group']][$combination['id_attribute']];
			if (!isset($groups[$combination['id_attribute_group']])) {
				$groups[$combination['id_attribute_group']] = array(
					'group_name' => $combination['group_name'],
					'name' => $combination['public_group_name'],
					'group_type' => $combination['group_type'],
					'id_attribute_group' => $combination['id_attribute_group'],
					'default' => -1,
				);
			}
			if (!empty($combination['id_manufacturer']) && !isset($manufacturers[$combination['id_manufacturer']])) {
				$manufacturers[$combination['id_manufacturer']] = array('id_manufacturer' => $combination['id_manufacturer']);
			}
			if (!empty($combination['id_carrier']) && !isset($carriers[$combination['id_carrier']])) {
				$carriers[$combination['id_carrier']] = array('id_carrier' => $combination['id_carrier']);
			}
		}
		return array('combinations'=>$combinations, 'groups'=>$groups, 'attributes'=>$attributes, 'manufacturers'=>$manufacturers, 'products'=>$productList, 'carriers'=>$carriers);
		
    }
}

class BlcAttributesSorter
{
	const ATTRIBUTE_MAX_LENGTH = 20;
	public $hasMore;
	public function __construct($combinations, $id_lang, $start, $limit, $orderWay, $orderColumnType, $orderColumn){
		$this->combinations = $combinations;
		$this->id_lang = $id_lang;
		$this->start = $start;
		$this->limit = $limit;
		$this->orderWay = $orderWay;
		$this->orderColumnType = $orderColumnType;
		$this->orderColumn = $orderColumn;
	}
	
	public function sortList(){
		$list = $this->combinations;
		$this->hasMore = false;
		if(($this->orderColumnType!=BlcAttributesQuery::ORDER_COLUMN_TYPE_NONE) && ($this->orderWay!=BlcAttributesQuery::ORDER_WAY_NONE)){
			usort($list, [$this,'compare']);
		}
		if(!empty($this->limit)){
			$nextIndex = $this->start + $this->limit;
			$this->hasMore = isset($list[$nextIndex]);
			$list = array_slice($list, $this->start, $this->limit);
		}
		return $list;
	}
	
	public function compare($item1, $item2){
		$value1 = $this->getValue($item1, $item2);
		$value2 = $this->getValue($item2, $item1);
		if($value1==$value2){
			return 0;
		}else if($this->orderWay==BlcAttributesQuery::ORDER_WAY_ASC){
			return ($value1 < $value2) ? -1 : 1;
		}else{
			return ($value1 < $value2) ? 1 : -1;
		}
	}
	
	public function getValue($item, $otherItem){
		if($this->orderColumnType==BlcAttributesQuery::ORDER_COLUMN_TYPE_PRICE){
			return $item['price'];
		}elseif($this->orderColumnType==BlcAttributesQuery::ORDER_COLUMN_TYPE_ATTRIBUTE_GROUP){
			$value = isset($item['attributes_values'][$this->orderColumn])?$item['attributes_values'][$this->orderColumn]:'';
			$valueOther = isset($otherItem['attributes_values'][$this->orderColumn])?$otherItem['attributes_values'][$this->orderColumn]:'';
			$value = trim($value);
			$valueOther = trim($valueOther);
			$len = strlen($value);
			$lenOther = strlen($valueOther);
			$maxLength = max($len, $lenOther);
			while($len<$maxLength){
				$value = '0'.$value;
				$len = strlen($value);
			}
			return $value;
		}elseif($this->orderColumnType==BlcAttributesQuery::ORDER_COLUMN_TYPE_TO_ORDER){
			return $item['to_order'];
		}elseif($this->orderColumnType==BlcAttributesQuery::ORDER_COLUMN_TYPE_INVENTORY_DAY){
			return $item['inventory_day'];
		}else{
			return 0;
		}
	}
}
