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

{$btnSelectItemSaveText = {l s='Save items' mod='menupro'}}
{$btnSelectItemCloseText = {l s='Close' mod='menupro'}}
<script type="text/javascript">
	var btnSelectItemSaveText = "{$btnSelectItemSaveText}";
	var btnSelectItemCloseText = "{$btnSelectItemCloseText}";
</script>
{if $ps_version>='1.6'}
<div class="modal fade" id="modalSelectItems" tabindex="-1" role="dialog" aria-labelledby="modalSelectItemsTitle" aria-hidden="true">
	<div class="modal-dialog modal-form-edition" role="document">
		<div class="modal-content">
			<div class="modal-header clearfix">
				<h5 class="modal-title pull-left" id="modalSelectItemsTitle">{l s='Close' mod='menupro'}</h5>
				<button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
{else}
<div id="dialogModalParent">
	<div id="modalSelectItems" title="">
		<div id="content">
{/if}
				<div id="divSelectItemNotify" style="display:none;"></div>
				<div class="collapse-group {if $ps_version>='1.6'}panel-group{else}{/if}" id="selectItemAccordion"{if $ps_version>='1.6'} role="tablist" aria-multiselectable="true"{/if}>
					{foreach from=$availableSecondaryMenuType item=secondaryMenuType}
						<div class="collapse-item {if $ps_version>='1.6'}panel-mp panel panel-default{else}{/if}" data-id="{$secondaryMenuType.id|intval}">
							<div class="{if $ps_version>='1.6'}panel-heading-mp panel-heading{else}with-icon{/if} collapse-head" role="tab" id="select-item-headingOne{$secondaryMenuType.id|intval}">
								<div class="panel-title-mp panel-title">
									<a data-toggle="collapse" data-parent="#selectItemAccordion"
										role="button" aria-expanded="true" 
										aria-controls="select-item-collapseOne{$secondaryMenuType.id|intval}"
										href="#select-item-collapseOne{$secondaryMenuType.id|intval}" class="collapse-action link-head-mp" >
										{if $ps_version>='1.6'}<span class="icon-plus"></span>{/if}
										{$secondaryMenuType.name|escape:'htmlall':'UTF-8'}
									</a>
								</div>
							</div>
							<div id="select-item-collapseOne{$secondaryMenuType.id|intval}" class="panel-collapse collapse collapse-target" role="tabpanel" 
								aria-labelledby="select-item-headingOne{$secondaryMenuType.id|intval}" data-id="{$secondaryMenuType.id|intval}"
								data-empty-message="{$secondaryMenuType.emptyMessage|escape:'htmlall':'UTF-8'}">
								<div class="panel-body">
									{if $secondaryMenuType.id!=$availableSecondaryMenuTypeConst.CUSTOMISE}
										<div id="divItems_{$secondaryMenuType.id|intval}">
											{if $ps_version<'1.6'}<div class="clear"></div>{/if}
											<ul class="nav nav-tabs">
												<li class="active"><a data-toggle="tab" href="#tabList_{$secondaryMenuType.id|intval}">{l s='List' mod='menupro'}</a></li>
												<li><a data-toggle="tab" href="#tabSearch_{$secondaryMenuType.id|intval}">{l s='Search' mod='menupro'}</a></li>
											</ul>
											{if $ps_version<'1.6'}<div class="clear"></div>{/if}
											<div class="tab-content">
												<div id="tabList_{$secondaryMenuType.id|intval}" class="tab-pane fade in active">
													<div id="tabListContent_{$secondaryMenuType.id|intval}" style="display:none;" class="{if $ps_version<'1.6'}clearfix{/if}">
														{if $secondaryMenuType.id!=$availableSecondaryMenuTypeConst.CATEGORY}
															<div id="divItemCurrentPage_{$secondaryMenuType.id|intval}" style="display:none;">
																<div id="divItemCurrentPageContent_{$secondaryMenuType.id|intval}" class="" style="display:none;"></div>
																<div id="divItemCurrentPageNotify_{$secondaryMenuType.id|intval}" style="display:none;"></div>
															</div>
															<div id="divItemPagination_{$secondaryMenuType.id|intval}" style="display:none;" class="pull-right">
																<div class="form-inline">
																	<div class="pagination  form-group">
																		<label class="control-label" for="selectItemGoTo_{$secondaryMenuType.id|intval}">{l s='Go to' mod='menupro'}</label>
																		<select class="form-control" id="selectItemGoTo_{$secondaryMenuType.id|intval}"></select>
																	</div>
																	<ul class="pagination form-group">
																		<li>
																			<a id="btnItemPaginatePrevious_{$secondaryMenuType.id|intval}" href="#">
																				{if $ps_version>='1.6'}<i class="icon-double-angle-left"></i>{else}&lt;&lt;{/if}
																				{l s='Previous' mod='menupro'}
																			</a>
																		</li>
																		<li>
																			<a id="btnItemPaginateScrollLeft_{$secondaryMenuType.id|intval}" href="#">
																				{if $ps_version>='1.6'}<i class="icon-angle-left"></i>{else}&lt;{/if}
																				...
																			</a>
																		</li>
																	</ul>
																	<ul id="divPaginationItemsNumbers_{$secondaryMenuType.id|intval}" class="pagination form-group"></ul>
																	<ul class="pagination form-group">
																		<li>
																			<a id="btnItemPaginateScrollRight_{$secondaryMenuType.id|intval}" href="#">
																				...
																				{if $ps_version>='1.6'}<i class="icon-angle-right"></i>{else}&gt;{/if}
																			</a>
																		</li>
																		<li>
																			<a id="btnItemPaginateNext_{$secondaryMenuType.id|intval}" href="#">
																				{l s='Next' mod='menupro'}
																				{if $ps_version>='1.6'}<i class="icon-double-angle-right"></i>{else}&gt;&gt;{/if}
																			</a>
																		</li>
																	</ul>
																</div>
																<div class="form-inline mp-center-content">
																	<label class="control-label">
																		{l s='Page' mod='menupro'}
																		<span id="lblCurrentPage_{$secondaryMenuType.id|intval}"></span>
																		{l s='of' mod='menupro'}
																		<span id="lblPagesCount_{$secondaryMenuType.id|intval}"></span>
																	</label>
																</div>
															</div>
														{/if}
													</div>
													<div id="tabListNotify_{$secondaryMenuType.id|intval}" style="display:none;"></div>
												</div>
												<div id="tabSearch_{$secondaryMenuType.id|intval}" class="tab-pane fade">
													<div id="divSearchType_{$secondaryMenuType.id|intval}" class="form-group clearfix">
														<label class="control-label col-lg-3 col-md-4 col-sm-5 col-xs-6 mp-label">
															{l s='Search by' mod='menupro'}
														</label>
														<div class="col-lg-9 col-md-8 col-sm-7 col-xs-6">
															<label class="radio-inline">
																{if isset($secondaryMenuType.searchName)}{$searchByName=$secondaryMenuType.searchName}{else}{$searchByName={l s='Name' mod='menupro'}}{/if}
																<input type="radio" name="optionSearchBy_{$secondaryMenuType.id|intval}" checked data-type="{$searchMethodConst.BY_NAME|intval}"  
																id="optionSearchByName_{$secondaryMenuType.id|intval}" data-name="{$searchByName|escape:'htmlall':'UTF-8'}">
																{$searchByName|escape:'htmlall':'UTF-8'}
															</label>
															<label class="radio-inline">
																<input type="radio" name="optionSearchBy_{$secondaryMenuType.id|intval}" data-type="{$searchMethodConst.BY_ID|intval}" 
																id="optionSearchById_{$secondaryMenuType.id|intval}" data-name="{l s='Id' mod='menupro'}">
																{l s='Id' mod='menupro'}
															</label>
														</div>
													</div>
													<div id="divSpecifiedItems_{$secondaryMenuType.id|intval}" class="form-group clearfix divSpecifiedItems">
														<label id="lblSearchInput_{$secondaryMenuType.id|intval}" class="control-label col-lg-3 col-md-4 col-sm-5 col-xs-6 mp-label" for="itemAutocompleteInput_{$secondaryMenuType.id|intval}">
															{$searchByName|escape:'htmlall':'UTF-8'}
														</label>
														<div class="col-lg-9 col-md-8 col-sm-7 col-xs-6">
															<div id="ajaxChooseItem_{$secondaryMenuType.id|intval}">
																<div class="input-group">
																	<input class="input-for-enable" type="text" id="itemAutocompleteInput_{$secondaryMenuType.id|intval}" />
																	<span class="input-group-addon"><i id="itemAutocompleteIcon_{$secondaryMenuType.id|intval}" class="icon-search"></i></span>
																</div>
															</div>
															<div id="divSearchChosenItems_{$secondaryMenuType.id|intval}" class="divSearchChosenItems"></div>
														</div>
													</div>
												</div>
											</div>
										</div>
									{else}
										<div id="divCustomizeItem"  class="{if $ps_version<'1.6'}clearfix{/if}">
											<label class="checkbox-inline" for="ckbCustomizeItem">
												<input type="checkbox" id="ckbCustomizeItem">
												{l s='New element' mod='menupro'}
											</label>
											{if $ps_version<'1.6'}<div class="clear"></div>{/if}
											<div id="divCustomizeItemQuantity">
												<label for="txtCustomizeItemQuantity">{l s='Quantity' mod='menupro'}</label>
												<input type="number" min="1" id="txtCustomizeItemQuantity" class="text" value="1">
												<button type="button" class="{if $ps_version>='1.6'}btn btn-default{else}{/if} button-plus" id="btnCustomizeItemPlus">
													{if $ps_version>='1.6'}<span><i class="icon-plus"></i></span>{else}+{/if}
												</button>
												<button type="button" class="{if $ps_version>='1.6'}btn btn-default{else}{/if} button-minus" id="btnCustomizeItemMinus">
													{if $ps_version>='1.6'}<span><i class="icon-minus"></i></span>{else}-{/if}
												</button>
											</div>
										</div>
									{/if}
								</div>
							</div>
						</div>
					{/foreach}
				</div>
{if $ps_version>='1.6'}
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">{$btnSelectItemCloseText}</button>
				<button type="button" id="btnSelectItems" class="btn btn-primary">{$btnSelectItemSaveText}</button>
			</div>
		</div>
	</div>
</div>
{else}
		</div>
	</div>
</div>
{/if}