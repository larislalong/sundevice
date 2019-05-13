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

class WebserviceRequest extends WebserviceRequestCore
{
    public static function getResources()
    {
        if (!class_exists('Seller')) {
            include_once _PS_MODULE_DIR_.'jmarketplace/classes/Seller.php';
        }

        if (!class_exists('SellerComment')) {
            include_once _PS_MODULE_DIR_.'jmarketplace/classes/SellerComment.php';
        }

        if (!class_exists('SellerCommission')) {
            include_once _PS_MODULE_DIR_.'jmarketplace/classes/SellerCommission.php';
        }

        if (!class_exists('SellerCommissionHistory')) {
            include_once _PS_MODULE_DIR_.'jmarketplace/classes/SellerCommissionHistory.php';
        }

        if (!class_exists('SellerIncidence')) {
            include_once _PS_MODULE_DIR_.'jmarketplace/classes/SellerIncidence.php';
        }

        if (!class_exists('SellerIncidenceMessage')) {
            include_once _PS_MODULE_DIR_.'jmarketplace/classes/SellerIncidenceMessage.php';
        }

        if (!class_exists('SellerPayment')) {
            include_once _PS_MODULE_DIR_.'jmarketplace/classes/SellerPayment.php';
        }
        
        $resources = parent::getResources();
        $resources['sellers'] = array('description' => 'Sellers', 'class' => 'Seller');
        //$resources['seller_products'] = array('description' => 'Seller products', 'class' => 'SellerProduct');
        $resources['seller_comments'] = array('description' => 'Seller comments', 'class' => 'SellerComment');
        $resources['seller_commissions'] = array('description' => 'Seller commissions', 'class' => 'SellerCommission');
        $resources['seller_commissions_history'] = array('description' => 'Seller commissions history', 'class' => 'SellerCommissionHistory');
        $resources['seller_incidences'] = array('description' => 'Seller incidences', 'class' => 'SellerIncidence');
        $resources['seller_incidence_messages'] = array('description' => 'Seller incidences messages', 'class' => 'SellerIncidenceMessage');
        $resources['seller_payments'] = array('description' => 'Seller payments', 'class' => 'SellerPayment');
        
        ksort($resources);
        return $resources;
    }
}
