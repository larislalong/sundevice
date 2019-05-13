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

class AdminSellerCommissionsHistoryStatesController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'seller_commission_history_state';
        $this->className = 'SellerCommissionHistoryState';
        $this->lang = true;
        
        $this->context = Context::getContext();
        
        parent::__construct();

        $this->fields_list = array(
            'id_seller_commission_history_state' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs'
            ),
            'name' => array(
                'title' => $this->l('Payment state name'),
            ),
            'active' => array(
                'title' => $this->l('Enabled'),
                'align' => 'center',
                'active' => 'status',
                'type' => 'bool',
                'orderby' => false,
                'class' => 'fixed-width-sm'
            )
        );
    }

    public function initPageHeaderToolbar()
    {
        if (empty($this->display)) {
            $this->page_header_toolbar_btn['new_seller_commission_history_state'] = array(
                'href' => self::$currentIndex.'&addseller_commission_history_state&token='.$this->token,
                'desc' => $this->l('Add new state', null, null, false),
                'icon' => 'process-icon-new'
            );
        }

        parent::initPageHeaderToolbar();
    }

    public function renderList()
    {
        $this->addRowAction('edit');
        //$this->addRowAction('delete');

        return parent::renderList();
    }

    public function renderForm()
    {
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Add/Edit Paypment state'),
                'icon' => 'icon-globe'
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Name'),
                    'name' => 'name',
                    'lang' => true,
                    'col' => 3,
                    'required' => true,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Active'),
                    'name' => 'active',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    )
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
        
        if (Tools::isSubmit('submitAddseller_commission_history_state')) {
            $id_seller_commission_history_state = (int)Tools::getValue('id_seller_commission_history_state');
            $state_name = pSQL(Tools::getValue('name_'.$this->context->language->id));
            $active = (int)Tools::getValue('active');
            
            if ($state_name == '') {
                $this->errors[] = $this->module->l('The state name is incorrect.', 'AdminSellerCommisionsHistoryStatesController');
            }
            
            if (count($this->errors) == 0) {
                if ($id_seller_commission_history_state) {
                    $seller_commission_history_state = new SellerCommissionHistoryState($id_seller_commission_history_state);
                } else {
                    $seller_commission_history_state = new SellerCommissionHistoryState();
                }

                $seller_commission_history_state->active = $active;
                
                foreach (Language::getLanguages() as $lang) {
                    if (Tools::getValue('name_'.$lang['id_lang']) != '') {
                        $seller_commission_history_state->name[$lang['id_lang']] = pSQL(Tools::getValue('name_'.$lang['id_lang']));
                    } else {
                        $seller_commission_history_state->name[$lang['id_lang']] = pSQL(Tools::getValue('name_'.$this->context->language->id));
                    }
                }

                if ($id_seller_commission_history_state) {
                    $seller_commission_history_state->update();
                } else {
                    $seller_commission_history_state->add();
                }
            }
        }
    }
}
