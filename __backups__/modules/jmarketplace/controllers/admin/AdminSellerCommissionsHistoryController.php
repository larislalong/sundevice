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

class AdminSellerCommissionsHistoryController extends ModuleAdminController
{
    public function setMedia()
    {
        parent::setMedia();
        if (Tools::isSubmit('addseller_commission_history') || Tools::isSubmit('updateseller_commission_history')) {
            $this->context->controller->addjQueryPlugin(array('select2'));
            $this->context->controller->addJS(_PS_MODULE_DIR_.'jmarketplace/views/js/select2call.js');
        }
    }
    
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'seller_commission_history';
        $this->className = 'SellerCommissionHistory';
        $this->lang = false;
        
        $this->context = Context::getContext();
        
        parent::__construct();

        $states = SellerCommissionHistoryState::getStates((int)$this->context->language->id);
        foreach ($states as $state) {
            $this->states_array[$state['id_seller_commission_history_state']] = $state['name'];
        }

        $this->_select = 'a.*, o.reference, s.name as seller_name, schsl.name as state_name';
        $this->_join = 'LEFT JOIN `'._DB_PREFIX_.'seller` s ON (s.`id_seller` = a.`id_seller`) 
                        LEFT JOIN `'._DB_PREFIX_.'seller_commission_history_state` schs ON (schs.`id_seller_commission_history_state` = a.`id_seller_commission_history_state`)
                        LEFT JOIN `'._DB_PREFIX_.'seller_commission_history_state_lang` schsl ON (schsl.`id_seller_commission_history_state` = a.`id_seller_commission_history_state` AND schsl.id_lang = '.(int)$this->context->language->id.')
                        LEFT JOIN `'._DB_PREFIX_.'product` p ON (a.`id_product` = p.`id_product`) 
                        LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (pl.`id_product` = p.`id_product` AND pl.id_lang = '.(int)$this->context->language->id.' AND pl.id_shop = '.(int)$this->context->shop->id.') 
                        LEFT JOIN `'._DB_PREFIX_.'orders` o ON (o.`id_order` = a.`id_order`)';

        $this->_where = 'AND a.id_shop = '.(int)$this->context->shop->id;
        
        if (Configuration::get('JMARKETPLACE_TAX_COMMISSION') == 1) {
            $price_column = 'price_tax_incl';
            $title_price = $this->l('Price (tax incl.)');
            $unit_commission_column = 'unit_commission_tax_incl';
            $title_unit_commission = $this->l('Unit commission (tax incl.)');
            $total_commission_column = 'total_commission_tax_incl';
            $title_total_commission = $this->l('Total commission (tax incl.)');
        } else {
            $price_column = 'price_tax_excl';
            $title_price = $this->l('Price (tax excl.)');
            $unit_commission_column = 'unit_commission_tax_excl';
            $title_unit_commission = $this->l('Unit commission (tax excl.)');
            $total_commission_column = 'total_commission_tax_excl';
            $title_total_commission = $this->l('Total commission (tax excl.)');
        }

        $this->fields_list = array(
            'id_order' => array(
                'title' => $this->l('Order ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs',
                'havingFilter' => true,
            ),
            'reference' => array(
                'title' => $this->l('Order reference'),
                'align' => 'center',
                'class' => 'fixed-width-xs',
                'havingFilter' => true,
            ),
            'seller_name' => array(
                'title' => $this->l('Seller name'),
                'havingFilter' => true,
            ),
            'product_name' => array(
                'title' => $this->l('Concept'),
                'havingFilter' => true,
            ),
            $price_column => array(
                'title' => $title_price,
                'havingFilter' => true,
                'align' => 'text-right',
                'type' => 'price',
                'currency' => true,
                'badge_success' => true
            ),
            $unit_commission_column => array(
                'title' => $title_unit_commission,
                'havingFilter' => true,
                'align' => 'text-right',
                'type' => 'price',
                'currency' => true,
                'badge_success' => true
            ),
            'quantity' => array(
                'title' => $this->l('Product quantity'),
                'havingFilter' => true,
                'align' => 'text-right',
            ),
            $total_commission_column => array(
                'title' => $title_total_commission,
                'havingFilter' => true,
                'align' => 'text-right',
                'type' => 'price',
                'currency' => true,
                'badge_success' => true
            ),
            'state_name' => array(
                'title' => $this->l('Payment state'),
                'type' => 'select',
                'list' => $this->states_array,
                'filter_key' => 'a!id_seller_commission_history_state',
                'filter_type' => 'int',
                'order_key' => 'state_name',
            ),
            'date_add' => array(
                'title' => $this->l('Date add'),
                'type' => 'datetime',
                'orderby' => true,
                'search' => true,
                'havingFilter' => true,
            ),
        );

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
                'icon' => 'icon-trash'
            ),
            'pay' => array(
                'text' => $this->l('Pay selected'),
                'confirm' => $this->l('Pay selected items?'),
                'icon' => 'icon-money'
            ),
            'cancel' => array(
                'text' => $this->l('Cancel selected'),
                'confirm' => $this->l('Cancel selected items?'),
                'icon' => 'icon-ban'
            )
        );
    }

    public function renderList()
    {
        //$this->addRowAction('view');
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        
        return parent::renderList();
    }

    public function renderForm()
    {
        $title = $this->l('Add new commission');
        if (Tools::getValue('id_seller_commission_history')) {
            $id_seller_commission_history = (int)Tools::getValue('id_seller_commission_history');
            $seller_commission_history = new SellerCommissionHistory($id_seller_commission_history);
            $currency = new Currency($seller_commission_history->id_currency);
            $title = $this->l('Edit commission for').' '.$seller_commission_history->product_name;
        }
        
        $orders = array(array('id_order' => 0, 'reference' => $this->l('Not apply')));
        $orders = array_merge($orders, Order::getOrdersWithInformations());
        
        $products = array(array('id_product' => 0, 'name' => $this->l('Not apply')));
        $products = array_merge($products, Product::getSimpleProducts($this->context->language->id));
        
        $states = SellerCommissionHistoryState::getStates((int)$this->context->language->id);
        
        $this->fields_form = array(
        'legend' => array(
            'title' => $title,
            'icon' => 'icon-money'
        ),
        'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => 'conversion_rate'
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Order'),
                    'name' => 'id_order',
                    'required' => false,
                    'class'=> 'select2',
                    'options' => array(
                        'query' => $orders,
                        'id' => 'id_order',
                        'name' => 'reference'
                    )
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Seller'),
                    'name' => 'id_seller',
                    'required' => false,
                    'class'=> 'select2',
                    'options' => array(
                        'query' => Seller::getSellers($this->context->shop->id),
                        'id' => 'id_seller',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Product'),
                    'name' => 'id_product',
                    'required' => false,
                    'class'=> 'select2',
                    'options' => array(
                        'query' => $products,
                        'id' => 'id_product',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Concept'),
                    'name' => 'product_name',
                    'lang' => false,
                    'col' => 3,
                    'required' => false,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Price tax excl.'),
                    'name' => 'price_tax_excl',
                    'suffix' => $currency->sign,
                    'lang' => false,
                    'col' => 2,
                    'required' => false,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Price tax incl.'),
                    'name' => 'price_tax_incl',
                    'suffix' => $currency->sign,
                    'lang' => false,
                    'col' => 2,
                    'required' => false,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Quantity'),
                    'name' => 'quantity',
                    'lang' => false,
                    'col' => 2,
                    'required' => false,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Unit commission tax excl.'),
                    'name' => 'unit_commission_tax_excl',
                    'suffix' => $currency->sign,
                    'lang' => false,
                    'col' => 2,
                    'required' => false,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Unit commission tax incl.'),
                    'name' => 'unit_commission_tax_incl',
                    'suffix' => $currency->sign,
                    'lang' => false,
                    'col' => 2,
                    'required' => false,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Total commission tax excl.'),
                    'name' => 'total_commission_tax_excl',
                    'suffix' => $currency->sign,
                    'lang' => false,
                    'col' => 2,
                    'required' => false,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Total commission tax incl.'),
                    'name' => 'total_commission_tax_incl',
                    'suffix' => $currency->sign,
                    'lang' => false,
                    'col' => 2,
                    'required' => false,
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Payment state'),
                    'name' => 'id_seller_commission_history_state',
                    'required' => false,
                    'class'=> 'select2',
                    'options' => array(
                        'query' => $states,
                        'id' => 'id_seller_commission_history_state',
                        'name' => 'name'
                    )
                ),
            )
        );
        
        $this->fields_value['conversion_rate'] = 1;

        $this->fields_form['submit'] = array(
            'title' => $this->l('Save'),
        );

        return parent::renderForm();
    }
    
    public function postProcess()
    {
        parent::postProcess();
        
        $this->_orderBy = 'date_add';
        $this->_orderWay = 'DESC';
        
        if (Tools::isSubmit('submitAddseller_commission_history')) {
            $id_order = Tools::getValue('id_order');
            $id_product = Tools::getValue('id_product');
            $id_seller = Tools::getValue('id_seller');
            $concept = Tools::getValue('product_name');
            $price_tax_excl = Tools::getValue('price_tax_excl');
            $price_tax_incl = Tools::getValue('price_tax_incl');
            $quantity = Tools::getValue('quantity');
            $unit_commission_tax_excl = Tools::getValue('unit_commission_tax_excl');
            $unit_commission_tax_incl = Tools::getValue('unit_commission_tax_incl');
            $total_commission_tax_excl = Tools::getValue('total_commission_tax_excl');
            $total_commission_tax_incl = Tools::getValue('total_commission_tax_incl');
            $id_seller_commission_history_state = (int)Tools::getValue('id_seller_commission_history_state');
            
            if (!$concept) {
                $this->errors[] = Tools::displayError('The concept is not valid.', 'AdminSellerCommissionsHistoryController');
            }
            
            if (!Validate::isFloat($price_tax_excl)) {
                $this->errors[] = Tools::displayError('The price tax excluded is not valid.', 'AdminSellerCommissionsHistoryController');
            }
            
            if (!Validate::isFloat($price_tax_incl)) {
                $this->errors[] = Tools::displayError('The price tax included is not valid.', 'AdminSellerCommissionsHistoryController');
            }
            
            if (!Validate::isInt($quantity)) {
                $this->errors[] = Tools::displayError('The quantity is not valid.', 'AdminSellerCommissionsHistoryController');
            }
            
            if (!Validate::isFloat($unit_commission_tax_excl)) {
                $this->errors[] = Tools::displayError('The unit commission tax excluded is not valid.', 'AdminSellerCommissionsHistoryController');
            }
            
            if (!Validate::isFloat($unit_commission_tax_incl)) {
                $this->errors[] = Tools::displayError('The unit commission tax included is not valid.', 'AdminSellerCommissionsHistoryController');
            }
            
            if (!Validate::isFloat($total_commission_tax_excl)) {
                $this->errors[] = Tools::displayError('The total commission tax excluded is not valid.', 'AdminSellerCommissionsHistoryController');
            }
            
            if (!Validate::isFloat($total_commission_tax_incl)) {
                $this->errors[] = Tools::displayError('The total commission tax included is not valid.', 'AdminSellerCommissionsHistoryController');
            }
            
            if (count($this->errors) == 0) {
                if (Tools::getValue('id_seller_commission_history')) {
                    $seller_commission_history = new SellerCommissionHistory((int)Tools::getValue('id_seller_commission_history'));
                } else {
                    $seller_commission_history = new SellerCommissionHistory();
                }

                $seller_commission_history->id_order = (int)$id_order;
                $seller_commission_history->id_product = (int)$id_product;
                $seller_commission_history->product_name = (string)$concept;
                $seller_commission_history->id_seller = (int)$id_seller;
                $seller_commission_history->id_shop = $this->context->shop->id;
                $seller_commission_history->price_tax_excl = (float)$price_tax_excl;
                $seller_commission_history->price_tax_incl = (float)$price_tax_incl;
                $seller_commission_history->quantity = (int)$quantity;
                $seller_commission_history->unit_commission_tax_excl = (float)$unit_commission_tax_excl;
                $seller_commission_history->unit_commission_tax_incl = (float)$unit_commission_tax_incl;
                $seller_commission_history->total_commission_tax_excl = (float)$total_commission_tax_excl;
                $seller_commission_history->total_commission_tax_incl = (float)$total_commission_tax_incl;
                $seller_commission_history->id_seller_commission_history_state = (int)$id_seller_commission_history_state;
                $seller_commission_history->conversion_rate = 1;

                if (Tools::getValue('id_seller_commission_history')) {
                    $seller_commission_history->update();
                } else {
                    $seller_commission_history->add();
                }
            }
        } elseif (Tools::isSubmit('submitBulkpayseller_commission_history')) {
            $seller_commissions = Tools::getValue('seller_commission_historyBox');
            if (is_array($seller_commissions)) {
                foreach ($seller_commissions as $id_seller_commission_history) {
                    $seller_commission_history = new SellerCommissionHistory($id_seller_commission_history);
                    $seller_commission_history->id_seller_commission_history_state = SellerCommissionHistoryState::getIdByReference('paid');
                    $seller_commission_history->update();
                }
            }
        } elseif (Tools::isSubmit('submitBulkcancelseller_commission_history')) {
            $seller_commissions = Tools::getValue('seller_commission_historyBox');
            if (is_array($seller_commissions)) {
                foreach ($seller_commissions as $id_seller_commission_history) {
                    $seller_commission_history = new SellerCommissionHistory($id_seller_commission_history);
                    $seller_commission_history->id_seller_commission_history_state = SellerCommissionHistoryState::getIdByReference('cancel');
                    $seller_commission_history->update();
                }
            }
        }
    }
}
