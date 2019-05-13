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

{if $secondaryMenu.use_custom_content}
	<div class="mp-custom-content">{$secondaryMenu.custom_content nofilter}</div>
{else}
	{if $secondaryMenu.display_style==$displayStylesConst.COMPLEX}
		{if $secondaryMenu.item_type==$menuTypesConst.PRODUCT}
			{if $ps_version<'1.7'}
				{include file="{$productComplexTemplates|escape:'htmlall':'UTF-8'}"}
			{else}
				<div class="mp-product-complex">
					{include file='catalog/_partials/miniatures/product.tpl' product=$complex_products}
				</div>
			{/if}
		{elseif $secondaryMenu.item_type==$menuTypesConst.CATEGORY}
			{if $ps_version<'1.7'}
				{include file="{$categoryComplexTemplates|escape:'htmlall':'UTF-8'}"}
			{else}
				<div class="mp-category-complex">
					{include file='catalog/_partials/miniatures/category.tpl' category=$complex_category}
				</div>
			{/if}
		{/if}
	{else}
		{if $isFooterTemplate && ($secondaryMenu.level==1)}
			<{if $ps_version<'1.6'}p{elseif $ps_version<'1.7'}h4{else}h3{/if} class="mp-footer-menu-title {if ($ps_version<'1.6') && ($ps_version<'1.7')}title_block{elseif $ps_version>='1.7'}hidden-sm-down{/if}">
		{/if}
		<a href="{if $secondaryMenu.clickable}{$secondaryMenu.link|escape:'javascript'}{else}javascript:void(0);{/if}" 
		title="{$secondaryMenu.name|escape:'htmlall':'UTF-8'}"
		class="{if $hasAdditionalClass}{$additionalClass|escape:'htmlall':'UTF-8'}{/if} mp-menu-link 
		{if $secondaryMenu.has_children} has-children{/if}{if !$secondaryMenu.clickable} mp-not-clickable{else} mp-clickable{/if}" 
		{if $secondaryMenu.new_tab} target="_blank"{/if} 
		{$secondaryMenu.style.active nofilter} {$secondaryMenu.style.reset_active nofilter} 
		{$secondaryMenu.style.no_event nofilter} {$secondaryMenu.style.reset nofilter} 
		{$secondaryMenu.style.hover nofilter}>
		{if !empty($secondaryMenu.icon_css_class)}
			<i class="{$secondaryMenu.icon_css_class|trim|escape:'htmlall':'UTF-8'}"></i>
		{/if}
		{$secondaryMenu.name|escape:'htmlall':'UTF-8'}
		</a>
		{if $isFooterTemplate && ($secondaryMenu.level==1)}
			</{if $ps_version<'1.6'}p{elseif $ps_version<'1.7'}h4{else}h3{/if}>
		{/if}
	{/if}
{/if}