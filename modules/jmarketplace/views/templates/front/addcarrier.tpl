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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{if $show_menu_top == 1}
    {hook h='displayMarketplaceHeader'}
{/if}

{capture name=path}
    <a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}">
        {l s='Your account' mod='jmarketplace'}
    </a>
    <span class="navigation-pipe">
        {$navigationPipe|escape:'html':'UTF-8'}
    </span>
    <a href="{$link->getModuleLink('jmarketplace', 'selleraccount', array(), true)|escape:'html':'UTF-8'}">
        {l s='Your seller account' mod='jmarketplace'}
    </a>
    <span class="navigation-pipe">
        {$navigationPipe|escape:'html':'UTF-8'}
    </span>
    <a href="{$link->getModuleLink('jmarketplace', 'carriers', array(), true)|escape:'html':'UTF-8'}">
        {l s='Your carriers' mod='jmarketplace'}
    </a>
    <span class="navigation-pipe">
        {$navigationPipe|escape:'html':'UTF-8'}
    </span>
    <span class="navigation_page">
        {l s='Add new carrier' mod='jmarketplace'}
    </span>
{/capture}

<div class="row">
    <div class="column col-xs-12 col-sm-12 col-lg-3"{if $show_menu_options == 0} style="display:none;"{/if}>
        {hook h='displayMarketplaceWidget'}
    </div>
    
    <div class="col-sm-12{if $show_menu_options == 1} col-lg-9{else} col-lg-12{/if}">
        {if isset($errors) && $errors}
            {if isset($errors) && $errors}
                {include file="./errors.tpl"}
            {/if}
        {/if}

        <div id="carrier_wizard" class="box">
            <h1 class="page-subheading">
                {l s='Add new carrier' mod='jmarketplace'}
            </h1>
            <form action="{$link->getModuleLink('jmarketplace', 'addcarrier', array(), true)|escape:'html':'UTF-8'}" method="post" class="std" enctype="multipart/form-data">   
                <div class="form-group">
                    <label for="carrier_name" class="required">
                        {l s='Carrier name' mod='jmarketplace'}
                    </label>
                    <input class="form-control" type="text" name="carrier_name" value="{if isset($carrier_name)}{$carrier_name|escape:'html':'UTF-8'}{else}{l s='My carrier' mod='jmarketplace'}{/if}" required />
                    <p class="help-block">
                        {l s='Allowed characters: letters, spaces and ().-. The carrier name will be displayed during checkout. For in-store pickup, enter 0 to replace the carrier name with your shop name.' mod='jmarketplace'}
                    </p>
                </div>

                <div class="row">
                    <div class="required form-group col-sm-10 col-xs-9">
                        <label for="delay" class="required">
                            {l s='Transit time' mod='jmarketplace'}
                        </label>
                        {foreach from=$languages item=language}
                            <input{if $id_lang != $language.id_lang} style="display:none;"{/if} class="is_required validate form-control delay" data-validate="isName" type="text" id="delay_{$language.id_lang|intval}" name="delay_{$language.id_lang|intval}" value="{if isset($delay[$language.id_lang|intval])}{$delay[$language.id_lang|intval]}{else}{l s='72h' mod='jmarketplace'}{/if}" required />
                        {/foreach} 
                        <p class="help-block">
                            {l s='The estimated delivery time will be displayed during checkout.' mod='jmarketplace'}
                        </p>
                    </div>

                    <div class="col-sm-2 col-xs-3">
                        <label for="delay_lang">
                            {l s='Language' mod='jmarketplace'}
                        </label>
                        <select name="id_lang" class="form-control delay_lang">
                            {foreach from=$languages item=language}
                                <option value="{$language.id_lang|intval}"{if $id_lang == $language.id_lang} selected="selected"{/if}>
                                    {$language.iso_code|escape:'html':'UTF-8'}
                                </option>
                            {/foreach}
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="grade">
                        {l s='Speed grade' mod='jmarketplace'}
                    </label>
                    <input class="form-control" type="text" name="grade" value="{if isset($grade)}{$grade|intval}{else}0{/if}" />
                    <p class="help-block">
                        {l s='Enter 0 for a longest shipping delay, or 9 for the shortest shipping delay.' mod='jmarketplace'}
                    </p>
                </div>        

                <div class="form-group">
                    <label for="fileUpload">
                        {l s='Logo' mod='jmarketplace'}
                    </label>
                    <input type="file" name="logo" id="fileUpload" class="form-control" />
                    <p class="help-block">
                        {l s='Format JPG, GIF, PNG. Filesize 2.00 MB max. Current size undefined.' mod='jmarketplace'}
                    </p>
                </div>

                <div class="form-group">
                    <label for="url">
                        {l s='Tracking URL' mod='jmarketplace'}
                    </label>
                    <input class="form-control" type="text" name="url"{if isset($url)} value="{$url|escape:'html':'UTF-8'}"{/if} />
                    <p class="help-block">
                        {l s='For example: http://exampl.com/track.php?num=@ with @ where the tracking number should appear.' mod='jmarketplace'}
                    </p>
                </div>

                <div class="form-group" style="display:none;">
                    <label class="control-label col-lg-3">
                        {l s='Add handling costs' mod='jmarketplace'}
                    </label>
                    <div class="col-lg-9 ">
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" value="1" id="shipping_handling_on" name="shipping_handling">
                            <label for="shipping_handling_on">{l s='Yes' mod='jmarketplace'}</label>
                            <input type="radio" checked="checked" value="0" id="shipping_handling_off" name="shipping_handling">
                            <label for="shipping_handling_off">{l s='No' mod='jmarketplace'}</label>
                            <a class="slide-button btn"></a>
                        </span>
                        <p class="help-block">{l s='Include the handling costs in the final carrier price.' mod='jmarketplace'}</p>
                    </div>               
                </div>

                <div class="row">        
                    <div class="form-group col-lg-4">
                        <label class="control-label">
                            {l s='Free shipping' mod='jmarketplace'}
                        </label>
                        <select id="is_free" name="is_free" class="form-control">
                            <option value="0" selected="selected">
                                {l s='No' mod='jmarketplace'}
                            </option>
                            <option value="1">
                                {l s='Yes' mod='jmarketplace'}
                            </option>
                        </select>
                    </div>

                    <div class="form-group col-lg-4">
                        <label class="control-label">
                            {l s='Billing' mod='jmarketplace'}
                        </label>
                        <select id="shipping_method" name="shipping_method" class="form-control">
                            <option value="1" selected="selected">
                                {l s='According to total weight.' mod='jmarketplace'}
                            </option>
                            <option value="2">
                                {l s='According to total price.' mod='jmarketplace'}
                            </option>
                        </select>
                    </div>

                    <div class="form-group col-lg-4">
                        <label class="control-label">
                            {l s='Tax' mod='jmarketplace'}
                        </label>
                        <select id="id_tax_rules_group" name="id_tax_rules_group" class="form-control">
                            <option value="0">
                                {l s='no tax' mod='jmarketplace'}
                            </option>
                            {foreach from=$taxes item=tax}
                                <option value="{$tax.id_tax|intval}"{if isset($id_tax) && $id_tax == $tax.id_tax} selected="selected"{/if}>
                                    {$tax.name|escape:'html':'UTF-8'}
                                </option>
                            {/foreach}
                        </select>
                    </div>
                </div>

                <div id="zone_ranges" class="box">
                    <h4>{l s='Ranges' mod='jmarketplace'}</h4>
                    <div class="table-responsive">
                        <table style="max-width:100%" class="table" id="zones_table">
                            <tbody>
                                <tr class="range_inf">
                                    <td class="range_type">
                                        {l s='Will be applied when the weight is' mod='jmarketplace'}
                                    </td>
                                    <td class="border_left border_bottom range_sign">
                                        &gt;=
                                    </td>
                                    <td class="border_bottom">
                                        <div class="input-group fixed-width-md">
                                            <span class="input-group-addon weight_unit">
                                                kg
                                            </span>
                                            <span class="input-group-addon price_unit" style="display: none;">
                                                {$currency_sign|escape:'html':'UTF-8'}
                                            </span>
                                            <input type="text" value="0.000000" name="range_inf[]" class="form-control">
                                        </div>
                                    </td>
                                </tr>
                                <tr class="range_sup">
                                    <td class="range_type">
                                        {l s='Will be applied when the weight is' mod='jmarketplace'}
                                    </td>
                                    <td class="border_left range_sign">
                                        &lt;
                                    </td>
                                    <td class="range_data">
                                        <div class="input-group fixed-width-md">
                                            <span class="input-group-addon weight_unit">
                                                kg
                                            </span>
                                            <span class="input-group-addon price_unit" style="display: none;">
                                                {$currency_sign|escape:'html':'UTF-8'}
                                            </span>
                                            <input type="text" autocomplete="off" value="" name="range_sup[]" class="form-control">
                                        </div>
                                    </td>
                                </tr>
                                <tr class="fees_all" style="display:none;">
                                    <td class="border_top border_bottom border_bold">
                                        <span class="fees_all">
                                            All
                                        </span>
                                    </td>
                                    <td>
                                        <input type="checkbox" class="form-control" onclick="checkAllZones(this);">
                                    </td>
                                    <td class="border_top border_bottom">
                                        <div class="input-group fixed-width-md">
                                            <span style="display:none" class="input-group-addon currency_sign">
                                                {$currency_sign|escape:'html':'UTF-8'}
                                            </span>
                                            <input type="text" autocomplete="off" style="display:none" disabled="disabled" class="form-control">
                                        </div>
                                    </td>
                                </tr>
                                {foreach from=$zones item=zone}
                                    <tr data-zoneid="{$zone.id_zone|intval}" class="fees">
                                        <td>
                                            <label for="zone_{$zone.id_zone|intval}">
                                                {$zone.name|escape:'html':'UTF-8'}
                                            </label>
                                        </td>
                                        <td class="zone">
                                            <input type="checkbox" value="{$zone.id_zone|intval}" name="zone_{$zone.id_zone|intval}" id="zone_{$zone.id_zone|intval}" class="input_zone" checked="checked">
                                        </td>
                                        <td>
                                            <div class="input-group fixed-width-md">
                                                <span class="input-group-addon">
                                                    {$currency_sign|escape:'html':'UTF-8'}
                                                </span>
                                                <input type="text" value="" name="fees[{$zone.id_zone|intval}][0]" class="form-control">
                                            </div>
                                        </td>
                                    </tr>
                                {/foreach}
                                <tr class="delete_range">
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="new_range pull-right">
                        <a id="add_new_range" class="btn btn-default" href="#">
                            {l s='Add new range' mod='jmarketplace'}
                        </a>
                    </div>  
                </div>

                <div class="form-group">
                    <label for="max_width">
                        {l s='Maximum package width (cm)' mod='jmarketplace'}
                    </label>
                    <input class="form-control" type="text" name="max_width" value="{if isset($max_width)}{$max_width|intval}{else}0{/if}" />
                    <p class="help-block">
                        {l s='Maximum width managed by this carrier. Set the value to "0", or leave this field blank to ignore. The value must be an integer.' mod='jmarketplace'}
                    </p>
                </div>

                <div class="form-group">
                    <label for="max_height">
                        {l s='Maximum package height (cm)' mod='jmarketplace'}
                    </label>
                    <input class="form-control" type="text" name="max_height" value="{if isset($max_height)}{$max_height|intval}{else}0{/if}" />
                    <p class="help-block">
                        {l s='Maximum height managed by this carrier. Set the value to "0", or leave this field blank to ignore. The value must be an integer.' mod='jmarketplace'}
                    </p>
                </div>  

                <div class="form-group">
                    <label for="max_depth">
                        {l s='Maximum package depth (cm)' mod='jmarketplace'}
                    </label>
                    <input class="form-control" type="text" name="max_depth" value="{if isset($max_depth)}{$max_depth|intval}{else}0{/if}" />
                    <p class="help-block">
                        {l s='Maximum depth managed by this carrier. Set the value to "0", or leave this field blank to ignore. The value must be an integer.' mod='jmarketplace'}
                    </p>
                </div>

                <div class="form-group">
                    <label for="max_weight">
                        {l s='Maximum package weight (kg)' mod='jmarketplace'}
                    </label>
                    <input class="form-control" type="text" name="max_weight" value="{if isset($max_weight)}{$max_weight|floatval}{else}0{/if}" />
                    <p class="help-block">
                        {l s='Maximum weight managed by this carrier. Set the value to "0", or leave this field blank to ignore.' mod='jmarketplace'}
                    </p>
                </div>
                
                <div class="form-group">
                    <p class="checkbox">
                        <input type="checkbox" value="1" id="associate_products" name="associate_products">
                        <label for="associate_products">
                            {l s='Associate this carrier with all my products' mod='jmarketplace'}
                        </label>
                    </p>
                </div>

                <div class="form-group" style="display:none;">
                    <label class="control-label col-lg-3">
                        {l s='Group access' mod='jmarketplace'}
                    </label>
                    <p class="help-block">
                        {l s='Mark the groups that are allowed access to this carrier.' mod='jmarketplace'}
                    </p>
                    <div class="col-lg-9">																								
                        <div class="row">
                            <div class="col-lg-6">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="fixed-width-xs">
                                                <span class="title_box">
                                                    <input type="checkbox" onclick="checkDelBoxes(this.form, 'groupBox[]', this.checked)" id="checkme" name="checkme">
                                                </span>
                                            </th>
                                            <th class="fixed-width-xs">
                                                <span class="title_box">
                                                    {l s='ID' mod='jmarketplace'}
                                                </span>
                                            </th>
                                            <th>
                                                <span class="title_box">
                                                    {l s='Group name' mod='jmarketplace'}
                                                </span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {foreach from=$groups item=group}
                                            <tr>
                                                <td>
                                                    <input type="checkbox" checked="checked" value="{$group.id_group|intval}" id="groupBox_{$group.id_group|intval}" class="groupBox" name="groupBox[]">
                                                </td>
                                                <td>
                                                    {$group.id_group|intval}
                                                </td>
                                                <td>
                                                    <label for="groupBox_{$group.id_group|intval}">
                                                        {$group.name|escape:'html':'UTF-8'}
                                                    </label>
                                                </td>
                                            </tr>
                                        {/foreach}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="submit" name="submitAddCarrier" class="btn btn-default button button-medium">
                                <span>
                                    {l s='Add carrier' mod='jmarketplace'} 
                                    <i class="icon-chevron-right right"></i>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>                     
            </form>
        </div>

        <ul class="footer_links clearfix">
            <li>
                <a class="btn btn-default button" href="{$link->getModuleLink('jmarketplace', 'carriers', array(), true)|escape:'html':'UTF-8'}">
                    <span>
                        <i class="icon-chevron-left"></i> 
                        {l s='Back to your carriers' mod='jmarketplace'}
                    </span>
                </a>
            </li>
            <li>
                <a class="btn btn-default button" href="{$link->getModuleLink('jmarketplace', 'selleraccount', array(), true)|escape:'html':'UTF-8'}"">
                    <span>
                        <i class="icon-chevron-left"></i> 
                        {l s='Back to your seller account' mod='jmarketplace'}
                    </span>
                </a>
            </li>
        </ul> 
    </div>
</div>
<script type="text/javascript">
var need_to_validate = "{l s='Please validate the last range before create a new one.' mod='jmarketplace'}";
var string_weight = "{l s='Will be applied when the weight is' mod='jmarketplace'}";
var string_price = "{l s='Will be applied when the price is' mod='jmarketplace'}";
var PS_WEIGHT_UNIT = "{$PS_WEIGHT_UNIT|escape:'html':'UTF-8'}";
var currency_sign = "{$currency_sign|escape:'html':'UTF-8'}";
</script>                        