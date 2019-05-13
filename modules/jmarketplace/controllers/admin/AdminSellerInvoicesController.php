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

class AdminSellerInvoicesController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'seller_transfer_invoice';
        $this->className = 'SellerTransferInvoice';
        $this->lang = false;
        $this->context = Context::getContext();

        parent::__construct();
        
        $this->_select = 'a.id_seller_transfer_invoice, s.name seller_name, a.total, a.payment, a.validate, a.date_add, a.date_upd';
        $this->_join = 'LEFT JOIN '._DB_PREFIX_.'seller s ON (s.id_seller = a.id_seller)';

        $this->fields_list = array(
            'seller_name' => array(
                'title' => $this->l('Seller name'),
                'havingFilter' => true,
            ),
            'total' => array(
                'title' => $this->l('Total'),
                'havingFilter' => true,
                'type' => 'price'
            ),
            'payment' => array(
                'title' => $this->l('Method of payment'),
                'havingFilter' => true,
            ),
            'validate' => array(
                'title' => $this->l('Validate payment'),
                'align' => 'center',
                'active' => 'status',
                'type' => 'bool',
                'orderby' => false,
                'class' => 'fixed-width-sm'
            ),
            'date_add' => array(
                'title' => $this->l('Date of request'),
                'type' => 'datetime',
            ),
            'date_upd' => array(
                'title' => $this->l('Date of payment'),
                'type' => 'datetime',
            ),
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
        $this->addRowAction('delete');
        return parent::renderList();
    }

    public function postProcess()
    {
        if ($this->display == 'view') {
            $id_seller_transfer_invoice = (int)Tools::getValue('id_seller_transfer_invoice');
            $seller_transfer_invoice = new SellerTransferInvoice($id_seller_transfer_invoice);
            $seller = new Seller($seller_transfer_invoice->id_seller);
            $commissions = SellerTransferCommission::getCommissionsByTransferInvoice($id_seller_transfer_invoice, $this->context->language->id);
            
            if (Configuration::get('PS_SSL_ENABLED') == 1) {
                $url_shop = Tools::getShopDomainSsl(true).__PS_BASE_URI__;
            } else {
                $url_shop = Tools::getShopDomain(true).__PS_BASE_URI__;
            }
            
            $pdf = $url_shop.'modules/jmarketplace/invoices/'.$id_seller_transfer_invoice.'.pdf';
            $pdf_image = $url_shop.'modules/jmarketplace/views/img/pdf-invoice.png';
            
            $total_paid = Tools::displayPrice($seller_transfer_invoice->total, (int)$seller_transfer_invoice->id_currency);

            $this->context->smarty->assign(array(
                'seller' => $seller,
                'total_paid' => $total_paid,
                'seller_transfer_invoice' => $seller_transfer_invoice,
                'commissions' => $commissions,
                'show_tax_commission' => Configuration::get('JMARKETPLACE_TAX_COMMISSION'),
                'pdf' => $pdf,
                'pdf_image' => $pdf_image,
                'url_post' => self::$currentIndex.'&id_seller_transfer_invoice='.$seller_transfer_invoice->id.'&statusseller_transfer_invoice&token='.$this->token,
                'token' => $this->token,
            ));
        }
        
        if (Tools::isSubmit('statusseller_transfer_invoice') || Tools::isSubmit('submitAcceptedPayment')) {
            $id_seller_transfer_invoice = (int)Tools::getValue('id_seller_transfer_invoice');
            $seller_transfer_invoice = new SellerTransferInvoice($id_seller_transfer_invoice);
            
            $seller_transfer_invoice->validate = 1;
            $seller_transfer_invoice->update();
            
            //Db::getInstance()->Execute('UPDATE `'._DB_PREFIX_.'seller_transfer_invoice` SET `validate` = 1 WHERE id_seller_transfer_invoice = '.$id_seller_transfer_invoice);
            
            $commissions = $seller_transfer_invoice->getTransferCommissions();
            
            foreach ($commissions as $commission) {
                Db::getInstance()->Execute('UPDATE `'._DB_PREFIX_.'seller_commission_history` SET `id_seller_commission_history_state` = 2 WHERE id_seller_commission_history = '.$commission['id_seller_commission_history']);
            }
            
            $seller = new Seller($seller_transfer_invoice->id_seller);
            
            $to = $seller->email;
            $to_name = $seller->name;
            $from = Configuration::get('PS_SHOP_EMAIL');
            $from_name = Configuration::get('PS_SHOP_NAME');

            $template = 'base';
            $reference = 'seller-transfer-accepted';
            $id_seller_email = SellerEmail::getIdByReference($reference);

            if ($id_seller_email) {
                $seller_email = new SellerEmail($id_seller_email, $seller->id_lang);
                $vars = array("{shop_name}", "{amount}", "{payment}");
                $values = array(Configuration::get('PS_SHOP_NAME'), Tools::displayPrice($seller_transfer_invoice->total, $this->context->currency->id), $seller_transfer_invoice->payment);
                $subject_var = $seller_email->subject;
                $subject_value = str_replace($vars, $values, $subject_var);
                $content_var = $seller_email->content;
                $content_value = str_replace($vars, $values, $content_var);

                $template_vars = array(
                    '{content}' => $content_value,
                    '{shop_name}' => Configuration::get('PS_SHOP_NAME')
                );

                $iso = Language::getIsoById($seller->id_lang);

                if (file_exists(dirname(__FILE__).'/../../mails/'.$iso.'/'.$template.'.txt') && file_exists(dirname(__FILE__).'/../../mails/'.$iso.'/'.$template.'.html')) {
                    Mail::Send(
                        $seller->id_lang,
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
        } elseif (Tools::isSubmit('deleteseller_transfer_invoice')) {
            $id_seller_transfer_invoice = (int)Tools::getValue('id_seller_transfer_invoice');
            $seller_transfer_commissions = SellerTransferCommission::getTransferCommissionsByTransferInvoice($id_seller_transfer_invoice);
            if (is_array($seller_transfer_commissions) && count($seller_transfer_commissions) > 0) {
                foreach ($seller_transfer_commissions as $stc) {
                    $seller_transfer_commission = new SellerTransferCommission($stc['id_seller_transfer_commission']);
                    $seller_transfer_commission->delete();
                }
            }
            
            $seller_transfer_invoice = new SellerTransferInvoice($id_seller_transfer_invoice);
            $seller_transfer_invoice->delete();
        } elseif (Tools::isSubmit('submitBulkdeleteseller_transfer_invoice')) {
            //delete products selected
            $seller_transfer_invoice_selected = Tools::getValue('seller_transfer_invoiceBox');
            foreach ($seller_transfer_invoice_selected as $id_seller_transfer_invoice) {
                $seller_transfer_commissions = SellerTransferCommission::getTransferCommissionsByTransferInvoice($id_seller_transfer_invoice);
                if (is_array($seller_transfer_commissions) && count($seller_transfer_commissions) > 0) {
                    foreach ($seller_transfer_commissions as $stc) {
                        $seller_transfer_commission = new SellerTransferCommission($stc['id_seller_transfer_commission']);
                        $seller_transfer_commission->delete();
                    }
                }
                
                $seller_transfer_invoice = new SellerTransferInvoice($id_seller_transfer_invoice);
                $seller_transfer_invoice->delete();
            }
        } else {
            parent::postProcess();
        }
        
        if (Tools::getValue('submitFilterseller_transfer_invoice')) {
            if (Tools::getValue('seller_transfer_invoiceFilter_seller_name') != '') {
                $this->_where .= ' AND s.name LIKE "%'.pSQL(Tools::getValue('seller_transfer_invoiceFilter_seller_name')).'%"';
            }

            if (Tools::getValue('seller_transfer_invoiceFilter_total') != '') {
                $this->_where .= ' AND a.total = '.(float)Tools::getValue('seller_transfer_invoiceFilter_total');
            }
            
            if (Tools::getValue('seller_transfer_invoiceFilter_payment') != '') {
                $this->_where .= ' AND a.payment LIKE "%'.pSQL(Tools::getValue('seller_transfer_invoiceFilter_payment')).'%"';
            }
            
            if (Tools::getValue('seller_transfer_invoiceFilter_validate') != '') {
                $this->_where .= ' AND a.validate = '.(int)Tools::getValue('seller_transfer_invoiceFilter_validate');
            }
            
            $arrayDateAdd = Tools::getValue('seller_transfer_invoiceFilter_date_add');
            if ($arrayDateAdd[0] != '' && $arrayDateAdd[1] != '') {
                $this->_where .= ' AND a.date_add BETWEEN "'.pSQL($arrayDateAdd[0]).' 00:00:00" AND "'.pSQL($arrayDateAdd[1]).' 23:59:59"';
            }
            
            $arrayDateUpd = Tools::getValue('seller_transfer_invoiceFilter_date_upd');
            if ($arrayDateUpd[0] != '' && $arrayDateUpd[1] != '') {
                $this->_where .= ' AND a.date_upd BETWEEN "'.pSQL($arrayDateUpd[0]).' 00:00:00" AND "'.pSQL($arrayDateUpd[1]).' 23:59:59"';
            }
        }
        
        $this->_orderBy = 'a.date_upd';
        $this->_orderWay = 'DESC';
        
        if (Tools::getValue('seller_transfer_invoiceOrderway')) {
            $this->_orderBy = pSQL(Tools::getValue('seller_transfer_invoiceOrderby'));
            $this->_orderWay = pSQL(Tools::getValue('seller_transfer_invoiceOrderway'));
        }
    }
}
