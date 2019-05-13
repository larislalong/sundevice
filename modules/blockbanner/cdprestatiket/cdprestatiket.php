<?php
/**
* cdprestatiket :: Customer ticket Information of the product
*
* @author    contact@cleanpresta.com (www.cleanpresta.com)
* @copyright 2015 cleandev.net
* @license   You only can use module, nothing more!
*/

if (!defined('_PS_VERSION_'))
	exit;

if(!class_exists('CleanModule'))
    require_once(dirname(__FILE__) .'/CleanModule.php');

class CdPrestaTiket  extends CleanModule 
{
	private $_html = '';
	
	public function __construct() 
	{
		$this->name 		= 'cdprestatiket';
		$this->tab 			= 'front_office_features';
		$this->version 		= '1.0.0';
		$this->mprefix 		= 'cdpt_';
		$this->reference 	= 'CDM0010';
		$this->addon_id 	= '20164';
		$this->module_key	= '5baa54c58f9da0b410526a85b6d32666';
		$this->author		= $this->l('cleanpresta.com');
		$this->secure_key	= Tools::encrypt($this->name);
		
		$this->displayName 	= $this->l('Customer ticket Informations.');
		$this->description 	= $this->l('Modulate allowing to give extra customer ticket informations of the product.');
		$this->full_description = $this->l('Modulate allowing to give extra customer ticket informations of the product.');
		$this->ps_versions_compliancy = array('min' => '1.5', 'max' => _PS_VERSION_);
		$this->confirmUninstall = $this->l('The desinstallation will remove all your data.');
		
		//cleanpresta var
		$this->hooks = array('displayCustomerAccount', 'displayFooterProduct', 'displayMyAccountBlockfooter', 'displayNav', 'displayRightColumnProduct');
		
		//config form
		$this->config_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Settings'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => 'select',
						'label' => $this->l('SAV ID (contact id)'),
						'name' => 'cdpt_CONTACT_ID',
						'lang' => false,
						'desc' => $this->l('Previously created in the section "Client > Contact"'),
						'options' => array(
							'query' => Db::getInstance()->ExecuteS('SELECT id_contact AS id, name FROM '._DB_PREFIX_.'contact_lang WHERE id_lang='.(int)Configuration::get('PS_LANG_DEFAULT')),
							'id' => 'id',
							'name' => 'name',
						),
					),
					array(
						'type' => 'text',
						'label' => $this->l('The messages quantity in page'),
						'name' => 'cdpt_NUMBER_PAGE',
						'dafault' => 5,
						'lang' => false,
						'desc' => $this->l('Put the message quantity in page'),
					),
				),
				'submit' => array(
					'title' => $this->l('Save')
				)
			),
		);
		
		parent::__construct();
	}
	
	/*hooks customer account*/
	public function hookDisplayCustomerAccount($params)
    {
		$this->context->smarty->assign(array(
			'cdpt_controller' => $this->context->link->getModuleLink($this->name, 'default'),
		));
		return $this->display(__FILE__, 'cdprestatiket.tpl');
	}
	
	/*hook footrProduct*/
	/*
	public function hookDisplayFooterProduct($param)
	{
		$this->context->smarty->assign(array(
			'cdpt_controller11' => $this->context->link->getModuleLink($this->name, 'sendend').'&cdptproducthook='.$param['product']->id,
		));
		return $this->display(__FILE__, 'cdprestatiketProduct.tpl');
	}
	*/
	public function hookDisplayMyAccountBlockfooter($param)
	{
		if ($this->context->customer->id)
		{
			$this->context->smarty->assign(array(
				'cdpt_controller12' => $this->context->link->getModuleLink($this->name, 'default', array('form' => 'formulaire')),
			));
			return $this->display(__FILE__, 'cdptAccount.tpl');
		}
	}
	public function hookDisplayNav($param)
	{
		if ($this->context->customer->id)
		{
			$this->context->smarty->assign(array(
				'cdpt_controller12' => $this->context->link->getModuleLink($this->name, 'default', array('form' => 'formulaire')),
			));
			return $this->display(__FILE__, 'cdptNav.tpl');
		}
	}
	
	public function hookDisplayRightColumnProduct($param)
	{
		$this->context->smarty->assign(array(
			'cdpt_controller11' => $this->context->link->getModuleLink($this->name, 'sendend', array('cdptproducthook' => Tools::getValue('id_product'))),
		));
		return $this->display(__FILE__, 'cdprestatiketProduct.tpl');
	}
}