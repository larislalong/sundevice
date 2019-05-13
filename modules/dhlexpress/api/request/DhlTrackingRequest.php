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
 * Class DhlTrackingRequest
 */
class DhlTrackingRequest extends AbstractDhlRequest
{
    /**
     *
     */
    const METHOD = 'POST';
    /**
     *
     */
    const XML_TEMPLATE = '/xml/Tracking.xml';

    /**
     * @return bool
     */
    public function getXmlName()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getXMLTemplateFile()
    {
        return self::XML_TEMPLATE;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return self::METHOD;
    }

    /**
     * @param SimpleXMLExtended $response
     * @return DhlTrackingResponse
     */
    public function buildResponse(SimpleXMLExtended $response)
    {
        if (!$response) {
            die();
        }
        $trackingResponse = DhlTrackingResponse::buildFromResponse($response);

        return $trackingResponse;
    }

    /**
     * @param string $language
     */
    public function setLanguageCode($language)
    {
        $this->xml->LanguageCode = $language;
    }

    /**
     * @param array $awbNumbers
     */
    public function setAwbNumber($awbNumbers)
    {
        foreach ($awbNumbers as $awbNumber) {
            $this->xml->LanguageCode->insertAfter(
                new SimpleXMLExtended('<AWBNumber>'.$awbNumber.'</AWBNumber>')
            );
        }
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->xml->asXML();
    }
}
