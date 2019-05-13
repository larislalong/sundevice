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
 * Class DhlTools
 */
class DhlTools
{
    /**
     *
     */
    const IMPERIAL_DIMENSION = 'in';
    /**
     *
     */
    const IMPERIAL_WEIGHT = 'lb';
    /**
     *
     */
    const METRIC_DIMENSION = 'cm';
    /**
     *
     */
    const METRIC_WEIGHT = 'kg';

    /**
     *
     */
    const DHL_URL_TRACKING = 'http://www.dhl.fr/fr/dhl_express/suivi_expedition.html?AWB=@&brand=DHL';

    /** @var array $isoEUCountries */
    public static $isoEUCountries = array(
        'AT',
        'BE',
        'BG',
        'CY',
        'CZ',
        'DE',
        'DK',
        'EE',
        'ES',
        'GR',
        'FI',
        'FR',
        'HR',
        'HU',
        'IE',
        'IT',
        'LT',
        'LU',
        'LV',
        'MT',
        'NL',
        'PL',
        'PT',
        'RO',
        'SE',
        'SI',
        'SK',
        'GB',
    );

    /** @var array $lifetimeExtracharge */
    public static $lifetimeExtracharge = array(
        3  => 'PT',
        6  => 'PU',
        12 => 'PV',
        24 => 'PW',
    );

    /** @var array $isoCountryFix Handle DHL ISO exceptions */
    public static $isoCountryFix = array(
        'BL' => 'XY',
    );

    /**
     * @param bool|false $forceLiveMode
     * @return array
     */
    public static function getCredentials($forceLiveMode = false)
    {
        $mode = (int) Configuration::get('DHL_LIVE_MODE');
        if ($forceLiveMode) {
            $mode = 1;
        }
        if ($mode) {
            return array(
                'SiteID'   => Configuration::get('DHL_ACCOUNT_ID_PRODUCTION'),
                'Password' => Configuration::get('DHL_ACCOUNT_PASSWORD_PRODUCTION'),
            );
        } else {
            return array(
                'SiteID'   => Configuration::get('DHL_ACCOUNT_ID_TEST'),
                'Password' => Configuration::get('DHL_ACCOUNT_PASSWORD_TEST'),
            );
        }
    }

    /**
     * @return string
     */
    public static function getDimensionUnit()
    {
        $system = Configuration::get('DHL_SYSTEM_UNITS');
        if ('imperial' == $system) {
            return self::IMPERIAL_DIMENSION;
        } else {
            return self::METRIC_DIMENSION;
        }
    }

    /**
     * @return string
     */
    public static function getWeightUnit()
    {
        $system = Configuration::get('DHL_SYSTEM_UNITS');
        if ('imperial' == $system) {
            return self::IMPERIAL_WEIGHT;
        } else {
            return self::METRIC_WEIGHT;
        }
    }

    /**
     * @param string $senderIso
     * @param string $receiverIso
     * @return string
     */
    public static function getDestinationType($senderIso, $receiverIso)
    {
        if ($senderIso === $receiverIso) {
            return 'DOMESTIC';
        }
        if (in_array($receiverIso, self::$isoEUCountries)) {
            return 'EUROPE';
        }

        return 'WORLDWIDE';
    }

    /**
     * @param array $extracharges
     * @return array
     */
    public static function getExtraCharges($extracharges)
    {
        $list = array();
        foreach ($extracharges as $extracharge) {
            if ($extracharge['active']) {
                $list[] = $extracharge['extracharge_code'];
            }
        }

        return $list;
    }

    /**
     * @param string $iso
     * @return bool
     */
    public static function isEUCountry($iso)
    {
        if (in_array($iso, self::$isoEUCountries)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $isoFrom
     * @param string $isoTo
     * @return bool
     */
    public static function isDeclaredValueRequired($isoFrom, $isoTo)
    {
        if (self::isEUCountry($isoFrom) && self::isEUCountry($isoTo)) {
            return false;
        } elseif ($isoFrom == $isoTo) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param Order    $order
     * @param Customer $customer
     * @param Carrier  $carrier
     * @param string   $awbNumber
     * @return bool|int
     */
    public static function sendInTransitMail(Order $order, Customer $customer, Carrier $carrier, $awbNumber)
    {
        $templateVars = array(
            '{followup}'        => str_replace('@', $awbNumber, $carrier->url),
            '{firstname}'       => $customer->firstname,
            '{lastname}'        => $customer->lastname,
            '{id_order}'        => $order->id,
            '{shipping_number}' => $awbNumber,
            '{order_name}'      => $order->getUniqReference(),
        );

        return @Mail::Send(
            (int) $order->id_lang,
            'in_transit',
            Mail::l('Package in transit', (int) $order->id_lang),
            $templateVars,
            $customer->email,
            $customer->firstname.' '.$customer->lastname,
            null,
            null,
            null,
            null,
            _PS_MAIL_DIR_,
            false,
            (int) $order->id_shop
        );
    }

    /**
     * @param Order  $order
     * @param string $subject
     * @param string $awbNumber
     * @return bool|int
     */
    public static function sendHandlingShipmentMail(Order $order, $subject, $awbNumber)
    {
        $customer = new Customer((int) $order->id_customer);
        $templateVars = array(
            '{followup}'        => str_replace('@', $awbNumber, self::DHL_URL_TRACKING),
            '{firstname}'       => $customer->firstname,
            '{lastname}'        => $customer->lastname,
            '{id_order}'        => $order->id,
            '{shipping_number}' => $awbNumber,
            '{order_name}'      => $order->getUniqReference(),
        );

        return @Mail::Send(
            (int) $order->id_lang,
            'dhl_handling_shipment',
            $subject,
            $templateVars,
            $customer->email,
            $customer->firstname.' '.$customer->lastname,
            null,
            null,
            null,
            null,
            dirname(__FILE__).'/../mails/',
            false,
            (int) $order->id_shop
        );
    }

    /**
     * @return array
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public static function getOrdersToTrack()
    {
        $dateAdd = new DateTime('now');
        $dateAdd->sub(new DateInterval('P15D'));
        $deliveredQuery = new DbQuery();
        $deliveredQuery->select('oo.id_order');
        $deliveredQuery->from('orders', 'oo');
        $deliveredQuery->leftJoin('order_history', 'ooh', 'ooh.id_order = oo.id_order');
        $deliveredQuery->where('ooh.id_order_state = '.(int) _PS_OS_DELIVERED_);
        $dbQuery = new DbQuery();
        $dbQuery->select('o.id_order, dl.awb_number, dl.id_dhl_label');
        $dbQuery->from('orders', 'o');
        $dbQuery->leftJoin('dhl_order', 'dho', 'dho.id_order = o.id_order');
        $dbQuery->leftJoin('dhl_label', 'dl', 'dl.id_dhl_order = dho.id_dhl_order');
        $dbQuery->where('o.valid = 1');
        $dbQuery->where('dl.awb_number IS NOT NULL');
        $dbQuery->where('dl.return_label = 0');
        $dbQuery->where('dl.date_add >= "'.$dateAdd->format('Y-m-d H:i:s').'"');
        $dbQuery->where('o.id_order NOT IN ('.$deliveredQuery->build().')');
        $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($dbQuery);
        $ordersToTrack = array();
        $ordersToTrack[0] = array();
        if ($results) {
            $i = 1;
            $key = 0;
            foreach ($results as $result) {
                if ($i == 1 || $i % 9) {
                    $ordersToTrack[$key][$result['id_order']][$result['awb_number']] = $result['id_dhl_label'];
                    $i++;
                } else {
                    $ordersToTrack[$key][$result['id_order']][$result['awb_number']] = $result['id_dhl_label'];
                    $key++;
                    $i = 1;
                }
            }
        }

        return $ordersToTrack;
    }

    /**
     * @param string $source
     * @param string $destination
     */
    public static function copyLogo($source, $destination)
    {
        $iconExists = file_exists($destination) && md5_file($source) === md5_file($destination);
        if (!$iconExists) {
            Tools::copy($source, $destination);
        }
    }

    /**
     * @return mixed
     */
    public static function getLabelLifetimeExtracharge()
    {
        $lifetime = (int) Configuration::get('DHL_LABEL_LIFETIME');

        return isset(self::$lifetimeExtracharge[(int) $lifetime]) ? self::$lifetimeExtracharge[(int) $lifetime] :
            self::$lifetimeExtracharge[3];
    }

    /**
     * Get ISO country by ID, using DHL correspondance for some country (e.g. Saint Barthelemy)
     *
     * @param int $id
     * @return string
     */
    public static function getIsoCountryById($id)
    {
        $iso = Country::getIsoById((int) $id);
        if (isset(self::$isoCountryFix[$iso])) {
            return self::$isoCountryFix[$iso];
        } else {
            return $iso;
        }
    }
}
