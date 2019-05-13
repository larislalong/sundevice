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

class BlcProductAttributePrice extends ObjectModel
{
	public static function addNew($idProduct, $idProductAttribute, $idShop, $idCurrency, $price, $deleteExisting = false){
		if($deleteExisting){
			$sql = 'DELETE FROM '._DB_PREFIX_.'blc_product_attribute_price WHERE (id_product_attribute = '.(int)$idProductAttribute.
				') AND (id_currency = '.(int)$idCurrency.') AND (id_shop = '.(int)$idShop.')';
			Db::getInstance()->execute($sql);
		}
		
		$sql = 'INSERT INTO '._DB_PREFIX_.'blc_product_attribute_price (id_product, id_product_attribute, id_shop, id_currency, price) VALUES ('.
		(int)$idProduct.', '.(int)$idProductAttribute.', '.(int)$idShop.', '.(int)$idCurrency.', '.(float)$price.')';
		return Db::getInstance()->execute($sql);
	}
	
	public static function indexProductAttributePrices($idProduct, $idProductAttribute, $smart = true)
	{
		$combination = new Combination();
		$combination->id = $idProductAttribute;
		$shop_list = $combination->getAssociatedShops();
		static $clonedContext = null;
		if(is_null($clonedContext)){
			$clonedContext = Context::getContext()->cloneContext();
		}
		if ($smart){
			$sql = 'DELETE FROM '._DB_PREFIX_.'blc_product_attribute_price WHERE id_product_attribute = '.(int)$idProductAttribute;
			Db::getInstance()->execute($sql);
		}
		$useTax = (bool)Configuration::get('BLC_USE_TAX_TO_FILTER_PRICE');
		foreach ($shop_list as $idShop)
		{
			static $currency_list = null;

			if (is_null($currency_list))
				$currency_list = Currency::getCurrencies(false, 1, new Shop($idShop));
			
			foreach ($currency_list as $currency)
			{
				$idCurrency = $currency['id_currency'];
				$clonedContext->currency->id = $idCurrency;
				$specific_price_output = null;
				$price = Product::getPriceStatic($idProduct, $useTax, $idProductAttribute, 6, null, false,true, 1, false, null, null, null, $specific_price_output, $useTax, true, $clonedContext, true);
				self::addNew($idProduct, $idProductAttribute, $idShop, $idCurrency, $price);
			}
		}
	}
	
	public static function indexProductPrices($idProduct, $start = 0, $limit =0)
	{
		$sql = 'SELECT id_product_attribute FROM '._DB_PREFIX_.'product_attribute WHERE id_product = '.(int)$idProduct. ' ORDER BY id_product_attribute'.
		(($limit>0)?' LIMIT '.(int)$start.', '.$limit:'');
		$combinations =Db::getInstance()->executeS($sql);
		foreach($combinations as $combination){
			self::indexProductAttributePrices($idProduct, $combination['id_product_attribute']);
		}
	}
	
	public static function getCombinationsCount($idProduct)
	{
		$sql = 'SELECT COUNT(*) FROM '._DB_PREFIX_.'product_attribute WHERE id_product = '.(int)$idProduct;
		return (int)Db::getInstance()->getValue($sql);
	}
}
