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

function PageManager(secondaryMenuTypeManager) {
    var self = this;
    this.isLoaded = false;
    this.itemsList = [];
    this.retrieveList = function () {
        self.showLoader();
        $start = (secondaryMenuTypeManager.currentPageIndex - 1) * ITEMS_PER_PAGE;
        $.ajax({
            url: ajaxModuleUrl,
            data: {
                ajax: true,
                action: "getAttachableItems",
                MENUPRO_ITEMS_SELECTION_ITEM_TYPE: secondaryMenuTypeManager.itemType,
                MENUPRO_ITEMS_SELECTION_START: $start
            },
            success: function (result) {
                self.hideLoader();
                if (result['status'] == 'success') {
                    secondaryMenuTypeManager.divItemCurrentPageContent.show();
                    secondaryMenuTypeManager.divItemCurrentPageContent.html("");
                    self.itemsList = result['data']['items'];
                    self.displayItems(true);
                    self.isLoaded = true;
                } else {
                    displayErrorOnDiv(secondaryMenuTypeManager.divItemCurrentPageNotify, result['data']);
                }
            },
            error: function () {
                self.hideLoader();
                displayErrorOnDiv(secondaryMenuTypeManager.divItemCurrentPageNotify, [ajaxRequestErrorMessage]);
            },
            type: 'post',
        });
    };
    this.showLoader = function () {
        secondaryMenuTypeManager.divItemCurrentPageNotify.show();
        secondaryMenuTypeManager.divItemCurrentPageNotify.html(getLoaderContent(loaderInitializePageMessage + " " + secondaryMenuTypeManager.currentPageIndex + "..."));
    };
    this.hideLoader = function () {
        secondaryMenuTypeManager.divItemCurrentPageNotify.html("");
    };
    this.getItemContent = function (item, index) {
        return '<div class="mp-selectable-item mp-selectable-full-size clearfix' + ((item.isSelected) ? 'mp-item-selected' : '') +
            '"><label class="checkbox-inline"><input class="page-item" ' +
            ((item.isSelected) ? 'checked' : '') +
            ' type="checkbox" value="' + item.id + '" data-index="' + index + '">' + item.name + '</label></div>';
    };
    this.displayItems = function (unSelected) {
        secondaryMenuTypeManager.divItemCurrentPageContent.html("");
        for (i in self.itemsList) {
            if (unSelected) {
                self.itemsList[i].isSelected = false;
            }
            secondaryMenuTypeManager.divItemCurrentPageContent.html(secondaryMenuTypeManager.divItemCurrentPageContent.html() + self.getItemContent(self.itemsList[i], i));
        }
    };
    this.displayPage = function () {
        secondaryMenuTypeManager.divItemCurrentPageContent.html("");
        if (self.isLoaded) {
            self.displayItems(false);
        } else {
            self.retrieveList();
        }
    };
    this.getSelectedItems = function () {
        if (self.isLoaded) {
            return getSelectedFromList(self.itemsList, secondaryMenuTypeManager.itemType);
        } else {
            return [];
        }
    };
    this.unSelectPage = function () {
        if (self.isLoaded) {
            secondaryMenuTypeManager.divItemCurrentPageContent.find(".page-item").each(function () {
                var target = $(this);
                target.prop('checked', false);
                target.closest(".mp-selectable-item").removeClass("mp-item-selected");
            });
        }
        self.itemsList = unSelectList(self.itemsList);
    };
}