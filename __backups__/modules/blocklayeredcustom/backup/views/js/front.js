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
var currentProductAttibutesPage = 1;
var blcSelectableManager;
var blcAttributeFilterManager
$(document).ready(function () {
	blcSelectableManager = new SelectableManager();
	blcSelectableManager.onReady();
	blcAttributeFilterManager = new AttributeFilterManager();
	blcAttributeFilterManager.onReady();
	blcAttributeFilterManager.showHideLoadMore();
	
	$(".blc-filter-block").delegate(".filter-left .filter-item .filter-head", "click", function (event) {
		var target = $(this);
		var filterContent = target.closest(".filter-item").find(".filter-content");
		if(filterContent.hasClass("open")){
			filterContent.removeClass("open").addClass("close");
			target.find(".filter-icon").removeClass("open").addClass("close");
			target.find(".filter-icon>*").removeClass("fa-minus").addClass("fa-plus");
		}else{
			filterContent.removeClass("close").addClass("open");
			target.find(".filter-icon").removeClass("close").addClass("open");
			target.find(".filter-icon>*").removeClass("fa-plus").addClass("fa-minus");
		}
	});
});

function displayBlcError(errors, div){
	var arrayErrors = Array.isArray(errors)?errors:[errors];
	div.html(getErrorsContent(errors));
}

function getErrorsContent(errors) {
    var htmlContent = "<div class=\"bootstrap\"><div class=\"alert alert-danger\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>";
    htmlContent += "<ol>";
    for (i in errors) {
        htmlContent += "<li>" + errors[i] + "</li>";
    }
    htmlContent += "</ol>";
    htmlContent += "</div>";
    htmlContent += "</div>";
    return htmlContent;
}

text_truncate = function(str, length, ending) {
    if (length == null) {
      length = 100;
    }
    if (ending == null) {
      ending = '...';
    }
    if (str.length > length) {
      return str.substring(0, length - ending.length) + ending;
    } else {
      return str;
    }
};