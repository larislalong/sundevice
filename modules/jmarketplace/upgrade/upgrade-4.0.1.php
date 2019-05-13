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
 * Function used to update your module from previous versions to the version 4.0.1,
 * Don't forget to create one file per version.
 */
function upgrade_module_4_0_1($module)
{
    $id_seller_email = SellerEmail::getIdByReference('seller-desactivated');
    $seller_email = new SellerEmail($id_seller_email);
    foreach (Language::getLanguages() as $lang) {
        $seller_email->content[$lang['id_lang']] = $seller_email->content[$lang['id_lang']].'<p>{reasons}</p>';
    }
    $seller_email->update();
    
    $id_seller_email = SellerEmail::getIdByReference('product-desactivated');
    $seller_email = new SellerEmail($id_seller_email);
    foreach (Language::getLanguages() as $lang) {
        $seller_email->content[$lang['id_lang']] = $seller_email->content[$lang['id_lang']].'<p>{reasons}</p>';
    }
    $seller_email->update();
    
    return $module;
}