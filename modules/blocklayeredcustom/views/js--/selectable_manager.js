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
var sliderList = null;
function SelectableManager() {
	var self = this;
	
	this.onReady = function () {
		this.loaderBlock = $(".blc-filter-block .filter-center .loading-product");
		this.parentBlock = $(".blc-filter-block");
		this.productBlock = $(".blc-filter-block .filter-center .filter-center-content");
		this.selectedFilters = [];
		this.filterString = "";
		self.selectablesManagers=[];
		sliderList = new Array();
    	self.handleEvent();
    };
	
	this.handleEvent = function () {
		self.handleFilter();
		self.parentBlock.find("#formFilterLeft .block-selected-filters .selected-filters-reset .btn-reset-all").on("click", function (event) {
			event.preventDefault();
			self.resetFilter(true);
		});
		self.parentBlock.find("#formFilterLeft .block-selected-filters .selected-filters-item .selected-filters-item-remove").on("click", function (event) {
			event.preventDefault();
			var parentDiv = $(this).closest(".selected-filters-item");
			self.setSelectedsFromSelectedBlock();
			self.setSelecteds(parentDiv.attr("data-id-block"), parentDiv.attr("data-block-type"), [], true);
		});
    };
	this.handleFilter = function () {
		var selectables = $("#formFilterLeft .filter-item .filter-content .filter-selectables");
		selectables.each( function () {
			var parentDiv = $(this);
			var filterType = parentDiv.attr("data-filter-type");
			var id = parentDiv.closest(".filter-item").attr("data-id-block");
			var blockType = parentDiv.closest(".filter-item").attr("data-block-type");
			self.selectablesManagers[id] = null;
			if(filterType==filterTypeConst.FILTER_TYPE_DROPDOWN_LIST){
				self.selectablesManagers[id] = new SelectableDropdownList(self, parentDiv);
			}else if(filterType==filterTypeConst.FILTER_TYPE_RADIO){
				self.selectablesManagers[id] = new SelectableDropdownRadio(self, parentDiv);
			}else if(filterType==filterTypeConst.FILTER_TYPE_CHECKBOX){
				self.selectablesManagers[id] = new SelectableDropdownCheckbox(self, parentDiv);
			}else if(filterType==filterTypeConst.FILTER_TYPE_SLIDER){
				self.selectablesManagers[id] = new SelectableSlider(self, parentDiv);
			}else if(filterType==filterTypeConst.FILTER_TYPE_INPUTS){
				self.selectablesManagers[id] = new SelectableInputs(self, parentDiv);
			}else if(filterType==filterTypeConst.FILTER_TYPE_COLOR){
				self.selectablesManagers[id] = new SelectableColor(self, parentDiv);
			}
			if((typeof self.selectablesManagers[id] !== "undefined")&&(self.selectablesManagers[id] !==null)){
				self.selectablesManagers[id].blockType = blockType;
				self.handleSeeMore(parentDiv);
				self.selectablesManagers[id].onReady();
				if(filterType==filterTypeConst.FILTER_TYPE_SLIDER){
					initSliders();
				}
				selecteds = self.selectablesManagers[id].getSelecteds();
				self.setSelecteds(id, blockType, selecteds, false);
			}
		});
    };
	this.handleSeeMore = function (parentDiv) {
		if(parentDiv.attr("data-show-see-more")){
			parentDiv.find(".div-see-more .btn-see-more").on("click", function (event) {
				event.preventDefault();
				var target = $(this);
				var unvisibleElements = parentDiv.find(".selectable-item.unvisible");
				var lengthUnvisible = unvisibleElements.length;
				var hasMore = (maxFilterItems<lengthUnvisible);
				var size = hasMore?maxFilterItems:lengthUnvisible;
				for(i = 0; i<size;i++){
					var item = $(unvisibleElements[i]);
					item.removeClass("unvisible");
				}
				if(!hasMore){
					target.closest(".div-see-more").addClass("unvisible");
				}
			});
		}
	};
	this.addSelecteds = function (target, selecteds, process) {
		var parentBlock = target.closest(".filter-item");
    	var idBlock = parentBlock.attr("data-id-block");
		var blockType = parentBlock.attr("data-block-type");
		self.setSelecteds(idBlock, blockType, selecteds, process);
    };
	this.setSelecteds = function (idBlock, blockType, selecteds, process) {
		self.selectedFilters[idBlock] = [];
		self.selectedFilters[idBlock]["selecteds"] = selecteds;
		self.selectedFilters[idBlock]["block_type"] = blockType;
		self.getSelectedsParams();
		if(process){
			self.processFilter(idBlock);
		}
    };
	this.setSelectedsFromSelectedBlock = function () {
		self.parentBlock.find(".selected-filters-list .selected-filters-item").each(function () {
			target = $(this);
			var idBlock = target.attr("data-id-block");
			var blockType = target.attr("data-block-type");
			var selectedsString = target.attr("data-selecteds").trim();
			var selecteds = (selectedsString=="") ? [] : selectedsString.split(",");
			self.setSelecteds(idBlock, blockType, selecteds, false);
		});
    };
	this.showLoader = function () {
		self.loaderBlock.show();
    };
	this.hideLoader = function () {
		self.loaderBlock.hide();
    };
	this.getSelectedsParams = function () {
		var first = true;
		self.filterString = "";
		for(idBlock in self.selectedFilters){
			if(self.selectedFilters[idBlock]["selecteds"].length>0){
				self.filterString+=(!first)?BLOCK_SEPARATOR:"";
				self.filterString+=idBlock+FILTER_VALUES_SEPARATOR+self.selectedFilters[idBlock]["block_type"]+FILTER_VALUES_SEPARATOR+self.selectedFilters[idBlock]["selecteds"].join(FILTER_VALUES_SEPARATOR);
				first =false;
			}
		}
		return self.filterString;
    };
	this.resetFilter = function (process) {
		self.filterString = "";
		if(process){
			self.processFilter(0);
		}
    };
	this.processFilter = function (idBlock) {
		currentProductAttibutesPage = 1;
		self.showLoader();
		var trigger_block_values = "";
		if((idBlock>0)&&(typeof(self.selectablesManagers[idBlock])!=="undefined")&&(self.selectablesManagers[idBlock].hasGetAll)){
			trigger_block_values = self.selectablesManagers[idBlock].getAll().join(",");
		}
    	$.ajax({
	        url: blfFrontAjaxUrl,
			dataType: "json",
	        data: {
	            ajax: true,
	            action: "FilterLeft",
				id_category: self.parentBlock.find(".input_category").val(),
	            selected_filters: self.filterString,
				trigger_block_id: idBlock,
				trigger_block_values: trigger_block_values
	        },
	        success: function (result) {
				self.hideLoader();
				if(result.hasError){
					displayBlcError(result.errors, self.productBlock);
				}else{
					self.parentBlock.html(result.form);
					self.onReady();
					bindUniform();
				}
				blcAttributeFilterManager.showHideLoadMore();
				ajaxCart.refreshQuantity();
	        },
	        error: function () {
				self.hideLoader();
	            displayBlcError("An ERROR OCCURED WHILE CONNECTING TO SERVER", self.productBlock);
	        },
	        type: 'post',
	    });
    };
}

function SelectableDropdownList(selectableManager, parentDiv) {
	var self = this;
	this.hasGetAll = true;
	this.onReady = function () {
    	self.handleEvent();
    };
	this.selector = ".selectable-field.selectable-field-dropdown-list"
    this.handleEvent = function () {
		parentDiv.delegate(self.selector, "change", function (event) {
    		var target = $(this);
			selectableManager.addSelecteds(target, self.getSelecteds(), true);
    	});
    };
	
	this.getSelecteds = function () {
		var value = parentDiv.find(self.selector).val();
		var selecteds = Array.isArray(value)?value:[value];
		if((selecteds.length>0)&&(parseInt(selecteds[0])==0)){
			selecteds.splice(0);
		}
		return selecteds;
    };
	this.getAll = function () {
		var selecteds = [];
		parentDiv.find(self.selector).each(function()
		{
			var value = parseInt($(this).val());
			if(value>0){
				selecteds.push(value)
			}
		});
		return selecteds;
    };
}

function SelectableDropdownCheckbox(selectableManager, parentDiv) {
	this.hasGetAll = true;
	var self = this;
	this.selector = ".selectable-field.selectable-field-checkbox";
	this.onReady = function () {
    	self.handleEvent();
    };
    this.handleEvent = function () {
		parentDiv.delegate(self.selector, "change", function (event) {
			var target = $(this);
			selectableManager.addSelecteds(target, self.getSelecteds(), true);
    	});
    };
	
	this.getSelecteds = function () {
		return self.getValue(self.selector+":checked");
    };
	this.getValue = function (selector) {
		var selecteds = [];
		parentDiv.find(selector).each(function () {
			selecteds.push($(this).val());
		});
		return selecteds;
    };
	this.getAll = function () {
		return self.getValue(self.selector);
    };
}

function SelectableDropdownRadio(selectableManager, parentDiv) {
	var self = this;
	this.hasGetAll = true;
	this.selector = ".selectable-field.selectable-field-radio";
	this.onReady = function () {
    	self.handleEvent();
    };
     this.handleEvent = function () {
		parentDiv.delegate(self.selector, "change", function (event) {
			var target = $(this);
			selectableManager.addSelecteds(target, self.getSelecteds(), true);
    	});
    };
	this.getSelecteds = function () {
		return self.getValue(self.selector+":checked");
    };
	this.getValue = function (selector) {
		var selecteds = [];
		parentDiv.find(selector).each(function () {
			selecteds.push($(this).val());
		});
		return selecteds;
    };
	this.getAll = function () {
		return self.getValue(self.selector);
    };
}

function SelectableInputs(selectableManager, parentDiv) {
	var self = this;
	this.hasGetAll = false;
	this.selector = ".selectable-field.selectable-field-input";
	this.onReady = function () {
    	self.handleEvent();
    };
    this.handleEvent = function () {
		parentDiv.delegate(self.selector, "change", function (event) {
    		var target = $(this);
			selectableManager.addSelecteds(target, self.getSelecteds(), true);
    	});
    };
	this.getSelecteds = function () {
		var selecteds = [];
		var minInput = parentDiv.find(".selectable-field.selectable-field-input.min");
		var maxInput = parentDiv.find(".selectable-field.selectable-field-input.max");
		var min = minInput.val();
		var max = maxInput.val();
		var selecteds = [];
		if((min!=minInput.attr("data-default"))||(max!=maxInput.attr("data-default"))){
			selecteds.push(min);
			selecteds.push(max);
		}
		return selecteds;
    };
}

function SelectableColor(selectableManager, parentDiv) {
	var self = this;
	this.hasGetAll = true;
	this.selector = ".selectable-field.selectable-field-color";
	this.onReady = function () {
    	self.handleEvent();
    };
    this.handleEvent = function () {
		var multiple = parseInt(parentDiv.attr("data-multiple"));
		parentDiv.delegate(self.selector, "click", function (event) {
			var target = $(this);
			var wasSelected = target.closest(".selectable-item").hasClass("selected");
			if(!multiple){
				parentDiv.find(".selectable-item").removeClass("selected");
			}
			if(wasSelected){
				target.closest(".selectable-item").removeClass("selected");
			}else{
				target.closest(".selectable-item").addClass("selected");
			}
    		
			selectableManager.addSelecteds(target, self.getSelecteds(), true);
    	});
    };
	this.getSelecteds = function () {
		return self.getValue(".selectable-item.selected "+self.selector);
    };
	this.getValue = function (selector) {
		var selecteds = [];
		parentDiv.find(selector).each(function () {
			selecteds.push($(this).val());
		});
		return selecteds;
    };
	this.getAll = function () {
		return self.getValue(".selectable-item "+self.selector);
    };
}
function SelectableSlider(selectableManager, parentDiv) {
	var self = this;
	this.hasGetAll = false;
	this.selector = ".layered_slider";
	this.onReady = function () {
		self.initFilter();
    };
    this.onSelected = function () {
		var target = parentDiv.find(self.selector);
		selectableManager.addSelecteds(target, self.getSelecteds(), true);
    };
	this.getSelecteds = function () {
		var selecteds = [];
		var target = parentDiv.find(self.selector);
		var sliderStart = target.slider('values', 0);
		var sliderStop = target.slider('values', 1);
		if (typeof(sliderStart) == 'number' && typeof(sliderStop) == 'number'){
			if((sliderStart!=self.filter.min)||(sliderStop!=self.filter.max)){
				selecteds = [sliderStart, sliderStop];
			}
			
		}
		return selecteds;
    };
	this.initFilter = function () {
		var filter = self.getFilter();
		self.filter = filter;
		var filterRange = parseInt(filter.max)-parseInt(filter.min);
		var step = filterRange / 100;

		if (step > 1)
			step = parseInt(step);

		addSlider(filter.type,
		{
			range: true,
			step: step,
			min: parseInt(filter.min),
			max: parseInt(filter.max),
			values: [filter.values[0], filter.values[1]],
			slide: function(event, ui) {
				if (parseInt($(event.target).data('format')) < 5)
				{
					from = formatCurrency(ui.values[0], parseInt($(event.target).data('format')),
						$(event.target).data('unit'));
					to = formatCurrency(ui.values[1], parseInt($(event.target).data('format')),
						$(event.target).data('unit'));
				}
				else
				{
					from = ui.values[0] + $(event.target).data('unit');
					to = ui.values[1] + $(event.target).data('unit');
				}

				parentDiv.find('#layered_' + $(event.target).data('type') + '_range').html(from + ' - ' + to);
			},
			stop: function () {
				self.onSelected();
			}
		}, filter.unit, parseInt(filter.format), parentDiv);
	};
	
	this.getFilter = function () {
		var filter = {};
		var sliderDiv = parentDiv.find(".blc-slider");
		filter.min = sliderDiv.attr("data-min");
		filter.max = sliderDiv.attr("data-max");
		filter.format = sliderDiv.attr("data-format");
		filter.unit = sliderDiv.attr("data-unit");
		filter.type = sliderDiv.attr("data-type");
		filter.values = sliderDiv.attr("data-values").split(",");
		return filter;
    };
}

function addSlider(type, data, unit, format, parentDiv)
{
	sliderList.push({
		type: type,
		data: data,
		unit: unit,
		format: format,
		parentDiv : parentDiv
	});
}

function initSliders()
{
	$(sliderList).each(function(i, slider){
		slider['parentDiv'].find('#layered_'+slider['type']+'_slider').slider(slider['data']);

		var from = '';
		var to = '';
		switch (slider['format'])
		{
			case 1:
			case 2:
			case 3:
			case 4:
				from = formatCurrency(slider['parentDiv'].find('#layered_'+slider['type']+'_slider').slider('values', 0), slider['format'], slider['unit']);
				to = formatCurrency(slider['parentDiv'].find('#layered_'+slider['type']+'_slider').slider('values', 1), slider['format'], slider['unit']);
				break;
			case 5:
				from =  slider['parentDiv'].find('#layered_'+slider['type']+'_slider').slider('values', 0)+slider['unit']
				to = slider['parentDiv'].find('#layered_'+slider['type']+'_slider').slider('values', 1)+slider['unit'];
				break;
		}
		slider['parentDiv'].find('#layered_'+slider['type']+'_range').html(from+' - '+to);
	});
}
