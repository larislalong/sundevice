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
 * Class AbstractDhlRequest
 */
abstract class AbstractDhlRequest
{
    /** @var SimpleXMLExtended $xml */
    protected $xml;

    /**
     * @return mixed
     */
    abstract public function getMethod();

    /**
     * @return mixed
     */
    abstract public function getXMLTemplateFile();

    /**
     * @return mixed
     */
    abstract public function getXmlName();

    /**
     * @param SimpleXMLExtended $response
     * @return mixed
     */
    abstract public function buildResponse(SimpleXMLExtended $response);

    /**
     * AbstractDhlRequest constructor.
     * @param array $credentials
     */
    public function __construct($credentials)
    {
        $this->xml = new SimpleXMLExtended(dirname(__FILE__).$this->getXMLTemplateFile(), null, true);
        $this->setHeader($credentials);
    }

    /**
     * @return null
     */
    public function getData()
    {
        return null;
    }

    /**
     * @param array $credentials
     */
    public function setHeader($credentials)
    {
        $xmlName = $this->getXmlName();
        if ($xmlName) {
            $this->xml->$xmlName->Request->ServiceHeader->MessageTime = date('c');
            $this->xml->$xmlName->Request->ServiceHeader->MessageReference = $this->generateMessageReference();
            $this->xml->$xmlName->Request->ServiceHeader->SiteID = $credentials['SiteID'];
            $this->xml->$xmlName->Request->ServiceHeader->Password = $credentials['Password'];
        } else {
            $this->xml->Request->ServiceHeader->MessageTime = date('c');
            $this->xml->Request->ServiceHeader->MessageReference = $this->generateMessageReference();
            $this->xml->Request->ServiceHeader->SiteID = $credentials['SiteID'];
            $this->xml->Request->ServiceHeader->Password = $credentials['Password'];
        }
    }

    /**
     * @return string
     */
    public function generateMessageReference()
    {
        return bin2hex(openssl_random_pseudo_bytes(16));
    }
}
