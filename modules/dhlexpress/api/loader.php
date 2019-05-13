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

require_once(dirname(__FILE__).'/SimpleXMLExtended.php');
require_once(dirname(__FILE__).'/DhlException.php');
require_once(dirname(__FILE__).'/DhlClient.php');
require_once(dirname(__FILE__).'/AbstractDhlRequest.php');
require_once(dirname(__FILE__).'/AbstractDhlResponse.php');
require_once(dirname(__FILE__).'/response/DhlReturnedResponseInterface.php');
require_once(dirname(__FILE__).'/request/DhlQuoteRequest.php');
require_once(dirname(__FILE__).'/response/DhlQuoteResponse.php');
require_once(dirname(__FILE__).'/request/DhlShipmentValidationRequest.php');
require_once(dirname(__FILE__).'/response/DhlShipmentValidationResponse.php');
require_once(dirname(__FILE__).'/request/DhlPickupRequest.php');
require_once(dirname(__FILE__).'/response/DhlPickupResponse.php');
require_once(dirname(__FILE__).'/request/DhlTrackingRequest.php');
require_once(dirname(__FILE__).'/response/DhlTrackingResponse.php');
