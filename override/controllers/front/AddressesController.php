<?php
/*
* 2007-2017 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2017 PrestaShop SA
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class AddressesController extends AddressesControllerCore
{



    /**
     * Assign template vars related to page content
     * @see FrontController::initContent()
     */
    public function initContent()
    {
        parent::initContent();

        $total = 0;
        $multiple_addresses_formated = array();
        $ordered_fields = array();
        $addresses = $this->context->customer->getAddresses($this->context->language->id);
        // @todo getAddresses() should send back objects
        foreach ($addresses as $detail) {
            $address = new Address($detail['id_address']);
            $multiple_addresses_formated[$total] = AddressFormat::getFormattedLayoutData($address);
            unset($address);
            ++$total;

            // Retro theme < 1.4.2
            $ordered_fields = AddressFormat::getOrderedAddressFields($detail['id_country'], false, true);
        }

        // Retro theme 1.4.2
        if ($key = array_search('Country:name', $ordered_fields)) {
            $ordered_fields[$key] = 'country';
        }

        $addresses_style = array(
            'company' => 'address_company',
            'vat_number' => 'address_company',
            'firstname' => 'address_name',
            'lastname' => 'address_name',
            'address1' => 'address_address1',
            'address2' => 'address_address2',
            'city' => 'address_city',
            'country' => 'address_country',
            'phone' => 'address_phone',
            'phone_mobile' => 'address_phone_mobile',
            'alias' => 'address_title',
        );

        $this->context->smarty->assign(array(
            'addresses_style' => $addresses_style,
            'multipleAddresses' => $multiple_addresses_formated,
            'ordered_fields' => $ordered_fields,
            'addresses' => $addresses, // retro compat themes 1.5ibility Theme < 1.4.1
        ));
        $has_address = $this->context->customer->getAddresses($this->context->language->id);
        $this->context->smarty->assign(array(
            'has_customer_an_address' => empty($has_address),
            'voucherAllowed' => (int)CartRule::isFeatureActive(),
            'returnAllowed' => (int)Configuration::get('PS_ORDER_RETURN')
        ));
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

        // $this->setTemplate(_PS_MODULE_DIR_.'/cleansav/views/templates/front/dash.tpl');
    }
}
