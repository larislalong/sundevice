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
*
* Don't forget to prefix your containers with your own identifier
* to avoid any conflicts with others containers.
*/
$(document).ready(function() {
    $('#sellerfunds-list input[type=checkbox]').on('click', function() {
        var commision = parseFloat($(this).attr('data'));
        var total_invoice = parseFloat($('#total_invoice').attr('data'));
        if($(this).is(':checked')) {  
            var new_total_invoice = total_invoice + commision;
            $('#total_invoice').attr('data', new_total_invoice.toFixed(2));
            $('#total_invoice').text(new_total_invoice.toFixed(2) + ' ' + sign);
        }
        else {
            var new_total_invoice = total_invoice - commision;
            if (new_total_invoice > 0) {
                $('#total_invoice').attr('data', new_total_invoice.toFixed(2));
                $('#total_invoice').text(new_total_invoice.toFixed(2) + ' ' + sign);
            }
            else {
                var new_total_invoice = 0;
                $('#total_invoice').attr('data', new_total_invoice);
                $('#total_invoice').text(new_total_invoice.toFixed(2) + ' ' + sign);
            }
        }
        
        $('input[name=total_invoice]').val(new_total_invoice.toFixed(2));        
    });
});
