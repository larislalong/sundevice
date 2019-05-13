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
 * Class DhlLogger
 */
class DhlLogger
{
    const LEVEL_INFO = 'INFO';
    const LEVEL_ERROR = 'ERROR';

    /** @var AbstractDhlHandler $handler */
    protected $handler;

    /** @var string $channel */
    protected $channel;

    /**
     * DhlLogger constructor.
     * @param string             $channel
     * @param AbstractDhlHandler $handler
     */
    public function __construct($channel, $handler)
    {
        $this->channel = $channel;
        $this->handler = $handler;
    }

    /**
     * @param string $message
     * @param array  $details
     */
    public function info($message, $details = array())
    {
        $this->handler->log(self::LEVEL_INFO, $message, $this->channel, $details);
    }

    /**
     * @param string $message
     * @param array  $details
     */
    public function error($message, $details = array())
    {
        $this->handler->log(self::LEVEL_ERROR, $message, $this->channel, $details);
    }

    /**
     * This method is used to convert the XML string to array.
     * We also need to obfuscate the password for security matters.
     * @param AbstractDhlRequest $request
     */
    public function logXmlRequest(AbstractDhlRequest $request)
    {
        $xmlName = $request->getXmlName();
        $requestXml = new SimpleXMLExtended($request->getData());
        if ($xmlName) {
            $requestXml->$xmlName->Request->ServiceHeader->Password = '*******';
        } else {
            $requestXml->Request->ServiceHeader->Password = '*******';
        }
        $requestArray = json_encode($requestXml);
        $this->info('Send Request', array('request' => json_decode($requestArray, true)));
    }
}
