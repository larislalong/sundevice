<?php
/*
* 2013 - 2015 CleanDev
*
* NOTICE OF LICENSE
*
* This file is proprietary and can not be copied and/or distributed
* without the express permission of CleanDev
*
* @author    CleanPresta : www.cleanpresta.com <contact@cleanpresta.com>
* @copyright 2013 - 2015 CleanDev.net
* @license   You only can use module, nothing more!
*/

class  CleanModule extends Module
{
	protected $_errors   = array();
	//list of hooks :: array('hook1', 'hook2')
	public $hooks = array();
	public $new_hooks = array();
	
	// list of new tabs :: array('class1' => 'name1', ...,'class2' => 'name2');
	public $tabs = array();
	
	// list of exchanged tab :: array('oldTab1' => 'newTab1', ...,'oldTabn' => 'newTabn');
	public $exchanged_tabs = array(); 
	
	//coping files :: array('name1' => '/path1/',...,'namen' => '/pathn/')
	public $files = array(); 
	
	/**
	* getContent form
	* 'associative' => true, for associate array key
	*/
	public $config_form = array(); 
	
	// create sub dir in dir (element of tab)
	public $extra_dir = array(); 
	
	//retour notice
	public $html = ''; 
	
	//tab of config elements
	public $tab_elts = array();
	
	public $full_description; 
	
	public function __construct() 
	{
		$this->secure_key = Tools::encrypt($this->name);
		$this->need_instance = 0;
		$this->bootstrap = true;
		$this->trusted = false;
		$this->is_configurable = 1;
		$this->author = $this->l('cleanpresta.com');
		$this->ps_versions_compliancy['max'] = _PS_VERSION_;
		$this->confirmUninstall = $this->l('Are you sure you want to delete this module? This removes the files and tables related modules.');
		parent::__construct();  
		$this->defaultLan = (int)Configuration::get('PS_LANG_DEFAULT');
		$this->lang = Context::getContext()->language;
		$this->formBoolType = ($this->is_16())?'switch':'radio'; 
	}
	
	public function install()
	{
		$this->_clearCache('*');
		return $this->cdManageDb(true) && 
			$this->cdManageConfigs(true) && 
			$this->cdManageTabs(true) && 
			$this->cdManageExchangedTabs(true)&&
			$this->cdManageFiles(true) &&
			$this->cdManageDir(true) &&
			parent::install() &&
			$this->cdManageHooks(true) &&
			$this->cdClearCache();
	}
	
	public function uninstall()
	{
		$this->_clearCache('*');
		return $this->cdManageDb(false) && 
			$this->cdManageConfigs(false) && 
			$this->cdManageTabs(false) && 
			$this->cdManageExchangedTabs(false) &&
			$this->cdManageFiles(false) &&
			$this->cdManageDir(false) &&
			parent::uninstall() &&
			$this->cdManageHooks(false) &&
			$this->cdClearCache();
	}
	 
	/*Gestion des tables supplémentaires*/
	protected function cdManageDb($install = true)
	{
		$file = (!$install)?$this->local_path.'sql'.DIRECTORY_SEPARATOR.'uninstall.sql':$this->local_path.'sql'.DIRECTORY_SEPARATOR.'install.sql';
		
		//var_dump($file, file_exists($file), Tools::file_get_contents($file));die(); 
		if(file_exists($file) && ($sql = Tools::file_get_contents($file)))
		{ // pas de fichier, tant mieux!
			$sql = str_replace(array('PREFIX_', 'ps_', 'ENGINE_TYPE'), array(_DB_PREFIX_, _DB_PREFIX_, _MYSQL_ENGINE_), $sql);
			$sql = preg_split("/;\s*[\r\n]+/", trim($sql)); 
			foreach ($sql as $query)
			{
				if (!Db::getInstance()->execute(trim($query)))
				{
					return false;
				}
			}
		}
		return true;
	}
	
	/*Gestion de la configuration*/
	protected function cdManageConfigs($install = true)
	{
		if(!empty($this->config_form))
		{
			if(isset($this->config_form['form']))
			{
				$this->config_form = array($this->config_form);
			}
			foreach($this->config_form as $configs)
			{
				foreach($configs as $form)
				{
					if(!empty($form['input']))
					{
						foreach($form['input'] as $input)
						{
							if(empty($install))
							{ //désintallation
								Configuration::deleteByName($input['name']);
							}
							elseif(!empty($input['default']))
							{
								Configuration::updateValue($input['name'], $input['default']);
							}
						}
					}
				}
			}
		}
		return true;
	}
	
	/*Gestion de nouveaux menus*/
	protected function cdManageTabs($install = true)
	{
		if(!empty($this->tabs))
		{
			foreach($this->tabs as $elt)
			{
				$id_tab = (int)Tab::getIdFromClassName($elt['class']);
				if (!empty($id_tab))
				{
					$tab = new Tab($id_tab);
				}
				
				if($install)
				{ // installation
					if (empty($tab) || !Validate::isLoadedObject($tab))
						$tab = new Tab();
					$tab->class_name = $elt['class'];
					$tab->id_parent = (int)Tab::getIdFromClassName($elt['parent']);
					foreach (Language::getLanguages(true) as $lang)
						$tab->name[$lang['id_lang']] = $elt['name'];
					unset($lang);
					$tab->module = $this->name; 
					$tab->save();
				}
				elseif (!empty($tab) && Validate::isLoadedObject($tab))
				{
					return $tab->delete();
				}
			}
		}
		return true;
	}
	
	/*Gestion de permutation des menus*/
	protected function cdManageExchangedTabs($install = true)
	{
		if(!empty($this->exchanged_tabs))
		{
			foreach($this->exchanged_tabs as $old => $new)
			{
				if($install){ // installation
					$tab = new Tab((int)Tab::getIdFromClassName($old));
					$tab->class_name = $new;
					$tab->module = $this->name; 
				}
				else
				{
					$tab = new Tab((int)Tab::getIdFromClassName($new));
					$tab->class_name = $old;
					$tab->module = ''; 
				}
				if(!empty($tab->id)){ // si l'onglet est trouvé
					$tab->save();
				}
			}
		}
		return true;
	}
	
	/*Gestion de du menu*/
	protected function cdManageDir($install = true)
	{
		if(!empty($install)){ //Installation
			if(!empty($this->extra_dir)){
				foreach($this->extra_dir as $val)
				{
					$dir = _PS_ROOT_DIR_.DIRECTORY_SEPARATOR.$val.DIRECTORY_SEPARATOR.$this->name;
					if(!file_exists($dir)) @mkdir($dir, 0777);
				}
			}
		}
		return true;
	}
	
	protected function cdManageFiles($install = true)
	{
		if(!empty($this->files)){
			$sep = DIRECTORY_SEPARATOR;
			foreach($this->files as $file => $path)
			{
				$path = str_replace(array('theme','/','\\'), array(_THEME_NAME_,$sep,$sep), $path); //ad=>basename(_PS_ADMIN_DIR_)
				$src = $this->local_path.'files'.$sep.$file; 
				$dest = _PS_ROOT_DIR_.$path.$file;
				if(!file_exists(dirname($dest))) @mkdir(dirname($dest), 0777); //on cree le dossier s'il n'existe pas.
				if(is_writable(dirname($dest)))
				{
					if(empty($install)){ //désintallation
						//@unlink($dest); // on supprime le fichier copié
						@rename($dest.'.CDBACK', $dest); // on remet le fichier de départ
					}
					else
					{ // installation
						@rename($dest, $dest.'.CDBACK'); // on renomme le fichier si existe
						if(!Tools::copy($src, $dest)){ // avant de copier le nouveau
							throw new Exception(sprintf(Tools::displayError('directory (%s) not writable'), dirname($dest)));
						}
					}
				}
				else
					throw new Exception(sprintf(Tools::displayError('directory (%s) not writable'), dirname($dest))); 
			}
		}
		return true;
	}
	
	protected function cdClearCache()
	{
		/*if((bool)(version_compare(_PS_VERSION_, '1.5.0', '>=') === true)){
			@unlink(_PS_ROOT_DIR_.DIRECTORY_SEPARATOR.'/cache'.DIRECTORY_SEPARATOR.'class_index.php');
		}*/
		return true;
	}
	
	/*GEstion des hooks*/
	protected function cdManageHooks($install = true)
	{
		if($install)
		{
			$this->registerHook('header');
			$this->registerHook('backOfficeHeader');
			foreach($this->hooks as $hook)
			{
				$this->registerHook($hook); 
			}
			foreach($this->new_hooks as $hook)
			{
				if(!Hook::getIdByName($hook))
				{ // create if hook no exist
					$newHook = new Hook();
					$newHook->name = $hook;
					$newHook->title = preg_replace('/(?<=\\w)(?=[A-Z])/'," $1", $hook);
					$newHook->description = $newHook->title.' for CleanPresta modules';
					$newHook->save();
				}
				$this->registerHook($hook);
			}
		}
		else
		{
			$this->unregisterHook('header');
			$this->unregisterHook('backOfficeHeader');
			foreach($this->hooks as $hook)
			{
				$this->unregisterHook($hook); 
			}
			foreach($this->new_hooks as $hook)
			{
				$newHookId = (int)Hook::getIdByName($hook);
				$newHook = new Hook($newHookId);
				$newHook->delete();
				$this->unregisterHook($hook);
			}
		}
		return true;
	}
	
	/*Formulaire de configuration*/
	protected function renderForm()
	{
		if(isset($this->config_form['form']))
		{
			$this->config_form = array($this->config_form);
		}
		$helper = new HelperForm(); 
		$helper->module = $this;
		$helper->name_controller = $this->name; 
		$helper->identifier = $this->identifier;
		$helper->token = Tools::getAdminTokenLite('AdminModules').'#cdTabConfig';
		$helper->table = 'module';
		$lang = new Language($this->defaultLan);
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = $this->config_form;
 
		$helper->submit_action = 'submitConf'.$this->name;
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name; 
		/*1.5 compliantion*/
		$helper->toolbar_scroll = true;
		$helper->show_toolbar = true;
		$helper->toolbar_btn = $this->initToolbar();
		$helper->title = $this->displayName;
		
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		); 
		return $helper->generateForm($this->fields_form);
	}
	
	protected function initToolbar()
	{
		// on met juste le bouton de validation des formulaires
		$this->toolbar_btn['save'] = array(
			'href' => '#',
			'desc' => $this->l('Save')
		);
		return $this->toolbar_btn;
	}
	
	/*Récupération des valeurs de configuration*/
	public function getConfigValues()
	{
		$configs = array();
		$param = '';
		if(!empty($this->config_form))
		{
			if(isset($this->config_form['form']))
			{
				$this->config_form = array($this->config_form);
			}
			foreach($this->config_form as $configs)
			{
				foreach($configs as $form)
				{
					if(!empty($form['input']))
					{
						foreach($form['input'] as $input)
						{
							$param .= $input['name'].',';
						}
					}
				}
			}
			if(!empty($param))
			{
				$param = trim($param, ', ');
				$configs = Configuration::getMultiple(explode(',', $param));  
			}
		}
		$configs['is_16'] = $this->is_16();
		$configs['module_path'] = $this->_path;
		$configs['module_name'] = $this->name;
		return array_change_key_case($configs, CASE_LOWER);
	}
	
	public function is_16()
	{
		return (version_compare(_PS_VERSION_, '1.6.0', '>=') === true)?:false;
	}
	
	/*Assignation des values de configuration des formulaire*/
	protected function getConfigFieldsValues()
	{
		$configTab = array(); 
		if(!empty($this->config_form))
		{
			if(isset($this->config_form['form']))
			{
				$this->config_form = array($this->config_form);
			}
			$languages = Language::getLanguages(false);
			foreach($this->config_form as $configs)
			{
				foreach($configs as $form)
				{
					foreach($form['input'] as $input)
					{
						if(!empty($input['lang']))
						{
							foreach ($languages as $lang)
							{
								$configTab[$input['name']][$lang['id_lang']] = Configuration::get($input['name'], $lang['id_lang']);
							}
						}
						elseif(!empty($input['multiple']) || !empty($input['associative']))
						{
							$input_name = Tools::substr($input['name'], 0, strpos($input['name'],'['));
							$configTab[$input['name']] = Tools::getValue($input_name, unserialize(Configuration::get($input_name)));
							if(!empty($input['associative']) && count($configTab[$input['name']]) > 0 && !empty($configTab[$input['name']]))
							{
								foreach($configTab[$input['name']] as $key=>$val)
								{
									$configTab[$input_name.'['.$key.']'] = $val;
								}
								//unset($configTab[$input['name']]);
							}
						}
						else
							$configTab[$input['name']] = Tools::getValue($input['name'], Configuration::get($input['name']));
					}
				}
			}
		}
		return $configTab;
	}
	
	/*Validation du formulaire*/
	protected function processForm()
	{
		if(!empty($this->config_form) && Tools::isSubmit('submitConf'.$this->name))
		{
			if(isset($this->config_form['form']))
			{
				$this->config_form = array($this->config_form);
			}
			$languages = Language::getLanguages(false);
			foreach($this->config_form as $configs)
			{
				foreach($configs as $form)
				{
					foreach($form['input'] as $input)
					{
						$html = (!empty($input['autoload_rte']))?true:false;
						if(!empty($input['lang']))
						{
							$text = array();
							foreach ($languages as $lang)
								$text[$lang['id_lang']] = Tools::getValue($input['name'].'_'.$lang['id_lang']);
							Configuration::updateValue($input['name'], $text, $html);
						}
						elseif(!empty($input['multiple']) || !empty($input['associative']))
						{
							$input_name = Tools::substr($input['name'], 0, strpos($input['name'],'['));
							Configuration::updateValue($input_name, serialize(Tools::getValue($input_name)));
						}
						else
							Configuration::updateValue($input['name'], Tools::getValue($input['name']), $html);
					}
				}
			}
			return $this->displayConfirmation($this->l('Configuration updated'));
		}
		return '';
	}
	
	public function getContent()
	{
		if(count($this->config_form) > 0){
			$this->html .= $this->processForm(); 
			if(count($this->tab_elts))
				array_unshift($this->tab_elts, array('id'=>'cdTabConfig', 'title'=>$this->l('Settings'), 'content'=>$this->renderForm())); // add config table4
			else	
				$this->tab_elts[] = array('id'=>'cdTabConfig', 'title'=>$this->l('Settings'), 'content'=>$this->renderForm());
		}
		
		//adding doc 
		if(file_exists($this->local_path."cleanpresta/readme_".$this->lang->iso_code.".pdf"))
		{
			$this->context->smarty->assign('readme', _MODULE_DIR_.$this->name."/cleanpresta/readme_".$this->lang->iso_code.".pdf");
		}
		else
		{
			$this->context->smarty->assign('readme', _MODULE_DIR_.$this->name."/cleanpresta/readme_fr.pdf");
		}
		
		//adding log
		if(file_exists($this->local_path.'cleanpresta/changelog_'.$this->lang->iso_code.'.txt'))
		{
			$this->context->smarty->assign('change_log', nl2br(Tools::file_get_contents($this->local_path.'cleanpresta/changelog_'.$this->lang->iso_code.'.txt')));
		}
		else
		{
			$this->context->smarty->assign('change_log', nl2br(Tools::file_get_contents($this->local_path.'cleanpresta/changelog_fr.txt')));
		}
		
		//features
		if(file_exists($this->local_path.'cleanpresta/features_'.$this->lang->iso_code.'.xml')) 
			$features = $this->local_path.'cleanpresta/features_'.$this->lang->iso_code.'.xml'; 
		else 
			$features = $this->local_path.'cleanpresta/features_fr.xml'; 
		
		if(($xml = simplexml_load_file($features)))
			$this->context->smarty->assign('features', Tools::jsonDecode(Tools::jsonEncode((array)$xml), 1));
		
		$this->context->smarty->assign(
			array(
				'module_dir' => $this->_path, 
				'reference' => $this->reference, 
				'is_16' => $this->is_16(), 
				'notice' => $this->html, 
				'tabConfig' => $this->tab_elts, 
				'description' => (empty($this->full_description))?$this->description:$this->full_description,
				'base_dir' => _PS_BASE_URL_.__PS_BASE_URI__, 
				'module_name' => $this->name, 
				'addon_link' => $this->name, 
				'addon_ratting' =>  'http://addons.prestashop.com/contact-community.php?id_product='.$this->addon_id, 
				'version' => $this->version
			)
		);
		
		return $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl'); 
	}
	
	
	public function hookDisplayHeader($params)
	{
		if(!$this->is_16()){ //compatibility CSS
			$this->context->controller->addCSS($this->_path.'css/compatibily16.css', 'all');
		}
		$this->context->controller->addJS($this->_path.'views/js/'.$this->name.'.js');
		$this->context->controller->addCSS($this->_path.'views/css/'.$this->name.'.css', 'all');
	}
	
	public function hookBackOfficeHeader()
	{
		if(Tools::getValue('configure') == $this->name)
		{
			$this->context->controller->addJS($this->_path.'js/'.$this->name.'-admin.js'); 
			$this->context->controller->addCSS($this->_path.'css/'.$this->name.'-admin.css', 'all'); 
			$this->context->controller->addJqueryUI('ui.tabs');
		}
	}
}