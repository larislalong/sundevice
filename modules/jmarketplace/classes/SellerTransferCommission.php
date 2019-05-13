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

class SellerTransferCommission extends ObjectModel
{
    public $id_seller_transfer_invoice;
    public $id_seller_commission_history;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'seller_transfer_commission',
        'primary' => 'id_seller_transfer_commission',
        'fields' => array(
            'id_seller_transfer_invoice' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => false),
            'id_seller_commission_history' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => false),
        ),
    );
    
    /**
     * Retorn el historial de comisiones pendientes de un vededor
     * @param type $id_seller
     * @param type $id_lang
     * @param type $id_shop
     * @return boolean
     */
    public static function getCommissionHistoryBySeller($id_seller, $id_lang)
    {
        $query = 'SELECT sch.*, o.reference, s.name as seller_name, product_name, schsl.name as state_name
                    FROM '._DB_PREFIX_.'seller_commission_history sch
                    LEFT JOIN `'._DB_PREFIX_.'seller` s ON (s.`id_seller` = sch.`id_seller`) 
                    LEFT JOIN `'._DB_PREFIX_.'seller_commission_history_state` schs ON (schs.`id_seller_commission_history_state` = sch.`id_seller_commission_history_state`)
                    LEFT JOIN `'._DB_PREFIX_.'seller_commission_history_state_lang` schsl ON (schsl.`id_seller_commission_history_state` = schs.`id_seller_commission_history_state` AND schsl.id_lang = '.$id_lang.') 
                    LEFT JOIN `'._DB_PREFIX_.'orders` o ON (o.`id_order` = sch.`id_order`)   
                    WHERE s.id_seller = '.(int)$id_seller.' AND schs.id_seller_commission_history_state = 1
                    ORDER BY sch.date_add DESC';
        $seller = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
        if ($seller) {
            return $seller;
        }
        return false;
    }
    
    public static function getTransferCommissionsBySeller($id_seller)
    {
        $query = 'SELECT id_seller_commission_history, validate
                    FROM '._DB_PREFIX_.'seller_transfer_commission stc
                    LEFT JOIN `'._DB_PREFIX_.'seller_transfer_invoice` sti ON (sti.id_seller_transfer_invoice = stc.id_seller_transfer_invoice)  
                    WHERE sti.`id_seller` = '.(int)$id_seller;
        $transfer = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
        if ($transfer) {
            return $transfer;
        }
        return false;
    }
    
    public static function getTransferCommissionsByTransferInvoice($id_seller_transfer_invoice)
    {
        $query = 'SELECT * FROM '._DB_PREFIX_.'seller_transfer_commission WHERE id_seller_transfer_invoice = '.(int)$id_seller_transfer_invoice;
        $transfer = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
        if ($transfer) {
            return $transfer;
        }
        return false;
    }
    
    public static function getCommissionsByTransferInvoice($id_seller_transfer_invoice, $id_lang)
    {
        $query = 'SELECT o.id_order, o.reference, s.name as seller_name, sch.product_name, sch.*, schsl.name as state_name, sch.date_add FROM '._DB_PREFIX_.'seller_transfer_commission stc
                    LEFT JOIN `'._DB_PREFIX_.'seller_commission_history` sch ON (sch.`id_seller_commission_history` = stc.`id_seller_commission_history`) 
                    LEFT JOIN `'._DB_PREFIX_.'seller` s ON (s.`id_seller` = sch.`id_seller`) 
                    LEFT JOIN `'._DB_PREFIX_.'seller_commission_history_state` schs ON (schs.`id_seller_commission_history_state` = sch.`id_seller_commission_history_state`)
                    LEFT JOIN `'._DB_PREFIX_.'seller_commission_history_state_lang` schsl ON (schsl.`id_seller_commission_history_state` = schs.`id_seller_commission_history_state` AND schsl.id_lang = '.$id_lang.') 
                    LEFT JOIN `'._DB_PREFIX_.'orders` o ON (o.`id_order` = sch.`id_order`)   
                    WHERE id_seller_transfer_invoice = '.(int)$id_seller_transfer_invoice;
        $transfer = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
        if ($transfer) {
            return $transfer;
        }
        return false;
    }
    
    public static function isSellerTransferCommission($id_seller_commission_history)
    {
        return Db::getInstance()->getValue(
            'SELECT COUNT(*) FROM '._DB_PREFIX_.'seller_transfer_commission 
            WHERE id_seller_commission_history = '.(int)$id_seller_commission_history
        );
    }
}
