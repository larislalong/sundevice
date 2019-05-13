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

function checkAllZones(elt)
{
    if($(elt).is(':checked'))
    {
        $('.input_zone').attr('checked', 'checked');
        $('.fees div.input-group input:text').each(function () {
            index = $(this).closest('td').index();
            //enableGlobalFees(index);
            $('span.fees_all').show();
            $('tr.fees_all td:eq('+index+')').find('div.input-group input').show().removeAttr('disabled');
            $('tr.fees_all td:eq('+index+')').find('div.input-group .currency_sign').show();
            $('.fees div.input-group input:text, .fees_all div.input-group input:text').removeAttr('disabled');
            if ($('tr.fees_all td:eq('+index+')').hasClass('validated'))
            {
                $(this).removeAttr('disabled');
                $('.fees_all td:eq('+index+') div.input-group input:text').removeAttr('disabled');
            }
        });
    }
    else
    {
        $('.input_zone').removeAttr('checked');
        $('.fees div.input-group input:text, .fees_all div.input-group input:text').attr('disabled', 'disabled');
    }
}

$(document).ready(function($) {
    
    $(".delay_lang").change(function () {
        $('.delay').hide();
        $('#delay_'+$(this).val()).show();
    });
    
    $('select#shipping_method').on('change', function() {
        if ($(this).val() == 1)
        {
            $('#zones_table tr td.range_type').text(string_weight);
            $('.weight_unit').show();
            $('.price_unit').hide();
        }
        else
        {
            $('#zones_table tr td.range_type').text(string_price);
            $('.price_unit').show();
            $('.weight_unit').hide();
        }  
    });
    
    $('select#is_free').on('change', function() {
        if ($(this).val() == 1)
        {
            $('tr.range_inf td, tr.range_sup td, tr.fees_all td, tr.fees td').each(function () {
                if ($(this).index() >= 2)
                {
                    $(this).find('input:text, button').val('').attr('disabled', 'disabled').css('background-color', '#999999').css('border-color', '#999999');
                    $(this).css('background-color', '#999999');
                }
            });
            fees_is_hide = true;
        }
        else {
            $('tr.range_inf td, tr.range_sup td, tr.fees_all td, tr.fees td').each(function () {
		if ($(this).index() >= 2)
		{
                    //enable only if zone is active
                    tr = $(this).closest('tr');
                    validate = $('tr.fees_all td:eq('+$(this).index()+')').hasClass('validated');
                    if ($(tr).index() > 2 && $(tr).find('td:eq(1) input').attr('checked') && validate || !$(tr).hasClass('range_sup') || !$(tr).hasClass('range_inf'))
                        $(this).find('div.input-group input:text').removeAttr('disabled');
                    $(this).find('input:text, button').css('background-color', '').css('border-color', '');
                    $(this).find('button').css('background-color', '').css('border-color', '').removeAttr('disabled');
                    $(this).css('background-color', '');
		}
            });
        }
    });
    
    $('.input_zone').on('click', function() {
        var zone = $(this).val();
        if ($(this).is(':checked')) {
            $('input[name="fees['+zone+'][0]"]').removeAttr('disabled');
        }
        else {
            $('input[name="fees['+zone+'][0]"]').attr('disabled', 'disabled');
        }
    });
    
    $('tr.fees_all input').on('click', function() {
        $('tr.fees_all td:last').addClass('validated').removeClass('not_validated');
    });
    
    $('#add_new_range').on('click', function() {
        
        var range_inf = $('.range_inf td:last input[name="range_inf[]"]').val();
        var range_sup = $('.range_sup td:last input[name="range_sup[]"]').val();
               
        if (range_sup == 'undefined' || range_sup == '' || range_inf == 'undefined' || range_inf == '' || Number(range_sup) <= Number(range_inf))
	{
            alert(need_to_validate);
            return false;
	}
        
        last_sup_val = $('tr.range_sup td:last input').val();
        //add new rand sup input
        $('tr.range_sup td:last').after('<td class="range_data"><div class="input-group fixed-width-md"><span class="input-group-addon weight_unit" style="display: none;">'+PS_WEIGHT_UNIT+'</span><span class="input-group-addon price_unit" style="display: none;">'+currency_sign+'</span><input class="form-control" name="range_sup[]" type="text" autocomplete="off" /></div></td>');
        //add new rand inf input
        $('tr.range_inf td:last').after('<td class="border_bottom"><div class="input-group fixed-width-md"><span class="input-group-addon weight_unit" style="display: none;">'+PS_WEIGHT_UNIT+'</span><span class="input-group-addon price_unit" style="display: none;">'+currency_sign+'</span><input class="form-control" name="range_inf[]" type="text" value="'+last_sup_val+'" autocomplete="off" /></div></td>');
        $('tr.fees_all td:last').after('<td class="border_top border_bottom"><div class="input-group fixed-width-md"><span class="input-group-addon currency_sign" style="display:none" >'+currency_sign+'</span><input class="form-control" style="display:none" type="text" /></div></td>');

        $('tr.fees').each(function () {
            $(this).find('td:last').after('<td><div class="input-group fixed-width-md"><span class="input-group-addon currency_sign">'+currency_sign+'</span><input class="form-control" name="fees['+$(this).data('zoneid')+'][]" type="text" value="" /></div></td>');
        });
        return false;
    });
});
