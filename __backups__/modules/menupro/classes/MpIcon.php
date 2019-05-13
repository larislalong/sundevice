<?php
/**
 * 2015-2017 Crystals Services
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
 * needs please refer to http://www.crystals-services.com/ for more information.
 *
 * @author Crystals Services Sarl <contact@crystals-services.com>
 * @copyright 2015-2017 Crystals Services Sarl
 * @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 *          International Registered Trademark & Property of Crystals Services Sarl
 */

class MpIcon extends ObjectModel
{
    const POSITION_LEFT = 1;

    const POSITION_RIGHT = 2;

    public $id_menupro_icon;

    public $id_menupro_secondary_menu;

    public $css_class;

    public $position;

    public static $definition = array(
        'table' => 'menupro_icon',
        'primary' => 'id_menupro_icon',
        'fields' => array(
            'position' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId'
            ),
            'id_menupro_secondary_menu' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId',
                'required' => true
            ),
            'css_class' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isCatalogName',
                'required' => true
            )
        )
    );

    /**
     * Retourne l'identifiant de l'icone d'un menu secondaire
     *
     * @param int $idMenu
     *            L'identifiant du menu
     * @return int
     */
    public static function getIdForSecondaryMenu($idMenu)
    {
        $result = self::getForSecondaryMenu($idMenu);
        if (empty($result[self::$definition['primary']])) {
            return 0;
        } else {
            return (int) $result[self::$definition['primary']];
        }
    }

    /**
     * Retourne l'icone d'un menu secondaire
     *
     * @param int $idMenu
     *            L'identifiant du menu
     * @return array
     */
    public static function getForSecondaryMenu($idMenu)
    {
        $sql = 'SELECT ' . self::$definition['primary'] . ', css_class FROM ' . _DB_PREFIX_ .
        self::$definition['table'] . ' WHERE id_menupro_secondary_menu=' . (int) $idMenu;
        $result = Db::getInstance()->getRow($sql);
        return $result;
    }

    /**
     * Retourne la classe de l'icone d'un menu secondaire
     *
     * @param int $idMenu
     *            L'identifiant du menu
     * @return int
     */
    public static function getCssClassForSecondaryMenu($idMenu)
    {
        $result = self::getForSecondaryMenu($idMenu);
        if (empty($result[self::$definition['primary']])) {
            return '';
        } else {
            return $result['css_class'];
        }
    }
}
