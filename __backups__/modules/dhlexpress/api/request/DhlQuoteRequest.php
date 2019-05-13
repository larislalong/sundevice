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
 * Class DhlQuoteRequest
 */
class DhlQuoteRequest extends AbstractDhlRequest
{
    /**
     *
     */
    const METHOD = 'POST';
    /**
     *
     */
    const XML_TEMPLATE = '/xml/GetQuote.xml';
    /**
     *
     */
    const XML_NAME = 'GetQuote';

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
        return self::XML_NAME;
    }

    /**
     * @param SimpleXMLExtended $response
     * @return DhlQuoteResponse
     */
    public function buildResponse(SimpleXMLExtended $response)
    {
        if (!$response) {
            die();
        }
        $quoteResponse = DhlQuoteResponse::buildFromResponse($response);

        return $quoteResponse;
    }

    /**
     * @param array $senderDetails
     */
    public function setSender($senderDetails)
    {
        foreach ($senderDetails as $senderDetailKey => $senderDetailValue) {
            $this->xml->GetQuote->From->$senderDetailKey = $senderDetailValue;
        }
    }

    /**
     * @param array $receiverDetails
     */
    public function setReceiver($receiverDetails)
    {
        foreach ($receiverDetails as $receiverDetailKey => $receiverDetailValue) {
            $this->xml->GetQuote->To->$receiverDetailKey = $receiverDetailValue;
        }
    }

    /**
     * @param array $packageDetails
     */
    public function setPackageDetails($packageDetails)
    {
        foreach ($packageDetails as $packageDetailKey => $packageDetailValue) {
            if ('Pieces' != $packageDetailKey) {
                $this->xml->GetQuote->BkgDetails->$packageDetailKey = $packageDetailValue;
            }
        }
        if (isset($packageDetails['Pieces']) && is_array($packageDetails['Pieces'])) {
            foreach ($packageDetails['Pieces'] as $piece) {
                $pieceElem = $this->xml->GetQuote->BkgDetails->Pieces->addChild('Piece');
                $pieceElem->addChild('PieceID', $piece['PieceID']);
                $pieceElem->addChild('Height', $piece['Height']);
                $pieceElem->addChild('Depth', $piece['Depth']);
                $pieceElem->addChild('Width', $piece['Width']);
                $pieceElem->addChild('Weight', $piece['Weight']);
            }
        }
    }

    /**
     * @param array $extraCharges
     */
    public function setQtdShp($extraCharges)
    {
        if ($extraCharges) {
            /** @var SimpleXMLExtended $qtdShp */
            $qtdShp = $this->xml->GetQuote->BkgDetails->QtdShp;
            foreach ($extraCharges as $extraCharge) {
                $qtdShpExChrg = $qtdShp->addChild('QtdShpExChrg');
                $qtdShpExChrg->addChild('SpecialServiceType', $extraCharge);
            }
        }
    }

    /**
     * @param array $insurance
     */
    public function setInsurance($insurance)
    {
        $insuredValue = $this->xml->GetQuote->BkgDetails->QtdShp->insertAfter(
            new SimpleXMLExtended('<InsuredValue>'.(float) $insurance['InsuredValue'].'</InsuredValue>')
        );
        $insuredValue->insertAfter(
            new SimpleXMLExtended('<InsuredCurrency>'.$insurance['InsuredCurrency'].'</InsuredCurrency>')
        );
    }

    /**
     * @param array $duty
     */
    public function setDuty($duty)
    {
        $this->xml->GetQuote->BkgDetails->IsDutiable = 'Y';
        $dutiable = $this->xml->GetQuote->addChild('Dutiable');
        $dutiable->addChild('DeclaredCurrency', $duty['DeclaredCurrency']);
        $dutiable->addChild('DeclaredValue', $duty['DeclaredValue']);
    }
}
