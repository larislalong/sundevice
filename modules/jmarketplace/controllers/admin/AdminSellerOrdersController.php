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

class AdminSellerOrdersController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'seller_order';
        $this->className = 'SellerOrder';
        $this->lang = false;
        
        $this->context = Context::getContext();

        parent::__construct();
        
        if (Configuration::get('JMARKETPLACE_TAX_COMMISSION') == 1) {
            $total_paid_column = 'total_paid_tax_incl';
            $total_paid = $this->l('Total paid (tax incl.)');
        } else {
            $total_paid_column = 'total_paid_tax_excl';
            $total_paid = $this->l('Total paid (tax excl.)');
        }
        
        $this->_select = 's.name as seller_name,
                          CONCAT(c.firstname, \' \',  c.lastname) as customer_name, 
                          osl.name as state_name,
                          '.$total_paid_column;

        $this->_join = '
            LEFT JOIN '._DB_PREFIX_.'seller s ON (s.id_seller = a.id_seller AND s.id_shop = '.(int)$this->context->shop->id.')
            LEFT JOIN '._DB_PREFIX_.'customer c ON (c.id_customer = a.id_customer)
            LEFT JOIN '._DB_PREFIX_.'order_state os ON (os.id_order_state = a.current_state)
            LEFT JOIN '._DB_PREFIX_.'order_state_lang osl ON (osl.id_order_state = os.id_order_state AND osl.id_lang = '.(int)$this->context->language->id.')';
        
        $this->_where = 'AND a.id_shop = '.(int)$this->context->shop->id;
        
        $statuses = OrderState::getOrderStates((int)$this->context->language->id);
        foreach ($statuses as $status) {
            $this->statuses_array[$status['id_order_state']] = $status['name'];
        }

        $this->fields_list = array(
            'reference' => array(
                'title' => $this->l('Order reference'),
                'align' => 'center',
                'class' => 'fixed-width-xs',
                'havingFilter' => true,
            ),
            'seller_name' => array(
                'title' => $this->l('Seller name'),
                'orderby' => true,
                'search' => true,
                'havingFilter' => true,
            ),
            'customer_name' => array(
                'title' => $this->l('Customer name'),
                'orderby' => true,
                'search' => true,
                'havingFilter' => true,
            ),
            $total_paid_column => array(
                'title' => $total_paid,
                'havingFilter' => true,
                'align' => 'text-right',
                'type' => 'price',
                'currency' => true,
                'badge_success' => true
            ),
            'state_name' => array(
                'title' => $this->l('Order state'),
                'type' => 'select',
                'color' => 'color',
                'list' => $this->statuses_array,
                'filter_key' => 'os!id_order_state',
                'filter_type' => 'int',
                'order_key' => 'state_name'
            ),
            'date_add' => array(
                'title' => $this->l('Date add'),
                'type' => 'datetime',
            ),
            'date_upd' => array(
                'title' => $this->l('Date update'),
                'type' => 'datetime',
            )
        );
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
                'icon' => 'icon-trash'
            )
        );
    }

    public function renderList()
    {
        $this->addRowAction('view');
        //$this->addRowAction('edit');
        $this->addRowAction('delete');

        return parent::renderList();
    }

    public function renderForm()
    {
        $title = $this->l('Add order');
        if (Tools::isSubmit('updateseller_order')) {
            $title = $this->l('Edit order');
        }
        
        $this->fields_form = array(
            'legend' => array(
                'title' => $title,
                'icon' => 'icon-globe'
            ),
            'input' => array(
                    array(
                        'type' => 'hidden',
                        'name' => 'id_seller_order',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Order reference'),
                        'name' => 'reference',
                        'lang' => false,
                        'col' => 3,
                        'required' => true,
                        'disabled' => true,
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Seller'),
                        'name' => 'id_seller',
                        'required' => true,
                        'disabled' => true,
                        'options' => array(
                            'query' => Seller::getSellers($this->context->shop->id),
                            'id' => 'id_seller',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Customer'),
                        'name' => 'id_customer',
                        'required' => true,
                        'disabled' => true,
                        'options' => array(
                            'query' => Customer::getCustomers(),
                            'id' => 'id_customer',
                            'name' => 'email'
                        )
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Order state'),
                        'name' => 'current_state',
                        'required' => false,
                        'options' => array(
                            'query' => OrderState::getOrderStates($this->context->language->id),
                            'id' => 'id_order_state',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Total discounts (tax excl.)'),
                        'name' => 'total_discounts_tax_excl',
                        'lang' => false,
                        'col' => 3,
                        'required' => false,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Total discounts (tax incl.)'),
                        'name' => 'total_discounts_tax_incl',
                        'lang' => false,
                        'col' => 3,
                        'required' => false,
                    ),
                    
                    array(
                        'type' => 'text',
                        'label' => $this->l('Total paid (tax excl.)'),
                        'name' => 'total_paid_tax_excl',
                        'lang' => false,
                        'col' => 3,
                        'required' => false,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Total paid (tax incl.)'),
                        'name' => 'total_paid_tax_incl',
                        'lang' => false,
                        'col' => 3,
                        'required' => false,
                    ),
            )
        );

        $this->fields_form['submit'] = array(
            'title' => $this->l('Save'),
        );

        return parent::renderForm();
    }
    
    public function postProcess()
    {
        parent::postProcess();
        
        if (Tools::isSubmit('submitFilter')) {
            if (Configuration::get('JMARKETPLACE_TAX_COMMISSION') == 1) {
                $total_paid_column = 'total_paid_tax_incl';
            } else {
                $total_paid_column = 'total_paid_tax_excl';
            }
            
            if (Tools::getValue('seller_orderFilter_reference') != '') {
                $this->_where .= ' AND a.reference LIKE "%'.pSQL(Tools::getValue('seller_orderFilter_reference')).'%"';
            }
            
            if (Tools::getValue('seller_orderFilter_seller_name') != '') {
                $this->_where .= ' AND s.name LIKE "%'.pSQL(Tools::getValue('seller_orderFilter_seller_name')).'%"';
            }
            
            if (Tools::getValue('seller_orderFilter_customer_name') != '') {
                $this->_where .= ' AND (c.firstname LIKE "%'.pSQL(Tools::getValue('seller_orderFilter_customer_name')).'%" OR c.lastname LIKE "%'.pSQL(Tools::getValue('seller_orderFilter_customer_name')).'%")';
            }
            
            if (Tools::getValue('seller_orderFilter_'.$total_paid_column) != '') {
                $this->_where .= ' AND a.'.$total_paid_column.' = '.(float)Tools::getValue('seller_orderFilter_'.$total_paid_column);
            }
            
            if (Tools::getValue('seller_orderFilter_os!id_order_state') != '') {
                $this->_where .= ' AND a.current_state = '.(int)Tools::getValue('seller_orderFilter_os!id_order_state');
            }
            
            $arrayDateAdd = Tools::getValue('seller_orderFilter_date_add');
            if ($arrayDateAdd[0] != '' && $arrayDateAdd[1] != '') {
                $this->_where .= ' AND a.date_add BETWEEN "'.pSQL($arrayDateAdd[0]).'" AND "'.pSQL($arrayDateAdd[1]).'"';
            }
            
            $arrayDateUpd = Tools::getValue('seller_orderFilter_date_upd');
            if ($arrayDateUpd[0] != '' && $arrayDateUpd[1] != '') {
                $this->_where .= ' AND a.date_upd BETWEEN "'.pSQL($arrayDateUpd[0]).'" AND "'.pSQL($arrayDateUpd[1]).'"';
            }
        }
        
        $this->_orderBy = 'date_upd';
        $this->_orderWay = 'DESC';
        
        if (Tools::getValue('seller_orderOrderway')) {
            $this->_orderBy = pSQL(Tools::getValue('seller_orderOrderby'));
            $this->_orderWay = pSQL(Tools::getValue('seller_orderOrderway'));
        }
        
        if ($this->display == 'view') {
            $id_seller_order = (int)Tools::getValue('id_seller_order');
            $order = new SellerOrder($id_seller_order);
            $seller = new Seller($order->id_seller);
            $customer = new Customer($order->id_customer);
            $address_delivery = new Address($order->id_address_delivery);
            $dlv_adr_fields = AddressFormat::getOrderedAddressFields($address_delivery->id_country);
            $deliveryAddressFormatedValues = AddressFormat::getFormattedAddressFieldsValues($address_delivery, $dlv_adr_fields);
            
            if (Tools::isSubmit('submitState')) {
                $id_seller = $seller->id;
                $id_order = (int)Tools::getValue('id_order');
                $id_order_state = (int)Tools::getValue('id_order_state');
                $order_state = new OrderState($id_order_state);

                if (!Validate::isLoadedObject($order_state)) {
                    $this->errors[] = Tools::displayError('The new order status is invalid.');
                } else {
                    //$current_order_state = $order->getCurrentOrderState();
                    if ($order->current_state != $order_state->id) {
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
                    }
                }
            }
            
            $products = $order->getProductsDetail();
            $i = 0;
            foreach ($products as $product) {
                $products[$i]['current_stock'] = StockAvailable::getQuantityAvailableByProduct($product['product_id'], $product['product_attribute_id'], $product['id_shop']);
                $products[$i]['unit_price_tax_incl'] = Tools::displayPrice($product['unit_price_tax_incl'], (int)$order->id_currency);
                $products[$i]['unit_price_tax_excl'] = Tools::displayPrice($product['unit_price_tax_excl'], (int)$order->id_currency);
                $products[$i]['total_price_tax_incl'] = Tools::displayPrice($product['total_price_tax_incl'], (int)$order->id_currency);
                $products[$i]['total_price_tax_excl'] = Tools::displayPrice($product['total_price_tax_excl'], (int)$order->id_currency);
                $products[$i]['unit_commission_tax_incl'] = Tools::displayPrice($product['unit_commission_tax_incl'], (int)$order->id_currency);
                $products[$i]['unit_commission_tax_excl'] = Tools::displayPrice($product['unit_commission_tax_excl'], (int)$order->id_currency);
                $products[$i]['total_commission_tax_incl'] = Tools::displayPrice($product['total_commission_tax_incl'], (int)$order->id_currency);
                $products[$i]['total_commission_tax_excl'] = Tools::displayPrice($product['total_commission_tax_excl'], (int)$order->id_currency);
                $products[$i]['reduction_amount_display'] = Tools::displayPrice($product['reduction_amount'], (int)$order->id_currency);
                $i++;
            }
            
            $this->context->smarty->assign(array(
                'tax_commission' => Configuration::get('JMARKETPLACE_TAX_COMMISSION'),
                'fixed_commission' => Tools::displayPrice($order->total_fixed_commission, (int)$order->id_currency),
                'order' => $order,
                'seller' => $seller,
                'customer' => $customer,
                'address_delivery' => $deliveryAddressFormatedValues,
                'total_weight' => $order->getTotalWeight(),
                'weight_unit' => Configuration::get('PS_WEIGHT_UNIT'),
                'products' => $products,
                'total_products_tax_incl' => Tools::displayPrice($order->total_products_tax_incl, (int)$order->id_currency),
                'total_products_tax_excl' => Tools::displayPrice($order->total_products_tax_excl, (int)$order->id_currency),
                'total_paid_tax_incl' => Tools::displayPrice($order->total_paid_tax_incl, (int)$order->id_currency),
                'total_paid_tax_excl' => Tools::displayPrice($order->total_paid_tax_excl, (int)$order->id_currency),
                'total_discounts' => Tools::displayPrice($order->total_discounts, (int)$order->id_currency),
                'total_discounts_tax_excl' => Tools::displayPrice($order->total_discounts_tax_excl, (int)$order->id_currency),
                'total_discounts_tax_incl' => Tools::displayPrice($order->total_discounts_tax_incl, (int)$order->id_currency),
                'total_shipping' => $order->total_shipping,
                'total_shipping_tax_incl' => Tools::displayPrice($order->total_shipping_tax_incl, (int)$order->id_currency),
                'total_shipping_tax_excl' => Tools::displayPrice($order->total_shipping_tax_excl, (int)$order->id_currency),
                'order_state_history' => $order->getHistory($this->context->language->id),
                'order_states' => OrderState::getOrderStates($this->context->language->id),
                'token' => $this->token,
                'order_link' => 'index.php?tab=AdminSellerOrders&id_seller_order='.(int)$order->id.'&viewseller_order&token='.Tools::getAdminToken('AdminSellerOrders'.(int)Tab::getIdFromClassName('AdminSellerOrders').(int)$this->context->employee->id),
                'url_order' => 'index.php?tab=AdminOrders&id_order='.(int)$order->id_order.'&vieworder&token='.Tools::getAdminToken('AdminOrders'.(int)Tab::getIdFromClassName('AdminOrders').(int)$this->context->employee->id),
                'url_customer' => 'index.php?tab=AdminCustomers&id_customer='.(int)$customer->id.'&viewcustomer&token='.Tools::getAdminToken('AdminCustomers'.(int)Tab::getIdFromClassName('AdminCustomers').(int)$this->context->employee->id),
                'url_seller' => 'index.php?tab=AdminSellers&id_seller='.(int)$seller->id.'&viewseller&token='.Tools::getAdminToken('AdminSellers'.(int)Tab::getIdFromClassName('AdminSellers').(int)$this->context->employee->id),
            ));
        }

        //delete seller order
        if (Tools::isSubmit('deleteseller_order')) {
            $id_seller_order = (int)Tools::getValue('id_seller_order');
            $seller_order = new SellerOrder($id_seller_order);
            $seller_order->delete();
        }
        
        //delete seller orders selected
        if (Tools::isSubmit('submitBulkdeleteseller_order')) {
            $seller_orders_selected = Tools::getValue('seller_orderBox');
            foreach ($seller_orders_selected as $id_seller_order) {
                $seller_order = new SellerOrder($id_seller_order);
                $seller_order->delete();
            }
        }
    }
}
