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

class MyAccountController extends MyAccountControllerCore
{
   public function setMedia()
    {
        parent::setMedia();

        $this->addCSS(_PS_MODULE_DIR_.'/cleansav/views/css/sb-admin-2.css');
    }
    /**
     * Assign template vars related to page content
     * @see FrontController::initContent()
     */
    public function initContent()
    {
        parent::initContent();

        $has_address = $this->context->customer->getAddresses($this->context->language->id);
        $this->context->smarty->assign(array(
            'has_customer_an_address' => empty($has_address),
            'voucherAllowed' => (int)CartRule::isFeatureActive(),
            'returnAllowed' => (int)Configuration::get('PS_ORDER_RETURN')
        ));
        $this->context->smarty->assign('HOOK_CUSTOMER_ACCOUNT', Hook::exec('displayCustomerAccount'));

        $customer = $this->context->customer;
        $this->context->smarty->assign('cleansav_customer', $customer);
        $this->context->smarty->assign(array(
            'cus_firstname' => $customer->firstname,
            'cus_lastname' => $customer->lastname,
            'cus_email' => $customer->email,
            'cus_newsletter' => $customer->newsletter

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
        $this->ClsAssignOrderList();
        // $this->setTemplate(_PS_MODULE_DIR_.'/cleansav/views/templates/front/dash.tpl');
    }

    /**
    * Assign template vars related to order list and product list ordered by the customer
    */
    public function ClsAssignOrderList()
    {
        if ($this->context->customer->isLogged()) {


            $orderList=Db::getInstance()->ExecuteS ('SELECT a.id_order,reference ,total_paid_tax_incl , payment , a. date_add  AS  date_add  , a.id_currency, a.id_order AS id_pdf, c. firstname , c. lastname  , osl. name  AS  osname , os. color ,
            IF((SELECT so.id_order FROM  '._DB_PREFIX_.'orders  so WHERE so.id_customer = a.id_customer AND so.id_order < a.id_order LIMIT 1) > 0, 0, 1) as new, country_lang.name as cname, IF(a.valid, 1, 0) badge_success FROM  '._DB_PREFIX_.'orders  a
            LEFT JOIN  '._DB_PREFIX_.'customer  c ON (c. id_customer  = a. id_customer )
            LEFT JOIN  '._DB_PREFIX_.'address  address ON address.id_address = a.id_address_delivery
            LEFT JOIN  '._DB_PREFIX_.'country  country ON address.id_country = country.id_country
            LEFT JOIN  '._DB_PREFIX_.'country_lang  country_lang ON (country. id_country  = country_lang. id_country  AND country_lang. id_lang  = 1)
            LEFT JOIN  '._DB_PREFIX_.'order_state  os ON (os. id_order_state  = a. current_state )
            LEFT JOIN  '._DB_PREFIX_.'order_state_lang  osl ON (os. id_order_state  = osl. id_order_state  AND osl. id_lang  = 1)
            WHERE 1 ORDER BY a. id_order  DESC LIMIT 0, 50');

            $addressList=Db::getInstance()->ExecuteS('SELECT a. id_address , a. firstname  AS  firstname ,
            a. lastname  AS  lastname ,  address1 ,  postcode ,  city ,
            cl. id_country  AS  country  , cl. name  as country
            FROM  '._DB_PREFIX_.'address  a
            LEFT JOIN  '._DB_PREFIX_.'country_lang  cl ON (cl. id_country  = a. id_country  AND cl. id_lang  = 1)
            LEFT JOIN  '._DB_PREFIX_.'customer  c ON a.id_customer = c.id_customer WHERE 1 AND a.id_customer != 0 AND c.id_shop IN (1) AND a. deleted  = 0
            ORDER BY a. id_address  ASC LIMIT 0, 50');

            $this->context->smarty->assign(array(

                'orderList' => $orderList,
                'addressList' => $addressList
            ));
        }
    }



}
