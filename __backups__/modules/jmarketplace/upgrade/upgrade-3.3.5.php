<?php
/**
* 2007-2016 PrestaShop
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
*  @copyright 2007-2016 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Function used to update your module from previous versions to the version 3.3.5,
 * Don't forget to create one file per version.
 */
function upgrade_module_3_3_5($module)
{
    $metas = array(
        array(
            'page' => 'module-jmarketplace-selleraccount',
            'configurable' => 1,
            'title' => 'JA Marketplace - Seller Account',
            'description' => 'This is page your seller account.',
            'url_rewrite' => 'seller-account',
        ),
    );

    $languages = Language::getLanguages();
    $id_metas = array();

    foreach ($metas as $m) {
        $meta = new Meta();
        $meta->page = (string)$m['page'];
        $meta->configurable = (int)$m['configurable'];

        foreach ($languages as $lang) {
            $meta->title[$lang['id_lang']] = (string)$m['title'];
            $meta->description[$lang['id_lang']] = (string)$m['page'];
            $meta->url_rewrite[$lang['id_lang']] = (string)$m['url_rewrite'];
        }
        
        $meta->save();
        $id_metas[] = $meta->id;
    }
    
    $theme_meta_value = array();
    
    if (count($id_metas) > 0) {
        $themes = Theme::getThemes();
        foreach ($themes as $theme) {
            foreach ($id_metas as $id_meta) {
                $theme_meta_value[] = array(
                    'id_theme' => (int)$theme->id,
                    'id_meta' => (int)$id_meta,
                    'left_column' => (int)$theme->default_left_column,
                    'right_column' => (int)$theme->default_right_column
                );
            }
        }

        if (count($theme_meta_value) > 0) {
            Db::getInstance()->insert('theme_meta', $theme_meta_value);
        }
    }
    
    $module->createHook('displayMarketplaceMenuOptions');
    $module->createHook('displayMarketplaceWidget');
    
    $module->removeThemeColumnByPage('module-jmarketplace-selleraccount');
    $module->removeThemeColumnByPage('module-jmarketplace-addproduct');
    $module->removeThemeColumnByPage('module-jmarketplace-editproduct');
    $module->removeThemeColumnByPage('module-jmarketplace-editseller');
    $module->removeThemeColumnByPage('module-jmarketplace-sellermessages');
    $module->removeThemeColumnByPage('module-jmarketplace-sellerorders');
    $module->removeThemeColumnByPage('module-jmarketplace-sellerpayment');
    $module->removeThemeColumnByPage('module-jmarketplace-sellerproducts');
    
    Configuration::updateValue('JMARKETPLACE_MENU_OPTIONS', 0);
    Configuration::updateValue('JMARKETPLACE_MENU_TOP', 1);

    return $module;
}
