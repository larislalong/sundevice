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

class AdminSellerCommissionsController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'seller_commission';
        $this->className = 'SellerCommission';
        $this->lang = false;
        
        $this->context = Context::getContext();

        parent::__construct();
        
        $this->_select = 'id_seller_commission, s.name seller_name, a.commission';
        $this->_join = 'LEFT JOIN '._DB_PREFIX_.'seller s ON (s.id_seller = a.id_seller)';
        $this->_where = 'AND s.id_shop = '.(int)$this->context->shop->id;

        $this->fields_list = array(
            'id_seller_commission' => array(
                'title' => $this->l('ID Commission'),
                'align' => 'center',
                'class' => 'fixed-width-xs',
                'havingFilter' => true,
            ),
            'seller_name' => array(
                'title' => $this->l('Seller name'),
                'havingFilter' => true,
            ),
            'commission' => array(
                'title' => $this->l('Commission (%)'),
                'havingFilter' => true,
            )
        );
    }

    public function renderList()
    {
        $this->addRowAction('edit');
        return parent::renderList();
    }
    
    public function renderForm()
    {
        $id_seller_commission = (int)Tools::getValue('id_seller_commission');
        $seller_commission = new SellerCommission($id_seller_commission);
        $seller = new Seller($seller_commission->id_seller);
        
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Edit seller commission of').' "'.$seller->name.'"',
                'icon' => 'icon-globe'
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Commission'),
                    'suffix' => '%',
                    'name' => 'commission',
                    'lang' => false,
                    'col' => 2,
                    'required' => true,
                    'desc' => $this->l('Values: 1-100'),
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
        
        if (Tools::isSubmit('submitFilterseller_commission')) {
            if (Tools::getValue('seller_commissionFilter_id_seller_commission') != '') {
                $this->_where = 'AND a.id_seller_commission = '.(int)Tools::getValue('seller_commissionFilter_id_seller_commission');
            }

            if (Tools::getValue('seller_commissionFilter_seller_name') != '') {
                $this->_where = 'AND s.name LIKE "%'.pSQL(Tools::getValue('seller_commissionFilter_seller_name')).'%"';
            }

            if (Tools::getValue('seller_commissionFilter_commission') != '') {
                $this->_where = 'AND a.commission = '.(int)Tools::getValue('seller_commissionFilter_commission');
            }
        }
        
        $this->_orderBy = 'a.id_seller_commission';
        $this->_orderWay = 'DESC';
        
        if (Tools::getValue('seller_commissionOrderway')) {
            $this->_orderBy = pSQL(Tools::getValue('seller_commissionOrderby'));
            $this->_orderWay = pSQL(Tools::getValue('seller_commissionOrderway'));
        }
    }
}
