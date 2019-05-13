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

function SecondaryMenuEditionManager(modalEditionForm, idMenu, itemType, divAction) {
    var self = this;
    
    this.itemType = itemType;
    this.styleManager = new StyleManager(idMenu);
    this.styleLevelManager = null;
    this.htmlContentManager = null;
    this.modalEditionForm = modalEditionForm;
    this.onOpenDialog = function () {
    	setModalMaxHeight(self.modalEditionForm);
        if (self.itemType == availableSecondaryMenuTypeConst.CUSTOMISE) {
        	$(document).off('focusin.modal');
        	if(ps_version<'1.6'){
        		resetMCE();
        	}
        }
    };
    if(ps_version>='1.6'){
    	this.modalEditionForm.modal({
            backdrop: 'static',
            keyboard: true,
            show: true
        });
    }else{
    	this.modalEditionForm.dialog({
    		modal: true,
    		width : "95%",
    		appendTo: "#divSecondaryMenuEditionParent",
    		close: function(event, ui)
            {
    			$(this).dialog('destroy').remove();
            },
            open: function(event, ui)
            {
            	self.onOpenDialog();
            }
    	});
    }
    this.divAction=divAction;
    this.idMenu=idMenu;
    this.divEditSecondaryMenuNotify = this.modalEditionForm.find("#divEditSecondaryMenuNotify");
    this.saveAndStayClick = false;
    this.txtDisplayType = $("#MENUPRO_SECONDARY_MENU_DISPLAY_STYLE");
    var divDisplayTypeSelector = (ps_version>='1.6')?".form-group":".margin-form";
    this.divDisplayType = this.txtDisplayType.closest(divDisplayTypeSelector);
    if(ps_version<'1.6'){
    	this.divDisplayType.addClass("clearfix");
    }
    this.onReady = function () {
        if (displayStyleLevels) {
            self.styleLevelManager = new StyleLevelManager(menuTypesConst.SECONDARY);
            self.styleLevelManager.onReady();
        }
        if((self.itemType == availableSecondaryMenuTypeConst.CATEGORY)||(self.itemType == availableSecondaryMenuTypeConst.PRODUCT)){
        	self.addDisplayTypePreviewContent();
        	self.txtDisplayType.on("change", function () {
                self.changeImagePreview();
            });
        }
        if (displayHtmlContents) {
            self.htmlContentManager = new HtmlContentManager(idMenu, self.modalEditionForm.find("#divHtmlContentBlock"));
            self.htmlContentManager.onReady();
        }
        self.handleEvent();
        manageTabs(self);
        self.styleManager.onReady();
        self.modalEditionForm.find("input[name='MENUPRO_SECONDARY_MENU_USE_CUSTOM_CONTENT']:checked").each(function (event) {
            self.showHideHtmlContentDiv($(this));
        });
    };
    this.addFieldShopBaseUrl = function (inputLinkType) {
    	if(ps_version>='1.6'){
    		var linkTypeDiv = inputLinkType.closest(".form-group");
            var parentDiv = linkTypeDiv.next().children("div");
    	}else{
    		var linkTypeDiv = inputLinkType.closest(".margin-form");
            var parentDiv = linkTypeDiv.nextAll(".margin-form:first");
    	}
        var contentBefore = parentDiv.html();
        parentDiv.html(self.getShopBaseUrlContent() +
            '<div class="col-lg-7 col-md-7 col-sm-6 col-xs-6'+
            ((inputLinkType.val()==linkTypesConst.EXTERNAL)?'style="display:none;':'')+'">' + 
            contentBefore + '</div>');
        if(ps_version<'1.6'){
        	parentDiv.find(".language_current").click(function() {
				toggleLanguageFlags(this);
			});
        	parentDiv.find(".language_flags:last").find("img").click(function() {
        		var source = $(this).attr("src");
        		source = removePrevString(source, "/");
        		var end = source.indexOf(".");
        		if(end>0){
        			var idLang = parseInt(source.substring(0, end).trim());
            		var language = getLangById(idLang);
            		changeFormLanguage(language['id_lang'], language['iso_code'], allowEmployeeFormLang);
            		self.changeBaseLink(idLang);
        		}
			});
        }
        self.changeBaseLink(defaultModLanguage);
    };
    this.getShopBaseUrlContent = function () {
        return '<div id="divShopBaseUrl" class="base-link col-lg-5 col-md-7 col-sm-6 col-xs-6">' +
            '<input type="text" id="txtShopBaseUrl" value="" disabled>' +
            '<p class="help-block">'+linkBaseDescription+'</p>'+
        '</div>';
    };
    this.addDisplayTypePreviewContent = function () {
    	self.divDisplayType.append(self.getDisplayTypePreviewContent(self.getImagePreviewSource()));
    	self.imgDisplayType=self.divDisplayType.find("#imgDisplayType");
    };
    this.getDisplayTypePreviewContent = function (source) {
        return '<div class="col-lg-5">' +
            '<img class="mp-preview" id="imgDisplayType" src="'+source+'" class="item-img " title="" alt="preview"/>'
        '</div>';
    };
    this.getImagePreviewSource = function () {
        var displayType = parseInt(self.txtDisplayType.val());
        return imageDisplayTypeFolder + itemTypeParams[parseInt(self.itemType)][displayType].image_file_name;
    };
    this.changeImagePreview = function () {
        self.imgDisplayType.attr("src",self.getImagePreviewSource());
    };
    
    this.handleEvent = function () {
    	var dialogOpenEvent = (ps_version>='1.6')?"shown.bs.modal":"dialogopen";
        self.modalEditionForm.on(dialogOpenEvent, function () {
            self.onOpenDialog();
        });
        self.modalEditionForm.find(".btnCancel").on("click", function (event) {
            event.preventDefault();
            self.closeModal();
        });
        self.modalEditionForm.find(".btnSave").on("click", function (event) {
            event.preventDefault();
            self.processUpdateMenu();
            self.saveAndStayClick = false;
        });
        self.modalEditionForm.find(".btnSaveAndStay").on("click", function (event) {
            event.preventDefault();
            self.processUpdateMenu();
            self.saveAndStayClick = true;
        });
        if (self.itemType == availableSecondaryMenuTypeConst.CUSTOMISE) {
            self.modalEditionForm.find("input[name='MENUPRO_SECONDARY_MENU_USE_CUSTOM_CONTENT']").on("change", function (event) {
                self.showHideHtmlContentDiv($(event.target));
            });
            self.inputLinkType=self.modalEditionForm.find("#MENUPRO_SECONDARY_MENU_LINK_TYPE");
            self.addFieldShopBaseUrl(self.inputLinkType);
            self.inputLinkType.on("change", function () {
                self.onLinkTypeChange();
            });
            if(ps_version>='1.6'){
            	self.modalEditionForm.find(".translatable-field").find(".dropdown-menu").find("a").on("click", function (event) {
                    var target=$(this);
                    var href=target.attr("href");
                    var hrefBegin="javascript:hideOtherLanguage(";
                    var hrefEnd=");";
                    var index=href.indexOf(hrefBegin);
                    if(index!=-1){
                        var idLang=parseInt(href.substring((index+hrefBegin.length),(href.length-hrefEnd.length)));
                        self.changeBaseLink(idLang);
                    }
                });
            }
        }
    };

    this.closeModal = function () {
    	if(ps_version>='1.6'){
    		self.modalEditionForm.modal('hide');
    	}else{
    		self.modalEditionForm.dialog("close");
    	}
    };
    this.changeBaseLink = function (idLang) {
        self.modalEditionForm.find("#divShopBaseUrl").find("#txtShopBaseUrl").val(homeLinkList[idLang]);
    };
    this.onLinkTypeChange = function () {
        if(self.inputLinkType.val()==linkTypesConst.INTERNAL){
            self.modalEditionForm.find("#divShopBaseUrl").show();
            self.changeBaseLink(defaultModLanguage);
        }else{
            self.modalEditionForm.find("#divShopBaseUrl").hide();
        }
    };
    this.showHideHtmlContentDiv = function (target) {
    	if(ps_version>='1.6'){
    		var divContentHtml = target.closest(".form-group").next();
    	}else{
    		var divUseContentHtml = target.closest(".margin-form");
    		var divContentHtml = divUseContentHtml.nextAll(".margin-form:first");
    		var label = divUseContentHtml.nextAll("label:first");
    	}
    	if (target.val()) {
            divContentHtml.show();
            if(ps_version<'1.6'){
            	label.show();
        	}
        } else {
            divContentHtml.hide();
            if(ps_version<'1.6'){
            	label.hide();
        	}
        }
    };
    this.processUpdateMenu = function () {
        self.showNotifyDiv();
        showModalLoader(loaderSecondaryMenuEditionUpdateMessage);
        var values = {};
        values.ajax = true;
        values.action = "UpdateSecondaryMenu";
        values = self.getNavInformationValues(values);
        values = self.styleManager.getStyleValues(values);
        
        values.MENUPRO_SECONDARY_MENU_ID = self.idMenu;
        values.MENUPRO_SECONDARY_MENU_ITEM_TYPE = self.itemType;
        $.ajax({
            url: ajaxModuleUrl,
            data: values,
            success: function (result) {
                hideModalLoader();
                if (result['status'] == 'success') {
                    self.divAction.attr("data-name",result['data']['name']);
                    self.divAction.prev().find("a").html(result['data']['name']);
                    self.divAction.find("#divMenuStatus" + self.idMenu).html(getStatusDivContent(result['data']['active']));
                    if (self.saveAndStayClick) {
                        displaySuccessOnDiv(self.divEditSecondaryMenuNotify, result['data']['message']);
                    } else {
                        self.closeModal();
                    }
                } else {
                    displayErrorOnDiv(self.divEditSecondaryMenuNotify, result['data']);
                }
            },
            error: function () {
                hideModalLoader();
                displayErrorOnDiv(self.divEditSecondaryMenuNotify, [ajaxRequestErrorMessage]);
            },
            type: 'post',
        });
    };
    this.showNotifyDiv = function () {
        self.divEditSecondaryMenuNotify.show();
        self.divEditSecondaryMenuNotify.html("");
    };
    this.getNavInformationValues = function (values) {
        var divInformation = self.modalEditionForm.find("#divInformationBlock");
        var radioAdded = [];
        divInformation.find("input, select").each(function (event) {
            var target=$(this);
            var type=target.attr("type");
            if (type == "radio") {
                var name = target.attr("name");
                if ((radioAdded.indexOf(name)==-1) && target.is(":checked")) {
                    radioAdded.push(name);
                    values[name] = target.val();
                }
            } else{
                values[target.attr("name")] = target.val();
            }
        });
        if (self.itemType == availableSecondaryMenuTypeConst.CUSTOMISE) {
            if (values["MENUPRO_SECONDARY_MENU_USE_CUSTOM_CONTENT"]) {
                divInformation.find("textarea").each(function (event) {
                    var target = $(this);
                    values[target.attr("name")] = getRichTextContent(target);
                });
            }
        }
        return values;
    };
    this.onTabChange = function (nav) {
        self.styleManager.onTabChange(nav);
    };
}