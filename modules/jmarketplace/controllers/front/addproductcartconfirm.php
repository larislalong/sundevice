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

class JmarketplaceAddproductcartconfirmModuleFrontController extends ModuleFrontController
{
    public $ssl = true;
    
    protected function ajaxProcessConfirmDeleteSellerProduct()
    {
        $confirm = '0';
        $id_product = (int)Tools::getValue('id_product');
        $id_seller = SellerProduct::isSellerProduct($id_product);
        
        //revisar los productos del carrito
        $products_in_cart = $this->context->cart->getProducts();
        
        if (is_array($products_in_cart) && count($products_in_cart) > 0) {
            foreach ($products_in_cart as $product) {
                $id_seller_product_cart = SellerProduct::isSellerProduct($product['id_product']);
                if ($id_seller != $id_seller_product_cart) {
                    $confirm = '1';
                }
            }
        }
 
        die($confirm);
    }
    
    protected function ajaxProcessDeleteProductCart()
    {
        $id_product = (int)Tools::getValue('id_product');
        //$id_product_attribute = 0;
        //$id_customization = (int)Tools::getValue('id_customization');
        //$this->context->cart->deleteProduct($id_product, $id_product_attribute, $id_customization);
        //$this->context->cart->updateQty(0, $id_product, $id_product_attribute);
        Db::getInstance()->Execute(
            'DELETE FROM `'._DB_PREFIX_.'cart_product` 
            WHERE `id_cart` = '.Context::getContext()->cart->id. ' 
            AND id_product = '.$id_product
        );
        die('deleted');
    }
    
    public function postProcess()
    {
        if (Tools::isSubmit('action') && Tools::getValue('action') == 'replace') {
            if (Configuration::get('JMARKETPLACE_SHOW_MANAGE_ORDERS') == 1 || Configuration::get('JMARKETPLACE_SHOW_MANAGE_CARRIER') == 1) {
                if (!isset(Context::getContext()->cart->id)) {
                    Context::getContext()->cart->add();
                    if (Context::getContext()->cart->id) {
                        Context::getContext()->cart->cookie->id_cart = Context::getContext()->cart->id;
                    }
                }
                
                if (isset($this->context->cart)) {
                    $product_array = $this->context->cart->getLastProduct();
                    $id_seller = Seller::getSellerByProduct($product_array['id_product'], $product_array['id_product_attribute']);
                    $cart_products = $this->context->cart->getProducts();
                    if (is_array($cart_products)) {
                        foreach ($cart_products as $cart_product) {
                            $id_seller_old = Seller::getSellerByProduct($cart_product['id_product']);
                            if ($id_seller_old != $id_seller) {
                                //$this->context->cart->deleteProduct($cart_product['id_product']);
                                Db::getInstance()->execute(
                                    'DELETE FROM `'._DB_PREFIX_.'cart_product`
                                    WHERE `id_product` = '.(int)$cart_product['id_product'].'
                                    AND `id_cart` = '.(int)$this->context->cart->id
                                );
                            }
                        }
                    }
                }
                
                Tools::redirect($this->context->link->getPageLink('order'));
            }
        }
    }

    public function initContent()
    {
        parent::initContent();
        
        if (Tools::isSubmit('action')) {
            switch (Tools::getValue('action')) {
                case 'review':
                    $this->ajaxProcessConfirmDeleteSellerProduct();
                    break;
                case 'deleteproductcart':
                    $this->ajaxProcessDeleteProductCart();
                    break;
            }
        }
    }
}
