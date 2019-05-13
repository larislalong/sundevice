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
 * Function used to update your module from previous versions to the version 3.7.3,
 * Don't forget to create one file per version.
 */
function upgrade_module_3_7_3($module)
{
    if (Module::isEnabled('jtransferfunds')) {
        Module::disableByName('jtransferfunds');
    }
    
    Db::getInstance()->Execute(
        'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'seller_transfer_invoice` (
        `id_seller_transfer_invoice` int( 10 ) NOT NULL AUTO_INCREMENT ,
        `id_seller` int( 10 ) NOT NULL ,
        `total` float NOT NULL ,
        `payment` varchar(32) NOT NULL,
        `validate` INT(2) NOT NULL,
        `date_add` DATETIME NOT NULL,
        `date_upd` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY ( `id_seller_transfer_invoice` )
        ) ENGINE = InnoDB DEFAULT CHARSET=utf8;'
    );
    
    Db::getInstance()->Execute(
        'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'seller_transfer_commision` (
        `id_seller_transfer_commision` int( 10 ) NOT NULL AUTO_INCREMENT ,
        `id_seller_transfer_invoice` int( 10 ) NOT NULL ,
        `id_seller_commision_history` int( 10 ) NOT NULL ,
        PRIMARY KEY ( `id_seller_transfer_commision` )
        ) ENGINE = InnoDB DEFAULT CHARSET=utf8;'
    );
    
    $metas = array(
        array(
            'page' => 'module-jmarketplace-sellerinvoice',
            'configurable' => 1,
            'title' => 'JA Marketplace - Seller Invoice',
            'description' => 'Seller Invoice page.',
            'url_rewrite' => 'seller-invoice',
        ),
        array(
            'page' => 'module-jmarketplace-sellerinvoicehistory',
            'configurable' => 1,
            'title' => 'JA Marketplace - Seller Invoice History',
            'description' => 'Seller Invoice History page.',
            'url_rewrite' => 'seller-invoice-history',
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
    
    $menu_jmarketplace_seller_invoices = array(
        'en' => 'Transfer Requests',
        'es' => 'Solicitudes de transferencia',
        'fr' => 'Demandes de transfert',
        'it' => 'Richieste di trasferimento',
        'br' => 'Transfer Requests',
    );
    
    $module->createTab('AdminSellerInvoices', $menu_jmarketplace_seller_invoices, 'AdminJmarketplace');
    
    Configuration::updateValue('JMARKETPLACE_SHOW_SELLER_INVOICE', 0);
    
    if (Configuration::get('PS_SSL_ENABLED') == 1) {
        $url_shop = Tools::getShopDomainSsl(true).__PS_BASE_URI__;
    } else {
        $url_shop = Tools::getShopDomain(true).__PS_BASE_URI__;
    }
    
    //seller-transfer-accepted
    $seller_email = new SellerEmail();
    $seller_email->reference = 'seller-transfer-accepted';

    foreach (Language::getLanguages(false) as $lang) {
        if ($lang['iso_code'] == 'es' || $lang['iso_code'] == 'mx' || $lang['iso_code'] == 'co' || $lang['iso_code'] == 'ar') {
            $seller_email->subject[$lang['id_lang']] = 'Su solicitud de pago ha sido aceptada';
            $seller_email->description[$lang['id_lang']] = 'This email is sent to the seller when a administrator accept a commissions request transfer.';
            $seller_email->content[$lang['id_lang']] = '<p>Your request for payment has been validated by our accounting team A amount of <strong>{amount}</strong> has been transfered in your {payment} account.</p>';
            $seller_email->content[$lang['id_lang']] .= '<p>You can access your shop to see more information in <a href="'.$url_shop.'">{shop_name}</a></p>';
        } elseif ($lang['iso_code'] == 'fr') {
            $seller_email->subject[$lang['id_lang']] = 'Your request for payment has been accepted';
            $seller_email->description[$lang['id_lang']] = 'This email is sent to the seller when a administrator accept a commissions request transfer.';
            $seller_email->content[$lang['id_lang']] = '<p>Your request for payment has been validated by our accounting team A amount of <strong>{amount}</strong> has been transfered in your {payment} account.</p>';
            $seller_email->content[$lang['id_lang']] .= '<p>You can access your shop to see more information in <a href="'.$url_shop.'">{shop_name}</a></p>';
        } elseif ($lang['iso_code'] == 'it') {
            $seller_email->subject[$lang['id_lang']] = 'Your request for payment has been accepted';
            $seller_email->description[$lang['id_lang']] = 'This email is sent to the seller when a administrator accept a commissions request transfer.';
            $seller_email->content[$lang['id_lang']] = '<p>Your request for payment has been validated by our accounting team A amount of <strong>{amount}</strong> has been transfered in your {payment} account.</p>';
            $seller_email->content[$lang['id_lang']] .= '<p>You can access your shop to see more information in <a href="'.$url_shop.'">{shop_name}</a></p>';
        } else {
            $seller_email->subject[$lang['id_lang']] = 'Your request for payment has been accepted';
            $seller_email->description[$lang['id_lang']] = 'This email is sent to the seller when a administrator accept a commissions request transfer.';
            $seller_email->content[$lang['id_lang']] = '<p>Your request for payment has been validated by our accounting team A amount of <strong>{amount}</strong> has been transfered in your {payment} account.</p>';
            $seller_email->content[$lang['id_lang']] .= '<p>You can access your shop to see more information in <a href="'.$url_shop.'">{shop_name}</a></p>';
        }
    }

    $seller_email->add();
    
    return $module;
}
