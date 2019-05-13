<?php
/**
* 2007-2014 PrestaShop
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
*  @copyright 2007-2014 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Function used to update your module from previous versions to the version 2.5.1,
 * Don't forget to create one file per version.
 */
function upgrade_module_2_5_1($module)
{
    $module->addQuickAccess();
    
    $metas = array(
        array(
            'page' => 'module-jmarketplace-addproduct',
            'configurable' => 1,
            'title' => 'JA Marketplace - Add product',
            'description' => 'This is page to sellers can add products.',
            'url_rewrite' => 'add-product',
        ),
        array(
            'page' => 'module-jmarketplace-addseller',
            'configurable' => 1,
            'title' => 'JA Marketplace - New seller',
            'description' => 'This is page to customers can register as seller.',
            'url_rewrite' => 'new-seller',
        ),
        array(
            'page' => 'module-jmarketplace-contactseller',
            'configurable' => 1,
            'title' => 'JA Marketplace - Contact',
            'description' => 'This is page contact seller.',
            'url_rewrite' => 'contact-seller',
        ),
        array(
            'page' => 'module-jmarketplace-editproduct',
            'configurable' => 1,
            'title' => 'JA Marketplace - Edit product',
            'description' => 'This is page to sellers can edit your products.',
            'url_rewrite' => 'edit-product',
        ),
        array(
            'page' => 'module-jmarketplace-editseller',
            'configurable' => 1,
            'title' => 'JA Marketplace - Contact',
            'description' => 'This is page to sellers can edit your seller account.',
            'url_rewrite' => 'edit-seller',
        ),
        array(
            'page' => 'module-jmarketplace-favoriteseller',
            'configurable' => 1,
            'title' => 'JA Marketplace - Favorite sellers',
            'description' => 'This is page favorite seller.',
            'url_rewrite' => 'favorite-seller',
        ),
        array(
            'page' => 'module-jmarketplace-sellermessages',
            'configurable' => 1,
            'title' => 'JA Marketplace - Seller messages',
            'description' => 'This is page to sellers can see your messages.',
            'url_rewrite' => 'seller-messages',
        ),
        array(
            'page' => 'module-jmarketplace-sellerorders',
            'configurable' => 1,
            'title' => 'JA Marketplace - Seller orders',
            'description' => 'This is page to sellers can see your orders and commisions.',
            'url_rewrite' => 'seller-orders',
        ),
        array(
            'page' => 'module-jmarketplace-sellerpayment',
            'configurable' => 1,
            'title' => 'JA Marketplace - Seller payment',
            'description' => 'This is page to sellers can configure your payments.',
            'url_rewrite' => 'seller-payment',
        ),
        array(
            'page' => 'module-jmarketplace-sellerproducts',
            'configurable' => 1,
            'title' => 'JA Marketplace - Your products',
            'description' => 'This is page to sellers can see your products.',
            'url_rewrite' => 'your-products',
        ),
        array(
            'page' => 'module-jmarketplace-sellers',
            'configurable' => 1,
            'title' => 'JA Marketplace - All sellers',
            'description' => 'This is page show all sellers.',
            'url_rewrite' => 'sellers',
        ),
        array(
            'page' => 'module-jmarketplace-sellerprofile',
            'configurable' => 1,
            'title' => 'JA Marketplace - Seller profile',
            'description' => 'This is page of seller profile.',
            'url_rewrite' => 'seller-profile',
        ),
        array(
            'page' => 'module-jmarketplace-sellerproductlist',
            'configurable' => 1,
            'title' => 'JA Marketplace - All products of seller',
            'description' => 'This is page show all products of sellers.',
            'url_rewrite' => 'seller-product-list',
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
    
    return $module;
}
