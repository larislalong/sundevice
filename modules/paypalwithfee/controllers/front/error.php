<?php
/**
 * 2014 4webs
 *
 * DEVELOPED By 4webs.es Prestashop Platinum Partner
 *
 * @author    4webs
 * @copyright 4webs 2014
 * @license   4webs
 * @version 4.0.2
 * @category payment_gateways
 */

class PayPalwithFeeErrorModuleFrontController extends ModuleFrontController {

    public function initContent() {
        parent::initContent();

        $error = Tools::getValue('error');

        $this->context->smarty->assign(
                array(
                    'error_paypal' => $error
        ));
        $this->setTemplate('error.tpl');
    }

}
