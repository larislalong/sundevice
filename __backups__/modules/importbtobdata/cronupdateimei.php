<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (!defined('_PS_ADMIN_DIR_')) {
    define('_PS_ADMIN_DIR_', 'admin445664mnc');
}

if (!defined('PS_ADMIN_DIR')) {
    define('PS_ADMIN_DIR', _PS_ADMIN_DIR_);
}
require(dirname(__FILE__).'/../../config/config.inc.php');
/*
$username = '2F2ZXByb2Rsb2dpbnNoaXBwbWVudAZA';
$password = 'GFzc3dvcmRmb3JzYXZlc2hpcHBtZW50';
$id_order = 'UBDZHNGYH';
//$id_order = 58;
$url = 'https://api.next-wireless.co/rest/b2b/orders/' . $id_order . '/imeis/';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
$result = curl_exec($ch);
curl_close($ch);  
var_dump($result);
$data = json_decode($result, false, 512, JSON_BIGINT_AS_STRING);
$tabkidp = array();
foreach($data->imeis as $value){
    //on construit le tableau des idp couplé au imei
    $tabkidp[$value->idp][] = $value->imei;
}
var_dump($tabkidp);*/
Module::getInstanceByName('importbtobdata')->cronUpdateImei();
