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

class AdminSellerEmailsController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'seller_email';
        $this->className = 'SellerEmail';
        $this->lang = true;
        
        $this->context = Context::getContext();

        parent::__construct();
        
        $this->fields_list = array(
            'reference' => array(
                'title' => $this->l('Email reference'),
                'align' => 'center',
                'class' => 'fixed-width-xs',
                'havingFilter' => true,
            ),
            'subject' => array(
                'title' => $this->l('Subject'),
                'type' => 'text',
            )
        );
    }
    
    public function renderList()
    {
        $this->addRowAction('edit');
        return parent::renderList().$this->displayFormResetEmails();
    }
    
    public function displayFormResetEmails()
    {
        $html = '<div class="panel">';
        $html .= '<div class="panel-heading">'.$this->l('Reset emails').'</div>';
        $html .= '<p>'.$this->l('It is possible reset the emails pressing this button.').'</p>';
        $html .= '<div class="panel-footer">
                    <a class="btn btn-default" href="index.php?controller=AdminSellerEmails&token='.$this->token.'&resetemails=1">
                        <i class="process-icon-update"></i> '.$this->l('Reset').'
                    </a>
                 </div>';
        
        $html .= '</div>';
        
        return $html;
    }
    
    public function renderForm()
    {
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Edit Email'),
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Subject'),
                    'name' => 'subject',
                    'lang' => true,
                    'col' => 9,
                    'required' => true,
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Content'),
                    'name' => 'content',
                    'required' => true,
                    'lang' => true,
                    'autoload_rte' => true,
                    'cols' => 40,
                    'rows' => 7,
                ),
            )
        );

        $this->fields_form['submit'] = array(
            'title' => $this->l('Save'),
        );

        return $this->displayInfo().parent::renderForm().$this->displayVars();
    }
    
    public function displayInfo()
    {
        $id_seller_email = Tools::getValue('id_seller_email');
        $seller_email = new SellerEmail($id_seller_email, $this->context->language->id);
        $html = '<div class="panel">';
        $html .= '<div class="panel-heading">'.$this->l('Template email information').'</div>';
        $html .= '<p>'.$seller_email->description.'</p>';
        $html .= '</div>';
        
        return $html;
    }
    
    public function displayVars()
    {
        $id_seller_email = Tools::getValue('id_seller_email');
        $seller_email = new SellerEmail($id_seller_email);
        $html = '<div class="panel">';
        $html .= '<div class="panel-heading">'.$this->l('Template vars').'</div>';
        $html .= '<ul>';
        switch ($seller_email->reference) {
            case 'welcome-seller':
                $html .= '<li>{seller_name} => '.$this->l('Display seller name in email.').'</li>';
                $html .= '<li>{seller_shop} => '.$this->l('Display seller shop in email.').'</li>';
                $html .= '<li>{shop_name} => '.$this->l('Display marketplace name in email.').'</li>';
                break;
            case 'new-seller':
                $html .= '<li>{seller_name} => '.$this->l('Display seller name in email.').'</li>';
                $html .= '<li>{seller_shop} => '.$this->l('Display seller shop in email.').'</li>';
                $html .= '<li>{shop_name} => '.$this->l('Display marketplace name in email.').'</li>';
                break;
            case 'edit-seller':
                $html .= '<li>{seller_name} => '.$this->l('Display seller name in email.').'</li>';
                $html .= '<li>{seller_shop} => '.$this->l('Display seller shop in email.').'</li>';
                $html .= '<li>{shop_name} => '.$this->l('Display marketplace name in email.').'</li>';
                break;
            case 'seller-activated':
                $html .= '<li>{seller_name} => '.$this->l('Display seller name in email.').'</li>';
                $html .= '<li>{seller_shop} => '.$this->l('Display seller shop in email.').'</li>';
                $html .= '<li>{shop_name} => '.$this->l('Display marketplace name in email.').'</li>';
                break;
            case 'seller-desactivated':
                $html .= '<li>{seller_name} => '.$this->l('Display seller name in email.').'</li>';
                $html .= '<li>{seller_shop} => '.$this->l('Display seller shop in email.').'</li>';
                $html .= '<li>{shop_name} => '.$this->l('Display marketplace name in email.').'</li>';
                $html .= '<li>{reasons} => '.$this->l('Display reasons in email.').'</li>';
                break;
            case 'new-product':
                $html .= '<li>{seller_name} => '.$this->l('Display seller name in email.').'</li>';
                $html .= '<li>{product_name} => '.$this->l('Display product name in email.').'</li>';
                $html .= '<li>{seller_shop} => '.$this->l('Display seller shop in email.').'</li>';
                $html .= '<li>{shop_name} => '.$this->l('Display marketplace name in email.').'</li>';
                break;
            case 'product-activated':
                $html .= '<li>{seller_name} => '.$this->l('Display seller name in email.').'</li>';
                $html .= '<li>{seller_shop} => '.$this->l('Display seller shop in email.').'</li>';
                $html .= '<li>{product_name} => '.$this->l('Display product name in email.').'</li>';
                $html .= '<li>{shop_name} => '.$this->l('Display marketplace name in email.').'</li>';
                break;
            case 'product-desactivated':
                $html .= '<li>{seller_name} => '.$this->l('Display seller name in email.').'</li>';
                $html .= '<li>{seller_shop} => '.$this->l('Display seller shop in email.').'</li>';
                $html .= '<li>{product_name} => '.$this->l('Display product name in email.').'</li>';
                $html .= '<li>{shop_name} => '.$this->l('Display marketplace name in email.').'</li>';
                $html .= '<li>{reasons} => '.$this->l('Display reasons in email.').'</li>';
                break;
            case 'edit-product':
                $html .= '<li>{seller_name} => '.$this->l('Display seller name in email.').'</li>';
                $html .= '<li>{seller_shop} => '.$this->l('Display seller shop in email.').'</li>';
                $html .= '<li>{product_name} => '.$this->l('Display product name in email.').'</li>';
                $html .= '<li>{shop_name} => '.$this->l('Display marketplace name in email.').'</li>';
                break;
            case 'new-order':
                $html .= '<li>{seller_name} => '.$this->l('Display seller name in email.').'</li>';
                $html .= '<li>{seller_shop} => '.$this->l('Display seller shop in email.').'</li>';
                $html .= '<li>{product_name} => '.$this->l('Display product name in email.').'</li>';
                $html .= '<li>{order_reference} => '.$this->l('Display order reference in email.').'</li>';
                $html .= '<li>{shop_name} => '.$this->l('Display marketplace name in email.').'</li>';
                break;
            case 'new-incidence':
                $html .= '<li>{order_reference} => '.$this->l('Display order reference in email.').'</li>';
                $html .= '<li>{incidence_reference} => '.$this->l('Display message reference in email.').'</li>';
                $html .= '<li>{description} => '.$this->l('Display message in email.').'</li>';
                $html .= '<li>{shop_name} => '.$this->l('Display marketplace name in email.').'</li>';
                break;
            case 'new-message':
                $html .= '<li>{incidence_reference} => '.$this->l('Display message reference in email.').'</li>';
                $html .= '<li>{product_name} => '.$this->l('Display product name in email.').'</li>';
                $html .= '<li>{description} => '.$this->l('Display message in email.').'</li>';
                $html .= '<li>{shop_name} => '.$this->l('Display marketplace name in email.').'</li>';
                break;
            case 'new-response-seller':
                $html .= '<li>{incidence_reference} => '.$this->l('Display message reference in email.').'</li>';
                $html .= '<li>{description} => '.$this->l('Display message in email.').'</li>';
                $html .= '<li>{shop_name} => '.$this->l('Display marketplace name in email.').'</li>';
                break;
            case 'new-response-customer':
                $html .= '<li>{incidence_reference} => '.$this->l('Display message reference in email.').'</li>';
                $html .= '<li>{description} => '.$this->l('Display message in email.').'</li>';
                $html .= '<li>{shop_name} => '.$this->l('Display marketplace name in email.').'</li>';
                break;
            case 'new-comment-admin':
                $html .= '<li>{seller_name} => '.$this->l('Display seller name in email.').'</li>';
                $html .= '<li>{grade} => '.$this->l('Display grade in email.').'</li>';
                $html .= '<li>{comment} => '.$this->l('Display comment in email.').'</li>';
                $html .= '<li>{shop_name} => '.$this->l('Display marketplace name in email.').'</li>';
                break;
            case 'new-comment-seller':
                $html .= '<li>{grade} => '.$this->l('Display grade in email.').'</li>';
                $html .= '<li>{comment} => '.$this->l('Display comment in email.').'</li>';
                $html .= '<li>{shop_name} => '.$this->l('Display marketplace name in email.').'</li>';
                break;
            case 'seller-transfer-accepted':
                $html .= '<li>{amount} => '.$this->l('Display transfer amount in email.').'</li>';
                $html .= '<li>{payment} => '.$this->l('Display method of payment selected by seller: bankwire or paypal in email.').'</li>';
                $html .= '<li>{shop_name} => '.$this->l('Display marketplace name in email.').'</li>';
                break;
            case 'seller-payment-request':
                $html .= '<li>{seller_name} => '.$this->l('Display seller name in email.').'</li>';
                $html .= '<li>{amount} => '.$this->l('Display transfer amount in email.').'</li>';
                $html .= '<li>{payment} => '.$this->l('Display method of payment selected by seller: bankwire or paypal in email.').'</li>';
                $html .= '<li>{shop_name} => '.$this->l('Display marketplace name in email.').'</li>';
                break;
            case 'seller-order-changed':
                $html .= '<li>{seller_name} => '.$this->l('Display seller name in email.').'</li>';
                $html .= '<li>{seller_shop} => '.$this->l('Display seller shop in email.').'</li>';
                $html .= '<li>{order_reference} => '.$this->l('Display order reference in email.').'</li>';
                $html .= '<li>{shop_name} => '.$this->l('Display marketplace name in email.').'</li>';
                break;
        }
        $html .= '</ul>';
        $html .= '</div>';
        
        return $html;
    }
    
    public function postProcess()
    {
        if (Tools::isSubmit('resetemails')) {
            Db::getInstance()->Execute('TRUNCATE TABLE `'._DB_PREFIX_.'seller_email`');
            Db::getInstance()->Execute('TRUNCATE TABLE `'._DB_PREFIX_.'seller_email_lang`');
            $this->module->createEmails();
        } else {
            parent::postProcess();
        }
    }
}
