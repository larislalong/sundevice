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

class SupportedLTEMarket extends ObjectModel
{
    public $id_product_supported_lte;
	public $market_image;
    public $market_name;
    
    public $content;

    public static $definition = array(
        'table' => 'supported_lte_market',
        'primary' => 'id_supported_lte_market',
        'multilang' => true,
        'fields' => array(
            'id_product_supported_lte' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'market_image' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
            'content' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
            'market_name' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml'),
        ),
    );
    
    public static function getAll($idProductSupportedLte, $idLang = false)
    {
        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . self::$definition['table'] . ' t ' .
        ((! empty($idLang)) ? ('LEFT JOIN ' . _DB_PREFIX_ . self::$definition['table'] .
        '_lang tl ON (t.' . self::$definition['primary'] . ' = tl.' .
        self::$definition['primary'] . ' AND tl.id_lang = ' . (int) $idLang . ')') : '') .
        ' WHERE (id_product_supported_lte=' . (int) $idProductSupportedLte. ') ';
        $result = Db::getInstance()->executeS($sql);
        return $result;
    }
	public static function deleteBy($idProductSupportedLte)
    {
        $result = Db::getInstance()->delete(self::$definition['table'], 'id_product_supported_lte=' . (int) $idProductSupportedLte);
        return $result;
    }
}
