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
    $('.open_incidence').on('click', function() {
        var data = $(this).attr('data');
        var id_seller_incidence = $(this).attr('data');
        
        //for url not friendly
        var concat_vars = '&';
        if (PS_REWRITING_SETTINGS == 1)
            concat_vars = '?';
        else
            sellermessages_controller_url = sellermessages_controller_url.replace(/&amp;/g, '&');
        
        $.ajax({
            type: "POST",
            url: sellermessages_controller_url + concat_vars + 'action=read_message&id_seller_incidence=' + id_seller_incidence + '&rand=' + new Date().getTime(),
            data : {id_seller_incidence : id_seller_incidence, action : "read_message"}, 
            success: function() {
                $('#incidence_'+data).slideToggle("slow");
            }
        });
        return false;
    });
    
    $('#open_form_incidence').on('click', function() {
        $('.form_incidence').slideToggle("slow");
        return false;
    });
});