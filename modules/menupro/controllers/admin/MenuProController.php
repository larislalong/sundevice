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

include_once _PS_MODULE_DIR_ . 'menupro/controllers/admin/MainMenuController.php';
include_once _PS_MODULE_DIR_ . 'menupro/controllers/admin/SecondaryMenuController.php';
include_once _PS_MODULE_DIR_ . 'menupro/classes/MpCSSPropertyMenu.php';
include_once _PS_MODULE_DIR_ . 'menupro/controllers/admin/StyleLevelController.php';
include_once _PS_MODULE_DIR_ . 'menupro/controllers/admin/StyleMenuController.php';
include_once _PS_MODULE_DIR_ . 'menupro/classes/MpMainMenu.php';
include_once _PS_MODULE_DIR_ . 'menupro/classes/MpSecondaryMenu.php';

class MenuProController
{
    public $module;

    public $cssPropertyController;

    public $mainMenuController;

    public $secondaryMenuController;

    public $styleLevelController;

    public $styleMenuController;

    public $context;

    public $local_path;

    public function __construct($module, $context, $local_path, $_path)
    {
        $this->module = $module;
        $this->context = $context;
        $this->local_path = $local_path;
        $this->_path = $_path;
        $this->cssPropertyController = new CSSPropertyController($this->module, $this->context, $this->local_path);
        $this->mainMenuController = new MainMenuController($this->module, $this->context, $this->local_path);
        $this->secondaryMenuController = new SecondaryMenuController($this->module, $this->context, $this->local_path);
        $this->styleLevelController = new StyleLevelController($module, $context, $local_path);
        $this->styleMenuController = new StyleMenuController($module, $context, $local_path);
    }

    public function renderForm($form, $values, $submitAction, $table, $identifier, $removeTagForm, $id = 0)
    {
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $table;
        $helper->identifier = $identifier;
        $helper->id = $id;
        $helper->module = $this->module;
        $helper->back_url = $this->module->getModuleHome();
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->submit_action = $submitAction;
        $helper->currentIndex = '';
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        
        $helper->tpl_vars = array(
            'fields_value' => $values, /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );
        $formContent = $helper->generateForm(array(
            $form
        ));
        if ($removeTagForm) {
            $formContent = $this->removeFormTag($formContent);
        }
        return $formContent;
    }

    public function removeFormTag($form)
    {
        $positionFormTagOpen = strpos($form, '<form');
        $positionFormTagOpen2 = strpos($form, '>', $positionFormTagOpen);
        $strFormTag = Tools::substr($form, $positionFormTagOpen, $positionFormTagOpen2 + 1);
        $form = str_replace(array(
            $strFormTag,
            '</form>'
        ), '', $form);
        return $form;
    }

    public function init()
    {
        $operationContent = '';
        $this->assignConst();
        $docFolder = Tools::getProtocol(Tools::usingSecureMode()) . $_SERVER ['HTTP_HOST'] .
        $this->module->getPathUri(). 'documentation/';
        $this->context->smarty->assign(
            array(
                'ajaxModuleUrl' => $this->module->getModuleHome(),
                'ps_version' => _PS_VERSION_,
                'englishDocLink' => $docFolder . 'documentation-menupro-en.pdf',
                'frenchDocLink' => $docFolder . 'documentation-menupro-fr.pdf',
            )
        );
        $headerContent = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');
        if (Tools::getIsset('mp_success')) {
            $code = Tools::getValue('mp_success');
            $message = '';
            switch ($code) {
                case MpMainMenu::ADD_SUCCESS_CODE:
                    $message = $this->module->l('Main menu added successfully', __CLASS__);
                    break;
                case MpMainMenu::UPDATE_SUCCESS_CODE:
                    $message = $this->module->l('main menu updated successfully', __CLASS__);
                    break;
                case MpMainMenu::DELETE_SUCCESS_CODE:
                    $message = $this->module->l('main menu deleted successfully', __CLASS__);
                    break;
                case MpMainMenu::BULK_DELETE_SUCCESS_CODE:
                    $message = $this->module->l('main menus bulk deleted successfully', __CLASS__);
                    break;
                case MpMainMenu::BULK_DISABLE_SUCCESS_CODE:
                    $message = $this->module->l('main menus bulk disabled successfully', __CLASS__);
                    break;
                case MpMainMenu::BULK_ENABLE_SUCCESS_CODE:
                    $message = $this->module->l('main menus bulk enabled successfully', __CLASS__);
                    break;
                case MpSecondaryMenu::SORT_SUCCESS_CODE:
                    $message = $this->module->l('Menu sorted successfully', __CLASS__);
                    break;
            }
            if (! empty($message)) {
                $operationContent .= $this->module->displayConfirmation($message);
            }
        }
        if (Tools::getValue('action') == 'add' . MpMainMenu::$definition['table']) {
            $operationContent .= $this->mainMenuController->getFormContent();
        } elseif (Tools::getIsset('view' . MpMainMenu::$definition['table'])) {
            $operationContent .= $this->secondaryMenuController->getFormContent();
        } elseif (Tools::getIsset('update' . MpMainMenu::$definition['table'])) {
            $operationContent .= $this->mainMenuController->getFormContent(true);
        } else {
            if (Tools::getIsset('status' . MpMainMenu::$definition['table'])) {
                $operationContent .= $this->mainMenuController->processStatusChange();
            } elseif (Tools::getIsset('delete' . MpMainMenu::$definition['table'])) {
                $operationContent .= $this->mainMenuController->processDelete();
            } elseif (Tools::getIsset('submitBulkdisable' . MpMainMenu::$definition['table'])) {
                $operationContent .= $this->mainMenuController->processBulkDisable();
            } elseif (Tools::getIsset('submitBulkdelete' . MpMainMenu::$definition['table'])) {
                $operationContent .= $this->mainMenuController->processBulkDelete();
            } elseif (Tools::getIsset('submitBulkenable' . MpMainMenu::$definition['table'])) {
                $operationContent .= $this->mainMenuController->processBulkEnable();
            }
            $operationContent .= $this->mainMenuController->renderList();
            
            $operationContent .= $this->styleLevelController->init(MpCSSPropertyMenu::MENU_TYPE_NONE, 0);
        }
        return $headerContent . $operationContent;
    }

    public function assignConst()
    {
        $menuTypesConst = array(
            'NONE' => MpCSSPropertyMenu::MENU_TYPE_NONE,
            'MAIN' => MpCSSPropertyMenu::MENU_TYPE_MAIN,
            'SECONDARY' => MpCSSPropertyMenu::MENU_TYPE_SECONDARY
        );
        $valueResultConst = array(
            'DEFINED' => MpCSSPropertyMenu::VALUE_DEFINED,
            'NOT_ACCESSIBLE' => MpCSSPropertyMenu::VALUE_NOT_ACCESSIBLE,
            'NOT_YET_DEFINED' => MpCSSPropertyMenu::VALUE_NOT_YET_DEFINED,
            'WRONG_CONFIG' => MpCSSPropertyMenu::VALUE_WRONG_CONFIG
        );
        $styleTypesConst = array(
            'DEFAULT' => MpCSSPropertyMenu::STYLE_DEFAULT,
            'MENU' => MpCSSPropertyMenu::STYLE_MENU
        );
        $usableValuesConst = array(
            'THEME' => MpCSSPropertyMenu::USABLE_VALUE_THEME,
            'DEFAULT' => MpCSSPropertyMenu::USABLE_VALUE_DEFAULT,
            'CUSTOMIZED' => MpCSSPropertyMenu::USABLE_VALUE_CUSTOMIZED,
            'MENU_PRO_LEVEL' => MpCSSPropertyMenu::USABLE_VALUE_MENU_PRO_LEVEL,
            'NEAREST_RELATIVE' => MpCSSPropertyMenu::USABLE_VALUE_NEAREST_RELATIVE,
            'HIGHEST_SECONDARY_MENU_LEVEL' => MpCSSPropertyMenu::USABLE_VALUE_HIGHEST_SECONDARY_MENU_LEVEL,
            'MAIN_MENU_LEVEL' => MpCSSPropertyMenu::USABLE_VALUE_MAIN_MENU_LEVEL
        );
        
        $propertiesTypesConst = array(
            'OTHER' => MpCSSProperty::PROPERTY_TYPE_OTHER,
            'COLOR' => MpCSSProperty::PROPERTY_TYPE_COLOR,
            'SELECT' => MpCSSProperty::PROPERTY_TYPE_SELECT,
            'SELECT_EDITABLE' => MpCSSProperty::PROPERTY_TYPE_SELECT_EDITABLE
        );
        $mainMenuTypeConst = array(
            'MENU_TYPE_MEGA' => MpMainMenu::MENU_TYPE_MEGA,
            'MENU_TYPE_SIMPLE' => MpMainMenu::MENU_TYPE_SIMPLE
        );
        $linkTypesConst = array(
            'INTERNAL' => MpSecondaryMenu::LINK_TYPE_INTERNAL,
            'EXTERNAL' => MpSecondaryMenu::LINK_TYPE_EXTERNAL
        );
        $homeLinkList = array();
        $languages = Language::getLanguages();
        foreach ($languages as $language) {
            $language = (object) $language;
            $homeLinkList[(int) $language->id_lang] = $this->context->link->getPageLink('', null, $language->id_lang);
        }
        $this->context->smarty->assign(
            array(
                'menuTypesConst' => $menuTypesConst,
                'propertiesTypesConst' => $propertiesTypesConst,
                'usableValuesConst' => $usableValuesConst,
                'styleTypesConst' => $styleTypesConst,
                'valueResultConst' => $valueResultConst,
                'mainMenuTypeConst' => $mainMenuTypeConst,
                'linkTypesConst' => $linkTypesConst,
                'homeLinkList' => $homeLinkList,
                'defaultModLanguage' => $this->context->language->id
            )
        );
    }

    public function includeBOCssJs()
    {
        $isMenuproPage = ((Tools::getValue('module_name') == $this->module->name) ||
        (Tools::getValue('configure') == $this->module->name));
        $anchor = Tools::getValue('anchor');
        if ($isMenuproPage && empty($anchor)) {
            $this->context->controller->addJquery();
            $this->context->controller->addJS($this->_path . 'views/js/back.js');
            $this->context->controller->addJS($this->_path . 'views/js/PropertyManager.js');
            $this->context->controller->addJS($this->_path . 'views/js/StyleLevelManager.js');
            if ((Tools::getValue('action') == 'add' . MpMainMenu::$definition['table']) ||
                    (Tools::getIsset('update' . MpMainMenu::$definition['table']))) {
                $this->context->controller->addJS($this->_path . 'views/js/MainMenuManager.js');
                $this->context->controller->addJS($this->_path . 'views/js/StyleManager.js');
            } elseif (Tools::getIsset('view' . MpMainMenu::$definition['table'])) {
                $this->context->controller->addJqueryUI('ui.sortable');
                $this->context->controller->addJS($this->_path . 'views/js/SecondaryMenuManager.js');
                $this->context->controller->addJS($this->_path . 'views/js/StyleManager.js');
                $this->context->controller->addJS($this->_path . 'views/js/PageManager.js');
                $this->context->controller->addJS($this->_path . 'views/js/SecondaryMenuTypeManager.js');
                $this->context->controller->addJS($this->_path . 'views/js/ItemSelectionManager.js');
                $this->context->controller->addJS($this->_path . 'views/js/SecondaryMenuEditionManager.js');
                $this->context->controller->addJS($this->_path . 'views/js/HtmlContentManager.js');
                $this->context->controller->addJS($this->_path . 'views/js/SecondaryMenuSortManager.js');
                if(_PS_VERSION_>='1.6'){
                    $directory = (_PS_VERSION_>='1.6')?_PS_CORE_DIR_:_PS_ROOT_DIR_;
                    $admin_webpath = str_ireplace($directory, '', _PS_ADMIN_DIR_);
                    $admin_webpath = preg_replace('/^' . preg_quote(DIRECTORY_SEPARATOR, '/') . '/', '', $admin_webpath);
                    $bo_theme = ((Validate::isLoadedObject($this->context->employee) &&
                            $this->context->employee->bo_theme) ? $this->context->employee->bo_theme : 'default');
                    
                    if (! file_exists(_PS_BO_ALL_THEMES_DIR_ . $bo_theme . DIRECTORY_SEPARATOR . 'template')) {
                        $bo_theme = 'default';
                    }
                    
                    $js_path = __PS_BASE_URI__ . $admin_webpath . '/themes/' . $bo_theme . '/js/tree.js';
                    $this->context->controller->addJS($js_path);
                }else{
                    $this->context->controller->addJqueryPlugin('treeview-categories');
                    $this->context->controller->addJS(_PS_JS_DIR_.'jquery/plugins/treeview-categories/jquery.treeview-categories.async.js');
                    $this->context->controller->addJS(_PS_JS_DIR_.'jquery/plugins/treeview-categories/jquery.treeview-categories.edit.js');
                    $this->context->controller->addJS(_PS_JS_DIR_.'admin-categories-tree.js');
                    
                }
                
                
                $this->context->controller->addJS(_PS_JS_DIR_ . 'tiny_mce/tiny_mce.js');
                $directoryMce = (_PS_VERSION_>='1.6')?'admin/':'';
                $this->context->controller->addJS(_PS_JS_DIR_ . $directoryMce.'tinymce.inc.js');
                $this->context->controller->addJqueryPlugin('autosize');
            }
            $this->context->controller->addCSS($this->_path . 'views/css/back.css');
            if(_PS_VERSION_<'1.6'){
				if(_PS_VERSION_<'1.5.6'){
					$this->context->controller->addJqueryUI('ui.button');
				}
                $this->context->controller->addJqueryUI('ui.dialog');
                $this->context->controller->addCSS($this->_path . 'views/css/back_1_5.css');
            }
            $this->context->controller->addJqueryPlugin('colorpicker');
            $this->context->controller->addJqueryPlugin('scrollTo');
        }
    }

    public function renderFormProperties()
    {
        $idStyle = (int) Tools::getValue('MENUPRO_ID_STYLE');
        $styleType = (int) Tools::getValue('MENUPRO_STYLE_TYPE');
        $menuType = (int) Tools::getValue('MENUPRO_MENU_TYPE');
        $menuLevel = (int) Tools::getValue('MENUPRO_MENU_LEVEL');
        $disableFields = Tools::getValue('MENUPRO_PROPERTIES_DISABLED') == 'true';
        $this->assignConst();
        
        $this->outputJSON(
            $this->cssPropertyController->getForm(
                $menuType,
                $styleType,
                $menuLevel,
                $idStyle,
                null,
                $disableFields
            ),
            'success'
        );
    }

    public function renderCategoriesTree()
    {
        if(_PS_VERSION_>='1.6'){
            $categoryTree = new HelperTreeCategories('categories-tree', $this->module->l('Select categories', __CLASS__));
            $categoryTree->setUseCheckBox(true);
            $categoryTree->setUseSearch(false);
            $this->outputJSON($categoryTree->render(), 'success');
        }else{
            $helper = new HelperForm();
            $this->outputJSON($helper->renderCategoryTree(null, array(), 'categoryBox'), 'success');
        }
    }

    public function outputResult($result)
    {
        if (! empty($result['errors'])) {
            $this->outputJSON($result['errors']);
        } else {
            $this->outputJSON($result['success'], 'success');
        }
    }

    public function outputJSON($msg, $status = 'error')
    {
        header('Content-Type: application/json');
        $result = array(
            'data' => $msg,
            'status' => $status
        );
        echo json_encode($result);
        die();
    }
}
