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
 * Class DhlClient
 */
class DhlClient
{
    /**
     *
     */
    const BASE_URL_PRODUCTION = 'http://xmlpi-ea.dhl.com/XMLShippingServlet?isUTF8Support=true';
    /**
     *
     */
    const BASE_URL_TEST = 'http://xmlpitest-ea.dhl.com/XMLShippingServlet?isUTF8Support=true';

    /** @var string $baseUrl */
    protected $baseUrl;

    /** @var AbstractDhlRequest $request */
    private $request;

    /**
     * DhlClient constructor.
     * @param int $mode
     */
    public function __construct($mode = 0)
    {
        if (1 === $mode) {
            $this->baseUrl = self::BASE_URL_PRODUCTION;
        } else {
            $this->baseUrl = self::BASE_URL_TEST;
        }
    }

    /**
     * @return bool|mixed
     */
    public function request()
    {
        $body = null;
        $requestMethod = $this->request->getMethod();
        $url = $this->baseUrl;
        $curl = curl_init();
        switch ($requestMethod) {
            case 'POST':
            default:
                $body = $this->request->getData();
                curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
                break;
        }
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL            => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => "",
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_HEADER         => false,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => $requestMethod,
            )
        );
        $response = utf8_encode(curl_exec($curl));
        if ($response === false) {
            return false;
        }
        $curlInfo = curl_getinfo($curl);
        $curlError = curl_errno($curl);
        if (!in_array($curlInfo['http_code'], array(200, 201))) {
            return false;
        }
        if ($curlError) {
            return false;
        }
        $responseXml = new SimpleXMLExtended($response);
        $dom = dom_import_simplexml($responseXml)->ownerDocument;
        $dom->formatOutput = true;
        if ($responseXml instanceof SimpleXMLExtended) {
            return $this->request->buildResponse($responseXml);
        } else {
            return false;
        }
    }

    /**
     * @param AbstractDhlRequest $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }
}
