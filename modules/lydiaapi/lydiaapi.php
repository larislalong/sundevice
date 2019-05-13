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

if (!defined('_PS_VERSION_')) {
    exit;
}

class Lydiaapi extends PaymentModule
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'lydiaapi';
        $this->tab = 'payments_gateways';
        $this->version = '1.0.0';
        $this->author = 'lydiaapi';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('lydiaapi');
        $this->description = $this->l('lydiaapilydiaapi lydiaapi lydiaapilydiaapi lydiaapilydiaapi');

        $this->limited_countries = array('FR');

        $this->limited_currencies = array('EUR');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        if (extension_loaded('curl') == false)
        {
            $this->_errors[] = $this->l('You have to enable the cURL extension on your server to install this module');
            return false;
        }

        $iso_code = Country::getIsoById(Configuration::get('PS_COUNTRY_DEFAULT'));

        if (in_array($iso_code, $this->limited_countries) == false)
        {
            $this->_errors[] = $this->l('This module is not available in your country');
            return false;
        }
		require_once(dirname(__FILE__) . '/sql/install.php');
        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('payment') &&
            $this->registerHook('paymentReturn');
    }

    public function uninstall()
    {
		require_once(dirname(__FILE__) . '/sql/uninstall.php');
        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitLydiaapiModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output.$this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitLydiaapiModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'desc' => $this->l('GATEWAY URL'),
                        'name' => 'LYDIAAPI_GATEWAY_URL',
                        'label' => $this->l('GATEWAY URL'),
                    ),
					array(
                        'col' => 3,
                        'type' => 'text',
                        'desc' => $this->l('GATEWAY TOKEN'),
                        'name' => 'LYDIAAPI_GATEWAY_TOKEN',
                        'label' => $this->l('GATEWAY TOKEN'),
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'LYDIAAPI_GATEWAY_URL' => Configuration::get('LYDIAAPI_GATEWAY_URL', ''),
            'LYDIAAPI_GATEWAY_TOKEN' => Configuration::get('LYDIAAPI_GATEWAY_TOKEN', ''),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        
    }

    /**
     * This method is used to render the payment button,
     * Take care if the button should be displayed or not.
     */
    public function hookPayment($params)
    {
        $currency_id = $params['cart']->id_currency;
        $currency = new Currency((int)$currency_id);

        if (in_array($currency->iso_code, $this->limited_currencies) == false)
            return false;
		$products = $this->context->cart->getProducts();
		$message = 'Products: ';
		foreach ($products as $product)
		{
			$attributes = Product::getAttributesParams($product['id_product'], $product['id_product_attribute']);
			$names='';
			foreach($attributes as $row){
				$names .= $row['group'].' : '.$row['name'].', ';
			}
			$productName = $product['name'] .(empty($names)?'':' - '.rtrim($names,', '));
			
			$message.=$productName .';  ';
		}
		$uniqueId = $this->createPaymentUniqueId();
		$params = array(
			'email'=> $this->context->customer->email,
			'currency'=> $currency->iso_code,
			'message'=> $message,
			'unique_id'=> $uniqueId,
			'amount'=> $params['cart']->getOrderTotal(true, Cart::BOTH),
			'success_url'=> $this->context->link->getModuleLink('lydiaapi', 'validation', array(), true),
			'fail_url'=> $this->context->link->getPageLink('order'),
		);
		$params['signature'] = $this->createSignature($params);
        $this->smarty->assign('module_dir', $this->_path);
        $this->smarty->assign('form_url', Configuration::get('LYDIAAPI_GATEWAY_URL'));
        $this->smarty->assign('form_data', $params);

        return $this->display(__FILE__, 'views/templates/hook/payment.tpl');
    }

    /**
     * This hook is used to display the order confirmation page.
     */
    public function hookPaymentReturn($params)
    {
        if ($this->active == false)
            return;

        $order = $params['objOrder'];

        if ($order->getCurrentOrderState()->id != Configuration::get('PS_OS_ERROR'))
            $this->smarty->assign('status', 'ok');

        $this->smarty->assign(array(
            'id_order' => $order->id,
            'reference' => $order->reference,
            'params' => $params,
            'total' => Tools::displayPrice($params['total_to_pay'], $params['currencyObj'], false),
        ));

        return $this->display(__FILE__, 'views/templates/hook/confirmation.tpl');
    }
	
	public function checkCurrency($cart)
	{
		$currency_order = new Currency($cart->id_currency);
		$currencies_module = $this->getCurrency($cart->id_currency);

		if (is_array($currencies_module))
			foreach ($currencies_module as $currency_module)
				if ($currency_order->id == $currency_module['id_currency'])
					return true;
		return false;
	}
	
	public function processGatewayCall($param)
    {
        $return = array();
		$curl = curl_init(Configuration::get('LYDIAAPI_GATEWAY_URL'));
		curl_setopt($curl,CURLOPT_POST, true);
		curl_setopt($curl,CURLOPT_POSTFIELDS, $param);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($curl);
		curl_close($curl);
		if(!empty($result)){
			$return['data']= json_decode($result, false, 512, JSON_BIGINT_AS_STRING);
		}else{
			$return['errors'][] = $this->l('An error occured while connecting to Payment server');
		}
		return $return;
    }
	
	public function createSignature($param)
    {
        ksort($param);
		return md5(http_build_query($param).'&'.Configuration::get('LYDIAAPI_GATEWAY_TOKEN'));
    }
	
	public function createPaymentUniqueId()
    {
        $value = md5($this->context->cart->id. strtotime(date("Y-m-d H:i:s")).$this->context->cart->secure_key);
		Db::getInstance()->update('cart', array('lydiaapi_unique_id'=>$value), ' id_cart=' . (int)$this->context->cart->id);
		return $value;
		
    }
	public function getPaymentUniqueId()
    {
		$sql = 'SELECT lydiaapi_unique_id FROM ' . _DB_PREFIX_ . 'cart WHERE id_cart = '.(int)$this->context->cart->id;
		return Db::getInstance()->getValue($sql);
    }
}
