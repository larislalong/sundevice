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

class SellerOrder extends ObjectModel
{
    public $id_shop;
    public $id_order;
    public $reference;
    public $id_seller;
    public $id_customer;
    public $id_currency;
    public $conversion_rate;
    public $id_address_delivery;
    public $current_state;
    public $total_discounts;
    public $total_discounts_tax_incl;
    public $total_discounts_tax_excl;
    public $total_paid;
    public $total_paid_tax_incl;
    public $total_paid_tax_excl;
    public $total_products;
    public $total_products_tax_incl;
    public $total_products_tax_excl;
    public $total_shipping;
    public $total_shipping_tax_incl;
    public $total_shipping_tax_excl;
    public $total_wrapping;
    public $total_wrapping_tax_incl;
    public $total_wrapping_tax_excl;
    public $total_fixed_commission;
    public $total_fixed_commission_tax_incl;
    public $total_fixed_commission_tax_excl;
    public $date_add;
    public $date_upd;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'seller_order',
        'primary' => 'id_seller_order',
        'multi_shop' => true,
        'fields' => array(
            'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_order' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'reference' => array('type' => self::TYPE_STRING),
            'id_seller' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_currency' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'conversion_rate' => array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat', 'required' => true),
            'id_customer' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_address_delivery' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'current_state' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'total_discounts' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice', 'required' => true),
            'total_discounts_tax_incl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_discounts_tax_excl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_paid' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice', 'required' => true),
            'total_paid_tax_incl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_paid_tax_excl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_products' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice', 'required' => true),
            'total_products_tax_incl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_products_tax_excl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_shipping' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice', 'required' => true),
            'total_shipping_tax_incl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_shipping_tax_excl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_wrapping' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice', 'required' => true),
            'total_wrapping_tax_incl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_wrapping_tax_excl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_fixed_commission' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice', 'required' => true),
            'total_fixed_commission_tax_incl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_fixed_commission_tax_excl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat', 'required' => false),
            'date_upd' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat', 'required' => false),
        ),
    );
    
    protected $_taxCalculationMethod = PS_TAX_EXC;
    
    public function __construct($id = null, $id_lang = null)
    {
        parent::__construct($id, $id_lang);

        $is_admin = (is_object(Context::getContext()->controller) && Context::getContext()->controller->controller_type == 'admin');
        if ($this->id_customer && !$is_admin) {
            $customer = new Customer((int)$this->id_customer);
            $this->_taxCalculationMethod = Group::getPriceDisplayMethod((int)$customer->id_default_group);
        } else {
            $this->_taxCalculationMethod = Group::getDefaultPriceDisplayMethod();
        }
    }
    
    public function getTaxCalculationMethod()
    {
        return (int)$this->_taxCalculationMethod;
    }
    
    public function delete()
    {
        $result = parent::delete();
        $result = ($this->deleteOrderDetail() && $this->deleteOrderHistory());
        return $result;
    }
    
    public function deleteOrderDetail()
    {
        return Db::getInstance()->execute(
            'DELETE FROM `'._DB_PREFIX_.'seller_order_detail` 
            WHERE `id_seller_order` = '.(int)$this->id
        );
    }
    
    public function deleteOrderHistory()
    {
        return Db::getInstance()->execute(
            'DELETE FROM `'._DB_PREFIX_.'seller_order_history` 
            WHERE `id_seller_order` = '.(int)$this->id
        );
    }
    
    public static function getSellerOrders($id_seller, $id_lang)
    {
        $query = 'SELECT 
                    so.id_order,
                    so.reference,
                    o.id_currency,
                    so.total_discounts_tax_incl,
                    so.total_discounts_tax_excl,
                    so.total_paid_tax_incl,
                    so.total_paid_tax_excl,
                    so.total_fixed_commission,
                    so.date_add,
                    o.payment,
                    so.id_order AS id_pdf,
                    CONCAT(LEFT(c.`firstname`, 1), \'. \', c.`lastname`) AS `customer`,
                    osl.`name` AS `osname`,
                    os.`color`,
                    country_lang.name as cname,
                    IF(o.valid, 1, 0) badge_success
                    FROM '._DB_PREFIX_.'seller_order so
                    LEFT JOIN `'._DB_PREFIX_.'seller` s ON (s.`id_seller` = so.`id_seller`) 
                    LEFT JOIN `'._DB_PREFIX_.'orders` o ON (o.`id_order` = so.`id_order`)  
                    LEFT JOIN `'._DB_PREFIX_.'customer` c ON (c.`id_customer` = so.`id_customer`)
                    INNER JOIN `'._DB_PREFIX_.'address` address ON address.id_address = so.id_address_delivery
                    INNER JOIN `'._DB_PREFIX_.'country` country ON address.id_country = country.id_country
                    INNER JOIN `'._DB_PREFIX_.'country_lang` country_lang ON (country.`id_country` = country_lang.`id_country` AND country_lang.`id_lang` = '.(int)$id_lang.')
                    LEFT JOIN `'._DB_PREFIX_.'order_state` os ON (os.`id_order_state` = so.`current_state`)
                    LEFT JOIN `'._DB_PREFIX_.'order_state_lang` osl ON (os.`id_order_state` = osl.`id_order_state` AND osl.`id_lang` = '.(int)$id_lang.')                    
                    WHERE so.id_seller = '.(int)$id_seller.'
                    ORDER BY so.date_add DESC';
        $orders = Db::getInstance()->executeS($query);
        if ($orders) {
            return $orders;
        }
        return false;
    }
    
    public static function getIdOrdersBySeller($id_seller)
    {
        $orders = Db::getInstance()->executeS(
            'SELECT id_seller_order 
            FROM '._DB_PREFIX_.'seller_order 
            WHERE id_seller = '.(int)$id_seller
        );
        if ($orders) {
            return $orders;
        }
        return false;
    }
    
    public static function getLastSellerOrders($id_seller, $id_lang)
    {
        $query = 'SELECT 
                    so.id_order,
                    so.reference,
                    o.id_currency,
                    so.total_discounts_tax_incl,
                    so.total_discounts_tax_excl,
                    so.total_paid_tax_incl,
                    so.total_paid_tax_excl,
                    so.total_fixed_commission,
                    so.date_add,
                    o.payment,
                    so.id_order AS id_pdf,
                    CONCAT(LEFT(c.`firstname`, 1), \'. \', c.`lastname`) AS `customer`,
                    osl.`name` AS `osname`,
                    os.`color`,
                    country_lang.name as cname,
                    IF(o.valid, 1, 0) badge_success
                    FROM '._DB_PREFIX_.'seller_order so
                    LEFT JOIN `'._DB_PREFIX_.'seller` s ON (s.`id_seller` = so.`id_seller`) 
                    LEFT JOIN `'._DB_PREFIX_.'orders` o ON (o.`id_order` = so.`id_order`)  
                    LEFT JOIN `'._DB_PREFIX_.'customer` c ON (c.`id_customer` = so.`id_customer`)
                    INNER JOIN `'._DB_PREFIX_.'address` address ON address.id_address = so.id_address_delivery
                    INNER JOIN `'._DB_PREFIX_.'country` country ON address.id_country = country.id_country
                    INNER JOIN `'._DB_PREFIX_.'country_lang` country_lang ON (country.`id_country` = country_lang.`id_country` AND country_lang.`id_lang` = '.(int)$id_lang.')
                    LEFT JOIN `'._DB_PREFIX_.'order_state` os ON (os.`id_order_state` = so.`current_state`)
                    LEFT JOIN `'._DB_PREFIX_.'order_state_lang` osl ON (os.`id_order_state` = osl.`id_order_state` AND osl.`id_lang` = '.(int)$id_lang.')                    
                    WHERE so.id_seller = '.(int)$id_seller.'
                    ORDER BY so.date_add DESC LIMIT 0,5';
        $orders = Db::getInstance()->executeS($query);
        if ($orders) {
            return $orders;
        }
        return false;
    }
    
    public static function getSellerOrdersByOrder($id_order, $id_lang)
    {
        $query = 'SELECT 
                    so.id_order,
                    so.id_seller_order,
                    so.reference,
                    so.id_currency,
                    so.total_discounts_tax_incl,
                    so.total_discounts_tax_excl,
                    so.total_paid_tax_incl,
                    so.total_paid_tax_excl,
                    so.total_fixed_commission,
                    so.date_add,
                    o.payment,
                    so.id_order AS id_pdf,
                    CONCAT(LEFT(c.`firstname`, 1), \'. \', c.`lastname`) AS `customer`,
                    s.name as seller_name,
                    osl.`name` AS `osname`,
                    os.`color`,
                    country_lang.name as cname,
                    IF(o.valid, 1, 0) badge_success
                    FROM '._DB_PREFIX_.'seller_order so
                    LEFT JOIN `'._DB_PREFIX_.'seller` s ON (s.`id_seller` = so.`id_seller`) 
                    LEFT JOIN `'._DB_PREFIX_.'orders` o ON (o.`id_order` = so.`id_order`)  
                    LEFT JOIN `'._DB_PREFIX_.'customer` c ON (c.`id_customer` = so.`id_customer`)
                    INNER JOIN `'._DB_PREFIX_.'address` address ON address.id_address = so.id_address_delivery
                    INNER JOIN `'._DB_PREFIX_.'country` country ON address.id_country = country.id_country
                    INNER JOIN `'._DB_PREFIX_.'country_lang` country_lang ON (country.`id_country` = country_lang.`id_country` AND country_lang.`id_lang` = '.(int)$id_lang.')
                    LEFT JOIN `'._DB_PREFIX_.'order_state` os ON (os.`id_order_state` = so.`current_state`)
                    LEFT JOIN `'._DB_PREFIX_.'order_state_lang` osl ON (os.`id_order_state` = osl.`id_order_state` AND osl.`id_lang` = '.(int)$id_lang.')                    
                    WHERE so.id_order = '.(int)$id_order.'
                    ORDER BY so.date_add DESC';
        $orders = Db::getInstance()->executeS($query);
        if ($orders) {
            return $orders;
        }
        return false;
    }
    
    public static function getSellerOrdersByDate($id_seller, $id_lang, $from, $to, $ids_not_in_order_states)
    {
        $query = 'SELECT so.*, s.name as seller_name, osl.name as state_name
                    FROM '._DB_PREFIX_.'seller_order so
                    LEFT JOIN `'._DB_PREFIX_.'seller` s ON (s.`id_seller` = so.`id_seller`) 
                    LEFT JOIN `'._DB_PREFIX_.'order_state` os ON (os.`id_order_state` = so.`current_state`)
                    LEFT JOIN `'._DB_PREFIX_.'order_state_lang` osl ON (os.`id_order_state` = osl.`id_order_state` AND osl.`id_lang` = '.(int)$id_lang.')
                    WHERE so.id_seller = '.(int)$id_seller.' 
                    AND so.date_add BETWEEN "'.pSQL($from).'" AND "'.pSQL($to).'" 
                    '.($ids_not_in_order_states ? 'AND so.current_state NOT IN ('.$ids_not_in_order_states.')' : '').'
                    ORDER BY so.date_add ASC';
        $orders = Db::getInstance()->executeS($query);
        if ($orders) {
            return $orders;
        }
        return false;
    }
    
    public static function getNumOrdersBySeller($id_seller, $from, $to, $ids_not_in_order_states)
    {
        return Db::getInstance()->getValue(
            'SELECT COUNT(*)
            FROM '._DB_PREFIX_.'seller_order
            WHERE id_seller = '.(int)$id_seller.' 
            AND date_add BETWEEN "'.pSQL($from).'" AND "'.pSQL($to).'" 
            '.($ids_not_in_order_states ? 'AND current_state NOT IN ('.$ids_not_in_order_states.')' : '')
        );
    }
    
    public static function getIdSellerOrderByOrderAndSeller($id_order, $id_seller)
    {
        return Db::getInstance()->getValue(
            'SELECT id_seller_order FROM '._DB_PREFIX_.'seller_order 
            WHERE id_order = '.(int)$id_order.' AND id_seller = '.(int)$id_seller
        );
    }
    
    public static function getIdSellerOrdersByOrder($id_order)
    {
        return Db::getInstance()->ExecuteS(
            'SELECT id_seller_order FROM '._DB_PREFIX_.'seller_order 
            WHERE id_order = '.(int)$id_order
        );
    }
    
    public static function existSellerOrder($id_order, $id_seller)
    {
        return Db::getInstance()->getValue(
            'SELECT COUNT(*) FROM '._DB_PREFIX_.'seller_order 
            WHERE id_order = '.(int)$id_order.' AND id_seller = '.(int)$id_seller
        );
    }
    
    public static function getTotalPaidBySeller($id_seller, $from, $to, $ids_not_in_order_states)
    {
        if (Configuration::get('JMARKETPLACE_TAX_COMMISSION') == 1) {
            $commission = 'total_paid_tax_incl';
        } else {
            $commission = 'total_paid_tax_excl';
        }
        
        return Db::getInstance()->getValue(
            'SELECT SUM('.$commission.') as benefits
            FROM '._DB_PREFIX_.'seller_order
            WHERE id_seller = '.(int)$id_seller.' 
            AND date_add BETWEEN "'.pSQL($from).'" AND "'.pSQL($to).'" 
            '.($ids_not_in_order_states ? 'AND current_state NOT IN ('.$ids_not_in_order_states.')' : '')
        );
    }
    
    public function getProductsDetail()
    {
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
        SELECT *
        FROM `'._DB_PREFIX_.'seller_order_detail` od
        LEFT JOIN `'._DB_PREFIX_.'product` p ON (p.id_product = od.product_id)
        LEFT JOIN `'._DB_PREFIX_.'product_shop` ps ON (ps.id_product = p.id_product AND ps.id_shop = od.id_shop)
        WHERE od.`id_seller_order` = '.(int)$this->id);
    }
    
    public function getHistory($id_lang, $id_order_state = false, $no_hidden = false, $filters = 0)
    {
        if (!$id_order_state) {
            $id_order_state = 0;
        }

        $logable = false;
        $delivery = false;
        $paid = false;
        $shipped = false;
        if ($filters > 0) {
            if ($filters & OrderState::FLAG_NO_HIDDEN) {
                $no_hidden = true;
            }
            if ($filters & OrderState::FLAG_DELIVERY) {
                $delivery = true;
            }
            if ($filters & OrderState::FLAG_LOGABLE) {
                $logable = true;
            }
            if ($filters & OrderState::FLAG_PAID) {
                $paid = true;
            }
            if ($filters & OrderState::FLAG_SHIPPED) {
                $shipped = true;
            }
        }

        $id_lang = $id_lang ? (int)$id_lang : 'o.`id_lang`';
        $result = Db::getInstance()->executeS(
            'SELECT os.*, oh.*, osl.`name` as ostate_name
            FROM `'._DB_PREFIX_.'seller_order_history` oh
            LEFT JOIN `'._DB_PREFIX_.'order_state` os ON os.`id_order_state` = oh.`id_order_state`
            LEFT JOIN `'._DB_PREFIX_.'order_state_lang` osl ON (os.`id_order_state` = osl.`id_order_state` AND osl.`id_lang` = '.(int)($id_lang).')
            WHERE oh.id_seller_order = '.(int)$this->id.'
            '.($no_hidden ? ' AND os.hidden = 0' : '').'
            '.($logable ? ' AND os.logable = 1' : '').'
            '.($delivery ? ' AND os.delivery = 1' : '').'
            '.($paid ? ' AND os.paid = 1' : '').'
            '.($shipped ? ' AND os.shipped = 1' : '').'
            '.((int)$id_order_state ? ' AND oh.`id_order_state` = '.(int)$id_order_state : '').'
            ORDER BY oh.date_add DESC, oh.id_seller_order_history DESC'
        );
        return $result;
    }
    
    public function getTotalWeight()
    {
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
        SELECT SUM(product_weight * product_quantity)
        FROM '._DB_PREFIX_.'seller_order_detail
        WHERE id_seller_order = '.(int)$this->id);
        return (float)$result;
    }
    
    public function getShipping()
    {
        return Db::getInstance()->executeS(
            'SELECT DISTINCT oc.`id_order_invoice`, oc.`weight`, oc.`shipping_cost_tax_excl`, oc.`shipping_cost_tax_incl`, c.`url`, oc.`id_carrier`, c.`name` as `carrier_name`, oc.`date_add`, "Delivery" as `type`, "true" as `can_edit`, oc.`tracking_number`, oc.`id_order_carrier`, osl.`name` as order_state_name, c.`name` as state_name
            FROM `'._DB_PREFIX_.'seller_order` o
            LEFT JOIN `'._DB_PREFIX_.'seller_order_history` oh
                ON (o.`id_seller_order` = oh.`id_seller_order`)
            LEFT JOIN `'._DB_PREFIX_.'order_carrier` oc
                ON (o.`id_order` = oc.`id_order`)
            LEFT JOIN `'._DB_PREFIX_.'carrier` c
                ON (oc.`id_carrier` = c.`id_carrier`)
            LEFT JOIN `'._DB_PREFIX_.'order_state_lang` osl
                ON (oh.`id_order_state` = osl.`id_order_state` AND osl.`id_lang` = '.(int)Context::getContext()->language->id.')
            WHERE o.`id_order` = '.(int)$this->id_order.'
            GROUP BY c.id_carrier'
        );
    }
}
