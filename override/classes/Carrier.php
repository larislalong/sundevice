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
class Carrier extends CarrierCore
{
    /*
    * module: jmarketplace
    * date: 2018-07-24 07:33:57
    * version: 5.0.0
    */
    public static function getAvailableCarrierList(Product $product, $id_warehouse, $id_address_delivery = null, $id_shop = null, $cart = null, &$error = array())
    {
        $carrier_list = CarrierCore::getAvailableCarrierList($product, $id_warehouse, $id_address_delivery, $id_shop, $cart, $error);
        if (Configuration::get('JMARKETPLACE_SHOW_MANAGE_CARRIER') == 1 && !Validate::isLoadedObject(Context::getContext()->employee) && Module::isEnabled('jmarketplace')) {
            include_once dirname(__FILE__).'/../../modules/jmarketplace/classes/SellerTransport.php';
            foreach (Context::getContext()->cart->getProducts(false, false) as $cart_product) {
                $id_seller = Seller::getSellerByProduct($cart_product['id_product']);
                if ($id_seller) {
                    $seller_carriers = SellerTransport::getCarriers(Context::getContext()->language->id, true, $id_seller);
                    if (count($seller_carriers) > 0) {
                        foreach ($carrier_list as $id_carrier) {
                            if (SellerTransport::isSellerCarrier($id_seller, $id_carrier) == 0) {
                                unset($carrier_list[$id_carrier]);
                            }
                        }
                    } else {
                        foreach ($carrier_list as $id_carrier) {
                            if (SellerTransport::isCarrierSeller($id_carrier) > 0) {
                                unset($carrier_list[$id_carrier]);
                            }
                        }
                    }
                } else {
                    foreach ($carrier_list as $id_carrier) {
                        if (SellerTransport::isCarrierSeller($id_carrier) > 0) {
                            unset($carrier_list[$id_carrier]);
                        }
                    }
                }
            }
        }
        
        return $carrier_list;
    }
}
