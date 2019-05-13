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
 * Class DhlService
 */
class DhlService extends ObjectModel
{
    /** @var int $id_dhl_service */
    public $id_dhl_service;

    /** @var int $id_carrier_reference */
    public $id_carrier_reference;

    /** @var string $global_product_code */
    public $global_product_code;

    /** @var string $global_product_name */
    public $global_product_name;

    /** @var string $product_content_code */
    public $product_content_code;

    /** @var float $declared_value */
    public $declared_value;

    /** @var int $doc */
    public $doc;
    /** @var string $service_type */

    public $service_type;
    /** @var string $destination_type */

    public $destination_type;
    /** @var int $editable */

    public $editable;
    /** @var int $active */
    public $active;

    /** @var string $name */
    public $name;

    /** @var array $definition */
    public static $definition = array(
        'table'     => 'dhl_service',
        'primary'   => 'id_dhl_service',
        'multilang' => true,
        'fields'    => array(
            'id_carrier_reference' => array(
                'type'     => self::TYPE_INT,
                'validate' => 'isNullOrUnsignedId',
                'required' => true,
                'default'  => null,
            ),
            'global_product_code'  => array(
                'type'     => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'size'     => 1,
                'required' => true,
            ),
            'global_product_name'  => array(
                'type'     => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'size'     => 35,
                'required' => true,
            ),
            'product_content_code' => array(
                'type'     => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'required' => true,
                'size'     => 3,
            ),
            'declared_value'       => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'doc'                  => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'service_type'         => array(
                'type'     => self::TYPE_STRING,
                'validate' => 'isAddress',
                'required' => true,
                'size'     => 5,
            ),
            'destination_type'     => array(
                'type'     => self::TYPE_STRING,
                'validate' => 'isAddress',
                'required' => true,
                'size'     => 15,
            ),
            'editable'             => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'active'               => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'name'                 => array(
                'type'     => self::TYPE_STRING,
                'lang'     => true,
                'validate' => 'isGenericName',
                'required' => false,
                'size'     => 80,
            ),
        ),
    );

    /**
     * @param int       $idLang
     * @param bool|true $activeOnly
     * @return array|false|mysqli_result|null|PDOStatement|resource
     * @throws PrestaShopDatabaseException
     */
    public static function getServices($idLang, $activeOnly = true)
    {
        $dbQuery = new DbQuery();
        $dbQuery->select('ds.*, dsl.name');
        $dbQuery->from('dhl_service', 'ds');
        $dbQuery->leftJoin('dhl_service_lang', 'dsl', 'dsl.id_dhl_service = ds.id_dhl_service');
        if (true === $activeOnly) {
            $dbQuery->where('ds.active=1');
        }
        $dbQuery->where('dsl.id_lang='.(int) $idLang);
        $dbQuery->orderBy('ds.destination_type, ds.declared_value DESC, dsl.name DESC');

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($dbQuery);
    }

    /**
     * @param int  $idLang
     * @param bool $activeOnly
     * @return array|bool
     * @throws PrestaShopDatabaseException
     */
    public static function getServicesByZone($idLang, $activeOnly = false)
    {
        $services = self::getServices($idLang, $activeOnly);
        if (!$services) {
            return false;
        }
        $list = array();
        foreach ($services as $service) {
            if (!$service['doc']) {
                $list[$service['destination_type']][] = array(
                    'name'           => $service['name'],
                    'content_code'   => $service['product_content_code'],
                    'id_dhl_service' => $service['id_dhl_service'],
                );
            }
        }

        return $list;
    }

    /**
     * @param int       $idLang
     * @param bool|true $editable
     * @return array|bool
     * @throws PrestaShopDatabaseException
     */
    public static function getServicesList($idLang, $editable = true)
    {
        $results = self::getServices($idLang, false);
        if (!$results) {
            return false;
        }
        $list = array();
        foreach ($results as $result) {
            if ($result['editable'] == $editable) {
                if ($result['doc'] == 0) {
                    $list[$result['destination_type']][] = array(
                        'service_id' => $result['id_dhl_service'],
                        'label'      => $result['name'],
                        'active'     => $result['active'],
                    );
                } else {
                    $list[$result['destination_type'].' DOCUMENT'][] = array(
                        'service_id' => $result['id_dhl_service'],
                        'label'      => $result['name'],
                        'active'     => $result['active'],
                    );
                }
            }
        }

        return $list;
    }

    /**
     * @param int       $idLang
     * @param bool|true $activeOnly
     * @return array|bool
     * @throws PrestaShopDatabaseException
     */
    public static function getServicesFilters($idLang, $activeOnly = false)
    {
        $results = self::getServices($idLang, $activeOnly);
        if (!$results) {
            return false;
        }
        $list = array();
        foreach ($results as $result) {
            $list[$result['name']] = $result['name'];
        }

        return $list;
    }

    /**
     * @param int    $idCarrier
     * @param string $destinationType
     * @param int    $doc
     * @return false|null|string
     */
    public static function getProductCodeByIdCarrierDestination($idCarrier, $destinationType, $doc)
    {
        $dbQuery = new DbQuery();
        $dbQuery->select('ds.global_product_code');
        $dbQuery->from(self::$definition['table'], 'ds');
        $dbQuery->where('ds.active = 1');
        $dbQuery->where('ds.id_carrier_reference = '.(int) $idCarrier);
        $dbQuery->where('ds.destination_type = "'.pSQL($destinationType).'"');
        if ('WORLDWIDE' == $destinationType) {
            $dbQuery->where('ds.doc = '.(int) $doc);
        }

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($dbQuery);
    }

    /**
     * @param int    $idCarrier
     * @param string $destinationType
     * @param int    $doc
     * @param bool   $install
     * @return false|null|string
     */
    public static function getServiceByIdCarrierDestination($idCarrier, $destinationType, $doc, $install = false)
    {
        $dbQuery = new DbQuery();
        $dbQuery->select(self::$definition['primary']);
        $dbQuery->from(self::$definition['table'], 'ds');
        if (!$install) {
            $dbQuery->where('ds.active = 1');
        }
        $dbQuery->where('ds.id_carrier_reference = '.(int) $idCarrier);
        $dbQuery->where('ds.destination_type = "'.pSQL($destinationType).'"');
        if ('WORLDWIDE' == $destinationType) {
            $dbQuery->where('ds.doc = '.(int) $doc);
        }

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($dbQuery);
    }

    /**
     * @param int $idCarrierReference
     * @return false|null|string
     */
    public static function isDhlCarrier($idCarrierReference)
    {
        $dbQuery = new DbQuery();
        $dbQuery->select(self::$definition['primary']);
        $dbQuery->from(self::$definition['table'], 'ds');
        $dbQuery->where('ds.id_carrier_reference = '.(int) $idCarrierReference);

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($dbQuery);
    }

    /**
     * @param string $serviceType
     * @return false|null|string
     */
    public static function getIdCarrierByServiceType($serviceType)
    {
        $dbQuery = new DbQuery();
        $dbQuery->select('ds.id_carrier_reference');
        $dbQuery->from(self::$definition['table'], 'ds');
        $dbQuery->where('ds.service_type = "'.pSQL($serviceType).'"');

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($dbQuery);
    }

    /**
     * @param string $productCode
     * @param int    $doc
     * @return false|null|string
     */
    public static function getIdByProductCode($productCode, $doc)
    {
        $dbQuery = new DbQuery();
        $dbQuery->select(self::$definition['primary']);
        $dbQuery->from(self::$definition['table'], 'ds');
        $dbQuery->where('ds.global_product_code = "'.pSQL($productCode).'"');
        $dbQuery->where('ds.doc = '.(int) $doc);

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($dbQuery);
    }
}
