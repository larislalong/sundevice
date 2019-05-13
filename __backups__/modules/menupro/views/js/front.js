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
 
$(document).ready(function () {
	const MOBILE_SIZE=943;
	//Side menu
	 $(".cat-sub-1 .mp_content_item").each(function(){
        var counts=$(this).children().length;
        if(counts>3){
           $(this).addClass('max-3');
        }else{
            $(this).addClass('items-'+counts);
        } 
    });
    
    $(".menu_side_bar_wrapper .content_menu_side_bar ul li .icone_side_responsive").click(function(){
		var divContent=$(this).next(".cat-sub-1");
		if ( divContent.css('display') == 'none' ){
			$(this).removeClass("ion-plus");
			$(this).addClass("ion-minus");
			divContent.removeClass("mp-hidden");
			divContent.addClass("mp-shown");
		}else{
			$(this).removeClass("ion-minus");
			$(this).addClass("ion-plus");
			divContent.removeClass("mp-shown");
			divContent.addClass("mp-hidden");
		} 
    });
	
	$(".simple-side-wrapper .icon_side_simple").click(function(){
		var divContent=$(this).next(".simple-side-container");
		if ( divContent.css('display') == 'none' ){
			$(this).removeClass("ion-plus");
			$(this).addClass("ion-minus");
			divContent.css({'display':'block'});
		}else{
			$(this).removeClass("ion-minus");
			$(this).addClass("ion-plus");
			divContent.css({'display':'none'});
		}
    });
	
	
	//Mega menu
	$(".menu-dropdown-icon").hover(
		function() {
			if ($(window).width() > MOBILE_SIZE) {
				$('.menu-sub-level-1', this).stop(true,true).slideDown("400");
				$(this).toggleClass('open');  
			}
		},
		function() {
			if ($(window).width() > MOBILE_SIZE) {
				$('.menu-sub-level-1', this).stop(true,true).slideUp("400");
				$(this).toggleClass('open');   
			}
		}
    );

    $(".mp_mega_menu .menu  .mp-icon-responsive").click(function (event) {
        $(this).next(".menu-sub-level-1").fadeToggle(150);
		$(this).toggleClass('mp-icon-minus','mp-icon-plus');
    });
	$( window ).resize(function() {
		$(".mp_mega_menu .menu-sub-level-1").css({'display':'none'});
	});

	
    $(".menu-mobile").click(function (e) {
        $(".menu > ul, .simple-menu > ul").toggleClass('show-on-mobile');
		e.preventDefault();
    });
	
	//Simple Menu
	$(".simple-menu  .mp-icon-responsive").click(function(event){
		var divContent=$(this).next(".simple-sub-menu");
		console.log(divContent);
		if ( divContent.css('display') == 'none' ){
			$(this).removeClass("mp-icon-plus");
			$(this).addClass("mp-icon-minus");
			divContent.removeClass("mp-hidden");
			divContent.addClass("mp-shown");
		}else{
			$(this).removeClass("mp-icon-minus");
			$(this).addClass("mp-icon-plus");
			divContent.removeClass("mp-shown");
			divContent.addClass("mp-hidden");
		}
    });
	
	//Set Active styleSheets
	var currentUrl=location.href;
	var currentLink=$(".mp-menu-link[href='"+currentUrl+"']:first");
	if(currentLink.length>0){
		var activeStyle=currentLink.attr("data-active-style");
		if((activeStyle!=null)&&(activeStyle!==undefined)){
			currentLink.attr("style",activeStyle);
		}
		var activeReset=currentLink.attr("data-active-reset");
		if((activeReset!=null)&&(activeReset!==undefined)){
			currentLink.attr("onmouseout",activeReset);
		}
	}
	if(ps_version<'1.6'){
    	handleLangDropdownClick();
    }
});

function handleLangDropdownClick() {
	$(document).click(function(event) {
		if (!event.target.matches('.dropdown-toggle')) {
			if(!$(event.target).closest(".dropdown-menu").length){
				$(".dropdown-toggle").closest(".dropdown").removeClass("open");
			}
		}
	});
	$(document.body).delegate(".dropdown-toggle", "click",function(event){
		var target =$(event.target);
		target.closest(".dropdown").toggleClass("open");
	});
}
