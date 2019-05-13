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

class SellerTransferInvoice extends ObjectModel
{
    public $id_seller;
    public $id_currency;
    public $conversion_rate;
    public $total;
    public $validate;
    public $payment;
    public $date_add;
    public $date_upd;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'seller_transfer_invoice',
        'primary' => 'id_seller_transfer_invoice',
        'fields' => array(
            'id_seller' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => false),
            'id_currency' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => false),
            'conversion_rate' => array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat', 'required' => true),
            'total' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice', 'required' => false),
            'payment' => array('type' => self::TYPE_STRING, 'size' => 32, 'required' => false),
            'validate' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => false),
            'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat', 'required' => false),
            'date_upd' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat', 'required' => false),
        ),
    );
    
    public static function getTransferFunsHistoryBySeller($id_seller)
    {
        $seller = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(
            'SELECT sti.id_seller_transfer_invoice, s.name as seller_name, total, sti.validate, sti.date_add, sti.date_upd
            FROM '._DB_PREFIX_.'seller_transfer_invoice sti
            LEFT JOIN `'._DB_PREFIX_.'seller` s ON (s.`id_seller` = sti.`id_seller`) 
            WHERE s.id_seller = '.(int)$id_seller.'
            ORDER BY sti.date_add DESC'
        );
        if ($seller) {
            return $seller;
        }
        return false;
    }
    
    public static function getValidatePaymentBySeller($id_seller, $validate)
    {
        return Db::getInstance()->getValue(
            'SELECT SUM(total) as benefits FROM '._DB_PREFIX_.'seller_transfer_invoice 
            WHERE validate  = '.$validate.' AND id_seller = '.(int)$id_seller
        );
    }
    
    public function getTransferCommissions()
    {
        return Db::getInstance()->executeS(
            'SELECT id_seller_commission_history FROM '._DB_PREFIX_.'seller_transfer_commission 
            WHERE id_seller_transfer_invoice = '.(int)$this->id
        );
    }
}
