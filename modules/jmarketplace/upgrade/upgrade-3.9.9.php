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
 * Function used to update your module from previous versions to the version 3.9.9,
 * Don't forget to create one file per version.
 */
function upgrade_module_3_9_9($module)
{
    $languages = Language::getLanguages();
    $meta = new Meta();
    $meta->page = 'module-jmarketplace-editcarrier';
    $meta->configurable = 1;

    foreach ($languages as $lang) {
        $meta->title[$lang['id_lang']] = 'JA Marketplace - Edit carrier';
        $meta->description[$lang['id_lang']] = 'Edit carrier.';
        $meta->url_rewrite[$lang['id_lang']] = 'editcarrier';
    }

    $meta->save();
    
    if (version_compare(_PS_VERSION_, '1.7', '<')) {
        $id_meta = $meta->id;
        $theme_meta_value = array();

        if ($id_meta) {
            $themes = Theme::getThemes();
            foreach ($themes as $theme) {
                $theme_meta_value[] = array(
                    'id_theme' => (int)$theme->id,
                    'id_meta' => (int)$id_meta,
                    'left_column' => (int)$theme->default_left_column,
                    'right_column' => (int)$theme->default_right_column
                );
            }

            if (count($theme_meta_value) > 0) {
                Db::getInstance()->insert('theme_meta', $theme_meta_value);
            }
        }
    }
    
    return $module;
}
