{if count($products) > 0 && $products != null}
<div class="posfeatureproduct product_block_container">
	<div class="container_out">
		<div class="header_title_out">
			<h3>{l s='Featuring Products' mod='posfeatureproduct'}</h3>
			<p>{l s='Trending & stunning. Unique.' mod='posfeatureproduct'}</p>
		</div>
		<div class="product_content">
			<div class="row">
				<div class="posfeatureslider">
					{foreach from=$products item=product name=myLoop}
						{if $smarty.foreach.myLoop.index % 1 == 0 || $smarty.foreach.myLoop.first }
								<div class="item_out">
							{/if}
								<div class="item">
										<div class="left-block">
											<img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'large_default')|escape:'html'}"
											alt="{$product.legend|escape:'html':'UTF-8'}"
											class="img-responsive"/>
											{if (!$PS_CATALOG_MODE && ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
												{if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
													{if $product.price_without_reduction > 0 && isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
														{if $product.specific_prices.reduction_type == 'percentage'}
															<div class="item_reduction">
																<span>-{$product.specific_prices.reduction * 100}%</span>
															</div>
														{/if}
													{/if}
												{/if}
											{/if}
										</div>
										<div class="right-block">
											<h5>
												<a class="product-name fontcustom1" href="{$product.link|escape:'html'}" title="{$product.name|truncate:50:'...'|escape:'htmlall':'UTF-8'}">
													{$product.name|escape:'htmlall':'UTF-8'}
												</a>
											</h5>
											<p class="manufacturer_name">
												{$product.manufacturer_name}
											</p>
											<div class="hook-reviews">
												{hook h='displayProductListReviews' product=$product}
											</div>
											<div class="price-box">
												<meta itemprop="priceCurrency" content="{$priceDisplay}" />
												<span class="price fontcustom1">{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}</span>
												{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
													<span class="old-price product-price">
														{displayWtPrice p=$product.price_without_reduction}
													</span>
												{/if}
											</div>
											<div class="transfer">
												{if ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && $product.minimal_quantity <= 1 && $product.customizable != 2 && !$PS_CATALOG_MODE}
													{if ($product.allow_oosp || $product.quantity > 0)}
														{if isset($static_token)}
															<a class="exclusive ajax_add_to_cart_button btn btn-default fontcustom1" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$product.id_product|intval}&amp;token={$static_token}", false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart' mod='posfeatureproduct'}" data-id-product="{$product.id_product|intval}">
																{l s='+ Add to cart' mod='posfeatureproduct'}
															</a>
														{else}
															<a class="exclusive ajax_add_to_cart_button btn btn-default fontcustom1" href="{$link->getPageLink('cart',false, NULL, 'add=1&amp;id_product={$product.id_product|intval}', false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart' mod='posfeatureproduct'}" data-id-product="{$product.id_product|intval}">
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
							{if $smarty.foreach.myLoop.iteration % 1 == 0 || $smarty.foreach.myLoop.last}
								</div>
							{/if}
					{/foreach}
				</div>
			</div>
		</div>
	</div>
</div>
{/if}