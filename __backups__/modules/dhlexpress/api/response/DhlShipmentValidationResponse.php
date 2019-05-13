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
 * Class DhlShipmentValidationResponse
 */
class DhlShipmentValidationResponse extends AbstractDhlResponse implements DhlReturnedResponseInterface
{
    /**
     *
     */
    const SPECIFIC_ERROR_RESPONSE_NODE = 'ShipmentValidateErrorResponse';

    /** @var array $labelDetails */
    protected $labelDetails;

    /**
     * @param SimpleXMLExtended $response
     * @return DhlShipmentValidationResponse
     */
    public static function buildFromResponse(SimpleXMLExtended $response)
    {
        $shipmentResponse = new self($response);
        $shipmentResponse->errors = false;
        $rootResponseNode = $response->getName();
        if ($rootResponseNode == $shipmentResponse->getSpecificErrorResponseNode() ||
            $rootResponseNode == $shipmentResponse->getGenericErrorResponseNode()
        ) {
            $shipmentResponse->errors = array(
                'code' => $response->Response->Status->Condition->ConditionCode->__toString(),
                'text' => $response->Response->Status->Condition->ConditionData->__toString(),
            );

            return $shipmentResponse;
        }
        $shipmentResponse->labelDetails = array(
            'GlobalProductCode' => $response->GlobalProductCode->__toString(),
            'ProductShortName'  => $response->ProductShortName->__toString(),
            'AirwayBillNumber'  => $response->AirwayBillNumber->__toString(),
            'LabelImage'        => array(
                'OutputFormat' => $response->LabelImage->OutputFormat->__toString(),
                'OutputImage'  => $response->LabelImage->OutputImage->__toString(),
            ),
            'Piece'             => $response->Piece->__toString(),
            'Contents'          => utf8_decode($response->Contents->__toString()),
            'ServiceAreaCode'   => $response->DestinationServiceArea->ServiceAreaCode->__toString(),
            'PersonName'        => utf8_decode($response->Consignee->Contact->PersonName->__toString()),
            'ChargeableWeight'  => $response->ChargeableWeight->__toString(),
            'CountryName'       => utf8_decode($response->Consignee->CountryName->__toString()),
        );

        return $shipmentResponse;
    }

    /**
     * @return string
     */
    public function getSpecificErrorResponseNode()
    {
        return self::SPECIFIC_ERROR_RESPONSE_NODE;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return mixed
     */
    public function getLabelDetails()
    {
        return $this->labelDetails;
    }
}
