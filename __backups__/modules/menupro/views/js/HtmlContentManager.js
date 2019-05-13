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

function HtmlContentManager(idMenu, parentDiv) {
    var self = this;
    this.parentDiv = parentDiv;
	var panelHeadingSelector = (ps_version>="1.6")?"div.panel-heading:first":"#menupro_html_content_toolbar";
    this.divHtmlContentList = this.parentDiv.find("#divHtmlContentList");
    this.formHtmlContentList = this.divHtmlContentList.find("#form-menupro_html_content");
    this.tableHtmlContent = this.formHtmlContentList.find(".menupro_html_content:first");
    this.tableHtmlContentBody = this.tableHtmlContent.find("tbody:first");
    this.panelHeadingList = this.formHtmlContentList.find(panelHeadingSelector);
    this.btnAddHtmlContent = this.panelHeadingList.find("#desc-menupro_html_content-new");
    if(ps_version>='1.6'){
    	this.badgeHtmlContentCount = this.panelHeadingList.find(".badge:first");
	}else{
		this.badgeHtmlContentCount = formatListTotal(this.formHtmlContentList);
	}
    this.divHtmlContentNotify = this.parentDiv.find("#divHtmlContentNotify");
    this.divHtmlContentListDisabler = this.divHtmlContentList.find("#divHtmlContentListDisabler");
    this.divHtmlContentEdition = this.parentDiv.find("#divHtmlContentEdition");
    this.divHtmlContentForm = this.divHtmlContentEdition.find("#divHtmlContentForm");
    this.panelHtmlContentEditionNotify = this.divHtmlContentEdition.find("#panelHtmlContentEditionNotify");
    this.divHtmlContentEditionNotify = this.panelHtmlContentEditionNotify.find("#divHtmlContentEditionNotify");
    if(ps_version<'1.6'){
    	this.divHtmlContentFormButtons = this.divHtmlContentEdition.find("#divHtmlContentFormButtons");
    	this.formButtonsContent = this.divHtmlContentFormButtons.html();
    	this.divHtmlContentFormButtons.remove();
	}
    this.idMenu = idMenu;
	this.currentAction = "ADD";
	this.currentTdID =null;
	this.currentTdPosition = null;
	this.currentTdStatus = null;
	this.currentRow = null;
	this.currentIDContent = 0;
    this.onReady = function () {
        this.btnAddHtmlContent.next().remove();
    	self.handleEvent();
    };
    this.handleEvent = function () {
        self.btnAddHtmlContent.on("click", function (event) {
    		event.stopPropagation(); 
    		event.preventDefault();
			self.onAddClick();
    	});
        var buttonsSelector = (ps_version>='1.6')?"td>.btn-group-action":"tr td:last-child";
        self.handleActionClick(self.tableHtmlContentBody.find(buttonsSelector).find("a"));
        self.divHtmlContentForm.delegate('#btnHtmlContentCancel', 'click', function (event) {
            self.clearNotify();
            self.hideEditor();
        });
        self.divHtmlContentForm.delegate('#btnHtmlContentSave', 'click', function (event) {
            self.processSave(self.currentIDContent);
        });
        var activeSelector = (ps_version>='1.6')?"td>a.list-action-enable":"td.fixed-width-xs>a";
        self.tableHtmlContentBody.delegate(activeSelector, 'click', function (event) {
            event.stopPropagation();
            event.preventDefault();
            self.currentRow = $(event.target).closest("tr");
            self.currentTdID = self.currentRow.find("td:first");
            if(ps_version<'1.6'){
				self.currentTdID = self.currentTdID.next();
			}
            self.currentTdPosition = self.currentTdID.next();
            self.currentTdStatus = self.currentTdPosition.next();
            self.onStatusChangeClick(self.currentTdID.html().trim());
        });
    };
    this.handleActionClick = function (selector) {
        selector.each(function () {
            $(this).attr("onclick", "");
            $(this).on("click", function (event) {
                self.currentRow=$(this).closest("tr");
                self.currentTdID = self.currentRow.find("td:first");
                if(ps_version<'1.6'){
					self.currentTdID = self.currentTdID.next();
				}
                var id = self.currentTdID.html().trim();
                event.stopPropagation();
                event.preventDefault();
                if ($(this).hasClass("edit")) {
                    self.currentTdPosition = self.currentTdID.next();
                    self.currentTdStatus = self.currentTdPosition.next();
                    self.onUpdateClick(id);
                } else {
                	if(ps_version>='1.6'){
						self.currentRow.find(".btn-group-action>.btn-group").removeClass("open");
					}
                    if ($(this).hasClass("delete")) {
                        self.onDeleteClick(id);
                    }
                }
            });
        });
    };
    this.onAddClick = function () {
        self.currentAction = 'ADD';
        self.currentIDContent = 0;
        self.showEditor(self.currentIDContent);
        self.clearNotify();
    };
    this.showEditor = function (idContent) {
        self.disableDivList();
    	self.divHtmlContentEdition.show();
    	self.processGetEditionContent(idContent);
    };
	this.hideEditor = function () {
	    self.divHtmlContentEdition.hide();
	    self.divHtmlContentForm.hide();
	    self.panelHtmlContentEditionNotify.hide();
	    self.divHtmlContentEditionNotify.html("");
	    self.divHtmlContentForm.html("");
	    self.enableDivList();
	};
	this.processGetEditionContent = function (idContent) {
	    self.showGetEditionFormLoader();
	    self.clearNotify();
	    $.ajax({
	        url: ajaxModuleUrl,
	        data: {
	            ajax: true,
	            action: "GetHtmlContentEditionContent",
	            MENUPRO_HTML_CONTENT_ID: idContent,
	        },
	        success: function (result) {
	            self.hideGetEditionFormLoader();
	            if (result['status'] == 'success') {
	                self.divHtmlContentForm.show();
	                self.divHtmlContentForm.html(result['data']['form']);
	                if(ps_version<'1.6'){
	                	self.addEditionButtons();
	                }
	                $(document).off('focusin.modal');
	            } else {
	                displayErrorOnDiv(self.divHtmlContentNotify, result['data']);
	                self.onLoadEditionContentErrors();
	            }
	        },
	        error: function () {
	            self.hideGetEditionFormLoader();
	            displayErrorOnDiv(self.divHtmlContentNotify, [ajaxRequestErrorMessage]);
	            self.onLoadEditionContentErrors();
	        },
	        type: 'post',
	    });
	};
	this.showGetEditionFormLoader = function () {
	    self.panelHtmlContentEditionNotify.show();
	    self.divHtmlContentEditionNotify.show();
	    self.divHtmlContentForm.hide();
	    self.divHtmlContentEditionNotify.html(getLoaderContent(loaderGetEditionHtmlContentMessage));
	};
	this.hideGetEditionFormLoader = function () {
	    self.panelHtmlContentEditionNotify.hide();
	    self.divHtmlContentEditionNotify.hide();
	    self.divHtmlContentEditionNotify.html("");
	};
	this.onUpdateClick = function (idContent) {
	    self.clearNotify();
	    self.currentAction = 'UPDATE';
	    self.currentIDContent = idContent;
	    self.showEditor(self.currentIDContent);
	};
	this.processSave = function (idContent) {
	    self.onOperation(loaderSaveHtmlContentMessage);
	    var values = {};
	    values.ajax= true,
	    values.action= "SaveHtmlContent",
	    values.MENUPRO_HTML_CONTENT_ID= idContent,
	    values.MENUPRO_HTML_CONTENT_MENU_ID= self.idMenu,
	    values.MENUPRO_HTML_CONTENT_POSITION= self.divHtmlContentForm.find("#MENUPRO_HTML_CONTENT_POSITION").val(),
	    self.divHtmlContentForm.find("textarea").each(function (event) {
	        var target = $(this);
	        values[target.attr("name")] = getRichTextContent(target);
	    });
       $.ajax({
            url: ajaxModuleUrl,
            data: values,
            success: function (result) {
            	hideModalLoader();
            	if (result['status'] == 'success') {
            	    if (self.currentAction == "ADD") {
            	        self.addHtmlContentInList(result['data']['id_content'], result['data']['position'], result['data']['active']);
            	    } else {
            	        self.updateHtmlContentInList(result['data']['id_content'], result['data']['position'], result['data']['active']);
            	    }
					displaySuccessOnDiv(self.divHtmlContentNotify,result['data']['message']);
                } else {
            	    displayErrorOnDiv(self.divHtmlContentNotify, result['data']);
                }
            },
            error: function () {
                hideModalLoader();
                displayErrorOnDiv(self.divHtmlContentNotify, [ajaxRequestErrorMessage]);
            },
            type: 'post',
        });
    };
	this.onDeleteClick = function (idContent) {
	    self.clearNotify();
	    if (confirm(confirmDeleteHtmlContentMessage + " " + idContent)) {
	        self.onOperation(loaderDeleteHtmlContentMessage);
	        $.ajax({
	            url: ajaxModuleUrl,
	            data: {
	                ajax: true,
	                action: "DeleteHtmlContent",
	                MENUPRO_HTML_CONTENT_ID: idContent,
	            },
	            success: function (result) {
	                hideModalLoader();
	                if (result['status'] == 'success') {
	                    var listCount = parseInt(self.badgeHtmlContentCount.html().trim()) - 1;
	                    self.badgeHtmlContentCount.html(listCount);
	                    self.currentRow.remove();
	                    if (listCount == 0) {
	                        self.addNoRecordRow();
	                    }
	                    displaySuccessOnDiv(self.divHtmlContentNotify, result['data']['message']);
	                } else {
	                    displayErrorOnDiv(self.divHtmlContentNotify, result['data']);
	                }
	            },
	            error: function () {
	                hideModalLoader();
	                displayErrorOnDiv(self.divHtmlContentNotify, [ajaxRequestErrorMessage]);
	            },
	            type: 'post',
	        });
	    }
	};
	this.onStatusChangeClick = function (idContent) {
	    self.clearNotify();
	    self.onOperation(loaderStatusChangeHtmlContentMessage);
	    $.ajax({
	        url: ajaxModuleUrl,
	        data: {
	            ajax: true,
	            action: "StatusChangeHtmlContent",
	            MENUPRO_HTML_CONTENT_ID: idContent,
	        },
	        success: function (result) {
	            hideModalLoader();
	            if (result['status'] == 'success') {
	                self.currentTdStatus.html(getStatusTdContent(result['data']['active']));
	                displaySuccessOnDiv(self.divHtmlContentNotify, result['data']['message']);
	            } else {
	                displayErrorOnDiv(self.divHtmlContentNotify, result['data']);
	            }
	        },
	        error: function () {
	            hideModalLoader();
	            displayErrorOnDiv(self.divHtmlContentNotify, [ajaxRequestErrorMessage]);
	        },
	        type: 'post',
	    });
	};
	this.onOperation = function (loaderMessage) {
		self.clearNotify();
		self.divHtmlContentNotify.show();
		showModalLoader(loaderMessage);
	};
	this.addHtmlContentInList = function (idContent, position, active) {
	    var listCount = parseInt(self.badgeHtmlContentCount.html().trim()) + 1;
	    if (listCount == 1) {
	        var emptyRow = self.tableHtmlContentBody.find("tr:first");
	        if ((emptyRow != null) && (emptyRow.length != 0)) {
	            emptyRow.remove();
	        }
	    }
	    self.badgeHtmlContentCount.html(listCount);
	    var trId = "trNew" + idContent;
	    var trClass = (ps_version>='1.6')?"":"row_hover";
		var trClassNext = (ps_version>='1.6')?"odd":"alt_row ";
		var condition = (ps_version>='1.6')?(listCount % 2 != 0):(listCount % 2 == 0);
		var htmlContent = '<tr id="'+ trId+ '" class="'+ (condition ? trClassNext : '')+trClass+ '">'
		+ ((ps_version>='1.6')?'':'<td></td>')+
	    '<td>' + idContent + '</td>' +
	    '<td>' + position + '</td>' +
        '<td class="fixed-width-xs center">' + getStatusTdContent(active) + '</td>' +
        getListActionContent(false)+
        '</tr>';
	    self.tableHtmlContentBody.append(htmlContent);
	    var buttonsSelector = (ps_version>='1.6')?"td>.btn-group-action":"td:last-child";
	    self.handleActionClick(self.tableHtmlContentBody.find("tr#" + trId).find(buttonsSelector).find("a"));
	    self.hideEditor();
	};
	this.addNoRecordRow = function () {
	    self.tableHtmlContent.find("tbody").append(getListNoRecordContent());
	};
	this.updateHtmlContentInList = function (idContent, position, active) {
	    self.currentTdID.html(idContent);
	    self.currentTdPosition.html(position);
	    self.currentTdStatus.html(getStatusTdContent(active));
	    self.hideEditor();
	};

	this.removeCurrentRow = function () {
	    self.currentRow.remove();
	};
	this.disableDivList = function () {
	    self.divHtmlContentListDisabler.show();
	};
	this.enableDivList = function () {
	    self.divHtmlContentListDisabler.hide();
	};
    this.clearNotify = function () {
	    self.divHtmlContentNotify.html("");
    };
    this.onLoadEditionContentErrors = function () {
        self.enableDivList();
    };
    this.addEditionButtons = function () {
        self.divHtmlContentForm.find("#menupro_html_content_form>fieldset").append(self.formButtonsContent);
    };
}