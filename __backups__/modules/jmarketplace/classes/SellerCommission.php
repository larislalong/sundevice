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

class SellerCommission extends ObjectModel
{
    public $id_seller;
    public $commission;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'seller_commission',
        'primary' => 'id_seller_commission',
        'fields' => array(
            'id_seller' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => false),
            'commission' => array('type' => self::TYPE_FLOAT, 'validate' => 'isUnsignedFloat', 'required' => false),
        ),
    );
    
    protected $webserviceParameters = array(
        'objectMethods' => array(
            'add' => 'addWs',
            'update' => 'updateWs'
        ),
        'objectNodeNames' => 'seller_commissions',
        'fields' => array(
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
    
    public static function getCommissionBySeller($id_seller)
    {
        return Db::getInstance()->getValue(
            'SELECT commission FROM '._DB_PREFIX_.'seller_commission 
            WHERE id_seller = '.(int)$id_seller
        );
    }
    
    public static function getRowCommissionBySeller($id_seller)
    {
        return Db::getInstance()->getRow(
            'SELECT * FROM '._DB_PREFIX_.'seller_commission 
            WHERE id_seller = '.(int)$id_seller
        );
    }
}
