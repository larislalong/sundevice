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

class SellerCategory extends ObjectModel
{
    public $id_category;
    public $id_shop;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'seller_category',
        'primary' => 'id_seller_category',
        'fields' => array(
            'id_category' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => false),
            'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => false),
        ),
    );
    
    public static function getSelectedCategories($id_shop)
    {
        return Db::getInstance()->executeS(
            'SELECT id_category FROM '._DB_PREFIX_.'seller_category 
            WHERE id_shop = '.(int)$id_shop
        );
    }
    
    public static function deleteSelectedCategories($id_shop)
    {
        return Db::getInstance()->execute(
            'DELETE FROM `'._DB_PREFIX_.'seller_category` 
            WHERE id_shop = '.(int)$id_shop
        );
    }
    
    public static function isSellerCategory($id_category, $id_shop)
    {
        return Db::getInstance()->getValue(
            'SELECT COUNT(*) FROM '._DB_PREFIX_.'seller_category 
            WHERE id_category = '.(int)$id_category.' AND id_shop = '.(int)$id_shop
        );
    }
}
