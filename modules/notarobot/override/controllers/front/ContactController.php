<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * @author    Presta.Site
 * @copyright 2017 Presta.Site
 * @license   LICENSE.txt
 */

class ContactController extends ContactControllerCore
{
    public function postProcess()
    {
        if (Tools::isSubmit('submitMessage')
            && Module::isEnabled('notarobot')
            && version_compare(_PS_VERSION_, '1.7.4.0', '<')
        ) {
            $nar_module = Module::getInstanceByName('notarobot');
            $nar_error = $nar_module->checkContactForm();
            if ($nar_error) {
                $this->errors[] = $nar_error;
            }
        }
        if (!count($this->errors)) {
            parent::postProcess();
        }
    }
}
