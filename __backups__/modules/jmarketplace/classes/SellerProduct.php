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

class SellerProduct extends ObjectModel
{
    public static function associateSellerProduct($id_seller, $id_product)
    {
        Db::getInstance()->Execute(
            'INSERT INTO `' . _DB_PREFIX_ . 'seller_product` 
            (`id_seller_product`, `id_product`)
            VALUES ('.(int)$id_seller.', '.(int)$id_product.')'
        );
    }
    
    public static function existAssociationSellerProduct($id_product)
    {
        $query = 'SELECT id_seller_product FROM '._DB_PREFIX_.'seller_product 
                  WHERE id_product = '.(int)$id_product;
        $id_seller = Db::getInstance()->getValue($query);
        if ($id_seller) {
            return $id_seller;
        }
        return false;
    }
    
    public static function deleteSellerProduct($id_seller, $id_product)
    {
        Db::getInstance()->Execute(
            'DELETE FROM `' . _DB_PREFIX_ . 'seller_product` 
            WHERE id_seller_product = '.(int)$id_seller.' 
            AND id_product = '.(int)$id_product
        );
    }
    
    public static function isSellerProduct($id_product)
    {
        $query = 'SELECT id_seller_product FROM '._DB_PREFIX_.'seller_product 
                  WHERE id_product = '.(int)$id_product;
        $id_seller = Db::getInstance()->getValue($query);
        if ($id_seller) {
            return $id_seller;
        }
        return false;
    }
    
    public static function import($item, $files, $images, $id_lang)
    {
        if (isset($item['id_product'])) {
            $product = new Product((int)$item['id_product']);
        } else {
            $product = new Product();
        }
        
        if (Configuration::get('JMARKETPLACE_MODERATE_PRODUCT') == 1) {
            $product->active = 0;
        } else {
            $product->active = 1;
        }
        
        if (isset($item['reference'])) {
            $product->reference = Tools::stripslashes($item['reference']);
        }
        
        if (isset($item['isbn'])) {
            $product->isbn = Tools::stripslashes($item['isbn']);
        }
        
        if (isset($item['ean13'])) {
            $product->ean13 = Tools::stripslashes($item['ean13']);
        }
        
        if (isset($item['upc'])) {
            $product->upc = Tools::stripslashes($item['upc']);
        }
        
        if (isset($item['width'])) {
            $product->width = (float)$item['width'];
        }
        
        if (isset($item['height'])) {
            $product->height = (float)$item['height'];
        }
        
        if (isset($item['depth'])) {
            $product->depth = (float)$item['depth'];
        }
        
        if (isset($item['weight'])) {
            $product->weight = (float)$item['weight'];
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_AVAILABLE_ORD') == 1) {
            if (isset($item['available_for_order'])) {
                $product->available_for_order = 1;
            } else {
                $product->available_for_order = 0;
            }
        } else {
            $product->available_for_order = 1;
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_SHOW_PRICE') == 1) {
            if (isset($item['show_product_price'])) {
                $product->show_price = 1;
            } else {
                $product->show_price = 0;
            }
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_ONLINE_ONLY') == 1) {
            if (isset($item['online_only'])) {
                $product->online_only = 1;
            } else {
                $product->online_only = 0;
            }
        }
        
        if (isset($item['condition'])) {
            $product->condition = pSQL($item['condition']);
        }
        
        if (isset($item['quantity'])) {
            $product->quantity = (int)$item['quantity'];
        }
        
        if (isset($item['minimal_quantity'])) {
            $product->minimal_quantity = (int)$item['minimal_quantity'];
        }
        
        $search = array('<', '>', ';', '#', '=', '{', '}');
        $replace = " ";
        
        if (isset($item['available_now_'.$id_lang])) {
            foreach (Language::getLanguages() as $language) {
                if (isset($item['available_now_'.$language['id_lang']])) {
                    $product->available_now[$language['id_lang']] = Tools::stripslashes(trim(Tools::substr(str_replace($search, $replace, $item['available_now_'.$language['id_lang']]), 0, 126)));
                } else {
                    $product->available_now[$language['id_lang']] = Tools::stripslashes(trim(Tools::substr(str_replace($search, $replace, $item['available_now_'.$id_lang]), 0, 126)));
                }
            }
        }
        
        if (isset($item['available_later_'.$id_lang])) {
            foreach (Language::getLanguages() as $language) {
                if (isset($item['available_later_'.$language['id_lang']])) {
                    $product->available_later[$language['id_lang']] = Tools::stripslashes(trim(Tools::substr(str_replace($search, $replace, $item['available_later_'.$language['id_lang']]), 0, 126)));
                } else {
                    $product->available_later[$language['id_lang']] = Tools::stripslashes(trim(Tools::substr(str_replace($search, $replace, $item['available_later_'.$id_lang]), 0, 126)));
                }
            }
        }
        
        if (isset($item['available_date']) && $item['available_date'] != '0000-00-00') {
            $product->available_date = Tools::stripslashes($item['available_date']);
        } else {
            $product->available_date = '0000-00-00';
        }
        
        if (isset($item['price'])) {
            $item['price'] = str_replace(',', '.', $item['price']);
            if (Context::getContext()->currency->id != Configuration::get('PS_CURRENCY_DEFAULT')) {
                $product->price = Tools::ps_round((float)$item['price'] / Context::getContext()->currency->conversion_rate, 4);
            } else {
                $product->price = (float)$item['price'];
            }
        }
        
        if (isset($item['id_tax'])) {
            $product->id_tax_rules_group = (int)$item['id_tax'];
        }
        
        if (isset($item['wholesale_price'])) {
            $item['wholesale_price'] = str_replace(',', '.', $item['wholesale_price']);
            if (Context::getContext()->currency->id != Configuration::get('PS_CURRENCY_DEFAULT')) {
                $product->wholesale_price = Tools::ps_round((float)$item['wholesale_price'] / Context::getContext()->currency->conversion_rate, 4);
            } else {
                $product->wholesale_price = (float)$item['wholesale_price'];
            }
        }
        
        if (isset($item['unit_price'])) {
            $item['unit_price'] = str_replace(',', '.', $item['unit_price']);
            $product->unit_price = (float)$item['unit_price'];
            $product->unity = (string)$item['unity'];
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_ON_SALE') == 1) {
            if (isset($item['on_sale'])) {
                $product->on_sale = 1;
            } else {
                $product->on_sale = 0;
            }
        }
        
        if (!Shop::isFeatureActive()) {
            $product->shop = 1;
        } elseif (!isset($product->shop) || empty($product->shop)) {
            $product->shop = implode(',', Shop::getContextListShopID());
        }

        if (!Shop::isFeatureActive()) {
            $product->id_shop_default = 1;
        } else {
            $product->id_shop_default = (int)Context::getContext()->shop->id;
        }

        // link product to shops
        $product->id_shop_list = array();
        foreach (explode(',', $product->shop) as $shop) {
            if (!is_numeric($shop)) {
                $product->id_shop_list[] = Shop::getIdByName($shop);
            } else {
                $product->id_shop_list[] = $shop;
            }
        }
        
        foreach (Language::getLanguages() as $language) {
            if ($item['name_'.$language['id_lang']] != '') {
                $product->name[$language['id_lang']] = Tools::stripslashes(trim(Tools::substr(str_replace($search, $replace, (string)$item['name_'.$language['id_lang']]), 0, 126)));
            } else {
                $product->name[$language['id_lang']] = Tools::stripslashes(trim(Tools::substr(str_replace($search, $replace, (string)$item['name_'.$id_lang]), 0, 126)));
            }
        }
        
        if (isset($item['description_'.$id_lang])) {
            foreach (Language::getLanguages() as $language) {
                if ($item['description_'.$language['id_lang']] != '') {
                    $product->description[$language['id_lang']] = Tools::stripslashes(trim((string)$item['description_'.$language['id_lang']])); //this is content html
                } else {
                    $product->description[$language['id_lang']] = Tools::stripslashes(trim((string)$item['description_'.$id_lang])); //this is content html
                }
            }
        }
        
        if (isset($item['short_description_'.$id_lang])) {
            foreach (Language::getLanguages() as $language) {
                if ($item['short_description_'.$language['id_lang']] != '') {
                    $product->description_short[$language['id_lang']] = Tools::stripslashes(Tools::substr(trim((string)$item['short_description_'.$language['id_lang']]), 0, 800)); //this is content html
                } else {
                    $product->description_short[$language['id_lang']] = Tools::stripslashes(Tools::substr(trim((string)$item['short_description_'.$id_lang]), 0, 800)); //this is content html
                }
            }
        }
        
        if (isset($item['link_rewrite_'.$id_lang]) && $item['link_rewrite_'.$id_lang] != '') {
            foreach (Language::getLanguages() as $language) {
                if ($item['link_rewrite_'.$language['id_lang']] != '') {
                    $product->link_rewrite[$language['id_lang']] = Tools::stripslashes(trim(Tools::substr(str_replace($search, $replace, (string)Tools::link_rewrite(pSQL($item['link_rewrite_'.$language['id_lang']]))), 0, 126)));
                } else {
                    $product->link_rewrite[$language['id_lang']] = Tools::stripslashes(trim(Tools::substr(str_replace($search, $replace, (string)Tools::link_rewrite(pSQL($item['link_rewrite_'.$id_lang]))), 0, 126)));
                }
            }
        } else {
            foreach (Language::getLanguages() as $language) {
                if ($item['name_'.$language['id_lang']] != '') {
                    $product->link_rewrite[$language['id_lang']] = Tools::link_rewrite($product->name[$language['id_lang']]);
                } else {
                    $product->link_rewrite[$language['id_lang']] = Tools::link_rewrite($product->name[$id_lang]);
                }
            }
        }
        
        //metas
        if (isset($item['meta_keywords_'.$id_lang])) {
            foreach (Language::getLanguages() as $language) {
                if ($item['meta_keywords_'.$language['id_lang']] != '') {
                    $product->meta_keywords[$language['id_lang']] = Tools::stripslashes(trim($item['meta_keywords_'.$language['id_lang']]));
                } else {
                    $product->meta_keywords[$language['id_lang']] = Tools::stripslashes(trim($item['meta_keywords_'.$id_lang]));
                }
            }
        }
        
        if (isset($item['meta_title_'.$id_lang])) {
            foreach (Language::getLanguages() as $language) {
                if ($item['meta_title_'.$language['id_lang']] != '') {
                    $product->meta_title[$language['id_lang']] = Tools::stripslashes(trim(Tools::substr(str_replace($search, $replace, (string)$item['meta_title_'.$language['id_lang']]), 0, 126)));
                } else {
                    $product->meta_title[$language['id_lang']] = Tools::stripslashes(trim(Tools::substr(str_replace($search, $replace, (string)$item['meta_title_'.$id_lang]), 0, 126)));
                }
            }
        }
        
        if (isset($item['meta_description_'.$id_lang])) {
            foreach (Language::getLanguages() as $language) {
                if ($item['meta_description_'.$language['id_lang']] != '') {
                    $product->meta_description[$language['id_lang']] = Tools::stripslashes(trim(str_replace($search, $replace, (string)$item['meta_description_'.$language['id_lang']])));
                } else {
                    $product->meta_description[$language['id_lang']] = Tools::stripslashes(trim(str_replace($search, $replace, (string)$item['meta_description_'.$id_lang])));
                }
            }
        }
        
        if (isset($item['id_manufacturer'])) {
            $product->id_manufacturer = (int)$item['id_manufacturer'];
        }
        
        if (isset($item['new_manufacturer']) && $item['new_manufacturer'] != '') {
            if ($manufacturer = Manufacturer::getIdByName(pSQL($item['new_manufacturer']))) {
                $product->id_manufacturer = (int)$manufacturer;
            } else {
                $manufacturer = new Manufacturer();
                $manufacturer->name = Tools::stripslashes($item['new_manufacturer']);
                $manufacturer->active = 1;
                $manufacturer->add();
                $product->id_manufacturer = (int)$manufacturer->id;
            }
        }
        
        if (isset($item['id_supplier'])) {
            $product->id_supplier = (int)$item['id_supplier'];
        }
        
        if (isset($item['new_supplier']) && $item['new_supplier'] != '') {
            if ($supplier = Supplier::getIdByName($item['new_supplier'])) {
                $product->id_supplier = (int)$supplier;
            } else {
                $supplier = new Supplier();
                $supplier->name = Tools::stripslashes($item['new_supplier']);
                $supplier->active = 1;
                $supplier->add();
                $product->id_supplier = (int)$supplier->id;
            }
        }
        
        if (!isset($item['id_category_default'])) {
            $product->id_category_default = (int)Configuration::get('PS_HOME_CATEGORY');
            $item['categories'][] = Configuration::get('PS_HOME_CATEGORY');
        } elseif (isset($item['id_category_default']) && $item['id_category_default'] != 0) {
            $product->id_category_default = (int)$item['id_category_default'];
        } elseif (!isset($item['id_category_default']) || ($item['id_category_default'] == 0 && count($item['categories']) > 0)) {
            $product->id_category_default = (int)$item['categories'][0];
        }
        
        $edit_product = false;
        if (isset($item['id_product'])) {
            $product->update();
            $edit_product = true;
        } else {
            $product->add();
        }
        
        if (isset($item['quantity'])) {
            StockAvailable::setQuantity($product->id, 0, (int)$item['quantity']);
        }
        
        if (isset($item['out_of_stock'])) {
            StockAvailable::setProductOutOfStock($product->id, (int)$item['out_of_stock']);
        }
        
        if ((Configuration::get('JMARKETPLACE_SHOW_CATEGORIES') == 1 && $edit_product == true) || $edit_product == false) {
            //all categories
            $product->updateCategories($item['categories']);
        }
        
        //images
        $shops = array();
        $product_shop = explode(',', $product->shop);
        foreach ($product_shop as $shop) {
            $shop = trim($shop);
            if (!is_numeric($shop)) {
                $shop = ShopGroup::getIdByName($shop);
            }
            $shops[] = $shop;
        }
        
        if (empty($shops)) {
            $shops = Shop::getContextListShopID();
        }

        if (Configuration::get('JMARKETPLACE_MAX_IMAGES') > 0 && count($images) > 0) {
            for ($i=1; $i<=Configuration::get('JMARKETPLACE_MAX_IMAGES'); $i++) {
                if ($images[$i] != '' || ($edit_product && isset($item['legend_'.$i.'_'.$id_lang]))) {
                    $id_image = self::getIdImageByPosition($product->id, $i);

                    if ($id_image > 0) {
                        $image = new Image($id_image);
                    } elseif ($images[$i] == '' && !$id_image && $edit_product) {
                        break;
                    } else {
                        $image = new Image();
                    }

                    $image->id_product = $product->id;
                    $image->position = $i;

                    if ($i == 1) {
                        $image->cover = 1;
                    } else {
                        $image->cover = 0;
                    }
                    
                    foreach (Language::getLanguages() as $language) {
                        if (isset($item['legend_'.$i.'_'.$id_lang]) && $item['legend_'.$i.'_'.$id_lang] != '') {
                            if ($item['legend_'.$i.'_'.$language['id_lang']] != '') {
                                $image->legend[$language['id_lang']] = Tools::stripslashes($item['legend_'.$i.'_'.$language['id_lang']]);
                            } else {
                                $image->legend[$language['id_lang']] = Tools::stripslashes($item['legend_'.$i.'_'.$id_lang]);
                            }
                        } else {
                            $image->legend[$language['id_lang']] = $product->name[$id_lang];
                        }
                    }

                    if ($id_image > 0) {
                        $image->update();
                    } else {
                        $image->add();
                    }

                    $image->associateTo($shops);
                
                    if ($images[$i] != '') {
                        self::copyImg($product->id, $image->id, $images[$i]);
                    }
                }
            }
        }
            
        //supplier
        if (isset($product->id_supplier)) {
            $id_product_supplier = ProductSupplier::getIdByProductAndSupplier((int)$product->id, 0, (int)$product->id_supplier);
            if ($id_product_supplier) {
                $product_supplier = new ProductSupplier((int) $id_product_supplier);
            } else {
                $product_supplier = new ProductSupplier();
            }

            $product_supplier->id_product = $product->id;
            $product_supplier->id_product_attribute = 0;
            $product_supplier->id_supplier = $product->id_supplier;
            $product_supplier->product_supplier_price_te = $product->wholesale_price;

            if (($product_supplier->validateFields(false, true)) === true && $product_supplier->save()) {
                //DO NOTHING
                $product->id;
            }
        }
        
        if (isset($item['specific_price']) && $item['specific_price'] > 0) {
            $item['specific_price'] = str_replace(',', '.', $item['specific_price']);
            SpecificPrice::deleteByProductId($product->id);
            $specificPrice = new SpecificPrice();
            $specificPrice->id_product = $product->id;
            $specificPrice->id_shop = 0;
            $specificPrice->id_shop_group = 0;
            $specificPrice->id_currency = 0;
            $specificPrice->id_country = 0;
            $specificPrice->id_group = 0;
            $specificPrice->id_customer = 0;
            $specificPrice->id_product_attribute = 0;
            $specificPrice->price = -1;
            $specificPrice->from_quantity = 1;
            $specificPrice->reduction = (float)($item['price'] - $item['specific_price']);
            $specificPrice->reduction_tax = 0; //sin impuestos
            $specificPrice->reduction_type = 'amount';
            $specificPrice->from = '0000-00-00 00:00:00';
            $specificPrice->to = '0000-00-00 00:00:00';
            $specificPrice->save();
        } else {
            SpecificPrice::deleteByProductId($product->id);
        }
        
        if (isset($item['carriers'])) {
            if (Configuration::get('JMARKETPLACE_SHOW_SHIP_PRODUCT') == 1 && is_array($item['carriers']) && count($item['carriers']) > 0) {
                $product->setCarriers($item['carriers']);
                $product->additional_shipping_cost = (float)$item['additional_shipping_cost'];
                $product->save();
            }
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_FEATURES') == 1) {
            // delete all objects
            $product->deleteFeatures();
            $features = Feature::getFeatures($id_lang);
            
            foreach ($features as $feature) {
                //selects
                if (isset($item['feature_value_'.$feature['id_feature']])) {
                    $feature_name = $feature['name'];
                    $featureValue = new FeatureValue((int)$item['feature_value_'.$feature['id_feature']], $id_lang);
                    $feature_value = $featureValue->value;
                    //$position = isset($tab_feature[2]) ? $tab_feature[2]: false;
                    if (!empty($feature_name) && !empty($feature_value)) {
                        $id_feature = Feature::addFeatureImport($feature_name);
                        $id_feature_value = FeatureValue::addFeatureValueImport($id_feature, $feature_value, $product->id, $id_lang);
                        Product::addFeatureProductImport($product->id, $id_feature, $id_feature_value);
                    }
                }
                foreach (Language::getLanguages() as $language) {
                    if (isset($item['feature_value_'.$feature['id_feature'].'_'.$language['id_lang']])) {
                        $feature_name = pSQL($feature['name']);
                        $feature_value = (string)$item['feature_value_'.$feature['id_feature'].'_'.$language['id_lang']];
                        if (!empty($feature_name) && !empty($feature_value)) {
                            $id_feature = Feature::addFeatureImport($feature_name);
                            $id_feature_value = FeatureValue::addFeatureValueImport($id_feature, $feature_value, $product->id, $language['id_lang'], true);
                            Product::addFeatureProductImport($product->id, $id_feature, $id_feature_value);
                        }
                    }
                }
            }
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_ATTRIBUTES') == 1 && !empty($item['attributes'])) {
            $groups = array();
            $info = array();
            foreach (AttributeGroup::getAttributesGroups($id_lang) as $group) {
                $groups[trim($group['name'])] = (int)$group['id_attribute_group'];
            }

            $attributes = array();
            foreach (Attribute::getAttributes($id_lang) as $attribute) {
                $attributes[trim($attribute['attribute_group']).'_'.trim($attribute['name'])] = (int)$attribute['id_attribute'];
            }

            if (!Shop::isFeatureActive()) {
                $info['shop'] = 1;
            } elseif (!isset($info['shop']) || empty($info['shop'])) {
                $info['shop'] = implode(',', Shop::getContextListShopID());
            }

            // Get shops for each attributes
            $info['shop'] = explode(',', $info['shop']);

            $id_shop_list = array();
            foreach ($info['shop'] as $shop) {
                if (!is_numeric($shop)) {
                    $id_shop_list[] = Shop::getIdByName($shop);
                } else {
                    $id_shop_list[] = $shop;
                }
            }

            $id_attribute_group = 0;
            // groups
            $groups_attributes = array();

            $counter = 0;
            foreach ($item['attributes'] as $combination) {
                $reference = $item['combination_reference'][$counter];
                $quantity = (int)$item['combination_qty'][$counter];
                $price = (float)$item['combination_price'][$counter];
                $weight = (float)$item['combination_weight'][$counter];
                
                if (isset($combination)) {
                    //Color : Gris pardo, Size : M
                    foreach (explode(',', $combination) as $key => $group) {
                        $tab_group = explode(':', $group); //Color : Gris pardo
                        $group = trim($tab_group[0]); //Color
                        // sets group
                        $groups_attributes[$key]['group'] = $group; //Color
                        $position = false;

                        if (!isset($groups[$group])) {
                            $obj = new AttributeGroup();
                            $obj->is_color_group = false;
                            $obj->group_type = pSQL('select');
                            $obj->name[$id_lang] = pSQL($group);
                            $obj->public_name[$id_lang] = pSQL($group);
                            $obj->position = (!$position) ? AttributeGroup::getHigherPosition() + 1 : $position;
                            // fils groups attributes
                            $id_attribute_group = $obj->id;
                            $groups_attributes[$key]['id'] = $id_attribute_group;
                        } else {
                            //alreay exists
                            $id_attribute_group = $groups[$group];
                            $groups_attributes[$key]['id'] = $id_attribute_group;
                        }
                    }
                }

                //inits attribute
                $id_product_attribute = 0;
                //$id_product_attribute_update = false;
                $attributes_to_add = array();

                //for each attribute
                if (isset($combination)) {
                    foreach (explode(',', $combination) as $key => $attribute) {
                        $tab_attribute = explode(':', $attribute);
                        $attribute = trim($tab_attribute[1]);
                        $position = false;

                        if (isset($groups_attributes[$key])) {
                            $group = $groups_attributes[$key]['group'];
                            if (!isset($attributes[$group.'_'.$attribute]) && count($groups_attributes[$key]) == 2) {
                                $id_attribute_group = $groups_attributes[$key]['id'];
                                $obj = new Attribute();
                                //sets the proper id (corresponding to the right key)
                                $obj->id_attribute_group = (int)$groups_attributes[$key]['id'];
                                $obj->name[$id_lang] = str_replace('\n', '', str_replace('\r', '', $attribute));
                                $obj->position = (!$position && isset($groups[$group])) ? Attribute::getHigherPosition($groups[$group]) + 1 : $position;
                            }

                            //if a reference is specified for this product, get the associate id_product_attribute to UPDATE
                            if (isset($item['id_product_attributes'][$counter]) && !empty($item['id_product_attributes'][$counter])) {
                                $id_product_attribute = $item['id_product_attributes'][$counter];
                                $id_product_attribute = SellerProduct::existCombination($product->id, $id_product_attribute);

                                //updates the attribute
                                if ($id_product_attribute) {
                                    //gets all the combinations of this product
                                    $attribute_combinations = $product->getAttributeCombinations($id_lang);
                                    foreach ($attribute_combinations as $attribute_combination) {
                                        if ($id_product_attribute && in_array($id_product_attribute, $attribute_combination)) {
                                            $product->updateAttribute(
                                                $id_product_attribute,
                                                0.00,
                                                $price,
                                                $weight,
                                                0.00,
                                                0.00,
                                                false,
                                                $reference,
                                                '',
                                                0,
                                                null,
                                                null,
                                                null,
                                                null,
                                                true,
                                                $id_shop_list
                                            );
                                        }
                                    }
                                }
                            }

                            //if no attribute reference is specified, creates a new one
                            if (!$id_product_attribute) {
                                $id_product_attribute = $product->addCombinationEntity(
                                    0.00,
                                    $price,
                                    $weight,
                                    0.00,
                                    0.00,
                                    $quantity,
                                    false,
                                    $reference,
                                    0,
                                    '',
                                    0,
                                    null,
                                    null,
                                    1,
                                    $id_shop_list,
                                    null
                                );
                            }

                            //fills our attributes array, in order to add the attributes to the product_attribute afterwards
                            if (isset($attributes[$group.'_'.$attribute])) {
                                $attributes_to_add[] = (int)$attributes[$group.'_'.$attribute];
                            }

                            //after insertion, we clean attribute position and group attribute position
                            $obj = new Attribute();
                            $obj->cleanPositions((int)$id_attribute_group, false);
                            AttributeGroup::cleanPositions();
                        }
                    }
                }

                $product->checkDefaultAttributes();
                if (!$product->cache_default_attribute) {
                    Product::updateDefaultAttribute($product->id);
                }
                
                if ($id_product_attribute) {
                    foreach ($attributes_to_add as $attribute_to_add) {
                        Db::getInstance()->execute(
                            'INSERT IGNORE INTO '._DB_PREFIX_.'product_attribute_combination (id_attribute, id_product_attribute)
                            VALUES ('.(int)$attribute_to_add.','.(int)$id_product_attribute.')'
                        );
                    }

                    StockAvailable::setQuantity($product->id, $id_product_attribute, $quantity);
                    
                    if (isset($item['out_of_stock'])) {
                        StockAvailable::setProductOutOfStock($product->id, $item['out_of_stock'], null, $id_product_attribute);
                    }
                }
                $counter++;
            }
        }
        
        //virtual product
        if (isset($item['type_product']) && $item['type_product'] == 2 && $files['virtual_file']['size'] > 0) {
            if ($product->is_virtual == 1) {
                $id_product_download = ProductDownload::getIdFromIdProduct((int)$product->id);
                $product_download = new ProductDownload((int)$id_product_download);
                $product->is_virtual = 0;
                $product->update();
                $product_download->delete((int)$id_product_download);
            }

            $filename = ProductDownload::getNewFilename();
            $id_product_download = ProductDownload::getIdFromFilename($filename);

            $download = new ProductDownload((int)$id_product_download);
            $download->id_product = (int)$product->id;

            if ($files['virtual_file']['size'] > 0) {
                $download->display_filename = Tools::stripslashes($files['virtual_file']['name']);
            }
            
            $download->filename = pSQL($filename);

            if ($item['virtual_product_expiration_date'] != "" && $item['virtual_product_expiration_date'] != "0000-00-00") {
                $download->date_expiration = pSQL($item['virtual_product_expiration_date']).' 00:00:00';
            }

            if ($item['virtual_product_nb_days'] == "") {
                $download->nb_days_accessible = 0;
            } else {
                $download->nb_days_accessible = (int)$item['virtual_product_nb_days'];
            }

            if ($item['virtual_product_nb_downloable'] == "") {
                $download->nb_downloadable = 0;
            } else {
                $download->nb_downloadable = (int)$item['virtual_product_nb_downloable'];
            }

            $download->active = 1;

            if (!Tools::copy($files['virtual_file']['tmp_name'], _PS_DOWNLOAD_DIR_.$filename)) {
                header('HTTP/1.1 500 Error');
                echo '<return result="error" msg="No permissions to write in the download folder" filename="'.Tools::safeOutput($filename).'" />';
            }

            $download->save();
            
            $product->is_virtual = 1;
            $product->update();
        }
        
        //attachments
        if (Configuration::get('JMARKETPLACE_SHOW_ATTACHMENTS') == 1 && count($_FILES['attachments']['name']) > 0) {
            for ($i=1; $i<=Configuration::get('JMARKETPLACE_MAX_ATTACHMENTS'); $i++) {
                if (isset($_FILES['attachments']['error'][$i]) && $_FILES['attachments']['error'][$i] == 0) {
                    if (isset($item['id_attachment_'.$i])) {
                        SellerProduct::deleteAttachment($product->id, $item['id_attachment_'.$i]);
                        $attachment = new Attachment($item['id_attachment_'.$i]);
                    } else {
                        $attachment = new Attachment();
                    }
                        
                    $uniqid = sha1(microtime());
                    copy($_FILES['attachments']['tmp_name'][$i], _PS_DOWNLOAD_DIR_.$uniqid);

                    foreach (Language::getLanguages() as $language) {
                        if (!empty($item['attachment_name_'.$i.'_'.$id_lang])) {
                            if ($item['attachment_name_'.$i.'_'.$language['id_lang']] != '') {
                                $attachment->name[$language['id_lang']] = Tools::stripslashes($item['attachment_name_'.$i.'_'.$language['id_lang']]);
                            } else {
                                $attachment->name[$language['id_lang']] = Tools::stripslashes($item['attachment_name_'.$i.'_'.$id_lang]);
                            }
                        } else {
                            $attachment->name[$language['id_lang']] = Tools::stripslashes(Tools::substr($_FILES['attachments']['name'][$i], 0, 30));
                        }
                    }
                    
                    foreach (Language::getLanguages() as $language) {
                        if ($item['attachment_description_'.$i.'_'.$language['id_lang']] != '') {
                            $attachment->description[$language['id_lang']] = Tools::stripslashes($item['attachment_description_'.$i.'_'.$language['id_lang']]);
                        } else {
                            $attachment->description[$language['id_lang']] = Tools::stripslashes($item['attachment_description_'.$i.'_'.$id_lang]);
                        }
                    }

                    $attachment->file = $uniqid;
                    $attachment->mime = $_FILES['attachments']['type'][$i];
                    $attachment->file_name = $_FILES['attachments']['name'][$i];
                    
                    if (isset($item['id_attachment_'.$i])) {
                        $attachment->update();
                    } else {
                        $attachment->add();
                    }

                    $attachment->attachProduct($product->id);
                }
            }
        }
        
        Search::indexation(Tools::link_rewrite($product->name), $product->id);
        
        return $product->id;
    }
    
    public static function existCombination($id_product, $id_product_attribute)
    {
        if (empty($id_product_attribute)) {
            return 0;
        }

        $query = new DbQuery();
        $query->select('pa.id_product_attribute');
        $query->from('product_attribute', 'pa');
        $query->where('pa.id_product_attribute = '.(int)$id_product_attribute);
        $query->where('pa.id_product = '.(int)$id_product);

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
    }
    
    /*Checking customs feature */
    protected function checkFeatures($languages, $feature_id)
    {
        $rules = call_user_func(array('FeatureValue', 'getValidationRules'), 'FeatureValue');
        $feature = Feature::getFeature((int)Configuration::get('PS_LANG_DEFAULT'), (int)$feature_id);

        foreach ($languages as $language) {
            if ($val = Tools::getValue('custom_'.$feature_id.'_'.$language['id_lang'])) {
                $current_language = new Language($language['id_lang']);
                if (Tools::strlen($val) > $rules['sizeLang']['value']) {
                    $this->errors[] = sprintf(
                        Tools::displayError('The name for feature %1$s is too long in %2$s.'),
                        ' <b>'.$feature['name'].'</b>',
                        $current_language->name
                    );
                } elseif (!call_user_func(array('Validate', $rules['validateLang']['value']), $val)) {
                    $this->errors[] = sprintf(
                        Tools::displayError('A valid name required for feature. %1$s in %2$s.'),
                        ' <b>'.$feature['name'].'</b>',
                        $current_language->name
                    );
                }
                
                if (count($this->errors)) {
                    return 0;
                }
                
                // Getting default language
                if ($language['id_lang'] == Configuration::get('PS_LANG_DEFAULT')) {
                    return $val;
                }
            }
        }
        return 0;
    }
    
    protected static function createMultiLangField($field)
    {
        $languages = Language::getLanguages(false);
        $res = array();
        foreach ($languages as $lang) {
            $res[$lang['id_lang']] = $field;
        }
        return $res;
    }
    
    private static function copyImg($id_entity, $id_image = null, $url = '', $entity = 'products')
    {
        $tmpfile = tempnam(_PS_TMP_IMG_DIR_, _DB_PREFIX_ . 'import');
        $watermark_types = explode(',', Configuration::get('WATERMARK_TYPES'));

        switch ($entity) {
            default:
            case 'products':
                $imageObj = new Image($id_image);
                $path = $imageObj->getPathForCreation();
                break;
            case 'categories':
                $path = _PS_CAT_IMG_DIR_ . (int) ($id_entity);
                break;
        }

        if (Tools::copy(trim($url), $tmpfile)) {
            ImageManager::resize($tmpfile, $path . '.jpg');
            $imagesTypes = ImageType::getImagesTypes($entity);
            foreach ($imagesTypes as $imageType) {
                ImageManager::resize($tmpfile, $path . '-' . Tools::stripslashes($imageType['name']) . '.jpg', $imageType['width'], $imageType['height']);
            }
            
            if (in_array($imageType['id_image_type'], $watermark_types)) {
                Module::hookExec('watermark', array('id_image' => $id_image, 'id_product' => $id_entity));
            }
        } else {
            unlink($tmpfile);
            return false;
        }
        unlink($tmpfile);
        return true;
    }
    
    public static function getImageDimensions()
    {
        return Db::getInstance()->getRow(
            'SELECT width, height FROM `'._DB_PREFIX_.'image_type` 
            WHERE name = "thickbox_default"'
        );
    }
    
    public static function getIdImageByPosition($id_product, $position)
    {
        return Db::getInstance()->getValue(
            'SELECT id_image FROM `'._DB_PREFIX_.'image` 
            WHERE id_product = '.(int)$id_product.' 
            AND position = '.(int)$position
        );
    }
    
    public static function getSimpleProductsWithReference($id_lang)
    {
        $front = false;
        return Db::getInstance()->executeS(
            'SELECT p.`id_product`, CONCAT(p.reference," - ", pl.`name`) as refname
            FROM `'._DB_PREFIX_.'product` p
            '.Shop::addSqlAssociation('product', 'p').'
            LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` '.Shop::addSqlRestrictionOnLang('pl').')
            WHERE pl.`id_lang` = '.(int)$id_lang.'
            '.($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '').' AND p.active = 1
            ORDER BY pl.`name`'
        );
    }
    
    /**
    * Delete product attachments
    *
    * @param bool $update_cache If set to true attachment cache will be updated
    * @return array Deletion result
    */
    public static function deleteAttachment($id_product, $id_attachment, $update_attachment_cache = true)
    {
        $res = Db::getInstance()->execute(
            'DELETE FROM `'._DB_PREFIX_.'product_attachment`
            WHERE `id_product` = '.(int)$id_product.' 
            AND id_attachment = '.(int)$id_attachment
        );

        if (isset($update_attachment_cache) && (bool)$update_attachment_cache === true) {
            Product::updateCacheAttachment((int)$id_product);
        }

        return $res;
    }
}
