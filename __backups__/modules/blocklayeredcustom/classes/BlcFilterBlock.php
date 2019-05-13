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

class BlcFilterBlock extends ObjectModel
{
	const BLOCK_TYPE_MANUFACTURER = 1;
	const BLOCK_TYPE_PRICE = 2;
	const BLOCK_TYPE_CARRIER = 3;
	const BLOCK_TYPE_ATTRIBUTE_GROUP = 4;
	const BLOCK_TYPE_PRODUCT = 5;
	const BLOCK_TYPE_STOCK = 6; 
	const BLOCK_TYPE_STATUS = 7; 
	
	const FILTER_TYPE_RADIO = 1;
	const FILTER_TYPE_CHECKBOX = 2;
	const FILTER_TYPE_DROPDOWN_LIST = 3;
	const FILTER_TYPE_SLIDER = 4;
	const FILTER_TYPE_INPUTS = 5;
	const FILTER_TYPE_COLOR = 6;
	
	const STOCK_AVAILABLE = 1;
	
	const STATUS_TO_ORDER = 1;
	
	const COMBINATIONS_SEPARATOR = ',';
	
    public $id_blc_filter_block;
	
	public $block_type;
	
    public $filter_type;
	
	public $position;

    public $multiple;
	
	public $active;

    public static $definition = array(
        'table' => 'blc_filter_block',
        'primary' => 'id_blc_filter_block',
        'fields' => array(
            'block_type' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId'
            ),
            'filter_type' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId'
            ),
			'position' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId'
            ),
			'multiple' => array(
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool'
            ),
            'active' => array(
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool'
            )
        )
    );
	
	public static function insertBaseBlocks(){
		$block = new BlcFilterBlock();
		
		$block->block_type=self::BLOCK_TYPE_STOCK;
		$block->filter_type=self::FILTER_TYPE_CHECKBOX;
		$block->multiple=true;
		$block->position=1;
		$block->active=true;
		$block->add();
		
		$block->block_type=self::BLOCK_TYPE_STATUS;
		$block->position=2;
		$block->add();
		
		$block->block_type=self::BLOCK_TYPE_MANUFACTURER;
		$block->position=3;
		$block->add();
		
		$block->block_type=self::BLOCK_TYPE_PRODUCT;
		$block->position=4;
		$block->add();
		
		$block->block_type=self::BLOCK_TYPE_CARRIER;
		$block->position=5;
		$block->add();
		
		$block->block_type=self::BLOCK_TYPE_PRICE;
		$block->filter_type=self::FILTER_TYPE_SLIDER;
		$block->position=6;
		$block->add();
	}
	
	public static function getAll($onlyActive = false){
		$sql = 'SELECT * FROM ' . _DB_PREFIX_ . self::$definition['table'].
		($onlyActive?' WHERE active = 1':'').' ORDER BY position ASC';
        return Db::getInstance()->executeS($sql);
	}
	
	/*public static function getIdByType($blockType){
		$sql = 'SELECT id_blc_filter_block FROM ' . _DB_PREFIX_ . self::$definition['table'].
		' WHERE block_type = '.(int)$blockType;
        return (int)Db::getInstance()->getValue($sql);
	}*/
	
	public static function getByType($blockType, $onlyActive = true){
		$sql = 'SELECT * FROM ' . _DB_PREFIX_ . self::$definition['table'].
		' WHERE (block_type = '.(int)$blockType.')'.($onlyActive?' AND (active = 1)':'');
        return Db::getInstance()->getRow($sql);
	}
	
	public static function getByIdGroup($idGroup, $onlyActive = true){
		$sql = 'SELECT fb.* FROM '. _DB_PREFIX_ .'blc_attribute_group bag INNER JOIN ' . _DB_PREFIX_ . self::$definition['table'].
		' fb  ON (bag.id_blc_filter_block = fb.id_blc_filter_block) WHERE (bag.id_attribute_group = '.(int)$idGroup.')'.($onlyActive?' AND (active = 1)':'');
        return Db::getInstance()->getRow($sql);
	}
	
	public static function getSelectableManufacturers(){
        return Manufacturer::getManufacturers();
	}
	
	public static function getSelectableCarriers($idLang){
        return Carrier::getCarriers($idLang, true);
	}
	
	public static function getPricesRange(){
		$context = Context::getContext();
		$sql = 'SELECT CEIL(MAX(pap.price)) FROM '._DB_PREFIX_.'blc_product_attribute_price pap WHERE (pap.id_currency = '.
		(int)$context->currency->id.') AND (pap.id_shop='.(int)$context->shop->id.')';
		$max = Db::getInstance()->getValue($sql);
		$sql = 'SELECT FLOOR(MIN(pap.price)) FROM '._DB_PREFIX_.'blc_product_attribute_price pap WHERE (pap.id_currency = '.
		(int)$context->currency->id.') AND (pap.id_shop='.(int)$context->shop->id.')';
		$min = Db::getInstance()->getValue($sql);
		$price_array = array();
		$price_array['min']=$min;
		$price_array['max']=$max;
		$price_array['values'][0] = $min;
		$price_array['values'][1] = $max;
        return $price_array;
	}
	
	public static function getManufacturerCount($idManufacturer){
		$sql = 'SELECT COUNT(DISTINCT pa.id_product_attribute) FROM '._DB_PREFIX_.'product_attribute pa '.
			Shop::addSqlAssociation('product_attribute', 'pa').
			' INNER JOIN '._DB_PREFIX_.'product p ON (pa.id_product = p.id_product) '.Shop::addSqlAssociation('product', 'p').
			' WHERE (product_shop.active=1)'.
			' AND (product_shop.`visibility` IN ("both", "catalog")) AND (p.id_manufacturer = '.(int)$idManufacturer.')'.
			' ';
		$count = Db::getInstance()->getValue($sql);
        return $count;
	}
	
	public static function getProductsCount($idProduct){
		$sql = 'SELECT COUNT(DISTINCT pa.id_product_attribute) FROM '._DB_PREFIX_.'product_attribute pa '.
			Shop::addSqlAssociation('product_attribute', 'pa').
			' INNER JOIN '._DB_PREFIX_.'product p ON (pa.id_product = p.id_product) '.Shop::addSqlAssociation('product', 'p').
			' WHERE (product_shop.active=1)'.
			' AND (product_shop.`visibility` IN ("both", "catalog")) AND (pa.id_product = '.(int)$idProduct.')'.self::addCategoriesRestriction().
			' ';
		$count = Db::getInstance()->getValue($sql);
        return $count;
	}
	
	public static function getCarrierCount($idCarrier){
		$context = Context::getContext();
		$idShop = $context->shop->id;
		$sql = 'SELECT COUNT(DISTINCT pa.id_product_attribute) FROM '._DB_PREFIX_.'product_attribute pa '.
			Shop::addSqlAssociation('product_attribute', 'pa').
			' INNER JOIN '._DB_PREFIX_.'product p ON (pa.id_product = p.id_product) '.
			Shop::addSqlAssociation('product', 'p').
			' INNER JOIN '._DB_PREFIX_.'product_carrier pc ON  ((p.id_product = pc.id_product) AND  (pc.id_carrier_reference = '.
				(int)$idCarrier.') AND (pc.id_shop = '.(int)$idShop.'))'.
			' WHERE (product_shop.active=1)'.
			' AND (product_shop.`visibility` IN ("both", "catalog"))'.
			'';
		$count = Db::getInstance()->getValue($sql);
        return $count;
	}
	
	public static function getAttributeCount($idAttribute){
		$context = Context::getContext();
		$idShop = $context->shop->id;
		$sql = 'SELECT COUNT(DISTINCT pa.id_product_attribute) FROM '._DB_PREFIX_.'product_attribute pa '.
			Shop::addSqlAssociation('product_attribute', 'pa').
			'INNER JOIN '._DB_PREFIX_.'product p ON (pa.id_product = p.id_product) '.Shop::addSqlAssociation('product', 'p').
			' INNER JOIN '._DB_PREFIX_.'product_attribute_combination  pac ON  ((pa.id_product_attribute = pac.id_product_attribute) AND  (pac.id_attribute = '.
				(int)$idAttribute.'))'.
			' WHERE (product_shop.active=1)'.
			' AND (product_shop.`visibility` IN ("both", "catalog"))';
		$count = Db::getInstance()->getValue($sql);
        return $count;
	}
	public static function getSelectableCount($blockType, $id){
		$count = 0;
		if($blockType==self::BLOCK_TYPE_ATTRIBUTE_GROUP){
			$count = self::getAttributeCount($id);
		}elseif($blockType==self::BLOCK_TYPE_MANUFACTURER){
			$count = self::getManufacturerCount($id);
		}elseif($blockType==self::BLOCK_TYPE_PRODUCT){
			$count = self::getProductsCount($id);
		}elseif($blockType==self::BLOCK_TYPE_CARRIER){
			$count = self::getCarrierCount($id);
		}
        return (int)$count;
	}
	
	public static function getCombinationsId(){
		$referenceString = Configuration::get('BLC_DEFAULT_COMBINATIONS_REFERENCES');
		$ids = array();
		$references = explode(self::COMBINATIONS_SEPARATOR, $referenceString);
		foreach ($references as $reference)
		{
			$sql = 'SELECT pa.id_product_attribute FROM '._DB_PREFIX_.'product_attribute pa '.
			' WHERE pa.reference = \''.trim(pSQL($reference)).'\'';
			$id = Db::getInstance()->getValue($sql);
			if(!empty($id)){
				$ids[]=$id;
			}
		}
        return $ids;
	}
	
	/*public static function addCategoriesRestriction(){
		$categoriesToExclude = Configuration::get('BLC_CATEGORY_TO_EXCLUDE');
		$categoriesToExclude = empty(trim($categoriesToExclude))?'':'('.trim($categoriesToExclude).')';
		$sql = empty($categoriesToExclude)?'':(' INNER JOIN `'._DB_PREFIX_.
				'category_product` cp ON ((cp.`id_product` = p.`id_product`) AND (cp.id_category NOT IN '.$categoriesToExclude.')) ');
        return $sql;
	}*/
	public static function addCategoriesRestriction(){
		$categoriesToExclude = Configuration::get('BLC_CATEGORY_TO_EXCLUDE');
		$categoriesToExclude = empty(trim($categoriesToExclude))?'':'('.trim($categoriesToExclude).')';
		$sql = empty($categoriesToExclude)?'':(' AND (p.id_product NOT IN (SELECT cp.id_product FROM `'._DB_PREFIX_.
				'category_product` cp WHERE cp.id_category IN '.$categoriesToExclude.')) ');
        return $sql;
	}
	
	public static function isBuyerMember(){
		$context = Context::getContext();
		return in_array(Configuration::get('IMPORTBTOBDATA_GROUP_ID'), $context->customer->getGroups()) ? true : false;
	}
}
