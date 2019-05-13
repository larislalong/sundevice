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
	var defaultNumberMenuPerLineLabel = "{l s='Default' mod='menupro'}";
	var numberMenuPerLineValueWhenDefault = "{l s='Depending on the width of the line' mod='menupro'}";
	var defaultNumberMenuPerLineValue = "{$defaultNumberMenuPerLine|intval}";
	var hookPreferences = {$hookPreferences|json_encode};
	var imageMenuTypeFolder = "{$imageMenuTypeFolder|escape:'htmlall':'UTF-8'}";
</script>
<fieldset>
    <h2>{if isset($id_menupro_main_menu)}{l s='Edit Main menu' mod='menupro'}{else}{l s='Add new main menu' mod='menupro'}{/if}</h2>
	<div class="row">
		<div id="tabs">
			<div class="productTabs col-lg-2 col-md-3">
				{if $ps_version>='1.6'}<div class="tab list-group">{else}<ul class="tab">{/if}
					{if $ps_version<'1.6'}<li class="tab-row">{/if}
					<a id="nav-information" class="list-group-item nav-optiongroup{if $currentNav=='nav-information'} active{/if}" href="#" 
					title="{l s='Information' mod='menupro'}">
						{l s='Information' mod='menupro'}
					</a>
					{if $ps_version<'1.6'}</li><li class="tab-row">{/if}
					<a id="nav-own-style" class="list-group-item nav-optiongroup{if $currentNav=='nav-own-style'} active{/if}" href="#" 
					title="{l s='Style Sheet' mod='menupro'}">
						{l s='Style Sheet' mod='menupro'}
					</a>
					{if $ps_version<'1.6'}</li><li class="tab-row">{/if}
					<a id="nav-default-styles" class="list-group-item nav-optiongroup {if $currentNav=='nav-default-styles'} active{/if}" href="#" 
					title="{l s='Styles Sheets for submenus' mod='menupro'}">
						{l s='Styles Sheets for submenus' mod='menupro'}
					</a>
					{if $ps_version<'1.6'}</li>{/if}
				{if $ps_version>='1.6'}</div>{else}</ul>{/if}
			</div>
		</div>
		<div class="form-horizontal col-lg-10 col-md-9">
			<div>
				<form id="formMainMenu" method="post" enctype="multipart/form-data" novalidate="" action="{$formAction|escape:'htmlall':'UTF-8'}">
					<input type="hidden" id="id_menupro_main_menu" name="id_menupro_main_menu" 
					value="{if isset($id_menupro_main_menu)}{$id_menupro_main_menu|intval}{else}0{/if}">
					
					<input type="hidden" id="txtCurrentNav" name="current_nav" value="{$currentNav|escape:'htmlall':'UTF-8'}">
					<input type="hidden" id="submitSaveAndStay" name="submitSaveAndStay" value="0">
					<div class="nav-information tab-optiongroup" {if $currentNav!='nav-information'}style="display: none"{/if}>
						{include file="{$displayHtmlTemplate|escape:'htmlall':'UTF-8'}" htmlContent=$mainMenuInformationFormContent}
						{if $ps_version<'1.6'}
						<div class="margin-form menupro toolbarBox clearfix">
							<a href="{$homeLink|escape:'htmlall':'UTF-8'}" class="pull-left mp-button" title="{l s='Back to list' mod='menupro'}">
								<i class="process-icon-back mp-icon"></i>{l s='Back to list' mod='menupro'}
							</a>
							<button type="submit" name="" class="pull-right mp-button">
								<i class="process-icon-save mp-icon"></i>{l s='Save' mod='menupro'}
							</button>
							<button type="submit" class="btnSaveAndStayMenu pull-right mp-button" name="staymainmemu">
								<i class="process-icon-save-and-stay mp-icon"></i>{l s='Save and stay' mod='menupro'}
							</button>
						</div>
						<input type="hidden" name="submitMainMenuSave" value="1">
						{/if}
					</div>
					<div class="nav-own-style tab-optiongroup" {if $currentNav!='nav-own-style'}style="display: none"{/if}>
						{if isset($id_menupro_main_menu)}
							{include file="{$displayHtmlTemplate|escape:'htmlall':'UTF-8'}" htmlContent=$mainMenuStyleFormContent}
						{else}
							{if $ps_version>='1.6'}
							<div class="alert alert-warning">
								<button type="button" class="close" data-dismiss="alert">×</button>
								<ul style="display:block;" id="seeMore">
									<li>
							{else}<div class="warn">{/if}			
										{l s='You must register this main menu before managing its style.' mod='menupro'}
							{if $ps_version>='1.6'}			
									</li>
								</ul>
							</div>
							{else}</div>{/if}
						{/if}
					</div>
				</form>
				<div class="nav-default-styles tab-optiongroup" style="display: none">
					{if isset($id_menupro_main_menu)}
						{include file="{$displayHtmlTemplate|escape:'htmlall':'UTF-8'}" htmlContent=$stylesLevelFormContent}
					{else}
						{if $ps_version>='1.6'}
						<div class="alert alert-warning">
							<button type="button" class="close" data-dismiss="alert">×</button>
							<ul style="display:block;" id="seeMore">
								<li>
						{else}<div class="warn">{/if}
									{l s='You must register this main menu before managing its styles level.' mod='menupro'}
						{if $ps_version>='1.6'}			
									</li>
							</ul>
						</div>
						{else}</div>{/if}
					{/if}
				</div>
			</div>
		</div>
	</div>
</fieldset>