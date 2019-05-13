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
    
    $(".open_search_box").on('click', function(){
        $('.search_box').slideToggle();
        return false;
    });
    
    $("#check_all").on('click', function(){
        $(".ck:checkbox:not(:checked)").attr("checked", "checked");
        $(".ck").parent().addClass("checked");
        return false;
    });
    
    $("#uncheck_all").on('click', function(){
        $(".ck:checkbox:checked").removeAttr("checked");
        $(".ck").parent().removeClass("checked");
        return false;
    });
    
    $(".bulk_all").on('click', function(){
        var form_action = $('#form-products').attr('action');
        var action = $(this).attr('id');
        
        //for url not friendly
        var concat_vars = '&';
        if (PS_REWRITING_SETTINGS == 1)
            concat_vars = '?';
        else
            form_action = form_action.replace(/&amp;/g, '&');

	if (form_action.indexOf('#') == -1)
		$('#form-products').attr('action', form_action + concat_vars + action);
	else
		$('#form-products').attr('action', form_action.splice(form_action.lastIndexOf('&'), 0, concat_vars + action));

	$('#form-products').submit();
    });
});
