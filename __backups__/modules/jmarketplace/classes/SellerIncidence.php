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

class SellerIncidence extends ObjectModel
{
    public $reference;
    public $id_order;
    public $id_product;
    public $id_customer;
    public $id_seller;
    public $id_employee;
    public $id_shop;
    public $date_add;
    public $date_upd;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'seller_incidence',
        'primary' => 'id_seller_incidence',
        'fields' => array(
            'reference' => array('type' => self::TYPE_STRING, 'validate' => 'isReference', 'size' => 8),
            'id_order' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => false),
            'id_product' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => false),
            'id_customer' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => false),
            'id_seller' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => false),
            'id_employee' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => false),
            'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => false),
            'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat', 'required' => false),
            'date_upd' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat', 'required' => false),
        ),
    );
    
    protected $webserviceParameters = array(
        'objectMethods' => array(
            'add' => 'addWs',
            'update' => 'updateWs'
        ),
        'objectNodeNames' => 'seller_incidences',
        'fields' => array(
            'id_order' => array('xlink_resource' => 'orders'),
            'id_product' => array('xlink_resource' => 'products'),
            'id_customer' => array('xlink_resource' => 'sellers'),
            'id_seller' => array('xlink_resource' => 'sellers'),
            'id_shop' => array('xlink_resource' => 'sellers'),
        ),
    );

    public function addWs($autodate = true, $null_values = false)
    {
        $success = $this->add($autodate, $null_values);
        return $success;
    }

    public function updateWs($null_values = false)
    {
        $success = parent::update($null_values);
        return $success;
    }
    
    public function add($autodate = true, $null_values = false)
    {
        if (!parent::add($autodate, $null_values)) {
            return false;
        }
        return true;
    }
    
    public function delete()
    {
        $result = $this->deleteMessages();
        $result = parent::delete();
        return $result;
    }
    
    public function deleteMessages()
    {
        return Db::getInstance()->execute(
            'DELETE FROM `'._DB_PREFIX_.'seller_incidence_message` 
            WHERE `id_seller_incidence` = '.(int)$this->id
        );
    }
    
    public static function getIncidencesByCustomer($id_customer)
    {
        $query = 'SELECT a.*, o.reference as order_ref FROM '._DB_PREFIX_.'seller_incidence a                    
                    LEFT JOIN `'._DB_PREFIX_.'customer` c ON (c.`id_customer` = a.`id_customer`) 
                    LEFT JOIN `'._DB_PREFIX_.'orders` o ON (o.`id_order` = a.`id_order`)
                    WHERE c.id_customer = '.(int)$id_customer.' ORDER BY a.date_upd DESC';
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
        if ($result) {
            return $result;
        }
        return false;
    }
    
    public static function getIncidencesBySeller($id_seller)
    {
        $query = 'SELECT a.*, o.reference as order_ref FROM '._DB_PREFIX_.'seller_incidence a                    
                    LEFT JOIN `'._DB_PREFIX_.'seller` c ON (c.`id_seller` = a.`id_seller`) 
                    LEFT JOIN `'._DB_PREFIX_.'orders` o ON (o.`id_order` = a.`id_order`)
                    WHERE c.id_seller = '.(int)$id_seller.' ORDER BY a.date_upd DESC';
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
        if ($result) {
            return $result;
        }
        return false;
    }
    
    public static function getMessages($id_incidence)
    {
        $query = 'SELECT im.*, c.firstname as customer_firstname, c.lastname as customer_lastname, s.name as seller_name, e.firstname as employee_firstname, e.lastname as employee_lastname
                FROM `'._DB_PREFIX_.'seller_incidence_message` im
                LEFT JOIN `'._DB_PREFIX_.'customer` c ON (c.`id_customer` = im.`id_customer`)
                LEFT JOIN `'._DB_PREFIX_.'seller` s ON (s.`id_seller` = im.`id_seller`)
                LEFT JOIN `'._DB_PREFIX_.'employee` e ON (e.`id_employee` = im.`id_employee`)
                WHERE im.id_seller_incidence = '.(int)$id_incidence.' ORDER BY im.date_add ASC';
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
        if ($result) {
            return $result;
        }
        return false;
    }
    
    public static function generateReference()
    {
        $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
        $ref = "#";
        for ($i=0; $i<6; $i++) {
            $ref .= Tools::substr($str, rand(0, 36), 1);
        }
        return $ref;
    }
    
    public static function getNumMessagesNotReaded($id_seller_incidence, $id_seller = false, $id_customer = false)
    {
        if ($id_seller != false) {
            $messages_not_readed = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow(
                'SELECT COUNT(*) as num FROM '._DB_PREFIX_.'seller_incidence_message
                WHERE readed = 0 
                AND id_seller = 0 
                AND (id_customer != 0 OR id_employee != 0) 
                AND id_seller_incidence = '.(int)$id_seller_incidence
            );
        } elseif ($id_customer != false) {
            $messages_not_readed = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow(
                'SELECT COUNT(*) as num FROM '._DB_PREFIX_.'seller_incidence_message
                WHERE readed = 0 
                AND id_customer = 0 
                AND (id_seller != 0 OR id_employee != 0) 
                AND id_seller_incidence = '.(int)$id_seller_incidence
            );
        }
        return $messages_not_readed['num'];
    }
}
