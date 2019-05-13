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
 * Class AdminDhlLabelController
 */
class AdminDhlLabelController extends ModuleAdminController
{
    private $data;

    /**
     * @var DhlLogger $logger
     */
    private $logger;

    /** @var array */
    private $labelFormat = array(
        'pdfa4'  => array(
            'LabelImageFormat' => 'PDF',
            'LabelTemplate'    => '8X4_A4_PDF',
        ),
        'pdf64'  => array(
            'LabelImageFormat' => 'PDF',
            'LabelTemplate'    => '6X4_PDF',
        ),
        'zpl264' => array(
            'LabelImageFormat' => 'ZPL2',
            'LabelTemplate'    => '6X4_thermal',
        ),
        'epl264' => array(
            'LabelImageFormat' => 'EPL2',
            'LabelTemplate'    => '6X4_thermal',
        ),
    );

    /**
     * AdminDhlLabelController constructor.
     * @throws PrestaShopException
     */
    public function __construct()
    {
        require_once(dirname(__FILE__).'/../../api/loader.php');
        require_once(dirname(__FILE__).'/../../classes/DhlTools.php');
        require_once(dirname(__FILE__).'/../../classes/DhlOrder.php');
        require_once(dirname(__FILE__).'/../../classes/DhlAddress.php');
        require_once(dirname(__FILE__).'/../../classes/DhlPackage.php');
        require_once(dirname(__FILE__).'/../../classes/DhlCommercialInvoice.php');
        require_once(dirname(__FILE__).'/../../classes/DhlLabel.php');
        require_once(dirname(__FILE__).'/../../classes/DhlService.php');
        require_once(dirname(__FILE__).'/../../classes/DhlExtracharge.php');
        require_once(dirname(__FILE__).'/../../classes/DhlError.php');
        require_once(dirname(__FILE__).'/../../classes/logger/loader.php');

        $this->bootstrap = true;
        $this->context = Context::getContext();
        parent::__construct();

        if (Configuration::get('DHL_ENABLE_LOG')) {
            $version = str_replace('.', '_', $this->module->version);
            $hash = Tools::encrypt(_PS_MODULE_DIR_.$this->module->name.'/logs/');
            $file = dirname(__FILE__).'/../../logs/dhlexpress_'.$hash.'.log';
            $this->logger = new DhlLogger('DHL_'.$version.'_Label', new DhlFileHandler($file));
        } else {
            $this->logger = new DhlLogger('', new DhlNullHandler());
        }
    }

    /**
     * @param bool $isNewTheme
     */
    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);
        $this->addjQueryPlugin('typewatch');
        $this->addjQueryPlugin('fancybox');
    }

    /**
     * @return bool|ObjectModel
     * @throws Exception
     * @throws SmartyException
     */
    public function postProcess()
    {
        $action = Tools::getValue('action');
        if (!$action) {
            // Create a label not related to a PrestaShop order
            $this->displayFreeLabelForm();
        }
        $idDhlLabel = (int) Tools::getValue('id_dhl_label');
        if ($action == 'create') {
            // Create a label related to a PrestaShop order
            $idOrder = (int) Tools::getValue('id_order');
            $dhlOrder = DhlOrder::getByIdOrder($idOrder);
            if (!Validate::isLoadedObject($dhlOrder)) {
                return parent::postProcess();
            }
            $this->displayLabelForm($dhlOrder);
        } elseif ($action == 'createreturn') {
            // Create a return label related to a PrestaShop order
            $dhlLabel = new DhlLabel((int) $idDhlLabel);
            if (!Validate::isLoadedObject($dhlLabel)) {
                return parent::postProcess();
            }
            $dhlOrder = new DhlOrder((int) $dhlLabel->id_dhl_order);
            if (!Validate::isLoadedObject($dhlOrder)) {
                return parent::postProcess();
            }
            $this->context->smarty->assign('return_label', $idDhlLabel);
            $dhlReturnLabel = $dhlLabel->getDhlReturnLabel();
            if (false !== $dhlReturnLabel) {
                if (!Validate::isLoadedObject($dhlReturnLabel)) {
                    // Return label already exists but object not valid
                    return parent::postProcess();
                } else {
                    // Return label already exists
                    $this->displayExistingLabel($dhlReturnLabel);
                }
            } else {
                $this->displayLabelForm($dhlOrder);
            }
        } elseif ('downloadlabel' == $action) {
            // Download an existing label
            $this->downloadLabel($idDhlLabel);
        }

        return parent::postProcess();
    }

    /**
     * @throws PrestaShopDatabaseException
     */
    public function assignVars()
    {
        $defaultSenderAddrDelivery = (int) Configuration::get('DHL_DEFAULT_SENDER_ADDRESS');
        $senderAddresses = DhlAddress::getAddressList();
        $packageTypes = DhlPackage::getPackageList();
        $defaultPackageType = (int) Configuration::get('DHL_DEFAULT_PACKAGE_TYPE');
        $defaultShipmentContent = Configuration::get('DHL_DEFAULT_SHIPMENT_CONTENT');
        $dimensionUnit = DhlTools::getDimensionUnit();
        $weightUnit = DhlTools::getWeightUnit();
        $extracharges = DhlExtracharge::getExtrachargesList($this->context->language->id);
        $updateDhlAddrLink = $this->context->link->getAdminLink('AdminModules').'&configure='.$this->module->name;
        $updatePackageLink = $this->context->link->getAdminLink('AdminModules').'&configure='.$this->module->name;
        $this->context->smarty->assign(
            array(
                'link'                     => $this->context->link,
                'dhl_img_path'             => $this->module->getPathUri().'views/img/',
                'currentIndex'             => $this->context->link->getAdminLink('AdminDhlLabel'),
                'default_package_type'     => $defaultPackageType,
                'default_sender_address'   => $defaultSenderAddrDelivery,
                'default_shipment_content' => $defaultShipmentContent,
                'dhl_extracharges'         => $extracharges,
                'dimension_unit'           => $dimensionUnit,
                'extracharge_insurance'    => DhlExtracharge::getIdByCode('II'),
                'extracharge_excepted'     => DhlExtracharge::getIdByCode('HH'),
                'extracharge_liability'    => DhlExtracharge::getIdByCode('IB'),
                'extracharge_dangerous'    => DhlExtracharge::getIdByCode('HE'),
                'package_types'            => $packageTypes,
                'sender_addresses'         => $senderAddresses,
                'update_dhl_addr_link'     => $updateDhlAddrLink,
                'update_package_link'      => $updatePackageLink,
                'weight_unit'              => $weightUnit,
            )
        );
    }

    /**
     * @param $dhlLabel
     * @throws Exception
     * @throws SmartyException
     */
    public function displayExistingLabel($dhlLabel)
    {
        $this->assignVars();
        $dhlService = new DhlService((int) $dhlLabel->id_dhl_service);
        $this->context->smarty->assign(
            array(
                'errors'           => false,
                'id_dhl_label'     => $dhlLabel->id,
                'alreadyGenerated' => true,
                'freeLabel'        => false,
                'labelDetails'     => array(
                    'GlobalProductCode' => $dhlService->global_product_code,
                    'ProductShortName'  => $dhlService->global_product_name,
                    'AirwayBillNumber'  => $dhlLabel->awb_number,
                    'LabelImage'        => array(
                        'OutputFormat' => '',
                        'OutputImage'  => $dhlLabel->label_string,
                    ),
                ),
                'link'             => $this->context->link,
                'dhl_img_path'     => $this->module->getPathUri().'views/img/',
            )
        );
        $header = $this->createTemplate('../_partials/dhl-header.tpl')->fetch();
        $content = $this->createTemplate('./_partials/dhl-label-result.tpl')->fetch();
        $this->content = $header.$content;
    }

    /**
     * @param DhlOrder $dhlOrder
     * @throws Exception
     * @throws SmartyException
     */
    public function displayLabelForm($dhlOrder)
    {
        $this->assignVars();
        $idOrder = (int) $dhlOrder->id_order;
        $order = new Order((int) $idOrder);
        $cart = new Cart((int) $order->id_cart);
        $customerAddrDelivery = new Address((int) $order->id_address_delivery);
        $updateAddrLink =
            $this->context->link->getAdminLink('AdminAddresses').'&updateaddress&id_address='.$customerAddrDelivery->id;
        $orderCurency = new Currency((int) $order->id_currency);
        $dhlOrder = DhlOrder::getByIdOrder((int) $order->id);
        $dhlService = new DhlService((int) $dhlOrder->id_dhl_service);
        $doc = $dhlService->doc;
        $this->context->smarty->assign(
            array(
                'declared_value_with_taxes'    => $cart->getOrderTotal(true, Cart::ONLY_PRODUCTS_WITHOUT_SHIPPING),
                'declared_value_without_taxes' => $cart->getOrderTotal(false, Cart::ONLY_PRODUCTS_WITHOUT_SHIPPING),
                'currency_iso'                 => $orderCurency->iso_code,
                'customer_address'             => $customerAddrDelivery,
                'customer_country_iso'         => DhlTools::getIsoCountryById((int) $customerAddrDelivery->id_country),
                'id_order'                     => $idOrder,
                'dhl_sending_doc'              => $doc,
                'update_addr_link'             => $updateAddrLink,
                'shipper_id'                   => $order->reference,
            )
        );
        $this->content = $this->createTemplate('dhl_label.tpl')->fetch();
    }

    /**
     * @throws Exception
     * @throws PrestaShopDatabaseException
     * @throws SmartyException
     */
    public function displayFreeLabelForm()
    {
        $this->assignVars();
        $countries = Country::getCountries($this->context->language->id);
        $defaultCurrency = new Currency((int) Configuration::get('PS_CURRENCY_DEFAULT'));
        $this->context->smarty->assign(
            array(
                'countries'       => $countries,
                'currency_iso'    => $defaultCurrency->iso_code,
                'dhl_sending_doc' => (int) Configuration::get('DHL_SENDING_DOC'),
                'default_country' => Country::getByIso('FR'),
                'shipper_id'      => '',
            )
        );
        $this->content = $this->createTemplate('dhl_free_label.tpl')->fetch();
    }

    /**
     * @param int $idDhlLabel
     */
    public function downloadLabel($idDhlLabel)
    {
        $dhlLabel = new DhlLabel((int) $idDhlLabel);
        if (!Validate::isLoadedObject($dhlLabel)) {
            die($this->module->l('Invalid Label.', 'AdminDhlLabel'));
        }
        $labelType = $dhlLabel->label_format;
        $decoded = base64_decode($dhlLabel->label_string);
        $file = sys_get_temp_dir();
        if ($file && Tools::substr($file, -1) != DIRECTORY_SEPARATOR) {
            $file .= DIRECTORY_SEPARATOR;
        }
        if (in_array($labelType, array('pdf', 'zpl', 'epl'))) {
            $file .= $dhlLabel->awb_number.'.'.$labelType;
        } else {
            die($this->module->l('Invalid Label.', 'AdminDhlLabel'));
        }
        file_put_contents($file, $decoded);
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: '.filesize($file));
            readfile($file);
            unlink($file);
            exit;
        }
    }

    /**
     * @param bool $doc
     * @return array
     * @throws PrestaShopDatabaseException
     */
    public function getChosenExtracharges($doc = false)
    {
        $list = array();
        $extracharges = DhlExtracharge::getExtrachargesList($this->context->language->id);
        foreach ($extracharges as $extracharge) {
            $idDhlExtracharge = (int) $extracharge['id_dhl_extracharge'];
            if (Tools::isSubmit('extracharge_'.$idDhlExtracharge) &&
                1 == (int) Tools::getValue('extracharge_'.$idDhlExtracharge)
            ) {
                if (($doc && $extracharge['doc']) || (!$doc && !$extracharge['doc'])) {
                    $dhlExtracharge = new DhlExtracharge($idDhlExtracharge);
                    $list[$dhlExtracharge->extracharge_code] = $dhlExtracharge->label;
                }
            }
        }

        return $list;
    }

    /**
     * @param array $packagesDimension
     * @return array
     */
    public function getProductsParams($packagesDimension)
    {
        $productsParam = array();
        if ($packagesDimension) {
            foreach ($packagesDimension as $packageDimension) {
                $values = explode('x', $packageDimension);
                if (is_array($values) && 5 == count($values)) {
                    $productsParam[] = array(
                        'PieceID' => (int) $values[0],
                        'Weight'  => (float) $values[1],
                        'Width'   => (int) $values[3],
                        'Height'  => (int) $values[2],
                        'Depth'   => (int) $values[4],
                    );
                }
            }
        }

        return $productsParam;
    }

    /**
     * @param float $orderTotalWeight
     * @return array
     */
    public function getBulkProductsParams($orderTotalWeight)
    {
        $productsParams = array();
        $idDhlPackage = Tools::getValue('dhl_package_type');
        if (Tools::getValue('dhl_use_order_weight')) {
            $weight = $orderTotalWeight;
        } else {
            $weight = Tools::getValue('dhl_package_weight_'.(int) $idDhlPackage);
        }
        $productsParams[] = array(
            'PieceID' => (int) $idDhlPackage,
            'Weight'  => (float) $weight,
            'Width'   => (int) Tools::getValue('dhl_package_width_'.(int) $idDhlPackage),
            'Height'  => (int) Tools::getValue('dhl_package_length_'.(int) $idDhlPackage),
            'Depth'   => (int) Tools::getValue('dhl_package_depth_'.(int) $idDhlPackage),
        );

        return $productsParams;
    }

    /**
     * @param array $productsParam
     * @return int
     */
    public function getTotalWeight($productsParam)
    {
        $weight = 0;
        foreach ($productsParam as $productParam) {
            $weight += $productParam['Weight'];
        }

        return $weight;
    }

    /**
     * @param array $a
     * @param array $b
     * @return mixed
     */
    public function sortByOrder($a, $b)
    {
        return $a['ShippingCharge'] - $b['ShippingCharge'];
    }

    /**
     * @param array $services
     * @return mixed
     */
    public function sortServicesByPrice($services)
    {
        uasort($services, 'self::sortByOrder');

        return $services;
    }

    /**
     * @param array  $address
     * @param string $email
     * @return array
     */
    public function formatCustomerAddress($address, $email)
    {
        $phone = $address['phone'] ? $address['phone'] : $address['phone_mobile'];
        $company = $address['company'] ? $address['company'] : $address['firstname'].' '.$address['lastname'];
        $address2 = $address['address2'] ? Tools::substr($address['address2'], 0, 35) : '';

        return array(
            'id_address'   => (int) $address['id'],
            'alias'        => $address['alias'],
            'company_name' => Tools::substr($company, 0, 35),
            'person_name'  => Tools::substr($address['firstname'].' '.$address['lastname'], 0, 35),
            'address1'     => Tools::substr($address['address1'], 0, 35),
            'address2'     => $address2,
            'address3'     => '',
            'zipcode'      => $address['postcode'],
            'city'         => Tools::substr($address['city'], 0, 35),
            'id_country'   => (int) $address['id_country'],
            'id_state'     => (int) $address['id_state'],
            'phone'        => $phone,
            'email'        => $email,
        );
    }

    /**
     * @param DhlShipmentValidationRequest $shipmentRequest
     */
    public function setArchiveDoc(DhlShipmentValidationRequest &$shipmentRequest)
    {
        if (!$this->data['form']['id_return_label']) {
            $destinationType = DhlTools::getDestinationType(
                $this->data['sender']->iso_country,
                $this->data['customer']['country']
            );
        } else {
            $destinationType = DhlTools::getDestinationType(
                $this->data['customer']['country'],
                $this->data['sender']->iso_country
            );
        }
        if ($destinationType == 'WORLDWIDE') {
            $this->logger->info('Set archive doc to "Y" (worldwide shipment)');
            $shipmentRequest->setRequestArchiveDoc('Y');
        } else {
            if ($this->data['options']['archive_doc']) {
                $this->logger->info('Set archive doc to "Y" (form option)');
                $shipmentRequest->setRequestArchiveDoc('Y');
            }
        }
    }

    /**
     * @param DhlShipmentValidationRequest $shipmentRequest
     */
    public function setShipmentSenderConsignee(DhlShipmentValidationRequest &$shipmentRequest)
    {
        if ($this->data['form']['id_return_label']) {
            $shipmentRequest->setConsignee(
                array(
                    'CompanyName' => $this->data['sender']->company_name,
                    'AddressLine' => array(
                        $this->data['sender']->address1,
                        $this->data['sender']->address2,
                        $this->data['sender']->address3,
                    ),
                    'City'        => $this->data['sender']->city,
                    'PostalCode'  => $this->data['sender']->zipcode,
                    'CountryCode' => $this->data['sender']->iso_country,
                    'CountryName' => Country::getNameById(
                        $this->context->language->id,
                        $this->data['sender']->id_country
                    ),
                )
            );
            $shipmentRequest->setConsigneeContact(
                array(
                    'PersonName'  => $this->data['sender']->contact_name,
                    'PhoneNumber' => $this->data['sender']->contact_phone,
                    'Email'       => $this->data['sender']->contact_email,
                )
            );
            $shipmentRequest->setShipper(
                array(
                    'ShipperID'   => '1',
                    'CompanyName' => $this->data['customer']['company_name'] ? $this->data['customer']['company_name'] :
                        $this->data['customer']['person_name'],
                    'AddressLine' => array(
                        0 => $this->data['customer']['address1'],
                        1 => $this->data['customer']['address2'],
                        2 => $this->data['customer']['address3'],
                    ),
                    'City'        => $this->data['customer']['city'],
                    'PostalCode'  => $this->data['customer']['zipcode'],
                    'CountryCode' => $this->data['customer']['country'],
                    'CountryName' => Country::getNameById(
                        $this->context->language->id,
                        (int) $this->data['customer']['id_country']
                    ),
                )
            );
            $shipmentRequest->setContactShipper(
                array(
                    'PersonName'  => $this->data['customer']['person_name'],
                    'PhoneNumber' => $this->data['customer']['phone'],
                    'Email'       => $this->data['customer']['email'],
                )
            );
            $extrachargeLabelLifetime = DhlTools::getLabelLifetimeExtracharge();
            $shipmentRequest->setSpecialService(array($extrachargeLabelLifetime));
        } else {
            $shipmentRequest->setConsignee(
                array(
                    'CompanyName' => $this->data['customer']['company_name'] ? $this->data['customer']['company_name'] :
                        $this->data['customer']['person_name'],
                    'AddressLine' => array(
                        0 => $this->data['customer']['address1'],
                        1 => $this->data['customer']['address2'],
                        2 => $this->data['customer']['address3'],
                    ),
                    'City'        => $this->data['customer']['city'],
                    'PostalCode'  => $this->data['customer']['zipcode'],
                    'CountryCode' => $this->data['customer']['country'],
                    'CountryName' => Country::getNameById(
                        $this->context->language->id,
                        (int) $this->data['customer']['id_country']
                    ),
                )
            );
            $shipmentRequest->setConsigneeContact(
                array(
                    'PersonName'  => $this->data['customer']['person_name'],
                    'PhoneNumber' => $this->data['customer']['phone'] ? $this->data['customer']['phone'] : '0000000000',
                    'Email'       => $this->data['customer']['email'],
                )
            );
            $shipmentRequest->setShipper(
                array(
                    'ShipperID'   => '1',
                    'CompanyName' => $this->data['sender']->company_name,
                    'AddressLine' => array(
                        $this->data['sender']->address1,
                        $this->data['sender']->address2,
                        $this->data['sender']->address3,
                    ),
                    'City'        => $this->data['sender']->city,
                    'PostalCode'  => $this->data['sender']->zipcode,
                    'CountryCode' => $this->data['sender']->iso_country,
                    'CountryName' => Country::getNameById(
                        $this->context->language->id,
                        $this->data['sender']->id_country
                    ),
                )
            );
            $shipmentRequest->setContactShipper(
                array(
                    'PersonName'  => $this->data['sender']->contact_name,
                    'PhoneNumber' => $this->data['sender']->contact_phone,
                    'Email'       => $this->data['sender']->contact_email,
                )
            );
        }
    }

    /**
     * @param DhlLabel $dhlReturnLabelGenerated
     * @throws Exception
     * @throws SmartyException
     */
    public function displayGeneratedReturnLabel(DhlLabel $dhlReturnLabelGenerated)
    {
        $dhlService = new DhlService((int) $dhlReturnLabelGenerated->id_dhl_service);
        $this->context->smarty->assign(
            array(
                'errors'           => false,
                'id_dhl_label'     => $dhlReturnLabelGenerated->id,
                'alreadyGenerated' => true,
                'freeLabel'        => false,
                'labelDetails'     => array(
                    'GlobalProductCode' => $dhlService->global_product_code,
                    'ProductShortName'  => $dhlService->global_product_name,
                    'AirwayBillNumber'  => $dhlReturnLabelGenerated->awb_number,
                    'LabelImage'        => array(
                        'OutputFormat' => '',
                        'OutputImage'  => $dhlReturnLabelGenerated->label_string,
                    ),
                ),
                'link'             => $this->context->link,
                'dhl_img_path'     => $this->module->getPathUri().'views/img/',
            )
        );
        $html = $this->createTemplate('_partials/dhl-label-result.tpl')->fetch();
        $return = array(
            'html'   => $html,
            'errors' => true,
        );
        $this->ajaxDie(Tools::jsonEncode($return));
    }

    /**
     * @param DhlService $dhlServiceObj
     * @param array      $dhlServices
     * @return string
     */
    public function getServiceWantedCode(DhlService $dhlServiceObj, $dhlServices)
    {
        foreach ($dhlServices as $code => $dhlService) {
            if (trim($code) == $dhlServiceObj->global_product_code) {
                return $dhlServiceObj->global_product_code;
            }
        }

        return '';
    }

    /**
     * @throws PrestaShopDatabaseException
     */
    public function buildFormData()
    {
        $dhlOrder = DhlOrder::getByIdOrder((int) Tools::getValue('dhl_id_order'));
        $this->data = array(
            'form'             => array(
                'free_label'      => Tools::getValue('dhl_free_label'),
                'id_order'        => Tools::getValue('dhl_id_order'),
                'id_return_label' => Tools::getValue('dhl_id_return_label'),
                'id_dhl_order'    => Validate::isLoadedObject($dhlOrder) ? $dhlOrder->id : 0,
            ),
            'sender'           => new DhlAddress((int) Tools::getValue('dhl_sender_address')),
            'options'          => array(
                'iso_currency'            => Tools::getValue('dhl_label_currency_iso'),
                'declared_value_currency' => Tools::getValue('dhl_label_currency_iso'),
                'insured_value_currency'  => Tools::getValue('dhl_label_currency_iso'),
                'reference'               => Tools::getValue('dhl_reference_id'),
                'contents'                => Tools::getValue('dhl_label_contents'),
                'doc'                     => Tools::getValue('dhl_sending_doc'),
                'declared_value'          => (int) Tools::getValue('dhl_label_declared_value'),
                'insured_value'           => Tools::getValue('dhl_label_insured_value'),
                'global_product_code'     => Tools::getValue('dhl_label_service'),
                'archive_doc'             => (int) Tools::getValue('dhl_print_doc_archive'),
            ),
            'packages'         => $this->getProductsParams(Tools::getValue('package_dimensions')),
            'extracharges'     => $this->getChosenExtracharges(),
            'doc_extracharges' => $this->getChosenExtracharges(true),
        );
        if ($this->data['form']['free_label']) {
            $this->data['customer'] = array(
                'company_name' => Tools::getValue('company_name'),
                'person_name'  => Tools::getValue('person_name'),
                'address1'     => Tools::getValue('address1'),
                'address2'     => Tools::getValue('address2'),
                'address3'     => Tools::getValue('address3'),
                'zipcode'      => Tools::getValue('zipcode'),
                'city'         => Tools::getValue('city'),
                'id_country'   => Tools::getValue('id_country'),
                'id_state'     => Tools::getValue('id_state'),
                'phone'        => Tools::getValue('phone'),
                'email'        => Tools::substr(Tools::getValue('email'), 0, 50),
                'country'      => DhlTools::getIsoCountryById(Tools::getValue('id_country')),
            );
        } else {
            $address = new Address((int) Tools::getValue('dhl_customer_address'));
            $country = new Country((int) $address->id_country);
            $state = new State((int) $address->id_state);
            $customer = new Customer((int) $address->id_customer);
            $this->data['customer'] = array(
                'company_name' => $address->company,
                'person_name'  => $address->lastname.' '.$address->firstname,
                'address1'     => $address->address1,
                'address2'     => $address->address2,
                'address3'     => '',
                'zipcode'      => $address->postcode,
                'city'         => $address->city,
                'id_country'   => $country->id,
                'id_state'     => $state->id,
                'phone'        => $address->phone ? $address->phone : $address->phone_mobile,
                'email'        => Tools::substr($customer->email, 0, 50),
                'country'      => DhlTools::getIsoCountryById((int) $country->id),
            );
        }
    }

    /**
     *
     */
    public function buildBulkShipmentData()
    {
        $dhlOrder = new DhlOrder((int) Tools::getValue('id_dhl_order'));
        $order = new Order((int) $dhlOrder->id_order);
        $orderCurrency = new Currency((int) $order->id_currency);
        $defaultCurrency = new Currency((int) Configuration::get('PS_CURRENCY_DEFAULT'));
        $extracharges = array();
        if (Tools::getValue('dhl_use_declared_value')) {
            $declaredValue = (int) $order->total_products;
            $declaredValueCurrency = $orderCurrency->iso_code;
        } else {
            $declaredValueCurrency = $defaultCurrency->iso_code;
            if (Tools::getValue('dhl_declared_value')) {
                $declaredValue = (int) Tools::getValue('dhl_declared_value');
            } else {
                $declaredValue = 0;
            }
        }
        $insuredValueCurrency = $defaultCurrency->iso_code;
        if (Tools::getValue('dhl_insure_shipment') && Tools::getValue('dhl_insured_value')) {
            $insuredValue = Tools::getValue('dhl_insured_value');
            $idExtrachargeInsurance = DhlExtracharge::getIdByCode('II');
            $extrachargeInsurance = new DhlExtracharge((int) $idExtrachargeInsurance);
            $extracharges[$extrachargeInsurance->extracharge_code] = $extrachargeInsurance->label;
        } else {
            $insuredValue = 0;
        }
        $this->data = array(
            'form'             => array(
                'free_label'      => false,
                'id_order'        => $order->id,
                'id_return_label' => false,
                'id_dhl_order'    => $dhlOrder->id,
            ),
            'sender'           => new DhlAddress((int) Configuration::get('DHL_DEFAULT_SENDER_ADDRESS')),
            'options'          => array(
                'iso_currency'            => $orderCurrency->iso_code,
                'declared_value_currency' => $declaredValueCurrency,
                'insured_value_currency'  => $insuredValueCurrency,
                'reference'               => $order->reference,
                'contents'                => Tools::getValue('dhl_contents'),
                'doc'                     => false,
                'declared_value'          => (int) $declaredValue,
                'insured_value'           => $insuredValue,
                'archive_doc'             => (int) Tools::getValue('dhl_print_doc_archive'),
            ),
            'packages'         => $this->getBulkProductsParams($order->getTotalWeight()),
            'extracharges'     => $extracharges,
            'doc_extracharges' => array(),
        );
        $address = new Address((int) $order->id_address_delivery);
        $country = new Country((int) $address->id_country);
        $state = new State((int) $address->id_state);
        $customer = new Customer((int) $address->id_customer);
        $this->data['customer'] = array(
            'company_name' => $address->company,
            'person_name'  => $address->lastname.' '.$address->firstname,
            'address1'     => $address->address1,
            'address2'     => $address->address2,
            'address3'     => '',
            'zipcode'      => $address->postcode,
            'city'         => $address->city,
            'id_country'   => $country->id,
            'id_state'     => $state->id,
            'phone'        => $address->phone ? $address->phone : $address->phone_mobile,
            'email'        => Tools::substr($customer->email, 0, 50),
            'country'      => DhlTools::getIsoCountryById((int) $country->id),
        );
        $useDhlService = Tools::getValue('dhl_use_dhl_service');
        if ($useDhlService) {
            $idDhlService = $dhlOrder->id_dhl_service;
        } else {
            if ($country->iso_code == 'FR') {
                $idDhlService = Tools::getValue('dhl_services_domestic');
            } elseif (DhlTools::isEUCountry($country->iso_code)) {
                $idDhlService = Tools::getValue('dhl_services_europe');
            } else {
                $idDhlService = Tools::getValue('dhl_services_world');
            }
        }
        $dhlService = new DhlService((int) $idDhlService);
        $this->data['options']['global_product_code'] = $dhlService->global_product_code;
    }

    /**
     * @throws DhlException
     */
    public function validateFreeAddress()
    {
        $idCountry = $this->data['customer']['id_country'];
        $idState = $this->data['customer']['id_state'];
        $country = new Country((int) $idCountry);
        $errors = array();
        if ($country && !(int) $country->contains_states && $idState) {
            $errors[] = $this->l('You have selected a state for a country that does not contain states.');
        }
        if ((int) $country->contains_states && !$idState) {
            $errors[] = $this->l('An address located in a country containing states must have a state selected.');
        }
        $zipcode = $this->data['customer']['zipcode'];
        if ($country->zip_code_format && !$country->checkZipCode($zipcode)) {
            $errors[] = $this->l('Your Zip/postal code is incorrect.');
        } elseif (empty($zipcode) && $country->need_zip_code) {
            $errors[] = $this->l('A Zip/postal code is required.');
        } elseif ($zipcode && !Validate::isPostCode($zipcode)) {
            $errors[] = $this->l('The Zip/postal code is invalid.');
        }
        $requiredFields = array(
            'company_name' => $this->module->l('company_name', 'AdminDhlLabel'),
            'person_name'  => $this->module->l('person_name', 'AdminDhlLabel'),
            'address1'     => $this->module->l('address1', 'AdminDhlLabel'),
            'city'         => $this->module->l('city', 'AdminDhlLabel'),
            'phone'        => $this->module->l('phone', 'AdminDhlLabel'),
        );
        foreach ($requiredFields as $key => $requiredField) {
            if (!$this->data['customer'][$key]) {
                $errors[] = sprintf($this->l('The field %s is required in customer address.'), $requiredField);
            }
        }
        if (!empty($errors)) {
            throw new DhlException('Errors found while validating address.', 0, null, $errors);
        }
    }

    /**
     * @throws DhlException
     * @throws Exception
     * @throws SmartyException
     */
    public function validateRequest()
    {
        if ($this->data['form']['free_label']) {
            $this->validateFreeAddress();
        }
        if ($this->data['form']['id_return_label']) {
            $idReturnLabel = $this->data['form']['id_return_label'];
            $dhlLabelOrigin = new DhlLabel((int) $idReturnLabel);
            if ((int) $idReturnLabel && !Validate::isLoadedObject($dhlLabelOrigin)) {
                $this->logger->error('Return label. Origin label is not valid.');
                throw new DhlException($this->module->l('Invalid label.', 'AdminDhlLabel'));
            }
            $dhlReturnLabelGenerated = $dhlLabelOrigin->getDhlReturnLabel();
            if (Validate::isLoadedObject($dhlReturnLabelGenerated)) {
                $this->displayGeneratedReturnLabel($dhlReturnLabelGenerated);
            }
        }
        if (!Validate::isLoadedObject($this->data['sender'])) {
            $this->logger->error('Invalid sender address.');
            throw new DhlException($this->module->l('Invalid sender address.', 'AdminDhlLabel'));
        }
        if (empty($this->data['packages'])) {
            $this->logger->error('Missing packages.');
            throw new DhlException($this->module->l('You must add one package at least.', 'AdminDhlLabel'));
        }
        if (!$this->data['options']['reference']) {
            $this->logger->error('Missing Reference ID.');
            throw new DhlException($this->module->l('You must fill a Reference ID', 'AdminDhlLabel'), 0, null);
        }
        if (!$this->data['options']['contents']) {
            $this->logger->error('Missing shipping contents.');
            throw new DhlException($this->module->l('You must specify a description of the content', 'AdminDhlLabel'));
        }
        if (!$this->data['options']['doc']) {
            $isoFrom = $this->data['sender']->iso_country;
            $isoTo = $this->data['customer']['country'];
            $declaredValue = $this->data['options']['declared_value'];
            $insuredValue = $this->data['options']['insured_value'];
            if (DhlTools::isDeclaredValueRequired($isoFrom, $isoTo) && (int) !$declaredValue) {
                $this->logger->error('This shipment requires you to declare the goods value.');
                // @formatter:off
                throw new DhlException(
                    $this->module->l('This shipment requires you to declare the goods value.', 'AdminDhlLabel')
                );
                // @formatter:on
            }
            if (array_key_exists('II', $this->data['extracharges']) && (!$declaredValue || !$insuredValue)) {
                $this->logger->error('Merchant chose insurance extracharge. Missing declared/goods value.');
                // @formatter:off
                throw new DhlException(
                    $this->module->l('You chose the shipment insurance extracharge, therefore you need to declare both the goods value and the insured value.', 'AdminDhlLabel')
                );
                // @formatter:on
            }
            if (array_key_exists('DD', $this->data['extracharges']) && !$this->data['sender']->account_duty) {
                $this->logger->error('Missing DHL duty account number');
                // @formatter:off
                throw new DhlException(
                    $this->module->l('Please complete your DHL duty account number for the chosen shipping address.', 'AdminDhlLabel')
                );
                // @formatter:on
            }
        }
    }

    /**
     * @throws DhlException
     * @throws Exception
     * @throws PrestaShopException
     */
    public function generateLabel()
    {
        $this->logger->info('Generating label.');
        $credentials = DhlTools::getCredentials();
        $shipmentRequest = new DhlShipmentValidationRequest($credentials);
        if ($this->data['form']['id_return_label']) {
            $accountNumber =
                $this->data['sender']->getReturnShippingAccountNumber((int) $this->data['customer']['id_country']);
        } else {
            $accountNumber = $this->data['sender']->getAccountNumber();
        }
        $shipmentRequest->setBilling(
            array(
                'ShipperAccountNumber' => $accountNumber,
            )
        );

        if ($this->data['options']['doc']) {
            $this->logger->info('"doc" shipment.');
            if (array_key_exists('IB', $this->data['doc_extracharges'])) {
                $shipmentRequest->setSpecialService(array_keys($this->data['doc_extracharges']));
            }
        } else {
            $this->logger->info('Not a "doc" shipment.');
            if ($this->data['options']['declared_value']) {
                $shipmentRequest->setDuty(
                    array(
                        'DeclaredValue'    => $this->data['options']['declared_value'],
                        'DeclaredCurrency' => $this->data['options']['declared_value_currency'],
                    )
                );
            }
            if (array_key_exists('II', $this->data['extracharges'])) {
                $shipmentRequest->setInsuredValue($this->data['options']['insured_value']);
            }
            $dutyAccount = $this->data['sender']->account_duty;
            if (array_key_exists('DD', $this->data['extracharges'])) {
                $shipmentRequest->setBilling(
                    array(
                        'DutyPaymentType'   => 'T',
                        'DutyAccountNumber' => $dutyAccount,
                    )
                );
            }
            $shipmentRequest->setSpecialService(array_keys($this->data['extracharges']));
            $labelRegText = '';
            foreach ($this->data['extracharges'] as $chosenExtracharge) {
                $labelRegText .= $chosenExtracharge.'. ';
            }
            $labelRegText = Tools::substr($labelRegText, 0, Tools::strlen($labelRegText) - 2);
            $labelRegText = Tools::substr($labelRegText, 0, 150);
            if ($labelRegText) {
                $shipmentRequest->setLabelRegText($labelRegText);
            }
        }
        $shipmentRequest->setLanguageCode('fr');
        $this->setShipmentSenderConsignee($shipmentRequest);
        $this->setArchiveDoc($shipmentRequest);
        $weightUnit = DhlTools::getWeightUnit();
        $dimensionUnit = DhlTools::getDimensionUnit();
        $totalWeight = $this->getTotalWeight($this->data['packages']);

        $shipmentRequest->setShipmentDetails(
            array(
                'NumberOfPieces'    => count($this->data['packages']),
                'Pieces'            => $this->data['packages'],
                'Weight'            => $totalWeight,
                'WeightUnit'        => $weightUnit == 'kg' ? 'K' : 'L',
                'GlobalProductCode' => $this->data['options']['global_product_code'],
                'Date'              => date('Y-m-d'),
                'Contents'          => $this->data['options']['contents'],
                'DimensionUnit'     => $dimensionUnit == 'cm' ? 'C' : 'I',
                'CurrencyCode'      => $this->data['options']['insured_value_currency'],
            )
        );
        $labelType = Configuration::get('DHL_LABEL_TYPE');
        $labelImageFormat =
            isset($this->labelFormat[$labelType]) ? $this->labelFormat[$labelType] : $this->labelFormat['pdfa4'];
        $shipmentRequest->setLabelImageFormat($labelImageFormat);
        $shipmentRequest->setReferenceID($this->data['options']['reference']);
        $client = new DhlClient((int) Configuration::get('DHL_LIVE_MODE'));
        $client->setRequest($shipmentRequest);
        $this->logger->logXmlRequest($shipmentRequest);
        $response = $client->request();
        if ($response && $response instanceof DhlShipmentValidationResponse) {
            $errors = $response->getErrors();
            $this->logger->info('Response received.', array('label_resp' => $response));
            if (empty($errors)) {
                $labelDetails = $response->getLabelDetails();
                if ($this->data['form']['id_dhl_order'] && !$this->data['form']['free_label']) {
                    $idDhlService = DhlService::getIdByProductCode(
                        $labelDetails['GlobalProductCode'],
                        $this->data['options']['doc']
                    );
                    $dhlOrder = new DhlOrder((int) $this->data['form']['id_dhl_order']);
                    $dhlLabel = new DhlLabel();
                    $serviceArea = $labelDetails['ServiceAreaCode'];
                    $countryName = $labelDetails['CountryName'];
                    $chargeableWeight = $labelDetails['ChargeableWeight'];
                    $dhlLabel->id_dhl_order = (int) $dhlOrder->id_dhl_order;
                    $dhlLabel->id_dhl_service = (int) $idDhlService;
                    $dhlLabel->awb_number = pSQL($labelDetails['AirwayBillNumber']);
                    $dhlLabel->return_label = (int) $this->data['form']['id_return_label'];
                    $dhlLabel->label_string = pSQL($labelDetails['LabelImage']['OutputImage']);
                    $dhlLabel->piece_contents = pSQL($labelDetails['Contents']);
                    $dhlLabel->total_pieces = (int) $labelDetails['Piece'];
                    $dhlLabel->total_weight = Tools::strtoupper((float) $chargeableWeight.$weightUnit);
                    $dhlLabel->consignee_contact = pSQL($labelDetails['PersonName']);
                    $dhlLabel->consignee_destination = pSQL($serviceArea.' / '.Tools::strtoupper($countryName));
                    if (!$dhlLabel->save()) {
                        $this->logger->error('Cannot save label to DB');
                        throw new DhlException($this->module->l('Cannot save label locally', 'AdminDhlLabel'));
                    } else {
                        $this->logger->info('Saving label to DB', array('label' => $dhlLabel));
                        // Order changes to "Handling of shipment in progress" if has not in the past
                        $idDhlOsPreparation = (int) Configuration::get('DHL_OS_PREPARATION');
                        $order = new Order((int) $this->data['form']['id_order']);
                        $orderHistory = $order->getHistory($order->id_lang, (int) $idDhlOsPreparation);
                        if (empty($orderHistory)) {
                            $history = new OrderHistory();
                            $history->id_order = (int) $order->id;
                            $history->changeIdOrderState($idDhlOsPreparation, (int) $order->id);
                            $history->addWithemail();
                            $this->logger->info('Sending mail "Handling shipment in progress"');
                        }
                        $subject = $this->module->l('Handling of shipment in progress', 'AdminDhlLabel');
                        DhlTools::sendHandlingShipmentMail($order, $subject, $dhlLabel->awb_number);
                        $this->logger->info('Displaying label details.');
                        $return = array(
                            'errors'           => false,
                            'labelDetails'     => $labelDetails,
                            'id_dhl_label'     => $dhlLabel->id,
                            'alreadyGenerated' => false,
                            'freeLabel'        => false,
                        );

                        return $return;
                    }
                } elseif ($this->data['form']['free_label']) {
                    $this->logger->info('This is a free label. Displaying label details.');
                    $return = array(
                        'errors'           => false,
                        'labelDetails'     => $labelDetails,
                        'alreadyGenerated' => false,
                        'freeLabel'        => true,
                    );

                    return $return;
                } else {
                    $this->logger->error('Unexpected error.');
                    throw new DhlException($this->module->l('Unexpected error. Please try again.', 'AdminDhlLabel'));
                }
            } else {
                $this->logger->error('Errors found. Please review response.', array('errors' => $errors));
                throw new DhlException(str_replace('\\n', ' ', $errors['code'].' - '.$errors['text']));
            }
        } else {
            $this->logger->error('Cannot connect to DHL API.');
            throw new DhlException($this->module->l('Cannot connect to DHL API.', 'AdminDhlLabel'));
        }
    }

    /**
     * @param $response
     * @throws DhlException
     * @throws Exception
     * @throws PrestaShopDatabaseException
     * @throws SmartyException
     */
    public function handleDhlServicesResult($response)
    {
        if ($response && $response instanceof DhlQuoteResponse) {
            $errors = $response->getErrors();
            $this->logger->info('Response received.', array('quotation_resp' => $response));
            if (empty($errors)) {
                $services = $response->getServiceDetails();
                if (!$services || empty($services) || !$services['currency']) {
                    $this->logger->error('No product available.');
                    // @formatter:off
                    throw new DhlException(
                        $this->module->l('No product available. Please try again or update your shipment details', 'AdminDhlLabel')
                    );
                    // @formatter:on
                } else {
                    $this->logger->info('Services found.', array('services' => $services));
                    $isoOrderCurrency = $this->data['options']['iso_currency'];
                    $isoQuoteCurrency = $services['currency'];
                    $services['services'] = $this->sortServicesByPrice($services['services']);
                    $freeLabel = (bool) $this->data['form']['free_label'];
                    $serviceWantedCode = '';
                    $dhlServices = DhlService::getServices($this->context->language->id, false);
                    if (!$freeLabel) {
                        $idOrder = $this->data['form']['id_order'];
                        $order = new Order((int) $idOrder);
                        $orderCurency = new Currency((int) $order->id_currency);
                        $dhlOrder = DhlOrder::getByIdOrder((int) $order->id);
                        $dhlService = new DhlService((int) $dhlOrder->id_dhl_service);
                        $shippingCostPaid = number_format($order->total_shipping_tax_incl, 2);
                        $serviceWantedCode = $this->getServiceWantedCode($dhlService, $services['services']);
                        $this->context->smarty->assign(
                            array(
                                'service_wanted' => $dhlService->global_product_name,
                                'shipping_paid'  => $orderCurency->iso_code.' '.$shippingCostPaid,
                            )
                        );
                        if ($isoOrderCurrency !== $isoQuoteCurrency && $idOrder) {
                            $quoteCurrency = new Currency((int) Currency::getIdByIsoCode($isoQuoteCurrency));
                            $orderCurrency = new Currency((int) Currency::getIdByIsoCode($isoOrderCurrency));
                            $conversionRate = $quoteCurrency->conversion_rate / $orderCurrency->conversion_rate;
                            $this->context->smarty->assign(
                                array(
                                    'convert_price' => number_format(
                                        ($shippingCostPaid * $conversionRate * 100) / 100,
                                        2
                                    ),
                                )
                            );
                        }
                    }
                    $dhlServicesList = array();
                    foreach ($dhlServices as $service) {
                        $dhlServicesList[$service['global_product_code']] = 1;
                    }
                    $this->logger->info('Returning services.');
                    $this->context->smarty->assign(
                        array(
                            'dhl_img_path'        => $this->module->getPathUri().'views/img/',
                            'errors'              => false,
                            'free_label'          => $freeLabel,
                            'services'            => $services['services'],
                            'service_wanted_code' => $serviceWantedCode,
                            'services_currency'   => $services['currency'],
                            'available_services'  => $dhlServicesList,
                        )
                    );
                    $html = $this->createTemplate('_partials/dhl-services-result.tpl')->fetch();
                    $return = array(
                        'html'   => $html,
                        'errors' => false,
                    );
                    $this->ajaxDie(Tools::jsonEncode($return));
                }
            } else {
                $this->logger->error('Errors found. Please review response.', array('errors' => $errors));
                throw new DhlException(str_replace('\\n', ' ', $errors['code'].' - '.$errors['text']));
            }
        } else {
            $this->logger->error('Cannot connect to DHL API.');
            throw new DhlException($this->module->l('Cannot connect to DHL API.', 'AdminDhlLabel'));
        }
    }

    /**
     * @throws Exception
     * @throws PrestaShopDatabaseException
     * @throws SmartyException
     */
    public function ajaxProcessRetrieveDhlService()
    {
        require_once(dirname(__FILE__).'/../../api/loader.php');

        $this->logger->info('Retrieving DHL Services');
        $this->buildFormData();
        try {
            $this->validateRequest();
        } catch (DhlException $e) {
            $this->context->smarty->assign(
                array(
                    'errors'      => true,
                    'description' => $e->getErrors(),
                )
            );
            $html = $this->createTemplate('_partials/dhl-services-result.tpl')->fetch();
            $return = array(
                'html'   => $html,
                'errors' => true,
            );
            $this->ajaxDie(Tools::jsonEncode($return));
        }
        $credentials = DhlTools::getCredentials(true);
        $quoteRequest = new DhlQuoteRequest($credentials);
        if ($this->data['form']['id_return_label']) {
            $this->logger->info('Quotation for a return label.');
            $accountNumber =
                $this->data['sender']->getReturnShippingAccountNumber((int) $this->data['customer']['id_country']);
            $quoteRequest->setReceiver(
                array(
                    'CountryCode' => $this->data['sender']->iso_country,
                    'Postalcode'  => $this->data['sender']->zipcode,
                    'City'        => $this->data['sender']->city,
                )
            );
            $quoteRequest->setSender(
                array(
                    'CountryCode' => $this->data['customer']['country'],
                    'Postalcode'  => $this->data['customer']['zipcode'],
                    'City'        => $this->data['customer']['city'],
                )
            );
        } else {
            $this->logger->info('Quotation is for a standard label.');
            $accountNumber = $this->data['sender']->getAccountNumber();
            $quoteRequest->setSender(
                array(
                    'CountryCode' => $this->data['sender']->iso_country,
                    'Postalcode'  => $this->data['sender']->zipcode,
                    'City'        => $this->data['sender']->city,
                )
            );
            $quoteRequest->setReceiver(
                array(
                    'CountryCode' => $this->data['customer']['country'],
                    'Postalcode'  => $this->data['customer']['zipcode'],
                    'City'        => $this->data['customer']['city'],
                )
            );
        }
        if ($this->data['options']['doc']) {
            $this->logger->info('"doc" shipment.');
            if (array_key_exists('IB', $this->data['doc_extracharges'])) {
                $quoteRequest->setQtdShp(array_keys($this->data['doc_extracharges']));
            }
        } else {
            $this->logger->info('Not a "doc" shipment.');
            if ($this->data['options']['declared_value']) {
                $quoteRequest->setDuty(
                    array(
                        'DeclaredValue'    => $this->data['options']['declared_value'],
                        'DeclaredCurrency' => $this->data['options']['declared_value_currency'],
                    )
                );
            }
            if (array_key_exists('II', $this->data['extracharges'])) {
                $quoteRequest->setInsurance(
                    array(
                        'InsuredValue'    => $this->data['options']['insured_value'],
                        'InsuredCurrency' => $this->data['options']['insured_value_currency'],
                    )
                );
            }
            $quoteRequest->setQtdShp(array_keys($this->data['extracharges']));
        }
        $quoteRequest->setPackageDetails(
            array(
                'PaymentCountryCode'   => DhlTools::getIsoCountryById(
                    (int) Configuration::get(
                        'DHL_ACCOUNT_OWNER_COUNTRY'
                    )
                ),
                'Date'                 => date('Y-m-d'),
                'ReadyTime'            => 'PT'.date('H').'H'.date('i').'M',
                'DimensionUnit'        => Tools::strtoupper(DhlTools::getDimensionUnit()),
                'WeightUnit'           => Tools::strtoupper(DhlTools::getWeightUnit()),
                'Pieces'               => $this->data['packages'],
                'PaymentAccountNumber' => $accountNumber,
            )
        );
        $client = new DhlClient(1);
        $client->setRequest($quoteRequest);
        $this->logger->logXmlRequest($quoteRequest);
        $response = $client->request();
        try {
            $this->handleDhlServicesResult($response);
        } catch (DhlException $e) {
            $this->context->smarty->assign(
                array(
                    'errors'      => true,
                    'description' => array($e->getErrors()),
                )
            );
            $html = $this->createTemplate('_partials/dhl-services-result.tpl')->fetch();
            $return = array(
                'html'   => $html,
                'errors' => true,
            );
            $this->ajaxDie(Tools::jsonEncode($return));
        }
    }

    /**
     *
     */
    public function ajaxProcessValidateBulkLabelForm()
    {
        $errors = array();
        $idPackage = Tools::getValue('dhl_package_type');
        $declaredValue = Tools::getValue('dhl_use_declared_value') ? 1 : Tools::getValue('dhl_declared_value');
        if (!Tools::getValue('dhl_use_order_weight') && (float) !Tools::getValue('dhl_package_weight_'.$idPackage)) {
            // @formatter:off
            $errors = array(
                'description' => $this->module->l('You chose to use package weight. Therefore you need to fill a valid weight value.', 'AdminDhlLabel'),
                'errors'      => true,
            );
            // @formatter:on
        }
        if (Tools::getValue('dhl_insure_shipment') &&
            ((int) !Tools::getValue('dhl_insured_value') || (int) !$declaredValue)
        ) {
            // @formatter:off
            $errors = array(
                'description' => $this->module->l('You chose to insure shipments. Therefore you need to fill both insured value and declared value.', 'AdminDhlLabel'),
                'errors'      => true,
            );
            // @formatter:on
        }
        if (!Tools::getValue('dhl_contents')) {
            $errors = array(
                'description' => $this->module->l('You must fill a shipment content.', 'AdminDhlLabel'),
                'errors'      => true,
            );
        }
        if (!empty($errors)) {
            $this->ajaxDie(Tools::jsonEncode($errors));
        }
        $return = array(
            'errors' => false,
        );
        $this->ajaxDie(Tools::jsonEncode($return));
    }

    /**
     * @throws Exception
     * @throws SmartyException
     */
    public function ajaxProcessGenerateBulkLabel()
    {
        $this->buildBulkShipmentData();
        try {
            $this->validateRequest();
            $return = $this->generateLabel();
        } catch (DhlException $e) {
            $return = array(
                'description' => $e->getErrors(),
                'errors'      => true,
            );
            $this->ajaxDie(Tools::jsonEncode($return));
        }
        $this->ajaxDie(Tools::jsonEncode($return));
    }

    /**
     * @throws Exception
     * @throws SmartyException
     */
    public function ajaxProcessGenerateFormLabel()
    {
        $this->buildFormData();
        try {
            $this->validateRequest();
            $return = $this->generateLabel();
        } catch (DhlException $e) {
            $this->context->smarty->assign(
                array(
                    'errors'      => true,
                    'description' => $e->getErrors(),
                )
            );
            $html = $this->createTemplate('_partials/dhl-label-result.tpl')->fetch();
            $return = array(
                'html'   => $html,
                'errors' => true,
            );
            $this->ajaxDie(Tools::jsonEncode($return));
        }
        $this->context->smarty->assign(
            $return + array(
                'dhl_img_path' => $this->module->getPathUri().'views/img/',
                'link'         => $this->context->link,
            )
        );
        $html = $this->createTemplate('_partials/dhl-label-result.tpl')->fetch();
        $return = array(
            'html'   => $html,
            'errors' => false,
        );
        $this->ajaxDie(Tools::jsonEncode($return));
    }

    /**
     *
     */
    public function ajaxProcessAddDhlPackage()
    {
        $idDhlPackage = (int) Tools::getValue('id_dhl_package');
        $dhlPackage = new DhlPackage($idDhlPackage);
        $this->logger->info('Adding package #'.(int) $idDhlPackage.' to the shipment.');
        if ($dhlPackage && Validate::isLoadedObject($dhlPackage)) {
            $return = array(
                'errors'         => false,
                'packageDetails' => array(
                    'id'     => (int) $dhlPackage->id,
                    'name'   => Tools::safeOutput($dhlPackage->name),
                    'width'  => (int) Tools::getValue('width') ? (int) Tools::getValue('width') : 1,
                    'length' => (int) Tools::getValue('length') ? (int) Tools::getValue('length') : 1,
                    'depth'  => (int) Tools::getValue('depth') ? (int) Tools::getValue('depth') : 1,
                    'weight' => (float) Tools::getValue('weight'),
                ),
                'init'           => array(
                    'width'  => (float) $dhlPackage->width_value,
                    'length' => (float) $dhlPackage->length_value,
                    'depth'  => (float) $dhlPackage->depth_value,
                    'weight' => (float) $dhlPackage->weight_value,
                ),
            );
            $this->logger->info('Package details.', array('details' => $return));
            $this->ajaxDie(Tools::jsonEncode($return));
        }
    }

    /**
     *
     */
    public function ajaxProcessLoadAddresses()
    {
        $idCustomer = (int) Tools::getValue('idCustomer');
        $customer = new Customer((int) $idCustomer);
        $this->logger->info('Loading customer #'.(int) $idCustomer.' addresses.');
        if (!Validate::isLoadedObject($customer)) {
            $this->logger->error('Cannot load customer address list.');
            $return = array(
                'errors'      => true,
                'noAddresses' => true,
                'description' => $this->module->l('Cannot load customer addresses.', 'AdminDhlLabel'),
            );
            $this->ajaxDie(Tools::jsonEncode($return));
        }
        $addresses = $customer->getAddresses($this->context->language->id);
        if (empty($addresses)) {
            $this->logger->info('Customer does not have addresses yet.');
            $return = array(
                'errors'      => true,
                'noAddresses' => true,
                'description' => $this->module->l('Customer does not have any addresses.', 'AdminDhlLabel'),
            );
            $this->ajaxDie(Tools::jsonEncode($return));
        }
        $validAddresses = array();
        foreach ($addresses as $address) {
            $address['id'] = $address['id_address'];
            $validAddresses[] = $this->formatCustomerAddress($address, $customer->email);
        }
        $this->logger->info('Returning valid address list.', array('addresses' => $validAddresses));
        $return = array(
            'errors'    => false,
            'addresses' => $validAddresses,
        );
        $this->ajaxDie(Tools::jsonEncode($return));
    }

    /**
     *
     */
    public function ajaxProcessLoadAddress()
    {
        $idAddress = (int) Tools::getValue('idAddress');
        $address = new Address((int) $idAddress);
        $this->logger->info('Loading address #'.(int) $idAddress);
        if (!Validate::isLoadedObject($address)) {
            $this->logger->error('Address is not valid');
            // @formatter:off
            $return = array(
                'errors'      => true,
                'noAddresses' => true,
                'description' => $this->module->l('Address not valid, please fill the customer address manually.', 'AdminDhlLabel'),
            );
            // @formatter:on
            $this->ajaxDie(Tools::jsonEncode($return));
        }
        $customer = new Customer((int) $address->id_customer);
        if (!Validate::isLoadedObject($customer)) {
            $this->logger->error('Cannot load address. Merchant will fill it manually.');
            // @formatter:off
            $return = array(
                'errors'      => true,
                'noAddresses' => false,
                'description' => $this->module->l('Cannot load address, please fill the customer address manually.', 'AdminDhlLabel'),
            );
            // @formatter:on
            $this->ajaxDie(Tools::jsonEncode($return));
        }
        $this->logger->info('Returning address', array('address' => $address));
        $return = array(
            'errors'  => false,
            'address' => $this->formatCustomerAddress((array) $address, $customer->email),
        );
        $this->ajaxDie(Tools::jsonEncode($return));
    }

    /**
     * @throws Exception
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws SmartyException
     */
    public function ajaxProcessDeleteLabel()
    {
        /** @var Dhlexpress $module */
        $module = $this->module;
        $idDhlLabel = Tools::getValue('id_dhl_label');
        $this->logger->info('Deleting label #'.(int) $idDhlLabel);
        $dhlLabel = new DhlLabel((int) $idDhlLabel);
        if (!Validate::isLoadedObject($dhlLabel)) {
            $this->logger->error('Label not valid.');
            $this->ajaxDie(
                Tools::jsonEncode(
                    array(
                        'errors'  => true,
                        'message' => $this->module->l('Label not valid.', 'AdminDhlLabel'),
                    )
                )
            );
        }
        $dhlOrder = new DhlOrder((int) $dhlLabel->id_dhl_order);
        if (!Validate::isLoadedObject($dhlOrder)) {
            $this->logger->error(
                'DHL Order not valid.',
                array(
                    'id' => (int) $dhlLabel->id_dhl_order,
                )
            );
            $this->ajaxDie(
                Tools::jsonEncode(
                    array(
                        'errors'  => true,
                        'message' => $this->module->l('Order not valid.', 'AdminDhlLabel'),
                    )
                )
            );
        }
        if ($dhlLabel->return_label) {
            $this->logger->info('Label is a return label.');
            $delete = $dhlLabel->delete();
        } else {
            $dhlCI = DhlCommercialInvoice::getByIdDhlLabel((int) $dhlLabel->id);
            $dhlReturnLabel = new DhlLabel($dhlLabel->return_label);
            $this->logger->info(
                'Label is a standard label',
                array(
                    'dhl_commercial'   => $dhlCI,
                    'dhl_return_label' => $dhlReturnLabel,
                )
            );
            $delete = $dhlLabel->deleteLabel($dhlCI, $dhlReturnLabel);
        }
        if (!$delete) {
            $this->logger->error('Could not delete label.');
            Db::getInstance()->delete('shipment_tracking', 'id_dhl_label = '.(int) $idDhlLabel);
            $this->ajaxDie(
                Tools::jsonEncode(
                    array(
                        'errors'  => true,
                        'message' => $this->module->l('Cannot delete label.', 'AdminDhlLabel'),
                    )
                )
            );
        }
        $this->logger->info('Label deleted successfully.');
        $htmlTable = $module->getDhlShipmentDetailsTable($dhlOrder);
        $this->ajaxDie(
            Tools::jsonEncode(
                array(
                    'errors'  => false,
                    'message' => $this->module->l('Label deleted successfully', 'AdminDhlLabel'),
                    'html'    => $htmlTable,
                )
            )
        );
    }

    /**
     * @throws PrestaShopDatabaseException
     */
    public function ajaxProcessSearchCustomers()
    {
        $searches = explode(' ', Tools::getValue('customer_search'));
        $customers = array();
        $searches = array_unique($searches);
        foreach ($searches as $search) {
            if (!empty($search) && $results = Customer::searchByName($search, 50)) {
                foreach ($results as $result) {
                    if ($result['active']) {
                        $result['fullname_and_email'] =
                            $result['firstname'].' '.$result['lastname'].' - '.$result['email'];
                        $customers[$result['id_customer']] = $result;
                    }
                }
            }
        }

        if (count($customers) && Tools::getValue('sf2')) {
            $to_return = $customers;
        } elseif (count($customers) && !Tools::getValue('sf2')) {
            $to_return = array(
                'customers' => $customers,
                'found'     => true,
            );
        } else {
            $to_return = Tools::getValue('sf2') ? array() : array('found' => false);
        }

        $this->ajaxDie(Tools::jsonEncode($to_return));
    }
}
