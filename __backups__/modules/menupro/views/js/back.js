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

var dialog;
var dialogLoader;
var pageHeadHeight = 0;
$(document).ready(function () {
    if ((location.href.indexOf("addmenupro_main_menu") != -1) || (location.href.indexOf("updatemenupro_main_menu") != -1)) {
        var mainMenuManager = new MainMenuManager();
        mainMenuManager.onReady();
        if (location.href.indexOf("updatemenupro_main_menu") != -1) {
        	var styleLevelManager = new StyleLevelManager(menuTypesConst.MAIN);
            styleLevelManager.onReady();
        }
    }else if (location.href.indexOf("viewmenupro_main_menu") != -1) {
        var secondaryMenuManager = new SecondaryMenuManager();
        secondaryMenuManager.onReady();
    } else {
        var styleLevelManager = new StyleLevelManager(menuTypesConst.NONE);
        styleLevelManager.onReady();
    }
    
    pageHeadHeight = $("#content>div.bootstrap>div.page-head").height();
    if(ps_version<'1.6'){
    	handleLangDropdownClick();
    	handleCollapseEvent();
    	handleTabsEvent();
    }
});
function handleLangDropdownClick() {
	$(document).click(function(event) {
		if (!event.target.matches('.dropbtn')) {
			$(".dropdown-content").hide();
		}
	});
	$(document.body).delegate(".dropbtn", "click",function(event){
		var target =$(event.target);
		target.next(".dropdown-content").toggle();
	});
}
function scrollToElement(elt) {
    var defaultRemove = 0;
    var position = elt.offset().top - pageHeadHeight;
    if (position > defaultRemove) {
        position -= defaultRemove;
    }
    $("html, body").animate({
        scrollTop: position
    });
}

/**
 * Affiche un message d'erreur dans une div
 *
 * Case 1 : Return value if present in $_POST / $_GET
 * Case 2 : Return object value
 *
 * @param object $obj Object
 * @param string $key Field name
 * @param array $id_lang Language id (optional)
 * @return string
 */
function displayErrorOnDiv (divErrors, errors, scrollToDiv = true) {
   divErrors.html(getErrorsContent(errors));
   if(scrollToDiv){
	   scrollToElement (divErrors); 
   }
}
function displaySuccessOnDiv (divSuccess, message) {
	var htmlContent="";
	if(ps_version>='1.6'){
		htmlContent+="<div class=\"bootstrap\"><div class=\"alert alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>";
		htmlContent+=message;
		htmlContent+="</div></div>";
	}else{
		htmlContent+='<div class="module_confirmation conf confirm">'+message+'</div>';
	}
   
	divSuccess.html(htmlContent);
    scrollToElement (divSuccess);
}
function displayInfoOnDiv(divInfo, message) {
	var htmlContent="";
	if(ps_version>='1.6'){
		htmlContent += "<div class=\"bootstrap\"><div class=\"alert alert-info\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>";
	    htmlContent += message;
	    htmlContent += "</div></div>";
	}else{
		htmlContent+='<div class="hint">'+message+'</div>';
	}
    divInfo.html(htmlContent);
    scrollToElement(divInfo);
}
function showModalLoader(message){
	if(ps_version>='1.6'){
		dialog = $(
				'<div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:15%; overflow-y:visible;">' +
				'<div class="modal-dialog modal-m" style="width: 220px;">' +
				'<div class="modal-content">' +
					'<div class="modal-body">' +getLoaderContent(message)+
					'</div>' +
				'</div></div></div>');
		dialog.modal();
		setModalMaxHeight(dialog);
	}else{
		dialogLoader = $( "#dialogLoader");
		dialogLoader.find(".loader-text:first").html(message);
		dialogLoader.dialog({modal: true, closeOnEscape: false, dialogClass: "no-title-bar no-close"});
	}
	
}
function getLoaderContent(message) {
    return '<div class="loader"></div><div class="loader-text">' + message + '</div>';
}
function getErrorsContent(errors) {
	if(ps_version>='1.6'){
		var htmlContent = "<div class=\"bootstrap\"><div class=\"alert alert-danger\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>";
	    htmlContent += "<ol>";
	    for (i in errors) {
	        htmlContent += "<li>" + errors[i] + "</li>";
	    }
	    htmlContent += "</ol>";
	    htmlContent += "</div>";
	    htmlContent += "</div>";
	    return htmlContent;
	}else{
		var htmlContent = "<div class=\"module_error alert error\">";
	    htmlContent += "<ol>";
	    for (i in errors) {
	        htmlContent += "<li>" + errors[i] + "</li>";
	    }
	    htmlContent += "</ol>";
	    htmlContent += "</div>";
	    return htmlContent;
	}
}
function hideModalLoader() {
	if(ps_version>='1.6'){
		if (dialog!==undefined) {
	        dialog.modal("hide");
	    }
	}else{
		if (dialogLoader!==undefined) {
			dialogLoader.dialog( "close" );
	    }
	}
}
function changeTab (nav, menuManager) {
    var id = nav.attr('id');
    $('.nav-optiongroup').removeClass('selected');
    nav.addClass('selected active');
    nav.siblings().removeClass('active');
    $('.tab-optiongroup').hide();
    $('.' + id).show();
    var txtCurrentNav = $('#txtCurrentNav');
    if ((txtCurrentNav != null) && (txtCurrentNav.length > 0)) {
        txtCurrentNav.val(id);
    }
    menuManager.onTabChange(nav);
}
function manageTabs (menuManager) {
    $('div.productTabs').find('a.nav-optiongroup').click(function (event) {
        event.preventDefault();
        changeTab($(this), menuManager);
    });
}

function setModalMaxHeight(element) {
    this.$element     = $(element);  
    this.$content     = this.$element.find('.modal-content');
    var borderWidth   = this.$content.outerHeight() - this.$content.innerHeight();
    var dialogMargin  = $(window).width() < 768 ? 20 : 60;
    var contentHeight = $(window).height() - (dialogMargin + borderWidth);
    var headerHeight  = this.$element.find('.modal-header').outerHeight() || 0;
    var footerHeight  = this.$element.find('.modal-footer').outerHeight() || 0;
    var maxHeight     = contentHeight - (headerHeight + footerHeight);

    this.$content.css({
        'overflow': 'hidden'
    });
  
    this.$element
      .find('.modal-body').css({
          'max-height': maxHeight,
          'overflow-y': 'auto'
      });
}
function getRichTextContent(textarea) {
	var EMPTY_CONTENT='<br data-mce-bogus="1">';
	if(ps_version>="1.6"){
	    var divMCE = textarea.prev();
	    var iframeMCE = divMCE.find(".mce-container-body:first").find(".mce-edit-area:first").find("iframe:first");
    }else{
	    var divMCE = textarea.next();
	    var iframeMCE = divMCE.find(".mceLayout:first").find(".mceIframeContainer:first").find("iframe:first");;
    }
    
    var frameBody = iframeMCE.contents().find("html:first>body:first");
    var contentBlock = frameBody.find("p:first");
    if ((contentBlock.length == 0) || (contentBlock.html().trim() == EMPTY_CONTENT)) {
        return "";
    } else {
        return frameBody.html();
    }
}
function getListNoRecordContent() {
	
    var htmlContent = '<tr>';
	if(ps_version>='1.6'){
		htmlContent +='<td class="list-empty" colspan="4">' +
        '<div class="list-empty-msg">' +
        '<i class="icon-warning-sign list-empty-icon"></i>' + noRecordMessage +
	    '</div>' +
	    '</td>';
	}else{
		htmlContent +='<td class="center" colspan="4">' +noRecordMessage +'</td>';
	}
	htmlContent +='</tr>';
    return htmlContent;
}
function getStatusTdContent(active) {
	var linkClass = "";
	if(ps_version>="1.6"){
		linkClass+="list-action-enable " + ((active) ? 'action-enabled' : 'action-disabled');
    	var activeContent = '<i class="icon-check'+((active) ? '' : 'hidden')+'"></i>' +
    	'<i class="icon-remove' + ((active) ? 'hidden' : '') + '"></i>';
    }else{
    	var activeContent = getActiveIcon(active);
    }
    var htmlContent = '<a class="' + linkClass +'" href="" title="' + ((active) ? enabledMessage : disabledMessage) + '">' +
    activeContent +
    '</a>';
    return htmlContent;
}
function getActiveIcon(active) {
	return '<img src="../img/admin/'+((active) ? 'enabled' : 'disabled')+'.gif" alt="'+((active) ? enabledMessage : disabledMessage)+'">';
}
function getStatusDivContent(active) {
	if(ps_version>="1.6"){
    	var activeContent = '<span data-toggle="tooltip" class="label-tooltip" data-html="true" data-placement="top" ' +
        'data-original-title="'+((active) ? enabledMessage : disabledMessage)+'">'+
        ((active) ? '<i class="icon-check"></i>' : '<i class="icon-remove"></i>')+
        '</span>';
    }else{
    	var activeContent = getActiveIcon(active);
    }
    var htmlContent = '<a class="mp-icon-status list-action-enable ' + ((active) ? 'action-enabled' : 'action-disabled')+
            '" href="#" title="'+((active) ? enabledMessage : disabledMessage)+'">'+ activeContent+'</a>';
    return htmlContent;
}
$('.modal').on('show.bs.modal', function() {
    $(this).show();
    setModalMaxHeight(this);
});

$(window).resize(function() {
    if ($('.modal.in').length != 0) {
        setModalMaxHeight($('.modal.in'));
    }
});

function removePrevString(content, key) {
	var index = content.lastIndexOf(key);
	var start = key.length+index;
	var end = content.length;
    return content.substring(start, end);
}

function formatListTotal(parentBlock) {
	var tdTotal = parentBlock.find(".table_grid tbody tr:first td:first-child");
	var content = tdTotal.find("span:first-child").html();
	content = removePrevString(content, "</select>").trim();
	content = removePrevString(content, "/").trim();
	var count = content;
	var label = "";
	if(isNaN(content)){
		var index = content.indexOf(" ");
		count = content.substring(0, index).trim();
		label = content.replace(count,"").trim();
	}
	var htmlContent = '<span class="list-total">'+count+'</span><span class="total-label">'+label+'</span>';
	tdTotal.html(htmlContent);
	return tdTotal.find(".list-total:first");
}

function getListActionContent(showDuplicate = true) {
	var htmlContent = "";
	if(ps_version>='1.6'){
		htmlContent +='<td class="text-right">'
		+ '<div class="btn-group-action">'
		+ '<div class="btn-group pull-right">'
		+ '<a href="" title="'+ updateButtonText+ '" class="edit btn btn-default" onclick="">'
		+ '<i class="icon-pencil"></i> '
		+ updateButtonText
		+ '</a>'
		+ '<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">'
		+ '<i class="icon-caret-down"></i>&nbsp;' + '</button>'
		+ '<ul class="dropdown-menu">' 
		+ (showDuplicate?('<li><a href="#" title="'+ duplicateButtonText + '" onclick=""><i class="icon-copy"></i> ' + duplicateButtonText + '</a></li>'):'') 
		+ '<li class="divider"></li>' + 
		'<li>'+ '<a href="iule_n=" onclick="" title="' + deleteButtonText+ '" class="delete">'
		+ '<i class="icon-trash"></i>'
		+ deleteButtonText + '</a>' + '</li>'
		+ '</ul>' + '</div>'
		+ '</div>	' + '</td>' ;
	}else{
		htmlContent +='<td class="center" style="white-space: nowrap;">'
			+ '<a href="" title="'+ updateButtonText+ '" class="edit" onclick="">'
			+ '<img src="../img/admin/edit.gif" alt="'+updateButtonText+'">'
			+ '</a>'
			+ (showDuplicate?
					('<a href="#" title="'+ duplicateButtonText
							+ '" onclick="" class="pointer">'
							+ '<img src="../img/admin/duplicate.png" alt="'+duplicateButtonText+'"></a>')
					:'') 
			+ '<a href="" onclick="" title="' + deleteButtonText+ '" class="delete">'
			+ '<img src="../img/admin/delete.gif" alt="'+deleteButtonText+'">'
			+ '</a>'
			+ '</td>' ;
	}
	return htmlContent;
}

function handleCollapseEvent() {
	$(document.body).delegate(".collapse-group .collapse-item .collapse-head .collapse-action", "click", function (event) {
		event.preventDefault();
		var target = $(this);
        var selector = "#" + target.attr("aria-controls");
        var selectorGroup = target.attr("data-parent");
        var collapseHead = target.closest(".collapse-head");
        var collapseItem = collapseHead.closest(".collapse-item");
        var collapseTarget = collapseItem.find(selector);
        var wasClose = !collapseTarget.hasClass("in");
        if(wasClose){
        	var multipleOpen = true;
        	if(selectorGroup!==undefined){
        		var collapseGroup = collapseItem.closest(selectorGroup);
            	multipleOpen = (collapseGroup.attr("aria-multiselectable")==="true")?true:false;
                if(!multipleOpen){
                	collapseGroup.find(".collapse-head").removeClass("active");
                	collapseGroup.find(".collapse-target").removeClass("in");
                }
        	}
            collapseTarget.addClass("in");
            collapseHead.addClass("active");
            collapseTarget.trigger("shown.bs.collapse");
        }else{
        	collapseTarget.removeClass("in");
            collapseHead.removeClass("active");
        }
    });
}

function handleTabsEvent() {
	$(document.body).delegate(".nav.nav-tabs>li>a", "click", function (event) {
		event.preventDefault();
		var target = $(this);
        var selector = target.attr("href");
        var tabLi = target.closest("li");
        var tabUl = tabLi.closest(".nav-tabs");
        var tabContent = tabUl.nextAll(".tab-content");
        tabContent.find(".tab-pane").removeClass("active");
        tabUl.children().removeClass("active");
        var tabPane = tabContent.find(selector);
        tabPane.addClass("active");
        tabLi.addClass("active");
    });
}

function getLangById(id) {
	for(i in languages){
		if(languages[i]['id_lang']==id){
			return languages[i];
		}
	}
}

function resetMCE() {
	clearMCE();
	initMCE();
}

function clearMCE() {
	for (var i = tinymce.editors.length - 1 ; i > -1 ; i--) {
	    var ed_id = tinymce.editors[i].id;
	    tinyMCE.execCommand("mceRemoveEditor", true, ed_id);
	}
}

function initMCE() {
	tinySetup({
		editor_selector :"autoload_rte"
	});
}

