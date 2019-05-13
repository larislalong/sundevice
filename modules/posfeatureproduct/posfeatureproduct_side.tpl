{if count($products) > 0 && $products != null}
<div class="posfeatureproduct_side block">
	<div class="title_block fontcustom1">
		{l s='Featured Products' mod='posfeatureproduct'}
	</div>
	<div class="product_left block_content">
	<div class="row">
	<div class="posfeatureslider">
		{foreach from=$products item=product name=myLoop}
			{if $smarty.foreach.myLoop.index % 3 == 0 || $smarty.foreach.myLoop.first }
				<div class="item_out">
			{/if}
				<div class="item">
					<div class="left-block">
						<a class="product_img_link"	href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url">
							<img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'small_default')|escape:'html'}"
							alt="{$product.legend|escape:'html':'UTF-8'}"
							class="img-responsive"/>
						</a>
					</div>
					<div class="right-block">
						<a class="product-name" href="{$product.link|escape:'html'}" title="{$product.name|truncate:50:'...'|escape:'htmlall':'UTF-8'}">
							{$product.name|escape:'htmlall':'UTF-8'}
						</a>
						<div class="price-box">
							<meta itemprop="priceCurrency" content="{$priceDisplay}" />
							<span class="price">
								{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}
							</span>
							{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
								<span class="old-price product-price">
									{displayWtPrice p=$product.price_without_reduction}
								</span>
							{/if}
						</div>
					</div>
				</div>
			{if $smarty.foreach.myLoop.iteration % 3 == 0 || $smarty.foreach.myLoop.last}
				</div>
			{/if}
		{/foreach}
	</div>
	</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		var feaSlide1 = $(".posfeatureproduct_side .posfeatureslider");
		feaSlide1.owlCarousel({
			singleItem: true,
			autoPlay :  5000,
			stopOnHover: true,
			addClassActive: true,
		});
	});
</script>
{/if}