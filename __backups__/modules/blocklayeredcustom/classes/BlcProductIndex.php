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

class BlcProductIndex extends ObjectModel
{
	public $id_product;
	
	public $is_up_to_date;
	
    public static $definition = array(
        'table' => 'blc_product_index',
        'primary' => 'id_product',
        'fields' => array(
            'id_product' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId'
            ),
            'is_up_to_date' => array(
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool'
            )
        )
    );
	public static function insertProductsIndex(){
		
		$sql = 'INSERT INTO '._DB_PREFIX_.'blc_product_index  (id_product, is_up_to_date) SELECT id_product, 0 FROM '._DB_PREFIX_.'product';
		return Db::getInstance()->execute($sql);
	}
	
	public static function getAllProducts($withName = true, $idLang = false, $idShop = 0){
		
		$sql = 'SELECT t.*'.($withName?', pl.name':'').' FROM '._DB_PREFIX_.'blc_product_index t'. 
			($withName?
			(' LEFT JOIN '._DB_PREFIX_.'product_lang pl ON ((pl.id_product = t.id_product) AND (pl.id_shop = '.(int)$idShop.
				') AND (pl.id_lang = '.(int)$idLang.'))')
			:'');
		return Db::getInstance()->executeS($sql);
	}
	
	public static function changeUpToDate($value, $idProduct = 0){
		
		$sql = 'UPDATE '._DB_PREFIX_.'blc_product_index SET is_up_to_date = '. (int)$value.
			(!empty($idProduct)?(' WHERE id_product = '.(int)$idProduct):'');
		Db::getInstance()->execute($sql);
	}
	
	public static function setUpToDate($idProduct = 0){
		
		self::changeUpToDate(1, $idProduct);
		Configuration::updateValue('BLC_PRICE_DEPRECATED', 0);
	}
	
	public static function setDeprecated($idProduct = 0){
		
		self::changeUpToDate(0, $idProduct);
		Configuration::updateValue('BLC_PRICE_DEPRECATED', 1);
	}
	
	public static function addNew($idProduct){
		$sql = 'INSERT INTO '._DB_PREFIX_.'blc_product_index (id_product, is_up_to_date) VALUES ('.(int)$idProduct.', 0)';
		return Db::getInstance()->execute($sql);
	}
}
