<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cseditor
 *
 * @author hus
 */
class Cseditor extends ObjectModel{
    //put your code here
    
    public $id;

	/** @var integer editor ID */
	public $id_cseditor;

    /** @var string Name */
	public $hook;
    
	/** @var string Name */
	public $titleblock;
    
    /** @var string Secondtitle */
	public $secondtitle;
    
    /** @var string linkblock */
	public $linkblock;

    /** @var string nameimg */
	public $nameimg;
    
    /** @var string nameimgsec */
	public $nameimgsec;
    
    /** @var string ID Block */
	public $id_block;
    
    /** @var string Class Block */
	public $class_block;
    
	/** @var boolean Status for display */
	public $active = 1;
    
    /** @var boolean Status for display */
	public $displaytitle = 1;

	/** @var  integer editor position */
	public $position;

	/** @var string Description */
	public $editortext;

	/** @var string Object creation date */
	public $date_add;

	/** @var string Object last modification date */
	public $date_upd;

	/** @var integer */
	public $id_shop_default;
    
    /** @var string image */
	public $color;

     /** @var integer linktype */
    public $linktype;     

    /** @var integer id_element */
    public $id_element;

	protected static $_links = array();

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'cseditor',
		'primary' => 'id_cseditor',
		'multilang' => true,
		'fields' => array(
			'active' => 			array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'displaytitle' => 		array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'hook' =>               array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 128),
            'id_block' =>           array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 100),
            'class_block' =>        array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 255),
			'position' => 			array('type' => self::TYPE_INT),
			'date_add' => 			array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
			'date_upd' => 			array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'nameimg' =>            array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 255),
            'nameimgsec' =>         array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 255),
            'linktype' =>           array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 255),
            'id_element' =>         array('type' => self::TYPE_INT),
            'color' =>              array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 20),
			// Lang fields
			'titleblock' => 		array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCatalogName', 'required' => false, 'size' => 128),
			'editortext' => 		array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
            'secondtitle' => 		array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCatalogName', 'required' => false, 'size' => 128),
            'linkblock' =>          array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isUrl', 'required' => false, 'size' => 128),
            
		),
	);
    
	public static function getListsHook(){
        $sql = 'SELECT name AS id, title AS name FROM `'._DB_PREFIX_.'hook` WHERE name LIKE "%display%" ORDER BY id';
        $results = Db::getInstance()->executeS($sql);
        return $results;
    }
    
    /**
	  * Allows to display the category description without HTML tags and slashes
	  *
	  * @return string
	  */
	public static function getEditorClean($description)
	{
		return strip_tags(stripslashes($description));
	}
    
    public function getTable(){
        return $this->table;
    }
    public function getIdentifier(){
        return $this->id_identifier;
    }
    
    public function getClassName(){
        return $this->def['classname'];
    }
    
    /**
     * Retourne la liste des blogs
     * @return array
     */
    /*public static function getList($id_lang, $id_shop){
        $sql ='SELECT cs.*,csl.* FROM `'._DB_PREFIX_.'cseditor` AS cs '
                . 'LEFT JOIN `'._DB_PREFIX_.'cseditor_shop` cs_shop ON(cs_shop.id_cseditor = cs.id_cseditor AND cs_shop.id_shop='.(int)$id_shop.')'.
				' LEFT JOIN `'._DB_PREFIX_.'cseditor_lang` AS csl ON (csl.id_cseditor = cs.id_cseditor AND csl.id_lang='.(int)$id_lang.')'.
				' ORDER BY position ASC';
        
        $results = Db::getInstance()->executeS($sql);
        return $results;
    }*/
	
	public static function getList($id_lang, $id_shop){
        $sql ='SELECT cs.*,csl.* FROM `'._DB_PREFIX_.'cseditor` AS cs '.
                ' LEFT JOIN `'._DB_PREFIX_.'cseditor_lang` AS csl ON (csl.id_cseditor = cs.id_cseditor AND csl.id_lang='.(int)$id_lang.')'.
				' ORDER BY position ASC';
        
        $results = Db::getInstance()->executeS($sql);
        return $results;
    }
    
    public static function updatePosition($id, $position){
        $data = array('position'=>(int)$position);
        $result = Db::getInstance()->update('cseditor', $data, ' id_cseditor = '.(int)$id);
        return $result;
    }
    
    public static function getBlockList($name, $id_lang, $id_shop, $id_element = 0){
        $sql ='SELECT cs.*,csl.* FROM `'._DB_PREFIX_.'cseditor` AS cs '
                .'LEFT JOIN `'._DB_PREFIX_.'cseditor_lang` AS csl ON (csl.`id_cseditor` = cs.`id_cseditor` AND csl.`id_lang`='.(int)$id_lang.') '
                .'INNER JOIN `'._DB_PREFIX_.'cseditor_shop` AS css ON (css.`id_cseditor` =cs.`id_cseditor` AND  css.`id_shop`='.(int)$id_shop.') '
                .'WHERE cs.`hook`="'.(string)$name.'" AND cs.`active`=1 '.((int)$id_element > 0?'AND cs.`id_element`='.(int)$id_element.' ':'')
                .'ORDER BY cs.`position` ASC, cs.`id_cseditor` ASC';
        
        $results = Db::getInstance()->executeS($sql);
        return $results;
    }
	
	public static function getCsAssociatedShops($id)
    {
        $list = array();
        $sql = 'SELECT id_shop FROM `'._DB_PREFIX_.self::$definition['table'].'_shop` WHERE `'.self::$definition['primary'].'` = '.(int)$id;
        foreach (Db::getInstance()->executeS($sql) as $row) {
            $list[] = $row['id_shop'];
        }

        return $list;
    }
	
	public static function duplicateShopData($oldShop, $newShop)
    {
		$sql = 'INSERT IGNORE INTO `'._DB_PREFIX_.self::$definition['table'].'_shop`('.self::$definition['primary'].', id_shop)'.
		' SELECT  '.self::$definition['primary'].', '.(int)$newShop.' FROM `'._DB_PREFIX_.self::$definition['table'].'_shop` WHERE id_shop = '.(int)$oldShop;
        $results = Db::getInstance()->execute($sql);
        return $results;
    }
}
