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

class Paypalwf {

    private $user;
    private $pwd;
    private $signature;
    private $endpoint;
    public $errors = array();

    public function __construct($user = false, $pwd = false, $signature = false) {
        if ($user) {
            $this->user = $user;
        } else {
            $this->user = Configuration::get('PPAL_FEE_USER');
        }
        if ($pwd) {
            $this->pwd = $pwd;
        } else {
            $this->pwd = Configuration::get('PPAL_FEE_PASS');
        }

        if ($signature) {
            $this->signature = $signature;
        } else {
            $this->signature = Configuration::get('PPAL_FEE_SIGNATURE');
        }
    }

    public function request($method, $params) {

        $params = array_merge($params, array(
            'METHOD' => $method,
            'VERSION' => '204.0',
            'USER' => $this->user,
            'PWD' => $this->pwd,
            'SIGNATURE' => $this->signature
        ));

        $params = http_build_query($params, '', '&');


        if (Configuration::get('PPAL_FEE_TEST') == '1')
            $this->endpoint = 'https://api-3T.sandbox.paypal.com/nvp'; 
        else
            $this->endpoint = 'https://api-3T.paypal.com/nvp';


        /*if (!defined('CURL_SSLVERSION_TLSv1')) {
            define('CURL_SSLVERSION_TLSv1', 1);
        }*/
        
        if (!defined('CURL_SSLVERSION_TLSv1_2')) {
            define('CURL_SSLVERSION_TLSv1_2', 6);
        }




        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->endpoint,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            //CURLOPT_SSLVERSION => defined(CURL_SSLVERSION_TLSv1) ? CURL_SSLVERSION_TLSv1 : 1,
            CURLOPT_SSLVERSION => defined(CURL_SSLVERSION_TLSv1_2) ? CURL_SSLVERSION_TLSv1_2 : 6,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_VERBOSE => 1
        ));

        $response = curl_exec($curl);
        $responseArray = array();
        parse_str($response, $responseArray);
        if (curl_errno($curl)) {
            $this->errors = curl_error($curl);
            curl_close($curl);
            return false;
        } else {
            if ($responseArray['ACK'] == 'Success') {
                curl_close($curl);
                return $responseArray;
            } else {
                $this->errors = $responseArray;
                curl_close($curl);
                return false;
            }
        }
    }

    public function logError($cart, $paypal_params, $paypal_error) {

        if (is_object($cart))
            $log_name = date('y_m_d_h_i_s') . $cart->id;
        else
            $log_name = date('y_m_d_h_i_s') . $cart;


        $log_file = _PS_MODULE_DIR_ . 'paypalwithfee/log/log_' . $log_name . '.log';
        $handle = fopen($log_file, 'w') or die('Cannot open file:  ' . $log_file);

        fwrite($handle, print_r($paypal_error, true));
        fwrite($handle, print_r($paypal_params, true));
        if (is_object($cart))
            fwrite($handle, print_r($cart, true));
        fclose($handle);
    }

}
