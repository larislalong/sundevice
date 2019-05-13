{$customizationField=null}
{foreach from=$customizationFields item='field' name='customizationFields'}
	{if $field.type == 0}
		{assign var='key' value='pictures_'|cat:$product->id|cat:'_'|cat:$field.id_customization_field}
		{if isset($pictures.$key)}{$fieldValue=$pictures.$key}{else}{$fieldValue=''}{/if}
		{$customizationField = $field}
		{break}
	{/if}
{/foreach}
{$isCustomPackeging = 0}
{$currentPackegingCode = 0}
{if !empty($fieldValue) && ($fieldValue!=$packegingDefinition.PACKEGING_ECOBOX.image_key) && ($fieldValue!=$packegingDefinition.PACKEGING_CRYSTALS_BOX.image_key)}
{$isCustomPackeging = 1}
{$currentPackegingCode = $packegingDefinition.PACKEGING_CUSTOM_BOX.code}
{elseif $fieldValue==$packegingDefinition.PACKEGING_ECOBOX.image_key}
{$currentPackegingCode = $packegingDefinition.PACKEGING_ECOBOX.code}
{elseif $fieldValue==$packegingDefinition.PACKEGING_CRYSTALS_BOX.image_key}
{$currentPackegingCode = $packegingDefinition.PACKEGING_CRYSTALS_BOX.code}
{/if}
{if $customizationField!=null}
<div class="packaging_left" id="divPackegingLeft" data-href="{$customizationFormTarget}">
	<div class="packaging_left_title">
		<span>{l s='Packaging'}</span>
		<a href="#helpAvailablePackaging" class="packaging-help-link"><i class="fa fa-question-circle" aria-hidden="true"></i>{l s='Available packagings'}</a>
	</div>
	<div class="clear"></div>
	<div class="packaging_attribute_list">
		<ul>
		{foreach from=$packegingDefinition item='packegingItem'}
			<li id="packaging-item{$packegingItem.code}" class="packaging-item{if $currentPackegingCode==$packegingItem.code} selected{/if}" data-value="{$packegingItem.image_key}" data-code="{$packegingItem.code}">
				<span>
				{if $packegingItem.code==$packegingDefinition.PACKEGING_ECOBOX.code}{l s='Eco box'}
				{elseif $packegingItem.code==$packegingDefinition.PACKEGING_CRYSTALS_BOX.code}{l s='Crystal box'}
				{elseif $packegingItem.code==$packegingDefinition.PACKEGING_CUSTOM_BOX.code}{l s='Custom box'}
				{else}{$packegingItem.label}
				{/if}
				</span>
			</li>
		{/foreach}
		</ul>
	</div>
	<div class="packeging-custom-content" {if !$isCustomPackeging}style="display:none;"{/if}>
		<p class="infoCustomizable">
			{l s='Allowed file formats are: GIF, JPG, PNG'}
		</p>
		<div class="customizableProductsFile">
			<h5 class="product-heading-h5">{l s='Pictures'}</h5>
			<ul id="uploadable_files" class="clearfix">
				<li class="customizationUploadLine{if $customizationField.required} required{/if}">
					<div class="divChoosenImage" {if empty($fieldValue)  || !$isCustomPackeging}style="display:none;"{/if}>
						<div class="customizationUploadBrowse">
							<img id="imgChoosen" src="{if !empty($fieldValue) && $isCustomPackeging}{$pic_dir}{$fieldValue}_small{/if}" alt="" />
							<a href="#" id="linkDeleteChoosen" data-href="{$link->getProductDeletePictureLink($product, $customizationField.id_customization_field)|escape:'html':'UTF-8'}" title="{l s='Delete'}" >
								<img src="{$img_dir}icon/delete.gif" alt="{l s='Delete'}" class="customization_delete_icon" width="11" height="13" />
							</a>
						</div>
					</div>
					<div class="customizationUploadBrowse form-group">
						<label class="customizationUploadBrowseDescription">
							{if !empty($customizationField.name)}
								{$customizationField.name}
							{else}
								{l s='Please select an image file from your computer'}
							{/if}
							{if $customizationField.required}<sup>*</sup>{/if}
						</label>
						<input type="file" name="file{$customizationField.id_customization_field}" id="imgCustomPackeging" class="form-control customization_block_input {if isset($pictures.$key)}filled{/if}" />
					</div>
				</li>
			</ul>
		</div>
	</div>
	<p id="customizedDatas">
		<input type="hidden" name="choosenPackegingCode" id="choosenPackegingCode" value="{$currentPackegingCode}" />
		<input type="hidden" name="choosenPackegingValue" id="choosenPackegingValue" value="{$fieldValue}" />
		<span id="ajax-loader" class="unvisible">
			<img src="{$img_ps_dir}loader.gif" alt="loader" />
		</span>
	</p>
	<div class="clear"></div>
</div>
<div id="helpAvailablePackaging" style="display:none;">{$available_packeging}</div>
{/if}