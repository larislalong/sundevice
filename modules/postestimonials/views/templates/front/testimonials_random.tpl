{if $testimonials}
	<div class="testimonial_container">
		<div class="header_title_out">
			<h3>{l s='What people say' mod='postestimonials'}</h3>
			<p>{l s='Testimonials' mod='postestimonials'}</p>
		</div>
		<div class="testimonial_inner">
			<div class="container">
				<div class="navi">
					<a class="prevtab fontcustom1">{l s='Prev' mod='postestimonials'}</a>
					<a class="nexttab fontcustom1">{l s='Next' mod='postestimonials'}</a>
				</div>
				<div class="testi_slider">
					{foreach from=$testimonials name=myLoop item=testimonial}
						{if $testimonial.active == 1}
							<div class="item">
								{if in_array($testimonial.media_type,$arr_img_type)}
									<img src="{$mediaUrl}{$testimonial.media}" class="testi_img img-responsive" alt="">
								{/if}
								<p class="des_testimonial">{$testimonial.content|escape:'html':'UTF-8'}</p>
								<p class="testi_author fontcustom1">{$testimonial.name_post}</p>
								<p class="testi_email">{$testimonial.email}</p>
							</div>
						{/if}
					{/foreach}
				</div>
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function() {
			var testi_slider = $(".testi_slider");
			testi_slider.owlCarousel({
				singleItem : true,
				autoPlay :  false,
				stopOnHover: false,
				addClassActive: true,
				paginationNumbers: true,
			});
			$(".testimonial_container .nexttab").click(function(){
				testi_slider.trigger('owl.next');})
			$(".testimonial_container .prevtab").click(function(){
				testi_slider.trigger('owl.prev');})
		});
	</script>
{/if}