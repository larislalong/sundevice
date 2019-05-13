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

var storeUsedGroups = {};

/**
 * Add an attribute from a group in the declination multilist
 */
function add_attr()
{
    var attr_group = $('#attribute_group option:selected');
    if (attr_group.val() == 0) {
        alert(errorAttributeGroupEmpty);
        return false;
    }

    var attr_name = $('#attribute option:selected');
    if (attr_name.val() == 0) {
        alert(errorAttributeEmpty);
        return false;
    }

    if (attr_group.val() in storeUsedGroups) {
        alert(attr_group.text()+' '+errorAttributeGroupAlreadySelected);
        return false;
    }

    storeUsedGroups[attr_group.val()] = true;
    $('<option></option>')
            .attr('value', attr_name.val())
            .attr('groupid', attr_group.val())
            .attr('selected', 'selected')
            .text(attr_group.text() + ' : ' + attr_name.text())
            .appendTo('#product_att_list');
}

function add_combination() {
    if ($('#product_att_list option').length > 0) {
        var text = '';
        var id_attributes_group = '';
        var id_attributes = '';
        $('#product_att_list :selected').each(function(){
            if ($(this).text() != 'undefined') {
                text += $(this).text()+', ';
                id_attributes_group += $(this).attr('groupid')+',';
                id_attributes += $(this).val()+',';
            }
        });

        id_attributes_group = id_attributes_group.slice(0,-1);
        id_attributes = id_attributes.slice(0,-1);
        text = text.slice(0,-2);

        var tr = '<tr class="highlighted odd selected-line">';
        var tr = tr + '<td class=" left">'+text+'</td>';
        var tr = tr + '<td class="left"><input type="text" class="form-control col-lg-12" value="" name="combination_reference[]" maxlength="32"></td>';
        var tr = tr + '<td class="left"><input type="text" class="form-control col-lg-12" value="0.00" name="combination_price[]" maxlength="10"></td>';
        var tr = tr + '<td class="left"><input type="text" class="form-control col-lg-12" value="0.00" name="combination_weight[]" maxlength="10"></td>';
        var tr = tr + '<td class="left"><input type="text" class="form-control col-lg-12" value="1" name="combination_qty[]" maxlength="10"></td>';
        var tr = tr + '<td class="text-right">';
        var tr = tr + '<a class="edit btn btn-default " onclick="delete_combination(this)"><i class="icon-minus-sign-alt"></i> '+buttonDelete+'</a>';
        var tr = tr + '<input type="hidden" name="attributes[]" value="'+quoteattr(text)+'" />';
        var tr = tr + '</td>';
        var tr = tr + '</tr>';

        $('#table-combinations-list tbody').append(tr);
        $('#product_att_list option').remove();
        storeUsedGroups = [];
        
        //hidde quantity field
        $('#quantity').parent().hide();
    }
    else {
        alert(errorSaveCombination);
    }
}

function quoteattr(s, preserveCR) {
    preserveCR = preserveCR ? '&#13;' : '\n';
    return ('' + s) /* Forces the conversion to string. */
        .replace(/&/g, '&amp;') /* This MUST be the 1st replacement. */
        .replace(/'/g, '&apos;') /* The 4 other predefined entities, required. */
        .replace(/"/g, '&quot;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        /*
        You may add other replacements here for HTML only 
        (but it's not necessary).
        Or for XML, only if the named entities are defined in its DTD.
        */ 
        .replace(/\r\n/g, preserveCR) /* Must be before the next replacement. */
        .replace(/[\r\n]/g, preserveCR);
        ;
}

function delete_combination(item) {
    var id_product_attribute = $(item).attr('data');
    if (id_product_attribute > 0) {
        var id_product = $('#id_product').val();
        
        //for url not friendly
        var concat_vars = '&';
        if (PS_REWRITING_SETTINGS == 1)
            concat_vars = '?';
        else
            editproduct_controller_url = editproduct_controller_url.replace(/&amp;/g, '&');
        
        $.ajax({
            type: "POST",
            url: editproduct_controller_url + concat_vars + 'action=delete_combination&rand=' + new Date().getTime(),
            data : {id_product : id_product, id_product_attribute : id_product_attribute, action : "delete_combination"}, 
            success: function(data) {
                $(item).parent().parent().remove();
            }
        });
    }
    else {
        $(item).parent().parent().remove();
    }
    
}

$(document).ready(function() {        
    //Attributes
    $('.select_all').on('click', function() {
        var id_attribute_group = $(this).val();
        if ($(this).is(':checked')) {
            $('#table_'+id_attribute_group+' input[type=checkbox]').prop('checked', true);
            $('#table_'+id_attribute_group+' input[type=checkbox]').parent().addClass('checker');
        }
        else {
            $('#table_'+id_attribute_group+' input[type=checkbox]').prop('checked', false);
            $('#table_'+id_attribute_group+' input[type=checkbox]').parent().removeClass('checker');
        }          
    });
    
    $('#attribute_group').on('change',function() {
        var id_attribute_group = $(this).val();
        
        //for url not friendly
        var concat_vars = '&';
        if (PS_REWRITING_SETTINGS == 1)
            concat_vars = '?';
        else
            editproduct_controller_url = editproduct_controller_url.replace(/&amp;/g, '&');
        
        $.ajax({
            type: "POST",
            url: editproduct_controller_url + concat_vars + 'action=select_attribute_group&rand=' + new Date().getTime(),
            data : {id_attribute_group : id_attribute_group, action : "select_attribute_group"}, 
            success: function(data) {
                $('#attribute option').remove();
                $('#attribute').append(data);
            }
        });
    });
});
