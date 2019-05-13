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

use PrestaShop\PrestaShop\Adapter\ObjectPresenter;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;

include_once _PS_MODULE_DIR_ . 'menupro/classes/MpStyleMenu.php';
include_once _PS_MODULE_DIR_ . 'menupro/classes/MpIcon.php';
include_once _PS_MODULE_DIR_ . 'menupro/classes/MpHtmlContent.php';
include_once _PS_MODULE_DIR_ . 'menupro/classes/MpCSSPropertyMenu.php';

class MpSecondaryMenu extends ObjectModel
{
    const MENU_TYPE_CATEGORY = 1;

    const MENU_TYPE_PRODUCT = 2;

    const MENU_TYPE_CMS = 3;

    const MENU_TYPE_SUPPLIER = 4;

    const MENU_TYPE_MANUFACTURER = 5;

    const MENU_TYPE_PAGE = 6;

    const MENU_TYPE_CUSTOMISE = 7;

    const DISPLAY_STYLE_SIMPLE = 1;

    const DISPLAY_STYLE_COMPLEX = 2;

    const LINK_TYPE_INTERNAL = 1;

    const LINK_TYPE_EXTERNAL = 2;

    const SEARCH_BY_NAME = 1;

    const SEARCH_BY_ID = 2;

    const ITEMS_PER_PAGE = 10;

    const SORT_SUCCESS_CODE = 21;

    public $id_menupro_secondary_menu;

    public $id_menupro_main_menu;

    public $parent_menu;

    public $name;

    public $link;

    public $title;

    public $clickable;

    public $link_type;

    public $level;

    public $position;

    public $new_tab;

    public $use_custom_content;

    public $item_type;

    public $id_item;

    public $display_style;

    public $associate_all;

    public $active;

    public static $definition = array(
        'table' => 'menupro_secondary_menu',
        'multilang' => true,
        'primary' => 'id_menupro_secondary_menu',
        'fields' => array(
            'id_menupro_main_menu' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId',
                'required' => true
            ),
            'parent_menu' => array(
                'type' => (_PS_VERSION_>='1.6')?self::TYPE_INT:self::TYPE_NOTHING,
                'validate' => 'isUnsignedId',
                'allow_null' => true
            ),
            'clickable' => array(
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool'
            ),
            'link_type' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId',
                'required' => true
            ),
            'level' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId',
                'required' => true
            ),
            'position' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId',
                'required' => true
            ),
            'new_tab' => array(
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool'
            ),
            'use_custom_content' => array(
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool'
            ),
            'item_type' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId',
                'required' => true
            ),
            'id_item' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId'
            ),
            'display_style' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId',
                'required' => true
            ),
            'associate_all' => array(
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool'
            ),
            'name' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isCatalogName',
                'size' => 50
            ),
            'link' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isCatalogName',
                'size' => 50
            ),
            'title' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isCatalogName',
                'required' => true,
                'size' => 50
            ),
            'active' => array(
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool'
            )
        )
    );

    public static function getAttachableItems($idItem, $idLang, $idShop, $itemType, $excludeIds = '', $searchType = 0, $query = '', $start = 0, $limit = 0, $itemInfo = null)
    {
        $idLang = (int) $idLang;
        $idShop = (int) $idShop;
        $searchType = (int) $searchType;
        if ($itemInfo == null) {
            $itemInfo = self::getAttachableItemInfo($itemType);
        }
        $table = $itemInfo['table'];
        $nameField = $itemInfo['nameField'];
        $useLangTable = $itemInfo['useLangTable'];
        $useShopLang = $itemInfo['useShopLang'];
        $idField = 'id_' . $table;
        if ($searchType == 0) {
            $searchField = '';
        } else {
            $searchField = ($useLangTable) ? 'tl.' : '';
            $searchField .= ($searchType == self::SEARCH_BY_NAME) ? $nameField : $idField;
        }
        $whereOrAnd = (empty($searchField)) ? ' WHERE' : ' AND';
        $sql = 'SELECT t.' . pSQL($idField) . ' AS id, ' . pSQL($nameField) . ' AS name' .
                 (($useLangTable) ? ', tl.id_lang' : '') .
                 ' FROM ' . _DB_PREFIX_ . pSQL($table) . ' t ' . (($useLangTable) ? ('LEFT JOIN ' . _DB_PREFIX_ . pSQL($table) .
                 '_lang tl ON (t.' . pSQL($idField) . ' = tl.' . pSQL($idField) . ' AND tl.id_lang = ' . (int) $idLang) : '') .
                 (($useLangTable && $useShopLang) ? (' AND tl.id_shop=' . (int) $idShop) : '') .
                 (($useLangTable) ? ') ' : '') .
                 ((! empty($searchField)) ? (' WHERE (' . pSQL($searchField) . ' LIKE \'%' . pSQL($query) . '%\')') : '') .
                 ((! empty($excludeIds)) ? ($whereOrAnd . ' t.' . pSQL($idField) .
                 ' NOT IN (' . pSQL($excludeIds) . ') ') : '') .
                 ((! empty($idItem)) ? (' WHERE (t.' . pSQL($idField) . '=' . (int) $idItem) . ')' : '') .
                 ' ORDER BY t.' . pSQL($idField) . ' DESC ' .
                 ((! empty($limit)) ? ('LIMIT ' . (int) $start . ',' . (int) $limit) : '');
        $items = Db::getInstance()->executeS($sql);
        return $items;
    }

    public static function getAttachableItemsCount($itemType)
    {
        $itemInfo = self::getAttachableItemInfo($itemType);
        $table = $itemInfo['table'];
        $sql = 'SELECT COUNT(*) FROM ' . _DB_PREFIX_ . pSQL($table);
        return (int) Db::getInstance()->getValue($sql);
    }

    public static function getAttachableItemInfo($itemType)
    {
        $itemType = (int) $itemType;
        $table = '';
        $nameField = 'name';
        $useLangTable = true;
        $useShopLang = false;
        switch ($itemType) {
            case self::MENU_TYPE_CATEGORY:
                $table = 'category';
                $useShopLang = true;
                break;
            case self::MENU_TYPE_PRODUCT:
                $table = 'product';
                $useShopLang = true;
                break;
            case self::MENU_TYPE_CMS:
                $table = 'cms';
                $nameField = 'meta_title';
                if(_PS_VERSION_>='1.6'){
                    $useShopLang = true;
                }
                break;
            case self::MENU_TYPE_SUPPLIER:
                $table = 'supplier';
                $useLangTable = false;
                break;
            case self::MENU_TYPE_MANUFACTURER:
                $table = 'manufacturer';
                $useLangTable = false;
                break;
            case self::MENU_TYPE_PAGE:
                $nameField = 'page';
                $table = 'meta';
                $useLangTable = false;
                break;
        }
        return array(
            'nameField' => $nameField,
            'table' => $table,
            'useShopLang' => $useShopLang,
            'useLangTable' => $useLangTable
        );
    }

    public static function getAll($idMainMenu, $idParent, $idLang, $onlyActive)
    {
        $sql = 'SELECT '.($idLang ?'tl.*,':'').' t.* FROM ' . _DB_PREFIX_ .
                self::$definition['table'] . ' t ' . (($idLang) ? ('LEFT JOIN ' .
                 _DB_PREFIX_ . self::$definition['table'] . '_lang tl ON (t.' . self::$definition['primary'] . ' = tl.' .
                 self::$definition['primary'] . ' AND tl.id_lang = ' . (int) $idLang . ')') : '') .
                 ' WHERE (id_menupro_main_menu=' . (int) $idMainMenu . ') AND (parent_menu ' .
                 ((! empty($idParent)) ? ('=' . (int) $idParent) : 'IS NULL') . ')' .
                 (($onlyActive) ? ' AND (active=1)' : '') . ' ORDER BY position ASC ';
        $items = Db::getInstance()->executeS($sql);
        return $items;
    }

    public static function getMenuLevel($idMenu)
    {
        $menu = new MpSecondaryMenu((int) $idMenu);
        if (empty($menu->parent_menu)) {
            return 1;
        } else {
            return 1 + self::getMenuLevel($menu->parent_menu);
        }
    }

    public static function getLevelForNewMenu($idParent)
    {
        if (empty($idParent)) {
            return 1;
        } else {
            $menu = new MpSecondaryMenu((int) $idParent);
            return $menu->level + 1;
        }
    }

    public static function addItems($idMainMenu, $idParent, $idLang, $idShop, $items, $newItemName)
    {
        $result = array();
        $languages = Language::getLanguages();
        foreach ($items as $item) {
            $secondaryMenu = new MpSecondaryMenu();
            $secondaryMenu->id_menupro_icon = null;
            $secondaryMenu->id_menupro_main_menu = (int) $idMainMenu;
            $secondaryMenu->parent_menu = (int) $idParent;
            if (empty($secondaryMenu->parent_menu)) {
                $secondaryMenu->parent_menu = null;
            }
            $secondaryMenu->level = self::getLevelForNewMenu($idParent);
            $secondaryMenu->position = self::getItemsCount($idMainMenu, $idParent) + 1;
            $secondaryMenu->active = true;
            $secondaryMenu->clickable = true;
            $secondaryMenu->associate_all = false;
            $secondaryMenu->new_tab = false;
            $secondaryMenu->use_custom_content = false;
            $secondaryMenu->item_type = (int) $item['type'];
            $secondaryMenu->id_item = (int) $item['id'];
            $secondaryMenu->link_type = self::LINK_TYPE_INTERNAL;
            $secondaryMenu->display_style = self::DISPLAY_STYLE_SIMPLE;
            $itemInfo = self::getAttachableItemInfo((int) $item['type']);
            if ($secondaryMenu->item_type != self::MENU_TYPE_CUSTOMISE) {
                $attachableItemsWithName = self::getAttachableItems(
                    $item['id'],
                    $idLang,
                    $idShop,
                    (int) $item['type'],
                    '',
                    0,
                    '',
                    0,
                    0,
                    $itemInfo
                );
            } else {
                $secondaryMenu->id_item = 0;
                $attachableItemsWithName = array(
                    array(
                        'id_lang' => $idLang.'',
                        'name' => $newItemName . ' ' . ((int) $item['id'] + 1)
                    )
                );
            }
            $languagesAdded = array();
            $lastAddName = '';
            $lastAddIdLang = '0';
            foreach ($attachableItemsWithName as $itemWithName) {
                $currentIdLang = (((! isset($itemWithName['id_lang'])) || empty($itemWithName['id_lang'])) ?
                        $idLang.'' : $itemWithName['id_lang']);
                if ($lastAddIdLang != $currentIdLang) {
                    $secondaryMenu->name[$currentIdLang] = $itemWithName['name'];
                    $secondaryMenu->title[$currentIdLang] = $secondaryMenu->name[$currentIdLang];
                    $lastAddName = $secondaryMenu->name[$currentIdLang];
                    $languagesAdded[] = $currentIdLang;
                    $lastAddIdLang = $currentIdLang;
                }
            }
            foreach ($languages as $lang) {
                if (! in_array($lang['id_lang'], $languagesAdded)) {
                    $secondaryMenu->name[$lang['id_lang']] = $lastAddName;
                    $secondaryMenu->title[$lang['id_lang']] = $secondaryMenu->name[$lang['id_lang']];
                }
            }
            if ($secondaryMenu->add(true, true)) {
                MpStyleMenu::addDefault(
                    MpCSSPropertyMenu::MENU_TYPE_SECONDARY,
                    $secondaryMenu,
                    $secondaryMenu->name[$idLang]
                );
                $result[] = array(
                    'id' => $secondaryMenu->id,
                    'name' => $secondaryMenu->name[$idLang],
                    'level' => $secondaryMenu->level,
                    'position' => $secondaryMenu->position,
                    'parent_menu' => (int) $secondaryMenu->parent_menu,
                    'item_type' => $secondaryMenu->item_type,
                    'active' => (int) $secondaryMenu->active
                );
            }
        }
        return $result;
    }

    public static function getItemsCount($idMainMenu, $idParent)
    {
        $sql = 'SELECT count(*) FROM ' . _DB_PREFIX_ . self::$definition['table'] . ' t ' .
                 ' WHERE (id_menupro_main_menu=' . (int) $idMainMenu . ') AND (parent_menu ' .
                 ((! empty($idParent)) ? ('=' . (int) $idParent) : 'IS NULL') . ')';
        return (int) Db::getInstance()->getValue($sql);
    }

    public static function getById($idSecondaryMenu)
    {
        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . self::$definition['table'] . ' t ' . ' WHERE ' .
                 self::$definition['primary'] . '=' . (int) $idSecondaryMenu;
        return Db::getInstance()->getRow($sql);
    }

    public static function getByIdAsObject($idSecondaryMenu, $data = array())
    {
        if (empty($data)) {
            $data = self::getById($idSecondaryMenu);
        }
        $secondaryMenu = new MpSecondaryMenu();
        $secondaryMenu->id_menupro_main_menu = $data['id_menupro_main_menu'];
        $secondaryMenu->id = $data[self::$definition['primary']];
        $secondaryMenu->id_menupro_secondary_menu = $data[self::$definition['primary']];
        $secondaryMenu->parent_menu = $data['parent_menu'];
        $secondaryMenu->level = $data['level'];
        return $secondaryMenu;
    }

    public static function getMainMenuID($idSecondaryMenu)
    {
        return (int) self::getById($idSecondaryMenu)['id_menupro_main_menu'];
    }

    public static function getTopParent($idMenu)
    {
        $sql = 'SELECT ' . self::$definition['primary'] . ', parent_menu FROM ' . _DB_PREFIX_ .
                 self::$definition['table'] . ' WHERE ' . self::$definition['primary'] . ' = ' . (int) $idMenu;
        $row = Db::getInstance()->getRow($sql);
        if ((int) $row['parent_menu'] > 0) {
            return self::getTopParent($row['parent_menu']);
        } else {
            return $row[self::$definition['primary']];
        }
    }

    public static function decrementBrothersPosition($menu)
    {
        $sql = 'UPDATE ' . _DB_PREFIX_ . self::$definition['table'] . ' SET position=position-1 ' .
                 ' WHERE (id_menupro_main_menu=' . (int) $menu->id_menupro_main_menu . ') AND (parent_menu ' .
                 ((! empty($menu->parent_menu)) ? ('=' . (int) $menu->parent_menu) : 'IS NULL') .
                ') AND (position>' . (int) $menu->position . ')';
        return Db::getInstance()->execute($sql);
    }

    public static function updateSort($menuData, $idMainMenu)
    {
        $whereClause = ' (' . self::$definition['primary'] . '=' . (int) $menuData['id'] . ') AND (id_menupro_main_menu=' .
                 (int) $idMainMenu . ')';
        $sql = 'UPDATE ' . _DB_PREFIX_ . self::$definition['table'] . ' SET position=' . (int) $menuData['position'] .
                 ', level=' . (int) $menuData['level'] . ', parent_menu=' .
                 ((empty($menuData['parent'])) ? 'null' : (int) $menuData['parent']) .
                 ' WHERE' . $whereClause;
        return Db::getInstance()->execute($sql);
    }

    public static function buildMenuTree($idMainMenu, $idParent, $onlyActive, $headerTreeTpl, $footerTreeTpl, $context, $idLang, $front = false, $items = null, $defaultStyle = null, $parentStyleForContainer = '')
    {
        $output = '';
        $parentStyleForContainerArg = $parentStyleForContainer;
        if ($items === null) {
            $items = self::getAll($idMainMenu, $idParent, $idLang, $onlyActive);
        }
        if (! empty($items)) {
            $firstItems = true;
            $lastItems = false;
            $itemsCount = count($items);
            $usedStyle = array();
            if ($defaultStyle !== null) {
                $usedStyle[(int) MpStyleMenu::USABLE_STYLE_DEFAULT] = $defaultStyle;
            }
            for ($i = 0; $i < $itemsCount; $i ++) {
                $itemsParam = null;
                if ($front) {
                    $itemsParam = ((isset($items[$i]['sub_category'])) ?
                            array() :
                            self::getAll($idMainMenu, $items[$i][self::$definition['primary']], $idLang, $onlyActive));
                    if (($items[$i]['item_type'] == self::MENU_TYPE_CATEGORY) &&
                            ($items[$i]['associate_all']) &&
                            (! isset($items[$i]['sub_category']))) {
                        $idShop = $context->shop->id;
                        $subCategoriesMenu = self::getSubCategoriesAsMenu($items[$i], $idLang, $idShop);
                        if (empty($itemsParam)) {
                            $itemsParam = $subCategoriesMenu;
                        } else {
                            $itemsParam = array_merge($itemsParam, $subCategoriesMenu);
                        }
                    }
                    $items[$i]['has_children'] = ! empty($itemsParam);
                    if ($items[$i]['clickable']) {
                        $items[$i]['link'] = self::getMenuLink($items[$i], $context, $idLang);
                    }
                    $style = ((isset($items[$i]['sub_category'])) ?
                        $items[$i]['style_info'] :
                        MpStyleMenu::getForMenu(MpCSSPropertyMenu::MENU_TYPE_SECONDARY, $items[$i][self::$definition['primary']]));
                    if (isset($usedStyle[(int) $style['usable_style']])) {
                        $items[$i]['style'] = $usedStyle[(int) $style['usable_style']];
                    } else {
                        $items[$i]['style'] = MpStyleMenu::getAsString(
                            $style,
                            MpCSSPropertyMenu::MENU_TYPE_SECONDARY,
                            self::getByIdAsObject($items[$i][self::$definition['primary']], $items[$i])
                        );
                        if ($style['usable_style'] != MpStyleMenu::USABLE_STYLE_CUSTOMIZED) {
                            $usedStyle[(int) $style['usable_style']] = $items[$i]['style'];
                            if ($style['usable_style'] == MpStyleMenu::USABLE_STYLE_DEFAULT) {
                                $defaultStyle = $items[$i]['style'];
                            }
                        }
                    }
                    $items[$i]['parent_style_for_container'] = $parentStyleForContainer;
                    $parentStyleForContainerArg = $items[$i]['style']['for_container'];
                    if (($items[$i]['item_type'] == self::MENU_TYPE_CUSTOMISE) && $items[$i]['use_custom_content']) {
                        $idContent = MpHtmlContent::getIdForSecondaryMenu($items[$i][self::$definition['primary']]);
                        $htmlContent = new MpHtmlContent($idContent, $idLang);
                        $items[$i]['custom_content'] = $htmlContent->content;
                    }
                    if ($items[$i]['level'] == 2) {
                        $htmlContents = MpHtmlContent::getAll($idParent, true, $idLang);
                        foreach ($htmlContents as $htmlContent) {
                            $items[$i]['html_contents'][(int) $htmlContent['position']] = $htmlContent['content'];
                        }
                    }
                    $items[$i]['icon_css_class'] = MpIcon::getCssClassForSecondaryMenu(
                        $items[$i][self::$definition['primary']]
                    );
                    if ((int) $items[$i]['display_style'] == self::DISPLAY_STYLE_COMPLEX) {
                        if ($items[$i]['item_type'] == self::MENU_TYPE_PRODUCT) {
                            if (_PS_VERSION_ < '1.7') {
                                $productArray = array(
                                    self::getProductRow($items[$i]['id_item'], $idLang, $context)
                                );
                            } else {
                                $productArray = self::getProductRow17($items[$i]['id_item'], $idLang, $context);
                            }
                            $context->smarty->assign('complex_products', $productArray);
                        } elseif ($items[$i]['item_type'] == self::MENU_TYPE_CATEGORY) {
                            if (_PS_VERSION_ < '1.7') {
                                $category = new Category($items[$i]['id_item'], $idLang);
                            } else {
                                $category = self::getCategoryRow17($items[$i]['id_item'], $idLang, $context);
                            }
                            
                            $context->smarty->assign(array(
                                'complex_category' => $category
                            ));
                        }
                    }
                }
                $context->smarty->assign('secondaryMenu', $items[$i]);
                $context->smarty->assign('firstItems', $firstItems);
                $output .= $context->smarty->fetch($headerTreeTpl);
                if ((! $front) || (! isset($items[$i]['sub_category']))) {
                    $output .= self::buildMenuTree($idMainMenu, $items[$i][self::$definition['primary']], $onlyActive, $headerTreeTpl, $footerTreeTpl, $context, $idLang, $front, $itemsParam, $defaultStyle, $parentStyleForContainerArg);
                }
                $firstItems = false;
                $lastItems = ($i == ($itemsCount - 1));
                $context->smarty->assign('secondaryMenu', $items[$i]);
                $context->smarty->assign('lastItems', $lastItems);
                $output .= $context->smarty->fetch($footerTreeTpl);
            }
        }
        return $output;
    }

    public static function getMenuLink($secondaryMenu, $context, $idLang)
    {
        $idShop = $context->shop->id;
        switch ($secondaryMenu['item_type']) {
            case self::MENU_TYPE_CATEGORY:
                return $context->link->getCategoryLink($secondaryMenu['id_item'], null, $idLang, null, $idShop);
            case self::MENU_TYPE_PRODUCT:
                return $context->link->getProductLink($secondaryMenu['id_item'], null, null, null, $idLang, $idShop);
            case self::MENU_TYPE_CMS:
                return $context->link->getCMSLink($secondaryMenu['id_item'], null, null, $idLang, $idShop);
            case self::MENU_TYPE_SUPPLIER:
                return $context->link->getSupplierLink($secondaryMenu['id_item'], null, $idLang, $idShop);
            case self::MENU_TYPE_MANUFACTURER:
                return $context->link->getManufacturerLink($secondaryMenu['id_item'], null, $idLang, $idShop);
            case self::MENU_TYPE_PAGE:
                $meta = new Meta($secondaryMenu['id_item'], $idLang);
                return self::getMetaLink($meta, $idLang, $idShop, $context);
            default:
                if ($secondaryMenu['link_type'] == self::LINK_TYPE_INTERNAL) {
                    return $context->link->getPageLink('', null, $idLang, null, false, $idShop) . $secondaryMenu['link'];
                } else {
                    return $secondaryMenu['link'];
                }
        }
    }
    public static function getMetaLink($meta, $idLang, $idShop, $context)
    {
        $page = $meta->page;
        $moduleIdentifier = "module-";
        if(strpos($page, $moduleIdentifier)===0){
            $page = str_replace($moduleIdentifier, '', $page);
            $index = strpos($page, '-');
            $controller = '';
            if($index===false){
                $moduleName = $page;
            }else{
                $moduleName = Tools::substr($page, 0, $index);
                $start = Tools::strlen($moduleName) + 1;
                $controller = Tools::substr($page, $start);
            }
            $controller = empty($controller)?'default':$controller;
            return $context->link->getModuleLink($moduleName, $controller, array(), null, $idLang, $idShop);
        }else{
            return $context->link->getPageLink($meta->page, null, $idLang, null, false, $idShop);
        }
    }

    public static function getSubCategoriesAsMenu($secondaryMenu, $idLang, $idShop)
    {
        $subCategories = Category::getChildren($secondaryMenu['id_item'], $idLang, true, $idShop);
        $result = array();
        foreach ($subCategories as $category) {
            $menu = array();
            $menu['sub_category'] = true;
            $menu['name'] = $category['name'];
            $menu['id_item'] = $category['id_category'];
            $menu['item_type'] = self::MENU_TYPE_CATEGORY;
            $menu['level'] = (int) $secondaryMenu['level'] + 1;
            $menu['parent_menu'] = $secondaryMenu[self::$definition['primary']];
            $menu[self::$definition['primary']] = (int) $secondaryMenu[self::$definition['primary']] *
                     (int) $category['id_category'];
            $menu['id_menupro_main_menu'] = $secondaryMenu['id_menupro_main_menu'];
            $menu['title'] = $category['name'];
            $menu['display_style'] = self::DISPLAY_STYLE_SIMPLE;
            $menu['clickable'] = true;
            $menu['new_tab'] = false;
            $menu['associate_all'] = false;
            $menu['use_custom_content'] = false;
            $usableStyle = MpStyleMenu::getDefaultUsable(
                MpCSSPropertyMenu::MENU_TYPE_SECONDARY,
                self::getByIdAsObject($secondaryMenu[self::$definition['primary']], $secondaryMenu)
            );
            $menu['style_info'] = array(
                'usable_style' => $usableStyle,
                MpStyleMenu::$definition['primary'] => 0
            );
            $result[] = $menu;
        }
        return $result;
    }

    public static function deleteMenuFromItem($itemType, $idItem)
    {
        try {
            $sql = 'DELETE FROM ' . _DB_PREFIX_ . self::$definition['table'] . ' WHERE (item_type = ' . (int) $itemType .
                     ') AND (id_item = ' . (int) $idItem . ')';
            return Db::getInstance()->getRow($sql);
        } catch (Exception $e) {
            return false;
        }
    }

    public static function getProductRow($idProduct, $idLang, $context)
    {
        if(_PS_VERSION_>='1.6'){
            $groups = FrontController::getCurrentCustomerGroups();
            $sql_groups = count($groups) ? 'IN (' . implode(',', $groups) . ')' : '= 1';
            $sql = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity' .
                    (Combination::isFeatureActive() ? ', product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity, IFNULL(product_attribute_shop.`id_product_attribute`,0) id_product_attribute' : '') .
                    ', pl.`description`, pl.`description_short`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`,
			pl.`meta_title`, pl.`name`, pl.`available_now`, pl.`available_later`, image_shop.`id_image` id_image, il.`legend`, m.`name` AS manufacturer_name,
				DATEDIFF(
					product_shop.`date_add`,
					DATE_SUB(
						"' . date('Y-m-d') . ' 00:00:00",
						INTERVAL ' . (Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20) . ' DAY
					)
				) > 0 AS new' . ' FROM `' . _DB_PREFIX_ . 'product` p
			' . Shop::addSqlAssociation('product', 'p') . (Combination::isFeatureActive() ? 'LEFT JOIN `' . _DB_PREFIX_ .
			        'product_attribute_shop` product_attribute_shop
						ON (p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop=' .
			        (int) $context->shop->id . ')' : '') . '
			LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl
				ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = ' . (int) $idLang . Shop::addSqlRestrictionOnLang('pl') . ')
				LEFT JOIN `' . _DB_PREFIX_ . 'image_shop` image_shop
					ON (image_shop.`id_product` = p.`id_product` AND image_shop.cover=1 AND image_shop.id_shop=' .
					(int) $context->shop->id . ')
			LEFT JOIN `' . _DB_PREFIX_ . 'image_lang` il
				ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = ' . (int) $idLang . ')
			LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` m
				ON (m.`id_manufacturer` = p.`id_manufacturer`)
			' . Product::sqlStock('p', 0);
					
					if (Group::isFeatureActive()) {
					    $sql .= 'JOIN `' . _DB_PREFIX_ . 'category_product` cp ON (p.id_product = cp.id_product)';
					    if (Group::isFeatureActive()) {
					        $sql .= 'JOIN `' . _DB_PREFIX_ .
					        'category_group` cg ON (cp.`id_category` = cg.`id_category` AND cg.`id_group` ' . $sql_groups .
					        ')';
					    }
					}
        }else{
            $sql = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, MAX(product_attribute_shop.id_product_attribute) id_product_attribute, product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity, pl.`description`, pl.`description_short`, pl.`available_now`,
					pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, MAX(image_shop.`id_image`) id_image,
					il.`legend`, m.`name` AS manufacturer_name, cl.`name` AS category_default,
					DATEDIFF(product_shop.`date_add`, DATE_SUB(NOW(),
					INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).'
						DAY)) > 0 AS new, product_shop.price AS orderprice
				FROM `'._DB_PREFIX_.'product` p '.Shop::addSqlAssociation('product', 'p').'
				LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa
				ON (p.`id_product` = pa.`id_product`)
				'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.`default_on` = 1').'
				'.Product::sqlStock('p', 'product_attribute_shop', false, $context->shop).'
				LEFT JOIN `'._DB_PREFIX_.'category_lang` cl
					ON (product_shop.`id_category_default` = cl.`id_category`
					AND cl.`id_lang` = '.(int)$idLang.Shop::addSqlRestrictionOnLang('cl').')
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
					ON (p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)$idLang.Shop::addSqlRestrictionOnLang('pl').')
				LEFT JOIN `'._DB_PREFIX_.'image` i
					ON (i.`id_product` = p.`id_product`)'.
					Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
				LEFT JOIN `'._DB_PREFIX_.'image_lang` il
					ON (image_shop.`id_image` = il.`id_image`
					AND il.`id_lang` = '.(int)$idLang.')
				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m
					ON m.`id_manufacturer` = p.`id_manufacturer`';
        }
        
        
        $sql .= ' WHERE p.`id_product` = ' . (int) $idProduct .
                 ' AND product_shop.`active` = 1 AND product_shop.`visibility` IN ("both", "catalog")';
        
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($sql);
        return Product::getProductProperties($idLang, $result, $context);
    }

    public static function getCategoryRow17($idCategory, $idLang, $context)
    {
        $category = new Category($idCategory, $idLang);
        $objectPresenter = new ObjectPresenter();
        $categorResult = $objectPresenter->present($category);
        $retriever = new ImageRetriever($context->link);
        $categorResult['image'] = $retriever->getImage($category, $category->id_image);
        
        $categorResult['url'] = $context->link->getCategoryLink($category->id, $category->link_rewrite);
        
        return $categorResult;
    }

    public static function getProductRow17($idProduct, $idLang, $context)
    {
        $productRow = self::getProductRow($idProduct, $idLang, $context);
        $product = (new ProductAssembler($context))->assembleProduct($productRow);
        $productFactory = new ProductPresenterFactory($context, new TaxConfiguration());
        $productPresentationSettings = $productFactory->getPresentationSettings();
        $productPresenter = $productFactory->getPresenter();
        
        return $productPresenter->present($productPresentationSettings, $product, $context->language);
    }
    
    public function toggleStatus()
    {
        if(_PS_VERSION_>='1.6'){
            return parent::toggleStatus();
        }else{
            $this->setFieldsToUpdate(array('active' => true));
            $this->active = !(int)$this->active;
            return $this->update(true);
        }
    }
}
