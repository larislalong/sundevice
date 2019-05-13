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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

$(document).ready(function() {
    $("#active_paypal").on('click', function() {
        $("#content_bankwire").addClass('hidden');
        $("#content_paypal").removeClass('hidden');
        $("#active_bankwire").removeAttr('checked');
        $("#active_bankwire").parent().removeClass('checked');
        $("#active_paypal").addAttr('checked');
        alert('entra');
        return false;
    });
    
    $("#active_bankwire").on('click', function() {
        $("#content_paypal").addClass('hidden');
        $("#content_bankwire").removeClass('hidden');
        $("#active_paypal").removeAttr('checked');
        $("#active_paypal").parent().removeClass('checked');
        $("#active_bankwire").addAttr('checked');
        return false;
    });
});
