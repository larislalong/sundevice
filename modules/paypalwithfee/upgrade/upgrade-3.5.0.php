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

if (!defined('_PS_VERSION_'))
    exit;

function upgrade_module_3_5_0($object) {

    if (!Db::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ . 'ppwf_order` ADD COLUMN `total_paypal` DECIMAL(20,6) NOT NULL'))
        return false;
    
    if (!Configuration::get('PPAL_ROUND_MODE'))
        Configuration::updateValue('PPAL_ROUND_MODE', 0);

    return true;
}
