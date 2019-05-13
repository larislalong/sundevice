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

class JmarketplaceSellerprofileModuleFrontController extends ModuleFrontController
{
    public $ssl = true;
    
    public function setMedia()
    {
        parent::setMedia();
        $this->context->controller->addCSS(_THEME_CSS_DIR_.'product_list.css', 'all');
    }
    
    public function init()
    {
        parent::init();
        
        $seller = new Seller((int)Tools::getValue('id_seller'));
        $meta = array();
        
        if ($seller->meta_title != '') {
            $meta['meta_title'] = $seller->meta_title.' '.$this->module->l('in', 'sellerprofile').' '.Configuration::get('PS_SHOP_NAME');
        } else {
            $meta['meta_title'] = $seller->name.' '.$this->module->l('in', 'sellerprofile').' '.Configuration::get('PS_SHOP_NAME');
        }
        
        if ($seller->meta_description != '') {
            $meta['meta_description'] = $seller->meta_description;
        } else {
            $meta['meta_description'] = strip_tags(Tools::substr($seller->description, 0, 160));
        }
        
        if ($seller->meta_keywords != '') {
            $meta['meta_keywords'] = $seller->meta_keywords;
        } else {
            $meta['meta_keywords'] = '';
        }

        $this->context->smarty->assign($meta);
    }

    public function initContent()
    {
        parent::initContent();
        
        $is_active_seller = Seller::isActiveSeller(Tools::getValue('id_seller'));
        
        if (!Configuration::get('JMARKETPLACE_SHOW_PROFILE') || !Tools::getValue('id_seller') || !$is_active_seller) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
        }

        $id_seller = (int)Tools::getValue('id_seller');
        $seller = new Seller($id_seller);
        
        if (Configuration::get('PS_SSL_ENABLED') == 1) {
            $url_shop = Tools::getShopDomainSsl(true).__PS_BASE_URI__;
        } else {
            $url_shop = Tools::getShopDomain(true).__PS_BASE_URI__;
        }
        
        if (file_exists(_PS_IMG_DIR_.'sellers/'.$seller->id_customer.'.jpg')) {
            $this->context->smarty->assign(array('photo' => $url_shop.'/img/sellers/'.$seller->id_customer.'.jpg'));
        } else {
            $this->context->smarty->assign(array('photo' =>  $url_shop.'/modules/jmarketplace/views/img/profile.jpg'));
        }

        $param = array('id_seller' => $seller->id, 'link_rewrite' => $seller->link_rewrite);
        $param2 = array('id_seller' => $seller->id);
        
        $url_seller_products = $this->module->getJmarketplaceLink('jmarketplace_sellerproductlist_rule', $param);
        $url_sellers = $this->context->link->getModuleLink('jmarketplace', 'sellers', array(), true);
        $url_favorite_seller = $this->context->link->getModuleLink('jmarketplace', 'favoriteseller', $param2, true);
        $url_seller_comments = Jmarketplace::getJmarketplaceLink('jmarketplace_sellercomments_rule', $param);
        $url_contact_seller = $this->context->link->getModuleLink('jmarketplace', 'contactseller', $param2, true);
        
        if ($seller->id_customer == $this->context->cookie->id_customer) {
            $seller_me = true;
        } else {
            $seller_me = false;
        }
        
        if (Configuration::get('JMARKETPLACE_SELLER_RATING')) {
            $average = SellerComment::getRatings($id_seller);
            $averageTotal = SellerComment::getCommentNumber($id_seller);
            
            $this->context->smarty->assign(array(
                'averageTotal' => (int)$averageTotal,
                'averageMiddle' => ceil($average['avg']),
            ));
        }
        
        $new_products = $seller->getNewProducts($this->context->language->id);
        
        if ($new_products) {
            $i = 0;
            foreach ($new_products as $p) {
                $product = new Product($p['id_product']);
                $new_products[$i]['regular_price'] = Tools::displayPrice($product->getPrice(true, null, 6, null, false, false, 1), $this->context->currency->id);
                $new_products[$i]['final_price'] = Tools::displayPrice($product->getPrice(true, null, 6, null, false, true, 1), $this->context->currency->id);
                $new_products[$i]['price'] = Tools::displayPrice($product->getPrice(true, null, 6, null, false, true, 1), $this->context->currency->id);

                if (is_array($new_products[$i]['specific_prices'])) {
                    $new_products[$i]['price_without_reduction'] = Tools::displayPrice($new_products[$i]['price_without_reduction'], $this->context->currency->id);
                }
                $i++;
            }
        }
        
        $seller_language = new Language($seller->id_lang);

        $this->context->smarty->assign(array(
            'show_shop_name' => Configuration::get('JMARKETPLACE_SHOW_PSHOP_NAME'),
            'show_cif' => Configuration::get('JMARKETPLACE_SHOW_PCIF'),
            'show_language' => Configuration::get('JMARKETPLACE_SHOW_PLANGUAGE'),
            'show_email' => Configuration::get('JMARKETPLACE_SHOW_PEMAIL'),
            'show_phone' => Configuration::get('JMARKETPLACE_SHOW_PPHONE'),
            'show_fax' => Configuration::get('JMARKETPLACE_SHOW_PFAX'),
            'show_address' => Configuration::get('JMARKETPLACE_SHOW_PADDRESS'),
            'show_country' => Configuration::get('JMARKETPLACE_SHOW_PCOUNTRY'),
            'show_state' => Configuration::get('JMARKETPLACE_SHOW_PSTATE'),
            'show_city' => Configuration::get('JMARKETPLACE_SHOW_PCITY'),
            'show_postcode' => Configuration::get('JMARKETPLACE_SHOW_PPOSTAL_CODE'),
            'show_description' => Configuration::get('JMARKETPLACE_SHOW_PDESCRIPTION'),
            'show_logo' => Configuration::get('JMARKETPLACE_SHOW_PLOGO'),
            'moderate' => Configuration::get('JMARKETPLACE_MODERATE_SELLER'),
            'show_orders' => Configuration::get('JMARKETPLACE_SHOW_ORDERS'),
            'show_edit_seller_account' => Configuration::get('JMARKETPLACE_SHOW_EDIT_ACCOUNT'),
            'show_contact' => Configuration::get('JMARKETPLACE_SHOW_CONTACT'),
            'show_seller_favorite' => Configuration::get('JMARKETPLACE_SELLER_FAVORITE'),
            'show_seller_rating' => Configuration::get('JMARKETPLACE_SELLER_RATING'),
            'show_new_products' => Configuration::get('JMARKETPLACE_NEW_PRODUCTS'),
            'seller' => $seller,
            'seller_me' => $seller_me,
            'seller_products_link' => $url_seller_products,
            'url_favorite_seller' => $url_favorite_seller,
            'url_seller_comments' => $url_seller_comments,
            'url_contact_seller' => $url_contact_seller,
            'url_sellers' => $url_sellers,
            'followers' => $seller->getFollowers(),
            'products' => $new_products,
            'seller_language' => $seller_language->name,
            'token' => Configuration::get('JMARKETPLACE_TOKEN'),
        ));
        
        $this->setTemplate('sellerprofile.tpl');
    }
}
