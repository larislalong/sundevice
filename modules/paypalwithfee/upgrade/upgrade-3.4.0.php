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

function upgrade_module_3_4_0($object) {

    if (!Db::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ . 'ppwf_order` ADD COLUMN `id_shop` INT(2) NOT NULL'))
        return false;

    if (!Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ppwf_order_refund` ('
                    . '`id_refund` INT(9) NOT NULL AUTO_INCREMENT,'
                    . '`id_ppwf` INT(9) NOT NULL,'
                    . '`id_order` INT(9) NOT NULL,'
                    . '`amount` DECIMAL(20,6) NOT NULL,'
                    . '`transaction_id` VARCHAR(50) NOT NULL,'
                    . '`date` DATETIME NOT NULL,'
                    . 'PRIMARY KEY (`id_refund`)'
                    . ') ENGINE=' . _MYSQL_ENGINE_ . ' default CHARSET=utf8;'))
        return false;


    $id_tab = (int) Tab::getIdFromClassName('Refundppwf');
    if (!$id_tab) {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = 'Refundppwf';
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang)
            $tab->name[$lang['id_lang']] = 'Refund';
        $tab->id_parent = -1;
        $tab->module = $object->name;

        if ($tab->add())
            return true;
        return false;
    }


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
        if (!$object->isRegisteredInHook('ActionEmailAddAfterContent')) {
            $object->registerHook('ActionEmailAddAfterContent');
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
        if (!$object->isRegisteredInHook()) {
            $object->registerHook('actionValidateOrder');
        }
    }

    if (!Configuration::get('PPAL_TAX_FEE'))
        Configuration::updateValue('PPAL_TAX_FEE', 0);

    if (!Configuration::get('PPAL_CUSTOM_INVOICE'))
        Configuration::updateValue('PPAL_CUSTOM_INVOICE', 0);

    return true;
}
