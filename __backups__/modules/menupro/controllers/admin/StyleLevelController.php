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
include_once _PS_MODULE_DIR_ . 'menupro/controllers/admin/CSSPropertyController.php';

class StyleLevelController
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

    public function init($menuType, $idMenu)
    {
        $this->context->smarty->assign(array(
            'displayHtmlTemplate' => $this->module->displayHtmlTemplate,
            'styleLevelList' => $this->renderList($menuType, $idMenu),
            'menuType' => $menuType,
            'idMenu' => $idMenu,
            'homeLink' => $this->module->getModuleHome()
        ));
        return $this->context->smarty->fetch($this->local_path . 'views/templates/admin/styles_level_block.tpl');
    }

    public function processSave()
    {
        $result = array();
        $values = $this->getFormPostedValues();
        $result['errors'] = $this->getValidationErrors($values);
        if (empty($result['errors'])) {
            $defaultStyle = new MpDefaultStyle();
            $defaultStyle->menu_level = (int) $values['MENUPRO_STYLES_LEVEL_MENU_LEVEL'];
            $defaultStyle->menu_type = (int) $values['MENUPRO_STYLES_LEVEL_MENU_TYPE'];
            $defaultStyle->name = $values['MENUPRO_STYLES_LEVEL_NAME'];
            $defaultStyle->id = (int) $values['MENUPRO_STYLES_LEVEL_ID'];
            $defaultStyle->id_menupro_main_menu = null;
            $defaultStyle->id_menupro_secondary_menu = null;
            if ($defaultStyle->menu_type == MpCSSPropertyMenu::MENU_TYPE_MAIN) {
                $defaultStyle->id_menupro_main_menu = (int) $values['MENUPRO_STYLES_LEVEL_MENU_ID'];
            } elseif ($defaultStyle->menu_type == MpCSSPropertyMenu::MENU_TYPE_SECONDARY) {
                $defaultStyle->id_menupro_secondary_menu = (int) $values['MENUPRO_STYLES_LEVEL_MENU_ID'];
            }
            $result = $this->saveDefaultStyle($defaultStyle, $values['properties'], $values['MENUPRO_STYLES_LEVEL_ID']);
            $this->module->clearAllTplCache();
        }
        return $result;
    }

    private function saveDefaultStyle($defaultStyle, $properties, $idStyle = 0)
    {
        $result = array();
        $defaultStyle->generateName();
        if ($defaultStyle->save(true, true)) {
            if ((int) $idStyle > 0) {
                $result['success']['message'] = $this->module->l('Style level updated successfully', __CLASS__);
            } else {
                $result['success']['message'] = $this->module->l('Style level added successfully', __CLASS__);
            }
            $result['success']['id_style'] = $defaultStyle->id;
            $result['success']['style_name'] = $defaultStyle->name;
            $result['success']['menu_level'] = $defaultStyle->menu_level;
            if (! MpCSSPropertyMenu::addProperties($properties, $defaultStyle->id, MpCSSPropertyMenu::STYLE_DEFAULT)) {
                $result['errors'][] = $this->module->l('An error occurred while saving style properties', __CLASS__);
            }
        } else {
            $result['errors'][] = $this->module->l('An error occurred while saving style level', __CLASS__);
        }
        return $result;
    }

    public function processDelete()
    {
        $result = array();
        $id = Tools::getValue('MENUPRO_STYLES_LEVEL_ID');
        if (empty($id) || (! is_numeric($id)) || ((int) $id < 0)) {
            $result['errors'][] = $this->module->l('Wrong id style', __CLASS__);
        } else {
            $defaultStyle = new MpDefaultStyle((int) $id);
            if ($defaultStyle->delete()) {
                $result['success']['message'] = $this->module->l('Style level deleted successfully', __CLASS__);
                $this->module->clearAllTplCache();
            } else {
                $result['errors'][] = $this->module->l('An error occurred while deleting style level', __CLASS__);
            }
        }
        return $result;
    }

    public function processDuplicate()
    {
        $result = array();
        $id = Tools::getValue('MENUPRO_STYLES_LEVEL_ID');
        if (empty($id) || (! is_numeric($id)) || ((int) $id < 0)) {
            $result['errors'][] = $this->module->l('Wrong id style', __CLASS__);
        } else {
            $defaultStyle = new MpDefaultStyle((int) $id);
            $duplicateDefaultStyle = new MpDefaultStyle();
            $duplicateDefaultStyle->menu_type = $defaultStyle->menu_type;
            $duplicateDefaultStyle->id_menupro_main_menu = $defaultStyle->id_menupro_main_menu;
            $duplicateDefaultStyle->id_menupro_secondary_menu = $defaultStyle->id_menupro_secondary_menu;
            $duplicateDefaultStyle->generateNewLevel($defaultStyle->menu_level);
            $result = $this->saveDefaultStyle(
                $duplicateDefaultStyle,
                MpCSSPropertyMenu::getStyleProperties($defaultStyle->id, MpCSSPropertyMenu::STYLE_DEFAULT),
                $duplicateDefaultStyle->id
            );
            if (empty($result['errors'])) {
                $result['success']['message'] = $this->module->l('Style level duplicated successfully', __CLASS__);
                $this->module->clearAllTplCache();
            }
        }
        return $result;
    }

    public function getValidationErrors($values)
    {
        $errors = array();
        if ((! is_numeric($values['MENUPRO_STYLES_LEVEL_MENU_LEVEL'])) ||
                ((int) $values['MENUPRO_STYLES_LEVEL_MENU_LEVEL'] < 0)) {
            $errors[] = $this->module->l('Please enter a correct level', __CLASS__);
        } else {
            $levelExist = MpDefaultStyle::isLevelExist(
                $values['MENUPRO_STYLES_LEVEL_MENU_TYPE'],
                $values['MENUPRO_STYLES_LEVEL_MENU_LEVEL'],
                $values['MENUPRO_STYLES_LEVEL_MENU_ID'],
                $values['MENUPRO_STYLES_LEVEL_ID']
            );
            if ($levelExist) {
                $errors[] = $this->module->l('A style is already register with this level', __CLASS__);
            }
        }
        if (empty($values['MENUPRO_STYLES_LEVEL_MENU_TYPE']) ||
                (! is_numeric($values['MENUPRO_STYLES_LEVEL_MENU_TYPE'])) ||
                ((int) $values['MENUPRO_STYLES_LEVEL_MENU_TYPE'] <= 0)) {
            $errors[] = $this->module->l('Wrong menu type', __CLASS__);
        }
        if (! is_numeric($values['MENUPRO_STYLES_LEVEL_ID'])) {
            $errors[] = $this->module->l('Wrong id style', __CLASS__);
        }
        $errors = array_merge($errors, $this->propertyController->getValidationErrors($values));
        return $errors;
    }

    public function getFormPostedValues()
    {
        $values = array(
            'MENUPRO_STYLES_LEVEL_ID' => Tools::getValue('MENUPRO_STYLES_LEVEL_ID'),
            'MENUPRO_STYLES_LEVEL_NAME' => Tools::getValue('MENUPRO_STYLES_LEVEL_NAME'),
            'MENUPRO_STYLES_LEVEL_MENU_LEVEL' => Tools::getValue('MENUPRO_STYLES_LEVEL_MENU_LEVEL'),
            'MENUPRO_STYLES_LEVEL_MENU_TYPE' => Tools::getValue('MENUPRO_STYLES_LEVEL_MENU_TYPE'),
            'MENUPRO_STYLES_LEVEL_MENU_ID' => Tools::getValue('MENUPRO_STYLES_LEVEL_MENU_ID')
        );
        $values = array_merge($values, $this->propertyController->getFormPostedValues());
        return $values;
    }

    public function renderList($menuType, $idMenu)
    {
        $this->fields_list = array(
            'id_menupro_default_style' => array(
                'title' => $this->module->l('ID', __CLASS__),
                'type' => 'text',
                'search' => false,
                'orderby' => false
            ),
            'name' => array(
                'title' => $this->module->l('Name', __CLASS__),
                'type' => 'text',
                'search' => false,
                'orderby' => false
            ),
            'menu_level' => array(
                'title' => $this->module->l('Level', __CLASS__),
                'type' => 'text',
                'search' => false,
                'orderby' => false
            )
        );
        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = false;
        $helper->identifier = 'id_menupro_default_style';
        $helper->actions = array(
            'edit',
            'duplicate',
            'delete'
        );
        $helper->show_toolbar = true;
        $helper->toolbar_scroll = true;
        $helper->imageType = 'jpg';
        $helper->toolbar_btn['new'] = array(
            'href' => $this->module->getModuleHome() . '&action=add' . $this->module->name,
            'desc' => $this->module->l('Add new', __CLASS__)
        );
        $helper->title = $this->module->l('Styles', __CLASS__);
        $helper->table = 'menupro_default_style';
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = $this->module->getModuleHome();
        $helper->no_link = true;
        
        $lists = MpDefaultStyle::getAll($menuType, $idMenu);
        
        $helper->listTotal = count($lists);
        
        return $helper->generateList($lists, $this->fields_list);
    }
}
