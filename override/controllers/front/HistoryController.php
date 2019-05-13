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

class HistoryController extends HistoryControllerCore
{

    /**
     * Assign template vars related to page content
     * @see FrontController::initContent()
     */
    public function initContent()
    {
        parent::initContent();

        if ($orders = Order::getCustomerOrders($this->context->customer->id)) {
            foreach ($orders as &$order) {
                $myOrder = new Order((int)$order['id_order']);
                if (Validate::isLoadedObject($myOrder)) {
                    $order['virtual'] = $myOrder->isVirtual(false);
                }
            }
        }
        $this->context->smarty->assign(array(
            'orders' => $orders,
            'invoiceAllowed' => (int)Configuration::get('PS_INVOICE'),
            'reorderingAllowed' => !(bool)Configuration::get('PS_DISALLOW_HISTORY_REORDERING'),
            'slowValidation' => Tools::isSubmit('slowvalidation')
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
