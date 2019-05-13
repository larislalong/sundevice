{*
* 2007-2016 PrestaShop
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<div id="product-packlist" class="panel product-tab">
	<input type="hidden" name="submitted_tabs[]" value="PackList" />
	<h3>{l s='Pack List'}</h3>
	<div class="form-group clearfix">
		<label class="control-label col-lg-4 col-md-4 col-sm-4 col-xs-4" for="pack_autocomplete_input">
			{l s='Packs'}
		</label>
		<div class="col-lg-5 col-md-5 col-sm-6 col-xs-7">
			<div id="ajax_choose_category">
				<div class="input-group">
					<input class="" type="text" id="pack_autocomplete_input" />
					<span class="input-group-addon"><i class="icon-search"></i></span>
				</div>
			</div>
			<div id="divSelectedPack">
				{foreach $selectedPacks as $pack}
				<div class="form-control-static parentDivDelPack">
					<input name="selectedPacks[]" class="inputSelectedPack" type="checkbox" value="{$pack.id_pack}" checked  style="display:none;" />
					<button type="button" class="delPack btn btn-default" data-id-product="{$pack.id_pack}">
						<i class="icon-remove text-danger"></i>
					</button>&nbsp;{$pack.product->name}<label style="margin-left:50px;" for="radiopack_{$pack.id_pack}">{l s='Default'}<input id="radiopack_{$pack.id_pack}" type="radio" name="default_pack" value="{$pack.id_pack}" {if $pack.is_default}checked{/if} /></label>
				</div>
				{/foreach}
			</div>
		</div>
	</div>
	<div class="panel-footer">
		<a href="{$link->getAdminLink('AdminProducts')|escape:'html':'UTF-8'}{if isset($smarty.request.page) && $smarty.request.page > 1}&amp;submitFilterproduct={$smarty.request.page|intval}{/if}" class="btn btn-default"><i class="process-icon-cancel"></i> {l s='Cancel'}</a>
		<button type="submit" name="submitAddproduct" class="btn btn-default pull-right" disabled="disabled"><i class="process-icon-loading"></i> {l s='Save'}</button>
		<button type="submit" name="submitAddproductAndStay" class="btn btn-default pull-right" disabled="disabled"><i class="process-icon-loading"></i> {l s='Save and stay'}</button>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('#pack_autocomplete_input').autocomplete('ajax_products_list.php?exclude_packs=0&excludeVirtuals=0', {
			minChars: 1,
			autoFill: true,
			max: 20,
			matchContains: true,
			mustMatch: false,
			scroll: false,
			cacheLength: 0,
			formatItem: function (item) {
				return item[1] + ' - ' + item[0];
			}
		}).result(addProductPack);
		setPackToExclude();
		function addProductPack(event, data, formatted){
			if (data == null)
				return false;
			var productId = data[1];
			var productName = data[0];

			var divSelectedPack = $('#divSelectedPack');
			divSelectedPack.append( '<div class="form-control-static parentDivDelPack"><input name="selectedPacks[]" class="inputSelectedPack" type="checkbox" value="'+productId+
			'" checked  style="display:none;" /><button type="button" class="delPack btn btn-default" data-id-product="' + productId + 
			'"><i class="icon-remove text-danger"></i></button>&nbsp;' + productName + 
			'<label style="margin-left:50px;" for="radiopack_'+productId+'">{l s='Default'}<input type="radio" name="default_pack" value="'+productId+'" id=radiopack_"'+productId+'" /></label></div>');
			$('#pack_autocomplete_input').val('');
			setPackToExclude();
			
		}
		{literal}
		function setPackToExclude(){
			var checked = [];
			checked.push(id_product);
			$('#divSelectedPack .inputSelectedPack').each(function ()
			{
				checked.push($(this).val());
			});
			$('#pack_autocomplete_input').setOptions({
				extraParams: {excludeIds: checked.join(',')}
			});
		}
		{/literal}
		$('#divSelectedPack').delegate('.delPack', 'click', function () {
			$(this).closest('.parentDivDelPack').remove();
			setPackToExclude();
        });
	});
</script>
