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

class JmarketplaceSellerproductsModuleFrontController extends ModuleFrontController
{
    public $ssl = true;
    
    public function setMedia()
    {
        parent::setMedia();
        $this->addJqueryUI('ui.datepicker');
        $this->context->controller->addJS(_MODULE_DIR_.$this->module->name.'/views/js/sellerproducts.js', 'all');
    }
    
    public function postProcess()
    {
        Hook::exec('actionMarketplaceSellerProducts');
        
        if (Tools::getValue('deleteproduct') == 1 && Tools::getValue('id_product') > 0 && Configuration::get('JMARKETPLACE_SHOW_DELETE_PRODUCT') == 1) {
            //comprobar si el producto es del usuario
            $id_product = (int)Tools::getValue('id_product');
            $id_seller = Seller::getSellerByCustomer($this->context->cookie->id_customer);
            if (SellerProduct::existAssociationSellerProduct($id_product) == $id_seller) {
                $product = new Product($id_product);
                $product->delete();
                Tools::redirect($this->context->link->getModuleLink('jmarketplace', 'sellerproducts', array(), true));
            } else {
                Tools::redirect($this->context->link->getModuleLink('jmarketplace', 'sellerproducts', array(), true));
            }
        }
        
        if (Tools::getValue('statusproduct') == 1 && Tools::getValue('id_product') > 0 && Configuration::get('JMARKETPLACE_SHOW_ACTIVE_PRODUCT') == 1) {
            //comprobar si el producto es del usuario
            $id_product = (int)Tools::getValue('id_product');
            $id_seller = Seller::getSellerByCustomer($this->context->cookie->id_customer);
            if (SellerProduct::existAssociationSellerProduct($id_product) == $id_seller) {
                $product = new Product($id_product);
                
                if ($product->active == 1) {
                    $product->active = 0;
                } else {
                    $product->active = 1;
                }
                
                $product->update();
                
                Tools::redirect($this->context->link->getModuleLink('jmarketplace', 'sellerproducts', array(), true));
            } else {
                Tools::redirect($this->context->link->getModuleLink('jmarketplace', 'sellerproducts', array(), true));
            }
        }
        
        if (Tools::isSubmit('submitBulkenableSelectionproduct')) {
            $productBox = Tools::getValue('productBox');
            if ($productBox) {
                foreach ($productBox as $id_product) {
                    $product = new Product($id_product);
                    $product->active = 1;
                    $product->update();
                }
            }
            
            Tools::redirect($this->context->link->getModuleLink('jmarketplace', 'sellerproducts', array(), true));
        }
        
        if (Tools::isSubmit('submitBulkdisableSelectionproduct')) {
            $productBox = Tools::getValue('productBox');
            if ($productBox) {
                foreach ($productBox as $id_product) {
                    $product = new Product($id_product);
                    $product->active = 0;
                    $product->update();
                }
            }
            
            Tools::redirect($this->context->link->getModuleLink('jmarketplace', 'sellerproducts', array(), true));
        }
        
        if (Tools::isSubmit('submitBulkdeleteSelectionproduct')) {
            $productBox = Tools::getValue('productBox');
            if ($productBox) {
                foreach ($productBox as $id_product) {
                    Db::getInstance()->Execute('DELETE FROM `'._DB_PREFIX_.'seller_product` WHERE id_product = '.$id_product);
                    $product = new Product($id_product);
                    $product->delete();
                }
            }
            
            Tools::redirect($this->context->link->getModuleLink('jmarketplace', 'sellerproducts', array(), true));
        }
    }

    public function initContent()
    {
        parent::initContent();
        
        if (!$this->context->cookie->id_customer) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
        }

        $id_seller = Seller::getSellerByCustomer($this->context->cookie->id_customer);
        $is_seller = Seller::isSeller($this->context->cookie->id_customer, $this->context->shop->id);
        
        if (!$is_seller) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
        }

        $seller = new Seller((int)$id_seller);
        
        if ($seller->active == 0) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
        }
        
        $num_products = $seller->getNumProducts();
        $start = 0;
        $limit = Configuration::get('PS_PRODUCTS_PER_PAGE');
        $search_query = '';
        $current_page = 1;

        if (Tools::getValue('orderby') && Tools::getValue('orderway')) {
            $order_by = Tools::getValue('orderby');
            $order_way = Tools::getValue('orderway');
        } else {
            $order_by = 'date_add';
            $order_way = 'desc';
        }
        
        if (Tools::getValue('page')) {
            $current_page = Tools::getValue('page');
            $start = (int)($current_page-1) * Configuration::get('PS_PRODUCTS_PER_PAGE');
        }

        if (Tools::getValue('search_query')) {
            $search_query = (string)Tools::getValue('search_query');
            $products = $seller->find($search_query, $this->context->language->id, 0, 9999, $order_by, $order_way);
            if (!$products) {
                $num_products = 0;
            } else {
                $num_products = count($products);
            }
        } else {
            $products = $seller->getProducts($this->context->language->id, $start, $limit, $order_by, $order_way);
        }
        
        $num_pages = ceil($num_products / $limit);

        $param = array('id_seller' => $seller->id, 'link_rewrite' => $seller->link_rewrite);
        $url_seller_profile = $this->module->getJmarketplaceLink('jmarketplace_seller_rule', $param);
        
        if ($products) {
            $i = 0;
            foreach ($products as $p) {
                $params_product_edit = array('id_product' => $p['id_product']);
                $params_product_delete = array('id_product' => $p['id_product'], 'deleteproduct' => 1);
                $params_product_active = array('id_product' => $p['id_product'], 'statusproduct' => 1);
                $edit_product_link = $this->context->link->getModuleLink('jmarketplace', 'editproduct', $params_product_edit, true);
                $delete_product_link = $this->context->link->getModuleLink('jmarketplace', 'sellerproducts', $params_product_delete, true);
                $active_product_link = $this->context->link->getModuleLink('jmarketplace', 'sellerproducts', $params_product_active, true);
                $products[$i]['edit_product_link'] = $edit_product_link;
                $products[$i]['delete_product_link'] = $delete_product_link;
                $products[$i]['active_product_link'] = $active_product_link;
                $product = new Product($p['id_product']);
                $products[$i]['regular_price'] = Tools::displayPrice($product->getPrice(true, null, 6, null, false, false, 1), $this->context->currency->id);
                $products[$i]['final_price'] = Tools::displayPrice($product->getPrice(true, null, 6, null, false, true, 1), $this->context->currency->id);
                $i++;
            }
        }
        
        $this->context->smarty->assign(array(
            'show_reference' => Configuration::get('JMARKETPLACE_SHOW_REFERENCE'),
            'show_quantity' => Configuration::get('JMARKETPLACE_SHOW_QUANTITY'),
            'show_price' => Configuration::get('JMARKETPLACE_SHOW_PRICE'),
            'show_images' => Configuration::get('JMARKETPLACE_SHOW_IMAGES'),
            'show_orders' => Configuration::get('JMARKETPLACE_SHOW_ORDERS'),
            'show_manage_orders' => Configuration::get('JMARKETPLACE_SHOW_MANAGE_ORDERS'),
            'show_manage_carriers' => Configuration::get('JMARKETPLACE_SHOW_MANAGE_CARRIER'),
            'show_edit_seller_account' => Configuration::get('JMARKETPLACE_SHOW_EDIT_ACCOUNT'),
            'show_edit_product' => Configuration::get('JMARKETPLACE_SHOW_EDIT_PRODUCT'),
            'show_delete_product' => Configuration::get('JMARKETPLACE_SHOW_DELETE_PRODUCT'),
            'show_active_product' => Configuration::get('JMARKETPLACE_SHOW_ACTIVE_PRODUCT'),
            'show_import_product' => Configuration::get('JMARKETPLACE_SELLER_IMPORT_PROD'),
            'show_contact' => Configuration::get('JMARKETPLACE_SHOW_CONTACT'),
            'show_dashboard' => Configuration::get('JMARKETPLACE_SHOW_DASHBOARD'),
            'show_seller_invoice' => Configuration::get('JMARKETPLACE_SHOW_SELLER_INVOICE'),
            'show_menu_top' => Configuration::get('JMARKETPLACE_MENU_TOP'),
            'show_menu_options' => Configuration::get('JMARKETPLACE_MENU_OPTIONS'),
            'moderate' => Configuration::get('JMARKETPLACE_MODERATE_PRODUCT'),
            'products' => $products,
            'num_products' => $num_products,
            'seller_link' => $url_seller_profile,
            'order_by' => $order_by,
            'order_way' => $order_way,
            'search_query' => $search_query,
            'current_page' => $current_page,
            'num_pages' => $num_pages,
            'PS_REWRITING_SETTINGS' => Configuration::get('PS_REWRITING_SETTINGS'),
        ));
        
        if (Tools::getValue('confirmation')) {
            $this->context->smarty->assign(array('confirmation' => 1));
        }
        
        $this->setTemplate('sellerproducts.tpl');
    }
}
