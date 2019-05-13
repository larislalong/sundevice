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
include_once _PS_MODULE_DIR_ . 'menupro/classes/MpSecondaryMenu.php';
include_once _PS_MODULE_DIR_ . 'menupro/classes/MpCSSPropertyMenu.php';
include_once _PS_MODULE_DIR_ . 'menupro/classes/MpHtmlContent.php';
include_once _PS_MODULE_DIR_ . 'menupro/controllers/admin/MainMenuInformationController.php';
include_once _PS_MODULE_DIR_ . 'menupro/controllers/admin/StyleMenuController.php';
include_once _PS_MODULE_DIR_ . 'menupro/controllers/admin/SecondaryMenuInformationController.php';
include_once _PS_MODULE_DIR_ . 'menupro/controllers/admin/HtmlContentController.php';

class SecondaryMenuController
{
    public $module;

    public $context;

    public $mainMenuInfoController;

    public $styleLevelController;

    public $htmlContentController;

    public $styleController;

    public $local_path;

    public $secondaryMenuInformationController;

    public function __construct($module, $context, $local_path)
    {
        $this->module = $module;
        $this->context = $context;
        $this->local_path = $local_path;
        $this->mainMenuInfoController = new MainMenuInformationController($this->module, $this->context);
        $this->secondaryMenuInformationController = new SecondaryMenuInformationController(
            $this->module,
            $this->context
        );
        $this->styleLevelController = new StyleLevelController($module, $context, $local_path);
        $this->htmlContentController = new HtmlContentController($module, $context, $local_path);
        $this->styleController = new StyleMenuController($module, $context, $local_path);
    }

    public function getFormContent()
    {
        $idLang = $this->context->language->id;
        $idMainMenu = Tools::getValue('id_menupro_main_menu');
        $mainMenu = new MpMainMenu((int) $idMainMenu, $idLang);
        $availableSecondaryMenuTypeConst = array(
            'CATEGORY' => MpSecondaryMenu::MENU_TYPE_CATEGORY,
            'PRODUCT' => MpSecondaryMenu::MENU_TYPE_PRODUCT,
            'CMS' => MpSecondaryMenu::MENU_TYPE_CMS,
            'SUPPLIER' => MpSecondaryMenu::MENU_TYPE_SUPPLIER,
            'MANUFACTURER' => MpSecondaryMenu::MENU_TYPE_MANUFACTURER,
            'PAGE' => MpSecondaryMenu::MENU_TYPE_PAGE,
            'CUSTOMISE' => MpSecondaryMenu::MENU_TYPE_CUSTOMISE
        );
        $searchMethodConst = array(
            'BY_NAME' => MpSecondaryMenu::SEARCH_BY_NAME,
            'BY_ID' => MpSecondaryMenu::SEARCH_BY_ID
        );
        $headerMenuTpl = $this->local_path . 'views/templates/admin/header_tree_secondary.tpl';
        $footerMenuTpl = $this->local_path . 'views/templates/admin/footer_tree_secondary.tpl';
        $this->context->smarty->assign(array(
            'addSubmenusTitle' => $this->module->l('Add submenus', __CLASS__),
            'editSubmenuTitle' => $this->module->l('Edit menu', __CLASS__),
            'deleteSubmenuTitle' => $this->module->l('Delete menu', __CLASS__)
        ));
        $imageFolder = Tools::getShopDomainSsl(true) . __PS_BASE_URI__ . 'modules/' . $this->module->name .
                 '/views/img/display_type/';
        $this->context->smarty->assign(array(
            'displayHtmlTemplate' => $this->module->displayHtmlTemplate,
            'imageDisplayTypeFolder' => $imageFolder,
            'itemTypeParams' => $this->getItemTypeParams(),
            'homeLink' => $this->module->getModuleHome(),
            'mainMenu' => (array) $mainMenu,
            'availableSecondaryMenuTypeConst' => $availableSecondaryMenuTypeConst,
            'availableSecondaryMenuType' => $this->getAvailableSecondaryMenuType(),
            'searchMethodConst' => $searchMethodConst,
            'addSubmenusTitle' => $this->module->l('Add submenus', __CLASS__),
            'editSubmenuTitle' => $this->module->l('Edit menu', __CLASS__),
            'deleteSubmenuTitle' => $this->module->l('Delete menu', __CLASS__),
            'ITEMS_PER_PAGE' => MpSecondaryMenu::ITEMS_PER_PAGE,
            'menuTreeContent' => MpSecondaryMenu::buildMenuTree(
                $mainMenu->id,
                0,
                false,
                $headerMenuTpl,
                $footerMenuTpl,
                $this->context,
                $idLang,
                false,
                null,
                null
            ),
            'selectItemsTemplates' => $this->local_path . 'views/templates/admin/select_items.tpl'
        ));
        return $this->context->smarty->fetch($this->local_path . 'views/templates/admin/secondary_menu_form.tpl');
    }

    public function getEditionForm()
    {
        $result = array();
        $idSecondaryMenu = Tools::getValue('MENUPRO_SECONDARY_MENU_ID');
        if (! is_numeric($idSecondaryMenu) || ((int) $idSecondaryMenu <= 0)) {
            $result['errors'][] = $this->l('Wrong secondary menu id');
        }
        if (empty($result['errors'])) {
            $secondaryMenu = new MpSecondaryMenu((int) $idSecondaryMenu);
            $values = $this->secondaryMenuInformationController->getSavedValues($secondaryMenu);
            $displayHtmlContents = ($secondaryMenu->level == 1);
            $styleValues = $this->styleController->getSavedValues(
                MpCSSPropertyMenu::MENU_TYPE_SECONDARY,
                $secondaryMenu->id
            );
            foreach ($styleValues as $key => $value) {
                $this->context->smarty->assign($key, $value);
            }
            $this->context->smarty->assign('secondaryMenuLevel', $secondaryMenu->level);
            $this->module->menuProController->assignConst();
            $this->context->smarty->assign('ps_version', _PS_VERSION_);
            $this->context->smarty->assign(
                array(
                    'ps_version' => _PS_VERSION_,
                    'displayHtmlTemplate' => $this->module->displayHtmlTemplate,
                    'secondaryMenuInformationFormContent' => $this->module->menuProController->renderForm(
                        $this->secondaryMenuInformationController->getForm($secondaryMenu),
                        $values,
                        'submitMainMenuSave',
                        MpSecondaryMenu::$definition['table'],
                        MpSecondaryMenu::$definition['primary'],
                        false,
                        $secondaryMenu->id
                    ),
                    'displayHtmlContents' => $displayHtmlContents,
                    'displayStyleLevels' => $displayHtmlContents,
                    'idSecondaryMenu' => $secondaryMenu->id,
                    'secondaryMenuStyleFormContent' => $this->styleController->init(
                        MpCSSPropertyMenu::MENU_TYPE_SECONDARY,
                        $secondaryMenu->level,
                        array(),
                        false
                    ),
                    'stylesLevelFormContent' => $this->styleLevelController->init(
                        MpCSSPropertyMenu::MENU_TYPE_SECONDARY,
                        $secondaryMenu->id
                    ),
                    'htmlContentFormContent' => $this->htmlContentController->init($secondaryMenu->id)
                )
            );
            $result['success']['form'] = $this->context->smarty->fetch($this->local_path .
                     'views/templates/admin/secondary_menu_edition_form.tpl');
        }
        return $result;
    }

    public function processGetHtmlContentEditionContent()
    {
        $result = array();
        $idContent = (int) Tools::getValue('MENUPRO_HTML_CONTENT_ID', 0);
        $htmlContent = null;
        if ($idContent) {
            $htmlContent = new MpHtmlContent($idContent);
        }
        $values=array();
        $values['MENUPRO_HTML_CONTENT_POSITION'] =
        (($idContent) ? $htmlContent->position : MpHtmlContent::POSITION_TOP);
        $values['MENUPRO_HTML_CONTENT_CONTENT'] = (($idContent) ? $htmlContent->content : null);
        $result['success']['form'] = $this->module->menuProController->renderForm(
            $this->htmlContentController->getForm(),
            $values,
            'submitMainMenuSave',
            MpHtmlContent::$definition['table'],
            MpHtmlContent::$definition['primary'],
            false,
            $idContent
        );
        
        return $result;
    }

    public function getItemTypeParams()
    {
        $params = array(
            MpSecondaryMenu::MENU_TYPE_CATEGORY => array(
                MpSecondaryMenu::DISPLAY_STYLE_SIMPLE => array(
                    'image_file_name' => 'category_simple.png'
                ),
                MpSecondaryMenu::DISPLAY_STYLE_COMPLEX => array(
                    'image_file_name' => (_PS_VERSION_ < '1.7') ? 'category_full.png' : 'category_full_1_7.png'
                )
            
            ),
            MpSecondaryMenu::MENU_TYPE_PRODUCT => array(
                MpSecondaryMenu::DISPLAY_STYLE_SIMPLE => array(
                    'image_file_name' => 'product_simple.png'
                ),
                MpSecondaryMenu::DISPLAY_STYLE_COMPLEX => array(
                    'image_file_name' => (_PS_VERSION_ < '1.7') ? 'product_full.png' : 'product_full_1_7.png'
                )
            
            )
        );
        return $params;
    }

    public function getAvailableSecondaryMenuType()
    {
        $availableSecondaryMenuType = array(
            array(
                'id' => MpSecondaryMenu::MENU_TYPE_CATEGORY,
                'emptyMessage' => $this->module->l('there is no category', __CLASS__),
                'name' => $this->module->l('Category', __CLASS__)
            ),
            array(
                'id' => MpSecondaryMenu::MENU_TYPE_PRODUCT,
                'emptyMessage' => $this->module->l('there is no product', __CLASS__),
                'name' => $this->module->l('Product', __CLASS__)
            ),
            array(
                'id' => MpSecondaryMenu::MENU_TYPE_CMS,
                'emptyMessage' => $this->module->l('there is no CMS', __CLASS__),
                'name' => $this->module->l('CMS', __CLASS__)
            ),
            array(
                'id' => MpSecondaryMenu::MENU_TYPE_SUPPLIER,
                'emptyMessage' => $this->module->l('there is no supplier', __CLASS__),
                'name' => $this->module->l('Supplier', __CLASS__)
            ),
            array(
                'id' => MpSecondaryMenu::MENU_TYPE_MANUFACTURER,
                'emptyMessage' => $this->module->l('there is no manufacturer', __CLASS__),
                'name' => $this->module->l('Manufacturer', __CLASS__)
            ),
            array(
                'id' => MpSecondaryMenu::MENU_TYPE_PAGE,
                'name' => $this->module->l('Page', __CLASS__),
                'emptyMessage' => $this->module->l('there is no page', __CLASS__),
                'searchName' => $this->module->l('Page', __CLASS__)
            ),
            array(
                'id' => MpSecondaryMenu::MENU_TYPE_CUSTOMISE,
                'emptyMessage' => $this->module->l('', __CLASS__),
                'name' => $this->module->l('Customized', __CLASS__)
            )
        );
        return $availableSecondaryMenuType;
    }

    public function processGetAttachableItems()
    {
        $resultValidate = $this->validateAttachableItemType();
        $result=array();
        $result['errors'] = $resultValidate['errors'];
        $start = (int) Tools::getValue('MENUPRO_ITEMS_SELECTION_START');
        if (empty($result['errors'])) {
            $idLang = $this->context->language->id;
            $idShop = $this->context->shop->id;
            $limit = MpSecondaryMenu::ITEMS_PER_PAGE;
            $items = MpSecondaryMenu::getAttachableItems(
                0,
                $idLang,
                $idShop,
                $resultValidate['type'],
                '',
                0,
                '',
                $start,
                $limit,
                null
            );
            $result['success']['items'] = $items;
        }
        return $result;
    }

    public function processUpdate()
    {
        $result = array();
        $languages = Language::getLanguages(true);
        $propertiesLoaded = (bool) Tools::getValue('MENUPRO_STYLE_PROPERTIES_LOADED');
        $informationsValues = $this->secondaryMenuInformationController->getFormPostedValues($languages);
        $secondaryMenu = new MpSecondaryMenu((int) $informationsValues['MENUPRO_SECONDARY_MENU_ID']);
        $informationsSaveResult = $this->secondaryMenuInformationController->processSave(
            $informationsValues,
            $secondaryMenu
        );
        $styleValues = array();
        if ($propertiesLoaded) {
            $styleValues = $this->styleController->getFormPostedValues();
        }
        if (empty($informationsSaveResult['errors'])) {
            if ($propertiesLoaded) {
                $resultSaveStyle = $this->styleController->processSave(
                    MpCSSPropertyMenu::MENU_TYPE_SECONDARY,
                    $informationsSaveResult['menu'],
                    $informationsSaveResult['menu']->name[$this->context->language->id],
                    $styleValues
                );
                if (! empty($resultSaveStyle['errors'])) {
                    $result['errors'] = $resultSaveStyle['errors'];
                }
            }
        } else {
            $result['errors'] = $informationsSaveResult['errors'];
            if ($propertiesLoaded) {
                $result['errors'] = array_merge(
                    $result['errors'],
                    $this->styleController->getValidationErrors($styleValues)
                );
            }
        }
        if (empty($result['errors'])) {
            $this->module->clearAllTplCache();
            $result['success']['message'] = $this->module->l('Menu updated successfully', __CLASS__);
            $result['success']['name'] = $informationsSaveResult['menu']->name[$this->context->language->id];
            $result['success']['active'] = $informationsSaveResult['menu']->active;
        }
        return $result;
    }

    public function processDelete()
    {
        $resultValidate = $this->validateIdMenu();
        $result = array();
        $result['errors'] = $resultValidate['errors'];
        if (empty($result['errors'])) {
            $secondaryMenu = new MpSecondaryMenu($resultValidate['id_menu']);
            if ($secondaryMenu->delete()) {
                MpSecondaryMenu::decrementBrothersPosition($secondaryMenu);
                $idLang = $this->context->language->id;
                $brothers = MpSecondaryMenu::getAll(
                    $secondaryMenu->id_menupro_main_menu,
                    $secondaryMenu->parent_menu,
                    $idLang,
                    false
                );
                $result['success']['brothers'] = array();
                foreach ($brothers as $brother) {
                    $result['success']['brothers'][] = array(
                        'id' => $brother[MpSecondaryMenu::$definition['primary']],
                        'position' => $brother['position']
                    );
                }
                $result['success']['parent_menu'] = (int) $secondaryMenu->parent_menu;
                $result['success']['message'] = $this->module->l('Menu deleted successfully', __CLASS__);
                $this->module->clearAllTplCache();
            } else {
                $result['errors'][] = $this->module->l('An error occured while deleting menu', __CLASS__);
            }
        }
        return $result;
    }

    public function processStatusChange()
    {
        $resultValidate = $this->validateIdMenu();
        $result = array();
        $result['errors'] = $resultValidate['errors'];
        if (empty($result['errors'])) {
            $secondaryMenu = new MpSecondaryMenu($resultValidate['id_menu']);
            if(empty($secondaryMenu->parent_menu)){
                $secondaryMenu->parent_menu = null;
            }
            if ($secondaryMenu->toggleStatus()) {
                $result['success']['active'] = $secondaryMenu->active;
                $result['success']['message'] = $this->module->l('Menu status changed successfully', __CLASS__);
                $this->module->clearAllTplCache();
            } else {
                $result['errors'][] = $this->module->l('An error occured while changing menu status', __CLASS__);
            }
        }
        return $result;
    }

    public function processSortMenu()
    {
        $result = array();
        $idMainMenu = Tools::getValue('MENUPRO_MENU_SORT_MAIN_MENU_ID');
        if (empty($idMainMenu) || (! is_numeric($idMainMenu)) || ((int) $idMainMenu <= 0)) {
            $result['errors'][] = $this->module->l('Wrong id menu', __CLASS__);
        }
        $menus = Tools::getValue('MENUPRO_MENU_SORT_SORTED_ITEMS');
        if (empty($menus) || (! is_array($menus))) {
            $result['errors'][] = $this->module->l('Menu empty', __CLASS__);
        }
        if (empty($result['errors'])) {
            foreach ($menus as $menu) {
                MpSecondaryMenu::updateSort($menu, $idMainMenu);
            }
            $result['success']['link'] = $this->module->getModuleHome() . '&view' . MpMainMenu::$definition['table'] .
                     '&' . MpMainMenu::$definition['primary'] . '=' . $idMainMenu . '&mp_success=' .
                     MpSecondaryMenu::SORT_SUCCESS_CODE;
            $this->module->clearAllTplCache();
        }
        return $result;
    }

    public function processGetAttachableItemsCount()
    {
        $resultValidate = $this->validateAttachableItemType();
        $result = array();
        $result['errors'] = $resultValidate['errors'];
        if (empty($result['errors'])) {
            $result['success']['count'] = MpSecondaryMenu::getAttachableItemsCount($resultValidate['type']);
        }
        return $result;
    }

    public function validateAttachableItemType()
    {
        $result = array();
        $result['errors'] = array();
        $itemType = Tools::getValue('MENUPRO_ITEMS_SELECTION_ITEM_TYPE');
        if (empty($itemType) || (! is_numeric($itemType)) || ((int) $itemType <= 0)) {
            $result['errors'][] = $this->module->l('Wrong item type', __CLASS__);
        } else {
            $result['type'] = $itemType;
        }
        return $result;
    }

    public function validateIdMenu()
    {
        $result = array();
        $result['errors'] = array();
        $idMenu = Tools::getValue('MENUPRO_SECONDARY_MENU_ID');
        if (empty($idMenu) || (! is_numeric($idMenu)) || ((int) $idMenu <= 0)) {
            $result['errors'][] = $this->module->l('Wrong id menu', __CLASS__);
        } else {
            $result['id_menu'] = (int) $idMenu;
        }
        return $result;
    }

    public function getAddItemsValidationErrors($values)
    {
        $errors = array();
        if (empty($values['id_main_menu']) ||
                (! is_numeric($values['id_main_menu'])) ||
                ((int) $values['id_main_menu'] <= 0)) {
             $errors[] = $this->module->l('Wrong id main menu', __CLASS__);
        }
        if (empty($values['items']) || (! is_array($values['items']))) {
            $errors[] = $this->module->l('You must select at least 1 item', __CLASS__);
        }
        return $errors;
    }

    public function processAddItems()
    {
        $values = $this->getAddItemsPostedValues();
        $result = $this->getAddItemsValidationErrors($values);
        if (empty($result['errors'])) {
            $idLang = $this->context->language->id;
            $idShop = $this->context->shop->id;
            $menus = MpSecondaryMenu::addItems(
                $values['id_main_menu'],
                $values['parent_menu'],
                $idLang,
                $idShop,
                $values['items'],
                $this->module->l('New item')
            );
            if (! empty($menus)) {
                $result['success']['menus'] = $menus;
                $result['success']['message'] = $this->module->l('Items added successfully', __CLASS__);
                $this->module->clearAllTplCache();
            } else {
                $result['errors'][] = $this->module->l('An error occured while adding items', __CLASS__);
            }
        }
        return $result;
    }

    public function getAddItemsPostedValues()
    {
        $values = array();
        $values['parent_menu'] = Tools::getValue('MENUPRO_ITEMS_SELECTION_PARENT_MENU_ID');
        $values['items'] = Tools::getValue('MENUPRO_ITEMS_SELECTION_SELECTED');
        $values['id_main_menu'] = Tools::getValue('MENUPRO_ITEMS_SELECTION_MAIN_MENU_ID');
        return $values;
    }

    public function processSearchAttachableItems()
    {
        $itemType = Tools::getValue('MENUPRO_ITEMS_SELECTION_ITEM_TYPE');
        $searchType = Tools::getValue('MENUPRO_ITEMS_SELECTION_SEARCH_TYPE');
        $query = Tools::getValue('q', false);
        if (! $query or $query == '' or Tools::strlen($query) < 1) {
            die();
        }
        $excludeIds = Tools::getValue('excludeIds', false);
        
        if ($excludeIds && $excludeIds != 'NaN') {
            $excludeIds = implode(',', array_map('intval', explode(',', $excludeIds)));
        } else {
            $excludeIds = '';
        }
        $idLang = $this->context->language->id;
        $idShop = $this->context->shop->id;
        $items = MpSecondaryMenu::getAttachableItems(
            0,
            $idLang,
            $idShop,
            $itemType,
            $excludeIds,
            $searchType,
            $query,
            0,
            0,
            null
        );
        if ($items) {
            foreach ($items as $item) {
                echo trim($item['name']) . '|' . (int) ($item['id']) . "\n";
            }
        } else {
            Tools::jsonEncode(new stdClass());
        }
    }
}
