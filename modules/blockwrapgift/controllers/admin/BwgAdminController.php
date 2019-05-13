<?php
/**
 * 2015-2017 Crystals Services
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
 * needs please refer to http://www.crystals-services.com/ for more information.
 *
 * @author Crystals Services Sarl <contact@crystals-services.com>
 * @copyright 2015-2017 Crystals Services Sarl
 * @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 *          International Registered Trademark & Property of Crystals Services Sarl
 */

include_once _PS_MODULE_DIR_ . 'blockwrapgift/controllers/admin/ModuleAdminController2.php';
include_once _PS_MODULE_DIR_ . 'blockwrapgift/controllers/admin/BwgEditionController.php';
include_once _PS_MODULE_DIR_ . 'blockwrapgift/classes/BwgWrapGift.php';
class BwgAdminController extends ModuleAdminController2
{
	public function renderList()
    {
        $this->fields_list = array(
            BwgWrapGift::$definition['primary'] => array(
                'title' => $this->module->l('ID', __CLASS__),
                'type' => 'text',
                'search' => false,
                'orderby' => false
            ),
            'name' => array(
                'title' => $this->module->l('Name', __CLASS__),
                'type' => 'text',
                'search' => false,
                'orderby' => false
            ),
            'description' => array(
                'title' => $this->module->l('Description', __CLASS__),
				'type' => 'html',
                'search' => false,
                'orderby' => false
            ),
			'position' => array(
				'title' => $this->module->l('Position'),
				'filter_key' => 'sa!position',
				'position' => 'position',
				'align' => 'center',
				'search' => false,
                'orderby' => false,
			    'class' => 'fixed-width-xs'
			),
			'price' => array(
                'title' => $this->module->l('Price', __CLASS__),
                'type' => 'float',
                'search' => false,
                'orderby' => false
            ),
            'image' => array(
				'type' => 'html',
				'class' => 'td_image',
                'title' => $this->module->l('Image', __CLASS__),
                'search' => false,
                'orderby' => false
            ),
            'active' => array(
                'title' => $this->module->l('Status', __CLASS__),
                'active' => 'status',
                'type' => 'bool',
                'class' => 'fixed-width-xs',
                'align' => 'center',
                'search' => false,
                'orderby' => false
            )
        );
        $helper = new HelperList();
		$helper->no_link = true;
		$helper->orderBy= 'position';
		$helper->orderWay= 'ASC';
        $helper->shopLinkType = '';
        $helper->simple_header = false;
        $helper->identifier = BwgWrapGift::$definition['primary'];
        $helper->actions = array(
			'edit',
            'delete'
        );
        $helper->show_toolbar = true;
        $helper->toolbar_scroll = true;
        $helper->imageType = 'jpg';
        $helper->toolbar_btn['new'] = array(
            'href' => $this->module->getModuleHome() . '&action=add' . BwgWrapGift::$definition['table'],
            'desc' => $this->module->l('Add new', __CLASS__)
        );
        $helper->title = $this->module->l('Wrap gifts', __CLASS__);
        $helper->table = BwgWrapGift::$definition['table'];
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = $this->module->getModuleHome();
		$helper->list_id = $helper->identifier;
		$helper->position_identifier = $helper->identifier;
		$helper->position_group_identifier= $helper->position_identifier;
        $idLang = $this->context->language->id;
        
        $list = BwgWrapGift::getAll($idLang);
		$this->context->smarty->assign('image_folder', $this->module->getUrl().$this->module->wrapGiftImageFolder);
        foreach ($list as $key => $value) {
            //$list[$key]['price'] = Tools::displayPrice($value['price'], $this->context->currency);
			if(!empty($value['image'])){
				$list[$key]['image'] = $this->module->getUrl().$this->module->wrapGiftImageFolder.$value['image'];
			}
			//$list[$key]['image'] =  $this->getEditorClean($list[$key]['image']);
			$list[$key]['description'] =  $this->getEditorClean($list[$key]['description']);
        }
        $helper->listTotal = BwgWrapGift::getCount();
        return $helper->generateList($list, $this->fields_list);
    }
	public static function getEditorClean($text)
	{
		return strip_tags(stripslashes($text));
	}
    public function init()
    {
		$this->editionController = new BwgEditionController($this->module, $this->context, $this->local_path, $this->_path);
        $operationContent = '';
        if (Tools::getIsset('bwg_success')) {
            $code = Tools::getValue('bwg_success');
            $message = '';
            switch ($code) {
                case BwgWrapGift::ADD_SUCCESS_CODE:
                    $message = $this->module->l('Wrap gift added successfully', __CLASS__);
                    break;
                case BwgWrapGift::UPDATE_SUCCESS_CODE:
                    $message = $this->module->l('Wrap gift updated successfully', __CLASS__);
                    break;
                case BwgWrapGift::DELETE_SUCCESS_CODE:
                    $message = $this->module->l('Wrap gift deleted successfully', __CLASS__);
                    break;
                case BwgWrapGift::STATUS_CHANGE_SUCCESS_CODE:
                    $message = $this->module->l('Wrap gift status changed successfully', __CLASS__);
                    break;
            }
            if (! empty($message)) {
                $operationContent .= $this->module->displayConfirmation($message);
            }
        }
        if (Tools::getValue('action') == 'add' . BwgWrapGift::$definition['table']) {
            $operationContent .= $this->editionController->getFormContent();
        }elseif (Tools::getIsset('update' . BwgWrapGift::$definition['table'])) {
            $operationContent .= $this->editionController->getFormContent(true);
        } else {
            if (Tools::getIsset('status' . BwgWrapGift::$definition['table'])) {
                $operationContent .= $this->processStatusChange();
            } elseif (Tools::getIsset('delete' . BwgWrapGift::$definition['table'])) {
                $operationContent .= $this->processDelete();
            }
            $operationContent .= $this->renderList();
        }
        return $operationContent;
    }
	public function processStatusChange()
    {
        $errors = array();
        $id = Tools::getValue(BwgWrapGift::$definition['primary']);
        $object = new BwgWrapGift((int) $id);
        if (! $object->toggleStatus()) {
            $errors[] = sprintf(
                $this->module->l('An error occured while changing main menu %s status', __CLASS__),
                $object->name[$this->context->language->id]
            );
        } else {
			$this->module->clearAllTplCache();
            $this->module->backToModuleHome('&bwg_success=' . BwgWrapGift::STATUS_CHANGE_SUCCESS_CODE);
        }
        return $this->module->displayError($errors);
    }

    public function processDelete()
    {
        $errors = array();
        $id = Tools::getValue(BwgWrapGift::$definition['primary']);
        $object = new BwgWrapGift((int) $id);
        if (! $object->delete()) {
            $errors[] = sprintf(
                $this->module->l('An error occured while deleting main menu %s', __CLASS__),
                $object->name[$this->context->language->id]
            );
        } else {
			$this->module->clearAllTplCache();
			if(!empty($object->image)){
				@unlink($this->local_path . $this->module->wrapGiftImageFolder.$object->image);
			}
            $this->module->backToModuleHome('&bwg_success=' . BwgWrapGift::DELETE_SUCCESS_CODE);
        }
        return $this->module->displayError($errors);
    }
    public function includeBOCssJs()
    {
        $isModulePage = ((Tools::getValue('module_name') == $this->module->name) ||
        (Tools::getValue('configure') == $this->module->name));
        $anchor = Tools::getValue('anchor');
        if ($isModulePage && empty($anchor)) {
            $this->context->controller->addJquery();
            $this->context->controller->addJS($this->_path . 'views/js/back.js');
			$this->context->controller->addCSS($this->_path . 'views/css/back.css');
        }
    }
	
	public function ajaxProcessUpdatePositions()
    {
        $way = (int)Tools::getValue('way');
        $positions = Tools::getValue('bwg_wrap_gift');
        
        $new_positions = array();
        foreach ($positions as $k => $v) {
            if (count(explode('_', $v)) == 7) {
                $new_positions[] = $v;
            }
        }
        $hasError = false;
        $successMessage = '';
        $errorsMessage = '';
        foreach ($new_positions as $position => $value) {
            $pos = explode('_', $value);
            $id = (int)$pos[5];
            if (isset($position) && BwgWrapGift::updatePosition($id, $position)) {
                $successMessage.='ok position '.(int)$position.' for attribute group '.$id.'\r\n';
            } else {
                $hasError = true;
                $errorsMessage.='Can not update the '.$id.' bwg_wrap_gift to position '.(int)$position;
            }
        }
        $message = $successMessage;
        if($hasError){
            $message = '{"hasError" : true, "errors" : "'.$errorsMessage.'"}';
        }
		$this->module->clearAllTplCache();
        die($message);
    }
}
