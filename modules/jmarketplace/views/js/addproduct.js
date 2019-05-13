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
    
    $('.list-group-item').on('click', function() {
        $('.list-group-item').removeClass('active');
        $(this).addClass("active");
    });
    
    //tabs default
    $('.tabs-default .list-group-item').on('click', function() {
        $('.list-group-item').removeClass('active');
        $(this).addClass('active');
        $('.tab-pane').hide();
        $($(this).attr('href')).show();
        return false;
    });
    
    $('#available_date').datepicker({
        dateFormat: 'yy-mm-dd',
    });
    
    $('#virtual_product_expiration_date').datepicker({
        dateFormat: 'yy-mm-dd',
    });
    
    //$('#tree1').tree();
    //$('#tree1').checkboxTree();
    
    $("#tree1 label").on('click', function() { 
        var li = $(this).parent().attr('id');
        var level = parseInt($(this).parent().attr('data'));
        var next_level = level + 1;
        
        if($(this).find('i').hasClass('icon-folder-close')) {
            $(this).find('i').removeClass('icon-folder-close').addClass('icon-folder-open');
            $(this).find('i').removeClass('fa-folder').addClass('fa-folder-open');
            $('#' + li + ' ul li.level_'+next_level).removeClass('hidden').addClass('displayed'); 
            $('#' + li + ' ul li.level_'+next_level + ' .category').removeClass('hidden'); 
        }
        else {
            $(this).find('i').removeClass('icon-folder-open').addClass('icon-folder-close');
            $(this).find('i').removeClass('fa-folder-open').addClass('fa-folder');
            $('#' + li + ' ul li.level_'+next_level + ' .category').addClass('hidden'); 
            $('#' + li + ' ul li.level_'+next_level).removeClass('displayed').addClass('hidden'); 
        }
    });
    
    $("#tree1 .category").on('click', function() {  
        if($(this).is(':checked')) {
            var label = $(this).parent().parent().next().text();
            if (label == '')
                var label = $(this).parent().find('label:first').text();
             
            $('#id_category_default').append('<option id="opt_'+$(this).val()+'" value="'+$(this).val()+'">'+label+'</option>');
            
        }
        else  
            $('#id_category_default').find('[value="'+$(this).val()+'"]').remove();
    });
    
    $('#open_new_supplier').click(function(){
        $('#content_new_supplier').slideToggle('slow');
        return false;
    });
    
    $('#open_new_manufacturer').click(function(){
        $('#content_new_manufacturer').slideToggle('slow');
        return false;
    });
    
    $('.flag').on('click', function () {
        $('.input_with_language').hide();
        $('.lang_'+$(this).attr('data')).show();
        $('.lang_selector img').removeClass('selected');
        $(this).addClass('selected');
    });
    
    $('a.fancybox').fancybox();
    
    //prices
    $('#price').on('keyup', function(e) { 
        if ($('#specific_price').val() == 0 || $('#specific_price').val() === undefined) {
            var tax = 0;
            var price = parseFloat($('#price').val());
            
            if ($('#id_tax option:selected').attr('data') > 0)
                var tax = parseFloat(($('#price').val() * $('#id_tax option:selected').attr('data')) / 100);
            
            var price_tax_incl = price + tax;
            $('#price_tax_incl').val(price_tax_incl.toFixed(2));
            
            if (tax_commission == 1) 
                var commission = (((price + tax) * $('#seller_commission').val()) / 100) - $('#fixed_commission').val();
            else 
                var commission = (($(this).val() * $('#seller_commission').val()) / 100) - $('#fixed_commission').val();
            
            $('#commission').val(commission.toFixed(2));
        }
    });
    
    $('#specific_price').on('keyup', function(e) { 
        var tax = 0;
        var price = parseFloat($(this).val());
        
        if ($(this).val() == 0 || $(this).val() === undefined) 
            price = parseFloat($('#price').val());
        
        if ($('#id_tax option:selected').attr('data') > 0)
            var tax = parseFloat((price* $('#id_tax option:selected').attr('data')) / 100);
        
        var price_tax_incl = price + tax;
        $('#price_tax_incl').val(price_tax_incl.toFixed(2));
        
        if (tax_commission == 1) 
            var commission = (((price + tax) * $('#seller_commission').val()) / 100) - $('#fixed_commission').val();
        else 
            var commission = ((price * $('#seller_commission').val()) / 100) - $('#fixed_commission').val();

        $('#commission').val(commission.toFixed(2));
    });
    
    //id_tax on change
    $('#id_tax').on('change', function() {
        var tax = 0;
        
        if ($('#specific_price').val() > 0)
            var price = parseFloat($('#specific_price').val());
        else
            var price = parseFloat($('#price').val());
        
        if ($('#id_tax option:selected').attr('data') > 0)
            var tax = parseFloat((price * $('#id_tax option:selected').attr('data')) / 100);
        
        var price_tax_incl = price + tax;
        $('#price_tax_incl').val(price_tax_incl.toFixed(2));
        
        if (tax_commission == 1) 
            var commission = (((price + tax) * $('#seller_commission').val()) / 100) - $('#fixed_commission').val();
        else 
            var commission = ((price * $('#seller_commission').val()) / 100) - $('#fixed_commission').val();
        
        $('#commission').val(commission.toFixed(2));
    });
    
    $('.delete_product_image').on('click', function() {
        var id_upload_preview = $(this).parent().attr('id');
        var position = $(this).parent().attr('data');
        
        //for url not friendly
        var concat_vars = '&';
        if (PS_REWRITING_SETTINGS == 1)
            concat_vars = '?';
        else
            editproduct_controller_url = editproduct_controller_url.replace(/&amp;/g, '&');
        
        $.ajax({
            url: editproduct_controller_url + concat_vars + 'action=delete_image&rand=' + new Date().getTime(),
            data : {id_image : $(this).attr('data'), action : "delete_image"}, 
            type: 'POST',
            //headers: { "cache-control": "no-cache" },
            //dataType: "json",
            success: function(response) {
                $('#'+id_upload_preview + ' a:first-child').attr('href', '#');
                $('#'+id_upload_preview + ' a:first-child').removeClass('fancybox');
                $('img#uploadPreview'+ position).attr('src', image_not_available);
                $('input[name="legends['+position+']"]').val('');
                $('input'+ + ' a:first-child').attr('href', '#');
            }
        });
        return false;
    });
    
    $('input[name="type_product"]').on('click', function() {
        if ($(this).val() == 2 && has_attributes == 0) {
            $('#table-combinations-list tbody').empty();
            $('#combinations_tab').css('display', 'none');
            $('#shipping input[type=checkbox]').removeAttr('checked');
            $('#shipping_tab').css('display', 'none');
            $('#virtual_product_tab').css('display', 'block');
        }
        else if ($(this).val() == 2 && has_attributes == 1) {
            alert(errorHasAttributes);
            $('#simple_product').click();
        }
        else {
            $('#virtual_product_tab').css('display', 'none');
            $('#combinations_tab').css('display', 'block');
            $('#shipping_tab').css('display', 'block');
            $('#shipping input[type=checkbox]').attr('checked', 'checked');
        }
    });
    
    $('.delete_product').on('click', function() {
        if(confirm(confirmProductDelete))
            return true;
        else
            return false;
    });
    
    var retraso;
    
    $('#search_tree_category').on('keydown', function(){
        clearTimeout(retraso);  
    });
    
    $('#search_tree_category').on('keyup', function() {
        retraso = setTimeout(function(){
            //for url not friendly
            var concat_vars = '&';
            if (PS_REWRITING_SETTINGS == 1)
                concat_vars = '?';
            else
                addproduct_controller_url = addproduct_controller_url.replace(/&amp;/g, '&');
            
            $.ajax({
                url: addproduct_controller_url + concat_vars + 'action=search_category&rand=' + new Date().getTime(),
                data : {key : $('#search_tree_category').val(), action : "search_category"}, 
                type: 'POST',
                //headers: { "cache-control": "no-cache" },
                //dataType: "json",
                success: function(response) {
                    $('#category_suggestions').slideDown('slow').html(response);
                    $('.suggest-element').on('click', function() {
                        var id_category = $(this).attr('data');
                        $('#category_suggestions').slideUp('slow');
                        $('#search_tree_category').val('');
                        $('#tree1 input[type=checkbox]').each(function(){
                            if (this.checked && id_category == $(this).val()) 
                                $(this).attr('checked', false);
                            else if (!this.checked && id_category == $(this).val()) {
                                $(this).click();
                                $(this).attr('checked', true);
                                $('.checkok').html('<strong>' + $('#category_'+id_category).text() + '</strong> ' + confirmSelectedCategoryTree).delay(3000).slideUp('slow');
                            }
                        });
                    }); 
                    //return false;
                }
            });
        }, 300); 
    });
    
    $(".list-group-item").on("click", function() {
        $(".list-group-item").removeClass("active");
        $(this).addClass("active");
        $(".tab-content div").removeClass("active");
        $($(this).attr('href')).addClass("active");
        $($(this).attr('href')).show('slow');
        return false;
    });
    
    if ($(window).width() < 768) {
        $('.upload_image div.preview').hide();
    }
});

function previewImage(nb) {        
    var reader = new FileReader();         
    reader.readAsDataURL(document.getElementById('uploadImage'+nb).files[0]);         
    reader.onload = function (e) {             
        document.getElementById('uploadPreview'+nb).src = e.target.result;         
    };     
}  