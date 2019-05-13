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
{if $ps_version>='1.6'}<div class="panel"><h3>{else}<fieldset><legend>{/if}
	{l s='Properties' mod='menupro'}
	{if $ps_version>='1.6'}</h3>{else}</legend>{/if}
	<div class="">
		{assign var="usableValuesList" value=array()}
		{$usableValuesList[$usableValuesConst.THEME]={l s='Theme' mod='menupro'}}
		{$usableValuesList[$usableValuesConst.DEFAULT]={l s='Default' mod='menupro'}}
		{$usableValuesList[$usableValuesConst.CUSTOMIZED]={l s='Customized' mod='menupro'}}
		{if $menuType!=$menuTypesConst.NONE}
			{$usableValuesList[$usableValuesConst.MENU_PRO_LEVEL]={l s='Menu pro' mod='menupro'}}
		{/if}
		{if ($menuType==$menuTypesConst.SECONDARY) || ($styleType==$styleTypesConst.DEFAULT)}
			{$usableValuesList[$usableValuesConst.NEAREST_RELATIVE]={l s='Nearest relative' mod='menupro'}}
		{/if}
		{if ($menuType!=$menuTypesConst.SECONDARY) || (($styleType==$styleTypesConst.MENU) && ($menuLevel>1))}
			{if ($menuType!=$menuTypesConst.MAIN) || (($styleType!=$styleTypesConst.MENU) && ($menuType==$menuTypesConst.MAIN))}
				{$usableValuesList[$usableValuesConst.HIGHEST_SECONDARY_MENU_LEVEL]={l s='Current highest secondary menu' mod='menupro'}}
			{/if}
		{/if}
		{if ($menuType!=$menuTypesConst.MAIN)}
			{$usableValuesList[$usableValuesConst.MAIN_MENU_LEVEL]={l s='current main menu' mod='menupro'}}
		{/if}
		{$isFirst=true}
		{foreach from=$cssProperties key=propertyIndex item=property}
			{if $ps_version>='1.6'}<div class="panel mp-property-panel"><h3>{else}
			<fieldset class="mp-property-panel{if !$isFirst} not-first{/if}"><legend class="mp-property-title">
			{/if}
				{$property.display_name|escape:'htmlall':'UTF-8'}
			{if $ps_version>='1.6'}</h3>{else}</legend>{/if}
			{$isFirst=false}
				<div class="property-block">
					{if $ps_version>='1.6'}
					<div class="form-group clearfix">
						<label class="control-label col-lg-4 col-md-4 col-sm-4 col-xs-4 mp-label" for="MENUPRO_PROPERTY_USABLE_VALUE_{$propertyIndex|intval}">
						<span class="label-tooltip" data-toggle="tooltip">
					{else}
					<label>
					{/if}
					{l s='Usable value' mod='menupro'}
					{if $ps_version>='1.6'}
					</span>
					</label>
					{else}
					</label>
					<div class="margin-form">
					{/if}
					<select id="MENUPRO_PROPERTY_USABLE_VALUE_{$propertyIndex|intval}" class="col-lg-8 col-md-8 col-sm-8 col-xs-8 usable-values" 
					name="MENUPRO_PROPERTY_USABLE_VALUE_{$propertyIndex|intval}" {if $disableFields}disabled{/if}>
						{foreach from=$usableValuesList key=k item=usableValueName}
							<option value="{$k|intval}" 
							{if (isset($property.usable_value) && ($property.usable_value==$k))||(!isset($property.usable_value) && ($k==$usableValuesConst.THEME))}selected{/if}>
							{$usableValueName|escape:'htmlall':'UTF-8'}
							</option>
						{/foreach}
					</select>
					{if $ps_version>='1.6'}
					</div>
					{else}
					</div>
					<div class="clear"></div>
					{/if}
					
					{if $ps_version>='1.6'}
					<div class="form-group clearfix mp-property-last-form-group">
						<label class="control-label col-lg-4 col-md-4 col-sm-4 col-xs-4 mp-label" for="MENUPRO_PROPERTY_VALUE_{$idStyle|intval}_{$styleType|intval}_{$propertyIndex|intval}">
							<span class="label-tooltip" data-toggle="tooltip">
					{else}
					<label>
					{/if}
					{l s='Value' mod='menupro'}
					{if $ps_version>='1.6'}
							</span>
						</label>
					{else}
					</label>
					<div class="margin-form">
					{/if}
						<div class="{if $ps_version>='1.6'}col-lg-8 col-md-8 col-sm-8 col-xs-8{/if}">
						{if (isset($property.value)) && (isset($property.usable_value)) && ($property.usable_value==$usableValuesConst.CUSTOMIZED)}
								{$valueToUse=$property.value}
						{elseif (isset($property.usable_value)) && ($property.usable_value==$usableValuesConst.THEME)}
							{$valueToUse=''}
						{else}
							{$valueToUse=''}
						{/if}
						{if $property.type==$propertiesTypesConst.COLOR}
							<div class="form-group mp-property-color-form-group">
								<div class="row">
									<div class="input-group div-property-value">
										<input type="text" data-hex="true" class="color mColorPickerInput mColorPicker property-value" 
										name="MENUPRO_PROPERTY_VALUE_{$propertyIndex|intval}" 
										value="{$valueToUse|escape:'htmlall':'UTF-8'}" 
										id="MENUPRO_PROPERTY_VALUE_{$idStyle|intval}_{$styleType|intval}_{$propertyIndex|intval}" data-result-value-type="" 
										{if $disableFields || (!isset($property.usable_value)) || (isset($property.usable_value) && ($property.usable_value!=$usableValuesConst.CUSTOMIZED))}disabled{/if} 
										data-default-value="{$property.default_value|escape:'htmlall':'UTF-8'}"/>
										<span style="cursor:pointer;" id="icp_MENUPRO_PROPERTY_VALUE_{$idStyle|intval}_{$styleType|intval}_{$propertyIndex|intval}" 
										class="mColorPickerTrigger input-group-addon" data-mcolorpicker="true"
										{if (!isset($property.usable_value)) || (isset($property.usable_value) && ($property.usable_value!=$usableValuesConst.CUSTOMIZED))}disabled{/if}>
										<img src="../img/admin/color.png" style="border:0;margin:0 0 0 3px" align="absmiddle"></span>
									</div>
								</div>
							</div>
						{elseif $property.type==$propertiesTypesConst.SELECT_EDITABLE}
							<div class="input-group div-property-value">
								<input id="MENUPRO_PROPERTY_VALUE_{$idStyle|intval}_{$styleType|intval}_{$propertyIndex|intval}" type="text" 
								value="{$valueToUse|escape:'htmlall':'UTF-8'}"  
								class="property-select-value property-value"  name="MENUPRO_PROPERTY_VALUE_{$propertyIndex|intval}" data-result-value-type="" 
								{if (!isset($property.usable_value)) || (isset($property.usable_value) && ($property.usable_value!=$usableValuesConst.CUSTOMIZED))}disabled{/if} 
								data-default-value="{$property.default_value|escape:'htmlall':'UTF-8'}"/>
								
								{if $ps_version>='1.6'}
								<span class="input-group-addon dropdown-toggle dropdown-toggle-split caret" data-toggle="dropdown" aria-haspopup="true" 
								aria-expanded="false" {if (!isset($property.usable_value)) || (isset($property.usable_value) && ($property.usable_value!=$usableValuesConst.CUSTOMIZED))}disabled{/if} >
									<i class="icon-caret-down"></i>
								</span>
								<ul class="dropdown-menu mp-dropdown-menu">
									{foreach from=$property.selectable_values key=k item=selectableValue}
									<li>
										<a class="dropdown-item" data-name="{$selectableValue.value|escape:'htmlall':'UTF-8'}" 
										href="#"  data-display-name="{$selectableValue.display_name|escape:'htmlall':'UTF-8'}" >
											{$selectableValue.display_name|escape:'htmlall':'UTF-8'}
										</a>
									</li>
									{/foreach}
								</ul>
								{else}
								<button type="button" class="dropdown dropbtn" {if (!isset($property.usable_value)) || (isset($property.usable_value) && ($property.usable_value!=$usableValuesConst.CUSTOMIZED))}disabled{/if}>
								</button>
								<span class="dropdown-content">
								{foreach from=$property.selectable_values key=k item=selectableValue}
								<a class="dropdown-item" data-name="{$selectableValue.value|escape:'htmlall':'UTF-8'}" 
								href="#"  data-display-name="{$selectableValue.display_name|escape:'htmlall':'UTF-8'}" >
									{$selectableValue.display_name|escape:'htmlall':'UTF-8'}
								</a>
								{/foreach}
								</span>
								{/if}
							</div>
						{elseif $property.type==$propertiesTypesConst.SELECT}
							<div class="div-property-value">
								<select id="MENUPRO_PROPERTY_VALUE_{$idStyle|intval}_{$styleType|intval}_{$propertyIndex|intval}" type="text" 
								value="{$valueToUse|escape:'htmlall':'UTF-8'}" 
								class="property-value"  name="MENUPRO_PROPERTY_VALUE_{$propertyIndex|intval}" data-result-value-type="" 
								{if (!isset($property.usable_value)) || (isset($property.usable_value) && ($property.usable_value!=$usableValuesConst.CUSTOMIZED))}disabled{/if} 
								data-default-value="{$property.default_value|escape:'htmlall':'UTF-8'}">
									{foreach from=$property.selectable_values key=k item=selectableValue}
										<option value="{$selectableValue.value|escape:'htmlall':'UTF-8'}" {if $valueToUse==$selectableValue.value}selected{/if}>{$selectableValue.display_name|escape:'htmlall':'UTF-8'}</option>
									{/foreach}
								</select>
							</div>
						{else}
							<div class="div-property-value">
								<input id="MENUPRO_PROPERTY_VALUE_{$idStyle|intval}_{$styleType|intval}_{$propertyIndex|intval}" type="text" 
								value="{$valueToUse|escape:'htmlall':'UTF-8'}"  
								class="property-value"  name="MENUPRO_PROPERTY_VALUE_{$propertyIndex|intval}" data-result-value-type="" 
								{if (!isset($property.usable_value)) || (isset($property.usable_value) && ($property.usable_value!=$usableValuesConst.CUSTOMIZED))}disabled{/if} 
								data-default-value="{$property.default_value|escape:'htmlall':'UTF-8'}"/>
							</div>
						{/if}
						</div>
					</div>
					{if $ps_version<'1.6'}<div class="clear"></div>{/if}
					<input type="hidden" id="MENUPRO_PROPERTY_ID_{$propertyIndex|intval}" 
					name="MENUPRO_PROPERTY_ID_{$propertyIndex|intval}" 
					value="{$property.id_menupro_css_property|intval}" class="property-id"/>
					<input type="hidden" id="MENUPRO_PROPERTY_DISPLAY_NAME_{$propertyIndex|intval}" 
					name="MENUPRO_PROPERTY_DISPLAY_NAME_{$propertyIndex|intval}" 
					value="{$property.display_name|escape:'htmlall':'UTF-8'}"  class="property-display-name"/>
					<input type="hidden" id="MENUPRO_PROPERTY_DEFAULT_VALUE_{$propertyIndex|intval}" 
					name="MENUPRO_PROPERTY_DEFAULT_VALUE_{$propertyIndex|intval}" 
					value="{$property.default_value|escape:'htmlall':'UTF-8'}" class="property-default-value"/>
					<input type="hidden" id="MENUPRO_PROPERTY_TYPE_{$propertyIndex|intval}" 
					name="MENUPRO_PROPERTY_TYPE_{$propertyIndex|intval}" 
					value="{$property.type|intval}" class="property-type"/>
					<input type="hidden" id="MENUPRO_PROPERTY_ID_BASE_{$propertyIndex|intval}" 
					name="MENUPRO_PROPERTY_ID_BASE_{$propertyIndex|intval}" 
					value="{$property.id_property_base|intval}" class="property-id-base"/>
				</div>
			{if $ps_version>='1.6'}</div>{else}</fieldset>{/if}
		{/foreach}
	</div>
	<input type="hidden" id="MENUPRO_PROPERTIES_COUNT" name="MENUPRO_PROPERTIES_COUNT" value="{count($cssProperties)|intval}" />
{if $ps_version>='1.6'}</div>{else}</fieldset>{/if}