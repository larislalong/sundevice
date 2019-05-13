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

include_once _PS_MODULE_DIR_ . 'menupro/classes/MpMainMenu.php';
include_once _PS_MODULE_DIR_ . 'menupro/classes/MpCSSPropertyMenu.php';
include_once _PS_MODULE_DIR_ . 'menupro/classes/MpCSSProperty.php';
include_once _PS_MODULE_DIR_ . 'menupro/controllers/admin/MainMenuInformationController.php';
include_once _PS_MODULE_DIR_ . 'menupro/controllers/admin/StyleMenuController.php';

class MainMenuController
{
    public $module;

    public $context;

    public $mainMenuInfoController;

    public $styleLevelController;

    public $styleController;

    public $local_path;

    public function __construct($module, $context, $local_path)
    {
        $this->module = $module;
        $this->context = $context;
        $this->local_path = $local_path;
        $this->mainMenuInfoController = new MainMenuInformationController($this->module, $this->context);
        $this->styleLevelController = new StyleLevelController($module, $context, $local_path);
        $this->styleController = new StyleMenuController($module, $context, $local_path);
    }

    public function renderList()
    {
        $this->fields_list = array(
            'id_menupro_main_menu' => array(
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
            'hook' => array(
                'title' => $this->module->l('Hook', __CLASS__),
                'type' => 'text',
                'search' => false,
                'orderby' => false
            ),
            'menu_type' => array(
                'title' => $this->module->l('Type', __CLASS__),
                'type' => 'text',
                'search' => false,
                'orderby' => false
            ),
            'active' => array(
                'title' => $this->module->l('Status', __CLASS__),
                'active' => 'status',
                'type' => 'bool',
                'class' => 'fixed-width-xs',
                'align' => 'center',
                'search' => false,
                'orderby' => false
            ),
            'shop_associated' => array(
                'title' => $this->module->l('Shop', __CLASS__),
                'type' => 'text',
                'search' => false,
                'orderby' => false
            )
        );
        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = false;
        $helper->identifier = MpMainMenu::$definition['primary'];
        $helper->actions = array(
            'view',
            'edit',
            'delete'
        );
        
        $helper->bulk_actions = array(
            'enable' => array(
                'text' => $this->module->l('Enable selected items', __CLASS__),
                'confirm' => $this->module->l('Enable selected items ?', __CLASS__)
            ),
            'disable' => array(
                'text' => $this->module->l('Disable selected items', __CLASS__),
                'confirm' => $this->module->l('Disable selected items ?', __CLASS__)
            ),
            'delete' => array(
                'text' => $this->module->l('Delete selected items', __CLASS__),
                'confirm' => $this->module->l('Delete selected items ?', __CLASS__)
            )
        );
        $helper->show_toolbar = true;
        $helper->toolbar_scroll = true;
        $helper->imageType = 'jpg';
        $helper->toolbar_btn['new'] = array(
            'href' => $this->module->getModuleHome() . '&action=add' . MpMainMenu::$definition['table'],
            'desc' => $this->module->l('Add new', __CLASS__)
        );
        $helper->title = $this->module->l('Main menus', __CLASS__);
        $helper->table = MpMainMenu::$definition['table'];
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = $this->module->getModuleHome();
        $idLang = $this->context->language->id;
        
        $list = MpMainMenu::getAll($idLang);
        $userShops = $this->context->employee->getAssociatedShops();
        $menuTypeLabels = $this->mainMenuInfoController->getMenuTypesLabels();
        foreach ($list as $key => $value) {
            $associatedShops = MpMainMenu::getMPAssociatedShops($value[MpMainMenu::$definition['primary']]);
            $canDisplay = false;
            foreach ($userShops as $idShop) {
                if (in_array($idShop, $associatedShops)) {
                    $canDisplay =true;
                    break;
                }
            }
            if ($canDisplay) {
                $list[$key]['menu_type'] = $menuTypeLabels[$value['menu_type']];
                if ((int) $value['hook'] == 0) {
                    $list[$key]['hook'] = $this->module->l('none', __CLASS__);
                } else {
                    $hook = new Hook((int) $value['hook']);
                    $list[$key]['hook'] = $hook->name;
                }
                $list[$key]['shop_associated'] = MpMainMenu::getShopNamesAssociated(
                    $value[MpMainMenu::$definition['primary']],
                    $idLang,
                    $associatedShops
                );
            } else {
                unset($list[$key]);
            }
            
        }
        $helper->listTotal = MpMainMenu::getMainMenuCount();
        return $helper->generateList($list, $this->fields_list);
    }

    public function getFormContent($updateForm = false)
    {
        $languages = Language::getLanguages(true);
        $output = '';
        
        $formSubmitted = (bool) Tools::isSubmit('submitMainMenuSave');
        $formAction = '';
        // If form is submitted
        $informationsValues = $this->mainMenuInfoController->getFormPostedValues($languages, $formSubmitted);
        $idLang = $this->context->language->id;
        $currentNav = Tools::getValue('current_nav');
        if (empty($currentNav)) {
            $currentNav = 'nav-information';
        }
        $errors = array();
        $styleValues = array();
        $idMainMenu = (int) Tools::getValue('id_menupro_main_menu', 0);
        if ($formSubmitted == true) {
            $propertiesLoaded = (bool) Tools::getValue('MENUPRO_STYLE_PROPERTIES_LOADED');
            $informationsSaveResult = $this->mainMenuInfoController->processSave($informationsValues, $idMainMenu);
            $styleValues = $this->styleController->getFormPostedValues();
            if (empty($informationsSaveResult['errors'])) {
                if ($idMainMenu == 0) {
                    MpStyleMenu::addDefault(
                        MpCSSPropertyMenu::MENU_TYPE_MAIN,
                        $informationsSaveResult['menu'],
                        $informationsSaveResult['menu']->name[$idLang]
                    );
                }
                if ($propertiesLoaded) {
                    $resultSaveStyle = $this->styleController->processSave(
                        MpCSSPropertyMenu::MENU_TYPE_MAIN,
                        $informationsSaveResult['menu'],
                        $informationsSaveResult['menu']->name[$idLang],
                        $styleValues
                    );
                    if (! empty($resultSaveStyle['errors'])) {
                        $errors = $resultSaveStyle['errors'];
                    }
                }
                $this->module->clearAllTplCache();
            } else {
                $errors = $informationsSaveResult['errors'];
                if ($propertiesLoaded && $idMainMenu) {
                    $errors = array_merge($errors, $this->styleController->getValidationErrors($styleValues));
                }
            }
            
            if (! empty($errors)) {
                $output .= $this->module->displayError($errors);
            } else {
                $successParam = '&mp_success=' .
                (($idMainMenu > 0) ? MpMainMenu::UPDATE_SUCCESS_CODE : MpMainMenu::ADD_SUCCESS_CODE);
                if ((bool) Tools::getValue('submitSaveAndStay')) {
                    $url = $this->getUpdateFormAction($informationsSaveResult['menu']->id) .
                    $successParam . '&current_nav=' . $currentNav;
                    Tools::redirectAdmin($url);
                } else {
                    $this->module->backToModuleHome($successParam);
                }
            }
            $defaultNumberMenuPerLine = (int) $informationsValues['MENUPRO_NUMBER_MENU_PER_LINE_DEFAULT'];
        } else {
            if ($updateForm) {
                $mainMenu = new MpMainMenu((int) $idMainMenu);
                $informationsValues = $this->mainMenuInfoController->getSavedValues($mainMenu);
            }
        }
        if (! $formSubmitted) {
            $defaultNumberMenuPerLine = empty($informationsValues['MENUPRO_NUMBER_MENU_PER_LINE']) ? 1 : 0;
        }
        if ($updateForm) {
            $formAction = $this->getUpdateFormAction($idMainMenu);
        } else {
            $formAction = $this->getAddFormAction();
        }
        $menuTypeLabels = $this->mainMenuInfoController->getMenuTypesLabels();
        $hookPreferences = MpMainMenu::getHookPreferences($menuTypeLabels);
        $imageFolder = Tools::getShopDomainSsl(true) . __PS_BASE_URI__ . 'modules/' . $this->module->name .
                 '/views/img/menu_type/';
        $this->context->smarty->assign(array(
            'displayHtmlTemplate' => $this->module->displayHtmlTemplate,
            'hookPreferences' => $hookPreferences,
            'imageMenuTypeFolder' => $imageFolder,
            'mainMenuInformationFormContent' => $this->module->menuProController->renderForm(
                $this->mainMenuInfoController->getForm($hookPreferences, $informationsValues['MENUPRO_MAIN_MENU_HOOK']),
                $informationsValues,
                'submitMainMenuSave',
                MpMainMenu::$definition['table'],
                MpMainMenu::$definition['primary'],
                true,
                $idMainMenu
            ),
            'currentNav' => $currentNav,
            'formAction' => $formAction,
            'defaultNumberMenuPerLine' => $defaultNumberMenuPerLine,
            'homeLink' => $this->module->getModuleHome()
        ));
        if ($idMainMenu) {
            $properties = (isset($styleValues['properties']) ? $styleValues['properties'] : array());
            if (isset($styleValues['properties'])) {
                $properties = MpCSSProperty::addSelectableValuesToList(
                    $properties,
                    array(),
                    array(),
                    array(),
                    $this->styleController->propertyController->getPropertiesSelectablesValuesLabels()
                );
            }
            if (! $formSubmitted) {
                $styleValues = $this->styleController->getSavedValues(MpCSSPropertyMenu::MENU_TYPE_MAIN, $mainMenu->id);
            }
            foreach ($styleValues as $key => $value) {
                $this->context->smarty->assign($key, $value);
            }
            $disableFields =
            ((int) $styleValues['MENUPRO_USABLE_STYLE'] != MpStyleMenu::USABLE_STYLE_CUSTOMIZED) ? true : false;
            $this->context->smarty->assign(
                array(
                    'id_menupro_main_menu' => $idMainMenu,
                    'mainMenuStyleFormContent' => $this->styleController->init(
                        MpCSSPropertyMenu::MENU_TYPE_MAIN,
                        0,
                        $properties,
                        $disableFields
                    ),
                    'stylesLevelFormContent' => $this->styleLevelController->init(
                        MpCSSPropertyMenu::MENU_TYPE_MAIN,
                        $idMainMenu
                    )
                )
            );
        }
        $output .= $this->context->smarty->fetch($this->local_path . 'views/templates/admin/main_menu_form.tpl');
        return $output;
    }

    public function getUpdateFormAction($idMenu)
    {
        return $this->module->getModuleHome() . '&update' . MpMainMenu::$definition['table'] . '&' .
                 MpMainMenu::$definition['primary'] . '=' . $idMenu;
    }

    public function getAddFormAction()
    {
        return $this->module->getModuleHome() . '&action=add' . MpMainMenu::$definition['table'];
    }

    public function processStatusChange()
    {
        $errors = array();
        $idMainMenu = Tools::getValue(MpMainMenu::$definition['primary']);
        $mainMenu = new MpMainMenu((int) $idMainMenu);
        if (! $mainMenu->toggleStatus()) {
            $errors[] = sprintf(
                $this->module->l('An error occured while changing main menu %s status', __CLASS__),
                $mainMenu->name[$this->context->language->id]
            );
        } else {
            $this->module->clearAllTplCache();
            $this->module->backToModuleHome('&mp_success=' . MpMainMenu::STATUS_CHANGE_SUCCESS_CODE);
        }
        return $this->module->displayError($errors);
    }

    public function processDelete()
    {
        $errors = array();
        $idMainMenu = Tools::getValue(MpMainMenu::$definition['primary']);
        $mainMenu = new MpMainMenu((int) $idMainMenu);
        if (! $mainMenu->delete()) {
            $errors[] = sprintf(
                $this->module->l('An error occured while deleting main menu %s', __CLASS__),
                $mainMenu->name[$this->context->language->id]
            );
        } else {
            $this->module->clearAllTplCache();
            $this->module->backToModuleHome('&mp_success=' . MpMainMenu::DELETE_SUCCESS_CODE);
        }
        return $this->module->displayError($errors);
    }

    public function processBulkDelete()
    {
        $errors = array();
        $idLang = $this->context->language->id;
        $boxes = Tools::getValue(MpMainMenu::$definition['table'] . 'Box');
        if (is_array($boxes) && ! empty($boxes)) {
            foreach ($boxes as $idMenu) {
                $mainMenu = new MpMainMenu((int) $idMenu);
                if (! $mainMenu->delete()) {
                    $errors[] = sprintf(
                        $this->module->l('An error occured while deleting main menu %s', __CLASS__),
                        $mainMenu->name[$idLang]
                    );
                }
            }
        } else {
            $errors[] = $this->module->l('You must select at least one element to delete.', __CLASS__);
        }
        $this->module->clearAllTplCache();
        if (empty($errors)) {
            $this->module->backToModuleHome('&mp_success=' . MpMainMenu::BULK_DELETE_SUCCESS_CODE);
        } else {
            return $this->module->displayError($errors);
        }
    }
    
    public function processBulkEnable()
    {
        $errors = array();
        $idLang = $this->context->language->id;
        $boxes = Tools::getValue(MpMainMenu::$definition['table'] . 'Box');
        if (is_array($boxes) && ! empty($boxes)) {
            foreach ($boxes as $idMenu) {
                $mainMenu = new MpMainMenu((int) $idMenu);
                $mainMenu->active = true;
                if (! $mainMenu->update()) {
                    $errors[] = sprintf(
                        $this->module->l('An error occured while enabling main menu %s', __CLASS__),
                        $mainMenu->name[$idLang]
                    );
                }
            }
        } else {
            $errors[] = $this->module->l('You must select at least one element to enable.', __CLASS__);
        }
        $this->module->clearAllTplCache();
        if (empty($errors)) {
            $this->module->backToModuleHome('&mp_success=' . MpMainMenu::BULK_ENABLE_SUCCESS_CODE);
        } else {
            return $this->module->displayError($errors);
        }
    }

    public function processBulkDisable()
    {
        $errors = array();
        $idLang = $this->context->language->id;
        $boxes = Tools::getValue(MpMainMenu::$definition['table'] . 'Box');
        if (is_array($boxes) && ! empty($boxes)) {
            foreach ($boxes as $idMenu) {
                $mainMenu = new MpMainMenu((int) $idMenu);
                $mainMenu->active = false;
                if (! $mainMenu->update()) {
                    $errors[] = sprintf(
                        $this->module->l('An error occured while disabling main menu %s', __CLASS__),
                        $mainMenu->name[$idLang]
                    );
                }
            }
        } else {
            $errors[] = $this->module->l('You must select at least one element to disable.', __CLASS__);
        }
        $this->module->clearAllTplCache();
        if (empty($errors)) {
            $this->module->backToModuleHome('&mp_success=' . MpMainMenu::BULK_DISABLE_SUCCESS_CODE);
        } else {
            return $this->module->displayError($errors);
        }
    }
}
