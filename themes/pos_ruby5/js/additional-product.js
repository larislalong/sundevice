/*
* 2007-2016 PrestaShop
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
$(document).ready(function(){
	/*$(".link_infobule_stock").fancybox({
        type : 'iframe',
        wrapCSS: 'supported_network_fancybox_wrapper',
		autoSize: false,
        width: 400,
        height: 400
    });*/
	$("#attributes .attribute-help-link, .packaging_left .packaging-help-link").fancybox({
        type : 'inline',
        wrapCSS: 'grade_model_fancybox_wrapper',
        padding : [65, 0, 20, 0],
        autoSize: false,
        width: 870,
    });
	
	$("#attributes .supportedLteNetwork-link, .display_combinations .modelLteNetwork-link").fancybox({
        type : 'inline',
        wrapCSS: 'supported_network_fancybox_wrapper',
        padding : [65, 0, 20, 0],
        autoSize: false,
        width: 870,
    });
	$(document).on('change', '.attribute_select', function(e){
		var target = $(this);
		changeSupportedLte(target, target.val());
	});

	// $(document).on('click', '.attribute_radio', function(e){
		// var target = $(this);
		// var parentGroup = target.closest(".attribute_fieldset");
		// if(parentGroup.hasClass("model-group")){
			// var radio_inputs = parseInt(parentGroup.find('.checked > input[type=radio]').length);
			// if (radio_inputs){
				// target = parentGroup.find('.checked > input[type=radio]');
			// }else{
				// target = parentGroup.find('input[type=radio]:checked');
			// }
			// changeSupportedLte(target, target.val());
		// }
	// });
	// $(".attribute_radio:checked").each(function(){
		// var target = $(this);
		// var parentGroup = target.closest(".attribute_fieldset");
		// if(parentGroup.hasClass("model-group")){
			// changeSupportedLte(target, target.val());
		// }
	// });
	
	$(document).on('click', '.link_infobule_stock', function(e){
		e.preventDefault();
		$.fancybox.open({
			href: $(this).attr("href"),
			type: "ajax",
			wrapCSS: 'supported_network_fancybox_wrapper',
			padding : [50, 30, 50, 30],
			autoSize: false,
			width: 400,
			height: 200,
			ajax: {
				type: "POST",
			}
		});
	});
});

function changeSupportedLte(target, idAttribute){
	var parentGroup = target.closest(".attribute_fieldset");
	parentGroup.find(".block_link_supported").hide();
	target.closest("li").find(".block_link_supported").show();
}

function handleRichTextCollapse(){
	$(".block_packaging_wrapper .panel-group, .block_graging_system_wrapper .panel-group").each(function(){
		var target = $(this);
		var collapseLink = target.find(".panel-heading a");
		collapseLink.attr("data-toggle", "collapse");
		collapseLink.attr("data-parent", "#"+target.attr("id"));
	});
}


function showError(error){
	if (!!$.prototype.fancybox)
		$.fancybox.open([
		{
			type: 'inline',
			autoScale: true,
			minHeight: 30,
			content: '<p class="fancybox-error">' + error + '</p>'
		}],
		{
			padding: 0
		});
	else
		alert(error);
}