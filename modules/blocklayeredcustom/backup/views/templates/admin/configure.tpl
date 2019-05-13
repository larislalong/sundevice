{**
* 2015-2017 Crystals Services
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    Crystals Services Sarl <contact@crystals-services.com>
*  @copyright 2015-2017 Crystals Services Sarl
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of Crystals Services Sarl
*}
<script type="text/javascript">
	var filterTypeDefinition = {$filterTypeDefinition|json_encode};
</script>
<div class="panel clearfix">
	<a href="{$priceRegenerationLink}" class="btn btn-primary btn-lg">
		<i class="process-icon-cogs"></i>{l s='Regenerate price index' mod='blocklayeredcustom'}
	</a>
</div>
<form action="" method="post" class="defaultForm form-horizontal" id="formBlockFilter">
	<input type="hidden" name="submitFilterBlockSave" value="1">
	<div class="panel">
		<h3>{l s='Configuration of filter' mod='blocklayeredcustom'}</h3>
		<div class="table-responsive-row clearfix">
			<table class="table table-blockFilter">
				<thead>
					<tr class="nodrag nodrop">
						<th class=""><span class="title_box">{l s='Filter name' mod='blocklayeredcustom'}</span></th>
						<th class=""><span class="title_box">{l s='Active' mod='blocklayeredcustom'}</span></th>
						<th class=""><span class="title_box">{l s='Filter type' mod='blocklayeredcustom'}</span></th>
						<th class=""><span class="title_box">{l s='Position' mod='blocklayeredcustom'}</span></th>
						<th class=""><span class="title_box">{l s='Multiple' mod='blocklayeredcustom'}</span></th>
					</tr>
				</thead>
				<tbody>
					{foreach $listBlockFilter as $blockFilter}
					<tr class="tr-blockFilter">		
						<td>{$blockFilter.label}</td>
						<td>
							<span class="switch prestashop-switch fixed-width-lg">
								<input type="radio" name="BLC_BLOCK_FILTER_ACTIVE{$blockFilter.code}" id="BLC_BLOCK_FILTER_ACTIVE{$blockFilter.code}_on" value="1" {if $blockFilter.active}checked="checked"{/if}>
								<label for="BLC_BLOCK_FILTER_ACTIVE{$blockFilter.code}_on">{l s='Yes' mod='blocklayeredcustom'}</label>
								<input type="radio" name="BLC_BLOCK_FILTER_ACTIVE{$blockFilter.code}" id="BLC_BLOCK_FILTER_ACTIVE{$blockFilter.code}_off" value="" {if !$blockFilter.active}checked="checked"{/if}>
								<label for="BLC_BLOCK_FILTER_ACTIVE{$blockFilter.code}_off">{l s='No' mod='blocklayeredcustom'}</label>
								<a class="slide-button btn"></a>
							</span>
						</td>
						<td>
							<select name="BLC_BLOCK_FILTER_FILTER_TYPE{$blockFilter.code}" class="filter-type-field">
								{foreach $blockTypeFilters[$blockFilter.block_type] as $filterCode}
								<option value="{$filterCode|intval}" {if $blockFilter.filter_type == $filterCode}selected{/if}>
									{$filterTypeDefinition[$filterCode].label}
								</option>
								{/foreach}
							</select>
						</td>
						<td>
							<input type="text" name="BLC_BLOCK_FILTER_POSITION{$blockFilter.code}" id="" value="{$blockFilter.position|intval}">
						</td>
						<td class="block-multiple">
							<span class="switch prestashop-switch fixed-width-lg span-multiple" {if !$filterTypeDefinition[$blockFilter.filter_type].configure_multiple}style="display:none;"{/if}>
								<input class="radio-on" type="radio" name="BLC_BLOCK_FILTER_MULTIPLE{$blockFilter.code}" id="BLC_BLOCK_FILTER_MULTIPLE{$blockFilter.code}_on" value="1" {if $blockFilter.multiple}checked="checked"{/if}>
								<label for="BLC_BLOCK_FILTER_MULTIPLE{$blockFilter.code}_on">{l s='Yes' mod='blocklayeredcustom'}</label>
								<input class="radio-off" type="radio" name="BLC_BLOCK_FILTER_MULTIPLE{$blockFilter.code}" id="BLC_BLOCK_FILTER_MULTIPLE{$blockFilter.code}_off" value="" {if !$blockFilter.multiple}checked="checked"{/if}>
								<label for="BLC_BLOCK_FILTER_MULTIPLE{$blockFilter.code}_off">{l s='No' mod='blocklayeredcustom'}</label>
								<a class="slide-button btn"></a>
							</span>
						</td>
					</tr>
					{/foreach}
				</tbody>
			</table>
		</div>
		<div class="panel-footer">
			<button type="submit" value="1" name="submitblocklayeredcustomModule" class="btn btn-default pull-right">
				<i class="process-icon-save"></i>{l s='Save' mod='blocklayeredcustom'}
			</button>
		</div>
	</div>
	{$configurationFormContent}
</form>