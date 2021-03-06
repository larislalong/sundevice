<?php
/**
* 2007-2015 PrestaShop
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
*  @copyright 2007-2015 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Function used to update your module from previous versions to the version 2.7.5,
 * Don't forget to create one file per version.
 */
function upgrade_module_2_7_5($module)
{
    Db::getInstance()->Execute('ALTER TABLE '._DB_PREFIX_.'seller_commision_history ADD product_name VARCHAR(255) NOT NULL');
    
    $array_seller_commision_history = Db::getInstance()->ExecuteS('SELECT id_seller_commision_history, id_product FROM '._DB_PREFIX_.'seller_commision_history');
    foreach ($array_seller_commision_history as $seller_commision_history) {
        $product = new Product($seller_commision_history['id_product'], Context::getContext()->language->id, Context::getContext()->shop->id);
        $sch = new SellerCommisionHistory($seller_commision_history['id_seller_commision_history']);
        $sch->product_name = $product->name;
        $sch->update();
    }

    return $module;
}
