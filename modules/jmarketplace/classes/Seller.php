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

class Seller extends ObjectModel
{
    public $id_customer;
    public $id_shop;
    public $id_lang;
    public $name;
    public $shop;
    public $cif;
    public $email;
    public $phone;
    public $fax;
    public $address;
    public $country;
    public $state;
    public $city;
    public $postcode;
    public $description;
    public $link_rewrite;
    public $meta_description;
    public $meta_keywords;
    public $meta_title;
    public $active;
    public $date_add;
    public $date_upd;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'seller',
        'primary' => 'id_seller',
        'multi_shop' => true,
        'fields' => array(
            'id_customer' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => false),
            'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => false),
            'id_lang' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => false),
            'name' => array('type' => self::TYPE_STRING, 'validate' => 'isCatalogName', 'required' => true, 'size' => 128),
            'shop' => array('type' => self::TYPE_STRING, 'validate' => 'isCatalogName', 'required' => false, 'size' => 128),
            'cif' => array('type' => self::TYPE_STRING, 'required' => false, 'size' => 16),
            'email' => array('type' => self::TYPE_STRING, 'validate' => 'isEmail', 'required' => true, 'size' => 128),
            'phone' => array('type' => self::TYPE_STRING, 'required' => false, 'size' => 32),
            'fax' => array('type' => self::TYPE_STRING, 'required' => false, 'size' => 32),
            'address' => array('type' => self::TYPE_STRING, 'required' => false, 'size' => 128),
            'country' => array('type' => self::TYPE_STRING, 'required' => false, 'size' => 75),
            'state' => array('type' => self::TYPE_STRING, 'required' => false, 'size' => 75),
            'city' => array('type' => self::TYPE_STRING, 'required' => false, 'size' => 75),
            'postcode' => array('type' => self::TYPE_STRING, 'required' => false, 'size' => 12),
            'description' => array('type' => self::TYPE_HTML, 'lang' => false),
            'link_rewrite' => array('type' => self::TYPE_STRING, 'size' => 128),
            'meta_description' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 255),
            'meta_keywords' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 255),
            'meta_title' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 128),
            'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat', 'required' => false),
            'date_upd' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat', 'required' => false),
        ),
    );
    
    protected $webserviceParameters = array(
        'objectMethods' => array(
            'add' => 'addWs',
            'update' => 'updateWs'
        ),
        'objectNodeNames' => 'sellers',
        'fields' => array(
            'id_customer' => array('xlink_resource' => 'customers'),
            'id_shop' => array('xlink_resource' => 'shops'),
            'id_lang' => array('xlink_resource' => 'languages'),
        ),
        'associations' => array(
        'sellers' => array('getter' => 'getChildrenWs', 'resource' => 'seller', ),
        'products' => array('getter' => 'getProductsWs', 'resource' => 'seller_product', ),
        ),
    );
    
    public function addWs($autodate = true, $null_values = false)
    {
        $success = $this->add($autodate, $null_values);
        return $success;
    }

    public function updateWs($null_values = false)
    {
        $success = parent::update($null_values);
        return $success;
    }

    public function add($autodate = true, $null_values = false)
    {
        if (!parent::add($autodate, $null_values)) {
            return false;
        }

        $data_commission = array();
        $data_payment = array();
        
        //insert to seller commission
        $data_commission[] = array(
            'id_seller' => (int)$this->id,
            'commission' => (float)Configuration::get('JMARKETPLACE_VARIABLE_COMMISSION'),
            'id_shop' => (int)Context::getContext()->shop->id,
        );
        
        Db::getInstance()->insert('seller_commission', $data_commission);
        
        //insert to seller payment
        $data_payment[] = array(
            'id_seller' => (int)$this->id,
            'active' => 0,
            'payment' => 'paypal',
        );
        
        $data_payment[] = array(
            'id_seller' => (int)$this->id,
            'active' => 1,
            'payment' => 'bankwire',
        );
        
        Db::getInstance()->insert('seller_payment', $data_payment);

        return true;
    }
    
    public function delete()
    {
        $result = ($this->deleteCommission() &&
            $this->deleteCommissionHistory() &&
            $this->deleteOrders() &&
            $this->deletePayments() &&
            $this->deleteCarriers() &&
            $this->deleteSellerImage() &&
            $this->deleteSellerProducts());
        $result = parent::delete();
        return $result;
    }
    
    public function deleteCommission()
    {
        return Db::getInstance()->execute(
            'DELETE FROM `'._DB_PREFIX_.'seller_commission` 
            WHERE `id_seller` = '.(int)$this->id
        );
    }
    
    public function deleteCommissionHistory()
    {
        return Db::getInstance()->execute(
            'DELETE FROM `'._DB_PREFIX_.'seller_commission_history` 
            WHERE `id_seller` = '.(int)$this->id
        );
    }
    
    public function deleteOrders()
    {
        $orders = SellerOrder::getIdOrdersBySeller($this->id);
        if (is_array($orders) && count($orders) > 0) {
            foreach ($orders as $o) {
                $seller_order = new SellerOrder($o['id_seller_order']);
                $seller_order->delete();
            }
        }
        
        return true;
    }
    
    public function deletePayments()
    {
        return Db::getInstance()->execute(
            'DELETE FROM `'._DB_PREFIX_.'seller_payment` 
            WHERE `id_seller` = '.(int)$this->id
        );
    }
    
    public function deleteCarriers()
    {
        return Db::getInstance()->Execute(
            'DELETE FROM `'._DB_PREFIX_.'seller_carrier` 
            WHERE `id_seller` = '.(int)$this->id
        );
    }
    
    public function deleteSellerProducts()
    {
        $products = $this->getIdProducts();
        if ($products) {
            foreach ($products as $p) {
                $product = new Product($p['id_product']);
                $product->delete();
            }
        }
        
        return Db::getInstance()->execute(
            'DELETE FROM `'._DB_PREFIX_.'seller_product` 
            WHERE `id_seller_product` = '.(int)$this->id
        );
    }
    
    public static function saveSellerImage($file, $id_customer)
    {
        if ((($file['type'] == "image/pjpeg") ||
            ($file['type'] == "image/jpeg") ||
            ($file['type'] == "image/png") ||
            ($file['type'] == "image/gif")) &&
            ($file['size'] < 3145728)) {
            if (file_exists(_PS_IMG_DIR_.'sellers/'.$id_customer.'.jpg')) {
                unlink(_PS_IMG_DIR_.'sellers/'.$id_customer.'.jpg');
            }
            move_uploaded_file($file['tmp_name'], _PS_IMG_DIR_.'sellers/'.$id_customer.'.jpg');
            return true;
        }
        
        return false;
    }
    
    public static function hasImage($id_customer)
    {
        if (file_exists(_PS_IMG_DIR_.'sellers/'.$id_customer.'.jpg')) {
            return true;
        }
        return false;
    }
    
    public function deleteSellerImage()
    {
        if (file_exists(_PS_IMG_DIR_.'sellers/'.$this->id_customer.'.jpg')) {
            unlink(_PS_IMG_DIR_.'sellers/'.$this->id_customer.'.jpg');
        }
        return true;
    }
    
    public static function isSeller($id_customer, $id_shop)
    {
        return Db::getInstance()->getValue(
            'SELECT COUNT(*) FROM '._DB_PREFIX_.'seller 
            WHERE id_customer = '.(int)$id_customer.' AND id_shop ='.(int)$id_shop
        );
    }
    
    public static function isActiveSeller($id_seller)
    {
        return Db::getInstance()->getValue(
            'SELECT COUNT(*) FROM '._DB_PREFIX_.'seller 
            WHERE active = 1 AND id_seller = '.(int)$id_seller
        );
    }
    
    public static function isActiveSellerByCustomer($id_customer)
    {
        return Db::getInstance()->getValue(
            'SELECT COUNT(*) FROM '._DB_PREFIX_.'seller 
            WHERE active = 1 AND id_customer = '.(int)$id_customer
        );
    }
    
    public static function getSellerByCustomer($id_customer)
    {
        $id_seller = Db::getInstance()->getValue(
            'SELECT id_seller FROM '._DB_PREFIX_.'seller 
            WHERE id_customer = '.(int)$id_customer
        );
        
        if ($id_seller) {
            return $id_seller;
        }
        return false;
    }
    
    public static function getDataSellerByCustomer($id_customer)
    {
        $seller = Db::getInstance()->ExecuteS(
            'SELECT name, email, shop, cif, phone, fax, address, country, state, city, postcode 
            FROM '._DB_PREFIX_.'seller 
            WHERE id_customer = '.(int)$id_customer
        );
        
        if ($seller) {
            return $seller;
        }
        return false;
    }
    
    public static function getSellers($id_shop)
    {
        $sellers = Db::getInstance()->executeS(
            'SELECT * FROM '._DB_PREFIX_.'seller 
            WHERE id_shop ='.(int)$id_shop.' ORDER BY name ASC'
        );
        
        if ($sellers) {
            return $sellers;
        }
        return false;
    }
    
    public static function getFrontSellers($id_shop)
    {
        $sellers = Db::getInstance()->executeS(
            'SELECT * FROM '._DB_PREFIX_.'seller 
            WHERE id_shop ='.(int)$id_shop.' AND active = 1 ORDER BY name ASC'
        );
        
        if ($sellers) {
            return $sellers;
        }
        return false;
    }
    
    public static function getSellerByProduct($id_product)
    {
        $id_seller = Db::getInstance()->getValue(
            'SELECT s.id_seller FROM '._DB_PREFIX_.'seller s
            LEFT JOIN `'._DB_PREFIX_.'seller_product` sp ON (sp.`id_seller_product` = s.`id_seller`) 
            LEFT JOIN `'._DB_PREFIX_.'product` p ON (sp.`id_product` = p.`id_product`)  
            WHERE sp.id_product = '.(int)$id_product
        );
        
        if ($id_seller) {
            return $id_seller;
        }
        return false;
    }
    
    public function getIdProducts()
    {
        $products = Db::getInstance()->executeS(
            'SELECT p.id_product
            FROM `'._DB_PREFIX_.'product` p
            LEFT JOIN `'._DB_PREFIX_.'seller_product` sp ON (sp.`id_product` = p.`id_product`) 
            WHERE sp.id_seller_product = '.(int)$this->id
        );
        
        if ($products) {
            return $products;
        }
        return false;
    }
    
    public function getProducts($id_lang, $start, $limit, $order_by, $order_way, $id_category = false, $only_active = false, $active_category = true)
    {
        if ($start < 1) {
            $start = 0;
        }

        if ($order_by == 'position') {
            $order_by = 'cp.position';
        } elseif ($order_by == 'price') {
            $order_by = 'p.price';
        } elseif ($order_by == 'date_add') {
            $order_by = 'p.date_add';
        } elseif ($order_by == 'date_upd') {
            $order_by = 'p.date_upd';
        } elseif ($order_by == 'reference') {
            $order_by = 'p.reference';
        } elseif ($order_by == 'name') {
            $order_by = 'pl.name';
        } elseif ($order_by == 'manufacturer_name') {
            $order_by = 'm.name';
        } elseif ($order_by == 'quantity') {
            $order_by = 'sav.`quantity`';
        } elseif ($order_by == 'active') {
            $order_by = 'p.`active`';
        } else {
            $order_by = 'p.date_add';
        }
        
        $groups = FrontController::getCurrentCustomerGroups();
        $sql_groups = count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1';
        
        $query = 'SELECT p.*, pl.*, i.id_image, m.name as manufacturer_name, sav.`quantity`  AS `real_quantity`
                FROM `'._DB_PREFIX_.'product` p
                '.Shop::addSqlAssociation('product', 'p').'
                LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` '.Shop::addSqlRestrictionOnLang('pl').')
                LEFT JOIN `'._DB_PREFIX_.'stock_available` sav ON (sav.`id_product` = p.`id_product` AND sav.`id_product_attribute` = 0 ) 
                LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
                LEFT JOIN `'._DB_PREFIX_.'supplier` s ON (s.`id_supplier` = p.`id_supplier`)'.
                ($id_category ? 'LEFT JOIN `'._DB_PREFIX_.'category_product` c ON (c.`id_product` = p.`id_product`)' : '').'
                LEFT JOIN `'._DB_PREFIX_.'seller_product` sp ON (sp.`id_product` = p.`id_product`) 
                LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND cover = 1)';
        
        if (Group::isFeatureActive() || $active_category) {
            $query .= ' LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (p.id_product = cp.id_product)';
            
            if (Group::isFeatureActive()) {
                $query .= ' LEFT JOIN `'._DB_PREFIX_.'category_group` cg ON (cp.`id_category` = cg.`id_category` AND cg.`id_group` '.$sql_groups.')';
            }
            
            if ($active_category) {
                $query .= ' LEFT JOIN `'._DB_PREFIX_.'category` ca ON cp.`id_category` = ca.`id_category` AND ca.`active` = 1';
            }
        }
        
        $query .= ' WHERE pl.`id_lang` = '.(int)$id_lang.
                        ($only_active ? ' AND product_shop.`active` = 1' : '').'
                            and sp.id_seller_product = '.(int)$this->id.'
                GROUP BY p.id_product
                ORDER BY '.pSQL($order_by).' '.pSQL($order_way).' LIMIT '.(int)$start.','.(int)$limit;

        $rq = Db::getInstance()->executeS($query);
                
        if (Product::getProductsProperties($id_lang, $rq)) {
            return Product::getProductsProperties($id_lang, $rq);
        }
        return false;
    }
    
    public function find($search_query, $id_lang, $start, $limit, $order_by, $order_way, $id_category = false, $only_active = false)
    {
        if ($start < 1) {
            $start = 0;
        }

        if ($order_by == 'position') {
            $order_by = 'cp.position';
        } elseif ($order_by == 'price') {
            $order_by = 'p.price';
        } elseif ($order_by == 'date_add') {
            $order_by = 'p.date_add';
        } elseif ($order_by == 'date_upd') {
            $order_by = 'p.date_upd';
        } elseif ($order_by == 'reference') {
            $order_by = 'p.reference';
        } elseif ($order_by == 'name') {
            $order_by = 'pl.name';
        } elseif ($order_by == 'manufacturer_name') {
            $order_by = 'm.name';
        } elseif ($order_by == 'quantity') {
            $order_by = 'sav.`quantity`';
        } elseif ($order_by == 'active') {
            $order_by = 'p.`active`';
        } else {
            $order_by = 'p.date_add';
        }
        
        $query = 'SELECT p.*, pl.*, i.id_image, m.name as manufacturer_name, sav.`quantity`  AS `real_quantity`
                FROM `'._DB_PREFIX_.'product` p
                '.Shop::addSqlAssociation('product', 'p').'
                LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` '.Shop::addSqlRestrictionOnLang('pl').')
                LEFT JOIN `'._DB_PREFIX_.'stock_available` sav ON (sav.`id_product` = p.`id_product` AND sav.`id_product_attribute` = 0 ) 
                LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
                LEFT JOIN `'._DB_PREFIX_.'supplier` s ON (s.`id_supplier` = p.`id_supplier`)'.
                ($id_category ? 'LEFT JOIN `'._DB_PREFIX_.'category_product` c ON (c.`id_product` = p.`id_product`)' : '').'
                LEFT JOIN `'._DB_PREFIX_.'seller_product` sp ON (sp.`id_product` = p.`id_product`) 
                LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND cover = 1)
                WHERE pl.`id_lang` = '.(int)$id_lang.
                        ($only_active ? ' AND product_shop.`active` = 1' : '').'
                            and sp.id_seller_product = '.(int)$this->id.'
                            AND pl.name LIKE "%'.pSQL($search_query).'%"
                ORDER BY '.pSQL($order_by).' '.pSQL($order_way).' LIMIT '.(int)$start.','.(int)$limit;

        $rq = Db::getInstance()->executeS($query);
                
        if (Product::getProductsProperties($id_lang, $rq)) {
            return Product::getProductsProperties($id_lang, $rq);
        }
        return false;
    }
    
    public static function getFavoriteSellersByCustomer($id_customer)
    {
        $sellers = Db::getInstance()->executeS(
            'SELECT s.id_seller, name, link_rewrite FROM '._DB_PREFIX_.'seller s
            LEFT JOIN `'._DB_PREFIX_.'seller_favorite` sf ON (sf.`id_seller` = s.`id_seller`)  
            WHERE sf.id_customer = '.(int)$id_customer
        );
        
        if ($sellers) {
            return $sellers;
        }
        return false;
    }
    
    public function getNumProducts()
    {
        return Db::getInstance()->getValue(
            'SELECT COUNT(*) FROM '._DB_PREFIX_.'seller_product 
            WHERE id_seller_product = '.(int)$this->id
        );
    }
    
    public function getNumActiveProducts()
    {
        return Db::getInstance()->getValue(
            'SELECT COUNT(*) FROM '._DB_PREFIX_.'seller_product sp
            LEFT JOIN '._DB_PREFIX_.'product p on (p.id_product = sp.id_product)
            WHERE active = 1 AND id_seller_product = '.(int)$this->id
        );
    }
    
    public static function existFavoriteSellerByCustomer($id_seller, $id_customer)
    {
        return Db::getInstance()->getValue(
            'SELECT COUNT(*) FROM '._DB_PREFIX_.'seller_favorite 
            WHERE id_seller = '.(int)$id_seller.' AND id_customer = '.(int)$id_customer
        );
    }
    
    public static function addFavorite($id_seller, $id_customer)
    {
        Db::getInstance()->Execute(
            'INSERT INTO `' . _DB_PREFIX_ . 'seller_favorite` 
            (`id_customer`, `id_seller`)
            VALUES ('.(int)$id_customer.', '.(int)$id_seller.')'
        );
    }
    
    public function getFollowers()
    {
        return Db::getInstance()->getValue(
            'SELECT COUNT(*) FROM '._DB_PREFIX_.'seller_favorite 
            WHERE id_seller = '.(int)$this->id
        );
    }
    
    public static function deleteFavoriteSellerByCustomer($id_seller, $id_customer)
    {
        return Db::getInstance()->execute(
            'DELETE FROM `'._DB_PREFIX_.'seller_favorite` 
            WHERE `id_seller` = '.(int)$id_seller.' AND `id_customer` = '.(int)$id_customer
        );
    }
    
    public function getNewProducts($id_lang, $page_number = 0, $nb_products = 10, $count = false, $order_by = null, $order_way = null, Context $context = null)
    {
        if (!$context) {
            $context = Context::getContext();
        }

        $front = true;
        if (!in_array($context->controller->controller_type, array('front', 'modulefront'))) {
            $front = false;
        }

        if ($page_number < 0) {
            $page_number = 0;
        }
        
        if ($nb_products < 1) {
            $nb_products = 10;
        }
        
        if (empty($order_by) || $order_by == 'position') {
            $order_by = 'date_add';
        }
        
        if (empty($order_way)) {
            $order_way = 'DESC';
        }
        
        if ($order_by == 'id_product' || $order_by == 'price' || $order_by == 'date_add' || $order_by == 'date_upd') {
            $order_by_prefix = 'p';
        } elseif ($order_by == 'name') {
            $order_by_prefix = 'pl';
        }
        
        if (!Validate::isOrderBy($order_by) || !Validate::isOrderWay($order_way)) {
            die(Tools::displayError());
        }

        $sql_groups = '';
        if (Group::isFeatureActive()) {
            $groups = FrontController::getCurrentCustomerGroups();
            $sql_groups = 'AND p.`id_product` IN (
                SELECT cp.`id_product`
                FROM `'._DB_PREFIX_.'category_group` cg
                LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)
                WHERE cg.`id_group` '.(count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1').')';
        }

        if (strpos($order_by, '.') > 0) {
            $order_by = explode('.', $order_by);
            $order_by_prefix = $order_by[0];
            $order_by = $order_by[1];
        }

        if ($count) {
            $sql = 'SELECT COUNT(p.`id_product`) AS nb
                    FROM `'._DB_PREFIX_.'product` p
                    '.Shop::addSqlAssociation('product', 'p').'
                    WHERE product_shop.`active` = 1
                    AND product_shop.`date_add` > "'.date('Y-m-d', strtotime('-'.(Configuration::get('PS_NB_DAYS_NEW_PRODUCT') ? (int)Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY')).'"
                    '.($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '').'
                    '.$sql_groups;
            return (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
        }

        $sql = new DbQuery();
        $sql->select(
            'p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, pl.`description`, pl.`description_short`, pl.`link_rewrite`, pl.`meta_description`,
            pl.`meta_keywords`, pl.`meta_title`, pl.`name`, pl.`available_now`, pl.`available_later`, MAX(image_shop.`id_image`) id_image, il.`legend`, m.`name` AS manufacturer_name,
            product_shop.`date_add` > "'.date('Y-m-d', strtotime('-'.(Configuration::get('PS_NB_DAYS_NEW_PRODUCT') ? (int)Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY')).'" as new'.(Combination::isFeatureActive() ? ', MAX(product_attribute_shop.minimal_quantity) AS product_attribute_minimal_quantity' : '')
        );

        $sql->from('product', 'p');
        $sql->join(Shop::addSqlAssociation('product', 'p'));
        $sql->leftJoin('product_lang', 'pl', 'p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl'));
        $sql->leftJoin('image', 'i', 'i.`id_product` = p.`id_product`');
        $sql->join(Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1'));
        $sql->leftJoin('image_lang', 'il', 'i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang);
        $sql->leftJoin('manufacturer', 'm', 'm.`id_manufacturer` = p.`id_manufacturer`');
        $sql->leftJoin('seller_product', 'sp', 'sp.`id_product` = p.`id_product`');

        $sql->where('product_shop.`active` = 1');
        $sql->where('sp.id_seller_product = '.(int)$this->id);
        if ($front) {
            $sql->where('product_shop.`visibility` IN ("both", "catalog")');
        }
        $sql->where('product_shop.`date_add` > "'.date('Y-m-d', strtotime('-'.(Configuration::get('PS_NB_DAYS_NEW_PRODUCT') ? (int)Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY')).'"');
        if (Group::isFeatureActive()) {
            $sql->join('JOIN '._DB_PREFIX_.'category_product cp ON (cp.id_product = p.id_product)');
            $sql->join('JOIN '._DB_PREFIX_.'category_group cg ON (cg.id_category = cp.id_category)');
            $sql->where('cg.`id_group` '.(count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1'));
        }
        $sql->groupBy('product_shop.id_product');

        $sql->orderBy((isset($order_by_prefix) ? pSQL($order_by_prefix).'.' : '').'`'.pSQL($order_by).'` '.pSQL($order_way));
        $sql->limit($nb_products, $page_number * $nb_products);

        if (Combination::isFeatureActive()) {
            $sql->select('MAX(product_attribute_shop.id_product_attribute) id_product_attribute');
            $sql->leftOuterJoin('product_attribute', 'pa', 'p.`id_product` = pa.`id_product`');
            $sql->join(Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.default_on = 1'));
        }
        $sql->join(Product::sqlStock('p', Combination::isFeatureActive() ? 'product_attribute_shop' : 0));

        $result = Db::getInstance()->executeS($sql);

        if ($order_by == 'price') {
            Tools::orderbyPrice($result, $order_way);
        }
        
        if (!$result) {
            return false;
        }

        $products_ids = array();
        foreach ($result as $row) {
            $products_ids[] = $row['id_product'];
        }
        // Thus you can avoid one query per product, because there will be only one query for all the products of the cart
        Product::cacheFrontFeatures($products_ids, $id_lang);
        return Product::getProductsProperties((int)$id_lang, $result);
    }
    
    public static function generateLinkRewrite($name)
    {
        $link_rewrite = Tools::strtolower($name);
        $link_rewrite = str_replace(array('á','à','â','ã','ª','ä'), "a", $link_rewrite);
        $link_rewrite = str_replace(array('Á','À','Â','Ã','Ä'), "A", $link_rewrite);
        $link_rewrite = str_replace(array('Í','Ì','Î','Ï'), "I", $link_rewrite);
        $link_rewrite = str_replace(array('í','ì','î','ï'), "i", $link_rewrite);
        $link_rewrite = str_replace(array('é','è','ê','ë'), "e", $link_rewrite);
        $link_rewrite = str_replace(array('É','È','Ê','Ë'), "E", $link_rewrite);
        $link_rewrite = str_replace(array('ó','ò','ô','õ','ö','º'), "o", $link_rewrite);
        $link_rewrite = str_replace(array('Ó','Ò','Ô','Õ','Ö'), "O", $link_rewrite);
        $link_rewrite = str_replace(array('ú','ù','û','ü'), "u", $link_rewrite);
        $link_rewrite = str_replace(array('Ú','Ù','Û','Ü'), "U", $link_rewrite);
        $link_rewrite = str_replace(array('(','[','^','´','`','¨','~',']',')'), "", $link_rewrite);
        $link_rewrite = str_replace(array(',','.',':','?','¿','!','¡'), "", $link_rewrite);
        $link_rewrite = str_replace("ç", "c", $link_rewrite);
        $link_rewrite = str_replace("Ç", "C", $link_rewrite);
        $link_rewrite = str_replace("ñ", "n", $link_rewrite);
        $link_rewrite = str_replace("Ñ", "N", $link_rewrite);
        $link_rewrite = str_replace("Ý", "Y", $link_rewrite);
        $link_rewrite = str_replace("ý", "y", $link_rewrite);
        $link_rewrite = str_replace("&", "y", $link_rewrite);
        $link_rewrite = str_replace(' ', '-', $link_rewrite);
        $link_rewrite = str_replace("'", '', $link_rewrite);
        $link_rewrite = str_replace('"', '', $link_rewrite);
        return $link_rewrite;
    }
    
    public static function existName($name)
    {
        return Db::getInstance()->getValue(
            'SELECT COUNT(*) FROM '._DB_PREFIX_.'seller 
            WHERE name = "'.pSQL($name).'" AND id_shop = '.Context::getContext()->shop->id
        );
    }
    
    public static function existEmail($email)
    {
        return Db::getInstance()->getValue(
            'SELECT COUNT(*) FROM '._DB_PREFIX_.'seller 
            WHERE email = "'.pSQL($email).'" AND id_shop = '.Context::getContext()->shop->id
        );
    }
}
