<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * @author    Presta.Site
 * @copyright 2018 Presta.Site
 * @license   LICENSE.txt
 */

class ContactformOverride extends Contactform
{
    public function sendMessage()
    {
        $errors = array();
        if (Module::isEnabled('notarobot')) {
            $nar_module = Module::getInstanceByName('notarobot');
            $nar_error = $nar_module->checkContactForm();
            if ($nar_error) {
                $errors[] = $nar_error;
                $this->context->controller->errors = array_merge($this->context->controller->errors, $errors);
            }
        }

        if (!count($errors)) {
            parent::sendMessage();
        }
    }
}