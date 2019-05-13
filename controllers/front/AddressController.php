<?php
/*
* 2007-2017 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2017 PrestaShop SA
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class AddressControllerCore extends FrontController
{
    public $auth = true;
    public $guestAllowed = true;
    public $php_self = 'address';
    public $authRedirection = 'addresses';
    public $ssl = true;

    /**
     * @var Address Current address
     */
    protected $_address;
    protected $id_country;

    /**
     * Set default medias for this controller
     */
    public function setMedia()
    {
        parent::setMedia();
        $this->addJS(array(
            _THEME_JS_DIR_.'tools/vatManagement.js',
            _THEME_JS_DIR_.'tools/statesManagement.js',
            _PS_JS_DIR_.'validate.js'
        ));
    }

    /**
     * Initialize address controller
     * @see FrontController::init()
     */
    public function init()
    {
        parent::init();

        // Get address ID
        $id_address = 0;
        if ($this->ajax && Tools::isSubmit('type')) {
            if (Tools::getValue('type') == 'delivery' && isset($this->context->cart->id_address_delivery)) {
                $id_address = (int)$this->context->cart->id_address_delivery;
            } elseif (Tools::getValue('type') == 'invoice' && isset($this->context->cart->id_address_invoice)
                        && $this->context->cart->id_address_invoice != $this->context->cart->id_address_delivery) {
                $id_address = (int)$this->context->cart->id_address_invoice;
            }
        } else {
            $id_address = (int)Tools::getValue('id_address', 0);
        }

        // Initialize address
        if ($id_address) {
            $this->_address = new Address($id_address);
            if (Validate::isLoadedObject($this->_address) && Customer::customerHasAddress($this->context->customer->id, $id_address)) {
                if (Tools::isSubmit('delete')) {
                    if ($this->_address->delete()) {
                        if ($this->context->cart->id_address_invoice == $this->_address->id) {
                            unset($this->context->cart->id_address_invoice);
                        }
                        if ($this->context->cart->id_address_delivery == $this->_address->id) {
                            unset($this->context->cart->id_address_delivery);
                            $this->context->cart->updateAddressId($this->_address->id, (int)Address::getFirstCustomerAddressId(Context::getContext()->customer->id));
                        }
                        Tools::redirect('index.php?controller=addresses');
                    }
                    $this->errors[] = Tools::displayError('This address cannot be deleted.');
                }
            } elseif ($this->ajax) {
                exit;
            } else {
                Tools::redirect('index.php?controller=addresses');
            }
        }
    }

    /**
     * Start forms process
     * @see FrontController::postProcess()
     */
    public function postProcess()
    {
        if (Tools::isSubmit('submitAddress')) {
            $this->processSubmitAddress();
        } elseif (!Validate::isLoadedObject($this->_address) && Validate::isLoadedObject($this->context->customer)) {
            $_POST['firstname'] = $this->context->customer->firstname;
            $_POST['lastname'] = $this->context->customer->lastname;
            $_POST['company'] = $this->context->customer->company;
        }
    }

    /**
     * Process changes on an address
     */
    protected function processSubmitAddress()
    {
        $address = new Address();
        $this->errors = $address->validateController();
        $address->id_customer = (int)$this->context->customer->id;

        // Check page token
        if ($this->context->customer->isLogged() && !$this->isTokenValid()) {
            $this->errors[] = Tools::displayError('Invalid token.');
        }

        // Check phone
        if (Configuration::get('PS_ONE_PHONE_AT_LEAST') && !Tools::getValue('phone') && !Tools::getValue('phone_mobile')) {
            $this->errors[] = Tools::displayError('You must register at least one phone number.');
        }
        if ($address->id_country) {
            // Check country
            if (!($country = new Country($address->id_country)) || !Validate::isLoadedObject($country)) {
                throw new PrestaShopException('Country cannot be loaded with address->id_country');
            }

            if ((int)$country->contains_states && !(int)$address->id_state) {
                $this->errors[] = Tools::displayError('This country requires you to chose a State.');
            }

            if (!$country->active) {
                $this->errors[] = Tools::displayError('This country is not active.');
            }

            $postcode = Tools::getValue('postcode');
            /* Check zip code format */
            if ($country->zip_code_format && !$country->checkZipCode($postcode)) {
                $this->errors[] = sprintf(Tools::displayError('The Zip/Postal code you\'ve entered is invalid. It must follow this format: %s'), str_replace('C', $country->iso_code, str_replace('N', '0', str_replace('L', 'A', $country->zip_code_format))));
            } elseif (empty($postcode) && $country->need_zip_code) {
                $this->errors[] = Tools::displayError('A Zip/Postal code is required.');
            } elseif ($postcode && !Validate::isPostCode($postcode)) {
                $this->errors[] = Tools::displayError('The Zip/Postal code is invalid.');
            }

            // Check country DNI
            if ($country->isNeedDni() && (!Tools::getValue('dni') || !Validate::isDniLite(Tools::getValue('dni')))) {
                $this->errors[] = Tools::displayError('The identification number is incorrect or has already been used.');
            } elseif (!$country->isNeedDni()) {
                $address->dni = null;
            }
        }
        // Check if the alias exists
        if (!$this->context->customer->is_guest && !empty($_POST['alias']) && (int)$this->context->customer->id > 0) {
            $id_address = Tools::getValue('id_address');
            if (Configuration::get('PS_ORDER_PROCESS_TYPE') && (int)Tools::getValue('opc_id_address_'.Tools::getValue('type')) > 0) {
                $id_address = Tools::getValue('opc_id_address_'.Tools::getValue('type'));
            }

            if (Address::aliasExist(Tools::getValue('alias'), (int)$id_address, (int)$this->context->customer->id)) {
                $this->errors[] = sprintf(Tools::displayError('The alias "%s" has already been used. Please select another one.'), Tools::safeOutput(Tools::getValue('alias')));
            }
        }

        // Check the requires fields which are settings in the BO
        $this->errors = array_merge($this->errors, $address->validateFieldsRequiredDatabase());

        // Don't continue this process if we have errors !
        if ($this->errors && !$this->ajax) {
            return;
        }

        // If we edit this address, delete old address and create a new one
        if (Validate::isLoadedObject($this->_address)) {
            if (Validate::isLoadedObject($country) && !$country->contains_states) {
                $address->id_state = 0;
            }
            $address_old = $this->_address;
            if (Customer::customerHasAddress($this->context->customer->id, (int)$address_old->id)) {
                if ($address_old->isUsed()) {
                    $address_old->delete();
                } else {
                    $address->id = (int)$address_old->id;
                    $address->date_add = $address_old->date_add;
                }
            }
        }

        if ($this->ajax && Configuration::get('PS_ORDER_PROCESS_TYPE')) {
            $this->errors = array_unique(array_merge($this->errors, $address->validateController()));
            if (count($this->errors)) {
                $return = array(
                    'hasError' => (bool)$this->errors,
                    'errors' => $this->errors
                );
                $this->ajaxDie(Tools::jsonEncode($return));
            }
        }

        // Save address
        if ($result = $address->save()) {
            // Update id address of the current cart if necessary
            if (isset($address_old) && $address_old->isUsed()) {
                $this->context->cart->updateAddressId($address_old->id, $address->id);
            } else { // Update cart address
                $this->context->cart->autosetProductAddress();
            }

            if ((bool)Tools::getValue('select_address', false) == true || (Tools::getValue('type') == 'invoice' && Configuration::get('PS_ORDER_PROCESS_TYPE'))) {
                $this->context->cart->id_address_invoice = (int)$address->id;
            } elseif (Configuration::get('PS_ORDER_PROCESS_TYPE')) {
                $this->context->cart->id_address_invoice = (int)$this->context->cart->id_address_delivery;
            }
            $this->context->cart->update();

            if ($this->ajax) {
                $return = array(
                    'hasError' => (bool)$this->errors,
                    'errors' => $this->errors,
                    'id_address_delivery' => (int)$this->context->cart->id_address_delivery,
                    'id_address_invoice' => (int)$this->context->cart->id_address_invoice
                );
                $this->ajaxDie(Tools::jsonEncode($return));
            }

            // Redirect to old page or current page
            if ($back = Tools::getValue('back')) {
                if ($back == Tools::secureReferrer(Tools::getValue('back'))) {
                    Tools::redirect(html_entity_decode($back));
                }
                $mod = Tools::getValue('mod');
                Tools::redirect('index.php?controller='.$back.($mod ? '&back='.$mod : ''));
            } else {
                Tools::redirect('index.php?controller=addresses');
            }
        }
        $this->errors[] = Tools::displayError('An error occurred while updating your address.');
    }

    /**
     * Assign template vars related to page content
     * @see FrontController::initContent()
     */
    public function initContent()
    {
        parent::initContent();

        $this->assignCountries();
        $this->assignVatNumber();
        $this->assignAddressFormat();

        // Assign common vars
        $this->context->smarty->assign(array(
            'address_validation' => Address::$definition['fields'],
            'one_phone_at_least' => (int)Configuration::get('PS_ONE_PHONE_AT_LEAST'),
            'onr_phone_at_least' => (int)Configuration::get('PS_ONE_PHONE_AT_LEAST'), //retro compat
            'ajaxurl' => _MODULE_DIR_,
            'errors' => $this->errors,
            'token' => Tools::getToken(false),
            'select_address' => (int)Tools::getValue('select_address'),
            'address' => $this->_address,
            'id_address' => (Validate::isLoadedObject($this->_address)) ? $this->_address->id : 0
        ));

        if ($back = Tools::getValue('back')) {
            $this->context->smarty->assign('back', Tools::safeOutput($back));
        }
        if ($mod = Tools::getValue('mod')) {
            $this->context->smarty->assign('mod', Tools::safeOutput($mod));
        }
        if (isset($this->context->cookie->account_created)) {
            $this->context->smarty->assign('account_created', 1);
            unset($this->context->cookie->account_created);
        }

        $this->setTemplate(_PS_THEME_DIR_.'address.tpl');
    }

    /**
     * Assign template vars related to countries display
     */
    protected function assignCountries()
    {
        $this->id_country = (int)Tools::getCountry($this->_address);
        // Generate countries list
        if (Configuration::get('PS_RESTRICT_DELIVERED_COUNTRIES')) {
            $countries = Carrier::getDeliveredCountries($this->context->language->id, true, true);
        } else {
            $countries = Country::getCountries($this->context->language->id, true);
        }

        // @todo use helper
        $list = '';
        foreach ($countries as $country) {
            $selected = ((int)$country['id_country'] === $this->id_country) ? ' selected="selected"' : '';
            $list .= '<option value="'.(int)$country['id_country'].'"'.$selected.'>'.htmlentities($country['name'], ENT_COMPAT, 'UTF-8').'</option>';
        }
		
		
		
		
    $table_data = array(
      array(1, 'Germany'),
      array(2, 'Austria'),
      array(3, 'Belgium'),
      array(4, 'Canada'),
      array(5, 'China'),
      array(6, 'Spain'),
      array(7, 'Finland'),
      array(8, 'France'),
      array(9, 'Greece'),
      array(10, 'Italy'),
      array(11, 'Japan'),
      array(12, 'Luxemburg'),
      array(13, 'Netherlands'),
      array(14, 'Poland'),
      array(15, 'Portugal'),
      array(16, 'Czech Republic'),
      array(17, 'United Kingdom'),
      array(18, 'Sweden'),
      array(19, 'Switzerland'),
      array(20, 'Denmark'),
      array(21, 'United States'),
      array(22, 'HongKong'),
      array(23, 'Norway'),
      array(24, 'Australia'),
      array(25, 'Singapore'),
      array(26, 'Ireland'),
      array(27, 'New Zealand'),
      array(28, 'South Korea'),
      array(29, 'Israel'),
      array(30, 'South Africa'),
      array(31, 'Nigeria'),
      array(32, 'Ivory Coast'),
      array(33, 'Togo'),
      array(34, 'Bolivia'),
      array(35, 'Mauritius'),
      array(36, 'Romania'),
      array(37, 'Slovakia'),
      array(38, 'Algeria'),
      array(39, 'American Samoa'),
      array(40, 'Andorra'),
      array(41, 'Angola'),
      array(42, 'Anguilla'),
      array(43, 'Antigua and Barbuda'),
      array(44, 'Argentina'),
      array(45, 'Armenia'),
      array(46, 'Aruba'),
      array(47, 'Azerbaijan'),
      array(48, 'Bahamas'),
      array(49, 'Bahrain'),
      array(50, 'Bangladesh'),
      array(51, 'Barbados'),
      array(52, 'Belarus'),
      array(53, 'Belize'),
      array(54, 'Benin'),
      array(55, 'Bermuda'),
      array(56, 'Bhutan'),
      array(57, 'Botswana'),
      array(58, 'Brazil'),
      array(59, 'Brunei'),
      array(60, 'Burkina Faso'),
      array(61, 'Burma (Myanmar)'),
      array(62, 'Burundi'),
      array(63, 'Cambodia'),
      array(64, 'Cameroon'),
      array(65, 'Cape Verde'),
      array(66, 'Central African Republic'),
      array(67, 'Chad'),
      array(68, 'Chile'),
      array(69, 'Colombia'),
      array(70, 'Comoros'),
      array(71, 'Congo, Dem. Republic'),
      array(72, 'Congo, Republic'),
      array(73, 'Costa Rica'),
      array(74, 'Croatia'),
      array(75, 'Cuba'),
      array(76, 'Cyprus'),
      array(77, 'Djibouti'),
      array(78, 'Dominica'),
      array(79, 'Dominican Republic'),
      array(80, 'East Timor'),
      array(81, 'Ecuador'),
      array(82, 'Egypt'),
      array(83, 'El Salvador'),
      array(84, 'Equatorial Guinea'),
      array(85, 'Eritrea'),
      array(86, 'Estonia'),
      array(87, 'Ethiopia'),
      array(88, 'Falkland Islands'),
      array(89, 'Faroe Islands'),
      array(90, 'Fiji'),
      array(91, 'Gabon'),
      array(92, 'Gambia'),
      array(93, 'Georgia'),
      array(94, 'Ghana'),
      array(95, 'Grenada'),
      array(96, 'Greenland'),
      array(97, 'Gibraltar'),
      array(98, 'Guadeloupe'),
      array(99, 'Guam'),
      array(100, 'Guatemala'),
      array(101, 'Guernsey'),
      array(102, 'Guinea'),
      array(103, 'Guinea-Bissau'),
      array(104, 'Guyana'),
      array(105, 'Haiti'),
      array(106, 'Heard Island and McDonald Islands'),
      array(107, 'Vatican City State'),
      array(108, 'Honduras'),
      array(109, 'Iceland'),
      array(110, 'India'),
      array(111, 'Indonesia'),
      array(112, 'Iran'),
      array(113, 'Iraq'),
      array(114, 'Man Island'),
      array(115, 'Jamaica'),
      array(116, 'Jersey'),
      array(117, 'Jordan'),
      array(118, 'Kazakhstan'),
      array(119, 'Kenya'),
      array(120, 'Kiribati'),
      array(121, 'Korea, Dem. Republic of'),
      array(122, 'Kuwait'),
      array(123, 'Kyrgyzstan'),
      array(124, 'Laos'),
      array(125, 'Latvia'),
      array(126, 'Lebanon'),
      array(127, 'Lesotho'),
      array(128, 'Liberia'),
      array(129, 'Libya'),
      array(130, 'Liechtenstein'),
      array(131, 'Lithuania'),
      array(132, 'Macau'),
      array(133, 'Macedonia'),
      array(134, 'Madagascar'),
      array(135, 'Malawi'),
      array(136, 'Malaysia'),
      array(137, 'Maldives'),
      array(138, 'Mali'),
      array(139, 'Malta'),
      array(140, 'Marshall Islands'),
      array(141, 'Martinique'),
      array(142, 'Mauritania'),
      array(143, 'Hungary'),
      array(144, 'Mayotte'),
      array(145, 'Mexico'),
      array(146, 'Micronesia'),
      array(147, 'Moldova'),
      array(148, 'Monaco'),
      array(149, 'Mongolia'),
      array(150, 'Montenegro'),
      array(151, 'Montserrat'),
      array(152, 'Morocco'),
      array(153, 'Mozambique'),
      array(154, 'Namibia'),
      array(155, 'Nauru'),
      array(156, 'Nepal'),
      array(157, 'Netherlands Antilles'),
      array(158, 'New Caledonia'),
      array(159, 'Nicaragua'),
      array(160, 'Niger'),
      array(161, 'Niue'),
      array(162, 'Norfolk Island'),
      array(163, 'Northern Mariana Islands'),
      array(164, 'Oman'),
      array(165, 'Pakistan'),
      array(166, 'Palau'),
      array(167, 'Palestinian Territories'),
      array(168, 'Panama'),
      array(169, 'Papua New Guinea'),
      array(170, 'Paraguay'),
      array(171, 'Peru'),
      array(172, 'Philippines'),
      array(173, 'Pitcairn'),
      array(174, 'Puerto Rico'),
      array(175, 'Qatar'),
      array(176, 'Reunion Island'),
      array(177, 'Russian Federation'),
      array(178, 'Rwanda'),
      array(179, 'Saint Barthelemy'),
      array(180, 'Saint Kitts and Nevis'),
      array(181, 'Saint Lucia'),
      array(182, 'Saint Martin'),
      array(183, 'Saint Pierre and Miquelon'),
      array(184, 'Saint Vincent and the Grenadines'),
      array(185, 'Samoa'),
      array(186, 'San Marino'),
      array(187, 'São Tomé and Príncipe'),
      array(188, 'Saudi Arabia'),
      array(189, 'Senegal'),
      array(190, 'Serbia'),
      array(191, 'Seychelles'),
      array(192, 'Sierra Leone'),
      array(193, 'Slovenia'),
      array(194, 'Solomon Islands'),
      array(195, 'Somalia'),
      array(196, 'South Georgia and the South Sandwich Islands'),
      array(197, 'Sri Lanka'),
      array(198, 'Sudan'),
      array(199, 'Suriname'),
      array(200, 'Svalbard and Jan Mayen'),
      array(201, 'Swaziland'),
      array(202, 'Syria'),
      array(203, 'Taiwan'),
      array(204, 'Tajikistan'),
      array(205, 'Tanzania'),
      array(206, 'Thailand'),
      array(207, 'Tokelau'),
      array(208, 'Tonga'),
      array(209, 'Trinidad and Tobago'),
      array(210, 'Tunisia'),
      array(211, 'Turkey'),
      array(212, 'Turkmenistan'),
      array(213, 'Turks and Caicos Islands'),
      array(214, 'Tuvalu'),
      array(215, 'Uganda'),
      array(216, 'Ukraine'),
      array(217, 'United Arab Emirates'),
      array(218, 'Uruguay'),
      array(219, 'Uzbekistan'),
      array(220, 'Vanuatu'),
      array(221, 'Venezuela'),
      array(222, 'Vietnam'),
      array(223, 'Virgin Islands (British)'),
      array(224, 'Virgin Islands (U.S.)'),
      array(225, 'Wallis and Futuna'),
      array(226, 'Western Sahara'),
      array(227, 'Yemen'),
      array(228, 'Zambia'),
      array(229, 'Zimbabwe'),
      array(230, 'Albania'),
      array(231, 'Afghanistan'),
      array(232, 'Antarctica'),
      array(233, 'Bosnia and Herzegovina'),
      array(234, 'Bouvet Island'),
      array(235, 'British Indian Ocean Territory'),
      array(236, 'Bulgaria'),
      array(237, 'Cayman Islands'),
      array(238, 'Christmas Island'),
      array(239, 'Cocos (Keeling) Islands'),
      array(240, 'Cook Islands'),
      array(241, 'French Guiana'),
      array(242, 'French Polynesia'),
      array(243, 'French Southern Territories'),
      array(244, 'Åland Islands')
	);
	
	if(isset($_GET['devUpdate'])){
		foreach($table_data as $data){
			echo "<hr/>UPDATE `sundev_country_lang` SET `name` = '".$data[1]."' WHERE `sundev_country_lang`.`id_country` = ".$data[0]." AND `sundev_country_lang`.`id_lang` = 2; <br/><br/> Returns: ";
			echo Db::getInstance()->execute("
				UPDATE `sundev_country_lang` 
				SET `name` = '".$data[1]."' 
				WHERE `sundev_country_lang`.`id_country` = ".$data[0]." 
				AND `sundev_country_lang`.`id_lang` = 2;"
			);
		}
		echo "<hr/>";
		die('OK!');
	}
		
		
		

        // Assign vars
        $this->context->smarty->assign(array(
            'countries_list' => $list,
            'countries' => $countries,
            'sl_country' => (int)$this->id_country,
        ));
    }

    /**
     * Assign template vars related to address format
     */
    protected function assignAddressFormat()
    {
        $id_country = is_null($this->_address)? (int)$this->id_country : (int)$this->_address->id_country;
        $requireFormFieldsList = AddressFormat::getFieldsRequired();
        $ordered_adr_fields = AddressFormat::getOrderedAddressFields($id_country, true, true);
        $ordered_adr_fields = array_unique(array_merge($ordered_adr_fields, $requireFormFieldsList));

        $this->context->smarty->assign(array(
            'ordered_adr_fields' => $ordered_adr_fields,
            'required_fields' => $requireFormFieldsList
        ));
    }

    /**
     * Assign template vars related to vat number
     * @todo move this in vatnumber module !
     */
    protected function assignVatNumber()
    {
        $vat_number_exists = file_exists(_PS_MODULE_DIR_.'vatnumber/vatnumber.php');
        $vat_number_management = Configuration::get('VATNUMBER_MANAGEMENT');
        if ($vat_number_management && $vat_number_exists) {
            include_once(_PS_MODULE_DIR_.'vatnumber/vatnumber.php');
        }

        if ($vat_number_management && $vat_number_exists && VatNumber::isApplicable((int)Tools::getCountry())) {
            $vat_display = 2;
        } elseif ($vat_number_management) {
            $vat_display = 1;
        } else {
            $vat_display = 0;
        }

        $this->context->smarty->assign(array(
            'vatnumber_ajax_call' => file_exists(_PS_MODULE_DIR_.'vatnumber/ajax.php'),
            'vat_display' => $vat_display,
        ));
    }

    public function displayAjax()
    {
        if (count($this->errors)) {
            $return = array(
                'hasError' => !empty($this->errors),
                'errors' => $this->errors
            );
            $this->ajaxDie(Tools::jsonEncode($return));
        }
    }
}
