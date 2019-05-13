{if isset($products) && $products}
	<!-- Products list -->
	<ul{if isset($id) && $id} id="{$id}"{/if} class="product_list {if isset($show_as_grid) && $show_as_grid}row grid{else}list{/if} product_content {if isset($class) && $class} {$class}{/if}">
	{foreach from=$products item=product name=products}
		{if isset($has_big_item) && $has_big_item}
			{if $smarty.foreach.products.first}
				<div class="col-xs-12 col-sm-6 col-lg-6">
					<li class="ajax_block_product big_item">
			{else}
				<li class="ajax_block_product">
			{/if}
		{elseif isset($show_in_menu) && $show_in_menu}
			<li class="ajax_block_product col-xs-12">
		{else}
			{if isset($show_as_grid) && $show_as_grid}
				<li class="ajax_block_product col-xs-12 col-sm-6 col-lg-4">
			{else}
				<li class="ajax_block_product {*col-xs-12 col-sm-6 col-lg-4*}">
			{/if}
		{/if}
			<div class="product-container item" itemscope itemtype="https://schema.org/Product">
				<div class="left-block">
					<a class="img_content" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url">
						<img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')|escape:'html'}"
						alt="{$product.legend|escape:'html':'UTF-8'}"
						class="img-responsive"/>
					</a>
					{* {if isset($product.new) && $product.new == 1}
						<a class="new-box" href="{$product.link|escape:'html':'UTF-8'}">
							<span class="new-label">{l s='New'}</span>
						</a>
					{/if}
					{if isset($product.on_sale) && $product.on_sale && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}
						<a class="sale-box" href="{$product.link|escape:'html':'UTF-8'}">
							<span class="sale-label">{l s='Sale'}</span>
						</a>
					{/if} *}
					{if !isset($show_on_home) || !$show_on_home}
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
					{/if}
					<div class="btn_container">
						{hook h='displayProductListFunctionalButtons' product=$product}
						{*if isset($comparator_max_item) && $comparator_max_item}
							<a class="add_to_compare" href="{$product.link|escape:'html':'UTF-8'}" title="{l s='Add to Compare'}" data-id-product="{$product.id_product}">
								<i class="icon_tags_alt"></i>
							</a>
						{/if}
						{if isset($quick_view) && $quick_view}
							<a 	title="{l s='Quick view'}"
								class="quick-view"
								href="{$product.link|escape:'html':'UTF-8'}">
								<i class="arrow_expand"></i>
							</a>
						{/if*}
					</div>
					<div class="hook-reviews">
						{hook h='displayProductListReviews' product=$product}
					</div>
				</div>
				<div class="right-block">
					<h5>
						<a class="product-name fontcustom1" href="{$product.link|escape:'html'}" title="{$product.name|escape:'htmlall':'UTF-8'}">
							{$product.name|escape:'htmlall':'UTF-8'}
						</a>
					</h5>
					{*<p class="manufacturer_name">
						{$product.manufacturer_name}
					</p>*}
					{*<div class="hook-reviews-right">
						{hook h='displayProductListReviews' product=$product}
					</div>*}
					{if $logged}
					<div class="price-box clearfix">
						<meta itemprop="priceCurrency" content="{$priceDisplay}" />
						<span class="price fontcustom1">{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}</span>
						{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
							<span class="old-price product-price">
								{displayWtPrice p=$product.price_without_reduction}
							</span>
						{/if}
					</div>
					{/if}
					{if !isset($show_on_home) || !$show_on_home}
						<p class="product-desc">
							{$product.description_short|strip_tags:'UTF-8'|truncate:360:'...'}
						</p>
						{if !isset($show_in_menu) || !$show_in_menu}
						<div class="custom-add-to-cart clearfix">
							<input type="text" value="{$product.minimal_quantity|intval}" data-min="{$product.minimal_quantity|intval}" data-max="{$product.quantity|intval}" maxlength="12" id="qty_{$id}_{$product.id_product|intval}" name="qty" class="input-text qty">
							<div class="qty-control">
								<button type="button" class="button btn-plus" onclick="increaseQtyFeature('qty_{$id}_{$product.id_product|intval}');">
									<span>
										<span>+</span>
									</span>
								</button>
								<button type="button" class="button btn-minus" onclick="decreaseQtyFeature('qty_{$id}_{$product.id_product|intval}');">
									<span>
										<span>-</span>
									</span>
								</button>
							</div>
						</div>
						{/if}
					{else}
					<div class="attributes-values">
						{foreach $product.attributes_values as $value}
							<span class="value-item">{$value}</span>
						{/foreach}
					</div>
					{/if}
					{if !isset($show_on_home) || !$show_on_home}
					<div class="transfer">
						{if ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && $product.minimal_quantity >= 1 && $product.customizable != 2 && !$PS_CATALOG_MODE}
							{*$product.quantity|var_dump*}
							{if ($product.allow_oosp || $product.quantity > 0)}
								{if isset($static_token)}
									<a class="exclusive ajax_add_to_cart_button btn btn-default fontcustom1" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$product.id_product|intval}&amp;token={$static_token}", false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart'}" data-id-product="{$product.id_product|intval}" data-targeted-input="qty_{$id}_{$product.id_product|intval}" data-minimal_quantity="{$product.minimal_quantity|intval}">
										{l s='+ Add to cart'}
									</a>
								{else}
									<a class="exclusive ajax_add_to_cart_button btn btn-default fontcustom1" href="{$link->getPageLink('cart',false, NULL, 'add=1&amp;id_product={$product.id_product|intval}', false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart'}" data-id-product="{$product.id_product|intval}"data-targeted-input="qty_{$id}_{$product.id_product|intval}" data-minimal_quantity="{$product.minimal_quantity|intval}">
										{l s='+ Add to cart'}
									</a>
								{/if}
							{else}
								<span class="exclusive ajax_add_to_cart_button btn btn-default fontcustom1 disabled">
									{l s='+ Add to cart'}
								</span>
							{/if}
						{/if}
					</div>
					{/if}
				</div>
			</div><!-- .product-container> -->
		</li>
			
		{if isset($has_big_item) && $has_big_item}
			{if $smarty.foreach.products.first}
				</div>
				<div class="col-xs-12 col-sm-6 col-lg-6">
			{/if}
			{if $smarty.foreach.products.last}
				</div>
			{/if}
		{/if}
	{/foreach}
	</ul>
{/if}

{strip}
{addJsDefL name=min_item}{l s='Please select at least one product' js=1}{/addJsDefL}
{addJsDefL name=max_item}{l s='You cannot add more than %d product(s) to the product comparison' sprintf=$comparator_max_item js=1}{/addJsDefL}
{addJsDef comparator_max_item=$comparator_max_item}
{addJsDef comparedProductsIds=$compared_products}
{/strip}