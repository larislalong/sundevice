/**
 * 2015-2017 Crystals Services
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
 *  @author    Crystals Services Sarl <contact@crystals-services.com>
 *  @copyright 2015-2017 Crystals Services Sarl
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of Crystals Services Sarl
 */

function AttributeFilterManager() {
	var self = this;
	
	this.parentBlock = $(".blc-filter-block");
	this.onReady = function () {
    	self.handleEvent();
    };
	this.unselectAttributesSort = function () {
		var sortersBlock = self.parentBlock.find('.attributes-groups-block .attributes-group-item');
		sortersBlock.removeClass("active");
		sortersBlock.find(".attributes-sort-trigger").removeClass("active");
    };
	this.handleEvent = function () {
		self.parentBlock.delegate('.price-filter-block .field-attribute-order .order-item', 'click', function (event) {
			event.preventDefault();
			var target = $(this);
			var way = target.attr("data-way");
			self.unselectAttributesSort();
			
			self.parentBlock.find('.price-filter-block .field-attribute-order .order-item').removeClass("active");
			var selectedDiv = self.parentBlock.find('.price-filter-block .field-attribute-order');
			selectedDiv.attr("data-id-selected", way);
			selectedDiv.find(".selected-name").html(target.attr("data-name-order-way"));
			
			target.addClass("active");
			selectedDiv.addClass("active");
            self.changeFilterValues (orderColumnTypeConst.ORDER_COLUMN_TYPE_PRICE, way, 0);
        });
		self.parentBlock.delegate('.attributes-groups-block .attributes-group-item .attributes-sort-trigger', 'click', function (event) {
			var target = $(this);
			var groupItem = target.closest(".attributes-group-item");
			self.unselectAttributesSort();
			self.parentBlock.find('.price-filter-block .field-attribute-order').removeClass("active");
			
			var way = target.attr("data-order-way");
			var column = groupItem.attr("data-id-column");
			
			target.addClass("active");
			groupItem.addClass("active");
            self.changeFilterValues (orderColumnTypeConst.ORDER_COLUMN_TYPE_ATTRIBUTE_GROUP, way, column);
        });
		
		self.parentBlock.delegate('.attributes-filter-content .attributes-load-more .load-more-action', 'click', function (event) {
			event.preventDefault();
			if(hasMoreCombinations){
				self.processLoadMoreAttributes();
			}else{
				self.showHideLoadMore();
			}
        });
		
		self.parentBlock.delegate(".attributes-filter-content .attributes-item .attributes-add-cart .up_button", "click", function() {
			self.onQuantityClick($(this), 1);
		});
		self.parentBlock.delegate(".attributes-filter-content .attributes-item .attributes-add-cart .down_button", "click", function() {
			self.onQuantityClick($(this), -1);
		});
		self.parentBlock.delegate(".attributes-filter-content .attributes-item .attributes-add-cart .add-cart-button", "click", function() {
			self.addToCart($(this));
		});
	};
	this.onQuantityClick = function (target, quantity) {
		var itemParent = target.closest(".add-cart-quantity-updown");
		var inputQuantity = itemParent.find(".add-cart-quantity");
		var currentQuantity = parseInt(inputQuantity.html().trim())+quantity;
		if(currentQuantity>=0){
			inputQuantity.html(currentQuantity)
		}
	};
	this.changeFilterValues = function (type, way, column) {
		self.parentBlock.find(".selected-order-way").val(way);
		self.parentBlock.find(".selected-order-column").val(column);
		self.parentBlock.find(".selected-order-column-type").val(type);
		self.processSortProductAttributes();
	};
	
	this.processSortProductAttributes = function () {
		currentProductAttibutesPage = 1;
		self.showLoader();
		var way = self.parentBlock.find(".selected-order-way").val();
		var column = self.parentBlock.find(".selected-order-column").val();
		var type = self.parentBlock.find(".selected-order-column-type").val();
		var productAttributesListDiv = self.parentBlock.find(".attributes-filter-content .attributes-list");
    	$.ajax({
	        url: blfFrontAjaxUrl,
			dataType: "json",
	        data: {
	            ajax: true,
	            action: "SortProductAttributes",
				selected_filters: blcSelectableManager.filterString,
	            way: way,
	            column: column,
	            type: type
	        },
	        success: function (result) {
				self.hideLoader();
				if(result.hasError){
					displayBlcError(result.errors, productAttributesListDiv);
				}else{
					productAttributesListDiv.html(result.formProductAttributes);
				}
				self.showHideLoadMore();
	        },
	        error: function () {
				self.hideLoader();
	            displayBlcError("An ERROR OCCURED WHILE CONNECTING TO SERVER", productAttributesListDiv);
	        },
	        type: 'post',
	    });
    };
	
	this.processLoadMoreAttributes = function () {
		self.showLoaderLoadMore();
		var way = self.parentBlock.find(".selected-order-way").val();
		var column = self.parentBlock.find(".selected-order-column").val();
		var type = self.parentBlock.find(".selected-order-column-type").val();
		var productAttributesListDiv = self.parentBlock.find(".attributes-filter-content .attributes-list");
    	$.ajax({
	        url: blfFrontAjaxUrl,
			dataType: "json",
	        data: {
	            ajax: true,
	            action: "LoadMoreAttributes",
				selected_filters: blcSelectableManager.filterString,
	            way: way,
	            column: column,
	            type: type,
				page: currentProductAttibutesPage+1
	        },
	        success: function (result) {
				self.hideLoaderLoadMore();
				if(result.hasError){
					displayBlcError(result.errors, productAttributesListDiv);
				}else{
					currentProductAttibutesPage++;
					productAttributesListDiv.append(result.formProductAttributes);
					self.showHideLoadMore();
				}
	        },
	        error: function () {
				self.hideLoaderLoadMore();
	            displayBlcError("An ERROR OCCURED WHILE CONNECTING TO SERVER", productAttributesListDiv);
	        },
	        type: 'post',
	    });
    };
	
	this.addToCart = function(target){
		
		var parentAttributeItem = target.closest(".attributes-item");
		var quantity = parseInt(parentAttributeItem.find(".attributes-add-cart .add-cart-quantity").html().trim());
		if(quantity>0){
			var idProduct = parentAttributeItem.attr("data-id-product");
			var idProductAttribute = parentAttributeItem.attr("data-id-product-attribute");
			ajaxCart.add(idProduct, idProductAttribute, false, target, quantity, false);
		}
	}
	
	this.showLoader = function () {
		self.parentBlock.find(".attributes-filter-content .loading-attributes").show();
    };
	this.hideLoader = function () {
		self.parentBlock.find(".attributes-filter-content .loading-attributes").hide();
    };
	
	this.showLoaderLoadMore = function () {
		self.parentBlock.find(".attributes-filter-content .attributes-load-more .load-more-icon").addClass("icon-spin");
    };
	this.hideLoaderLoadMore = function () {
		self.parentBlock.find(".attributes-filter-content .attributes-load-more .load-more-icon").removeClass("icon-spin");
    };
	
	this.showHideLoadMore = function () {
		if((typeof(hasMoreCombinations)!="undefined")  && hasMoreCombinations){
			self.parentBlock.find(".attributes-filter-content .attributes-load-more").show();
		}else{
			self.parentBlock.find(".attributes-filter-content .attributes-load-more").hide();
		}
    };
}