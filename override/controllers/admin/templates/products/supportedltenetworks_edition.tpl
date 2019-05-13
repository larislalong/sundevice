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
<div class="productLte_edition_content">
	<div class="form-group">
		<button type="button" class="btn btn-primary btnGenerateMarket">{l s='Generate markets'}</button>
	</div>	
	<div class="form-group block-regenerate-markets" style="display:none;">
		<div class="form-group">
			<label class="control-label col-lg-2" for="">{l s='Content'}</label>
			<div class="col-lg-10">
				<textarea class="txtRegenerateMarket" rows="30"></textarea>
			</div>
		</div>
		<div class="form-group">
			<button type="button" class="btn btn-default pull-right btnResetMarkets"><i class="process-icon-cogs"></i>{l s='Reset markets'}</button>
		</div>
		<div class="form-group blockRegenerateContent" style="display:none;"></div>
	</div>
	<div class="panel productLte_edition_content">
		<div class="form-group block-markets">
			<input type="hidden" id="txtTotalMarket" value="{count($markets)|intval}" class="txt-total-markets"/>
			<div class="panel-heading head-market">
				{l s='Markets'}<span class="badge market-count">{count($markets)|intval}</span>
				<span class="panel-heading-action">
					<a class="market-new list-toolbar-btn" href="#">
						<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="{l s='Add'}" data-html="true" data-placement="top">
						<i class="process-icon-new"></i>
						</span>
					</a>
				</span>
			</div>	
			<div class="table-responsive-row clearfix">
				<table class="table table-markets">
					<thead>
						<tr class="nodrag nodrop">
							<th class=""><span class="title_box">{l s='Market name'}</span></th>
							<th class=""><span class="title_box">{l s='Image'}</span></th>
							<th class=""><span class="title_box">{l s='LTE Networks'}</span></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					{$i=1}
					{foreach $markets as $market}
					<tr class="tr-market  {if $i%2 !=0}odd{/if}">		
						<td class="td_market_name">
							<div>
								{include file="{$custom_text_lang_tpl}" languages=$languages input_name='market_name' isTextArea =false 
									input_class="form-control market-field"
									input_value=$market.market_name index = $i}
							</div>
						</td>
						<td class="td_market_image"><input type="text" name="market_image" value="{$market.market_image}" class="market-field" data-field-name="market_image"/></td>
						<td class="td_market_content">
							<div>
								{include file="{$custom_text_lang_tpl}" languages=$languages input_name='content' isTextArea =true 
									input_class="autoload_rte_lte market-field"
									input_value=$market.content index = $i}
							</div>	
						</td>
						<td class="text-right">
							<a href="#" class="btn btn-default market-delete" title="{l s='Delete'}"><i class="icon-trash"></i></a>
						</td>
					</tr>
					{$i=$i+1}
					{/foreach}
					</tbody>
				</table>
			</div>
		</div>
		<div class="panel-footer">
			<button type="button" class="btn btn-default btnCancel"><i class="process-icon-cancel"></i> {l s='Cancel'}</button>
			<button type="button" class="btn btn-default pull-right btnSave"><i class="process-icon-save"></i> {l s='Save'}</button>
		</div>
	</div>
</div>
<script type="text/javascript">
	hideOtherLanguage({$default_form_language});
</script>
