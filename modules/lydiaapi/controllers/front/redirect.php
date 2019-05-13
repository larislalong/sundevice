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

class LydiaapiRedirectModuleFrontController extends ModuleFrontController
{
    /**
     * Do whatever you have to before redirecting the customer on the website of your payment processor.
     */
    public function postProcess()
    {
        $cart = $this->context->cart;
        if (! $this->module->checkCurrency($cart)) {
            Tools::redirect('index.php?controller=order');
        }
		$currency = new Currency((int)$cart->id_currency);
		$params = array(
			'currency'=> $currency->iso_code,
			'amount'=> $cart->getOrderTotal(true, Cart::BOTH),
			'success_url'=> $this->context->link->getModuleLink('lydiaapi', 'validation', array(), true),
			'fail_url'=> $this->context->link->getModuleLink('lydiaapi', 'validation', array('cancel'=>1), true),
		);
		
		$params['signature'] = $this->module->createSignature($params);
		$result = $this->module->processGatewayCall($params);
		if(isset($result['errors']) && !empty($result['errors'])){
			$this->errors = $result['errors'];
		}elseif($result['data']->has_error){
			$this->errors = $result['data']->errors;
		}
		return $this->displayError('');
    }

    protected function displayError($message, $description = false)
    {
        /**
         * Create the breadcrumb for your ModuleFrontController.
         */
        $this->context->smarty->assign('path', '
			<a href="'.$this->context->link->getPageLink('order', null, null, 'step=3').'">'.$this->module->l('Payment').'</a>
			<span class="navigation-pipe">&gt;</span>'.$this->module->l('Error'));

        /**
         * Set error message and description for the template.
         */
        array_push($this->errors, $this->module->l($message), $description);

        return $this->setTemplate('error.tpl');
    }
}
