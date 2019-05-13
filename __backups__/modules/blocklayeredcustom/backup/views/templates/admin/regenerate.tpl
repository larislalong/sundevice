<script type="text/javascript">
	var ajaxModuleUrl = "{$ajaxModuleUrl|escape:'javascript'}";
	var FROM_TEXT = "{l s='From' mod='blocklayeredcustom'}";
	var TO_TEXT = "{l s='To' mod='blocklayeredcustom'}";
	var SUCCESS_TEXT = "{l s='Price of combinations of this product are up to date' mod='blocklayeredcustom'}";
	var autoStartDeprecated = true;
</script>
<div id="divNotifyGeneral"></div>
<div id="divRegenerateParent" class="panel">
	<h3>{l s='Regeneration progress' mod='blocklayeredcustom'}</h3>
	<div id="divAllAction" class="panel clearfix">
		<button type="button" class="btn btn-default btnStartAll">
			<i class="process-icon-cogs"></i>{l s='Start All' mod='blocklayeredcustom'}
		</button>
		<button type="button" class="btn btn-default btnUpdate">
			<i class="process-icon-refresh"></i>{l s='Update' mod='blocklayeredcustom'}
		</button>
		<button type="button" class="btn btn-default btnCancelAll" style="display:none;">
			<i class="process-icon-cancel"></i>{l s='Cancel All' mod='blocklayeredcustom'}
		</button>
	</div>
	<div class="row">
		<div id="tabs">
			<div class="productTabs col-lg-3 col-md-4">
				<div class="tab list-group">
					{$first = true}
					{foreach $products as $product}
					<a id="nav-product{$product.id_product|intval}" class="list-group-item nav-optiongroup{if $first} active{/if}{if $product.is_up_to_date} list-group-item-success{else} list-group-item-warning{/if}" href="#" title="{$product.name}">{$product.id_product}: {$product.name}<span class="nav-loader-span" style="display:none;"><i class="icon-refresh icon-spin"></i></span></a>
					{$first = false}
					{/foreach}
				</div>
			</div>
		</div>
		<div class="form-horizontal col-lg-9 col-md-8">
			<div class="panel product-content-list">
				{$first = true}
				{foreach $products as $product}
				<div id="div-product{$product.id_product|intval}" class="product-content-item nav-product{$product.id_product|intval} tab-optiongroup nav-product" data-id_product="{$product.id_product|intval}" data-is_up_to_date="{$product.is_up_to_date|intval}" data-name="{$product.name}" {if !$first}style="display:none;"{/if}>
					<h3>{$product.id_product}: {$product.name}</h3>
					<div class="div-content-warning" {if $product.is_up_to_date}style="display:none;"{/if}><div class="alert alert-warning">{l s='Price of combinations of this product are not up to date' mod='blocklayeredcustom'}</div></div>
					<div class="div-content-buttons">
						<button type="button" class="btn btn-default btnRegenerate">
							<i class="icon-cogs"></i>  {l s='Regenerate' mod='blocklayeredcustom'}
						</button>
						<button type="button" class="btn btn-default btnCancel" style="display:none;">
							<i class="icon-stop"></i>  {l s='Cancel' mod='blocklayeredcustom'}
						</button>
						<button type="button" class="btn btn-default btnResume" style="display:none;">
							<i class="icon-play"></i>  {l s='Resume' mod='blocklayeredcustom'}
						</button>
						<button type="button" class="btn btn-default btnPause" style="display:none;">
							<i class="icon-pause"></i>  {l s='Pause' mod='blocklayeredcustom'}
						</button>
					</div>
					<div class="div-content-count" style="display:none;">
						<span class="">{l s='Combinations count' mod='blocklayeredcustom'}: </span><span class="value"></span>
					</div>
					<div class="div-content-loader" style="display:none;">
						<div class="loading"><i class="icon-refresh icon-spin"></i>{l s='Regenerating combinations of ' mod='blocklayeredcustom'} {$product.name} ...</div>
					</div>
					<ul class="div-content-log"></ul>
					
					<div class="div-content-success"></div>
					<div class="div-content-errors"></div>
				</div>
				{$first = false}
				{/foreach}
			</div>
		</div>
	</div>
	<div class="panel-footer clearfix">
		<a href="{$homeLink}" class="btn btn-default pull-right">
			<i class="process-icon-back"></i>{l s='Back to module configuration page' mod='blocklayeredcustom'}
		</a>
	</div>
</div>