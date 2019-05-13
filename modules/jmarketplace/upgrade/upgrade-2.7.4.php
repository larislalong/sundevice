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
 * Function used to update your module from previous versions to the version 2.7.4,
 * Don't forget to create one file per version.
 */
function upgrade_module_2_7_4($module)
{
    $menu_jmarketplace = array(
        'en' => 'JA MarketPlace',
        'es' => 'JA MarketPlace',
        'fr' => 'JA MarketPlace',
        'it' => 'JA MarketPlace',
        'br' => 'JA MarketPlace',
    );

    $module->updateTab('AdminJmarketplace', $menu_jmarketplace);

    $menu_jmarketplace_sellers = array(
        'en' => 'Sellers',
        'es' => 'Vendedores',
        'fr' => 'Vendeurs',
        'it' => 'Venditori',
        'br' => 'Sellers',
    );

    $module->updateTab('AdminSellers', $menu_jmarketplace_sellers);

    $menu_jmarketplace_seller_products = array(
        'en' => 'Seller Products',
        'es' => 'Productos de los vendedores',
        'fr' => 'Produits des vendeurs',
        'it' => 'Prodotti venditore',
        'br' => 'Produtos de vendedores',
    );

    $module->updateTab('AdminSellerProducts', $menu_jmarketplace_seller_products);

    $menu_jmarketplace_seller_commissions = array(
        'en' => 'Seller Commissions',
        'es' => 'Comisiones de los vendedores',
        'fr' => 'Commissions des vendeurs',
        'it' => 'Commissioni  dei venditori',
        'br' => 'Seller Commissions',
    );

    $module->updateTab('AdminSellerCommisions', $menu_jmarketplace_seller_commissions);

    $menu_jmarketplace_seller_commission_history = array(
        'en' => 'Seller Commisions History',
        'es' => 'Historial de comisiones',
        'fr' => 'Historique de commissions',
        'it' => 'Precedenti delle commissioni',
        'br' => 'Seller Commisions History',
    );

    $module->updateTab('AdminSellerCommisionsHistory', $menu_jmarketplace_seller_commission_history);

    $menu_jmarketplace_seller_commissiom_history_states = array(
        'en' => 'Seller Payment States',
        'es' => 'Estado de los pagos',
        'fr' => 'États des paiements',
        'it' => 'Stati dei pagamenti',
        'br' => 'Seller Payment States',
    );

    $module->updateTab('AdminSellerCommisionsHistoryStates', $menu_jmarketplace_seller_commissiom_history_states);

    $menu_jmarketplace_incidences = array(
        'en' => 'Seller Messages',
        'es' => 'Mensajes',
        'fr' => 'Messages',
        'it' => 'Messaggi',
        'br' => 'Seller Messages',
    );

    $module->updateTab('AdminSellerIncidences', $menu_jmarketplace_incidences);

    return $module;
}
