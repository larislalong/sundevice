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

class PayPalwithFeePaymentModuleFrontController extends ModuleFrontController {

    public $php_self = 'paymentppwf';
    public $ssl = true;
    public $display_column_left = false;
    public $currency;
    public $decimals;

    public function initContent() {
        parent::initContent();

        $currency = new Currency((int) $this->context->cart->id_currency);
        $round_active = Configuration::get('PPAL_ROUND_MODE');
        $currency_decimals = is_array($currency) ? (int) $currency['decimals'] : (int) $currency->decimals;
        $this->decimals = $currency_decimals * _PS_PRICE_DISPLAY_PRECISION_;

        if ($this->decimals > 2)
            $this->decimals = 2;
        
        /* processing */
        $is_free = false;
        $gift_fee = array();
        $gift_amount = $this->context->cart->gift;

        if ($gift_amount == 1) {
            $gift_fee['gift_amount'] = Tools::convertPrice(Tools::ps_round($this->context->cart->getGiftWrappingPrice(TRUE), $this->decimals), Currency::getCurrencyInstance((int) $this->context->cookie->id_currency));
            $gift_fee['gift_name'] = $this->module->l('Gift-wrapping', 'payment');
            $gift_fee['gift_description'] = $this->module->l('Gift-wrapping', 'payment');
        } else {
            $gift_fee = 0;
        }

        $discount = array();
        $array_discount = $this->context->cart->getCartRules();
        $position = 0;

        if (count($array_discount) > 0) {
            foreach ($array_discount as $value) {
                $position +=1;
                if ($value['free_shipping'] != 1) {
                    $discount[$position]['total'] = Tools::ps_round($value['value_real'], $this->decimals);
                    $discount[$position]['name'] = $this->module->l('Discount', 'payment');
                    $discount[$position]['desc'] = $value['code'];
                } else {
                    $is_free = true;
                    if ($value['value_real'] > 0) {
                        $discount[$position]['total'] = Tools::ps_round($value['value_real'], $this->decimals);
                        $discount[$position]['name'] = $this->module->l('Discount', 'payment');
                        $discount[$position]['desc'] = $value['code'];
                    }
                }
            }
        }

        //apply amount of products always 2 decimals.
        $products = $this->context->cart->getProducts(true);
        $total_amount = $round_active ? $this->context->cart->getOrderTotal(true, Cart::ONLY_PRODUCTS) : $this->getTotalAmount($products);
        $discountsamt = $this->getTotalDiscounts();
        $shippingamt = Tools::ps_round($this->context->cart->getTotalShippingCost(),$this->decimals);

        if (is_array($gift_fee))
            $total_amount_final = ($total_amount + $shippingamt + $gift_fee['gift_amount']) - $discountsamt;
        else
            $total_amount_final = ($total_amount + $shippingamt) - $discountsamt;

        if ($is_free) {
            $itemamt = $total_amount_final - $shippingamt;
        } else {
            $itemamt = $total_amount_final - $shippingamt;
        }


        $discountme = $discount;

        $user = Configuration::get('PPAL_FEE_USER');
        $password = Configuration::get('PPAL_FEE_PASS');
        $signature = Configuration::get('PPAL_FEE_SIGNATURE');
        $returnURL = Tools::getValue('returnURL');
        $cancelURL = Tools::getValue('cancelURL');
        $currencycode = $this->context->currency->iso_code;
        $fixedfee = Configuration::get('PPAL_FEE_FIXEDFEE');
        $percentage = Configuration::get('PPAL_FEE_PERCENTAGE');

        $fee_amount = Tools::ps_round((($percentage / 100) * $total_amount_final + $fixedfee), $this->decimals);
        $total_amount_with_fee = Tools::ps_round($total_amount_final + $fee_amount, $this->decimals);
        $total_with_fee = Tools::ps_round($itemamt + $fee_amount, $this->decimals);


        $paypal = new Paypalwf($user, $password, $signature);

        //params to paypal
        $params = array(
            'RETURNURL' => $returnURL,
            'CANCELURL' => $cancelURL,
            'PAYMENTREQUEST_0_AMT' => $total_amount_with_fee, //total of order
            'PAYMENTREQUEST_0_CURRENCYCODE' => $currencycode,
            'PAYMENTREQUEST_0_SHIPPINGAMT' => $shippingamt,
            'PAYMENTREQUEST_0_ITEMAMT' => $total_with_fee //total of items   
        );


        if ($fee_amount > 0) {
            $params['L_PAYMENTREQUEST_0_NAME0'] = $this->module->l('Fee', 'payment');
            $params['L_PAYMENTREQUEST_0_DESC0'] = $this->module->l('Paypal Fee', 'payment');
            $params['L_PAYMENTREQUEST_0_AMT0'] = $fee_amount;
            $params['L_PAYMENTREQUEST_0_QTY0'] = "1";

            $i = 1;
        } else {
            $i = 0;
        }

        foreach ($products as $product) {
            $params['L_PAYMENTREQUEST_0_NAME' . $i] = $round_active ? $product['quantity'].' x '.$product['name'] : $product['name'];
            $params['L_PAYMENTREQUEST_0_DESC' . $i] = preg_replace('/[^(\x20-\x7f)]*/s', '', strip_tags(Tools::substr($product['description_short'], 0, 50)) . '...');
            if ($product['quantity'] > 1 && !$round_active)
                $params['L_PAYMENTREQUEST_0_AMT' . $i] = Tools::ps_round($product['price_wt'], $this->decimals);
            else
                $params['L_PAYMENTREQUEST_0_AMT' . $i] = Tools::ps_round($product['total_wt'], $this->decimals);

            if($round_active)
                $params['L_PAYMENTREQUEST_0_QTY' . $i] = 1;
            else    
                $params['L_PAYMENTREQUEST_0_QTY' . $i] = $product['quantity'];
            
            $i += 1;
        }

        if (is_array($discountme) && count($discountme) > 0) {
            foreach ($discountme as $discount) {
                $params['L_PAYMENTREQUEST_0_NAME' . $i] = $discount['name'];
                $params['L_PAYMENTREQUEST_0_DESC' . $i] = $discount['desc'];
                $params['L_PAYMENTREQUEST_0_AMT' . $i] = (-1 * $discount['total']);
                $params['L_PAYMENTREQUEST_0_QTY' . $i] = 1;
                $i += 1;
            }
        }


        if (is_array($gift_fee) && count($gift_fee) > 0) {
            $params['L_PAYMENTREQUEST_0_NAME' . $i] = $gift_fee['gift_name'];
            $params['L_PAYMENTREQUEST_0_DESC' . $i] = preg_replace('/[^(\x20-\x7f)]*/s', '', strip_tags(Tools::substr($gift_fee['gift_description'], 0, 50)) . '...');
            $params['L_PAYMENTREQUEST_0_AMT' . $i] = $gift_fee['gift_amount'];
            $params['L_PAYMENTREQUEST_0_QTY' . $i] = 1;
        }

        $response = $paypal->request('SetExpressCheckout', $params);


        if ($response) {
            $this->redirectPaypal($response['TOKEN']);
        } else {
            $this->context->smarty->assign(
                    array(
                        'error_paypal' => $paypal->errors,
                        'this_path' => $this->module->getPathUri(),
                        'this_path_check' => $this->module->getPathUri(),
                        'this_path_ssl' => Tools::getShopDomainSsl(true, true) . __PS_BASE_URI__ . 'modules/' . $this->module->name . '/'
            ));
            $paypal->logError($this->context->cart, $params, $paypal->errors);
            $this->setTemplate('error.tpl');
        }
    }

    protected function redirectPaypal($token) {
        if (Configuration::get('PPAL_FEE_TEST') == '0')
            $ruta = 'https://www.paypal.com/webscr?cmd=_express-checkout&useraction=commit&token=' . $token;
        else
            $ruta = 'https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&useraction=commit&token=' . $token;

        $this->context->smarty->assign(array(
            'ruta' => $ruta
        ));

        Tools::redirect($ruta);
    }

    public function getTotalAmount($products) {
        $total = 0;
        foreach ($products as $product) {
            $total = $total + (Tools::ps_round($product['price_wt'], $this->decimals) * $product['quantity']);
        }

        return $total;
    }

    public function getTotalDiscounts() {
        $total = 0;
        $discounts = $this->context->cart->getCartRules();
        if (count($discounts) > 0) {
            foreach ($discounts as $discount) {
                $total = $total + Tools::ps_round($discount['value_real'], $this->decimals);
            }
        }

        return $total;
    }

}
