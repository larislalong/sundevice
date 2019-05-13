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
 * Class DhlexpressCrontrackingModuleFrontController
 */
class DhlexpressCrontrackingModuleFrontController extends ModuleFrontController
{
    /**
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function postProcess()
    {
        require_once(dirname(__FILE__).'/../../classes/DhlTools.php');
        require_once(dirname(__FILE__).'/../../classes/DhlLabel.php');
        require_once(dirname(__FILE__).'/../../classes/DhlOrder.php');
        require_once(dirname(__FILE__).'/../../classes/DhlOrderCarrier.php');
        require_once(dirname(__FILE__).'/../../api/loader.php');

        $secureKey = md5(_COOKIE_KEY_.Configuration::get('PS_SHOP_NAME'));
        if (!empty($secureKey) && Tools::getValue('secure_key') === $secureKey) {
            $labelPacks = DhlTools::getOrdersToTrack();
            /** @var Dhlexpress $module */
            $module = $this->module;
            // Request limits to 10 the number of AWB Number we can track in one request.
            // So we call track request n times, in groups of 9 AWB Number.
            foreach ($labelPacks as $labelsToTrack) {
                $module->updateShipmentTracking($labelsToTrack);
            }
            die();
        } else {
            die();
        }
    }
}
