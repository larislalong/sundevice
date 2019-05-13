<?php
/*
* 2007-2016 PrestaShop
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_'))
    exit;


class Savemypurchases extends Module
{

    public function __construct()
    {
        $this->name = 'savemypurchases';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Cleandev';
        $this->need_instance = 0;
        $this->context = Context::getContext();

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName =  $this->l('Save my purchases');
        $this->description = $this->l('save all your purchases and order them later');
        $this->ps_versions_compilancy = array('min'=>'1.6', 'max'=>'1.7');

    }

    public function install(){
        return  parent::install() &&
                $this->registerHook('displayBanner') &&
                $this->registerHook('displayHeader') &&
                $this->registerHook('displayShoppingCartFooter') &&
                $this->registerHook('displayCustomerAccount') &&
                $this->installDb();
    }

    public function installDb()
    {
        return Db::getInstance()->execute('
			CREATE TABLE `'._DB_PREFIX_.'os_cart` (
			  `id_cart` int(10) NOT NULL,
			  `id_customer` int(10) unsigned NOT NULL,
			  UNIQUE KEY `id_cart_customer` (`id_cart`,`id_customer`) USING BTREE
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;'
		);
    }

    public function uninstall()
    {
        return parent::uninstall() && $this->unistallDb();
    }

    public function unistallDb(){
        return Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'os_cart`');
    }

    public function hookDisplayHeader(){
        if(isset($this->context->cookie->vx_success) && ((int)$this->context->cookie->vx_success == 1)){
            $this->context->cookie->vx_success = 0;
            return $this->display(__FILE__, 'vxsuccess.tpl');
        }

        //on verifie si il y'a une erreur dans les cookies
        if(isset($this->context->cookie->vx_error) && !empty($this->context->cookie->vx_error)){
            $this->errors[] = $this->context->cookie->vx_error;

            $this->context->cookie->vx_error = '';

            $this->context->smarty->assign(array(
                'errors' => $this->errors
            ));
            return $this->display(__FILE__, 'vxerror.tpl');
        }
    }

    public function hookDisplayShoppingCartFooter(){

        if(Tools::getValue('vx_save_my_purchases')){
            $this->savePurchases($this->context->customer->id);
        }

        $this->context->controller->addCSS($this->_path.'views/css/mod_purchases.css');
        $this->context->smarty->assign('vx_link', _PS_BASE_URL_.$_SERVER['REQUEST_URI']);

        return $this->display(__FILE__, 'purchases.tpl');


    }

    public function hookDisplayCustomerAccount(){
        $this->context->smarty->assign('purchaseLink', $this->context->link->getModulelink('savemypurchases','purchases', []));
        return $this->display(__FILE__, 'btnpurchase.tpl');
    }

    private function savePurchases($id_customer)
	{

        if(is_null($id_customer)){
            $this->redirAuth();
        }
		
		$data = array(
			'id_customer' => $id_customer,
			'id_cart' => $this->context->cart->id,
		);

		
		if(Db::getInstance()->insert('os_cart', $data, false, true, Db::REPLACE)){
			//on supprime ce panier sauvegardé
			$this->context->cart = new Cart();
            $this->context->cookie->id_cart = (int)$this->context->cart->id;
			CartRule::autoAddToCart($this->context);
            $this->context->cookie->write();

			//aller à la catégorie par défaut
			Tools::redirect($this->context->link->getModulelink('savemypurchases','purchases', []));
		}
		
        
    }

    private function redirAuth(){
        $this->context->cookie->vx_error = $this->l('you must log in before continuing');
        Tools::redirect($this->context->link->getPageLink('authentication', true));
    }

    private function redirCurrent(){
        $root = explode("?", _PS_BASE_URL_.$_SERVER['REQUEST_URI']);
        Tools::redirect($root[0]);
    }
}
