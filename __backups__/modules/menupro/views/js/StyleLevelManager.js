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

function StyleLevelManager() {
	var self = this;
	this.formStyleLevel = $("#form-menupro_default_style");
	//var tableStyleLevelSelector = (ps_version>="1.6")?".menupro_default_style":".table_grid";
	
	this.tableStyleLevel = this.formStyleLevel.find(".menupro_default_style");
	//var panelHeadingSelector = (ps_version>="1.6")?"div.panel-heading":"#menupro_default_style_toolbar";
	var panelHeadingSelector = (ps_version>="1.6")?"div.panel-heading":".toolbar-placeholder:first";
	this.panelHeading = this.formStyleLevel.find(panelHeadingSelector);
	this.btnAddStyleLevel = this.panelHeading.find("#desc-menupro_default_style-new");
	if(ps_version>='1.6'){
		this.badgeStyleLevelCount = this.panelHeading.find(".badge");
	}else{
		this.badgeStyleLevelCount = formatListTotal(this.formStyleLevel);
	}
	this.divStyleLevelEditionContent = $("#divStyleLevelEditionContent");
	this.divStyleLevelEdition = $("#divStyleLevelEdition");
	this.btnStyleLevelCancel = $("#btnStyleLevelCancel");
	this.btnStyleLevelSave = $("#btnStyleLevelSave");
	this.divStyleLevelNotify = $("#divStyleLevelNotify");
	this.divStyleLevelListDisabler = $("#divStyleLevelListDisabler");
	this.txtName = $("#MENUPRO_STYLES_LEVEL_NAME");
	this.txtLevel = $("#MENUPRO_STYLES_LEVEL_MENU_LEVEL");
	this.txtId = $("#MENUPRO_STYLES_LEVEL_ID");
	this.idMenu = $("#MENUPRO_STYLES_LEVEL_MENU_ID").val();
	this.menuType = $("#MENUPRO_STYLES_LEVEL_MENU_TYPE").val();
	this.currentAction = "ADD";
	this.currentTdID = null;
	this.currentTdName = null;
	this.currentTdLevel = null;
	this.currentRow = null;
	this.onReady = function() {
		this.btnAddStyleLevel.next().remove();
		self.handleEvent();
	};
	this.handleEvent = function() {
		self.btnAddStyleLevel.on("click", function(event) {
			event.stopPropagation();
			event.preventDefault();
			self.onAddClick();
		});
		var buttonsSelector = (ps_version>='1.6')?"td>.btn-group-action":"tbody tr td:last-child";
		self.handleActionClick(self.tableStyleLevel.find(buttonsSelector).find("a"));
		self.btnStyleLevelCancel.on("click", function(event) {
			self.clearNotify();
			self.hideEditor();
		});
		self.btnStyleLevelSave.on("click", function(event) {
			self.processSave();
		});
		self.txtLevel.on("change", function() {
			var level = self.txtLevel.val();
			self.propertyManager.menuLevel = (!isNaN(level)) ? parseInt(level)
					: 0;
		});

		self.divStyleLevelEditionContent.find(".level-dropdown-item").on(
				"click", function(event) {
					event.preventDefault();
					self.txtLevel.val($(event.target).attr("data-value"));
				});

	};
	this.handleActionClick = function(selector) {
		selector.each(function() {
			$(this).attr("onclick", "");
			$(this).on("click", function(event) {
				self.currentRow = $(this).closest("tr");
				self.currentTdID = self.currentRow.find("td:first");
				if(ps_version<'1.6'){
					self.currentTdID = self.currentTdID.next();
				}
				var id = self.currentTdID.html().trim();
				event.stopPropagation();
				event.preventDefault();
				if ($(this).hasClass("edit")) {
					self.currentTdName = self.currentTdID.next();
					self.currentTdLevel = self.currentTdName.next();
					self.onUpdateClick(id, self.currentTdName.html().trim(), self.currentTdLevel.html().trim());
				} else {
					if(ps_version>='1.6'){
						self.currentRow.find(".btn-group-action>.btn-group").removeClass("open");
					}
					if ($(this).hasClass("delete")) {
						self.currentTdName = self.currentTdID.next();
						self.onDeleteClick(id,self.currentTdName.html().trim());
					} else {
						self.onDuplicateClick(id);
					}
				}
			});
		});
	};
	this.onAddClick = function() {
		self.currentAction = 'ADD';
		self.showEditor(-1, 0);
		self.fillEditorFields(0, "", "");
		self.clearNotify();
	};
	this.showEditor = function(menuLevel, idStyle) {
		self.disableDivList();
		self.divStyleLevelEdition.show();
		self.propertyManager = new PropertyManager();
		self.propertyManager.menuType = self.menuType;
		var level = self.txtLevel.val();
		self.propertyManager.menuLevel = (!isNaN(level)) ? parseInt(level) : 0;
		self.propertyManager.idMenu = self.idMenu;
		self.propertyManager.renderPropertiesBlock(self,
				self.divStyleLevelEditionContent, styleTypesConst.DEFAULT,
				self.menuType, menuLevel, idStyle, false);
	};
	this.hideEditor = function() {
		self.divStyleLevelEdition.hide();
		self.enableDivList();
	};
	this.onUpdateClick = function(idStyle, nameStyle, menuLevel) {
		self.clearNotify();
		self.currentAction = 'UPDATE';
		self.showEditor(menuLevel, idStyle);
		self.fillEditorFields(idStyle, nameStyle, menuLevel);
	};
	this.fillEditorFields = function(idStyle, nameStyle, menuLevel) {
		self.txtName.val(nameStyle);
		self.txtLevel.val(menuLevel);
		self.txtId.val(idStyle);
	};
	this.processSave = function() {
		self.onOperation(loaderSaveStyleLevelMessage);
		var values = {};
		values.ajax = true;
		values.action = "saveStyleLevel";
		values.MENUPRO_STYLES_LEVEL_ID = self.txtId.val();
		values.MENUPRO_STYLES_LEVEL_NAME = self.txtName.val();
		values.MENUPRO_STYLES_LEVEL_MENU_LEVEL = self.txtLevel.val();
		values.MENUPRO_STYLES_LEVEL_MENU_TYPE = self.menuType;
		values.MENUPRO_STYLES_LEVEL_MENU_ID = self.idMenu;
		values = self.propertyManager.getData(values);
		$
				.ajax({
					url : ajaxModuleUrl,
					data : values,
					success : function(result) {
						hideModalLoader();
						if (result['status'] == 'success') {
							if (self.currentAction == "ADD") {
								self.addStleLevelInList(
										result['data']['id_style'],
										result['data']['style_name'],
										result['data']['menu_level']);
							} else {
								self.updateStleLevelInList(
										result['data']['id_style'],
										result['data']['style_name'],
										result['data']['menu_level']);
							}
							displaySuccessOnDiv(self.divStyleLevelNotify,
									result['data']['message']);
						} else {
							displayErrorOnDiv(self.divStyleLevelNotify,
									result['data']);
						}
					},
					error : function() {
						hideModalLoader();
						displayErrorOnDiv(self.divStyleLevelNotify,
								[ ajaxRequestErrorMessage ]);
					},
					type : 'post',
				});
	};
	this.onDeleteClick = function(idStyle, name) {
		self.clearNotify();
		if (confirm(confirmDeleteStyleLevelMessage + " " + name)) {
			self.onOperation(loaderDeleteStyleLevelMessage);
			$.ajax({
				url : ajaxModuleUrl,
				data : {
					ajax : true,
					action : "deleteStyleLevel",
					MENUPRO_STYLES_LEVEL_ID : idStyle,
				},
				success : function(result) {
					hideModalLoader();
					if (result['status'] == 'success') {
						var listCount = parseInt(self.badgeStyleLevelCount
								.html().trim()) - 1;
						self.badgeStyleLevelCount.html(listCount);
						self.currentRow.remove();
						if (listCount == 0) {
							self.addNoRecordRow();
						}
						displaySuccessOnDiv(self.divStyleLevelNotify,
								result['data']['message']);
					} else {
						displayErrorOnDiv(self.divStyleLevelNotify,
								result['data']);
					}
				},
				error : function() {
					hideModalLoader();
					displayErrorOnDiv(self.divStyleLevelNotify,
							[ ajaxRequestErrorMessage ]);
				},
				type : 'post',
			});
		}
	};
	this.onDuplicateClick = function(idStyle) {
		self.onOperation(loaderDuplicateStyleLevelMessage);
		$
				.ajax({
					url : ajaxModuleUrl,
					data : {
						ajax : true,
						action : "duplicateStyleLevel",
						MENUPRO_STYLES_LEVEL_ID : idStyle,
					},
					success : function(result) {
						hideModalLoader();
						if (result['status'] == 'success') {
							self.addStleLevelInList(result['data']['id_style'],
									result['data']['style_name'],
									result['data']['menu_level']);
							displaySuccessOnDiv(self.divStyleLevelNotify,
									result['data']['message']);
						} else {
							displayErrorOnDiv(self.divStyleLevelNotify,
									result['data']);
						}
					},
					error : function() {
						hideModalLoader();
						displayErrorOnDiv(self.divStyleLevelNotify,
								[ ajaxRequestErrorMessage ]);
					},
					type : 'post',
				});
	};
	this.onOperation = function(loaderMessage) {
		self.clearNotify();
		self.divStyleLevelNotify.show();
		showModalLoader(loaderMessage);
	};
	this.addStleLevelInList = function(idStyle, name, level) {
		var listCount = parseInt(self.badgeStyleLevelCount.html().trim()) + 1;
		if (listCount == 1) {
			var emptyRow = self.tableStyleLevel.find("tbody").find("tr:first");
			if ((emptyRow != null) && (emptyRow.length != 0)) {
				emptyRow.remove();
			}
		}
		self.badgeStyleLevelCount.html(listCount);
		var trId = "trNew" + idStyle;
		var trClass = (ps_version>='1.6')?"":"row_hover";
		var trClassNext = (ps_version>='1.6')?"odd":"alt_row ";
		var condition = (ps_version>='1.6')?(listCount % 2 != 0):(listCount % 2 == 0);
		var htmlContent = '<tr id="'+ trId+ '" class="'+ (condition ? trClassNext : '')+trClass+ '">'
				+ ((ps_version>='1.6')?'':'<td></td>')
				+ '<td>'+ idStyle+ '</td>'+ '<td>'+ name+ '</td>'+ '<td>'+ level+ '</td>'
				+ getListActionContent()+
				+ '</tr>';
		self.tableStyleLevel.find("tbody").append(htmlContent);
		var buttonsSelector = (ps_version>='1.6')?"td>.btn-group-action":"td:last-child";
		self.handleActionClick(self.tableStyleLevel.find("tbody>tr#" + trId).find(buttonsSelector).find("a"));
		self.clearFields();
		self.hideEditor();
	};
	this.addNoRecordRow = function() {
		self.tableStyleLevel.find("tbody").append(getListNoRecordContent());
	};
	this.updateStleLevelInList = function(idStyle, name, level) {
		self.currentTdID.html(idStyle);
		self.currentTdName.html(name);
		self.currentTdLevel.html(level);
		self.clearFields();
		self.hideEditor();
	};

	this.removeCurrentRow = function() {
		self.currentRow.remove();
	};

	this.clearFields = function() {
		self.txtName.val("");
		self.txtLevel.val("");
		self.txtId.val("");
	};
	this.disableDivList = function() {
		self.divStyleLevelListDisabler.show();
	};
	this.enableDivList = function() {
		self.divStyleLevelListDisabler.hide();
	};
	this.clearNotify = function() {
		self.divStyleLevelNotify.html("");
	};
	this.onLoadPropertiesErrors = function() {
		self.enableDivList();
	};
	this.onLoadPropertiesSuccess = function() {
	};
}