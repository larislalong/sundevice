<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

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
require(dirname(__FILE__).'/config/config.inc.php');

Module::getInstanceByName('importbtobdata')->cronImport();

die('Import Terminé');
