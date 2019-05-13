{*
* 2007-2018 PrestaShop
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
*  @author     PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2018 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{extends file="helpers/form/form.tpl"}

{block name="input_row"}
  {if $input.type == 'dhl_services'}
    <div
      class="form-group{if isset($input.form_group_class)} {$input.form_group_class|escape:'html':'UTF-8'}{/if}{if $input.type == 'hidden'} hide{/if}"{if isset($tabs) && isset($input.tab)} data-tab-id="{$input.tab|escape:'html':'UTF-8'}"{/if}>
      <div class="col-lg-12">
        {foreach $input.services as $key => $service}
          <h2>{$input[$key]|escape:'html':'UTF-8'}</h2>
          <table class="table col-lg-12 dhl-services">
            {foreach $service as $field}
              <tr>
                <td class="col-lg-3">
                  <img src="{$input.logo|escape:'html':'UTF-8'}" width="150"/>
                </td>
                <td class="col-lg-6">
                  {if isset($field.label)}
                    <span
                      class="col-lg-12 {if isset($field.required) && $field.required && $field.type != 'radio'} required{/if}">
                      {$field.label|escape:'html':'UTF-8'}
                    </span>
                  {/if}
                </td>
                <td class="col-lg-3">
                  <span class="switch prestashop-switch fixed-width-lg">
                    <input type="radio"
                      name="service_{$field.service_id|intval}" id="service_{$field.service_id|intval}_on"
                      value="1" {if $field['active'] == 1} checked="checked"{/if}
                    />
                    {strip}
                      <label for="service_{$field.service_id|intval}_on">
                          {l s='Enabled' mod='dhlexpress'}
                      </label>
                    {/strip}
                    <input type="radio"
                           name="service_{$field.service_id|intval}" id="service_{$field.service_id|intval}_off"
                           value="0" {if $field['active'] == 0} checked="checked"{/if}
                    />
                    {strip}
                      <label for="service_{$field.service_id|intval}_off">
                          {l s='Disabled' mod='dhlexpress'}
                      </label>
                    {/strip}
                    <a class="slide-button btn"></a>
                  </span>
                </td>
              </tr>
            {/foreach}
          </table>
        {/foreach}
      </div>
    </div>
  {elseif $input.type == 'dhl_dimension'}
    <div
      class="form-group{if isset($input.form_group_class)} {$input.form_group_class|escape:'html':'UTF-8'}{/if}{if $input.type == 'hidden'} hide{/if}"{if isset($tabs) && isset($input.tab)} data-tab-id="{$input.tab|escape:'html':'UTF-8'}"{/if}>
      <label class="control-label col-lg-3">&nbsp;</label>
      <div class="col-lg-4">
        {if isset($fields_value[$input.dim_values[0].name]) || $input.readonly == false}
          {foreach $input.dim_values as $dim_value}
            {assign var='value_text' value=$fields_value[$dim_value.name]}
            <label class="control-label col-lg-3">{$dim_value.label|escape:'html':'UTF-8'}</label>
            <div class="input-group fixed-width-xs">
              <input onchange="this.value = parseFloat(this.value.replace(/,/g, '.')) || 0" type="text" {if $input.readonly == true}readonly="readonly" {/if}name="{$dim_value.name|escape:'html':'UTF-8'}" id="{$dim_value.name|escape:'html':'UTF-8'}"
                     value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                     class="fixed-width-xs"/>
              <span class="input-group-addon {$dim_value.suffix_class|escape:'html':'UTF-8'}">{$dim_value.suffix|escape:'html':'UTF-8'}</span>
            </div>
          {/foreach}
        {else}
          <div class="alert alert-info">{$input.no_package|escape:'html':'UTF-8'}</div>
        {/if}
      </div>
    </div>
  {elseif $input.type == 'dhl_display_addr'}
    <div class="form-group">
      <div class="col-lg-4 col-lg-offset-3">
        {if isset($fields_value[$input.obj])}
          <div class="well">
            <div class="row">
              <div class="col-sm-12">
                <a class="btn btn-default pull-right"
                   href="{$input.edit_link|escape:'html':'UTF-8'}&addNewAddress&id_dhl_address={$fields_value[$input.obj]->id|intval}">
                  <i class="icon-pencil"></i>
                  {$input.edit_label|escape:'html':'UTF-8'}
                </a>

                <p style="font-weight: bold; font-size: 18px;">{$fields_value[$input.obj]->iso_country|escape:'html':'UTF-8'}</p>
                <p style="font-weight: bold;">{$fields_value[$input.obj]->company_name|escape:'html':'UTF-8'}</p>
                <p>
                  {$fields_value[$input.obj]->address1|escape:'html':'utf-8'}<br>
                  {if $fields_value[$input.obj]->address2}{$fields_value[$input.obj]->address2|escape:'html':'utf-8'}<br>{/if}
                  {if $fields_value[$input.obj]->address3}{$fields_value[$input.obj]->address3|escape:'html':'utf-8'}<br>{/if}
                  {$fields_value[$input.obj]->zipcode|escape:'html':'utf-8'}<br>
                  {$fields_value[$input.obj]->city|escape:'html':'utf-8'}<br>
                  {if isset($fields_value[$input.obj]->state)}{$fields_value[$input.obj]->state|escape:'html':'utf-8'}<br>{/if}
                  {$fields_value[$input.obj]->country|escape:'html':'utf-8'}<br>
                  {$fields_value[$input.obj]->phone|escape:'html':'utf-8'}<br>
                </p>
              </div>
            </div>
          </div>
        {else}
          <div class="alert alert-info">{$input.no_address|escape:'html':'UTF-8'}</div>
        {/if}
      </div>
    </div>
  {elseif $input.type == 'dhl_credentials'}
    <div class="row">
      {foreach $input.modes as $mode}
        {assign var='value_login' value=$fields_value[$mode.login_name]}
        {assign var='value_passw' value=$fields_value[$mode.password_name]}
        <div class="col-md-6">
          <div class="panel">
            <div class="panel-heading">{$mode.title|escape:'html':'UTF-8'}</div>
            <div class="form-group">
              <label class="control-label col-lg-6">{$mode.login_label|escape:'html':'UTF-8'}</label>
              <div class="col-lg-6">
                <div class="input-group">
                  <span class="input-group-addon">
                    <i class="icon icon-user"></i>
                  </span>
                  <input type="text"
                         name="{$mode.login_name|escape:'html':'UTF-8'}"
                         id="{$mode.login_name|escape:'html':'UTF-8'}"
                         value="{$value_login|escape:'html':'UTF-8'}">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-lg-6">{$mode.password_label|escape:'html':'UTF-8'}</label>
              <div class="col-lg-6">
                <div class="input-group">
                  <span class="input-group-addon">
                    <i class="icon icon-key"></i>
                  </span>
                  <input type="text"
                         name="{$mode.password_name|escape:'html':'UTF-8'}"
                         id="{$mode.password_name|escape:'html':'UTF-8'}"
                         value="{$value_passw|escape:'html':'UTF-8'}"/>
                </div>
              </div>
            </div>
          </div>
        </div>
      {/foreach}
    </div>
  {else}
    {$smarty.block.parent}
  {/if}
{/block}
