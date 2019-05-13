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
 * Class DhlPackage
 */
class DhlPackage extends ObjectModel
{
    /** @var int $id_dhl_package */
    public $id_dhl_package;

    /** @var string $name */
    public $name;

    /** @var float $weight_value */
    public $weight_value;

    /** @var float $length_value */
    public $length_value;

    /** @var float $width_value */
    public $width_value;

    /** @var float $depth_value */
    public $depth_value;

    /** @var array $definition */
    public static $definition = array(
        'table'   => 'dhl_package',
        'primary' => 'id_dhl_package',
        'fields'  => array(
            'name'         => array(
                'type'     => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'required' => true,
                'size'     => 35,
            ),
            'weight_value' => array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat', 'required' => true),
            'length_value' => array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat', 'required' => true),
            'width_value'  => array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat', 'required' => true),
            'depth_value'  => array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat', 'required' => true),
        ),
    );

    /**
     * @param string       $field
     * @param string       $class
     * @param bool|true    $htmlentities
     * @param Context|null $context
     * @return string
     */
    public static function displayFieldName($field, $class = __CLASS__, $htmlentities = true, Context $context = null)
    {
        return Translate::getModuleTranslation('dhlexpress', $field, 'dhlexpress');
    }

    /**
     * @return array|false|mysqli_result|null|PDOStatement|resource
     * @throws PrestaShopDatabaseException
     */
    public static function getPackageList()
    {
        $dbQuery = new DbQuery();
        $dbQuery->select('dp.*');
        $dbQuery->from(self::$definition['table'], 'dp');
        $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($dbQuery);

        return $results;
    }

    /**
     * @return bool|DhlPackage
     */
    public static function getFirstPackage()
    {
        $dbQuery = new DbQuery();
        $dbQuery->select('dp.id_dhl_package');
        $dbQuery->from(self::$definition['table'], 'dp');
        $dbQuery->orderBy('dp.id_dhl_package ASC');
        $id = (int) Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($dbQuery);
        if (false === $id) {
            return false;
        } else {
            return new self($id);
        }
    }
}
