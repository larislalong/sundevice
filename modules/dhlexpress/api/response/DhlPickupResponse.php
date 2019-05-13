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
 * Class DhlPickupResponse
 */
class DhlPickupResponse extends AbstractDhlResponse implements DhlReturnedResponseInterface
{
    /**
     *
     */
    const SPECIFIC_ERROR_RESPONSE_NODE = 'PickupErrorResponse';

    /** @var array $pickupDetails */
    protected $pickupDetails;

    /**
     * @return bool
     */
    public function getSpecificErrorResponseNode()
    {
        return self::SPECIFIC_ERROR_RESPONSE_NODE;
    }

    /**
     * @param SimpleXMLExtended $response
     * @return DhlPickupResponse
     */
    public static function buildFromResponse(SimpleXMLExtended $response)
    {
        $pickupResponse = new self($response);
        $rootResponseNode = $response->getName();
        if ($rootResponseNode == $pickupResponse->getSpecificErrorResponseNode() ||
            $rootResponseNode == $pickupResponse->getGenericErrorResponseNode()
        ) {
            $pickupResponse->errors = array(
                'code' => $response->Response->Status->Condition->ConditionCode->__toString(),
                'text' => $response->Response->Status->Condition->ConditionData->__toString(),
            );

            return $pickupResponse;
        }

        $pickupResponse->pickupDetails = array(
            'ConfirmationNumber' => $response->ConfirmationNumber->__toString(),
            'ReadyByTime'        => $response->ReadyByTime->__toString(),
        );

        return $pickupResponse;
    }

    /**
     * @return mixed
     */
    public function getPickupDetails()
    {
        return $this->pickupDetails;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
