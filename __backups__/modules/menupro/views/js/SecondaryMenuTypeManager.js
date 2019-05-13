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

function SecondaryMenuTypeManager(type, selectItemAccordion) {
    var self = this;
    const MAX_PAGINATION_PAGE_DISPLAYED = 5;
    this.currentFistPageIndex = 0;
    this.currentLastPageIndex = 0;
    this.currentPageIndex = 0;
    this.itemType = type;
    this.emptyListMessage = '';
    this.isListInitialized = false;
    this.isSearchInitialized = false;
    this.showList = true;
    this.showSearch = true;
    this.itemsCount = 0;
    this.pagesCount = 0;
    this.searchMethod = searchMethodConst.BY_NAME;
    this.divItem = null;
    this.tabList = null;
    this.tabListContent = null;
    this.tabListNotify = null;

    this.tabSearch = null;
    this.divSpecifiedItems = null;
    this.divSearchType = null;
    this.lblSearchInput = null;
    this.itemAutocompleteInput = null;
    this.itemAutocompleteIcon = null;
    this.divSearchChosenItems = null;

    this.divItemCurrentPage = null;
    this.divItemCurrentPageContent = null;
    this.divItemCurrentPageErrors = null;
    this.divItemPagination = null;

    this.selectItemGoTo = null;
    this.btnItemPaginatePrevious = null;
    this.btnItemPaginateScrollLeft = null;
    this.divPaginationItemsNumbers = null;
    this.btnItemPaginateNext = null;
    this.btnItemPaginateScrollRight = null;
    this.lblCurrentPage = null;
    this.lblPagesCount = null;
    this.divItemCurrentPage = null;
    this.divItemCurrentPageContent = null;
    this.divItemCurrentPageNotify = null;

    this.divCustomizeItem = null;
    this.ckbCustomizeItem = null;
    this.divCustomizeItemQuantity = null;
    this.txtCustomizeItemQuantity = null;
    this.btnCustomizeItemMinus = null;
    this.btnCustomizeItemPlus = null;

    this.searchItemsIds = [];
    this.searchItemsList = [];
    this.pageManagerList = [];
    this.listInitializer = new function () {
        this.initialize = function () {
            self.initializeList();
        }
    }
    this.itemsSelector = new function () {
        this.getSelected = function () {
            return $.merge(self.getPageItemsSelected(), self.getSearchItemsSelected());
        }
    }
    this.itemsUnSelector = new function () {
        this.unSelect = function () {
            self.unSelectPageItems();
            self.unSelectSearchItems();
        }
    }
    this.onReady = function () {
        if (self.itemType == availableSecondaryMenuTypeConst.CUSTOMISE) {
            self.isListInitialized = true;
            self.isSearchInitialized = true;
            self.showList = false;
            self.showSearch = false;
            self.divCustomizeItem = selectItemAccordion.find("#divCustomizeItem");
            self.ckbCustomizeItem = self.divCustomizeItem.find("#ckbCustomizeItem");
            self.divCustomizeItemQuantity = self.divCustomizeItem.find("#divCustomizeItemQuantity");
            self.txtCustomizeItemQuantity = self.divCustomizeItemQuantity.find("#txtCustomizeItemQuantity");
            self.btnCustomizeItemMinus = self.divCustomizeItemQuantity.find("#btnCustomizeItemMinus");
            self.btnCustomizeItemPlus = self.divCustomizeItemQuantity.find("#btnCustomizeItemPlus");
            self.initializeCustomizeSelection();
            self.itemsSelector = new function () {
                this.getSelected = function () {
                    return self.getSelectedCustomized();
                }
            }
            self.itemsUnSelector = new function () {
                this.unSelect = function () {
                    self.unSelectCustomized();
                }
            }
        } else {
            self.divItems = selectItemAccordion.find("#divItems_" + self.itemType);
            self.tabList = self.divItems.find("#tabList_" + self.itemType);
            self.tabSearch = self.divItems.find("#tabSearch_" + self.itemType);
            self.tabListContent = self.tabList.find("#tabListContent_" + self.itemType);
            self.tabListNotify = self.tabList.find("#tabListNotify_" + self.itemType);

            self.divSpecifiedItems = self.tabSearch.find("#divSpecifiedItems_" + self.itemType);
            self.divSearchType = self.tabSearch.find("#divSearchType_" + self.itemType);
            self.lblSearchInput = self.divSpecifiedItems.find("#lblSearchInput_" + self.itemType);
            self.itemAutocompleteInput = self.divSpecifiedItems.find("#itemAutocompleteInput_" + self.itemType);
            self.itemAutocompleteIcon = self.divSpecifiedItems.find("#itemAutocompleteIcon_" + self.itemType);
            self.divSearchChosenItems = self.divSpecifiedItems.find("#divSearchChosenItems_" + self.itemType);

            self.tabListContent.hide();
            self.tabListNotify.hide();
            if (self.itemType == availableSecondaryMenuTypeConst.CATEGORY) {
                self.listInitializer = new function () {
                    this.initialize = function () {
                        self.initializeListCategory();
                    }
                }
                self.itemsSelector = new function () {
                    this.getSelected = function () {
                        return $.merge(self.getSelectedCategories(), self.getSearchItemsSelected());
                    }
                }
                self.itemsUnSelector = new function () {
                    this.unSelect = function () {
                        self.unSelectCategories();
                        self.unSelectSearchItems();
                    }
                }
            } else {
                self.divItemCurrentPage = self.tabListContent.find("#divItemCurrentPage_" + self.itemType);
                self.divItemCurrentPageContent = self.divItemCurrentPage.find("#divItemCurrentPageContent_" + self.itemType);
                self.divItemCurrentPageErrors = self.divItemCurrentPage.find("#divItemCurrentPageErrors_" + self.itemType);
                self.divItemPagination = self.tabListContent.find("#divItemPagination_" + self.itemType);
                self.divItemCurrentPage.hide();
                self.divItemCurrentPageContent.hide();
                self.divItemPagination.hide();
                self.divItemCurrentPage.hide();
            }
        }
    };
    this.handleSearchEvent = function () {
        var optionName = "optionSearchBy_" + self.itemType;
        self.divSearchType.find("input[name='" + optionName + "']").on("change", function (event) {
            var target = $(event.target);
            self.searchMethod = target.attr("data-type");
            self.lblSearchInput.html(target.attr("data-name"));
            self.resetAutocompleteOptions();
        });
        self.initAutocomplete();
        self.divSearchChosenItems.delegate('.delSearchItem', 'click', function () {
            self.deleteSearchItem($(this).attr('data-id'));
        });
        self.divSearchChosenItems.delegate('.search-item', 'change', function (event) {
            var target = $(this);
            var checked = target.is(':checked');
            var index = self.getSearchItemIndexById(target.attr("data-id"));
            self.searchItemsList[index].isSelected = checked;
            if (checked) {
                target.closest(".mp-selectable-item").addClass("mp-item-selected");
            } else {
                target.closest(".mp-selectable-item").removeClass("mp-item-selected");
            }
        });
    };
    this.hasLeftPagesNotDisplayed = function () {
        return self.currentFistPageIndex > 1;
    };
    this.hasRightPagesNotDisplayed = function () {
        return self.currentLastPageIndex < self.pagesCount;
    };
    this.hasPreviousItems = function () {
        return self.currentPageIndex > 1;
    };
    this.hasNextItems = function () {
        return self.currentPageIndex < self.pagesCount;
    };
    this.showHideNext = function () {
        if (self.hasNextItems()) {
            self.btnItemPaginateNext.show();
        } else {
            self.btnItemPaginateNext.hide();
        }
    };
    this.showHidePrevious = function () {
        if (self.hasPreviousItems()) {
            self.btnItemPaginatePrevious.show();
        } else {
            self.btnItemPaginatePrevious.hide();
        }
    };
    this.showHideScrollLeft = function () {
        if (self.hasLeftPagesNotDisplayed()) {
            self.btnItemPaginateScrollLeft.show();
        } else {
            self.btnItemPaginateScrollLeft.hide();
        }
    };
    this.showHideScrollRight = function () {
        if (self.hasRightPagesNotDisplayed()) {
            self.btnItemPaginateScrollRight.show();
        } else {
            self.btnItemPaginateScrollRight.hide();
        }
    };
    this.initAutocomplete = function () {
        var actionUrl = ajaxModuleUrl + "&ajax&action=SearchAttachableItems&MENUPRO_ITEMS_SELECTION_ITEM_TYPE=" + self.itemType;
        self.itemAutocompleteInput
                .autocomplete(actionUrl, {
                    minChars: 1,
                    autoFill: true,
                    max: 20,
                    matchContains: true,
                    mustMatch: false,
                    scroll: false,
                    loadingClass:"autocomplete-loader",
                    cacheLength: 0,
                    formatItem: function (item) {
                        return item[1] + ' - ' + item[0];
                    },
                }).result(self.addSearchItem);
        self.resetAutocompleteOptions();
    };
    this.resetAutocompleteOptions = function () {
        self.itemAutocompleteInput.setOptions({
            extraParams: {
                excludeIds: self.getSearchItemsIdsAsString(),
                MENUPRO_ITEMS_SELECTION_SEARCH_TYPE: self.searchMethod
            }
        });
    }
    this.getSearchItemsIdsAsString = function () {
        return self.searchItemsIds.join(',');
    }
    this.addSearchItem = function (event, data, formatted) {
        if (data == null)
            return false;
        var dataId = data[1];
        var dataName = data[0];
        var index = self.searchItemsList.length;
        var item = { id: dataId, name: dataName, isSelected: true };
        self.searchItemsList.push(item);
        self.searchItemsIds.push(dataId);
        var deleteIcon = (ps_version>='1.6')?'<i class="icon-remove text-danger"></i>':
        	'<img src="../img/admin/delete.gif" alt="X">';
        var btnClass = (ps_version>='1.6')?'btn btn-default':'btn-search-delete';
        var blocClasses = (ps_version>='1.6')?'':'search-item-div clearfix';
        var htmlContent = '<div class="'+blocClasses+' form-control-static parentDivDelSearchItem">' +
            '<label class="checkbox-inline mp-selectable-item mp-item-selected"><input class="search-item" type="checkbox" value="' + dataId +
            '" data-id="' + dataId + '" checked>' + dataName + '</label>&nbsp;' +
            '<button type="button" class="'+btnClass+' delSearchItem button-for-enable" data-id="' + dataId +
            '" data-index="' + index + '">' +
            deleteIcon+'</button>' +
            '</div>';
        self.divSearchChosenItems.append(htmlContent);
        self.itemAutocompleteInput.val('');
        self.resetAutocompleteOptions();
    };

    this.deleteSearchItem = function (id) {
        self.divSearchChosenItems.find('div > button[data-id=' + id + ']').parent('.parentDivDelSearchItem').remove();
        var index = self.getSearchItemIndexById(id);
        if (index >= 0) {
            self.searchItemsIds.splice(index, 1);
            self.searchItemsList.splice(index, 1);
        }
        self.resetAutocompleteOptions();
    };
    this.getSearchItemIndexById = function (id) {
        return self.searchItemsIds.indexOf(id);
    };
    this.initialize = function (emptyListMessage) {
        if (self.showList && (!self.isListInitialized)) {
            self.emptyListMessage = emptyListMessage;
            self.tabListContent.hide();
            self.showLoader();
            self.listInitializer.initialize();
        }
        if (self.showSearch && (!self.isSearchInitialized)) {
            self.initializeSearch();
        }
    };
    this.getSelectedCategories = function () {
        var selected = [];
        var idTree = (ps_version>='1.6')?'categories-tree':'categories-treeview';
        var selectedSelector = (ps_version>='1.6')?'.tree-selected':'input:checked';
        self.tabListContent.find("#"+idTree).find(selectedSelector).each(function () {
            var target = $(this);
            var idItem = (ps_version>='1.6')?target.find("input").val():target.val();
            var item = { id: idItem, type: self.itemType };
            selected.push(item);
        });
        return selected;
    };

    this.getSearchItemsSelected = function () {
        return getSelectedFromList(self.searchItemsList, self.itemType);
    };
    this.getPageItemsSelected = function () {
        var selected = [];
        for (i in self.pageManagerList) {
            if ((self.pageManagerList[i] != undefined) && (self.pageManagerList[i] != null)) {
                selected = $.merge(selected, self.pageManagerList[i].getSelectedItems());
            }
        }
        return selected;
    };
    this.getSelectedCustomized = function () {
        var selected = [];
        if (self.ckbCustomizeItem.is(':checked')) {
        	var quantity=parseInt(self.txtCustomizeItemQuantity.val());
        	for(i=0;i<quantity;i++){
        		var item = { id: i, type: self.itemType };
                selected.push(item);
        	}
        }
        return selected;
    };
    this.unSelectCustomized = function () {
        self.ckbCustomizeItem.prop('checked', false);
        self.txtCustomizeItemQuantity.val("1");
        self.showDivCustomizeQuantity();
    	self.showHideBtnMinus();
        
    };
    this.unSelectCategories = function () {
    	var idTree = (ps_version>='1.6')?'categories-tree':'categories-treeview';
        var selectedSelector = (ps_version>='1.6')?'.tree-selected':'input:checked';
        self.tabListContent.find("#"+idTree).find(selectedSelector).each(function () {
            var target = $(this);
            if(ps_version>='1.6'){
            	target.find("input").prop('checked', false);
                target.removeClass("tree-selected");
            }else{
            	target.prop('checked', false);
            }
        });
    };
    this.unSelectSearchItems = function () {
        self.divSearchChosenItems.find(".search-item").each(function () {
            var target = $(this);
            target.prop('checked', false);
            target.closest(".mp-selectable-item").removeClass("mp-item-selected");
        });
        self.searchItemsList = unSelectList(self.searchItemsList);
    };
    this.unSelectPageItems = function () {
        for (i in self.pageManagerList) {
            if ((self.pageManagerList[i] != undefined) && (self.pageManagerList[i] != null)) {
                self.pageManagerList[i].unSelectPage();
            }
        }
    };
    this.initPagination = function () {
        self.selectItemGoTo = self.divItemPagination.find("#selectItemGoTo_" + self.itemType);
        self.btnItemPaginatePrevious = self.divItemPagination.find("#btnItemPaginatePrevious_" + self.itemType);
        self.btnItemPaginateScrollLeft = self.divItemPagination.find("#btnItemPaginateScrollLeft_" + self.itemType);
        self.divPaginationItemsNumbers = self.divItemPagination.find("#divPaginationItemsNumbers_" + self.itemType);
        self.btnItemPaginateNext = self.divItemPagination.find("#btnItemPaginateNext_" + self.itemType);
        self.btnItemPaginateScrollRight = self.divItemPagination.find("#btnItemPaginateScrollRight_" + self.itemType);
        self.divItemCurrentPage = self.tabListContent.find("#divItemCurrentPage_" + self.itemType);
        self.divItemCurrentPageContent = self.divItemCurrentPage.find("#divItemCurrentPageContent_" + self.itemType);
        self.divItemCurrentPageNotify = self.divItemCurrentPage.find("#divItemCurrentPageNotify_" + self.itemType);
        self.lblCurrentPage = self.divItemPagination.find("#lblCurrentPage_" + self.itemType);
        self.lblPagesCount = self.divItemPagination.find("#lblPagesCount_" + self.itemType);
        self.lblPagesCount.html(" " + self.pagesCount);
        self.divItemCurrentPage.show();
        self.currentPageIndex = 1;
        self.currentFistPageIndex = self.currentPageIndex;
        var pageIndex = 0;
        self.divPaginationItemsNumbers.html("");
        for (i = 0; i < self.pagesCount; i++) {
            var pageIndex = i + 1;
            self.selectItemGoTo.html(self.selectItemGoTo.html() + '<option value="' + pageIndex + '">' + pageIndex + '</option>');
            if (i < MAX_PAGINATION_PAGE_DISPLAYED) {
                self.currentLastPageIndex = pageIndex;
                self.divPaginationItemsNumbers.html(self.divPaginationItemsNumbers.html() + self.getPaginationLinkContent(pageIndex));
            }
        }
        self.changeCurrentPage(self.currentPageIndex);
        self.showHideScrollLeft();
        self.showHideScrollRight();
        self.divPaginationItemsNumbers.delegate('.item-pagination-link', 'click', function (event) {
            var target = $(this);
            var index = target.attr('data-id');
            self.changeCurrentPage(index);
            event.preventDefault();
        });
        self.divItemCurrentPageContent.delegate('.page-item', 'change', function (event) {
            var target = $(this);
            var checked = target.is(':checked');
            var index = parseInt(target.attr("data-index"));
            if (self.isCurrentPageInstanciate()) {
                self.pageManagerList[self.currentPageIndex].itemsList[index].isSelected = checked;
            }
            if (checked) {
                target.closest(".mp-selectable-item").addClass("mp-item-selected");
            } else {
                target.closest(".mp-selectable-item").removeClass("mp-item-selected");
            }
        });
        self.btnItemPaginateScrollRight.on('click', function (event) {
            self.scrollPagination(self.currentLastPageIndex + 1);
            event.preventDefault();
        });
        self.btnItemPaginateScrollLeft.on('click', function (event) {
            self.scrollPagination(self.currentFistPageIndex - MAX_PAGINATION_PAGE_DISPLAYED);
            event.preventDefault();
        });
        self.btnItemPaginateNext.on('click', function (event) {
            var nextIndex = self.currentPageIndex + 1;
            self.changeCurrentPage(nextIndex);
            event.preventDefault();
        });
        self.btnItemPaginatePrevious.on('click', function (event) {
            var prevIndex = self.currentPageIndex - 1;
            self.changeCurrentPage(prevIndex);
            event.preventDefault();
        });
        self.selectItemGoTo.on('change', function () {
            var selectedIndex = self.selectItemGoTo.val();
            if (self.currentPageIndex != selectedIndex) {
                self.changeCurrentPage(selectedIndex);
            }
        });
    };
    this.scrollPagination = function (firstIndex) {
        if (firstIndex <= 0) {
            firstIndex = 1;
        }
        self.currentFistPageIndex = firstIndex;
        if (firstIndex <= self.pagesCount) {
            self.divPaginationItemsNumbers.html("");
            for (i = 0; i < MAX_PAGINATION_PAGE_DISPLAYED; i++) {
                var pageIndex = firstIndex + i;
                self.divPaginationItemsNumbers.html(self.divPaginationItemsNumbers.html() + self.getPaginationLinkContent(pageIndex));
                if (pageIndex >= self.pagesCount) {
                    break;
                }
            }
            self.currentLastPageIndex = pageIndex;
        } else {
            self.currentLastPageIndex = self.pagesCount;
        }
        self.activeCurrentPage();
        self.showHideScrollLeft();
        self.showHideScrollRight();
    };
    this.isPageDisplayed = function (index) {
        return (index >= self.currentFistPageIndex) && (index <= self.currentLastPageIndex);
    };
    this.changeCurrentPage = function (index) {
        index = parseInt(index);
        self.currentPageIndex = index;
        self.lblCurrentPage.html(" " + self.currentPageIndex + " ");
        if (!self.isPageDisplayed(index)) {
            self.scrollPagination(index);
        }
        self.divPaginationItemsNumbers.find(".item-pagination").removeClass("active");
        self.activeCurrentPage();
        if (!self.isCurrentPageInstanciate()) {
            self.pageManagerList[self.currentPageIndex] = new PageManager(self);
        }
        self.pageManagerList[self.currentPageIndex].displayPage();
        self.showHidePrevious();
        self.showHideNext();
        self.changeCurrentGoToIndex();
    };
    this.activeCurrentPage = function () {
        self.divPaginationItemsNumbers.find("#liPage_" + self.currentPageIndex).addClass("active");
    };
    this.changeCurrentGoToIndex = function () {
        if (self.selectItemGoTo.val() != self.currentPageIndex) {
            self.selectItemGoTo.val(self.currentPageIndex);
        }
    };
    this.getPaginationLinkContent = function (index) {
        return '<li id="liPage_' + index + '" class="item-pagination"><a class="item-pagination-link" href="#" data-id="' + index + '" id="itemPage_' + index + '">' + index + '</a></li>';
    };
    this.initializeList = function () {
        $.ajax({
            url: ajaxModuleUrl,
            data: {
                ajax: true,
                action: "getAttachableItemsCount",
                MENUPRO_ITEMS_SELECTION_ITEM_TYPE: self.itemType
            },
            success: function (result) {
                self.hideLoader();
                if (result['status'] == 'success') {
                    self.itemsCount = result['data']['count'];
                    if (self.itemsCount == 0) {
                        displayInfoOnDiv(self.tabListNotify, self.emptyListMessage);
                    } else {
                        self.tabListContent.show();
                        self.divItemPagination.show();
                        self.pagesCount = Math.ceil(self.itemsCount / ITEMS_PER_PAGE);
                        self.initPagination();
                    }
                    self.isListInitialized = true;
                } else {
                    displayErrorOnDiv(self.tabListNotify, result['data']);
                }
            },
            error: function () {
                self.hideLoader();
                displayErrorOnDiv(self.tabListNotify, [ajaxRequestErrorMessage]);
            },
            type: 'post',
        });
    };
    this.initializeListCategory = function () {
        $.ajax({
            url: ajaxModuleUrl,
            data: {
                ajax: true,
                action: "getAttachableCategory"
            },
            success: function (result) {
                self.hideLoader();
                if (result['status'] == 'success') {
                    self.isListInitialized = true;
                    self.tabListContent.show();
                    self.tabListContent.html(result['data']);
                } else {
                    displayErrorOnDiv(self.tabListNotify, result['data']);
                }
            },
            error: function () {
                self.hideLoader();
                displayErrorOnDiv(self.tabListNotify, [ajaxRequestErrorMessage]);
            },
            type: 'post',
        });
    };
    this.initializeCustomizeSelection = function () {
    	self.showDivCustomizeQuantity();
    	self.showHideBtnMinus();
    	self.btnCustomizeItemPlus.on('click', function (event) {
    		var value=parseInt(self.txtCustomizeItemQuantity.val());
    		self.txtCustomizeItemQuantity.val(value+1);
    		self.showHideBtnMinus();
        });
    	self.btnCustomizeItemMinus.on('click', function (event) {
    		var value=parseInt(self.txtCustomizeItemQuantity.val());
    		self.txtCustomizeItemQuantity.val(value-1);
    		self.showHideBtnMinus();
        });
    	self.txtCustomizeItemQuantity.on('change', function (event) {
    		self.showHideBtnMinus();
        });
    	self.ckbCustomizeItem.on('change', function (event) {
    		self.showDivCustomizeQuantity();
        });
    };
    this.showHideBtnMinus = function () {
    	var minVal=parseInt(self.txtCustomizeItemQuantity.attr("min"));
    	var value=parseInt(self.txtCustomizeItemQuantity.val());
    	if(value<=minVal){
    		self.btnCustomizeItemMinus.hide();
    	}else{
    		self.btnCustomizeItemMinus.show();
    	}
    };
    this.showDivCustomizeQuantity = function () {
    	var checked = self.ckbCustomizeItem.is(':checked');
    	if(checked){
    		self.divCustomizeItemQuantity.show();
    	}else{
    		self.divCustomizeItemQuantity.hide();
    	}
    };
    this.initializeSearch = function () {
        self.handleSearchEvent();
        self.isSearchInitialized = true;
    };
    this.showLoader = function () {
        self.tabListNotify.show();
        self.tabListNotify.html(getLoaderContent(loaderInitializeAttachableItemListMessage));
    };
    this.hideLoader = function () {
        self.tabListNotify.html("");
    };
    this.isCurrentPageInstanciate = function () {
        return (self.pageManagerList[self.currentPageIndex] != undefined) && (self.pageManagerList[self.currentPageIndex] != null);
    };
}