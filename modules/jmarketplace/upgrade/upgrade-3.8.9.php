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
 * Function used to update your module from previous versions to the version 3.8.9,
 * Don't forget to create one file per version.
 */
function upgrade_module_3_8_9($module)
{
    Configuration::updateValue('JMARKETPLACE_SEND_ADMIN_REQUEST', 0);
    
    foreach (Language::getLanguages() as $lang) {
        if ($lang['iso_code'] == 'es' || $lang['iso_code'] == 'mx' || $lang['iso_code'] == 'co' || $lang['iso_code'] == 'ar') {
            $module->createEmailByIsoCode('es', $lang, 18);
        } elseif ($lang['iso_code'] == 'fr') {
            $module->createEmailByIsoCode('fr', $lang, 18);
        } elseif ($lang['iso_code'] == 'it') {
            $module->createEmailByIsoCode('it', $lang, 18);
        } elseif ($lang['iso_code'] == 'de') {
            $module->createEmailByIsoCode('de', $lang, 18);
        } else {
            $module->createEmailByIsoCode('en', $lang, 18);
        }
    }
    
    return $module;
}
