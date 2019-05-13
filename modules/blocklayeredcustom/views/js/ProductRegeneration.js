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

function ProductRegeneration(idProduct, parentManager, parentDiv) {
	var self = this;
	this.divContent = parentDiv;
	this.idProduct = idProduct;
	this.nextId = 0;
	this.startByParent = false;
	this.parentManager = parentManager;
	this.navLink = parentManager.divParent.find("#nav-product"+idProduct);
	this.btnRegenerate = this.divContent.find(".btnRegenerate");
	this.btnCancel = this.divContent.find(".btnCancel");
	this.divLog = this.divContent.find(".div-content-log");
	this.divLoader = this.divContent.find(".div-content-loader");
	this.divSucess= this.divContent.find(".div-content-success");
	this.divWarning= this.divContent.find(".div-content-warning");
	this.divErrors = this.divContent.find(".div-content-errors");
	this.divCount = this.divContent.find(".div-content-count");
	this.spanCount = this.divCount.find(".value");
	this.navLoader = this.navLink.find(".nav-loader-span");
	this.count = 0;
	this.limit = 0;
	this.hasMore = false;
	
	this.currentPage = 1;
	
	
    this.onReady = function () {
    	self.handleEvent();
    };
	
	this.reset = function () {
    	self.currentPage = 1;
		self.clearLog();
		self.hideLoader();
		self.divSucess.html("");
		self.divErrors.html("");
		self.divWarning.show();
		self.count = 0;
		self.limit = 0;
		self.divCount.hide();
    };
	
	this.clearLog = function () {
    	self.divLog.html("");
    };
	
	this.showLoader = function () {
    	self.divLoader.show();
    	self.navLoader.show();
    };
	this.hideLoader = function () {
    	self.divLoader.hide();
    	self.navLoader.hide();
    };
	
	this.start = function (startByParent = false) {
		self.parentManager.onActionRequest();
		self.parentManager.changeTab(self.navLink);
    	self.startByParent = startByParent;
		self.btnRegenerate.prop("disabled", true);
		self.reset();
		self.divContent.attr("data-is_up_to_date", "0");
		self.navLink.removeClass("list-group-item-success").addClass("list-group-item-warning");
		self.initRegeneration();
    };
	
	this.initRegeneration = function () {
		self.showLoader();
    	$.ajax({
			url: ajaxModuleUrl,
			data: {
				ajax: true,
				action: "InitRegenerate",
				idProduct: self.idProduct,
			},
			success: function (result) {
				if(!result.hasError){
					self.count = result.count;
					self.limit = result.combinationLimit;
					self.divCount.show();
					self.spanCount.html(self.count);
					if(self.count>0){
						self.processRegenerate();
					}else{
						self.onProductComplete();
					}
				}else{
					self.showLoader();
				}
			},
			dataType : "JSON",
			error: function () {self.showLoader();},
			type: 'post',
		});
    };
	
	this.showNewLog = function () {
		if(self.limit>0){
			var start = (self.currentPage-1)*self.limit;
			end = start+self.limit;
			start+=1;
		}else{
			start = self.currentPage;
			end = self.count;
		}
		
		var htmlContent = '<li>'+FROM_TEXT+' '+start+' '+TO_TEXT+' '+end+'</li>';
		self.divLog.append(htmlContent);
    };
	this.onProductComplete = function () {
		self.hideLoader();
		self.btnRegenerate.prop("disabled", false);
		self.parentManager.onActionComplete();
		self.divContent.attr("data-is_up_to_date", "1");
		self.navLink.removeClass("list-group-item-warning").addClass("list-group-item-success");
		self.divWarning.hide();
		self.divSucess.html('<div class="alert alert-success">'+SUCCESS_TEXT+'</div>');
		console.log(self.startByParent);
		console.log(self.nextId);
		if(self.startByParent && (self.nextId>0)){
			self.parentManager.productManagerList[self.nextId].start(self.startByParent);
		}
	};
	this.processRegenerate = function () {
		self.showNewLog();
    	$.ajax({
			url: ajaxModuleUrl,
			data: {
				ajax: true,
				action: "Regenerate",
				idProduct: self.idProduct,
				page: self.currentPage,
				count: self.count,
			},
			success: function (result) {
				if(!result.hasError){
					self.hasMore = result.hasMore;
					if(self.hasMore){
						self.currentPage++;
						self.processRegenerate();
					}else{
						self.onProductComplete();
					}
				}else{
					
				}
			},
			dataType : "JSON",
			error: function () {},
			type: 'post',
		});
    };
	
	this.handleEvent = function () {
    	self.btnRegenerate.on("click", function(event) {
			self.start();
		});
    };
}