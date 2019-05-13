<?php /* Smarty version Smarty-3.1.19, created on 2019-04-29 16:48:30
         compiled from "/home/sundevice/public_html/override/controllers/admin/templates/products/supportedltenetworks_js.tpl" */ ?>
<?php /*%%SmartyHeaderCode:5810685155cc70ebeae53c2-79257215%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e73e024520fa21ba6fc1e00d107c74f900a208ee' => 
    array (
      0 => '/home/sundevice/public_html/override/controllers/admin/templates/products/supportedltenetworks_js.tpl',
      1 => 1518187958,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5810685155cc70ebeae53c2-79257215',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'link' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cc70ebeaf8d25_41353681',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cc70ebeaf8d25_41353681')) {function content_5cc70ebeaf8d25_41353681($_smarty_tpl) {?><script type="text/javascript">
	var pageHeadHeight = $("#content>div.bootstrap>div.page-head").height();
	function ProductLteManager(parentBlock){
		var self = this;
		this.parentBlock = parentBlock;
		this.editionManager = new ProductLteEditionManager(this);
		this.isRunning = false;
		this.onReady = function () {
			self.handleEvent();
		};
		this.handleEvent = function () {
			self.parentBlock.delegate("#product-supportedltenetworks .productLTE-edit", "click", function(event){
				event.preventDefault();
				self.onEditClick($(this));
			});
			self.parentBlock.delegate("#product-supportedltenetworks .btnSave", "click", function(event){
				self.onSave(false);
			});
			self.parentBlock.delegate("#product-supportedltenetworks .btnCancel", "click", function(event){
				self.clearNotify();
				self.hideEditor();
			});
			self.editionManager.handleEvent();
		};
		
		this.onEditClick = function (target) {
			self.divProductLTEListDisabler = this.parentBlock.find("#divProductLTEListDisabler");
			self.divProductLTENotify = this.parentBlock.find("#divProductLTENotify");
			self.idProduct = self.parentBlock.find("#product-supportedltenetworks .productId:first").val().trim();
			self.editionForm = self.parentBlock.find("#productLTE-EditionForm");
			if(self.isRunning==true){
				return false;
			}
			self.currentRow = target.closest("tr");
			self.currentIdAttribute = self.currentRow.find("input.td_id_attribute").val().trim();
			self.currentId = self.currentRow.find("input.td_id_product_supported_lte").val().trim();
			self.showEditor();
		};
		
		this.processGetEditionContent = function () {
			self.clearNotify();
			self.showLoader("<?php echo smartyTranslate(array('s'=>'Loading form...'),$_smarty_tpl);?>
");
			$.ajax({
				url: "<?php echo addslashes($_smarty_tpl->tpl_vars['link']->value->getAdminLink('AdminProducts',true));?>
",
				dataType: 'json',
				data: {
					ajax: true,
					action: "GetLTEEditionForm",
					id_attribute: self.currentIdAttribute,
					id_product_supported_lte: self.currentId
				},
				success: function (result) {
					self.hideLoader();
					if(!result.hasError){
						self.editionForm.html(result.form);
						self.editionManager.resetSize();
						//initMCE("autoload_rte_lte");
						hideOtherLanguage(default_language);
					}else{
						displayErrorOnDiv(self.divProductLTENotify, result.errors);
					}
				},
				error: function () {
					self.hideLoader();
					displayErrorOnDiv(self.divProductLTENotify, ["<?php echo smartyTranslate(array('s'=>'An error occured while connecting to server'),$_smarty_tpl);?>
"]);
				},
				type: 'post',
			});
		};
		
		this.getValue = function (values, hasMce) {
			values["total_markets"] =self.editionForm.find("#txtTotalMarket").val();
			var i =1;
			self.editionForm.find(".block-markets .table-markets .tr-market").each(function() {
				var row = $(this);
				row.find(".market-field").each(function() {
					var field=$(this);
					var parentTd = field.closest("td");
					if(parentTd.hasClass("td_market_name") || parentTd.hasClass("td_market_image")){
						values[field.attr("data-field-name")+"_"+i] = field.val();
					}else{
						if(hasMce){
							values[field.attr("data-field-name")+"_"+i] = getRichTextContent(field);
						}else{
							values[field.attr("data-field-name")+"_"+i] = field.val();
						}
					}
				});
				i++;
			});
			return values;
		}
		this.onSave = function (hasMce) {
			var values = {};
			values.ajax= true;
			values.action= "SaveProductLte";
			values.id_attribute= self.currentIdAttribute;
			values.id_product_supported_lte= self.currentId;
			values.id_product= self.idProduct;
			
			/*self.editionForm.find("textarea").each(function (event) {
				var target = $(this);
				values[target.attr("name")] = getRichTextContent(target);
			});*/
			values = self.getValue(values, hasMce);
			self.showLoader("<?php echo smartyTranslate(array('s'=>'Saving data...'),$_smarty_tpl);?>
");
			$.ajax({
				url: "<?php echo addslashes($_smarty_tpl->tpl_vars['link']->value->getAdminLink('AdminProducts',true));?>
",
				dataType: 'json',
				data: values,
				success: function (result) {
					self.hideLoader();
					if(!result.hasError){
						self.editionForm.hide();
						self.currentRow.find("input.td_id_product_supported_lte").val(result.id_product_supported_lte);
						self.currentRow.find("td.lte_content").html(result.content);
						displaySuccessOnDiv(self.divProductLTENotify, result.message);
						self.hideEditor();
					}else{
						displayErrorOnDiv(self.divProductLTENotify, result.errors);
					}
				},
				error: function () {
					self.hideLoader();
					displayErrorOnDiv(self.divProductLTENotify, ["<?php echo smartyTranslate(array('s'=>'An error occured while connecting to server'),$_smarty_tpl);?>
"]);
				},
				type: 'post',
			});
		};
		this.disableDivList = function () {
			self.divProductLTEListDisabler.show();
		};
		this.enableDivList = function () {
			self.divProductLTEListDisabler.hide();
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
			self.divProductLTENotify.html("");
		};
		this.showLoader = function (message) {
			self.divProductLTENotify.show();
			self.divProductLTENotify.html(getLoaderContent(message));
		};
		this.hideLoader = function () {
			self.divProductLTENotify.html("");
		};
	}
	function ProductLteEditionManager(productLteManager){
		var self = this;
		this.newRowContent = "";
		this.newClassRte = "autoload_rte_lte_"+new Date().getTime();
		this.parentBlock = productLteManager.parentBlock;
		this.handleEvent = function () {
			self.parentBlock.delegate("#product-supportedltenetworks .productLte_edition_content .market-new", "click", function(event){
				event.preventDefault();
				self.addNewRow($(this), "", "", "", true);
			});
			self.parentBlock.delegate("#product-supportedltenetworks .productLte_edition_content .market-delete", "click", function(event){
				event.preventDefault();
				self.removeRow($(this));
			});
			self.parentBlock.delegate("#product-supportedltenetworks .productLte_edition_content .btnGenerateMarket", "click", function(event){
				self.showRegenerateBlock();
			});
			self.parentBlock.delegate("#product-supportedltenetworks .productLte_edition_content .btnResetMarkets", "click", function(event){
				self.processRegeneration();
			});
		};
		this.processRegeneration = function () {
			var imageBaseUrl = "https://www.apple.com";
			var btnAddNew = self.parentBlock.find("#product-supportedltenetworks .productLte_edition_content .market-new");
			var blockMarkets = btnAddNew.closest(".block-markets");
			blockMarkets.find(".table-markets tbody").html("");
			self.resetListCount(blockMarkets, 0);
			var blockRegenerateContent = self.parentBlock.find("#product-supportedltenetworks .productLte_edition_content .blockRegenerateContent");
			blockRegenerateContent.html(self.parentBlock.find("#product-supportedltenetworks .productLte_edition_content .txtRegenerateMarket").val());
			var markets = blockRegenerateContent.find(".countries").children();
			markets.each(function(){
				var marketRow = $(this);
				var imageSource = imageBaseUrl+marketRow.find("dt img").attr("src");
				var marketName = marketRow.find("dt").text();
				var marketLte = marketRow.find("dd").html();
				self.addNewRow(btnAddNew, marketName, imageSource, marketLte, false);
			});
			blockRegenerateContent.html("");
			self.hideRegenerateBlock();
			productLteManager.onSave(false);
			
		};
		this.showRegenerateBlock = function () {
			self.parentBlock.find("#product-supportedltenetworks .productLte_edition_content .block-regenerate-markets").show();
		};
		this.hideRegenerateBlock = function () {
			self.parentBlock.find("#product-supportedltenetworks .productLte_edition_content .block-regenerate-markets").hide();
		};
		this.addNewRow = function (target, marketName, marketImage, lteContent, displayContent) {
			self.updateRowCount(target, 1, marketName, marketImage, lteContent, displayContent);
		};
		this.removeRow = function (target) {
			self.updateRowCount(target, -1, "", "", "", false);
		};
		this.updateRowCount = function (target, value, marketName, marketImage, lteContent, displayContent) {
			var blockMarkets = target.closest(".block-markets");
			/*var spanTotal = blockMarkets.find(".head-market").find(".market-count:first");
			var listCount = parseInt(spanTotal.html().trim()) + value;
			spanTotal.html(listCount);
			blockMarkets.find(".txt-total-markets").val(listCount);*/
			if(value>0){
				blockMarkets.find(".table-markets tbody").append(self.getNewRowContent(marketName, marketImage, lteContent, !displayContent));
				self.resetFieldName(blockMarkets);
				if(displayContent){
					self.resetSize();
					//initMCE(self.newClassRte);
					hideOtherLanguage(default_language);
				}else{
					self.newRowContent = "";
				}
				blockMarkets.find(".tr-market .market-field."+self.newClassRte).removeClass(self.newClassRte);
			}else{
				target.closest(".tr-market").remove();
				self.resetFieldName(blockMarkets);
			}
		};
		this.getNewRowContent = function (marketName, marketImage, lteContent, onlyField) {
			if((self.newRowContent=="") || (marketName!="") || (marketImage!="") || (lteContent!="")){
				self.newRowContent = '<tr class="tr-market">'+
					'<td class="td_market_name"><div>'+self.getLangNewField('market_name', 'form-control market-field', false, marketName, onlyField)+'</div></td>'+
					'<td class="td_market_image"><input type="text" name="market_image" value="'+marketImage+'" class="market-field" data-field-name="market_image"></td>'+
					'<td class="td_market_content"><div>'+self.getLangNewField('content', 'autoload_rte_lte market-field '+self.newClassRte, true, lteContent, onlyField)+'</div></td>'+
					'<td class="text-right">'+
						'<a href="#" class="btn btn-default market-delete" title="<?php echo smartyTranslate(array('s'=>'Delete'),$_smarty_tpl);?>
"><i class="icon-trash"></i></a>'+
					'</td>'+
				'</tr>';
			}
			return self.newRowContent;
		};
		this.resetSize = function () {
			self.parentBlock.find(".td_market_content .translatable-field .col-lg-9").removeClass("col-lg-9").addClass("col-lg-11");
			self.parentBlock.find(".td_market_content .translatable-field .col-lg-2").removeClass("col-lg-2").addClass("col-lg-1");
		};
		this.resetFieldName = function (blockMarkets) {
			var i =1;
			blockMarkets.find(".tr-market").each(function() {
				var row = $(this);
				if((i%2)!=0){
					row.addClass("odd");
				}else{
					row.removeClass("odd");
				}
				row.find(".market-field").each(function() {
					var field=$(this);
					var name= field.attr("data-field-name")+"_"+i;
					field.attr("name",name);
					field.attr("id",name);
				});
				i++;
			});
			var listCount = i-1;
			self.resetListCount(blockMarkets, listCount);
		};
		this.resetListCount = function (blockMarkets, value) {
			blockMarkets.find(".txt-total-markets").val(value);
			var spanTotal = blockMarkets.find(".head-market").find(".market-count:first").html(value);
		}
		this.getLangNewField = function (fieldName, fieldClass, isTextArea, fieldValue, onlyField) {
			var htmlContent = '';
			if(onlyField){
				htmlContent+=self.getLangFieldInput(fieldName, fieldClass, isTextArea, fieldValue, default_language);
			}else{
				var langListContent = '';
			
				langLength  = languages.length;
				for(i in languages){
					langListContent+='<li><a href="javascript:tabs_manager.allow_hide_other_languages = false;hideOtherLanguage('+languages[i]["id_lang"]+');">'+
						languages[i]["name"]+'</a></li>';
				}
				for(i in languages){
					var idField = fieldName+'_'+languages[i]["id_lang"];
					var attributesContent = ' id="'+idField+'" name="'+idField+'" data-field-name="'+idField+'" class="'+fieldClass+'"';
					htmlContent+=((langLength>1)?'<div class="translatable-field row lang-'+languages[i]["id_lang"]+'"><div class="col-lg-9">':'')+
					self.getLangFieldInput(fieldName, fieldClass, isTextArea, fieldValue, languages[i]["id_lang"])+
					((langLength>1)?('</div><div class="col-lg-2"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">'+
						languages[i]["iso_code"]+'<span class="caret"></span></button><ul class="dropdown-menu">'+langListContent+'</ul></div></div>'):'');
					
				}
			}
			
			return htmlContent;
		};
		this.getLangFieldInput = function (fieldName, fieldClass, isTextArea, fieldValue, idLang) {
			var idField = fieldName+'_'+idLang;
			var attributesContent = ' id="'+idField+'" name="'+idField+'" data-field-name="'+idField+'" class="'+fieldClass+'"';
			var htmlContent = (isTextArea ? '<textarea rows="10" '+attributesContent+'>'+fieldValue+'</textarea>' : '<input type="text" '+attributesContent+' value="'+fieldValue+'" />');
			return htmlContent;
		};
	}
	$('document').ready( function() {
		var productLteManager = new ProductLteManager($("#product_form"));
		productLteManager.onReady();
	});
	function getRichTextContent(textarea) {
		var EMPTY_CONTENT='<br data-mce-bogus="1">';
		var divMCE = textarea.prev();
		var iframeMCE = divMCE.find(".mce-container-body:first").find(".mce-edit-area:first").find("iframe:first");
		var frameBody = iframeMCE.contents().find("html:first>body:first");
		var contentBlock = frameBody.find("p:first");
		if ((contentBlock.length > 0) && (contentBlock.html().trim() == EMPTY_CONTENT)) {
			return "";
		} else {
			return frameBody.html();
		}
	}
	function initMCE(fieldClass = "autoload_rte") {
		tinySetup({
			editor_selector :fieldClass
		});
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
</script><?php }} ?>
