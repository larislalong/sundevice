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

class BwgWrapGift extends ObjectModel
{

    public $image;

    public $price;

    public $active;
	
	public $name;
	
	public $position;

    public $description;
	
	const ADD_SUCCESS_CODE = 1;

    const UPDATE_SUCCESS_CODE = 2;

    const DELETE_SUCCESS_CODE = 3;

    const STATUS_CHANGE_SUCCESS_CODE = 4;

    public static $definition = array(
        'table' => 'bwg_wrap_gift',
        'primary' => 'id_bwg_wrap_gift',
		'multilang' => true,
        'fields' => array(
            'image' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isCleanHtml'
            ),
            'price' => array(
                'type' => self::TYPE_FLOAT,
                'validate' => 'isPrice'
            ),
			'active' => array(
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool'
            ),
			'position' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
			'name' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'IsGenericName', 'required' => true),
			'description' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
        )
    );
	
	public static function getAll($idLang, $onlyActive = false){
		$sql = 'SELECT * FROM ' . _DB_PREFIX_ . self::$definition['table'] . 
		' t LEFT JOIN ' . _DB_PREFIX_ . self::$definition['table'] . '_lang tl ON (tl.id_bwg_wrap_gift = t.id_bwg_wrap_gift) AND (tl.id_lang = '.
		(int)$idLang.')'.($onlyActive ? ' WHERE t.`active` = 1' : '').' ORDER BY position ASC';
        return Db::getInstance()->executeS($sql);
	}
	
	public static function getCount()
    {
        return (int) Db::getInstance()->getValue('SELECT COUNT(*) FROM ' . _DB_PREFIX_ . self::$definition['table']);
    }
	
	 public static function updatePosition($id, $position){
        $data = array('position'=>(int)$position);
        $result = Db::getInstance()->update(self::$definition['table'], $data, self::$definition['primary'].' = '.(int)$id);
        return $result;
    }
}
