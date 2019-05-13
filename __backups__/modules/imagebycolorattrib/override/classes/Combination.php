<?php

class Combination extends CombinationCore
{
    public function deleteAssociations()
    {
        $result = Db::getInstance()->delete('product_attribute_combination', '`id_product_attribute` = '.(int)$this->id);
        $result &= Db::getInstance()->delete('cart_product', '`id_product_attribute` = '.(int)$this->id);
        $result &= Db::getInstance()->delete('product_attribute_image', ' ibca_id_product = 0 AND `id_product_attribute` = '.(int)$this->id);

        return $result;
    }

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
