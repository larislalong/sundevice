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
	var usableStylesListConst = {$usableStylesListConst|json_encode};
</script>
<input type="hidden" id="MENUPRO_STYLE_MENU_TYPE" value="{$menuType|intval}">
{assign var="usableStylesList" value=array()}
{$usableStylesList[$usableStylesListConst.DEFAULT]={l s='Default' mod='menupro'}}
{$usableStylesList[$usableStylesListConst.THEME]={l s='Theme' mod='menupro'}}
{$usableStylesList[$usableStylesListConst.CUSTOMIZED]={l s='Customized' mod='menupro'}}
{$usableStylesList[$usableStylesListConst.MENU_PRO_LEVEL]={l s='Menu pro' mod='menupro'}}
{if $menuType==$menuTypesConst.SECONDARY}
	{$usableStylesList[$usableStylesListConst.NEAREST_RELATIVE]={l s='Nearest relative' mod='menupro'}}
	{$usableStylesList[$usableStylesListConst.MAIN_MENU_LEVEL]={l s='Current main menu' mod='menupro'}}
	{if $secondaryMenuLevel>1}
		{$usableStylesList[$usableStylesListConst.HIGHEST_SECONDARY_MENU_LEVEL]={l s='Current highest secondary menu' mod='menupro'}}
	{/if}
{/if}
<input type="hidden" id="MENUPRO_STYLE_PROPERTIES_LOADED" name="MENUPRO_STYLE_PROPERTIES_LOADED" value="{isset($propertiesFormContent)|intval}">
{if $ps_version>='1.6'}<div class="panel"><h3>{else}<fieldset><legend>{/if}
	{l s='Style Sheet' mod='menupro'}
{if $ps_version>='1.6'}</h3>{else}</legend>{/if}
	<div id="divStyleGetStyleNotify" style="display:none;"></div>
	<div id="divStyleEdition" style="display:none;">
		{if $ps_version>='1.6'}
		<div class="form-group clearfix">
			<label class="control-label col-lg-4 col-md-4 col-sm-4 col-xs-4 mp-label" for="MENUPRO_STYLE_NAME">
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
			<input type="text" id="MENUPRO_STYLE_NAME" name="MENUPRO_STYLE_NAME" value="{if isset($MENUPRO_STYLE_NAME)}{$MENUPRO_STYLE_NAME|escape:'htmlall':'UTF-8'}{/if}">
		{if $ps_version>='1.6'}
			</div>
		</div>
		{else}
		</div>
		<div class="clear"></div>
		{/if}
		
		{if $ps_version>='1.6'}
		<div class="form-group clearfix">
			<label class="control-label col-lg-4 col-md-4 col-sm-4 col-xs-4 mp-label" for="MENUPRO_USABLE_STYLE">
		{else}
		<label>
		{/if}
		{l s='Usable style' mod='menupro'}
		{if $ps_version>='1.6'}
		</label>
		{else}
		</label>
		<div class="div-usable-style margin-form">
		{/if}
			<select id="MENUPRO_USABLE_STYLE" class="col-lg-5 col-md-5 col-sm-5 col-xs-5" name="MENUPRO_USABLE_STYLE">
				{foreach from=$usableStylesList key=k item=usableStyleName}
					<option value="{$k|intval}" {if isset($MENUPRO_USABLE_STYLE) && ($MENUPRO_USABLE_STYLE==$k)}selected{/if}>{$usableStyleName|escape:'htmlall':'UTF-8'}</option>
				{/foreach}
			</select>
		{if $ps_version>='1.6'}
		</div>
		{else}
		</div>
		<div class="clear"></div>
		{/if}
		
		{if $ps_version>='1.6'}
		<div id="divUsableStyleName" class="form-group clearfix" style="display:none;">
			<label class="control-label col-lg-4 col-md-4 col-sm-4 col-xs-4 mp-label" for="txtUsableStyleName">
		{else}
		<div id="divUsableStyleName" style="display:none;">
		<label>
		{/if}
		{l s='Usable style name' mod='menupro'}
		{if $ps_version>='1.6'}
		</label>
		<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
		{else}
		</label>
		<div class="margin-form">
		{/if}
			<input type="text" id="txtUsableStyleName" value="" disabled>
		{if $ps_version>='1.6'}
			</div>
		</div>
		{else}
		</div>
		</div>
		<div class="clear"></div>
		{/if}
		
		<input type="hidden" id="MENUPRO_STYLE_ID" name="MENUPRO_STYLE_ID" value="{$MENUPRO_STYLE_ID|intval}">
		<input type="hidden" id="MENUPRO_STYLE_MENU_LEVEL" name="MENUPRO_STYLE_MENU_LEVEL" value="{$MENUPRO_STYLE_MENU_LEVEL|intval}">
		<div class="divProperties">
			{if isset($propertiesFormContent)}{include file="{$displayHtmlTemplate|escape:'htmlall':'UTF-8'}" htmlContent=$propertiesFormContent}{/if}
		</div>
		<div class="{if $ps_version>='1.6'}panel-footer{else}margin-form menupro toolbarBox{/if}">
			<button type="submit" value="1" id="btnSaveStyleMenu" name="submitSaveStyleMenu" class="{if $ps_version>='1.6'}btn btn-default{else}mp-button{/if} pull-right btnSave">
				<i class="process-icon-save{if $ps_version<'1.6'} mp-icon{/if}"></i>{l s='Save' mod='menupro'}
			</button>
			<a href="{$homeLink|escape:'htmlall':'UTF-8'}" class="{if $ps_version>='1.6'}btn btn-default{else}mp-button{/if} pull-left btnCancel">
				<i class="process-icon-back{if $ps_version<'1.6'} mp-icon{/if}"></i>{if $menuType==$menuTypesConst.SECONDARY}{l s='Quit' mod='menupro'}{else}{l s='Back to list' mod='menupro'}{/if}
			</a>
			<button type="submit" id="btnSaveAndStayStyleMenu" class="{if $ps_version>='1.6'}btn btn-default{else}mp-button{/if} pull-right btnSaveAndStayMenu btnSaveAndStay" name="staymainmemu">
				<i class="{if $ps_version>='1.6'}process-icon-save{else}process-icon-save-and-stay mp-icon{/if}"></i>{l s='Save and stay' mod='menupro'}
			</button>
		</div>
	</div>
{if $ps_version>='1.6'}</div>{else}</fieldset>{/if}