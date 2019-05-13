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
 * Function used to update your module from previous versions to the version 3.2.8,
 * Don't forget to create one file per version.
 */
function upgrade_module_3_2_8($module)
{
    Db::getInstance()->Execute('ALTER TABLE '._DB_PREFIX_.'seller_commision_history_state ADD reference varchar(32) NULL');
    
    $states = SellerCommissionHistoryState::getStates(Context::getContext()->language->id);
    $id_lang = Language::getIdByIso('en');
    if ($states) {
        foreach ($states as $s) {
            $state = new SellerCommisionHistoryState($s['id_seller_commision_history_state'], $id_lang);
            if ($s['id_seller_commision_history_state'] == 1) {
                $state->reference = 'pending';
            } elseif ($s['id_seller_commision_history_state'] == 2) {
                $state->reference = 'paid';
            } else {
                $state->reference = Tools::strtolower(trim($state->name));
            }
            $state->update();
        }
    }
    
    //add cancel state
    $state = new SellerCommisionHistoryState();
    $state->active = 1;
    $state->reference = 'cancel';
    foreach (Language::getLanguages() as $lang) {
        if ($lang['iso_code'] == 'es' || $lang['iso_code'] == 'mx' || $lang['iso_code'] == 'co' || $lang['iso_code'] == 'ar') {
            $state->name[$lang['id_lang']] = 'Cancelado';
        } else {
            $state->name[$lang['id_lang']] = 'Cancel';
        }
    }
    $state->add();
    
    Configuration::updateValue('JMARKETPLACE_CANCEL_COMMISSION_6', 1);
    Configuration::updateValue('JMARKETPLACE_CANCEL_COMMISSION_7', 1);
    Configuration::updateValue('JMARKETPLACE_CANCEL_COMMISSION_8', 1);

    return $module;
}
