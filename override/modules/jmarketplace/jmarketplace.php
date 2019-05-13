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

if (!defined('_PS_VERSION_')) {
    exit;
}

class JmarketplaceOverride extends Jmarketplace
{

    public function hookDisplayCustomerAccount($params)
    {
        $customer_can_be_seller = false;
        $customer_groups = Customer::getGroupsStatic($this->context->cookie->id_customer);

        foreach ($customer_groups as $id_group) {
            if (Configuration::get('JMARKETPLACE_CUSTOMER_GROUP_'.$id_group) == 1) {
                $customer_can_be_seller = true;
            }
        }

        $this->context->smarty->assign(array(
            'is_seller' => Seller::isSeller($this->context->cookie->id_customer, $this->context->shop->id),
            'is_active_seller' => Seller::isActiveSellerByCustomer($this->context->cookie->id_customer),
            'customer_can_be_seller' => $customer_can_be_seller,
            'id_default_group' => $this->context->customer->id_default_group,
            'show_contact' => Configuration::get('JMARKETPLACE_SHOW_CONTACT'),
            'show_seller_favorite' => Configuration::get('JMARKETPLACE_SELLER_FAVORITE'),
            'ssl_enabled' => Configuration::get('PS_SSL_ENABLED')
        ));

        //return $this->display(__FILE__, 'customer-account.tpl');
    }



  }
