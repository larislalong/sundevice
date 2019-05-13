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

include(_PS_MODULE_DIR_ . 'paypalwithfee' . DIRECTORY_SEPARATOR . 'api/Paypalwf.php');

class PayPalwithFeeValidationModuleFrontController extends ModuleFrontController {

    public $ssl = true;
    public $display_column_left = false;

    public function initContent() {
        parent::initContent();
        $params = array();
        $cart = $this->context->cart;
        $customer = new Customer($cart->id_customer);

        if (!Validate::isLoadedObject($customer))
            Tools::redirect('index.php?controller=order&step=1');

        $paypal = new Paypalwf();
        $paypalwithfee = new Paypalwithfee();
        $response = $paypal->request('GetExpressCheckoutDetails', array(
            'TOKEN' => Tools::getValue('token'),
        ));

        if (!$response) {
            $params['error'] = 'Paymentprocess';
            $this->context->smarty->assign(
                    array(
                        'error_paypal' => $paypal->errors,
                        'this_path' => $this->module->getPathUri(),
                        'this_path_check' => $this->module->getPathUri(),
                        'this_path_ssl' => Tools::getShopDomainSsl(true, true) . __PS_BASE_URI__ . 'modules/' . $this->module->name . '/'
            ));
            $paypal->logError($this->context->cart, $params, $paypal->errors);
            return $this->setTemplate('module:paypalwithfee/views/templates/front/error.tpl');
        }

        $num_products = count(preg_grep("/^L_PAYMENTREQUEST_0_NAME(\d)+$/", array_keys($response)));

        $params = array(
            'TOKEN' => Tools::getValue('token'),
            'PAYERID' => Tools::getValue('PayerID'),
            'PAYMENTREQUEST_0_AMT' => $response['PAYMENTREQUEST_0_AMT'],
            'PAYMENTACTION' => 'Sale',
            'PAYMENTREQUEST_0_CURRENCYCODE' => $response['PAYMENTREQUEST_0_CURRENCYCODE'],
            'PAYMENTREQUEST_0_SHIPPINGAMT' => $response['PAYMENTREQUEST_0_SHIPPINGAMT'],
            'PAYMENTREQUEST_0_ITEMAMT' => $response['PAYMENTREQUEST_0_ITEMAMT']
        );

        for ($i = 0; $i < $num_products; $i++) {
            $params['L_PAYMENTREQUEST_0_NAME' . $i] = $response['L_PAYMENTREQUEST_0_NAME' . $i];
            $params['L_PAYMENTREQUEST_0_DESC' . $i] = $response['L_PAYMENTREQUEST_0_DESC' . $i];
            $params['L_PAYMENTREQUEST_0_AMT' . $i] = $response['L_PAYMENTREQUEST_0_AMT' . $i];
            $params['L_PAYMENTREQUEST_0_QTY' . $i] = $response['L_PAYMENTREQUEST_0_QTY' . $i];
        }

        $response = $paypal->request('DoExpressCheckoutPayment', $params);

        if ($response) {
            if ($response['ACK'] == 'Success') {
                
                $transaction_id = $response['PAYMENTINFO_0_TRANSACTIONID'];
                $payment_status = $response['PAYMENTINFO_0_PAYMENTSTATUS'];

                if ($payment_status == 'Completed') {
                    $status_payment = _PS_OS_PAYMENT_;
                } else {
                    $status_payment = _PS_OS_PAYPAL_;
                }

                $currency = $this->context->currency;
                $total = (float) $cart->getOrderTotal(true, Cart::BOTH);
                $total_paypal = $response['PAYMENTINFO_0_AMT'];

                $mailVars = array(
                    '{fee}' => $paypalwithfee->getFee($cart),
                );

                $paypalwithfee->validateOrder4webs($cart->id, $status_payment, $total, $this->module->displayName, $transaction_id,NULL, $mailVars, (int) $currency->id, false, $customer->secure_key,$total_paypal);
                Tools::redirect('index.php?controller=order-confirmation&id_cart=' . $cart->id . '&id_module=' . $paypalwithfee->id . '&id_order=' . $paypalwithfee->currentOrder . '&key=' . $customer->secure_key);
            }
        } else {
            $paypal->logError($this->context->cart, $params, $paypal->errors);
        }
    }

}
