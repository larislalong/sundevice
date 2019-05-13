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
<div class="collapse-group {if $ps_version>='1.6'}panel-group{else}{/if}" id="accordionMenu{$secondaryMenu.parent_menu|intval}" role="tablist" aria-multiselectable="true">
{/if}
	<div id="newMenuPanel{$secondaryMenu.id_menupro_secondary_menu|intval}" class="collapse-item {if $ps_version>='1.6'}panel-mp panel panel-default{else}clearfix{/if} new-menu-panel">
		<div class="{if $ps_version>='1.6'}panel-heading-mp panel-heading{else}clearfix{/if} collapse-head" role="tab" id="headingMenu{$secondaryMenu.id_menupro_secondary_menu|intval}">
			<h4 class="panel-title col-lg-10 col-md-9 col-sm-8 col-xs-7 panel-title-mp">
				<a class="link-head-mp collapse-action" data-toggle="collapse" role="button" aria-expanded="true"  
				aria-controls="collapseMenu{$secondaryMenu.id_menupro_secondary_menu|intval}"
				data-target="#collapseMenu{$secondaryMenu.id_menupro_secondary_menu|intval}">
					{$secondaryMenu.name|escape:'htmlall':'UTF-8'}
				</a>
			</h4>
			<div id="menuAction{$secondaryMenu.id_menupro_secondary_menu|intval}" class="menu-action pull-right col-lg-2 col-md-3 col-sm-4 col-xs-5" 
			data-id="{$secondaryMenu.id_menupro_secondary_menu|intval}"
				data-position="{$secondaryMenu.position|intval}" data-level="{$secondaryMenu.level|intval}"
				data-name="{$secondaryMenu.name|escape:'htmlall':'UTF-8'}" data-item-type="{$secondaryMenu.item_type|intval}">
				<a title="{$addSubmenusTitle|escape:'htmlall':'UTF-8'}" href="#" class="add-submenus pull-right">
					{if $ps_version>='1.6'}
					<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="{$addSubmenusTitle|escape:'htmlall':'UTF-8'}" data-html="true" data-placement="top">
						<i class="process-icon-new"></i>
					</span>
					{else}
					<img src="../img/admin/add.gif" alt="{$addSubmenusTitle|escape:'htmlall':'UTF-8'}">
					{/if}
				</a>
				<a title="{$editSubmenuTitle|escape:'htmlall':'UTF-8'}" href="#" class="edit-submenu pull-right">
					{if $ps_version>='1.6'}
					<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="{$editSubmenuTitle|escape:'htmlall':'UTF-8'}" data-html="true" data-placement="top">
						<i class="process-icon-edit"></i>
					</span>
					{else}
					<img src="../img/admin/edit.gif" alt="{$editSubmenuTitle|escape:'htmlall':'UTF-8'}">
					{/if}
				</a>
				<a title="{$deleteSubmenuTitle|escape:'htmlall':'UTF-8'}" href="#" class="delete-submenu pull-right">
					{if $ps_version>='1.6'}
					<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="{$deleteSubmenuTitle|escape:'htmlall':'UTF-8'}" data-html="true" data-placement="top">
						<i class="process-icon-delete"></i>
					</span>
					{else}
					<img src="../img/admin/delete.gif" alt="{$deleteSubmenuTitle|escape:'htmlall':'UTF-8'}">
					{/if}
				</a>
				{$enableMessage={l s='Enabled' mod='menupro'}}
				{$disableMessage={l s='Disabled' mod='menupro'}}
				<div id="divMenuStatus{$secondaryMenu.id_menupro_secondary_menu|intval}" class="status-change-submenu pull-right">
					<a class="mp-icon-status list-action-enable {if $secondaryMenu.active}action-enabled{else}action-disabled{/if}" href="#" 
					title="{if $secondaryMenu.active}{$enableMessage|escape:'htmlall':'UTF-8'}{else}{$disableMessage|escape:'htmlall':'UTF-8'}{/if}">
						{if $ps_version>='1.6'}
						<span title="" data-toggle="tooltip" class="label-tooltip" 
						data-original-title="{if $secondaryMenu.active}{$enableMessage|escape:'htmlall':'UTF-8'}{else}{$disableMessage|escape:'htmlall':'UTF-8'}{/if}" 
						data-html="true" data-placement="top">
						{if $secondaryMenu.active}
							<i class="icon-check"></i>
						{else}
							<i class="icon-remove"></i>
						{/if}
						</span>
						{else}
						<img src="../img/admin/{if $secondaryMenu.active}enabled{else}disabled{/if}.gif" alt="{if $secondaryMenu.active}{$enableMessage|escape:'htmlall':'UTF-8'}{else}{$disableMessage|escape:'htmlall':'UTF-8'}{/if}">
						{/if}
					</a>
				</div>
			</div>
		</div>
		<div id="collapseMenu{$secondaryMenu.id_menupro_secondary_menu|intval}" class="panel-collapse collapse collapse-target" 
		role="tabpanel" aria-labelledby="headingMenu{$secondaryMenu.id_menupro_secondary_menu|intval}">
			<div id="newMenuPanelBody{$secondaryMenu.id_menupro_secondary_menu|intval}" class="panel-body new-menu-panel-body">