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

class JmarketplaceCarriersModuleFrontController extends ModuleFrontController
{
    public $ssl = true;
    
    public function setMedia()
    {
        parent::setMedia();
        $this->context->controller->addCSS(_MODULE_DIR_.$this->module->name.'/views/css/carriers.css');
    }
    
    public function postProcess()
    {
        $id_seller = Seller::getSellerByCustomer($this->context->cookie->id_customer);
        
        if (Tools::getValue('desactivate')) {
            $id_carrier = (int)Tools::getValue('desactivate');
            $carriers = SellerTransport::getCarriers($this->context->language->id, false, $id_seller);
            
            if ($carriers) {
                foreach ($carriers as $c) {
                    if ($c['id_carrier'] == $id_carrier) {
                        $is_seller_carrier = true;
                    }
                }
            }
            
            if ($is_seller_carrier) {
                $carrier = new Carrier($id_carrier);
                $carrier->active = 0;
                $carrier->update();
                Tools::redirect($this->context->link->getModuleLink('jmarketplace', 'carriers'));
            } else {
                $this->errors[] = $this->module->l('You do not have permission to desactivate this carrier.', 'carriers');
                $this->context->smarty->assign(array(
                    'errors' => $this->errors,
                ));
            }
        }
        
        if (Tools::getValue('activate')) {
            $id_carrier = (int)Tools::getValue('activate');
            $carriers = SellerTransport::getCarriers($this->context->language->id, false, $id_seller);
            
            if ($carriers) {
                foreach ($carriers as $c) {
                    if ($c['id_carrier'] == $id_carrier) {
                        $is_seller_carrier = true;
                    }
                }
            }
            
            if ($is_seller_carrier) {
                $carrier = new Carrier($id_carrier);
                $carrier->active = 1;
                $carrier->update();
                Tools::redirect($this->context->link->getModuleLink('jmarketplace', 'carriers', array(), true));
            } else {
                $this->errors[] = $this->module->l('You do not have permission to desactivate this carrier.', 'carriers');
                $this->context->smarty->assign(array(
                    'errors' => $this->errors,
                ));
            }
        }
        
        if (Tools::getValue('delete')) {
            $is_seller_carrier = false;
            $id_carrier = (int)Tools::getValue('delete');
            $carriers = SellerTransport::getCarriers($this->context->language->id, false, $id_seller);
            
            if ($carriers) {
                foreach ($carriers as $c) {
                    if ($c['id_carrier'] == $id_carrier) {
                        $is_seller_carrier = true;
                    }
                }
            }
            
            if ($is_seller_carrier) {
                $carrier = new Carrier($id_carrier);
                Db::getInstance()->Execute(
                    'DELETE FROM `'._DB_PREFIX_.'seller_carrier` 
                    WHERE id_carrier = '.(int)$carrier->id
                );
                $carrier->delete();
                Tools::redirect($this->context->link->getModuleLink('jmarketplace', 'carriers'));
            } else {
                $this->errors[] = $this->module->l('You do not have permission to remove this carrier.', 'carriers');
                $this->context->smarty->assign(array(
                    'errors' => $this->errors,
                ));
            }
        }
    }

    public function initContent()
    {
        parent::initContent();
        
        $id_lang = $this->context->language->id;
        
        if (!$this->context->cookie->id_customer) {
            Tools::redirect($this->context->link->getPageLink('my-account', true));
        }
        
        if (Configuration::get('JMARKETPLACE_SHOW_MANAGE_CARRIER') == 0) {
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
        
        $params = array('id_seller' => $seller->id, 'link_rewrite' => $seller->link_rewrite);
        $url_seller_profile = $this->module->getJmarketplaceLink('jmarketplace_seller_rule', $params);
        
        $carriers = SellerTransport::getCarriers($id_lang, false, $id_seller);
        foreach ($carriers as $key => $value) {
            $carrier_logo = _PS_IMG_DIR_.'s/'.$value['id_carrier'].'.jpg';
            if (file_exists($carrier_logo)) {
                $carriers[$key]['logo'] = _PS_BASE_URL_ . __PS_BASE_URI__.'img/s/'.$value['id_carrier'].'.jpg';
            }
            
            $params_carrier_edit = array('id_carrier' => $value['id_carrier']);
            $params_carrier_delete = array('delete' => $value['id_carrier']);
            $params_carrier_activate = array('activate' => $value['id_carrier']);
            $params_carrier_desactivate = array('desactivate' => $value['id_carrier']);
            $edit_carrier_link = $this->context->link->getModuleLink('jmarketplace', 'editcarrier', $params_carrier_edit, true);
            $delete_carrier_link = $this->context->link->getModuleLink('jmarketplace', 'carriers', $params_carrier_delete, true);
            $activate_carrier_link = $this->context->link->getModuleLink('jmarketplace', 'carriers', $params_carrier_activate, true);
            $desactivate_carrier_link = $this->context->link->getModuleLink('jmarketplace', 'carriers', $params_carrier_desactivate, true);
            $carriers[$key]['edit_carrier_link'] = $edit_carrier_link;
            $carriers[$key]['delete_carrier_link'] = $delete_carrier_link;
            $carriers[$key]['activate_carrier_link'] = $activate_carrier_link;
            $carriers[$key]['desactivate_carrier_link'] = $desactivate_carrier_link;
        }
        
        $this->context->smarty->assign(array(
            'seller_link' => $url_seller_profile,
            'show_import_product' => Configuration::get('JMARKETPLACE_SELLER_IMPORT_PROD'),
            'show_orders' => Configuration::get('JMARKETPLACE_SHOW_ORDERS'),
            'show_edit_seller_account' => Configuration::get('JMARKETPLACE_SHOW_EDIT_ACCOUNT'),
            'show_contact' => Configuration::get('JMARKETPLACE_SHOW_CONTACT'),
            'show_manage_orders' => Configuration::get('JMARKETPLACE_SHOW_MANAGE_ORDERS'),
            'show_manage_carriers' => Configuration::get('JMARKETPLACE_SHOW_MANAGE_CARRIER'),
            'show_dashboard' => Configuration::get('JMARKETPLACE_SHOW_DASHBOARD'),
            'show_seller_invoice' => Configuration::get('JMARKETPLACE_SHOW_SELLER_INVOICE'),
            'show_menu_top' => Configuration::get('JMARKETPLACE_MENU_TOP'),
            'show_menu_options' => Configuration::get('JMARKETPLACE_MENU_OPTIONS'),
            'carriers' => $carriers,
        ));
        
        $this->setTemplate('carriers.tpl');
    }
}
