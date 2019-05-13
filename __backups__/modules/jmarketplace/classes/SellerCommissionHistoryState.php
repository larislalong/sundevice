<?php
/**
* 2007-2018 PrestaShop
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
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class SellerCommissionHistoryState extends ObjectModel
{
    public $active;
    public $reference;
    public $name;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'seller_commission_history_state',
        'primary' => 'id_seller_commission_history_state',
        'multilang' => true,
        'fields' => array(
            'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'reference' => array('type' => self::TYPE_STRING, 'lang' => false, 'validate' => 'isCatalogName', 'size' => 32),
            'name' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCatalogName', 'size' => 64),
        ),
    );
    
    public static function getStates($id_lang)
    {
        $result = Db::getInstance()->executeS(
            'SELECT *
            FROM `'._DB_PREFIX_.'seller_commission_history_state` ist
            LEFT JOIN `'._DB_PREFIX_.'seller_commission_history_state_lang` isl ON (isl.`id_seller_commission_history_state` = ist.`id_seller_commission_history_state` AND isl.id_lang = '.(int)$id_lang.')
            WHERE active = 1
            ORDER BY isl.name DESC'
        );
        if ($result) {
            return $result;
        }
        return false;
    }
    
    public static function getIdByReference($reference)
    {
        return Db::getInstance()->getValue(
            'SELECT id_seller_commission_history_state FROM '._DB_PREFIX_.'seller_commission_history_state 
            WHERE reference = "'.pSQL($reference).'"'
        );
    }
}
