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

function ItemSelectionManager(secondaryMenuManager) {
    var self = this;
    this.modalSelectItems = $("#modalSelectItems");
    if(ps_version>='1.6'){
    	this.modalSelectItemsTitle = $("#modalSelectItemsTitle");
    }
    this.selectItemAccordion = this.modalSelectItems.find("#selectItemAccordion");
    this.divSelectItemNotify = this.modalSelectItems.find("#divSelectItemNotify");
    this.btnSelectItems = this.modalSelectItems.find("#btnSelectItems");
    this.divCurrentMenuTree = null;
    this.itemManagerList = [];
    this.parentMenuId = 0;
    this.onReady = function () {
        for (var key in availableSecondaryMenuTypeConst) {
            var value = availableSecondaryMenuTypeConst[key];
            self.itemManagerList[value] = new SecondaryMenuTypeManager(value, self.selectItemAccordion);
            self.itemManagerList[value].onReady();
        }
        self.handleEvent();
    };
    this.handleEvent = function () {
    	var dialogOpenEvent = (ps_version>='1.6')?"shown.bs.modal":"dialogopen";
    	var dialogCloseEvent = (ps_version>='1.6')?"hidden.bs.modal":"dialogclose";
        self.modalSelectItems.on(dialogOpenEvent, function () {
            setModalMaxHeight(self.modalSelectItems);
            self.selectItemAccordion.find(".collapse.in").each(function (event) {
                var target = $(this);
                self.onCollapseOpen(target);
            });
        });
        self.selectItemAccordion.find(".collapse").on("shown.bs.collapse", function (event) {
            var target = $(event.target);
            self.onCollapseOpen(target);
        });
        if(ps_version>='1.6'){
        	self.btnSelectItems.on("click", function () {
                self.onSelectedClick();
            });
    	}
        self.modalSelectItems.on(dialogCloseEvent, function (e) {
            self.onModalClosed();
        });
    };
    this.onSelectedClick = function () {
        var selectedItems = [];
        for (var key in availableSecondaryMenuTypeConst) {
            var value = availableSecondaryMenuTypeConst[key];
            if ((self.itemManagerList[value] != undefined) && (self.itemManagerList[value] != null)) {
                selectedItems = $.merge(selectedItems, self.itemManagerList[value].itemsSelector.getSelected());
            }
        }
        self.processAddItems(selectedItems);
    };
    this.onCollapseOpen = function (target) {
        var type = target.attr("data-id");
        var emptyListMessage = target.attr("data-empty-message");
        self.itemManagerList[type].initialize(emptyListMessage);
    };
    this.processAddItems = function (items) {
        self.showLoader();
        $.ajax({
            url: ajaxModuleUrl,
            data: {
                ajax: true,
                action: "AddMenuItems",
                MENUPRO_ITEMS_SELECTION_SELECTED: items,
                MENUPRO_ITEMS_SELECTION_PARENT_MENU_ID: self.parentMenuId,
                MENUPRO_ITEMS_SELECTION_MAIN_MENU_ID: mainMenu.id
            },
            success: function (result) {
                self.hideLoader();
                if (result['status'] == 'success') {
                    var size = result['data']['menus'].length;
                    var isFirst = false;
                    var isLast = false;
                    var needLast = false;
                    var divMenuTree = self.divCurrentMenuTree.find("#accordionMenu" + self.parentMenuId);
                    if (divMenuTree.length == 0) {
                        divMenuTree = self.divCurrentMenuTree;
                        isFirst = true;
                        needLast = true;
                    }
                    if(parseInt(self.parentMenuId)>0){
                    	 if(ps_version>='1.6'){
                    		 self.divCurrentMenuTree.closest("#collapseMenu" + self.parentMenuId).collapse("show");
                    	 }else{
                    		 self.divCurrentMenuTree.closest("#collapseMenu" + self.parentMenuId).addClass("in");
                    	 }
                    	
                    }
                    var htmlContent=divMenuTree.html();
                    for (i = 0; i < size; i++) {
                        isLast = (needLast) && (i == (size - 1));
                        htmlContent+= self.getMenuContent(result['data']['menus'][i], isFirst, isLast);
                        isFirst = false;
                    }
                    divMenuTree.html(htmlContent);
                    secondaryMenuManager.onItemsAdded(result['data']);
                    self.closeModal();
                } else {
                    displayErrorOnDiv(self.divSelectItemNotify, result['data']);
                }
            },
            error: function () {
                self.hideLoader();
                displayErrorOnDiv(self.divSelectItemNotify, [ajaxRequestErrorMessage]);
            },
            type: 'post',
        });
    };
    this.displaySelection = function (menuType, $menuName, menuTree, parentMenuId) {
        self.parentMenuId = parentMenuId;
        self.divCurrentMenuTree = menuTree;
        if(ps_version>='1.6'){
        	self.modalSelectItems.modal({
                backdrop: 'static',
                keyboard: true,
                show: true
            });
        }else{
        	self.modalSelectItems.dialog({
        		modal: true,
        		width : "80%",
                buttons: [
                    {
                      text: btnSelectItemCloseText,
                      icon: "ui-icon-heart",
                      "class" : "btn-dialog dialog-close",
                      click: function() {
                    	  self.closeModal();
                      }
                    },
                    {
                        text: btnSelectItemSaveText,
                        icon: "ui-icon-heart",
                        id: "btnSelectItems",
                        "class" : "btn-dialog dialog-ok btn-select-items",
                        click: function() {
                        	self.onSelectedClick();
                        }
                      }
                  ],
                  
    		});
        	this.modalSelectItemsTitle = this.modalSelectItems.prev(".ui-dialog-titlebar").find(".ui-dialog-title:first");
        }
        self.modalSelectItemsTitle.html(modalSelecItemTitlePrefix + " " + $menuName);
        self.hideLoader();
    };
    this.unselectAll = function () {
        for (var key in availableSecondaryMenuTypeConst) {
            var value = availableSecondaryMenuTypeConst[key];
            if ((self.itemManagerList[value] != undefined) && (self.itemManagerList[value] != null)) {
                self.itemManagerList[value].itemsUnSelector.unSelect();
            }
        }
    };
    this.onModalClosed = function () {
        self.unselectAll();
    };
    this.showLoader = function () {
        showModalLoader(AddItemMessage);
    };
    this.hideLoader = function () {
        hideModalLoader();
    };
    this.closeModal = function () {
    	if(ps_version>='1.6'){
    		self.modalSelectItems.modal('hide');
    	}else{
    		self.modalSelectItems.dialog("close");
    	}
    };
    this.getMenuContent = function (menuItem, isFirst, isLast) {
        var htmlContent = '';
        if (isFirst) {
        	var groupClass = (ps_version>="1.6")?"panel-group":"";
            htmlContent += '<div class="collapse-group '+groupClass+'" id="accordionMenu' + menuItem.parent_menu + '" role="tablist" aria-multiselectable="true">'
        }
        var itemClass = (ps_version>="1.6")?"panel-mp panel panel-default":"clearfix";
        if(ps_version>="1.6"){
        	var addContent = '<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="'+addSubmenusTitle+'" data-html="true" data-placement="top">'+
                '<i class="process-icon-new"></i>'+
            '</span>';
        	var updateContent = '<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="'+editSubmenuTitle+'" data-html="true" data-placement="top">'+
                '<i class="process-icon-edit"></i>'+
            '</span>';
    		var deleteContent = '<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="'+deleteSubmenuTitle+'" data-html="true" data-placement="top">'+
            '<i class="process-icon-delete"></i>'+
            '</span>';
        }else{
        	var addContent = '<img src="../img/admin/add.gif" alt="'+addSubmenusTitle+'">';
	    	var updateContent = '<img src="../img/admin/edit.gif" alt="'+editSubmenuTitle+'">';
			var deleteContent = '<img src="../img/admin/delete.gif" alt="'+deleteSubmenuTitle+'">';
        }
        var headClass = (ps_version>="1.6")?"panel-heading-mp panel-heading":"clearfix";
        htmlContent += '<div id="newMenuPanel' + menuItem.id + '" class="collapse-item '+itemClass+' new-menu-panel">' +
                '<div class="collapse-head '+headClass+'" role="tab" id="headingMenu' + menuItem.id + '">' +
                '<h4 class="panel-title col-lg-10 col-md-9 col-sm-8 col-xs-7 panel-title-mp">' +
				'<a class="collapse-action link-head-mp" data-toggle="collapse" role="button" aria-expanded="true" data-parent="#accordionMenu' +
                menuItem.parent_menu + '" href="#collapseMenu' + menuItem.id + '" aria-controls="collapseMenu' + menuItem.id + '">' +
					menuItem.name +
    '</a>'+
    '</h4>'+
    '<div id="menuAction' + menuItem.id + '" class="menu-action pull-right col-lg-2 col-md-3 col-sm-4 col-xs-5" data-id="' +
        menuItem.id + '"' +
        'data-position="' + menuItem.position + '" data-level="' + menuItem.level + '"' +
       'data-name="' + menuItem.name + '" data-item-type="' + menuItem.item_type + '">' +
        '<a title="'+addSubmenusTitle+'" href="#" class="add-submenus pull-right">'+addContent+'</a>'+
        '<a title="'+editSubmenuTitle+'" href="#" class="edit-submenu pull-right">'+updateContent+'</a>'+
        '<a title="' + deleteSubmenuTitle + '" href="#" class="delete-submenu pull-right">' +deleteContent+'</a>' +
        '<div id="divMenuStatus' + menuItem.id + '" class="status-change-submenu pull-right">' +
         getStatusDivContent(menuItem.active) +
        '</div>' +
    '</div>'+
'</div>'+
'<div role="tabpanel" class="collapse-target panel-collapse collapse" id="collapseMenu' + menuItem.id + '" aria-labelledby="headingMenu' + menuItem.id + '">' +
    '<div id="newMenuPanelBody' + menuItem.id + '" class="panel-body new-menu-panel-body">' +
    '</div>' +
		'</div>' +
	'</div>';
        if (isLast) {
            htmlContent += '</div>'
        }
        return htmlContent;
    };
}

function getSelectedFromList(list, itemType) {
    var selected = [];
    for (i in list) {
        if (list[i].isSelected) {
            var item = { id: list[i].id, type: itemType };
            selected.push(item);
        }
    }
    return selected;
}
function unSelectList(list) {
    for (i in list) {
        list[i].isSelected = false;
    }
    return list;
}