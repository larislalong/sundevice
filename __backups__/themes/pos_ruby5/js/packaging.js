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
	
$(document).ready(function () {
	var overrideAjaxCartAdd = function(idProduct, idCombination, addedFromProductPage, callerElement, quantity, whishlist){
		alert('dev test');
		if (addedFromProductPage && !checkCustomizations())
		{
			if (contentOnly)
			{
				var productUrl = window.document.location.href + '';
				var data = productUrl.replace('content_only=1', '');
				window.parent.document.location.href = data;
				return;
			}
			if (!!$.prototype.fancybox)
				$.fancybox.open([
					{
						type: 'inline',
						autoScale: true,
						minHeight: 30,
						content: '<p class="fancybox-error">' + fieldRequired + '</p>'
					}
				], {
					padding: 0
				});
			else
				alert(fieldRequired);
			return;
		}

		//disabled the button when adding to not double add if user double click
		if (addedFromProductPage)
		{
			$('#add_to_cart button').prop('disabled', 'disabled').addClass('disabled');
			$('.filled').removeClass('filled');
		}
		else
			$(callerElement).prop('disabled', 'disabled');

		if ($('.cart_block_list').hasClass('collapsed'))
			this.expand();
		//send the ajax request to the server 
		// var addPack = $(".input_select_packaging:checked").val();
		var addPack = $("#input_add_packaging").is(':checked') ? 1 : 0;
		// addPack = (!addPack)?0:parseInt(addPack);
		var defaultData = 'controller=cart&add=1&ajax=true&qty=' + ((quantity && quantity != null) ? quantity : '1') + '&id_product=' + idProduct + '&token=' + static_token + ( (parseInt(idCombination) && idCombination != null) ? '&ipa=' + parseInt(idCombination): '' + '&id_customization=' + ((typeof customizationId !== 'undefined') ? customizationId : 0));
		if(addPack){
			defaultData += '&product_add_pack='+addPack;
		}
		$.ajax({
			type: 'POST',
			headers: { "cache-control": "no-cache" },
			url: baseUri + '?rand=' + new Date().getTime(),
			async: true,
			cache: false,
			dataType : "json",
			data: defaultData, //+'&product_add_pack='+addPack,
			success: function(jsonData,textStatus,jqXHR)
			{
				// add appliance to whishlist module
				if (whishlist && !jsonData.errors)
					WishlistAddProductCart(whishlist[0], idProduct, idCombination, whishlist[1]);

				if (!jsonData.hasError)
				{
					if (contentOnly)
						window.parent.ajaxCart.updateCartInformation(jsonData, addedFromProductPage);
					else
						ajaxCart.updateCartInformation(jsonData, addedFromProductPage);

					if (jsonData.crossSelling)
						$('.crossseling').html(jsonData.crossSelling);

					if (idCombination)
						$(jsonData.products).each(function(){
							if (this.id != undefined && this.id == parseInt(idProduct) && this.idCombination == parseInt(idCombination))
								if (contentOnly)
									window.parent.ajaxCart.updateLayer(this);
								else
									ajaxCart.updateLayer(this);
						});
					else
						$(jsonData.products).each(function(){
							if (this.id != undefined && this.id == parseInt(idProduct))
								if (contentOnly)
									window.parent.ajaxCart.updateLayer(this);
								else
									ajaxCart.updateLayer(this);
						});
					if (contentOnly)
						parent.$.fancybox.close();
				}
				else
				{
					if (contentOnly)
						window.parent.ajaxCart.updateCart(jsonData);
					else
						ajaxCart.updateCart(jsonData);
					if (addedFromProductPage)
						$('#add_to_cart button').removeProp('disabled').removeClass('disabled');
					else
						$(callerElement).removeProp('disabled');
				}

				emptyCustomizations();

			},
			error: function(XMLHttpRequest, textStatus, errorThrown)
			{
				var error = "Impossible to add the product to the cart.<br/>textStatus: '" + textStatus + "'<br/>errorThrown: '" + errorThrown + "'<br/>responseText:<br/>" + XMLHttpRequest.responseText;
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
				//reactive the button when adding has finished
				if (addedFromProductPage)
					$('#add_to_cart button').removeProp('disabled').removeClass('disabled');
				else
					$(callerElement).removeProp('disabled');
			}
		});
	}
	
	var overrideCartFunction = function(){
		ajaxCart.add = function(idProduct, idCombination, addedFromProductPage, callerElement, quantity, whishlist){
			overrideAjaxCartAdd(idProduct, idCombination, addedFromProductPage, callerElement, quantity, whishlist);
		};
	}
	
	overrideCartFunction();
});