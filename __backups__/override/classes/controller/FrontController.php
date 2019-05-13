<?php
class FrontController extends FrontControllerCore{
    
    public function initContent()
    {
        $this->process();
        if (!isset($this->context->cart)) {
            $this->context->cart = new Cart();
        }
        if (!$this->useMobileTheme()) {
            $this->context->smarty->assign(array(
                'HOOK_HEADER'       => Hook::exec('displayHeader'),
                'HOOK_TOP'          => ($this->context->customer->isLogged() ? Hook::exec('displayTop') : ''),
                'HOOK_LEFT_COLUMN'  => ($this->display_column_left  ? Hook::exec('displayLeftColumn') : ''),
                'HOOK_RIGHT_COLUMN' => ($this->display_column_right ? Hook::exec('displayRightColumn', array('cart' => $this->context->cart)) : ''),
            ));
        } else {
            $this->context->smarty->assign('HOOK_MOBILE_HEADER', Hook::exec('displayMobileHeader'));
        }
		
		
		$featuredCatIds = array(14,15,16,17);
		if(isset($featuredCatIds) and !empty($featuredCatIds)){
			$featuredCatObts = array();
			foreach($featuredCatIds as $cid){
				$currentCatObj = new Category($cid);
				// var_dump($currentCatObj);die;
				if(Validate::isLoadedObject($currentCatObj)){
					$featuredCatObts[$currentCatObj->id] = array(
						'link' => Tools::safeOutput($currentCatObj->getLink()),
						'name' => $currentCatObj->name[$this->context->language->id],
						'image' => Tools::safeOutput($this->context->link->getCatImageLink($currentCatObj->link_rewrite[$this->context->language->id], $currentCatObj->id, 'medium_default')),
						// 'image' => Tools::safeOutput($this->context->link->getMediaLink(_THEME_CAT_DIR_.$currentCatObj->id.'-_thumb.jpg')),
					);
				}
			}
			$this->context->smarty->assign('featuredCatObts', $featuredCatObts);
		}
    }
	
	public function setMedia()
    {
		// die(_THEME_CSS_DIR_.'font-awesome/font-awesome.css');
        parent::setMedia(); // This will take all code in setMedia() of the original classes/controller/FrontController.php
        $this->addCSS(_THEME_CSS_DIR_.'font-awesome/font-awesome.css');
        $this->addCSS(_THEME_CSS_DIR_.'font-awesome/font-awesome-ie7.css');
    }
}
