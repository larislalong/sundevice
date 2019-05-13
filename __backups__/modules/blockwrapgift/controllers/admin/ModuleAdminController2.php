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

class ModuleAdminController2
{
    public $module;

    public $context;
    public $primary;
    public $table;

    public $local_path;
    public function __construct($module, $context, $local_path, $_path)
    {
        $this->module = $module;
        $this->context = $context;
        $this->local_path = $local_path;
        $this->_path = $_path;
    }

    public function renderForm($form, $values, $submitAction, $table, $identifier, $removeTagForm, $id = 0)
    {
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $table;
        $helper->identifier = $identifier;
        $helper->id = $id;
        $helper->module = $this->module;
        $helper->back_url = $this->module->getModuleHome();
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->submit_action = $submitAction;
        $helper->currentIndex = '';
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        
        $helper->tpl_vars = array(
            'fields_value' => $values,
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );
        $formContent = $helper->generateForm(array(
            $form
        ));
        if ($removeTagForm) {
            $formContent = $this->removeFormTag($formContent);
        }
        return $formContent;
    }

    public function removeFormTag($form)
    {
        $positionFormTagOpen = strpos($form, '<form');
        $positionFormTagOpen2 = strpos($form, '>', $positionFormTagOpen);
        $strFormTag = Tools::substr($form, $positionFormTagOpen, $positionFormTagOpen2 + 1);
        $form = str_replace(array(
            $strFormTag,
            '</form>'
        ), '', $form);
        return $form;
    }
	
	public function getUpdateFormAction($id)
    {
        return $this->module->getModuleHome() . '&update' . $this->table . '&' .
                 $this->primary . '=' . $id;
    }

    public function getAddFormAction()
    {
        return $this->module->getModuleHome() . '&action=add' . $this->table;
    }
}
