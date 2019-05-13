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
<input type="hidden" id="MENUPRO_STYLES_LEVEL_MENU_TYPE" value="{$menuType|intval}">
<input type="hidden" id="MENUPRO_STYLES_LEVEL_MENU_ID" value="{if isset($idMenu)}{$idMenu|intval}{else}0{/if}">
<script type="text/javascript">
	var loaderSaveStyleLevelMessage = "{l s='Saving style level...' mod='menupro'}";
	var loaderDeleteStyleLevelMessage = "{l s='deleting style level...' mod='menupro'}";
	var loaderDuplicateStyleLevelMessage = "{l s='duplicating style level...' mod='menupro'}";
	var confirmDeleteStyleLevelMessage = "{l s='Are you sure you want to delete style' mod='menupro'}";
	var updateButtonText = "{l s='Edit' mod='menupro'}";
	var deleteButtonText = "{l s='Delete' mod='menupro'}";
	var duplicateButtonText = "{l s='Duplicate' mod='menupro'}";
</script>
{assign var="headerTitle" value=""}
{if $menuType==$menuTypesConst.NONE}
	{$headerTitle={l s='Different Style Sheets' mod='menupro'}}
{else if $menuType==$menuTypesConst.MAIN}
	{$headerTitle={l s='Styles Sheets for submenus' mod='menupro'}}
	{$backTitle={l s='Back to list' mod='menupro'}}
{else}
	{$headerTitle={l s='Styles Sheets for submenus' mod='menupro'}}
	{$homeLink='#'}
	{$backTitle={l s='Quit' mod='menupro'}}
{/if}
{if $ps_version>='1.6'}<div class="panel"><h3>{else}<fieldset><legend>{/if}
	{$headerTitle|escape:'htmlall':'UTF-8'}
{if $ps_version>='1.6'}</h3>{else}</legend>{/if}
	<div id="divStyleLevelNotify" style="display:none;">
	</div>
	<div class="row">
		<div id="divStyleLevelList" class="col-lg-5 col-md-5">
			<div id="divStyleLevelListDisabler" style="display:none;" class="div-disabler"></div>
			{if $ps_version<'1.6'}<div id="form-menupro_default_style">{/if}
			{include file="{$displayHtmlTemplate|escape:'htmlall':'UTF-8'}" htmlContent=$styleLevelList}
			{if $ps_version<'1.6'}</div>{/if}
		</div>
		<div id="divStyleLevelEdition" class="{if $ps_version>='1.6'}col-lg-7 col-md-7{else}col-lg-6 col-md-6{/if}" style="display:none;">
			{if $ps_version>='1.6'}<div class="panel"><h3>{else}<fieldset><legend>{/if}
				{l s='Style edition' mod='menupro'}
				{if $ps_version>='1.6'}</h3>{else}</legend>{/if}
				<div id="divStyleLevelEditionContent">
					<div class="form-group clearfix">
						{if $ps_version>='1.6'}
						<div class="form-group clearfix">
							<label class="control-label col-lg-4 col-md-4 col-sm-4 col-xs-4 mp-label" for="MENUPRO_STYLES_LEVEL_NAME">
						{else}
						<label>
						{/if}
								{l s='Name' mod='menupro'}
						{if $ps_version>='1.6'}
						</label>
						<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
						{else}
						</label>
						<div class="margin-form">
						{/if}
								<input type="text" id="MENUPRO_STYLES_LEVEL_NAME" value="" class="">
							
						{if $ps_version>='1.6'}
							</div>
						</div>
						{else}
						</div>
						<div class="clear"></div>
						{/if}
						
						{if $ps_version>='1.6'}
						<div class="form-group clearfix">
							<label class="control-label col-lg-4 col-md-4 col-sm-4 col-xs-4 mp-label" for="MENUPRO_STYLES_LEVEL_MENU_LEVEL">
						{else}
						<label>
						{/if}
								{l s='Menu level' mod='menupro'}
						{if $ps_version>='1.6'}
						</label>
						<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 input-group">
						{else}
						</label>
						<div class="margin-form">
						{/if}
								<input type="text" id="MENUPRO_STYLES_LEVEL_MENU_LEVEL" value="" class="">
							
						{if $ps_version>='1.6'}
								<span class="input-group-addon dropdown-toggle dropdown-toggle-split caret" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i class="icon-caret-down"></i>
								</span>
								<ul class="dropdown-menu mp-dropdown-menu">
									{for $i=0 to 5}
										<li>
											<a class="dropdown-item level-dropdown-item" data-value="{$i|intval}" href="#">
												{$i|intval}
											</a>
										</li>
									{/for}
								</ul>
							</div>
						</div>
						{else}
							<span class="dropdown">
								<button type="button" class="dropbtn"></button>
								<span class="dropdown-content">
								{for $i=0 to 5}
									<a class="dropdown-item level-dropdown-item" data-value="{$i|intval}" href="#">
										{$i|intval}
									</a>
								{/for}
								</span>
							</span>
						</div>
						<div class="clear"></div>
						{/if}
						<input type="hidden" id="MENUPRO_STYLES_LEVEL_ID" value="" class="">
						<div class="divProperties">
						</div>
					</div>
					<div class="{if $ps_version>='1.6'}panel-footer{else}margin-form menupro toolbarBox{/if}">
						<button type="button" value="1" id="btnStyleLevelSave" name="" class="{if $ps_version>='1.6'}btn btn-default{else}mp-button{/if} pull-right">
							<i class="process-icon-save{if $ps_version<'1.6'} mp-icon{/if}"></i>{l s='Save' mod='menupro'}
						</button>
						<button id="btnStyleLevelCancel" type="button" class="{if $ps_version>='1.6'}btn btn-default{else}mp-button{/if} pull-left" title="{l s='Cancel' mod='menupro'}">
							<i class="{if $ps_version>='1.6'}process-icon-cancel{else}process-icon-cancel mp-icon{/if}"></i>{l s='Cancel' mod='menupro'}
						</button>
					</div>
				</div>
			{if $ps_version>='1.6'}</div>{else}</fieldset>{/if}
		</div>
	</div>
	{if $menuType!=$menuTypesConst.NONE}
		{if $ps_version<'1.6'}
		<div class="clear"></div>
		{/if}
		<div class="{if $ps_version>='1.6'}panel-footer{else}margin-form menupro toolbarBox clearfix{/if}">
			<a href="{$homeLink|escape:'htmlall':'UTF-8'}" class="{if $ps_version>='1.6'}btn btn-default{else}mp-button{/if} pull-right btnCancel" title="{$backTitle|escape:'htmlall':'UTF-8'}">
				<i class="process-icon-back{if $ps_version<'1.6'} mp-icon{/if}"></i>{$backTitle|escape:'htmlall':'UTF-8'}
			</a>
		</div>
	{/if}
{if $ps_version>='1.6'}</div>{else}</fieldset>{/if}