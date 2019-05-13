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
class ProductPack extends ObjectModel
{
	public static $definition = array(
        'table' => 'product_pack'
    );
    public static function getAll($idProduct, $idLang, $front = false)
    {
        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . self::$definition['table'] . ' t ' .
        ' WHERE (id_product=' . (int) $idProduct. ') ORDER BY is_default DESC;';
        $result = Db::getInstance()->executeS($sql);
		foreach ($result as $key => $value) {
            $result[$key]['product'] = new Product($value['id_pack'], true, $idLang);
			if($front){
				$productCover = Product::getCover($result[$key]['product']->id);
				$productCover = new Image($productCover['id_image'], $idLang);
				$result[$key]['id_image'] = $productCover->id_image;
				$result[$key]['legend'] = $productCover->legend;
				$result[$key]['allow_oosp'] = $result[$key]['product']->isAvailableWhenOutOfStock((int)$result[$key]['product']->out_of_stock);
			}
        }
        return $result;
    }
	public static function getDefault($idProduct)
    {
        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . self::$definition['table'] . ' t ' .
        ' WHERE (id_product=' . (int) $idProduct. ') AND (is_default = 1);';
        $result = Db::getInstance()->getRow($sql);
        return $result;
    }
	public static function getForCartProduct($idCart, $idProduct, $idProductAttribute)
    {
		$id_product_parent=(int)$idProduct.'_'.(int)$idProductAttribute;
        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'cart_product t ' .
        ' WHERE (id_cart = ' . (int) $idCart. ') AND (id_product_parent LIKE "%' . $id_product_parent. '%" )';
        $result = Db::getInstance()->getRow($sql);
		if(!empty($result)){
			$result['products'] = array();
			$products = explode(',', $result['id_product_parent']);
			foreach($products as $parent){
				$tab = explode('_', $parent);
				$result['products'][$parent] = array('id_product'=> $tab[0], 'id_product_attribute'=> $tab[1]);
			}
		}
        return $result;
    }
	
	public static function getExisting($idCart, $idPack)
    {
        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'cart_product t ' .
        ' WHERE (id_cart = ' . (int) $idCart. ') AND (id_product = ' . (int)$idPack. ' ) AND (id_product_parent <>"" )';
        $result = Db::getInstance()->getRow($sql);
		if(!empty($result)){
			$result['products'] = array();
			$products = explode(',', $result['id_product_parent']);
			foreach($products as $parent){
				$tab = explode('_', $parent);
				$result['products'][$parent] = array('id_product'=> $tab[0], 'id_product_attribute'=> $tab[1]);
			}
		}
        return $result;
    }
	
	public static function addNew($idProduct, $packs, $default)
    {
		Db::getInstance()->delete(self::$definition['table'], ' id_product = '.(int)$idProduct);
		if(!empty($packs)){
			foreach($packs as $pack){
				$isDefault = ($default==$pack)?1:0;
				$insert = array('id_product'=> (int)$idProduct,'id_pack'=> (int)$pack,'is_default'=> $isDefault);
				Db::getInstance()->insert(self::$definition['table'], $insert);
			}
		}
    }
	
	public static function updateCartProduct($idCart, $idPack, $products)
    {
		$id_product_parent = '';
		$first = true;
		foreach($products as $product){
			if(!$first){
				$id_product_parent.=',';
			}
			$id_product_parent.=(int)$product['id_product'].'_'.(int)$product['id_product_attribute'];
			$first = false;
		}
		$data = array('id_cart' => (int) $idCart, 'id_product_parent' => $id_product_parent);
		$where = '(id_cart = '.(int)$idCart.') AND (id_product = '.(int)$idPack.')';
        $result = Db::getInstance()->update('cart_product', $data, $where);
        return $result;
    }
}
