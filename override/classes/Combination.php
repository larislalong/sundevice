<?php
class Combination extends CombinationCore
{
    /** @var bool Product statuts */
    public $active = true;
	
	public $deactivate_price_update = false;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'product_attribute',
        'primary' => 'id_product_attribute',
        'fields' => array(
            'id_product' =>        array('type' => self::TYPE_INT, 'shop' => 'both', 'validate' => 'isUnsignedId', 'required' => true),
            'location' =>            array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 64),
            'ean13' =>                array('type' => self::TYPE_STRING, 'validate' => 'isEan13', 'size' => 13),
            'upc' =>                array('type' => self::TYPE_STRING, 'validate' => 'isUpc', 'size' => 12),
            'quantity' =>            array('type' => self::TYPE_INT, 'validate' => 'isInt', 'size' => 10),
            'reference' =>            array('type' => self::TYPE_STRING, 'size' => 32),
            'supplier_reference' => array('type' => self::TYPE_STRING, 'size' => 32),
            'active' =>                    array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'deactivate_price_update' =>    array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),

            /* Shop fields */
            'wholesale_price' =>    array('type' => self::TYPE_FLOAT, 'shop' => true, 'validate' => 'isPrice', 'size' => 27),
            'price' =>                array('type' => self::TYPE_FLOAT, 'shop' => true, 'validate' => 'isNegativePrice', 'size' => 20),
            'ecotax' =>            array('type' => self::TYPE_FLOAT, 'shop' => true, 'validate' => 'isPrice', 'size' => 20),
            'weight' =>            array('type' => self::TYPE_FLOAT, 'shop' => true, 'validate' => 'isFloat'),
            'unit_price_impact' =>    array('type' => self::TYPE_FLOAT, 'shop' => true, 'validate' => 'isNegativePrice', 'size' => 20),
            'minimal_quantity' =>    array('type' => self::TYPE_INT, 'shop' => true, 'validate' => 'isUnsignedId', 'required' => true),
            'default_on' =>        array('type' => self::TYPE_BOOL, 'allow_null' => true, 'shop' => true, 'validate' => 'isBool'),
            'available_date' =>    array('type' => self::TYPE_DATE, 'shop' => true, 'validate' => 'isDateFormat'),
        ),
    );
	
    /*
    * module: imagebycolorattrib
    * date: 2018-09-13 11:00:34
    * version: 1.0.0
    */
    public function deleteAssociations()
    {
        $result = Db::getInstance()->delete('product_attribute_combination', '`id_product_attribute` = '.(int)$this->id);
        $result &= Db::getInstance()->delete('cart_product', '`id_product_attribute` = '.(int)$this->id);
        $result &= Db::getInstance()->delete('product_attribute_image', ' ibca_id_product = 0 AND `id_product_attribute` = '.(int)$this->id);
        return $result;
    }
    /*
    * module: imagebycolorattrib
    * date: 2018-09-13 11:00:34
    * version: 1.0.0
    */
    public function setImages($ids_image)
    {
        if (Db::getInstance()->execute('
			DELETE FROM `'._DB_PREFIX_.'product_attribute_image`
			WHERE  ibca_id_product = 0 AND `id_product_attribute` = '.(int)$this->id) === false) {
            return false;
        }
        if (is_array($ids_image) && count($ids_image)) {
            $sql_values = array();
            foreach ($ids_image as $value) {
                $sql_values[] = '('.(int)$this->id.', '.(int)$value.')';
            }
            if (is_array($sql_values) && count($sql_values)) {
                Db::getInstance()->execute('
					INSERT INTO `'._DB_PREFIX_.'product_attribute_image` (`id_product_attribute`, `id_image`)
					VALUES '.implode(',', $sql_values)
                );
            }
        }
        return true;
    }
}
