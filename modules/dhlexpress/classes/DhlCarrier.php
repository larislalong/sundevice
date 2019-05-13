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
 * Class DhlCarrier
 */
class DhlCarrier extends Carrier
{
    /**
     * @param array     $zones
     * @param bool|true $delete
     * @return bool
     */
    public function setZones($zones, $delete = true)
    {
        if ($delete) {
            Db::getInstance()->delete('carrier_zone', 'id_carrier = '.(int) $this->id);
        }
        if (!is_array($zones) || !count($zones)) {
            return true;
        }
        $return = true;
        foreach ($zones as $zone) {
            $this->addZone((int) $zone['id_zone']);
        }

        return $return;
    }

    /**
     * @param array     $groups
     * @param bool|true $delete
     * @return bool
     * @throws PrestaShopDatabaseException
     */
    public function setGroups($groups, $delete = true)
    {
        if ($delete) {
            Db::getInstance()->delete('carrier_group', 'id_carrier = '.(int) $this->id);
        }
        if (!is_array($groups) || !count($groups)) {
            return true;
        }
        $return = true;
        foreach ($groups as $group) {
            Db::getInstance()->insert(
                'carrier_group',
                array('id_carrier' => (int) $this->id, 'id_group' => (int) $group['id_group'],)
            );
        }

        return $return;
    }

    /**
     * @return bool
     */
    public function setRanges()
    {
        $rangeWeight = new RangeWeight();
        $rangeWeight->id_carrier = $this->id;
        $rangeWeight->delimiter1 = 0;
        $rangeWeight->delimiter2 = 99999;

        return $rangeWeight->add();
    }

    /**
     * @param string $logoPath
     * @param int    $idLang
     */
    public function setLogo($logoPath, $idLang)
    {
        require_once(dirname(__FILE__).'/../classes/DhlTools.php');

        DhlTools::copyLogo($logoPath, _PS_SHIP_IMG_DIR_.(int) $this->id.'.jpg');
        DhlTools::copyLogo($logoPath, _PS_TMP_IMG_DIR_.'carrier_mini_'.(int) $this->id.'_'.$idLang.'.png');
    }
}
