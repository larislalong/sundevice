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
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2018 PrestaShop SA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

/**
 * Class DhlOrder
 */
class DhlOrder extends ObjectModel
{
    /** @var int $id_dhl_order */
    public $id_dhl_order;

    /** @var int $id_order */
    public $id_order;

    /** @var int $id_dhl_service */
    public $id_dhl_service;

    /** @var array $definition */
    public static $definition = array(
        'table'   => 'dhl_order',
        'primary' => 'id_dhl_order',
        'fields'  => array(
            'id_order'       => array('type' => self::TYPE_INT, 'validate' => 'isNullOrUnsignedId', 'required' => true),
            'id_dhl_service' => array('type' => self::TYPE_INT, 'validate' => 'isNullOrUnsignedId', 'required' => true),
        ),
    );

    /**
     * @param int $idOrder
     * @return bool|DhlOrder
     */
    public static function getByIdOrder($idOrder)
    {
        $dbQuery = new DbQuery();
        $dbQuery->select('id_dhl_order');
        $dbQuery->from(self::$definition['table']);
        $dbQuery->where('id_order = '.(int) $idOrder);
        $idDhlOrder = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($dbQuery);
        if (false === $idDhlOrder) {
            return false;
        }
        $dhlOrder = new self($idDhlOrder);

        return $dhlOrder;
    }

    /**
     * @return array|false|mysqli_result|null|PDOStatement|resource
     * @throws PrestaShopDatabaseException
     */
    public function getLabelIds()
    {
        $dbQuery = new DbQuery();
        $dbQuery->select('id_dhl_label');
        $dbQuery->from('dhl_label');
        $dbQuery->where('id_dhl_order = '.(int) $this->id);
        $dbQuery->where('return_label = 0');

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($dbQuery);
    }
}
