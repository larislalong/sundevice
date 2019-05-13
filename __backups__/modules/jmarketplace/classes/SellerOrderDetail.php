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

class SellerOrderDetail extends ObjectModel
{
    public $id_seller_order;
    public $id_shop;
    public $product_id;
    public $product_attribute_id;
    public $id_customization;
    public $product_name;
    public $product_quantity;
    public $product_price;
    public $reduction_percent;
    public $reduction_amount;
    public $reduction_amount_tax_incl;
    public $reduction_amount_tax_excl;
    public $group_reduction;
    public $product_quantity_discount;
    public $product_ean13;
    public $product_isbn;
    public $product_upc;
    public $product_reference;
    public $product_weight;
    public $tax_name;
    public $tax_rate;
    public $tax_computation_method;
    public $id_tax_rules_group;
    public $ecotax;
    public $ecotax_tax_rate;
    public $discount_quantity_applied;
    public $unit_price_tax_incl;
    public $unit_price_tax_excl;
    public $total_price_tax_incl;
    public $total_price_tax_excl;
    public $total_shipping_price_tax_excl;
    public $total_shipping_price_tax_incl;
    public $unit_commission_tax_excl;
    public $unit_commission_tax_incl;
    public $total_commission_tax_excl;
    public $total_commission_tax_incl;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'seller_order_detail',
        'primary' => 'id_seller_order_detail',
        'fields' => array(
            'id_seller_order' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'product_id' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'product_attribute_id' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'id_customization' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'product_name' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true),
            'product_quantity' => array('type' => self::TYPE_INT, 'validate' => 'isInt', 'required' => true),
            'product_price' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice', 'required' => true),
            'reduction_percent' => array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat'),
            'reduction_amount' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'reduction_amount_tax_incl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'reduction_amount_tax_excl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'group_reduction' => array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat'),
            'product_quantity_discount' => array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat'),
            'product_ean13' => array('type' => self::TYPE_STRING, 'validate' => 'isEan13'),
            'product_isbn' => array('type' => self::TYPE_STRING),
            'product_upc' => array('type' => self::TYPE_STRING, 'validate' => 'isUpc'),
            'product_reference' => array('type' => self::TYPE_STRING, 'validate' => 'isReference'),
            'product_weight' => array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat'),
            'tax_name' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
            'tax_rate' => array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat'),
            'tax_computation_method' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'id_tax_rules_group' => array('type' => self::TYPE_INT, 'validate' => 'isInt'),
            'ecotax' => array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat'),
            'ecotax_tax_rate' => array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat'),
            'discount_quantity_applied' => array('type' => self::TYPE_INT, 'validate' => 'isInt'),
            'unit_price_tax_incl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'unit_price_tax_excl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_price_tax_incl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_price_tax_excl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_shipping_price_tax_excl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_shipping_price_tax_incl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'unit_commission_tax_excl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'unit_commission_tax_incl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_commission_tax_excl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_commission_tax_incl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
        ),
    );
    
    public static function getTotalPriceBySeller($id_seller, $from, $to, $ids_not_in_order_states)
    {
        if (Configuration::get('JMARKETPLACE_TAX_COMMISSION') == 1) {
            $field = 'total_price_tax_incl';
        } else {
            $field = 'total_price_tax_excl';
        }
        
        return Db::getInstance()->getValue(
            'SELECT SUM('.$field.')
            FROM '._DB_PREFIX_.'seller_order_detail sod
            LEFT JOIN `'._DB_PREFIX_.'seller_order` so ON (sod.`id_seller_order` = so.`id_seller_order`) 
            WHERE so.id_seller = '.(int)$id_seller.' 
            AND so.date_add BETWEEN "'.pSQL($from).'" AND "'.pSQL($to).'" 
            '.($ids_not_in_order_states ? 'AND so.current_state NOT IN ('.$ids_not_in_order_states.')' : '')
        );
    }
    
    public static function getProductQuantityBySeller($id_seller, $from, $to, $ids_not_in_order_states)
    {
        return Db::getInstance()->getValue(
            'SELECT SUM(product_quantity) 
            FROM '._DB_PREFIX_.'seller_order_detail sod
            LEFT JOIN `'._DB_PREFIX_.'seller_order` so ON (sod.`id_seller_order` = so.`id_seller_order`)
            WHERE id_seller = '.(int)$id_seller.' 
            AND date_add BETWEEN "'.pSQL($from).'" AND "'.pSQL($to).'" 
            '.($ids_not_in_order_states ? 'AND so.current_state NOT IN ('.$ids_not_in_order_states.')' : '')
        );
    }
}
