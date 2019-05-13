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

{if $secondaryMenu.level==1}
	<{if ($ps_version>='1.6') && ($ps_version<'1.7')}section{else}div{/if} class="mp-footer-menu {if $ps_version<'1.6'}mp-footer-menu-1-5{elseif $ps_version<'1.7'}footer-block col-xs-12 col-sm-2{else}col-md-2 links wrapper{/if}" {$secondaryMenu.parent_style_for_container nofilter}>
{else}
	{if $firstItems}
		<ul id="mp-footer-{$mainMenu.id_menupro_main_menu|intval}-{$secondaryMenu.parent_menu|intval}"  
		class="{if $ps_version<'1.7'}toggle-footer{else}collapse{/if} {if $secondaryMenu.level>2} mp-footer2-sub-menu{/if}" 
		{$secondaryMenu.parent_style_for_container nofilter}{* HTML, cannot escape*}>
	{/if}
{/if}

{if $secondaryMenu.level!=1}
	<li class="{if $ps_version<'1.7'}item{/if}">
{/if}

{include file="{$displayElementTemplate|escape:'htmlall':'UTF-8'}" isFooterTemplate=true hasAdditionalClass=false}
{if ($ps_version>='1.7') && ($secondaryMenu.has_children) && ($secondaryMenu.level==1)}
	<div class="title clearfix hidden-md-up">
		<span class="h3">
			{include file="{$displayElementTemplate|escape:'htmlall':'UTF-8'}" isFooterTemplate=false hasAdditionalClass=false}
		</span>
		<span class="pull-xs-right" data-target="#mp-footer-{$mainMenu.id_menupro_main_menu|intval}-{$secondaryMenu.id_menupro_secondary_menu|intval}" data-toggle="collapse">
			<span class="navbar-toggler collapse-icons">
				<i class="material-icons add"></i>
				<i class="material-icons remove"></i>
			</span>
		</span>
	</div>
{/if}