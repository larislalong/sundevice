<?php

class Attribute extends AttributeCore
{
    public $attr_desc;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'attribute',
        'primary' => 'id_attribute',
        'multilang' => true,
        'fields' => array(
            'id_attribute_group' 	=> array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'color' 				=> array('type' => self::TYPE_STRING, 'validate' => 'isColor'),
            'position' 				=> array('type' => self::TYPE_INT, 'validate' => 'isInt'),

            /* Lang fields */
            'name' 					=> array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'required' => true, 'size' => 128),
            'attr_desc' 			=> array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 4000),
        )
    );
}
