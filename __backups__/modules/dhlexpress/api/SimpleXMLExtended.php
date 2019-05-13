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
 * Class SimpleXMLExtended
 *
 * @property SimpleXMLExtended GetQuote
 * @property SimpleXMLExtended To
 * @property SimpleXMLExtended From
 * @property SimpleXMLExtended BkgDetails
 * @property SimpleXMLExtended QtdShp
 * @property SimpleXMLExtended LanguageCode
 * @property SimpleXMLExtended Requestor
 * @property SimpleXMLExtended RequestorContact
 * @property SimpleXMLExtended Place
 * @property SimpleXMLExtended Pickup
 * @property SimpleXMLExtended weight
 * @property SimpleXMLExtended PickupContact
 * @property SimpleXMLExtended Pieces
 * @property SimpleXMLExtended IsDutiable
 * @property SimpleXMLExtended Billing
 * @property SimpleXMLExtended Consignee
 * @property SimpleXMLExtended AddressLine
 * @property SimpleXMLExtended Contact
 * @property SimpleXMLExtended ShipmentDetails
 * @property SimpleXMLExtended Shipper
 * @property SimpleXMLExtended DimensionUnit
 * @property SimpleXMLExtended Dutiable
 * @property SimpleXMLExtended LabelImageFormat
 * @property SimpleXMLExtended Label
 * @property SimpleXMLExtended LabelTemplate
 * @property SimpleXMLExtended Reference
 * @property SimpleXMLExtended ReferenceID
 * @property SimpleXMLExtended LabelRegText
 * @property SimpleXMLExtended Response
 * @property SimpleXMLExtended Status
 * @property SimpleXMLExtended Condition
 * @property SimpleXMLExtended ConditionCode
 * @property SimpleXMLExtended ConditionData
 * @property SimpleXMLExtended ConfirmationNumber
 * @property SimpleXMLExtended ReadyByTime
 * @property SimpleXMLExtended GetQuoteResponse
 * @property SimpleXMLExtended Note
 * @property SimpleXMLExtended ShippingCharge
 * @property SimpleXMLExtended TotalTaxAmount
 * @property SimpleXMLExtended PickupCutoffTime
 * @property SimpleXMLExtended GlobalProductCode
 * @property SimpleXMLExtended LocalProductName
 * @property SimpleXMLExtended DeliveryDate
 * @property SimpleXMLExtended DeliveryTime
 * @property SimpleXMLExtended LocalProductCode
 * @property SimpleXMLExtended CurrencyCode
 * @property SimpleXMLExtended ProductShortName
 * @property SimpleXMLExtended AirwayBillNumber
 * @property SimpleXMLExtended LabelImage
 * @property SimpleXMLExtended OutputFormat
 * @property SimpleXMLExtended OutputImage
 * @property SimpleXMLExtended Piece
 * @property SimpleXMLExtended Contents
 * @property SimpleXMLExtended DestinationServiceArea
 * @property SimpleXMLExtended ServiceAreaCode
 * @property SimpleXMLExtended PersonName
 * @property SimpleXMLExtended ChargeableWeight
 * @property SimpleXMLExtended CountryName
 * @property SimpleXMLExtended AWBInfo
 * @property SimpleXMLExtended AWBNumber
 * @property SimpleXMLExtended ShipmentInfo
 * @property SimpleXMLExtended ActionStatus
 * @property SimpleXMLExtended ShipmentEvent
 * @property SimpleXMLExtended ServiceEvent
 * @property SimpleXMLExtended EventCode
 * @property SimpleXMLExtended RequestArchiveDoc
 */
class SimpleXMLExtended extends SimpleXMLElement
{
    /**
     * @param string $cdataText
     */
    public function addCData($cdataText)
    {
        $node = dom_import_simplexml($this);
        $no = $node->ownerDocument;
        $node->appendChild($no->createCDATASection($cdataText));
    }

    /**
     * @param SimpleXMLExtended $insert
     * @return SimpleXMLExtended
     */
    public function insertAfter($insert)
    {
        $targetDom = dom_import_simplexml($this);
        $insertDom = $targetDom->ownerDocument->importNode(dom_import_simplexml($insert), true);
        if ($targetDom->nextSibling) {
            return simplexml_import_dom(
                $targetDom->parentNode->insertBefore($insertDom, $targetDom->nextSibling),
                'SimpleXMLExtended'
            );
        } else {
            return simplexml_import_dom($targetDom->parentNode->appendChild($insertDom), 'SimpleXMLExtended');
        }
    }
}
