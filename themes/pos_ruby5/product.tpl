{include file="$tpl_dir./errors.tpl"}
{if $errors|@count == 0}
	{if !isset($priceDisplayPrecision)}
		{assign var='priceDisplayPrecision' value=2}
	{/if}
	{if !$priceDisplay || $priceDisplay == 2}
		{assign var='productPrice' value=$product->getPrice(false, $smarty.const.NULL, 6)}
		{assign var='productPriceWithoutReduction' value=$product->getPriceWithoutReduct(false, $smarty.const.NULL)}
	{elseif $priceDisplay == 1}
		{assign var='productPrice' value=$product->getPrice(false, $smarty.const.NULL, 6)}
		{assign var='productPriceWithoutReduction' value=$product->getPriceWithoutReduct(false, $smarty.const.NULL)}
	{/if}
<div itemscope itemtype="https://schema.org/Product">
	<meta itemprop="url" content="{$link->getProductLink($product)}">
	<div class="primary_block row">
		{if isset($adminActionDisplay) && $adminActionDisplay}
			<div id="admin-action" class="container">
				<p class="alert alert-info">{l s='This product is not visible to your customers.'}
					<input type="hidden" id="admin-action-product-id" value="{$product->id}" />
					<a id="publish_button" class="btn btn-default button button-small" href="#">
						<span>{l s='Publish'}</span>
					</a>
					<a id="lnk_view" class="btn btn-default button button-small" href="#">
						<span>{l s='Back'}</span>
					</a>
				</p>
				<p id="admin-action-result"></p>
			</div>
		{/if}
		{if isset($confirmation) && $confirmation}
			<p class="confirmation">
				{$confirmation}
			</p>
		{/if}
		
		<!-- left infos-->
		<div class="pb-left-column col-xs-12 col-sm-4 col-md-5">
			<!-- product img-->
			<div id="image-block" class="clearfix">
				{*if $product->new}
					<span class="new-box">
						<span class="new-label">{l s='New'}</span>
					</span>
				{/if*}
				{*if $product->on_sale}
					<span class="sale-box no-print">
						<span class="sale-label">{l s='Sale!'}</span>
					</span>
				{elseif $product->specificPrice && $product->specificPrice.reduction && $productPriceWithoutReduction > $productPrice}
					<span class="discount">{l s='Reduced price!'}</span>
				{/if*}
				{if $have_image}
					<span id="view_full_size">
						{if $jqZoomEnabled && $have_image && !$content_only}
							<a class="jqzoom" title="{if !empty($cover.legend)}{$cover.legend|escape:'html':'UTF-8'}{else}{$product->name|escape:'html':'UTF-8'}{/if}" rel="gal1" href="{$link->getImageLink($product->link_rewrite, $cover.id_image, 'thickbox_default')|escape:'html':'UTF-8'}">
								<img itemprop="image" src="{$link->getImageLink($product->link_rewrite, $cover.id_image, 'large_default')|escape:'html':'UTF-8'}" title="{if !empty($cover.legend)}{$cover.legend|escape:'html':'UTF-8'}{else}{$product->name|escape:'html':'UTF-8'}{/if}" alt="{if !empty($cover.legend)}{$cover.legend|escape:'html':'UTF-8'}{else}{$product->name|escape:'html':'UTF-8'}{/if}"/>
							</a>
						{else}
							<img id="bigpic" itemprop="image" src="{$link->getImageLink($product->link_rewrite, $cover.id_image, 'large_default')|escape:'html':'UTF-8'}" title="{if !empty($cover.legend)}{$cover.legend|escape:'html':'UTF-8'}{else}{$product->name|escape:'html':'UTF-8'}{/if}" alt="{if !empty($cover.legend)}{$cover.legend|escape:'html':'UTF-8'}{else}{$product->name|escape:'html':'UTF-8'}{/if}" width="{$largeSize.width}" height="{$largeSize.height}"/>
							{if !$content_only}
								<span class="span_link no-print">{l s='View larger'}</span>
							{/if}
						{/if}
					</span>
				{else}
					<span id="view_full_size">
						<img itemprop="image" src="{$img_prod_dir}{$lang_iso}-default-large_default.jpg" id="bigpic" alt="" title="{$product->name|escape:'html':'UTF-8'}" width="{$largeSize.width}" height="{$largeSize.height}"/>
						{if !$content_only}
							<span class="span_link">
								{l s='View larger'}
							</span>
						{/if}
					</span>
				{/if}
			</div> <!-- end image-block -->
			{if isset($images) && count($images) > 0}
				<!-- thumbnails -->
				<div id="views_block" class="clearfix {if isset($images) && count($images) < 2}hidden{/if}">
					<div class="navi {if isset($images) && count($images) < 5}hidden{/if}">
						<a class="prevtab"><i class="icon-angle-left"></i></a>
						<a class="nexttab"><i class="icon-angle-right"></i></a>
					</div>
					<div class="row_edited">
						<div id="thumbs_list">
							{if isset($images)}
								{foreach from=$images item=image name=thumbnails}
									{assign var=imageIds value="`$product->id`-`$image.id_image`"}
									{if !empty($image.legend)}
										{assign var=imageTitle value=$image.legend|escape:'html':'UTF-8'}
									{else}
										{assign var=imageTitle value=$product->name|escape:'html':'UTF-8'}
									{/if}
									<div id="thumbnail_{$image.id_image}" class="item">
										<a{if $jqZoomEnabled && $have_image && !$content_only} href="javascript:void(0);" rel="{literal}{{/literal}gallery: 'gal1', smallimage: '{$link->getImageLink($product->link_rewrite, $imageIds, 'large_default')|escape:'html':'UTF-8'}',largeimage: '{$link->getImageLink($product->link_rewrite, $imageIds, 'thickbox_default')|escape:'html':'UTF-8'}'{literal}}{/literal}"{else} href="{$link->getImageLink($product->link_rewrite, $imageIds, 'thickbox_default')|escape:'html':'UTF-8'}"	data-fancybox-group="other-views" class="fancybox{if $image.id_image == $cover.id_image} shown{/if}"{/if} title="{$imageTitle}">
											<img class="img-responsive" id="thumb_{$image.id_image}" src="{$link->getImageLink($product->link_rewrite, $imageIds, 'small_default')|escape:'html':'UTF-8'}" alt="{$imageTitle}" title="{$imageTitle}" itemprop="image" />
										</a>
									</div>
								{/foreach}
							{/if}
						</div> <!-- end thumbs_list -->
					</div> <!-- end thumbs_list -->
				</div> <!-- end views-block -->
				<!-- end thumbnails -->
			{/if}
			{if isset($images) && count($images) > 1}
				<p class="resetimg clear no-print">
					<span id="wrapResetImages" style="display: none;">
						<a href="{$link->getProductLink($product)|escape:'html':'UTF-8'}" data-id="resetImages">
							<i class="icon-repeat"></i>
							{l s='Display all pictures'}
						</a>
					</span>
				</p>
			{/if}
		</div> <!-- end pb-left-column -->
		<!-- end left infos-->
		<!-- center infos -->
		<div class="pb-center-column pb-right-column col-xs-12 col-sm-8 col-md-7">
			<h1 itemprop="name" class="col-xs-12">{$product->name|escape:'html':'UTF-8'}</h1>
		{if ($product->show_price && !isset($restricted_country_mode)) || isset($groups) || $product->reference || (isset($HOOK_PRODUCT_ACTIONS) && $HOOK_PRODUCT_ACTIONS)}
			<!-- add to cart form-->
		<form id="buy_block"{if $PS_CATALOG_MODE && !isset($groups) && $product->quantity > 0} class="hidden"{/if} action="{$link->getPageLink('cart')|escape:'html':'UTF-8'}" method="post" class="clearfix clear">
		{/if}
			<div class="pb-right-first-column col-xs-12 col-md-7 col-sm-6">
				{if $product->online_only}
					<p class="online_only">{l s='Online only'}</p>
				{/if}
				{*if $product->description_short || $packItems|@count > 0}
					<div id="short_description_block">
						{if $product->description_short}
							<div id="short_description_content" class="rte align_justify" itemprop="description">{$product->description_short}</div>
						{/if}

						{if $product->description}
							<p class="buttons_bottom_block">
								<a href="javascript:{ldelim}{rdelim}" class="button">
									{l s='More details'}
								</a>
							</p>
						{/if}
						<!--{if $packItems|@count > 0}
							<div class="short_description_pack">
							<h3>{l s='Pack content'}</h3>
								{foreach from=$packItems item=packItem}

								<div class="pack_content">
									{$packItem.pack_quantity} x <a href="{$link->getProductLink($packItem.id_product, $packItem.link_rewrite, $packItem.category)|escape:'html':'UTF-8'}">{$packItem.name|escape:'html':'UTF-8'}</a>
									<p>{$packItem.description_short}</p>
								</div>
								{/foreach}
							</div>
						{/if}-->
					</div> <!-- end short_description_block -->
				{/if*}
				{*if $PS_STOCK_MANAGEMENT}
					{if !$product->is_virtual}{hook h="displayProductDeliveryTime" product=$product}{/if}
					<p class="warning_inline" id="last_quantities"{if ($product->quantity > $last_qties || $product->quantity <= 0) || $allow_oosp || !$product->available_for_order || $PS_CATALOG_MODE} style="display: none"{/if} >{l s='Warning: Last items in stock!'}</p>
				{/if*}
				{*<p id="availability_date"{if ($product->quantity > 0) || !$product->available_for_order || $PS_CATALOG_MODE || !isset($product->available_date) || $product->available_date < $smarty.now|date_format:'%Y-%m-%d'} style="display: none;"{/if}>
					<span id="availability_date_label">{l s='Availability date:'}</span>
					<span id="availability_date_value">{if Validate::isDate($product->available_date)}{dateFormat date=$product->available_date full=false}{/if}</span>
				</p>*}
				{if ($product->show_price && !isset($restricted_country_mode)) || isset($groups) || $product->reference || (isset($HOOK_PRODUCT_ACTIONS) && $HOOK_PRODUCT_ACTIONS)}
					<!-- hidden datas -->
					<p class="hidden">
						<input type="hidden" name="token" value="{$static_token}" />
						<input type="hidden" name="id_product" value="{$product->id|intval}" id="product_page_product_id" />
						<input type="hidden" name="add" value="1" />
						<input type="hidden" name="id_product_attribute" id="idCombination" value="" />
					</p>
					<div class="box-info-product">
						<div class="product_attributes clearfix">
							{if isset($groups)}
								<!-- attributes -->
								<div id="attributes">
									<div class="clearfix"></div>
									{foreach from=$groups key=id_attribute_group item=group}
										{if $group.attributes|@count}
											<fieldset class="attribute_fieldset">
												<label class="attribute_label fontcustom1" {if $group.group_type != 'color' && $group.group_type != 'radio'}for="group_{$id_attribute_group|intval}"{/if}>{$group.name|escape:'html':'UTF-8'}&nbsp;</label>
												{assign var="groupName" value="group_$id_attribute_group"}
												<div class="attribute_list">
													{if ($group.group_type == 'select')}
														<select name="{$groupName}" id="group_{$id_attribute_group|intval}" class="form-control attribute_select no-print">
															{foreach from=$group.attributes key=id_attribute item=group_attribute}
																<option value="{$id_attribute|intval}"{if (isset($smarty.get.$groupName) && $smarty.get.$groupName|intval == $id_attribute) || $group.default == $id_attribute} selected="selected"{/if} title="{$group_attribute|escape:'html':'UTF-8'}">{$group_attribute|escape:'html':'UTF-8'}</option>
															{/foreach}
														</select>
													{elseif ($group.group_type == 'color')}
														<ul id="color_to_pick_list" class="attributes-{$group.name|strtolower} clearfix">
															{assign var="default_colorpicker" value=""}
															{foreach from=$group.attributes key=id_attribute item=group_attribute}
																{assign var='img_color_exists' value=file_exists($col_img_dir|cat:$id_attribute|cat:'.jpg')}
																<li class="attribute-item-{$group.name|strtolower} {if $group.default == $id_attribute} selected {/if}"  data-desc="#attribute-grade-desc{$id_attribute|intval}">
																	<a href="{$link->getProductLink($product)|escape:'html':'UTF-8'}" id="color_{$id_attribute|intval}" name="{$colors.$id_attribute.name|escape:'html':'UTF-8'}" class="color_pick{if ($group.default == $id_attribute)} selected{/if}"{*if !$img_color_exists && isset($colors.$id_attribute.value) && $colors.$id_attribute.value}{/if*}{if $img_color_exists} style="background:url('{$img_col_dir}{$id_attribute|intval}.jpg');"{/if} title="{$colors.$id_attribute.name|escape:'html':'UTF-8'}">
																		{if $img_color_exists}
																			<img src="{$img_col_dir}{$id_attribute|intval}.jpg" alt="{$colors.$id_attribute.name|escape:'html':'UTF-8'}" title="{$colors.$id_attribute.name|escape:'html':'UTF-8'}" width="20" height="20" />
																		{/if}
																	</a>
																</li>
																{if ($group.default == $id_attribute)}
																	{$default_colorpicker = $id_attribute}
																{/if}
															{/foreach}
														</ul>
														{if ($group.name|strtolower == 'grade')}
														<div class="attribute-grade-desc">
															{foreach from=$group.descriptions key=id_attribute item=description}
															<div id="attribute-grade-desc{$id_attribute|intval}" {if $group.default != $id_attribute} style="display:none;"{/if}>
																{$description}
															</div>
															{/foreach}
														</div>
														{/if}
														<input type="hidden" class="color_pick_hidden" name="{$groupName|escape:'html':'UTF-8'}" value="{$default_colorpicker|intval}" />
													{elseif ($group.group_type == 'radio')}
														<ul class="attribute-radio-list">
															{foreach from=$group.attributes key=id_attribute item=group_attribute}
																<li {if ($group.default == $id_attribute)} class="selected"{/if}>
																	<label class="attribute-radio-label" for="attribute-radio-{$id_attribute}">
																		<input type="radio" id="attribute-radio-{$id_attribute}" class="attribute-radio" name="{$groupName|escape:'html':'UTF-8'}" value="{$id_attribute}" {if ($group.default == $id_attribute)} checked{/if} />
																		<span>{$group_attribute|escape:'html':'UTF-8'}</span>
																	</label>
																</li>
															{/foreach}
														</ul>
													{/if}
												</div> <!-- end attribute_list -->
											</fieldset>
										{/if}
									{/foreach}
								</div> <!-- end attributes -->
							{/if}
						</div> <!-- end product_attributes -->
					</div> <!-- end box-info-product -->
				{/if}
				{*hook h="displaySocialSharing"*}
			</div> <!-- end pb-right-first-column-->
			<div class="pb-right-second-column col-xs-12 col-md-5 col-sm-6">
				{if ($product->show_price && !isset($restricted_country_mode)) || isset($groups) || $product->reference || (isset($HOOK_PRODUCT_ACTIONS) && $HOOK_PRODUCT_ACTIONS)}
					<p id="product_reference"{if empty($product->reference) || !$product->reference} style="display: none;"{/if}>
						<label class="fontcustom1">{l s='Ref:'} </label>
						<span class="editable" itemprop="sku"{if !empty($product->reference) && $product->reference} content="{$product->reference}"{/if}>{if !isset($groups)}{$product->reference|escape:'html':'UTF-8'}{/if}</span>
					</p>
				{/if}
				{if (isset($inAdminGroup) && $inAdminGroup)}
					<p id="product_idp">
						<label class="fontcustom1">{l s='IDP:'} </label>
						<span class="editable"></span>
					</p>
					<table style="width:100%;">
						<tr>
							<td style="padding: 0 5px 0 0;">
								{if (isset($id_shop) && $id_shop == 1)}
								<p id="product_lppe" style="margin:0;line-height:1;">
									<label class="fontcustom1">{l s='LPPE:'} </label>
									<span class="editable"></span>
								</p>
								<p id="product_usp" style="line-height:1;">
									<label class="fontcustom1">{l s='USP:'} </label>
									<span class="editable"></span>
								</p>
								{/if}
								{if (isset($id_shop) && $id_shop == 2)}
								<p id="product_lppu">
									<label class="fontcustom1">{l s='LPPU:'} </label>
									<span class="editable"></span>
								</p>
								{/if}
							</td>
							<td style="padding: 0 5px 0 0;{if (isset($id_shop) && $id_shop == 1)}vertical-align:top;{/if}">
								<p id="product_floor_price">
									<label class="fontcustom1">{l s='FP:'} </label>
									<span class="editable"></span>
								</p>
							</td>
						</tr>
						<tr>
							<td style="padding: 0 5px 0 0;">
								<p id="product_floor_price_10" style="margin:0;">
									<label class="fontcustom1">{l s='10%:'} </label>
									<span class="editable"></span>
								</p>
							</td>
							<td style="padding: 0 5px 0 0;">
								<p id="product_floor_price_11" style="margin:0;">
									<label class="fontcustom1">{l s='11%:'} </label>
									<span class="editable"></span>
								</p>
							</td>
						</tr>
						<tr>
							<td style="padding: 0 5px 0 0;">
								<p id="product_floor_price_12" style="margin:0;">
									<label class="fontcustom1">{l s='12%:'} </label>
									<span class="editable"></span>
								</p>
							</td>
							<td style="padding: 0 5px 0 0;">
								<p id="product_floor_price_13" style="margin:0;">
									<label class="fontcustom1">{l s='13%:'} </label>
									<span class="editable"></span>
								</p>
							</td>
						</tr>
						<tr>
							<td style="padding: 0 5px 0 0;">
								<p id="product_floor_price_14" style="margin:0;">
									<label class="fontcustom1">{l s='14%:'} </label>
									<span class="editable"></span>
								</p>
							</td>
							<td style="padding: 0 5px 0 0;">
								<p id="product_floor_price_15" style="margin:0;">
									<label class="fontcustom1">{l s='15%:'} </label>
									<span class="editable"></span>
								</p>
							</td>
						</tr>
						<tr>
							<td style="padding: 0 5px 0 0;">
								<p id="product_floor_price_16" style="margin:0;">
									<label class="fontcustom1">{l s='16%:'} </label>
									<span class="editable"></span>
								</p>
							</td>
							<td style="padding: 0 5px 0 0;">
								<p id="product_floor_price_18" style="margin:0;">
									<label class="fontcustom1">{l s='18%:'} </label>
									<span class="editable"></span>
								</p>
							</td>
						</tr>
						<tr>
							<td style="padding: 0 5px 0 0;">
								<p id="product_floor_price_19" style="margin:0;">
									<label class="fontcustom1">{l s='19%:'} </label>
									<span class="editable"></span>
								</p>
							</td>
							<td style="padding: 0 5px 0 0;">
								<p id="product_floor_price_20" style="margin:0;">
									<label class="fontcustom1">{l s='20%:'} </label>
									<span class="editable"></span>
								</p>
							</td>
						</tr>
						<tr>
							<td style="padding: 0 5px 0 0;">
								<p id="product_floor_price_21" style="margin:0;">
									<label class="fontcustom1">{l s='21%:'} </label>
									<span class="editable"></span>
								</p>
							</td>
							<td style="padding: 0 5px 0 0;">
								<p id="product_floor_price_22" style="margin:0;">
									<label class="fontcustom1">{l s='22%:'} </label>
									<span class="editable"></span>
								</p>
							</td>
						</tr>
						{*<tr>
							<td style="padding: 0 5px 0 0;" colspan="2">
								<p id="product_price_update" style="margin:0;font-weight:bold;">
									<label class="fontcustom1">{l s='Mise à jour des prix:'} </label>
									<span class="editable"></span>
								</p>
							</td>
						</tr>*}
					</table>
				{/if}
				
				{if $logged}
					{*if isset($id_shop) && $id_shop == 1*}
					{*if (isset($inAdminGroup) && $inAdminGroup)*}
					<div class="in_border" style="margin-bottom: -16px;">
						{include file="$tpl_dir./product-pack-list.tpl"}
					</div>
					{*/if*}
					
					{*if isset($HOOK_EXTRA_RIGHT) && $HOOK_EXTRA_RIGHT}{$HOOK_EXTRA_RIGHT}{/if*}
					<div class="">
						{*if !$product->is_virtual && $product->condition}
						<p id="product_condition">
							<label class="fontcustom1">{l s='Condition:'} </label>
							{if $product->condition == 'new'}
								<link itemprop="itemCondition" href="https://schema.org/NewCondition"/>
								<span class="editable">{l s='New product'}</span>
							{elseif $product->condition == 'used'}
								<link itemprop="itemCondition" href="https://schema.org/UsedCondition"/>
								<span class="editable">{l s='Used'}</span>
							{elseif $product->condition == 'refurbished'}
								<link itemprop="itemCondition" href="https://schema.org/RefurbishedCondition"/>
								<span class="editable">{l s='Refurbished'}</span>
							{/if}
						</p>
						{/if*}
					</div>
					{if ($product->show_price && !isset($restricted_country_mode)) || isset($groups) || $product->reference || (isset($HOOK_PRODUCT_ACTIONS) && $HOOK_PRODUCT_ACTIONS)}
						<div class="box-info-product">
						{if $product->quantity > 0}
							{if ($product->show_price && !isset($restricted_country_mode)) || isset($groups) || $product->reference || (isset($HOOK_PRODUCT_ACTIONS) && $HOOK_PRODUCT_ACTIONS)}
								{if $product->show_price && !isset($restricted_country_mode) && !$PS_CATALOG_MODE}
								<div class="content_prices in_border clearfix">
										<!-- prices -->
										<div>
											<p class="our_price_display fontcustom1" itemprop="offers" itemscope itemtype="https://schema.org/Offer">{strip}
												{if $product->quantity > 0}<link itemprop="availability" href="https://schema.org/InStock"/>{/if}
												{if $priceDisplay >= 0 && $priceDisplay <= 2}
													<span id="our_price_display" class="price" itemprop="price" content="{$productPrice}">{convertPrice price=$productPrice|floatval}</span>
													{if $tax_enabled  && ((isset($display_tax_label) && $display_tax_label == 1) || !isset($display_tax_label))}
														{*if $priceDisplay == 1} {l s='tax excl.'}{else} {l s='tax incl.'}{/if*}
													{/if}
													<meta itemprop="priceCurrency" content="{$currency->iso_code}" />
													{hook h="displayProductPriceBlock" product=$product type="price"}
												{/if}
											{/strip}</p>
											<p id="reduction_percent" {if $productPriceWithoutReduction <= 0 || !$product->specificPrice || $product->specificPrice.reduction_type != 'percentage'} style="display:none;"{/if}>{strip}
												<span id="reduction_percent_display" class="fontcustom1">
													{if $product->specificPrice && $product->specificPrice.reduction_type == 'percentage'}-{$product->specificPrice.reduction*100}%{/if}
												</span>
											{/strip}</p>
											<p id="reduction_amount" {if $productPriceWithoutReduction <= 0 || !$product->specificPrice || $product->specificPrice.reduction_type != 'amount' || $product->specificPrice.reduction|floatval ==0} style="display:none"{/if}>{strip}
												<span id="reduction_amount_display" class="fontcustom1">
												{if $product->specificPrice && $product->specificPrice.reduction_type == 'amount' && $product->specificPrice.reduction|floatval !=0}
													-{convertPrice price=$productPriceWithoutReduction|floatval-$productPrice|floatval}
												{/if}
												</span>
											{/strip}</p>
											<p id="old_price"{if (!$product->specificPrice || !$product->specificPrice.reduction)} class="hidden"{/if}>{strip}
												{if $priceDisplay >= 0 && $priceDisplay <= 2}
													{hook h="displayProductPriceBlock" product=$product type="old_price"}
													<span id="old_price_display" class="fontcustom1"><span class="price">{if $productPriceWithoutReduction > $productPrice}{convertPrice price=$productPriceWithoutReduction|floatval}{/if}</span>{*if $productPriceWithoutReduction > $productPrice && $tax_enabled && $display_tax_label == 1} {if $priceDisplay == 1}{l s='tax excl.'}{else}{l s='tax incl.'}{/if}{/if*}</span>
												{/if}
											{/strip}</p>
											{if $priceDisplay == 2}
												<br />
												<span id="pretaxe_price" class="fontcustom1">{strip}
													<span id="pretaxe_price_display">{convertPrice price=$product->getPrice(false, $smarty.const.NULL)}</span> {*l s='tax excl.'*}
												{/strip}</span>
											{/if}
										</div> <!-- end prices -->
										{if $packItems|@count && $productPrice < $product->getNoPackPrice()}
											<p class="pack_price">{l s='Instead of'} <span style="text-decoration: line-through;">{convertPrice price=$product->getNoPackPrice()}</span></p>
										{/if}
										{if $product->ecotax != 0}
											<p class="price-ecotax">{l s='Including'} <span id="ecotax_price_display">{if $priceDisplay == 2}{$ecotax_tax_exc|convertAndFormatPrice}{else}{$ecotax_tax_inc|convertAndFormatPrice}{/if}</span> {l s='for ecotax'}
												{if $product->specificPrice && $product->specificPrice.reduction}
												<br />{l s='(not impacted by the discount)'}
												{/if}
											</p>
										{/if}
										{if !empty($product->unity) && $product->unit_price_ratio > 0.000000}
											{math equation="pprice / punit_price" pprice=$productPrice  punit_price=$product->unit_price_ratio assign=unit_price}
											<p class="unit-price"><span id="unit_price_display">{convertPrice price=$unit_price}</span> {l s='per'} {$product->unity|escape:'html':'UTF-8'}</p>
											{hook h="displayProductPriceBlock" product=$product type="unit_price"}
										{/if}
									{hook h="displayProductPriceBlock" product=$product type="weight" hook_origin='product_sheet'}
									{hook h="displayProductPriceBlock" product=$product type="after_price"}
									<div class="clear"></div>
								</div> <!-- end content_prices -->
							{/if} {*close if for show price*}
						{/if}
					{/if}
							{if ($display_qties == 1 && !$PS_CATALOG_MODE && $PS_STOCK_MANAGEMENT && $product->available_for_order)}
								<!-- number of item in stock -->
								<p id="pQuantityAvailable"{if $product->quantity <= 0} style="display: none;"{/if}>
									<i class="fa fa-info-circle" aria-hidden="true"></i>
									<span id="quantityAvailable">{$product->quantity|intval}</span>
									<span {if $product->quantity > 1} style="display: none;"{/if} id="quantityAvailableTxt">{l s='Produit en stock'}</span>
									<span {if $product->quantity == 1} style="display: none;"{/if} id="quantityAvailableTxtMultiple">{l s='Produits en stock'}</span>
								</p>
							{/if}
							<!-- availability or doesntExist -->
							<p id="availability_statut"{if !$PS_STOCK_MANAGEMENT || ($product->quantity <= 0 && !$product->available_later && $allow_oosp) || ($product->quantity > 0 && !$product->available_now) || !$product->available_for_order || $PS_CATALOG_MODE} style="display: none;"{/if}>
								{*<span id="availability_label">{l s='Availability:'}</span>*}
								<span id="availability_value" class="label{if $product->quantity <= 0 && !$allow_oosp} label-danger{elseif $product->quantity <= 0} label-warning{else} label-success{/if}">{if $product->quantity <= 0}{if $PS_STOCK_MANAGEMENT && $allow_oosp}{$product->available_later}{else}{l s='This product is no longer in stock'}{/if}{elseif $PS_STOCK_MANAGEMENT}{$product->available_now}{/if}</span>
							</p>
							<!-- quantity wanted -->
							{if !$PS_CATALOG_MODE}
							<p id="quantity_wanted_p"{if (!$allow_oosp && $product->quantity <= 0) || !$product->available_for_order || $PS_CATALOG_MODE} style="display: none;"{/if}>
								<label for="quantity_wanted" class="fontcustom1">{l s='Quantity'}</label>
								<input type="number" min="1" name="qty" id="quantity_wanted" class="text" value="{if isset($quantityBackup)}{$quantityBackup|intval}{else}{if $product->minimal_quantity > 1}{$product->minimal_quantity}{else}1{/if}{/if}" />
								<a href="#" data-field-qty="qty" class="btn btn-default button-minus product_quantity_down">
									<span><i class="icon-minus"></i></span>
								</a>
								<a href="#" data-field-qty="qty" class="btn btn-default button-plus product_quantity_up">
									<span><i class="icon-plus"></i></span>
								</a>
								<span class="clearfix"></span>
							</p>
							{/if}
							<!-- minimal quantity wanted -->
							{*<p id="minimal_quantity_wanted_p"{if $product->minimal_quantity <= 1 || !$product->available_for_order || $PS_CATALOG_MODE} style="display: none;"{/if}>
								{l s='The minimum purchase order quantity for the product is'} <b id="minimal_quantity_label">{$product->minimal_quantity}</b>
							</p>*}
							<div class="box-cart-bottom">
								{*if (!$allow_oosp && $product->quantity <= 0) || !$product->available_for_order || (isset($restricted_country_mode) && $restricted_country_mode) || $PS_CATALOG_MODE}<div class="hidden">{/if*}
									<p id="add_to_cart" class="buttons_bottom_block no-print">
										<button type="submit" name="Submit" class="exclusive fontcustom1">
											{if $content_only && (isset($product->customization_required) && $product->customization_required)}
												{l s='+ Customize'}
											{else}
												{l s='+ Add to cart'}
											{/if}
										</button>
									</p>
								{*if (!$allow_oosp && $product->quantity <= 0) || !$product->available_for_order || (isset($restricted_country_mode) && $restricted_country_mode) || $PS_CATALOG_MODE}</div>{/if*}
									<!-- usefull links-->
									{*<ul id="usefull_link_block" class="clearfix no-print">
										{if !$content_only}
										{if $HOOK_EXTRA_LEFT}{$HOOK_EXTRA_LEFT}{/if}
										<li class="print">
											<a href="javascript:print();">
												<i class="icon-print"></i>
											</a>
										</li>
										{/if}
										{if isset($HOOK_PRODUCT_ACTIONS) && $HOOK_PRODUCT_ACTIONS}{$HOOK_PRODUCT_ACTIONS}{/if}
									</ul>*}
							</div> <!-- end box-cart-bottom -->
							<!-- Out of stock hook -->
							<div id="oosHook"{if $product->quantity > 0} style="display: none;"{/if}>
								{$HOOK_PRODUCT_OOS}
							</div>
						</div> <!-- end box-info-product -->
					{/if}
				{/if}
			</div> <!-- end pb-right-second-column-->
		{if ($product->show_price && !isset($restricted_country_mode)) || isset($groups) || $product->reference || (isset($HOOK_PRODUCT_ACTIONS) && $HOOK_PRODUCT_ACTIONS)}
		</form>
		{/if}
		{*hook h="actionProductOutOfStock"*}
		</div> <!-- end pb-right-column-->
	</div> <!-- end primary_block -->
	{if !$content_only}
<div class="product_tab_container">
	<ul class="nav nav-tabs clearfix">
		{if (isset($quantity_discounts) && count($quantity_discounts) > 0)}<li><a data-toggle="tab" href="#tab1" class="fontcustom1">{l s='Volume discounts'}</a></li>{/if}
		{if isset($features) && $features}<li class="active"><a data-toggle="tab" href="#tab2" class="fontcustom1">{l s='Data sheet'}</a></li>{/if}
		{if isset($product) && $product->description}<li><a data-toggle="tab" href="#tab3" class="fontcustom1">{l s='More info'}</a></li>{/if}
		{if isset($packItems) && $packItems|@count > 0}<li><a data-toggle="tab" href="#tab4" class="fontcustom1">{l s='Pack content'}</a></li>{/if}
		{if isset($attachments) && $attachments}<li><a data-toggle="tab" href="#tab5" class="fontcustom1">{l s='Download'}</a></li>{/if}
		{if isset($product) && $product->customizable}<li><a data-toggle="tab" href="#tab6" class="fontcustom1">{l s='Product customization'}</a></li>{/if}
		{$HOOK_PRODUCT_TAB}
	</ul>
	<div class="tab-content">
		{if (isset($quantity_discounts) && count($quantity_discounts) > 0)}
			<!-- quantity discount -->
			<section class="page-product-box" id="tab1">
				<div id="quantityDiscount">
					<table class="std table-product-discounts">
						<thead>
							<tr>
								<th>{l s='Quantity'}</th>
								<th>{if $display_discount_price}{l s='Price'}{else}{l s='Discount'}{/if}</th>
								<th>{l s='You Save'}</th>
							</tr>
						</thead>
						<tbody>
							{foreach from=$quantity_discounts item='quantity_discount' name='quantity_discounts'}
							<tr id="quantityDiscount_{$quantity_discount.id_product_attribute}" class="quantityDiscount_{$quantity_discount.id_product_attribute}" data-discount-type="{$quantity_discount.reduction_type}" data-discount="{$quantity_discount.real_value|floatval}" data-discount-quantity="{$quantity_discount.quantity|intval}">
								<td>
									{$quantity_discount.quantity|intval}
								</td>
								<td>
									{if $quantity_discount.price >= 0 || $quantity_discount.reduction_type == 'amount'}
										{if $display_discount_price}
											{if $quantity_discount.reduction_tax == 0 && !$quantity_discount.price}
												{convertPrice price = $productPriceWithoutReduction|floatval-($productPriceWithoutReduction*$quantity_discount.reduction_with_tax)|floatval}
											{else}
												{convertPrice price=$productPriceWithoutReduction|floatval-$quantity_discount.real_value|floatval}
											{/if}
										{else}
											{convertPrice price=$quantity_discount.real_value|floatval}
										{/if}
									{else}
										{if $display_discount_price}
											{if $quantity_discount.reduction_tax == 0}
												{convertPrice price = $productPriceWithoutReduction|floatval-($productPriceWithoutReduction*$quantity_discount.reduction_with_tax)|floatval}
											{else}
												{convertPrice price = $productPriceWithoutReduction|floatval-($productPriceWithoutReduction*$quantity_discount.reduction)|floatval}
											{/if}
										{else}
											{$quantity_discount.real_value|floatval}%
										{/if}
									{/if}
								</td>
								<td>
									<span>{l s='Up to'}</span>
									{if $quantity_discount.price >= 0 || $quantity_discount.reduction_type == 'amount'}
										{$discountPrice=$productPriceWithoutReduction|floatval-$quantity_discount.real_value|floatval}
									{else}
										{$discountPrice=$productPriceWithoutReduction|floatval-($productPriceWithoutReduction*$quantity_discount.reduction)|floatval}
									{/if}
									{$discountPrice=$discountPrice * $quantity_discount.quantity}
									{$qtyProductPrice=$productPriceWithoutReduction|floatval * $quantity_discount.quantity}
									{convertPrice price=$qtyProductPrice - $discountPrice}
								</td>
							</tr>
							{/foreach}
						</tbody>
					</table>
				</div>
			</section>
		{/if}
	{if isset($features) && $features}
		<!-- Data sheet -->
		<section class="page-product-box active" id="tab2">
			<table class="table-data-sheet">
				{foreach from=$features item=feature}
				<tr class="{cycle values="odd,even"}">
					{if isset($feature.value)}
					<td>{$feature.name|escape:'html':'UTF-8'}</td>
					<td>{$feature.value|escape:'html':'UTF-8'}</td>
					{/if}
				</tr>
				{/foreach}
			</table>
		</section>
		<!--end Data sheet -->
	{/if}
	{if isset($product) && $product->description}
		<!-- More info -->
		<section class="page-product-box" id="tab3">
			<!-- full description -->
			<div  class="rte">{$product->description}</div>
		</section>
		<!--end  More info -->
	{/if}
	{if isset($packItems) && $packItems|@count > 0}
	<section id="blockpack"  id="tab4">
		{include file="$tpl_dir./product-list.tpl" products=$packItems}
	</section>
	{/if}
	{if isset($HOOK_PRODUCT_TAB_CONTENT) && $HOOK_PRODUCT_TAB_CONTENT}{$HOOK_PRODUCT_TAB_CONTENT}{/if}
	{if isset($attachments) && $attachments}
		<!--Download -->
		<section class="page-product-box" id="tab5">
			{foreach from=$attachments item=attachment name=attachements}
				{if $smarty.foreach.attachements.iteration %3 == 1}<div class="row">{/if}
					<div class="col-lg-4">
						<h4><a href="{$link->getPageLink('attachment', true, NULL, "id_attachment={$attachment.id_attachment}")|escape:'html':'UTF-8'}">{$attachment.name|escape:'html':'UTF-8'}</a></h4>
						<p class="text-muted">{$attachment.description|escape:'html':'UTF-8'}</p>
						<a class="btn btn-default btn-block" href="{$link->getPageLink('attachment', true, NULL, "id_attachment={$attachment.id_attachment}")|escape:'html':'UTF-8'}">
							<i class="icon-download"></i>
							{l s="Download"} ({Tools::formatBytes($attachment.file_size, 2)})
						</a>
						<hr />
					</div>
				{if $smarty.foreach.attachements.iteration %3 == 0 || $smarty.foreach.attachements.last}</div>{/if}
			{/foreach}
		</section>
		<!--end Download -->
	{/if}
	{if isset($product) && $product->customizable}
		<!--Customization -->
		<section class="page-product-box" id="tab6">
			<!-- Customizable products -->
			<form method="post" action="{$customizationFormTarget}" enctype="multipart/form-data" id="customizationForm" class="clearfix">
				<p class="infoCustomizable">
					{l s='After saving your customized product, remember to add it to your cart.'}
					{if $product->uploadable_files}
					<br />
					{l s='Allowed file formats are: GIF, JPG, PNG'}{/if}
				</p>
				{if $product->uploadable_files|intval}
					<div class="customizableProductsFile">
						<h5 class="product-heading-h5">{l s='Pictures'}</h5>
						<ul id="uploadable_files" class="clearfix">
							{counter start=0 assign='customizationField'}
							{foreach from=$customizationFields item='field' name='customizationFields'}
								{if $field.type == 0}
									<li class="customizationUploadLine{if $field.required} required{/if}">{assign var='key' value='pictures_'|cat:$product->id|cat:'_'|cat:$field.id_customization_field}
										{if isset($pictures.$key)}
											<div class="customizationUploadBrowse">
												<img src="{$pic_dir}{$pictures.$key}_small" alt="" />
													<a href="{$link->getProductDeletePictureLink($product, $field.id_customization_field)|escape:'html':'UTF-8'}" title="{l s='Delete'}" >
														<img src="{$img_dir}icon/delete.gif" alt="{l s='Delete'}" class="customization_delete_icon" width="11" height="13" />
													</a>
											</div>
										{/if}
										<div class="customizationUploadBrowse form-group">
											<label class="customizationUploadBrowseDescription">
												{if !empty($field.name)}
													{$field.name}
												{else}
													{l s='Please select an image file from your computer'}
												{/if}
												{if $field.required}<sup>*</sup>{/if}
											</label>
											<input type="file" name="file{$field.id_customization_field}" id="img{$customizationField}" class="form-control customization_block_input {if isset($pictures.$key)}filled{/if}" />
										</div>
									</li>
									{counter}
								{/if}
							{/foreach}
						</ul>
					</div>
				{/if}
				{if $product->text_fields|intval}
					<div class="customizableProductsText">
						<h5 class="product-heading-h5">{l s='Text'}</h5>
						<ul id="text_fields">
						{counter start=0 assign='customizationField'}
						{foreach from=$customizationFields item='field' name='customizationFields'}
							{if $field.type == 1}
								<li class="customizationUploadLine{if $field.required} required{/if}">
									<label for ="textField{$customizationField}">
										{assign var='key' value='textFields_'|cat:$product->id|cat:'_'|cat:$field.id_customization_field}
										{if !empty($field.name)}
											{$field.name}
										{/if}
										{if $field.required}<sup>*</sup>{/if}
									</label>
									<textarea name="textField{$field.id_customization_field}" class="form-control customization_block_input" id="textField{$customizationField}" rows="3" cols="20">{strip}
										{if isset($textFields.$key)}
											{$textFields.$key|stripslashes}
										{/if}
									{/strip}</textarea>
								</li>
								{counter}
							{/if}
						{/foreach}
						</ul>
					</div>
				{/if}
				<p id="customizedDatas">
					<input type="hidden" name="quantityBackup" id="quantityBackup" value="" />
					<input type="hidden" name="submitCustomizedDatas" value="1" />
					<button class="button btn btn-default button button-small" name="saveCustomization">
						<span>{l s='Save'}</span>
					</button>
					<span id="ajax-loader" class="unvisible">
						<img src="{$img_ps_dir}loader.gif" alt="loader" />
					</span>
				</p>
			</form>
			<p class="clear required"><sup>*</sup> {l s='required fields'}</p>
		</section>
		<!--end Customization -->
	{/if}	
	</div>
</div>	
{if isset($accessories) && $accessories}
	<!--Accessories -->
	<div class="accessories_block product_block_container">
		<div class="header_title_out">
			<h3>{l s='Accessories'}</h3>
			<p>{l s='Find your unique accessories here.'}</p>
		</div>
		<div class="product_content block_content">
			<div class="navi">
				<a class="prevtab"><i class="arrow_carrot-left"></i></a>
				<a class="nexttab"><i class="arrow_carrot-right"></i></a>
			</div>
			<div class="row">
				<div class="accessories_sld">
					{foreach from=$accessories item=accessory name=myLoop}
						{if $smarty.foreach.myLoop.index % 1 == 0 || $smarty.foreach.myLoop.first }
							<div class="item_out">
						{/if}
							<div class="item">
								<div class="left-block">
									<a href="{$accessory.link|escape:'html':'UTF-8'}" title="{$accessory.legend|escape:'html':'UTF-8'}" class="img_content">
										<img class="img-responsive" src="{$link->getImageLink($accessory.link_rewrite, $accessory.id_image, 'home_default')|escape:'html':'UTF-8'}" alt="{$accessory.legend|escape:'html':'UTF-8'}" />
									</a>
								</div>
								<div class="right-block">
									<h5>
										<a class="product-name" href="{$accessory.link|escape:'html':'UTF-8'}">
											{$accessory.name|escape:'html':'UTF-8'}
										</a>
									</h5>
									<div class="price-box">
										<span class="price fontcustom1">
											{if $priceDisplay != 1}
												{displayWtPrice p=$accessory.price}
											{else}
												{displayWtPrice p=$accessory.price_tax_exc}
											{/if}
											{hook h="displayProductPriceBlock" product=$accessory type="price"}
										</span>
										{hook h="displayProductPriceBlock" product=$accessory type="after_price"}
									</div>
									<div class="transfer">
										{if ($accessory.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $accessory.available_for_order && !isset($restricted_country_mode) && $accessory.minimal_quantity <= 1 && $accessory.customizable != 2 && !$PS_CATALOG_MODE}
											{if ($accessory.allow_oosp || $accessory.quantity > 0)}
												{if isset($static_token)}
													<a class="exclusive ajax_add_to_cart_button btn btn-default fontcustom1" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$accessory.id_product|intval}&amp;token={$static_token}", false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart' mod='posfeatureproduct'}" data-id-product="{$accessory.id_product|intval}">
														{l s='+ Add to cart' mod='posfeatureproduct'}
													</a>
												{else}
													<a class="exclusive ajax_add_to_cart_button btn btn-default fontcustom1" href="{$link->getPageLink('cart',false, NULL, 'add=1&amp;id_product={$accessory.id_product|intval}', false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart' mod='posfeatureproduct'}" data-id-product="{$accessory.id_product|intval}">
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
	<!--end Accessories -->
{/if}
{if isset($HOOK_PRODUCT_FOOTER) && $HOOK_PRODUCT_FOOTER}{$HOOK_PRODUCT_FOOTER}{/if}
	{/if}
</div> <!-- itemscope product wrapper -->
{strip}
{if isset($smarty.get.ad) && $smarty.get.ad}
	{addJsDefL name=ad}{$base_dir|cat:$smarty.get.ad|escape:'html':'UTF-8'}{/addJsDefL}
{/if}
{if isset($smarty.get.adtoken) && $smarty.get.adtoken}
	{addJsDefL name=adtoken}{$smarty.get.adtoken|escape:'html':'UTF-8'}{/addJsDefL}
{/if}
{addJsDef allowBuyWhenOutOfStock=$allow_oosp|boolval}
{addJsDef availableNowValue=$product->available_now|escape:'quotes':'UTF-8'}
{addJsDef availableLaterValue=$product->available_later|escape:'quotes':'UTF-8'}
{addJsDef attribute_anchor_separator=$attribute_anchor_separator|escape:'quotes':'UTF-8'}
{addJsDef attributesCombinations=$attributesCombinations}
{addJsDef currentDate=$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}
{if isset($combinations) && $combinations}
	{addJsDef combinations=$combinations}
	{addJsDef combinationsFromController=$combinations}
	{addJsDef displayDiscountPrice=$display_discount_price}
	{addJsDefL name='upToTxt'}{l s='Up to' js=1}{/addJsDefL}
{/if}
{if isset($combinationImages) && $combinationImages}
	{addJsDef combinationImages=$combinationImages}
{/if}
{addJsDef customizationId=$id_customization}
{addJsDef customizationFields=$customizationFields}
{addJsDef default_eco_tax=$product->ecotax|floatval}
{addJsDef displayPrice=$priceDisplay|intval}
{addJsDef ecotaxTax_rate=$ecotaxTax_rate|floatval}
{if isset($cover.id_image_only)}
	{addJsDef idDefaultImage=$cover.id_image_only|intval}
{else}
	{addJsDef idDefaultImage=0}
{/if}
{addJsDef img_ps_dir=$img_ps_dir}
{addJsDef img_prod_dir=$img_prod_dir}
{addJsDef id_product=$product->id|intval}
{addJsDef jqZoomEnabled=$jqZoomEnabled|boolval}
{addJsDef maxQuantityToAllowDisplayOfLastQuantityMessage=$last_qties|intval}
{addJsDef minimalQuantity=$product->minimal_quantity|intval}
{addJsDef noTaxForThisProduct=$no_tax|boolval}
{if isset($customer_group_without_tax)}
	{addJsDef customerGroupWithoutTax=$customer_group_without_tax|boolval}
{else}
	{addJsDef customerGroupWithoutTax=false}
{/if}
{if isset($group_reduction)}
	{addJsDef groupReduction=$group_reduction|floatval}
{else}
	{addJsDef groupReduction=false}
{/if}
{addJsDef oosHookJsCodeFunctions=Array()}
{addJsDef productHasAttributes=isset($groups)|boolval}
{addJsDef productPriceTaxExcluded=($product->getPriceWithoutReduct(true)|default:'null' - $product->ecotax)|floatval}
{addJsDef productPriceTaxIncluded=($product->getPriceWithoutReduct(false)|default:'null' - $product->ecotax * (1 + $ecotaxTax_rate / 100))|floatval}
{addJsDef productBasePriceTaxExcluded=($product->getPrice(false, null, 6, null, false, false) - $product->ecotax)|floatval}
{addJsDef productBasePriceTaxExcl=($product->getPrice(false, null, 6, null, false, false)|floatval)}
{addJsDef productBasePriceTaxIncl=($product->getPrice(true, null, 6, null, false, false)|floatval)}
{addJsDef productReference=$product->reference|escape:'html':'UTF-8'}
{addJsDef productAvailableForOrder=$product->available_for_order|boolval}
{addJsDef productPriceWithoutReduction=$productPriceWithoutReduction|floatval}
{addJsDef productPrice=$productPrice|floatval}
{addJsDef productUnitPriceRatio=$product->unit_price_ratio|floatval}
{addJsDef productShowPrice=(!$PS_CATALOG_MODE && $product->show_price)|boolval}
{addJsDef PS_CATALOG_MODE=$PS_CATALOG_MODE}
{if $product->specificPrice && $product->specificPrice|@count}
	{addJsDef product_specific_price=$product->specificPrice}
{else}
	{addJsDef product_specific_price=array()}
{/if}
{if $display_qties == 1 && $product->quantity}
	{addJsDef quantityAvailable=$product->quantity}
{else}
	{addJsDef quantityAvailable=0}
{/if}
{addJsDef quantitiesDisplayAllowed=$display_qties|boolval}
{if $product->specificPrice && $product->specificPrice.reduction && $product->specificPrice.reduction_type == 'percentage'}
	{addJsDef reduction_percent=$product->specificPrice.reduction*100|floatval}
{else}
	{addJsDef reduction_percent=0}
{/if}
{if $product->specificPrice && $product->specificPrice.reduction && $product->specificPrice.reduction_type == 'amount'}
	{addJsDef reduction_price=$product->specificPrice.reduction|floatval}
{else}
	{addJsDef reduction_price=0}
{/if}
{if $product->specificPrice && $product->specificPrice.price}
	{addJsDef specific_price=$product->specificPrice.price|floatval}
{else}
	{addJsDef specific_price=0}
{/if}
{addJsDef specific_currency=($product->specificPrice && $product->specificPrice.id_currency)|boolval} {* TODO: remove if always false *}
{addJsDef stock_management=$PS_STOCK_MANAGEMENT|intval}
{*addJsDef taxRate=$tax_rate|floatval*}
{addJsDef taxRate=0}
{addJsDefL name=doesntExist}{l s='This combination does not exist for this product. Please select another combination.' js=1}{/addJsDefL}
{addJsDefL name=doesntExistNoMore}{l s='This product is no longer in stock' js=1}{/addJsDefL}
{addJsDefL name=doesntExistNoMoreBut}{l s='with those attributes but is available with others.' js=1}{/addJsDefL}
{addJsDefL name=fieldRequired}{l s='Please fill in all the required fields before saving your customization.' js=1}{/addJsDefL}
{addJsDefL name=uploading_in_progress}{l s='Uploading in progress, please be patient.' js=1}{/addJsDefL}
{addJsDefL name='product_fileDefaultHtml'}{l s='No file selected' js=1}{/addJsDefL}
{addJsDefL name='product_fileButtonHtml'}{l s='Choose File' js=1}{/addJsDefL}
{/strip}
{/if}
