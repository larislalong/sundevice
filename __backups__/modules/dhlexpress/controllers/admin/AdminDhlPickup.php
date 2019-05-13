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
 * Class AdminDhlPickupController
 */
class AdminDhlPickupController extends ModuleAdminController
{
    /**
     * AdminDhlPickupController constructor.
     * @throws PrestaShopException
     */
    public function __construct()
    {
        require_once(dirname(__FILE__).'/../../api/loader.php');
        require_once(dirname(__FILE__).'/../../classes/DhlAddress.php');
        require_once(dirname(__FILE__).'/../../classes/DhlTools.php');
        require_once(dirname(__FILE__).'/../../classes/DhlError.php');
        require_once(dirname(__FILE__).'/../../classes/DhlPickup.php');

        $this->bootstrap = true;
        $this->context = Context::getContext();
        parent::__construct();
    }

    /**
     * @return bool
     * @throws Exception
     * @throws SmartyException
     */
    public function postProcess()
    {
        $this->displayPickupForm();

        return parent::postProcess();
    }

    /**
     * @throws Exception
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws SmartyException
     */
    public function ajaxProcessRequestDhlPickupForce()
    {
        $this->ajaxProcessRequestDhlPickup(true);
    }

    /**
     * @param bool|false $force
     * @throws Exception
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws SmartyException
     */
    public function ajaxProcessRequestDhlPickup($force = false)
    {
        $requiredFields = array(
            'dhl_pickup_location',
            'dhl_pickup_contact',
            'dhl_pickup_phone',
            'dhl_pickup_date',
            'dhl_pickup_weight',
            'dhl_pickup_packages',
            'dhl_pickup_instructions',
        );
        foreach ($requiredFields as $requiredField) {
            if (!Tools::getValue($requiredField)) {
                // @formatter:off
                $this->context->smarty->assign(
                    array(
                        'errors'      => true,
                        'description' => $this->module->l('Please make sure you filled all required fields.', 'AdminDhlPickup'),
                    )
                );
                // @formatter:on
                $html = $this->createTemplate('_partials/dhl-pickup-result.tpl')->fetch();
                $return = array(
                    'html' => $html,
                );
                $this->ajaxDie(Tools::jsonEncode($return));
            }
        }
        $idDhlAdress = Tools::getValue('dhl_sender_address');
        $closingTime = Tools::getValue('dhl_closing_time_hour').':'.Tools::getValue('dhl_closing_time_minute');
        $pickupLocation = Tools::getValue('dhl_pickup_location');
        $pickupContactName = Tools::getValue('dhl_pickup_contact');
        $pickupContactPhone = Tools::getValue('dhl_pickup_phone');
        $requestedDate = strtotime(Tools::getValue('dhl_pickup_date'));
        $pickupDate = date('Y-m-d', $requestedDate);
        $requestedTime = Tools::getValue('dhl_request_time_hour').':'.Tools::getValue('dhl_request_time_minute');
        $pickupWeight = Tools::getValue('dhl_pickup_weight');
        $nbParcels = Tools::getValue('dhl_pickup_packages');
        $specialInstructions = Tools::getValue('dhl_pickup_instructions');

        // If daily pickup is enabled in module configuration then we should not allow more requests.
        // If daily pickup is not enabled, we allow one request per day and per location / address.
        // However, merchants need to have to ability to "force" a second pickup request by clicking on "Yes" when
        // they're asked to confirm the addional request

        $dailyPickup = Configuration::get('DHL_DAILY_PICKUP');
        $pickups = DhlPickup::getPickupByDateLocation($pickupDate, $idDhlAdress);
        if ((int) $dailyPickup) {
            if ($pickups && is_array($pickups) && count($pickups) >= 1) {
                // @formatter:off
                $this->context->smarty->assign(
                    array(
                        'errors'      => true,
                        'description' => $this->module->l('You cannot request more pickup for this date, at this location.', 'AdminDhlPickup'),
                    )
                );
                // @formatter:on
                $html = $this->createTemplate('_partials/dhl-pickup-result.tpl')->fetch();
                $return = array(
                    'html' => $html,
                );
                $this->ajaxDie(Tools::jsonEncode($return));
            } else {
                if (!$force) {
                    // @formatter:off
                    $this->context->smarty->assign(
                        array(
                            'errors'           => false,
                            'pickup_resume'    => $this->module->l('Regaular pickup option is enabled, that means a pickup has already been scheduled for this date.', 'AdminDhlPickup'),
                            'alreadyRequested' => true,
                        )
                    );
                    // @formatter:on
                    $html = $this->createTemplate('_partials/dhl-pickup-result.tpl')->fetch();
                    $return = array(
                        'html' => $html,
                    );
                    $this->ajaxDie(Tools::jsonEncode($return));
                }
            }
        } else {
            if ($pickups) {
                if (is_array($pickups) && count($pickups) >= 2) {
                    // @formatter:off
                    $this->context->smarty->assign(
                        array(
                            'errors'      => true,
                            'description' => $this->module->l('You cannot request more pickup for this date, at this location.', 'AdminDhlPickup'),
                        )
                    );
                    // @formatter:on
                    $html = $this->createTemplate('_partials/dhl-pickup-result.tpl')->fetch();
                    $return = array(
                        'html' => $html,
                    );
                    $this->ajaxDie(Tools::jsonEncode($return));
                } elseif (is_array($pickups) && count($pickups) == 1) {
                    // @formatter:off
                    $pickupResume = sprintf(
                        $this->module->l('You already request a pickup at this location on %s at %s (confirmation number: %d)', 'AdminDhlPickup'),
                        Tools::safeOutput($pickups[0]['pickup_date']),
                        Tools::safeOutput($pickups[0]['pickup_time']),
                        (int) $pickups[0]['confirmation_number']
                    );
                    // @formatter:on
                    if (!$force) {
                        $this->context->smarty->assign(
                            array(
                                'errors'           => false,
                                'pickup_resume'    => $pickupResume,
                                'alreadyRequested' => true,
                            )
                        );
                        $html = $this->createTemplate('_partials/dhl-pickup-result.tpl')->fetch();
                        $return = array(
                            'html' => $html,
                        );
                        $this->ajaxDie(Tools::jsonEncode($return));
                    }
                }
            }
        }
        $dhlAddress = new DhlAddress($idDhlAdress);
        $dhlAccountNumber = $dhlAddress->getAccountNumber();
        $credentials = DhlTools::getCredentials();
        $pickupRequest = new DhlPickupRequest($credentials);
        $pickupRequest->setRequestor(
            array(
                'AccountType'      => 'D',
                'AccountNumber'    => $dhlAccountNumber,
                'RequestorContact' => array(
                    'PersonName' => $pickupContactName,
                    'Phone'      => $pickupContactPhone,
                ),
                'CompanyName'      => $dhlAddress->company_name,
            )
        );
        $pickupRequest->setPlace(
            array(
                'LocationType'    => 'B',
                'CompanyName'     => $dhlAddress->company_name,
                'Address1'        => $dhlAddress->address1,
                'Address2'        => $dhlAddress->address2,
                'PackageLocation' => $pickupLocation,
                'City'            => $dhlAddress->city,
                'CountryCode'     => $dhlAddress->iso_country,
                'PostalCode'      => $dhlAddress->zipcode,
            )
        );
        $pickupRequest->setPickup(
            array(
                'PickupDate'          => $pickupDate,
                'ReadyByTime'         => $requestedTime,
                'CloseTime'           => $closingTime,
                'Pieces'              => $nbParcels,
                'Weight'              => array(
                    'Weight'     => $pickupWeight,
                    'WeightUnit' => DhlTools::getWeightUnit() == 'kg' ? 'K' : 'L',
                ),
                'SpecialInstructions' => $specialInstructions,
            )
        );
        $pickupRequest->setContact(
            array(
                'PersonName' => $pickupContactName,
                'Phone'      => $pickupContactPhone,
            )
        );
        $dhlClient = new DhlClient((int) Configuration::get('DHL_LIVE_MODE'));
        $dhlClient->setRequest($pickupRequest);
        $response = $dhlClient->request();
        if ($response && $response instanceof DhlPickupResponse) {
            $errors = $response->getErrors();
            if (empty($errors)) {
                $pickupDetails = $response->getPickupDetails();
                $dhlPickup = new DhlPickup();
                $dhlPickup->id_dhl_address = (int) $idDhlAdress;
                $dhlPickup->pickup_date = pSQL($pickupDate);
                $dhlPickup->pickup_time = pSQL($requestedTime);
                $dhlPickup->confirmation_number = (int) $pickupDetails['ConfirmationNumber'];
                $dhlPickup->total_pieces = (int) $nbParcels;
                if (!$dhlPickup->save()) {
                    $this->context->smarty->assign(
                        array(
                            'errors'      => true,
                            'description' => $this->module->l('Cannot save pickup details.', 'AdminDhlPickup'),
                        )
                    );
                    $html = $this->createTemplate('_partials/dhl-pickup-result.tpl')->fetch();
                    $return = array(
                        'html' => $html,
                    );
                    $this->ajaxDie(Tools::jsonEncode($return));
                }
                $this->context->smarty->assign(
                    array(
                        'errors'        => false,
                        'pickupDetails' => $pickupDetails,
                    )
                );
                $html = $this->createTemplate('_partials/dhl-pickup-result.tpl')->fetch();
                $return = array(
                    'html' => $html,
                );
                $this->ajaxDie(Tools::jsonEncode($return));
            } else {
                $message = DhlError::getMessageByCode($errors['code'], $this->context->language->id);
                if (!$message) {
                    $message = $errors['code'].' - '.$errors['text'];
                }
                $this->context->smarty->assign(
                    array(
                        'errors'      => true,
                        'description' => Tools::safeOutput($message),
                    )
                );
                $html = $this->createTemplate('_partials/dhl-pickup-result.tpl')->fetch();
                $return = array(
                    'html' => $html,
                );
                $this->ajaxDie(Tools::jsonEncode($return));
            }
        } else {
            $this->context->smarty->assign(
                array(
                    'errors'      => true,
                    'description' => $this->module->l('Cannot connect to DHL API.', 'AdminDhlPickup'),
                )
            );
            $html = $this->createTemplate('_partials/dhl-pickup-result.tpl')->fetch();
            $return = array(
                'html' => $html,
            );
            $this->ajaxDie(Tools::jsonEncode($return));
        }
    }

    /**
     * @throws Exception
     * @throws SmartyException
     */
    public function displayPickupForm()
    {
        $defaultSenderAddrDelivery = (int) Configuration::get('DHL_DEFAULT_SENDER_ADDRESS');
        $defaultSenderAddress = new DhlAddress((int) $defaultSenderAddrDelivery);
        if (Validate::isLoadedObject($defaultSenderAddress)) {
            $defaultSenderContactName = $defaultSenderAddress->contact_name;
            $defaultSenderContactPhone = $defaultSenderAddress->contact_phone;
        } else {
            $defaultSenderContactName = '';
            $defaultSenderContactPhone = '';
        }
        $senderAddresses = DhlAddress::getAddressList();
        $updateDhlAddrLink = $this->context->link->getAdminLink('AdminModules').'&configure='.$this->module->name;
        $this->context->smarty->assign(
            array(
                'link'                   => $this->context->link,
                'dhl_img_path'           => $this->module->getPathUri().'views/img/',
                'currentIndex'           => $this->context->link->getAdminLink('AdminDhlPickup'),
                'sender_addresses'       => $senderAddresses,
                'default_sender_address' => $defaultSenderAddrDelivery,
                'default_sender_contact' => $defaultSenderContactName,
                'default_sender_phone'   => $defaultSenderContactPhone,
                'update_dhl_addr_link'   => $updateDhlAddrLink,
                'weight_unit'            => DhlTools::getWeightUnit(),
            )
        );
        $this->content = $this->createTemplate('dhl_pickup.tpl')->fetch();
    }
}
