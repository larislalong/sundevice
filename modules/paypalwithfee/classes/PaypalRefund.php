<?php
/**
 * 2014 4webs
 *
 * DEVELOPED By 4webs.es Prestashop Platinum Partner
 *
 * @author    4webs
 * @copyright 4webs 2014
 * @license   4webs
 * @version 4.0.2
 * @category payment_gateways
 */

class PaypalRefund extends ObjectModel {

    public $id_refund;
    public $id_ppwf;
    public $id_order;
    public $amount;
    public $transaction_id;
    public $date;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'ppwf_order_refund',
        'primary' => 'id_refund',
        'multilang' => false,
        'fields' => array(
            'id_ppwf' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'id_order' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'amount' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice', 'required' => true),
            'transaction_id' => array('type' => self::TYPE_STRING, 'size' => 50),
            'date' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
        )
    );

    public function add($autodate = true, $null_values = false) {
        if (parent::add($autodate, $null_values)) {
            return true;
        }
        return false;
    }

    public static function getTransactionID($id_order) {
        $sql = 'SELECT `transaction_id` FROM `' . _DB_PREFIX_ . 'ppwf_order` WHERE `id_order`=' . (int) $id_order;
        return Db::getInstance()->getValue($sql);
    }

    public static function getPpwfID($id_order) {
        $sql = 'SELECT `id_ppwf` FROM `' . _DB_PREFIX_ . 'ppwf_order` WHERE `id_order`=' . (int) $id_order;
        return Db::getInstance()->getValue($sql);
    }

    public static function getRefundData($id_order) {
        $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'ppwf_order_refund` WHERE `id_order`=' . (int) $id_order;
        return Db::getInstance()->executeS($sql);
    }

    public static function getMaxRefundAmount($id_order) {
        $sql = 'SELECT sum(`amount`) FROM `' . _DB_PREFIX_ . 'ppwf_order_refund` WHERE `id_order`=' . (int) $id_order;
        return Db::getInstance()->getValue($sql);
    }

}
