/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function csaccordion(status)
{
	if(status == 'enable')
	{
		$('.block-elt h4').on('click', function(){
			$(this).toggleClass('active').parent().find('.toggle-footer').stop().slideToggle('medium');
		})
		$('.block-elt').addClass('accordion').find('.toggle-footer').slideUp('fast');
	}
	else
	{
		$('.block-elt h4').removeClass('active').off().parent().find('.toggle-footer').removeAttr('style').slideDown('fast');
		$('.block-elt').removeClass('accordion');
	}
}
/**
 * this function assign max height element on all element
 * @param {type} e
 * @returns {undefined}
 */
function assignMaxHeight(e){
    var h = 0;
    $(e).each(function(){
        if ($(this).height() > h)
            h = $(this).height();
    });
    $(e).height(h);
}

