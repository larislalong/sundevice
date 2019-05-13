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
include_once _PS_MODULE_DIR_ . 'menupro/classes/MpMainMenu.php';
include_once _PS_MODULE_DIR_ . 'menupro/classes/MpSecondaryMenu.php';
include_once _PS_MODULE_DIR_ . 'menupro/classes/MpStyleMenu.php';
include_once _PS_MODULE_DIR_ . 'menupro/classes/MpHtmlContent.php';

class MenuProFrontController
{
    public $module;

    public $cssPropertyController;

    public $mainMenuController;

    public $secondaryMenuController;

    public $styleLevelController;

    public $styleMenuController;

    public $context;

    public $local_path;

    public function __construct($module, $context, $local_path, $_path, $file)
    {
        $this->module = $module;
        $this->context = $context;
        $this->local_path = $local_path;
        $this->_path = $_path;
        $this->file = $file;
        $this->cssPropertyController = new CSSPropertyController($this->module, $this->context, $this->local_path);
        $this->mainMenuController = new MainMenuController($this->module, $this->context, $this->local_path);
        $this->secondaryMenuController = new SecondaryMenuController($this->module, $this->context, $this->local_path);
        $this->styleLevelController = new StyleLevelController($module, $context, $local_path);
        $this->styleMenuController = new StyleMenuController($module, $context, $local_path);
    }

    public function renderMenu($hookName)
    {
        $idHook = Hook::getIdByName($hookName);
        if (empty($idHook)) {
            return '';
        } else {
            $idLang = $this->context->language->id;
            $idShop = $this->context->shop->id;
            $mainMenu = MpMainMenu::getMenuForHook($idHook, 0, false, $idShop, $idLang);
            if ((! empty($mainMenu)) && ((bool) $mainMenu['active'])) {
                $hookPreferences = MpMainMenu::getHookPreferences();
                $tplVar = self::getTplVar($mainMenu, $idHook, $hookPreferences);
                $cacheId = $this->getCacheId($mainMenu[MpMainMenu::$definition['primary']]);
                
                if (! $this->module->isCached($tplVar['mainMenuTpl'], $cacheId)) {
                    $style = MpStyleMenu::getForMenu(
                        MpCSSPropertyMenu::MENU_TYPE_MAIN,
                        $mainMenu[MpMainMenu::$definition['primary']]
                    );
                    $menuObject = new MpMainMenu();
                    $menuObject->id = $mainMenu[MpMainMenu::$definition['primary']];
                    $menuObject->id_menupro_main_menu = $mainMenu[MpMainMenu::$definition['primary']];
                    $mainMenu['style'] = MpStyleMenu::getAsString(
                        $style,
                        MpCSSPropertyMenu::MENU_TYPE_MAIN,
                        $menuObject
                    );
                    $defaultStyle = null;
                    $mainMenu['id'] = $mainMenu[MpMainMenu::$definition['primary']];
                    $htmlContentPositionsConst = array(
                        'TOP' => MpHtmlContent::POSITION_TOP,
                        'LEFT' => MpHtmlContent::POSITION_LEFT,
                        'DOWN' => MpHtmlContent::POSITION_DOWN,
                        'RIGHT' => MpHtmlContent::POSITION_RIGHT
                    );
                    $menuTypesConst = array(
                        'CATEGORY' => MpSecondaryMenu::MENU_TYPE_CATEGORY,
                        'PRODUCT' => MpSecondaryMenu::MENU_TYPE_PRODUCT,
                        'CMS' => MpSecondaryMenu::MENU_TYPE_CMS,
                        'SUPPLIER' => MpSecondaryMenu::MENU_TYPE_SUPPLIER,
                        'MANUFACTURER' => MpSecondaryMenu::MENU_TYPE_MANUFACTURER,
                        'PAGE' => MpSecondaryMenu::MENU_TYPE_PAGE,
                        'CUSTOMISE' => MpSecondaryMenu::MENU_TYPE_CUSTOMISE
                    );
                    $displayStylesConst = array(
                        'SIMPLE' => MpSecondaryMenu::DISPLAY_STYLE_SIMPLE,
                        'COMPLEX' => MpSecondaryMenu::DISPLAY_STYLE_COMPLEX
                    );
                    if ($style['usable_style'] == MpStyleMenu::USABLE_STYLE_DEFAULT) {
                        $defaultStyle = $mainMenu['style'];
                    }
                    if ($mainMenu['show_search_bar']) {
                        $this->context->smarty->assign('searchAction', $this->context->link->getPageLink('search'));
                    }
                    if (_PS_VERSION_ < '1.7') {
                        $categoryTemplate = $this->local_path . 'views/templates/hook/category_complex.tpl';
                        $this->context->smarty->assign(array(
                            'productComplexTemplates' => $this->local_path . 'views/templates/hook/product_complex.tpl',
                            'categoryComplexTemplates' => $categoryTemplate
                        ));
                    }
                    
                    $elementTemplate = $this->local_path . 'views/templates/hook/display_element.tpl';
                    $this->context->smarty->assign(array(
                        'displayElementTemplate' => $elementTemplate,
                        'htmlContentPositionsConst' => $htmlContentPositionsConst,
                        'menuTypesConst' => $menuTypesConst,
                        'ps_version' => _PS_VERSION_,
                        'displayStylesConst' => $displayStylesConst,
                        'mainMenu' => $mainMenu
                    ));
                    $this->context->smarty->assign(
                        'secondaryMenuContent',
                        MpSecondaryMenu::buildMenuTree(
                            $mainMenu[MpMainMenu::$definition['primary']],
                            0,
                            true,
                            $tplVar['headerSecondaryMenuTpl'],
                            $tplVar['footerSecondaryMenuTpl'],
                            $this->context,
                            $idLang,
                            true,
                            null,
                            $defaultStyle,
                            $mainMenu['style']['for_container']
                        )
                    );
                }
                return $this->module->display($this->file, $tplVar['mainMenuTpl'], $cacheId);
            } else {
                return '';
            }
        }
    }

    public function getTplVar($mainMenu, $idHook, $hookPreferences)
    {
        $result = array();
        $result['mainMenuTpl'] = 'main_menu';
        $result['headerSecondaryMenuTpl'] = $this->local_path . 'views/templates/hook/header_secondary_menu';
        $result['footerSecondaryMenuTpl'] = $this->local_path . 'views/templates/hook/footer_secondary_menu';
        $idHook = (int) $idHook;
        if (! isset($hookPreferences[(int) $idHook])) {
            $idHook = 0;
        }
        
        if ($hookPreferences[(int) $idHook]['need_menu_type']) {
            $mainMenuType = (int) $mainMenu['menu_type'];
            $result['mainMenuTpl'] .= '_' . $hookPreferences[$idHook]['menu_type_list'][$mainMenuType]['file_suffix'];
            $result['headerSecondaryMenuTpl'] .= '_' .
                     $hookPreferences[$idHook]['menu_type_list'][$mainMenuType]['file_suffix'];
            $result['footerSecondaryMenuTpl'] .= '_' .
                     $hookPreferences[$idHook]['menu_type_list'][$mainMenuType]['file_suffix'];
        } else {
            $result['mainMenuTpl'] .= '_' . $hookPreferences[$idHook]['preferred_file_suffix'];
            $result['headerSecondaryMenuTpl'] .= '_' . $hookPreferences[$idHook]['preferred_file_suffix'];
            $result['footerSecondaryMenuTpl'] .= '_' . $hookPreferences[$idHook]['preferred_file_suffix'];
        }
        $result['mainMenuTpl'] .= '.tpl';
        $result['headerSecondaryMenuTpl'] .= '.tpl';
        $result['footerSecondaryMenuTpl'] .= '.tpl';
        return $result;
    }

    public function getCacheId($idMenu)
    {
        return $this->module->smartyGetCacheId($this->module->name . (int) $idMenu);
    }
}
