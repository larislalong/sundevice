<?php
if (!defined('_PS_VERSION_')){
  exit;
 } 



  class klavyio  extends Module
  
  { 
  
  public function __construct()
  {
    $this->name = 'klavyio';
    $this->tab = 'front_office_features';
    $this->version = '1.0.0';
    $this->author = 'NdiagaSoft';
    $this->need_instance = 0;
    $this->ps_versions_compliancy = array('min' => '1.5', 'max' =>_PS_VERSION_);
    $this->bootstrap = true;
 
    parent::__construct();
 
    $this->displayName = $this->l('klavyio API');
    $this->description = $this->l('Integrate the marketing tool klavyio.');
 
    $this->confirmUninstall = $this->l('Are you sure you want to uninstall?'); 
    
  }
  
  
  
  public function install()
{
  if (Shop::isFeatureActive())
    Shop::setContext(Shop::CONTEXT_ALL);
	
 
  if (!parent::install() ||
      !$this->registerHook('displayFooter')		
     )
    return false;
 
  return true;
}
       public function uninstall()
    {
		
		if (!parent::uninstall()		
           )
                return false;
           
           return true;
    }  	
  
        public function getContent()
    {
          $output = null;
		  
		  $output.=$this->displayForm();
		  
		  	    if (Tools::isSubmit('submit'.$this->name))
      {
        $api_key =Tools::getValue('klavyio_API_KEY');
		
		
        if (!$api_key || empty($api_key))
            $output .= $this->displayError($this->l('Invalid Configuration value'));
        else
        {
            Configuration::updateValue('klavyio_API_KEY', $api_key);		
            $output.= $this->displayConfirmation($this->l('Settings updated'));
        }
      }  
	  
	  $button= '<div class="panel">'; 
      $button .= '<a href="https://www.prestashop.com/forums/profile/818747-ndiaga/"  target="_blank">
               <button class="btn btn-default">'.$this->l('For Full integration you can contact me from the PrestaShop forum').'</button>
               </a>';
	 $button.='</div>';		   
	  
		
       
            return $output.$button;
    }  	
   
    
    public function hookDisplayHeader()
   {
        //$this->context->controller->addCSS(($this->_path).'css/klavyio.css', 'all');
		//$this->context->controller->addJS(($this->_path).'js/klavyio.js', 'all');
		
    }  	
	
	 
   
  
  //for employee email 
  
  	   public function displayForm()
  {
    // Get default language
    $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
     
    // Init Fields form array
    $fields_form[0]['form'] = array(
        'legend' => array(
            'title' => $this->l('Settings'),
			'icon' => 'icon-cogs'
        ),
        'input' => array(
            array(
                'type' => 'text',
                'label' => $this->l('Enter your Klaviyo account Public API key:'),
                'name' => 'klavyio_API_KEY',
                'size' => 20,
                'required' => true
            )					
        ),
        'submit' => array(
            'title' => $this->l('Save'),           
        )
    );
     
    $helper = new HelperForm();
     
    // Module, token and currentIndex
    $helper->module = $this;
    $helper->name_controller = $this->name;
    $helper->token = Tools::getAdminTokenLite('AdminModules');
    $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
     
    // Language
    $helper->default_form_language = $default_lang;
    $helper->allow_employee_form_lang = $default_lang;
     
    // Title and toolbar
    $helper->title = $this->displayName;
    $helper->show_toolbar = true;        // false -> remove toolbar
    $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
    $helper->submit_action = 'submit'.$this->name;
    $helper->toolbar_btn = array(
        'save' =>
        array(
            'desc' => $this->l('Save'),
            'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
            '&token='.Tools::getAdminTokenLite('AdminModules'),
        ),
        'back' => array(
            'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
            'desc' => $this->l('Back to list')
        )
    );
     
    // Load current value
    $helper->fields_value['klavyio_API_KEY'] = Configuration::get('klavyio_API_KEY');
	
	
	
     
    return $helper->generateForm($fields_form);
    } 
  
    public function hookDisplayFooter($params)
	{
		  			  
	    $this->context->smarty->assign(
      array(	      
		  'API_KEY'=>Configuration::get('klavyio_API_KEY'),	          
            )
            );  		  
      return $this->display(__FILE__, 'hook_footer.tpl');		
		
	} 
  
  
  
  
  
  }

