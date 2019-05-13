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
 * Class DhlTracking
 */
class DhlTracking extends ObjectModel
{
    /** @var int $id_dhl_tracking */
    public $id_dhl_tracking;

    /** @var string $tracking_code */
    public $tracking_code;

    /** @var string $description */
    public $description;

    /** @var array $definition */
    public static $definition = array(
        'table'     => 'dhl_tracking',
        'primary'   => 'id_dhl_tracking',
        'multilang' => true,
        'fields'    => array(
            'tracking_code' => array(
                'type'     => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'required' => true,
                'size'     => 5,
            ),
            'description'   => array(
                'type'     => self::TYPE_STRING,
                'lang'     => true,
                'validate' => 'isGenericName',
                'required' => true,
                'size'     => 255,
            ),
        ),
    );

    /**
     * @return array
     * @throws PrestaShopDatabaseException
     */
    public static function getList()
    {
        $dbQuery = new DbQuery();
        $dbQuery->select('dt.*');
        $dbQuery->from(self::$definition['table'], 'dt');
        $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($dbQuery);
        $return = array();
        foreach ($results as $result) {
            $return[$result['tracking_code']] = (int) $result['id_dhl_tracking'];
        }

        return $return;
    }
}
