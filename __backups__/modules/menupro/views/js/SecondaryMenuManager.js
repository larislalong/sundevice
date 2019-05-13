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

function SecondaryMenuManager() {
    var self = this;
    this.btnMainMenuAddSubMenu = $("#btnMainMenuAddSubMenu");
    this.itemSelectionManager;
    this.secondaryMenuTree = $("#secondaryMenuTree");
    this.divSecondaryMenuNotify = $("#divSecondaryMenuNotify");
    this.divSecondaryMenuEditionParent = $("#divSecondaryMenuEditionParent");
    this.onReady = function () {
        self.handleEvent();
        self.itemSelectionManager = new ItemSelectionManager(self);
        self.secondaryMenuSortManager=new SecondaryMenuSortManager(self.secondaryMenuTree);
        self.secondaryMenuSortManager.onReady();
        self.itemSelectionManager.onReady();
    };
    this.handleEvent = function () {
        self.btnMainMenuAddSubMenu.on("click", function () {
            self.displaySelectItemsForm(menuTypesConst.MAIN, mainMenu.name, self.secondaryMenuTree,0);
        });
        self.secondaryMenuTree.delegate('.add-submenus', 'click', function (event) {
            event.preventDefault();
            var target = $(this);
            var divAction = self.getActionDiv(target);
            var id = divAction.attr("data-id");
            var menuTree = divAction.closest("#newMenuPanel" + id).find("#collapseMenu" + id).find("#newMenuPanelBody" + id);
            self.displaySelectItemsForm(menuTypesConst.SECONDARY, divAction.attr("data-name"), menuTree, id);
        });
        self.secondaryMenuTree.delegate('.edit-submenu', 'click', function (event) {
            event.preventDefault();
            var target = $(this);
            var divAction = self.getActionDiv(target);
            var id = divAction.attr("data-id");
            self.loadEditionForm(id, divAction);
        });
        self.secondaryMenuTree.delegate('.delete-submenu', 'click', function (event) {
            event.preventDefault();
            var target = $(this);
            var divAction = self.getActionDiv(target);
            var id = divAction.attr("data-id");
            self.processDeleteMenu(id, divAction.attr("data-name"), divAction);
        });
        self.secondaryMenuTree.delegate('.status-change-submenu', 'click', function (event) {
            event.preventDefault();
            var target = $(this);
            var divAction = self.getActionDiv(target);
            var id = divAction.attr("data-id");
            self.processStatusChange(id, divAction);
        });
        self.handleCollapsibleToggleIcon();
    };
    this.displaySelectItemsForm = function (menuType, menuName, menuTree, parentMenuId) {
    	self.hideNotifyDiv();
        self.itemSelectionManager.displaySelection(menuType, menuName, menuTree, parentMenuId);
    };
    this.getActionDiv = function (target) {
        return target.closest(".menu-action");
    };
    this.onTabChange = function (nav) {
        if (self.idMenu != "0") {
            self.styleManager.onTabChange(nav);
        }
    };
    this.handleCollapsibleToggleIcon = function () {
        $('.panel-group').on('hidden.bs.collapse', self.toggleIcon);
        $('.panel-group').on('shown.bs.collapse', self.toggleIcon);
    };
    this.toggleIcon=function (e) {
        $(e.target)
            .prev('.panel-heading-mp')
            .find(".link-head-mp >span")
            .toggleClass('icon-plus icon-minus');
    };
    this.loadEditionForm = function (idMenu, divAction) {
    	var itemType=divAction.attr("data-item-type");
        showModalLoader(loaderSecondaryMenuEditionFormMessage);
        self.hideNotifyDiv();
        $.ajax({
            url: ajaxModuleUrl,
            data: {
                ajax: true,
                action: "GetSecondaryMenuEditionForm",
                MENUPRO_SECONDARY_MENU_ID: idMenu,
            },
            success: function (result) {
                hideModalLoader();
                if (result['status'] == 'success') {
					if(ps_version>='1.5.6'){
						self.divSecondaryMenuEditionParent.html(result['data']['form']);
					}else{
						var contentHtml = "";
						$(result['data']['form']).each(function(event) {
							contentHtml = $(this)[0].outerHTML;
							try{
								self.divSecondaryMenuEditionParent.append(contentHtml);
							}catch(e){}
						});
					}
                    
                    var divFormEdition = self.divSecondaryMenuEditionParent.find("#modalEditSecondaryMenu");
                    
                    var editionFormManager = new SecondaryMenuEditionManager(divFormEdition, idMenu, itemType, divAction);
                    editionFormManager.onReady();
                } else {
                    self.showNotifyDiv();
                    displayErrorOnDiv(self.divSecondaryMenuNotify, result['data']);
                }
            },
            error: function () {
                hideModalLoader();
                self.showNotifyDiv();
                displayErrorOnDiv(self.divSecondaryMenuNotify, [ajaxRequestErrorMessage]);
            },
            type: 'post',
        });
    };
    this.processDeleteMenu = function (idMenu, name, divAction) {
        if (confirm(confirmDeleteSecondaryMenuMessage + " " + name)) {
            showModalLoader(loaderDeleteSecondaryMenuMessage);
            self.hideNotifyDiv();
            $.ajax({
                url: ajaxModuleUrl,
                data: {
                    ajax: true,
                    action: "DeleteSecondaryMenu",
                    MENUPRO_SECONDARY_MENU_ID: idMenu,
                },
                success: function (result) {
                    hideModalLoader();
                    self.showNotifyDiv();
                    if (result['status'] == 'success') {
                        var panelMenu = divAction.closest("#newMenuPanel" + idMenu);
                        var parentDiv = panelMenu.closest("#accordionMenu" + result['data']['parent_menu']);
                        panelMenu.remove();
                        self.updateBrothersPosition(result['data']['brothers'], parentDiv);
                        displaySuccessOnDiv(self.divSecondaryMenuNotify, result['data']['message']);
                        self.secondaryMenuSortManager.showHideSortButton();
                    } else {
                        displayErrorOnDiv(self.divSecondaryMenuNotify, result['data']);
                    }
                },
                error: function () {
                    hideModalLoader();
                    self.showNotifyDiv();
                    displayErrorOnDiv(self.divSecondaryMenuNotify, [ajaxRequestErrorMessage]);
                },
                type: 'post',
            });
        }
    };
    this.processStatusChange = function (idMenu, divAction) {
        showModalLoader(loaderStatusChangeSecondaryMenuMessage);
        self.hideNotifyDiv();
        $.ajax({
            url: ajaxModuleUrl,
            data: {
                ajax: true,
                action: "StatusChangeSecondaryMenu",
                MENUPRO_SECONDARY_MENU_ID: idMenu,
            },
            success: function (result) {
                hideModalLoader();
                self.showNotifyDiv();
                if (result['status'] == 'success') {
                    divAction.find("#divMenuStatus" + idMenu).html(getStatusDivContent(result['data']['active']));
                    displaySuccessOnDiv(self.divSecondaryMenuNotify, result['data']['message']);
                } else {
                    displayErrorOnDiv(self.divSecondaryMenuNotify, result['data']);
                }
            },
            error: function () {
                hideModalLoader();
                self.showNotifyDiv();
                displayErrorOnDiv(self.divSecondaryMenuNotify, [ajaxRequestErrorMessage]);
            },
            type: 'post',
        });
    };
    this.updateBrothersPosition = function (data, parentDiv) {
        for (i in data) {
            parentDiv.find("#newMenuPanel" + data[i].id).find("#menuAction" + data[i].id).attr("data-position", data[i].position);
        }
    };
    this.onItemsAdded= function (data){
    	self.showNotifyDiv();
    	displaySuccessOnDiv(self.divSecondaryMenuNotify, data['message']);
        self.secondaryMenuSortManager.showHideSortButton();
    };
    this.showNotifyDiv= function (){
        self.divSecondaryMenuNotify.show();
        self.divSecondaryMenuNotify.html("");
    };
    this.hideNotifyDiv = function () {
        self.divSecondaryMenuNotify.hide();
        self.divSecondaryMenuNotify.html("");
    };
}