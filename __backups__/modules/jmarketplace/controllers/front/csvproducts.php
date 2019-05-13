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

class JmarketplaceCsvproductsModuleFrontController extends ModuleFrontController
{
    public $ssl = true;

    public function initContent()
    {
        parent::initContent();
        
        if (!$this->context->cookie->id_customer) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
        }
        
        if (Configuration::get('JMARKETPLACE_SELLER_IMPORT_PROD') == 0) {
            Tools::redirect($this->context->link->getModuleLink('jmarketplace', 'selleraccount', array(), true));
        }
        
        $is_seller = Seller::isSeller($this->context->cookie->id_customer, $this->context->shop->id);
        if (!$is_seller) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
        }
        
        $id_seller = Seller::getSellerByCustomer($this->context->cookie->id_customer);
        $seller = new Seller($id_seller);
        
        if ($seller->active == 0) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
        }
        
        $languages = Language::getLanguages();
        
        $csv_seller_product = new CSVSellerProduct();
        
        $param = array('id_seller' => $seller->id, 'link_rewrite' => $seller->link_rewrite);
        $url_seller_profile = $this->module->getJmarketplaceLink('jmarketplace_seller_rule', $param);
        
        $this->context->smarty->assign(array(
            'show_orders' => Configuration::get('JMARKETPLACE_SHOW_ORDERS'),
            'show_manage_orders' => Configuration::get('JMARKETPLACE_SHOW_MANAGE_ORDERS'),
            'show_manage_carriers' => Configuration::get('JMARKETPLACE_SHOW_MANAGE_CARRIER'),
            'show_edit_seller_account' => Configuration::get('JMARKETPLACE_SHOW_EDIT_ACCOUNT'),
            'show_edit_product' => Configuration::get('JMARKETPLACE_SHOW_EDIT_PRODUCT'),
            'show_delete_product' => Configuration::get('JMARKETPLACE_SHOW_DELETE_PRODUCT'),
            'show_active_product' => Configuration::get('JMARKETPLACE_SHOW_ACTIVE_PRODUCT'),
            'show_import_product' => Configuration::get('JMARKETPLACE_SELLER_IMPORT_PROD'),
            'show_contact' => Configuration::get('JMARKETPLACE_SHOW_CONTACT'),
            'show_dashboard' => Configuration::get('JMARKETPLACE_SHOW_DASHBOARD'),
            'show_seller_invoice' => Configuration::get('JMARKETPLACE_SHOW_SELLER_INVOICE'),
            'show_menu_top' => Configuration::get('JMARKETPLACE_MENU_TOP'),
            'show_menu_options' => Configuration::get('JMARKETPLACE_MENU_OPTIONS'),
            'seller_link' => $url_seller_profile,
            'languages' => $languages,
            'id_lang' => $this->context->language->id,
            'available_fields' => $csv_seller_product->getFields(),
            'url_example' => $this->context->link->getModuleLink('jmarketplace', 'csvproducts', array('example' => 1), true),
            'submitNextStep' => 0,
        ));
        
        if (Tools::isSubmit('submitExport')) {
            $num_products = $csv_seller_product->export($id_seller);
            $this->context->smarty->assign(array(
                'submitExport' => 1,
                'num_products' => $num_products,
                'file' => 'products_'.$id_seller.'.csv',
                'errors' => $this->errors,
            ));
        }
        
        if (Tools::isSubmit('submitNextStep')) {
            if ($_FILES['file']["type"] == "text/comma-separated-values" ||
                $_FILES['file']["type"] == "text/csv" ||
                $_FILES['file']["type"] == "application/force-download" ||
                $_FILES['file']["type"] == "application/vnd.ms-excel") {
                $id_lang = Tools::getValue('id_lang');
                $truncate = Tools::getValue('truncate');
                $match_ref = Tools::getValue('match_ref');
                $csv_seller_product = new CSVSellerProduct();
                $first_rows = $csv_seller_product->getFirstLine($_FILES['file']);
                $header = $csv_seller_product->getHeaderLine($_FILES['file']);
                
                $this->context->smarty->assign(array(
                    'id_lang' => $id_lang,
                    'truncate' => $truncate,
                    'match_ref' => $match_ref,
                    'filename' => $_FILES['file']['name'],
                    'first_rows' => $first_rows,
                    'header' => $header,
                    'submitNextStep' => 1,
                ));
            } else {
                $this->errors[] = $this->module->l('File is incorrect.', 'csvproducts');
                $this->context->smarty->assign(array(
                    'errors' => $this->errors,
                ));
            }
        }
        
        if (Tools::isSubmit('submitImport')) {
            $id_lang = Tools::getValue('id_lang');
            $truncate = Tools::getValue('truncate');
            $match_ref = Tools::getValue('match_ref');
            $filename = Tools::getValue('filename');
            $type_value = Tools::getValue('type_value');
            $id_seller = Seller::getSellerByCustomer($this->context->cookie->id_customer);
            $seller = new Seller($id_seller);
            
            if ($truncate == 1) {
                //ignorar columna id_product
                foreach ($type_value as $key => $value) {
                    if ($value == 'id_product') {
                        $type_value[$key] = false;
                    }
                }
                
                $seller->deleteSellerProducts();
            }
            
            $result = $csv_seller_product->import($id_seller, $filename, $id_lang, $type_value, $match_ref);
            if (is_array($result)) {
                $this->context->smarty->assign(array(
                    'id_seller' => $id_seller,
                    'submitImport' => 1,
                    'added' => $result['added'],
                    'updated' => $result['updated'],
                    'invalid' => $result['invalid'],
                ));
            } elseif ($result == 'error_name') {
                $this->errors = $this->module->l('Missing name column.', 'csvproducts');
                $this->context->smarty->assign(array(
                    'errors' => $this->errors,
                ));
            } elseif ($result == 'error_reference') {
                $this->errors = $this->module->l('Missing reference column.', 'csvproducts');
                $this->context->smarty->assign(array(
                    'errors' => $this->errors,
                ));
            } else {
                $this->errors = $this->module->l('File type is incorrect.', 'csvproducts');
                $this->context->smarty->assign(array(
                    'errors' => $this->errors,
                ));
            }
        }
        
        if (Tools::isSubmit('example')) {
            $csv_seller_product->generateExample();
        }
        
        $this->setTemplate('csvproducts.tpl');
    }
}
