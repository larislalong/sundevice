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
 * Class DhlQuoteResponse
 */
class DhlQuoteResponse extends AbstractDhlResponse implements DhlReturnedResponseInterface
{
    /** @var array $serviceDetails */
    protected $serviceDetails;

    /**
     * @return bool
     */
    public function getSpecificErrorResponseNode()
    {
        return false;
    }

    /**
     * @param SimpleXMLExtended $response
     * @return DhlQuoteResponse
     */
    public static function buildFromResponse(SimpleXMLExtended $response)
    {
        $quoteResponse = new self($response);
        $rootResponseNode = $response->getName();
        if ($rootResponseNode == $quoteResponse->getSpecificErrorResponseNode() ||
            $rootResponseNode == $quoteResponse->getGenericErrorResponseNode()
        ) {
            $quoteResponse->errors = array(
                'code' => $response->Response->Status->Condition->ConditionCode->__toString(),
                'text' => $response->Response->Status->Condition->ConditionData->__toString(),
            );

            return $quoteResponse;
        } elseif (isset($response->GetQuoteResponse->Note->Condition->ConditionCode)) {
            $quoteResponse->errors = array(
                'code' => $response->GetQuoteResponse->Note->Condition->ConditionCode->__toString(),
                'text' => $response->GetQuoteResponse->Note->Condition->ConditionData->__toString(),
            );

            return $quoteResponse;
        }
        $serviceDetails = array();
        /** @var SimpleXMLExtended[] $bkgDetails */
        $bkgDetails = $response->GetQuoteResponse->BkgDetails->QtdShp;
        $nbServices = 0;
        $quoteResponse->serviceDetails['currency'] = '';
        foreach ($bkgDetails as $bkgDetail) {
            $nbServices++;
            $shippingCharge = $bkgDetail->ShippingCharge->__toString();
            $totalTaxAmount = $bkgDetail->TotalTaxAmount->__toString();
            if (!$shippingCharge) {
                continue;
            }
            $cutoffTime = $bkgDetail->PickupCutoffTime->__toString();
            $cutoffTime = str_replace(array('PT', 'M'), '', $cutoffTime);

            // We need to add a space before product code to have the possibility to sort services by price later
            // Some browsers like Chrome ignore the order and sort the array themselves.
            $serviceDetails[' '.(string) $bkgDetail->GlobalProductCode] = array(
                'GlobalProductCode' => $bkgDetail->GlobalProductCode->__toString(),
                'ProductShortName'  => $bkgDetail->LocalProductName->__toString(),
                'DeliveryDate'      => $bkgDetail->DeliveryDate->__toString(),
                'DeliveryTime'      => $bkgDetail->DeliveryTime->__toString(),
                'PickupCutoffTime'  => $cutoffTime,
                'LocalProductCode'  => $bkgDetail->LocalProductCode->__toString(),
                'CurrencyCode'      => $bkgDetail->CurrencyCode->__toString(),
                'ShippingCharge'    => $shippingCharge,
                'TotalTaxAmount'    => $totalTaxAmount,
                'ChargeWithoutTax'  => number_format($shippingCharge - $totalTaxAmount, 2),
            );
            $quoteResponse->serviceDetails['currency'] = $bkgDetail->CurrencyCode->__toString();
        }
        $quoteResponse->serviceDetails['services'] = $serviceDetails;

        return $quoteResponse;
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
    public function getServiceDetails()
    {
        return $this->serviceDetails;
    }
}
