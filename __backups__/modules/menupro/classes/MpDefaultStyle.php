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

include_once _PS_MODULE_DIR_ . 'menupro/classes/MpCSSPropertyMenu.php';

class MpDefaultStyle extends ObjectModel
{
    public $id_menupro_default_style;

    public $id_menupro_main_menu;

    public $id_menupro_secondary_menu;

    public $name;

    public $menu_level;

    public $menu_type;

    public static $definition = array(
        'table' => 'menupro_default_style',
        'primary' => 'id_menupro_default_style',
        'fields' => array(
            'menu_level' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId',
                'required' => true
            ),
            'menu_type' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId',
                'required' => true
            ),
            'id_menupro_main_menu' => array(
                'type' => (_PS_VERSION_>='1.6')?self::TYPE_INT:self::TYPE_NOTHING,
                'validate' => 'isUnsignedId',
                'allow_null' => true
            ),
            'id_menupro_secondary_menu' => array(
                'type' => (_PS_VERSION_>='1.6')?self::TYPE_INT:self::TYPE_NOTHING,
                'validate' => 'isUnsignedId',
                'allow_null' => true
            ),
            'name' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isCatalogName',
                'required' => true
            )
        )
    );

    /**
     * Retourne les styles d'un menu
     *
     * @param int $menuType
     *            Type de menu (aucun, principal, secondaire)
     * @param int $idMenu
     *            L'identifiant du menu
     * @return array
     */
    public static function getAll($menuType, $idMenu)
    {
        if ($menuType == MpCSSPropertyMenu::MENU_TYPE_NONE) {
            $idClause = '';
        } else {
            $idClause = ' AND ' .
            (($menuType == MpCSSPropertyMenu::MENU_TYPE_MAIN) ? 'id_menupro_main_menu' : 'id_menupro_secondary_menu') .
             ' = ' . (int) $idMenu;
        }
        $result = Db::getInstance()->executeS('SELECT * FROM ' . _DB_PREFIX_ . self::$definition['table'] .
                 ' ds WHERE menu_type=' . (int) $menuType . $idClause);
        return $result;
    }

    /**
     * Verifie si un style possède déjà un style
     *
     * @param int $menuType
     *            Type de menu (aucun, principal, secondaire)
     * @param int $level
     *            Le niveau du menu
     * @param int $idMenu
     *            L'identifiant du menu
     * @param int $idStyle
     *            L'identifiant du style
     * @return bool
     */
    public static function isLevelExist($menuType, $level, $idMenu, $idStyle)
    {
        return self::getStyleForLevel($menuType, $level, $idMenu, $idStyle) > 0;
    }

    /**
     * retourne un style en fonction du niveau
     *
     * @param int $menuType
     *            Type de menu (aucun, principal, secondaire)
     * @param int $level
     *            Le niveau du menu
     * @param int $idMenu
     *            L'identifiant du menu
     * @param int $idStyle
     *            L'identifiant du style
     * @return array
     */
    public static function getStyleForLevelFullRow($menuType, $level, $idMenu, $idStyle)
    {
        if ($menuType == MpCSSPropertyMenu::MENU_TYPE_NONE) {
            $idClause = '';
        } else {
            $idClause = ' AND ' .
            (($menuType == MpCSSPropertyMenu::MENU_TYPE_MAIN) ? 'id_menupro_main_menu' : 'id_menupro_secondary_menu') .
             ' = ' . (int) $idMenu;
        }
        $sql = 'SELECT ' . self::$definition['primary'] . ', name FROM ' . _DB_PREFIX_ .
        self::$definition['table'] . ' WHERE menu_level=' . (int) $level . ' AND menu_type=' .
        (int) $menuType . ((! empty($idStyle)) ? (' AND ' .
        self::$definition['primary'] . ' <>' . (int) $idStyle) : '') . $idClause;
        $result = Db::getInstance()->getRow($sql);
        return $result;
    }

    /**
     * retourne l'identifiant d'un style en fonction du niveau
     *
     * @param int $menuType
     *            Type de menu (aucun, principal, secondaire)
     * @param int $level
     *            Le niveau du menu
     * @param int $idMenu
     *            L'identifiant du menu
     * @param int $idStyle
     *            L'identifiant du style
     * @return int
     */
    public static function getStyleForLevel($menuType, $level, $idMenu, $idStyle)
    {
        $result = self::getStyleForLevelFullRow($menuType, $level, $idMenu, $idStyle);
        return (isset($result[self::$definition['primary']])) ? (int) $result[self::$definition['primary']] : 0;
    }

    /**
     * Cree un nom pour un style si le nom du style n'est pas renseigné
     */
    public function generateName()
    {
        if (empty($this->name)) {
            $this->name = 'Style level ' . $this->menu_level;
        }
    }

    /**
     * Cree un niveau qui n'existe pas encore pour un style
     */
    public function generateNewLevel($level = 0)
    {
        $this->menu_level = $level;
        do {
            $this->menu_level ++;
            $idMenu = (($this->menu_type == MpCSSPropertyMenu::MENU_TYPE_MAIN) ?
                    $this->id_menupro_main_menu : $this->id_menupro_secondary_menu);
            $levelExist = self::isLevelExist($this->menu_type, $this->menu_level, $idMenu, $this->id);
        } while ($levelExist);
    }
}
