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
include_once _PS_MODULE_DIR_ . 'menupro/classes/MpCSSProperty.php';
include_once _PS_MODULE_DIR_ . 'menupro/classes/MpDefaultStyle.php';
include_once _PS_MODULE_DIR_ . 'menupro/classes/MpSecondaryMenu.php';
include_once _PS_MODULE_DIR_ . 'menupro/classes/MpMainMenu.php';

class MpStyleMenu extends ObjectModel
{
    const USABLE_STYLE_DEFAULT = 0;

    const USABLE_STYLE_THEME = 1;

    const USABLE_STYLE_CUSTOMIZED = 2;

    const USABLE_STYLE_MENU_PRO_LEVEL = 3;

    const USABLE_STYLE_NEAREST_RELATIVE = 4;

    const USABLE_STYLE_HIGHEST_SECONDARY_MENU_LEVEL = 5;

    const USABLE_STYLE_MAIN_MENU_LEVEL = 6;

    public $id_menupro_menu_style;

    public $id_menupro_main_menu;

    public $id_menupro_secondary_menu;

    public $name;

    public $usable_style;

    public static $definition = array(
        'table' => 'menupro_menu_style',
        'primary' => 'id_menupro_menu_style',
        'fields' => array(
            'usable_style' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId'
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

    public static function addDefault($menuType, $menu, $menuName)
    {
        $styleMenu = new MpStyleMenu();
        $styleMenu->generateName($menuName);
        $styleMenu->usable_style = self::getDefaultUsable($menuType, $menu);
        if ($menuType == MpCSSPropertyMenu::MENU_TYPE_MAIN) {
            $styleMenu->id_menupro_main_menu = $menu->id;
            $styleMenu->id_menupro_secondary_menu = null;
        } else {
            $styleMenu->id_menupro_main_menu = null;
            $styleMenu->id_menupro_secondary_menu = $menu->id;
        }
        $styleMenu->add(true, true);
        return $styleMenu;
    }

    /**
     * Cree un nom pour un style si le nom du style n'est pas renseigné
     */
    public function generateName($menuName)
    {
        if (empty($this->name)) {
            $this->name = 'Style ' . $menuName;
        }
    }

    public static function getAsString($style, $menuType, $menu)
    {
        $idStyle = $style[self::$definition['primary']];
        $styleResult = array();
        if (($style['usable_style'] != self::USABLE_STYLE_CUSTOMIZED) && ($style['usable_style'] !=
                 self::USABLE_STYLE_DEFAULT) && ($style['usable_style'] != self::USABLE_STYLE_THEME)) {
            $finalStyle = self::getStyleFromUsable($style['usable_style'], $menuType, $menu, $idStyle);
            if (empty($finalStyle['id_style'])) {
                $finalStyleUsable = self::getDefaultUsableInitial();
            } else {
                $finalStyleId = $finalStyle['id_style'];
                $finalStyleType = $finalStyle['style_type'];
                $finalMenuType = $finalStyle['menu_type'];
                $finalMenuLevel = $finalStyle['menu_level'];
                $finalMenuId = $finalStyle['id_menu'];
                if (isset($finalStyle['usable_style'])) {
                    $finalStyleUsable = $finalStyle['usable_style'];
                }
            }
        } else {
            $finalStyleId = $idStyle;
            $finalStyleUsable = $style['usable_style'];
            $finalStyleType = MpCSSPropertyMenu::STYLE_MENU;
            $finalMenuType = $menuType;
            $finalMenuLevel = ($menuType == MpCSSPropertyMenu::MENU_TYPE_MAIN) ? 0 : $menu->level;
            $finalMenuId = $menu->id;
        }
        if (isset($finalStyleUsable) && ($finalStyleUsable == self::USABLE_STYLE_THEME)) {
            $styleResult['for_container'] = ' ';
            $styleResult['no_event'] = ' ';
            $styleResult['reset'] = ' ';
            $styleResult['reset_active'] = ' ';
            $styleResult['hover'] = ' ';
            $styleResult['active'] = ' ';
        } else {
            $styleResult['for_container'] = ' style="';
            $styleResult['no_event'] = ' style="';
            $styleResult['reset'] = ' onmouseout="$(this).css({';
            $styleResult['reset_active'] = ' data-active-reset="$(this).css({';
            $styleResult['hover'] = ' onmouseover="$(this).css({';
            $styleResult['active'] = ' data-active-style="';
            if (isset($finalStyleUsable) && ($finalStyleUsable == self::USABLE_STYLE_DEFAULT)) {
                $properties = MpCSSProperty::getAll();
            } else {
                $properties = MpCSSPropertyMenu::getStylePropertiesWithInfos($finalStyleId, $finalStyleType);
            }
            
            foreach ($properties as $property) {
                if (!isset($property['usable_value'])) {
                    $property['usable_value'] = MpCSSPropertyMenu::USABLE_VALUE_DEFAULT;
                }
                if ($property['usable_value'] != MpCSSPropertyMenu::USABLE_VALUE_THEME) {
                    $propertyName = $property['name'];
                    if ((! isset($property['usable_value'])) || ($property['usable_value'] ==
                             MpCSSPropertyMenu::USABLE_VALUE_DEFAULT)) {
                        $propertyValue = $property['default_value'];
                    } else {
                        if ($property['usable_value'] == MpCSSPropertyMenu::USABLE_VALUE_CUSTOMIZED) {
                            $propertyValue = $property['value'];
                        } else {
                            $valueResult = MpCSSPropertyMenu::getValueFromUsable(
                                $property['usable_value'],
                                $property[MpCSSProperty::$definition['primary']],
                                $finalMenuLevel,
                                $finalMenuType,
                                $finalMenuId
                            );
                            if ($valueResult == MpCSSPropertyMenu::VALUE_DEFINED) {
                                $propertyValue = $valueResult['value'];
                            } else {
                                $propertyValue = $property['default_value'];
                            }
                        }
                    }
                    if ($property['for_container']) {
                        $styleResult['for_container'] .=
                        MpCSSProperty::getAsString($propertyName, $propertyValue, MpCSSProperty::EVENT_NONE);
                    } else {
                        if ($property['event'] == MpCSSProperty::EVENT_NONE) {
                            $styleResult['reset'] .=
                            MpCSSProperty::getAsString($propertyName, $propertyValue, MpCSSProperty::EVENT_HOVER);
                        } elseif ($property['event'] == MpCSSProperty::EVENT_ACTIVE) {
                            $styleResult['reset_active'] .=
                            MpCSSProperty::getAsString($propertyName, $propertyValue, MpCSSProperty::EVENT_HOVER);
                        }
                        $styleResult[$property['event']] .=
                        MpCSSProperty::getAsString($propertyName, $propertyValue, $property['event']);
                    }
                }
            }
            if ($styleResult['for_container'] == ' style="') {
                $styleResult['for_container'] = '';
            } else {
                $styleResult['for_container'] .= '" ';
            }
            
            if ($styleResult['no_event'] == ' style="') {
                $styleResult['no_event'] = '';
            } else {
                $styleResult['no_event'] .= '" ';
            }
            
            if ($styleResult['hover'] == ' onmouseover="$(this).css({') {
                $styleResult['hover'] = '';
            } else {
                $styleResult['hover'] .= '});" ';
            }
            
            if ($styleResult['active'] == ' data-active-style="') {
                $styleResult['active'] = '';
            } else {
                $styleResult['active'] .= '" ';
            }
            
            if ($styleResult['reset'] == ' onmouseout="$(this).css({') {
                $styleResult['reset'] = '';
            } else {
                $styleResult['reset'] .= '});" ';
            }
            
            if ($styleResult['reset_active'] == ' data-active-reset="$(this).css({') {
                $styleResult['reset_active'] = '';
            } else {
                $styleResult['reset_active'] .= '});" ';
            }
        }
        return $styleResult;
    }

    public static function getDefaultUsableInitial()
    {
        return self::USABLE_STYLE_THEME;
    }

    public static function getDefaultUsable($menuType, $menu)
    {
        $usableStyle = self::getDefaultUsableInitial();
        if ($menuType == MpCSSPropertyMenu::MENU_TYPE_MAIN) {
            if (MpDefaultStyle::isLevelExist(MpCSSPropertyMenu::MENU_TYPE_NONE, 0, 0, 0)) {
                $usableStyle = self::USABLE_STYLE_MENU_PRO_LEVEL;
            }
        } else {
            if ($menu->level == 1) {
                if (MpDefaultStyle::isLevelExist(
                    MpCSSPropertyMenu::MENU_TYPE_MAIN,
                    1,
                    $menu->id_menupro_main_menu,
                    0
                )) {
                    $usableStyle = self::USABLE_STYLE_MAIN_MENU_LEVEL;
                } elseif (MpDefaultStyle::isLevelExist(MpCSSPropertyMenu::MENU_TYPE_NONE, 1, 0, 0)) {
                    $usableStyle = self::USABLE_STYLE_MENU_PRO_LEVEL;
                } elseif (self::getUsableForMenu(MpCSSPropertyMenu::MENU_TYPE_MAIN, $menu->id_menupro_main_menu) ==
                         self::USABLE_STYLE_CUSTOMIZED) {
                    $usableStyle = self::USABLE_STYLE_NEAREST_RELATIVE;
                }
            } else {
                $idTopParent = MpSecondaryMenu::getTopParent($menu->id);
                if (MpDefaultStyle::isLevelExist(
                    MpCSSPropertyMenu::MENU_TYPE_SECONDARY,
                    $menu->level,
                    $idTopParent,
                    0
                )) {
                    $usableStyle = self::USABLE_STYLE_HIGHEST_SECONDARY_MENU_LEVEL;
                } elseif (MpDefaultStyle::isLevelExist(
                    MpCSSPropertyMenu::MENU_TYPE_MAIN,
                    $menu->level,
                    $menu->id_menupro_main_menu,
                    0
                )) {
                    $usableStyle = self::USABLE_STYLE_MAIN_MENU_LEVEL;
                } elseif (MpDefaultStyle::isLevelExist(MpCSSPropertyMenu::MENU_TYPE_NONE, $menu->level, 0, 0)) {
                    $usableStyle = self::USABLE_STYLE_MENU_PRO_LEVEL;
                } elseif (self::getUsableForMenu(MpCSSPropertyMenu::MENU_TYPE_SECONDARY, $menu->parent_menu) ==
                         self::USABLE_STYLE_CUSTOMIZED) {
                    $usableStyle = self::USABLE_STYLE_NEAREST_RELATIVE;
                }
            }
        }
        return $usableStyle;
    }

    /**
     * retourne le style d'un menu
     *
     * @param int $menuType
     *            Type de menu (principal, secondaire)
     * @param int $idMenu
     *            L'identifiant du menu
     * @param int $idStyle
     *            L'identifiant du style
     * @return array
     */
    public static function getForMenu($menuType, $idMenu)
    {
        $idClause = (($menuType == MpCSSPropertyMenu::MENU_TYPE_MAIN) ?
                'id_menupro_main_menu' : 'id_menupro_secondary_menu') . ' = ' . (int) $idMenu;
        $result = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . self::$definition['table'] . ' WHERE ' .
                 $idClause);
        return $result;
    }

    public static function getStyleFromUsable($usableStyle, $menuType, $menu, $idStyle)
    {
        $level = ($menuType == MpCSSPropertyMenu::MENU_TYPE_SECONDARY) ? $menu->level : 0;
        switch ($usableStyle) {
            case self::USABLE_STYLE_MENU_PRO_LEVEL:
                return self::getForLevel(MpCSSPropertyMenu::MENU_TYPE_NONE, $level, 0);
            case self::USABLE_STYLE_MAIN_MENU_LEVEL:
                return self::getForLevel(MpCSSPropertyMenu::MENU_TYPE_MAIN, $level, $menu->id_menupro_main_menu);
            case self::USABLE_STYLE_HIGHEST_SECONDARY_MENU_LEVEL:
                return self::getForLevel(
                    MpCSSPropertyMenu::MENU_TYPE_SECONDARY,
                    $level,
                    MpSecondaryMenu::getTopParent($menu->id)
                );
            case self::USABLE_STYLE_NEAREST_RELATIVE:
                if (empty($menu->parent_menu)) {
                    $nearestMenutype = MpCSSPropertyMenu::MENU_TYPE_MAIN;
                    $idNearestMenu = $menu->id_menupro_main_menu;
                    $levelNearest = 1;
                    $className = 'MpMainMenu';
                } else {
                    $nearestMenutype = MpCSSPropertyMenu::MENU_TYPE_SECONDARY;
                    $idNearestMenu = $menu->parent_menu;
                    $levelNearest = $menu->level;
                    $className = 'MpSecondaryMenu';
                }
                $nearestStyle = self::getForMenu($nearestMenutype, $idNearestMenu);
                if (($nearestStyle['usable_style'] == self::USABLE_STYLE_CUSTOMIZED) ||
                        ($nearestStyle['usable_style'] == self::USABLE_STYLE_DEFAULT) ||
                        ($nearestStyle['usable_style'] == self::USABLE_STYLE_THEME)) {
                    return array(
                        'style_type' => MpCSSPropertyMenu::STYLE_MENU,
                        'id_style' => $nearestStyle[self::$definition['primary']],
                        'name' => $nearestStyle['name'],
                        'menu_type' => $nearestMenutype,
                        'id_menu' => $idNearestMenu,
                        'menu_level' => $levelNearest,
                        'usable_style' => $nearestStyle['usable_style']
                    );
                } else {
                    $menu = new $className($idNearestMenu);
                    return self::getStyleFromUsable(
                        $nearestStyle['usable_style'],
                        $nearestMenutype,
                        $menu,
                        $nearestStyle[self::$definition['primary']]
                    );
                }
            default:
                return array(
                    'style_type' => MpCSSPropertyMenu::STYLE_MENU,
                    'id_style' => $idStyle,
                    'name' => '',
                    'usable_style' => $usableStyle
                );
        }
    }

    public static function getForLevel($menuType, $level, $idMenu)
    {
        $styleLevel = MpDefaultStyle::getStyleForLevelFullRow($menuType, $level, $idMenu, 0);
        $styleLevelId = ((isset($styleLevel[MpDefaultStyle::$definition['primary']])) ?
        (int) $styleLevel[MpDefaultStyle::$definition['primary']] : 0);
        return ($styleLevelId) ? array(
            'style_type' => MpCSSPropertyMenu::STYLE_DEFAULT,
            'id_style' => $styleLevelId,
            'name' => $styleLevel['name'],
            'menu_type' => $menuType,
            'id_menu' => $idMenu,
            'menu_level' => $level
        ) : self::getNotYetDefined();
    }

    public static function getNotYetDefined()
    {
        return array(
            'style_type' => MpCSSPropertyMenu::STYLE_MENU,
            'id_style' => 0,
            'name' => ''
        );
    }

    public static function getUsableForMenu($menuType, $idMenu)
    {
        return (int) self::getForMenu($menuType, $idMenu)['usable_style'];
    }

    public static function getIdForMenu($menuType, $idMenu)
    {
        return (int) self::getForMenu($menuType, $idMenu)[self::$definition['primary']];
    }
}
