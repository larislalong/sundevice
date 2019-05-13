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

class JmarketplaceSellersModuleFrontController extends ModuleFrontController
{
    public $ssl = true;

    public function initContent()
    {
        parent::initContent();
        
        if (Configuration::get('PS_SSL_ENABLED') == 1) {
            $url_shop = Tools::getShopDomainSsl(true).__PS_BASE_URI__;
        } else {
            $url_shop = Tools::getShopDomain(true).__PS_BASE_URI__;
        }
        
        $sellers = Seller::getFrontSellers($this->context->shop->id);
        
        if (is_array($sellers) && count($sellers) > 0) {
            $i = 0;
            foreach ($sellers as $s) {
                $param = array('id_seller' => $s['id_seller'], 'link_rewrite' => $s['link_rewrite']);
                $url_seller_profile = $this->module->getJmarketplaceLink('jmarketplace_seller_rule', $param);
                $sellers[$i]['url'] = $url_seller_profile;

                if (file_exists(_PS_IMG_DIR_.'sellers/'.$s['id_customer'].'.jpg')) {
                    $sellers[$i]['photo'] = $url_shop.'/img/sellers/'.$s['id_customer'].'.jpg';
                } else {
                    $sellers[$i]['photo'] = $url_shop.'/modules/jmarketplace/views/img/profile.jpg';
                }

                if (Configuration::get('JMARKETPLACE_SELLER_RATING')) {
                    $average = SellerComment::getRatings($s['id_seller']);
                    $averageTotal = SellerComment::getCommentNumber($s['id_seller']);
                    $sellers[$i]['averageTotal'] = (int)$averageTotal;
                    $sellers[$i]['averageMiddle'] = ceil($average['avg']);
                }

                $i++;
            }
        }

        $this->context->smarty->assign(array(
            'show_logo' => Configuration::get('JMARKETPLACE_SHOW_LOGO'),
            'show_seller_rating' => Configuration::get('JMARKETPLACE_SELLER_RATING'),
            'sellers' => $sellers,
        ));

        $this->setTemplate('sellers.tpl');
    }
}
