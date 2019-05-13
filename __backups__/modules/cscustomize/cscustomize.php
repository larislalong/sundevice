<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of customizefooter
 *
 * @author Fozeu Takoudjou
 */
if (!defined('_PS_VERSION_'))
	exit;

include_once _PS_MODULE_DIR_.'cscustomize/classes/Cseditor.php';

class Cscustomize extends Module{
    //put your code here
    public $_languages = array();
    private $_template = 'cscustomize.tpl';
    private $_tabtpl = array('displayHomeTop', 'displayFooter','displayHomeMiddle', 'displayHomeBottom',
        'displayFooterBottom', 'displayFooterCopyright', 'displayCategoryPage', 'displaySubTopColumn');


    public function __construct()
	{
		$this->name = 'cscustomize';
		$this->tab = 'front_office_features';
		$this->version = '1';
		$this->author = 'Fozeu Takoudjou';
		$this->need_instance = 0;
        $this->bootstrap = true;
        
		parent::__construct();

		$this->displayName = ('Customize your text bloc');
		$this->description = $this->l('This module help you to add customize text on your shop');
		$this->confirmUninstall = $this->l('Uninstalling the module will delete all its entries in the database. To keep your data, delete the folder "install" of the module before uninstall it. Do you want to continue uninstalling the module ?');
		$isUpdatePosition = Tools::getValue('ajax') && (Tools::getValue('action')=='updatePositions') &&
		(Tools::getValue('id')=='cseditor') && is_array(Tools::getValue('cseditor'));
		if($isUpdatePosition){
		    $this->ajaxProcessUpdatePositions();
		}
    }
    
    public function install()
	{
		if (!parent::install()
                || !$this->_installDb()
                || !$this->registerHook('displayHeader')
                || !$this->registerHook('displayFooter')
                || !$this->registerHook('displayHomeTabContent')
                || !$this->registerHook('displayTop')
                || !$this->registerHook('displayBanner')
                || !$this->registerHook('displayHeaderSlider')
                || !$this->registerHook('displayHomeTopLogin')
                || !$this->registerHook('displayHomeBottomLogin')
                || !$this->registerHook('displayHomeTop')
                || !$this->registerHook('displayHomeMiddle')
                || !$this->registerHook('displayHomeBottom')
                || !$this->registerHook('displayFooterBottom')
                || !$this->registerHook('displayFooterCopyright')
                || !$this->registerHook('displayCategoryPage')
                || !$this->registerHook('displaySubTopColumn')
                || !$this->registerHook('displayBackOfficeHeader')
                || !$this->registerHook('displayProductLogin')
		        
		        || !$this->registerHook('displayWhatIsAGrade')
		        || !$this->registerHook('displayAvailablePackeging')
		        || !$this->registerHook('displayClearGradingSysteme')
		        || !$this->registerHook('displayTestedPhone')
		        || !$this->registerHook('displayPackeging')
                || !$this->registerHook('displayCMSPage')
                || !$this->registerHook('displayCMSPageAbout')
				
				|| !$this->registerHook('displayUsaInStock')
		        || !$this->registerHook('displayUsaInReadToShip')
		        || !$this->registerHook('displayUsaShipIn2Days')
		        || !$this->registerHook('displayUsaShipIn8Days')
				
				|| !$this->registerHook('displayIrelandInStock')
		        || !$this->registerHook('displayIrelandInReadToShip')
		        || !$this->registerHook('displayIrelandShipIn2Days')
		        || !$this->registerHook('displayIrelandShipIn8Days')
                
                || !$this->registerHook('displayCmsAtelierRow2')
                || !$this->registerHook('displayCmsAtelierRow3')
            )
                return false;
            return true;
	}
    
    public function uninstall()
	{
		 if(
                !$this->_uninstallDb()
                ||!parent::uninstall()
            )
            return false;
        return true;
	}
    /**
     * installe la ou les tables nécessaire
     */
    private function _installDb(){
        
        $sql1 ="CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."cseditor` (
            `id_cseditor` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `hook` varchar(100) NOT NULL DEFAULT 'displayFooter',
            `id_shop_default` int(10) unsigned NOT NULL DEFAULT '1',
            `displaytitle` tinyint(1) unsigned NOT NULL DEFAULT '0',
            `active` tinyint(1) unsigned NOT NULL DEFAULT '0',
            `date_add` datetime NOT NULL,
            `date_upd` datetime NOT NULL,
            `position` int(10) unsigned NOT NULL DEFAULT '0',
            `id_block` varchar(100) NOT NULL,
            `class_block` varchar(255) NOT NULL,
            `nameimg` varchar(255) NOT NULL,
			`nameimgsec` varchar(255) NOT NULL,
            `color` varchar(20) NOT NULL,
            `linktype` varchar(255) NOT NULL,
            `id_element` int(11) unsigned NOT NULL,
            PRIMARY KEY (`id_cseditor`)
            ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
        
        $sql2 = "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."cseditor_lang` (
            `id_cseditor` int(10) unsigned NOT NULL,
            `id_shop` int(11) unsigned NOT NULL DEFAULT '1',
            `id_lang` int(10) unsigned NOT NULL,
            `titleblock` varchar(254) NOT NULL,
            `secondtitle` varchar(254) NOT NULL,
            `linkblock` varchar(254) NOT NULL,
            `editortext` text,
            PRIMARY KEY (`id_cseditor`,`id_shop`,`id_lang`)
          ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;";
        
        $sql3 ="CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."cseditor_shop` (
            `id_cseditor` int(11) NOT NULL,
            `id_shop` int(11) NOT NULL,
            `position` int(10) unsigned NOT NULL DEFAULT '0',
            PRIMARY KEY (`id_cseditor`,`id_shop`)
          ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;";
        $r1 = Db::getInstance()->Execute($sql1);
        $r2 = Db::getInstance()->Execute($sql2);
        $r3 = Db::getInstance()->Execute($sql3);
        
        if (!$r1 && !$r2 && !$r3)
           return false;
       return true;
    }
    /**
     * déinstalle la ou les tables nécessaire
     */
    private function _uninstallDb(){
        $r1 = Db::getInstance()->Execute('DROP TABLE IF EXISTS `'. _DB_PREFIX_ .'cseditor`');
        $r2 = Db::getInstance()->Execute('DROP TABLE IF EXISTS `'. _DB_PREFIX_ .'cseditor_lang`');
        $r3 = Db::getInstance()->Execute('DROP TABLE IF EXISTS `'. _DB_PREFIX_ .'cseditor_shop`');

        if (!$r1 && !$r2 && !$r3)
            return false;
        return true;
    }
    
    function getContent(){
        $this->registerHook('actionShopDataDuplication');
        $this->registerHook('displayCMSPageAbout');
		$this->_html ='';
        $this->_html .=$this->_postProcess();
        $this->_html .=$this->renderForm();
        $listHtmlPerHook = $this->renderList();
        $this->context->smarty->assign(
            array(
                'listHtmlPerHook' => $listHtmlPerHook
            )
        );
        $listBlock = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');
        $this->_html .=$listBlock;
        $contact = '<p class="center" style="clear: both; text-align: center;">'.$this->l('For any problem with this module, contact us on').
            ' <a class="contact" href="mailto:fozeutakoudjou@gmail.com" title="Francis Fozeu">fozeutakoudjou@gmail.com</a> '.$this->l('or on skype').': <span class="contact">ffozeu</span>
        </p>';
        return $this->_html.$contact;
    }
    
    /**
     * retourne le formulaire d'ajout ou d'edition
     * @return type
     */
    public function renderForm(){
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
        $image_url = false;
        $image_size = false;
        $image_url_sec = false;
        $image_size_sec = false;
        if ($id_editor = Tools::getValue('id_cseditor')){
            $cseditor = new Cseditor((int)$id_editor);
            $image = dirname(__FILE__).'/img/'.$cseditor->nameimg;
            $image_sec = dirname(__FILE__).'/img/'.$cseditor->nameimgsec;
            $image_url = ImageManager::thumbnail($image, $cseditor->nameimg, 350);
            $image_url_sec = ImageManager::thumbnail($image_sec, $cseditor->nameimgsec, 350);
            $image_size = file_exists($image) ? filesize($image) / 1000 : false;
            $image_size_sec = file_exists($image_sec) ? filesize($image_sec) / 1000 : false;
        }
		$this->fields_form[0]['form'] = array(
			'tinymce' => true,
			'legend' => array(
				'title' => $this->l('New custom block'),
			),
			'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Title'),
                    'name' => 'titleblock',
                    'lang' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Second Title '),
                    'name' => 'secondtitle',
                    'lang' => true,
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Link Type'),
                    'name' => 'linktype',
                    'class' => 'select-linktype',
                    'options' => array(
                        'query' => array(
                            array('key' => '0', 'name' => $this->l('Free')),
                            array('key' => 'Product', 'name' => $this->l('Product')),
                            array('key' => 'Category', 'name' => $this->l('Category')),
                            array('key' => 'Cms', 'name' => $this->l('CMS')),
                        ),
                        'id' => 'key',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Id Element'),
                    'name' => 'id_element',
                    'class' => 'col-lg-3 id_element_input',
                    'size' => '10',
                    'maxchar' => '10',
                    'maxlength' => '10',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Link '),
                    'name' => 'linkblock',
                    'lang' => true,
                    'class' => 'col-lg-3 linkblock_input',
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Hook'),
                    'name' => 'hook',
                    'options' => array(
                        'query'=>Cseditor::getListsHook(),
                        'id' => 'id',
                        'name' => 'name'
                    ),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Position'),
                    'name' => 'position',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Identifiant'),
                    'name' => 'id_block',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Différentes classes'),
                    'name' => 'class_block',
                    'desc' => $this->l('You can input more class separate by coma'),
                    'help-line' => $this->l('You can input more class separate by coma'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Color'),
                    'name' => 'color',
                    'desc' => $this->l('Example : #00000'),
                ),
                array(
					'type' => 'switch',
					'label' => $this->l('Displayed Title'),
					'name' => 'displaytitle',
					'required' => false,
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'displaytitle_on',
							'value' => 1,
							'label' => $this->l('Enabled')
						),
						array(
							'id' => 'displaytitle_off',
							'value' => 0,
							'label' => $this->l('Disabled')
						)
					)
				),
                array(
					'type' => 'switch',
					'label' => $this->l('Displayed'),
					'name' => 'active',
					'required' => false,
					'is_bool' => true,
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
					)
				),
				array(
					'type' => 'textarea',
					'label' => $this->l('Text'),
					'lang' => true,
					'name' => 'editortext',
					'cols' => 40,
					'rows' => 30,
					'class' => 'rte',
					'autoload_rte' => true,

				),
                array(
                    'type' => 'file',
                    'label' => $this->l('Select primary file'),
                    'name' => 'nameimg',
                    'display_image' => true,
                    'image' => $image_url ? $image_url : false,
                    'size' => $image_size,
                    'desc' => sprintf($this->l('Maximum image size: %s.'), ini_get('upload_max_filesize'))
                ),
				array(
                    'type' => 'file',
                    'label' => $this->l('Select second file'),
                    'name' => 'nameimgsec',
                    'display_image' => true,
                    'image' => $image_url_sec ? $image_url_sec : false,
                    'size' => $image_size_sec,
                    'desc' => sprintf($this->l('Maximum image size: %s.'), ini_get('upload_max_filesize'))
                ),
			),
			'buttons' => array(
				array(
					'href' => AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
					'title' => $this->l('Back to list'),
					'icon' => 'process-icon-back'
				),
				'save' => array(
					'name' => 'savecscustomize',
					'type' => 'submit',
					'title' => $this->l('Save'),
					'class' => 'btn btn-default pull-right',
					'icon' => 'process-icon-save'
				)
			)
		);
        if (Shop::isFeatureActive()) {
            $this->fields_form[0]['form']['input'][] = array(
                'type' => 'shop',
                'label' => $this->l('Shop association'),
                'name' => 'checkBoxShopAsso'
            );
        }

		$helper = new HelperForm();
		$helper->table = 'cseditor';
        $helper->identifier = 'id_cseditor';
        $helper->id = (int)Tools::getValue('id_cseditor');
		$helper->module = $this;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->languages = $this->getLanguages();
		
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		$helper->default_form_language = $default_lang;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : $default_lang;
		$helper->toolbar_scroll = true;
		$helper->title = $this->displayName;
		$helper->submit_action = 'savecscustomize';
        if ($id_editor = Tools::getValue('id_cseditor')){
            $this->fields_form[0]['form']['input'][] = array('type' => 'hidden', 'name' => 'id_cseditor');
            $cseditor = new Cseditor((int)$id_editor);
        }else
            $cseditor = new Cseditor();
        //var_dump($cseditor);die();
        $helper->tpl_vars = array(
			'fields_value' => $this->getFieldsValue($cseditor),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id,
            'nameimg_baseurl' => $this->_path.'views/img/'
		);

		return $helper->generateForm($this->fields_form);
    }
    
    /**
     * retourne la liste des blocks
     * @return type
     */
    public function renderList(){
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
        $this->fields_list = array(
            'id_cseditor' => array(
                'title' => $this->l('Block ID'),
                'type' => 'text',
                'search' => false,
                'orderby' => true,
            ),
            /*'hook' => array(
				'title' => $this->l('Hook Name'),
				'type' => 'text',
				'orderby' => true,
                'orderby' => false,
			),*/
            'titleblock' => array(
				'title' => $this->l('Name'),
                'type' => 'text',
                'filter' => false,
                'orderby' => false,
			),
			'editortext' => array(
				'title' => $this->l('content'),
				//'callback' => 'getEditorClean',
				'orderby' => false,
                'filter' => false,
                'orderby' => false,
                'search' => false
			),
            /*'id_block' => array(
				'title' => $this->l('Identifiant'),
				'type' => 'text',
				'align' => 'center',
                'filter' => false,
                'orderby' => false,
                'search' => false
			),*/
            /*'class_block' => array(
				'title' => $this->l('Class'),
				'type' => 'text',
				'align' => 'center',
                'filter' => false,
                'orderby' => false,
                'search' => false
			),*/
			'position' => array(
				'title' => $this->l('Position'),
				'filter_key' => 'sa!position',
				'position' => 'position',
				'align' => 'center',
			    'class' => 'fixed-width-xs'
			),
            'displaytitle' => array(
				'title' => $this->l('Displayed Title'),
				'active' => 'status',
				'type' => 'bool',
				'class' => 'fixed-width-xs',
				'align' => 'center',
				'orderby' => false
			),
			'active' => array(
				'title' => $this->l('Displayed'),
				'active' => 'status',
				'type' => 'bool',
				'class' => 'fixed-width-xs',
				'align' => 'center',
				'orderby' => false
			),
            
		);

		if (Shop::isFeatureActive())
			$this->fields_list['id_shop'] = array(
				'title' => $this->l('Shop ID'),
				'align' => 'center',
				'width' => 25,
				'type' => 'int',
				'search' => false
			);

		$helper = new HelperList();
		$helper->shopLinkType = '';
		$helper->simple_header = false;
		$helper->actions = array('edit', 'delete', 'duplicate');
		$helper->show_toolbar = true;
		$helper->imageType = 'jpg';
		$helper->toolbar_btn['new'] = array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&add'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Add new')
		);

		$helper->title = $this->displayName;
		$helper->table = 'cseditor';
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		$helper->identifier = 'id_cseditor';
		$helper->orderBy= 'position';
		$helper->orderWay= 'ASC';
		$helper->module = $this;
		
        $lists = Cseditor::getList($default_lang,  $this->context->shop->id);
        
        $listPerHook = array();
        foreach ($lists as &$value){
			$shops = Cseditor::getCsAssociatedShops($value['id_cseditor']);
            $value['editortext'] = $this->getEditorClean($value['editortext']);
            $value['id_shop'] = implode(', ', $shops);
            $listPerHook[$value['hook']][]= $value;
        }
        $listHtmlPerHook = array();
        foreach ($listPerHook as $hook => $list) {
            $newHelper = clone $helper;
            $newHelper->list_id = $newHelper->identifier.$hook;
            $newHelper->position_identifier = $newHelper->identifier;
            $newHelper->position_group_identifier= $newHelper->position_identifier;
            $newHelper->listTotal = count($list);
            
            $listHtmlPerHook[$hook] = $newHelper->generateList($list, $this->fields_list);
        }
        return $listHtmlPerHook;
    }
    
    /**
     * 
     */
    private function _postProcess(){
        //ajout ou mise à ajour
        if (Tools::isSubmit('savecscustomize'))
		{
            if ($id_cseditor = Tools::getValue('id_cseditor')){
                $cseditor = new Cseditor((int)$id_cseditor);
                $executesuccessful = $this->l('Custom block update succefull.');
            }else{
                $cseditor = new Cseditor();
                $executesuccessful = $this->l('Custom block add succefull.');
            }
            
            $this->copyFromPost($cseditor, 'cseditor');
            
            if ($cseditor->validateFields(false) && $cseditor->validateFieldsLang(false))
            {
                if($cseditor->save()){
					$this->updateAssoShop($cseditor->id);
                    $this->_html .= $this->displayConfirmation($executesuccessful);
                    $this->clearCache();
                }else $this->_html .= $this->displayError($this->l('Save or update failed.'));
            }
            else
                $this->_html .= $this->displayError($this->l('An error occurred while attempting to save.'));
        }
        //suppression block
        if (Tools::getIsset('deletecseditor'))
		{
			$info = new Cseditor((int)Tools::getValue('id_cseditor'));
			$info->delete();
			$this->_clearCache('cscustomize.tpl');
            $this->_html .= $this->displayConfirmation($this->l('Custom block delete succefull.'));
			Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
		}
    }
	protected function getSelectedAssoShop($table)
    {
        if (! Shop::isFeatureActive()) {
            return array(
                $this->context->shop->id
            );
        }
        
        $shops = Shop::getShops(true, null, true);
        if (count($shops) == 1 && isset($shops[0])) {
            return array(
                $shops[0],
                'shop'
            );
        }
        
        $assos = array();
        if (Tools::isSubmit('checkBoxShopAsso_' . $table)) {
            foreach (Tools::getValue('checkBoxShopAsso_' . $table) as $id_shop => $value) {
                $assos[] = (int) $id_shop;
            }
        } elseif (Shop::getTotalShops(false) == 1) {
            // if we do not have the checkBox multishop, we can have an admin
            // with only
            // one shop and being in multishop
            $assos[] = (int) Shop::getContextShopID();
        }
        return $assos;
    }
	 protected function updateAssoShop($id_object)
    {
        $table='cseditor';
        $identifier = 'id_cseditor';
        
        // Get list of shop id we want to exclude from asso deletion
        $assos_data = $this->getSelectedAssoShop($table);
        $exclude_ids = $assos_data;
        foreach (Db::getInstance()->executeS('SELECT id_shop FROM ' . _DB_PREFIX_ . 'shop') as $row) {
            if (! $this->context->employee->hasAuthOnShop($row['id_shop'])) {
                $exclude_ids[] = $row['id_shop'];
            }
        }
        $where = '`' . bqSQL($identifier) . '` = ' . (int) $id_object .
        ($exclude_ids ? ' AND id_shop NOT IN (' . implode(', ', array_map('intval', $exclude_ids)) . ')' : '');
        Db::getInstance()->delete($table . '_shop', $where);
        $insert = array();
        foreach ($assos_data as $id_shop) {
            $insert[] = array(
                $identifier => (int) $id_object,
                'id_shop' => (int) $id_shop
            );
        }
        return Db::getInstance()->insert($table . '_shop', $insert, false, true, Db::INSERT_IGNORE);
    }
    
    /**
	 * Return the list of fields value
	 *
	 * @param object $obj Object
	 * @return array
	 */
	public function getFieldsValue($obj)
	{
		foreach ($this->fields_form as $fieldset)
			if (isset($fieldset['form']['input']))
				foreach ($fieldset['form']['input'] as $input)
					if (!isset($this->fields_value[$input['name']]))
						if (isset($input['type']) && $input['type'] == 'shop')
						{
							if ($obj->id)
							{
								$result = Shop::getShopById((int)$obj->id, $this->identifier, $this->table);
								foreach ($result as $row)
									$this->fields_value['shop'][$row['id_'.$input['type']]][] = $row['id_shop'];
							}
						}
						elseif (isset($input['lang']) && $input['lang'])
							foreach ($this->_languages as $language)
							{
								$fieldValue = $this->getFieldValue($obj, $input['name'], $language['id_lang']);
								if (empty($fieldValue))
								{
									if (isset($input['default_value']) && is_array($input['default_value']) && isset($input['default_value'][$language['id_lang']]))
										$fieldValue = $input['default_value'][$language['id_lang']];
									elseif (isset($input['default_value']))
										$fieldValue = $input['default_value'];
								}
								$this->fields_value[$input['name']][$language['id_lang']] = $fieldValue;
							}
						else
						{
							$fieldValue = $this->getFieldValue($obj, $input['name']);
							if ($fieldValue === false && isset($input['default_value']))
								$fieldValue = $input['default_value'];
							$this->fields_value[$input['name']] = $fieldValue;
						}

		return $this->fields_value;
	}

	/**
	 * Return field value if possible (both classical and multilingual fields)
	 *
	 * Case 1 : Return value if present in $_POST / $_GET
	 * Case 2 : Return object value
	 *
	 * @param object $obj Object
	 * @param string $key Field name
	 * @param integer $id_lang Language id (optional)
	 * @return string
	 */
	public function getFieldValue($obj, $key, $id_lang = null)
	{
		if ($id_lang)
			$default_value = (isset($obj->id) && $obj->id && isset($obj->{$key}[$id_lang])) ? $obj->{$key}[$id_lang] : false;
		else
			$default_value = isset($obj->{$key}) ? $obj->{$key} : false;

		return Tools::getValue($key.($id_lang ? '_'.$id_lang : ''), $default_value);
	}
    
    /**
     * renvoi les langues possibles
     * @return type
     */
    public function getLanguages()
	{
		$cookie = $this->context->cookie;
		$this->allow_employee_form_lang = (int)Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG');
		if ($this->allow_employee_form_lang && !$cookie->employee_form_lang)
			$cookie->employee_form_lang = (int)Configuration::get('PS_LANG_DEFAULT');
		
		$lang_exists = false;
		$this->_languages = Language::getLanguages(false);
		foreach ($this->_languages as $lang)
			if (isset($cookie->employee_form_lang) && $cookie->employee_form_lang == $lang['id_lang'])
				$lang_exists = true;

		$this->default_form_language = $lang_exists ? (int)$cookie->employee_form_lang : (int)Configuration::get('PS_LANG_DEFAULT');

		foreach ($this->_languages as $k => $language)
			$this->_languages[$k]['is_default'] = ((int)($language['id_lang'] == $this->default_form_language));

		return $this->_languages;
	}
    
    /**
	 * Copy datas from $_POST to object
	 *
	 * @param object &$object Object
	 * @param string $table Object table
	 */
	protected function copyFromPost(&$object, $table)
	{
		/* Classical fields */
        
		foreach ($_POST as $key => $value)
			if (array_key_exists($key, $object) && $key != 'id_'.$table)
			{
				/* Do not take care of password field if empty */
				if ($key == 'passwd' && Tools::getValue('id_'.$table) && empty($value))
					continue;
				/* Automatically encrypt password in MD5 */
				if ($key == 'passwd' && !empty($value))
					$value = Tools::encrypt($value);
				$object->{$key} = $value;
			}

		/* Multilingual fields */
		$rules = call_user_func(array(get_class($object), 'getValidationRules'), get_class($object));
		if (count($rules['validateLang']))
		{
			$languages = Language::getLanguages(false);
			foreach ($languages as $language)
				foreach (array_keys($rules['validateLang']) as $field)
					if (isset($_POST[$field.'_'.(int)$language['id_lang']]))
						$object->{$field}[(int)$language['id_lang']] = $_POST[$field.'_'.(int)$language['id_lang']];
		}
        /* image fields */
        if (isset($_FILES['nameimg']['tmp_name']) && $_FILES['nameimg']['tmp_name'] != null) {
            $this->copyImage($object, 'nameimg');
        }
        if (isset($_FILES['nameimgsec']['tmp_name']) && $_FILES['nameimgsec']['tmp_name'] != null) {
            $this->copyImage($object, 'nameimgsec');
        }
	}
    /**
     * gestion de l'upload de l'image pour le bloc
     * @param type $object
     * @param type $nameimg
     * @return boolean
     */
    protected function copyImage(&$object, $nameimg = 'nameimg', $method = 'auto') {
        
        if (!isset($_FILES[$nameimg]['tmp_name'])) {
            return false;
        }
        if ($error = ImageManager::validateUpload($_FILES[$nameimg])) {
            $this->errors[] = $error;
        } else {
            $imgn =  $_FILES[$nameimg]['name'];
            $ext = Tools::substr($imgn, -4);
            $finalname = preg_replace('/[^A-Za-z0-9\-]/', '-', Tools::substr($imgn, 0, -4));
            if (!($tmpName = tempnam(_PS_TMP_IMG_DIR_, 'PS')) || !move_uploaded_file($_FILES[$nameimg]['tmp_name'], $tmpName)) {
                $this->errors[] = Tools::displayError('An error occurred during the image upload process.');
            } elseif (!ImageManager::resize($tmpName, dirname(__FILE__).'/img/'.$finalname.$ext)) {
                $this->errors[] = Tools::displayError('An error occurred while copying the image.');
            }
            if ($method == 'auto') {
                $imagesTypes = array(
                    /*array('name' => 'meduim', 'height' => 350, 'width' => 350),
                    array('name' => 'small', 'height' => 250, 'width' => 250),
                    array('name' => 'big', 'height' => 450, 'width' => 450),*/
                );
                foreach ($imagesTypes as $k => $image_type) {
                    if (!ImageManager::resize($tmpName, dirname(__FILE__).'/img/'.stripslashes($image_type['name']).'-'.$finalname.$ext, $image_type['width'], $image_type['height'], 'png')) {
                        $this->errors[] = Tools::displayError('An error occurred while copying this image:').' '.stripslashes($image_type['name']);
                    }
                }
            }
            $object->{$nameimg} = $finalname.$ext;
            @unlink($tmpName);
        }
    }
    
    /**
	  * Allows to display description without HTML tags and slashes
	  *
	  * @return string
	  */
    public static function getEditorClean($description)
	{
		return strip_tags(stripslashes($description));
	}
    /**
     * en cas d'un greffage à une position non implémentée
     * @param type $method
     * @param type $args
     * @return type
     */
    public function __call($method, $args)
	{
		//if hook exists
		if(function_exists($method))
			return call_user_func_array($method, $args);
        
        $hookname = substr($method, 4);
        return $this->getListblock($hookname, (isset($args['id_element'])?$args['id_element']:0)); 
	}
    /**
     * Implémentation des hooks
     * @param type $name
     */
    public function hookDisplayHeader($params){
        $this->context->controller->addJqueryPlugin(array('bxslider'));
        $this->context->controller->addJS($this->_path.'views/js/cscustomize.js');
        $this->context->controller->addCSS($this->_path.'views/css/cscustomize.css', 'all');
    }

    public function hookDisplayBackOfficeHeader() {
        $this->context->controller->addJS($this->_path . 'views/js/backcscustomize.js' );
    }
    
    public function hookDisplayFooter($params){
        return $this->getListblock('displayFooter');
    }
    
    public function hookDisplayTop($params){
        return $this->getListblock('displayTop');
    }
    
    public function hookDisplayHomeTabContent($params){
        return $this->getListblock('displayHomeTabContent');
    }
    
    public function hookDisplayBanner($params){
        return $this->getListblock('displayBanner');
    }
    
    public function hookDisplayFooterBottom($params){
        return $this->getListblock('displayFooterBottom');
    }
    
    public function hookDisplayFooterCopyright($params){
        $results = Cseditor::getBlockList(lcfirst('displayFooterCopyright'), $this->context->language->id, $this->context->shop->id);
        
        $this->smarty->assign(array(
            'listblocs' => $results,
            'numbercol' => count($results),
        ));
		return $this->display(__FILE__, 'footer-copyright.tpl');
    }

    public function hookdisplayTopColumn($params)
	{
		if (!isset($this->context->controller->php_self) || $this->context->controller->php_self != 'index')
			return;
        
		return $this->getListblock('displayTopColumn');
	}
    
    public function hookdisplayCategoryPage($params)
	{
		if (!isset($params['cat']) || $params['cat'] !=12)
            return;
        
		return $this->getListblock('displayCategoryPage');
	}
    public function hookDisplayClearGradingSysteme($params)
	{
		return $this->getListblock('displayClearGradingSysteme', (isset($params['id_element'])?$params['id_element']:0));
	}
    
    public function hookDisplayPackeging($params)
	{
		return $this->getListblock('displayPackeging', (isset($params['id_element'])?$params['id_element']:0));
	}
    
    /**
     * retourne la liste de contenu html implémenté sur un hook donné
     * @param type $name
     * @return type
     */
    private function getListblock($name, $id_element = 0){
        $cacheId = $this->getCacheId(lcfirst($name));
        
        if (!$this->isCached($this->_template, $cacheId))
		{
            $results = Cseditor::getBlockList(lcfirst($name), $this->context->language->id, $this->context->shop->id, $id_element);
            $this->_setTemplate(lcfirst($name).'.tpl');
            // var_dump($results);die;
            foreach ($results as $key => $value) {
                switch ($value['linktype']){
                case 'Product':
                    if ((int)$value['id_element'] != 0) {
                        $prod = new Product((int)$value['id_element'],false, $this->context->language->id, $this->context->shop->id);
                        $results[$key]['linkblock'] = $prod->getLink();
                    }
                    break;
                case 'Category':
                    if ((int)$value['id_element'] != 0) {
                        $cat = new Category((int)$value['id_element'], $this->context->language->id, $this->context->shop->id);
                        $results[$key]['linkblock'] =$cat->getLink();
                    }
                    break;
                case 'Cms':
                    if ((int)$value['id_element'] != 0) {
                        $cms = new CMS((int)$value['id_element'], $this->context->language->id, $this->context->shop->id);
                        $results[$key]['linkblock'] =  $this->context->link->getCMSLink($cms);
                    }
                    break;
                }
            }
            $this->smarty->assign(array(
                'dchookname' => lcfirst($name),
                'listblocs' => $results,
                'numbercol' => count($results),
                'mod_img' => $this->_path.'img/',
                'id_element' => (int)$id_element,
                'ismobile' => $this->context->isMobile(),
            ));
        }
		return $this->display(__FILE__, $this->_template, $cacheId);
    }
    
    private function _setTemplate($template){
        if (!$path = $this->_getTemplatePath($template))
			$template = 'cscustomize.tpl';
		$this->_template = $template;
    }
    
    private function _getTemplatePath($template){
        if (Tools::file_exists_cache(_PS_THEME_DIR_.'modules/'.$this->name.'/'.$template))
			return _PS_THEME_DIR_.'modules/'.$this->name.'/'.$template;
		elseif (Tools::file_exists_cache(_PS_THEME_DIR_.'modules/'.$this->name.'/views/templates/hook/'.$template))
			return _PS_THEME_DIR_.'modules/'.$this->name.'/views/templates/hook/'.$template;
		elseif (Tools::file_exists_cache(_PS_MODULE_DIR_.$this->name.'/views/templates/hook/'.$template))
			return _PS_MODULE_DIR_.$this->name.'/views/templates/hook/'.$template;

		return false;
    }
    private function clearCache()
    {
        foreach ($this->_tabtpl as $value) {
            $this->_clearCache($value);
        }
    }
    
    public function ajaxProcessUpdatePositions()
    {
        $way = (int)Tools::getValue('way');
        $positions = Tools::getValue('cseditor');
        
        $new_positions = array();
        foreach ($positions as $k => $v) {
            if (count(explode('_', $v)) == 5) {
                $new_positions[] = $v;
            }
        }
        $hasError = false;
        $successMessage = '';
        $errorsMessage = '';
        foreach ($new_positions as $position => $value) {
            $pos = explode('_', $value);
            $id = (int)$pos[3];
            if (isset($position) && Cseditor::updatePosition($id, $position)) {
                $successMessage.='ok position '.(int)$position.' for attribute group '.$id.'\r\n';
            } else {
                $hasError = true;
                $errorsMessage.='Can not update the '.$id.' cseditor to position '.(int)$position;
            }
        }
        $message = $successMessage;
        if($hasError){
            $message = '{"hasError" : true, "errors" : "'.$errorsMessage.'"}';
        }
        die($message);
    }
    
    public function hookdisplayCMSPage($params)
	{
		if (!isset($params['id_cms']))
            return;
        $hookname = ($params['id_cms'] == 8?'displayPackeging':($params['id_cms']==6?'displayClearGradingSysteme':'default'));
		return $this->getListblock($hookname, $params['id_cms']);
	}
	public function hookActionShopDataDuplication($params)
	{
		return Cseditor::duplicateShopData($params['old_id_shop'], $params['new_id_shop']);
	}
    
    protected function getCacheId($name = null){
        $cacheId = parent::getCacheId($name );
        return $cacheId.'|'.(int)$this->context->isMobile();
    }
}
