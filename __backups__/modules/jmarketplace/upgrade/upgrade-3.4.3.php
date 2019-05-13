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
 * Function used to update your module from previous versions to the version 3.4.3,
 * Don't forget to create one file per version.
 */
function upgrade_module_3_4_3($module)
{
    if (Module::isEnabled('jsellercomments')) {
        Module::disableByName('jsellercomments');
    }
    
    $metas = array(
        array(
            'page' => 'module-jmarketplace-sellercomments',
            'configurable' => 1,
            'title' => 'JA Marketplace - Seller comments',
            'description' => 'In this page is displayed all comments and ratings of seller.',
            'url_rewrite' => 'seller-comments',
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
    
    $module->removeThemeColumnByPage('module-jmarketplace-sellercomments');
    
    $menu_jmarketplace_seller_comments = array(
        'en' => 'Ratings and comments',
        'es' => 'Valoraciones y comentarios',
        'fr' => 'Notes et commentaires',
        'it' => 'Valutazioni e commenti',
        'br' => 'Ratings and comments',
    );
    
    $module->createTab('AdminSellerComments', $menu_jmarketplace_seller_comments, 'AdminJmarketplace');
    
    //create tables
    Db::getInstance()->Execute('
        CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'seller_comment` (
        `id_seller_comment` int(10) unsigned NOT NULL auto_increment,
        `id_seller` int(10) unsigned NOT NULL,
        `id_customer` int(10) unsigned NOT NULL,
        `id_guest` int(10) unsigned NULL,
        `title` varchar(64) NULL,
        `content` text NOT NULL,
        `customer_name` varchar(64) NULL,
        `grade` float unsigned NOT NULL,
        `validate` tinyint(1) NOT NULL,
        `deleted` tinyint(1) NOT NULL,
        `date_add` datetime NOT NULL,
        PRIMARY KEY (`id_seller_comment`),
        KEY `id_seller` (`id_seller`),
        KEY `id_customer` (`id_customer`),
        KEY `id_guest` (`id_guest`)
        ) ENGINE = InnoDB DEFAULT CHARSET=utf8;');
    
    Db::getInstance()->Execute('
        CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'seller_comment_criterion` (
        `id_seller_comment_criterion` int(10) unsigned NOT NULL auto_increment,
        `active` tinyint(1) NOT NULL,
        PRIMARY KEY (`id_seller_comment_criterion`)
        ) ENGINE = InnoDB DEFAULT CHARSET=utf8;');
    
    Db::getInstance()->Execute('
        CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'seller_comment_criterion_lang` (
        `id_seller_comment_criterion` INT(11) UNSIGNED NOT NULL ,
        `id_lang` INT(11) UNSIGNED NOT NULL ,
        `name` VARCHAR(64) NOT NULL ,
        PRIMARY KEY ( `id_seller_comment_criterion` , `id_lang` )
        ) ENGINE = InnoDB DEFAULT CHARSET=utf8;');
    
    Db::getInstance()->Execute('
        CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'seller_comment_grade` (
        `id_seller_comment` int(10) unsigned NOT NULL,
        `id_seller_comment_criterion` int(10) unsigned NOT NULL,
        `grade` int(10) unsigned NOT NULL,
        PRIMARY KEY (`id_seller_comment`, `id_seller_comment_criterion`),
        KEY `id_seller_comment_criterion` (`id_seller_comment_criterion`)
        ) ENGINE = InnoDB DEFAULT CHARSET=utf8;');
    
    Db::getInstance()->Execute('INSERT IGNORE INTO `'._DB_PREFIX_.'seller_comment_criterion` (`id_seller_comment_criterion`, `active`) VALUES (1, 1)');
    Db::getInstance()->Execute('INSERT IGNORE INTO `'._DB_PREFIX_.'seller_comment_criterion_lang` (`id_seller_comment_criterion`, `id_lang`, `name`) (SELECT 1, l.`id_lang`, "Quality" FROM `'._DB_PREFIX_.'lang` l)');
    
    //move ratings to comments
    $ratings = Db::getInstance()->ExecuteS('SELECT * FROM '._DB_PREFIX_.'seller_rating');
    if (is_array($ratings) && count($ratings) > 0) {
        foreach ($ratings as $rating) {
            $comment = new SellerComment();
            $comment->title = $module->l('Untitled for comment');
            $comment->content = $module->l('No comment');
            $comment->id_seller = $rating['id_seller'];
            $comment->id_customer = $rating['id_customer'];
            $comment->id_guest = 0;
            $customer = new Customer($comment->id_customer);
            $comment->customer_name = $customer->firstname.' '.$customer->lastname;
            $comment->validate = 1;
            $comment->grade = $rating['grade'];
            $comment->save();
            
            $criterions = SellerCommentCriterion::getCriterions(Context::getContext()->language->id);

            foreach ($criterions as $scc) {
                $seller_comment_criterion = new SellerCommentCriterion($scc['id_seller_comment_criterion']);
                if ($seller_comment_criterion->id) {
                    $seller_comment_criterion->addGrade($comment->id, $rating['grade']);
                }
            }
        }
    }
    
    Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'seller_rating`');
    
    Configuration::updateValue('JMARKETPLACE_MODERATE_COMMENTS', 1);
    Configuration::updateValue('JMARKETPLACE_ALLOW_GUEST_COMMENT', 0);
    Configuration::updateValue('JMARKETPLACE_SEND_COMMENT_SELLER', 1);
    Configuration::updateValue('JMARKETPLACE_SEND_COMMENT_ADMIN', 1);

    return $module;
}
