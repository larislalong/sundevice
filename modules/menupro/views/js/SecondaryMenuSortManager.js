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

function SecondaryMenuSortManager(secondaryMenuTree) {
    var self = this;
    this.secondaryMenuTree=secondaryMenuTree;
    this.btnSortMenu = $("#btnSortMenu");
    var self = this;
    this.modalSortSecondaryMenu = $("#modalSortSecondaryMenu");
    this.divSortSecondaryMenuNotify = this.modalSortSecondaryMenu.find("#divSortSecondaryMenuNotify");
    this.modalSortSecondaryMenuContent = this.modalSortSecondaryMenu.find("#modalSortSecondaryMenuContent");
    this.btnConfirmSort = this.modalSortSecondaryMenu.find("#btnConfirmSort");
    this.onReady = function () {
        self.handleEvent();
        self.showHideSortButton();
    };
    this.showHideSortButton = function () {
        if($(".new-menu-panel").length>1){
            self.btnSortMenu.show();
        }else{
            self.btnSortMenu.hide();
        }
    };
    this.handleEvent = function () {
        self.btnSortMenu.on("click", function () {
        	if(ps_version>='1.6'){
        		self.modalSortSecondaryMenu.modal({
                    backdrop: 'static',
                    keyboard: true,
                    show: true
                });
            }else{
            	self.modalSortSecondaryMenu.dialog({
            		modal: true,
            		width : "80%",
                    buttons: [
                        {
                          text: btnSortCloseText,
                          icon: "ui-icon-heart",
                          "class" : "btn-dialog dialog-close",
                          click: function() {
                        	  self.closeModal();
                          }
                        },
                        {
                            text: btnSortReorganiseText,
                            icon: "ui-icon-heart",
                            id: "btnConfirmSort",
                            "class" : "btn-dialog dialog-ok btn-sort-items",
                            click: function() {
                            	self.onConfirmSortClick();
                            }
                          }
                      ],
                      
        		});
            }
        });
        self.btnConfirmSort.on("click", function () {
            self.onConfirmSortClick();
        });
        var dialogOpenEvent = (ps_version>='1.6')?"shown.bs.modal":"dialogopen";
        self.modalSortSecondaryMenu.on(dialogOpenEvent, function () {
            setModalMaxHeight(self.modalSortSecondaryMenu);
            /*$('body').css({
                transform: 'scale('+(50/100)+')', // set zoom
                transformOrigin: '50% 0' // set transform scale base
            });
            $('body').css({ width: '100%' });*/
            //document.body.style.zoom ="50%";
           // $('body').css({ zoom: '50%' });
           var htmlContent='<div class="sort-container">';
           htmlContent+='<h3 class="title" id="title0">'+mainMenu.name+'</h3>';
           htmlContent+=self.buildList(0,self.secondaryMenuTree.find("#accordionMenu0"));
           htmlContent+='</div>';
           self.modalSortSecondaryMenuContent.html(htmlContent);
           self.changeZoom("50%");
           self.handleSort();
        });
        var dialogCloseEvent = (ps_version>='1.6')?"hidden.bs.modal":"dialogclose";
        self.modalSortSecondaryMenu.on(dialogCloseEvent, function (e) {
            self.onModalClosed();
        });
    };
    this.changeZoom = function (value) {
        //self.modalSortSecondaryMenuContent.css({ zoom: value });
        //self.modalSortSecondaryMenuContent.animate({ 'zoom': 0.5 }, 400);
    };
    this.handleSort = function () {
        calcWidth($('#title0'));
        window.onresize = function (event) {
        };
        //recursively calculate the Width all titles
        function calcWidth(obj) {
            var titles =
                    $(obj).siblings('.space').children('.route').children('.title');
            $(titles).each(function (index, element) {
                var pTitleWidth = parseInt($(obj).css('width'));
                var leftOffset = parseInt($(obj).siblings('.space').css('margin-left'));
                var newWidth = pTitleWidth - leftOffset;
                if ($(obj).attr('id') == 'title0') {
                    newWidth = newWidth - 10;
                }
                $(element).css({
                    'width': newWidth,
                })
                calcWidth(element);
            });
        }
        $('.space').sortable({
            connectWith: '.space',
            tolerance: 'intersect',
            over: function (event, ui) {

            },
            receive: function (event, ui) {
                calcWidth($(this).siblings('.title'));
            },
        });
        $('.space').disableSelection();
    };
    this.onConfirmSortClick = function () {
        var values=self.getValue(0,self.modalSortSecondaryMenuContent.find("#space0"),1);
        self.processSortItems(values);
    };
    this.getValue = function (idParent, divParent,level) {
        var values=[];
        var subMenus=divParent.children();
        var itemsCount=subMenus.length; 
        if(itemsCount>0){
            var i=1;
            subMenus.each(function () {
                var item={};
                var target=$(this);
                var id=target.attr("data-id");
                item.id=id;
                item.parent=idParent;
                item.position=i;
                item.level=level;
                values.push(item);
                var divNextParent=target.find("#space"+id);
                values=$.merge(values,self.getValue(id,divNextParent,(level+1)));
                i++;
            });
        }
        return values;
    };
    this.processSortItems = function (values) {
        self.showNotify();
        showModalLoader(loaderSortMenuMessage);
        $.ajax({
            url: ajaxModuleUrl,
            data: {
                ajax: true,
                action: "SortMenu",
                MENUPRO_MENU_SORT_SORTED_ITEMS: values,
                MENUPRO_MENU_SORT_MAIN_MENU_ID:mainMenu.id
            },
            success: function (result) {
                if (result['status'] == 'success') {
                    location.href=result['data']['link'];
                    //self.closeModal();
                } else {
                    hideModalLoader();
                    displayErrorOnDiv(self.divSortSecondaryMenuNotify, result['data']);
                }
            },
            error: function () {
                hideModalLoader();
                displayErrorOnDiv(self.divSortSecondaryMenuNotify, [ajaxRequestErrorMessage]);
            },
            type: 'post',
        });
    };
    this.getSortableHeaderContent = function (idParent, id, name, isFirst) {
        var htmlContent='';
        if(isFirst){
            htmlContent +='<ul class="space" id="space'+idParent+'">';
        }
        htmlContent += '<li class="route" data-id="'+id+'">'+
        ' <h3 class="title" id="title'+id+'">'+name+'</h3>'+
        '<span class="ui-icon ui-icon-arrow-4-diag"></span>';
        return htmlContent;
    };
    this.getSortableFooterContent = function (isLast) {
        var htmlContent='</li>';
        if (isLast) {
            htmlContent += '</ul>';
        }
        return htmlContent;
    };
    this.buildList = function (idParent, divParent) {
        var htmlContent='';
        itemsCount=divParent.length;
        if(itemsCount>0){
           var subMenus=divParent.children();
           var itemsCount=subMenus.length; 
        }
        if(itemsCount>0){
            var firstItems=true;
            var lastItems=false;
            var i=0;
            subMenus.each(function () {
                var target=$(this);
                var divAction=target.find(".menu-action:first");
                var id=divAction.attr("data-id");
                htmlContent+=self.getSortableHeaderContent(idParent,id,divAction.attr("data-name"),firstItems);
                var divNextParent=target.find("#accordionMenu"+id);
                htmlContent+=self.buildList(id,divNextParent);
                firstItems=false;
                lastItems=(i==(itemsCount-1));
                htmlContent+=self.getSortableFooterContent(lastItems);
                i++;
            });
        }else{
            htmlContent+='<ul class="space" id="space'+idParent+'"></ul>';
        }
        return htmlContent;
    };
    this.showNotify = function () {
        self.divSortSecondaryMenuNotify.show();
        self.divSortSecondaryMenuNotify.html("");
    };
    this.hideNotify = function () {
        self.divSortSecondaryMenuNotify.hide();
        self.divSortSecondaryMenuNotify.html("");
    };
    this.onModalClosed = function () {
         self.changeZoom("normal");
         self.modalSortSecondaryMenuContent.html("");
    };
    this.closeModal = function () {
        if(ps_version>='1.6'){
    		self.modalSortSecondaryMenu.modal('hide');
    	}else{
    		self.modalSortSecondaryMenu.dialog("close");
    	}
    };
}