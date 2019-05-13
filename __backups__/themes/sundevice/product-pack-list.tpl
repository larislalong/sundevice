<div class="packaging_left packaging_block" id="divPackegingLeft">
    {assign var=has_box value=false}
    {foreach from=$pack_list item='productPack'}
		{if $productPack.product->active == 1 and ($productPack.is_default || count($pack_list) == 1)}
			{$has_box = true}
			{if !$priceDisplay || $priceDisplay == 2}
				{assign var='productPricePack' value=$productPack.product->getPrice(true, $smarty.const.NULL, 6)}
				{assign var='productPriceWithoutReductionPack' value=$productPack.product->getPriceWithoutReduct(false, $smarty.const.NULL)}
			{elseif $priceDisplay == 1}
				{assign var='productPricePack' value=$productPack.product->getPrice(false, $smarty.const.NULL, 6)}
				{assign var='productPriceWithoutReductionPack' value=$productPack.product->getPriceWithoutReduct(true, $smarty.const.NULL)}
			{/if}
			
			<div>
				<input id="input_add_packaging" name="input_packaging" class="input_select_packaging" type="radio" value="1" checked />
				{*<a href="#helpAvailablePackaging" class="packaging-help-link"><i class="fa fa-question-circle" aria-hidden="true"></i></a>*}
				<label for="input_add_packaging">{l s='Ajouter un coffret'}</label>
				{if $productPack.product->show_price && !$PS_CATALOG_MODE}
				<span  itemprop="price" class="price product-price">{convertPrice price=$productPricePack}</span>
				{/if}
			</div>
			{break}
		{/if}
	{/foreach}
	{if $has_box}
	<div>
		<input id="input_no_packaging" name="input_packaging" class="input_select_packaging" type="radio" value="0"/>
		<label for="input_no_packaging">{l s='Sans coffret'}</label>
	</div>
	{/if}
</div>
<div id="helpAvailablePackaging" style="display:none;">{$available_packeging}</div>