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
 * Class DhlBackend
 */
class DhlBackend extends Dhlexpress
{
    /**
     * @return HelperForm
     */
    public function getHelperForm()
    {
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->identifier = $this->identifier;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false);
        $helper->currentIndex .= '&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;

        return $helper;
    }

    /**
     * @return array
     */
    public function getAccountSettingsForm()
    {
        $logName = 'dhlexpress_'.Tools::encrypt(_PS_MODULE_DIR_.$this->name.'/logs/').'.log';

        // @formatter:off
        return array(
            'form' => array(
                'legend'  => array(
                    'title' => $this->l('Account Settings', 'DhlBackend'),
                    'icon'  => 'icon-cogs',
                ),
                'input'   => array(
                    array(
                        'type'    => 'switch',
                        'label'   => $this->l('Live mode', 'DhlBackend'),
                        'name'    => 'DHL_LIVE_MODE',
                        'is_bool' => true,
                        'desc'    => $this->l('Use this module in live mode', 'DhlBackend'),
                        'values'  => array(
                            array(
                                'id'    => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled', 'DhlBackend'),
                            ),
                            array(
                                'id'    => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled', 'DhlBackend'),
                            ),
                        ),
                    ),
                    array(
                        'type'    => 'switch',
                        'label'   => $this->l('Enable log', 'DhlBackend'),
                        'name'    => 'DHL_ENABLE_LOG',
                        'is_bool' => true,
                        'hint'    => $this->l('Please enable log only if you needed it, as the file may take large spaces.', 'DhlBackend'),
                        'desc'    => $this->l('Log file is available in the logs directory of the module (make sure the directory has correct permissions)', 'DhlBackend').'<br/>'.$logName,
                        'values'  => array(
                            array(
                                'id'    => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled', 'DhlBackend'),
                            ),
                            array(
                                'id'    => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled', 'DhlBackend'),
                            ),
                        ),
                    ),
                    array(
                        'type'  => 'dhl_credentials',
                        'modes' => array(
                            'test'       => array(
                                'title'          => $this->l('Test credentials', 'DhlBackend'),
                                'login_label'    => $this->l('Test Account ID', 'DhlBackend'),
                                'password_label' => $this->l('Test Account password', 'DhlBackend'),
                                'login_name'     => 'DHL_ACCOUNT_ID_TEST',
                                'password_name'  => 'DHL_ACCOUNT_PASSWORD_TEST',
                            ),
                            'production' => array(
                                'title'          => $this->l('Production credentials', 'DhlBackend'),
                                'login_label'    => $this->l('Production Account ID', 'DhlBackend'),
                                'password_label' => $this->l('Production Account password', 'DhlBackend'),
                                'login_name'     => 'DHL_ACCOUNT_ID_PRODUCTION',
                                'password_name'  => 'DHL_ACCOUNT_PASSWORD_PRODUCTION',
                            ),
                        ),
                    ),
                ),
                'buttons' => array(
                    array(
                        'type'  => 'submit',
                        'name'  => 'submitAccountCheck',
                        'icon'  => 'process-icon-check icon-check',
                        'title' => $this->l('Check credentials', 'DhlBackend'),
                        'class' => 'pull-right',
                    ),
                ),
                'submit'  => array(
                    'title' => $this->l('Save', 'DhlBackend'),
                ),
            ),
        );
        // @formatter:on
    }

    /**
     * @return array
     * @throws PrestaShopDatabaseException
     */
    public function getFrontOfficeSettingsForm()
    {
        $defaultCurrency = Currency::getDefaultCurrency();
        $zones = Zone::getZones(true);
        $francoArray = array();
        foreach ($zones as $zone) {
            $francoArray[] = array(
                'type'             => 'text',
                'form_group_class' => 'dhl-free-delivery-from',
                'class'            => 'fixed-width-sm',
                'label'            => $zone['name'],
                'name'             => 'DHL_FRANCO_'.(int) $zone['id_zone'],
                'prefix'           => $defaultCurrency->iso_code,
            );
        }

        // @formatter:off
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Front-Office settings', 'DhlBackend'),
                    'icon'  => 'icon-cogs',
                ),
                'input'  => array_merge(
                    array(
                        array(
                            'type'             => 'switch',
                            'form_group_class' => 'dhl-use-dhl-prices-div',
                            'label'            => $this->l('Use DHL prices for my carriers', 'DhlBackend'),
                            'name'             => 'DHL_USE_DHL_PRICES',
                            'is_bool'          => true,
                            'values'           => array(
                                array(
                                    'id'    => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('Enabled', 'DhlBackend'),
                                ),
                                array(
                                    'id'    => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('Disabled', 'DhlBackend'),
                                ),
                            ),
                        ),
                        array(
                            'type'             => 'switch',
                            'form_group_class' => 'dhl-use-dhl-packages-div',
                            'label'            => $this->l('Use weights and dimensions of my products to quote shipping costs', 'DhlBackend'),
                            'name'             => 'DHL_USE_PREDEFINED_PACKAGES',
                            'hint'             => $this->l('If you don\'t use weights and dimensions of your products, the default pre-defined package configured in the next tab will be used to quote shipping costs in Front-Office', 'DhlBackend'),
                            'is_bool'          => true,
                            'values'           => array(
                                array(
                                    'id'    => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('Yes', 'DhlBackend'),
                                ),
                                array(
                                    'id'    => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('No', 'DhlBackend'),
                                ),
                            ),
                        ),
                        array(
                            'type'             => 'switch',
                            'form_group_class' => 'dhl-enable-free-delivery-from',
                            'label'            => $this->l('Enable free delivery by zone from an amount', 'DhlBackend'),
                            'name'             => 'DHL_ENABLE_FREE_SHIPPING_FROM',
                            'is_bool'          => true,
                            'values'           => array(
                                array(
                                    'id'    => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('Yes', 'DhlBackend'),
                                ),
                                array(
                                    'id'    => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('No', 'DhlBackend'),
                                ),
                            ),
                        ),
                        array(
                            'type'             => 'html',
                            'form_group_class' => 'dhl-free-delivery-from',
                            'name'             => 'html_data',
                            'html_content'     => '<strong>'.$this->l('Free delivery from:', 'DhlBackend').'</strong>',
                        ),
                    ),
                    $francoArray,
                    array(
                        array(
                            'type'               => 'dhl_services',
                            'form_group_class'   => 'dhl-services-list-div',
                            'services'           => DhlService::getServicesList($this->context->language->id),
                            'logo'               => $this->_path.'views/img/dhl.png',
                            'DOMESTIC'           => $this->l('Domestic products', 'DhlBackend'),
                            'EUROPE'             => $this->l('Europe products', 'DhlBackend'),
                            'WORLDWIDE'          => $this->l('Worldwide products', 'DhlBackend'),
                            'WORLDWIDE DOCUMENT' => $this->l('Worldwide products (Document only)', 'DhlBackend'),
                        ),
                        array(
                            'type'             => 'switch',
                            'form_group_class' => 'dhl-weight-prices-div',
                            'label'            => $this->l('Weight DHL prices', 'DhlBackend'),
                            'name'             => 'DHL_WEIGHT_PRICES',
                            'is_bool'          => true,
                            'values'           => array(
                                array(
                                    'id'    => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('Enabled', 'DhlBackend'),
                                ),
                                array(
                                    'id'    => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('Disabled', 'DhlBackend'),
                                ),
                            ),
                        ),
                        array(
                            'type'             => 'radio',
                            'form_group_class' => 'dhl-weighting-type-div',
                            'label'            => $this->l('Weighting type', 'DhlBackend'),
                            'name'             => 'DHL_WEIGHTING_TYPE',
                            'values'           => array(
                                array(
                                    'id'    => 'type-percent',
                                    'value' => 'percent',
                                    'label' => $this->l('Percent', 'DhlBackend'),
                                ),
                                array(
                                    'id'    => 'type-amount',
                                    'value' => 'amount',
                                    'label' => $this->l('Amount', 'DhlBackend'),
                                ),
                            ),
                        ),
                        array(
                            'type'             => 'text',
                            'form_group_class' => 'dhl-weighting-value-percent-div',
                            'class'            => 'fixed-width-xs',
                            'label'            => $this->l('Percent', 'DhlBackend'),
                            'name'             => 'DHL_WEIGHTING_VALUE_PERCENT',
                            'prefix'           => '%',
                        ),
                        array(
                            'type'             => 'text',
                            'form_group_class' => 'dhl-weighting-value-amount-div',
                            'class'            => 'fixed-width-sm',
                            'label'            => $this->l('Amount (tax excl.)', 'DhlBackend'),
                            'name'             => 'DHL_WEIGHTING_VALUE_AMOUNT',
                            'prefix'           => $defaultCurrency->iso_code,
                        ),
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Save', 'DhlBackend'),
                ),
            ),
        );
        // @formatter:on
    }

    /**
     * @param $key
     * @return mixed
     * @throws PrestaShopDatabaseException
     */
    public function getBackOfficeSettingsForm($key)
    {
        require_once(dirname(__FILE__).'/DhlExtracharge.php');

        $newAddressLink = AdminController::$currentIndex.'&amp;configure='.$this->name.'&amp;token=';
        $newAddressLink .= Tools::getAdminTokenLite('AdminModules').'&amp;addNewAddress';
        $newPackageLink = AdminController::$currentIndex.'&amp;configure='.$this->name.'&amp;token=';
        $newPackageLink .= Tools::getAdminTokenLite('AdminModules').'&amp;addNewPackage';
        $extracharges = DhlExtracharge::getExtrachargesList($this->context->language->id);
        $extrachargesInput = array();
        $forms = array();
        foreach ($extracharges as $extracharge) {
            $input = array(
                'type'    => 'switch',
                'label'   => $extracharge['name'],
                'hint'    => $extracharge['description'],
                'name'    => 'extracharge_'.$extracharge['id_dhl_extracharge'],
                'is_bool' => true,
                'values'  => array(
                    array(
                        'id'    => 'active_on',
                        'value' => 1,
                        'label' => $this->l('Enabled', 'DhlBackend'),
                    ),
                    array(
                        'id'    => 'active_off',
                        'value' => 0,
                        'label' => $this->l('Disabled', 'DhlBackend'),
                    ),
                ),
            );
            $extrachargesInput[] = $input;
        }
        $forms['billing'] = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Billing account settings', 'DhlBackend'),
                    'icon'  => 'icon-cogs',
                ),
                'input'  => array(
                    array(
                        'type'    => 'select',
                        'label'   => $this->l('Origin country of DHL account owner', 'DhlBackend'),
                        'name'    => 'DHL_ACCOUNT_OWNER_COUNTRY',
                        'options' => array(
                            'query' => Country::getCountries((int) Context::getContext()->cookie->id_lang),
                            'id'    => 'id_country',
                            'name'  => 'name',
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save', 'DhlBackend'),
                ),
            ),
        );
        // @formatter:off
        $forms['sender'] = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Default sender address', 'DhlBackend'),
                    'icon'  => 'icon-envelope',
                ),
                'input'  => array(
                    array(
                        'type'    => 'select',
                        'label'   => $this->l('Your default address', 'DhlBackend'),
                        'name'    => 'DHL_DEFAULT_SENDER_ADDRESS',
                        'options' => array(
                            'query' => DhlAddress::getAddressList(),
                            'id'    => 'id_dhl_address',
                            'name'  => 'title',
                        ),
                    ),
                    array(
                        'type'       => 'dhl_display_addr',
                        'obj'        => 'dhl_default_address_obj',
                        'edit_label' => $this->l('Edit', 'DhlBackend'),
                        'edit_link'  => $this->context->link->getAdminLink('AdminModules', false).
                                        '&token='.
                                        Tools::getAdminTokenLite('AdminModules').
                                        '&configure='.
                                        $this->name,
                        'no_address' => $this->l('Please create at least one address.', 'DhlBackend'),
                    ),
                    array(
                        'type'         => 'html',
                        'name'         => 'dhl_btn_addresses',
                        'html_content' => '<a class="btn btn-xl btn-primary" href="'.$newAddressLink.'" id="dhl-add-new-address">
                            <i class="icon-plus-sign"></i> '.$this->l('Add a new address', 'DhlBackend').'</a>',
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save', 'DhlBackend'),
                ),
            ),
        );
        // @formatter:on
        $forms['shipment'] = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Default shipment settings', 'DhlBackend'),
                    'icon'  => 'icon-cogs',
                ),
                'input'  => array(
                    array(
                        'type'         => 'html',
                        'name'         => 'dhl_shipment_title',
                        'html_content' => '<h2>'.$this->l('Shipment options', 'DhlBackend').'</h2>',
                    ),
                    array(
                        'type'    => 'switch',
                        'label'   => $this->l('Regular pickup', 'DhlBackend'),
                        'name'    => 'DHL_DAILY_PICKUP',
                        'is_bool' => true,
                        'values'  => array(
                            array(
                                'id'    => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled', 'DhlBackend'),
                            ),
                            array(
                                'id'    => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled', 'DhlBackend'),
                            ),
                        ),
                    ),
                    array(
                        'type'    => 'select',
                        'label'   => $this->l('Label type', 'DhlBackend'),
                        'name'    => 'DHL_LABEL_TYPE',
                        'options' => array(
                            'query' => array(
                                'pdfa4'  => array(
                                    'id'   => 'pdfa4',
                                    'name' => $this->l('PDF A4', 'DhlBackend'),
                                ),
                                'pdf64'  => array(
                                    'id'   => 'pdf64',
                                    'name' => $this->l('PDF 10x16 (6x4")', 'DhlBackend'),
                                ),
                                'zpl264' => array(
                                    'id'   => 'zpl264',
                                    'name' => $this->l('ZPL2 10x16 (6x4")', 'DhlBackend'),
                                ),
                                'epl264' => array(
                                    'id'   => 'epl264',
                                    'name' => $this->l('EPL2 10x16 (6x4")', 'DhlBackend'),
                                ),
                            ),
                            'id'    => 'id',
                            'name'  => 'name',
                        ),
                    ),
                    array(
                        'type'    => 'select',
                        'label'   => $this->l('Return label lifetime', 'DhlBackend'),
                        'name'    => 'DHL_LABEL_LIFETIME',
                        'options' => array(
                            'query' => array(
                                '3M'  => array(
                                    'id'   => '3',
                                    'name' => $this->l('3 Months', 'DhlBackend'),
                                ),
                                '6M'  => array(
                                    'id'   => '6',
                                    'name' => $this->l('6 Months', 'DhlBackend'),
                                ),
                                '12M' => array(
                                    'id'   => '12',
                                    'name' => $this->l('12 Months', 'DhlBackend'),
                                ),
                            ),
                            'id'    => 'id',
                            'name'  => 'name',
                        ),
                    ),
                    array(
                        'type'         => 'html',
                        'name'         => 'dhl_packages_title',
                        'html_content' => '<h2>'.$this->l('Package settings', 'DhlBackend').'</h2>',
                    ),
                    array(
                        'type'    => 'switch',
                        'label'   => $this->l('Sending document only', 'DhlBackend'),
                        'name'    => 'DHL_SENDING_DOC',
                        'is_bool' => true,
                        'values'  => array(
                            array(
                                'id'    => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled', 'DhlBackend'),
                            ),
                            array(
                                'id'    => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled', 'DhlBackend'),
                            ),
                        ),
                    ),
                    array(
                        'type'             => 'radio',
                        'form_group_class' => 'dhl_system_units',
                        'label'            => $this->l('System of units', 'DhlBackend'),
                        'name'             => 'DHL_SYSTEM_UNITS',
                        'values'           => array(
                            array(
                                'id'    => 'system-metric',
                                'value' => 'metric',
                                'label' => $this->l('Metric (kg/cm)', 'DhlBackend'),
                            ),
                            array(
                                'id'    => 'system-imperial',
                                'value' => 'imperial',
                                'label' => $this->l('Imperial (lb/in)', 'DhlBackend'),
                            ),
                        ),
                    ),
                    array(
                        'type'  => 'textarea',
                        'label' => $this->l('Your default shipment content', 'DhlBackend'),
                        'class' => 'col-lg-5',
                        'name'  => 'DHL_DEFAULT_SHIPMENT_CONTENT',
                    ),
                    array(
                        'type'    => 'select',
                        'label'   => $this->l('Your default package type', 'DhlBackend'),
                        'name'    => 'DHL_DEFAULT_PACKAGE_TYPE',
                        'options' => array(
                            'query' => DhlPackage::getPackageList(),
                            'id'    => 'id_dhl_package',
                            'name'  => 'name',
                        ),
                    ),
                    array(
                        'type'             => 'dhl_dimension',
                        'form_group_class' => 'dhl_default_dimension_value',
                        'class'            => 'fixed-width-xs',
                        'readonly'         => true,
                        'label'            => $this->l('Package default weight & dimension', 'DhlBackend'),
                        'no_package'       => $this->l('Please create at least one package type.', 'DhlBackend'),
                        'dim_values'       => array(
                            array(
                                'label'        => $this->l('Weight', 'DhlBackend'),
                                'name'         => 'DHL_DEFAULT_PACKAGE_WEIGHT',
                                'suffix_class' => 'dhl-suffix-weight',
                                'suffix'       => DhlTools::getWeightUnit(),
                            ),
                            array(
                                'label'        => $this->l('Length', 'DhlBackend'),
                                'name'         => 'DHL_DEFAULT_PACKAGE_LENGTH',
                                'suffix_class' => 'dhl-suffix-dimension',
                                'suffix'       => DhlTools::getDimensionUnit(),
                            ),
                            array(
                                'label'        => $this->l('Width', 'DhlBackend'),
                                'name'         => 'DHL_DEFAULT_PACKAGE_WIDTH',
                                'suffix_class' => 'dhl-suffix-dimension',
                                'suffix'       => DhlTools::getDimensionUnit(),
                            ),
                            array(
                                'label'        => $this->l('Depth', 'DhlBackend'),
                                'name'         => 'DHL_DEFAULT_PACKAGE_DEPTH',
                                'suffix_class' => 'dhl-suffix-dimension',
                                'suffix'       => DhlTools::getDimensionUnit(),
                            ),
                        ),
                    ),
                    array(
                        'type'         => 'html',
                        'name'         => 'dhl_btn_packages',
                        'html_content' => '<a class="btn btn-xl btn-primary" href="'.$newPackageLink.'" id="dhl-add-new-package">
                            <i class="icon-plus-sign"></i> '.$this->l('Add a new package type', 'DhlBackend').'</a>',
                    ),
                    array(
                        'type'         => 'html',
                        'name'         => 'dhl_invoice_title',
                        'html_content' => '<h2>'.$this->l('Commercial invoice settings', 'DhlBackend').'</h2>',
                    ),
                    array(
                        'type'  => 'text',
                        'col'   => 3,
                        'label' => $this->l('Your default HS Code', 'DhlBackend'),
                        'desc'  => $this->l('(e.g. 620411)', 'DhlBackend'),
                        'class' => 'col-lg-5',
                        'name'  => 'DHL_DEFAULT_HS_CODE',
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save', 'DhlBackend'),
                ),
            ),
        );
        $forms['extracharges'] = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Default extra charges', 'DhlBackend'),
                    'icon'  => 'icon-cogs',
                ),
                'input'  => $extrachargesInput,
                'submit' => array(
                    'title' => $this->l('Save', 'DhlBackend'),
                ),
            ),
        );

        return $forms[$key];
    }

    /**
     * @return array
     */
    public function getAddressForm()
    {
        $backUrl = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&token=';
        $backUrl .= Tools::getAdminTokenLite('AdminModules').'&viewAddresses';

        return array(
            'form' => array(
                'legend'  => array(
                    'title' => $this->l('Add a new address', 'DhlBackend'),
                    'icon'  => 'icon-envelope',
                ),
                'input'   => array(
                    array(
                        'type' => 'hidden',
                        'name' => 'id_dhl_address',
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'redirectAfter',
                    ),
                    array(
                        'type'         => 'html',
                        'name'         => 'account_title',
                        'html_content' => '<h2>'.$this->l('DHL accounts number', 'DhlBackend').'</h2>',
                    ),
                    array(
                        'type'      => 'text',
                        'col'       => 3,
                        'label'     => $this->l('Export account', 'DhlBackend'),
                        'name'      => 'account_export',
                        'maxchar'   => 12,
                        'maxlength' => 12,
                        'required'  => true,
                    ),
                    array(
                        'type'      => 'text',
                        'col'       => 3,
                        'label'     => $this->l('Import account', 'DhlBackend'),
                        'name'      => 'account_import',
                        'maxchar'   => 12,
                        'maxlength' => 12,
                    ),
                    array(
                        'type'      => 'text',
                        'col'       => 3,
                        'label'     => $this->l('Duty account', 'DhlBackend'),
                        'name'      => 'account_duty',
                        'maxchar'   => 12,
                        'maxlength' => 12,
                        'required'  => false,
                    ),
                    array(
                        'type'         => 'html',
                        'name'         => 'contact_title',
                        'html_content' => '<h2>'.$this->l('Contact at this location', 'DhlBackend').'</h2>',
                    ),
                    array(
                        'type'      => 'text',
                        'col'       => 3,
                        'label'     => $this->l('Contact name', 'DhlBackend'),
                        'name'      => 'contact_name',
                        'maxchar'   => 35,
                        'maxlength' => 35,
                        'required'  => true,
                    ),
                    array(
                        'type'     => 'text',
                        'col'      => 3,
                        'label'    => $this->l('Contact email', 'DhlBackend'),
                        'name'     => 'contact_email',
                        'required' => true,
                    ),
                    array(
                        'type'     => 'text',
                        'col'      => 3,
                        'label'    => $this->l('Contact phone', 'DhlBackend'),
                        'name'     => 'contact_phone',
                        'required' => true,
                    ),
                    array(
                        'type'         => 'html',
                        'name'         => 'address_title',
                        'html_content' => '<h2>'.$this->l('Address', 'DhlBackend').'</h2>',
                    ),
                    array(
                        'type'     => 'text',
                        'col'      => 3,
                        'label'    => $this->l('Company', 'DhlBackend'),
                        'name'     => 'company_name',
                        'required' => true,
                    ),
                    array(
                        'type'     => 'text',
                        'col'      => 3,
                        'label'    => $this->l('VAT no.', 'DhlBackend'),
                        'name'     => 'vat_number',
                        'required' => true,
                    ),
                    array(
                        'type'      => 'text',
                        'col'       => 3,
                        'label'     => $this->l('Address line 1', 'DhlBackend'),
                        'name'      => 'address1',
                        'required'  => true,
                        'maxchar'   => 35,
                        'maxlength' => 35,
                    ),
                    array(
                        'type'      => 'text',
                        'col'       => 3,
                        'label'     => $this->l('Address line 2', 'DhlBackend'),
                        'name'      => 'address2',
                        'maxchar'   => 35,
                        'maxlength' => 35,
                    ),
                    array(
                        'type'      => 'text',
                        'col'       => 3,
                        'label'     => $this->l('Address line 3', 'DhlBackend'),
                        'name'      => 'address3',
                        'maxchar'   => 35,
                        'maxlength' => 35,
                    ),
                    array(
                        'type'  => 'text',
                        'col'   => 3,
                        'label' => $this->l('Zipcode', 'DhlBackend'),
                        'name'  => 'zipcode',
                    ),
                    array(
                        'type'     => 'text',
                        'col'      => 3,
                        'label'    => $this->l('City', 'DhlBackend'),
                        'name'     => 'city',
                        'required' => true,
                    ),
                    array(
                        'type'     => 'select',
                        'class'    => 'chosen',
                        'label'    => $this->l('Country name', 'DhlBackend'),
                        'name'     => 'id_country',
                        'required' => true,
                        'options'  => array(
                            'query' => Country::getCountries((int) Context::getContext()->cookie->id_lang),
                            'id'    => 'id_country',
                            'name'  => 'name',
                        ),
                    ),
                    array(
                        'type'     => 'select',
                        'label'    => $this->l('State name', 'DhlBackend'),
                        'name'     => 'id_state',
                        'required' => false,
                        'options'  => array(
                            'query' => array(),
                            'id'    => 'id_state',
                            'name'  => 'name',
                        ),
                    ),
                    array(
                        'type'     => 'text',
                        'col'      => 3,
                        'label'    => $this->l('Phone number', 'DhlBackend'),
                        'name'     => 'phone',
                        'required' => true,
                    ),
                ),
                'buttons' => array(
                    array(
                        'href'  => $backUrl,
                        'id'    => 'viewAddress',
                        'icon'  => 'process-icon-back icon-back',
                        'title' => $this->l('Back', 'DhlBackend'),
                        'class' => 'pull-right',
                    ),
                ),
                'submit'  => array(
                    'title' => $this->l('Save', 'DhlBackend'),
                ),
            ),
        );
    }

    /**
     * @return array
     */
    public function getPackageForm()
    {
        $backUrl = AdminController::$currentIndex.'&amp;configure='.$this->name.'&amp;token=';
        $backUrl .= Tools::getAdminTokenLite('AdminModules').'&amp;viewPackages';

        return array(
            'form' => array(
                'legend'  => array(
                    'title' => $this->l('Add a new package', 'DhlBackend'),
                    'icon'  => 'icon-archive',
                ),
                'input'   => array(
                    array(
                        'type' => 'hidden',
                        'name' => 'id_dhl_package',
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'redirectAfter',
                    ),
                    array(
                        'type'     => 'text',
                        'col'      => 3,
                        'label'    => $this->l('Name', 'DhlBackend'),
                        'name'     => 'name',
                        'required' => true,
                    ),
                    array(
                        'type'             => 'dhl_dimension',
                        'form_group_class' => 'dhl-default-dimension-value',
                        'class'            => 'fixed-width-xs',
                        'label'            => $this->l('Package dimension', 'DhlBackend'),
                        'readonly'         => false,
                        'dim_values'       => array(
                            array(
                                'label'        => $this->l('Weight', 'DhlBackend'),
                                'name'         => 'weight_value',
                                'suffix_class' => 'dhl-suffix-weight',
                                'suffix'       => DhlTools::getWeightUnit(),
                            ),
                            array(
                                'label'        => $this->l('Length', 'DhlBackend'),
                                'name'         => 'length_value',
                                'suffix_class' => 'dhl-suffix-dimension',
                                'suffix'       => DhlTools::getDimensionUnit(),
                            ),
                            array(
                                'label'        => $this->l('Width', 'DhlBackend'),
                                'name'         => 'width_value',
                                'suffix_class' => 'dhl-suffix-dimension',
                                'suffix'       => DhlTools::getDimensionUnit(),
                            ),
                            array(
                                'label'        => $this->l('Depth', 'DhlBackend'),
                                'name'         => 'depth_value',
                                'suffix_class' => 'dhl-suffix-dimension',
                                'suffix'       => DhlTools::getDimensionUnit(),
                            ),
                        ),
                    ),
                    array(
                        'type'  => 'free',
                        'label' => '',
                        'name'  => 'dhl_dimensions_cast',
                    ),
                ),
                'buttons' => array(
                    array(
                        'href'  => $backUrl,
                        'id'    => 'viewPackage',
                        'icon'  => 'process-icon-back icon-back',
                        'title' => $this->l('Back', 'DhlBackend'),
                        'class' => 'pull-right',
                    ),
                ),
                'submit'  => array(
                    'title' => $this->l('Save', 'DhlBackend'),
                ),
            ),
        );
    }

    /**
     * @return string
     * @throws PrestaShopException
     */
    public function renderAccountSettingsForm()
    {
        $fieldsValue = $this->getAccountSettingsFormValues();
        $helper = $this->getHelperForm();
        $helper->submit_action = 'submitDhlAccount';
        $helper->name_controller = 'col-lg-12';
        $helper->tpl_vars = array(
            'fields_value' => $fieldsValue,
            'languages'    => $this->context->controller->getLanguages(),
            'id_language'  => $this->context->language->id,
        );
        $helperTestHtml = $helper->generateForm(array($this->getAccountSettingsForm()));

        return $helperTestHtml;
    }

    /**
     * @return string
     * @throws PrestaShopException
     */
    public function renderFrontOfficeSettingsForm()
    {
        $helper = $this->getHelperForm();
        $helper->submit_action = 'submitFrontOfficeSettings';
        $fieldsValue = $this->getFrontOfficeSettingsFormValues();
        $helper->tpl_vars = array(
            'fields_value' => $fieldsValue,
            'languages'    => $this->context->controller->getLanguages(),
            'id_language'  => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getFrontOfficeSettingsForm()));
    }

    /**
     * @return string
     * @throws PrestaShopException
     */
    public function renderBackOfficeSettingsForm()
    {
        $output = '';
        $fieldsValue = $this->getBackOfficeSettingsFormValues();

        $helperBillingAccount = $this->getHelperForm();
        $helperBillingAccount->submit_action = 'submitBillingAccount';
        $helperBillingAccount->tpl_vars = array(
            'fields_value' => $fieldsValue,
            'languages'    => $this->context->controller->getLanguages(),
            'id_language'  => $this->context->language->id,
        );
        $helperSenderAddr = $this->getHelperForm();
        $helperSenderAddr->submit_action = 'submitDefaultSenderAddress';
        $helperSenderAddr->tpl_vars = array(
            'fields_value' => $fieldsValue,
            'languages'    => $this->context->controller->getLanguages(),
            'id_language'  => $this->context->language->id,
        );
        $helperShipment = $this->getHelperForm();
        $helperShipment->submit_action = 'submitDefaultShipmentDetails';
        $helperShipment->tpl_vars = array(
            'fields_value' => $fieldsValue,
            'languages'    => $this->context->controller->getLanguages(),
            'id_language'  => $this->context->language->id,
        );
        $helperExtracharges = $this->getHelperForm();
        $helperExtracharges->submit_action = 'submitDefaultExtracharges';
        $helperExtracharges->tpl_vars = array(
            'fields_value' => $fieldsValue,
            'languages'    => $this->context->controller->getLanguages(),
            'id_language'  => $this->context->language->id,
        );
        $output .= $helperBillingAccount->generateForm(array($this->getBackOfficeSettingsForm('billing')));
        $output .= $helperSenderAddr->generateForm(array($this->getBackOfficeSettingsForm('sender')));
        $output .= $helperShipment->generateForm(array($this->getBackOfficeSettingsForm('shipment')));
        $output .= $helperExtracharges->generateForm(array($this->getBackOfficeSettingsForm('extracharges')));

        return $output;
    }

    /**
     * @return string
     */
    public function renderAddressForm()
    {
        $idDhlAddress = (int) Tools::getValue('id_dhl_address');
        $helper = $this->getHelperForm();
        $helper->submit_action = 'submitAddressForm';
        $fieldsValue = $this->getAddressFormFormValues($idDhlAddress);
        $helper->tpl_vars = array(
            'fields_value' => $fieldsValue,
            'languages'    => $this->context->controller->getLanguages(),
            'id_language'  => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getAddressForm()));
    }

    /**
     * @return string
     */
    public function renderPackageForm()
    {
        $idDhlPackage = (int) Tools::getValue('id_dhl_package');
        $helper = $this->getHelperForm();
        $helper->submit_action = 'submitPackageForm';
        $fieldsValue = $this->getPackageFormFormValues($idDhlPackage);
        $helper->tpl_vars = array(
            'fields_value' => $fieldsValue,
            'languages'    => $this->context->controller->getLanguages(),
            'id_language'  => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getPackageForm()));
    }
}
