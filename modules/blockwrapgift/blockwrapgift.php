<?php
/**
 * 2015-2017 Crystals Services
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
 * needs please refer to http://www.crystals-services.com/ for more information.
 *
 * @author Crystals Services Sarl <contact@crystals-services.com>
 * @copyright 2015-2017 Crystals Services Sarl
 * @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 *          International Registered Trademark & Property of Crystals Services Sarl
 */

if (! defined('_PS_VERSION_')) {
    exit();
}

include_once _PS_MODULE_DIR_ . 'blockwrapgift/controllers/admin/BwgAdminController.php';
include_once _PS_MODULE_DIR_ . 'blockwrapgift/controllers/front/BwgFrontController.php';
include_once _PS_MODULE_DIR_ . 'blockwrapgift/classes/BwgCartProduct.php';

class Blockwrapgift extends Module
{
    protected $config_form = false;

    public $adminController;

    public function __construct()
    {
        $this->name = 'blockwrapgift';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Crystals Services Sarl';
        $this->need_instance = 0;
        $this->bootstrap = true;
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);  
		$this->module_key = '9cbb840a4aa3d10ca775233ea423edcf';
        
        parent::__construct();
        
        $this->displayName = $this->l('blockwrapgift');
        $this->description = $this->l('blockwrapgift.');
		
        $this->adminController = new BwgAdminController($this, $this->context, $this->local_path, $this->_path);
		$this->frontController = new BwgFrontController($this, $this->context, $this->local_path, $this->_path, __FILE__);
		$this->wrapGiftImageFolder = 'views/img/';
		
		$isUpdatePosition = Tools::getValue('ajax') && (Tools::getValue('action')=='updatePositions') &&
		(Tools::getValue('id')=='bwg') && is_array(Tools::getValue('bwg_wrap_gift'));
		if($isUpdatePosition){
			$this->adminController->ajaxProcessUpdatePositions();
		}
    }

    public function install()
    {
		if (! parent::install() ||
                ! $this->registerHook('header') ||
                ! $this->registerHook('backOfficeHeader')||
				!$this->registerHook('displayPackegingGift')||
				!$this->registerHook('displayPackegingText')||
				!$this->registerHook('actionAfterDeleteProductInCart')||
				!$this->registerHook('actionAfterChangeProductInCart')) {
            return false;
        }
        require_once(dirname(__FILE__) . '/sql/install.php');
		Configuration::updateValue('PS_GIFT_WRAPPING', 1);
        return true;
    }

    public function uninstall()
    {
        if (! parent::uninstall()) {
            return false;
        }
        require_once(dirname(__FILE__) . '/sql/uninstall.php');
        return true;
    }
    public function backToModuleHome($aditionalParameter = '')
    {
        Tools::redirectAdmin($this->getModuleHome() . $aditionalParameter);
    }

    public function getModuleHome()
    {
        return $this->context->link->getAdminLink('AdminModules') . '&configure=' . $this->name . '&module_name=' .
                 $this->name;
    }
	
    public function getContent()
    {
		$this->registerHook('actionAfterDeleteProductInCart');
		$this->registerHook('actionAfterChangeProductInCart');
		$this->registerHook('displayPackegingText');
        return $this->adminController->init();
    }

    public function hookBackOfficeHeader()
    {
        return $this->adminController->includeBOCssJs();
    }
	
	public function hookDisplayPackegingGift()
    {
        return $this->frontController->renderList();
    }
	
	public function hookActionAfterChangeProductInCart($params)
    {
		$idWrapGift = Tools::getValue('id_bwg_wrap_gift');
		if(!empty($idWrapGift)){
			return BwgCartProduct::addNew($params['id_cart'], $params['id_product'], $params['id_product_attribute'], $idWrapGift);
		}
    }
	
	public function hookActionAfterDeleteProductInCart($params)
    {
        return BwgCartProduct::deleteBy($params['id_cart'], $params['id_product'], $params['id_product_attribute']);
    }

    public function hookHeader()
    {
		$this->context->controller->addJS($this->_path . '/views/js/front.js');
		$this->context->controller->addCSS($this->_path . '/views/css/front.css');
    }
	public function getUrl()
    {
        return Tools::getShopDomainSsl(true) . __PS_BASE_URI__ . 'modules/' . $this->name . '/';
    }
	
	public function clearAllTplCache()
    {
        $this->smartyClearCache('packeging_list.tpl');
    }
	
	public function smartyClearCache($template, $cache_id = null, $compile_id = null)
    {
        return $this->_clearCache($template, $cache_id, $compile_id);
    }

    public function smartyGetCacheId($name = null)
    {
        return $this->getCacheId($name);
    }

	public function hookDisplayPackegingText($params)
    {
        $result = BwgCartProduct::getPackaging($this->context->cart->id, $params['prodid'], $params['prodipa'], $this->context->language->id);
        $this->smarty->assign(array(
            'wrapgift' => $result
        ));
        return $this->display(__FILE__, 'shooping-product.tpl');
        var_dump($result);
    }
}
