<?php
/**
 * 2007-2017 PrestaShop
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
 *  @copyright 2007-2017 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

const SQL_SECONDARY_MENU_INDEX = 3;
$sql = array();
$sql[] = 'create table IF NOT EXISTS ' . _DB_PREFIX_ . 'menupro_css_property
(
   id_menupro_css_property int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
   name                 varchar(50),
   display_name         varchar(50),
   type                 int,
   default_value        varchar(20),
	id_property_base int(11) UNSIGNED ,
   event        varchar(20),
   for_container               bool,
   primary key (id_menupro_css_property)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';

$sql[] = 'create table IF NOT EXISTS ' . _DB_PREFIX_ . 'menupro_selectable_value
(
   id_menupro_selectable_value int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	id_menupro_css_property int(11) UNSIGNED NOT NULL,
   display_name         varchar(50),
   value        varchar(20),
   primary key (id_menupro_selectable_value),
	constraint FK_Association_35 foreign key (id_menupro_css_property)
      references ' . _DB_PREFIX_ . 'menupro_css_property (id_menupro_css_property) on delete cascade on update cascade
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';

$sql[] = 'create table IF NOT EXISTS ' . _DB_PREFIX_ . 'menupro_main_menu
(
   id_menupro_main_menu int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
   hook                 int,
   menu_type                 int,
   number_menu_per_ligne     int,
   show_search_bar      bool,
   active               bool,
   primary key (id_menupro_main_menu)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';

$sql[] = 'create table ' . _DB_PREFIX_ . 'menupro_secondary_menu
(
   id_menupro_secondary_menu int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
   id_menupro_main_menu int(11) UNSIGNED NOT NULL,
   parent_menu int(11) UNSIGNED,
   clickable            bool,
   position             int,
   link_type             int,
   level                int,
   new_tab           bool,
   use_custom_content   bool,
   item_type         int,
   id_item           int,
   display_style        int,
   associate_all        bool,
   active               bool,
   primary key (id_menupro_secondary_menu),
   constraint FK_Association_3 foreign key (id_menupro_main_menu)
      references ' . _DB_PREFIX_ . 'menupro_main_menu (id_menupro_main_menu) on delete cascade on update cascade
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';

$sql[] = 'alter table ' . _DB_PREFIX_ .
'menupro_secondary_menu add constraint FK_menupro_secondary_menu_menupro_secondary_menu foreign key (parent_menu)
      references ' . _DB_PREFIX_ .
         'menupro_secondary_menu (id_menupro_secondary_menu) on delete cascade on update cascade;';

$sql[] = 'create table IF NOT EXISTS ' . _DB_PREFIX_ . 'menupro_default_style
(
   id_menupro_default_style int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
   id_menupro_main_menu int(11) UNSIGNED,
   id_menupro_secondary_menu int(11) UNSIGNED,
   name                 varchar(50),
   menu_level           int,
   menu_type            int,
   primary key (id_menupro_default_style),
   constraint FK_Association_5 foreign key (id_menupro_secondary_menu)
      references ' . _DB_PREFIX_ .
  'menupro_secondary_menu (id_menupro_secondary_menu) on delete cascade on update cascade,
   constraint FK_Association_8 foreign key (id_menupro_main_menu)
      references ' . _DB_PREFIX_ . 'menupro_main_menu (id_menupro_main_menu) on delete cascade on update cascade
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';

$sql[] = 'create table IF NOT EXISTS ' . _DB_PREFIX_ . 'menupro_menu_style
(
   id_menupro_menu_style int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
   id_menupro_secondary_menu int(11) UNSIGNED,
   id_menupro_main_menu int(11) UNSIGNED,
   name                 varchar(50),
   usable_style         int,
   primary key (id_menupro_menu_style),
   constraint FK_Association_21 foreign key (id_menupro_secondary_menu)
      references ' . _DB_PREFIX_ .
  'menupro_secondary_menu (id_menupro_secondary_menu) on delete cascade on update cascade,
   constraint FK_Association_22 foreign key (id_menupro_main_menu)
      references ' . _DB_PREFIX_ . 'menupro_main_menu (id_menupro_main_menu) on delete cascade on update cascade
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';

$sql[] = 'create table IF NOT EXISTS ' . _DB_PREFIX_ . 'menupro_css_property_menu
(
   id_menupro_css_property_menu int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
   id_menupro_default_style int(11) UNSIGNED,
   id_menupro_css_property int(11) UNSIGNED,
   id_menupro_menu_style int(11) UNSIGNED,
   usable_value         int,
   value                varchar(254),
   primary key (id_menupro_css_property_menu),
   constraint FK_Association_6 foreign key (id_menupro_default_style)
      references ' . _DB_PREFIX_ .
  'menupro_default_style (id_menupro_default_style) on delete cascade on update cascade,
   constraint FK_Association_7 foreign key (id_menupro_css_property)
      references ' . _DB_PREFIX_ . 'menupro_css_property (id_menupro_css_property) on delete restrict on update cascade,
   constraint FK_Association_11 foreign key (id_menupro_menu_style)
      references ' . _DB_PREFIX_ . 'menupro_menu_style (id_menupro_menu_style) on delete cascade on update cascade
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';

$sql[] = 'create table IF NOT EXISTS ' . _DB_PREFIX_ . 'menupro_html_content
(
   id_menupro_html_content int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
   id_menupro_secondary_menu int(11) UNSIGNED NOT NULL,
   position             int,
   active               bool,
   primary key (id_menupro_html_content),
   constraint FK_Association_2 foreign key (id_menupro_secondary_menu)
      references ' . _DB_PREFIX_ .
  'menupro_secondary_menu (id_menupro_secondary_menu) on delete cascade on update cascade
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';

$sql[] = 'create table IF NOT EXISTS ' . _DB_PREFIX_ . 'menupro_html_content_lang
(
   id_menupro_html_content int(11) UNSIGNED NOT NULL,
   id_lang              int(11) UNSIGNED NOT NULL,
   content              text,
   primary key (id_menupro_html_content, id_lang),
   constraint FK_Association_16 foreign key (id_menupro_html_content)
      references ' . _DB_PREFIX_ . 'menupro_html_content (id_menupro_html_content) on delete cascade on update cascade
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';

$sql[] = 'create table IF NOT EXISTS ' . _DB_PREFIX_ . 'menupro_main_menu_lang
(
   id_menupro_main_menu int(11) UNSIGNED NOT NULL,
   id_lang              int(11) UNSIGNED NOT NULL,
   name                 varchar(254),
   primary key (id_menupro_main_menu, id_lang),
   constraint FK_Association_13 foreign key (id_menupro_main_menu)
      references ' . _DB_PREFIX_ . 'menupro_main_menu (id_menupro_main_menu) on delete cascade on update cascade
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';

$sql[] = 'create table IF NOT EXISTS ' . _DB_PREFIX_ . 'menupro_main_menu_shop
(
   id_menupro_main_menu int(11) UNSIGNED NOT NULL,
   id_shop              int(11) UNSIGNED NOT NULL,
   primary key (id_menupro_main_menu, id_shop),
   constraint FK_Association_14 foreign key (id_menupro_main_menu)
      references ' . _DB_PREFIX_ . 'menupro_main_menu (id_menupro_main_menu) on delete cascade on update cascade
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';

$sql[] = 'create table IF NOT EXISTS ' . _DB_PREFIX_ . 'menupro_secondary_menu_lang
(
   id_menupro_secondary_menu int(11) UNSIGNED NOT NULL,
   id_lang              int(11) UNSIGNED NOT NULL,
   name                 varchar(50),
   title                 varchar(50),
   link                 varchar(60),
    primary key (id_menupro_secondary_menu, id_lang),
   constraint FK_Association_15 foreign key (id_menupro_secondary_menu)
      references ' . _DB_PREFIX_ .
  'menupro_secondary_menu (id_menupro_secondary_menu) on delete cascade on update cascade
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';

$sql[] = 'create table IF NOT EXISTS ' . _DB_PREFIX_ . 'menupro_icon
(
   id_menupro_icon      int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
   id_menupro_secondary_menu int(11) UNSIGNED NOT NULL,
   css_class            varchar(50),
   position             int,
   primary key (id_menupro_icon),
   constraint FK_Association_19 foreign key (id_menupro_secondary_menu)
      references ' . _DB_PREFIX_ .
  'menupro_secondary_menu (id_menupro_secondary_menu) on delete cascade on update cascade
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';

$tableSecondaryMenuCreated = false;
$continueExecute = true;
foreach ($sql as $key => $query) {
    $continueExecute = true;
    if ($key == SQL_SECONDARY_MENU_INDEX) {
        try {
            $continueExecute = false;
            $tableSecondaryMenuCreated = Db::getInstance()->execute($query);
        } catch (Exception $e) {
            $tableSecondaryMenuCreated = false;
        }
    } elseif ($key == SQL_SECONDARY_MENU_INDEX + 1) {
        if (! $tableSecondaryMenuCreated) {
            $continueExecute = false;
        }
    }
    if ($continueExecute) {
        if (Db::getInstance()->execute($query) == false) {
            return false;
        }
    }
}
