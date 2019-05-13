<?php

// Security
if (!defined('_PS_VERSION_'))
	exit;

// Checking compatibility with older PrestaShop and fixing it
if (!defined('_MYSQL_ENGINE_'))
	define('_MYSQL_ENGINE_', 'MyISAM');

// Loading Models
require_once(_PS_MODULE_DIR_ . 'posslideshow/models/Nivoslideshow.php');
class Posslideshow extends Module {
    private $_html = '';
    private $_postErrors = array();
        
    public function __construct() {
        $this->name = 'posslideshow';
        $this->tab = 'front_office_features';
        $this->version = '1.0';
        $this->author = 'posthemes';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.6');

        parent::__construct();

        $this->displayName = $this->l('Pos Slideshow');
        $this->description = $this->l('block config');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
	$this->admin_tpl_path 	= _PS_MODULE_DIR_.$this->name.'/views/templates/admin/';
        if (!Configuration::get('POSSLIDESHOW'))
            $this->warning = $this->l('No name provided');
    }

   

    public function install()
	{
        
        //create folder blocklogo
        $path = _PS_IMG_DIR_.'blockslideshow';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }  
        
        // Install SQL
		include(dirname(__FILE__).'/sql/install.php');
		foreach ($sql as $s)
			if (!Db::getInstance()->execute($s))
				return false;
        
          // Install Tabs
		if(!(int)Tab::getIdFromClassName('AdminPosMenu')) {
                    $parent_tab = new Tab();
                    // Need a foreach for the language
                    $parent_tab->name[$this->context->language->id] = $this->l('PosExtentions');
                    $parent_tab->class_name = 'AdminPosMenu';
                    $parent_tab->id_parent = 0; // Home tab
                    $parent_tab->module = $this->name;
                    $parent_tab->add();
                }
		
		
		
		$tab = new Tab();		
		// Need a foreach for the language
		$tab->name[$this->context->language->id] = $this->l('Manage Slideshow');
		$tab->class_name = 'AdminPosslideshow';
		$tab->id_parent = (int)Tab::getIdFromClassName('AdminPosMenu');
		$tab->module = $this->name;
		$tab->add();
                if(!Configuration::updateValue($this->name.'_enable_md', 1) OR
                    !Configuration::updateValue($this->name.'_animation_type', 'random')OR
                    !Configuration::updateValue($this->name.'_pause_time', 6000) OR
                    !Configuration::updateValue($this->name.'_animation_speed', 600)OR
                    !Configuration::updateValue($this->name.'_qty_item', 8) OR
					!Configuration::updateValue($this->name.'_show_arrow', 0) OR
					!Configuration::updateValue($this->name.'_show_caption', 1) OR
					!Configuration::updateValue($this->name.'_show_navigation', 1) OR
                    !Configuration::updateValue($this->name.'_start_slide', 0)) {return false;}
	
		// Set some defaults
                return parent::install() &&
                 $this->registerHook('actionObjectSlideshowAddAfter') &&
		 $this->_installHookCustomer()&&
		 $this->registerHook('bannerSequence')&&
		 $this->registerHook('displayHeader');
              
	}
        
        public function uninstall() {
            
		Configuration::deleteByName('POSSLIDESHOW');

		// Uninstall Tabs
		//$tab = new Tab((int)Tab::getIdFromClassName('AdminPosMenu'));
		//$tab->delete();
                $tab = new Tab((int)Tab::getIdFromClassName('AdminPosslideshow'));
		$tab->delete();
		Configuration::deleteByName($this->name.'_enable_md');
		Configuration::deleteByName($this->name.'_animation_type');
		Configuration::deleteByName($this->name.'_pause_time');
		Configuration::deleteByName($this->name.'_animation_speed');
		Configuration::deleteByName($this->name.'_qty_item');
                Configuration::deleteByName($this->name.'_show_thumbnail');
		Configuration::deleteByName($this->name.'_show_arrow');
		Configuration::deleteByName($this->name.'_show_caption');
                Configuration::deleteByName($this->name.'_show_navigation');
                Configuration::deleteByName($this->name.'_start_slide');
		//uninstall db
                include(dirname(__FILE__).'/sql/uninstall_sql.php');
		foreach ($sql as $s)
			if (!Db::getInstance()->execute($s))
				return false;
		// Uninstall Module
		if (!parent::uninstall())
			return false;
		// !$this->unregisterHook('actionObjectExampleDataAddAfter')
		return true;
        }

	private function _postValidation()
	{

	}
	
	private function _postProcess()
	{  
		Configuration::updateValue($this->name.'_enable_md', Tools::getValue('enable_md'));
		Configuration::updateValue($this->name.'_animation_type', Tools::getValue('animation_type'));
		Configuration::updateValue($this->name.'_pause_time', Tools::getValue('pause_time'));
		Configuration::updateValue($this->name.'_animation_speed', Tools::getValue('animation_speed')); 
		Configuration::updateValue($this->name.'_qty_item', Tools::getValue('qty_item'));
                Configuration::updateValue($this->name.'_show_thumbnail', Tools::getValue('show_thumbnail'));
		Configuration::updateValue($this->name.'_show_arrow', Tools::getValue('show_arrow'));
		Configuration::updateValue($this->name.'_show_caption', Tools::getValue('show_caption'));
                Configuration::updateValue($this->name.'_show_navigation', Tools::getValue('show_navigation'));
                Configuration::updateValue($this->name.'_start_slide', Tools::getValue('start_slide'));
		
		
		$this->_html .= '<div class="conf confirm">'.$this->l('Settings updated').'</div>';
	}
	
	public function getContent()
	{
		$this->_html .= '<h2>'.$this->displayName.'</h2>';
		
		if (Tools::isSubmit('submit'))
		{			
			$this->_postValidation();
			
			if (!sizeof($this->_postErrors))
				$this->_postProcess();
			else
			{
				foreach ($this->_postErrors AS $err)
				{
					$this->_html .= '<div class="alert error">'.$err.'</div>';
				}
			}
		}
		
		$this->_displayForm();
		
		return $this->_html;
	}
        
        
        public function  getAttrFromImage($image = NULL){
            $doc = new DOMDocument();
            $doc->loadHTML($image);
            $imageTags = $doc->getElementsByTagName('img');
            foreach ($imageTags as $tag) {
                if($tag->getAttribute('src')) {
                    return $tag->getAttribute('src'); 
                    break;
                }
            }
            return NULL;
        }
        
        public function getSlideshow() {
		
                        $id_shop = (int)Context::getContext()->shop->id;
						$id_lang = (int)$this->context->language->id;
                        $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'pos_slideshow` ps'; 
						$sql .= ' LEFT JOIN `'. _DB_PREFIX_ . 'pos_slideshow_lang` psl';
						$sql .= ' ON ps.id_pos_slideshow = psl.id_pos_slideshow';
                        $sql .= ' LEFT JOIN `' . _DB_PREFIX_ . 'pos_slideshow_shop`  pss ';
						$sql .= ' ON ps.id_pos_slideshow = pss.id_pos_slideshow';
						$sql .= ' where pss.`id_shop` ='.$id_shop ;
						$sql .= ' AND psl.`id_lang` ='.$id_lang ;
						$sql .=' AND ps.`active` =1';
						$sql .= ' ORDER BY `porder` ASC';
                        $slides = Db::getInstance()->ExecuteS($sql);
                        
                        if(is_array($slides)){
                            $limit = 0;
                            $arraySlides = array();
							
                            foreach($slides  as $key => $slideArray) {
                                if($limit == Configuration::get($this->name.'_qty_item')) break;
                                $limit ++;
                                 //echo "<pre>"; print_r($slideArray); 
                                $newSlide = array();
                                 foreach($slideArray as $k => $v) {
                                     if($k=='image'){
										 $v = null ; 	
                                         $v = _PS_BASE_URL_.__PS_BASE_URI__.'img/blockslideshow/'.$slideArray['id_pos_slideshow'].'.jpg';
										 if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') { 
												$v = str_replace('http://','https://',$v); 
										} 
                                     }
                                     $newSlide[$k] = $v;
                                 }
                                 $arraySlides[$key] = $newSlide;
                            }

                        }
						//echo "<Pre>"; print_r($arraySlides);
                        return $arraySlides;
        }
        private function _displayForm()
	{ 
            $this->_html .= '
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
                  <fieldset>
                    <legend><img src="../img/admin/cog.gif" alt="" class="middle" />' . $this->l('Settings') . '</legend>
                    <label>' . $this->l('Transition Type') . '</label>
                    <div class="margin-form">
                        <select name ="animation_type">';
                        $animationCurrent = Tools::getValue('animation_type');
	                foreach($this->getTransitionTypeArray() as $key => $val){
                            if($animationCurrent == $key) { 
                                $this->_html.='<option value='.$key.' selected="selected" > '.$val.'</option>';
                            }else {
                                 $this->_html.='<option value='.$key.'>'.$val.'</option>';
                            }
                        }
                 $this->_html.='</select>
                    </div>
                    <label>'.$this->l('Pause Time: ').'</label>
                    <div class="margin-form">
                            <input type = "text"  name="pause_time" value ='. (Tools::getValue('pause_time')?Tools::getValue('pause_time'): Configuration::get($this->name.'_pause_time')).' ></input>
                    </div>
                    <label>'.$this->l('Start from slide: ').'</label>
                    <div class="margin-form">
                            <input type = "text"  name="start_slide" value ='.(Tools::getValue('start_slide')?Tools::getValue('start_slide'): Configuration::get($this->name.'_start_slide')).' ></input>
                    </div>
                     <label>'.$this->l('Animation Speed: ').'</label>
                    <div class="margin-form">
                            <input type = "text"  name="animation_speed" value ='.(Tools::getValue('animation_speed')?Tools::getValue('animation_speed'): Configuration::get($this->name.'_animation_speed')).' ></input>
                    </div>
                    <label>'.$this->l('Qty of items: ').'</label>
                    <div class="margin-form">
                            <input type = "text"  name="qty_item" value ='.(Tools::getValue('qty_item')?Tools::getValue('qty_item'): Configuration::get($this->name.'_qty_item')).' ></input>
                    </div>
                    <label>'.$this->l('Show Caption: ').'</label>
                    <div class="margin-form">';
                       $this->_html .= $this->getSelectOptionsHtml(array(0=>'No',1=>'Yes'),'show_caption', (Tools::getValue('title') ? Tools::getValue('show_caption') : Configuration::get($this->name . '_show_caption')));
                       $this->_html .='
                    </div>
                    <label>'.$this->l('Show Next/Back: ').'</label>
                    <div class="margin-form">';
                       $this->_html .= $this->getSelectOptionsHtml(array(0=>'No',1=>'Yes'),'show_arrow',  (Tools::getValue('title') ? Tools::getValue('show_arrow') : Configuration::get($this->name . '_show_arrow')));
                       $this->_html .='
                    </div>
                     <label>'.$this->l('Show navigation control: ').'</label>
                    <div class="margin-form">';
                       $this->_html .= $this->getSelectOptionsHtml(array(0=>'No',1=>'Yes'),'show_navigation', (Tools::getValue('title') ? Tools::getValue('show_navigation') : Configuration::get($this->name . '_show_navigation')));
                       $this->_html .='
                    </div>
                    <input type="submit" name="submit" value="'.$this->l('Update').'" class="button" />
                     </fieldset>
		</form>';
	}
        public function getSelectOptionsHtml($options = NULL, $name = NULL, $selected = NULL) {
            $html = "";
            $html .='<select name ='.$name.'>';
            if(count($options)>0) {
                foreach($options as $key => $val) {
                    if(trim($key) == trim($selected)) {
                        $html .='<option value='.$key.' selected="selected">'.$val.'</option>';
                    } else {
                        $html .='<option value='.$key.'>'.$val.'</option>';
                    }
                }
            }
            $html .= '</select>';
            return $html;
        }
        
        public function getTransitionTypeArray() {
            return array(
                "random" => "random",   
                "sliceDown" => "sliceDown",
                "sliceDownLeft" => "sliceDownLeft",
                "sliceUp" => "sliceUp",
                "sliceUpLeft" => "sliceUpLeft",
                "sliceUpDown" => "sliceUpDown",
                "sliceUpDownLeft" => "sliceUpDownLeft",
                "fold" => "fold",
                "fade" => "fade",
                "slideInRight" => "slideInRight",
                "slideInLeft" => "slideInLeft",
                "boxRandom" => "boxRandom",
                "boxRain" => "boxRain",
                "boxRainReverse" => "boxRainReverse",
                "boxRainGrow" => "boxRainGrow",
                "boxRainGrowReverse" => "boxRainGrowReverse",
            );
        }
        
        public function hookDisplayHeader()
        { 
			$options = array(
                'pause_time' => Configuration::get($this->name.'_pause_time'),
            );
            $this->context->smarty->assign('slideOptions', $options);
			$this->context->controller->addCSS($this->_path.'css/nivo-slider/nivo-slider.css');
			$this->context->controller->addJS($this->_path.'js/nivo-slider/jquery.nivo.slider.js');
			 return $this->display(__FILE__, 'pie-timer.tpl');
        }
	function hookbannerSequence($params)
	{ 
		 $options = array(
                'enable_md' => Configuration::get($this->name.'_enable_md'),
                'animation_type' => Configuration::get($this->name.'_animation_type'),
                'pause_time' => Configuration::get($this->name.'_pause_time'),
                'animation_speed' => Configuration::get($this->name.'_animation_speed'),
                'qty_item' => Configuration::get($this->name.'_qty_item'),
                'show_thumbnail' => Configuration::get($this->name.'_show_thumbnail'),
                'show_caption' => Configuration::get($this->name.'_show_caption'),
                'show_arrow' => Configuration::get($this->name.'_show_arrow'),
                'show_navigation' => Configuration::get($this->name.'_show_navigation'),
                'start_slide' => Configuration::get($this->name.'_start_slide'),

            );

            $slides = $this->getSlideshow();
            $this->context->smarty->assign('slideOptions', $options);
            $this->context->smarty->assign('slides', $slides);
            return $this->display(__FILE__, 'slideshow.tpl');
	}
	
        public function hookActionObjectSlideshowAddAfter($params) {
	
		return true;
	}
	
	private function _installHookCustomer(){
		$hookspos = array(
				'banner7Home',
			); 
		foreach( $hookspos as $hook ){
			if( Hook::getIdByName($hook) ){
				
			} else {
				$new_hook = new Hook();
				$new_hook->name = pSQL($hook);
				$new_hook->title = pSQL($hook);
				$new_hook->add();
				$id_hook = $new_hook->id;
			}
		}
		return true;
	}

}