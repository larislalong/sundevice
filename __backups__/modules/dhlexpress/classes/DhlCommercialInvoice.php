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
 * Class DhlCommercialInvoice
 */
class DhlCommercialInvoice extends ObjectModel
{
    /** @var int $idOrder */
    public $idOrder;

    /** @var string $moduleName */
    public $moduleName;

    /** @var array $productVars */
    public $productVars;

    /** @var array $invoiceVars */
    public $invoiceVars;

    /** @var int $id_dhl_commercial_invoice */
    public $id_dhl_commercial_invoice;

    /** @var int $id_dhl_order */
    public $id_dhl_order;

    /** @var int $id_dhl_label */
    public $id_dhl_label;

    /** @var int $pdf_string */
    public $pdf_string;

    /** @var string $date_add */
    public $date_add;

    /** @var array $definition */
    public static $definition = array(
        'table'   => 'dhl_commercial_invoice',
        'primary' => 'id_dhl_commercial_invoice',
        'fields'  => array(
            'id_dhl_order' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_dhl_label' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'size' => 80),
            'pdf_string'   => array('type' => self::TYPE_STRING, 'validate' => 'isAnything'),
            'date_add'     => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
        ),
    );

    /**
     * @param int $idDhlLabel
     * @return bool|DhlCommercialInvoice
     */
    public static function getByIdDhlLabel($idDhlLabel)
    {
        $dbQuery = new DbQuery();
        $dbQuery->select('dci.'.self::$definition['primary']);
        $dbQuery->from(self::$definition['table'], 'dci');
        $dbQuery->where('dci.id_dhl_label = '.(int) $idDhlLabel);
        $id = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($dbQuery);
        if (false === $id) {
            return false;
        } else {
            return new self((int) $id);
        }
    }
}
