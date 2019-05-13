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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class SellerTransport extends ObjectModel
{
    public $id_seller;
    public $id_carrier;
    
    const PS_CARRIERS_AND_CARRIER_MODULES_NEED_RANGE = 4;
    
    /**
     * Get all carriers in a given language
     *
     * @param integer $id_lang Language id
     * @param $modules_filters, possible values:
                    PS_CARRIERS_ONLY
                    CARRIERS_MODULE
                    CARRIERS_MODULE_NEED_RANGE
                    PS_CARRIERS_AND_CARRIER_MODULES_NEED_RANGE
                    ALL_CARRIERS
     * @param boolean $active Returns only active carriers when true
     * @return array Carriers
     */
    public static function getCarriers($id_lang, $active = false, $id_seller = 0, $delete = false, $id_zone = false, $ids_group = null)
    {
        // Filter by groups and no groups => return empty array
        if ($ids_group && (!is_array($ids_group) || !count($ids_group))) {
            return array();
        }

        $sql = 'SELECT c.*, cl.delay
        FROM `'._DB_PREFIX_.'carrier` c
        LEFT JOIN `'._DB_PREFIX_.'carrier_lang` cl ON (c.`id_carrier` = cl.`id_carrier` AND cl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('cl').')
        LEFT JOIN `'._DB_PREFIX_.'carrier_zone` cz ON (cz.`id_carrier` = c.`id_carrier`)'.
        ($id_zone ? 'LEFT JOIN `'._DB_PREFIX_.'zone` z ON (z.`id_zone` = '.(int)$id_zone.')' : '').'
        '.Shop::addSqlAssociation('carrier', 'c').'
        LEFT JOIN `'._DB_PREFIX_.'seller_carrier` sc ON (sc.`id_carrier` = c.`id_carrier`)
        WHERE c.`deleted` = '.($delete ? '1' : '0');
        if ($active) {
            $sql .= ' AND c.`active` = 1 ';
        }
        if ($id_zone) {
            $sql .= ' AND cz.`id_zone` = '.(int)$id_zone.' AND z.`active` = 1 ';
        }
        if ($ids_group) {
            $sql .= ' AND c.id_carrier IN (SELECT id_carrier FROM '._DB_PREFIX_.'carrier_group WHERE id_group IN ('.implode(',', array_map('intval', $ids_group)).')) ';
        }

        /*switch ($modules_filters) {
            case 1 :
                $sql .= ' AND c.is_module = 0 ';
                break;
            case 2 :
                $sql .= ' AND c.is_module = 1 ';
                break;
            case 3 :
                $sql .= ' AND c.is_module = 1 AND c.need_range = 1 ';
                break;
            case 4 :
                $sql .= ' AND (c.is_module = 0 OR c.need_range = 1) ';
                break;
        }*/
        
        $sql .= ' AND sc.`id_seller` = '.(int)$id_seller;
        $sql .= ' GROUP BY c.`id_carrier` ORDER BY c.`position` ASC';

        $carriers = Db::getInstance()->executeS($sql);
        foreach ($carriers as $key => $carrier) {
            if ($carrier['name'] == '0') {
                $carriers[$key]['name'] = Configuration::get('PS_SHOP_NAME');
            }
        }
        return $carriers;
    }
    
    /**
     * @param int $id_zone
     * @param Array $groups group of the customer
     * @param array &$error contain an error message if an error occurs
     * @return Array
     */
    public static function getCarriersForOrder($id_seller, $id_zone, $groups = null, $cart = null, &$error = array())
    {
        $context = Context::getContext();
        $id_lang = $context->language->id;
        if (is_null($cart)) {
            $cart = $context->cart;
        }
        if (isset($context->currency)) {
            $id_currency = $context->currency->id;
        }

        if (is_array($groups) && !empty($groups)) {
            $result = SellerTransport::getCarriers($id_lang, true, $id_seller, false, (int)$id_zone, $groups, self::PS_CARRIERS_AND_CARRIER_MODULES_NEED_RANGE);
        } else {
            $result = SellerTransport::getCarriers($id_lang, true, $id_seller, false, (int)$id_zone, array(Configuration::get('PS_UNIDENTIFIED_GROUP')), self::PS_CARRIERS_AND_CARRIER_MODULES_NEED_RANGE);
        }
        $results_array = array();

        foreach ($result as $k => $row) {
            $carrier = new Carrier((int)$row['id_carrier']);
            $shipping_method = $carrier->getShippingMethod();
            if ($shipping_method != Carrier::SHIPPING_METHOD_FREE) {
                // Get only carriers that are compliant with shipping method
                if (($shipping_method == Carrier::SHIPPING_METHOD_WEIGHT && $carrier->getMaxDeliveryPriceByWeight($id_zone) === false)) {
                    $error[$carrier->id] = Carrier::SHIPPING_WEIGHT_EXCEPTION;
                    unset($result[$k]);
                    continue;
                }
                if (($shipping_method == Carrier::SHIPPING_METHOD_PRICE && $carrier->getMaxDeliveryPriceByPrice($id_zone) === false)) {
                    $error[$carrier->id] = Carrier::SHIPPING_PRICE_EXCEPTION;
                    unset($result[$k]);
                    continue;
                }

                // If out-of-range behavior carrier is set on "Desactivate carrier"
                if ($row['range_behavior']) {
                    // Get id zone
                    if (!$id_zone) {
                        //$id_zone = (int)Country::getIdZone(Country::getDefaultCountryId());
                        $id_zone = (int)Country::getIdZone(Configuration::get('PS_COUNTRY_DEFAULT'));
                    }

                    // Get only carriers that have a range compatible with cart
                    if ($shipping_method == Carrier::SHIPPING_METHOD_WEIGHT
                        && (!Carrier::checkDeliveryPriceByWeight($row['id_carrier'], $cart->getTotalWeight(), $id_zone))) {
                        $error[$carrier->id] = Carrier::SHIPPING_WEIGHT_EXCEPTION;
                        unset($result[$k]);
                        continue;
                    }

                    if ($shipping_method == Carrier::SHIPPING_METHOD_PRICE
                        && (!Carrier::checkDeliveryPriceByPrice($row['id_carrier'], $cart->getOrderTotal(true, Cart::BOTH_WITHOUT_SHIPPING), $id_zone, $id_currency))) {
                        $error[$carrier->id] = Carrier::SHIPPING_PRICE_EXCEPTION;
                        unset($result[$k]);
                        continue;
                    }
                }
            }

            $row['name'] = $row['name'] != '0' ? $row['name'] : Carrier::getCarrierNameFromShopName();
            $row['price'] = (($shipping_method == Carrier::SHIPPING_METHOD_FREE) ? 0 : $cart->getPackageShippingCost((int)$row['id_carrier'], true, null, null, $id_zone));
            $row['price_tax_exc'] = (($shipping_method == Carrier::SHIPPING_METHOD_FREE) ? 0 : $cart->getPackageShippingCost((int)$row['id_carrier'], false, null, null, $id_zone));
            $row['img'] = file_exists(_PS_SHIP_IMG_DIR_.(int)$row['id_carrier']).'.jpg' ? _THEME_SHIP_DIR_.(int)$row['id_carrier'].'.jpg' : '';

            // If price is false, then the carrier is unavailable (carrier module)
            if ($row['price'] === false) {
                unset($result[$k]);
                continue;
            }
            $results_array[] = $row;
        }

        // if we have to sort carriers by price
        $prices = array();
        if (Configuration::get('PS_CARRIER_DEFAULT_SORT') == Carrier::SORT_BY_PRICE) {
            foreach ($results_array as $r) {
                $prices[] = $r['price'];
            }
            if (Configuration::get('PS_CARRIER_DEFAULT_ORDER') == Carrier::SORT_BY_ASC) {
                array_multisort($prices, SORT_ASC, SORT_NUMERIC, $results_array);
            } else {
                array_multisort($prices, SORT_DESC, SORT_NUMERIC, $results_array);
            }
        }

        return $results_array;
    }
    
    public static function getAllSellerCarriers($id_lang, $active = false, $delete = false, $id_zone = false, $ids_group = null)
    {
        // Filter by groups and no groups => return empty array
        if ($ids_group && (!is_array($ids_group) || !count($ids_group))) {
            return array();
        }

        $sql = 'SELECT c.*, cl.delay
        FROM `'._DB_PREFIX_.'carrier` c
        LEFT JOIN `'._DB_PREFIX_.'carrier_lang` cl ON (c.`id_carrier` = cl.`id_carrier` AND cl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('cl').')
        LEFT JOIN `'._DB_PREFIX_.'carrier_zone` cz ON (cz.`id_carrier` = c.`id_carrier`)'.
        ($id_zone ? 'LEFT JOIN `'._DB_PREFIX_.'zone` z ON (z.`id_zone` = '.(int)$id_zone.')' : '').'
        '.Shop::addSqlAssociation('carrier', 'c').'
        LEFT JOIN `'._DB_PREFIX_.'seller_carrier` sc ON (sc.`id_carrier` = c.`id_carrier`)
        WHERE c.`deleted` = '.($delete ? '1' : '0');
        if ($active) {
            $sql .= ' AND c.`active` = 1 ';
        }
        if ($id_zone) {
            $sql .= ' AND cz.`id_zone` = '.(int)$id_zone.' AND z.`active` = 1 ';
        }
        if ($ids_group) {
            $sql .= ' AND c.id_carrier IN (SELECT id_carrier FROM '._DB_PREFIX_.'carrier_group WHERE id_group IN ('.implode(',', array_map('intval', $ids_group)).')) ';
        }

        $sql .= ' AND sc.`id_seller` != 0';
        $sql .= ' GROUP BY c.`id_carrier` ORDER BY c.`position` ASC';

        $carriers = Db::getInstance()->executeS($sql);
        foreach ($carriers as $key => $carrier) {
            if ($carrier['name'] == '0') {
                $carriers[$key]['name'] = Configuration::get('PS_SHOP_NAME');
            }
        }
        return $carriers;
    }
    
    public static function isCarrierSeller($id_carrier)
    {
        return Db::getInstance()->getValue(
            'SELECT COUNT(*) FROM '._DB_PREFIX_.'seller_carrier 
            WHERE id_carrier = '.(int)$id_carrier
        );
    }
    
    public static function isSellerCarrier($id_seller, $id_carrier)
    {
        return Db::getInstance()->getValue(
            'SELECT COUNT(*) FROM '._DB_PREFIX_.'seller_carrier 
            WHERE id_seller = '.(int)$id_seller.' AND id_carrier = '.(int)$id_carrier
        );
    }
}
