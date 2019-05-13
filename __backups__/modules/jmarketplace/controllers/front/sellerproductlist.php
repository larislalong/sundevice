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

class JmarketplaceSellerproductlistModuleFrontController extends ModuleFrontController
{
    public $ssl = true;
    public $nbProducts;
    public $cat_products;
    public $seller;

    public function setMedia()
    {
        parent::setMedia();
        $this->addCSS(_THEME_CSS_DIR_.'category.css', 'all');
        $this->addCSS(_THEME_CSS_DIR_.'product_list.css', 'all');
    }
    
    public function init()
    {
        parent::init();
        
        $seller = new Seller((int)Tools::getValue('id_seller'));
        $meta = array();
        
        if ($seller->meta_title) {
            $meta['meta_title'] = $seller->meta_title.' - '.Configuration::get('PS_SHOP_NAME').' - '.$this->module->l('Products', 'sellerproductlist');
        } else {
            $meta['meta_title'] = $seller->name.' - '.Configuration::get('PS_SHOP_NAME').' - '.$this->module->l('Products', 'sellerproductlist');
        }

        $this->context->smarty->assign($meta);
    }

    public function initContent()
    {
        parent::initContent();
        
        if (!Tools::getValue('id_seller')) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
        }
        
        $id_seller = (int)Tools::getValue('id_seller');
        $this->seller = new Seller($id_seller);
        
        $this->productSort();
        
        $this->assignProductList();

        $params = array('id_seller' => $this->seller->id, 'link_rewrite' => $this->seller->link_rewrite);
        $url_seller_profile = $this->module->getJmarketplaceLink('jmarketplace_seller_rule', $params);
        
        if ($this->products) {
            $i = 0;
            foreach ($this->products as $p) {
                $product = new Product($p['id_product']);
                $this->products[$i]['regular_price'] = Tools::displayPrice($product->getPrice(true, null, 6, null, false, false, 1), $this->context->currency->id);
                $this->products[$i]['final_price'] = Tools::displayPrice($product->getPrice(true, null, 6, null, false, true, 1), $this->context->currency->id);
                $i++;
            }
        }

        $this->context->smarty->assign(array(
            'seller' => $this->seller,
            'products' => (isset($this->products) && $this->products) ? $this->products : null,
            'seller_link' => $url_seller_profile,
            'add_prod_display' => Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'),
            //'homeSize' => Image::getSize(ImageType::getFormatedName('home')),
            'allow_oosp' => (int)Configuration::get('PS_ORDER_OUT_OF_STOCK'),
            'comparator_max_item' => (int)Configuration::get('PS_COMPARATOR_MAX_ITEM'),
        ));
        
        $this->setTemplate('sellerproductlist.tpl');
    }
    
    protected function assignProductList()
    {
        $this->nbProducts = $this->seller->getNumActiveProducts();

        $this->pagination((int)$this->nbProducts); // Pagination must be call after "getProducts"
        
        if (!Tools::getValue('p')) {
            $this->p = 0;
        } else {
            $this->p = (int)(Tools::getValue('p')-1) * Configuration::get('PS_PRODUCTS_PER_PAGE');
        }

        $this->n = Configuration::get('PS_PRODUCTS_PER_PAGE');

        $this->products = $this->seller->getProducts($this->context->language->id, $this->p, $this->n, $this->orderBy, $this->orderWay, false, true);

        $this->context->smarty->assign(array(
            'pages_nb' => ceil($this->nbProducts / (int)$this->n),
            'nb_products' => $this->nbProducts,
        ));
    }
}
