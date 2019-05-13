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

{$btnSortReorganiseText = {l s='Reorganize' mod='menupro'}}
{$btnSortCloseText = {l s='Close' mod='menupro'}}
<script type="text/javascript">
	var mainMenu = {$mainMenu|json_encode};
	const ITEMS_PER_PAGE = {$ITEMS_PER_PAGE|intval};
	var availableSecondaryMenuTypeConst = {$availableSecondaryMenuTypeConst|json_encode};
	var itemTypeParams = {$itemTypeParams|json_encode};
	var imageDisplayTypeFolder = "{$imageDisplayTypeFolder|escape:'htmlall':'UTF-8'}";
	var searchMethodConst = {$searchMethodConst|json_encode};
	var modalSelecItemTitlePrefix = "{l s='Select items for' mod='menupro'}";
	var loaderInitializeAttachableItemListMessage = "{l s='List initialization...' mod='menupro'}";
	var loaderSecondaryMenuEditionFormMessage = "{l s='Loading form...' mod='menupro'}";
	var loaderDeleteSecondaryMenuMessage = "{l s='Deleting menu...' mod='menupro'}";
	var loaderStatusChangeSecondaryMenuMessage = "{l s='Changing menu status...' mod='menupro'}";
	var loaderSecondaryMenuEditionUpdateMessage = "{l s='Updating menu...' mod='menupro'}";
	var loaderSortMenuMessage = "{l s='Reorganizing the menu...' mod='menupro'}";
	var loaderInitializePageMessage = "{l s='Loading page' mod='menupro'}";
	var AddItemMessage = "{l s='Adding items...' mod='menupro'}";
	var addSubmenusTitle = "{$addSubmenusTitle|escape:'htmlall':'UTF-8'}";
	var editSubmenuTitle = "{$editSubmenuTitle|escape:'htmlall':'UTF-8'}";
	var deleteSubmenuTitle = "{$deleteSubmenuTitle|escape:'htmlall':'UTF-8'}";
	var confirmDeleteSecondaryMenuMessage = "{l s='Are you sure you want to delete menu' mod='menupro'}";
	var btnSortReorganiseText = "{$btnSortReorganiseText}";
	var btnSortCloseText = "{$btnSortCloseText}";
</script>
<div id="divSecondaryMenuNotify" style="display:none;"></div>
{if $ps_version>='1.6'}<div class="panel"><div class="panel-heading">{else}<fieldset class="sm-page-block clearfix"><legend class="header"><span class="sm-page-title">{/if}
	{$mainMenu.name|escape:'htmlall':'UTF-8'}
	{if $ps_version<'1.6'}</span><span class="sm-page-action menupro toolbarBox">{/if}
	<button type="button" value="1" id="btnMainMenuAddSubMenu" class="{if $ps_version>='1.6'}btn btn-primary{else}mp-button{/if} pull-right btn-main-menu-add-new-submenu">
		<i class="{if $ps_version>='1.6'}icon icon-plus-sign{else}process-icon-new mp-icon{/if}"></i>{l s='Add elements' mod='menupro'}
	</button>
	<button type="button" value="1" id="btnSortMenu" class="{if $ps_version>='1.6'}btn btn-primary{else}mp-button{/if} pull-right btn-sort-menu">
		<i class="{if $ps_version>='1.6'}icon-move{else}process-icon-move mp-icon{/if}"></i>{l s='Reorganize the menu' mod='menupro'}
	</button>
{if $ps_version>='1.6'}</div>{else}</span></legend>{/if}
	<div id="secondaryMenuTree" class="{if $ps_version<'1.6'}clearfix{/if}">
		{include file="{$displayHtmlTemplate|escape:'htmlall':'UTF-8'}" htmlContent=$menuTreeContent}
	</div>
	<div class="{if $ps_version>='1.6'}panel-footer{else}margin-form menupro toolbarBox clearfix{/if}">
		<a href="{$homeLink|escape:'htmlall':'UTF-8'}" class="{if $ps_version>='1.6'}btn btn-default{else}mp-button{/if} pull-right"  title="{l s='Back to list' mod='menupro'}">
			<i class="process-icon-back{if $ps_version<'1.6'} mp-icon{/if}"></i>{l s='Back to list' mod='menupro'}
		</a>
	</div>
{if $ps_version>='1.6'}</div>{else}</fieldset>{/if}
{include file="{$selectItemsTemplates|escape:'htmlall':'UTF-8'}"}
<div id="divSecondaryMenuEditionParent"></div>

{$headerTitle = "{l s='Reorganize ' mod='menupro'} : {$mainMenu.name|escape:'htmlall':'UTF-8'}"}
{if $ps_version>='1.6'}
<div class="modal fade" id="modalSortSecondaryMenu" tabindex="-1" role="dialog" aria-labelledby="modalSortSecondaryMenuTitle" aria-hidden="true">
	<div class="modal-dialog modal-form-edition" role="document">
		<div class="modal-content">
			<div class="modal-header clearfix">
				<h5 class="modal-title pull-left" id="modalSortSecondaryMenuTitle">{$headerTitle|escape:'htmlall':'UTF-8'}</h5>
				<button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
{else}
<div id="dialogModalParent">
	<div id="modalSortSecondaryMenu" title="{$headerTitle|escape:'htmlall':'UTF-8'}">
		<div id="content">
{/if}
				<div id="divSortSecondaryMenuNotify" style="display:none;"></div>
				<div id="modalSortSecondaryMenuContent">
					
				</div>
{if $ps_version>='1.6'}
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">{$btnSortCloseText}</button>
				<button type="button" id="btnConfirmSort" class="btn btn-primary">{$btnSortReorganiseText}</button>
			</div>
		</div>
	</div>
</div>
{else}
		</div>
	</div>
</div>
{/if}
