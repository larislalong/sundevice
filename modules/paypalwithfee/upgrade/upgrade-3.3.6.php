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

function upgrade_module_3_3_6($object) {
    if (!Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ppwf_order` ('
                    . ' `id_ppwf` INT(9) NOT NULL AUTO_INCREMENT,'
                    . ' `id_cart` INT(9) NOT NULL,'
                    . ' `id_order` INT(9) NOT NULL,'
                    . ' `total_amount` DECIMAL(20,6) NOT NULL,'
                    . ' `tax_rate` DECIMAL(10,2),'
                    . ' `fee` DECIMAL(20,6) NOT NULL,'
                    . ' `transaction_id` VARCHAR(50) NOT NULL,'
                    . ' PRIMARY KEY (`id_ppwf`)'
                    . ' ) ENGINE=' . _MYSQL_ENGINE_ . ' default CHARSET=utf8;'))
        return false;

    if (version_compare(_PS_VERSION_, '1.6', '>=')) {
        if (!$object->isRegisteredInHook('header')) {
            $object->registerHook('header');
        }
        if (!$object->isRegisteredInHook('adminOrder')) {
            $object->registerHook('adminOrder');
        }
        if (!$object->isRegisteredInHook('DisplayAdminOrder')) {
            $object->registerHook('DisplayAdminOrder');
        }
        if (!$object->isRegisteredInHook('backOfficeHeader')) {
            $object->registerHook('backOfficeHeader');
        }
        if (!$object->isRegisteredInHook()) {
            $object->registerHook('actionValidateOrder');
        }
        if (!$object->isRegisteredInHook('PDFInvoice')) {
            $object->registerHook('PDFInvoice');
        }
        if (!$object->isRegisteredInHook('DisplayOrderDetail')) {
            $object->registerHook('DisplayOrderDetail');
        }
        if (!$object->isRegisteredInHook('DisplayAdminOrderTabShip')) {
            $object->registerHook('DisplayAdminOrderTabShip');
        }
        if (!$object->isRegisteredInHook('DisplayAdminOrderContentShip')) {
            $object->registerHook('DisplayAdminOrderContentShip');
        }
    } else {
        if (!$object->isRegisteredInHook('header')) {
            $object->registerHook('header');
        }
        if (!$object->isRegisteredInHook('adminOrder')) {
            $object->registerHook('adminOrder');
        }
        if (!$object->isRegisteredInHook('backOfficeHeader')) {
            $object->registerHook('backOfficeHeader');
        }
        if (!$object->isRegisteredInHook('PDFInvoice')) {
            $object->registerHook('PDFInvoice');
        }
        if (!$object->isRegisteredInHook('DisplayOrderDetail')) {
            $object->registerHook('DisplayOrderDetail');
        }
        if (!$object->isRegisteredInHook('DisplayAdminOrder')) {
            $object->registerHook('DisplayAdminOrder');
        }
    }

    if (!Configuration::get('PPAL_TAX_FEE'))
        Configuration::updateValue('PPAL_TAX_FEE', 0);

    if (!Configuration::get('PPAL_CUSTOM_INVOICE'))
        Configuration::updateValue('PPAL_CUSTOM_INVOICE', 0);

    return true;
}
