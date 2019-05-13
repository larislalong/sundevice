var pageHeadHeight = $("#content>div.bootstrap>div.page-head").height();
$('document').ready( function() {
	var imageAttributeManager = new ImageAttributeManager($("#product_form"));
	imageAttributeManager.onReady();
});
function ImageAttributeManager(parentBlock){
	var self = this;
	this.parentBlock = parentBlock;
	this.isRunning = false;
	this.onReady = function () {
		self.handleEvent();
	};
	this.handleEvent = function () {
		self.parentBlock.delegate("#product-imagesbyattribute .item-edit", "click", function(event){
			event.preventDefault();
			self.onEditClick($(this));
		});
		self.parentBlock.delegate("#product-imagesbyattribute .btnSave", "click", function(event){
			self.onSave();
		});
		self.parentBlock.delegate("#product-imagesbyattribute .btnCancel", "click", function(event){
			self.clearNotify();
			self.hideEditor();
		});
	};
	
	this.onEditClick = function (target) {
		self.divListDisabler = this.parentBlock.find("#product-imagesbyattribute .divListDisabler");
		self.divNotify = this.parentBlock.find("#product-imagesbyattribute .divNotify");
		self.idProduct = self.parentBlock.find("#product-imagesbyattribute .productId:first").val().trim();
		self.editionForm = self.parentBlock.find("#product-imagesbyattribute .divEditionForm");
		if(self.isRunning==true){
			return false;
		}
		self.currentRow = target.closest("tr");
		self.currentIdAttribute = self.currentRow.find("input.td_id_attribute").val().trim();
		self.showEditor();
	};
	
	this.processGetEditionContent = function () {
		self.clearNotify();
		self.showLoader(LOADING_FORM_MESSAGE);
		$.ajax({
			url: ibcaAjaxUrl,
			dataType: 'json',
			data: {
				ajax: true,
				action: "GetEditionForm",
				id_attribute: self.currentIdAttribute,
				id_product: self.idProduct
			},
			success: function (result) {
				self.hideLoader();
				if(!result.hasError){
					self.editionForm.html(result.form);
				}else{
					self.enableDivList();
					displayErrorOnDiv(self.divNotify, result.errors);
				}
			},
			error: function () {
				self.enableDivList();
				self.hideLoader();
				displayErrorOnDiv(self.divNotify, [ERROR_MESSAGE]);
			},
			type: 'post',
		});
	};
	
	this.onSave = function () {
		var values = {};
		values.ajax= true;
		values.action= "SaveImages";
		values.id_attribute= self.currentIdAttribute;
		values.id_product= self.idProduct;
		var images = [];
		self.editionForm.find(".ibca_block_image .image-item-field:checked").each(function (event) {
			var target = $(this);
			images.push(target.val());
		});
		values.images = images.join(",");
		self.showLoader(SAVING_MESSAGE);
		$.ajax({
			url: ibcaAjaxUrl,
			dataType: 'json',
			data: values,
			success: function (result) {
				self.hideLoader();
				if(!result.hasError){
					self.editionForm.hide();
					self.currentRow.find("td.td_images").html(result.imageListContent);
					self.currentRow.find("td.td_imageCount").html(result.imageCount);
					displaySuccessOnDiv(self.divNotify, result.message);
					self.hideEditor();
				}else{
					displayErrorOnDiv(self.divNotify, result.errors);
				}
			},
			error: function () {
				self.hideLoader();
				displayErrorOnDiv(self.divNotify, [ERROR_MESSAGE]);
			},
			type: 'post',
		});
	};
	this.disableDivList = function () {
		self.divListDisabler.show();
	};
	this.enableDivList = function () {
		self.divListDisabler.hide();
	};
	this.showEditor = function (idContent) {
		self.disableDivList();
		self.editionForm.show();
		self.processGetEditionContent();
	};
	this.hideEditor = function () {
		self.editionForm.hide();
		self.editionForm.html("");
		self.enableDivList();
	};
	this.clearNotify = function () {
		self.divNotify.html("");
	};
	this.showLoader = function (message) {
		self.divNotify.show();
		self.divNotify.html(getLoaderContent(message));
	};
	this.hideLoader = function () {
		self.divNotify.html("");
	};
}

function displayErrorOnDiv (divErrors, errors, scrollToDiv = true) {
   divErrors.html(getErrorsContent(errors));
   divErrors.show();
   if(scrollToDiv){
	   scrollToElement (divErrors); 
   }
}
function displaySuccessOnDiv (divSuccess, message) {
	var htmlContent="";
	htmlContent+="<div class=\"bootstrap\"><div class=\"alert alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>";
	htmlContent+=message;
	htmlContent+="</div></div>";
   
	divSuccess.html(htmlContent);
	divSuccess.show();
	scrollToElement (divSuccess);
}
function getLoaderContent(message) {
	return '<div class="loading"><i class="icon-refresh icon-spin"></i>&nbsp;' + message + '</div>';
}
function getErrorsContent(errors) {
	var htmlContent = "<div class=\"bootstrap\"><div class=\"alert alert-danger\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>";
	htmlContent += "<ol>";
	for (i in errors) {
		htmlContent += "<li>" + errors[i] + "</li>";
	}
	htmlContent += "</ol>";
	htmlContent += "</div>";
	htmlContent += "</div>";
	return htmlContent;
}
function scrollToElement(elt) {
	var defaultRemove = 0;
	var position = elt.offset().top - pageHeadHeight;
	if (position > defaultRemove) {
		position -= defaultRemove;
	}
	$("html, body").animate({
		scrollTop: position
	});
}