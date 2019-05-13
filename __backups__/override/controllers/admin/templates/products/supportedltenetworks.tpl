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

{if isset($product->id)}
	<style type="text/css">
	{literal}
	#product-supportedltenetworks .lte_content{ padding-top: 20px;}
	#content #product-supportedltenetworks .lte_content h3{ margin: 0px;}
	#product-supportedltenetworks .table-productLTE table{ width: 100%;}
	#product-supportedltenetworks .table-productLTE .countries_dl {width: 490px; padding-top: 18px;}
	#product-supportedltenetworks .table-productLTE table th {width: 245px; vertical-align: top;  padding: 0px;}
	#product-supportedltenetworks .table-productLTE table th h3{
		margin: 0px 16px 0px 0px;
		border-bottom: 2px solid #333;
		text-align: left;
		color: #333;
		font-size: 16px;
		line-height: 1.5000;
		text-transform: capitalize;
	}
	#product-supportedltenetworks .table-productLTE .countries_dt {float: left;width: 290px;}
	#product-supportedltenetworks .table-productLTE .countries_dd {float: left; padding-bottom: 18px;}
	{/literal}
	</style>
	<div id="product-supportedltenetworks" class="panel product-tab">
		<input type="hidden" class="productId" value="{$product->id}" />
		<input type="hidden" name="submitted_tabs[]" value="SupportedLTENetworks" />
		<h3>{l s='Supported LTE Networks by model'}</h3>
		<div class="form-group">
			<div class="alert alert-info">
				{l s='To edit identifier of the model attribute, '}<a target="_blank" class="" href="{$preferencesLink}">{l s='Click here'}</a>
			</div>
		</div>
		<div id="divProductLTENotify" style="display:none;"></div>
		<div class="form-group" id="productLTE-EditionForm" style="display:none;">
			
		</div>
		<div>
			<div id="divProductLTEListDisabler" style="display:none; position: absolute;width: 98%; height: 96%; background-color: #000; z-index: 9; opacity: 0.3;" class="div-disabler"></div>
			<div class="panel">
				<div class="panel-heading head-productLTE">
					{l s='Models'}<span class="badge productLTE-count">{count($productLTEList)}</span>
				</div>	
				<div class="table-responsive-row clearfix">
					<table class="table table-productLTE">
						<thead>
							<tr class="nodrag nodrop">
								<th class=""><span class="title_box">{l s='Model name'}</span></th>
								<th class=""><span class="title_box">{l s='Content'}</span></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							{$i = 1}
							{foreach $productLTEList as $productLTE}
							<tr class="tr-productLTE {if $i%2 !=0}odd{/if}">
								<input type="hidden" name="" value="{$productLTE.id_product_supported_lte|intval}" class="td_id_product_supported_lte">
								<input type="hidden" name="" value="{$productLTE.id_attribute|intval}" class="td_id_attribute">
								<td>{$productLTE.attributeName}</td>
								<td class="lte_content">{$productLTE.content}</td>
								<td class="text-right">
									<a href="#" class="btn btn-default productLTE-edit" title="{l s='Edit'}"><i class="icon-pencil"></i>{l s='Edit'}</a>
								</td>
							</tr>
							{$i = $i + 1}
							{/foreach}
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="panel-footer">
			<a href="{$link->getAdminLink('AdminProducts')|escape:'html':'UTF-8'}{if isset($smarty.request.page) && $smarty.request.page > 1}&amp;submitFilterproduct={$smarty.request.page|intval}{/if}" class="btn btn-default"><i class="process-icon-cancel"></i> {l s='Cancel'}</a>
			<button type="submit" name="submitAddproduct" class="btn btn-default pull-right" disabled="disabled"><i class="process-icon-loading"></i> {l s='Save'}</button>
			<button type="submit" name="submitAddproductAndStay" class="btn btn-default pull-right" disabled="disabled"><i class="process-icon-loading"></i> {l s='Save and stay'}</button>
		</div>
	</div>
{/if}
