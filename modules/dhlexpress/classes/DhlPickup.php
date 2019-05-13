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
 * Class DhlPickup
 */
class DhlPickup extends ObjectModel
{
    /** @var int $id_dhl_pickup */
    public $id_dhl_pickup;

    /** @var int $id_dhl_address */
    public $id_dhl_address;

    /** @var string $pickup_date */
    public $pickup_date;

    /** @var string $pickup_time */
    public $pickup_time;

    /** @var int $confirmation_number */
    public $confirmation_number;

    /** @var int $total_pieces */
    public $total_pieces;

    /** @var array $definition */
    public static $definition = array(
        'table'   => 'dhl_pickup',
        'primary' => 'id_dhl_pickup',
        'fields'  => array(
            'id_dhl_address'      => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'pickup_date'         => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'pickup_time'         => array(
                'type'     => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'required' => true,
                'size'     => 5,
            ),
            'confirmation_number' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'total_pieces'        => array('type' => self::TYPE_INT, 'validate' => 'isGenericName'),
        ),
    );

    /**
     * @param string $date
     * @param int    $idDhlAddress
     * @return array|false|mysqli_result|null|PDOStatement|resource
     * @throws PrestaShopDatabaseException
     */
    public static function getPickupByDateLocation($date, $idDhlAddress)
    {
        $dbQuery = new DbQuery();
        $dbQuery->select('*');
        $dbQuery->from(self::$definition['table']);
        $dbQuery->where('pickup_date = "'.pSQL($date).'"');
        $dbQuery->where('id_dhl_address = '.(int) $idDhlAddress);

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($dbQuery);
    }
}
