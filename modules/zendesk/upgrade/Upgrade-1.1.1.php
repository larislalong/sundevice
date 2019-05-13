<?php
/**
 * Copyright (C) 2015-2018 Zendesk - All Rights Reserved
 *
 *  @author    Presta-Module
 *  @author    202 ecommerce
 *  @copyright 2009-2016 Presta-Module
 *  @copyright 2017-2019 202 ecommerce
 *  @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

function upgrade_module_1_1_1($module)
{
    /* Uninstall Tabs */
    $tab = new Tab((int)Tab::getIdFromClassName('AdminZendesk'));
    $tab->delete();

    $sql = "SELECT id_tab FROM " . _DB_PREFIX_ . "tab 
			WHERE class_name = 'AdminCustomers'";
    $id_parent = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
    if ($id_parent <= 0) {
        return false;
    }

    /* Install Tabs */
    $tab = new Tab();
    foreach (Language::getLanguages(true) as $language) {
        $tab->name[(int)$language['id_lang']] = 'Zendesk';
    }
    $tab->class_name = 'AdminZendesk';
    $tab->id_parent = $id_parent;
    $tab->module = 'zendesk';
    $tab->add();
    return true;
}
