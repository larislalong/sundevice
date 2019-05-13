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
$(document).ready(function () {
    var regenerationManager = new RegenerationManager();
	regenerationManager.onReady();
});
function RegenerationManager() {
	var self = this;
	this.divParent = $("#divRegenerateParent");
	this.btnRegenerateAll = this.divParent.find("#divAllAction .btnStartAll");
	this.btnCancelAll = this.divParent.find("#divAllAction .btnCancelAll");
	this.btnUpdate = this.divParent.find("#divAllAction .btnUpdate");
	this.productManagerList = [];
	this.defaultSelector = ".product-content-list .product-content-item";
    this.onReady = function () {
		self.divParent.find(this.defaultSelector).each(function() {
			var target = $(this);
			var idProduct = target.attr("data-id_product");
			self.productManagerList[idProduct] = new ProductRegeneration(idProduct, self, target);
			self.productManagerList[idProduct].onReady();
		});
		if(autoStartDeprecated){
			self.onUpdate();
		}
    	self.handleEvent();
		self.manageTabs();
    };
	
	this.handleEvent = function () {
    	self.btnRegenerateAll.on("click", self.onRegenerateAll);
		self.btnUpdate.on("click", self.onUpdate);
    };
	
	this.onRegenerateAll = function () {
		var selector = ".product-content-list .product-content-item";
		self.startGeneration(selector);
	};
	this.onUpdate = function () {
		var selector = ".product-content-list .product-content-item[data-is_up_to_date='0']";
		self.startGeneration(selector);
	};
	
	this.startGeneration = function (selector) {
		var prevId = 0;
		var firstId = 0;
    	self.divParent.find(selector).each(function() {
			var target = $(this);
			idProduct = target.attr("data-id_product");
			if(prevId==0){
				firstId = idProduct;
			}else{
				self.productManagerList[prevId].nextId = idProduct;
			}
			prevId = idProduct;
		});
		if(firstId>0){
			self.productManagerList[firstId].start(true);
		}
    };
	
	this.onStartCall = function () {
		self.disableRegenerate();
    };
	
	this.onActionComplete = function () {
		self.enableRegenerate();
    };
	
	this.onActionRequest = function () {
		self.disableCancel();
		self.disableRegenerate();
    };
	
	this.disableRegenerate = function () {
		self.btnRegenerateAll.prop("disabled", true);
		self.btnUpdate.prop("disabled", true);
		
		self.btnRegenerateAll.find("i").removeClass("process-icon-cogs").addClass("process-icon-loading");
		self.btnUpdate.find("i").removeClass("process-icon-refresh").addClass("process-icon-loading");
    };
	this.disableCancel = function () {
		self.btnCancelAll.prop("disabled", true);
		self.btnCancelAll.find("i").removeClass("process-icon-cancel").addClass("process-icon-loading");
    };
	this.enableCancel = function () {
		self.btnCancelAll.prop("disabled", false);
		self.btnCancelAll.find("i").removeClass("process-icon-loading").addClass("process-icon-cancel");
    };
	this.enableRegenerate = function () {
		self.btnRegenerateAll.prop("disabled", false);
		self.btnUpdate.prop("disabled", false);
		
		self.btnRegenerateAll.find("i").removeClass("process-icon-loading").addClass("process-icon-cogs");
		self.btnUpdate.find("i").removeClass("process-icon-loading").addClass("process-icon-refresh");
    };
	this.changeTab =function(nav){
	   var id = nav.attr('id');
		self.divParent.find('.nav-optiongroup').removeClass('selected');
		nav.addClass('selected active');
		nav.siblings().removeClass('active');
		self.divParent.find('.tab-optiongroup').hide();
		self.divParent.find('.'+id).show();
   }
   this.manageTabs =function(){
		self.divParent.find('div.productTabs').find('a.nav-optiongroup').click(function(event) {
			event.preventDefault();
			self.changeTab($(this));
		});
	}
}