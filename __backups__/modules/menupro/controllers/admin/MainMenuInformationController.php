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
include_once _PS_MODULE_DIR_ . 'menupro/classes/MpTools.php';
include_once _PS_MODULE_DIR_ . 'menupro/classes/MpStyleMenu.php';
include_once _PS_MODULE_DIR_ . 'menupro/classes/MpCSSPropertyMenu.php';

class MainMenuInformationController
{

    public $module;

    public function __construct($module, $context)
    {
        $this->module = $module;
        $this->context = $context;
    }

    public function getHooksToExclude()
    {
        return array(
            'displayBackOfficeHeader',
            (_PS_VERSION_>='1.6')?'Header':'displayHeader',
            'actionObjectCategoryUpdateAfter',
            'actionObjectCategoryDeleteAfter',
            'actionObjectCategoryAddAfter',
            'actionObjectProductUpdateAfter',
            'actionObjectProductDeleteAfter',
            'actionObjectCmsDeleteAfter',
            'actionObjectSupplierDeleteAfter',
            'actionObjectManufacturerDeleteAfter',
            'actionObjectMetaDeleteAfter'
        );
    }

    public function getSelectableHook()
    {
        $list = MpMainMenu::getModuleHook($this->module->id);
        $toExcludeList = $this->getHooksToExclude();
        $options = array();
        $options[] = array(
            'id' => 0,
            'name' => $this->module->l('none', __CLASS__)
        );
        $lastHookId = - 1;
        foreach ($list as $value) {
            if (($value['id_hook'] != $lastHookId) && (! in_array($value['name'], $toExcludeList))) {
                $options[] = array(
                    'id' => $value['id_hook'],
                    'name' => $value['name']
                );
            }
            $lastHookId = $value['id_hook'];
        }
        return $options;
    }

    public function getMenuTypeOptions($hookPreferences, $idHook = 0)
    {
        $options = array();
        $idHook = (int) $idHook;
        if (! isset($hookPreferences[$idHook])) {
            $idHook = 0;
        }
        foreach ($hookPreferences[$idHook]['menu_type_list'] as $key => $value) {
            $options[] = array(
                'id' => $key,
                'name' => $value['label']
            );
        }
        return $options;
    }

    public function getMenuTypesLabels()
    {
        $labels = array(
            MpMainMenu::MENU_TYPE_MEGA => $this->module->l('Mega menu', __CLASS__),
            MpMainMenu::MENU_TYPE_SIMPLE => $this->module->l('Simple menu', __CLASS__),
            MpMainMenu::MENU_TYPE_SIDE_SIMPLE => $this->module->l('Left or right simple menu', __CLASS__),
            MpMainMenu::MENU_TYPE_LEFT_1 => $this->module->l('Mega menu left', __CLASS__),
            MpMainMenu::MENU_TYPE_RIGHT_1 => $this->module->l('Mega menu right', __CLASS__),
            MpMainMenu::MENU_TYPE_FOOTER_1 => $this->module->l('Footer menu', __CLASS__)
        );
        return $labels;
    }

    public function getForm($hookPreferences, $idHook = 0)
    {
        $form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->module->l('Information', __CLASS__)
                ),
                'input' => array(
                    array(
                        'col' => 6,
                        'type' => 'text',
                        'desc' => $this->module->l('Enter a valid name', __CLASS__),
                        'name' => 'MENUPRO_MAIN_MENU_NAME',
                        'label' => $this->module->l('Name', __CLASS__),
                        'required' => true,
                        'lang' => true
                    ),
                    array(
                        'col' => 6,
                        'type' => 'select',
                        'label' => $this->module->l('Hook', __CLASS__),
                        'name' => 'MENUPRO_MAIN_MENU_HOOK',
                        'desc' => $this->module->l('Select a hook', __CLASS__),
                        'required' => true,
                        'options' => array(
                            'query' => $this->getSelectableHook(),
                            'id' => 'id',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'col' => 3,
                        'type' => 'select',
                        'label' => $this->module->l('Menu type', __CLASS__),
                        'name' => 'MENUPRO_MAIN_MENU_TYPE',
                        'desc' => $this->module->l('select a menu type', __CLASS__),
                        'required' => true,
                        'options' => array(
                            'query' => $this->getMenuTypeOptions($hookPreferences, $idHook),
                            'id' => 'id',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'col' => 6,
                        'type' => (_PS_VERSION_>='1.6')?'switch':'radio',
                        'class' => (_PS_VERSION_>='1.6')?'':'t',
                        'label' => $this->module->l('Show search bar', __CLASS__),
                        'name' => 'MENUPRO_MAIN_MENU_SHOW_SEARCH_BAR',
                        'is_bool' => true,
                        'desc' => $this->module->l('Show search bar on menu', __CLASS__),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->module->l('Enabled', __CLASS__)
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->module->l('Disabled', __CLASS__)
                            )
                        )
                    ),
                    array(
                        'col' => 6,
                        'type' => (_PS_VERSION_>='1.6')?'switch':'radio',
                        'class' => (_PS_VERSION_>='1.6')?'':'t',
                        'label' => $this->module->l('Enabled', __CLASS__),
                        'name' => 'MENUPRO_MAIN_MENU_ACTIVE',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->module->l('Enabled', __CLASS__)
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->module->l('Disabled', __CLASS__)
                            )
                        )
                    )
                )
            )
        );
        if (Shop::isFeatureActive()) {
            $form['form']['input'][] = array(
                'type' => 'shop',
                'label' => $this->module->l('Shop association', __CLASS__),
                'name' => 'checkBoxShopAsso'
            );
        }
        if (_PS_VERSION_>='1.6') {
            $form['form']['submit'] = array(
                'title' => $this->module->l('Save', __CLASS__)
            );
            $form['form']['buttons'] = array(
                array(
                    'href' => $this->module->getModuleHome(),
                    'title' => $this->module->l('Back to list', __CLASS__),
                    'icon' => 'process-icon-back'
                ),
                'save_and_stay' => array(
                    'name' => 'staymainmemu',
                    'type' => 'submit',
                    'title' => $this->module->l('Save and stay', __CLASS__),
                    'class' => 'btn btn-default pull-right btnSaveAndStayMenu',
                    'icon' => 'process-icon-save'
                )
            );
        }
        return $form;
    }

    public function getValidationErrors($values)
    {
        $errors = array();
        if (MpTools::isMultilangFieldEmpty($values, 'MENUPRO_MAIN_MENU_NAME')) {
            $errors[] = $this->module->l('Please Enter a menu name', __CLASS__);
        }
        if (! is_numeric($values['MENUPRO_MAIN_MENU_TYPE']) || ((int) $values['MENUPRO_MAIN_MENU_TYPE'] < 0)) {
            $errors[] = $this->l('Please select a menu type', __CLASS__);
        }
        if (empty($values['MENUPRO_MAIN_MENU_SHOPS'])) {
            $errors[] = $this->module->l('You must select at least a shop', __CLASS__);
        } else {
            $menusWithSameHook = MpMainMenu::getMenuForHook(
                $values['MENUPRO_MAIN_MENU_HOOK'],
                Tools::getValue('id_menupro_main_menu', 0),
                true
            );
            $exist = false;
            $idLang = $this->context->language->id;
            foreach ($menusWithSameHook as $menu) {
                foreach ($values['MENUPRO_MAIN_MENU_SHOPS'] as $shop) {
                    if (in_array($shop, $menu['shops'])) {
                        $exist = true;
                        if (_PS_VERSION_>='1.6') {
                            $shop = new Shop((int) $shop, $idLang);
                        }else{
                            $shop = new Shop((int) $shop);
                        }
                        $errors[] = sprintf(
                            $this->module->l(
                                'Another menu is already associated with this hook for shop %s',
                                __CLASS__
                            ),
                            $shop->name
                        );
                    }
                }
                if ($exist) {
                    break;
                }
            }
        }
        $menuExistForHook = MpMainMenu::isMenuExistForHook(
            $values['MENUPRO_MAIN_MENU_HOOK'],
            Tools::getValue('id_menupro_main_menu', 0)
        );
        if ($menuExistForHook) {
            $errors[] = $this->module->l('Another menu is already associated with this hook', __CLASS__);
        }
        return $errors;
    }

    public function getFormPostedValues($languages, $formSubmitted = false)
    {
        $active = Tools::getValue('MENUPRO_MAIN_MENU_ACTIVE');
        if ($formSubmitted) {
            $name = array();
            foreach ($languages as $language) {
                $language = (object) $language;
                if (Tools::getIsset('MENUPRO_MAIN_MENU_NAME_' . $language->id_lang)) {
                    $name[$language->id_lang] = Tools::getValue('MENUPRO_MAIN_MENU_NAME_' . $language->id_lang);
                }
            }
        } else {
            $name = null;
            $active = true;
        }
        return array(
            'MENUPRO_MAIN_MENU_NAME' => $name,
            'MENUPRO_MAIN_MENU_HOOK' => Tools::getValue('MENUPRO_MAIN_MENU_HOOK'),
            'MENUPRO_MAIN_MENU_SHOW_SEARCH_BAR' => Tools::getValue('MENUPRO_MAIN_MENU_SHOW_SEARCH_BAR'),
            'MENUPRO_MAIN_MENU_TYPE' => Tools::getValue('MENUPRO_MAIN_MENU_TYPE'),
            'MENUPRO_NUMBER_MENU_PER_LINE' => Tools::getValue('MENUPRO_NUMBER_MENU_PER_LINE'),
            'MENUPRO_NUMBER_MENU_PER_LINE_DEFAULT' => Tools::getValue('MENUPRO_NUMBER_MENU_PER_LINE_DEFAULT'),
            'MENUPRO_MAIN_MENU_ACTIVE' => $active,
            'MENUPRO_MAIN_MENU_SHOPS' => $this->getSelectedAssoShop(MpMainMenu::$definition['table'])
        );
    }

    /**
     * call a controller to submit form and retrieve possible errors
     *
     * @param MpMainMenu $mainMenu
     *            the controller class name
     * @return array
     */
    public function getSavedValues($mainMenu)
    {
        return array(
            'MENUPRO_MAIN_MENU_NAME' => $mainMenu->name,
            'MENUPRO_MAIN_MENU_HOOK' => $mainMenu->hook,
            'MENUPRO_MAIN_MENU_SHOW_SEARCH_BAR' => $mainMenu->show_search_bar,
            'MENUPRO_MAIN_MENU_TYPE' => $mainMenu->menu_type,
            'MENUPRO_NUMBER_MENU_PER_LINE' => $mainMenu->number_menu_per_ligne,
            'MENUPRO_MAIN_MENU_ACTIVE' => $mainMenu->active,
            'MENUPRO_NUMBER_MENU_PER_LINE_DEFAULT' => ($mainMenu->number_menu_per_ligne == 0) ? true : false
        );
    }

    public function processSave($values, $idMainMenu = 0)
    {
        $errors = $this->getValidationErrors($values);
        $mainMenu = null;
        if (empty($errors)) {
            $mainMenu = new MpMainMenu();
            $mainMenu->id = (int) $idMainMenu;
            $mainMenu->name = MpTools::fillMultilangEmptyFields($values['MENUPRO_MAIN_MENU_NAME']);
            $mainMenu->hook = (int) $values['MENUPRO_MAIN_MENU_HOOK'];
            $mainMenu->show_search_bar = (bool) $values['MENUPRO_MAIN_MENU_SHOW_SEARCH_BAR'];
            $mainMenu->menu_type = (int) $values['MENUPRO_MAIN_MENU_TYPE'];
            $mainMenu->active = (int) $values['MENUPRO_MAIN_MENU_ACTIVE'];
            $mainMenu->number_menu_per_ligne = 0;
            if ($mainMenu->save()) {
                if (! $this->updateAssoShop($mainMenu->id, $values['MENUPRO_MAIN_MENU_SHOPS'])) {
                    $errors[] = $this->module->l(
                        'An error occurred while associating this main menu to shops',
                        __CLASS__
                    );
                }
            } else {
                $errors[] = $this->module->l('An error occurred while saving main menu', __CLASS__);
            }
        }
        return array(
            'errors' => $errors,
            'menu' => $mainMenu
        );
    }

    protected function getSelectedAssoShop($table)
    {
        if (! Shop::isFeatureActive()) {
            return array(
                $this->context->shop->id
            );
        }
        
        $shops = Shop::getShops(true, null, true);
        if (count($shops) == 1 && isset($shops[0])) {
            return array(
                $shops[0],
                'shop'
            );
        }
        
        $assos = array();
        if (Tools::isSubmit('checkBoxShopAsso_' . $table)) {
            foreach (Tools::getValue('checkBoxShopAsso_' . $table) as $id_shop => $value) {
                $assos[] = (int) $id_shop;
            }
        } elseif (Shop::getTotalShops(false) == 1) {
            // if we do not have the checkBox multishop, we can have an admin
            // with only
            // one shop and being in multishop
            $assos[] = (int) Shop::getContextShopID();
        }
        return $assos;
    }

    /**
     * Update the associations of shops
     *
     * @param int $id_object
     * @return bool|void
     * @throws PrestaShopDatabaseException
     */
    protected function updateAssoShop($id_object, $assos_data)
    {
        $table = MpMainMenu::$definition['table'];
        $identifier = MpMainMenu::$definition['primary'];
        
        // Get list of shop id we want to exclude from asso deletion
        $exclude_ids = $assos_data;
        foreach (Db::getInstance()->executeS('SELECT id_shop FROM ' . _DB_PREFIX_ . 'shop') as $row) {
            if (! $this->context->employee->hasAuthOnShop($row['id_shop'])) {
                $exclude_ids[] = $row['id_shop'];
            }
        }
        $where = '`' . bqSQL($identifier) . '` = ' . (int) $id_object .
        ($exclude_ids ? ' AND id_shop NOT IN (' . implode(', ', array_map('intval', $exclude_ids)) . ')' : '');
        Db::getInstance()->delete($table . '_shop', $where);
        
        $insert = array();
        foreach ($assos_data as $id_shop) {
            $insert[] = array(
                $identifier => (int) $id_object,
                'id_shop' => (int) $id_shop
            );
        }
        return Db::getInstance()->insert($table . '_shop', $insert, false, true, Db::INSERT_IGNORE);
    }
}
