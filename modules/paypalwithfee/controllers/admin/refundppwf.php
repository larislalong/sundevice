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

class RefundppwfController extends ModuleAdminController {

    public $currency;
    public $decimals;
    public $php_self = 'refundppwf';
    public $ssl = true;

    public function __construct() {
        parent::__construct();
    }

    public function init() {
        parent::init();

        if (Tools::isSubmit('ppwf_refund')) {
            $id_order = Tools::getValue('id_order');
            $order = new Order($id_order);
            $currency = new Currency((int) $order->id_currency);

            $currency_decimals = is_array($currency) ? (int) $currency['decimals'] : (int) $currency->decimals;
            $this->decimals = $currency_decimals * _PS_PRICE_DISPLAY_PRECISION_;

            if ($this->decimals > 2)
                $this->decimals = 2;

            $transaction_id = PaypalRefund::getTransactionID($id_order);

            $refund_type = Tools::getValue('refund');
            if ($refund_type == 0)
                $amount_init = Tools::getValue('ppwf_refund_amount');

            if (empty($transaction_id) || $transaction_id == '-') {
                Tools::redirectAdmin('index.php?tab=AdminOrders&id_order=' . $id_order . '&messageppwf=ppwf1&vieworder' . '&token=' . Tools::getAdminTokenLite('AdminOrders') . '#paypalwithfee_refund');
            }
            if (isset($amount_init)) {
                if (!preg_match('/-?^[0-9]{1,10}+(?:\.[0-9]{1,2})?$/', $amount_init) && $refund_type != 1) {
                    Tools::redirectAdmin('index.php?tab=AdminOrders&id_order=' . $id_order . '&messageppwf=ppwf2&vieworder' . '&token=' . Tools::getAdminTokenLite('AdminOrders') . '#paypalwithfee_refund');
                } else {
                    if ($refund_type == 0)
                        $amount = number_format(Tools::convertPrice($amount_init), $this->decimals);
                }
            }

            $refund_type = $refund_type == 1 ? 'Full' : 'Partial';
            $user = Configuration::get('PPAL_FEE_USER');
            $password = Configuration::get('PPAL_FEE_PASS');
            $signature = Configuration::get('PPAL_FEE_SIGNATURE');

            $paypal = new Paypalwf($user, $password, $signature);

            if (Tools::getValue('refund') == 1) {
                $params = array(
                    'TRANSACTIONID' => $transaction_id,
                    'REFUNDTYPE' => $refund_type,
                    'CURRENCYCODE' => $currency->iso_code,
                );
            } else {
                $params = array(
                    'TRANSACTIONID' => $transaction_id,
                    'REFUNDTYPE' => $refund_type,
                    'CURRENCYCODE' => $currency->iso_code,
                    'AMT' => $amount,
                );
            }
            $response = $paypal->request('RefundTransaction', $params);
            if ($response) {
                $paypal_refund = new PaypalRefund();
                $paypal_refund->id_ppwf = PaypalRefund::getPpwfID($id_order);
                $paypal_refund->id_order = $id_order;
                $paypal_refund->amount = $refund_type == 'Full' ? $order->getTotalPaid() : $amount;
                $paypal_refund->transaction_id = $response['REFUNDTRANSACTIONID'];
                $paypal_refund->date = date('Y-m-d H:i:s');
                $paypal_refund->add();
                Tools::redirectAdmin('index.php?tab=AdminOrders&id_order=' . $id_order . '&messageppwf=ok&vieworder' . '&token=' . Tools::getAdminTokenLite('AdminOrders') . '#paypalwithfee_refund');
            } else {
                $paypal->logError($id_order, $params, $paypal->errors);
                Tools::redirectAdmin('index.php?tab=AdminOrders&id_order=' . $id_order . '&messageppwf=' . $paypal->errors['L_ERRORCODE0'] . '&vieworder' . '&token=' . Tools::getAdminTokenLite('AdminOrders') . '#paypalwithfee_refund');
            }
        } else {
            Tools::redirectAdmin('AdminDashboard');
        }
    }

}
