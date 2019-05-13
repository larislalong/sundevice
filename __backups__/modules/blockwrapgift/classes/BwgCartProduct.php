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
include_once _PS_MODULE_DIR_ . 'blockwrapgift/classes/BwgWrapGift.php';
class BwgCartProduct extends ObjectModel
{
	public static $definition = array(
        'table' => 'bwg_wrap_gift_product_cart',
    );
	public static function getAll($idCart =0, $idProduct =0, $idProductAttribute =0, $idWrapGift =0){
		$sql = 'SELECT * FROM ' . _DB_PREFIX_ . self::$definition['table'] . ' WHERE 1'.self::getWhere($idCart, $idProduct , $idProductAttribute , $idWrapGift);
        return Db::getInstance()->executeS($sql);
	}
	public static function deleteBy($idCart =0, $idProduct =0, $idProductAttribute =0, $idWrapGift =0)
    {
		$result = Db::getInstance()->delete(self::$definition['table'], '1'.self::getWhere($idCart, $idProduct, $idProductAttribute, $idWrapGift));
        return $result;
    }
	public static function addNew($idCart, $idProduct, $idProductAttribute, $idWrapGift, $smart = true)
    {
		if($smart){
			self::deleteBy($idCart, $idProduct, $idProductAttribute);
		}
		$data = array('id_cart'=>(int)$idCart, 'id_product'=>(int)$idProduct, 'id_product_attribute'=>(int)$idProductAttribute, 'id_bwg_wrap_gift'=>(int)$idWrapGift);
		$result = Db::getInstance()->insert(self::$definition['table'], $data);
        return $result;
    }
	public static function getWhere($idCart =0, $idProduct =0, $idProductAttribute =0, $idWrapGift =0){
		$sql = '';
		$sql.=empty($idCart)?'':(' AND (id_cart = '.(int)$idCart.')');
		$sql.=empty($idProduct)?'':(' AND (id_product = '.(int)$idProduct.')');
		$sql.=empty($idProductAttribute)?'':(' AND (id_product_attribute = '.(int)$idProductAttribute.')');
		$sql.=empty($idWrapGift)?'':(' AND (id_bwg_wrap_gift = '.(int)$idWrapGift.')');
        return $sql;
    }
	
	public static function getTotalPrice($idCart, $products)
    {
		$total =0;
		foreach($products as $product){
			$data = self::getAll($idCart, $product['id_product'], $product['id_product_attribute']);
			foreach($data as $row){
				$wrapGift = new BwgWrapGift((float)$row['id_bwg_wrap_gift']);
				$total += $wrapGift->price * (int)$product['cart_quantity'];
			}
		}
		return $total;
		var_dump($total);die();
    }
	public static function getPackaging($idCart, $idProduct, $idProductAttribute, $idLang){
		$sql = 'SELECT t.*, tl.* FROM '. _DB_PREFIX_ . self::$definition['table'] . ' t
		INNER JOIN ' . _DB_PREFIX_ .'bwg_wrap_gift_lang tl ON (tl.id_bwg_wrap_gift = t.id_bwg_wrap_gift) AND (tl.id_lang = '.(int)$idLang.') 
		WHERE 1'.self::getWhere($idCart, $idProduct , $idProductAttribute);
        return Db::getInstance()->getRow($sql);
	}
}
