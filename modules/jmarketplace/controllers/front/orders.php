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

class JmarketplaceOrdersModuleFrontController extends ModuleFrontController
{
    public $ssl = true;
    
    public function postProcess()
    {
        if (Tools::isSubmit('submitState')) {
            $id_seller = Seller::getSellerByCustomer($this->context->cookie->id_customer);
            $seller = new Seller($id_seller);
            $id_order = (int)Tools::getValue('id_order');
            $id_seller_order = SellerOrder::getIdSellerOrderByOrderAndSeller($id_order, $id_seller);
            $id_order_state = (int)Tools::getValue('id_order_state');
            $order = new SellerOrder($id_seller_order);
            $order_state = new OrderState($id_order_state);

            if (!Validate::isLoadedObject($order_state)) {
                $this->errors[] = Tools::displayError('The new order status is invalid.');
            } else {
                //$current_order_state = $order->getCurrentOrderState();
                if ($order->current_state != $order_state->id) {
                    //update main order history
                    if (Configuration::get('JMARKETPLACE_SHOW_MANAGE_CARRIER') == 1) {
                        $main_order = new Order($id_order);
                        $main_order->setCurrentState($order_state->id);
                    }
                    
                    //update history commissions
                    $states = OrderState::getOrderStates($this->context->language->id);
                    $cancel_commissions = false;
                    foreach ($states as $state) {
                        if (Configuration::get('JMARKETPLACE_CANCEL_COMMISSION_'.$state['id_order_state']) == 1 && $id_order_state == $state['id_order_state']) {
                            $cancel_commissions = true;
                        }
                    }
                    
                    //si toca cancelar comisiones
                    if ($cancel_commissions) {
                        SellerCommissionHistory::changeStateCommissionsByOrder($id_order, 'cancel');
                    }
                    
                    // Create new SellerOrderHistory
                    $seller_order_history = new SellerOrderHistory();
                    $seller_order_history->id_seller_order = SellerOrder::getIdSellerOrderByOrderAndSeller($id_order, $id_seller);
                    $seller_order_history->id_seller = $id_seller;
                    $seller_order_history->id_order_state = $id_order_state;
                    $seller_order_history->add();
                    
                    $seller_order = new SellerOrder($seller_order_history->id_seller_order);
                    $seller_order->current_state = $id_order_state;
                    $seller_order->update();
                    
                    //send email to administrator when seller change order status
                    if (Configuration::get('JMARKETPLACE_SEND_ORDER_CHANGED') == 1) {
                        $id_seller_email = false;
                        $to = Configuration::get('JMARKETPLACE_SEND_ADMIN');
                        $to_name = Configuration::get('PS_SHOP_NAME');
                        $from = Configuration::get('PS_SHOP_EMAIL');
                        $from_name = Configuration::get('PS_SHOP_NAME');

                        $template = 'base';
                        $reference = 'seller-order-changed';
                        $id_seller_email = SellerEmail::getIdByReference($reference);

                        if ($id_seller_email) {
                            $seller_email = new SellerEmail($id_seller_email, Configuration::get('PS_LANG_DEFAULT'));
                            $vars = array("{shop_name}", "{seller_name}", "{seller_shop}", "{order_reference}");
                            $values = array(Configuration::get('PS_SHOP_NAME'), $seller->name, $seller->shop, $seller_order->reference);
                            $subject_var = $seller_email->subject;
                            $subject_value = str_replace($vars, $values, $subject_var);
                            $content_var = $seller_email->content;
                            $content_value = str_replace($vars, $values, $content_var);

                            $template_vars = array(
                                '{content}' => $content_value,
                                '{shop_name}' => Configuration::get('PS_SHOP_NAME')
                            );

                            $iso = Language::getIsoById(Configuration::get('PS_LANG_DEFAULT'));

                            if (file_exists(dirname(__FILE__).'/../../mails/'.$iso.'/'.$template.'.txt') && file_exists(dirname(__FILE__).'/../../mails/'.$iso.'/'.$template.'.html')) {
                                Mail::Send(
                                    Configuration::get('PS_LANG_DEFAULT'),
                                    $template,
                                    $subject_value,
                                    $template_vars,
                                    $to,
                                    $to_name,
                                    $from,
                                    $from_name,
                                    null,
                                    null,
                                    dirname(__FILE__).'/../../mails/'
                                );
                            }
                        }
                    }

                    /*$carrier = new Carrier($order->id_carrier, $order->id_lang);
                    $templateVars = array();
                    if ($history->id_order_state == Configuration::get('PS_OS_SHIPPING') && $order->shipping_number) {
                        $templateVars = array('{followup}' => str_replace('@', $order->shipping_number, $carrier->url));
                    }

                    // Save all changes
                    if ($history->addWithemail(true, $templateVars)) {
                        // synchronizes quantities if needed..
                        if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
                            foreach ($order->getProducts() as $product) {
                                if (StockAvailable::dependsOnStock($product['product_id'])) {
                                    StockAvailable::synchronize($product['product_id'], $product['id_shop']);
                                }
                            }
                        }

                        $params = array('id_order' => $id_order);
                        Tools::redirect($this->context->link->getModuleLink('jmarketplace', 'orders', $params, true));
                    }*/
                }
            }
        }
        
        if (Tools::getValue('action') == 'generateInvoicePDF') {
            $id_order = (int)Tools::getValue('id_order');
            $this->generateInvoicePDFByIdOrder($id_order);
        }
        
        if (Tools::isSubmit('submitShippingNumber')) {
            $tracking_number = Tools::getValue('tracking_number');
            $id_order_carrier = Tools::getValue('id_order_carrier');
            $id_order = (int)Tools::getValue('id_order');
            $order = new Order($id_order);
            $order_carrier = new OrderCarrier($id_order_carrier);

            if (!Validate::isLoadedObject($order_carrier)) {
                $this->errors[] = $this->module->l('The order carrier ID is invalid.', 'orders');
            }
            
            if (!Validate::isLoadedObject($order)) {
                $this->errors[] = $this->module->l('The order ID is invalid.', 'orders');
            }
            
            if (!Validate::isTrackingNumber($tracking_number)) {
                $this->errors[] = $this->module->l('The tracking number is incorrect.', 'orders');
            }

            if (count($this->errors) > 0) {
                $this->context->smarty->assign(array(
                    'errors' => $this->errors,
                ));
            } else {
                // update shipping number
                $order->shipping_number = $tracking_number;
                $order->update();
                
                // Update order_carrier
                $order_carrier->tracking_number = pSQL($tracking_number);
                if ($order_carrier->update()) {
                    $customer = new Customer((int)$order->id_customer);
                    $carrier = new Carrier((int)$order->id_carrier, $order->id_lang);
                    
                    $templateVars = array(
                        '{followup}' => str_replace('@', $order->shipping_number, $carrier->url),
                        '{firstname}' => $customer->firstname,
                        '{lastname}' => $customer->lastname,
                        '{id_order}' => $order->id,
                        '{shipping_number}' => $order->shipping_number,
                        '{order_name}' => $order->getUniqReference()
                    );
                    
                    Mail::Send(
                        (int)$order->id_lang,
                        'in_transit',
                        Mail::l('Package in transit', (int)$order->id_lang),
                        $templateVars,
                        $customer->email,
                        $customer->firstname.' '.$customer->lastname,
                        null,
                        null,
                        null,
                        null,
                        _PS_MAIL_DIR_,
                        true,
                        (int)$order->id_shop
                    );
                    
                    $params = array('id_order' => $id_order);
                    Tools::redirect($this->context->link->getModuleLink('jmarketplace', 'orders', $params, true));
                }
            }
        }
    }

    public function initContent()
    {
        parent::initContent();
        
        if (Configuration::get('JMARKETPLACE_SHOW_MANAGE_ORDERS') == 0) {
            Tools::redirect($this->context->link->getModuleLink('jmarketplace', 'selleraccount', array(), true));
        }
        
        if (!$this->context->cookie->id_customer) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
        }

        $id_seller = Seller::getSellerByCustomer($this->context->cookie->id_customer);
        $is_seller = Seller::isSeller($this->context->cookie->id_customer, $this->context->shop->id);
        
        if (!$is_seller) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
        }
        
        $seller = new Seller($id_seller);
        
        if ($seller->active == 0) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
        }
        
        $param = array('id_seller' => $seller->id, 'link_rewrite' => $seller->link_rewrite);
        $url_seller_profile = $this->module->getJmarketplaceLink('jmarketplace_seller_rule', $param);
       
        if (!Tools::getValue('id_order')) {
            $orders = SellerOrder::getSellerOrders($id_seller, $this->context->language->id);
            if (is_array($orders) && count($orders) > 0) {
                foreach ($orders as $key => $o) {
                    $currency_from = new Currency($o['id_currency']);
                    $orders[$key]['total_paid_tax_incl'] = Tools::displayPrice(Tools::convertPriceFull($o['total_paid_tax_incl'], $currency_from, $this->context->currency));
                    $orders[$key]['total_paid_tax_excl'] = Tools::displayPrice(Tools::convertPriceFull($o['total_paid_tax_excl'], $currency_from, $this->context->currency));
                    $orders[$key]['total_discounts_tax_incl'] = Tools::displayPrice(Tools::convertPriceFull($o['total_discounts_tax_incl'], $currency_from, $this->context->currency));
                    $orders[$key]['total_discounts_tax_excl'] = Tools::displayPrice(Tools::convertPriceFull($o['total_discounts_tax_excl'], $currency_from, $this->context->currency));
                    $params = array('id_order' => $o['id_order']);
                    $orders[$key]['link'] = $this->context->link->getModuleLink('jmarketplace', 'orders', $params, true);
                }
            }

            $this->context->smarty->assign(array(
                'seller_link' => $url_seller_profile,
                'show_import_product' => Configuration::get('JMARKETPLACE_SELLER_IMPORT_PROD'),
                'show_orders' => Configuration::get('JMARKETPLACE_SHOW_ORDERS'),
                'show_manage_orders' => Configuration::get('JMARKETPLACE_SHOW_MANAGE_ORDERS'),
                'show_manage_carriers' => Configuration::get('JMARKETPLACE_SHOW_MANAGE_CARRIER'),
                'show_edit_seller_account' => Configuration::get('JMARKETPLACE_SHOW_EDIT_ACCOUNT'),
                'show_contact' => Configuration::get('JMARKETPLACE_SHOW_CONTACT'),
                'show_dashboard' => Configuration::get('JMARKETPLACE_SHOW_DASHBOARD'),
                'show_seller_invoice' => Configuration::get('JMARKETPLACE_SHOW_SELLER_INVOICE'),
                'show_menu_top' => Configuration::get('JMARKETPLACE_MENU_TOP'),
                'show_menu_options' => Configuration::get('JMARKETPLACE_MENU_OPTIONS'),
                'tax_commission' => Configuration::get('JMARKETPLACE_TAX_COMMISSION'),
                'orders' => $orders,
            ));
        
            $this->setTemplate('orders.tpl');
        } else {
            $id_order = (int)Tools::getValue('id_order');
            
            if (!SellerOrder::existSellerOrder($id_order, $id_seller)) {
                Tools::redirect($this->context->link->getModuleLink('jmarketplace', 'orders', array(), true));
            }
            
            $commission_history_by_order = SellerCommissionHistory::getCommissionHistoryByOrder($id_order, $this->context->language->id, $this->context->shop->id);
            
            if (!$commission_history_by_order) {
                Tools::redirect($this->context->link->getModuleLink('jmarketplace', 'orders', array(), true));
            }

            $id_seller_order = SellerOrder::getIdSellerOrderByOrderAndSeller($id_order, $id_seller);
            $order = new SellerOrder($id_seller_order);
            $currency_from = new Currency($order->id_currency);
            
            $customer = new Customer($order->id_customer);
            $address_delivery = new Address($order->id_address_delivery);
            $dlv_adr_fields = AddressFormat::getOrderedAddressFields($address_delivery->id_country);
            $deliveryAddressFormatedValues = AddressFormat::getFormattedAddressFieldsValues($address_delivery, $dlv_adr_fields);
            
            $params_order = array('id_order' => $id_order);
            
            $products = $order->getProductsDetail();
            foreach ($products as $key => $product) {
                $products[$key]['current_stock'] = StockAvailable::getQuantityAvailableByProduct($product['product_id'], $product['product_attribute_id'], $product['id_shop']);
                $products[$key]['unit_price_tax_incl'] = Tools::displayPrice(Tools::convertPriceFull($product['unit_price_tax_incl'], $currency_from, $this->context->currency));
                $products[$key]['unit_price_tax_excl'] = Tools::displayPrice(Tools::convertPriceFull($product['unit_price_tax_excl'], $currency_from, $this->context->currency));
                $products[$key]['total_price_tax_incl'] = Tools::displayPrice(Tools::convertPriceFull($product['total_price_tax_incl'], $currency_from, $this->context->currency));
                $products[$key]['total_price_tax_excl'] = Tools::displayPrice(Tools::convertPriceFull($product['total_price_tax_excl'], $currency_from, $this->context->currency));
                $products[$key]['unit_commission_tax_incl'] = Tools::displayPrice(Tools::convertPriceFull($product['unit_commission_tax_incl'], $currency_from, $this->context->currency));
                $products[$key]['unit_commission_tax_excl'] = Tools::displayPrice(Tools::convertPriceFull($product['unit_commission_tax_excl'], $currency_from, $this->context->currency));
                $products[$key]['total_commission_tax_incl'] = Tools::displayPrice(Tools::convertPriceFull($product['total_commission_tax_incl'], $currency_from, $this->context->currency));
                $products[$key]['total_commission_tax_excl'] = Tools::displayPrice(Tools::convertPriceFull($product['total_commission_tax_excl'], $currency_from, $this->context->currency));
                $products[$key]['reduction_amount_display'] = Tools::displayPrice(Tools::convertPriceFull($product['reduction_amount'], $currency_from, $this->context->currency));
            }
            
            $order_states = OrderState::getOrderStates($this->context->language->id);
            if (is_array($order_states) && count($order_states)) {
                foreach ($order_states as $key => $state) {
                    if (Configuration::get('JMARKETPLACE_SELL_ORDER_STATE_'.$state['id_order_state']) == 0) {
                        unset($order_states[$key]);
                    }
                }
            }
            
            $order_shipping = $order->getShipping();
            if (is_array($order_shipping) && count($order_shipping)) {
                foreach ($order_shipping as $key => $oc) {
                    $order_shipping[$key]['shipping_cost_tax_excl'] = Tools::displayPrice(Tools::convertPriceFull($oc['shipping_cost_tax_excl'], $currency_from, $this->context->currency), (int)$this->context->currency->id);
                    $order_shipping[$key]['shipping_cost_tax_incl'] = Tools::displayPrice(Tools::convertPriceFull($oc['shipping_cost_tax_incl'], $currency_from, $this->context->currency), (int)$this->context->currency->id);
                }
            }
            
            $this->context->smarty->assign(array(
                'seller_link' => $url_seller_profile,
                'show_import_product' => Configuration::get('JMARKETPLACE_SELLER_IMPORT_PROD'),
                'show_orders' => Configuration::get('JMARKETPLACE_SHOW_ORDERS'),
                'show_manage_orders' => Configuration::get('JMARKETPLACE_SHOW_MANAGE_ORDERS'),
                'show_manage_carriers' => Configuration::get('JMARKETPLACE_SHOW_MANAGE_CARRIER'),
                'show_contact' => Configuration::get('JMARKETPLACE_SHOW_CONTACT'),
                'show_edit_seller_account' => Configuration::get('JMARKETPLACE_SHOW_EDIT_ACCOUNT'),
                'show_dashboard' => Configuration::get('JMARKETPLACE_SHOW_DASHBOARD'),
                'show_seller_invoice' => Configuration::get('JMARKETPLACE_SHOW_SELLER_INVOICE'),
                'show_menu_top' => Configuration::get('JMARKETPLACE_MENU_TOP'),
                'show_menu_options' => Configuration::get('JMARKETPLACE_MENU_OPTIONS'),
                'tax_commission' => Configuration::get('JMARKETPLACE_TAX_COMMISSION'),
                'shipping_commission' => Configuration::get('JMARKETPLACE_SHIPPING_COMMISSION'),
                'fixed_commission' => Tools::displayPrice(Tools::convertPriceFull($order->total_fixed_commission, $currency_from, $this->context->currency), (int)$this->context->currency->id),
                'order_link' => $this->context->link->getModuleLink('jmarketplace', 'orders', $params_order, true),
                'order' => $order,
                'order_state_history' => $order->getHistory($this->context->language->id),
                'order_shipping' => $order_shipping,
                'ps_weight_unit' => Configuration::get('PS_WEIGHT_UNIT'),
                'order_states' => $order_states,
                'customer_name' => $customer->firstname.' '.$customer->lastname,
                'address_delivery' => $deliveryAddressFormatedValues,
                'products' => $products,
                'total_weight' => $order->getTotalWeight(),
                'total_products_tax_incl' => Tools::displayPrice(Tools::convertPriceFull($order->total_products_tax_incl, $currency_from, $this->context->currency), (int)$this->context->currency->id),
                'total_products_tax_excl' => Tools::displayPrice(Tools::convertPriceFull($order->total_products_tax_excl, $currency_from, $this->context->currency), (int)$this->context->currency->id),
                'total_paid_tax_incl' => Tools::displayPrice(Tools::convertPriceFull($order->total_paid_tax_incl, $currency_from, $this->context->currency), (int)$this->context->currency->id),
                'total_paid_tax_excl' => Tools::displayPrice(Tools::convertPriceFull($order->total_paid_tax_excl, $currency_from, $this->context->currency), (int)$this->context->currency->id),
                'total_discounts' => Tools::displayPrice(Tools::convertPriceFull($order->total_discounts, $currency_from, $this->context->currency), (int)$this->context->currency->id),
                'total_discounts_tax_excl' => Tools::displayPrice(Tools::convertPriceFull($order->total_discounts_tax_excl, $currency_from, $this->context->currency), (int)$this->context->currency->id),
                'total_discounts_tax_incl' => Tools::displayPrice(Tools::convertPriceFull($order->total_discounts_tax_incl, $currency_from, $this->context->currency), (int)$this->context->currency->id),
                'total_shipping' => $order->total_shipping,
                'total_shipping_tax_incl' => Tools::displayPrice(Tools::convertPriceFull($order->total_shipping_tax_incl, $currency_from, $this->context->currency), (int)$this->context->currency->id),
                'total_shipping_tax_excl' => Tools::displayPrice(Tools::convertPriceFull($order->total_shipping_tax_excl, $currency_from, $this->context->currency), (int)$this->context->currency->id),
                'weight_unit' => Configuration::get('PS_WEIGHT_UNIT'),
                'sign' => $this->context->currency->sign,
            ));
            
            $this->setTemplate('order.tpl');
        }
    }
}
