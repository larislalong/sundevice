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
 * Class DhlError
 */
class DhlError extends ObjectModel
{
    /** @var int $id_dhl_error */
    public $id_dhl_error;

    /** @var string $code */
    public $code;

    /** @var string $message */
    public $message;

    /**
     * @var array
     */
    public static $definition = array(
        'table'     => 'dhl_error',
        'primary'   => 'id_dhl_error',
        'multilang' => true,
        'fields'    => array(
            'code'    => array(
                'type'     => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'required' => true,
                'size'     => 10,
            ),
            'message' => array(
                'type'     => self::TYPE_STRING,
                'lang'     => true,
                'validate' => 'isGenericName',
                'required' => true,
                'size'     => 355,
            ),
        ),
    );

    /**
     * @param string $code
     * @param int    $idLang
     * @return false|null|string
     */
    public static function getMessageByCode($code, $idLang)
    {
        $dbQuery = new DbQuery();
        $dbQuery->select('del.message');
        $dbQuery->from(self::$definition['table'], 'de');
        $dbQuery->leftJoin(
            self::$definition['table'].'_lang',
            'del',
            'del.'.self::$definition['primary'].' = de.'.self::$definition['primary']
        );
        $dbQuery->where('de.code = "'.pSQL($code).'" AND del.id_lang = '.(int) $idLang);

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($dbQuery);
    }
}
