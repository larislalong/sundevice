<?php
/**
 * 2014 4webs
 *
 * DEVELOPED By 4webs.es Prestashop Platinum Partner
 *
 * @author    4webs
 * @copyright 4webs 2018
 * @license   4webs
 * @version 4.0.2
 * @category payment_gateways
 */

include(_PS_MODULE_DIR_ . 'paypalwithfee' . DIRECTORY_SEPARATOR . 'classes/PaypalOrder.php');
include(_PS_MODULE_DIR_ . 'paypalwithfee' . DIRECTORY_SEPARATOR . 'classes/PaypalRefund.php');

if (!defined('_PS_VERSION_'))
    exit;

class PayPalwithFee extends PaymentModule {

    private $_postErrors = array();
    private $_html = '';

    public function __construct() {
        $this->name = 'paypalwithfee';
        $this->tab = 'payments_gateways';
        $this->version = '4.0.2';
        $this->author = '4webs.es';
        $this->module_key = 'a919c7484a7ecf71b1665b04f2851680';
        $this->author_address = '0xF2f66881B34D8497784cD8B138bd0dE65734b84b';
        $this->controllers = array('payment', 'generatepdf', 'error');
        $this->need_instance = 1;
        $this->ajax = true;

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => '1.6.99.99999');
        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('PayPal with Fee');
        $this->description = $this->l('PayPal payment with fee');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
        
        if (Module::isInstalled($this->name) && Module::isEnabled($this->name) && Configuration::get('PPAL_TAX_FEE')){
            if($this->isTaxRuleDeleted(Configuration::get('PPAL_TAX_FEE')))
                $this->warning = $this->l('The tax rule has been changed recently. Please you must update and check it in paypalwithfee module').'.';
        }
    }

    public function install() {

        if (!$this->installDB())
            return false;

        if (!$this->installTab())
            return false;

        if (Shop::isFeatureActive())
            Shop::setContext(Shop::CONTEXT_ALL);

        if (!$this->_iscurl())
            return false;

        if (!parent::install() OR ! $this->registerHook('payment') OR ! $this->registerHook('paymentReturn') OR ! $this->registerHook('header') OR ! $this->registerHook('adminOrder') OR ! $this->registerHook('DisplayOrderDetail') OR ! $this->registerHook('DisplayAdminOrderTabShip') OR ! $this->registerHook('DisplayAdminOrderContentShip') OR ! $this->registerHook('ActionValidateOrder') OR ! $this->registerHook('BackOfficeHeader') OR ! $this->initialize())
            return false;
        

        return true;
    }

    public function installDB() {
        $return = true;
        $return &= Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ppwf_order` ('
                . ' `id_ppwf` INT(9) NOT NULL AUTO_INCREMENT,'
                . ' `id_cart` INT(9) NOT NULL,'
                . ' `id_order` INT(9) NOT NULL,'
                . ' `total_amount` DECIMAL(20,6) NOT NULL,'
                . ' `total_paypal` DECIMAL(20,6) NOT NULL,'
                . ' `tax_rate` DECIMAL(10,2),'
                . ' `fee` DECIMAL(20,6) NOT NULL,'
                . ' `transaction_id` VARCHAR(50) NOT NULL,'
                . ' `id_shop` INT(2) NOT NULL,'
                . ' `ppwf_version` VARCHAR(6) NOT NULL,'
                . ' PRIMARY KEY (`id_ppwf`)'
                . ' ) ENGINE=' . _MYSQL_ENGINE_ . ' default CHARSET=utf8;');

        $return &= Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ppwf_order_refund` ('
                . '`id_refund` INT(9) NOT NULL AUTO_INCREMENT,'
                . '`id_ppwf` INT(9) NOT NULL,'
                . '`id_order` INT(9) NOT NULL,'
                . '`amount` DECIMAL(20,6) NOT NULL,'
                . '`transaction_id` VARCHAR(50) NOT NULL,'
                . '`date` DATETIME NOT NULL,'
                . 'PRIMARY KEY (`id_refund`)'
                . ') ENGINE=' . _MYSQL_ENGINE_ . ' default CHARSET=utf8;');

        return $return;
    }

    public function uninstall() {

        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            if (!parent::uninstall() OR ! $this->unregisterHook('payment')
                    OR ! $this->unregisterHook('paymentReturn') OR ! $this->unregisterHook('header') OR ! $this->unregisterHook('adminOrder')
                    OR ! $this->unregisterHook('DisplayOrderDetail') OR ! $this->unregisterHook('DisplayAdminOrderTabShip') OR ! $this->unregisterHook('DisplayAdminOrderContentShip') OR ! Configuration::deleteByName('PPAL_FEE_USER')
                    OR ! Configuration::deleteByName('PPAL_FEE_PASS') OR ! Configuration::deleteByName('PPAL_FEE_SIGNATURE') OR ! Configuration::deleteByName('PPAL_FEE_PERCENTAGE')
                    OR ! Configuration::deleteByName('PPAL_FEE_FIXEDFEE') OR ! Configuration::deleteByName('PPAL_FEE_TEST'))
                return false;
        }else {
            if (!parent::uninstall() OR ! $this->unregisterHook('payment')
                    OR ! $this->unregisterHook('paymentReturn') OR ! $this->unregisterHook('header') OR ! $this->unregisterHook('adminOrder')
                    OR ! $this->unregisterHook('backOfficeHeader') OR ! $this->unregisterHook('DisplayOrderDetail') OR ! $this->unregisterHook('DisplayAdminOrder') OR ! Configuration::deleteByName('PPAL_FEE_USER')
                    OR ! Configuration::deleteByName('PPAL_FEE_PASS') OR ! Configuration::deleteByName('PPAL_FEE_SIGNATURE') OR ! Configuration::deleteByName('PPAL_FEE_PERCENTAGE')
                    OR ! Configuration::deleteByName('PPAL_FEE_FIXEDFEE') OR ! Configuration::deleteByName('PPAL_FEE_TEST'))
                return false;
        }
        $this->uninstallTab();
        return true;
    }

    private function installTab() {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = 'Refundppwf';
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang)
            $tab->name[$lang['id_lang']] = 'Refund';
        $tab->id_parent = -1;
        $tab->module = $this->name;

        if ($tab->add())
            return true;
        return false;
    }

    private function uninstallTab() {
        $id_tab = (int) Tab::getIdFromClassName('Refundppwf');
        if ($id_tab) {
            $tab = new Tab($id_tab);
            $tab->delete();
        }
    }

    public function initialize() {
        Configuration::updateValue('PPAL_FEE_USER', '');
        Configuration::updateValue('PPAL_FEE_PASS', '');
        Configuration::updateValue('PPAL_FEE_TEST', 0);
        Configuration::updateValue('PPAL_FEE_SIGNATURE', '');
        Configuration::updateValue('PPAL_FEE_PERCENTAGE', '3.4');
        Configuration::updateValue('PPAL_FEE_FIXEDFEE', '0');
        Configuration::updateValue('PPAL_FEE_LIMIT', '0');
        Configuration::updateValue('PPAL_FEE_DISABLECAT', '');
        Configuration::updateValue('PPAL_TAX_FEE', 0);
        Configuration::updateValue('PPAL_CUSTOM_INVOICE', 0);
        return true;
    }

    public function get4webs() {
        $idlang = $this->context->language->id;
        $lang = Language::getIsoById($idlang);

        switch ($lang) {
            case 'es':
                $lang = 'es';
                break;
            case 'pt':
                $lang = 'pt';
                break;
            case 'fr' :
                $lang = 'fr';
                break;
            case 'it' :
                $lang = 'it';
                break;
            default:
                $lang = 'en';
                break;
        }
        $this->context->smarty->assign(array(
            'module_name' => $this->name,
            'module_path' => $this->_path,
            'module_lang' => $lang,
        ));

        return $this->context->smarty->fetch(_PS_MODULE_DIR_ . $this->name.'/views/templates/admin/module_info.tpl');
    }

    public function getContent() {
        $this->_html = (version_compare(_PS_VERSION_, '1.6', '>=') ? $this->get4webs() : '') . '<h2>' . $this->displayName . '</h2>';
        if (Tools::isSubmit("submitPaypal")) {
            $this->_postValidation();
            if (!count($this->_postErrors)) {
                $this->_postProcess();
            } else {
                foreach ($this->_postErrors as $key) {
                    $this->_html .= $this->displayError($key);
                }
            }
        }

        $this->_html .= version_compare(_PS_VERSION_, '1.6', '>=') ? $this->renderForm() : $this->renderForm();
        return $this->_html;
    }

    public function renderForm() {
        $this->context->controller->addJqueryPlugin('idTabs');

        $tax = TaxRulesGroup::getTaxRulesGroups(true);
        $tax[] = array('id_tax_rules_group' => 0, 'name' => $this->l('None'));

        $default_currency = new Currency(Configuration::get('PS_CURRENCY_DEFAULT'));
        
        $tax_id = array();
        foreach ($tax as $key => $row) {
            $tax_id[$key] = $row['id_tax_rules_group'];
        }
        array_multisort($tax_id, SORT_ASC, $tax);

        $fields_form = array(
            'form' => array(
                'tabs' => array(
                    'paypal_credentials' => $this->l('Paypal credentials'),
                    'extra_fee_limits' => $this->l('Extra fee and limits'),
                    'more_options' => $this->l('More options'),
                ),
                'legend' => array(
                    'title' => $this->l('Paypal configuration'),
                    'icon' => 'icon-wrench',
                ),
                'input' => array(
                    array(
                        'type' => 'radio',
                        'label' => $this->l('Environment'),
                        'name' => 'PPAL_FEE_TEST',
                        'is_bool' => version_compare(_PS_VERSION_, '1.6', '<') ? false : true,
                        'required' => true,
                        'class' => 't',
                        'tab' => 'paypal_credentials',
                        'values' => array(
                            array(
                                'id' => 'active_real',
                                'value' => 0,
                                'label' => $this->l('Real (ready to recieve payments).'),
                            ),
                            array(
                                'id' => 'active_test',
                                'value' => 1,
                                'label' => $this->l('Test (only sandbox test accounts).'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Username API'),
                        'name' => 'PPAL_FEE_USER',
                        'tab' => 'paypal_credentials',
                        'size' => 75,
                        'required' => true,
                        'desc' => $this->l('Please enter username API from Paypal.')
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Password API'),
                        'name' => 'PPAL_FEE_PASS',
                        'tab' => 'paypal_credentials',
                        'size' => 75,
                        'required' => true,
                        'desc' => $this->l('Please enter the password API from Paypal.')
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Signature'),
                        'name' => 'PPAL_FEE_SIGNATURE',
                        'tab' => 'paypal_credentials',
                        'size' => 75,
                        'required' => true,
                        'desc' => $this->l('Please enter Paypal API signature.'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Percentage of the fee'),
                        'name' => 'PPAL_FEE_PERCENTAGE',
                        'tab' => 'extra_fee_limits',
                        'desc' => $this->l('Example: 5% amount. Set 0 to disable.'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Fixed fee (optional)'),
                        'name' => 'PPAL_FEE_FIXEDFEE',
                        'tab' => 'extra_fee_limits',
                        'desc' => $this->l('Example: 10€ amount. Set 0 to disable.'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Limit payment to'),
                        'name' => 'PPAL_FEE_LIMIT',
                        'suffix' => $default_currency->getSign(),
                        'tab' => 'extra_fee_limits',
                        'desc' => $this->l('Maximum payment for Paypal in store. Set 0 to disable.'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Disable Paypal by category'),
                        'name' => 'PPAL_FEE_DISABLECAT',
                        'tab' => 'extra_fee_limits',
                        'desc' => $this->l('All cart that will be with any product in this categories. Enter categories by id like: 6,2,3,14'),
                    ),
                    array(
                        'type' => version_compare(_PS_VERSION_, '1.6', '<') ? 'radio' : 'switch',
                        'label' => $this->l('Round mode compatibility'),
                        'name' => 'PPAL_ROUND_MODE',
                        'tab' => 'more_options',
                        'class' => 't',
                        'desc' => $this->l('If you have problems with paypal rounding mode. Active it to rounding total.'),
                        'required' => false,
                        'is_bool' => version_compare(_PS_VERSION_, '1.6', '<') ? false : true,
                        'values' => array(
                            array(
                                'id' => 'roundmode_enabled',
                                'value' => 1,
                                'label' => version_compare(_PS_VERSION_, '1.6', '<') ? '<img src="../img/admin/enabled.gif" alt="' . $this->l('Enabled') . '" title="' . $this->l('Enabled') . '" />' . $this->l('Enabled') : $this->l('Enabled')
                            ),
                            array(
                                'id' => 'roundmode_disabled',
                                'value' => 0,
                                'label' => version_compare(_PS_VERSION_, '1.6', '<') ? '<img src="../img/admin/disabled.gif" alt="' . $this->l('Disabled') . '" title="' . $this->l('Disabled') . '" />' . $this->l('Disabled') : $this->l('Disabled')
                            )
                        ),
                    ),
                    /*array(
                        'type' => version_compare(_PS_VERSION_, '1.6', '<') ? 'radio' : 'switch',
                        'label' => $this->l('Custom invoice'),
                        'name' => 'PPAL_CUSTOM_INVOICE',
                        'tab' => 'more_options',
                        'class' => 't',
                        'desc' => $this->l('Yes = Allow download custom invoice (Paypal breakdown) || No = Custom invoice not allowed.'),
                        'required' => false,
                        'is_bool' => version_compare(_PS_VERSION_, '1.6', '<') ? false : true,
                        'values' => array(
                            array(
                                'id' => 'custom_enabled',
                                'value' => 1,
                                'label' => version_compare(_PS_VERSION_, '1.6', '<') ? '<img src="../img/admin/enabled.gif" alt="' . $this->l('Enabled') . '" title="' . $this->l('Enabled') . '" />' . $this->l('Enabled') : $this->l('Enabled')
                            ),
                            array(
                                'id' => 'custom_disabled',
                                'value' => 0,
                                'label' => version_compare(_PS_VERSION_, '1.6', '<') ? '<img src="../img/admin/disabled.gif" alt="' . $this->l('Disabled') . '" title="' . $this->l('Disabled') . '" />' . $this->l('Disabled') : $this->l('Disabled')
                            )
                        ),
                    ),*/
                    array(
                        'type' => 'select',
                        'label' => $this->l('Tax'),
                        'name' => 'PPAL_TAX_FEE',
                        'tab' => 'more_options',
                        'default' => 'none',
                        'desc' => $this->l('Select if fee use tax.'),
                        'options' => array(
                            'query' => $tax,
                            'id' => 'id_tax_rules_group',
                            'name' => 'name',
                        )
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'name' => 'submitPaypal',
                )
            ),
        );


        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submit';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        $this->fields_form = array();

        return $helper->generateForm(array($fields_form));
    }

    /*
     * @_displayForm to ps15
     */

    public function _displayForm() {
        $this->context->controller->addJqueryPlugin('idTabs');
        $tax = Tax::getTaxes($this->context->language->id, true);
        $tax[] = array('id_tax' => 0, 'name' => $this->l('None')); //add id_tax 0 (None value).
        $tax[] = array('id_tax' => '0dt', 'name' => $this->l('Customer default tax'));
        
        $tax_id = array();
        foreach ($tax as $key => $row) {
            $tax_id[$key] = $row['id_tax'];
        }
        array_multisort($tax_id, SORT_ASC, $tax); //order by key id_tax.

        $this->context->smarty->assign(array(
            'action_ppwf' => Tools::safeOutput($_SERVER['REQUEST_URI']),
            '_path' => $this->_path,
            'tax_ppwf' => $tax,
        ));

        $this->context->smarty->assign($this->getConfigFieldsValues());
        $admin_tpl_15 = $this->context->smarty->fetch(_PS_MODULE_DIR_ . $this->name . '/views/templates/admin/admin15.tpl');

        return $admin_tpl_15;
    }

    protected function _postValidation() {
        if (trim(Tools::getValue('PPAL_FEE_USER')) == '')
            $this->_postErrors[] = $this->l('Username API is required.');
        if (Tools::getValue('PPAL_FEE_PASS') == '')
            $this->_postErrors[] = $this->l('Password API is required.');
        if (trim(Tools::getValue('PPAL_FEE_SIGNATURE')) == '')
            $this->_postErrors[] = $this->l('Signature is required');
        if (trim(Tools::getValue('PPAL_FEE_LIMIT')) == '')
            $this->_postErrors[] = $this->l('Limit payment is invalid. Set 0 to disable.');
        if (trim(Tools::getValue('PPAL_FEE_PERCENTAGE')) == '')
            $this->_postErrors[] = $this->l('Percentage is invalid. Set 0 to disable.');
        if (trim(Tools::getValue('PPAL_FEE_FIXEDFEE')) == '')
            $this->_postErrors[] = $this->l('Fixed fee is invalid. Set 0 to disable.');
        if (Tools::getValue('PPAL_FEE_LIMIT'))
            if (!preg_match('/-?^[0-9]{1,10}+(?:\.[0-9]{1,2})?$/', Tools::getValue('PPAL_FEE_LIMIT')))
                $this->_postErrors[] = $this->l('Limit payment not set correctly. (Example: 12.99).');
        if (Tools::getValue('PPAL_FEE_PERCENTAGE'))
            if (!preg_match('/-?^[0-9]{1,10}+(?:\.[0-9]{1,2})?$/', Tools::getValue('PPAL_FEE_PERCENTAGE')))
                $this->_postErrors[] = $this->l('Percentage not set correctly. (Example: 3.4).');
        if (Tools::getValue('PPAL_FEE_FIXEDFEE'))
            if (!preg_match('/-?^[0-9]{1,10}+(?:\.[0-9]{1,2})?$/', Tools::getValue('PPAL_FEE_FIXEDFEE')))
                $this->_postErrors[] = $this->l('Fixed Fee not set correctly. (Example: 5.99).');
        if (Tools::getValue('PPAL_FEE_DISABLECAT'))
            if (!preg_match('/^[0-9]{1,5}(,[0-9]{1,5})*$/', Tools::getValue('PPAL_FEE_DISABLECAT')))
                $this->_postErrors[] = $this->l('Disable category not set correctly. (Example: 1,12,3). Remember separate by comma, each id category.');
    }

    protected function _postProcess() {
        if (Tools::isSubmit('submitPaypal')) {
            Configuration::updateValue('PPAL_FEE_USER', trim(Tools::getValue('PPAL_FEE_USER')));
            Configuration::updateValue('PPAL_FEE_PASS', trim(Tools::getValue('PPAL_FEE_PASS')));
            Configuration::updateValue('PPAL_FEE_SIGNATURE', trim(Tools::getValue('PPAL_FEE_SIGNATURE')));
            Configuration::updateValue('PPAL_FEE_PERCENTAGE', trim(Tools::getValue('PPAL_FEE_PERCENTAGE')));
            Configuration::updateValue('PPAL_FEE_FIXEDFEE', trim(Tools::getValue('PPAL_FEE_FIXEDFEE')));
            Configuration::updateValue('PPAL_FEE_TEST', Tools::getValue('PPAL_FEE_TEST'));
            Configuration::updateValue('PPAL_FEE_LIMIT', trim(Tools::getValue('PPAL_FEE_LIMIT')));
            Configuration::updateValue('PPAL_FEE_DISABLECAT', trim(Tools::getValue('PPAL_FEE_DISABLECAT')));
            Configuration::updateValue('PPAL_TAX_FEE', (Tools::getValue('PPAL_TAX_FEE')));
            Configuration::updateValue('PPAL_CUSTOM_INVOICE', (Tools::getValue('PPAL_CUSTOM_INVOICE')));
            Configuration::updateValue('PPAL_ROUND_MODE', (Tools::getValue('PPAL_ROUND_MODE')));
            $this->_html .= $this->displayConfirmation($this->l('Settings updated'));
        }
    }

    public function checkCurrency($cart) {
        $currency_order = new Currency($cart->id_currency);
        $currencies_module = $this->getCurrency($cart->id_currency);

        if (is_array($currencies_module)) {
            foreach ($currencies_module as $currency_module) {
                if ($currency_order->id == $currency_module['id_currency']) {
                    return true;
                }
            }
        }
        return false;
    }

    public function getConfigFieldsValues() {
        return array(
            'PPAL_FEE_USER' => Tools::getValue('PPAL_FEE_USER', Configuration::get('PPAL_FEE_USER')),
            'PPAL_FEE_TEST' => Tools::getValue('PPAL_FEE_TEST', Configuration::get('PPAL_FEE_TEST')),
            'PPAL_FEE_PASS' => Tools::getValue('PPAL_FEE_PASS', Configuration::get('PPAL_FEE_PASS')),
            'PPAL_FEE_SIGNATURE' => Tools::getValue('PPAL_FEE_SIGNATURE', Configuration::get('PPAL_FEE_SIGNATURE')),
            'PPAL_FEE_PERCENTAGE' => Tools::getValue('PPAL_FEE_PERCENTAGE', Configuration::get('PPAL_FEE_PERCENTAGE')),
            'PPAL_FEE_FIXEDFEE' => Tools::getValue('PPAL_FEE_FIXEDFEE', Configuration::get('PPAL_FEE_FIXEDFEE')),
            'PPAL_FEE_LIMIT' => Tools::getValue('PPAL_FEE_LIMIT', Configuration::get('PPAL_FEE_LIMIT')),
            'PPAL_FEE_DISABLECAT' => Tools::getValue('PPAL_FEE_DISABLECAT', Configuration::get('PPAL_FEE_DISABLECAT')),
            'PPAL_TAX_FEE' => Tools::getValue('PPAL_TAX_FEE', Configuration::get('PPAL_TAX_FEE')),
            'PPAL_CUSTOM_INVOICE' => Tools::getValue('PPAL_CUSTOM_INVOICE', Configuration::get('PPAL_CUSTOM_INVOICE')),
            'PPAL_ROUND_MODE' => Tools::getValue('PPAL_ROUND_MODE', Configuration::get('PPAL_ROUND_MODE'))
        );
    }

    public function hookHeader() {
        /*if ($this->context->controller->php_self == 'history' || $this->context->controller->php_self == 'order-detail') {
            if (Configuration::get('PS_INVOICE') && Configuration::get('PPAL_CUSTOM_INVOICE')) {
                if (version_compare(_PS_VERSION_, '1.6', '<')) {
                    $this->context->smarty->assign('ppwf_ajax_url', $this->context->link->getModuleLink($this->name, 'generatepdf'));
                    return $this->display(__FILE__, 'views/templates/hook/header15.tpl');
                } else {
                    Media::addJsDef(array('ppwf_ajax_url' => $this->context->link->getModuleLink($this->name, 'generatepdf')));
                    $this->context->controller->addJS(_PS_MODULE_DIR_ . $this->name . '/views/js/paypalwithfeePdfHistory.js');
                }
            }
        }*/
    }

    public function hookDisplayPaymentEU($params) {
        if (!$this->active && !$this->checkCurrency($params['cart']))
            return;
        
        if(Configuration::get('PPAL_TAX_FEE')){
            if($this->isTaxRuleDeleted(Configuration::get('PPAL_TAX_FEE'))){
                return;
            }
        }
        
        $blockme = false;
        $categorias_search = array();
        $limited_categories = explode(",", Configuration::get('PPAL_FEE_DISABLECAT'));
        $product_by_category = $params['cart']->getProducts(true);
        $ppal_fee_limit = Configuration::get('PPAL_FEE_LIMIT');
        if($params['cart']->id_currency != Configuration::get('PS_CURRENCY_DEFAULT')){
            $current_currency = new Currency($params['cart']->id_currency);
            $conversion_rate = $current_currency->conversion_rate;
            $ppal_fee_limit = round($ppal_fee_limit * $conversion_rate,6);
        }
        
        foreach ($product_by_category as $value) {
            $categorias_search[] = $value['id_category_default'];
            foreach ($categories = $this->getCategoriesByProduct($value['id_product']) as $category) {
                $categorias_search[] = $category['id_category'];
            }
        }

        foreach ($limited_categories as $value) {
            if (in_array($value, $categorias_search)) {
                $blockme = true;
                break;
            }
        }

        if ($ppal_fee_limit >= 0 && !$blockme) {
            $total_compare = $this->context->cart->getordertotal(true);

            if ($total_compare <= $ppal_fee_limit || $ppal_fee_limit == "0") {
                $link = new Link();
                $path_controller = Configuration::get('PS_SSL_ENABLED') == 0 ? $this->context->link->getModuleLink('paypalwithfee', 'payment') : $this->context->link->getModuleLink('paypalwithfee', 'payment', array(), true);
                $return_url = Configuration::get('PS_SSL_ENABLED') == 0 ? $this->context->link->getModuleLink('paypalwithfee', 'validation') : $this->context->link->getModuleLink('paypalwithfee', 'validation', array(), true);
                $cancel_url = Configuration::get('PS_SSL_ENABLED') == 0 ? $link->getPageLink('order') : $link->getPageLink('order', true);

                $fee = $this->getFee($this->context->cart);
                
                $this->context->smarty->assign(
                        array(
                            'ps_version' => Tools::substr(_PS_VERSION_, 0, 3),
                            'limitpay' => $ppal_fee_limit,
                            'returnURL' => $return_url,
                            'cancelURL' => $cancel_url,
                            'path_controller' => $path_controller,
                            'path' => $this->_path,
                            'fee' => $fee,
                ));
                
                $payment_options = array(
                    'cta_text' => $fee > 0 ? $this->l('Pay with paypal') . ' - ' . $this->l('fee') . ': ' . Tools::displayPrice($fee) : $this->l('Pay with paypal'),
                    'logo' => Media::getMediaPath(_PS_MODULE_DIR_ . $this->name . '/views/img/paypal-eu.png'),
                    'form' => $this->display(__FILE__, 'views/templates/front/paymentEU.tpl'),
                );

                return $payment_options;
            }
        }
    }

    public function hookPayment($params) {
        if (!$this->active && !$this->checkCurrency($params['cart']))
            return;
        
        if(Configuration::get('PPAL_TAX_FEE')){
            if($this->isTaxRuleDeleted(Configuration::get('PPAL_TAX_FEE'))){
                return;
            }
        }
        
        
        $blockme = false;
        $categorias_search = array();
        $limited_categories = explode(",", Configuration::get('PPAL_FEE_DISABLECAT'));
        $product_by_category = $params['cart']->getProducts(true);
        $ppal_fee_limit = Configuration::get('PPAL_FEE_LIMIT');
        
        /*conversion rate o fee limit if currency is not default*/
        if($params['cart']->id_currency != Configuration::get('PS_CURRENCY_DEFAULT')){
            $current_currency = new Currency($params['cart']->id_currency);
            $conversion_rate = $current_currency->conversion_rate;
            $ppal_fee_limit = round($ppal_fee_limit * $conversion_rate,6);
        }
        
        foreach ($product_by_category as $value) {
            $categorias_search[] = $value['id_category_default'];
            foreach ($this->getCategoriesByProduct($value['id_product']) as $category) {
                $categorias_search[] = $category['id_category'];
            }
        }
        
        foreach ($limited_categories as $value) {
            if (in_array($value, $categorias_search)) {
                $blockme = true;
                break;
            }
        }

        if ($ppal_fee_limit >= 0 && !$blockme) {
            $total_compare = $this->context->cart->getOrderTotal(true);

            if ($total_compare <= $ppal_fee_limit || $ppal_fee_limit == 0) {
                $link = new Link();
                $path_controller = Configuration::get('PS_SSL_ENABLED') == 0 ? $this->context->link->getModuleLink('paypalwithfee', 'payment') : $this->context->link->getModuleLink('paypalwithfee', 'payment', array(), true);
                $return_url = Configuration::get('PS_SSL_ENABLED') == 0 ? $this->context->link->getModuleLink('paypalwithfee', 'validation') : $this->context->link->getModuleLink('paypalwithfee', 'validation', array(), true);
                $cancel_url = Configuration::get('PS_SSL_ENABLED') == 0 ? $link->getPageLink('order') : $link->getPageLink('order', true);
                $cart_total = (float)($this->context->cart->getOrderTotal(true, Cart::BOTH));
                $fee = $this->getFee($this->context->cart);
                
                $this->context->smarty->assign(
                        array(
                            'ps_version' => Tools::substr(_PS_VERSION_, 0, 3),
                            'limitpay' => $ppal_fee_limit,
                            'returnURL' => $return_url,
                            'cancelURL' => $cancel_url,
                            'path_controller' => $path_controller,
                            'path' => $this->_path,
                            'fee' => $fee,
                            'total_ppwf' => $cart_total + $fee,
                ));

                return $this->display(__FILE__, 'views/templates/front/payment.tpl');
            }
        }
    }

    public function hookPaymentReturn($params) {
        if (!$this->active)
            return;

        $this->context->smarty->assign(array(
            'id_order' => $params['objOrder']->id
        ));

        return $this->display(__FILE__, 'views/templates/front/confirmation.tpl');
    }

    public function hookActionValidateOrder($params) {
        //Creating fee to manual order.
        if (Tools::isSubmit('submitAddOrder')) {
            $order = $params['order'];
            if ($order->module == $this->name) {
                $cart = new Cart($order->id_cart);
                $fee = $this->getCompleteFee($cart);//$this->getFee($this->context->cart);
        
                $fee_with_tax = $fee['fee_with_tax'];
                $fee_without_tax = $fee['fee_without_tax'];

                if($fee_without_tax > 0){
                    $customer = new Customer($cart->id_customer);
                    
                    $order->total_paid_tax_excl = (float) $order->total_paid_tax_excl + $fee_without_tax;
                    $order->total_paid_tax_incl = (float) $order->total_paid_tax_incl + $fee_with_tax;
                    $order->total_paid = $order->total_paid_tax_incl;
                    
                    $orderpayment = New OrderPayment();
                    $orderpayment->order_reference = $order->reference;
                    if (version_compare(_PS_VERSION_, '1.6', '<')) {
                        $orderpayment->id_address_delivery = $order->id_address_delivery;
                    }
                    
                    $orderpayment->id_currency = $order->id_currency;
                    $orderpayment->amount = $fee_with_tax;
                    $orderpayment->payment_method = $order->payment;
                    
                    $orderpayment->add();
                    
                    
                    //add new order detail to paypal fee [PRODUCT PAYPAL FEE]
                    if($fee_with_tax > 0){
                        $order_detail_fee = new OrderDetail();
                        $order_detail_fee->id_order = (int)$order->id;
                        $order_detail_fee->product_id = 999999999;
                        $order_detail_fee->id_shop = $order->id_shop;
                        $order_detail_fee->product_attribute_id = 0;
                        $order_detail_fee->product_name = $this->l('Paypal Fee');
                        $order_detail_fee->product_quantity = 1;
                        $order_detail_fee->product_price = $fee_without_tax;
                        $order_detail_fee->original_product_price = $fee_with_tax;
                        $order_detail_fee->unit_price_tax_incl = $fee_with_tax;
                        $order_detail_fee->unit_price_tax_excl = $fee_without_tax;
                        $order_detail_fee->total_price_tax_incl = $fee_with_tax;
                        $order_detail_fee->total_price_tax_excl = $fee_without_tax;
                        $order_detail_fee->product_reference = $this->l('PPWF');
                        $order_detail_fee->product_supplier_reference = 'PPWF';


                        $order_detail_fee->id_tax_rules_group = Configuration::get('PPAL_TAX_FEE');
                        $order_detail_fee->id_warehouse = 0;

                        //add order_detail_tax
                        if($order_detail_fee->save()){
                        //$id_order_detail_fee = Db::getInstance()->getValue('SELECT `id_order_detail` FROM `'._DB_PREFIX_.'order_detail` WHERE `id_order`='.(int)$order->id.' AND `product_id`=0 AND `product_supplier_reference`="PPWF"');

                            if($order_detail_fee->id_tax_rules_group > 0){
                                //id_tax of id_tax_rules_group
                                $vat_address = new Address($order->id_address_invoice);
                                $tax_manager_order_detail_fee = TaxManagerFactory::getManager($vat_address, Configuration::get('PPAL_TAX_FEE'),$this->context);
                                $tax_calculator = $tax_manager_order_detail_fee->getTaxCalculator();
                                $tax_computation_method = (int)$tax_calculator->computation_method;

                                if (count($tax_calculator->taxes) > 0) {
                                    if (version_compare(_PS_VERSION_, '1.6.1.0', '>=')) {
                                        $round_mode = $order->round_mode;
                                        $round_type = $order->round_type;
                                    }else{
                                        $round_mode = Configuration::get('PS_PRICE_ROUND_MODE');
                                        $round_type = Configuration::get('PS_ROUND_TYPE');
                                    }

                                    foreach ($tax_calculator->getTaxesAmount($order_detail_fee->unit_price_tax_excl) as $id_tax => $amount) {
                                        
                                            switch ($round_type) {
                                                case 1:
                                                    //$total_tax_base = $quantity * Tools::ps_round($discounted_price_tax_excl, _PS_PRICE_COMPUTE_PRECISION_, $this->round_mode);
                                                    $total_amount = 1 * Tools::ps_round($amount, _PS_PRICE_COMPUTE_PRECISION_, $round_mode);
                                                break;
                                                case 2:
                                                    //$total_tax_base = Tools::ps_round($quantity * $discounted_price_tax_excl, _PS_PRICE_COMPUTE_PRECISION_, $this->round_mode);
                                                    $total_amount = Tools::ps_round(1 * $amount, _PS_PRICE_COMPUTE_PRECISION_, $round_mode);
                                                break;
                                                case 3:
                                                    //$total_tax_base = $quantity * $discounted_price_tax_excl;
                                                    $total_amount = 1 * $amount;
                                                break;
                                            }

                                        
                                        

                                        $sql_order_detail_tax = 'INSERT INTO `'._DB_PREFIX_.'order_detail_tax` (id_order_detail, id_tax, unit_amount, total_amount)
                                        VALUES ('.(int)$order_detail_fee->id.','.(int)$id_tax.','.(float)$amount.','.(float)$total_amount.')';

                                        Db::getInstance()->execute($sql_order_detail_tax);
                                    }
                                }
                            }
                        }else{
                            //error saving fee
                        }
                    }
                }
                
                //Maybe the order not finished because can be waiting to accept paypal, but must be created.
                $id_cart = $order->id_cart;
                $id_order = $order->id;
                $amount = $order->total_paid_tax_incl;
                $id_tax = Configuration::get('PPAL_TAX_FEE');

                if($id_tax && Configuration::get('PS_TAX')){
                //search tax rule group
                    if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_delivery') {
                        $id_address = $cart->id_address_delivery;
                    }else{
                        $id_address = $cart->id_address_invoice;
                    }
                    $address = new Address($id_address);
                    $tax_manager = TaxManagerFactory::getManager($address, $id_tax);
                    $tax_calculator = $tax_manager->getTaxCalculator();
                    $tax_rate = $tax_calculator->getTotalRate();
                }else{
                    $tax_rate = 0;
                }

                $fee_amount = $fee_with_tax;
                $transaction_paypal_id = '';
                if (!empty($transaction_paypal_id))
                    $transaction_idppwf = $transaction_paypal_id;
                else
                    $transaction_idppwf = '-';


                $paypal_order = new PaypalOrderx();
                $paypal_order->id_cart = $id_cart;
                $paypal_order->id_order = $id_order;
                $paypal_order->total_amount = $amount;
                $paypal_order->total_paypal = $order->total_paid_tax_incl;
                $paypal_order->tax_rate = (float) $tax_rate;
                $paypal_order->fee = $fee_amount;
                $paypal_order->transaction_id = $transaction_idppwf;
                $paypal_order->id_shop = $this->context->cart->id_shop;
                $paypal_order->ppwf_version = $this->version;

                $paypal_order->add();
                //end paypal order
                 
            }
        }
    }

    public function hookDisplayOrderDetail() {
        /*$id_order = Tools::getValue('id_order');
        $order = new Order($id_order);

        if ($order->module == $this->name) {
            
            $ppwfv = Db::getInstance()->getValue('SELECT `ppwf_version` FROM `'._DB_PREFIX_.'ppwf_order` WHERE `id_order`='.(int)$id_order);
            
            $this->context->smarty->assign(array(
                'fee' => Tools::displayPrice(Tools::convertPrice(PaypalOrderx::getFeeDB($order->id), new Currency($order->id_currency)), new Currency($order->id_currency)),
                'id_currency' => $order->id_currency,
                'id_order_ppwf' => $id_order,
                'custom_invoice' => Configuration::get('PPAL_CUSTOM_INVOICE'),
                'href' => $ppwfv ? 'index.php?fc=module&module=paypalwithfee&controller=generatepdf&id_order='.$id_order : '',
            ));
            return $this->display(__FILE__, '/views/templates/hook/order_detail.tpl');
        }*/
    }

    public function hookDisplayAdminOrderContentShip() {
        $id_order = Tools::getValue('id_order');
        $order = new Order($id_order);
        
        if ($order->module == $this->name) {
            
            $ppwfv = Db::getInstance()->getValue('SELECT `ppwf_version` FROM `'._DB_PREFIX_.'ppwf_order` WHERE `id_order`='.(int)$id_order);
            
            if (Tools::getValue('messageppwf')) {
                    if (Tools::getValue('messageppwf') == 'ok')
                        $this->context->smarty->assign('ppwfmessage_ok', $this->l('Refund complete'));
                    else {
                        switch (Tools::getValue('messageppwf')) {
                            case 'ppwf1':
                                $this->context->smarty->assign('ppwfmessage_error', $this->l('Refund error - Transaction ID is empty.'));
                                break;
                            case 'ppwf2':
                                $this->context->smarty->assign('ppwfmessage_error', $this->l('Refund error - The amount not set correctly. (Example: 12.99)'));
                                break;
                            default:
                                $this->context->smarty->assign('ppwfmessage_error', $this->l('Refund error'));
                                break;
                        }
                    }
                }
            
            
            $link = new Link();
            $params = array('id_order' => $id_order);

            $this->context->smarty->assign(array(
                'ppwfv' => $ppwfv ? true : false,
                'form_go_ppwf_generatepdf' => $link->getModuleLink($this->name, 'generatepdf', $params),
                'form_go_ppwf_refund' => 'index.php?tab=Refundppwf&id_order=' . $id_order . '&ppwfr' . '&token=' . Tools::getAdminTokenLite('AdminOrders'),
                'invoice_number_' => $order->invoice_number,
                'invoices_collection_' => $order->getInvoicesCollection(),
                'id_currency' => $order->id_currency,
                'refund' => PaypalRefund::getRefundData($id_order),
                'max_refund' => PaypalRefund::getMaxRefundAmount($id_order),
                'paypalwf' => PaypalOrderx::getPaypalByIdOrder($id_order),
                'fee' => PaypalOrderx::getFeeData($order->id)));
            return $this->display(__FILE__, '/views/templates/hook/admin_order_content_ship.tpl');
        }
    }

    public function hookDisplayAdminOrderTabShip() {
        $id_order = Tools::getValue('id_order');
        $order = new Order($id_order);

        if ($order->module == $this->name) {
            return $this->display(__FILE__, 'views/templates/hook/admin_order_tab_ship.tpl');
        }
    }

    public function validateOrder4webs($id_cart, $id_order_state, $amount_paid, $payment_method = 'Unknown', $transaction_paypal_id,$message = NULL, $extra_vars = array(), $currency_special = NULL, $dont_touch_amount = false, $secure_key = false,$total_paypal, Shop $shop = null) {
        if (!isset($this->context))
            $this->context = Context::getContext();

        $this->context->cart = new Cart($id_cart);
        $fee = $this->getCompleteFee($this->context->cart);//$this->getFee($this->context->cart);
        $fee_with_tax = $fee['fee_with_tax'];
        $fee_without_tax = $fee['fee_without_tax'];

        $this->context->customer = new Customer($this->context->cart->id_customer);

        if (version_compare(_PS_VERSION_, '1.6.1.0', '>=')) {
            // The tax cart is loaded before the customer so re-cache the tax calculation method
            $this->context->cart->setTaxCalculationMethod();
        }

        $this->context->language = new Language($this->context->cart->id_lang);
        $this->context->shop = ($shop ? $shop : new Shop($this->context->cart->id_shop));
        if (version_compare(_PS_VERSION_, '1.6.1.0', '>='))
            ShopUrl::resetMainDomainCache();

        $id_currency = $currency_special ? (int) $currency_special : (int) $this->context->cart->id_currency;
        $this->context->currency = new Currency($id_currency, null, $this->context->shop->id);
        if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_delivery')
            $context_country = $this->context->country;


        $order_status = new OrderState((int) $id_order_state, (int) $this->context->language->id);
        if (!Validate::isLoadedObject($order_status)) {
            if (version_compare(_PS_VERSION_, '1.6.1.0', '>='))
                PrestaShopLogger::addLog('PaymentModule::validateOrder - Order Status cannot be loaded', 3, null, 'Cart', (int) $id_cart, true);

            throw new PrestaShopException('Can\'t load Order state status');
        }

        if (!$this->active) {
            if (version_compare(_PS_VERSION_, '1.6.1.0', '>='))
                PrestaShopLogger::addLog('PaymentModule::validateOrder - Module is not active', 3, null, 'Cart', (int) $id_cart, true);

            die(Tools::displayError());
        }

        // Does order already exists ?
        if (Validate::isLoadedObject($this->context->cart) && $this->context->cart->OrderExists() == false) {
            if ($secure_key !== false && $secure_key != $this->context->cart->secure_key) {
                if (version_compare(_PS_VERSION_, '1.6.1.0', '>='))
                    PrestaShopLogger::addLog('PaymentModule::validateOrder - Secure key does not match', 3, null, 'Cart', (int) $id_cart, true);

                die(Tools::displayError());
            }

            // For each package, generate an order
            $delivery_option_list = $this->context->cart->getDeliveryOptionList();
            $package_list = $this->context->cart->getPackageList();
            $cart_delivery_option = $this->context->cart->getDeliveryOption();

            // If some delivery options are not defined, or not valid, use the first valid option
            foreach ($delivery_option_list as $id_address => $package)
                if (!isset($cart_delivery_option[$id_address]) || !array_key_exists($cart_delivery_option[$id_address], $package))
                    foreach ($package as $key) {
                        $cart_delivery_option[$id_address] = $key;
                        break;
                    }

            $order_list = array();
            $order_detail_list = array();
            $reference = Order::generateReference();
            $this->currentOrderReference = $reference;

            $order_creation_failed = false;
            $cart_total_paid = (float) Tools::ps_round((float) $this->context->cart->getOrderTotal(true, Cart::BOTH), 2);


            foreach ($cart_delivery_option as $id_address => $key_carriers)
                foreach ($delivery_option_list[$id_address][$key_carriers]['carrier_list'] as $id_carrier => $data)
                    foreach ($data['package_list'] as $id_package) {
                        // Rewrite the id_warehouse
                        if (version_compare(_PS_VERSION_, '1.5.3.0', '>='))
                            $package_list[$id_address][$id_package]['id_warehouse'] = (int) $this->context->cart->getPackageIdWarehouse($package_list[$id_address][$id_package], (int) $id_carrier);

                        $package_list[$id_address][$id_package]['id_carrier'] = $id_carrier;
                    }
            // Make sure CarRule caches are empty
            CartRule::cleanCache();

            if (version_compare(_PS_VERSION_, '1.6.1.0', '>=')) {
                $cart_rules = $this->context->cart->getCartRules();
                foreach ($cart_rules as $cart_rule) {
                    if (($rule = new CartRule((int) $cart_rule['obj']->id)) && Validate::isLoadedObject($rule)) {
                        if ($error = $rule->checkValidity($this->context, true, true)) {
                            $this->context->cart->removeCartRule((int) $rule->id);
                            if (isset($this->context->cookie) && isset($this->context->cookie->id_customer) && $this->context->cookie->id_customer && !empty($rule->code)) {
                                if (Configuration::get('PS_ORDER_PROCESS_TYPE') == 1)
                                    Tools::redirect('index.php?controller=order-opc&submitAddDiscount=1&discount_name=' . urlencode($rule->code));
                                Tools::redirect('index.php?controller=order&submitAddDiscount=1&discount_name=' . urlencode($rule->code));
                            }
                            else {
                                $rule_name = isset($rule->name[(int) $this->context->cart->id_lang]) ? $rule->name[(int) $this->context->cart->id_lang] : $rule->code;
                                $error = sprintf(Tools::displayError('CartRule ID %1s (%2s) used in this cart is not valid and has been withdrawn from cart'), (int) $rule->id, $rule_name);
                                PrestaShopLogger::addLog($error, 3, '0000002', 'Cart', (int) $this->context->cart->id);
                            }
                        }
                    }
                }
            }

            $multiple_carrier = 0;

            foreach ($package_list as $id_address => $packageByAddress)
                foreach ($packageByAddress as $id_package => $package) {
                    if ($multiple_carrier > 0) //if has multiple carrier for different products // assign fee to one order.
                        $fee = 0;

                    $order = new Order();
                    $order->product_list = $package['product_list'];

                    if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_delivery') {
                        $address = new Address($id_address);
                        $this->context->country = new Country($address->id_country, $this->context->cart->id_lang);
                        if (!$this->context->country->active)
                            throw new PrestaShopException('The delivery address country is not active.');
                    }

                    $carrier = null;
                    if (!$this->context->cart->isVirtualCart() && isset($package['id_carrier'])) {
                        $carrier = new Carrier($package['id_carrier'], $this->context->cart->id_lang);
                        $order->id_carrier = (int) $carrier->id;
                        $id_carrier = (int) $carrier->id;
                    } else {
                        $order->id_carrier = 0;
                        $id_carrier = 0;
                    }

                    $order->id_customer = (int) $this->context->cart->id_customer;
                    $order->id_address_invoice = (int) $this->context->cart->id_address_invoice;
                    $order->id_address_delivery = (int) $id_address;
                    $order->id_currency = $this->context->currency->id;
                    $order->id_lang = (int) $this->context->cart->id_lang;
                    $order->id_cart = (int) $this->context->cart->id;
                    $order->reference = $reference;
                    $order->id_shop = (int) $this->context->shop->id;
                    $order->id_shop_group = (int) $this->context->shop->id_shop_group;

                    $order->secure_key = ($secure_key ? pSQL($secure_key) : pSQL($this->context->customer->secure_key));
                    $order->payment = $payment_method;
                    if (isset($this->name))
                        $order->module = $this->name;
                    $order->recyclable = $this->context->cart->recyclable;
                    $order->gift = (int) $this->context->cart->gift;
                    $order->gift_message = $this->context->cart->gift_message;
                    $order->mobile_theme = $this->context->cart->mobile_theme;
                    $order->conversion_rate = $this->context->currency->conversion_rate;
                    $amount_paid = !$dont_touch_amount ? Tools::ps_round((float) $amount_paid, 2) : $amount_paid;

                    $order->total_paid_real = 0;//(float) (Tools::ps_round((float) ($fee_with_tax), 2)); // total_paid_real only add fee, saves two sum

                    $order->total_products = (float) ($this->context->cart->getOrderTotal(false, Cart::ONLY_PRODUCTS, $order->product_list, $id_carrier) + $fee_without_tax);
                    $order->total_products_wt = (float)($this->context->cart->getOrderTotal(true, Cart::ONLY_PRODUCTS, $order->product_list, $id_carrier) + $fee_with_tax);

                    $order->total_discounts_tax_excl = (float) abs($this->context->cart->getOrderTotal(false, Cart::ONLY_DISCOUNTS, $order->product_list, $id_carrier));
                    $order->total_discounts_tax_incl = (float) abs($this->context->cart->getOrderTotal(true, Cart::ONLY_DISCOUNTS, $order->product_list, $id_carrier));
                    $order->total_discounts = $order->total_discounts_tax_incl;

                    $order->total_shipping_tax_excl = (float) $this->context->cart->getPackageShippingCost((int) $id_carrier, false, null, $order->product_list);
                    $order->total_shipping_tax_incl = (float) $this->context->cart->getPackageShippingCost((int) $id_carrier, true, null, $order->product_list);
                    $order->total_shipping = $order->total_shipping_tax_incl;

                    if (!is_null($carrier) && Validate::isLoadedObject($carrier))
                        $order->carrier_tax_rate = $carrier->getTaxesRate(new Address($this->context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')}));

                    $order->total_wrapping_tax_excl = (float) abs($this->context->cart->getOrderTotal(false, Cart::ONLY_WRAPPING, $order->product_list, $id_carrier));
                    $order->total_wrapping_tax_incl = (float) abs($this->context->cart->getOrderTotal(true, Cart::ONLY_WRAPPING, $order->product_list, $id_carrier));
                    $order->total_wrapping = $order->total_wrapping_tax_incl;

                    $order->total_paid_tax_excl = (float) Tools::ps_round((float) $this->context->cart->getOrderTotal(false, Cart::BOTH, $order->product_list, $id_carrier), 2) + $fee_without_tax;
                    $order->total_paid_tax_incl = (float) Tools::ps_round((float) $this->context->cart->getOrderTotal(true, Cart::BOTH, $order->product_list, $id_carrier),2) + $fee_with_tax;
                    $order->total_paid = $order->total_paid_tax_incl;

                    /*if ($fee > 0) {//create fee payment WHILE order is validate state
                        $orderpayment = New OrderPayment;
                        $orderpayment->order_reference = $order->reference;
                        $orderpayment->id_currency = $order->id_currency;
                        $orderpayment->amount = $fee;
                        $orderpayment->payment_method = $order->payment;

                        $orderpayment->add();
                    }*/

                    if (version_compare(_PS_VERSION_, '1.6.1.0', '>=')) {
                        $order->round_mode = Configuration::get('PS_PRICE_ROUND_MODE');
                        $order->round_type = Configuration::get('PS_ROUND_TYPE');
                    }



                    $order->invoice_date = '0000-00-00 00:00:00';
                    $order->delivery_date = '0000-00-00 00:00:00';


                    // Creating order
                    $result = $order->add();

                    if (!$result) {
                        if (version_compare(_PS_VERSION_, '1.6.1.0', '>='))
                            PrestaShopLogger::addLog('PaymentModule::validateOrder - Order cannot be created', 3, null, 'Cart', (int) $id_cart, true);

                        throw new PrestaShopException('Can\'t save Order');
                    }

                    // Amount paid by customer is not the right one -> Status = payment error
                    // We don't use the following condition to avoid the float precision issues : http://www.php.net/manual/en/language.types.float.php
                    // if ($order->total_paid != $order->total_paid_real)
                    // We use number_format in order to compare two string
                    if ($order_status->logable && number_format($cart_total_paid, 2) != number_format($amount_paid, 2))
                        $id_order_state = Configuration::get('PS_OS_ERROR');

                    $order_list[] = $order;



                    // Insert new Order detail list using cart for the current order
                    $order_detail = new OrderDetail(null, null, $this->context);
                    $order_detail->createList($order, $this->context->cart, $id_order_state, $order->product_list, 0, true, $package_list[$id_address][$id_package]['id_warehouse']);
                    $order_detail_list[] = $order_detail;

                    
                    
                    
                    //add new order detail to paypal fee [PRODUCT PAYPAL FEE]
                    if($fee_with_tax > 0){
                        $order_detail_fee = new OrderDetail();
                        $order_detail_fee->id_order = (int)$order->id;
                        $order_detail_fee->product_id = 999999999;
                        $order_detail_fee->id_shop = $order->id_shop;
                        $order_detail_fee->product_attribute_id = 0;
                        $order_detail_fee->product_name = $this->l('Paypal Fee');
                        $order_detail_fee->product_quantity = 1;
                        $order_detail_fee->product_price = $fee_without_tax;
                        $order_detail_fee->original_product_price = $fee_with_tax;
                        $order_detail_fee->unit_price_tax_incl = $fee_with_tax;
                        $order_detail_fee->unit_price_tax_excl = $fee_without_tax;
                        $order_detail_fee->total_price_tax_incl = $fee_with_tax;
                        $order_detail_fee->total_price_tax_excl = $fee_without_tax;
                        $order_detail_fee->product_reference = $this->l('PPWF');
                        $order_detail_fee->product_supplier_reference = 'PPWF';


                        $order_detail_fee->id_tax_rules_group = Configuration::get('PPAL_TAX_FEE');
                        $order_detail_fee->id_warehouse = 0;

                        //add order_detail_tax
                        if($order_detail_fee->save()){
                        //$id_order_detail_fee = Db::getInstance()->getValue('SELECT `id_order_detail` FROM `'._DB_PREFIX_.'order_detail` WHERE `id_order`='.(int)$order->id.' AND `product_id`=0 AND `product_supplier_reference`="PPWF"');

                            if($order_detail_fee->id_tax_rules_group > 0){
                                //id_tax of id_tax_rules_group
                                $vat_address = new Address($order->id_address_invoice);
                                $tax_manager_order_detail_fee = TaxManagerFactory::getManager($vat_address, Configuration::get('PPAL_TAX_FEE'),$this->context);
                                $tax_calculator = $tax_manager_order_detail_fee->getTaxCalculator();
                                $tax_computation_method = (int)$tax_calculator->computation_method;

                                if (count($tax_calculator->taxes) > 0) {
                                    if (version_compare(_PS_VERSION_, '1.6.1.0', '>=')) {
                                        $round_mode = $order->round_mode;
                                        $round_type = $order->round_type;
                                    }else{
                                        $round_mode = Configuration::get('PS_PRICE_ROUND_MODE');
                                        $round_type = Configuration::get('PS_ROUND_TYPE');
                                    }

                                    foreach ($tax_calculator->getTaxesAmount($order_detail_fee->unit_price_tax_excl) as $id_tax => $amount) {
                                            switch ($round_type) {
                                                case 1:
                                                    //$total_tax_base = $quantity * Tools::ps_round($discounted_price_tax_excl, _PS_PRICE_COMPUTE_PRECISION_, $this->round_mode);
                                                    $total_amount = 1 * Tools::ps_round($amount, _PS_PRICE_COMPUTE_PRECISION_, $round_mode);
                                                break;
                                                case 2:
                                                    //$total_tax_base = Tools::ps_round($quantity * $discounted_price_tax_excl, _PS_PRICE_COMPUTE_PRECISION_, $this->round_mode);
                                                    $total_amount = Tools::ps_round(1 * $amount, _PS_PRICE_COMPUTE_PRECISION_, $round_mode);
                                                break;
                                                case 3:
                                                    //$total_tax_base = $quantity * $discounted_price_tax_excl;
                                                    $total_amount = 1 * $amount;
                                                break;
                                            }
                                        
                                        
                                        

                                        $sql_order_detail_tax = 'INSERT INTO `'._DB_PREFIX_.'order_detail_tax` (id_order_detail, id_tax, unit_amount, total_amount)
                                        VALUES ('.(int)$order_detail_fee->id.','.(int)$id_tax.','.(float)$amount.','.(float)$total_amount.')';

                                        Db::getInstance()->execute($sql_order_detail_tax);
                                    }
                                }
                            }
                        }else{
                            //error saving fee
                        }
                    }
                    // Adding an entry in order_carrier table
                    if (!is_null($carrier)) {
                        $order_carrier = new OrderCarrier();
                        $order_carrier->id_order = (int) $order->id;
                        $order_carrier->id_carrier = (int) $id_carrier;
                        $order_carrier->weight = (float) $order->getTotalWeight();
                        $order_carrier->shipping_cost_tax_excl = (float) $order->total_shipping_tax_excl;
                        $order_carrier->shipping_cost_tax_incl = (float) $order->total_shipping_tax_incl;
                        $order_carrier->add();
                    }
                    $multiple_carrier += 1; //if has multiple carrier for different products // assign fee to one order.
                }

            // The country can only change if the address used for the calculation is the delivery address, and if multi-shipping is activated
            if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_delivery')
                $this->context->country = $context_country;

            if (!$this->context->country->active) {
                if (version_compare(_PS_VERSION_, '1.6.1.0', '>='))
                    PrestaShopLogger::addLog('PaymentModule::validateOrder - Country is not active', 3, null, 'Cart', (int) $id_cart, true);
                throw new PrestaShopException('The order address country is not active.');
            }




            // Register Payment only if the order status validate the order
            if ($order_status->logable) {
                // $order is the last order loop in the foreach
                // The method addOrderPayment of the class Order make a create a paymentOrder
                //     linked to the order reference and not to the order id
                if (isset($extra_vars['transaction_id']))
                    $transaction_id = $extra_vars['transaction_id'];
                else
                    $transaction_id = null;

                if (!$order->addOrderPayment($amount_paid, null, $transaction_id)) {
                    if (version_compare(_PS_VERSION_, '1.6.1.0', '>='))
                        PrestaShopLogger::addLog('PaymentModule::validateOrder - Cannot save Order Payment', 3, null, 'Cart', (int) $id_cart, true);
                    throw new PrestaShopException('Can\'t save Order Payment');
                }
            }

            //Maybe the order not finished because can be waiting to accept paypal, but must be created.
            $id_cart = $order->id_cart;
            $id_order = $order->id;
            $amount = $order->total_paid;
            $id_tax = Configuration::get('PPAL_TAX_FEE');

            if($id_tax && Configuration::get('PS_TAX')){
            //search tax rule group
                if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_delivery') {
                    $id_address = $this->context->cart->id_address_delivery;
                }else{
                    $id_address = $this->context->cart->id_address_invoice;
                }
                $address = new Address($id_address);
                $tax_manager = TaxManagerFactory::getManager($address, $id_tax);
                $tax_calculator = $tax_manager->getTaxCalculator();
                $tax_rate = $tax_calculator->getTotalRate();
            }else{
                $tax_rate = 0;
            }

            $fee_amount = $fee_with_tax;
            
            if (!empty($transaction_paypal_id))
                $transaction_idppwf = $transaction_paypal_id;
            else
                $transaction_idppwf = '-';




            $paypal_order = new PaypalOrderx();
            $paypal_order->id_cart = $id_cart;
            $paypal_order->id_order = $id_order;
            $paypal_order->total_amount = $amount;
            $paypal_order->total_paypal = $total_paypal;
            $paypal_order->tax_rate = (float) $tax_rate;
            $paypal_order->fee = $fee_amount;
            $paypal_order->transaction_id = $transaction_idppwf;
            $paypal_order->id_shop = $this->context->cart->id_shop;
            $paypal_order->ppwf_version = $this->version;
            
            $paypal_order->add();
            //end paypal order
            // Next !
            //if (version_compare(_PS_VERSION_, '1.6.1.0', '>='))
            //    $only_one_gift = false;

            $cart_rule_used = array();
            if (version_compare(_PS_VERSION_, '1.6.1.0', '<'))
                $cart_rules = $this->context->cart->getCartRules();

            // Make sure CarRule caches are empty
            CartRule::cleanCache();

            foreach ($order_detail_list as $key => $order_detail) {
                $order = $order_list[$key];
                if (!$order_creation_failed && isset($order->id)) {
                    if (!$secure_key)
                        $message .= '<br />' . Tools::displayError('Warning: the secure key is empty, check your payment account before validation');
                    // Optional message to attach to this order
                    if (isset($message) & !empty($message)) {
                        $msg = new Message();
                        $message = strip_tags($message, '<br>');
                        if (Validate::isCleanHtml($message)) {
                            $msg->message = $message;
                            if (version_compare(_PS_VERSION_, '1.6.1.0', '>=')) {
                                $msg->id_cart = (int) $id_cart;
                                $msg->id_customer = (int) ($order->id_customer);
                            }
                            $msg->id_order = (int) ($order->id);
                            $msg->private = 1;
                            $msg->add();
                        }
                    }

                    // Insert new Order detail list using cart for the current order
                    //$orderDetail = new OrderDetail(null, null, $this->context);
                    //$orderDetail->createList($order, $this->context->cart, $id_order_state);
                    // Construct order detail table for the email
                    $products_list = '';
                    $virtual_product = true;

                    foreach ($order->product_list as $key => $product) {
                        $price = Product::getPriceStatic((int) $product['id_product'], false, ($product['id_product_attribute'] ? (int) $product['id_product_attribute'] : null), 6, null, false, true, $product['cart_quantity'], false, (int) $order->id_customer, (int) $order->id_cart, (int) $order->{Configuration::get('PS_TAX_ADDRESS_TYPE')});
                        $price_wt = Product::getPriceStatic((int) $product['id_product'], true, ($product['id_product_attribute'] ? (int) $product['id_product_attribute'] : null), 2, null, false, true, $product['cart_quantity'], false, (int) $order->id_customer, (int) $order->id_cart, (int) $order->{Configuration::get('PS_TAX_ADDRESS_TYPE')});

                        if (version_compare(_PS_VERSION_, '1.6.1.0', '>=')) {
                            $product_price = Product::getTaxCalculationMethod() == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt;

                            $product_var_tpl = array(
                                'reference' => $product['reference'],
                                'name' => $product['name'] . (isset($product['attributes']) ? ' - ' . $product['attributes'] : ''),
                                'unit_price' => Tools::displayPrice($product_price, $this->context->currency, false),
                                'price' => Tools::displayPrice($product_price * $product['quantity'], $this->context->currency, false),
                                'quantity' => $product['quantity'],
                                'customization' => array()
                            );
                        }

                        $customization_quantity = 0;
                        $customized_datas = Product::getAllCustomizedDatas((int) $order->id_cart);
                        if (isset($customized_datas[$product['id_product']][$product['id_product_attribute']])) {
                            $customization_text = '';
                            foreach ($customized_datas[$product['id_product']][$product['id_product_attribute']][$order->id_address_delivery] as $customization) {
                                if (isset($customization['datas'][Product::CUSTOMIZE_TEXTFIELD]))
                                    foreach ($customization['datas'][Product::CUSTOMIZE_TEXTFIELD] as $text)
                                        $customization_text .= $text['name'] . ': ' . $text['value'] . '<br />';

                                if (isset($customization['datas'][Product::CUSTOMIZE_FILE]))
                                    $customization_text .= sprintf(Tools::displayError('%d image(s)'), count($customization['datas'][Product::CUSTOMIZE_FILE])) . '<br />';
                                $customization_text .= '---<br />';
                            }
                            $customization_text = rtrim($customization_text, '---<br />');

                            $customization_quantity = (int) $product['customization_quantity'];
                            $products_list .=
                                    '<tr style="background-color: ' . ($key % 2 ? '#DDE2E6' : '#EBECEE') . ';">
                                <td style="padding: 0.6em 0.4em;width: 15%;">' . $product['reference'] . '</td>
                                <td style="padding: 0.6em 0.4em;width: 30%;"><strong>' . $product['name'] . (isset($product['attributes']) ? ' - ' . $product['attributes'] : '') . ' - ' . Tools::displayError('Customized') . (!empty($customization_text) ? ' - ' . $customization_text : '') . '</strong></td>
                                <td style="padding: 0.6em 0.4em; width: 20%;">' . Tools::displayPrice(Product::getTaxCalculationMethod() == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt, $this->context->currency, false) . '</td>
                                <td style="padding: 0.6em 0.4em; width: 15%;">' . $customization_quantity . '</td>
                                <td style="padding: 0.6em 0.4em; width: 20%;">' . Tools::displayPrice($customization_quantity * (Product::getTaxCalculationMethod() == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt), $this->context->currency, false) . '</td>
                            </tr>';
                        }

                        if (!$customization_quantity || (int) $product['cart_quantity'] > $customization_quantity)
                            $products_list .=
                                    '<tr style="background-color: ' . ($key % 2 ? '#DDE2E6' : '#EBECEE') . ';">
                                <td style="padding: 0.6em 0.4em;width: 15%;">' . $product['reference'] . '</td>
                                <td style="padding: 0.6em 0.4em;width: 30%;"><strong>' . $product['name'] . (isset($product['attributes']) ? ' - ' . $product['attributes'] : '') . '</strong></td>
                                <td style="padding: 0.6em 0.4em; width: 20%;">' . Tools::displayPrice(Product::getTaxCalculationMethod((int) $this->context->customer->id) == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt, $this->context->currency, false) . '</td>
                                <td style="padding: 0.6em 0.4em; width: 15%;">' . ((int) $product['cart_quantity'] - $customization_quantity) . '</td>
                                <td style="padding: 0.6em 0.4em; width: 20%;">' . Tools::displayPrice(((int) $product['cart_quantity'] - $customization_quantity) * (Product::getTaxCalculationMethod() == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt), $this->context->currency, false) . '</td>
                            </tr>';

                        // Check if is not a virutal product for the displaying of shipping
                        if (!$product['is_virtual'])
                            $virtual_product &= false;
                    } // end foreach ($products)

                    $cart_rules_list = '';
                    $total_reduction_value_ti = 0;
                    $total_reduction_value_tex = 0;
                    foreach ($cart_rules as $cart_rule) {
                        $package = array('id_carrier' => $order->id_carrier, 'id_address' => $order->id_address_delivery, 'products' => $order->product_list);
                        $values = array(
                            'tax_incl' => $cart_rule['obj']->getContextualValue(true, $this->context, CartRule::FILTER_ACTION_ALL_NOCAP, $package),
                            'tax_excl' => $cart_rule['obj']->getContextualValue(false, $this->context, CartRule::FILTER_ACTION_ALL_NOCAP, $package)
                        );

                        // If the reduction is not applicable to this order, then continue with the next one
                        if (!$values['tax_excl'])
                            continue;

                        /* IF
                         * * - This is not multi-shipping
                         * * - The value of the voucher is greater than the total of the order
                         * * - Partial use is allowed
                         * * - This is an "amount" reduction, not a reduction in % or a gift
                         * * THEN
                         * * The voucher is cloned with a new value corresponding to the remainder
                         */

                        if (count($order_list) == 1 && $values['tax_incl'] > ($order->total_products_wt - $total_reduction_value_ti) && $cart_rule['obj']->partial_use == 1 && $cart_rule['obj']->reduction_amount > 0) {
                            // Create a new voucher from the original
                            $voucher = new CartRule($cart_rule['obj']->id); // We need to instantiate the CartRule without lang parameter to allow saving it
                            unset($voucher->id);

                            // Set a new voucher code
                            $voucher->code = empty($voucher->code) ? Tools::substr(md5($order->id . '-' . $order->id_customer . '-' . $cart_rule['obj']->id), 0, 16) : $voucher->code . '-2';
                            if (preg_match('/\-([0-9]{1,2})\-([0-9]{1,2})$/', $voucher->code, $matches) && $matches[1] == $matches[2])
                                $voucher->code = preg_replace('/' . $matches[0] . '$/', '-' . ((int) ($matches[1]) + 1), $voucher->code);

                            // Set the new voucher value
                            if ($voucher->reduction_tax)
                                $voucher->reduction_amount = $values['tax_incl'] - ($order->total_products_wt - $total_reduction_value_ti) - ($voucher->free_shipping == 1 ? $order->total_shipping_tax_incl : 0);
                            else
                                $voucher->reduction_amount = $values['tax_excl'] - ($order->total_products - $total_reduction_value_tex) - ($voucher->free_shipping == 1 ? $order->total_shipping_tax_excl : 0);

                            $voucher->id_customer = $order->id_customer;
                            $voucher->quantity = 1;
                            $voucher->quantity_per_user = 1;
                            $voucher->free_shipping = 0;
                            if ($voucher->add()) {
                                // If the voucher has conditions, they are now copied to the new voucher
                                CartRule::copyConditions($cart_rule['obj']->id, $voucher->id);

                                $params = array(
                                    '{voucher_amount}' => Tools::displayPrice($voucher->reduction_amount, $this->context->currency, false),
                                    '{voucher_num}' => $voucher->code,
                                    '{firstname}' => $this->context->customer->firstname,
                                    '{lastname}' => $this->context->customer->lastname,
                                    '{id_order}' => $order->reference,
                                    '{order_name}' => $order->getUniqReference()
                                );
                                Mail::Send(
                                        (int) $order->id_lang, 'voucher', sprintf(Mail::l('New voucher regarding your order %s', (int) $order->id_lang), $order->reference), $params, $this->context->customer->email, $this->context->customer->firstname . ' ' . $this->context->customer->lastname, null, null, null, null, _PS_MAIL_DIR_, false, (int) $order->id_shop
                                );
                            }

                            $values['tax_incl'] -= $values['tax_incl'] - $order->total_products_wt;
                            $values['tax_excl'] -= $values['tax_excl'] - $order->total_products;
                        }
                        $total_reduction_value_ti += $values['tax_incl'];
                        $total_reduction_value_tex += $values['tax_excl'];

                        $order->addCartRule($cart_rule['obj']->id, $cart_rule['obj']->name, $values, 0, $cart_rule['obj']->free_shipping);

                        if ($id_order_state != Configuration::get('PS_OS_ERROR') && $id_order_state != Configuration::get('PS_OS_CANCELED') && !in_array($cart_rule['obj']->id, $cart_rule_used)) {
                            $cart_rule_used[] = $cart_rule['obj']->id;

                            // Create a new instance of Cart Rule without id_lang, in order to update its quantity
                            $cart_rule_to_update = new CartRule($cart_rule['obj']->id);
                            $cart_rule_to_update->quantity = max(0, $cart_rule_to_update->quantity - 1);
                            $cart_rule_to_update->update();
                        }

                        $cart_rules_list .= '
                        <tr>
                            <td colspan="4" style="padding:0.6em 0.4em;text-align:right">' . Tools::displayError('Voucher name:') . ' ' . $cart_rule['obj']->name . '</td>
                            <td style="padding:0.6em 0.4em;text-align:right">' . ($values['tax_incl'] != 0.00 ? '-' : '') . Tools::displayPrice($values['tax_incl'], $this->context->currency, false) . '</td>
                        </tr>';
                    }

                    // Specify order id for message
                    $old_message = Message::getMessageByCartId((int) $this->context->cart->id);
                    if ($old_message) {
                        $update_message = new Message((int) $old_message['id_message']);
                        $update_message->id_order = (int) $order->id;
                        $update_message->update();

                        // Add this message in the customer thread
                        $customer_thread = new CustomerThread();
                        $customer_thread->id_contact = 0;
                        $customer_thread->id_customer = (int) $order->id_customer;
                        $customer_thread->id_shop = (int) $this->context->shop->id;
                        $customer_thread->id_order = (int) $order->id;
                        $customer_thread->id_lang = (int) $this->context->language->id;
                        $customer_thread->email = $this->context->customer->email;
                        $customer_thread->status = 'open';
                        $customer_thread->token = Tools::passwdGen(12);
                        $customer_thread->add();

                        $customer_message = new CustomerMessage();
                        $customer_message->id_customer_thread = $customer_thread->id;
                        $customer_message->id_employee = 0;
                        $customer_message->message = $update_message->message;
                        $customer_message->private = 0;

                        if (!$customer_message->add())
                            $this->errors[] = Tools::displayError('An error occurred while saving message');
                    }

                    // Hook validate order
                    Hook::exec('actionValidateOrder', array(
                        'cart' => $this->context->cart,
                        'order' => $order,
                        'customer' => $this->context->customer,
                        'currency' => $this->context->currency,
                        'orderStatus' => $order_status
                    ));

                    foreach ($this->context->cart->getProducts() as $product)
                        if ($order_status->logable)
                            ProductSale::addProductSale((int) $product['id_product'], (int) $product['cart_quantity']);

                    // Set the order state
                    $new_history = new OrderHistory();
                    $new_history->id_order = (int) $order->id;
                    $new_history->changeIdOrderState((int) $id_order_state, $order, true);
                    $new_history->addWithemail(true, $extra_vars);

                    // Switch to back order if needed
                    if (Configuration::get('PS_STOCK_MANAGEMENT') && $order_detail->getStockState()) {
                        $history = new OrderHistory();
                        $history->id_order = (int) $order->id;
                        $history->changeIdOrderState(Configuration::get('PS_OS_OUTOFSTOCK'), $order, true);
                        $history->addWithemail();
                    }

                    unset($order_detail);

                    // Order is reloaded because the status just changed
                    $order = new Order($order->id);

                    // Send an e-mail to customer (one order = one email)
                    if ($id_order_state != Configuration::get('PS_OS_ERROR') && $id_order_state != Configuration::get('PS_OS_CANCELED') && $this->context->customer->id) {
                        $invoice = new Address($order->id_address_invoice);
                        $delivery = new Address($order->id_address_delivery);
                        $delivery_state = $delivery->id_state ? new State($delivery->id_state) : false;
                        $invoice_state = $invoice->id_state ? new State($invoice->id_state) : false;

                        $data = array(
                            '{firstname}' => $this->context->customer->firstname,
                            '{lastname}' => $this->context->customer->lastname,
                            '{email}' => $this->context->customer->email,
                            '{delivery_block_txt}' => $this->_getFormatedAddress($delivery, "\n"),
                            '{invoice_block_txt}' => $this->_getFormatedAddress($invoice, "\n"),
                            '{delivery_block_html}' => $this->_getFormatedAddress($delivery, '<br />', array(
                                'firstname' => '<span style="font-weight:bold;">%s</span>',
                                'lastname' => '<span style="font-weight:bold;">%s</span>'
                            )),
                            '{invoice_block_html}' => $this->_getFormatedAddress($invoice, '<br />', array(
                                'firstname' => '<span style="font-weight:bold;">%s</span>',
                                'lastname' => '<span style="font-weight:bold;">%s</span>'
                            )),
                            '{delivery_company}' => $delivery->company,
                            '{delivery_firstname}' => $delivery->firstname,
                            '{delivery_lastname}' => $delivery->lastname,
                            '{delivery_address1}' => $delivery->address1,
                            '{delivery_address2}' => $delivery->address2,
                            '{delivery_city}' => $delivery->city,
                            '{delivery_postal_code}' => $delivery->postcode,
                            '{delivery_country}' => $delivery->country,
                            '{delivery_state}' => $delivery->id_state ? $delivery_state->name : '',
                            '{delivery_phone}' => ($delivery->phone) ? $delivery->phone : $delivery->phone_mobile,
                            '{delivery_other}' => $delivery->other,
                            '{invoice_company}' => $invoice->company,
                            '{invoice_vat_number}' => $invoice->vat_number,
                            '{invoice_firstname}' => $invoice->firstname,
                            '{invoice_lastname}' => $invoice->lastname,
                            '{invoice_address2}' => $invoice->address2,
                            '{invoice_address1}' => $invoice->address1,
                            '{invoice_city}' => $invoice->city,
                            '{invoice_postal_code}' => $invoice->postcode,
                            '{invoice_country}' => $invoice->country,
                            '{invoice_state}' => $invoice->id_state ? $invoice_state->name : '',
                            '{invoice_phone}' => ($invoice->phone) ? $invoice->phone : $invoice->phone_mobile,
                            '{invoice_other}' => $invoice->other,
                            '{order_name}' => $order->getUniqReference(),
                            '{date}' => Tools::displayDate(date('Y-m-d H:i:s'), null, 1),
                            '{carrier}' => $virtual_product ? Tools::displayError('No carrier') : $carrier->name,
                            '{payment}' => Tools::substr($order->payment, 0, 32),
                            '{products}' => $this->formatProductAndVoucherForEmail($products_list),
                            '{discounts}' => $this->formatProductAndVoucherForEmail($cart_rules_list),
                            '{total_paid}' => Tools::displayPrice($order->total_paid, $this->context->currency, false),
                            '{total_products}' => Tools::displayPrice($order->total_paid - $order->total_shipping - $order->total_wrapping + $order->total_discounts, $this->context->currency, false),
                            '{total_discounts}' => Tools::displayPrice($order->total_discounts, $this->context->currency, false),
                            '{total_shipping}' => Tools::displayPrice($order->total_shipping, $this->context->currency, false),
                            '{total_wrapping}' => Tools::displayPrice($order->total_wrapping, $this->context->currency, false),
                            '{total_tax_paid}' => Tools::displayPrice(($order->total_products_wt - $order->total_products) + ($order->total_shipping_tax_incl - $order->total_shipping_tax_excl), $this->context->currency, false));

                        if (is_array($extra_vars))
                            $data = array_merge($data, $extra_vars);

                        // Join PDF invoice
                        if ((int) Configuration::get('PS_INVOICE') && $order_status->invoice && $order->invoice_number) {
                            $pdf = new PDF($order->getInvoicesCollection(), PDF::TEMPLATE_INVOICE, $this->context->smarty);
                            $file_attachement = array();
                            $file_attachement['content'] = $pdf->render(false);
                            $file_attachement['name'] = Configuration::get('PS_INVOICE_PREFIX', (int) $order->id_lang, null, $order->id_shop) . sprintf('%06d', $order->invoice_number) . '.pdf';
                            $file_attachement['mime'] = 'application/pdf';
                        } else
                            $file_attachement = null;

                        if (Validate::isEmail($this->context->customer->email))
                            Mail::Send(
                                    (int) $order->id_lang, 'order_conf', Mail::l('Order confirmation', (int) $order->id_lang), $data, $this->context->customer->email, $this->context->customer->firstname . ' ' . $this->context->customer->lastname, null, null, $file_attachement, null, _PS_MAIL_DIR_, false, (int) $order->id_shop
                            );
                    }

                    // updates stock in shops
                    if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
                        $product_list = $order->getProducts();
                        foreach ($product_list as $product) {
                            // if the available quantities depends on the physical stock
                            if (StockAvailable::dependsOnStock($product['product_id'])) {
                                // synchronizes
                                StockAvailable::synchronize($product['product_id'], $order->id_shop);
                            }
                        }
                    }
                } else {
                    $error = Tools::displayError('Order creation failed');
                    Logger::addLog($error, 4, '0000002', 'Cart', (int) ($order->id_cart));
                    die($error);
                }
            }// End foreach $order_detail_list
            // Use the last order as currentOrder
            $this->currentOrder = (int) $order->id;
            return true;
        } else {
            $error = Tools::displayError('Cart cannot be loaded or an order has already been placed using this cart');
            Logger::addLog($error, 4, '0000001', 'Cart', (int) ($this->context->cart->id));
            die($error);
        }
    }

    public function getFee($cart) {
        $currency = new Currency((int) $cart->id_currency);
        $currency_decimals = is_array($currency) ? (int) $currency['decimals'] : (int) $currency->decimals;
        $decimals = $currency_decimals * _PS_PRICE_DISPLAY_PRECISION_;

        $fixedfee = Configuration::get('PPAL_FEE_FIXEDFEE');
        $percentage = Configuration::get('PPAL_FEE_PERCENTAGE');

        $totalttc = (float) ($cart->getOrderTotal(true, Cart::BOTH));
        $totalfee = Tools::ps_round((($percentage / 100) * $totalttc) + $fixedfee, $decimals);

        return $totalfee;
    }
    
    public function getCompleteFee($cart){
        $currency = new Currency((int) $cart->id_currency);
        $currency_decimals = is_array($currency) ? (int) $currency['decimals'] : (int) $currency->decimals;
        $decimals = $currency_decimals * _PS_PRICE_DISPLAY_PRECISION_;

        $fee_content = array();
        $fee_with_tax = $this->getFee($cart);

        $id_tax = Configuration::get('PPAL_TAX_FEE');

        if($id_tax && Configuration::get('PS_TAX')){
            //search tax rule group
            if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_delivery') {
                $id_address = $cart->id_address_delivery;
            }else{
                $id_address = $cart->id_address_invoice;
            }

            $address = new Address($id_address);
            $tax_manager = TaxManagerFactory::getManager($address, $id_tax);
            $tax_calculator = $tax_manager->getTaxCalculator();
            $tax_rate = $tax_calculator->getTotalRate();
            
            if($tax_rate > 0){
                $fee_without_tax = Tools::ps_round($fee_with_tax / ( 1 + (0.01 * $tax_rate)),$decimals);
            }else{
                $fee_without_tax = $fee_with_tax;
            }
        }else{
            $fee_without_tax = $fee_with_tax;
        }

        $fee_content['fee_with_tax'] = $fee_with_tax;
        $fee_content['fee_without_tax'] = $fee_without_tax;

        return $fee_content;
    }

    protected function IsOrderPpwf() {
        $this->ajaxDie(Tools::jsonEncode(array(
                    'is_ppwf' => true,
                    'id_order' => 1
        )));
        if (Tools::isSubmit('ajax')) {
            $id_order = Tools::getValue('id_order');
            $order = new order($id_order);
            if ($order->module == $this->name) {
                $this->ajaxDie(Tools::jsonEncode(array(
                            'is_ppwf' => true,
                            'id_order' => $id_order
                )));
            }
            $this->ajaxDie(Tools::jsonEncode(array(
                        'is_ppwf' => false,
                        'id_order' => $id_order
            )));
        }
    }

    protected function getCategoriesByProduct($id_product) {
        $sql = 'SELECT `id_category` FROM `' . _DB_PREFIX_ . 'category_product` WHERE `id_product`=' . (int) $id_product;
        return Db::getInstance()->executeS($sql);
    }

    public function _iscurl() {
        return function_exists('curl_version');
    }

    public static function generateAddress(Address $address, $patternRules, $newLine = "\r\n", $separator = ' ', $style = array()) {
        $addressFields = AddressFormat::getOrderedAddressFields($address->id_country);
        $addressFormatedValues = self::getFormattedAddressFieldsValues($address, $addressFields);

        $addressText = '';
        foreach ($addressFields as $line)
            if (($patternsList = explode(' ', $line))) {
                $tmpText = '';
                foreach ($patternsList as $pattern)
                    if (!in_array($pattern, $patternRules['avoid']))
                        $tmpText .= (isset($addressFormatedValues[$pattern])) ?
                                (((isset($style[$pattern])) ?
                                        (sprintf($style[$pattern], $addressFormatedValues[$pattern])) :
                                        $addressFormatedValues[$pattern]) . $separator) : '';
                $tmpText = trim($tmpText);
                $addressText .= (!empty($tmpText)) ? $tmpText . $newLine : '';
            }
        return $addressText;
    }

    public function getFormattedAddressFieldsValues($address, $addressFormat) {
        $cookie = $this->context->$cookie;
        $tab = array();
        $temporyObject = array();

        // Check if $address exist and it's an instanciate object of Address
        if ($address && ($address instanceof Address))
            foreach ($addressFormat as $lineNum) {
                foreach ($lineNum as $line) {
                    if (($keyList = explode(' ', $line)) && is_array($keyList))
                        foreach ($keyList as $pattern)
                            if ($associateName = explode(':', $pattern)) {
                                $totalName = count($associateName);
                                if ($totalName == 1 && isset($address->{$associateName[0]}))
                                    $tab[$associateName[0]] = $address->{$associateName[0]};
                                else {
                                    $tab[$pattern] = '';

                                    // Check if the property exist in both classes
                                    if (($totalName == 2) && class_exists($associateName[0]) &&
                                            Tools::property_exists($associateName[0], $associateName[1]) &&
                                            Tools::property_exists($address, 'id_' . Tools::strtolower($associateName[0]))) {
                                        $idFieldName = 'id_' . Tools::strtolower($associateName[0]);

                                        if (!isset($temporyObject[$associateName[0]]))
                                            $temporyObject[$associateName[0]] = new $associateName[0]($address->{$idFieldName});
                                        if ($temporyObject[$associateName[0]])
                                            $tab[$pattern] = (is_array($temporyObject[$associateName[0]]->{$associateName[1]})) ?
                                                    ((isset($temporyObject[$associateName[0]]->{$associateName[1]}[(isset($cookie) ? $cookie->id_lang : Configuration::get('PS_LANG_DEFAULT'))])) ?
                                                            $temporyObject[$associateName[0]]->{$associateName[1]}[(isset($cookie) ? $cookie->id_lang : Configuration::get('PS_LANG_DEFAULT'))] : '') :
                                                    $temporyObject[$associateName[0]]->{$associateName[1]};
                                    }
                                }
                            }
                }
            }
        // Free the instanciate objects
        foreach ($temporyObject as $objectName)
            foreach ($objectName as &$object)
                unset($object);
        return $tab;
    }
    
    public function isTaxRuleDeleted($id_tax){
        if (version_compare(_PS_VERSION_, '1.6.0.10', '>=')) {
            return Db::getInstance()->getValue('
                    SELECT `deleted`
                    FROM `'._DB_PREFIX_.'tax_rules_group`
                    WHERE `id_tax_rules_group` = '.(int)$id_tax
            );
        }else{
            return false;
        }
    }
    
    public function HookBackOfficeHeader(){
        if (Module::isInstalled($this->name) && Module::isEnabled($this->name) && Configuration::get('PPAL_TAX_FEE')){
            if($this->isTaxRuleDeleted(Configuration::get('PPAL_TAX_FEE'))){
                return $this->display(__FILE__, 'views/templates/admin/ppwf-need-conf.tpl');
            }
        }
    }

}
