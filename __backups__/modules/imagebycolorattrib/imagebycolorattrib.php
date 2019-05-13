<?php
/**
* 2007-2017 PrestaShop
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
*  @copyright 2007-2017 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

include_once _PS_MODULE_DIR_ . 'imagebycolorattrib/classes/ImageByAttribute.php';
class Imagebycolorattrib extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'imagebycolorattrib';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'FOZEU TAKOUDJOU Francis André';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Image By Color Attribute');
        $this->description = $this->l('This module help you to define image by color attribute and not by declination. And in Front Office, this function override native functionality.  ');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
		$this->imageListTemplate = $this->local_path.'views/templates/admin/image_list.tpl';
    }

    public function install()
    {
		if (! parent::install() ||
                ! $this->registerHook('header') ||
                ! $this->registerHook('backOfficeHeader') ||
                ! $this->registerHook('displayAdminProductsExtra') ||
				! $this->registerHook('actionObjectCombinationAddAfter')) {
            return false;
        }
        require_once(dirname(__FILE__) . '/sql/install.php');
		Tools::clearSmartyCache();
        return true;
    }

    public function uninstall()
    {
        if (! parent::uninstall()) {
            return false;
        }
        require_once(dirname(__FILE__) . '/sql/uninstall.php');
		Tools::clearSmartyCache();
        return true;
    }

    public function getContent()
    {
        if (((bool)Tools::isSubmit('submitImagebycolorattribModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        return $this->renderForm();
    }

    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitImagebycolorattribModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }
	
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
                        'name' => 'ID_GROUP_COLOR',
                        'label' => $this->l('ID Group color'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    protected function getConfigFormValues()
    {
        return array(
            'ID_GROUP_COLOR' => Configuration::get('ID_GROUP_COLOR'),
        );
    }

    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();
		foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
		Tools::redirectAdmin($this->getModuleHome());
    }

    public function hookBackOfficeHeader()
    {
        if ((Tools::getValue('controller') == 'AdminProducts')&&Tools::getValue('id_product')) {
            $this->context->controller->addJS($this->_path.'views/js/product_attribute_image.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }
	
	public function getModuleHome()
    {
        return $this->context->link->getAdminLink('AdminModules') . '&configure=' . $this->name . '&module_name=' . $this->name;
    }

    public function hookDisplayAdminProductsExtra()
    {
        $idProduct = (int)Tools::getValue('id_product');
		if($idProduct){
			$showImagesInList = true;
			$list = ImageByAttribute::getCompleteList($idProduct, $this->context->language->id, $showImagesInList);
			$this->context->smarty->assign(
				array(
					'idProduct' => $idProduct,
					'showImagesInList' => $showImagesInList,
					'moduleLink' => $this->getModuleHome(),
					'imageListTemplate' => $this->imageListTemplate,
					'attributesList' => $list
				)
			);
			$this->assignImageType();
			return $this->context->smarty->fetch($this->local_path.'views/templates/admin/product_attribute_image.tpl');
		}else{
			return $this->displayWarning($this->l('You must save this product before updating colors images.'));
		}
    }
	public function assignImageType()
    {
        $type = ImageType::getByNameNType('%', 'products', 'height');
		if (isset($type['name'])) {
			$this->context->smarty->assign('imageType', $type['name']);
		} else {
			$this->context->smarty->assign('imageType', ImageType::getFormatedName('small'));
		}
    }
	public function hookActionObjectCombinationAddAfter($params)
    {
		$attributes = Tools::getValue('attribute_combination_list');
		$images = ImageByAttribute::getCombinationImages($params['object']->id, $attributes);
		foreach($images as $image){
			ImageByAttribute::updateCombinationImages($params['object']->id_product, $image['id_attribute'], $params['object']->id, $image['id_image']);
		}
    }
	
	public function ajaxProcessGetEditionForm()
    {
		$idProduct = (int)Tools::getValue('id_product');
		$idAttribute = (int)Tools::getValue('id_attribute');
	    $productImages = Image::getImages($this->context->language->id, $idProduct);
		$attributeImages = ImageByAttribute::getAll($idProduct, $idAttribute);
		$imagesAdded = array();
		$attribute = new Attribute($idAttribute, $this->context->language->id);
        foreach ($attributeImages as $key => $value) {
            $imagesAdded[] = $value['id_image'];
			$obj = new Image($value['id_image'], $this->context->language->id);
            $attributeImages[$key]['isSelected'] = true;
            $attributeImages[$key]['legend'] = $obj->legend;
            $attributeImages[$key]['obj'] = $obj;
        }
		foreach ($productImages as $value) {
            if (!in_array($value['id_image'], $imagesAdded)) {
				$obj = new Image($value['id_image'], $this->context->language->id);
                $attributeImages[] = array(
                    'obj'=>$obj,
                    'id_image'=>$value['id_image'],
                    'legend'=>$value['legend'],
                    'isSelected'=>false
                );
            }
        }
		$this->assignImageType();
		$this->context->smarty->assign(
			array(
				'idAttribute' => $idAttribute,
				'images' => $attributeImages,
				'attributeName' => $attribute->name,
			)
		);
		$form = $this->context->smarty->fetch($this->local_path.'views/templates/admin/product_attribute_image_edition.tpl');
		die(Tools::jsonEncode(
			array(
				'hasError' => false,
				'form' => $form
			)
		));
    }
	
	public function ajaxProcessSaveImages()
    {
		$idProduct = (int)Tools::getValue('id_product');
		$idAttribute = (int)Tools::getValue('id_attribute');
        $imagesString = Tools::getValue('images');
		$list = explode(',', trim($imagesString));
		$images = array();
		foreach($list as $key => $image){
			if(!empty($image)){
				$images[]=$image;
			}
		}
		ImageByAttribute::addNew($idProduct, $idAttribute, $images);
		$this->assignImageType();
		$this->context->smarty->assign('images', ImageByAttribute::formatImageList($images, $this->context->language->id));
		$content = $this->context->smarty->fetch($this->imageListTemplate);
		Tools::clearSmartyCache();
		die(Tools::jsonEncode(
			array(
				'hasError' => false,
				'message' => $this->l('Save successfully'),
				'imageCount' => count($images),
				'imageListContent' => $content
			)
		));
    }
}
