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
include_once  _PS_OVERRIDE_DIR_. 'classes/SupportedLTEMarket.php';
class ProductSupportedLTE extends ObjectModel
{
    public $id_product_supported_lte;

    public $id_product;
    public $id_attribute;

    public static $definition = array(
        'table' => 'product_supported_lte',
        'primary' => 'id_product_supported_lte',
        'multilang' => true,
        'fields' => array(
            'id_product' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_attribute' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true)
        ),
    );
    
    public static function getAll($idProduct, $idLang = false)
    {
        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . self::$definition['table'] . ' t ' .
        ((! empty($idLang)) ? ('LEFT JOIN ' . _DB_PREFIX_ . self::$definition['table'] .
        '_lang tl ON (t.' . self::$definition['primary'] . ' = tl.' .
        self::$definition['primary'] . ' AND tl.id_lang = ' . (int) $idLang . ')') : '') .
        ' WHERE (id_product=' . (int) $idProduct. ') ';
        $result = Db::getInstance()->executeS($sql);
        return $result;
    }
	public static function getAllDisplayable($product, $idLang, $smarty, $drawHeader = true)
    {
        $sql = 'SELECT t.*, al.name AS attributeName FROM ' . _DB_PREFIX_ . self::$definition['table'] . ' t ' .
		'INNER JOIN ' . _DB_PREFIX_ .'attribute a ON (t.id_attribute = a.id_attribute) '.
        'LEFT JOIN ' . _DB_PREFIX_ .'attribute_lang al ON (al.id_attribute = a.id_attribute AND al.id_lang = ' . (int) $idLang . ')' .
        ' WHERE (t.id_product=' . (int) $product->id. ') ';
        $result = Db::getInstance()->executeS($sql);
		foreach ($result as $key => $value) {
            $result[$key]['content'] = self::getContent($product, $value, $idLang, $smarty, $drawHeader);
        }
        return $result;
    }
	
	public static function getContent($product, $data, $idLang, $smarty, $drawHeader = true)
    {
        $markets = SupportedLTEMarket::getAll($data['id_product_supported_lte'], $idLang);
		$smarty->assign(
			array(
				'markets' => $markets,
				'product' => $product,
				'productLTE' => $data,
				'drawHeader' => $drawHeader,
				'headerContent' => is_array($product->what_is_model_number)?$product->what_is_model_number[$idLang]:$product->what_is_model_number,
			)
		);
        return $smarty->fetch(_PS_THEME_DIR_.'supported-lte.tpl');
    }
    
    public static function getModelIdAttributeGroup()
    {
        $id_attribute_group = Configuration::get('PS_ID_MODEl_ATTRIBUTE_GROUP');
        return $id_attribute_group;
    }
    
    public static function getGradeIdAttributeGroup()
    {
        $id_attribute_group = Configuration::get('PS_ID_GRADE_ATTRIBUTE_GROUP');
        return $id_attribute_group;
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
}
