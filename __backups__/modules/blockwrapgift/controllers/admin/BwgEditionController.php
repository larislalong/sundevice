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

include_once _PS_MODULE_DIR_ . 'blockwrapgift/classes/BwgWrapGift.php';
include_once _PS_MODULE_DIR_ . 'blockwrapgift/classes/BwgTools.php';
class BwgEditionController extends ModuleAdminController2
{
	public function __construct($module, $context, $local_path, $_path)
    {
        parent::__construct($module, $context, $local_path, $_path);
		$this->primary = BwgWrapGift::$definition['primary'];
		$this->table = BwgWrapGift::$definition['table'];
		$this->langFields = array('name', 'description');
		
    }
	public function getFormContent($updateForm = false)
    {
        $languages =$this->context->controller->getLanguages();
        $output = '';
        
        $formSubmitted = (bool) Tools::isSubmit('submitWrapGiftSave');
        $formAction = '';
        // If form is submitted
        $informationsValues = $this->getFormPostedValues($languages, $formSubmitted);
        $idLang = $this->context->language->id;
        $errors = array();
        $idWrapGift = (int) Tools::getValue(BwgWrapGift::$definition['primary'], 0);
		$image='';
        if ($formSubmitted == true) {
            $informationsSaveResult = $this->processSave($informationsValues, $idWrapGift);
            $errors = $informationsSaveResult['errors'];
            if (! empty($errors)) {
                $output .= $this->module->displayError($errors);
            } else {
                $successParam = '&bwg_success=' .
                (($idWrapGift > 0) ? BwgWrapGift::UPDATE_SUCCESS_CODE : BwgWrapGift::ADD_SUCCESS_CODE);
                $this->module->backToModuleHome($successParam);
            }
        } else {
            if ($updateForm) {
				$wrapGift = new BwgWrapGift((int) $idWrapGift);
                $informationsValues = $this->getSavedValues($wrapGift);
				$image = $wrapGift->image;
            }
        }
        if ($updateForm) {
            $formAction = $this->getUpdateFormAction($idWrapGift);
        } else {
            $formAction = $this->getAddFormAction();
        }
        
        $output .= $this->renderForm(
			$this->getForm($image),
			$informationsValues,
			'submitWrapGiftSave',
			BwgWrapGift::$definition['table'],
			BwgWrapGift::$definition['primary'],
			false,
			$idWrapGift
		);
        return $output;
    }
    public function processSave($values, $id = 0)
    {
        $errors = $this->getValidationErrors($values);
        $object = null;
		if (empty($errors)) {
			if($id){
				$oldObject = new BwgWrapGift($id);
			}
			if(isset($_FILES['image_file']) && !empty($_FILES['image_file']['name'])){
				$result = $this->processUpload('image_file');
				$errors=$result['errors'];
				$values['image'] = $result['fileName'];
			}elseif($id){
				$values['image'] = $oldObject->image;
			}
			
		}
		if (empty($errors)) {
			$object = new BwgWrapGift();
			foreach(BwgWrapGift::$definition['fields'] as $field => $def){
				if(isset($values[$field])){
					$object->{$field} = $values[$field];
				}
			}
			foreach($this->langFields as $field){
				if(isset($values[$field])){
					$object->{$field} = BwgTools::fillMultilangEmptyFields($values[$field]);
				}
			}
            $object->id = (int) $id;
            if (!$object->save()) {
                $errors[] = $this->module->l('An error occurred while saving wrap gift', __CLASS__);
            }else{
				$this->module->clearAllTplCache();
				if(!empty($id) && !empty($object->image) && ($object->image!=$oldObject->image)){
					@unlink($this->local_path . $this->module->wrapGiftImageFolder.$oldObject->image);
				}
			}
        }
        return array(
            'errors' => $errors,
            'object' => $object
        );
    }
	protected function processUpload($field)
    {
		$resultFile = '';
		$errors = array();
		$allowedFileUploadExtension = array('jpeg','jpg','png','gif','svg');
		$ext = Tools::strtolower(pathinfo($_FILES [$field]['name'], PATHINFO_EXTENSION));
		if ($_FILES[$field]['error'] > 0) {
			$errors[] = Tools::displayError('An error occurred during the file upload process.');
		} elseif (! in_array($ext, $allowedFileUploadExtension)) {
			$errors[] = Tools::displayError('This file extension is not allowed.');
		}else {
			$resultFile = md5(uniqid(rand(), true)).'.'.$ext;
			$dirOk = true;
			$str_dir = $this->local_path . $this->module->wrapGiftImageFolder;
			if (! file_exists($str_dir)) {
				$dirOk = mkdir($str_dir, 0777, true);
			}
			if (!$dirOk || !move_uploaded_file($_FILES[$field]['tmp_name'], $str_dir. $resultFile)) {
				$resultFile = '';
				$errors[] = Tools::displayError('An error occurred during the file upload process');
			}
		}
		return array('errors'=>$errors, 'fileName'=>$resultFile);
    }
	public function getForm($image = '')
    {
		$form = array(
            'form' => array(
				'tinymce' => true,
                'legend' => array(
                    'title' => $this->module->l('Configuration', __CLASS__)
                ),
                'input' => array(
					array(
                        'col' => 6,
                        'type' => 'text',
                        'desc' => $this->module->l('Enter a valid name', __CLASS__),
                        'name' => 'name',
                        'label' => $this->module->l('Name', __CLASS__),
                        'required' => true,
                        'lang' => true
                    ),
					array(
                        'col' => 6,
                        'type' => 'text',
                        'label' => $this->module->l('Position', __CLASS__),
                        'name' => 'position'
                    ),
					array(
                        'col' => 6,
                        'type' => 'text',
                        'label' => $this->module->l('Price', __CLASS__),
                        'name' => 'price',
						'prefix' => $this->context->currency->sign
                    ),
					array(
						'type' => 'file',
						'label' => $this->module->l('Image'),
						'name' => 'image_file',
						'display_image' => true,
						'thumb' => empty($image)?'':$this->module->getUrl().$this->module->wrapGiftImageFolder.$image,
						'desc' => sprintf($this->module->l('Maximum image size: %s.'), ini_get('upload_max_filesize'))
					),
					array(
                        'col' => 6,
                        'type' => 'textarea',
                        'label' => $this->module->l('Description', __CLASS__),
                        'lang' => true,
                        'name' => 'description',
                        'rows' => 5,
                        'cols' => 40,
                        'class' => 'rte',
                        'autoload_rte' => true
                    ),
					array(
                        'col' => 6,
                        'type' => 'switch',
                        'label' => $this->module->l('Enabled', __CLASS__),
                        'name' => 'active',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->module->l('Enabled', __CLASS__)
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->module->l('Disabled', __CLASS__)
                            )
                        )
                    )
                ),
                'buttons' => array(
                    array(
                        'href' => $this->module->getModuleHome(),
                        'title' => $this->module->l('Back to list', __CLASS__),
                        'icon' => 'process-icon-back'
                    ),
                    'save' => array(
                        'name' => 'save',
                        'type' => 'submit',
                        'title' => $this->module->l('Save', __CLASS__),
                        'class' => 'btn btn-default pull-right',
                        'icon' => 'process-icon-save'
                    )
                )
            )
        );
        return $form;
    }
	
	public function getValidationErrors($values)
    {
        $errors = array();
        if (BwgTools::isMultilangFieldEmpty($values, 'name')) {
            $errors[] = $this->module->l('Please Enter a wrap name', __CLASS__);
        }
        if (! Validate::isUnsignedInt($values['position'])) {
            $errors[] = $this->module->l('Please enter a valid position', __CLASS__);
        }
        if (! Validate::isPrice($values['price'])) {
            $errors[] = $this->module->l('Please enter a valid price', __CLASS__);
        }
        return $errors;
    }
	
	public function getFormPostedValues($languages, $formSubmitted = false)
    {
        $active = Tools::getValue('active');
		
		$langValues = array();
        if ($formSubmitted) {
			foreach($this->langFields as $field){
				$langValues[$field] = array();
				foreach ($languages as $language) {
					$language = (object) $language;
					if (Tools::getIsset($field . '_' . $language->id_lang)) {
						$langValues[$field][$language->id_lang] = Tools::getValue($field . '_' . $language->id_lang);
					}
				}
			}
           
        } else {
            foreach($this->langFields as $field){
				$langValues[$field] = null;
			}
            $active = true;
        }
		foreach(BwgWrapGift::$definition['fields'] as $field => $def){
			$data[$field] = Tools::getValue($field);
		}
		foreach($langValues as $field => $value){
			$data[$field] = $value;
		}
		$data['active'] = $active;
		return $data;
    }
	
	public function getSavedValues($object)
    {
		$data = array();
		foreach(BwgWrapGift::$definition['fields'] as $field => $def){
			$data[$field] = $object->{$field};
		}
        return $data;
    }
}
