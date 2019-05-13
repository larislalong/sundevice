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
 * Class DhlPickupRequest
 */
class DhlPickupRequest extends AbstractDhlRequest
{
    /**
     *
     */
    const METHOD = 'POST';
    /**
     *
     */
    const XML_TEMPLATE = '/xml/BookPURequest.xml';


    /**
     * @return string
     */
    public function getMethod()
    {
        return self::METHOD;
    }

    /**
     * @return string
     */
    public function getXMLTemplateFile()
    {
        return self::XML_TEMPLATE;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->xml->asXML();
    }

    /**
     * @return string
     */
    public function getXmlName()
    {
        return false;
    }

    /**
     * @param SimpleXMLExtended $response
     * @return DhlPickupResponse
     */
    public function buildResponse(SimpleXMLExtended $response)
    {
        if (!$response) {
            die();
        }
        $pickupResponse = DhlPickupResponse::buildFromResponse($response);

        return $pickupResponse;
    }

    /**
     * @param array $requestorDetails
     */
    public function setRequestor($requestorDetails)
    {
        foreach ($requestorDetails as $requestorDetailKey => $requestorDetailValue) {
            if ('RequestorContact' != $requestorDetailKey) {
                $this->xml->Requestor->$requestorDetailKey = $requestorDetailValue;
            }
        }
        if (isset($requestorDetails['RequestorContact']) && is_array($requestorDetails['RequestorContact'])) {
            foreach ($requestorDetails['RequestorContact'] as $contactDetailKey => $contactDetailValue) {
                $this->xml->Requestor->RequestorContact->$contactDetailKey = $contactDetailValue;
            }
        }
    }

    /**
     * @param array $placeDetails
     */
    public function setPlace($placeDetails)
    {
        foreach ($placeDetails as $placeDetailKey => $placeDetailValue) {
            $this->xml->Place->$placeDetailKey = $placeDetailValue;
        }
    }

    /**
     * @param array $pickupDetails
     */
    public function setPickup($pickupDetails)
    {
        foreach ($pickupDetails as $pickupDetailKey => $pickupDetailValue) {
            if ($pickupDetailKey != 'Weight') {
                $this->xml->Pickup->$pickupDetailKey = $pickupDetailValue;
            }
        }
        if (isset($pickupDetails['Weight']) && is_array($pickupDetails['Weight'])) {
            foreach ($pickupDetails['Weight'] as $pickupDetailKey => $pickupDetailValue) {
                $this->xml->Pickup->weight->$pickupDetailKey = $pickupDetailValue;
            }
        }
    }

    /**
     * @param array $contactDetails
     */
    public function setContact($contactDetails)
    {
        foreach ($contactDetails as $contactDetailKey => $contactDetailValue) {
            $this->xml->PickupContact->$contactDetailKey = $contactDetailValue;
        }
    }
}
