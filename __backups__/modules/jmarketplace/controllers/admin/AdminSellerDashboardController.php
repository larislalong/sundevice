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

class AdminSellerDashboardController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->lang = false;
        
        $this->context = Context::getContext();

        parent::__construct();
    }
    
    public function setMedia()
    {
        parent::setMedia();
        $this->addJqueryUI('ui.datepicker');
        $this->context->controller->addJS(_PS_MODULE_DIR_.'jmarketplace/views/js/Chart.bundle.min.js', 'all');
        $this->context->controller->addJS(_PS_MODULE_DIR_.'jmarketplace/views/js/admindashboard.js', 'all');
    }

    public function renderList()
    {
        $year = date('Y');
        
        $from = Configuration::get('JMARKETPLACE_EARNINGS_FROM').' 00:00:00';
        $to = Configuration::get('JMARKETPLACE_EARNINGS_TO').' 23:59:59';

        if (Tools::isSubmit('submitFilterDate')) {
            $from = Tools::getValue('from').' 00:00:00';
            $to = Tools::getValue('to').' 23:59:59';
            Configuration::updateValue('JMARKETPLACE_EARNINGS_FROM', Tools::getValue('from'));
            Configuration::updateValue('JMARKETPLACE_EARNINGS_TO', Tools::getValue('to'));
        }
        
        $commisions_chart = array();
        $from_array = explode(' ', $from);
        $to_array = explode(' ', $to);

        $date_next = $from_array[0];
        $date_end = $to_array[0];
        
        while (Dashboard::compareDates($date_next, $date_end) > 0) {
            $year_array = explode('-', $date_next);
            $year = $year_array[0];
            $month_array = explode('-', $date_next);
            $month = $month_array[1];
            $commisions_chart[$year.'-'.$month] = 0;
            $date_next = strtotime('+28 day', strtotime($date_next));
            $date_next = date('Y-m-d', $date_next);
        }
        
        $commissions = SellerCommissionHistory::getCommissions(2, $this->context->shop->id, $from, $to);
        $total_entries_for_admin = 0;
        $total_spends_for_admin = 0;
        $benefit_for_admin = 0;
        if (is_array($commissions) && count($commissions) > 0) {
            foreach ($commissions as $commission) {
                $currency_from = new Currency($commission['id_currency']);
                
                if (Configuration::get('JMARKETPLACE_TAX_COMMISSION') == 1) {
                    $total_entries_for_admin = $total_entries_for_admin + Tools::convertPriceFull(($commission['price_tax_incl'] * $commission['quantity']), $currency_from, $this->context->currency);
                    $total_spends_for_admin = $total_spends_for_admin + Tools::convertPriceFull($commission['total_commission_tax_incl'], $currency_from, $this->context->currency);
                } else {
                    $total_entries_for_admin = $total_entries_for_admin + Tools::convertPriceFull(($commission['price_tax_excl'] * $commission['quantity']), $currency_from, $this->context->currency);
                    $total_spends_for_admin = $total_spends_for_admin + Tools::convertPriceFull($commission['total_commission_tax_excl'], $currency_from, $this->context->currency);
                }
            }
        }
        
        $benefit_for_admin = $total_entries_for_admin - $total_spends_for_admin;
        
        $month_array = array(
            $this->module->l('Jan', 'AdminSellerDashboard'),
            $this->module->l('Feb', 'AdminSellerDashboard'),
            $this->module->l('Mar', 'AdminSellerDashboard'),
            $this->module->l('Apr', 'AdminSellerDashboard'),
            $this->module->l('May', 'AdminSellerDashboard'),
            $this->module->l('Jun', 'AdminSellerDashboard'),
            $this->module->l('Jul', 'AdminSellerDashboard'),
            $this->module->l('Aug', 'AdminSellerDashboard'),
            $this->module->l('Sep', 'AdminSellerDashboard'),
            $this->module->l('Oct', 'AdminSellerDashboard'),
            $this->module->l('Nov', 'AdminSellerDashboard'),
            $this->module->l('Dec', 'AdminSellerDashboard')
        );
        
        //stats
        $labels = '';
        $entry_string = '';
        $spend_string = '';
        $benefit_string = '';
        
        
        
        foreach ($commisions_chart as $key => $value) {
            $entry_month_array = SellerCommissionHistory::getCommissions(2, $this->context->shop->id, $key.'-01', $key.'-31');
            $entry_month = 0;
            $spend_month = 0;
            $benefit_month = 0;
            if (is_array($entry_month_array) && count($entry_month_array) > 0) {
                foreach ($entry_month_array as $commission) {
                    $currency_from = new Currency($commission['id_currency']);
                    
                    if (Configuration::get('JMARKETPLACE_TAX_COMMISSION') == 1) {
                        $entry_month = $entry_month + Tools::convertPriceFull(($commission['price_tax_incl'] * $commission['quantity']), $currency_from, $this->context->currency);
                        $spend_month = $spend_month + Tools::convertPriceFull($commission['total_commission_tax_incl'], $currency_from, $this->context->currency);
                    } else {
                        $entry_month = $entry_month + Tools::convertPriceFull(($commission['price_tax_excl'] * $commission['quantity']), $currency_from, $this->context->currency);
                        $spend_month = $spend_month + Tools::convertPriceFull($commission['total_commission_tax_excl'], $currency_from, $this->context->currency);
                    }
                }
            }
            
            $entry_month = Tools::ps_round($entry_month, 2);
            $spend_month = Tools::ps_round($spend_month, 2);
            $benefit_month = $entry_month - $spend_month;

            $labels .= '"'.$key.'",';

            $entry_string .= $entry_month.',';
            $spend_string .= $spend_month.',';
            $benefit_string .= $benefit_month.',';
            
            $commisions_chart[$key] = $value + $benefit_month;
        }
        
        $range_dates = Configuration::get('JMARKETPLACE_EARNINGS_FROM').' - '.Configuration::get('JMARKETPLACE_EARNINGS_TO');
  
        $best_sellers = array();
        $sellers = Seller::getSellers($this->context->shop->id);
        if (is_array($sellers) && count($sellers) > 0) {
            foreach ($sellers as $s) {
                $seller = new Seller($s['id_seller']);
                $seller_commissions = SellerCommissionHistory::getCommissionsBySellerAndState(2, $seller->id, $from, $to);
                $total_seller_commissions = 0;
                if (is_array($seller_commissions) && count($seller_commissions) > 0) {
                    foreach ($seller_commissions as $sc) {
                        $currency_from = new Currency($sc['id_currency']);
                        
                        if (Configuration::get('JMARKETPLACE_TAX_COMMISSION') == 1) {
                            $total_seller_commissions = $total_seller_commissions + Tools::convertPriceFull($sc['total_commission_tax_incl'], $currency_from, $this->context->currency);
                        } else {
                            $total_seller_commissions = $total_seller_commissions + Tools::convertPriceFull($sc['total_commission_tax_excl'], $currency_from, $this->context->currency);
                        }
                    }
                }
                
                $admin_commissions = SellerCommissionHistory::getTotalVariableCommissionsForAdmin(2, $this->context->shop->id, $seller->id, $from, $to);
                $total_admin_commissions = 0;
                if (is_array($admin_commissions) && count($admin_commissions) > 0) {
                    if (Configuration::get('JMARKETPLACE_TAX_COMMISSION') == 1) {
                        $labe_price = 'price_tax_incl';
                        $label_commission = 'total_commission_tax_incl';
                    } else {
                        $labe_price = 'price_tax_excl';
                        $label_commission = 'total_commission_tax_excl';
                    }
                    
                    foreach ($admin_commissions as $ac) {
                        $currency_from = new Currency($ac['id_currency']);
                        $total_admin_commissions = $total_admin_commissions + Tools::convertPriceFull((($ac[$labe_price] * $ac['quantity']) - $ac[$label_commission]), $currency_from, $this->context->currency);
                    }
                }
                
                $admin_fix_commissions = SellerCommissionHistory::getTotalFixCommissionsForAdmin(2, $this->context->shop->id, $seller->id, $from, $to);
                if (is_array($admin_fix_commissions) && count($admin_fix_commissions) > 0) {
                    if (Configuration::get('JMARKETPLACE_TAX_COMMISSION') == 1) {
                        $label_commission = 'total_commission_tax_incl';
                    } else {
                        $label_commission = 'total_commission_tax_excl';
                    }
                    
                    foreach ($admin_fix_commissions as $ac) {
                        $currency_from = new Currency($ac['id_currency']);
                        $total_admin_commissions = $total_admin_commissions + Tools::convertPriceFull((abs($ac[$label_commission]) * $ac['quantity']), $currency_from, $this->context->currency);
                    }
                }
                
                if (!$seller_commissions) {
                    $total_seller_commissions = 0;
                    $total_admin_commissions = 0;
                }
                
                $best_sellers[] = array(
                    'id_seller' => $seller->id,
                    'name' => $seller->name,
                    'shop' => $seller->shop,
                    'commissions' => $total_seller_commissions,
                    'admin_commissions' => $total_admin_commissions,
                );
            }
        }
        
        if (is_array($best_sellers) && count($best_sellers) > 0) {
            $aux = array();
            foreach ($best_sellers as $key => $row) {
                $aux[$key] = $row['admin_commissions'];
            }

            array_multisort($aux, SORT_DESC, $best_sellers);
            $best_sellers = array_slice($best_sellers, 0, 10);
        }
        
        //echo '<pre>'; print_r($best_sellers); die();
        //echo '<pre>'; print_r($commisions_chart); die();

        $this->context->smarty->assign(array(
            'name' => $this->module->name,
            'range_dates' => $range_dates,
            'labels' => $labels,
            'entry_string' => $entry_string,
            'spend_string' => $spend_string,
            'total_entries_for_admin' => $total_entries_for_admin,
            'total_spends_for_admin' => $total_spends_for_admin,
            'benefit_for_admin' => $benefit_for_admin,
            'benefit_string' => $benefit_string,
            'year' => $year,
            'from' => $from,
            'to' => $to,
            'best_sellers' => $best_sellers,
            'sign' => $this->context->currency->sign,
            'tax_commission' => Configuration::get('JMARKETPLACE_TAX_COMMISSION'),
            'url_form' => 'index.php?tab=AdminSellerDashboard&token='.Tools::getAdminToken('AdminSellerDashboard'.(int)Tab::getIdFromClassName('AdminSellerDashboard').(int)$this->context->employee->id),
        ));
        return $this->context->smarty->fetch($this->module->getLocalPath().'views/templates/admin/dashboard.tpl');
    }
    
    public function compareDates($date_start, $date_end)
    {
        $endTimestamp = strtotime($date_end);
        $beginTimestamp = strtotime($date_start);
        return ceil(($endTimestamp - $beginTimestamp) / 86400);
    }
}
