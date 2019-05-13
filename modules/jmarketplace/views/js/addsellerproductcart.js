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
    
    //pagina de categoria o listado de productos
    $('.ajax_add_to_cart_button').on('click', function() {
        var href = $(this).attr('href');
        var id_product = $(this).attr('data-id-product');
        
        //for url not friendly
        var concat_vars = '&';
        if (PS_REWRITING_SETTINGS == 1)
            concat_vars = '?';
        else
            confirm_controller_url = confirm_controller_url.replace(/&amp;/g, '&');
        
        $.ajax({
            url: confirm_controller_url + concat_vars + 'action=review&rand=' + new Date().getTime(),
            data : {id_product : id_product, action : "review"}, 
            type: 'POST',
            //headers: { "cache-control": "no-cache" },
            //dataType: "json",
            success: function(response) {
                if (response == '1') {
                    if(confirm(confirmDeleteProductsOtherSeller)) {
                        $(location).attr('href', href);
                    }
                }
                else {
                    $(location).attr('href', href);
                }
            }
        });
        return false;
    });
    
    //ps 1.6 product page
    $('#add_to_cart .exclusive').on('click', function(e) {
        e.preventDefault();
        var id_product = $('#product_page_product_id').val();
        
        //for url not friendly
        var concat_vars = '&';
        if (PS_REWRITING_SETTINGS == 1)
            concat_vars = '?';
        else
            confirm_controller_url = confirm_controller_url.replace(/&amp;/g, '&');
        
        $.ajax({
            url: confirm_controller_url + concat_vars + 'action=review&id_product=' + id_product + '&rand=' + new Date().getTime(),
            data : {id_product : id_product, action : "review"}, 
            type: 'POST',
            //headers: { "cache-control": "no-cache" },
            //dataType: "json",
            success: function(response) {
                if (response == '1') {
                    if(confirm(confirmDeleteProductsOtherSeller)) {
                        $('form#buy_block').submit();
                    }
                }
                else {
                    $('form#buy_block').submit();
                }
            }
        });
        //return false;
    });
});