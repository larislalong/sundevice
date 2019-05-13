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

include_once _PS_MODULE_DIR_ . 'menupro/classes/MpDefaultStyle.php';
include_once _PS_MODULE_DIR_ . 'menupro/classes/MpStyleMenu.php';
include_once _PS_MODULE_DIR_ . 'menupro/classes/MpCSSProperty.php';
include_once _PS_MODULE_DIR_ . 'menupro/classes/MpSecondaryMenu.php';
include_once _PS_MODULE_DIR_ . 'menupro/classes/MpMainMenu.php';

class MpCSSPropertyMenu extends ObjectModel
{

    const STYLE_DEFAULT = 1;

    const STYLE_MENU = 2;

    const USABLE_VALUE_THEME = 0;

    const USABLE_VALUE_DEFAULT = 1;

    const USABLE_VALUE_CUSTOMIZED = 2;

    const USABLE_VALUE_MENU_PRO_LEVEL = 3;

    const USABLE_VALUE_NEAREST_RELATIVE = 4;

    const USABLE_VALUE_HIGHEST_SECONDARY_MENU_LEVEL = 5;

    const USABLE_VALUE_MAIN_MENU_LEVEL = 6;

    const MENU_TYPE_NONE = 1;

    const MENU_TYPE_MAIN = 2;

    const MENU_TYPE_SECONDARY = 3;

    const VALUE_DEFINED = 1;

    const VALUE_NOT_YET_DEFINED = 2;

    const VALUE_NOT_ACCESSIBLE = 3;

    const VALUE_WRONG_CONFIG = 4;

    public $id_menupro_css_property_menu;

    public $id_menupro_css_property;

    public $id_menupro_default_style;

    public $id_menupro_menu_style;

    public $usable_value;

    public $value;

    public static $definition = array(
        'table' => 'menupro_css_property_menu',
        'primary' => 'id_menupro_css_property_menu',
        'fields' => array(
            'id_menupro_css_property' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId',
                'required' => true
            ),
            'id_menupro_default_style' => array(
                'type' => (_PS_VERSION_>='1.6')?self::TYPE_INT:self::TYPE_NOTHING,
                'validate' => 'isUnsignedId',
                'allow_null' => true
            ),
            'id_menupro_menu_style' => array(
                'type' => (_PS_VERSION_>='1.6')?self::TYPE_INT:self::TYPE_NOTHING,
                'validate' => 'isUnsignedId',
                'allow_null' => true
            ),
            'usable_value' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId'
            ),
            'value' => array(
                'type' => self::TYPE_STRING
            )
        )
    );

    /**
     * Ajoute des propriétés à un style
     *
     * @param array $properties
     *            la liste des proprietés à ajouter
     * @param int $idStyle
     *            L'identifiant du style
     * @param int $styleType
     *            Type de style (style de menu ou style pour niveau de menu)
     */
    public static function addProperties($properties, $idStyle, $styleType)
    {
        $whereClause = ' ' .
        (($styleType == self::STYLE_DEFAULT) ? 'id_menupro_default_style' : 'id_menupro_menu_style') .
         ' = ' . (int) $idStyle;
        Db::getInstance()->delete(self::$definition['table'], $whereClause);
        foreach ($properties as $property) {
            $propertyMenu = new MpCSSPropertyMenu();
            $propertyMenu->id_menupro_css_property = (int) $property['id_menupro_css_property'];
            if ($styleType == self::STYLE_DEFAULT) {
                $propertyMenu->id_menupro_default_style = (int) $idStyle;
                $propertyMenu->id_menupro_menu_style = null;
            } else {
                $propertyMenu->id_menupro_menu_style = (int) $idStyle;
                $propertyMenu->id_menupro_default_style = null;
            }
            $propertyMenu->usable_value = (int) $property['usable_value'];
            $propertyMenu->value = $property['value'];
            $propertyMenu->add(true, true);
        }
        return true;
    }

    /**
     * Retourne la proprieté d'un style en se basant sur l'id de la proprieté
     * CSS
     *
     * @param int $idProperty
     *            L'identifiant de la proprieté CSS
     * @param int $idStyle
     *            L'identifiant du style
     * @param int $styleType
     *            Type de style (style de menu ou style pour niveau de menu)
     * @return array
     */
    public static function getPropertiesById($idProperty, $idStyle, $styleType)
    {
        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . self::$definition['table'] . ' mp WHERE ' .
        (($styleType == self::STYLE_DEFAULT) ? 'id_menupro_default_style' : 'id_menupro_menu_style') .
        ' = ' . (int) $idStyle . ' And ' . MpCSSProperty::$definition['primary'] . ' = ' . (int) $idProperty;
        return Db::getInstance()->getRow($sql);
    }

    /**
     * Retourne les proprietés d'un style
     *
     * @param int $idStyle
     *            L'identifiant du style
     * @param int $styleType
     *            Type de style (style de menu ou style pour niveau de menu)
     * @return array
     */
    public static function getStyleProperties($idStyle, $styleType)
    {
        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . self::$definition['table'] . ' mp WHERE ' .
        (($styleType == self::STYLE_DEFAULT) ? 'id_menupro_default_style' : 'id_menupro_menu_style') . ' = ' .
        (int) $idStyle . ' ORDER BY ' . MpCSSProperty::$definition['primary'] . ' ASC';
        return Db::getInstance()->executeS($sql);
    }
    
    /**
     * Retourne les proprietés d'un style en y rajoutant les info sur la
     * proprieté CSS;
     * Si le style n'a pas encore de proprietés, la liste des proprietés CSS
     * configurable sera retourné
     *
     * @param int $idStyle
     *            L'identifiant du style
     * @param int $styleType
     *            Type de style (style de menu ou style pour niveau de menu)
     * @param bool $withSelectabe
     *            Permet de spécifier si l'on veut aussi recuperer les valeurs
     *            selectionnables de chaque proprietés
     * @param array $propertiesLabels
     *            Contient les libellés pour chaque propriété
     * @param array $eventLabels
     *            Contient les libellés pour chaque evènement
     * @param array $selectablesValuesLabels
     *            Contient les libellés pour chaque valeur selectionnable
     * @param string $childrenContainerLabel
     *            Contient le libellé pour "for children container"
     * @return array
     */
    public static function getStylePropertiesWithInfos($idStyle, $styleType, $withSelectabe = false, $propertiesLabels = array(), $eventLabels = array(), $selectablesValuesLabels = array(), $childrenContainerLabel = null)
    {
        // Recuperation des proprietés du style
        $properties = self::getStyleProperties($idStyle, $styleType);
        $size = count($properties);
        $defaultPropertiesSize = MpCSSProperty::getPropertiesCount();
        
        // Variable permettant de savoir si le style à toutes les proprietés
        $listNoMacthDefault = false;
        
        // Variable permettant de savoir les identifiants des propriétés déjà
        // enregistré pour le style
        $idPropertiesInlist = array();
        
        $selectableValuesList = array();
        if ($defaultPropertiesSize != $size) {
            $listNoMacthDefault = true;
        }
        for ($i = 0; $i < $size; $i ++) {
            $property = new MpCSSProperty($properties[$i]['id_menupro_css_property']);
            $properties[$i]['default_value'] = $property->default_value;
            $properties[$i]['display_name'] = $property->display_name;
            $properties[$i]['name'] = $property->name;
            $properties[$i]['type'] = $property->type;
            $properties[$i]['for_container'] = $property->for_container;
            $properties[$i]['event'] = $property->event;
            $properties[$i]['id_property_base'] = $property->id_property_base;
            
            // Ajout des valeurs selectionnables à la proprieté et modification
            // des libellés
            if ($withSelectabe) {
                MpCSSProperty::addSelectableToPropertyAndFixLabels(
                    $selectableValuesList,
                    $properties[$i],
                    $propertiesLabels,
                    $eventLabels,
                    $selectablesValuesLabels,
                    $childrenContainerLabel
                );
            }
            $idPropertiesInlist[] = $properties[$i]['id_menupro_css_property'];
        }
        if ($listNoMacthDefault) {
            $defaultList = MpCSSProperty::getAll(
                $withSelectabe,
                $propertiesLabels,
                $eventLabels,
                $selectablesValuesLabels,
                $childrenContainerLabel
            );
            foreach ($defaultList as $property) {
                if (! in_array($property['id_menupro_css_property'], $idPropertiesInlist)) {
                    if ($withSelectabe) {
                        MpCSSProperty::addSelectableToPropertyAndFixLabels(
                            $selectableValuesList,
                            $property,
                            $propertiesLabels,
                            $eventLabels,
                            $selectablesValuesLabels,
                            $childrenContainerLabel
                        );
                    }
                    $properties[] = $property;
                }
            }
        }
        return $properties;
    }

    public static function returnValue($usableValue, $idProperty, $level, $menuType, $idMenu, $styleType, $idStyle)
    {
        if ($idStyle) {
            $propertyData = self::getPropertiesById($idProperty, $idStyle, $styleType);
            if ($propertyData['usable_value'] == self::USABLE_VALUE_CUSTOMIZED) {
                return array(
                    'type_value' => self::VALUE_DEFINED,
                    'value' => $propertyData['value']
                );
            } elseif ($propertyData['usable_value'] == self::USABLE_VALUE_DEFAULT) {
                return array(
                    'type_value' => self::VALUE_DEFINED,
                    'value' => MpCSSProperty::getDefaultValueById($idProperty)
                );
            } elseif ($propertyData['usable_value'] == self::USABLE_VALUE_THEME) {
                return array(
                    'type_value' => self::VALUE_DEFINED,
                    'value' => ''
                );
            } else {
                if (($styleType == self::STYLE_DEFAULT) && ($propertyData['usable_value'] == $usableValue)) {
                    return array(
                        'type_value' => self::VALUE_WRONG_CONFIG,
                        'value' => 0
                    );
                } else {
                    return self::getValueFromUsable(
                        $propertyData['usable_value'],
                        $idProperty,
                        $level,
                        $menuType,
                        $idMenu
                    );
                }
            }
        } else {
            return array(
                'type_value' => self::VALUE_NOT_YET_DEFINED,
                'value' => 0
            );
        }
    }

    /**
     * Retourne la valeur finale d'une proprieté à partir d'une valeur
     * utilisable
     *
     * @param int $usableValue
     *            Le code de la valeur utilisable
     * @param int $idProperty
     *            L'identifiant de la propriété
     * @param int $level
     *            Le niveau du menu
     * @param int $menuType
     *            Type de menu (aucun, principal, secondaire)
     * @param int $idMenu
     *            L'identifiant du menu
     * @return array
     */
    public static function getValueFromUsable($usableValue, $idProperty, $level, $menuType, $idMenu)
    {
        switch ($usableValue) {
            case self::USABLE_VALUE_MENU_PRO_LEVEL:
                $idStyleLevel = MpDefaultStyle::getStyleForLevel(self::MENU_TYPE_NONE, $level, 0, 0);
                return self::returnValue(
                    $usableValue,
                    $idProperty,
                    $level,
                    $menuType,
                    $idMenu,
                    self::STYLE_DEFAULT,
                    $idStyleLevel
                );
            case self::USABLE_VALUE_MAIN_MENU_LEVEL:
                $idMainMenu = (($menuType == MpCSSPropertyMenu::MENU_TYPE_SECONDARY) ?
                MpSecondaryMenu::getMainMenuID($idMenu) : $idMenu);
                $idStyleLevel = MpDefaultStyle::getStyleForLevel(self::MENU_TYPE_MAIN, $level, $idMainMenu, 0);
                return self::returnValue(
                    $usableValue,
                    $idProperty,
                    $level,
                    $menuType,
                    $idMenu,
                    self::STYLE_DEFAULT,
                    $idStyleLevel
                );
            case self::USABLE_VALUE_HIGHEST_SECONDARY_MENU_LEVEL:
                $idHighestParent = MpSecondaryMenu::getTopParent($idMenu);
                $idStyleLevel = MpDefaultStyle::getStyleForLevel(
                    self::MENU_TYPE_SECONDARY,
                    $level,
                    $idHighestParent,
                    0
                );
                return self::returnValue(
                    $usableValue,
                    $idProperty,
                    $level,
                    $menuType,
                    $idMenu,
                    self::STYLE_DEFAULT,
                    $idStyleLevel
                );
            case self::USABLE_VALUE_THEME:
                return array(
                    'type_value' => self::VALUE_DEFINED,
                    'value' => ''
                );
            case self::USABLE_VALUE_NEAREST_RELATIVE:
                if ((! empty($idMenu)) && ($menuType == self::MENU_TYPE_MAIN)) {
                    return array(
                        'type_value' => self::VALUE_WRONG_CONFIG,
                        'value' => 0
                    );
                } elseif (($menuType != self::MENU_TYPE_SECONDARY) || (empty($idMenu))) {
                    return array(
                        'type_value' => self::VALUE_NOT_ACCESSIBLE,
                        'value' => 0
                    );
                } else {
                    $menuData = MpSecondaryMenu::getById($idMenu);
                    if (empty($menuData['parent_menu'])) {
                        $idParent = (int) $menuData['id_menupro_main_menu'];
                        $menuTypeParent = self::MENU_TYPE_MAIN;
                        $levelParent = 1;
                        $parentMenu = new MpMainMenu($idParent);
                        $parentMenu->id_menupro_main_menu = $idParent;
                        $parentMenu->id = $idParent;
                    } else {
                        $idParent = (int) $menuData['parent_menu'];
                        $menuTypeParent = self::MENU_TYPE_SECONDARY;
                        $parentMenu = MpSecondaryMenu::getByIdAsObject($idParent);
                        $levelParent = $parentMenu->level;
                    }
                    $styleMenu = MpStyleMenu::getForMenu($menuTypeParent, $idParent);
                    $finalStyle = MpStyleMenu::getStyleFromUsable(
                        $styleMenu['usable_style'],
                        $menuTypeParent,
                        $parentMenu,
                        $styleMenu[MpStyleMenu::$definition['primary']]
                    );
                    if (isset($finalStyle['usable_style']) &&
                            ($finalStyle['usable_style'] == MpStyleMenu::USABLE_STYLE_THEME)) {
                        return array(
                            'type_value' => self::VALUE_DEFINED,
                            'value' => ''
                        );
                    } elseif (isset($finalStyle['usable_style']) &&
                            ($finalStyle['usable_style'] == MpStyleMenu::USABLE_STYLE_DEFAULT)) {
                        return array(
                            'type_value' => self::VALUE_DEFINED,
                            'value' => MpCSSProperty::getDefaultValueById($idProperty)
                        );
                    } else {
                        return self::returnValue(
                            $usableValue,
                            $idProperty,
                            $levelParent,
                            $menuTypeParent,
                            $idParent,
                            $finalStyle['style_type'],
                            $finalStyle['id_style']
                        );
                    }
                }
            default:
                return array(
                    'type_value' => self::VALUE_DEFINED,
                    'value' => MpCSSProperty::getDefaultValueById($idProperty)
                );
        }
    }
}
