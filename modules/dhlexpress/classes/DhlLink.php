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
 * Class DhlLink
 */
class DhlLink extends Link
{
    /**
     * getBaseLink() is public only since 1.6.1.15 (protected before)
     * This method allows us to call it from DhlAdminOrders
     *
     * @param null $id_shop
     * @param null $ssl
     * @param bool $relative_protocol
     * @return string
     */
    public function getBaseLink($id_shop = null, $ssl = null, $relative_protocol = false)
    {
        return parent::getBaseLink($id_shop, $ssl, $relative_protocol);
    }
}
