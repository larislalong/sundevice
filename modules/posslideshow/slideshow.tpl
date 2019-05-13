<div class="slideshow_container">
<div class="pos-slideshow">
	<div class="flexslider ma-nivoslider">
        <div class="load-bar">
		  <div class="bar"></div>
		  <div class="bar"></div>
		  <div class="bar"></div>
		</div>
            <div id="pos-slideshow-home" class="slides">
                {$count=0}
                {foreach from=$slides key=key item=slide}
					
                    <img style ="display:none" src="{$slide.image}"  data-thumb="{$slide.image}"  alt="" title="#htmlcaption{$slide.id_pos_slideshow}"  />
			   {/foreach}
            </div>
			{foreach from=$slides key=key item=slide}
				<div id="htmlcaption{$slide.id_pos_slideshow}" class="pos-slideshow-caption nivo-html-caption nivo-caption">
					{if $slideOptions.show_caption != 0}
						<div class="pos-slideshow-info pos-slideshow-info{$slide.id_pos_slideshow}">
							<div class="container">
							<div class="pos_description">
									{$slide.description}
							</div>
							</div>
						</div>
					{/if}
					{if $slide.link}
						<div class="pos-slideshow-readmore">
							<a target="_blank" href="{$slide.link}" title="{l s=('Shop now') mod='posslideshow'}" class="titleFont">{l s=('Shop now') mod='posslideshow'}</a>	
						</div>
					{/if}
					<!-- <div class="pie_out visible-md visible-lg">
						<span class="demi-droit"></span>
						<span class="demi-gauche"></span>
					</div> -->
				</div>
			{/foreach}
        </div>
    </div>
    </div>
 <script type="text/javascript">
    $(window).load(function() {
        $('#pos-slideshow-home').nivoSlider({
			effect: '{if $slideOptions.animation_type != ''}{$slideOptions.animation_type}{else}random{/if}',
			slices: 15,
			boxCols: 8,
			boxRows: 4,
			animSpeed: '{if $slideOptions.animation_speed != ''}{$slideOptions.animation_speed}{else}600{/if}',
			pauseTime: '{if $slideOptions.pause_time != ''}{$slideOptions.pause_time}{else}5000{/if}',
			startSlide: {if $slideOptions.start_slide != ''}{$slideOptions.start_slide}{else}0{/if},
			directionNav: {if $slideOptions.show_arrow != 0}{$slideOptions.show_arrow}{else}false{/if},
			controlNav: {if $slideOptions.show_navigation != 0}{$slideOptions.show_navigation}{else}false{/if},
			controlNavThumbs: false,
			pauseOnHover: false,
			manualAdvance: false,
			prevText: '<i class="icon-angle-left"></i>',
			nextText: '<i class="icon-angle-right"></i>',
			afterLoad: function(){
				$('.load-bar').slideUp();
			},
 		});
    });
</script>
