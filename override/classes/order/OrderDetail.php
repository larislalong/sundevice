<?php
/*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class OrderDetail extends OrderDetailCore
{
    /** @var int */
    public $idp;
    public $emei;
    public $product_old_price;
    public $floor_price;
    public static $definition = array(
        'table' => 'order_detail',
        'primary' => 'id_order_detail',
        'fields' => array(
            'id_order' =>                    array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_order_invoice' =>            array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'id_warehouse' =>                array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_shop' =>                array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'product_id' =>                array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'product_attribute_id' =>        array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'product_name' =>                array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true),
            'product_quantity' =>            array('type' => self::TYPE_INT, 'validate' => 'isInt', 'required' => true),
            'product_quantity_in_stock' =>    array('type' => self::TYPE_INT, 'validate' => 'isInt'),
            'product_quantity_return' =>    array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'product_quantity_refunded' =>    array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'product_quantity_reinjected' =>array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'product_price' =>                array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice', 'required' => true),
            'product_old_price' =>                array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'floor_price' =>                array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'reduction_percent' =>            array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat'),
            'reduction_amount' =>            array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'reduction_amount_tax_incl' =>  array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'reduction_amount_tax_excl' =>  array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'group_reduction' =>            array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat'),
            'product_quantity_discount' =>    array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat'),
            'product_ean13' =>                array('type' => self::TYPE_STRING, 'validate' => 'isEan13'),
            'product_upc' =>                array('type' => self::TYPE_STRING, 'validate' => 'isUpc'),
            'product_reference' =>            array('type' => self::TYPE_STRING, 'validate' => 'isReference'),
            'product_supplier_reference' => array('type' => self::TYPE_STRING, 'validate' => 'isReference'),
            'product_weight' =>            array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat'),
            'tax_name' =>                    array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
            'tax_rate' =>                    array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat'),
            'tax_computation_method' =>        array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'id_tax_rules_group' =>        array('type' => self::TYPE_INT, 'validate' => 'isInt'),
            'ecotax' =>                    array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat'),
            'ecotax_tax_rate' =>            array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat'),
            'discount_quantity_applied' =>    array('type' => self::TYPE_INT, 'validate' => 'isInt'),
            'download_hash' =>                array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
            'download_nb' =>                array('type' => self::TYPE_INT, 'validate' => 'isInt'),
            'download_deadline' =>            array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
            'unit_price_tax_incl' =>        array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'unit_price_tax_excl' =>        array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_price_tax_incl' =>        array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_price_tax_excl' =>        array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_shipping_price_tax_excl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_shipping_price_tax_incl' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'purchase_supplier_price' =>    array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'original_product_price' =>    array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'original_wholesale_price' =>    array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'idp' =>    array('type' => self::TYPE_INT, 'validate' => 'isInt'),
            'emei' =>    array('type' => self::TYPE_STRING, 'validate' => 'isString')
        ),
    );
	protected function create(Order $order, Cart $cart, $product, $id_order_state, $id_order_invoice, $use_taxes = true, $id_warehouse = 0)
    {
		$sql = 'SELECT idp FROM ' . _DB_PREFIX_ . 'product_attribute t ' .
        ' WHERE (id_product_attribute = ' . (int) $product['id_product_attribute']. ');';
        $this->idp = (int) Db::getInstance()->getValue($sql);
		
		if($this->idp == 0){
			$sql = 'SELECT reference FROM ' . _DB_PREFIX_ . 'product ' .
				' WHERE (id_product = ' . (int) $product['id_product']. ');';
			$this->idp = (int) Db::getInstance()->getValue($sql);
		}
		
        $this->product_old_price = $product['price'];
        if($this->id_shop == 1){
			$sql = 'SELECT floor_price FROM ' . _DB_PREFIX_ . 'product_attribute ' .
			' WHERE (id_product_attribute = ' . (int) $product['id_product_attribute']. ');';
			$this->floor_price = (float) Db::getInstance()->getValue($sql);
		}elseif($this->id_shop == 2){
			$sql = 'SELECT floor_price_us FROM ' . _DB_PREFIX_ . 'product_attribute ' .
			' WHERE (id_product_attribute = ' . (int) $product['id_product_attribute']. ');';
			$this->floor_price = (float) Db::getInstance()->getValue($sql);
		}
		
		return parent::create($order, $cart, $product, $id_order_state, $id_order_invoice, $use_taxes, $id_warehouse);
	}
}
