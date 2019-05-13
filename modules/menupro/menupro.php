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

if (! defined('_PS_VERSION_')) {
    exit();
}

include_once _PS_MODULE_DIR_ . 'menupro/controllers/admin/MenuProController.php';
include_once _PS_MODULE_DIR_ . 'menupro/controllers/front/MenuProFrontController.php';
include_once _PS_MODULE_DIR_ . 'menupro/classes/MpCSSProperty.php';
include_once _PS_MODULE_DIR_ . 'menupro/classes/MpSecondaryMenu.php';

class Menupro extends Module
{

    protected $config_form = false;

    public $menuProController;

    public $menuProFrontController;

    public function __construct()
    {
        $this->name = 'menupro';
        $this->tab = 'front_office_features';
        $this->version = '2.0.0';
        $this->author = 'Crystals Services Sarl';
        $this->need_instance = 0;
        
        /**
         * Set $this->bootstrap to true if your module is compliant with
         * bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;
		if(_PS_VERSION_>='1.6'){
			$max = _PS_VERSION_;
		}else{
			$max = '1.7';
		}
		$this->ps_versions_compliancy = array('min' => '1.5', 'max' => $max);  
		$this->module_key = '9cbb840a4aa3d10ca775233ea423edcf';
        
        parent::__construct();
        
        $this->displayName = $this->l('Menu Pro');
        $this->description = $this->l('Module allowing you to create menus and graft them where you want');
        
        $this->menuProController = new MenuProController($this, $this->context, $this->local_path, $this->_path);
        $this->menuProFrontController = new MenuProFrontController(
            $this,
            $this->context,
            $this->local_path,
            $this->_path,
            __FILE__
        );
        $this->displayHtmlTemplate = $this->local_path . 'views/templates/admin/display_html.tpl';
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        if (! parent::install() ||
                ! $this->registerHook('header') ||
                ! $this->registerHook('backOfficeHeader') ||
                ! $this->registerHook('displayFooter') ||
                ! $this->registerHook('displayHome') ||
                ! $this->registerHook('displayHomeTab') ||
                ! $this->registerHook('displayHomeTabContent') ||
                ! $this->registerHook('displayLeftColumn') ||
                ! $this->registerHook('displayNav') ||
                ! $this->registerHook('displayRightColumn') ||
                ! $this->registerHook('displayTop') ||
                ! $this->registerHook('displayTopColumn') ||
                ! $this->registerHook('actionObjectCategoryUpdateAfter') ||
                ! $this->registerHook('actionObjectCategoryDeleteAfter') ||
                ! $this->registerHook('actionObjectCategoryAddAfter') ||
                ! $this->registerHook('actionObjectProductUpdateAfter') ||
                ! $this->registerHook('actionObjectProductDeleteAfter') ||
                ! $this->registerHook('actionObjectCmsDeleteAfter') ||
                ! $this->registerHook('actionObjectSupplierDeleteAfter') ||
                ! $this->registerHook('actionObjectManufacturerDeleteAfter') ||
                ! $this->registerHook('actionObjectMetaDeleteAfter')) {
            return false;
        }
        $this->clearAllTplCache();
        require_once(dirname(__FILE__) . '/sql/install.php');
        MpCSSProperty::insertBaseProperty();
        return true;
    }

    public function uninstall()
    {
        if (! parent::uninstall()) {
            return false;
        }
        $this->clearAllTplCache();
        require_once(dirname(__FILE__) . '/sql/uninstall.php');
        return true;
    }

    public function backToModuleHome($aditionalParameter = '')
    {
        Tools::redirectAdmin($this->getModuleHome() . $aditionalParameter);
    }

    public function getModuleHome()
    {
        return $this->context->link->getAdminLink('AdminModules') . '&configure=' . $this->name . '&module_name=' .
                 $this->name;
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        if (Tools::getIsset('ajax') && (_PS_VERSION_ < '1.6')) {
            $ajaxAction = Tools::getValue('action');
            $methodName = 'ajaxProcess'.$ajaxAction;
            $methodName = is_callable(array($this, $methodName))?$methodName:
                'ajaxProcess'.ucfirst($ajaxAction);
            if (is_callable(array($this, $methodName))) {
                $this->$methodName();
            }
        }
        return $this->menuProController->init();
    }

    /**
     * Add the CSS & JavaScript files you want to be loaded in the BO.
     */
    public function hookBackOfficeHeader()
    {
        $this->menuProController->includeBOCssJs();
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path . '/views/js/front.js');
        $this->context->controller->addCSS($this->_path . '/views/css/front.css');
        $this->context->controller->addCSS($this->_path . '/views/css/ionicons.min.css');
        if (_PS_VERSION_ < '1.7') {
            $this->context->controller->addCSS(_THEME_CSS_DIR_ . 'product_list.css');
            if(_PS_VERSION_ >= '1.6'){
                $compared_products = array();
                if (Configuration::get('PS_COMPARATOR_MAX_ITEM') && isset($this->context->cookie->id_compare)) {
                    $compared_products = CompareProduct::getCompareProducts($this->context->cookie->id_compare);
                }
                $addJsDef = $this->local_path . 'views/templates/hook/add_js_def.tpl';
                $this->context->smarty->assign(
                    array(
                        'compared_products' => is_array($compared_products) ? $compared_products : array(),
                        'addJsDef' => $addJsDef
                    )
                );
            }else{
                $this->context->controller->addCSS($this->_path . '/views/css/front_1_5.css');
            }
        }
        $this->context->smarty->assign('ps_version', _PS_VERSION_);
        return $this->context->smarty->fetch($this->local_path . 'views/templates/hook/header.tpl');
    }

    public function hookDisplayFooter()
    {
        return $this->menuProFrontController->renderMenu('footer');
    }

    public function hookDisplayHome()
    {
        return $this->menuProFrontController->renderMenu('displayHome');
    }

    public function hookDisplayHomeTab()
    {
        return $this->menuProFrontController->renderMenu('displayHomeTab');
    }

    public function hookDisplayHomeTabContent()
    {
        return $this->menuProFrontController->renderMenu('displayHomeTabContent');
    }

    public function hookDisplayLeftColumn()
    {
        return $this->menuProFrontController->renderMenu('displayLeftColumn');
    }

    public function hookDisplayNav()
    {
        return $this->menuProFrontController->renderMenu('displayNav');
    }

    public function hookDisplayRightColumn()
    {
        return $this->menuProFrontController->renderMenu('displayRightColumn');
    }

    public function hookDisplayTop($params)
    {
        return $this->menuProFrontController->renderMenu('displayTop');
    }

    public function hookDisplayTopColumn($params)
    {
        return $this->menuProFrontController->renderMenu('displayTopColumn');
    }

    public function hookActionObjectCategoryAddAfter($params)
    {
        $this->clearAllTplCache();
    }

    public function hookActionObjectCategoryUpdateAfter($params)
    {
        $this->clearAllTplCache();
    }

    public function hookActionObjectCategoryDeleteAfter($params)
    {
        MpSecondaryMenu::deleteMenuFromItem(MpSecondaryMenu::MENU_TYPE_CATEGORY, $params['object']->id);
        $this->clearAllTplCache();
    }

    public function hookActionObjectCmsDeleteAfter($params)
    {
        MpSecondaryMenu::deleteMenuFromItem(MpSecondaryMenu::MENU_TYPE_CMS, $params['object']->id);
        $this->clearAllTplCache();
    }

    public function hookActionObjectSupplierDeleteAfter($params)
    {
        MpSecondaryMenu::deleteMenuFromItem(MpSecondaryMenu::MENU_TYPE_SUPPLIER, $params['object']->id);
        $this->clearAllTplCache();
    }

    public function hookActionObjectManufacturerDeleteAfter($params)
    {
        MpSecondaryMenu::deleteMenuFromItem(MpSecondaryMenu::MENU_TYPE_MANUFACTURER, $params['object']->id);
        $this->clearAllTplCache();
    }

    public function hookActionObjectProductUpdateAfter($params)
    {
        $this->clearAllTplCache();
    }

    public function hookActionObjectProductDeleteAfter($params)
    {
        MpSecondaryMenu::deleteMenuFromItem(MpSecondaryMenu::MENU_TYPE_PRODUCT, $params['object']->id);
        $this->clearAllTplCache();
    }

    public function hookActionObjectMetaDeleteAfter($params)
    {
        MpSecondaryMenu::deleteMenuFromItem(MpSecondaryMenu::MENU_TYPE_PAGE, $params['object']->id);
        $this->clearAllTplCache();
    }

    public function ajaxProcessRenderFormProperties()
    {
        $this->menuProController->renderFormProperties();
    }

    public function ajaxProcessSaveStyleLevel()
    {
        $this->menuProController->outputResult($this->menuProController->styleLevelController->processSave());
    }

    public function ajaxProcessDeleteStyleLevel()
    {
        $this->menuProController->outputResult($this->menuProController->styleLevelController->processDelete());
    }

    public function ajaxProcessDuplicateStyleLevel()
    {
        $this->menuProController->outputResult($this->menuProController->styleLevelController->processDuplicate());
    }

    public function ajaxProcessSearchAttachableItems()
    {
        $this->menuProController->secondaryMenuController->processSearchAttachableItems();
    }

    public function ajaxProcessGetAttachableItems()
    {
        $this->menuProController->outputResult(
            $this->menuProController->secondaryMenuController->processGetAttachableItems()
        );
    }

    public function ajaxProcessGetAttachableItemsCount()
    {
        $this->menuProController->outputResult(
            $this->menuProController->secondaryMenuController->processGetAttachableItemsCount()
        );
    }

    public function ajaxProcessGetAttachableCategory()
    {
        $this->menuProController->outputResult($this->menuProController->renderCategoriesTree());
    }

    public function ajaxProcessAddMenuItems()
    {
        $this->menuProController->outputResult($this->menuProController->secondaryMenuController->processAddItems());
    }

    public function ajaxProcessGetSecondaryMenuEditionForm()
    {
        $this->menuProController->outputResult($this->menuProController->secondaryMenuController->getEditionForm());
    }

    public function ajaxProcessUpdateSecondaryMenu()
    {
        $this->menuProController->outputResult($this->menuProController->secondaryMenuController->processUpdate());
    }

    public function ajaxProcessGetHtmlContentEditionContent()
    {
        $this->menuProController->outputResult(
            $this->menuProController->secondaryMenuController->processGetHtmlContentEditionContent()
        );
    }

    public function ajaxProcessSaveHtmlContent()
    {
        $this->menuProController->outputResult(
            $this->menuProController->secondaryMenuController->htmlContentController->processSave()
        );
    }

    public function ajaxProcessDeleteHtmlContent()
    {
        $this->menuProController->outputResult(
            $this->menuProController->secondaryMenuController->htmlContentController->processDelete()
        );
    }

    public function ajaxProcessStatusChangeHtmlContent()
    {
        $this->menuProController->outputResult(
            $this->menuProController->secondaryMenuController->htmlContentController->processStatusChange()
        );
    }

    public function ajaxProcessDeleteSecondaryMenu()
    {
        $this->menuProController->outputResult($this->menuProController->secondaryMenuController->processDelete());
    }

    public function ajaxProcessStatusChangeSecondaryMenu()
    {
        $this->menuProController->outputResult(
            $this->menuProController->secondaryMenuController->processStatusChange()
        );
    }

    public function ajaxProcessSortMenu()
    {
        $this->menuProController->outputResult($this->menuProController->secondaryMenuController->processSortMenu());
    }

    public function ajaxProcessGetPropertyValue()
    {
        $this->menuProController->outputResult($this->menuProController->cssPropertyController->processGetValue());
    }

    public function ajaxProcessGetStyle()
    {
        $this->menuProController->outputResult($this->menuProController->styleMenuController->processGetStyle());
    }

    public function smartyClearCache($template, $cache_id = null, $compile_id = null)
    {
        return $this->_clearCache($template, $cache_id, $compile_id);
    }

    public function smartyGetCacheId($name = null)
    {
        return $this->getCacheId($name);
    }

    public function clearAllTplCache()
    {
        $this->smartyClearCache('main_menu_footer_1.tpl');
        $this->smartyClearCache('main_menu_left_1.tpl');
        $this->smartyClearCache('main_menu_mega.tpl');
        $this->smartyClearCache('main_menu_right_1.tpl');
        $this->smartyClearCache('main_menu_side_simple.tpl');
        $this->smartyClearCache('main_menu_simple.tpl');
    }
    
    public function displayError($error)
    {
        if(_PS_VERSION_<'1.6'){
            $errors = is_array($error)?$error:array($error);
            $output = '<div class="module_error alert error"><ul>';
			foreach ($errors as $value) {
			    $output.='<li>'.$value.'</li>';
			}
		    $output .= '</ul></div>';
            $this->error = true;
            return $output;
        }else{
            return parent::displayError($error);
        }
    }
}
