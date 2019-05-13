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

class Dashboard
{
    public static function getCommissionHistoryBySeller($id_seller, $id_lang, $id_shop, $from, $to)
    {
        $seller = Db::getInstance()->executeS(
            'SELECT sch.*, o.reference, s.name as seller_name, pl.name as product_name, schsl.name as state_name
            FROM '._DB_PREFIX_.'seller_commission_history sch
            LEFT JOIN `'._DB_PREFIX_.'seller` s ON (s.`id_seller` = sch.`id_seller`) 
            LEFT JOIN `'._DB_PREFIX_.'seller_commission_history_state` schs ON (schs.`id_seller_commission_history_state` = sch.`id_seller_commission_history_state`)
            LEFT JOIN `'._DB_PREFIX_.'seller_commission_history_state_lang` schsl ON (schsl.`id_seller_commission_history_state` = schs.`id_seller_commission_history_state` AND schsl.id_lang = '.(int)$id_lang.') 
            LEFT JOIN `'._DB_PREFIX_.'product` p ON (sch.`id_product` = p.`id_product`) 
            LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (pl.`id_product` = p.`id_product` AND pl.id_lang = '.(int)$id_lang.' AND pl.id_shop = '.(int)$id_shop.') 
            LEFT JOIN `'._DB_PREFIX_.'orders` o ON (o.`id_order` = sch.`id_order`)  
            WHERE s.id_seller = '.(int)$id_seller.' 
            AND schs.reference = "paid" 
            AND sch.date_add BETWEEN "'.pSQL($from).'" AND "'.pSQL($to).'"
            ORDER BY sch.date_add ASC'
        );
        if ($seller) {
            return $seller;
        }
        return false;
    }
    
    public static function getBenefitsBySeller($id_seller, $from, $to)
    {
        if (Configuration::get('JMARKETPLACE_TAX_COMMISSION') == 1) {
            $commission = 'total_commission_tax_incl';
        } else {
            $commission = 'total_commission_tax_excl';
        }
        
        return Db::getInstance()->getValue(
            'SELECT SUM('.$commission.') as benefits
            FROM '._DB_PREFIX_.'seller_commission_history sch
            LEFT JOIN `'._DB_PREFIX_.'seller_commission_history_state` schs ON (schs.`id_seller_commission_history_state` = sch.`id_seller_commission_history_state`)
            WHERE id_seller = '.(int)$id_seller.' 
            AND reference = "paid" 
            AND date_add BETWEEN "'.pSQL($from).'" AND "'.pSQL($to).'"'
        );
    }
    
    public static function getSalesBySeller($id_seller, $from, $to)
    {
        if (Configuration::get('JMARKETPLACE_TAX_COMMISSION') == 1) {
            $price = 'price_tax_incl';
        } else {
            $price = 'price_tax_excl';
        }
        
        return Db::getInstance()->getValue(
            'SELECT SUM('.$price.'*quantity) as sales
            FROM '._DB_PREFIX_.'seller_commission_history sch
            LEFT JOIN `'._DB_PREFIX_.'seller_commission_history_state` schs ON (schs.`id_seller_commission_history_state` = sch.`id_seller_commission_history_state`)
            WHERE id_seller = '.(int)$id_seller.' 
            AND reference = "paid" 
            AND date_add BETWEEN "'.pSQL($from).'" AND "'.pSQL($to).'"'
        );
    }
    
    public static function getProductQuantityBySeller($id_seller, $from, $to)
    {
        return Db::getInstance()->getValue(
            'SELECT SUM(quantity) as quantities 
            FROM '._DB_PREFIX_.'seller_commission_history sch
            LEFT JOIN `'._DB_PREFIX_.'seller_commission_history_state` schs ON (schs.`id_seller_commission_history_state` = sch.`id_seller_commission_history_state`)
            WHERE id_product != 0 
            AND id_seller = '.(int)$id_seller.' 
            AND reference = "paid" 
            AND date_add BETWEEN "'.pSQL($from).'" AND "'.pSQL($to).'"'
        );
    }
    
    public static function getNumOrdersBySeller($id_seller, $from, $to)
    {
        return Db::getInstance()->getValue(
            'SELECT COUNT(DISTINCT(id_order)) as orders
            FROM '._DB_PREFIX_.'seller_commission_history sch
            LEFT JOIN `'._DB_PREFIX_.'seller_commission_history_state` schs ON (schs.`id_seller_commission_history_state` = sch.`id_seller_commission_history_state`)
            WHERE id_seller = '.(int)$id_seller.' 
            AND schs.reference = "paid" 
            AND date_add BETWEEN "'.pSQL($from).'" AND "'.pSQL($to).'"'
        );
    }
    
    public static function compareDates($date_start, $date_end)
    {
        $endTimestamp = strtotime($date_end);
        $beginTimestamp = strtotime($date_start);
        return ceil(($endTimestamp - $beginTimestamp) / 86400);
    }
}
