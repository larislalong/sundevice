<?php
/**
* History:
*
* 1.0 - First version
* 1.4 - Possibility to add the function "display combinations" everywhere
* 1.5 - for prestashop 1.5, function smarty "display_combinations" become function "d_combinations"
* 1.5.1 - add the list on product page and possiblity to choicy the quantity for the customers
* 1.5.2 - correction bug in query SQL and javascript (minimal_quantity)
* 1.5.3 - correction bug when product have "no-tax"
* 1.5.4 - compatibility with module blocklayered and displaying a square instead of the image
* 1.5.5 - add buttons + and -, correct little bug for installation in the file product-list.tpl
* 1.5.6 - correction bug with blocklayered
* 1.5.7 - list in ajax and little optimisations
* 1.5.8 - correction bug
* 1.5.9 - optimisations (image default if no img for attribute)and add reduction and price stripped in the back office
* 1.5.9.1 - Correction bug (image not displayed if attribute have not color)
* 1.5.9.2 - i forget a {debug} in the file display_combinations.tpl - sorry
* 1.5.9.3 - Possiblity to choose ajax or not on the page category, correction bug with blocklayered
* 1.5.9.4 - debug url product when ajax selected
* 1.5.9.5 - debug : when _PS_VERSION_ < 1.5.5 and ajax not selected the file tpl is not load correctly
* 1.5.9.6 - debug display price with specific_price_output
* 1.5.9.7 - display message if the image size is > 200px in the BO,
            add the animation image product in the page category,
            change the src of the image in BO (if the combination's name contain "%" for example)
* 1.6 - version for prestashop 1.6 - valide for https://validator.prestashop.com
* 1.6.1 - correction for controller (like search), modification of the function updateConfigFile(),
            the file js/displaycombinations_categories.js and url of the file tpl for 1.5.x
* 1.6.2 - debug for compatibility with prestashop 1.5.x
* 1.6.3 - little debug when smartcache is disable on product-list.tpl
* 1.6.4 - add selector function
* 1.6.5 - debug SQL (problem quantity in multishop) and function zoom and function for hide the list of attributes
* 1.6.6 - add column unit price, add column message stock and warning,
            add background-color for 1 line on 2, change the gestion of stock
* 1.6.7 - code cleaning, add function for sorting out combinations
* 1.6.8 - debug problem with module blocklayered, optimisations responsive
* 1.6.9 - debug with the sort, optimisations responsive : add size of image for responsive (0-768px)
* 1.6.10 - compatibility with blockcart "no-ajax"
* 1.6.11 - update mode list/grid, compatibility with blockcart without ajax
* 1.6.12 - add column reference, display attribute name before the attribute,
            possibility to display one attribute by column
* 1.6.12.3 - debug template in mode responsive and with certain configuration of the module
* 1.6.12.4 - compatibility CHF and order, possibility to have a display like smartphone
            with table_declinaisons_mini (see that in tpl), delete the "function square in color".
* 1.6.12.5 (=1.6.13) - minor modifications, modification config by default for installation
* 1.6.14 - option for have one button "add to cart" by table
* 1.6.15 - debug problem with $product->show_price and $product->available_for_order
            possibility to choice the number maximum of coimbinations to display, with the choice of attributes
* 1.6.16 - compatibility with the module "hide prices easily"
* 1.7.0 - version for prestashop 1.6 and 1.7
* 1.7.1 - version for prestashop 1.6 and 1.7
* 1.7.2 - change request SQL for the image combination
* 1.7.3 - correction with the sort of public_name
* 1.7.4 - add column supplier reference
* 1.7.5 - change image of combination
* 1.7.6 - correction mode catalog on 1.6
* 1.7.7 - correction sort of combinations, display on categories page and quick view
* 1.7.8 - minor updates
* 1.7.9 - add date available for each combination
* 1.7.10 - minor updates
*
*  @author    Vincent MASSON <contact@coeos.pro>
*  @copyright Vincent MASSON <www.coeos.pro>
*  @license   http://www.coeos.pro/boutique/fr/content/3-conditions-generales-de-ventes
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

include_once  _PS_OVERRIDE_DIR_. 'classes/ProductSupportedLTE.php';
class DisplayCombinationsOverride extends DisplayCombinations
{
    
    public function install()
    {
        if (!parent::install()
            || !$this->registerHook('displayProductAvailableStock'))
             {
            return false;
        }
        return true;
    }
   
    public function hookdisplayProductAvailableStock($params)
    {
		$this->context->smarty->assign(
            array(
                'modelIdAttributeGroup' => ProductSupportedLTE::getModelIdAttributeGroup(),
                'colorIdAttributeGroup' => (int)Configuration::get('ID_GROUP_COLOR'),
                'maxCombinationsPerLoad' => 10
            )
        );
        return $this->hookdisplayCombinations($params);
    }
    
	public function hookproductFooter($params)
    {
        return '';
    }
	
	public static function dCombinations($params)
    {
        $c = Configuration::get('d_c_config');
        $config = unserialize($c);
        $context = Context::getContext();
        $id_product  = (Tools::getValue('id_product'))? (int)Tools::getValue('id_product') : (int)$params['id_product'];
        $config['id_product'] = $id_product;
        $config['product_name']     = (Tools::getValue('id_product'))? '' : $params['name'];
        $config['link_product']                = (isset($params['link_product']) && !empty($params['link_product']))?
            str_replace('|', '&', $params['link_product']) : '';
        $config['link_img'] = (Tools::getValue('id_product'))? '' : $params['link_img'];
        $list_id_product_attribute = array();
        $id_group_customer = (isset($params['id_group_customer']))? $params['id_group_customer'] :
            (int)$context->customer->id_default_group;
        $tax = ((int)Group::getPriceDisplayMethod($id_group_customer) == 1)? false : true;
        $id_currency = (isset($params['id_currency']))? (int)$params['id_currency'] : (int)$context->currency->id;
        $id_lang = (isset($params['id_lang']))? (int)$params['id_lang'] : (int)$context->language->id;
        $iso_code = (isset($params['iso_code']))? $params['iso_code'] : $context->language->iso_code;

        $context->currency = new Currency($id_currency);
        $context->language = new Language($id_lang);
        $context->language->iso_code = $iso_code;

        $store_commander = '';
        // $store_commander = ' AND p_a.`sc_active` = 1 ';

        $config['product'] = new Product($id_product, true, $context->language->id, $context->shop->id);
        $link_rewrite = (Tools::substr(_PS_VERSION_, 0, 3) == '1.7')?
            $params['product']['link_rewrite'] : $config['product']->link_rewrite;
        if (Module::isInstalled('hidepriceseasily') && Module::isEnabled('hidepriceseasily')) {
            $id_category = (int)$config['product']->id_category_default;
            $igd = (int)Context::getContext()->customer->id_default_group;
            $sql = 'SELECT `id_category`
                    FROM `'._DB_PREFIX_.'hidepriceseasily`
                    WHERE `id_category` = '.(int)$id_category.'
                    AND `id_group` = '.(int)$igd.'
                    AND `id_shop` = '.(int)$context->shop->id;
            $price_hide = (Db::getInstance()->getValue($sql) == $id_category)? false : true;
            $hpe = Configuration::get('config_hpe');
            $hpe = unserialize($hpe);
            $stay_visible_for_pro = false;
            if ((int)$context->customer->logged == 1 && (int)$hpe['hpe_pro'] == 1) {
                $sql = 'SELECT `vat_number`
                        FROM `'._DB_PREFIX_.'address`
                        WHERE `id_customer` = '.(int)Context::getContext()->customer->id.'
                        AND `vat_number` != ""';
                $vat = (isset(Context::getContext()->customer->id))?
                    Db::getInstance()->getValue($sql)
                    : false;
                $stay_visible_for_pro = (!empty($context->customer->siret) || !empty($vat))? true : false;
            }
            if ($price_hide && !$stay_visible_for_pro) {
                $config['product']->available_for_order = false;
                $config['product']->show_price = false;
            }
        }

        $sql = 'SELECT p_a.`id_product_attribute`, psa.`position`, psa.id_attribute, p.`price`,
        p.`id_tax_rules_group`, p.`ecotax`, tr.`id_tax`, tr.`id_tax_rules_group`, tax.`rate`
        FROM `'._DB_PREFIX_.'product_attribute` p_a 
        JOIN `'._DB_PREFIX_.'product` p ON(p.`id_product` = p_a.`id_product`)
        JOIN `'._DB_PREFIX_.'product_attribute_combination` pac
            ON(pac.`id_product_attribute` = p_a.`id_product_attribute`)
        JOIN `'._DB_PREFIX_.'attribute` psa ON(psa.`id_attribute` = pac.`id_attribute`)
        LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON(tr.`id_tax_rules_group` = p.`id_tax_rules_group`)
        LEFT JOIN `'._DB_PREFIX_.'tax` tax ON(tax.`id_tax` = tr.`id_tax`)
        WHERE (p_a.active = 1) AND p.`id_product`='.(int)$id_product.'
        '.$store_commander.'
        GROUP BY p_a.`id_product_attribute`
        ORDER BY psa.`position` ASC';
        $list_id_product_attribute = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($sql);
        if (!$list_id_product_attribute || empty($list_id_product_attribute)) {
            return false;
        }
        $config['specific_prices'] = SpecificPrice::getByProductId($id_product);
        foreach ($config['specific_prices'] as $key => $specific_price) {
            $config['impact'][$specific_price['id_product_attribute']] = $config['specific_prices'][$key];
        }

        $a_c = array();
        foreach ($list_id_product_attribute as $lipav) {
            $config['list_id_product_attribute'][$lipav['id_product_attribute']] = $lipav;
        }

        foreach ($config['list_id_product_attribute'] as $id_product_attribute) {
            $specific_price_output = '';
            $prix = Product::getPriceStatic(
                $id_product,
                $tax,
                $id_product_attribute['id_product_attribute'],
                2,
                null,
                false,
                true,
                1,
                false,
                null,
                null,
                null,
                $specific_price_output
            );
            $prix_remise = Product::getPriceStatic(
                $id_product,
                $tax,
                $id_product_attribute['id_product_attribute'],
                2,
                null,
                false,
                false
            );
            $config['reduction'][$id_product_attribute['id_product_attribute']] = $specific_price_output;
            $config['prices'][$id_product_attribute['id_product_attribute']] = $prix;
            $config['price_without_reduction'][$id_product_attribute['id_product_attribute']] = $prix_remise;

            $sql = 'SELECT *, p_a_c.`id_product_attribute` AS `idCombination`, agl.`name` AS `nom_url`,
                    sk.`quantity` AS `stock_available`, sk.`depends_on_stock`, sk.`out_of_stock`,
                    ps.`product_supplier_reference` AS `supplier_ref`, p_a.`available_date`
                    FROM `'._DB_PREFIX_.'product_attribute` p_a
                    LEFT JOIN `'._DB_PREFIX_.'stock_available` sk
                        ON(sk.`id_product_attribute` = p_a.`id_product_attribute` AND 
                                (sk.`id_shop`='.((int)$context->shop->id).'
                                OR sk.`id_shop_group`='.((int)$context->shop->id_shop_group).'))
                    JOIN `'._DB_PREFIX_.'product_attribute_combination` p_a_c
                        ON(p_a_c.`id_product_attribute` = p_a.`id_product_attribute`)
                    JOIN `'._DB_PREFIX_.'attribute` ps_a
                        ON(ps_a.`id_attribute` = p_a_c.`id_attribute`)
                    JOIN `'._DB_PREFIX_.'attribute_group` ag
                        ON(ag.`id_attribute_group` = ps_a.`id_attribute_group`)
                    JOIN `'._DB_PREFIX_.'attribute_group_lang` agl
                        ON(agl.`id_attribute_group` = ps_a.`id_attribute_group`)
                    JOIN `'._DB_PREFIX_.'attribute_lang` a_l    
                        ON(a_l.`id_attribute` = p_a_c.`id_attribute`)
                    LEFT JOIN `'._DB_PREFIX_.'product_attribute_image` pai
                        ON(pai.`id_product_attribute` = p_a.`id_product_attribute`)
                    LEFT JOIN `'._DB_PREFIX_.'image` img ON(img.`id_image` = pai.`id_image`)
                    LEFT JOIN `'._DB_PREFIX_.'product_supplier` ps
                        ON(ps.`id_product_attribute` = p_a_c.`id_product_attribute`)
                    WHERE (p_a.active = 1) AND p_a.`id_product`='.(int)$id_product.'
                    AND agl.`id_lang`='.(int)$id_lang.'
                    AND a_l.`id_lang` ='.(int)$id_lang.'
                    AND p_a.`id_product_attribute`='.(int)$id_product_attribute['id_product_attribute'].'
                    '.$store_commander.'
                    GROUP BY (ps_a.id_attribute_group)
                    ORDER BY ag.`position`, ps_a.`position`, img.`position` ASC';
                    $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($sql);
            $config['declinaison'][$id_product_attribute['id_product_attribute']] = $result;
        }
        asort($config['declinaison']);
        $config['attributesCombinations'] = Product::getAttributesInformationsByProduct($id_product);
        foreach ($config['attributesCombinations'] as &$ac) {
            foreach ($ac as &$val) {
                $val = str_replace('-', '_', Tools::link_rewrite(str_replace(array(',', '.'), '-', $val)));
            }
        }
        foreach ($config['attributesCombinations'] as $item) {
            $a_c[$item['id_attribute']]['attribute']     = $item['attribute'];
            $a_c[$item['id_attribute']]['group']         = $item['group'];
        }
        $ak = array_keys($config['declinaison']);
        $a_shift = array_shift($ak);
        foreach ($config['declinaison'][$a_shift] as $attribut) {
            $config['attributs'][$attribut['public_name']] = '';
        }
        $https_link = (Tools::usingSecureMode() && Configuration::get('PS_SSL_ENABLED')) ? 'https://' : 'http://';
        $link = new Link($https_link, $https_link);

        $id_image_default = (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
            SELECT `id_image` FROM `'._DB_PREFIX_.'image` WHERE `id_product`='.(int)$id_product.' AND `cover` = 1');
        $sql = 'SELECT `name` FROM `'._DB_PREFIX_.'image_type` WHERE `id_image_type` = '.(int)$config['d_c_id_img'];
        $name_img = ($config['d_c_id_img'] != 0)? Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql) : false;
        $d_c_id_img_zoom = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('SELECT `name` FROM `'._DB_PREFIX_.'image_type`
            WHERE `id_image_type` = '.(int)$config['d_c_id_img_zoom']);
        $module = new DisplayCombinations();
        foreach ($config['declinaison'] as $ipa => $item_dec) {
            $link_product = $config['link_product'].'#';
            foreach ($item_dec as $item_attribute) {
                $link_product .= '/'.$a_c[$item_attribute['id_attribute']]['group'].
                    '-'.$a_c[$item_attribute['id_attribute']]['attribute'];
            }
            foreach ($config['list_id_product_attribute'] as $id_product_attribute) {
                $config['url_combinations'][$item_dec[0]['idCombination']] = $link_product;
            }

            // commandable
            if (Configuration::get('PS_STOCK_MANAGEMENT') == 0) {
                $commandable = 1;
            } else {
                if ($item_dec[0]['stock_available'] >= $item_dec[0]['minimal_quantity']
                && $item_dec[0]['stock_available'] > 0) {
                    $commandable = 1;
                } else {
                    if ($item_dec[0]['out_of_stock'] == 0) {
                        $commandable = 0;
                    } elseif ($item_dec[0]['out_of_stock'] == 1) {
                        $commandable = 1;
                    } elseif ($item_dec[0]['out_of_stock'] == 2 && Configuration::get('PS_ORDER_OUT_OF_STOCK') == 1) {
                        $commandable = 1;
                    } elseif ($item_dec[0]['out_of_stock'] == 2 && Configuration::get('PS_ORDER_OUT_OF_STOCK') == 0) {
                        $commandable = 0;
                    }
                }
            }
            $config['declinaison'][$ipa][0]['commandable'] = $commandable;
            $sql = 'SELECT img.`id_image` 
                    FROM `'._DB_PREFIX_.'product_attribute_image` pai
                    LEFT JOIN `'._DB_PREFIX_.'image` img ON(img.`id_image` = pai.`id_image`) 
                    WHERE pai.`id_product_attribute` = '.(int)$ipa.'
                    ORDER BY img.`position`';
            $id_image = (int)Db::getInstance()->getValue($sql);
            $image_ids = (isset($id_image) && $id_image > 0)?
            $id_product.'-'.$id_image : $id_product.'-'.$id_image_default;
            if (isset($item_dec[0]['id_image'])) {
                $config['declinaison'][$ipa][0]['img']['src'] =
                    $link->getImageLink($link_rewrite, $image_ids, $name_img);
                $config['declinaison'][$ipa][0]['img']['title'] =
                    $item_dec[0]['name'];
                $config['declinaison'][$ipa][0]['img']['data_zoom'] =
                    $link->getImageLink($link_rewrite, $image_ids, $d_c_id_img_zoom);
            }

            //bullet
            if ($item_dec[0]['stock_available'] <= 0) {
                if ($commandable == 0) {
                    $bullet = 'bullet-red.png';
                    $title = $module->l('This product is no longer in stock');
                } else {
                    $bullet = 'bullet-yellow.png';
                    $title = $module->l('available later');
                }
            } else {
                $bullet = 'bullet-green.png';
                $title = $module->l('available now');
            }
            $config['declinaison'][$ipa][0]['bullet'] = $bullet;
            $config['declinaison'][$ipa][0]['bullet_title'] = $title;

            foreach ($item_dec as $item_attribute) {
                $config['declinaison'][$ipa][0]['list_noms_attributs'][$item_attribute['public_name']] = '';
            }
        }

        $config['id_img_to_display'] = (int)$config['d_c_id_img'];
        $config['name_img'] = $name_img;
        $config['d_c_id_img_zoom'] = $d_c_id_img_zoom;
        $config['id_image_default'] = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
            SELECT `id_image` FROM `'._DB_PREFIX_.'image` WHERE `id_product`='.(int)$id_product.' AND `cover` = 1');
        $config['ps_order_out_of_stock'] = Configuration::get('PS_ORDER_OUT_OF_STOCK');
        $config['ps_stock_management'] = Configuration::get('PS_STOCK_MANAGEMENT');
        $config['ps_last_qties'] = Configuration::get('PS_LAST_QTIES');
        $config['bgc'] = 0;
        $config['nbr_declinaisons'] = count($config['declinaison']);
        $config['col_img_dir'] = _PS_COL_IMG_DIR_;
        $config['ps_block_cart_ajax'] = (int)Configuration::get('PS_BLOCK_CART_AJAX');
        $config['add_anchor'] = (version_compare(_PS_VERSION_, '1.6', '<'))? false : true;
        $config['isps17'] = $module->isPS17();
        return $config;
    }
}
