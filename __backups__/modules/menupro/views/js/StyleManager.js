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

function StyleManager(idMenu) {
    var self = this;
    this.divStyleEdition = $("#divStyleEdition");
    this.divStyleGetStyleNotify = $("#divStyleGetStyleNotify");
    this.divUsableStyleName = this.divStyleEdition.find("#divUsableStyleName");
	this.btnStyleLevelCancel=$("#btnStyleLevelCancel");
	this.divStyleLevelNotify = $("#divStyleLevelNotify");
	this.txtName = $("#MENUPRO_STYLE_NAME");
	this.txtUsableStyle = $("#MENUPRO_USABLE_STYLE");
	this.menuType = $("#MENUPRO_STYLE_MENU_TYPE").val();
	this.idStyle = $("#MENUPRO_STYLE_ID").val();
	this.menuLevel = $("#MENUPRO_STYLE_MENU_LEVEL").val();
	this.txtPropertiesLoaded = $("#MENUPRO_STYLE_PROPERTIES_LOADED");
	this.navStyle = $("#nav-own-style");
        this.idMenu=idMenu;
        this.previousSelectedUsable=this.txtUsableStyle.val();
	this.onReady = function () {
        self.propertyManager = new PropertyManager();
        
    	self.handleEvent();
    	if(self.navStyle.hasClass("active") && (!self.isPropertiesLoaded())){
    	    self.showEditor(self.menuType, self.idStyle, styleTypesConst.MENU,  self.menuLevel,false);
    	} else if (self.isPropertiesLoaded()) {
    	    self.divStyleEdition.show();
    	    self.propertyManager.onReady(self.divStyleEdition.find(".divProperties"));
    	    self.propertyManager.setColorFieldsBackground();
            self.onUsableStyleChange();
            self.propertyManager.triggerAllUsableValuesChange();
    	}
    };
    this.handleEvent = function () {
        self.txtUsableStyle.on("change", function () {
            self.onUsableStyleChange();
        });
    };
    this.onUsableStyleChange = function () {
        var selectedOption = self.txtUsableStyle.val();
        self.hideGetStyleNotify();
        var needToGetStyle=true;
        self.showPropertyDiv();
        self.hideDivUsableStyleName();
        if (selectedOption == usableStylesListConst.CUSTOMIZED) {
            needToGetStyle=false;
            self.propertyManager.enableDivProperties();
        } else {
            self.propertyManager.disableDivProperties();
        }
        if (selectedOption == usableStylesListConst.DEFAULT) {
            needToGetStyle=false;
            self.propertyManager.setAllUsableValuesAsDefault();
        }else if (selectedOption == usableStylesListConst.THEME) {
            needToGetStyle=false;
            self.propertyManager.setAllUsableValuesAsTheme();
        }
        if(needToGetStyle){
            self.getStyleFromUsable(selectedOption,self.menuType,self.idStyle,self.idMenu);
        }else{
            self.initializeWithCurrentMenu();
            if((!self.isPropertiesLoaded())||((self.previousSelectedUsable!=usableStylesListConst.DEFAULT)&&
            		(self.previousSelectedUsable!=usableStylesListConst.CUSTOMIZED)&&
            		(self.previousSelectedUsable!=usableStylesListConst.THEME))){
                self.showEditor(self.menuType, self.idStyle, styleTypesConst.MENU,  self.menuLevel,false);
            }
        }
        self.previousSelectedUsable=selectedOption;
    };
    this.getStyleFromUsable = function ( usableStyle, menuType,idStyle, idMenu) {
        //self.divStyleEdition.show();
        self.showGetStyleNotify();
        self.showGetStyleLoader();
        self.hidePropertyDiv();
        $.ajax({
            url: ajaxModuleUrl,
            data: {
                ajax: true,
                action: "GetStyle",
                MENUPRO_GET_STYLE_ID_STYLE: idStyle,
                MENUPRO_GET_STYLE_USABLE_STYLE: usableStyle,
                MENUPRO_GET_STYLE_MENU_TYPE: menuType,
                MENUPRO_GET_STYLE_ID_MENU: idMenu
            },
            success: function (result) {
                self.hideGetStyleLoader();
                if (result['status'] == 'success') {
                    if(result['data']['defined']){
                        self.showPropertyDiv();
                        self.propertyManager.menuType=result['data']['menu_type'];
                        self.propertyManager.menuLevel=result['data']['menu_level'];
                        self.propertyManager.idMenu=result['data']['id_menu'];
                        self.showEditor(result['data']['menu_type'], result['data']['id_style'], result['data']['style_type'], result['data']['menu_level'],true);
                        self.showDivUsableStyleName(result['data']['name']);
                    }else{
                        self.txtPropertiesLoaded.val("1");
                        displayInfoOnDiv(self.divStyleGetStyleNotify,result['data']['message']);
						self.divStyleEdition.show();
                    }
                } else {
                    displayErrorOnDiv(self.divStyleGetStyleNotify,result['data']);
                }
            },
            error: function () {
                self.hideGetStyleLoader();
                displayErrorOnDiv(self.divStyleGetStyleNotify,[ajaxRequestErrorMessage]);
            },
            type: 'post'
        });
    };
    this.initializeWithCurrentMenu = function () {
        self.propertyManager.menuType=self.menuType;
        self.propertyManager.menuLevel=self.menuLevel;
        self.propertyManager.idMenu=self.idMenu;
    };
    this.showPropertyDiv = function () {
        self.divStyleEdition.find(".divProperties:first").show();
    };
    this.hidePropertyDiv = function () {
        self.divStyleEdition.find(".divProperties:first").hide();
    };
    this.showDivUsableStyleName = function (name) {
        self.divUsableStyleName.show();
        self.divUsableStyleName.find("#txtUsableStyleName").val(name);
    };
    this.hideDivUsableStyleName = function () {
        self.divUsableStyleName.hide();
        self.divUsableStyleName.find("#txtUsableStyleName").val("");
    };
    this.showEditor = function (menuType, idStyle,styleType,menuLevel,getStyleFromUsableCall) {
        var selectedOption = self.txtUsableStyle.val();
        var needToShowEditor=true;
        var disableProperties =(selectedOption != usableStylesListConst.CUSTOMIZED);
        if((selectedOption != usableStylesListConst.CUSTOMIZED)&&(selectedOption != usableStylesListConst.DEFAULT)
        		&&(selectedOption != usableStylesListConst.THEME)){
            if(!getStyleFromUsableCall){
                needToShowEditor=false;
                self.getStyleFromUsable(selectedOption,self.menuType,self.idStyle,self.idMenu);
            }
        }else{
            self.showPropertyDiv();
            self.hideDivUsableStyleName();
            self.initializeWithCurrentMenu();
        }
        if(needToShowEditor){
            self.propertyManager.renderPropertiesBlock(self, self.divStyleEdition, styleType, menuType, menuLevel, idStyle, disableProperties);
        }
    };
    this.showGetStyleNotify = function () {
        self.divStyleGetStyleNotify.show();
        self.divStyleGetStyleNotify.html("");
    };
    this.hideGetStyleNotify = function () {
        self.divStyleGetStyleNotify.hide();
        self.divStyleGetStyleNotify.html("");
    };
    this.showGetStyleLoader = function () {
        self.divStyleGetStyleNotify.html(getLoaderContent(loaderGetStyleMessage));
    };
    this.hideGetStyleLoader = function () {
        self.divStyleGetStyleNotify.html("");
    };
	this.processSave = function () {
		self.onOperation(loaderSaveStyleLevelMessage);
		var values={};
		values.ajax=true;
		values.action="saveStyleLevel";
		values.MENUPRO_STYLES_LEVEL_ID=self.txtId.val();
		values.MENUPRO_STYLES_LEVEL_NAME=self.txtName.val();
		values.MENUPRO_STYLES_LEVEL_MENU_LEVEL=self.txtLevel.val();
		values.MENUPRO_STYLES_LEVEL_MENU_TYPE=self.menuType;
		values.MENUPRO_STYLES_LEVEL_MENU_ID=self.idMenu;
		values = self.propertyManager.getData(values);
       $.ajax({
            url: ajaxModuleUrl,
            data: values,
            success: function (result) {
            	hideModalLoader();
            	if (result['status'] == 'success') {
            	    if (self.currentAction == "ADD") {
            	        self.addStleLevelInList(result['data']['id_style'], result['data']['style_name'], result['data']['menu_level']);
            	    } else {
            	        self.updateStleLevelInList(result['data']['id_style'], result['data']['style_name'], result['data']['menu_level']);
            	    }
					displaySuccessOnDiv(self.divStyleLevelNotify,result['data']['message']);
                } else {
					displayErrorOnDiv(self.divStyleLevelNotify,result['data']);
                }
            },
            error: function () {
                hideModalLoader();
                displayErrorOnDiv(self.divStyleLevelNotify, [ajaxRequestErrorMessage]);
            },
            type: 'post',
        });
    };
	this.onOperation = function (loaderMessage) {
		self.clearNotify();
		self.divStyleLevelNotify.show();
		showModalLoader(loaderMessage);
	};
    this.clearNotify = function () {
	    self.divStyleLevelNotify.html("");
    };
    this.onLoadPropertiesErrors = function () {
        self.txtPropertiesLoaded.val("0");
    };
    this.onLoadPropertiesSuccess = function () {
        self.txtPropertiesLoaded.val("1");
        if (self.previousSelectedUsable == usableStylesListConst.DEFAULT) {
            self.propertyManager.setAllUsableValuesAsDefault();
        }else if(self.previousSelectedUsable == usableStylesListConst.THEME){
        	self.propertyManager.setAllUsableValuesAsTheme();
        }
    };
    this.onTabChange = function (nav) {
        if ((nav.attr("id") == "nav-own-style") && nav.hasClass("active") && (!self.isPropertiesLoaded())) {
            self.showEditor(self.menuType, self.idStyle, styleTypesConst.MENU,  self.menuLevel,false);
        }
    };
    this.isPropertiesLoaded = function () {
        return self.txtPropertiesLoaded.val() == "1";
    };
    this.getStyleValues = function (values) {
        values.MENUPRO_STYLE_PROPERTIES_LOADED = self.txtPropertiesLoaded.val();
        if (self.isPropertiesLoaded()) { 
            values.MENUPRO_STYLE_ID = $("#MENUPRO_STYLE_ID").val();
            values.MENUPRO_STYLE_NAME = $("#MENUPRO_STYLE_NAME").val();
            values.MENUPRO_USABLE_STYLE = $("#MENUPRO_USABLE_STYLE").val();
            values = self.propertyManager.getData(values);
        }
        return values;
    };
}