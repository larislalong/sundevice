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

function MainMenuManager() {
    var self = this;
    this.btnSaveAndStay = $(".btnSaveAndStayMenu");
    this.idMenu = $("#id_menupro_main_menu").val();
    this.txtHook = $("#MENUPRO_MAIN_MENU_HOOK");
    this.txtMenuType = $("#MENUPRO_MAIN_MENU_TYPE");
    this.radioSeachBar=$("input[name='MENUPRO_MAIN_MENU_SHOW_SEARCH_BAR']");
    var parentClass = (ps_version>="1.6")?"form-group":"margin-form";
    this.divSeachBar=this.radioSeachBar.closest("."+parentClass);
    this.divMainMenuType = this.txtMenuType.closest("."+parentClass);
    this.hookChangeByEvent=false;
    this.onReady = function () {
        self.addMenuPreviewContent();
        self.handleEvent();
        manageTabs(self);
        if (self.idMenu != "0") {
            self.styleManager = new StyleManager(self.idMenu);
            self.styleManager.onReady();
        }
        self.onHookChange();
    };
    
    this.handleEvent = function () {
        self.txtMenuType.on("change", function () {
            self.changeImagePreview();
        });
        self.btnSaveAndStay.on("click", function () {
            $("#submitSaveAndStay").val("1");
        });
        self.txtHook.on("change", function () {
        	self.hookChangeByEvent=true;
        	self.onHookChange();
        });
    };
    this.onHookChange = function () {
    	var hook=self.getIdHookForPreference();
    	if(self.hookChangeByEvent){
    		self.txtMenuType.empty();
        	for(i in hookPreferences[hook].menu_type_list){
        		var htmlContent='<option value="'+i+'">'+hookPreferences[hook].menu_type_list[i].label+'</option>';
        		self.txtMenuType.append(htmlContent);
        	}
        	self.txtMenuType.val(hookPreferences[hook].preferred_menu_type);
        	self.changeImagePreview();
    	}
    	if(hookPreferences[hook].need_search_bar){
    		self.showDivSearch();
    	}else{
    		self.hideDivSearch();
    	}
    	if(hookPreferences[hook].need_menu_type){
    		self.showDivMenuType();
    	}else{
    		self.hideDivMenuType();
    	}
    };
    this.getIdHookForPreference = function () {
    	var hook=parseInt(self.txtHook.val());
    	if((hookPreferences[hook] == undefined) && (hookPreferences[hook] == null)){
    		hook=0;
    	}
    	return hook;
    }
    this.showDivMenuType = function () {
    	self.divMainMenuType.show();
    }
    this.hideDivMenuType = function () {
    	self.divMainMenuType.hide();
    }
    
    this.showDivSearch = function () {
    	self.divSeachBar.show();
    }
    this.hideDivSearch = function () {
    	self.divSeachBar.hide();
    }
    this.changeImagePreview = function () {
        self.imgMenuType.attr("src",self.getImagePreviewSource());
    };
    this.getImagePreviewSource = function () {
        var mainMenuType = self.txtMenuType.val();
        var hook=self.getIdHookForPreference();
        return imageMenuTypeFolder + hookPreferences[hook].menu_type_list[mainMenuType].image_file_name;
    };
    this.addMenuPreviewContent = function () {
    	self.divMainMenuType.append(self.getMenuPreviewContent(self.getImagePreviewSource()));
    	self.imgMenuType=self.divMainMenuType.find("#imgMenuType");
    };
    this.getMenuPreviewContent = function (source) {
        return '<div class="col-lg-6">' +
            '<img class="mp-preview" id="imgMenuType" src="'+source+'" class="item-img " title="" alt="preview"/>'
        '</div>';
    };
    this.onTabChange = function (nav) {
        if (self.idMenu != "0") {
            self.styleManager.onTabChange(nav);
        }
    };
}