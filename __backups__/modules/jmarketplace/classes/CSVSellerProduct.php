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

class CSVSellerProduct extends Jmarketplace
{
    public $file;
    public $separator = ';';
    public $rows = 0;
    public $invalid = 0;
    public $added;
    public $updated = 0;
    public $fields = array();
    
    public function getFields()
    {
        $this->fields[] = 'id_product';

        if (Configuration::get('JMARKETPLACE_SHOW_SUPPLIERS') == 1) {
            $this->fields[] = 'id_supplier';
        }

        if (Configuration::get('JMARKETPLACE_SHOW_MANUFACTURERS') == 1) {
            $this->fields[] = 'id_manufacturer';
        }

        if (Configuration::get('JMARKETPLACE_SHOW_CATEGORIES') == 1) {
            $this->fields[] = 'id_category_default';
            $this->fields[] = 'categories';
        }

        if (Configuration::get('JMARKETPLACE_SHOW_TAX') == 1) {
            $this->fields[] = 'id_tax_rules_group';
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_ISBN') == 1) {
            $this->fields[] = 'isbn';
        }

        if (Configuration::get('JMARKETPLACE_SHOW_EAN13') == 1) {
            $this->fields[] = 'ean13';
        }

        if (Configuration::get('JMARKETPLACE_SHOW_UPC') == 1) {
            $this->fields[] = 'upc';
        }

        if (Configuration::get('JMARKETPLACE_SHOW_QUANTITY') == 1) {
            $this->fields[] = 'quantity';
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_PRICE') == 1) {
            $this->fields[] = 'wholesale_price';
        }

        if (Configuration::get('JMARKETPLACE_SHOW_PRICE') == 1) {
            $this->fields[] = 'price';
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_UNIT_PRICE') == 1) {
            $this->fields[] = 'unit_price';
            $this->fields[] = 'unity';
        }

        if (Configuration::get('JMARKETPLACE_SHOW_SHIP_PRODUCT') == 1) {
            $this->fields[] = 'additional_shipping_cost';
        }

        if (Configuration::get('JMARKETPLACE_SHOW_REFERENCE') == 1) {
            $this->fields[] = 'reference';
        }

        if (Configuration::get('JMARKETPLACE_SHOW_WIDTH') == 1) {
            $this->fields[] = 'width';
        }

        if (Configuration::get('JMARKETPLACE_SHOW_HEIGHT') == 1) {
            $this->fields[] = 'height';
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_DEPTH') == 1) {
            $this->fields[] = 'depth';
        }

        if (Configuration::get('JMARKETPLACE_SHOW_WEIGHT') == 1) {
            $this->fields[] = 'weight';
        }

        if (Configuration::get('JMARKETPLACE_SHOW_CONDITION') == 1) {
            $this->fields[] = 'condition';
        }

        if (Configuration::get('JMARKETPLACE_SHOW_DESC') == 1) {
            $this->fields[] = 'description';
        }

        if (Configuration::get('JMARKETPLACE_SHOW_DESC_SHORT') == 1) {
            $this->fields[] = 'description_short';
        }

        if (Configuration::get('JMARKETPLACE_SHOW_LINK_REWRITE') == 1) {
            $this->fields[] = 'link_rewrite';
        }

        if (Configuration::get('JMARKETPLACE_SHOW_META_DESC') == 1) {
            $this->fields[] = 'meta_description';
        }

        if (Configuration::get('JMARKETPLACE_SHOW_META_KEYWORDS') == 1) {
            $this->fields[] = 'meta_keywords';
        }

        if (Configuration::get('JMARKETPLACE_SHOW_META_TITLE') == 1) {
            $this->fields[] = 'meta_title';
        }

        $this->fields[] = 'name';

        if (Configuration::get('JMARKETPLACE_SHOW_IMAGES') == 1) {
            $this->fields[] = 'images';
        }
        
        return $this->fields;
    }
    
    public function export($id_seller)
    {
        $line = '';
        $seller = new Seller($id_seller);
        $this->file = 'products_'.$id_seller.'.csv';
        $fp = fopen(dirname(__FILE__).'/../export/'.$this->file, "w");
        
        $line = 'id_product'.$this->separator;

        if (Configuration::get('JMARKETPLACE_SHOW_SUPPLIERS') == 1) {
            $line .= 'id_supplier'.$this->separator;
        }

        if (Configuration::get('JMARKETPLACE_SHOW_MANUFACTURERS') == 1) {
            $line .= 'id_manufacturer'.$this->separator;
        }

        if (Configuration::get('JMARKETPLACE_SHOW_CATEGORIES') == 1) {
            $line .= 'id_category_default'.$this->separator;
            $line .= 'categories'.$this->separator;
        }

        if (Configuration::get('JMARKETPLACE_SHOW_TAX') == 1) {
            $line .= 'id_tax_rules_group'.$this->separator;
        }

        if (Configuration::get('JMARKETPLACE_SHOW_ISBN') == 1) {
            $line .= 'isbn'.$this->separator;
        }

        if (Configuration::get('JMARKETPLACE_SHOW_EAN13') == 1) {
            $line .= 'ean13'.$this->separator;
        }

        if (Configuration::get('JMARKETPLACE_SHOW_UPC') == 1) {
            $line .= 'upc'.$this->separator;
        }

        if (Configuration::get('JMARKETPLACE_SHOW_QUANTITY') == 1) {
            $line .= 'quantity'.$this->separator;
        }

        if (Configuration::get('JMARKETPLACE_SHOW_WHOLESALEPRICE') == 1) {
            $line .= 'wholesale_price'.$this->separator;
        }

        if (Configuration::get('JMARKETPLACE_SHOW_PRICE') == 1) {
            $line .= 'price'.$this->separator;
        }

        if (Configuration::get('JMARKETPLACE_SHOW_UNIT_PRICE') == 1) {
            $line .= 'unit_price'.$this->separator;
            $line .= 'unity'.$this->separator;
        }

        if (Configuration::get('JMARKETPLACE_SHOW_SHIP_PRODUCT') == 1) {
            $line .= 'additional_shipping_cost'.$this->separator;
        }

        if (Configuration::get('JMARKETPLACE_SHOW_REFERENCE') == 1) {
            $line .= 'reference'.$this->separator;
        }

        if (Configuration::get('JMARKETPLACE_SHOW_WIDTH') == 1) {
            $line .= 'width'.$this->separator;
        }

        if (Configuration::get('JMARKETPLACE_SHOW_HEIGHT') == 1) {
            $line .= 'height'.$this->separator;
        }

        if (Configuration::get('JMARKETPLACE_SHOW_DEPTH') == 1) {
            $line .= 'depth'.$this->separator;
        }

        if (Configuration::get('JMARKETPLACE_SHOW_WEIGHT') == 1) {
            $line .= 'weight'.$this->separator;
        }

        if (Configuration::get('JMARKETPLACE_SHOW_CONDITION') == 1) {
            $line .= 'condition'.$this->separator;
        }

        if (Configuration::get('JMARKETPLACE_SHOW_DESC') == 1) {
            $line .= 'description'.$this->separator;
        }

        if (Configuration::get('JMARKETPLACE_SHOW_DESC_SHORT') == 1) {
            $line .= 'description_short'.$this->separator;
        }

        if (Configuration::get('JMARKETPLACE_SHOW_LINK_REWRITE') == 1) {
            $line .= 'link_rewrite'.$this->separator;
        }

        if (Configuration::get('JMARKETPLACE_SHOW_META_DESC') == 1) {
            $line .= 'meta_description'.$this->separator;
        }

        if (Configuration::get('JMARKETPLACE_SHOW_META_KEYWORDS') == 1) {
            $line .= 'meta_keywords'.$this->separator;
        }

        if (Configuration::get('JMARKETPLACE_SHOW_META_TITLE') == 1) {
            $line .= 'meta_title'.$this->separator;
        }

        $line .= 'name'.$this->separator;

        if (Configuration::get('JMARKETPLACE_SHOW_IMAGES') == 1) {
            $line .= 'images'.$this->separator;
        }

        $line = Tools::substr($line, 0, -1);
        $line .= "\n";

        fwrite($fp, $line);

        $products = $seller->getProducts(Context::getContext()->language->id, 0, 9999, 'date_add', 'ASC', false, false);

        if (count($products) >= 1) {
            foreach ($products as $p) {
                $product = new Product($p['id_product'], false, Context::getContext()->language->id, Context::getContext()->shop->id);

                $p['name'] = str_replace("&", "y", $p['name']);
                
                $p['description'] = str_replace("&", "y", $p['description']);
                $p['description'] = str_replace(";", "", $p['description']);
                $p['description'] = str_replace("\r\n", "", $p['description']);
                $p['description'] = str_replace("\n", "", $p['description']);

                $p['meta_description'] = str_replace("&", "y", $p['meta_description']);
                $p['meta_description'] = str_replace("/", " y ", $p['meta_description']);

                $p['meta_keywords'] = str_replace("&", "y", $p['meta_keywords']);

                $p['meta_title'] = str_replace("&", "y", $p['meta_title']);

                $line = $p['id_product'].$this->separator;

                if (Configuration::get('JMARKETPLACE_SHOW_SUPPLIERS') == 1) {
                    $line .= $p['id_supplier'].$this->separator;
                }

                if (Configuration::get('JMARKETPLACE_SHOW_MANUFACTURERS') == 1) {
                    $line .= $p['id_manufacturer'].$this->separator;
                }

                if (Configuration::get('JMARKETPLACE_SHOW_CATEGORIES') == 1) {
                    $line .= $p['id_category_default'].$this->separator;

                    //all categories
                    $id_categories_array = $product->getWsCategories();

                    $id_categories_string = '';
                    foreach ($id_categories_array as $value) {
                        $id_categories_string .= $value['id'].',';
                    }

                    $id_categories_string = Tools::substr($id_categories_string, 0, -1);

                    $line .= $id_categories_string.$this->separator;
                }

                if (Configuration::get('JMARKETPLACE_SHOW_TAX') == 1) {
                    $line .= $p['id_tax_rules_group'].$this->separator;
                }

                if (Configuration::get('JMARKETPLACE_SHOW_ISBN') == 1) {
                    $line .= $p['isbn'].$this->separator;
                }

                if (Configuration::get('JMARKETPLACE_SHOW_EAN13') == 1) {
                    $line .= $p['ean13'].$this->separator;
                }

                if (Configuration::get('JMARKETPLACE_SHOW_UPC') == 1) {
                    $line .= $p['upc'].$this->separator;
                }

                if (Configuration::get('JMARKETPLACE_SHOW_QUANTITY') == 1) {
                    $line .= $p['quantity'].$this->separator;
                }

                if (Configuration::get('JMARKETPLACE_SHOW_WHOLESALEPRICE') == 1) {
                    $line .= Tools::ps_round($product->wholesale_price, 2).$this->separator;
                }

                if (Configuration::get('JMARKETPLACE_SHOW_PRICE') == 1) {
                    $line .= Tools::ps_round($product->price, 2).$this->separator;
                }

                if (Configuration::get('JMARKETPLACE_SHOW_UNIT_PRICE') == 1) {
                    if ($product->unit_price_ratio > 0) {
                        $product->unit_price = $product->price / $product->unit_price_ratio;
                    } else {
                        $product->unit_price = 0;
                    }
                    $line .= Tools::ps_round($product->unit_price, 2).$this->separator;
                    $line .= $product->unity.$this->separator;
                }

                if (Configuration::get('JMARKETPLACE_SHOW_SHIP_PRODUCT') == 1) {
                    $line .= Tools::ps_round($p['additional_shipping_cost'], 2).$this->separator;
                }

                if (Configuration::get('JMARKETPLACE_SHOW_REFERENCE') == 1) {
                    $line .= $p['reference'].$this->separator;
                }

                if (Configuration::get('JMARKETPLACE_SHOW_WIDTH') == 1) {
                    $line .= Tools::ps_round($p['width'], 2).$this->separator;
                }

                if (Configuration::get('JMARKETPLACE_SHOW_HEIGHT') == 1) {
                    $line .= Tools::ps_round($p['height'], 2).$this->separator;
                }

                if (Configuration::get('JMARKETPLACE_SHOW_DEPTH') == 1) {
                    $line .= Tools::ps_round($p['depth'], 2).$this->separator;
                }

                if (Configuration::get('JMARKETPLACE_SHOW_WEIGHT') == 1) {
                    $line .= Tools::ps_round($p['weight'], 2).$this->separator;
                }

                if (Configuration::get('JMARKETPLACE_SHOW_CONDITION') == 1) {
                    $line .= $p['condition'].$this->separator;
                }

                if (Configuration::get('JMARKETPLACE_SHOW_DESC') == 1) {
                    $line .= utf8_decode($p['description']).$this->separator;
                }

                if (Configuration::get('JMARKETPLACE_SHOW_DESC_SHORT') == 1) {
                    $line .= utf8_decode($p['description_short']).$this->separator;
                }

                if (Configuration::get('JMARKETPLACE_SHOW_LINK_REWRITE') == 1) {
                    $line .= $p['link_rewrite'].$this->separator;
                }

                if (Configuration::get('JMARKETPLACE_SHOW_META_DESC') == 1) {
                    $line .= utf8_decode($p['meta_description']).$this->separator;
                }

                if (Configuration::get('JMARKETPLACE_SHOW_META_KEYWORDS') == 1) {
                    $line .= utf8_decode($p['meta_keywords']).$this->separator;
                }

                if (Configuration::get('JMARKETPLACE_SHOW_META_TITLE') == 1) {
                    $line .= utf8_decode($p['meta_title']).$this->separator;
                }

                $line .= utf8_decode($p['name']).$this->separator;

                if (Configuration::get('JMARKETPLACE_SHOW_IMAGES') == 1) {
                    $images = $product->getImages(Context::getContext()->language->id);
                    $url_images = '';
                    foreach ($images as $image) {
                        $url_images .= Context::getContext()->link->getImageLink($product->link_rewrite, $image['id_image']).',';
                    }
                    $url_images = Tools::substr($url_images, 0, -1);

                    $line .= $url_images.$this->separator;
                }

                $line = Tools::substr($line, 0, -1);
                $line .= "\n";

                fwrite($fp, $line);
            }
        }

        fclose($fp);
        return count($products);
    }
    
    public static function getShortString($string)
    {
        return Tools::substr(strip_tags($string), 0, 30).'...';
    }
    
    public function getFirstLine($file)
    {
        $file_import = dirname(__FILE__).'/../import/'.$file['name'];
        if (@move_uploaded_file($file['tmp_name'], $file_import)) {
            //chmod($file_import,  0777);
            $handle = fopen($file_import, 'r');
            $row = 0;
            $html = '';
            while ($data = fgetcsv($handle, 9999, ";")) {
                $html .= '<tr>';
                for ($i = 0; $i<30; $i++) {
                    if (isset($data[$i])) {
                        if (Tools::strlen($data[$i]) > 30) {
                            $html .= '<td>'.CSVSellerProduct::getShortString(utf8_encode($data[$i])).'</td>';
                        } else {
                            $html .= '<td>'.utf8_encode(strip_tags($data[$i])).'</td>';
                        }
                    }
                }
                
                $html .= '</tr>';
                
                $row++;
                
                if ($row > 5) {
                    break;
                }
            }

            fclose($handle);
            return $html;
        }
    }
    
    public function getHeaderLine($file)
    {
        $file_import = dirname(__FILE__).'/../import/'.$file['name'];
        $handle = fopen($file_import, 'r');
        $html = '';
        $fields = $this->getFields();
        while ($data = fgetcsv($handle, 9999, ";")) {
            for ($i = 0; $i<count($data); $i++) {
                if (isset($data[$i]) && $data[$i] != '') {
                    $html .= '<th>';
                    $html .= '<select name="type_value['.$i.']" id="type_value['.$i.']">';
                    $html .= '<option value="0">Ignore this column</option>';
                    foreach ($fields as $field) {
                        $selected = '';
                        if ($field == $data[$i]) {
                            $selected = 'selected="selected"';
                        }
                        $html .= '<option value="'.$field.'" '.$selected.'>'.$field.'</option>';
                    }
                    $html .= '</select>';
                    $html .= '</th>';
                }
            }
            break;
        }
        fclose($handle);
        return $html;
    }
    
    public function import($id_seller, $filename, $id_lang, $type_value, $match_ref)
    {
        $file_import = dirname(__FILE__).'/../import/'.$filename;
        
        CSVSellerProductLog::create('w', $id_seller);

        $handle = fopen($file_import, 'r');
        $row = 0;
        $header = array();
        $product = array();
        $url_images = array();
        
        while ($data = fgetcsv($handle, 9999, ";")) {
            if ($row == 0) {
                if (!in_array('name', $type_value)) {
                    return 'error_name';
                }
                
                if (!in_array('reference', $type_value) && $match_ref == 1) {
                    return 'error_reference';
                }
                
                $header = $type_value;
            }

            if ($row > 0) {
                if ($this->validateRow($header, $data, $row, $id_seller, $match_ref)) {
                    foreach ($header as $key => $value) {
                        //languages
                        if ($value == 'name' ||
                                $value == 'link_rewrite' ||
                                $value == 'description' ||
                                $value == 'description_short' ||
                                $value == 'meta_description' ||
                                $value == 'meta_keywords' ||
                                $value == 'meta_title') {
                            foreach (Language::getLanguages() as $lang) {
                                $product[$value.'_'.$lang['id_lang']] = utf8_encode($data[$key]);
                            }
                        } elseif ($value == 'categories') {
                            $product[$value] = explode(',', $data[$key]);
                        } else {
                            $product[$value] = $data[$key];
                        }

                        if ($value == 'images') {
                            $jmarketplace = Module::getInstanceByName('jmarketplace');
                            if (version_compare($jmarketplace->version, '3.1.2') < 0) {
                                $url_images = explode(',', $data[$key]);
                            } else {
                                $array_url_images = explode(',', $data[$key]);
                                $i = 1;
                                foreach ($array_url_images as $url) {
                                    $url_images[$i] = $url;
                                    $i++;
                                }
                            }
                        }
                    }

                    if (Configuration::get('JMARKETPLACE_MODERATE_PRODUCT') == 1) {
                        $product['active'] = 0;
                    } else {
                        $product['active'] = 1;
                    }

                    $product['attributes'] = '';

                    $id_seller_product = 0;

                    if (isset($product['id_tax_rules_group'])) {
                        $product['id_tax'] = (int)$product['id_tax_rules_group'];
                    }

                    //Si se quiere actualizar por id
                    if (array_key_exists('id_product', $product) && $match_ref == 0) {
                        $id_seller_product = SellerProduct::isSellerProduct($product['id_product']);
                    }
                    
                    //Si se quiere actualizar por referencia
                    if (array_key_exists('reference', $product) && $match_ref == 1) {
                        $id_product = CSVSellerProduct::getProductIdByReference($product['reference']);
                        $id_seller_product = SellerProduct::isSellerProduct($id_product);
                    }

                    $id_product = CSVSellerProduct::save($id_seller, $product, $url_images, $id_lang, $match_ref);

                    if ($id_seller_product > 0 && $id_seller_product == $id_seller) {
                        $this->updated++;
                        CSVSellerProductLog::add(utf8_decode($this->l('The product').' '.$product['name_'.Context::getContext()->language->id].' '.$this->l('has been updated.')), $id_seller);
                    } else {
                        SellerProduct::associateSellerProduct($id_seller, $id_product);
                        $this->added++;
                        CSVSellerProductLog::add(utf8_decode($this->l('The product').' '.$product['name_'.Context::getContext()->language->id].' '.$this->l('has been added.')), $id_seller);
                    }
                } else {
                    $this->invalid++;
                }
            }

            $row++;
        }
        
        return $this->getResults();
    }
    
    public static function getProductIdByReference($reference)
    {
        $query = 'SELECT id_product FROM '._DB_PREFIX_.'product WHERE reference = "'.pSQL($reference).'"';
        return DB::getInstance()->getValue($query);
    }
    
    public static function save($id_seller, $item, $images, $id_lang, $match_ref)
    {
        if ($match_ref == 1) {
            $id_product = CSVSellerProduct::getProductIdByReference($item['reference']);

            if (!$id_product) {
                return false;
            }
            
            $product = new Product($id_product);
        } else {
            if (isset($item['id_product']) && $item['id_product'] > 0) {
                $product = new Product((int)$item['id_product']);
            } else {
                $product = new Product();
            }
        }
        
        if (Configuration::get('JMARKETPLACE_MODERATE_PRODUCT') == 1) {
            $product->active = 0;
        } else {
            $product->active = 1;
        }
        
        if (isset($item['reference'])) {
            $product->reference = pSQL($item['reference']);
        }
        
        if (isset($item['isbn'])) {
            $product->isbn = pSQL($item['isbn']);
        }
        
        if (isset($item['ean13'])) {
            $product->ean13 = pSQL($item['ean13']);
        }
        
        if (isset($item['upc'])) {
            $product->upc = pSQL($item['upc']);
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
        
        if (isset($item['condition'])) {
            $product->condition = pSQL($item['condition']);
        }
        
        if (isset($item['quantity'])) {
            $product->quantity = (int)$item['quantity'];
        }
        
        if (isset($item['minimal_quantity'])) {
            $product->minimal_quantity = (int)$item['minimal_quantity'];
        }
        
        if (isset($item['additional_shipping_cost'])) {
            $product->additional_shipping_cost = (float)$item['additional_shipping_cost'];
        }
        
        $search = array('<', '>', ';', '#', '=', '{', '}');
        $replace = " ";
        
        if (isset($item['wholesale_price'])) {
            $item['wholesale_price'] = str_replace(',', '.', $item['wholesale_price']);
            if (Context::getContext()->currency->id != Configuration::get('PS_CURRENCY_DEFAULT')) {
                $product->wholesale_price = Tools::ps_round((float)$item['wholesale_price'] / Context::getContext()->currency->conversion_rate, 4);
            } else {
                $product->wholesale_price = (float)$item['wholesale_price'];
            }
        }
        
        if (isset($item['price'])) {
            $item['price'] = str_replace(',', '.', $item['price']);
            if (Context::getContext()->currency->id != Configuration::get('PS_CURRENCY_DEFAULT')) {
                $product->price = Tools::ps_round((float)$item['price'] / Context::getContext()->currency->conversion_rate, 4);
            } else {
                $product->price = (float)$item['price'];
            }
        }
        
        if (isset($item['unit_price'])) {
            $item['unit_price'] = str_replace(',', '.', $item['unit_price']);
            $product->unit_price = (float)$item['unit_price'];
            $product->unity = (string)$item['unity'];
        }
        
        if (isset($item['id_tax'])) {
            $product->id_tax_rules_group = (int)$item['id_tax'];
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
        
        if (isset($item['id_product']) || $match_ref == 1) {
            $product->name[$id_lang] = Tools::stripslashes(trim(Tools::substr(str_replace($search, $replace, (string)$item['name_'.$id_lang]), 0, 126)));
            
            if (isset($item['description_'.$id_lang])) {
                $product->description[$id_lang] = Tools::stripslashes(trim((string)$item['description_'.$id_lang])); //this is content html
            }
            
            if (isset($item['description_short_'.$id_lang])) {
                $product->description_short[$id_lang] = Tools::stripslashes(trim((string)$item['description_short_'.$id_lang])); //this is content html
            }
            
            if (isset($item['link_rewrite_'.$id_lang]) && $item['link_rewrite_'.$id_lang] != '') {
                $product->link_rewrite[$id_lang] = Tools::stripslashes(trim((string)Tools::link_rewrite(pSQL($item['link_rewrite_'.$id_lang]))));
            } else {
                $product->link_rewrite[$id_lang] = Tools::link_rewrite($product->name[$id_lang]);
            }
            
            if (isset($item['meta_keywords_'.$id_lang])) {
                $product->meta_keywords[$id_lang] = Tools::stripslashes(trim(pSQL($item['meta_keywords_'.$id_lang])));
            }
            
            if (isset($item['meta_description_'.$id_lang])) {
                $product->meta_description[$id_lang] = Tools::stripslashes(trim(pSQL($item['meta_description_'.$id_lang])));
            }
            
            if (isset($item['meta_title_'.$id_lang])) {
                $product->meta_title[$id_lang] = Tools::stripslashes(trim(pSQL($item['meta_title_'.$id_lang])));
            }
        } else {
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

            if (isset($item['description_short_'.$id_lang])) {
                foreach (Language::getLanguages() as $language) {
                    if ($item['description_short_'.$language['id_lang']] != '') {
                        $product->description_short[$language['id_lang']] = Tools::stripslashes(trim((string)$item['description_short_'.$language['id_lang']])); //this is content html
                    } else {
                        $product->description_short[$language['id_lang']] = Tools::stripslashes(trim((string)$item['description_short_'.$id_lang])); //this is content html
                    }
                }
            }

            if (isset($item['link_rewrite_'.$id_lang]) && $item['link_rewrite_'.$id_lang] != '') {
                foreach (Language::getLanguages() as $language) {
                    if ($item['link_rewrite_'.$language['id_lang']] != '') {
                        $product->link_rewrite[$language['id_lang']] = Tools::stripslashes(trim(Tools::link_rewrite(pSQL($item['link_rewrite_'.$language['id_lang']]))));
                    } else {
                        $product->link_rewrite[$language['id_lang']] = Tools::stripslashes(trim(Tools::link_rewrite(pSQL($item['link_rewrite_'.$id_lang]))));
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
                        $product->meta_keywords[$language['id_lang']] = Tools::stripslashes(trim(pSQL($item['meta_keywords_'.$language['id_lang']])));
                    } else {
                        $product->meta_keywords[$language['id_lang']] = Tools::stripslashes(trim(pSQL($item['meta_keywords_'.$id_lang])));
                    }
                }
            }

            if (isset($item['meta_title_'.$id_lang])) {
                foreach (Language::getLanguages() as $language) {
                    if ($item['meta_title_'.$language['id_lang']] != '') {
                        $product->meta_title[$language['id_lang']] = Tools::stripslashes(trim(pSQL($item['meta_title_'.$language['id_lang']])));
                    } else {
                        $product->meta_title[$language['id_lang']] = Tools::stripslashes(trim(pSQL($item['meta_title_'.$id_lang])));
                    }
                }
            }

            if (isset($item['meta_description_'.$id_lang])) {
                foreach (Language::getLanguages() as $language) {
                    if ($item['meta_description_'.$language['id_lang']] != '') {
                        $product->meta_description[$language['id_lang']] = Tools::stripslashes(trim(pSQL($item['meta_description_'.$language['id_lang']])));
                    } else {
                        $product->meta_description[$language['id_lang']] = Tools::stripslashes(trim(pSQL($item['meta_description_'.$id_lang])));
                    }
                }
            }
        }
        
        if (isset($item['id_manufacturer'])) {
            $product->id_manufacturer = (int)$item['id_manufacturer'];
        }
        
        if (isset($item['new_manufacturer']) && $item['new_manufacturer'] != '') {
            if ($manufacturer = Manufacturer::getIdByName((string)$item['new_manufacturer'])) {
                $product->id_manufacturer = (int)$manufacturer;
            } else {
                $manufacturer = new Manufacturer();
                $manufacturer->name = (string)$item['new_manufacturer'];
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
                $supplier->name = (string)$item['new_supplier'];
                $supplier->active = 1;
                $supplier->add();
                $product->id_supplier = (int)$supplier->id;
            }
        }
        
        if (array_key_exists('id_category_default', $item) || array_key_exists('categories', $item)) {
            if ($item['id_category_default'] != 0) {
                $product->id_category_default = (int)$item['id_category_default'];
            } elseif ($item['id_category_default'] == 0 && count($item['categories']) > 0) {
                $product->id_category_default = (int)$item['categories'][0];
            } else {
                $product->id_category_default = (int)Configuration::get('PS_HOME_CATEGORY');
                $item['categories'][] = Configuration::get('PS_HOME_CATEGORY');
            }
        }
        
        $edit_product = false;
        if ((isset($item['id_product']) && $item['id_product'] > 0) || $match_ref == 1) {
            $product->update();
            $edit_product = true;
        } else {
            $product->add();
        }
        
        if (isset($item['quantity'])) {
            StockAvailable::setQuantity($product->id, 0, (int)$item['quantity']);
        }
        
        //all categories
        if (array_key_exists('id_category_default', $item) || array_key_exists('categories', $item)) {
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
            $counter = 1;
            foreach ($images as $url) {
                if ($url != '' || $edit_product) {
                    $id_image = self::getIdImageByPosition($product->id, $counter);

                    if ($id_image > 0) {
                        $image = new Image($id_image);
                    } else {
                        $image = new Image();
                    }

                    $image->id_product = (int)$product->id;
                    $image->position = $counter;

                    if ($counter == 1) {
                        $image->cover = 1;
                    } else {
                        $image->cover = 0;
                    }
                    
                    $image->legend = self::createMultiLangField($product->name[$id_lang]);

                    if ($id_image > 0) {
                        $image->update();
                    } else {
                        $image->add();
                    }

                    $image->associateTo($shops);
                
                    if ($url != '') {
                        self::copyImg($product->id, $image->id, $url);
                    }
                    
                    $counter++;
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
        
        $carriers = SellerTransport::getCarriers($id_lang, true, $id_seller);
        
        if (Configuration::get('JMARKETPLACE_SHOW_MANAGE_CARRIER') == 1 && is_array($carriers) && count($carriers) > 0) {
            $id_carriers = array();

            foreach ($carriers as $carrier) {
                $id_carriers[] = $carrier['id_carrier'];
            }

            $product->setCarriers($id_carriers);
        }

        Search::indexation(Tools::link_rewrite($product->name[$id_lang]), $product->id);

        return $product->id;
    }

    public function getResults()
    {
        return array(
            'added' => $this->added,
            'updated' => $this->updated,
            'invalid' => $this->invalid
        );
    }
    
    public function existsRefInDatabase($reference)
    {
        $row = Db::getInstance()->getRow('
		SELECT `reference`
		FROM `'._DB_PREFIX_.'product` p
		WHERE p.reference = "'.pSQL($reference).'"');

        return isset($row['reference']);
    }
    
    public function validateRow($header, $data, $row, $id_seller, $match_ref)
    {
        $return = true;
        foreach ($header as $key => $value) {
            //ok
            if ($value == 'id_product') {
                if (!Validate::isUnsignedId($data[$key]) && $data[$key] != '') {
                    CSVSellerProductLog::add(utf8_decode($this->l('In line').' '.($row+1).' '.$this->l('the value of id_product is incorrect.')), $id_seller);
                    $return = false;
                }

                if (Seller::getSellerByProduct($data[$key]) != $id_seller) {
                    CSVSellerProductLog::add(utf8_decode($this->l('In line').' '.($row+1).' '.$this->l('this product is not yours.')), $id_seller);
                    $return = false;
                }
            }
            
            //ok
            if ($value == 'id_supplier') {
                if (!Validate::isUnsignedId($data[$key])) {
                    CSVSellerProductLog::add(utf8_decode($this->l('In line').' '.($row+1).' '.$this->l('the value of id_supplier is incorrect.')), $id_seller);
                    $return = false;
                }
            }
            
            //ok
            if ($value == 'id_manufacturer') {
                if (!Validate::isUnsignedId($data[$key])) {
                    CSVSellerProductLog::add(utf8_decode($this->l('In line').' '.($row+1).' '.$this->l('the value of id_manufacturer is incorrect.')), $id_seller);
                    $return = false;
                }
            }
            
            //ok
            if ($value == 'id_category_default') {
                if (!Validate::isUnsignedId($data[$key])) {
                    CSVSellerProductLog::add(utf8_decode($this->l('In line').' '.($row+1).' '.$this->l('the value of id_category_default is incorrect.')), $id_seller);
                    $return = false;
                }
            }
            
            //ok
            if ($value == 'categories') {
                $categories = explode(',', $data[$key]);
                
                if (!is_array($categories)) {
                    CSVSellerProductLog::add(utf8_decode($this->l('In line').' '.($row+1).$this->l('Error in categories. You must separate the categories by comma.')), $id_seller);
                    $return = false;
                } else {
                    foreach ($categories as $cat) {
                        if (!Validate::isUnsignedId($cat)) {
                            CSVSellerProductLog::add(utf8_decode($this->l('In line').' '.($row+1).' '.$this->l('the value of categories is incorrect.')), $id_seller);
                            $return = false;
                        }
                    }
                }
            }
            
            //ok
            if ($value == 'id_tax_rules_group') {
                if (!Validate::isUnsignedId($data[$key])) {
                    CSVSellerProductLog::add(utf8_decode($this->l('In line').' '.($row+1).' '.$this->l('the value of id_tax_rules_group is incorrect.')), $id_seller);
                    $return = false;
                }
            }
            
            //ok
            if ($value == 'quantity') {
                if (!Validate::isInt($data[$key])) {
                    CSVSellerProductLog::add(utf8_decode($this->l('In line').' '.($row+1).' '.$this->l('the quantity stock is incorrect.').' '), $id_seller);
                    $return = false;
                }
            }
            
            //ok
            if ($value == 'wholesale_price') {
                if (!Validate::isPrice($data[$key])) {
                    CSVSellerProductLog::add(utf8_decode($this->l('In line').' '.($row+1).' '.$this->l('the product wholesale price is incorrect.')), $id_seller);
                    $return = false;
                }
            }

            //ok
            if ($value == 'price') {
                if (!Validate::isPrice($data[$key])) {
                    CSVSellerProductLog::add(utf8_decode($this->l('In line').' '.($row+1).' '.$this->l('the product price is incorrect.')), $id_seller);
                    $return = false;
                }
            }

            //ok
            if ($value == 'unit_price') {
                if (!Validate::isPrice($data[$key])) {
                    CSVSellerProductLog::add(utf8_decode($this->l('In line').' '.($row+1).' '.$this->l('the product unit price is incorrect.')), $id_seller);
                    $return = false;
                }
            }

            //ok
            if ($value == 'additional_shipping_cost') {
                if (!Validate::isUnsignedFloat($data[$key])) {
                    CSVSellerProductLog::add(utf8_decode($this->l('In line').' '.($row+1).' '.$this->l('the additional shipping cost is incorrect.').' '), $id_seller);
                    $return = false;
                }
            }

            //ok
            if ($value == 'reference') {
                if (!Validate::isReference($data[$key])) {
                    CSVSellerProductLog::add(utf8_decode($this->l('In line').' '.($row+1).' '.$this->l('the product reference is incorrect.')), $id_seller);
                    $return = false;
                }
                
                if ($this->existsRefInDatabase($data[$key]) && $match_ref == 1) {
                    $id_product = CSVSellerProduct::getProductIdByReference($data[$key]);
                    if (Seller::getSellerByProduct($id_product) != $id_seller) {
                        CSVSellerProductLog::add(utf8_decode($this->l('In line').' '.($row+1).' '.$this->l('this product is not yours.')), $id_seller);
                        $return = false;
                    }
                }
            }
            
            //ok
            if ($value == 'width') {
                if (!Validate::isUnsignedFloat($data[$key])) {
                    CSVSellerProductLog::add(utf8_decode($this->l('In line').' '.($row+1).' '.$this->l('the value of width is incorrect.')), $id_seller);
                    $return = false;
                }
            }
            
            //ok
            if ($value == 'height') {
                if (!Validate::isUnsignedFloat($data[$key])) {
                    CSVSellerProductLog::add(utf8_decode($this->l('In line').' '.($row+1).' '.$this->l('the value of height is incorrect.')), $id_seller);
                    $return = false;
                }
            }

            //ok
            if ($value == 'weight') {
                if (!Validate::isUnsignedFloat($data[$key])) {
                    CSVSellerProductLog::add(utf8_decode($this->l('In line').' '.($row+1).' '.$this->l('the value of weight is incorrect.')), $id_seller);
                    $return = false;
                }
            }

            //ok
            if ($value == 'isbn') {
                if (!Validate::isIsbn($data[$key])) {
                    CSVSellerProductLog::add(utf8_decode($this->l('In line').' '.($row+1).' '.$this->l('the value of isbn is incorrect.')), $id_seller);
                    $return = false;
                }
            }

            //ok
            if ($value == 'ean13') {
                if (!Validate::isEan13($data[$key])) {
                    CSVSellerProductLog::add(utf8_decode($this->l('In line').' '.($row+1).' '.$this->l('the value of ean13 is incorrect.')), $id_seller);
                    $return = false;
                }
            }

            //ok
            if ($value == 'upc') {
                if (!Validate::isUpc($data[$key])) {
                    CSVSellerProductLog::add(utf8_decode($this->l('In line').' '.($row+1).' '.$this->l('the value of upc is incorrect.')), $id_seller);
                    $return = false;
                }
            }

            //ok
            if ($value == 'condition') {
                if ($data[$key] != 'new' && $data[$key] != 'used' && $data[$key] != 'refurbished') {
                    CSVSellerProductLog::add(utf8_decode($this->l('In line').' '.($row+1).' '.$this->l('the value of condition is incorrect. it must be: new, used or refurbished.')), $id_seller);
                    $return = false;
                }
            }
            
            //ok
            if ($value == 'link_rewrite') {
                if (!Validate::isLinkRewrite($data[$key])) {
                    CSVSellerProductLog::add(utf8_decode($this->l('In line').' '.($row+1).$this->l('the link rewrite is incorrect.')), $id_seller);
                    $return = false;
                }
            }
            
            if ($value == 'name') {
                if (!Validate::isCatalogName(utf8_encode($data[$key]))) {
                    CSVSellerProductLog::add(utf8_decode($this->l('In line').' '.($row+1).$this->l('the product name is incorrect.')), $id_seller);
                    $return = false;
                }
            }
            
            if ($value == 'images') {
                $images = explode(',', $data[$key]);
                
                if (!is_array($images)) {
                    CSVSellerProductLog::add(utf8_decode($this->l('In line').' '.($row+1).$this->l('Error in images.')), $id_seller);
                    $return = false;
                }
                
                if (count($images) > Configuration::get('JMARKETPLACE_MAX_IMAGES')) {
                    CSVSellerProductLog::add(utf8_decode($this->l('In line').' '.($row+1).' '.$this->l('Maximum number of images to upload').': '.Configuration::get('JMARKETPLACE_MAX_IMAGES')), $id_seller);
                    $return = false;
                }
            }
        }

        return $return;
    }
    
    public function generateExample()
    {
        $examples = array();
        
        $examples[0]['id_product'] = 3;
        
        if (Configuration::get('JMARKETPLACE_SHOW_SUPPLIERS') == 1) {
            $examples[0]['id_supplier'] = 1;
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_MANUFACTURERS') == 1) {
            $examples[0]['id_manufacturer'] = 1;
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_CATEGORIES') == 1) {
            $examples[0]['id_category_default'] = 9;
            $examples[0]['categories'] = '2,3,8,9';
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_TAX') == 1) {
            $examples[0]['id_tax_rules_group'] = 1;
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_ISBN') == 1) {
            $examples[0]['isbn'] = '';
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_EAN13') == 1) {
            $examples[0]['ean13'] = '';
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_UPC') == 1) {
            $examples[0]['upc'] = '';
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_QUANTITY') == 1) {
            $examples[0]['quantity'] = 299;
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_WHOLESALEPRICE') == 1) {
            $examples[0]['wholesale_price'] = 180;
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_PRICE') == 1) {
            $examples[0]['price'] = 291.61;
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_SHIP_PRODUCT') == 1) {
            $examples[0]['additional_shipping_cost'] = 0;
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_REFERENCE') == 1) {
            $examples[0]['reference'] = 'demo_3';
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_WIDTH') == 1) {
            $examples[0]['width'] = 0;
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_HEIGHT') == 1) {
            $examples[0]['height'] = 0;
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_DEPTH') == 1) {
            $examples[0]['depth'] = 0;
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_WEIGHT') == 1) {
            $examples[0]['weight'] = 0;
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_CONDITION') == 1) {
            $examples[0]['condition'] = 'new';
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_DESC') == 1) {
            $examples[0]['description'] = 'Lorem ipsum...';
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_DESC_SHORT') == 1) {
            $examples[0]['description_short'] = 'Lorem ipsum...';
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_LINK_REWRITE') == 1) {
            $examples[0]['link_rewrite'] = 'vestido-estampado';
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_META_DESC') == 1) {
            $examples[0]['meta_description'] = '';
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_META_KEYWORDS') == 1) {
            $examples[0]['meta_keywords'] = '';
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_META_TITLE') == 1) {
            $examples[0]['meta_title'] = '';
        }
        
        $examples[0]['name'] = 'Vestido estampado';
        
        if (Configuration::get('JMARKETPLACE_SHOW_IMAGES') == 1) {
            $examples[0]['images'] = 'http://your-domain.com/vestido-estampado1.jpg,http://your-domain.com/vestido-estampado2.jpg';
        }
        
        $examples[1]['id_product'] = 4;
        
        if (Configuration::get('JMARKETPLACE_SHOW_SUPPLIERS') == 1) {
            $examples[1]['id_supplier'] = 1;
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_MANUFACTURERS') == 1) {
            $examples[1]['id_manufacturer'] = 1;
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_CATEGORIES') == 1) {
            $examples[1]['id_category_default'] = 11;
            $examples[1]['categories'] = '2,11';
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_TAX') == 1) {
            $examples[1]['id_tax_rules_group'] = 0;
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_ISBN') == 1) {
            $examples[1]['isbn'] = '';
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_EAN13') == 1) {
            $examples[1]['ean13'] = '';
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_UPC') == 1) {
            $examples[1]['upc'] = '';
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_QUANTITY') == 1) {
            $examples[1]['quantity'] = 300;
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_WHOLESALEPRICE') == 1) {
            $examples[1]['wholesale_price'] = 45;
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_PRICE') == 1) {
            $examples[1]['price'] = 61.7;
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_SHIP_PRODUCT') == 1) {
            $examples[1]['additional_shipping_cost'] = 0;
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_REFERENCE') == 1) {
            $examples[1]['reference'] = 'demo_4';
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_WIDTH') == 1) {
            $examples[1]['width'] = 0;
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_HEIGHT') == 1) {
            $examples[1]['height'] = 0;
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_DEPTH') == 1) {
            $examples[1]['depth'] = 0;
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_WEIGHT') == 1) {
            $examples[1]['weight'] = 0;
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_CONDITION') == 1) {
            $examples[1]['condition'] = 'used';
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_DESC') == 1) {
            $examples[1]['description'] = 'Lorem ipsum...';
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_DESC_SHORT') == 1) {
            $examples[1]['description_short'] = 'Lorem ipsum...';
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_LINK_REWRITE') == 1) {
            $examples[1]['link_rewrite'] = 'vestido-estampado';
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_META_DESC') == 1) {
            $examples[1]['meta_description'] = '';
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_META_KEYWORDS') == 1) {
            $examples[1]['meta_keywords'] = '';
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_META_TITLE') == 1) {
            $examples[1]['meta_title'] = '';
        }
        
        $examples[1]['name'] = 'Vestido estampado';
        
        if (Configuration::get('JMARKETPLACE_SHOW_IMAGES') == 1) {
            $examples[1]['images'] = 'http://your-domain.com/vestido-estampado1.jpg,http://your-domain.com/vestido-estampado2.jpg';
        }
        
        $file_example = dirname(__FILE__).'/../example.csv';
        chmod($file_example, 0777);
        $fp = fopen($file_example, "w");
        
        $line = '';
        
        foreach ($this->fields as $field) {
            $line .= $field.$this->separator;
        }
        $line .= "\n";
        
        fwrite($fp, $line);
        
        foreach ($examples as $example) {
            $line = '';
            foreach ($this->fields as $field) {
                $line .= $example[$field].$this->separator;
            }
            $line .= "\n";
            fwrite($fp, $line);
        }
        
        fclose($fp);
        
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($file_example));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_example));
        ob_clean();
        flush();
        readfile($file_example);
        exit;
    }
    
    public static function getIdImageByPosition($id_product, $position)
    {
        $query = 'SELECT id_image FROM `'._DB_PREFIX_.'image` WHERE id_product = '.(int)$id_product.' AND position = '.(int)$position;
        return Db::getInstance()->getValue($query);
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
                if (in_array($imageType['id_image_type'], $watermark_types)) {
                    Module::hookExec('watermark', array('id_image' => $id_image, 'id_product' => $id_entity));
                }
            }
        } else {
            unlink($tmpfile);
            return false;
        }
        unlink($tmpfile);
        return true;
    }
}
