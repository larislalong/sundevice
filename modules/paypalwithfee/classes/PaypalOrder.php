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

class PaypalOrderx extends ObjectModel {

    public $id_ppwf;
    public $id_cart;
    public $id_order;
    public $total_amount;
    public $tax_rate;
    public $fee;
    public $transaction_id;
    public $id_shop;
    public $total_paypal;
    public $ppwf_version;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'ppwf_order',
        'primary' => 'id_ppwf',
        'multilang' => false,
        'fields' => array(
            'id_cart' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'id_order' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'total_amount' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice', 'required' => true),
            'total_paypal' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice', 'required' => true),
            'tax_rate' => array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat', 'required' => true),
            'fee' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice', 'required' => true),
            'transaction_id' => array('type' => self::TYPE_STRING, 'size' => 50),
            'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'ppwf_version' => array('type' => self::TYPE_STRING, 'size' => 8),
        )
    );

    public function add($autodate = true, $null_values = false) {
        if (parent::add($autodate, $null_values)) {
            return true;
        }
        return false;
    }

    public static function getFeeData($id_order) {
        $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'ppwf_order` WHERE `id_order`=' . (int) $id_order;
        return Db::getInstance()->getRow($sql);
    }

    public static function getFeeDB($id_order) {
        $sql = 'SELECT `fee` FROM `' . _DB_PREFIX_ . 'ppwf_order` WHERE `id_order`=' . (int) $id_order;
        return Db::getInstance()->getValue($sql);
    }
    
    public static function getPaypalByIdOrder($id_order){
        $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'ppwf_order` WHERE `id_order`=' . (int) $id_order;
        return Db::getInstance()->getRow($sql);
    }

}
