<?php
/**
 * 2007-2018 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2018 PrestaShop SA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

/**
 * Class Dhlexpress
 */
class Dhlexpress extends CarrierModule
{
    /** @var bool $config_form */
    protected $config_form = false;

    /** @var int $id_carrier */
    public $id_carrier;

    /** @var array $tabs */
    protected $tabs;

    /**
     * Dhlexpress constructor.
     */
    public function __construct()
    {
        if (!defined('_PS_VERSION_')) {
            exit;
        }
        $this->name = 'dhlexpress';
        $this->tab = 'shipping_logistics';
        $this->version = '2.3.0';
        $this->module_key = 'd7d0836d68cf44e9c3ab4765e002be17';
        $this->author = 'DHL';
        $this->need_instance = 0;
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('DHL Express ®');
        // @formatter:off
        $this->description = $this->l('Integrate the official DHL Express® module for Expresse Delivery in France and internationnaly');
        // @formatter:on
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall my module?');
        $this->ps_versions_compliancy = array(
            'min' => '1.6',
            'max' => _PS_VERSION_,
        );
        $this->controllers = array(
            'AdminDhlOrders',
            'AdminDhlLabel',
            'AdminDhlBulkLabel',
            'AdminDhlPickup',
            'AdminDhlCommercialInvoice',
            'AdminDhlManifest',
        );
        $this->controllersWithNoTab = array('AdminDhlCommercialInvoice');
        $this->fieldsName = array(
            'contact_name'   => $this->l('contact_name'),
            'contact_email'  => $this->l('contact_email'),
            'contact_phone'  => $this->l('contact_phone'),
            'company_name'   => $this->l('company_name'),
            'address1'       => $this->l('address1'),
            'address2'       => $this->l('address2'),
            'address3'       => $this->l('address3'),
            'zipcode'        => $this->l('zipcode'),
            'city'           => $this->l('city'),
            'country'        => $this->l('country'),
            'phone'          => $this->l('phone'),
            'name'           => $this->l('name'),
            'weight_unit'    => $this->l('weight_unit'),
            'dimension_unit' => $this->l('dimension_unit'),
            'weight_value'   => $this->l('weight_value'),
            'length_value'   => $this->l('length_value'),
            'width_value'    => $this->l('width_value'),
            'depth_value'    => $this->l('depth_value'),
        );
        $this->tabs = array(
            array(
                'class_name' => 'AdminDhlOrders',
                'menu_names' => array('en' => 'DHL Orders', 'fr' => 'DHL Commandes'),
                'active'     => 1,
            ),
            array(
                'class_name' => 'AdminDhlLabel',
                'menu_names' => array('en' => 'DHL Label', 'fr' => 'DHL Etiquettes'),
                'active'     => 1,
            ),
            array(
                'class_name' => 'AdminDhlBulkLabel',
                'menu_names' => array('en' => 'DHL Bulk Label', 'fr' => 'DHL Etiquettes en masse'),
                'active'     => 1,
            ),
            array(
                'class_name' => 'AdminDhlPickup',
                'menu_names' => array('en' => 'DHL Pickup', 'fr' => 'DHL Enlèvement'),
                'active'     => 1,
            ),
            array(
                'class_name' => 'AdminDhlManifest',
                'menu_names' => array('en' => 'DHL Manifest', 'fr' => 'DHL Manifeste'),
                'active'     => 1,
            ),
            array(
                'class_name' => 'AdminDhlCommercialInvoice',
                'menu_names' => array('en' => 'DHL Commercial Invoice', 'fr' => 'DHL Facture commerciale'),
                'active'     => 0,
            ),
        );
    }

    /**
     * @return bool
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function install()
    {
        require_once(dirname(__FILE__).'/classes/DhlCarrier.php');
        require_once(dirname(__FILE__).'/classes/DhlService.php');
        require_once(dirname(__FILE__).'/classes/DhlExtracharge.php');
        require_once(dirname(__FILE__).'/classes/DhlTools.php');

        if (extension_loaded('curl') == false) {
            $this->_errors[] = $this->l('You have to enable the cURL extension on your server to install this module');

            return false;
        }
        Db::getInstance()->execute(
            '
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'dhl_address` (
                `id_dhl_address` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_country` INT(10) UNSIGNED NOT NULL DEFAULT \'0\',
                `id_state` INT(10) UNSIGNED NOT NULL DEFAULT \'0\',
                `contact_name` VARCHAR(35) NULL DEFAULT \'0\',
                `contact_email` VARCHAR(50) NULL DEFAULT \'0\',
                `contact_phone` VARCHAR(25) NULL DEFAULT \'0\',
                `company_name` VARCHAR(35) NULL DEFAULT \'0\',
                `vat_number` VARCHAR(35) NOT NULL DEFAULT \'0\',
                `account_import` VARCHAR(12) NULL DEFAULT \'0\',
                `account_export` VARCHAR(12) NOT NULL DEFAULT \'0\',
                `account_duty` VARCHAR(12) NULL DEFAULT \'0\',
                `address1` VARCHAR(35) NOT NULL DEFAULT \'0\',
                `address2` VARCHAR(35) NULL DEFAULT \'0\',
                `address3` VARCHAR(35) NULL DEFAULT \'0\',
                `zipcode` VARCHAR(12) NULL DEFAULT \'0\',
                `city` VARCHAR(35) NULL DEFAULT \'0\',
                `phone` VARCHAR(25) NULL DEFAULT \'0\',
                `deleted` TINYINT(4) NOT NULL DEFAULT \'0\',
                PRIMARY KEY (`id_dhl_address`),
                INDEX `id_country` (`id_country`),
                INDEX `id_state` (`id_state`)
            )
        '
        );
        Db::getInstance()->execute(
            '
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'dhl_commercial_invoice` (
                `id_dhl_commercial_invoice` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_dhl_order` INT(10) UNSIGNED NOT NULL,
                `id_dhl_label` INT(11) NOT NULL,
                `pdf_string` LONGBLOB NULL,
                `date_add` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (`id_dhl_commercial_invoice`),
                INDEX `id_dhl_order` (`id_dhl_order`)
            )
        '
        );
        Db::getInstance()->execute(
            '
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'dhl_error` (
                `id_dhl_error` INT(11) NOT NULL AUTO_INCREMENT,
                `code` VARCHAR(10) NOT NULL,
                PRIMARY KEY (`id_dhl_error`),
                INDEX `code` (`code`)
        	)
        '
        );
        Db::getInstance()->execute(
            '
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'dhl_error_lang` (
                `id_dhl_error` INT(11) NOT NULL,
                `id_lang` INT(11) NOT NULL,
                `message` VARCHAR(355) NOT NULL,
                PRIMARY KEY (`id_dhl_error`, `id_lang`)
        	)
        '
        );
        Db::getInstance()->execute(
            '
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'dhl_extracharge` (
                `id_dhl_extracharge` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `extracharge_code` VARCHAR(3) NOT NULL,
                `active` TINYINT(4) NOT NULL DEFAULT \'1\',
                `doc` TINYINT(4) NOT NULL,
                `dangerous` TINYINT(4) NOT NULL,
                `label` VARCHAR(120) NULL DEFAULT NULL,
                PRIMARY KEY (`id_dhl_extracharge`),
                INDEX `extracharge_code` (`extracharge_code`)
        	)
        '
        );
        Db::getInstance()->execute(
            '
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'dhl_extracharge_lang` (
                `id_dhl_extracharge` INT(11) NOT NULL,
                `id_lang` INT(11) NOT NULL,
                `name` VARCHAR(50) NULL DEFAULT NULL,
                `description` VARCHAR(255) NULL DEFAULT NULL,
                PRIMARY KEY (`id_dhl_extracharge`, `id_lang`)
        	)
        '
        );
        Db::getInstance()->execute(
            '
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'dhl_label` (
                `id_dhl_label` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_dhl_order` INT(10) UNSIGNED NOT NULL,
                `id_dhl_service` INT(11) NOT NULL,
                `id_dhl_address` INT(11) NOT NULL,
                `awb_number` VARCHAR(45) NOT NULL,
            	`return_label` INT(11) NOT NULL DEFAULT \'0\',
                `label_format` VARCHAR(4) NULL DEFAULT NULL,
                `label_string` LONGBLOB NULL,
                `piece_contents` VARCHAR(90) NULL DEFAULT NULL,
                `total_pieces` INT(11) NULL DEFAULT NULL,
                `total_weight` VARCHAR(50) NULL DEFAULT NULL,
                `consignee_contact` VARCHAR(35) NULL DEFAULT NULL,
                `consignee_destination` VARCHAR(35) NULL DEFAULT NULL,
                `date_add` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (`id_dhl_label`),
                INDEX `id_dhl_order` (`id_dhl_order`)
        	)
        '
        );
        Db::getInstance()->execute(
            '
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'dhl_order` (
                `id_dhl_order` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_order` INT(10) UNSIGNED NOT NULL,
                `id_dhl_service` INT(10) UNSIGNED NOT NULL,
                PRIMARY KEY (`id_dhl_order`),
                INDEX `id_order` (`id_order`, `id_dhl_service`)
        	)
        '
        );
        Db::getInstance()->execute(
            '
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'dhl_package` (
                `id_dhl_package` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(35) NOT NULL,
                `weight_value` FLOAT NOT NULL,
                `length_value` FLOAT NOT NULL,
                `width_value` FLOAT NOT NULL,
                `depth_value` FLOAT NOT NULL,
                PRIMARY KEY (`id_dhl_package`)
            )
        '
        );
        Db::getInstance()->execute(
            '
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'dhl_pickup` (
                `id_dhl_pickup` INT(11) NOT NULL AUTO_INCREMENT,
                `id_dhl_address` INT(11) NOT NULL DEFAULT \'0\',
                `pickup_date` DATE NOT NULL,
                `pickup_time` VARCHAR(5) NOT NULL,
                `confirmation_number` INT(11) NOT NULL,
                `total_pieces` INT(11) NOT NULL,
                PRIMARY KEY (`id_dhl_pickup`),
                INDEX `id_dhl_address` (`id_dhl_address`)
            )
        '
        );
        Db::getInstance()->execute(
            '
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'dhl_service` (
                `id_dhl_service` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_carrier_reference` INT(10) UNSIGNED NULL DEFAULT NULL,
                `global_product_code` VARCHAR(1) NOT NULL,
                `global_product_name` VARCHAR(35) NOT NULL,
                `product_content_code` VARCHAR(3) NOT NULL,
                `declared_value` TINYINT(4) NOT NULL,
                `doc` TINYINT(4) NOT NULL,
                `service_type` VARCHAR(5) NOT NULL,
                `destination_type` VARCHAR(15) NOT NULL,
                `editable` TINYINT(4) NOT NULL DEFAULT \'1\',
                `active` TINYINT(4) NOT NULL DEFAULT \'1\',
                PRIMARY KEY (`id_dhl_service`),
                INDEX `id_carrier_reference` (`id_carrier_reference`)
            )
        '
        );
        Db::getInstance()->execute(
            '
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'dhl_service_lang` (
                `id_dhl_service` INT(11) UNSIGNED NOT NULL,
                `id_lang` INT(11) UNSIGNED NOT NULL,
                `name` VARCHAR(80) NULL DEFAULT NULL,
                PRIMARY KEY (`id_dhl_service`, `id_lang`)
            )
        '
        );
        Db::getInstance()->execute(
            '
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'dhl_shipment_tracking` (
                `id_dhl_label` INT(11) UNSIGNED NOT NULL,
                `id_dhl_order` INT(11) UNSIGNED NOT NULL,
                `id_dhl_tracking` INT(11) UNSIGNED NOT NULL,
                `date_upd` DATETIME NOT NULL,
                PRIMARY KEY (`id_dhl_label`),
                INDEX `id_dhl_tracking` (`id_dhl_tracking`),
                INDEX `id_dhl_label` (`id_dhl_label`),
                INDEX `id_dhl_order` (`id_dhl_order`)
            )
        '
        );
        Db::getInstance()->execute(
            '
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'dhl_tracking` (
                `id_dhl_tracking` INT(11) NOT NULL AUTO_INCREMENT,
                `tracking_code` VARCHAR(5) NOT NULL,
                PRIMARY KEY (`id_dhl_tracking`)
            )
        '
        );
        Db::getInstance()->execute(
            '
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'dhl_tracking_lang` (
                `id_dhl_tracking` INT(11) NOT NULL,
                `id_lang` INT(11) NOT NULL,
                `description` VARCHAR(255) NOT NULL,
                INDEX `id_lang` (`id_lang`),
                INDEX `id_dhl_tracking` (`id_dhl_tracking`)
            )
        '
        );
        $this->setConfigurationValues();
        $this->deleteExistingCarriers();
        if (!$this->createCarriers() ||
            !$this->createExtracharges() ||
            !$this->createErrors() ||
            !$this->createTracking() ||
            !$this->createDhlOrderStatuses() ||
            !$this->installTabs()
        ) {
            return false;
        }

        return parent::install() &&
               $this->registerHook('actionAdminControllerSetMedia') &&
               $this->registerHook('newOrder') &&
               $this->registerHook('displayAdminOrder');
    }

    /**
     * @return bool
     */
    public function uninstall()
    {
        $this->uninstallTabs();
        $this->deleteExistingCarriers();
        Db::getInstance()->execute('TRUNCATE '._DB_PREFIX_.'dhl_extracharge');
        Db::getInstance()->execute('TRUNCATE '._DB_PREFIX_.'dhl_extracharge_lang');
        Db::getInstance()->execute('TRUNCATE '._DB_PREFIX_.'dhl_error');
        Db::getInstance()->execute('TRUNCATE '._DB_PREFIX_.'dhl_error_lang');
        Db::getInstance()->execute('TRUNCATE '._DB_PREFIX_.'dhl_tracking');
        Db::getInstance()->execute('TRUNCATE '._DB_PREFIX_.'dhl_tracking_lang');

        return parent::uninstall();
    }

    /**
     * @return bool
     */
    public function installTabs()
    {
        foreach ($this->tabs as $tab) {
            if (!$this->installTab($tab['class_name'], $tab['menu_names'], $tab['active'])) {
                return false;
            }
        }

        return true;
    }

    /**
     *
     */
    public function uninstallTabs()
    {
        foreach ($this->tabs as $tab) {
            $this->uninstallTab($tab['class_name']);
        }
    }

    /**
     * @param string $className
     * @param string $menuName
     * @param int    $active
     * @return int
     */
    public function installTab($className, $menuName, $active)
    {
        $tab = new Tab();
        $tab->active = (int) $active;
        $tab->name = array();
        $tab->class_name = pSQL($className);
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] =
                isset($menuName[$lang['iso_code']]) ? pSQL($menuName[$lang['iso_code']]) : pSQL($menuName['en']);
        }
        $tab->id_parent = (int) Tab::getIdFromClassName('AdminParentOrders');
        $tab->module = pSQL($this->name);

        return $tab->add();
    }

    /**
     * @param string $className
     * @return bool
     */
    public function uninstallTab($className)
    {
        $idTab = (int) Tab::getIdFromClassName($className);
        if ($idTab) {
            $tab = new Tab($idTab);

            return $tab->delete();
        }

        return true;
    }

    /**
     * @return bool
     */
    public function setConfigurationValues()
    {
        /** Account settings */
        Configuration::updateValue('DHL_LIVE_MODE', 0);

        /** Front-office settings */
        Configuration::updateValue('DHL_USE_DHL_PRICES', 1);
        Configuration::updateValue('DHL_ENABLE_FREE_SHIPPING_FROM', 0);
        Configuration::updateValue('DHL_USE_PREDEFINED_PACKAGES', 1);
        Configuration::updateValue('DHL_WEIGHT_PRICES', 0);
        Configuration::updateValue('DHL_WEIGHTING_TYPE', 'percent');

        /** Back-office settings */
        Configuration::updateValue('DHL_ACCOUNT_OWNER_COUNTRY', Country::getByIso('FR'));
        Configuration::updateValue('DHL_DAILY_PICKUP', 0);
        Configuration::updateValue('DHL_LABEL_TYPE', 'pdfa4');
        Configuration::updateValue('DHL_LABEL_LIFETIME', 3);
        Configuration::updateValue('DHL_SYSTEM_UNITS', 'metric');

        return true;
    }

    /**
     * @return bool
     * @throws PrestaShopException
     */
    public function createExtracharges()
    {
        $extracharges = array(
            array(
                'extracharge_code' => 'II',
                'doc'              => 0,
                'dangerous'        => 0,
                'label'            => '',
                'names'            => array(
                    'fr' => 'Shipment insurance',
                    'en' => 'Shipment insurance',
                ),
                'descriptions'     => array(
                    'fr' => 'The provision at individual shipment level, of declared value coverage above Standard Liability, for the amount necessary to repair or replace a shipment / piece in the unlikely event of physical loss or damage. Known as Shipment Value Protection in the US & Canada.',
                    'en' => 'The provision at individual shipment level, of declared value coverage above Standard Liability, for the amount necessary to repair or replace a shipment / piece in the unlikely event of physical loss or damage. Known as Shipment Value Protection in the US & Canada.',
                ),
            ),
            array(
                'extracharge_code' => 'DD',
                'doc'              => 0,
                'dangerous'        => 0,
                'label'            => '',
                'names'            => array(
                    'fr' => 'Duty & Taxes paid (DTP)',
                    'en' => 'Duty & Taxes paid (DTP)',
                ),
                'descriptions'     => array(
                    'fr' => 'The administration and invoicing of destination duties and taxes to the Shipper, third party or to an entity in a third country, to ensure such inbound duties and taxes are not paid for by the Receiver.',
                    'en' => 'The administration and invoicing of destination duties and taxes to the Shipper, third party or to an entity in a third country, to ensure such inbound duties and taxes are not paid for by the Receiver.',
                ),
            ),
            array(
                'extracharge_code' => 'HE',
                'doc'              => 0,
                'dangerous'        => 1,
                'label'            => 'Dangerous Goods as per attached DGD',
                'names'            => array(
                    'fr' => 'Dangerous goods',
                    'en' => 'Dangerous goods',
                ),
                'descriptions'     => array(
                    'fr' => 'The handling and transportation of substances and commodities classified as Full Dangerous Goods either on the DHL air or road network or on commercial airlines. Comprises Full IATA Dangerous Goods to the limits of ADR Limited Quantities (LQ).',
                    'en' => 'The handling and transportation of substances and commodities classified as Full Dangerous Goods either on the DHL air or road network or on commercial airlines. Comprises Full IATA Dangerous Goods to the limits of ADR Limited Quantities (LQ).',
                ),
            ),
            array(
                'extracharge_code' => 'HH',
                'doc'              => 0,
                'dangerous'        => 1,
                'label'            => 'Dangerous Goods in Excepted Quantities',
                'names'            => array(
                    'fr' => 'Excepted quantities',
                    'en' => 'Excepted quantities',
                ),
                'descriptions'     => array(
                    'fr' => 'The handling and transportation of shipments / pieces containing substances & commodities in Excepted Quantities either on the DHL air or road network or on commercial airlines.',
                    'en' => 'The handling and transportation of shipments / pieces containing substances & commodities in Excepted Quantities either on the DHL air or road network or on commercial airlines.',
                ),
            ),
            array(
                'extracharge_code' => 'HB',
                'doc'              => 0,
                'dangerous'        => 1,
                'label'            => 'Lithium ion batteries in compliance with Section II of P.I. 965-CAO',
                'names'            => array(
                    'fr' => 'Lithium Ion PI965 Section II',
                    'en' => 'Lithium Ion PI965 Section II',
                ),
                'descriptions'     => array(
                    'fr' => 'The handling and transportation of shipments containing Lithium Ion batteries to UN3480 and UN3481that are compliant with sections 1 & II of the IATA Packing Instructions, either on the DHL Aviation or road networks or on Commercial airlines. Surcharge is not applied for Collect & Return shipments booked through ELP. Lithium Ion batteries are the rechargeable type used in notebooks, tablets and mobile phones',
                    'en' => 'The handling and transportation of shipments containing Lithium Ion batteries to UN3480 and UN3481that are compliant with sections 1 & II of the IATA Packing Instructions, either on the DHL Aviation or road networks or on Commercial airlines. Surcharge is not applied for Collect & Return shipments booked through ELP. Lithium Ion batteries are the rechargeable type used in notebooks, tablets and mobile phones',
                ),
            ),
            array(
                'extracharge_code' => 'HD',
                'doc'              => 0,
                'dangerous'        => 1,
                'label'            => 'Lithium ion batteries in compliance with Section II of P.I. 966',
                'names'            => array(
                    'fr' => 'Lithium Ion PI966 Section II',
                    'en' => 'Lithium Ion PI966 Section II',
                ),
                'descriptions'     => array(
                    'fr' => 'The handling and transportation of shipments containing Lithium Ion batteries to UN3480 and UN3481that are compliant with sections 1 & II of the IATA Packing Instructions, either on the DHL Aviation or road networks or on Commercial airlines. Surcharge is not applied for Collect & Return shipments booked through ELP. Lithium Ion batteries are the rechargeable type used in notebooks, tablets and mobile phones',
                    'en' => 'The handling and transportation of shipments containing Lithium Ion batteries to UN3480 and UN3481that are compliant with sections 1 & II of the IATA Packing Instructions, either on the DHL Aviation or road networks or on Commercial airlines. Surcharge is not applied for Collect & Return shipments booked through ELP. Lithium Ion batteries are the rechargeable type used in notebooks, tablets and mobile phones',
                ),
            ),
            array(
                'extracharge_code' => 'HV',
                'doc'              => 0,
                'dangerous'        => 1,
                'label'            => 'Lithium ion batteries in compliance with Section II of P.I. 967',
                'names'            => array(
                    'fr' => 'Lithium Ion PI967-Section II',
                    'en' => 'Lithium Ion PI967-Section II',
                ),
                'descriptions'     => array(
                    'fr' => 'The handling and transportation of shipments containing Lithium Ion batteries to UN3480 and UN3481that are compliant with sections 1 & II of the IATA Packing Instructions, either on the DHL Aviation or road networks or on Commercial airlines. Surcharge is not applied for Collect & Return shipments booked through ELP. Lithium Ion batteries are the rechargeable type used in notebooks, tablets and mobile phones',
                    'en' => 'The handling and transportation of shipments containing Lithium Ion batteries to UN3480 and UN3481that are compliant with sections 1 & II of the IATA Packing Instructions, either on the DHL Aviation or road networks or on Commercial airlines. Surcharge is not applied for Collect & Return shipments booked through ELP. Lithium Ion batteries are the rechargeable type used in notebooks, tablets and mobile phones',
                ),
            ),
            array(
                'extracharge_code' => 'HM',
                'doc'              => 0,
                'dangerous'        => 1,
                'label'            => 'Lithium metal batteries in compliance with Section II of P.I. 969',
                'names'            => array(
                    'fr' => 'Lithium Metal PI969 Section II',
                    'en' => 'Lithium Metal PI969 Section II',
                ),
                'descriptions'     => array(
                    'fr' => 'The handling and transportation of shipments containing Lithium Ion batteries to UN3480 and UN3481that are compliant with sections 1 & II of the IATA Packing Instructions, either on the DHL Aviation or road networks or on Commercial airlines. Surcharge is not applied for Collect & Return shipments booked through ELP. Lithium Ion batteries are the rechargeable type used in notebooks, tablets and mobile phones',
                    'en' => 'The handling and transportation of shipments containing Lithium Ion batteries to UN3480 and UN3481that are compliant with sections 1 & II of the IATA Packing Instructions, either on the DHL Aviation or road networks or on Commercial airlines. Surcharge is not applied for Collect & Return shipments booked through ELP. Lithium Ion batteries are the rechargeable type used in notebooks, tablets and mobile phones',
                ),
            ),
            array(
                'extracharge_code' => 'HW',
                'doc'              => 0,
                'dangerous'        => 1,
                'label'            => 'Lithium metal batteries in compliance with Section II of P.I. 970',
                'names'            => array(
                    'fr' => 'Lithium Metal PI970-Section II',
                    'en' => 'Lithium Metal PI970-Section II',
                ),
                'descriptions'     => array(
                    'fr' => 'The handling and transportation of shipments containing Lithium Ion batteries to UN3480 and UN3481that are compliant with sections 1 & II of the IATA Packing Instructions, either on the DHL Aviation or road networks or on Commercial airlines. Surcharge is not applied for Collect & Return shipments booked through ELP. Lithium Ion batteries are the rechargeable type used in notebooks, tablets and mobile phones',
                    'en' => 'The handling and transportation of shipments containing Lithium Ion batteries to UN3480 and UN3481that are compliant with sections 1 & II of the IATA Packing Instructions, either on the DHL Aviation or road networks or on Commercial airlines. Surcharge is not applied for Collect & Return shipments booked through ELP. Lithium Ion batteries are the rechargeable type used in notebooks, tablets and mobile phones',
                ),
            ),
            array(
                'extracharge_code' => 'IB',
                'doc'              => 1,
                'dangerous'        => 0,
                'label'            => 'Extended Liability',
                'names'            => array(
                    'fr' => 'Extended Liability',
                    'en' => 'Extended Liability',
                ),
                'descriptions'     => array(
                    'fr' => 'Extended Liability for document shipment',
                    'en' => 'Extended Liability for document shipment',
                ),
            ),
        );
        $languages = Language::getLanguages(false);
        foreach ($extracharges as $extracharge) {
            $dhlExtracharge = new DhlExtracharge();
            $dhlExtracharge->hydrate($extracharge);
            $dhlExtracharge->active = 0;
            foreach ($languages as $language) {
                $name =
                    isset($extracharge['names'][$language['iso_code']]) ? $extracharge['names'][$language['iso_code']] :
                        $extracharge['names']['en'];
                $dhlExtracharge->name[(int) $language['id_lang']] = $name;
                $description =
                    isset($extracharge['descriptions'][$language['iso_code']]) ?
                        $extracharge['descriptions'][$language['iso_code']] : $extracharge['descriptions']['en'];
                $dhlExtracharge->description[(int) $language['id_lang']] = $description;
            }
            if (!$dhlExtracharge->save()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param int   $idCarrier
     * @param array $dhlServices
     * @param array $languages
     * @return bool
     * @throws PrestaShopException
     */
    public function createServices($idCarrier, $dhlServices, $languages)
    {
        $services = $dhlServices['services'];
        foreach ($services as $service) {
            $idDhlService = DhlService::getServiceByIdCarrierDestination(
                $idCarrier,
                $service['destination_type'],
                $service['doc'],
                true
            );
            $dhlService = new DhlService((int) $idDhlService);
            $dhlService->hydrate($service);
            $dhlService->id_carrier_reference = $idCarrier;
            foreach ($languages as $language) {
                $name =
                    isset($dhlServices['delays'][$language['iso_code']]) ?
                        $dhlServices['delays'][$language['iso_code']] : $dhlServices['delays']['en'];
                $dhlService->name[(int) $language['id_lang']] = $dhlServices['name'].' - '.$name;
                if (isset($service['delays_extra'])) {
                    $nameExtra =
                        isset($service['delays_extra'][$language['iso_code']]) ?
                            $service['delays_extra'][$language['iso_code']] : $service['delays_extra']['en'];
                    $dhlService->name[(int) $language['id_lang']] .= ' '.$nameExtra;
                }
            }
            if (!$dhlService->save()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return bool
     * @throws PrestaShopException
     */
    public function createErrors()
    {
        require_once(dirname(__FILE__).'/classes/DhlError.php');

        $fd = fopen(dirname(__FILE__).'/dhl_errors.csv', 'r');
        $languages = Language::getLanguages(false);
        while ($line = fgetcsv($fd, null, ';')) {
            $error = new DhlError();
            $error->code = $line[1];
            foreach ($languages as $language) {
                $error->message[(int) $language['id_lang']] = $line[2];
            }
            if (!$error->save()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return bool
     * @throws PrestaShopException
     */
    public function createTracking()
    {
        require_once(dirname(__FILE__).'/classes/DhlTracking.php');

        $fd = fopen(dirname(__FILE__).'/dhl_tracking.csv', 'r');
        $languages = Language::getLanguages(false);
        while ($line = fgetcsv($fd, null, ';')) {
            $tracking = new DhlTracking();
            $tracking->tracking_code = $line[1];
            foreach ($languages as $language) {
                $tracking->description[(int) $language['id_lang']] = $line[2];
            }
            if (!$tracking->save()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return bool
     * @throws PrestaShopException
     */
    public function createDhlOrderStatuses()
    {
        $dhlStatuses = array(
            'DHL_OS_DELIVERY'    => array(
                'invoice'        => 1,
                'send_mail'      => 0,
                'module_name'    => $this->name,
                'color'          => '#ffcc00',
                'unremovable'    => 0,
                'hidden'         => 0,
                'invoiceinvoice' => 1,
                'logable'        => 1,
                'delivery'       => 1,
                'shipped'        => 1,
                'paid'           => 1,
                'pdf_invoice'    => 0,
                'pdf_delivery'   => 0,
                'deleted'        => 0,
                'names'          => array(
                    'fr' => 'En cours de livraison',
                    'en' => 'Delivery in progress',
                ),
            ),
            'DHL_OS_PREPARATION' => array(
                'invoice'        => 1,
                'send_mail'      => 0,
                'module_name'    => $this->name,
                'color'          => '#ffcc00',
                'unremovable'    => 0,
                'hidden'         => 0,
                'invoiceinvoice' => 1,
                'logable'        => 1,
                'delivery'       => 1,
                'shipped'        => 0,
                'paid'           => 1,
                'pdf_invoice'    => 0,
                'pdf_delivery'   => 0,
                'deleted'        => 0,
                'names'          => array(
                    'fr' => 'Traitement de l\'expédition en cours',
                    'en' => 'Handling of shipment in progress',
                ),
            ),
        );
        $orderStatuses = OrderState::getOrderStates((int) $this->context->language->id);
        foreach ($orderStatuses as $dhlOrderStatus) {
            if ($dhlOrderStatus['module_name'] == $this->name) {
                $oldOrderStatus = new OrderState((int) $dhlOrderStatus['id_order_state']);
                $oldOrderStatus->delete();
            }
        }
        $languages = Language::getLanguages(true);
        foreach ($dhlStatuses as $confKey => $dhlStatus) {
            $newOrderStatus = new OrderState();
            $newOrderStatus->hydrate($dhlStatus);
            foreach ($languages as $language) {
                $name =
                    isset($dhlStatus['names'][$language['iso_code']]) ? $dhlStatus['names'][$language['iso_code']] :
                        $dhlStatus['names']['en'];
                $newOrderStatus->name[(int) $language['id_lang']] = $name;
                $newOrderStatus->template[(int) $language['id_lang']] = '';
            }
            if ($newOrderStatus->save()) {
                Configuration::updateValue($confKey, (int) $newOrderStatus->id);
                $logoPath = _PS_MODULE_DIR_.$this->name.'/views/img/dhl_order_state.gif';
                DhlTools::copyLogo($logoPath, _PS_ORDER_STATE_IMG_DIR_.(int) $newOrderStatus->id.'.gif');
            } else {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $service
     * @param array $languages
     * @return bool|DhlCarrier
     * @throws PrestaShopException
     */
    public function createCarrier($service, $languages)
    {
        $carrier = new DhlCarrier();
        $carrier->hydrate($service);
        $idReference = DhlService::getIdCarrierByServiceType($service['carrier_type']);
        foreach ($languages as $language) {
            $carrier->delay[(int) $language['id_lang']] =
                isset($service['delays'][$language['iso_code']]) ? $service['delays'][$language['iso_code']] :
                    $service['delays']['en'];
        }

        if (!$carrier->save()) {
            return false;
        } else {
            if (Tools::version_compare(_PS_VERSION_, '1.7', '>=')) {
                $logoPath = _PS_MODULE_DIR_.$this->name.'/views/img/dhl_carrier_17.png';
            } else {
                $logoPath = _PS_MODULE_DIR_.$this->name.'/views/img/dhl_carrier.png';
            }
            $carrier->setLogo($logoPath, $this->context->language->id);
            if (false !== $idReference && Validate::isLoadedObject(new Carrier((int) $idReference))) {
                $carrier->id_reference = (int) $idReference;
                $carrier->save();
            }

            return $carrier;
        }
    }

    /**
     * @return bool
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function createCarriers()
    {
        $dhlCarriers = array(
            array(
                'name'                 => 'DHL Express',
                'delays'               => array(
                    'fr' => 'Livraison avant 18H',
                    'en' => 'Delivered by 6PM',
                ),
                'url'                  => DhlTools::DHL_URL_TRACKING,
                'active'               => true,
                'shipping_handling'    => false,
                'range_behavior'       => 0,
                'is_module'            => true,
                'is_free'              => false,
                'shipping_external'    => true,
                'need_range'           => true,
                'external_module_name' => $this->name,
                'shipping_method'      => Carrier::SHIPPING_METHOD_WEIGHT,
                'carrier_type'         => '24H',
                'services'             => array(
                    array(
                        'global_product_code'  => 'N',
                        'global_product_name'  => 'DOMESTIC EXPRESS',
                        'product_content_code' => 'DOM',
                        'declared_value'       => 0,
                        'doc'                  => 0,
                        'service_type'         => '24H',
                        'destination_type'     => 'DOMESTIC',
                        'editable'             => 1,
                        'active'               => 1,
                    ),
                    array(
                        'global_product_code'  => 'U',
                        'global_product_name'  => 'EXPRESS WORLDWIDE',
                        'product_content_code' => 'ECX',
                        'declared_value'       => 0,
                        'doc'                  => 0,
                        'service_type'         => '24H',
                        'destination_type'     => 'EUROPE',
                        'editable'             => 1,
                        'active'               => 1,
                    ),
                    array(
                        'global_product_code'  => 'P',
                        'global_product_name'  => 'EXPRESS WORLDWIDE',
                        'product_content_code' => 'WPX',
                        'declared_value'       => 1,
                        'doc'                  => 0,
                        'service_type'         => '24H',
                        'destination_type'     => 'WORLDWIDE',
                        'editable'             => 1,
                        'active'               => 1,
                    ),
                    array(
                        'global_product_code'  => 'D',
                        'global_product_name'  => 'EXPRESS WORLDWIDE DOC',
                        'product_content_code' => 'DOX',
                        'declared_value'       => 0,
                        'doc'                  => 1,
                        'service_type'         => '24H',
                        'destination_type'     => 'WORLDWIDE',
                        'editable'             => 1,
                        'active'               => 0,
                        'delays_extra'         => array(
                            'fr' => '(DOC)',
                            'en' => '(DOC)',
                        ),
                    ),
                ),
            ),
            array(
                'name'                 => 'DHL Express',
                'delays'               => array(
                    'fr' => 'Livraison avant 09H',
                    'en' => 'Delivered by 9AM',
                ),
                'url'                  => DhlTools::DHL_URL_TRACKING,
                'active'               => true,
                'shipping_handling'    => false,
                'range_behavior'       => 0,
                'is_module'            => true,
                'is_free'              => false,
                'shipping_external'    => true,
                'need_range'           => true,
                'external_module_name' => $this->name,
                'shipping_method'      => Carrier::SHIPPING_METHOD_WEIGHT,
                'carrier_type'         => '9H',
                'services'             => array(
                    array(
                        'global_product_code'  => 'I',
                        'global_product_name'  => 'DOMESTIC EXPRESS 9:00',
                        'product_content_code' => 'DOK',
                        'declared_value'       => 0,
                        'doc'                  => 0,
                        'service_type'         => '9H',
                        'destination_type'     => 'DOMESTIC',
                        'editable'             => 1,
                        'active'               => 1,
                    ),
                    array(
                        'global_product_code'  => 'K',
                        'global_product_name'  => 'EXPRESS 9:00',
                        'product_content_code' => 'TDK',
                        'declared_value'       => 0,
                        'doc'                  => 0,
                        'service_type'         => '9H',
                        'destination_type'     => 'EUROPE',
                        'editable'             => 1,
                        'active'               => 1,
                    ),
                    array(
                        'global_product_code'  => 'E',
                        'global_product_name'  => 'EXPRESS 9:00',
                        'product_content_code' => 'TDE',
                        'declared_value'       => '1',
                        'doc'                  => '0',
                        'service_type'         => '9H',
                        'destination_type'     => 'WORLDWIDE',
                        'editable'             => 1,
                        'active'               => 1,
                    ),
                    array(
                        'global_product_code'  => 'K',
                        'global_product_name'  => 'EXPRESS 9:00 DOC',
                        'product_content_code' => 'TDK',
                        'declared_value'       => 0,
                        'doc'                  => 1,
                        'service_type'         => '9H',
                        'destination_type'     => 'WORLDWIDE',
                        'editable'             => 1,
                        'active'               => 0,
                        'delays_extra'         => array(
                            'fr' => '(DOC)',
                            'en' => '(DOC)',
                        ),
                    ),
                ),
            ),
            array(
                'name'                 => 'DHL Express',
                'delays'               => array(
                    'fr' => 'Livraison avant 12h',
                    'en' => 'Delivered by 12PM',
                ),
                'url'                  => DhlTools::DHL_URL_TRACKING,
                'active'               => true,
                'shipping_handling'    => false,
                'range_behavior'       => 0,
                'is_module'            => true,
                'is_free'              => false,
                'shipping_external'    => true,
                'need_range'           => true,
                'external_module_name' => $this->name,
                'shipping_method'      => Carrier::SHIPPING_METHOD_WEIGHT,
                'carrier_type'         => '12H',
                'services'             => array(
                    array(
                        'global_product_code'  => '1',
                        'global_product_name'  => 'DOMESTIC EXPRESS 12:00',
                        'product_content_code' => 'DOT',
                        'declared_value'       => 0,
                        'doc'                  => 0,
                        'service_type'         => '12H',
                        'destination_type'     => 'DOMESTIC',
                        'editable'             => 1,
                        'active'               => 1,
                    ),
                    array(
                        'global_product_code'  => 'T',
                        'global_product_name'  => 'EXPRESS 12:00',
                        'product_content_code' => 'TDT',
                        'declared_value'       => 0,
                        'doc'                  => 0,
                        'service_type'         => '12H',
                        'destination_type'     => 'EUROPE',
                        'editable'             => 1,
                        'active'               => 1,
                    ),
                    array(
                        'global_product_code'  => 'Y',
                        'global_product_name'  => 'EXPRESS 12:00',
                        'product_content_code' => 'TDY',
                        'declared_value'       => 1,
                        'doc'                  => 0,
                        'service_type'         => '12H',
                        'destination_type'     => 'WORLDWIDE',
                        'editable'             => 1,
                        'active'               => 1,
                    ),
                    array(
                        'global_product_code'  => 'T',
                        'global_product_name'  => 'EXPRESS 12:00 DOC',
                        'product_content_code' => 'TDT',
                        'declared_value'       => 0,
                        'doc'                  => 1,
                        'service_type'         => '12H',
                        'destination_type'     => 'WORLDWIDE',
                        'editable'             => 1,
                        'active'               => 0,
                        'delays_extra'         => array(
                            'fr' => '(DOC)',
                            'en' => '(DOC)',
                        ),
                    ),
                ),
            ),
            array(
                'name'                 => 'DHL Economy',
                'delays'               => array(
                    'fr' => 'Livraison sous 48h/96h',
                    'en' => 'Delivered within 2-4 days',
                ),
                'url'                  => DhlTools::DHL_URL_TRACKING,
                'active'               => true,
                'shipping_handling'    => false,
                'range_behavior'       => 0,
                'is_module'            => true,
                'is_free'              => false,
                'shipping_external'    => true,
                'need_range'           => true,
                'external_module_name' => $this->name,
                'shipping_method'      => Carrier::SHIPPING_METHOD_WEIGHT,
                'carrier_type'         => 'ECO',
                'services'             => array(
                    array(
                        'global_product_code'  => 'W',
                        'global_product_name'  => 'ECONOMY SELECT',
                        'product_content_code' => 'ESU',
                        'declared_value'       => 0,
                        'doc'                  => 0,
                        'service_type'         => 'ECO',
                        'destination_type'     => 'EUROPE',
                        'editable'             => 1,
                        'active'               => 1,
                        'delays_extra'         => array(
                            'fr' => '(Union Européenne)',
                            'en' => '(European Union)',
                        ),
                    ),
                    array(
                        'global_product_code'  => 'H',
                        'global_product_name'  => 'ECONOMY SELECT',
                        'product_content_code' => 'ESI',
                        'declared_value'       => 1,
                        'doc'                  => 0,
                        'service_type'         => 'ECO',
                        'destination_type'     => 'WORLDWIDE',
                        'editable'             => 1,
                        'active'               => 1,
                        'delays_extra'         => array(
                            'fr' => '(Suisse, Norvège)',
                            'en' => '(Switzerland, Norway)',
                        ),
                    ),
                ),
            ),
        );
        $languages = Language::getLanguages(false);
        foreach ($dhlCarriers as $dhlCarrier) {
            $carrier = $this->createCarrier($dhlCarrier, $languages);
            if (false === $carrier) {
                $this->_errors[] = $this->l('DHL carrier cannot be created.');

                return false;
            } else {
                $carrier->setGroups(Group::getGroups($this->context->language->id));
                $carrier->setZones(Zone::getZones(true));
                $carrier->setRanges();
                $idReference = empty($carrier->id_reference) ? $carrier->id : $carrier->id_reference;
                if (!$this->createServices($idReference, $dhlCarrier, $languages)) {
                    $this->_errors[] = $this->l('DHL services cannot be created.');

                    return false;
                }
            }
        }

        return true;
    }

    /**
     *
     */
    public function deleteExistingCarriers()
    {
        Db::getInstance()->update('carrier', array('deleted' => 1), 'external_module_name = "'.$this->name.'"');
    }

    /**
     * @return string
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function postProcessServices()
    {
        $services = DhlService::getServices($this->context->language->id, false);
        foreach ($services as $service) {
            $idDhlService = (int) $service['id_dhl_service'];
            if (Tools::isSubmit('service_'.$idDhlService)) {
                $dhlService = new DhlService($idDhlService);
                $dhlService->active = (int) Tools::getValue('service_'.$idDhlService);
                if (!$dhlService->save()) {
                    return $this->displayError($this->l('Services cannot be updated.'));
                }
            }
        }

        return $this->postProcessFrontOfficeSettings($this->getFrontOfficeSettingsFormFields());
    }

    /**
     *
     * @param $keys
     * @return string
     */
    public function postProcessFrontOfficeSettings($keys)
    {
        foreach ($keys as $key) {
            $value = Tools::getValue($key);
            if ($key === 'DHL_WEIGHTING_VALUE_PERCENT' || $key === 'DHL_WEIGHTING_VALUE_AMOUNT') {
                if ($value && !Validate::isFloat($value)) {
                    return $this->displayError($this->l('Please enter valid weighting values.'));
                }
                Configuration::updateValue($key, (float) Tools::getValue($key));
            } else {
                Configuration::updateValue($key, Tools::getValue($key));
            }
        }

        return $this->displayConfirmation($this->l('Settings updated'));
    }

    /**
     * @param array $keys
     * @return string
     */
    public function postProcessSettings($keys)
    {
        foreach ($keys as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }

        return $this->displayConfirmation($this->l('Settings updated'));
    }

    /**
     * To verify credentials, we simulate a Quote request betwenn Lyon and Paris
     *
     * @return string
     */
    public function postProcessAccountCheck()
    {
        $credentials = DhlTools::getCredentials();
        $quoteRequest = new DhlQuoteRequest($credentials);
        $quoteRequest->setSender(
            array(
                'CountryCode' => 'FR',
                'Postalcode'  => '69007',
                'City'        => 'Lyon',
            )
        );
        $quoteRequest->setReceiver(
            array(
                'CountryCode' => 'FR',
                'Postalcode'  => '75001',
                'City'        => 'Paris',
            )
        );
        $quoteRequest->setPackageDetails(
            array(
                'PaymentCountryCode'   => 'FR',
                'Date'                 => date('Y-m-d'),
                'ReadyTime'            => 'PT'.date('H').'H'.date('i').'M',
                'DimensionUnit'        => Tools::strtoupper(DhlTools::getDimensionUnit()),
                'WeightUnit'           => Tools::strtoupper(DhlTools::getWeightUnit()),
                'PaymentAccountNumber' => '',
                'Pieces'               => array(
                    array(
                        'PieceID' => 1,
                        'Height'  => 10,
                        'Depth'   => 10,
                        'Width'   => 10,
                        'Weight'  => 10,
                    ),
                ),
            )
        );
        $client = new DhlClient();
        $client->setRequest($quoteRequest);
        $response = $client->request();
        if ($response && $response instanceof DhlQuoteResponse) {
            $errors = $response->getErrors();
            if (empty($errors)) {
                return $this->displayConfirmation($this->l('Your credentials has been checked successfully.'));
            } else {
                return $this->displayError($this->l('Please check your credentials.'));
            }
        } else {
            return $this->displayError($this->l('Cannot connect to DHL API.'));
        }
    }

    /**
     * @return string
     * @throws PrestaShopException
     */
    public function postProcessAddress()
    {
        $idDhlAddress = (int) Tools::getValue('id_dhl_address');
        $dhlAddress = new DhlAddress($idDhlAddress);
        $fields = DhlAddress::$definition['fields'];
        $errors = array();
        foreach (array_keys($fields) as $field) {
            $validate = $dhlAddress->validateField($field, Tools::getValue($field), null, array(), true);
            if (true !== $validate) {
                $errors[] = $validate;
            }
        }
        if ($errors) {
            $this->context->smarty->assign('addNewAddress', 1);

            return $this->displayError($errors);
        }
        $idCountry = (int) Tools::getValue('id_country');
        $idState = (int) Tools::getValue('id_state');
        $country = new Country($idCountry);
        if ($country && !(int) $country->contains_states && $idState) {
            $errors[] = $this->l('You have selected a state for a country that does not contain states.');
        }
        if ((int) $country->contains_states && !$idState) {
            $errors[] = $this->l('An address located in a country containing states must have a state selected.');
        }
        $zipcode = Tools::getValue('zipcode');
        if ($country->zip_code_format && !$country->checkZipCode($zipcode)) {
            $errors[] = $this->l('Your Zip/postal code is incorrect.');
        } elseif (empty($zipcode) && $country->need_zip_code) {
            $errors[] = $this->l('A Zip/postal code is required.');
        } elseif ($zipcode && !Validate::isPostCode($zipcode)) {
            $errors[] = $this->l('The Zip/postal code is invalid.');
        }
        if ($errors) {
            $this->context->smarty->assign('addNewAddress', 1);

            return $this->displayError($errors);
        }
        $dhlAddress->contact_name = Tools::getValue('contact_name');
        $dhlAddress->contact_email = Tools::getValue('contact_email');
        $dhlAddress->contact_phone = Tools::getValue('contact_phone');
        $dhlAddress->company_name = Tools::getValue('company_name');
        $dhlAddress->vat_number = Tools::getValue('vat_number');
        $dhlAddress->account_import = trim(Tools::getValue('account_import'));
        $dhlAddress->account_export = trim(Tools::getValue('account_export'));
        $dhlAddress->account_duty = trim(Tools::getValue('account_duty'));
        $dhlAddress->address1 = Tools::getValue('address1');
        $dhlAddress->address2 = Tools::getValue('address2');
        $dhlAddress->address3 = Tools::getValue('address3');
        $dhlAddress->zipcode = $zipcode;
        $dhlAddress->city = Tools::getValue('city');
        $dhlAddress->phone = Tools::getValue('phone');
        $dhlAddress->id_state = (int) $idState;
        $dhlAddress->id_country = (int) $idCountry;
        if ($dhlAddress->save()) {
            $addresses = DhlAddress::getAddressList();
            // If this is the first address created (deleted addresses not included), we must set it
            // to be the default one
            if (is_array($addresses) && 1 == count($addresses)) {
                Configuration::updateValue('DHL_DEFAULT_SENDER_ADDRESS', $dhlAddress->id);
            }

            if (Tools::getValue('redirectAfter')) {
                Tools::redirectAdmin(Tools::getValue('redirectAfter'));
            }

            return $this->displayConfirmation($this->l('Address saved successfully.'));
        } else {
            $this->context->smarty->assign('addNewAddress', 1);

            return $this->displayError($this->l('An error occurred during address creation'));
        }
    }

    /**
     * @return string
     * @throws PrestaShopException
     */
    public function postProcessPackage()
    {
        $idDhlPackage = (int) Tools::getValue('id_dhl_package');
        $dhlPackage = new DhlPackage($idDhlPackage);
        $fields = DhlPackage::$definition['fields'];
        $errors = array();
        foreach (array_keys($fields) as $field) {
            $validate = $dhlPackage->validateField($field, Tools::getValue($field), null, array(), true);
            if (true !== $validate) {
                $errors[] = $validate;
            }
        }
        if ($errors) {
            $this->context->smarty->assign('addNewPackage', 1);

            return $this->displayError($errors);
        }
        $dhlPackage->name = Tools::getValue('name');
        $dhlPackage->weight_value = (float) Tools::getValue('weight_value');
        $dhlPackage->length_value = (int) Tools::getValue('length_value') ? (int) Tools::getValue('length_value') : 1;
        $dhlPackage->width_value = (int) Tools::getValue('width_value') ? (int) Tools::getValue('width_value') : 1;
        $dhlPackage->depth_value = (int) Tools::getValue('depth_value') ? (int) Tools::getValue('depth_value') : 1;
        if ($dhlPackage->save()) {
            $packages = DhlPackage::getPackageList();
            if (is_array($packages) && 1 == count($packages)) {
                Configuration::updateValue('DHL_DEFAULT_PACKAGE_TYPE', $dhlPackage->id);
            }

            if (Tools::getValue('redirectAfter')) {
                Tools::redirectAdmin(Tools::getValue('redirectAfter'));
            }

            return $this->displayConfirmation($this->l('Package saved successfully.'));
        } else {
            $this->context->smarty->assign('addNewPackage', 1);

            return $this->displayError($this->l('An error occurred during package creation'));
        }
    }

    /**
     * @return string
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function postProcessExtracharges()
    {
        require_once(dirname(__FILE__).'/classes/DhlExtracharge.php');

        $extracharges = DhlExtracharge::getExtrachargesList($this->context->language->id);
        foreach ($extracharges as $extracharge) {
            $idDhlExtracharge = (int) $extracharge['id_dhl_extracharge'];
            if (Tools::isSubmit('extracharge_'.$idDhlExtracharge)) {
                $dhlExtracharge = new DhlExtracharge($idDhlExtracharge);
                $dhlExtracharge->active = (int) Tools::getValue('extracharge_'.$idDhlExtracharge);
                if (!$dhlExtracharge->save()) {
                    return $this->displayError($this->l('Extracharges cannot be updated.'));
                }
            }
        }

        return $this->displayConfirmation($this->l('Settings updated'));
    }

    /**
     * @return string
     * @throws PrestaShopException
     */
    public function postProcessDeleteAddress()
    {
        $idDhlAddress = (int) Tools::getValue('id_dhl_address');
        $dhlAddress = new DhlAddress($idDhlAddress);
        if (Validate::isLoadedObject($dhlAddress)) {
            if ($dhlAddress->delete()) {
                if ((int) Configuration::get('DHL_DEFAULT_SENDER_ADDRESS') == $idDhlAddress) {
                    $newDefaultAddress = DhlAddress::getFirstAddress();
                    if (false === $newDefaultAddress) {
                        Configuration::updateValue('DHL_DEFAULT_SENDER_ADDRESS', '');
                    } else {
                        Configuration::updateValue('DHL_DEFAULT_SENDER_ADDRESS', $newDefaultAddress->id);
                    }
                }

                return $this->displayConfirmation($this->l('Address deleted successfully.'));
            } else {
                return $this->displayError($this->l('Address cannot be deleted.'));
            }
        } else {
            return $this->displayError($this->l('The address you try to delete is not valid.'));
        }
    }

    /**
     * @return string
     * @throws PrestaShopException
     */
    public function postProcessDeletePackage()
    {
        $idDhlPackage = (int) Tools::getValue('id_dhl_package');
        $dhlPackage = new DhlPackage($idDhlPackage);
        if (Validate::isLoadedObject($dhlPackage)) {
            if ($dhlPackage->delete()) {
                if ((int) Configuration::get('DHL_DEFAULT_PACKAGE_TYPE') == $idDhlPackage) {
                    $newDefaultPackage = DhlPackage::getFirstPackage();
                    if (false === $newDefaultPackage) {
                        Configuration::updateValue('DHL_DEFAULT_PACKAGE_TYPE', '');
                    } else {
                        Configuration::updateValue('DHL_DEFAULT_PACKAGE_TYPE', $newDefaultPackage->id);
                    }
                }

                return $this->displayConfirmation($this->l('Package deleted successfully.'));
            } else {
                return $this->displayError($this->l('Package cannot be deleted.'));
            }
        } else {
            return $this->displayError($this->l('The package you try to delete is not valid.'));
        }
    }

    /**
     * @return array
     */
    public function getAccountSettingsFormFields()
    {
        return array(
            'DHL_LIVE_MODE',
            'DHL_ENABLE_LOG',
            'DHL_ACCOUNT_ID_TEST',
            'DHL_ACCOUNT_PASSWORD_TEST',
            'DHL_ACCOUNT_ID_PRODUCTION',
            'DHL_ACCOUNT_PASSWORD_PRODUCTION',
        );
    }

    /**
     * @return array
     * @throws PrestaShopException
     */
    public function getAccountSettingsFormValues()
    {
        return Configuration::getMultiple($this->getAccountSettingsFormFields());
    }

    /**
     * @return array
     */
    public function getFrontOfficeSettingsFormFields()
    {
        $return = array(
            'DHL_USE_DHL_PRICES',
            'DHL_ENABLE_FREE_SHIPPING_FROM',
            'DHL_USE_PREDEFINED_PACKAGES',
            'DHL_WEIGHT_PRICES',
            'DHL_WEIGHTING_TYPE',
            'DHL_WEIGHTING_VALUE_PERCENT',
            'DHL_WEIGHTING_VALUE_AMOUNT',
        );
        $zones = Zone::getZones(true);
        foreach ($zones as $zone) {
            array_push($return, 'DHL_FRANCO_'.(int) $zone['id_zone']);
        }

        return $return;
    }

    /**
     * @return array
     * @throws PrestaShopException
     */
    public function getFrontOfficeSettingsFormValues()
    {
        return Configuration::getMultiple($this->getFrontOfficeSettingsFormFields());
    }

    /**
     * @param bool|false $key
     * @return array
     */
    public function getBackOfficeSettingsFormFields($key = false)
    {
        $return = array();
        $return['BillingAccount'] = array(
            'DHL_ACCOUNT_OWNER_COUNTRY',
        );
        $return['SenderAddress'] = array(
            'DHL_DEFAULT_SENDER_ADDRESS',
        );
        $return['ShipmentDetails'] = array(
            'DHL_DAILY_PICKUP',
            'DHL_LABEL_TYPE',
            'DHL_LABEL_LIFETIME',
            'DHL_SENDING_DOC',
            'DHL_DEFAULT_PACKAGE_TYPE',
            'DHL_DEFAULT_SHIPMENT_CONTENT',
            'DHL_DEFAULT_HS_CODE',
            'DHL_SYSTEM_UNITS',
        );
        if (!$key) {
            return array_merge(
                $return['BillingAccount'],
                $return['SenderAddress'],
                $return['ShipmentDetails']
            );
        }

        return isset($return[$key]) ? $return[$key] : array();
    }

    /**
     * @return array
     * @throws PrestaShopException
     */
    public function getBackOfficeSettingsFormValues()
    {
        require_once(dirname(__FILE__).'/classes/DhlExtracharge.php');

        $defaultPackageId = (int) Configuration::get('DHL_DEFAULT_PACKAGE_TYPE');
        $dhlPackage = new DhlPackage($defaultPackageId);
        $defaultPackageArray = array();
        if (Validate::isLoadedObject($dhlPackage)) {
            $defaultPackageArray = array(
                'DHL_DEFAULT_PACKAGE_WEIGHT' => $dhlPackage->weight_value,
                'DHL_DEFAULT_PACKAGE_LENGTH' => $dhlPackage->length_value,
                'DHL_DEFAULT_PACKAGE_WIDTH'  => $dhlPackage->width_value,
                'DHL_DEFAULT_PACKAGE_DEPTH'  => $dhlPackage->depth_value,
            );
        }
        $defaultSenderAddressId = (int) Configuration::get('DHL_DEFAULT_SENDER_ADDRESS');
        $dhlAddress = new DhlAddress($defaultSenderAddressId);
        $defaultAddressArray = array();
        if (Validate::isLoadedObject($dhlAddress)) {
            $defaultAddressArray = array(
                'dhl_default_address_obj' => $dhlAddress,
            );
        }
        $extracharges = DhlExtracharge::getExtrachargesList($this->context->language->id);
        $extrachargeArray = array();
        foreach ($extracharges as $extracharge) {
            $extrachargeArray['extracharge_'.$extracharge['id_dhl_extracharge']] = (int) $extracharge['active'];
        }

        return array_merge(
            $defaultPackageArray,
            $defaultAddressArray,
            $extrachargeArray,
            Configuration::getMultiple($this->getBackOfficeSettingsFormFields())
        );
    }

    /**
     * @param int $idDhlAddress
     * @return array
     */
    public function getAddressFormFormValues($idDhlAddress)
    {
        $dhlAddress = new DhlAddress((int) $idDhlAddress);
        $redirectAfter = Tools::getValue('redirectAfter');

        return array(
            'id_dhl_address' => $dhlAddress->id,
            'redirectAfter'  => $redirectAfter,
            'contact_name'   => $dhlAddress->contact_name,
            'contact_email'  => $dhlAddress->contact_email,
            'contact_phone'  => $dhlAddress->contact_phone,
            'company_name'   => $dhlAddress->company_name,
            'account_import' => $dhlAddress->account_import,
            'account_export' => $dhlAddress->account_export,
            'account_duty'   => $dhlAddress->account_duty,
            'vat_number'     => $dhlAddress->vat_number,
            'address1'       => $dhlAddress->address1,
            'address2'       => $dhlAddress->address2,
            'address3'       => $dhlAddress->address3,
            'id_country'     => $dhlAddress->id_country != null ? $dhlAddress->id_country : Country::getByIso('FR'),
            'id_state'       => $dhlAddress->id_state != null ? $dhlAddress->id_state : 0,
            'zipcode'        => $dhlAddress->zipcode,
            'city'           => $dhlAddress->city,
            'phone'          => $dhlAddress->phone,
        );
    }

    /**
     * @param int $idDhlPackage
     * @return array
     */
    public function getPackageFormFormValues($idDhlPackage)
    {
        $dhlPackage = new DhlPackage((int) $idDhlPackage);
        $redirectAfter = Tools::getValue('redirectAfter');

        return array(
            'id_dhl_package'      => $dhlPackage->id,
            'redirectAfter'       => $redirectAfter,
            'name'                => $dhlPackage->name,
            'weight_unit'         => DhlTools::getWeightUnit(),
            'dimension_unit'      => DhlTools::getDimensionUnit(),
            'weight_value'        => $dhlPackage->weight_value,
            'length_value'        => $dhlPackage->length_value,
            'width_value'         => $dhlPackage->width_value,
            'depth_value'         => $dhlPackage->depth_value,
            'dhl_dimensions_cast' => $this->l('Length, width and depth values will be converted to integers.'),
        );
    }

    /**
     * @return string
     * @throws Exception
     * @throws SmartyException
     */
    public function getContent()
    {
        require_once(dirname(__FILE__).'/classes/DhlTools.php');
        require_once(dirname(__FILE__).'/classes/DhlBackend.php');
        require_once(dirname(__FILE__).'/classes/DhlAddress.php');
        require_once(dirname(__FILE__).'/classes/DhlPackage.php');
        require_once(dirname(__FILE__).'/classes/DhlService.php');
        require_once(dirname(__FILE__).'/api/loader.php');

        $output = '';
        $this->context->smarty->assign('active', 'intro');
        if (Tools::isSubmit('submitDhlAccount')) {
            $output .= $this->postProcessSettings($this->getAccountSettingsFormFields());
            $this->context->smarty->assign('active', 'account');
        }
        if (Tools::isSubmit('submitAccountCheck')) {
            $output .= $this->postProcessAccountCheck();
            $this->context->smarty->assign('active', 'account');
        }
        if (Tools::isSubmit('submitFrontOfficeSettings')) {
            $output .= $this->postProcessServices();
            $this->context->smarty->assign('active', 'fo');
        }
        if (Tools::isSubmit('submitBillingAccount')) {
            $output .= $this->postProcessSettings($this->getBackOfficeSettingsFormFields('BillingAccount'));
            $this->context->smarty->assign('active', 'bo');
        }
        if (Tools::isSubmit('submitDefaultSenderAddress')) {
            $output .= $this->postProcessSettings($this->getBackOfficeSettingsFormFields('SenderAddress'));
            $this->context->smarty->assign('active', 'bo');
        }
        if (Tools::isSubmit('submitDefaultShipmentDetails')) {
            $output .= $this->postProcessSettings($this->getBackOfficeSettingsFormFields('ShipmentDetails'));
            $this->context->smarty->assign('active', 'bo');
        }
        if (Tools::isSubmit('submitDefaultExtracharges')) {
            $output .= $this->postProcessExtracharges();
            $this->context->smarty->assign('active', 'bo');
        }
        if (Tools::isSubmit('addNewAddress')) {
            $this->context->smarty->assign(array('active' => 'addresses', 'addNewAddress' => 1));
        }
        if (Tools::isSubmit('viewAddresses')) {
            $this->context->smarty->assign(array('active' => 'addresses'));
        }
        if (Tools::isSubmit('submitAddressForm')) {
            $output .= $this->postProcessAddress();
            $this->context->smarty->assign('active', 'addresses');
        }
        if (Tools::isSubmit('deleteAddress')) {
            $output .= $this->postProcessDeleteAddress();
            $this->context->smarty->assign('active', 'addresses');
        }
        if (Tools::isSubmit('addNewPackage')) {
            $this->context->smarty->assign(array('active' => 'packages', 'addNewPackage' => 1));
        }
        if (Tools::isSubmit('viewPackages')) {
            $this->context->smarty->assign(array('active' => 'packages'));
        }
        if (Tools::isSubmit('submitPackageForm')) {
            $output .= $this->postProcessPackage();
            $this->context->smarty->assign('active', 'packages');
        }
        if (Tools::isSubmit('deletePackage')) {
            $output .= $this->postProcessDeletePackage();
            $this->context->smarty->assign('active', 'packages');
        }

        $backend = new DhlBackend();
        $accountSettings = $backend->renderAccountSettingsForm();
        $frontOfficeSettings = $backend->renderFrontOfficeSettingsForm();
        $backOfficeSettings = $backend->renderBackOfficeSettingsForm();
        $newAddressForm = $backend->renderAddressForm();
        $newPackageForm = $backend->renderPackageForm();
        $this->context->smarty->assign(
            array(
                'accountSettings'     => $accountSettings,
                'frontOfficeSettings' => $frontOfficeSettings,
                'backOfficeSettings'  => $backOfficeSettings,
                'newAddressForm'      => $newAddressForm,
                'newPackageForm'      => $newPackageForm,
                'dhl_addresses'       => DhlAddress::getAddressList(),
                'dhl_packages'        => DhlPackage::getPackageList(),
                'weight_unit'         => DhlTools::getWeightUnit(),
                'dimension_unit'      => DhlTools::getDimensionUnit(),
                'link'                => $this->context->link,
                'dhl_img_path'        => $this->_path.'views/img/',
            )
        );

        return $output.$this->context->smarty->fetch($this->local_path.'views/templates/admin/dhl-layout.tpl');
    }

    /**
     *
     * @param array $products
     * @param bool  $withQuantities
     * @return array
     */
    public function getCartPieces($products, $withQuantities = true)
    {
        $productsParam = array();
        foreach ($products as $product) {
            $i = 0;
            $qty = $product['quantity'];
            if ($withQuantities) {
                while ($i < $qty) {
                    $productsParam[] = array(
                        'PieceID' => $product['id_product'],
                        'Height'  => Tools::ps_round($product['height'], 3),
                        'Depth'   => Tools::ps_round($product['depth'], 3),
                        'Width'   => Tools::ps_round($product['width'], 3),
                        'Weight'  => Tools::ps_round($product['weight'], 3) == 0 ? 0.001 : Tools::ps_round(
                            $product['weight'],
                            3
                        ),
                    );
                    $i++;
                }
            } else {
                $weight = Tools::ps_round($product['weight'], 3) == 0 ? 0.001 : Tools::ps_round($product['weight'], 3);
                $weight *= $qty;
                $productsParam[] = array(
                    'PieceID' => $product['id_product'],
                    'Height'  => Tools::ps_round($product['height'], 3),
                    'Depth'   => Tools::ps_round($product['depth'], 3),
                    'Width'   => Tools::ps_round($product['width'], 3),
                    'Weight'  => $weight,
                );
            }
        }

        return $productsParam;
    }

    /**
     *
     * @param DhlOrder $dhlOrder
     * @return bool|string
     * @throws Exception
     * @throws PrestaShopDatabaseException
     * @throws SmartyException
     */
    public function getDhlShipmentDetailsTable(DhlOrder $dhlOrder)
    {
        require_once(dirname(__FILE__).'/classes/DhlTracking.php');

        $idDhlOrder = $dhlOrder->id;
        if (!Validate::isLoadedObject($dhlOrder)) {
            return false;
        }
        $labelIds = $dhlOrder->getLabelIds();
        $dhlLabels = array();
        if ($labelIds) {
            foreach ($labelIds as $label) {
                $dhlLabel = new DhlLabel((int) $label['id_dhl_label']);
                if (!Validate::isLoadedObject($dhlLabel)) {
                    continue;
                }
                $idDhlTracking = (int) $dhlLabel->getLastTrackingStatusKnown();
                $dhlTracking = new DhlTracking((int) $idDhlTracking, $this->context->language->id);
                $dhlCI = DhlCommercialInvoice::getByIdDhlLabel((int) $dhlLabel->id);
                $idDhlCI = $dhlCI ? $dhlCI->id : 0;
                $dhlReturnLabel = $dhlLabel->getDhlReturnLabel();
                $idDhlReturnLabel = $dhlReturnLabel ? $dhlReturnLabel->id : 0;
                if (Validate::isLoadedObject($dhlTracking)) {
                    $isTracked = true;
                    $lastUpdate = $dhlLabel->getLastTrackingUpdate();
                    $lastTrackingStatusKnown = $dhlTracking->description;
                } else {
                    $isTracked = false;
                    $lastUpdate = false;
                    $lastTrackingStatusKnown = '-';
                }
                $dhlLabels[] = array(
                    'id_dhl_order'              => $idDhlOrder,
                    'id_dhl_label'              => $dhlLabel->id,
                    'id_dhl_commercial_invoice' => $idDhlCI,
                    'id_dhl_return_label'       => $idDhlReturnLabel,
                    'awb_number'                => $dhlLabel->awb_number,
                    'tracking_url'              => str_replace('@', $dhlLabel->awb_number, DhlTools::DHL_URL_TRACKING),
                    'last_status_known'         => $lastTrackingStatusKnown,
                    'last_status_update'        => $lastUpdate,
                    'is_tracked'                => $isTracked,
                );
            }
        }
        $this->context->smarty->assign(
            array(
                'link'          => $this->context->link,
                'id_order'      => (int) $dhlOrder->id_order,
                'dhl_shipments' => $dhlLabels,
            )
        );
        $htmlTable = $this->context->smarty->fetch(
            $this->getLocalPath().'views/templates/admin/dhl_orders/_partials/dhl-shipment-details-table.tpl'
        );

        return $htmlTable;
    }

    /**
     *
     * @param DhlOrder $dhlOrder
     * @return string
     * @throws Exception
     * @throws SmartyException
     */
    public function getDhlShipmentDetails(DhlOrder $dhlOrder)
    {
        $htmlTable = $this->getDhlShipmentDetailsTable($dhlOrder);
        $this->context->smarty->assign(
            array(
                'dhl_img_path'                => $this->_path.'views/img/',
                'link'                        => $this->context->link,
                'id_order'                    => (int) $dhlOrder->id_order,
                'id_dhl_order'                => (int) $dhlOrder->id,
                'html_shipment_details_table' => $htmlTable,
            )
        );
        $html = $this->context->smarty->fetch(
            $this->getLocalPath().'views/templates/admin/dhl_orders/_partials/dhl-shipment-details.tpl'
        );

        return $html;
    }

    /**
     * @param Cart       $cart
     * @param float      $shippingCost
     * @param array|null $products
     * @return float|bool
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function getPackageShippingCost($cart, $shippingCost, $products)
    {
        require_once(dirname(__FILE__).'/api/loader.php');
        require_once(dirname(__FILE__).'/classes/DhlAddress.php');
        require_once(dirname(__FILE__).'/classes/DhlTools.php');
        require_once(dirname(__FILE__).'/classes/DhlService.php');
        require_once(dirname(__FILE__).'/classes/DhlExtracharge.php');
        require_once(dirname(__FILE__).'/classes/DhlPackage.php');
        require_once(dirname(__FILE__).'/classes/logger/loader.php');

        if (Configuration::get('DHL_ENABLE_LOG')) {
            $version = str_replace('.', '_', $this->version);
            $hash = Tools::encrypt(_PS_MODULE_DIR_.$this->name.'/logs/');
            $file = dirname(__FILE__).'/logs/dhlexpress_'.$hash.'.log';
            $logger = new DhlLogger('DHL_'.$version.'_Quotation', new DhlFileHandler($file));
        } else {
            $logger = new DhlLogger('', new DhlNullHandler());
        }

        if (!Configuration::get('DHL_USE_DHL_PRICES')) {
            $logger->info('Returning price from native carrier setup.');

            return $shippingCost;
        }
        $credentials = DhlTools::getCredentials();
        if (!$credentials['SiteID'] || !$credentials['Password']) {
            $logger->error('No SiteID or no Password.');

            return false;
        }
        $logger->info('Processing carrier #'.$this->id_carrier);
        $carrier = new Carrier($this->id_carrier);
        $idCarrierReference = $carrier->id_reference;
        $defaultSenderAddress = Configuration::get('DHL_DEFAULT_SENDER_ADDRESS');
        $customerAddress = new Address($cart->id_address_delivery);
        if (!Validate::isLoadedObject($customerAddress)) {
            return false;
        }
        if ($defaultSenderAddress) {
            $dhlAddress = new DhlAddress((int) $defaultSenderAddress);
        } else {
            $dhlAddress = DhlAddress::getFirstAddress();
        }
        if (!Validate::isLoadedObject($dhlAddress)) {
            $logger->error('Sender address not valid.');

            return false;
        }
        $doc = (int) Configuration::get('DHL_SENDING_DOC');
        $orderCurrency = new Currency((int) $cart->id_currency);
        $customerAddressIso = DHLTools::getIsoCountryById($customerAddress->id_country);
        $senderAddressIso = DHLTools::getIsoCountryById($dhlAddress->id_country);
        $destinationType = DhlTools::getDestinationType($dhlAddress->iso_country, $customerAddressIso);
        $globalProductCode = DhlService::getProductCodeByIdCarrierDestination(
            $idCarrierReference,
            $destinationType,
            $doc
        );

        // If the service associated to this shipping is not enabled, we don't display it
        if (!$globalProductCode) {
            $logger->info(
                'Service not enabled for carrier #'.$idCarrierReference.' - Dest. '.$destinationType.' - Doc '.$doc
            );

            return false;
        }

        // Otherwise, we make the request. If the product code is available we return the price, tax excl.
        // If not available we don't display the shipping option.
        $credentials = DhlTools::getCredentials();
        $quoteRequest = new DhlQuoteRequest($credentials);
        $quoteRequest->setSender(
            array(
                'CountryCode' => $senderAddressIso,
                'Postalcode'  => $dhlAddress->zipcode,
                'City'        => $dhlAddress->city,
            )
        );
        if (!$doc && DhlTools::isDeclaredValueRequired($customerAddressIso, $senderAddressIso)) {
            $quoteRequest->setDuty(
                array(
                    'DeclaredValue'    => $cart->getOrderTotal(false, Cart::ONLY_PRODUCTS, $products),
                    'DeclaredCurrency' => $orderCurrency->iso_code,
                )
            );
        }
        $quoteRequest->setReceiver(
            array(
                'CountryCode' => DhlTools::getIsoCountryById($customerAddress->id_country),
                'Postalcode'  => $customerAddress->postcode,
                'City'        => $customerAddress->city,
            )
        );

        if (!Configuration::get('DHL_USE_PREDEFINED_PACKAGES')) {
            $logger->info('Using pre-defined package.');
            $defaultPackage = new DhlPackage((int) Configuration::get('DHL_DEFAULT_PACKAGE'));
            if (!Validate::isLoadedObject($defaultPackage)) {
                $defaultPackage = DhlPackage::getFirstPackage();
                if (!Validate::isLoadedObject($defaultPackage)) {
                    $logger->error('Default package is not valid.');

                    return false;
                }
            }
            $productsParam = array(
                array(
                    'PieceID' => $defaultPackage->id,
                    'Height'  => Tools::ps_round($defaultPackage->length_value, 3),
                    'Depth'   => Tools::ps_round($defaultPackage->depth_value, 3),
                    'Width'   => Tools::ps_round($defaultPackage->width_value, 3),
                    'Weight'  => Tools::ps_round($defaultPackage->weight_value, 3),
                ),
            );
        } else {
            $logger->info('Using weight & size of catalog.');
            $productsParam = $this->getCartPieces($cart->getProducts());
            if (count($productsParam) > 99) {
                $productsParam = $this->getCartPieces($cart->getProducts(), false);
            }
        }

        $idPaymentCountry = Configuration::get('DHL_ACCOUNT_OWNER_COUNTRY');
        $accountNumber = $dhlAddress->getAccountNumber();
        $quoteRequest->setPackageDetails(
            array(
                'PaymentCountryCode'   => DhlTools::getIsoCountryById((int) $idPaymentCountry),
                'Date'                 => date('Y-m-d'),
                'ReadyTime'            => 'PT'.date('H').'H'.date('i').'M',
                'DimensionUnit'        => Tools::strtoupper(DhlTools::getDimensionUnit()),
                'WeightUnit'           => Tools::strtoupper(DhlTools::getWeightUnit()),
                'Pieces'               => $productsParam,
                'PaymentAccountNumber' => $accountNumber,
            )
        );
        $extracharges = DhlExtracharge::getExtrachargesList($this->context->language->id);
        $availableExtracharges = DhlTools::getExtraCharges($extracharges);
        $quoteRequest->setQtdShp($availableExtracharges);
        $client = new DhlClient(1);
        $client->setRequest($quoteRequest);
        if (is_array($products) && !empty($products)) {
            $productString = implode(
                '|',
                array_map(
                    function ($entry) {
                        return isset($entry['id_product']) ? $entry['id_product'].'_'.$entry['quantity'] : 0;
                    },
                    $products
                )
            );
            $cacheKey = 'Dhlexpress::quoteRequest_'.$productString.'_'.$dhlAddress->id.'_';
            $cacheKey .= $customerAddress->id.'_'.date('Ymd');
        } else {
            $cacheKey = 'Dhlexpress::quoteRequest_'.$dhlAddress->id.'_'.$customerAddress->id.'_'.date('Ymd');
        }
        if (!Cache::isStored($cacheKey)) {
            $logger->logXmlRequest($quoteRequest);
            $response = $client->request();
            $logger->info('Response received.', array('response' => $response));
        } else {
            $logger->info('Retrieving from cache');
            $response = Cache::retrieve($cacheKey);
        }

        if ($response && $response instanceof DhlQuoteResponse) {
            $errors = $response->getErrors();
            if (empty($errors)) {
                $services = $response->getServiceDetails();
                $logger->info('Services found.', array('services' => $services));
                if (isset($services['currency']) && $services['currency']) {
                    // We store Response in cache only if there's at least one service
                    // Therefore, if DHL system is down temporarily it doesn't prevent customer from purchasing later
                    Cache::store($cacheKey, $response);
                }

                // We added a space to allow us to sort services by prices if needed
                // Some browsers like Chrome ignore the order and sort the array themselves.
                if (isset($services['services'][' '.$globalProductCode])) {
                    $service = $services['services'][' '.$globalProductCode];
                    $priceWithTaxes = (float) $service['ShippingCharge'];
                    $taxes = (float) $service['TotalTaxAmount'];
                    $priceWithoutTaxes = $priceWithTaxes - $taxes;
                    $isoDhlCurrency = $service['CurrencyCode'];
                    $idCurrencyDhl = Currency::getIdByIsoCode($isoDhlCurrency);

                    // If the currency returned by DHL does not exist in the shop, we cannot display prices
                    if (!$idCurrencyDhl) {
                        $logger->error('Currency '.$service['CurrencyCode'].' unknown');

                        return false;
                    }

                    $dhlCurrency = new Currency((int) $idCurrencyDhl);
                    $customerCurrency = new Currency((int) $cart->id_currency);
                    $totalCartWithTaxes = $cart->getOrderTotal(true, Cart::ONLY_PRODUCTS, $products);
                    $totalCartWithTaxes = $totalCartWithTaxes / $customerCurrency->conversion_rate;
                    $idZoneDelivery = Country::getIdZone((int) $customerAddress->id_country);
                    if (Configuration::get('DHL_ENABLE_FREE_SHIPPING_FROM')) {
                        $francoAmount = Configuration::get('DHL_FRANCO_'.(int) $idZoneDelivery);
                        if ($francoAmount && $francoAmount <= $totalCartWithTaxes) {
                            return 0;
                        }
                    }
                    if (!Validate::isLoadedObject($dhlCurrency) || !Validate::isLoadedObject($customerCurrency)) {
                        return false;
                    }

                    $handlingFees = Configuration::get('PS_SHIPPING_HANDLING');
                    // Adding handling charges
                    if ($carrier->shipping_handling) {
                        $priceWithoutTaxes += (float) $handlingFees;
                    }

                    // Additional Shipping Cost per product
                    foreach ($products as $p) {
                        if (!$p['is_virtual']) {
                            $priceWithoutTaxes += $p['additional_shipping_cost'] * (int) $p['cart_quantity'];
                        }
                    }

                    // We need to convert the price in the default currency of the shop
                    $priceWithoutTaxes = $priceWithoutTaxes / $dhlCurrency->conversion_rate;
                    // If price weighting is enabled, we calculate the increase now
                    if (Configuration::get('DHL_WEIGHT_PRICES')) {
                        if (Configuration::get('DHL_WEIGHTING_TYPE') == 'percent') {
                            $percent = (float) Configuration::get('DHL_WEIGHTING_VALUE_PERCENT');
                            if ($percent) {
                                $percent = 1 + ($percent / 100);
                                $priceWithoutTaxes *= $percent;
                            }
                        } else {
                            $priceWithoutTaxes += (float) Configuration::get('DHL_WEIGHTING_VALUE_AMOUNT');
                        }
                    }
                    // Then we convert it in the currency of the cart
                    $priceWithoutTaxes = $priceWithoutTaxes * $customerCurrency->conversion_rate;
                    $logger->info('Service found for this shipment. Price without taxes: '.(float) $priceWithoutTaxes);

                    return $priceWithoutTaxes;
                } else {
                    $logger->info('No service available for this shipment.');

                    return false;
                }
            } else {
                $logger->error('Errors found.', array('errors' => $errors));

                return false;
            }
        } else {
            $logger->error('Cannot connect to DHL API.');

            return false;
        }
    }

    /**
     * @param Cart  $cart
     * @param float $shippingCost
     * @return bool|float
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function getOrderShippingCost($cart, $shippingCost)
    {
        return $this->getPackageShippingCost($cart, $shippingCost, null);
    }

    /**
     * @param array $params
     * @return bool|float
     */
    public function getOrderShippingCostExternal($params)
    {
        return false;
    }

    /**
     * Return the highest status the shipment has reached so far (highest = OK = delivered)
     *
     * @param $statuses
     * @param $dhlCheckpointsWithStatus
     * @return array|bool
     */
    public function getHighestStatus($statuses, $dhlCheckpointsWithStatus)
    {
        $dhlCodes = array_keys($dhlCheckpointsWithStatus);
        foreach ($dhlCodes as $dhlCode) {
            $awbNumber = array_search($dhlCode, $statuses);
            if (false !== $awbNumber) {
                return array(
                    'id_order_state' => $dhlCheckpointsWithStatus[$statuses[$awbNumber]],
                    'awb_number'     => $awbNumber,
                );
            }
        }

        return false;
    }

    /**
     *
     * @param $ordersToTrack
     * @return array
     */
    public function getAwbNumbers($ordersToTrack)
    {
        $awbNumbers = array();
        foreach ($ordersToTrack as $labels) {
            $numbers = array_keys($labels);
            foreach ($numbers as $number) {
                $awbNumbers[] = $number;
            }
        }

        return $awbNumbers;
    }

    /**
     *
     * @param $ordersToTrack
     * @return string
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function updateShipmentTracking($ordersToTrack)
    {
        require_once(dirname(__FILE__).'/classes/DhlTracking.php');
        require_once(dirname(__FILE__).'/classes/logger/loader.php');

        if (Configuration::get('DHL_ENABLE_LOG')) {
            $version = str_replace('.', '_', $this->version);
            $hash = Tools::encrypt(_PS_MODULE_DIR_.$this->name.'/logs/');
            $file = dirname(__FILE__).'/logs/dhlexpress_'.$hash.'.log';
            $logger = new DhlLogger('DHL_'.$version.'_Tracking', new DhlFileHandler($file));
        } else {
            $logger = new DhlLogger('', new DhlNullHandler());
        }
        $logger->info('Method updateShipmentTracking', array('orders_to_track' => $ordersToTrack));
        if ((is_array($ordersToTrack) && empty($ordersToTrack)) || !is_array($ordersToTrack)) {
            $logger->info('No orders to track');
            $this->context->controller->confirmations[] = $this->l('Order trackings are already up-to-date.');

            return $this->l('Order trackings are already up-to-date.');
        }
        $credentials = DhlTools::getCredentials();
        $track = new DhlTrackingRequest($credentials);
        $track->setLanguageCode('en');
        $track->setAwbNumber($this->getAwbNumbers($ordersToTrack));
        $client = new DhlClient(1);
        $client->setRequest($track);
        $logger->logXmlRequest($track);
        $response = $client->request();
        if ($response && $response instanceof DhlTrackingResponse) {
            $errors = $response->getErrors();
            $logger->info('Response received.', array('tracking_resp' => $response));
            if (empty($errors)) {
                $shipmentsHistory = $response->getCheckpointHistory();
                $dhlCheckpointsWithStatus = array(
                    'OK' => (int) Configuration::get('PS_OS_DELIVERED'),
                    'WC' => (int) Configuration::get('DHL_OS_DELIVERY'),
                    'PU' => (int) Configuration::get('PS_OS_SHIPPING'),
                );
                $dhlCheckpoints = DhlTracking::getList();
                if (is_array($shipmentsHistory)) {
                    foreach ($ordersToTrack as $idOrder => $dhlLabels) {
                        $logger->info('Processing Order #'.(int) $idOrder.'.');
                        $statuses = array();
                        $order = new Order((int) $idOrder);
                        if (!Validate::isLoadedObject($order)) {
                            $logger->error('Invalid order.');
                            continue;
                        }
                        foreach ($dhlLabels as $awbNumber => $idDhlLabel) {
                            if (!isset($shipmentsHistory[$awbNumber])) {
                                $logger->info('No history for shipment #'.$awbNumber);
                                continue;
                            }
                            $shipmentHistory = array_reverse($shipmentsHistory[$awbNumber], true);
                            $logger->info('History for shipment #'.$awbNumber, array('history' => $shipmentHistory));
                            $update = 0;
                            $dhlLabel = new DhlLabel((int) $idDhlLabel);
                            foreach ($shipmentHistory as $dhlCode) {
                                if (isset($dhlCheckpoints[$dhlCode])) {
                                    $logger->info('Checkpoint '.$dhlCode);
                                    if (!$update) {
                                        // add / update dhl order tracking
                                        $idDhlTracking = $dhlCheckpoints[$dhlCode];
                                        $dhlOrder = DhlOrder::getByIdOrder((int) $idOrder);
                                        if (!Validate::isLoadedObject($dhlOrder)) {
                                            $logger->error('Invalid Dhl Order.');
                                            break;
                                        }
                                        if ($dhlLabel->getLastTrackingStatusKnown()) {
                                            $logger->info(
                                                'Update shipment_tracking row.',
                                                array('id_dhl_tracking' => $idDhlTracking)
                                            );
                                            Db::getInstance()->update(
                                                'dhl_shipment_tracking',
                                                array(
                                                    'id_dhl_tracking' => (int) $idDhlTracking,
                                                    'date_upd'        => date('Y-m-d H:i:s'),
                                                ),
                                                'id_dhl_label = '.(int) $idDhlLabel
                                            );
                                        } else {
                                            $logger->info(
                                                'Create shipment_tracking row.',
                                                array('id_dhl_tracking' => $idDhlTracking)
                                            );
                                            Db::getInstance()->insert(
                                                'dhl_shipment_tracking',
                                                array(
                                                    'id_dhl_label'    => (int) $idDhlLabel,
                                                    'id_dhl_order'    => (int) $dhlOrder->id,
                                                    'id_dhl_tracking' => (int) $idDhlTracking,
                                                    'date_upd'        => date('Y-m-d H:i:s'),
                                                )
                                            );
                                            $customer = new Customer((int) $order->id_customer);
                                            $carrier = new Carrier((int) $order->id_carrier, $order->id_lang);
                                            if (!Validate::isLoadedObject($customer) ||
                                                !Validate::isLoadedObject($carrier)
                                            ) {
                                                $logger->error('Invalid Customer or Carrier.');
                                                break;
                                            }
                                            $logger->info('Send "in-transit" mail with shipping number.');
                                            DhlTools::sendInTransitMail(
                                                $order,
                                                $customer,
                                                $carrier,
                                                $awbNumber
                                            );
                                        }
                                        $update = 1;
                                    }
                                    // Update PrestaShop order status if checkpoints exists
                                    if (isset($dhlCheckpointsWithStatus[$dhlCode])) {
                                        $logger->info('DHL Code found: '.$dhlCode.'. Need order status update.');
                                        $statuses[$awbNumber] = $dhlCode;
                                        break;
                                    }
                                }
                            }
                        }
                        $highestStatus = $this->getHighestStatus($statuses, $dhlCheckpointsWithStatus);
                        if (!$highestStatus) {
                            $logger->info('No highest status.');
                            continue;
                        }
                        $logger->info('Highest status found.', array('status' => $highestStatus));
                        $idStatus = $highestStatus['id_order_state'];
                        $awbNumber = $highestStatus['awb_number'];
                        $statusesHistory = $order->getHistory($this->context->language->id, $idStatus);
                        if (is_array($statusesHistory) && !empty($statusesHistory)) {
                            // The order has or had this status in its history, so we do nothing
                            $logger->info('Order has or had this status in its history.');
                        } elseif (is_array($statusesHistory)) {
                            // The order never had this status in the past, we need to update the status
                            $logger->info('Order never had this status in its history.');

                            $history = new OrderHistory();
                            $history->id_order = (int) $order->id;
                            $history->changeIdOrderState($idStatus, (int) $order->id);
                            $history->addWithemail();

                            $logger->info('PrestaShop status updated');

                            // We also need to add the tracking number and send an email to the customer
                            // if order has no tracking number yet
                            $orderCarrier = DhlOrderCarrier::getByIdOrder((int) $order->id);
                            if (Validate::isLoadedObject($orderCarrier) && !$orderCarrier->tracking_number) {
                                $orderCarrier->tracking_number = pSQL($awbNumber);
                                $orderCarrier->save();
                                $logger->info('Tracking number updated in customer order.');
                            }
                        }
                    }
                    $logger->info('Tracking updated.');
                    $this->context->controller->confirmations[] = $this->l('Tracking updated.');

                    return $this->l('Tracking updated.');
                } else {
                    $logger->info('Tracking is already up-to-date.');
                    $this->context->controller->confirmations[] = $this->l('Tracking is already up-to-date.');

                    return $this->l('Tracking is already up-to-date.');
                }
            } else {
                $logger->error('Cannot update tracking. Errors found.', array('errors' => $errors));
                $this->adminDisplayWarning($this->l('Cannot update tracking.'));

                return $this->l('Cannot update tracking.');
            }
        } else {
            $logger->error('Cannot connect to DHL API.');
            $this->adminDisplayWarning($this->l('Cannot connect to DHL API.'));

            return $this->l('Cannot connect to DHL API.');
        }
    }

    /**
     *
     */
    public function hookActionAdminControllerSetMedia()
    {
        if (Tools::getValue('module_name') == $this->name || Tools::getValue('configure') == $this->name) {
            $this->context->controller->addCSS($this->_path.'views/css/header.back.css');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
            $this->context->controller->addJS($this->_path.'views/js/back.js');
        }
        if (in_array(Tools::getValue('controller'), $this->controllers)) {
            $this->context->controller->addCSS($this->_path.'views/css/header.back.css');
            $this->context->controller->addCSS($this->_path.'views/css/admin.back.css');
            $this->context->controller->addJS($this->_path.'views/js/admin.back.js');
        }
    }

    /**
     * @param array $params
     * @return bool
     * @throws PrestaShopException
     */
    public function hookNewOrder($params)
    {
        require_once(dirname(__FILE__).'/classes/DhlAddress.php');
        require_once(dirname(__FILE__).'/classes/DhlTools.php');
        require_once(dirname(__FILE__).'/classes/DhlService.php');
        require_once(dirname(__FILE__).'/classes/DhlOrder.php');
        require_once(dirname(__FILE__).'/classes/logger/loader.php');

        if (Configuration::get('DHL_ENABLE_LOG')) {
            $version = str_replace('.', '_', $this->version);
            $hash = Tools::encrypt(_PS_MODULE_DIR_.$this->name.'/logs/');
            $file = dirname(__FILE__).'/logs/dhlexpress_'.$hash.'.log';
            $logger = new DhlLogger('DHL_'.$version.'_HookNewOrder', new DhlFileHandler($file));
        } else {
            $logger = new DhlLogger('', new DhlNullHandler());
        }

        $logger->info('Hook newOrder called.');
        $order = new Order((int) $params['order']->id);
        if (Validate::isLoadedObject($order) && $order->current_state != Configuration::get('PS_OS_ERROR')) {
            $carrier = new Carrier((int) $order->id_carrier);
            $idCarrierReference = $carrier->id_reference;
            if (false === DhlService::isDhlCarrier($idCarrierReference)) {
                $logger->info('Not a DHL Carrier.', array('id' => (int) $idCarrierReference));

                return true;
            }
            $idSenderAddress = Configuration::get('DHL_DEFAULT_SENDER_ADDRESS');
            $senderAddress = new DhlAddress((int) $idSenderAddress);
            if (!Validate::isLoadedObject($senderAddress)) {
                $logger->error('Not a valid sender address.');

                return true;
            }
            $doc = (int) Configuration::get('DHL_SENDING_DOC');
            $senderAddressIso = DhlTools::getIsoCountryById((int) $senderAddress->id_country);
            $customerAddress = new Address((int) $order->id_address_delivery);
            $customerAddressIso = DhlTools::getIsoCountryById((int) $customerAddress->id_country);
            $destinationType = DhlTools::getDestinationType($senderAddressIso, $customerAddressIso);
            $idDhlService = DhlService::getServiceByIdCarrierDestination($idCarrierReference, $destinationType, $doc);
            $dhlOrder = new DhlOrder();
            $dhlOrder->id_order = $order->id;
            $dhlOrder->id_dhl_service = (int) $idDhlService;
            $dhlOrder->save();
            $logger->info('DHL Order created', array('obj' => $dhlOrder));
        } else {
            $logger->info('Not a valid order.');
        }

        return true;
    }

    /**
     * @param array $params
     * @return bool|string
     * @throws Exception
     * @throws SmartyException
     */
    public function hookDisplayAdminOrder($params)
    {
        return $this->hookDisplayAdminOrderLeft($params);
    }

    /**
     * @param array $params
     * @return bool|string
     * @throws Exception
     * @throws SmartyException
     */
    public function hookDisplayAdminOrderLeft($params)
    {
        require_once(dirname(__FILE__).'/api/loader.php');
        require_once(dirname(__FILE__).'/classes/DhlOrder.php');
        require_once(dirname(__FILE__).'/classes/DhlLabel.php');
        require_once(dirname(__FILE__).'/classes/DhlTools.php');
        require_once(dirname(__FILE__).'/classes/DhlCommercialInvoice.php');
        require_once(dirname(__FILE__).'/classes/DhlTracking.php');

        $this->context->controller->addCSS($this->_path.'views/css/adminorder.css');
        $this->context->controller->addCSS($this->_path.'views/css/admin.shipmentdetails.css');
        $idOrder = (int) $params['id_order'];
        $dhlOrder = DhlOrder::getByIdOrder((int) $idOrder);
        if (!Validate::isLoadedObject($dhlOrder)) {
            return false;
        }

        $html = $this->getDhlShipmentDetails($dhlOrder);
        $this->context->smarty->assign(
            array(
                'html'         => $html,
                'dhl_img_path' => $this->_path.'views/img/',
            )
        );

        return $this->context->smarty->fetch($this->local_path.'views/templates/hook/dhl-admin-order-left.tpl');
    }
}
