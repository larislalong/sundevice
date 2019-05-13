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
 * Class DhlLabel
 */
class DhlLabel extends ObjectModel
{
    /** @var int $id_dhl_label */
    public $id_dhl_label;

    /** @var int $id_dhl_order */
    public $id_dhl_order;

    /** @var int $id_dhl_service */
    public $id_dhl_service;

    /** @var string $awb_number */
    public $awb_number;

    /** @var int $return_label */
    public $return_label;

    /** @var string $label_format */
    public $label_format;

    /** @var string $label_string */
    public $label_string;

    /** @var string $piece_contents */
    public $piece_contents;

    /** @var int $total_pieces */
    public $total_pieces;

    /** @var string $total_weight */
    public $total_weight;

    /** @var string $consignee_contact */
    public $consignee_contact;

    /** @var string $consignee_destination */
    public $consignee_destination;

    /** @var string $date_add */
    public $date_add;

    /** @var array $definition */
    public static $definition = array(
        'table'   => 'dhl_label',
        'primary' => 'id_dhl_label',
        'fields'  => array(
            'id_dhl_order'          => array('type'     => self::TYPE_INT,
                                             'validate' => 'isUnsignedId',
                                             'required' => true,
            ),
            'id_dhl_service'        => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'awb_number'            => array(
                'type'     => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'required' => true,
            ),
            'return_label'          => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'label_format'          => array(
                'type'     => self::TYPE_STRING,
                'validate' => 'isAnything',
                'size'     => 4,
                'default'  => 'pdf',
            ),
            'label_string'          => array('type' => self::TYPE_STRING, 'validate' => 'isAnything'),
            'piece_contents'        => array('type' => self::TYPE_STRING, 'validate' => 'isAnything', 'size' => 90),
            'total_pieces'          => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'total_weight'          => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
            'consignee_contact'     => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 35),
            'consignee_destination' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
            'date_add'              => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
        ),
    );

    /**
     * @param int        $idDhlOrder
     * @param bool|false $returnLabel
     * @return bool|DhlLabel
     */
    public static function getByIdDhlOrder($idDhlOrder, $returnLabel = false)
    {
        $dbQuery = new DbQuery();
        $dbQuery->select(self::$definition['primary']);
        $dbQuery->from(self::$definition['table'], 'dl');
        $dbQuery->where('dl.id_dhl_order = '.(int) $idDhlOrder);
        if ($returnLabel) {
            $dbQuery->where('dl.return_label  1');
        } else {
            $dbQuery->where('dl.return_label = 0');
        }

        $id = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($dbQuery);
        if (!$id) {
            return false;
        } else {
            return new self((int) $id);
        }
    }

    /**
     * Delete label, delete commercial invoice and return label if needed
     *
     * @param DhlCommercialInvoice|false $obj1
     * @param DhlLabel|false             $obj2
     * @return bool
     * @throws PrestaShopException
     */
    public function deleteLabel($obj1, $obj2)
    {
        if (Validate::isLoadedObject($obj1) && $obj1 instanceof DhlCommercialInvoice) {
            $obj1->delete();
        }
        if (Validate::isLoadedObject($obj2) && $obj2 instanceof DhlLabel) {
            $obj2->delete();
        }

        return $this->delete();
    }

    /**
     * @param string     $date
     * @param bool|false $returnLabel
     * @return array|false|mysqli_result|null|PDOStatement|resource
     * @throws PrestaShopDatabaseException
     */
    public static function getByDate($date, $returnLabel = false)
    {
        $dbQuery = new DbQuery();
        $dbQuery->select(self::$definition['primary']);
        $dbQuery->from(self::$definition['table'], 'dl');
        $dbQuery->where('dl.date_add < "'.pSQL($date).'"');
        if (!$returnLabel) {
            $dbQuery->where('dl.return_label = 0');
        } else {
            $dbQuery->where('dl.return_label != 0');
        }

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($dbQuery);
    }

    /**
     * @param string $awbNumber
     * @return false|null|string
     */
    public static function getIdOrderByAWBNumber($awbNumber)
    {
        $dbQuery = new DbQuery();
        $dbQuery->select('do.id_order');
        $dbQuery->from(self::$definition['table'], 'dl');
        $dbQuery->rightJoin('dhl_order', 'do', 'do.id_dhl_order = dl.id_dhl_order');
        $dbQuery->where('dl.awb_number = "'.pSQL($awbNumber).'"');

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($dbQuery);
    }

    /**
     * Return false or DhlLabel instance of the return label associated to this label
     *
     * @return bool|DhlLabel
     */
    public function getDhlReturnLabel()
    {
        $dbQuery = new DbQuery();
        $dbQuery->select(self::$definition['primary']);
        $dbQuery->from(self::$definition['table'], 'dl');
        $dbQuery->where('dl.return_label = '.(int) $this->id);
        $id = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($dbQuery);
        if (!$id) {
            return false;
        } else {
            return new self((int) $id);
        }
    }

    /**
     *
     * @return false|null|string
     */
    public function getLastTrackingStatusKnown()
    {
        $dbQuery = new DbQuery();
        $dbQuery->select('id_dhl_tracking');
        $dbQuery->from('dhl_shipment_tracking');
        $dbQuery->where('id_dhl_label = '.(int) $this->id);

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($dbQuery);
    }

    /**
     *
     * @return false|null|string
     */
    public function getLastTrackingUpdate()
    {
        $dbQuery = new DbQuery();
        $dbQuery->select('date_upd');
        $dbQuery->from('dhl_shipment_tracking');
        $dbQuery->where('id_dhl_label = '.(int) $this->id);

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($dbQuery);
    }
}
