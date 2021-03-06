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

{if $firstItems}
	{if $secondaryMenu.level==2}
		<div class="menu-sub-level-1" {$secondaryMenu.parent_style_for_container nofilter}{* HTML, cannot escape*}>
			{if isset($secondaryMenu.html_contents[$htmlContentPositionsConst.TOP])  && (!empty($secondaryMenu.html_contents[$htmlContentPositionsConst.TOP]))}
			<div class="mp-image-top">
				<div class="mp-image-top_inner row">
					<div class="col-md-12">
						{$secondaryMenu.html_contents[$htmlContentPositionsConst.TOP] nofilter}{* HTML, cannot escape*}
					</div>
				</div>
			</div>
			{/if}
			<div class="grig_items_mp">
				{if isset($secondaryMenu.html_contents[$htmlContentPositionsConst.LEFT])  && (!empty($secondaryMenu.html_contents[$htmlContentPositionsConst.LEFT]))}
				<div class="mp-image-left hidden-sm hidden-xs">
					<div class="mp-image-left_inner row">
						<div class="col-md-12">
							{$secondaryMenu.html_contents[$htmlContentPositionsConst.LEFT] nofilter}{* HTML, cannot escape*}
						</div>
					</div>
				</div>
				{/if}
				<ul {$secondaryMenu.parent_style_for_container nofilter}{* HTML, cannot escape*}>
	{else}
		<ul   
		{$secondaryMenu.parent_style_for_container nofilter}{* HTML, cannot escape*}>
	{/if}
{/if}

	<li class="{if ($secondaryMenu.has_children) && ($secondaryMenu.level==1)}menu-dropdown-icon {else}normal-sub{/if}">
		{include file="{$displayElementTemplate|escape:'htmlall':'UTF-8'}" isFooterTemplate=false hasAdditionalClass=false}
		{if (!$secondaryMenu.use_custom_content) && ($secondaryMenu.display_style!=$displayStylesConst.COMPLEX)}
			{if $secondaryMenu.level==2}<hr/>{/if}
			{if ($secondaryMenu.has_children) && ($secondaryMenu.level==1)}
				<span class="mp-icon-responsive mp-icon-plus"></span>
			{/if}
		{/if}