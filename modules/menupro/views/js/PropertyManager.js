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

function PropertyManager() {
    var self = this;
    this.idMenu=0;
    this.menuType=menuTypesConst.NONE;
    this.menuLevel=0;
    this.propertiesLoaded=false;

    this.onReady = function (divProperties) {
        self.divProperties = divProperties;
        self.handleEvent();
    };
    this.handleEvent = function () {
        self.divProperties.find(".usable-values").on("change", function () {
            self.onUsableValueChange($(this));
        });
        self.divProperties.find(".div-property-value").find(".dropdown-item").on("click", function () {
        	var target=$(event.target);
        	event.preventDefault();
        	var divPropertyValue=target.closest(".div-property-value");
        	var input=divPropertyValue.find(".property-select-value");
        	//var inputHidden=divPropertyValue.find(".property-value");
        	input.val(target.attr("data-name"));
        	//inputHidden.val(target.attr("data-name"));
        });
    };
    
    this.setAllUsableValuesAsDefault = function () {
        var selectUsablesValues = self.divProperties.find(".usable-values");
        jQuery.each(selectUsablesValues, function () {
            $(this).val(usableValuesConst.DEFAULT);
            self.onUsableValueChange($(this));
        });
    };
    this.setAllUsableValuesAsTheme = function () {
        var selectUsablesValues = self.divProperties.find(".usable-values");
        jQuery.each(selectUsablesValues, function () {
            $(this).val(usableValuesConst.THEME);
            self.onUsableValueChange($(this));
        });
    };
    this.triggerAllUsableValuesChange = function () {
        var selectUsablesValues = self.divProperties.find(".usable-values");
        jQuery.each(selectUsablesValues, function () {
            self.onUsableValueChange($(this));
        });
    };
    this.onUsableValueChange = function (selectUsableValue) {
        var selectedOption = selectUsableValue.val();
        var parentDiv = selectUsableValue.parents(".property-block");
        var divPropertyValue = parentDiv.find(".div-property-value").children();
        var propertyField=null;
        jQuery.each(divPropertyValue, function () {
            var isInputValue=false;
            var target=$(this);
            if (target.hasClass("property-value")) {
                isInputValue=true;
                propertyField=target;
            }
            if ((selectedOption == usableValuesConst.CUSTOMIZED)&&(!selectUsableValue.is(':disabled'))) {
                target.prop("disabled", false);
                var valueResultType=target.attr("data-result-value-type");
                if((valueResultType!="")&&(valueResultType!=valueResultConst.DEFINED)){
                    target.val("");
                }
            } else {
                target.prop("disabled", true);
            }
            if (selectedOption == usableValuesConst.DEFAULT) {
                if (isInputValue) {
                    target.val(target.attr("data-default-value"));
                }
            }
            if (selectedOption == usableValuesConst.THEME) {
                if (isInputValue) {
                    target.val("");
                }
            }
            if ((isInputValue) && (target.hasClass("mColorPickerInput"))) {
            	self.setColorFieldBackground(target);
            }
        });
        if((selectedOption != usableValuesConst.CUSTOMIZED)&&(selectedOption != usableValuesConst.DEFAULT)&&
        		(selectedOption != usableValuesConst.THEME)){
            var idProperty=parentDiv.find(".property-id:first").val();
            self.getValueFromUsable(idProperty,selectedOption,self.menuType,self.idMenu,self.menuLevel,propertyField);
        }
    };

    this.renderPropertiesBlock = function (styleManager, divStyleProperties, styleType, menuType, menuLevel, idStyle, disableFields) {
        self.propertiesLoaded=false;
        var divProperties = divStyleProperties.find(".divProperties");
        var notifyDiv = self.createNotifyDiv(divStyleProperties);
        var loaderDiv = self.getLoaderDiv(notifyDiv);
        var errorsDiv = self.getErrorsDiv(notifyDiv);
        scrollToElement(divStyleProperties);
        self.showLoader(loaderDiv);
        $.ajax({
            url: ajaxModuleUrl,
            data: {
                ajax: true,
                action: "RenderFormProperties",
                MENUPRO_ID_STYLE: idStyle,
                MENUPRO_STYLE_TYPE: styleType,
                MENUPRO_MENU_TYPE: menuType,
                MENUPRO_MENU_LEVEL: menuLevel,
                MENUPRO_PROPERTIES_DISABLED: disableFields
            },
            success: function (result) {
                self.hideLoader(loaderDiv);
                divStyleProperties.show();
                if (result['status'] == 'success') {
                    // scrollToElement(divStyleProperties);
                    self.propertiesLoaded=true;
                    divProperties.html(result['data']);
                    self.onReady(divProperties);
                    styleManager.onLoadPropertiesSuccess();
                    self.setColorFieldsBackground();
                    self.triggerAllUsableValuesChange();
                } else {
                    self.displayErrorOnDiv(errorsDiv, result['data']);
                    styleManager.onLoadPropertiesErrors();
                }
            },
            error: function () {
                divStyleProperties.show();
                self.hideLoader(loaderDiv);
                self.displayErrorOnDiv(errorsDiv, [ajaxRequestErrorMessage]);
                styleManager.onLoadPropertiesErrors();
            },
            type: 'post'
        });
    };
    
    this.getValueFromUsable = function (idProperty, usableValue, menuType, idMenu, menuLevel,propertyField) {
        self.hideErrorOnInput(propertyField);
        self.showLoaderOnInput(propertyField);
        $.ajax({
            url: ajaxModuleUrl,
            data: {
                ajax: true,
                action: "GetPropertyValue",
                MENUPRO_GET_PROPERTY_VALUE_ID_PROPERTY: idProperty,
                MENUPRO_GET_PROPERTY_VALUE_USABLE_VALUE: usableValue,
                MENUPRO_GET_PROPERTY_VALUE_MENU_TYPE: menuType,
                MENUPRO_GET_PROPERTY_VALUE_MENU_LEVEL: menuLevel,
                MENUPRO_GET_PROPERTY_VALUE_ID_MENU: idMenu
            },
            success: function (result) {
                self.hideLoaderOnInput(propertyField);
                if (result['status'] == 'success') {
                    propertyField.val(result['data']['value']);
                    propertyField.attr("data-result-value-type",result['data']['type_value']);
                    self.setColorFieldBackground(propertyField);
                    
                } else {
                    self.showErrorOnInput(propertyField,result['data'][0]);
                }
            },
            error: function () {
                self.hideLoaderOnInput(propertyField);
                self.showErrorOnInput(propertyField,ajaxRequestErrorMessage);
            },
            type: 'post'
        });
    };

    this.createNotifyDiv = function (parentDiv) {
        parentDiv.hide();
        var notifyDiv = parentDiv.next();
        if ((notifyDiv == null) || (notifyDiv.length == 0)) {
            parentDiv.after("<div id=\"divNotifyProperties\"></div>");
            notifyDiv = parentDiv.next();
        }
        notifyDiv.html(self.getNotifyNewContent());
        return notifyDiv;
    };
    this.showLoaderOnInput = function (input) {
        input.val(loaderPropertyValueMessage);
    };
    this.hideLoaderOnInput = function (input) {
        input.val("");
    };
    this.showErrorOnInput = function (input,error) {
        input.val(error);
    };
    this.hideErrorOnInput = function (input) {
        //input.val(error);
    };
    this.getLoaderDiv = function (notifyDiv) {
        return notifyDiv.find("#loaderProperties");
    };
    this.getErrorsDiv = function (notifyDiv) {
        return notifyDiv.find("#errorsProperties");
    };
    this.getNotifyNewContent = function () {

        return "<div id=\"loaderProperties\"></div></div><div id=\"errorsProperties\"></div>";
    };
    this.showLoader = function (divLoader) {
        divLoader.html(getLoaderContent(loaderPropertiesMessage));
    };
    this.hideLoader = function (divLoader) {
        divLoader.html("");
    };
    this.displayErrorOnDiv = function (divErrors, errors) {
        /*var htmlContent = "<div class=\"alert alert-danger\">";
        htmlContent += "<ol>";
        for (i in errors)
        {
            htmlContent += "<li>" + errors[i] + "</li>";
        }
        htmlContent += "</ol>";
        htmlContent += "</div>";
        divErrors.html(htmlContent);*/
    	displayErrorOnDiv (divErrors, errors, false)
    };
    this.getData = function (result) {
        result.MENUPRO_PROPERTIES_COUNT = $("#MENUPRO_PROPERTIES_COUNT").val();
        var i = 0;
        self.divProperties.find(".property-block").each(function () {
            result["MENUPRO_PROPERTY_ID_" + i] = $(this).find(".property-id").val();
            result["MENUPRO_PROPERTY_USABLE_VALUE_" + i] = $(this).find(".usable-values").val();
            result["MENUPRO_PROPERTY_VALUE_" + i] = $(this).find(".property-value").val();
            result["MENUPRO_PROPERTY_DISPLAY_NAME_" + i] = $(this).find(".property-display-name").val();
            result["MENUPRO_PROPERTY_DEFAULT_VALUE_" + i] = $(this).find(".property-default-value").val();
            result["MENUPRO_PROPERTY_ID_BASE_" + i] = $(this).find(".property-id-base").val();
            result["MENUPRO_PROPERTY_TYPE_" + i] = $(this).find(".property-type").val();
            i++;
        });
        return result;
    };
    this.enableDivProperties = function () {
        if(self.propertiesLoaded){
            self.divProperties.find(".usable-values").each(function () {
                $(this).prop("disabled", false);
                self.onUsableValueChange($(this));
            });
        }
        
    };
    this.disableDivProperties = function () {
        if(self.propertiesLoaded){
            self.divProperties.find("input,select").prop("disabled", true);
        }
    };
    this.setColorFieldsBackground = function () {
        self.divProperties.find(".div-property-value>.mColorPickerInput").each(function () {
            self.setColorFieldBackground($(this));
        });
    };
    this.setColorFieldBackground = function (target) {
    	if (ps_version<'1.6'){
    		var fieldDisabled = target.is(':disabled');
    		if(fieldDisabled){
    			target.prop("disabled", false);
    		}
    		target.keyup();
    		if(fieldDisabled){
    			target.prop("disabled", true);
    		}
    	}else{
    		target.keyup();
    	}
    };
}