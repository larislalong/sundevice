<?php
/**
* 2007-2018 PrestaShop
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
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class CategoryTree
{
    public static function generateCheckboxesCategories($categories, $id_product = false)
    {
        $html = '';
        
        if ($id_product != false) {
            $product_categories = Product::getProductCategories($id_product);
        }

        foreach ($categories as $category) {
            $checked = '';
            $class = '';
            if ($id_product != false) {
                if (in_array($category['id_category'], $product_categories)) {
                    $checked = 'checked="checked"';
                    $class = ' displayed';
                }
            }
            
            if ($category['level_depth'] > 2) {
                $class = ' hidden';
            } else {
                $class = ' displayed';
            }
            
            $html .= '<li id="category_'.$category['id_category'].'" class="level_'.$category['level_depth'].$class.'" data="'.$category['level_depth'].'">';
            
            if (SellerCategory::isSellerCategory($category['id_category'], Context::getContext()->shop->id) != 0) {
                $html .= '<input class="category not-uniform" type="checkbox" name="categories[]" value="'.$category['id_category'].'" data="'.$category['level_depth'].'" '.$checked.'>';
            } else {
                $html .= '<input type="checkbox" class="disabled" disabled="disabled">';
            }
            
            $html .= '<label><i class="icon-folder fa fa-folder icon-folder-close"></i> '.$category['name'].'</label>';

            if (isset($category['children']) && !empty($category['children'])) {
                if ($category['level_depth'] > 1) {
                    $html .= '<ul id="level_'.$category['level_depth'].'">';
                } else {
                    $html .= '<ul id="level_'.$category['level_depth'].'">';
                }
                $html .= CategoryTree::generateCheckboxesCategories($category['children'], $id_product);
                $html .= '</ul>';
            }

            $html .= '</li>';
        }
        return $html;
    }
    
    public static function getNestedCategories(
        $root_category = null,
        $id_lang = false,
        $active = true,
        $groups = null,
        $use_shop_restriction = true,
        $sql_filter = '',
        $sql_sort = '',
        $sql_limit = ''
    ) {
        if (isset($root_category) && !Validate::isInt($root_category)) {
            die(Tools::displayError());
        }

        if (!Validate::isBool($active)) {
            die(Tools::displayError());
        }

        if (isset($groups) && Group::isFeatureActive() && !is_array($groups)) {
            $groups = (array)$groups;
        }

        $cache_id = 'Category::getNestedCategories_'.md5((int)$root_category.(int)$id_lang.(int)$active.(int)$active
                .(isset($groups) && Group::isFeatureActive() ? implode('', $groups) : ''));

        if (!Cache::isStored($cache_id)) {
            $result = Db::getInstance()->executeS(
                'SELECT c.*, cl.*
                FROM `'._DB_PREFIX_.'category` c
                '.($use_shop_restriction ? Shop::addSqlAssociation('category', 'c') : '').'
                LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON c.`id_category` = cl.`id_category`'.Shop::addSqlRestrictionOnLang('cl').'
                '.(isset($groups) && Group::isFeatureActive() ? 'LEFT JOIN `'._DB_PREFIX_.'category_group` cg ON c.`id_category` = cg.`id_category`' : '').'
                '.(isset($root_category) ? 'RIGHT JOIN `'._DB_PREFIX_.'category` c2 ON c2.`id_category` = '.(int)$root_category.' AND c.`nleft` >= c2.`nleft` AND c.`nright` <= c2.`nright`' : '').'
                WHERE 1 '.$sql_filter.' '.($id_lang ? 'AND `id_lang` = '.(int)$id_lang : '').'
                '.($active ? ' AND c.`active` = 1' : '').'
                '.(isset($groups) && Group::isFeatureActive() ? ' AND cg.`id_group` IN ('.implode(',', $groups).')' : '').'
                '.(!$id_lang || (isset($groups) && Group::isFeatureActive()) ? ' GROUP BY c.`id_category`' : '').'
                '.($sql_sort != '' ? $sql_sort : ' ORDER BY c.`level_depth` ASC').'
                '.($sql_sort == '' && $use_shop_restriction ? ', category_shop.`position` ASC' : '').'
                '.($sql_limit != '' ? $sql_limit : '')
            );

            $categories = array();
            $buff = array();

            if (!isset($root_category)) {
                $root_category = Category::getRootCategory()->id;
            }

            foreach ($result as $row) {
                $current = &$buff[$row['id_category']];
                $current = $row;

                if ($row['id_category'] == $root_category) {
                    $categories[$row['id_category']] = &$current;
                } else {
                    $buff[$row['id_parent']]['children'][$row['id_category']] = &$current;
                }
            }

            Cache::store($cache_id, $categories);
        }

        return Cache::retrieve($cache_id);
    }
}
