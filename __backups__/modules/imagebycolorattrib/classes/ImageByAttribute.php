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

class ImageByAttribute extends ObjectModel
{

    /*public $id_ibca_attribute_image;
    public $id_product;
    public $id_image;
    public $id_attribute;*/

    public static $definition = array(
        'table' => 'ibca_attribute_image',
        'primary' => 'id_ibca_attribute_image',
        'fields' => array(
            'id_product' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId'
            ),
			'id_image' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId'
            ),
			'id_attribute' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId'
            )
        )
    );
	
	public static function getAll($idProduct, $idAttribute = 0)
    {
        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . self::$definition['table'] . ' t ' .
        ' WHERE (id_product=' . (int) $idProduct. ')'.
		(empty($idAttribute)?'':' AND (id_attribute = '.(int)$idAttribute.')');
        $result = Db::getInstance()->executeS($sql);
        return $result;
    }
	
	public static function addNew($idProduct, $idAttribute, $images)
    {
		self::clearExisting($idProduct, $idAttribute);
		$combinations = self::getCombinations($idProduct, $idAttribute);
		foreach ($images as $idImage) {
            $insert = array (
					'id_product' => ( int ) $idProduct,
					'id_attribute' => ( int ) $idAttribute,
					'id_image' => ( int ) $idImage
			);
			if(Db::getInstance()->insert(self::$definition['table'], $insert)){
				foreach($combinations as $combination){
					self::updateCombinationImages($idProduct, $idAttribute, $combination['id_product_attribute'], $idImage);
				}
			}
        }
		
        return true;
    }
	
	public static function updateCombinationImages($idProduct, $idAttribute, $idProductAttribute, $idImage, $smart = true)
    {
		$add = true;
		if($smart){
			$sql = 'SELECT COUNT(*) FROM ' . _DB_PREFIX_ . 'product_attribute_image ' .
			' WHERE (id_product_attribute=' . (int) $idProductAttribute. ') AND (id_image = '.(int)$idImage.')';
			$count = (int)Db::getInstance()->getValue($sql);
			$add = ($count ==0);
		}
		if($add){
			$insert = array (
					'id_product_attribute' => ( int ) $idProductAttribute,
					'id_image' => ( int ) $idImage,
					'ibca_id_product' => ( int ) $idProduct,
					'ibca_id_attribute' => ( int ) $idAttribute,
			);
			Db::getInstance()->insert('product_attribute_image', $insert);
		}
    }
	
	public static function getCombinations($idProduct, $idAttribute)
    {
        if (!Combination::isFeatureActive()) {
            return array();
        }
        $sql = '
			SELECT pa.id_product_attribute  
			FROM `'._DB_PREFIX_.'product_attribute` pa 
            INNER JOIN `'._DB_PREFIX_.'product_attribute_combination` pac
                ON (pac.id_product_attribute = pa.id_product_attribute)  
            WHERE (pa.`id_product` = '.(int)$idProduct.') AND (pac.id_attribute = '.(int)$idAttribute.')
            GROUP BY pa.id_product_attribute
		';
        return Db::getInstance()->executeS($sql);
    }
	public static function getCombinationImages($idProductAttribute, $attributes)
    {
		if(empty($attributes)){
			return array();
		}
		$attributesString = '';
		foreach ($attributes as $value) {
			$attributesString .= empty($attributesString)?'':',';
            $attributesString.=(int)$value;
        }
        $sql = '
			SELECT ai.id_image, ai.id_attribute 
			FROM '._DB_PREFIX_.self::$definition['table'].' ai 
            INNER JOIN `'._DB_PREFIX_.'product_attribute` pa
                ON  (pa.id_product = ai.id_product)
            WHERE (pa.id_product_attribute = '.(int)$idProductAttribute.') AND  (ai.id_attribute IN ('.$attributesString.')) 
            GROUP BY ai.id_image
		';
		var_dump($sql);
        return Db::getInstance()->executeS($sql);
    }
	
	/*public static function getCombinationImages($idProductAttribute)
    {
        $sql = '
			SELECT ai.id_image, ai.id_attribute 
			FROM '._DB_PREFIX_.self::$definition['table'].' ai 
            INNER JOIN `'._DB_PREFIX_.'product_attribute_combination` pac
                ON (pac.id_attribute = ai.id_attribute)  
			INNER JOIN `'._DB_PREFIX_.'product_attribute` pa
                ON  (pac.id_product_attribute = pa.id_product_attribute AND pa.id_product = ai.id_product)
            WHERE (pac.id_product_attribute = '.(int)$idProductAttribute.') 
            GROUP BY ai.id_image
		';
        return Db::getInstance()->executeS($sql);
    }*/
	
	public static function clearExisting($idProduct, $idAttribute)
    {
        $sql = 'DELETE FROM ' . _DB_PREFIX_ . self::$definition['table'] .
        ' WHERE (id_product=' . (int) $idProduct. ')'.
		(empty($idAttribute)?'':' AND (id_attribute = '.(int)$idAttribute.')');
        $result = Db::getInstance()->execute($sql);
		$sql = 'DELETE FROM ' . _DB_PREFIX_ . 'product_attribute_image ' .
        ' WHERE (ibca_id_product=' . (int) $idProduct. ')'.
		(empty($idAttribute)?'':' AND (ibca_id_attribute = '.(int)$idAttribute.')');
        $result = Db::getInstance()->execute($sql);
        return $result;
    }
	
	 public static function getAttributes($id_lang, $id_attribute_group, $id_product)
    {
        if (!Combination::isFeatureActive()) {
            return array();
        }
        $sql = '
			SELECT pa.id_product, a.*, al.* 
			FROM `'._DB_PREFIX_.'product_attribute` pa
			'.Shop::addSqlAssociation('product_attribute', 'pa').'
            INNER JOIN `'._DB_PREFIX_.'product_attribute_combination` pac
                ON (pac.id_product_attribute = pa.id_product_attribute)
            INNER JOIN `'._DB_PREFIX_.'attribute` a 
                ON (a.id_attribute = pac.id_attribute
                    AND a.`id_attribute_group` = '.(int)$id_attribute_group.') '.
            Shop::addSqlAssociation('attribute', 'a').'
			LEFT JOIN `'._DB_PREFIX_.'attribute_lang` al
				ON (a.`id_attribute` = al.`id_attribute` AND al.`id_lang` = '.(int)$id_lang.') 
            WHERE pa.`id_product` = '.(int)$id_product.' 
            GROUP BY a.id_attribute 
			ORDER BY `position` ASC
		';
        return Db::getInstance()->executeS($sql);
    }
	
	public static function getCompleteList($idProduct, $idLang, $getImages = true)
    {
		$id_attribute_group = (int)Configuration::get('ID_GROUP_COLOR');
		$attributes = self::getAttributes($idLang, $id_attribute_group, $idProduct);
        foreach ($attributes as $value) {
            $data = array(
				'id_attribute'=>$value['id_attribute'],
				'attributeName'=>$value['name'],
				'id_product'=>$idProduct,
			);
			if($getImages){
				$images = self::getAll($idProduct, $value['id_attribute']);
				$data['images'] = self::formatImageList($images, $idLang);
				$data['imagesCount'] = count($images);
			}
			$list[]= $data;
        }
        return $list;
    }
	
	public static function formatImageList($images, $idLang)
    {
		$result = array();
        foreach ($images as $image) {
			$idImage = is_array($image)?$image['id_image']:$image;
			$obj = new Image($idImage, $idLang);
			$result[] = array(
				'obj'=>$obj,
				'id_image'=>$idImage,
				'legend'=>$obj->legend
			);
        }
        return $result;
    }
}
