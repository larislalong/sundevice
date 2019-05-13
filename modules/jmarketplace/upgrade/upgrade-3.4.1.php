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
 * Function used to update your module from previous versions to the version 3.4.1,
 * Don't forget to create one file per version.
 */
function upgrade_module_3_4_1($module)
{
    $menu_jmarketplace_seller_emails = array(
        'en' => 'Seller Emails',
        'es' => 'Emails',
        'fr' => 'Emails',
        'it' => 'Emails',
        'br' => 'Emails',
    );

    $module->createTab('AdminSellerEmails', $menu_jmarketplace_seller_emails, 'AdminJmarketplace');
    
    //create tables
    Db::getInstance()->Execute(
        'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'seller_email` (
	`id_seller_email` INT(10) NOT NULL AUTO_INCREMENT,
        `reference` varchar(45) character set utf8 NOT NULL,
	PRIMARY KEY (`id_seller_email`)
        ) ENGINE = InnoDB DEFAULT CHARSET=utf8;'
    );
    
    Db::getInstance()->Execute(
        'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'seller_email_lang` (
	`id_seller_email` INT(10) NOT NULL,
	`id_lang` INT(10) NOT NULL,
	`subject` VARCHAR(155) NOT NULL,
        `description` TEXT NOT NULL,
        `content` TEXT NOT NULL,
	PRIMARY KEY (`id_seller_email`, `id_lang`),
	KEY `id_lang` (`id_lang`),
        KEY `subject` (`subject`)
        ) ENGINE = InnoDB DEFAULT CHARSET=utf8;'
    );
    
    //fill tables
    $module->createEmails();

    return $module;
}
