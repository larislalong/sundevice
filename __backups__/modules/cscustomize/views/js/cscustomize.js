function csaccordion(e){"enable"==e?($(".block-elt h4").on("click",function(){$(this).toggleClass("active").parent().find(".toggle-footer").stop().slideToggle("medium")}),$(".block-elt").addClass("accordion").find(".toggle-footer").slideUp("fast")):($(".block-elt h4").removeClass("active").off().parent().find(".toggle-footer").removeAttr("style").slideDown("fast"),$(".block-elt").removeClass("accordion"))}function assignMaxHeight(e){var o=0;$(e).each(function(){$(this).height()>o&&(o=$(this).height())}),$(e).height(o)}