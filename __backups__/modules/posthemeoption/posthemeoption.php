<?php
if (!defined('_PS_VERSION_'))
	exit;
class posthemeoption extends Module
{
	protected static $cache_products;
	public function __construct()
	{
		$this->name = 'posthemeoption';
		$this->tab = 'front_office_features';
		$this->version = '1.0';
		$this->author = 'posthemes';
		$this->need_instance = 0;
		$this->bootstrap = true;
		parent::__construct();
		$this->displayName = $this->l('Pos theme editor');
		$this->description = $this->l('Google font and custom color editor');
	}
	public function install()
	{
		// font title
        $defaultfont1  = htmlentities("<link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>");
		// font link
        $defaultfont  = htmlentities("<link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,400italic,700,700italic' rel='stylesheet' type='text/css'>");
        if (is_string($defaultfont) === true)
        	$defaultfont = urldecode(preg_replace('/((\%5C0+)|(\%00+))/i', '', urlencode($defaultfont)));
        $defaultfont = !is_string($defaultfont)? $defaultfont : stripslashes($defaultfont);
		if (is_string($defaultfont1) === true)
        	$defaultfont1 = urldecode(preg_replace('/((\%5C0+)|(\%00+))/i', '', urlencode($defaultfont1)));
        $defaultfont1 = !is_string($defaultfont1)? $defaultfont1 : stripslashes($defaultfont1);
        Configuration::updateValue('FONT_TITLE', $defaultfont1);
        Configuration::updateValue('FONT_LINK', $defaultfont);
       // Configuration::updateValue('TEXT_COLOR','#808080');
        //Configuration::updateValue('LINK_COLOR','#000');
        //Configuration::updateValue('LINK_COLOR_HOVER','#ff8900');
		if (!parent::install()
            || !$this->registerHook('displayHeader')
			|| !Configuration::updateValue('PS_SET_DISPLAY_SUBCATEGORIES', 1)
		)
			return false;
		return true;
	}
	public function uninstall()
	{
	    Configuration::deleteByName('FONT_TITLE');
	    Configuration::deleteByName('FONT_LINK');
		//Configuration::deleteByName('MAIN_COLOR');
        //Configuration::deleteByName('LINK_COLOR');
        //Configuration::deleteByName('LINK_COLOR_HOVER');
		return parent::uninstall();
	}
    public function getContent()
	{
		if (Tools::isSubmit('submitModule'))
		{
			Configuration::updateValue('PS_QUICK_VIEW', (int)Tools::getValue('quick_view'));
			Configuration::updateValue('PS_GRID_PRODUCT', (int)Tools::getValue('grid_list'));
			Configuration::updateValue('PS_SET_DISPLAY_SUBCATEGORIES', (int)Tools::getValue('sub_cat'));
			foreach ($this->getConfigurableModules() as $module)
			{
				if (!isset($module['is_module']) || !$module['is_module'] || !Validate::isModuleName($module['name']) || !Tools::isSubmit($module['name']))
					continue;
				$module_instance = Module::getInstanceByName($module['name']);
				if ($module_instance === false || !is_object($module_instance))
					continue;
				$is_installed = (int)Validate::isLoadedObject($module_instance);
				if ($is_installed)
				{
					if (($active = (int)Tools::getValue($module['name'])) == $module_instance->active)
						continue;
					if ($active)
						$module_instance->enable();
					else
						$module_instance->disable();
				}
				else
					if ((int)Tools::getValue($module['name']))
						$module_instance->install();
			}
		}
		if (Tools::isSubmit('submitAxanMultiSave'))
		{
			Configuration::updateValue('FONT_TITLE', $this->getHtmlValue('FONT_TITLE'));
			Configuration::updateValue('FONT_LINK', $this->getHtmlValue('FONT_LINK'));
			//Configuration::updateValue('MAIN_COLOR', $this->getHtmlValue('TEXT_COLOR'));
			//Configuration::updateValue('MAIN_COLOR', $this->getHtmlValue('LINK_COLOR'));
			//Configuration::updateValue('MAIN_COLOR', $this->getHtmlValue('LINK_COLOR_HOVER'));
		}
		$html = $this->renderConfigurationForm();
		$html .= $this->renderThemeConfiguratorForm();
		return $html;
	}
	public function renderConfigurationForm()
	{
		$inputs = array();
		foreach ($this->getConfigurableModules() as $module)
		{
			$desc = '';
			if (isset($module['is_module']) && $module['is_module'])
			{
				$module_instance = Module::getInstanceByName($module['name']);
				if (Validate::isLoadedObject($module_instance) && method_exists($module_instance, 'getContent'))
					$desc = '<a class="btn btn-default" href="'.$this->context->link->getAdminLink('AdminModules', true).'&configure='.urlencode($module_instance->name).'&tab_module='.$module_instance->tab.'&module_name='.urlencode($module_instance->name).'">'.$this->l('Configure').' <i class="icon-external-link"></i></a>';
			}
			if (!$desc && isset($module['desc']) && $module['desc'])
				$desc = $module['desc'];
			$inputs[] = array(
				'type' => 'switch',
				'label' => $module['label'],
				'name' => $module['name'],
				'desc' => $desc,
				'values' => array(
					array(
						'id' => 'active_on',
						'value' => 1,
						'label' => $this->l('Enabled')
					),
					array(
						'id' => 'active_off',
						'value' => 0,
						'label' => $this->l('Disabled')
					)
				),
			);
		}
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Settings'),
					'icon' => 'icon-cogs'
				),
				'input' => $inputs,
				'submit' => array(
					'title' => $this->l('Save'),
					'class' => 'btn btn-default pull-right'
				)
			),
		);
		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitModule';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);
		return $helper->generateForm(array($fields_form));
	}
	protected function getConfigurableModules()
	{
		return array(
			array(
				'label' => $this->l('Display top banner'),
				'name' => 'blockbanner',
				'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('blockbanner')) && $module->isEnabledForShopContext(),
				'is_module' => true,
			),
			array(
				'label' => $this->l('Display links to your store\'s social accounts (Twitter, Facebook, etc.)'),
				'name' => 'blocksocial',
				'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('blocksocial')) && $module->isEnabledForShopContext(),
				'is_module' => true,
			),
			array(
				'label' => $this->l('Display social sharing buttons on the product\'s page'),
				'name' => 'socialsharing',
				'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('socialsharing')) && $module->isEnabledForShopContext(),
				'is_module' => true,
			),
			array(
				'label' => $this->l('Display information about store (Company name, Address, Phone number, Email)'),
				'name' => 'blockcontactinfos',
				'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('blockcontactinfos')) && $module->isEnabledForShopContext(),
				'is_module' => true,
			),
			array(
				'label' => $this->l('Display quick view on homepage and category pages'),
				'name' => 'quick_view',
				'value' => (int)Tools::getValue('PS_QUICK_VIEW', Configuration::get('PS_QUICK_VIEW'))
			),
			array(
				'label' => $this->l('Display categories as list instead of the default grid'),
				'name' => 'grid_list',
				'value' => (int)Configuration::get('PS_GRID_PRODUCT'),
				'desc' => $this->l('Works only for first-time users. This setting is overridden by the user\'s choice as soon as the user cookie is set.'),
			),
			array(
				'label' => $this->l('Display subcategories in category page'),
				'name' => 'sub_cat',
				'value' => (int)Tools::getValue('PS_SET_DISPLAY_SUBCATEGORIES', Configuration::get('PS_SET_DISPLAY_SUBCATEGORIES')),
			)
		);
	}
	public function getConfigFieldsValues()
	{
		$values = array();
		foreach ($this->getConfigurableModules() as $module)
			$values[$module['name']] = $module['value'];
		return $values;
	}
    private function getHtmlValue($key, $default_value = false)
	{
		if (!isset($key) || empty($key) || !is_string($key))
			return false;
		$ret = (isset($_POST[$key]) ? $_POST[$key] : (isset($_GET[$key]) ? $_GET[$key] : $default_value));
        $ret = htmlentities($ret);
		if (is_string($ret) === true)
			$ret = urldecode(preg_replace('/((\%5C0+)|(\%00+))/i', '', urlencode($ret)));
		return !is_string($ret)? $ret : stripslashes($ret);
	}
    public function renderThemeConfiguratorForm()
	{
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Settings'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => 'text',
						'label' => $this->l('Google font custom 1'),
						'name' => 'FONT_TITLE',
                        'desc' => $this->l("Example: <link href='http://fonts.googleapis.com/css?family=Gilda+Display' rel='stylesheet' type='text/css'>"),
					),
					array(
						'type' => 'text',
						'label' => $this->l('Google font custom 2'),
						'name' => 'FONT_LINK',
                        'desc' => $this->l("Example: <link href='http://fonts.googleapis.com/css?family=Gilda+Display' rel='stylesheet' type='text/css'>"),
					),
                    //array(
					//	'type' => 'color',
					//	'label' => $this->l('Body color'),
					//	'name' => 'TEXT_COLOR',
                    //    'size' => 30,
					//),
					//array(
					//	'type' => 'color',
					//	'label' => $this->l('Link color'),
					//	'name' => 'LINK_COLOR',
                    //    'size' => 30,
					//),
					//array(
					//	'type' => 'color',
					//	'label' => $this->l('Link color on hover'),
					//	'name' => 'LINK_COLOR_HOVER',
                    //    'size' => 30,
					//),
				),
				'submit' => array(
					'title' => $this->l('Save'),
				)
			),
		);
		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();
		$helper->id = (int)Tools::getValue('id_carrier');
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitAxanMultiSave';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => array(
                'FONT_LINK'=> html_entity_decode(Tools::getValue('FONT_LINK',Configuration::get('FONT_LINK'))),
                'FONT_TITLE'=> html_entity_decode(Tools::getValue('FONT_TITLE',Configuration::get('FONT_TITLE'))),
                //'TEXT_COLOR'=> Tools::getValue('TEXT_COLOR',Configuration::get('TEXT_COLOR')),
                //'LINK_COLOR'=> Tools::getValue('LINK_COLOR',Configuration::get('LINK_COLOR')),
                //'LINK_COLOR_HOVER'=> Tools::getValue('LINK_COLOR_HOVER',Configuration::get('LINK_COLOR_HOVER')),
            ),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);
		return $helper->generateForm(array($fields_form));
	}
    private function hex2rgba($hex) {
       $hex = str_replace("#", "", $hex);
       if(strlen($hex) == 3) {
          $r = hexdec(substr($hex,0,1).substr($hex,0,1));
          $g = hexdec(substr($hex,1,1).substr($hex,1,1));
          $b = hexdec(substr($hex,2,1).substr($hex,2,1));
       } else {
          $r = hexdec(substr($hex,0,2));
          $g = hexdec(substr($hex,2,2));
          $b = hexdec(substr($hex,4,2));
       }
       $rgba = 'rgba('.$r.','.$g.','.$b.',0.8)';
       return $rgba;
    }
    public function hookDisplayHeader($params)
	{
		$this->context->controller->addCSS($this->_path . 'css/ElegantIcons.css');
		$this->context->controller->addCSS($this->_path . 'css/animate.css');
		$this->context->controller->addCSS($this->_path . 'css/addition.css');
		$this->context->controller->addCSS($this->_path . 'css/animation.css');
        $this->context->controller->addJS($this->_path . 'js/owl.carousel.js');
		// title
		$titlefont =  Configuration::get('FONT_TITLE');
		if( $titlefont != '' ){
		   $start1 = strpos($titlefont,'family');
		   $titlefont = substr_replace($titlefont,'',0,$start1+7);
		   $start1 = strpos($titlefont,"'");
		   $titlefont = substr_replace($titlefont,'',$start1,strlen($titlefont));
		   if (strpos($titlefont,":")>0){
				$start1 = strpos($titlefont,":");
				$titlefont = substr_replace($titlefont,'',$start1,strlen($titlefont));
		   }
		   $font_name_title = str_replace('+',' ',$titlefont);
		   $titlefont =  Configuration::get('FONT_TITLE');
		   $start1 = strpos($titlefont,'http');
		   $substr1 = substr_replace($titlefont,'',$start1,strlen($titlefont)-$start1);
		   $start1 = strpos($titlefont,'://');
		   $titlefont = substr_replace($titlefont,'',0,$start1);
		   $titlefont = $substr1.(empty( $_SERVER['HTTPS'] ) ? 'http' : 'https') .$titlefont;
	   }
		// link and text
	   $linkfont =  Configuration::get('FONT_LINK');
	   if( $linkfont != '' ){
		   $start = strpos($linkfont,'family');
		   $linkfont = substr_replace($linkfont,'',0,$start+7);
		   $start = strpos($linkfont,"'");
		   $linkfont = substr_replace($linkfont,'',$start,strlen($linkfont));
		   if (strpos($linkfont,":")>0){
				$start = strpos($linkfont,":");
				$linkfont = substr_replace($linkfont,'',$start,strlen($linkfont));
		   }
		   $font_name_link = str_replace('+',' ',$linkfont);
		   $linkfont =  Configuration::get('FONT_LINK');
		   $start = strpos($linkfont,'http');
		   $substr = substr_replace($linkfont,'',$start,strlen($linkfont)-$start);
		   $start = strpos($linkfont,'://');
		   $linkfont = substr_replace($linkfont,'',0,$start);
		   $linkfont = $substr.(empty( $_SERVER['HTTPS'] ) ? 'http' : 'https') .$linkfont;
	   }
		// color
       //$textcolor = Configuration::get('TEXT_COLOR');
       //$linkcolor = Configuration::get('LINK_COLOR');
       //$linkcolorhover = Configuration::get('LINK_COLOR_HOVER');
	   // in case you need rgba color
       //$grbacolor = $this->hex2rgba($maincolor);
       $this->context->smarty->assign(array(
			'linkfont' => $linkfont,
			'titlefont' => $titlefont,
			'fontnamelink' => isset($font_name_link)?$font_name_link:'',
			'fontnametitle' => $font_name_title,
			//'textcolor' => $textcolor,
			//'linkcolor' => $linkcolor,
			//'linkcolorhover' => $linkcolorhover,
	      	));
		// sub category
		if (isset($this->context->controller->php_self) && $this->context->controller->php_self == 'category')
		{
			$this->context->smarty->assign(array(
				'display_subcategories' => (int)Configuration::get('PS_SET_DISPLAY_SUBCATEGORIES')
			));
		}
       return $this->display(__FILE__, 'posthemeoption.tpl');
	}
 }