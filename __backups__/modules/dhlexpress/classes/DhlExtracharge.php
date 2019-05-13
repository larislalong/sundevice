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
 * Class DhlExtracharge
 */
class DhlExtracharge extends ObjectModel
{
    /** @var int $id_dhl_extracharge */
    public $id_dhl_extracharge;

    /** @var string $extracharge_code */
    public $extracharge_code;

    /** @var int $active */
    public $active;

    /** @var int $doc */
    public $doc;

    /** @var int $dangerous */
    public $dangerous;

    /** @var string $label */
    public $label;

    /** @var string $name */
    public $name;

    /** @var string $description */
    public $description;

    /** @var array $definition */
    public static $definition = array(
        'table'     => 'dhl_extracharge',
        'primary'   => 'id_dhl_extracharge',
        'multilang' => true,
        'fields'    => array(
            'extracharge_code' => array(
                'type'     => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'required' => true,
                'size'     => 3,
            ),
            'active'           => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'doc'              => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'dangerous'        => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'label'            => array(
                'type'     => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'required' => false,
                'size'     => 120,
            ),
            'name'             => array(
                'type'     => self::TYPE_STRING,
                'lang'     => true,
                'validate' => 'isGenericName',
                'required' => true,
                'size'     => 50,
            ),
            'description'      => array(
                'type'     => self::TYPE_STRING,
                'lang'     => true,
                'validate' => 'isGenericName',
                'required' => true,
                'size'     => 500,
            ),
        ),
    );

    /**
     * @param int $idLang
     * @return array|false|mysqli_result|null|PDOStatement|resource
     * @throws PrestaShopDatabaseException
     */
    public static function getExtrachargesList($idLang)
    {
        $primary = self::$definition['primary'];
        $table = self::$definition['table'];
        $dbQuery = new DbQuery();
        $dbQuery->select('de.*, del.*');
        $dbQuery->from($table, 'de');
        $dbQuery->leftJoin($table.'_lang', 'del', 'del.'.$primary.' = de.'.$primary);
        $dbQuery->where('del.id_lang = '.(int) $idLang);

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($dbQuery);
    }

    /**
     * @param string $code
     * @return int
     */
    public static function getIdByCode($code)
    {
        $primary = self::$definition['primary'];
        $table = self::$definition['table'];
        $dbQuery = new DbQuery();
        $dbQuery->select($primary);
        $dbQuery->from($table, 'de');
        $dbQuery->where('de.extracharge_code = "'.pSQL($code).'"');

        return (int) Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($dbQuery);
    }
}
