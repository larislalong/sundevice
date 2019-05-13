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
 * Function used to update your module from previous versions to the version 2.8.1,
 * Don't forget to create one file per version.
 */
function upgrade_module_2_8_1($module)
{
    Configuration::updateValue('JMARKETPLACE_SHOW_COUNTRY', 0);
    Configuration::updateValue('JMARKETPLACE_SHOW_STATE', 0);
    Configuration::updateValue('JMARKETPLACE_SHOW_CITY', 0);
    
    Db::getInstance()->Execute('ALTER TABLE '._DB_PREFIX_.'seller ADD country varchar(55) NOT NULL');
    Db::getInstance()->Execute('ALTER TABLE '._DB_PREFIX_.'seller ADD state varchar(55) NOT NULL');
    Db::getInstance()->Execute('ALTER TABLE '._DB_PREFIX_.'seller ADD city varchar(55) NOT NULL');
    
    return $module;
}
