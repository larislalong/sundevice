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

class BlockCartOverride extends BlockCart
{
	public function assignContentVars($params)
	{
		$isolatedQuantities = array();
		$quantities = Cart::getIsolatedQuantities();
		foreach($quantities as $row){
			$isolatedQuantities[$row['id_product'].'_'.$row['id_product_attribute']] = $row['quantity'];
		}
	    parent::assignContentVars($params);
	    $this->smarty->assign('PS_CART_LIFE_TIME', Configuration::get('PS_CART_LIFE_TIME'));
	    $this->smarty->assign('PS_CART_REFRESH_SHOW_TIME', Configuration::get('PS_CART_REFRESH_SHOW_TIME'));
	    $this->smarty->assign('currentDate', date('Y-m-d H:i:s'));
	    $this->smarty->assign('lastUpdateDate', $params['cart']->date_upd);
		$this->smarty->assign('isolatedQuantities', $isolatedQuantities);
	}
	
	public function hookHeader()
	{
	    parent::hookHeader();
		if(!Tools::getValue('content_only')){
			// $this->context->controller->addJS(($this->_path).'additional.js');
			// $this->context->controller->addJS(_THEME_JS_DIR_.'additional-product.js');
			// $this->context->controller->addJS(_THEME_JS_DIR_.'packaging.js');
		}
	}
}
