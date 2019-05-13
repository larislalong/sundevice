<?php
/**
* 2007-2018 PrestaShop
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
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Function used to update your module from previous versions to the version 5.0.0,
 * Don't forget to create one file per version.
 */
function upgrade_module_5_0_0($module)
{
    Db::getInstance()->Execute('ALTER TABLE '._DB_PREFIX_.'seller ADD meta_description VARCHAR(255) NULL DEFAULT NULL');
    Db::getInstance()->Execute('ALTER TABLE '._DB_PREFIX_.'seller ADD meta_keywords VARCHAR(255) NULL DEFAULT NULL');
    Db::getInstance()->Execute('ALTER TABLE '._DB_PREFIX_.'seller ADD meta_title VARCHAR(128) NULL DEFAULT NULL');
    
    Configuration::updateValue('JMARKETPLACE_SHOW_MTA_DESCRIPTION', 0);
    Configuration::updateValue('JMARKETPLACE_SHOW_MTA_TITLE', 0);
    Configuration::updateValue('JMARKETPLACE_SHOW_MTA_KEYWORDS', 0);
    
    $languages = Language::getLanguages(false);
    $values = array();
    foreach ($languages as $lang) {
        $values['JMARKETPLACE_MAIN_ROUTE'][(int)$lang['id_lang']] = $module->l('jmarketplace');
        Configuration::updateValue('JMARKETPLACE_MAIN_ROUTE', $values['JMARKETPLACE_MAIN_ROUTE']);
    }
    
    $values = array();
    foreach ($languages as $lang) {
        $values['JMARKETPLACE_ROUTE_PRODUCTS'][(int)$lang['id_lang']] = $module->l('products');
        Configuration::updateValue('JMARKETPLACE_ROUTE_PRODUCTS', $values['JMARKETPLACE_ROUTE_PRODUCTS']);
    }
    
    $values = array();
    foreach ($languages as $lang) {
        $values['JMARKETPLACE_ROUTE_COMMENTS'][(int)$lang['id_lang']] = 'comments';
        Configuration::updateValue('JMARKETPLACE_ROUTE_COMMENTS', $values['JMARKETPLACE_ROUTE_COMMENTS']);
    }
    
    $module->createHook('displayMarketplaceWidget');
    $module->registerHook('registerGDPRConsent');
    $module->registerHook('actionDeleteGDPRCustomer');
    $module->registerHook('actionExportGDPRData');
    $module->registerHook('displayMyAccountBlock');
    $module->registerHook('displayMyAccountBlockfooter');
    
    return $module;
}
