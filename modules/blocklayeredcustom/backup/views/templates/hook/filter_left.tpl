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
	var filterTypeConst = {$filterTypeConst|json_encode};
	var maxFilterItems = {$maxFilterItems|intval};
</script>
<form action="" method="post" class="" id="formFilterLeft">
	{if $hasFiltersSelected}
	<div class="block-selected-filters">
		<div class="selected-filter-title">{l s='Selected filters' mod='blocklayeredcustom'}</div>
		<div class="selected-filters-list">
		{foreach $selectedFilters as $selectedFilter}
			<div class="selected-filters-item" data-id-block="{$selectedFilter.idBlock}" data-block-type="{$selectedFilter.blockType}">
                <a class="selected-filters-item-remove" href="#" title="{l s='Remove' mod='blocklayeredcustom'}">
					<i class="fa fa-times-circle" aria-hidden="true"></i>
				</a>
				<span class="selected-filters-item-title">{$selectedFilter.label}: {$selectedFilter.names}</span>
			</div>
		{/foreach}
		</div>
		<div class="selected-filters-reset">
			<a class="btn-reset-all" href="#"><i class="fa fa-times-circle" aria-hidden="true"></i><span>{l s='Reset all' mod='blocklayeredcustom'}</span></a>
		</div>
	</div>
	{/if}
	<div class="filter_item_inner">
        <h3 class="filter-title1">{l s='Filters' mod='blocklayeredcustom'}</h3>
		{foreach $listBlockFilter as $blockFilter}
		<div id="filter-item{$blockFilter.id_blc_filter_block|intval}" class="filter-item clearfix" data-id-block="{$blockFilter.id_blc_filter_block|intval}" data-block-type="{$blockFilter.block_type|intval}" data-filter-type="{$blockFilter.filter_type|intval}">
			<div class="filter-head">
				<span class="filter-title">{$blockFilter.label}</span>
				<span class="filter-icon close"><i class="fa fa-plus" aria-hidden="true"></i></span>
			</div>
			<div class="filter-content close">
				{$showSeeMore = 0}
				{if isset($blockFilter.show_see_more) && $blockFilter.show_see_more && ($maxFilterItems>0) && ($blockFilter.selectables_count>0) && ($maxFilterItems < $blockFilter.selectables_count)}
					{$showSeeMore = 1}
				{/if}
				<div class="filter-selectables" data-filter-type="{$blockFilter.filter_type|intval}" data-multiple="{$blockFilter.multiple|intval}" 
					data-is-color="{if isset($blockFilter.value_type) && ($blockFilter.value_type=='color')}1{else}0{/if}" 
					data-show-see-more="{$showSeeMore|intval}" {if $showSeeMore}data-selectables-count="{$blockFilter.selectables_count|intval}"{/if}>
					{include file="{$templateFolder}selectables_{$filterTypeDefinition[$blockFilter.filter_type].tpl_suffix}.tpl"}
					{if $showSeeMore}
					<div class="div-see-more">
						<a class="btn-see-more" href="#">{l s='See more' mod='blocklayeredcustom'}</a>
					</div>
					{/if}
				</div>
			</div>
		</div>
		{/foreach}
	</div>
</form>