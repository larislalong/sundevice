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

class SellerCommissionHistory extends ObjectModel
{
    public $id_order;
    public $id_product;
    public $product_name;
    public $id_seller;
    public $id_shop;
    public $id_currency;
    public $conversion_rate;
    public $price_tax_incl;
    public $price_tax_excl;
    public $quantity;
    public $unit_commission_tax_incl;
    public $unit_commission_tax_excl;
    public $total_commission_tax_incl;
    public $total_commission_tax_excl;
    public $id_seller_commission_history_state;
    public $date_add;
    public $date_upd;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'seller_commission_history',
        'primary' => 'id_seller_commission_history',
        'fields' => array(
            'id_order' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => false),
            'id_product' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => false),
            'product_name' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => false),
            'id_seller' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => false),
            'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => false),
            'id_currency' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => false),
            'conversion_rate' => array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat', 'required' => true),
            'price_tax_excl' => array('type' => self::TYPE_FLOAT, 'required' => false),
            'price_tax_incl' => array('type' => self::TYPE_FLOAT, 'required' => false),
            'quantity' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => false),
            'unit_commission_tax_incl' => array('type' => self::TYPE_FLOAT, 'required' => false),
            'unit_commission_tax_excl' => array('type' => self::TYPE_FLOAT, 'required' => false),
            'total_commission_tax_incl' => array('type' => self::TYPE_FLOAT, 'required' => false),
            'total_commission_tax_excl' => array('type' => self::TYPE_FLOAT, 'required' => false),
            'id_seller_commission_history_state' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => false),
            'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat', 'required' => false),
            'date_upd' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat', 'required' => false),
        ),
    );
    
    protected $webserviceParameters = array(
        'objectMethods' => array(
            'add' => 'addWs',
            'update' => 'updateWs'
        ),
        'objectNodeNames' => 'seller_commissions_history',
        'fields' => array(
            'id_order' => array('xlink_resource' => 'orders'),
            'id_product' => array('xlink_resource' => 'products'),
            'id_seller' => array('xlink_resource' => 'sellers'),
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
    
    public static function getCommissionHistoryBySeller($id_seller, $id_lang, $id_shop)
    {
        $query = 'SELECT sch.*, o.reference, s.name as seller_name, schsl.name as state_name, sch.date_add
                    FROM '._DB_PREFIX_.'seller_commission_history sch
                    LEFT JOIN `'._DB_PREFIX_.'seller` s ON (s.`id_seller` = sch.`id_seller`) 
                    LEFT JOIN `'._DB_PREFIX_.'seller_commission_history_state` schs ON (schs.`id_seller_commission_history_state` = sch.`id_seller_commission_history_state`)
                    LEFT JOIN `'._DB_PREFIX_.'seller_commission_history_state_lang` schsl ON (schsl.`id_seller_commission_history_state` = schs.`id_seller_commission_history_state` AND schsl.id_lang = '.(int)$id_lang.') 
                    LEFT JOIN `'._DB_PREFIX_.'product` p ON (sch.`id_product` = p.`id_product`) 
                    LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (pl.`id_product` = p.`id_product` AND pl.id_lang = '.(int)$id_lang.' AND pl.id_shop = '.(int)$id_shop.') 
                    LEFT JOIN `'._DB_PREFIX_.'orders` o ON (o.`id_order` = sch.`id_order`)  
                    WHERE s.id_seller = '.(int)$id_seller.'
                    ORDER BY sch.date_add DESC';
        $seller = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
        if ($seller) {
            return $seller;
        }
        return false;
    }
    
    public static function getCommissionHistoryByOrder($id_order, $id_lang, $id_shop)
    {
        $query = 'SELECT sch.*, sch.id_order, o.reference, schsl.name as state_name, sch.date_add
                    FROM '._DB_PREFIX_.'seller_commission_history sch
                    LEFT JOIN `'._DB_PREFIX_.'seller_commission_history_state` schs ON (schs.`id_seller_commission_history_state` = sch.`id_seller_commission_history_state`)
                    LEFT JOIN `'._DB_PREFIX_.'seller_commission_history_state_lang` schsl ON (schsl.`id_seller_commission_history_state` = schs.`id_seller_commission_history_state` AND schsl.id_lang = '.(int)$id_lang.') 
                    LEFT JOIN `'._DB_PREFIX_.'product` p ON (sch.`id_product` = p.`id_product`) 
                    LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (pl.`id_product` = p.`id_product` AND pl.id_lang = '.(int)$id_lang.' AND pl.id_shop = '.(int)$id_shop.') 
                    LEFT JOIN `'._DB_PREFIX_.'orders` o ON (o.`id_order` = sch.`id_order`)  
                    WHERE sch.id_order = '.(int)$id_order.'
                    ORDER BY sch.date_add DESC';
        $seller = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
        if ($seller) {
            return $seller;
        }
        return false;
    }
    
    public static function getCommissionsBySellerAndState($id_seller_commission_history_state, $id_seller, $from = false, $to = false)
    {
        $sql = 'SELECT * FROM '._DB_PREFIX_.'seller_commission_history 
               WHERE id_seller_commission_history_state = '.(int)$id_seller_commission_history_state.' AND id_seller = '.(int)$id_seller;
        
        if ($from != false) {
            $sql .= ' AND DATE_FORMAT(date_add, "%Y-%m-%d") BETWEEN "'.pSQL($from).'" AND "'.pSQL($to).'"';
        }

        return Db::getInstance()->ExecuteS($sql);
    }
    
    public static function getTotalCommissionByOrder($id_order)
    {
        if (Configuration::get('JMARKETPLACE_TAX_COMMISSION') == 1) {
            $commission = 'total_commission_tax_incl';
        } else {
            $commission = 'total_commission_tax_excl';
        }
        
        return Db::getInstance()->getValue(
            'SELECT SUM('.$commission.') FROM '._DB_PREFIX_.'seller_commission_history 
            WHERE id_order = '.(int)$id_order
        );
    }
    
    public static function getFixedCommissionOfSellerInOrder($id_seller, $id_order)
    {
        if (Configuration::get('JMARKETPLACE_TAX_COMMISSION') == 1) {
            $commission = 'total_commission_tax_incl';
        } else {
            $commission = 'total_commission_tax_excl';
        }
        
        return Db::getInstance()->getValue(
            'SELECT '.$commission.' FROM '._DB_PREFIX_.'seller_commission_history 
            WHERE id_seller = '.(int)$id_seller.' 
            AND id_order ='.(int)$id_order.' 
            AND id_product = 0 AND '.$commission.' < 0'
        );
    }
    
    public static function changeStateCommissionsByOrder($id_order, $reference)
    {
        $order_commissions = SellerCommissionHistory::getCommissionHistoryByOrder($id_order, Context::getContext()->language->id, Context::getContext()->shop->id);
        if ($order_commissions) {
            foreach ($order_commissions as $commission) {
                $seller_commission_history = new SellerCommissionHistory($commission['id_seller_commission_history']);
                $seller_commission_history->id_seller_commission_history_state = SellerCommissionHistoryState::getIdByReference($reference);
                $seller_commission_history->update();
            }
        }
    }
    
    public static function getCommissions($id_seller_commission_history_state, $id_shop, $from = false, $to = false)
    {
        $sql = 'SELECT * FROM '._DB_PREFIX_.'seller_commission_history 
               WHERE id_seller_commission_history_state = '.(int)$id_seller_commission_history_state.' 
               AND id_shop = '.(int)$id_shop;
        
        if ($from != false) {
            $sql .= ' AND DATE_FORMAT(date_add, "%Y-%m-%d") BETWEEN "'.pSQL($from).'" AND "'.pSQL($to).'"';
        }
        
        return Db::getInstance()->ExecuteS($sql);
    }
    
    
    public static function getTotalCommissionForSellers($id_seller_commission_history_state, $id_shop, $from = false, $to = false)
    {
        $sql = 'SELECT * FROM '._DB_PREFIX_.'seller_commission_history 
               WHERE id_seller_commission_history_state = '.(int)$id_seller_commission_history_state.' 
               AND id_shop = '.(int)$id_shop;
        
        if ($from != false) {
            $sql .= ' AND DATE_FORMAT(date_add, "%Y-%m-%d") BETWEEN "'.pSQL($from).'" AND "'.pSQL($to).'"';
        }
        
        return Db::getInstance()->ExecuteS($sql);
    }
    
    public static function getTotalPriceHistory($id_seller_commission_history_state, $id_shop, $from = false, $to = false, $use_tax = false)
    {
        if ($use_tax == 1) {
            $price = 'price_tax_incl';
        } else {
            $price = 'price_tax_excl';
        }
        
        $sql = 'SELECT * FROM '._DB_PREFIX_.'seller_commission_history 
               WHERE '.$price.' > 0 
               AND id_seller_commission_history_state = '.(int)$id_seller_commission_history_state.' 
               AND id_shop = '.(int)$id_shop;
        
        if ($from != false) {
            $sql .= ' AND DATE_FORMAT(date_add, "%Y-%m-%d") BETWEEN "'.pSQL($from).'" AND "'.pSQL($to).'"';
        }
        
        return Db::getInstance()->ExecuteS($sql);
    }
    
    public static function getTotalVariableCommissionsForAdmin($id_seller_commission_history_state, $id_shop, $id_seller = false, $from = false, $to = false)
    {
        $sql = 'SELECT * FROM '._DB_PREFIX_.'seller_commission_history 
               WHERE id_seller_commission_history_state = '.(int)$id_seller_commission_history_state.' 
               AND total_commission_tax_excl > 0 AND id_shop = '.(int)$id_shop;

        if ($id_seller != false) {
            $sql .= ' AND id_seller = '.(int)$id_seller;
        }
        
        if ($from != false) {
            $sql .= ' AND DATE_FORMAT(date_add, "%Y-%m-%d") BETWEEN "'.pSQL($from).'" AND "'.pSQL($to).'"';
        }
        
        return Db::getInstance()->ExecuteS($sql);
    }
    
    public static function getTotalFixCommissionsForAdmin($id_seller_commission_history_state, $id_shop, $id_seller = false, $from = false, $to = false)
    {
        $sql = 'SELECT * FROM '._DB_PREFIX_.'seller_commission_history 
               WHERE total_commission_tax_excl < 0 
               AND id_seller_commission_history_state = '.(int)$id_seller_commission_history_state.' 
               AND id_shop = '.(int)$id_shop;

        if ($id_seller != false) {
            $sql .= ' AND id_seller = '.(int)$id_seller;
        }
        
        if ($from != false) {
            $sql .= ' AND DATE_FORMAT(date_add, "%Y-%m-%d") BETWEEN "'.pSQL($from).'" AND "'.pSQL($to).'"';
        }
        
        return Db::getInstance()->ExecuteS($sql);
    }
    
    public static function getSellerByOrder($id_order)
    {
        return Db::getInstance()->getValue(
            'SELECT id_seller FROM '._DB_PREFIX_.'seller_commission_history 
            WHERE id_order = '.(int)$id_order
        );
    }
}
