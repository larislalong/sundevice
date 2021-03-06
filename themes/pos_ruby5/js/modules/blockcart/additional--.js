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
var cartTimer;
var carIsClearing = false;
$(document).ready(function(){
	/*$("body").on('DOMSubtreeModified', ".ajax_cart_quantity:first", function() {
		onCartChange();
	});*/
	$(".cart_block_list").delegate(".bloc_quantity_price .quantity_dev .minus", "click", function() {
		onQuantityClick($(this), false)
	});
	$(".cart_block_list").delegate(".bloc_quantity_price .quantity_dev .plus", "click", function() {
		onQuantityClick($(this), true)
	});
	if($(".shopping_cart").length>0){
		startCartTimer();
	}
});
var oldUpdateCart = ajaxCart.updateCart;
ajaxCart.updateCart = function(jsonData){
	oldUpdateCart(jsonData);
	if (!jsonData.hasError)
	{
		cartLastUpdate = jsonData.lastUpdateDate;
		currentDate = jsonData.currentDate;
		onCartChange(jsonData);
	}
}

function onQuantityClick(target, isAddAction){
	var quantityInCart = parseInt(target.parent().find(".qte_number:first").html());
	quantityToAdd = 1;
	//quantity+=quantityToAdd;
	var customizationId = 0;
	var productId = 0;
	var productAttributeId = 0;
	var parentDiv = target.parent().parent().parent().parent();
	var customizableProductDiv = $(parentDiv).find("div[data-id^=deleteCustomizableProduct_]");
	var idAddressDelivery = false;
	if (customizableProductDiv && $(customizableProductDiv).length)
	{
		var ids = customizableProductDiv.data('id').split('_');
		if (typeof(ids[1]) != 'undefined')
		{
			customizationId = parseInt(ids[1]);
			productId = parseInt(ids[2]);
			if (typeof(ids[3]) != 'undefined')
				productAttributeId = parseInt(ids[3]);
			if (typeof(ids[4]) != 'undefined')
				idAddressDelivery = parseInt(ids[4]);
		}
	}

	// Common product management
	if (!customizationId)
	{
		//retrieve idProduct and idCombination from the displayed product in the block cart
		var firstCut = parentDiv.data('id').replace('cart_block_product_', '');
		firstCut = firstCut.replace('deleteCustomizableProduct_', '');
		ids = firstCut.split('_');
		productId = parseInt(ids[0]);

		if (typeof(ids[1]) != 'undefined')
			productAttributeId = parseInt(ids[1]);
		if (typeof(ids[2]) != 'undefined')
			idAddressDelivery = parseInt(ids[2]);
	}
	if((quantityInCart>1) || isAddAction){
		changeProductQuantity(isAddAction, quantityToAdd, productId, productAttributeId, customizationId, idAddressDelivery);
	}else{
		ajaxCart.remove(productId, productAttributeId, customizationId, idAddressDelivery);
	}
	
}

function changeProductQuantity(isAddAction, quantity, productId, productAttributeId, customizationId, id_address_delivery){
	var summaryPage = isSummaryPage();
	$.ajax({
		type: 'POST',
		headers: { "cache-control": "no-cache" },
		url: baseUri + '?rand=' + new Date().getTime(),
		async: true,
		cache: false,
		dataType: 'json',
		data: 'controller=cart'
			+ '&ajax=true'
			+ '&add=true'
			+(summaryPage?('&getproductprice=true'+ '&summary=true'):'')
			+(isAddAction?'':'&op=down')
			+ '&id_product=' + productId
			+ '&ipa=' + productAttributeId
			+ '&id_address_delivery=' + id_address_delivery
			+ ((customizationId !== 0) ? '&id_customization=' + customizationId : '')
			+ '&qty=' + quantity
			+ '&token=' + static_token,
		success: function(jsonData)
		{
			ajaxCart.updateCart(jsonData);
			if (!jsonData.hasError)
			{
				if (summaryPage){
					updateCartSummary(jsonData.summary);
					updateHookShoppingCart(jsonData.HOOK_SHOPPING_CART);
					updateHookShoppingCartExtra(jsonData.HOOK_SHOPPING_CART_EXTRA);
					if (typeof(getCarrierListAndUpdate) !== 'undefined')
						getCarrierListAndUpdate();
					if (typeof(updatePaymentMethodsDisplay) !== 'undefined')
						updatePaymentMethodsDisplay();
				}
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			if (textStatus !== 'abort')
			{
				error = "TECHNICAL ERROR: unable to save update quantity \n\nDetails:\nError thrown: " + XMLHttpRequest + "\n" + 'Text status: ' + textStatus;
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
		}
	});
}

function isSummaryPage(){
	return ($('body').attr('id') == 'order') || ($('body').attr('id') == 'order-opc');
}

function onCartChange(jsonData){
	carIsClearing = false;
	hideClearingMessage()
	startCartTimer(jsonData);
	showHidePrice(jsonData);
	showHideTime(jsonData);
	showHideWrapping(jsonData);
}
function showHideWrapping(jsonData){
	if(jsonData.show_wrapping){
		$(".wrapping-prices-line").removeClass("unvisible");
	}
}

function showHidePrice(jsonData){
	if(isCartEmpty(jsonData)){
		$(".ajax_cart_quantity").closest(".counter-wrapper").addClass("unvisible");
		$(".price_top_cart.ajax_block_cart_total").addClass("unvisible");
	}else{
		$(".ajax_cart_quantity").closest(".counter-wrapper").removeClass("unvisible");
		$(".price_top_cart.ajax_block_cart_total").removeClass("unvisible");
	}
}

function showHideTime(jsonData){
	if(isCartEmpty(jsonData)){
		$(".bloc_time_cart").addClass("unvisible");
	}else{
		$(".bloc_time_cart").removeClass("unvisible");
	}
}

function startCartTimer(jsonData){
	var RECALL_TIME = 1000;
	if(cartTimer!==undefined){
		try{
			clearInterval(cartTimer);
		}catch(e){}
	}
	if(!carIsClearing && !isCartEmpty(jsonData)){
		carIsClearing
		var totalTime = (PS_CART_LIFE_TIME*60*1000) + new Date(cartLastUpdate).getTime();
		var usedTime = 0;
		cartTimer = setInterval(function(){
			usedTime+=RECALL_TIME;
			var currentTime = new Date(currentDate).getTime() + usedTime;
			var remainingTime = totalTime - currentTime;
			if(remainingTime<=0){
				showClearingMessage();
				clearInterval(cartTimer);
				carIsClearing = true;
				clearCart();
			}else{
			    var days = Math.floor(remainingTime / (1000 * 60 * 60 * 24));
				var hours = Math.floor((remainingTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
				var minutes = Math.floor((remainingTime % (1000 * 60 * 60)) / (1000 * 60));
				var seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);
				var strHours = (hours>9)?hours:"0"+hours;
				var strMinutes = (minutes>9)?minutes:"0"+minutes;
				var strSeconds = (seconds>9)?seconds:"0"+seconds;
				var contentTimer = ((days>0)?days+DAY_TIMER+" ":"")+((hours>0)?strHours+HOURS_TIMER+" ":"")+
				strMinutes+MINUTE_TIMER+" "+strSeconds+SECOND_TIMER;
				$(".cart-remaining-time").html(contentTimer);
			}
		}, RECALL_TIME);
	}
}
function isCartEmpty(jsonData){
	var quantity = (jsonData !=undefined)?jsonData.nbTotalProducts:cartTotalProduct;
	return parseInt(quantity)==0;
}

function showClearingMessage(){
	$(".cart-safe-time").addClass("unvisible");
	$(".cart-clearing").removeClass("unvisible");
}
function hideClearingMessage(){
	$(".cart-safe-time").removeClass("unvisible");
	$(".cart-clearing").addClass("unvisible");
}
function clearCart(){
	$.ajax({
		type: 'POST',
		headers: { "cache-control": "no-cache" },
		url: baseUri + '?rand=' + new Date().getTime(),
		async: true,
		cache: false,
		dataType : "json",
		data: 'controller=cart&getProductsInCart=1&token=' + static_token + '&ajax=true',
		success: function(jsonData)	{
			if(!jsonData.hasError){
				for (i in jsonData.products)
				{
					ajaxCart.remove(jsonData.products[i].id_product, jsonData.products[i].id_product_attribute,
							jsonData.products[i].id_customization, jsonData.products[i].id_address_delivery);
				}
			}
		},
		error: function()
		{
			
			var error = 'ERROR: unable to clear the cart';
			if (!!$.prototype.fancybox)
			{
				$.fancybox.open([
					{
						type: 'inline',
						autoScale: true,
						minHeight: 30,
						content: error
					}
				], {
					padding: 0
				});
			}
			else
				alert(error);
		}
	});
}