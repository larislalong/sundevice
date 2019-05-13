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

class SellerEmail extends ObjectModel
{
    public $reference;
    public $subject;
    public $description;
    public $content;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'seller_email',
        'primary' => 'id_seller_email',
        'multilang' => true,
        'fields' => array(
            'reference' => array('type' => self::TYPE_STRING, 'lang' => false, 'validate' => 'isCatalogName', 'size' => 45),
            'subject' => array('type' => self::TYPE_STRING, 'lang' => true, 'size' => 155),
            'description' => array('type' => self::TYPE_STRING, 'lang' => true),
            'content' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
        ),
    );
    
    public static function getIdByReference($reference)
    {
        return Db::getInstance()->getValue(
            'SELECT id_seller_email FROM '._DB_PREFIX_.'seller_email 
            WHERE reference = "'.pSQL($reference).'"'
        );
    }
    
    public static function getAllEmails($id_lang)
    {
        return Db::getInstance()->ExecuteS(
            'SELECT * FROM '._DB_PREFIX_.'seller_email se
            LEFT JOIN '._DB_PREFIX_.'seller_email_lang sel ON (se.id_seller_email = sel.id_seller_email AND sel.id_lang = '.(int)$id_lang.')
            ORDER BY se.id_seller_email ASC'
        );
    }
}
