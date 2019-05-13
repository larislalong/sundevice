{if count($categoryProducts) > 0 && $categoryProducts !== false}
	<div class="productscategory_block product_block_container">
		<div class="header_title_out">
			<h3>{l s='Related Products' mod='productscategory'}</h3>
			{if $categoryProducts|@count == 1}
				<p>{l s='%s other product in the same category:' sprintf=[$categoryProducts|@count] mod='productscategory'}</p>
			{else}
				<p>{l s='%s other products in the same category:' sprintf=[$categoryProducts|@count] mod='productscategory'}</p>
			{/if}
		</div>
		<div class="product_content block_content">
			<div class="navi">
				<a class="prevtab"><i class="arrow_carrot-left"></i></a>
				<a class="nexttab"><i class="arrow_carrot-right"></i></a>
			</div>
			<div class="row">
				<div class="productscategory">
					{foreach from=$categoryProducts item='categoryProduct' name=categoryProduct}
						<div class="item_out">
						<div class="item">
							<div class="left-block">
								<a href="{$link->getProductLink($categoryProduct.id_product, $categoryProduct.link_rewrite, $categoryProduct.category, $categoryProduct.ean13)}" class="img_content" title="{$categoryProduct.name|htmlspecialchars}"><img src="{$link->getImageLink($categoryProduct.link_rewrite, $categoryProduct.id_image, 'home_default')|escape:'html':'UTF-8'}" alt="{$categoryProduct.name|htmlspecialchars}" /></a>
							</div>
							<div class="right-block">
								<h5 itemprop="name" class="product-name">
									<a class="product-name" href="{$link->getProductLink($categoryProduct.id_product, $categoryProduct.link_rewrite, $categoryProduct.category, $categoryProduct.ean13)|escape:'html':'UTF-8'}" title="{$categoryProduct.name|htmlspecialchars}">{$categoryProduct.name|truncate:35:'...'|escape:'html':'UTF-8'}</a>
								</h5>
								{if (!$PS_CATALOG_MODE && ((isset($categoryProduct.show_price) && $categoryProduct.show_price) || (isset($categoryProduct.available_for_order) && $categoryProduct.available_for_order)))}
								<div class="price-box">
									{if isset($categoryProduct.specific_prices) && $categoryProduct.specific_prices && ($categoryProduct.price|number_format:2 !== $categoryProduct.price_without_reduction|number_format:2)}
										<span class="price special-price fontcustom1">{convertPrice price=$categoryProduct.price}</span>
										<span class="old-price">{displayWtPrice p=$categoryProduct.price_without_reduction}</span>
									{else}
										<span class="price fontcustom1">{convertPrice price=$categoryProduct.price}</span>
									{/if}
								</div>
								{/if}
								<div class="transfer">
									{if ($categoryProduct.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $categoryProduct.available_for_order && !isset($restricted_country_mode) && $categoryProduct.minimal_quantity <= 1 && $categoryProduct.customizable != 2 && !$PS_CATALOG_MODE}
										{if ($categoryProduct.allow_oosp || $categoryProduct.quantity > 0)}
											{if isset($static_token)}
												<a class="exclusive ajax_add_to_cart_button btn btn-default fontcustom1" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$product.id_product|intval}&amp;token={$static_token}", false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart' mod='posfeatureproduct'}" data-id-product="{$categoryProduct.id_product|intval}">
													{l s='+ Add to cart' mod='posfeatureproduct'}
												</a>
											{else}
												<a class="exclusive ajax_add_to_cart_button btn btn-default fontcustom1" href="{$link->getPageLink('cart',false, NULL, 'add=1&amp;id_product={$categoryProduct.id_product|intval}', false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart' mod='posfeatureproduct'}" data-id-product="{$categoryProduct.id_product|intval}">
													{l s='+ Add to cart' mod='posfeatureproduct'}
												</a>
											{/if}
										{else}
											<span class="exclusive ajax_add_to_cart_button btn btn-default fontcustom1 disabled">
												{l s='+ Add to cart' mod='posfeatureproduct'}
											</span>
										{/if}
									{/if}
								</div>
							</div>
						</div>
						</div>
					{/foreach}
				</div>
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function() {
			var productscategory = $(".productscategory");
			productscategory.owlCarousel({
				items : 4,
				itemsDesktop : [1199,3],
				itemsDesktopSmall : [991,2],
				itemsTablet: [767,2],
				itemsMobile : [480,1],
				autoPlay :  false,
				stopOnHover: false,
				addClassActive: true,
			});
			$(".productscategory_block .nexttab").click(function(){
				productscategory.trigger('owl.next');})
			$(".productscategory_block .prevtab").click(function(){
				productscategory.trigger('owl.prev');})
		});
	</script>
{/if}