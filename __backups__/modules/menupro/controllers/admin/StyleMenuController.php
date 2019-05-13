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
include_once _PS_MODULE_DIR_ . 'menupro/classes/MpSecondaryMenu.php';
include_once _PS_MODULE_DIR_ . 'menupro/classes/MpMainMenu.php';
include_once _PS_MODULE_DIR_ . 'menupro/classes/MpStyleMenu.php';
include_once _PS_MODULE_DIR_ . 'menupro/controllers/admin/CSSPropertyController.php';

class StyleMenuController
{
    public $module;

    public $context;

    public $local_path;

    public $propertyController;

    public function __construct($module, $context, $local_path)
    {
        $this->module = $module;
        $this->context = $context;
        $this->local_path = $local_path;
        $this->propertyController = new CSSPropertyController($module, $context, $local_path);
    }

    public function init($menuType, $menuLevel, $properties, $disableFields = false)
    {
        $usableStylesListConst = array(
            'DEFAULT' => MpStyleMenu::USABLE_STYLE_DEFAULT,
            'THEME' => MpStyleMenu::USABLE_STYLE_THEME,
            'CUSTOMIZED' => MpStyleMenu::USABLE_STYLE_CUSTOMIZED,
            'MENU_PRO_LEVEL' => MpStyleMenu::USABLE_STYLE_MENU_PRO_LEVEL,
            'NEAREST_RELATIVE' => MpStyleMenu::USABLE_STYLE_NEAREST_RELATIVE,
            'HIGHEST_SECONDARY_MENU_LEVEL' => MpStyleMenu::USABLE_STYLE_HIGHEST_SECONDARY_MENU_LEVEL,
            'MAIN_MENU_LEVEL' => MpStyleMenu::USABLE_STYLE_MAIN_MENU_LEVEL
        );
        $this->context->smarty->assign(array(
            'menuType' => $menuType,
            'usableStylesListConst' => $usableStylesListConst,
            'homeLink' => $this->module->getModuleHome(),
            'MENUPRO_STYLE_MENU_LEVEL' => $menuLevel
        ));
        if (! empty($properties)) {
            $this->context->smarty->assign('displayHtmlTemplate', $this->module->displayHtmlTemplate);
            $this->context->smarty->assign(
                'propertiesFormContent',
                $this->propertyController->getForm(
                    $menuType,
                    MpCSSPropertyMenu::STYLE_MENU,
                    $menuLevel,
                    0,
                    $properties,
                    $disableFields
                )
            );
        }
        return $this->context->smarty->fetch($this->local_path . 'views/templates/admin/style_block.tpl');
    }

    public function processSave($menuType, $menu, $menuName, $values = array())
    {
        $result = array();
        if (empty($values)) {
            $values = $this->getFormPostedValues();
        }
        $result['errors'] = $this->getValidationErrors($values);
        if (empty($result['errors'])) {
            $style = new MpStyleMenu();
            $style->usable_style = (int) $values['MENUPRO_USABLE_STYLE'];
            $style->name = $values['MENUPRO_STYLE_NAME'];
            $style->id = (int) $values['MENUPRO_STYLE_ID'];
            if ($menuType == MpCSSPropertyMenu::MENU_TYPE_MAIN) {
                $style->id_menupro_main_menu = $menu->id;
                $style->id_menupro_secondary_menu = null;
            } else {
                $style->id_menupro_main_menu = null;
                $style->id_menupro_secondary_menu = $menu->id;
            }
            $style->generateName($menuName);
            if ($style->save(true, true)) {
                $result['success']['message'] = $this->module->l('Style menu updated successfully', __CLASS__);
                $result['success']['style_name'] = $style->name;
                $result['success']['usable_style'] = $style->usable_style;
                if ($style->usable_style == MpStyleMenu::USABLE_STYLE_CUSTOMIZED) {
                    $addPropertiesResult = MpCSSPropertyMenu::addProperties(
                        $values['properties'],
                        $style->id,
                        MpCSSPropertyMenu::STYLE_MENU
                    );
                    if (! $addPropertiesResult) {
                        $result['errors'][] = $this->module->l(
                            'An error occurred while saving style properties',
                            __CLASS__
                        );
                    }
                }
            } else {
                $result['errors'][] = $this->module->l('An error occurred while saving menu style', __CLASS__);
            }
        }
        return $result;
    }

    public function getValidationErrors($values)
    {
        $errors = array();
        if (empty($values['MENUPRO_STYLE_ID']) ||
                (! is_numeric($values['MENUPRO_STYLE_ID'])) ||
                ((int) $values['MENUPRO_STYLE_ID'] <= 0)) {
            $errors[] = $this->module->l('Wrong style id', __CLASS__);
        }
        if (! is_numeric($values['MENUPRO_USABLE_STYLE'])) {
            $errors[] = $this->module->l('Wrong usable style', __CLASS__);
        }
        if ((int) $values['MENUPRO_USABLE_STYLE'] == MpStyleMenu::USABLE_STYLE_CUSTOMIZED) {
            $errors = array_merge($errors, $this->propertyController->getValidationErrors($values));
        }
        return $errors;
    }

    public function getFormPostedValues()
    {
        $values = array(
            'MENUPRO_STYLE_ID' => Tools::getValue('MENUPRO_STYLE_ID'),
            'MENUPRO_USABLE_STYLE' => Tools::getValue('MENUPRO_USABLE_STYLE'),
            'MENUPRO_STYLE_NAME' => Tools::getValue('MENUPRO_STYLE_NAME')
        );
        if ((int) $values['MENUPRO_USABLE_STYLE'] == MpStyleMenu::USABLE_STYLE_CUSTOMIZED) {
            $values = array_merge($values, $this->propertyController->getFormPostedValues());
        }
        return $values;
    }

    public function getSavedValues($menuType, $idMenu)
    {
        $style = MpStyleMenu::getForMenu($menuType, $idMenu);
        $values = array(
            'MENUPRO_STYLE_ID' => $style[MpStyleMenu::$definition['primary']],
            'MENUPRO_USABLE_STYLE' => $style['usable_style'],
            'MENUPRO_STYLE_NAME' => $style['name']
        );
        return $values;
    }

    public function processGetStyle()
    {
        $result = array();
        $idStyle = Tools::getValue('MENUPRO_GET_STYLE_ID_STYLE');
        $usableStyle = (int) Tools::getValue('MENUPRO_GET_STYLE_USABLE_STYLE');
        $menuType = Tools::getValue('MENUPRO_GET_STYLE_MENU_TYPE');
        $idMenu = (int) Tools::getValue('MENUPRO_GET_STYLE_ID_MENU');
        if (empty($idMenu) || (! is_numeric($idMenu)) || ((int) $idMenu <= 0)) {
            $result['errors'][] = $this->module->l('Wrong menu id', __CLASS__);
        }
        if (empty($menuType) || (! is_numeric($menuType)) || ((int) $menuType <= 0)) {
            $result['errors'][] = $this->module->l('Wrong menu id', __CLASS__);
        }
        if (empty($idStyle) || (! is_numeric($idStyle)) || ((int) $idStyle <= 0)) {
            $result['errors'][] = $this->module->l('Wrong menu id', __CLASS__);
        }
        if (empty($result['errors'])) {
            if ($menuType == MpCSSPropertyMenu::MENU_TYPE_MAIN) {
                $menu = new MpMainMenu();
                $menu->id_menupro_main_menu = $idMenu;
                $menu->id = $idMenu;
            } else {
                $menu = MpSecondaryMenu::getByIdAsObject($idMenu);
            }
            $resultStyle = MpStyleMenu::getStyleFromUsable($usableStyle, $menuType, $menu, $idStyle);
            if (empty($resultStyle['id_style'])) {
                $result['success']['message'] = $this->module->l('Style is not yet defined', __CLASS__);
                $result['success']['defined'] = false;
            } else {
                $result['success']['style_type'] = $resultStyle['style_type'];
                $result['success']['id_style'] = $resultStyle['id_style'];
                $result['success']['name'] = $resultStyle['name'];
                $result['success']['menu_level'] = $resultStyle['menu_level'];
                $result['success']['id_menu'] = $resultStyle['id_menu'];
                $result['success']['menu_level'] = $resultStyle['menu_level'];
                $result['success']['defined'] = true;
            }
        }
        return $result;
    }
}
