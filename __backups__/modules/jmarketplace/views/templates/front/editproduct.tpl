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
    <a href="{$link->getModuleLink('jmarketplace', 'sellerproducts', array(), true)|escape:'html':'UTF-8'}">
        {l s='Your products' mod='jmarketplace'}
    </a>
    <span class="navigation-pipe">
        {$navigationPipe|escape:'html':'UTF-8'}
    </span>
    <span class="navigation_page">
        {l s='Edit product' mod='jmarketplace'} 
        {if isset($id_product) AND $id_product}
            "{$product->name[$id_lang]|escape:'html':'UTF-8'}"
        {/if}
    </span>
{/capture}

<div class="row">
    <div class="column col-xs-12 col-sm-12 col-lg-3"{if $show_menu_options == 0} style="display:none;"{/if}>
        {hook h='displayMarketplaceWidget'}
    </div>
    
    <div class="col-sm-12{if $show_menu_options == 1} col-lg-9{else} col-lg-12{/if}">
        <div class="box">
            <h1 class="page-subheading">
                {l s='Edit product' mod='jmarketplace'} 
                {if isset($id_product) AND $id_product}
                    "{$product->name[$id_lang]|escape:'html':'UTF-8'}"
                {/if}
            </h1>
            {if isset($errors) && $errors}
                {include file="./errors.tpl"}
            {/if}
            <form action="{$form_edit|escape:'html':'UTF-8'}" method="post" class="std" enctype="multipart/form-data">
                <input type="hidden" name="id_product" id="id_product" value="{$product->id|intval}" />
                {if count($languages) > 1}
                    <div class="fixed">
                        <div>{l s='Language' mod='jmarketplace'}</div>
                        <div class="lang_selector">
                            {foreach from=$languages item=language}
                                <img class="flag{if $id_lang == $language.id_lang} selected{/if}" src="{$img_lang_dir|escape:'html':'UTF-8'}{$language.id_lang|intval}.jpg" title="{$language.name|escape:'html':'UTF-8'}" data="{$language.id_lang|intval}" />
                            {/foreach}
                        </div> 
                    </div>
                {/if}
                {if $show_tabs == 1}
                    <div id="jmarketplace-tabs" class="row">
                        <div class="col-lg-3">
                            <div class="list-group">
                                <a href="#information" class="list-group-item active" data-toggle="tab">
                                    <i class="icon-info fa fa-info-circle"></i> 
                                    <span>{l s='Information' mod='jmarketplace'}</span>
                                </a>
                                {if $show_price == 1 || $show_tax == 1}
                                    <a href="#prices" class="list-group-item" data-toggle="tab">
                                        <i class="icon-money fa fa-money"></i> 
                                        <span>{l s='Price' mod='jmarketplace'}</span>
                                    </a>
                                {/if}
                                {if $show_meta_keywords == 1 || $show_meta_title == 1 || $show_meta_desc == 1}
                                    <a href="#seo" class="list-group-item" data-toggle="tab">
                                        <i class="icon-globe fa fa-globe"></i> 
                                        <span>{l s='SEO' mod='jmarketplace'}</span>
                                    </a>
                                {/if}
                                {if $show_categories == 1 || $show_suppliers == 1 || $show_manufacturers == 1}
                                    <a href="#associations" class="list-group-item" data-toggle="tab">
                                        <i class="icon-folder fa fa-folder"></i>  
                                        <span>{l s='Associations' mod='jmarketplace'}</span>
                                    </a>
                                {/if}
                                {if ($show_width == 1 || $show_height == 1 || $show_depth == 1 || $show_weight == 1 || $show_shipping_product == 1)}
                                    <a href="#shipping" class="list-group-item" data-toggle="tab" id="shipping_tab"{if $product->is_virtual == 1} style="display:none;"{/if}>
                                        <i class="icon-truck fa fa-truck"></i> 
                                        <span>{l s='Shipping' mod='jmarketplace'}</span>
                                    </a>
                                {/if} 
                                {if $show_quantity == 1 || $show_minimal_quantity == 1 || $show_available_now == 1 || $show_available_later == 1 || $show_available_date == 1}
                                    <a href="#quantities" class="list-group-item" data-toggle="tab">
                                        <i class="icon-battery-half fa fa-battery-half"></i>  
                                        <span>{l s='Quantities' mod='jmarketplace'}</span>
                                    </a>
                                {/if}
                                {if $show_attributes == 1}
                                    <a href="#combinations" class="list-group-item" data-toggle="tab" id="combinations_tab"{if $show_attributes == 1 && $product->is_virtual == 1} style="display:none;"{/if}>
                                        <i class="icon-list-ul fa fa-list-ul"></i> 
                                        <span>{l s='Combinations' mod='jmarketplace'}</span>
                                    </a>
                                {/if}
                                {if $show_images == 1}
                                    <a href="#images" class="list-group-item" data-toggle="tab">
                                        <i class="icon-images fa fa-file-image-o"></i>  
                                        <span>{l s='Images' mod='jmarketplace'}</span>
                                    </a>
                                {/if}
                                {if $show_features == 1}
                                    <a href="#features" class="list-group-item" data-toggle="tab">
                                        <i class="icon-book fa fa-book"></i> 
                                        <span>{l s='Features' mod='jmarketplace'}</span>
                                    </a>
                                {/if}
                                {if $show_virtual == 1}
                                    <a href="#virtualproduct" class="list-group-item" data-toggle="tab" id="virtual_product_tab"{if $product->is_virtual == 0} style="display:none;"{/if}>
                                        <i class="icon-archive fa fa-archive"></i> 
                                        <span>{l s='Virtual product' mod='jmarketplace'}</span>
                                    </a>
                                {/if}   
                                {if $show_attachments == 1}
                                    <a href="#attachments" class="list-group-item" data-toggle="tab" id="attachments_tab">
                                        <i class="icon-paperclip fa fa-paperclip"></i> 
                                        <span>{l s='Attachments' mod='jmarketplace'}</span>
                                    </a>
                                {/if}
                                {hook h='displayMarketplaceFormAddProductTab'}
                            </div>
                        </div>
                        <div class="tab-content col-lg-9">
                            <div class="tab-pane active panel" id="information">
                                {if $show_virtual == 1}
                                    <div class="form-group hidden d-none">
                                        <label class="control-label">
                                            {l s='Type' mod='jmarketplace'}
                                        </label>
                                        <div>
                                            <div class="radio">
                                                <label for="simple_product">
                                                    <input type="radio" checked="checked" value="0" id="simple_product" name="type_product">
                                                    {l s='Standard product' mod='jmarketplace'}
                                                </label>
                                            </div>

                                            <div class="radio">
                                                <label for="virtual_product">
                                                    <input type="radio" value="2" id="virtual_product" name="type_product"{if $product->is_virtual == 1} checked="checked"{/if}>
                                                    {l s='Virtual product (services, booking, downloadable products, etc.)' mod='jmarketplace'}
                                                </label>
                                            </div>
                                        </div>    
                                    </div> 
                                {/if}
                                <div class="required form-group">
                                    <label for="product_name" class="required">
                                        {l s='Product name' mod='jmarketplace'}
                                    </label>
                                    {foreach from=$languages item=language}
                                        <input{if $id_lang != $language.id_lang} style="display:none;"{/if} class="is_required validate form-control product_name input_with_language lang_{$language.id_lang|intval}" data-validate="isName" type="text" id="name_{$language.id_lang|intval}" name="name_{$language.id_lang|intval}" value="{$product->name[{$language.id_lang|intval}]|escape:'html':'UTF-8'}" maxlength="128" />
                                    {/foreach} 
                                </div>

                                {if $show_reference == 1}
                                    <div class="form-group">
                                        <label for="reference">
                                            {l s='Reference' mod='jmarketplace'} 
                                        </label>
                                        <input class="form-control" type="text" name="reference" id="reference" value="{$product->reference|escape:'html':'UTF-8'}"  maxlength="32" />
                                    </div>
                                {/if}
                                {if $show_isbn == 1}
                                    <div class="form-group">
                                        <label for="isbn">
                                            {l s='ISBN' mod='jmarketplace'} 
                                        </label>
                                        <input class="form-control" type="text" name="isbn" id="isbn" value="{$product->isbn|escape:'html':'UTF-8'}" maxlength="32" />
                                    </div>
                                {/if}
                                {if $show_ean13 == 1}
                                    <div class="form-group">
                                        <label for="ean13">
                                            {l s='Ean13' mod='jmarketplace'} 
                                        </label>
                                        <input class="form-control" type="text" name="ean13" id="ean13" value="{$product->ean13|escape:'html':'UTF-8'}" maxlength="13" />
                                    </div>
                                {/if}
                                {if $show_upc == 1}
                                    <div class="form-group">
                                        <label for="upc">
                                            {l s='UPC' mod='jmarketplace'} 
                                        </label>
                                        <input class="form-control" data-validate="isName" type="text" name="upc" id="upc" value="{$product->upc|escape:'html':'UTF-8'}" maxlength="12" />
                                    </div>
                                {/if}
                                {if $show_available_order == 1 OR $show_show_price == 1 OR $show_online_only == 1}
                                    <label for="options">
                                        {l s='Options' mod='jmarketplace'}
                                    </label>
                                    {if $show_available_order == 1}
                                        <div class="form-group">
                                            <p class="checkbox">
                                                <input type="checkbox" value="1" id="available_for_order" name="available_for_order"{if $product->available_for_order == 1} checked="checked"{/if}>
                                                <label for="available_for_order">
                                                    {l s='Available for order' mod='jmarketplace'}
                                                </label>
                                            </p>
                                        </div>
                                    {/if}
                                    {if $show_show_price == 1}
                                        <div class="form-group">
                                            <p class="checkbox">
                                                <input type="checkbox" value="1" id="show_product_price" name="show_product_price"{if $product->show_price == 1} checked="checked"{/if}>
                                                <label for="show_price">
                                                    {l s='Show price' mod='jmarketplace'}
                                                </label>
                                            </p>
                                        </div>
                                    {/if}
                                    {if $show_online_only == 1}
                                        <div class="form-group">
                                            <p class="checkbox">
                                                <input type="checkbox" value="1" id="online_only" name="online_only"{if $product->online_only == 1} checked="checked"{/if}>
                                                <label for="online_only">
                                                    {l s='Online only (not sold in your retail store)' mod='jmarketplace'}
                                                </label>
                                            </p>
                                        </div>
                                    {/if}
                                {/if}
                                {if $show_condition == 1}
                                    <div class="form-group">
                                        <label for="condition">
                                            {l s='Condition' mod='jmarketplace'}
                                        </label>
                                        <select id="condition" name="condition">
                                            <option{if $product->condition == 'new'} selected="selected"{/if} value="new">
                                                {l s='New' mod='jmarketplace'}
                                            </option>
                                            <option{if $product->condition == 'used'} selected="selected"{/if} value="used">
                                                {l s='Used' mod='jmarketplace'}
                                            </option>
                                            <option{if $product->condition == 'refurbished'} selected="selected"{/if} value="refurbished">
                                                {l s='Refurbished' mod='jmarketplace'}
                                            </option>
                                        </select>
                                    </div>
                                    {if $show_pcondition == 1}
                                        <div class="form-group">
                                            <p class="checkbox">
                                                <input type="checkbox" value="1" id="show_product_condition" name="show_product_condition"{if $product->show_condition == 1} checked="checked"{/if}>
                                                <label for="show_product_condition">
                                                    {l s='Show condition on the product page' mod='jmarketplace'}
                                                </label>
                                            </p>
                                        </div>
                                    {/if}     
                                {/if}
                                {if $show_desc_short == 1}
                                    <div class="form-group">
                                        <label for="short_description">
                                            {l s='Short description' mod='jmarketplace'}
                                        </label>
                                        {foreach from=$languages item=language}
                                            <div id="short_description_{$language.id_lang|intval}" class="short_description input_with_language lang_{$language.id_lang|intval}"{if $id_lang != $language.id_lang} style="display:none;"{/if}>
                                                <textarea name="short_description_{$language.id_lang|intval}" cols="40" rows="7">
                                                    {$product->description_short[{$language.id_lang|intval}] nofilter} {*This is HTML content*}
                                                </textarea>
                                            </div>
                                        {/foreach} 
                                    </div>
                                {/if}
                                {if $show_desc == 1}
                                    <div class="form-group">
                                        <label for="description">
                                            {l s='Description' mod='jmarketplace'}
                                        </label>
                                        {foreach from=$languages item=language}
                                            <div id="description_{$language.id_lang|intval}" class="description input_with_language lang_{$language.id_lang|intval}"{if $id_lang != $language.id_lang} style="display:none;"{/if}>
                                                <textarea name="description_{$language.id_lang|intval}" cols="40" rows="7">
                                                    {$product->description[{$language.id_lang|intval}] nofilter} {*This is HTML content*}
                                                </textarea>
                                            </div>
                                        {/foreach} 
                                    </div>
                                {/if}
                            </div>
                            <div class="tab-pane panel" id="prices">
                                {if $show_price == 1}
                                    <input type="hidden" name="seller_commission" id="seller_commission" value="{$seller_commission|floatval}" />
                                    <input type="hidden" name="fixed_commission" id="fixed_commission" value="{$fixed_commission|floatval}" />
                                    
                                    {if $show_wholesale_price == 1}    
                                        <div class="required form-group">
                                            <label for="wholesale_price">
                                                {l s='Wholesale price' mod='jmarketplace'}
                                            </label>
                                            <div class="input-group">
                                                <input class="form-control" data-validate="isNumber" type="text" name="wholesale_price" id="wholesale_price" value="{$product->wholesale_price|floatval}" maxlength="10" />
                                                <span class="input-group-addon">
                                                    {$sign|escape:'html':'UTF-8'}
                                                </span>
                                            </div>
                                            <p class="help-block">
                                                {l s='The cost price is the price you paid for the product. Do not include the tax. It should be lower than the net sales price: the difference between the two will be your margin.' mod='jmarketplace'}
                                            </p>
                                        </div>
                                    {/if}
                                    <div class="form-group">
                                        <label for="price">
                                            {l s='Price (tax excl.)' mod='jmarketplace'}
                                        </label>
                                        <div class="input-group">
                                            <input class="form-control" data-validate="isNumber" type="text" name="price" id="price" value="{$product->price|floatval}" maxlength="10" />
                                            <span class="input-group-addon">
                                                {$sign|escape:'html':'UTF-8'}
                                            </span>
                                        </div>
                                    </div>
                                        
                                    {if $show_unit_price == 1}  
                                        <div class="row">
                                            <div class="required form-group col-md-6">
                                                <label for="unit_price">
                                                    {l s='Unit price (tax excl.)' mod='jmarketplace'}
                                                </label>
                                                <div class="input-group">
                                                    <input class="form-control" data-validate="isNumber" type="text" name="unit_price" id="unit_price" value="{if $product->unit_price_ratio > 0}{Tools::ps_round(($product->price / $product->unit_price_ratio), 2)|floatval}{else}0{/if}"  maxlength="10" />
                                                    <span class="input-group-addon">
                                                        {$sign|escape:'html':'UTF-8'}
                                                    </span>
                                                </div>
                                                <p class="help-block">
                                                    {l s='If your country\'s pricing laws or regulations require mandatory informations about the base price of a unit, fill in the base price here (for example, price per kg, per liter, per meter).' mod='jmarketplace'}
                                                </p>
                                            </div>
                                            <div class="required form-group col-md-6">
                                                <label for="unit_price">
                                                    {l s='Unity' mod='jmarketplace'}
                                                </label>
                                                <div class="input-group">
                                                    <input class="form-control" type="text" name="unity" id="unity"{if $product->unity != ''} value="{$product->unity|escape:'html':'UTF-8'}"{else} placeholder="{l s='Per kilo, per litre' mod='jmarketplace'}"{/if} maxlength="255" />
                                                </div>
                                            </div>
                                        </div>
                                    {/if}    
                                        
                                    {if $show_offer_price == 1}    
                                        <div class="required form-group">
                                            <label for="offer_price">
                                                {l s='Offer price' mod='jmarketplace'}
                                            </label>
                                            <div class="input-group">
                                                <input class="form-control" data-validate="isNumber" type="text" name="specific_price" id="specific_price" value="{if isset($specific_price)}{$final_price_tax_excl|escape:'html':'UTF-8'}{else}0{/if}" maxlength="10" />
                                                <span class="input-group-addon">
                                                    {$sign|escape:'html':'UTF-8'}
                                                </span>
                                            </div>
                                                <p class="help-block">
                                                    {l s='Leave 0 if no offer. The offer price must be lower than the price.' mod='jmarketplace'}
                                                </p>
                                        </div>  
                                    {/if}
                                    {if $show_tax == 1}
                                        <div class="form-group">
                                            <label for="id_tax">
                                                {l s='Tax' mod='jmarketplace'}
                                            </label>
                                            <select id="id_tax" name="id_tax">
                                                <option value="0">
                                                    {l s='no tax' mod='jmarketplace'}
                                                </option>
                                                {foreach from=$taxes item=tax}
                                                    <option value="{$tax.id_tax_rules_group|intval}" data="{$tax.rate|floatval}"{if isset($product->id_tax_rules_group) && $product->id_tax_rules_group == $tax.id_tax_rules_group} selected="selected"{/if}>
                                                        {$tax.name|escape:'html':'UTF-8'}
                                                    </option>
                                                {/foreach}
                                            </select>
                                        </div>
                                    {/if}
                                    <div class="form-group"{if $show_tax == 0}  style="display:none;"{/if}>
                                        <label for="price">
                                            {l s='Price (tax incl.)' mod='jmarketplace'}
                                        </label>
                                        <div class="input-group">
                                            <input class="form-control" data-validate="isNumber" type="text" name="price_tax_incl" id="price_tax_incl" value="{$final_price_tax_incl|floatval}" readonly="readonly" />
                                            <span class="input-group-addon">
                                                {$sign|escape:'html':'UTF-8'}
                                            </span>
                                        </div>
                                    </div>
                                    {if $show_commission == 1}    
                                        <div class="form-group">
                                            <label for="commission">
                                                {l s='Commission for you' mod='jmarketplace'}
                                            </label>
                                            <div class="input-group">
                                                <input class="form-control" data-validate="isNumber" type="text" name="commission" id="commission" value="{if $tax_commission == 1}{(($final_price_tax_incl * $seller_commission) / 100) - $fixed_commission|floatval}{else}{(($final_price_tax_excl * $seller_commission) / 100)  - $fixed_commission|floatval}{/if}" readonly="readonly" />
                                                <span class="input-group-addon">
                                                    {$sign|escape:'html':'UTF-8'}
                                                </span>
                                            </div>
                                        </div>
                                    {/if}
                                {/if}
                                {if $show_on_sale == 1}
                                    <div class="form-group">
                                        <p class="checkbox">
                                            <input type="checkbox" value="1" id="on_sale" name="on_sale"{if $product->on_sale == 1} checked="checked"{/if}>
                                            <label for="on_sale">
                                                {l s='Display the "on sale" icon on the product page, and in the text found within the product listing.' mod='jmarketplace'}
                                            </label>
                                        </p>
                                    </div>
                                {/if}
                            </div>
                            <div class="tab-pane panel" id="seo">
                                <h4>
                                    {l s='Search Engine Optimization' mod='jmarketplace'}
                                </h4>
                                {if $show_meta_keywords == 1}
                                    <div class="form-group">
                                        <label for="meta_keywords">
                                            {l s='Meta keywords (Every keyword separate by coma, ex. key1, key2, key3...)' mod='jmarketplace'}
                                        </label>
                                        {foreach from=$languages item=language}
                                            <input{if $id_lang != $language.id_lang} style="display:none;"{/if} class="is_required validate form-control meta_keywords input_with_language lang_{$language.id_lang|intval}" data-validate="isName" type="text" id="meta_keywords_{$language.id_lang|intval}" name="meta_keywords_{$language.id_lang|intval}" value="{$product->meta_keywords[{$language.id_lang|intval}]|escape:'html':'UTF-8'}" maxlength="255" />
                                        {/foreach} 
                                    </div>
                                {/if}
                                {if $show_meta_title == 1}
                                    <div class="form-group">
                                        <label for="meta_title">
                                            {l s='Meta title' mod='jmarketplace'}
                                        </label>
                                        {foreach from=$languages item=language}
                                            <input{if $id_lang != $language.id_lang} style="display:none;"{/if} class="is_required validate form-control meta_title input_with_language lang_{$language.id_lang|intval}" data-validate="isName" type="text" id="meta_title_{$language.id_lang|intval}" name="meta_title_{$language.id_lang|intval}" value="{$product->meta_title[{$language.id_lang|intval}]|escape:'html':'UTF-8'}" maxlength="128" />
                                        {/foreach} 
                                    </div>
                                {/if}
                                {if $show_meta_desc == 1}
                                    <div class="form-group">
                                        <label for="meta_description">
                                            {l s='Meta description' mod='jmarketplace'}
                                        </label>
                                        {foreach from=$languages item=language}
                                            <input{if $id_lang != $language.id_lang} style="display:none;"{/if} class="is_required validate form-control meta_description input_with_language lang_{$language.id_lang|intval}" data-validate="isName" type="text" id="meta_description_{$language.id_lang|intval}" name="meta_description_{$language.id_lang|intval}" value="{$product->meta_description[{$language.id_lang|intval}]|escape:'html':'UTF-8'}" maxlength="255" />
                                        {/foreach} 
                                    </div>
                                {/if}
                                {if $show_link_rewrite == 1}
                                    <div class="form-group">
                                        <label for="link_rewrite">
                                            {l s='Friendly URL' mod='jmarketplace'}
                                        </label>
                                        {foreach from=$languages item=language}
                                            <input{if $id_lang != $language.id_lang} style="display:none;"{/if} class="is_required validate form-control link_rewrite input_with_language lang_{$language.id_lang|intval}" data-validate="isName" type="text" id="link_rewrite_{$language.id_lang|intval}" name="link_rewrite_{$language.id_lang|intval}" value="{$product->link_rewrite[{$language.id_lang|intval}]|escape:'html':'UTF-8'}" maxlength="128" />
                                        {/foreach} 
                                    </div>
                                {/if}
                            </div>
                            <div class="tab-pane panel" id="associations">
                                {if $show_categories == 1}
                                    <div class="form-group">
                                        <div class="category_search_block">
                                            <label for="search_tree_category">
                                                {l s='Categories' mod='jmarketplace'}
                                            </label>
                                            <input name="search_tree_category" id="search_tree_category" type="text" class="search_category" placeholder="{l s='Search category' mod='jmarketplace'}" autocomplete="off">
                                            <div id="category_suggestions"></div>    
                                            <div class="checkok"></div>    
                                        </div>
                                        {$categoryTree nofilter} {*This is HTML content*}
                                    </div>
                                    <p>
                                        {l s='This product is associated with' mod='jmarketplace'}: 
                                        {$categories_string|escape:'html':'UTF-8'}
                                    </p>
                                {/if}
                                {if $show_categories == 1}
                                    <div id="category_default" class="form-group">
                                        <label for="id_category_default">
                                            {l s='Category default' mod='jmarketplace'}
                                        </label>
                                        <select id="id_category_default" name="id_category_default">
                                            {foreach from=$categories_selected item=category}
                                                <option value="{$category.id_category|intval}"{if ($category.id_category == $product->id_category_default)} selected="selected"{/if}>
                                                    {$category.name|escape:'html':'UTF-8'}
                                                </option>
                                            {/foreach}
                                        </select>
                                    </div>
                                {/if}
                                {if $show_suppliers == 1}
                                    <div class="form-group">
                                        <label for="id_supplier">
                                            {l s='Supplier' mod='jmarketplace'}
                                        </label>
                                        <select name="id_supplier">
                                            <option value="0">
                                                {l s='-- Choose --' mod='jmarketplace'}
                                            </option>
                                            {foreach from=$suppliers item=supplier}
                                                <option value="{$supplier.id_supplier|intval}"{if $product->id_supplier == $supplier.id_supplier} selected="selected"{/if}>
                                                    {$supplier.name|escape:'html':'UTF-8'}
                                                </option>
                                            {/foreach}
                                        </select>
                                    </div>
                                {/if}
                                {if $show_new_suppliers == 1}
                                    <div class="form-group">
                                        <a id="open_new_supplier" href="#">
                                            {l s='Add new supplier' mod='jmarketplace'}
                                        </a>
                                    </div>
                                    <div id="content_new_supplier" class="form-group" style="display:none;">
                                        <label for="new_supplier">
                                            {l s='New supplier' mod='jmarketplace'}
                                        </label>
                                        <input class="form-control" data-validate="isName" type="text" name="new_supplier" id="new_supplier" maxlength="64" />
                                    </div>
                                {/if}
                                {if $show_manufacturers == 1}
                                    <div class="form-group">
                                        <label for="id_manufacturer">
                                            {l s='Manufacturer' mod='jmarketplace'}
                                        </label>
                                        <select name="id_manufacturer">
                                            <option value="0">
                                                {l s='-- Choose --' mod='jmarketplace'}
                                            </option>
                                            {foreach from=$manufacturers item=manufacturer}
                                                <option value="{$manufacturer.id_manufacturer|intval}"{if $product->id_manufacturer == $manufacturer.id_manufacturer} selected="selected"{/if}>
                                                    {$manufacturer.name|escape:'html':'UTF-8'}
                                                </option>
                                            {/foreach}
                                        </select>
                                    </div>
                                {/if}
                                {if $show_new_manufacturers == 1}
                                    <div class="form-group">
                                        <a id="open_new_manufacturer" href="#">
                                            {l s='Add new manufacturer' mod='jmarketplace'}
                                        </a>
                                    </div>
                                    <div id="content_new_manufacturer" class="form-group" style="display:none;">
                                        <label for="new_manufacturer">
                                            {l s='New manufacturer' mod='jmarketplace'}
                                        </label>
                                        <input class="form-control" data-validate="isName" type="text" name="new_manufacturer" id="new_manufacturer" maxlength="64" />
                                    </div>
                                {/if}
                            </div>
                            {if !$product->is_virtual}
                                <div class="tab-pane panel" id="shipping">
                                    {if $show_width == 1}
                                        <div class="form-group">
                                            <label for="width">
                                                {l s='Width (cm)' mod='jmarketplace'} 
                                            </label>
                                            <input class="form-control" data-validate="isNumber" type="text" name="width" id="width" value="{$product->width|escape:'html':'UTF-8'}" maxlength="10" />
                                        </div>
                                    {/if}
                                    {if $show_height == 1}
                                        <div class="form-group">
                                            <label for="height">
                                                {l s='Height (cm)' mod='jmarketplace'} 
                                            </label>
                                            <input class="form-control" data-validate="isNumber" type="text" name="height" id="height" value="{$product->height|escape:'html':'UTF-8'}" maxlength="10" />
                                        </div>
                                    {/if}
                                    {if $show_depth == 1}
                                        <div class="form-group">
                                            <label for="depth">
                                                {l s='Depth (cm)' mod='jmarketplace'} 
                                            </label>
                                            <input class="form-control" data-validate="isNumber" type="text" name="depth" id="depth" value="{$product->depth|escape:'html':'UTF-8'}" maxlength="10" />
                                        </div>
                                    {/if}
                                    {if $show_weight == 1}
                                        <div class="form-group">
                                            <label for=weight">
                                                {l s='Weight (kg)' mod='jmarketplace'} 
                                            </label>
                                            <input class="form-control" data-validate="isNumber" type="text" name="weight" id="weight" value="{$product->weight|escape:'html':'UTF-8'}" maxlength="10" />
                                        </div>
                                    {/if}

                                    {if $show_shipping_product == 1}                   
                                        <h4>
                                            {l s='Select delivery method' mod='jmarketplace'}
                                        </h4>
                                        {if isset($carriers) AND $carriers}
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                {l s='Delivery service name' mod='jmarketplace'}
                                                            </th>
                                                            <th>
                                                                {l s='Delivery speed' mod='jmarketplace'}
                                                            </th>
                                                            <th>
                                                                {l s='Tick to enable for this product' mod='jmarketplace'}
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        {foreach from=$carriers item=carrier}
                                                            <tr>
                                                                <td>
                                                                    {$carrier.name|escape:'html':'UTF-8'}
                                                                </td>
                                                                <td>
                                                                    {$carrier.delay|escape:'html':'UTF-8'}
                                                                    {if $carrier.is_free == 1}
                                                                         - {l s='Shipping free!' mod='jmarketplace'}
                                                                    {/if}
                                                                </td>
                                                                <td>
                                                                    <input type="checkbox" name="carriers[]" value="{$carrier.id_reference|intval}"{if $carrier.checked == 1} checked="checked"{/if} />
                                                                </td>
                                                            </tr>
                                                        {/foreach}
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="form-group">
                                                <label for="additional_shipping_cost">
                                                    {l s='Additional shipping cost' mod='jmarketplace'} 
                                                </label>
                                                <input class="form-control" type="text" name="additional_shipping_cost" value="{$product->additional_shipping_cost|escape:'html':'UTF-8'}" maxlength="10" />
                                            </div>
                                        {else}
                                             {if $show_manage_carriers == 1}
                                                 <p>
                                                     {l s='First you must create at least one carrier.' mod='jmarketplace'} 
                                                     <a href="{$link->getModuleLink('jmarketplace', 'addcarrier', array(), true)|escape:'html':'UTF-8'}" target="_blank">
                                                         {l s='Create your first carrier now' mod='jmarketplace'}
                                                     </a>
                                                 </p>
                                             {/if}
                                        {/if}  
                                    {/if}
                                </div>
                            {/if}
                            <div class="tab-pane panel" id="quantities">
                                {if $show_quantity == 1}
                                    <div class="form-group">
                                        <label for="quantity">
                                            {l s='Quantity' mod='jmarketplace'} 
                                        </label>
                                        <input class="form-control" data-validate="isNumber" type="text" name="quantity" id="quantity" value="{$real_quantity|intval}" maxlength="10" />
                                    </div>
                                {/if} 
                                {if $show_minimal_quantity == 1}
                                    <div class="form-group">
                                        <label for="minimal_quantity">
                                            {l s='Minimal quantity' mod='jmarketplace'}
                                        </label>
                                        <input class="form-control" data-validate="isNumber" type="text" name="minimal_quantity" id="quantity"{if isset($product->minimal_quantity)} value="{$product->minimal_quantity|intval}"{else} value="1"{/if} maxlength="10" />
                                    </div>
                                {/if} 
                                {if $show_availability == 1}
                                    <div class="form-group">
                                        <label class="control-label">
                                            {l s='Availability preferences (Behavior when out of stock)' mod='jmarketplace'}
                                        </label>
                                        <div>
                                            <div class="radio">
                                                <label for="deny_orders">
                                                    <input type="radio" value="0" id="deny_orders" name="out_of_stock"{if (isset($out_of_stock) AND $out_of_stock == 0)} checked="checked"{/if}>
                                                    {l s='Deny orders' mod='jmarketplace'}
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label for="allow_orders">
                                                    <input type="radio" value="1" id="allow_orders" name="out_of_stock"{if (isset($out_of_stock) AND $out_of_stock == 1)} checked="checked"{/if}>
                                                    {l s='Allow orders' mod='jmarketplace'}
                                                </label>
                                            </div>     
                                            <div class="radio">
                                                <label for="default_behavior">
                                                    <input type="radio" value="2" id="default_behavior" name="out_of_stock"{if (isset($out_of_stock) AND $out_of_stock == 2)} checked="checked"{/if}>
                                                    {l s='Use default behavior (Deny orders)' mod='jmarketplace'}
                                                </label>
                                            </div>  
                                        </div>    
                                    </div> 
                                {/if}
                                {if $show_available_now == 1}
                                    <div class="form-group">
                                        <label for="available_now">
                                            {l s='Available now' mod='jmarketplace'}
                                        </label>
                                        {foreach from=$languages item=language}
                                            <input{if $id_lang != $language.id_lang} style="display:none;"{/if} class="is_required validate form-control product_name input_with_language lang_{$language.id_lang|intval}" type="text" id="available_now_{$language.id_lang|intval}" name="available_now_{$language.id_lang|intval}"{if isset($product->available_now[$language.id_lang|intval])} value="{$product->available_now[{$language.id_lang|intval}]|escape:'html':'UTF-8'}"{/if} maxlength="255" />
                                        {/foreach} 
                                    </div>
                                {/if} 
                                {if $show_available_later == 1}
                                    <div class="form-group">
                                        <label for="available_later">
                                            {l s='Available later' mod='jmarketplace'}
                                        </label>
                                        {foreach from=$languages item=language}
                                            <input{if $id_lang != $language.id_lang} style="display:none;"{/if} class="is_required validate form-control product_name input_with_language lang_{$language.id_lang|intval}" type="text" id="available_later_{$language.id_lang|intval}" name="available_later_{$language.id_lang|intval}"{if isset($product->available_later[$language.id_lang|intval])} value="{$product->available_later[{$language.id_lang|intval}]|escape:'html':'UTF-8'}"{/if} maxlength="255" />
                                        {/foreach} 
                                    </div>
                                {/if} 
                                {if $show_available_date == 1}
                                    <div class="form-group">
                                        <label for="available_date">
                                            {l s='Available date' mod='jmarketplace'}
                                        </label>
                                        <input class="form-control" type="text" name="available_date" id="available_date" value="{if isset($product->available_date)}{$product->available_date|escape:'html':'UTF-8'}{else}0000-00-00{/if}" maxlength="10" />
                                    </div>
                                {/if} 
                            </div>
                            {if $show_attributes == 1 && !$product->is_virtual}  
                                <div class="tab-pane panel" id="combinations">
                                    <h4>
                                        {l s='Attributes' mod='jmarketplace'}
                                    </h4>
                                    {if isset($attribute_groups) AND $attribute_groups}
                                        <div class="row" style="margin-bottom:15px;">
                                            <div class="form-group col-md-5">
                                                <label for="attribute_group">
                                                    {l s='Attribute' mod='jmarketplace'}
                                                </label>
                                                <select id="attribute_group" name="attribute_group">
                                                    <option value="0" selected="selected">
                                                        {l s='-- Choose --' mod='jmarketplace'}
                                                    </option>
                                                    {foreach from=$attribute_groups item=ag}
                                                        <option value="{$ag.id_attribute_group|intval}">
                                                            {$ag.name|escape:'html':'UTF-8'}
                                                        </option>
                                                    {/foreach}
                                                </select>
                                            </div>
                                            <div class="form-group col-md-5">
                                                <label for="attribute">
                                                    {l s='Value' mod='jmarketplace'}
                                                </label>
                                                <select id="attribute" name="attribute">
                                                    <option value="0" selected="selected">
                                                        {l s='-- Choose attribute --' mod='jmarketplace'}
                                                    </option>
                                                    {foreach from=$first_options item=option}
                                                        <option value="{$option.id_attribute|intval}">
                                                            {$option.name|escape:'html':'UTF-8'}
                                                        </option>
                                                    {/foreach}
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <button id="button_add_combination" onclick="add_attr();" class="btn btn-default btn-block" type="button">
                                                    <i class="icon-plus-sign-alt"></i> 
                                                    {l s='Add' mod='jmarketplace'}
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-12">
                                                <select class="form-control col-lg-12" multiple="multiple" name="attribute_combination_list[]" id="product_att_list"></select>
                                            </div>

                                            <div class="form-group col-lg-12">
                                                <button  onclick="add_combination()" class="btn btn-default btn-block" type="button">
                                                    <i class="icon-plus-sign-alt"></i> 
                                                    {l s='Save combination' mod='jmarketplace'}
                                                </button>
                                            </div>

                                            <div class="form-group col-lg-12">
                                                <h4>
                                                    {l s='Combinations' mod='jmarketplace'}
                                                </h4>
                                                <div class="table-responsive">
                                                    <table class="table" id="table-combinations-list">
                                                        <thead>
                                                            <tr class="nodrag nodrop">
                                                                <th class="left">
                                                                    <span class="title_box">
                                                                        {l s='Attribute - value' mod='jmarketplace'}
                                                                    </span>
                                                                </th>
                                                                <th class="left">
                                                                    <span class="title_box">
                                                                        {l s='Combination reference' mod='jmarketplace'}
                                                                    </span>
                                                                </th>
                                                                <th class="left">
                                                                    <span class="title_box">
                                                                        {l s='Impact price' mod='jmarketplace'}
                                                                    </span>
                                                                </th>
                                                                <th class="left">
                                                                    <span class="title_box">
                                                                        {l s='Impact weight' mod='jmarketplace'}
                                                                    </span>
                                                                </th>
                                                                <th class="left">
                                                                    <span class="title_box">
                                                                        {l s='Quantity' mod='jmarketplace'}
                                                                    </span>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            {if isset($attributes) && $attributes}
                                                                {foreach from=$attributes item=attribute}
                                                                    <tr id="combination_{$attribute.id_product_attribute|intval}" class="highlighted odd selected-line">
                                                                        <td class="left">
                                                                            {$attribute.attribute_designation|escape:'html':'UTF-8'}
                                                                        </td>
                                                                        <td class="left">
                                                                            <input type="text" class="form-control col-lg-12" value="{$attribute.reference|escape:'html':'UTF-8'}" name="combination_reference[]">
                                                                        </td>
                                                                        <td class="left">
                                                                            <input type="text" class="form-control col-lg-12" value="{$attribute.price|floatval}" name="combination_price[]">
                                                                        </td>
                                                                        <td class="left">
                                                                            <input type="text" class="form-control col-lg-12" value="{$attribute.weight|floatval}" name="combination_weight[]">
                                                                        </td>
                                                                        <td class="left">
                                                                            <input type="text" class="form-control col-lg-12" value="{$attribute.quantity|floatval}" name="combination_qty[]">
                                                                        </td>
                                                                        <td>
                                                                            <input type="hidden" class="form-control col-md-2" value="{$attribute.id_product_attribute|intval}" name="id_product_attributes[]">
                                                                            <input type="hidden" name="attributes[]" value="{$attribute.attribute_designation|escape:'html':'UTF-8'}" />
                                                                            <a class="edit btn btn-default" data="{$attribute.id_product_attribute|intval}" onclick="delete_combination(this)">
                                                                                <i class="icon-minus-sign-alt"></i> 
                                                                                {l s='Delete' mod='jmarketplace'}
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                {/foreach}
                                                            {/if}  
                                                        </tbody>
                                                    </table> 
                                                </div>
                                            </div> 
                                        </div>
                                    {/if} 
                                    <div class="clear"></div>
                                </div>
                            {/if}
                            <div class="tab-pane panel" id="images">
                                {if $show_images == 1}
                                    <div class="form-group">
                                        <label for="fileUpload">
                                            {l s='Images' mod='jmarketplace'}
                                        </label>
                                        <p>
                                            {l s='You can upload up to' mod='jmarketplace'} 
                                            {$max_images|intval} 
                                            {l s='images.' mod='jmarketplace'}
                                        </p>
                                        <p>
                                            {l s='The optimal size of the images is' mod='jmarketplace'} 
                                            {$max_dimensions|escape:'html':'UTF-8'}
                                        </p>
                                        <br/>
                                        {if isset($images) AND count($images) > 0}
                                            {foreach from=$images item=image name=thumbnails}
                                                {if $smarty.foreach.thumbnails.iteration <= $max_images}
                                                    {assign var=imageIds value="`$product->id`-`$image->id`"}
                                                    {assign var=imageType value='thickbox_default'}
                                                    <hr>
                                                    <div class="row upload_image">
                                                        <div id="contentUploadPreview{$smarty.foreach.thumbnails.iteration|intval}" class="col-xs-12 col-md-3" data="{$smarty.foreach.thumbnails.iteration|intval}">
                                                            <a href="{$link->getImageLink($product->link_rewrite[$id_lang], $imageIds, $imageType)|escape:'html':'UTF-8'}" class="fancybox">
                                                                <img class="img-responsive fancybox" id="uploadPreview{$smarty.foreach.thumbnails.iteration|intval}" src="{$link->getImageLink($product->link_rewrite[$id_lang], $imageIds, 'medium_default')|escape:'html':'UTF-8'}" title="{$image->legend[$id_lang]|escape:'html':'UTF-8'}" height="150" width="150" />
                                                            </a>
                                                            <a class="delete_product_image btn btn-default" href="#" data="{$image->id|intval}">
                                                                <i class="icon-trash fa fa-trash"></i> 
                                                                {l s='Delete' mod='jmarketplace'}
                                                            </a>
                                                        </div>

                                                        <div class="col-xs-12 col-md-9">
                                                            <div class="form-group">
                                                                <label>
                                                                    {l s='Image' mod='jmarketplace'} {$smarty.foreach.thumbnails.iteration|intval}
                                                                    {if $image->cover == 1}
                                                                        <i class="icon-check-sign icon-2x"></i> 
                                                                        {l s='Cover image' mod='jmarketplace'}
                                                                    {/if}
                                                                </label>
                                                                <input class="form-control not_uniform" id="uploadImage{$smarty.foreach.thumbnails.iteration|intval}" type="file" name="images[{$smarty.foreach.thumbnails.iteration|intval}]" onchange="previewImage({$smarty.foreach.thumbnails.iteration|intval});" />
                                                            </div>
                                                            <div class="form-group">             
                                                                <label for="legend">
                                                                    {l s='Legend image' mod='jmarketplace'} 
                                                                    {$smarty.foreach.thumbnails.iteration|intval}
                                                                </label>
                                                                {foreach from=$languages item=language}
                                                                    <input{if $id_lang != $language.id_lang} style="display:none;"{/if} class="form-control input_with_language lang_{$language.id_lang|intval}" type="text" name="legend_{$smarty.foreach.thumbnails.iteration|intval}_{$language.id_lang|intval}" value="{$image->legend[$language.id_lang]|escape:'html':'UTF-8'}" maxlength="128" />
                                                                {/foreach} 
                                                            </div>
                                                        </div>
                                                    </div>
                                                {/if}
                                            {/foreach}
                                        {/if}
                                        
                                        {if count($images) < $max_images}
                                            {for $foo=count($images)+1 to $max_images}
                                                <hr>
                                                <div class="row upload_image">
                                                    <div class="col-xs-12 col-md-3">
                                                        <div class="preview">
                                                            <img class="img-responsive" id="uploadPreview{$foo|intval}" width="150" height="150" src="{$image_not_available|escape:'html':'UTF-8'}" />
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12 col-md-9">
                                                        <div class="form-group">
                                                            <label>
                                                                {l s='Image' mod='jmarketplace'} {$foo|intval}
                                                                {if $foo == 1}
                                                                    <i class="icon-check-sign icon-2x"></i> 
                                                                    {l s='Cover image' mod='jmarketplace'}
                                                                {/if}
                                                            </label>
                                                            <input class="form-control not_uniform" id="uploadImage{$foo|intval}" type="file" name="images[{$foo|intval}]" onchange="previewImage({$foo|intval});" />
                                                        </div>
                                                        <div class="form-group">             
                                                            <label for="legend">
                                                                {l s='Legend image' mod='jmarketplace'} {$foo|intval}
                                                            </label>
                                                            {foreach from=$languages item=language}
                                                                <input{if $id_lang != $language.id_lang} style="display:none;"{/if} class="form-control input_with_language lang_{$language.id_lang|intval}" type="text" name="legend_{$foo|intval}_{$language.id_lang|intval}" value="" maxlength="128" />
                                                            {/foreach} 
                                                        </div>
                                                    </div>
                                                </div>
                                            {/for} 
                                        {/if}
                                    </div>
                                {/if}
                            </div>
                            {if $show_features == 1}     
                                <div class="tab-pane panel" id="features">          
                                    <h4>
                                        {l s='Features' mod='jmarketplace'}
                                    </h4>
                                    {if isset($features) AND $features}
                                        <div class="row">
                                            {foreach from=$features item=feature}
                                                {if isset($feature.featureValues) AND $feature.featureValues}
                                                    <div class="form-group col-lg-12">
                                                        <label for="feature_value_{$feature.id_feature|intval}">
                                                            {$feature.name|escape:'html':'UTF-8'}
                                                        </label>
                                                        <select name="feature_value_{$feature.id_feature|intval}">
                                                            <option value="0">
                                                                {l s='-- Choose --' mod='jmarketplace'}
                                                            </option>
                                                            {foreach from=$feature.featureValues item=option}
                                                                <option value="{$option.id_feature_value|intval}"{if $feature.current_item == $option.id_feature_value} selected="selected"{/if}>
                                                                    {$option.value|escape:'html':'UTF-8'}
                                                                </option>
                                                            {/foreach}
                                                        </select>
                                                    </div>
                                                {else}
                                                    <div class="form-group col-sm-12" style="display:none;">
                                                        <label for="feature_value_{$feature.id_feature|intval}">
                                                            {$feature.name|escape:'html':'UTF-8'}
                                                        </label>
                                                        {if isset($feature.val) AND $feature.val}
                                                            {foreach from=$feature.val item=val}
                                                                {if $feature.current_item == $val.id_feature_value}
                                                                    <input{if $id_lang != $val.id_lang} style="display:none;"{/if} class="form-control features_{$feature.id_feature|intval} input_with_language lang_{$language.id_lang|intval}" data-validate="isName" type="text" name="feature_value_{$feature.id_feature|intval}_{$val.id_lang|intval}" id="feature_value_{$feature.id_feature|intval}_{$val.id_lang|intval}" value="{$val.value|escape:'html':'UTF-8'}" />
                                                                {/if}
                                                            {/foreach}
                                                        {/if}
                                                    </div>
                                                {/if}
                                            {/foreach}
                                        </div>
                                    {/if}      
                                </div>
                            {/if}
                            {if $show_virtual == 1}    
                                <div class="tab-pane panel" id="virtualproduct">   
                                    <div id="virtual_file" class="form-group">
                                        <label for="virtual_file">
                                            {l s='Virtual file' mod='jmarketplace'}
                                        </label>
                                        <input class="form-control" type="file" name="virtual_file" />
                                        {if $is_virtual == 1 AND $display_filename != ''}
                                            <a href="{$form_edit|escape:'html':'UTF-8'}&key={$filename|escape:'html':'UTF-8'}&download" title="{l s='Download this product' mod='jmarketplace'}"> 
                                                <img src="{$modules_dir|escape:'html':'UTF-8'}jmarketplace/views/img/download_product.gif" class="icon" alt="{l s='Download product' mod='jmarketplace'}" />
                                                {$display_filename|escape:'html':'UTF-8'}
                                            </a>
                                        {/if}
                                        
                                        <p class="help-block">
                                            {if $is_virtual == 1 AND $display_filename == ''}
                                                {l s='You have not uploaded a virtual file for this product. ' mod='jmarketplace'}
                                            {/if}
                                            {l s='Upload a file from your computer' mod='jmarketplace'} 
                                            {$attachment_maximun_size|intval} 
                                            {l s='MB maximum.' mod='jmarketplace'}
                                        </p>
                                    </div>
                                    <div class="form-group hidden">
                                        <label for="filename">
                                            {l s='Filename' mod='jmarketplace'}
                                        </label>
                                        <input class="form-control" type="text" name="virtual_product_name" id="virtual_product_name" value="{if isset($virtual_product_name)}{$virtual_product_name|escape:'html':'UTF-8'}{/if}" maxlength="255" />
                                        <p class="help-block">
                                            {l s='The full filename with its extension (e.g. Book.pdf)' mod='jmarketplace'}
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <label for="virtual_product_nb_downloable">
                                            {l s='Number of allowed downloads' mod='jmarketplace'}
                                        </label>
                                        <input class="form-control" type="text" name="virtual_product_nb_downloable" id="virtual_product_nb_downloable" value="{if isset($virtual_product_nb_downloable)}{$virtual_product_nb_downloable|intval}{/if}" maxlength="10" />
                                        <p class="help-block">
                                            {l s='Number of downloads allowed per customer. Set to 0 for unlimited downloads.' mod='jmarketplace'}
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <label for="virtual_product_expiration_date">
                                            {l s='Expiration date' mod='jmarketplace'}
                                        </label>
                                        <input class="form-control" type="text" name="virtual_product_expiration_date" id="virtual_product_expiration_date" value="{if isset($virtual_product_expiration_date)}{$virtual_product_expiration_date|escape:'html':'UTF-8'}{/if}" maxlength="10" />
                                        <p class="help-block">
                                            {l s='If set, the file will not be downloadable after this date. Leave blank if you do not wish to attach an expiration date.' mod='jmarketplace'}
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <label for="virtual_product_nb_days">
                                            {l s='Number of days' mod='jmarketplace'}
                                        </label>
                                        <input class="form-control" type="text" name="virtual_product_nb_days" id="virtual_product_nb_days" value="{if isset($virtual_product_nb_days)}{$virtual_product_nb_days|intval}{else}0{/if}" maxlength="10" />
                                        <p class="help-block">
                                            {l s='Number of days this file can be accessed by customers. Set to zero for unlimited access.' mod='jmarketplace'}
                                        </p>
                                        <p>
                                            {l s='Important: If you want to edit the virtual product information indicated in that box, you must upload a virtual file again.' mod='jmarketplace'}
                                        </p>
                                    </div>  
                                </div>  
                            {/if}
                            {if $show_attachments == 1}
                                <div class="tab-pane panel" id="attachments">
                                    <h4>
                                        {l s='Attachments' mod='jmarketplace'}
                                    </h4>
                                    <p>
                                        {l s='You can upload up to' mod='jmarketplace'} 
                                        {$max_attachments|intval} 
                                        {l s='attached files.' mod='jmarketplace'}
                                    </p>
                                    <p>
                                        {l s='To modify an attachment, you must upload the file.' mod='jmarketplace'}
                                    </p>
                                    {if isset($product_attachments)}
                                        {foreach from=$product_attachments item=attachment name=attachments}
                                            <hr>
                                            <div class="row"> 
                                                {if isset($info_attachments)}
                                                    <input type="hidden" name="id_attachment_{$smarty.foreach.attachments.iteration|intval}" value="{$info_attachments[{$smarty.foreach.attachments.iteration-1|intval}]->id|intval}" />
                                                {/if}
                                                <div class="col-xs-12">
                                                    <div class="form-group">             
                                                        <label for="attachment_name">
                                                            {l s='Attachment name' mod='jmarketplace'} 
                                                            {$smarty.foreach.attachments.iteration|intval}
                                                        </label>
                                                        {foreach from=$languages item=language}
                                                            <input{if $id_lang != $language.id_lang} style="display:none;"{/if} class="is_required validate form-control input_with_language lang_{$language.id_lang|intval}" type="text" name="attachment_name_{$smarty.foreach.attachments.iteration|intval}_{$language.id_lang|intval}" value="{$info_attachments[{$smarty.foreach.attachments.iteration-1|intval}]->name[{$language.id_lang|intval}]|escape:'htmlall':'UTF-8'}" maxlength="32" />
                                                        {/foreach} 
                                                    </div>
                                                    <div class="form-group">             
                                                        <label for="attachment_description">
                                                            {l s='Attachment description' mod='jmarketplace'} 
                                                            {$smarty.foreach.attachments.iteration|intval}
                                                        </label>
                                                        {foreach from=$languages item=language}
                                                            <input{if $id_lang != $language.id_lang} style="display:none;"{/if} class="is_required validate form-control input_with_language lang_{$language.id_lang|intval}" type="text" name="attachment_description_{$smarty.foreach.attachments.iteration|intval}_{$language.id_lang|intval}"  value="{$info_attachments[{$smarty.foreach.attachments.iteration-1|intval}]->description[{$language.id_lang|intval}|escape:'htmlall':'UTF-8']}" />
                                                        {/foreach}
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="attachments">
                                                            {l s='Attachment' mod='jmarketplace'} 
                                                            {$smarty.foreach.attachments.iteration|intval}
                                                        </label>
                                                        <input class="form-control not_uniform" type="file" name="attachments[{$smarty.foreach.attachments.iteration|intval}]" />
                                                        <a href="{$link->getPageLink('attachment', true, NULL, "id_attachment={$attachment.id_attachment}")|escape:'html':'UTF-8'}">
                                                            <i class="icon-download fa fa-download"></i>
                                                            {$attachment.file_name|escape:'html':'UTF-8'} 
                                                            ({Tools::formatBytes($attachment.file_size, 2)|escape:'html':'UTF-8'})
                                                        </a>
                                                    </div>
                                                </div>
                                            </div> 
                                        {/foreach} 
                                        {for $foo=count($product_attachments)+1 to $max_attachments}
                                            <hr>
                                            <div class="row">                                               
                                                <div class="col-xs-12">
                                                    <div class="form-group">             
                                                        <label for="attachment_name">
                                                            {l s='Attachment name' mod='jmarketplace'} {$foo|intval}
                                                        </label>
                                                        {foreach from=$languages item=language}
                                                            <input{if $id_lang != $language.id_lang} style="display:none;"{/if} class="is_required validate form-control input_with_language lang_{$language.id_lang|intval}" type="text" name="attachment_name_{$foo|intval}_{$language.id_lang|intval}"{if isset($attachment_name_{$foo|intval}[$language.id_lang|intval])} value="{$attachment_name_{$foo|intval}_{$language.id_lang|intval}}"{/if} maxlength="32" />
                                                        {/foreach} 
                                                    </div>
                                                    <div class="form-group">             
                                                        <label for="attachment_description">
                                                            {l s='Attachment description' mod='jmarketplace'} {$foo|intval}
                                                        </label>
                                                        {foreach from=$languages item=language}
                                                            <input{if $id_lang != $language.id_lang} style="display:none;"{/if} class="is_required validate form-control input_with_language lang_{$language.id_lang|intval}" type="text" name="attachment_description_{$foo|intval}_{$language.id_lang|intval}"{if isset($attachment_description_{$foo|intval}_{$language.id_lang|intval})} value="{$attachment_description_{$foo|intval}[$language.id_lang|intval]}"{/if} maxlength="32" />
                                                        {/foreach}
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="attachments">
                                                            {l s='Attachment' mod='jmarketplace'} {$foo|intval}
                                                        </label>
                                                        <input class="form-control not_uniform" type="file" name="attachments[{$foo|intval}]" />
                                                    </div>
                                                </div>
                                            </div> 
                                        {/for}
                                    {/if}
                                </div> 
                            {/if}
                            {hook h='displayMarketplaceFormAddProductTabContent'}
                            <div class="form-group pull-right">
                                <button type="submit" name="submitAddProduct" class="btn btn-default button button-medium submitAddProduct">
                                    <span>
                                        {l s='Save' mod='jmarketplace'}
                                        <i class="icon-chevron-right right"></i>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                {else}
                    <div class="form-group">
                        {if $show_virtual == 1}
                            <div class="form-group hidden d-none">
                                <label class="control-label">
                                    {l s='Type' mod='jmarketplace'}
                                </label>
                                <div>
                                    <div class="radio">
                                        <label for="simple_product">
                                            <input type="radio" checked="checked" value="0" id="simple_product" name="type_product">
                                            {l s='Standard product' mod='jmarketplace'}
                                        </label>
                                    </div>

                                    <div class="radio">
                                        <label for="virtual_product">
                                            <input type="radio" value="2" id="virtual_product" name="type_product"{if $product->is_virtual == 1} checked="checked"{/if}>
                                            {l s='Virtual product (services, booking, downloadable products, etc.)' mod='jmarketplace'}
                                        </label>
                                    </div>
                                </div>    
                            </div> 
                        {/if}
                        <div class="required form-group">
                            <label for="product_name" class="required">
                                {l s='Product name' mod='jmarketplace'}
                            </label>
                            {foreach from=$languages item=language}
                                <input{if $id_lang != $language.id_lang} style="display:none;"{/if} class="is_required validate form-control product_name input_with_language lang_{$language.id_lang|intval}" data-validate="isName" type="text" id="name_{$language.id_lang|intval}" name="name_{$language.id_lang|intval}" value="{$product->name[{$language.id_lang|intval}]|escape:'html':'UTF-8'}" maxlength="128" />
                            {/foreach} 
                        </div>

                        {if $show_reference == 1}
                            <div class="form-group">
                                <label for="reference">
                                    {l s='Reference' mod='jmarketplace'} 
                                </label>
                                <input class="form-control" data-validate="isName" type="text" name="reference" id="reference" value="{$product->reference|escape:'html':'UTF-8'}" maxlength="32" />
                            </div>
                        {/if}
                        {if $show_isbn == 1}
                            <div class="form-group">
                                <label for="isbn">
                                    {l s='ISBN' mod='jmarketplace'} 
                                </label>
                                <input class="form-control" type="text" name="isbn" id="isbn" value="{$product->isbn|escape:'html':'UTF-8'}" maxlength="32" />
                            </div>
                        {/if}
                        {if $show_ean13 == 1}
                            <div class="form-group">
                                <label for="ean13">
                                    {l s='Ean13' mod='jmarketplace'} 
                                </label>
                                <input class="form-control" data-validate="isName" type="text" name="ean13" id="ean13" value="{$product->ean13|escape:'html':'UTF-8'}" maxlength="13" />
                            </div>
                        {/if}
                        {if $show_upc == 1}
                            <div class="form-group">
                                <label for="upc">
                                    {l s='UPC' mod='jmarketplace'} 
                                </label>
                                <input class="form-control" data-validate="isName" type="text" name="upc" id="upc" value="{$product->upc|escape:'html':'UTF-8'}" maxlength="12" />
                            </div>
                        {/if}
                        {if $show_available_order == 1 OR $show_show_price == 1 OR $show_online_only == 1}
                            <label for="options">
                                {l s='Options' mod='jmarketplace'}
                            </label>
                            {if $show_available_order == 1}
                                <div class="form-group">
                                    <p class="checkbox">
                                        <input type="checkbox" value="1" id="available_for_order" name="available_for_order"{if $product->available_for_order == 1} checked="checked"{/if}>
                                        <label for="available_for_order">
                                            {l s='Available for order' mod='jmarketplace'}
                                        </label>
                                    </p>
                                </div>
                            {/if}
                            {if $show_show_price == 1}
                                <div class="form-group">
                                    <p class="checkbox">
                                        <input type="checkbox" value="1" id="show_price" name="show_product_price"{if $product->show_price == 1} checked="checked"{/if}>
                                        <label for="show_price">
                                            {l s='Show price' mod='jmarketplace'}
                                        </label>
                                    </p>
                                </div>
                            {/if}
                            {if $show_online_only == 1}
                                <div class="form-group">
                                    <p class="checkbox">
                                        <input type="checkbox" value="1" id="online_only" name="online_only"{if $product->online_only == 1} checked="checked"{/if}>
                                        <label for="online_only">
                                            {l s='Online only (not sold in your retail store)' mod='jmarketplace'}
                                        </label>
                                    </p>
                                </div>
                            {/if}
                        {/if}
                        {if $show_condition == 1}
                            <div class="form-group">
                                <label for="condition">
                                    {l s='Condition' mod='jmarketplace'}
                                </label>
                                <select id="condition" name="condition">
                                    <option{if $product->condition == 'new'} selected="selected"{/if} value="new">
                                        {l s='New' mod='jmarketplace'}
                                    </option>
                                    <option{if $product->condition == 'used'} selected="selected"{/if} value="used">
                                        {l s='Used' mod='jmarketplace'}
                                    </option>
                                    <option{if $product->condition == 'refurbished'} selected="selected"{/if} value="refurbished">
                                        {l s='Refurbished' mod='jmarketplace'}
                                    </option>
                                </select>
                            </div>
                        {/if}
                        {if $show_desc_short == 1}
                            <div class="form-group">
                                <label for="short_description">
                                    {l s='Short description' mod='jmarketplace'}
                                </label>
                                {foreach from=$languages item=language}
                                    <div id="short_description_{$language.id_lang|intval}" class="short_description input_with_language lang_{$language.id_lang|intval}"{if $id_lang != $language.id_lang} style="display:none;"{/if}>
                                        <textarea name="short_description_{$language.id_lang|intval}" cols="40" rows="7">
                                            {$product->description_short[{$language.id_lang|intval}] nofilter} {*This is HTML content*}
                                        </textarea>
                                    </div>
                                {/foreach} 
                            </div>
                        {/if}
                        {if $show_desc == 1}
                            <div class="form-group">
                                <label for="description">
                                    {l s='Description' mod='jmarketplace'}
                                </label>
                                {foreach from=$languages item=language}
                                    <div id="description_{$language.id_lang|intval}" class="description input_with_language lang_{$language.id_lang|intval}"{if $id_lang != $language.id_lang} style="display:none;"{/if}>
                                        <textarea name="description_{$language.id_lang|intval}" cols="40" rows="7">
                                            {$product->description[{$language.id_lang|intval}] nofilter} {*This is HTML content*}
                                        </textarea>
                                    </div>
                                {/foreach} 
                            </div>
                        {/if}
                        {if $show_price == 1}
                            <input type="hidden" name="seller_commission" id="seller_commission" value="{$seller_commission|floatval}" />
                            <input type="hidden" name="fixed_commission" id="fixed_commission" value="{$fixed_commission|floatval}" />
                            
                            {if $show_wholesale_price == 1}    
                                <div class="required form-group">
                                    <label for="wholesale_price">
                                        {l s='Wholesale price' mod='jmarketplace'}
                                    </label>
                                    <div class="input-group">
                                        <input class="form-control" data-validate="isNumber" type="text" name="wholesale_price" id="wholesale_price" value="{$product->wholesale_price|floatval}" maxlength="10" />
                                        <span class="input-group-addon">
                                            {$sign|escape:'html':'UTF-8'}
                                        </span>
                                    </div>
                                </div>
                            {/if}
                            
                            <div class="form-group">
                                <label for="price">
                                    {l s='Price (tax excl.)' mod='jmarketplace'}
                                </label>
                                <div class="input-group">
                                    <input class="form-control" data-validate="isNumber" type="text" name="price" id="price" value="{$product->price|floatval}" maxlength="10" />
                                    <span class="input-group-addon">
                                        {$sign|escape:'html':'UTF-8'}
                                    </span>
                                </div>
                            </div>
                            {if $show_offer_price == 1}    
                                <div class="required form-group">
                                    <label for="offer_price">
                                        {l s='Offer price' mod='jmarketplace'}
                                    </label>
                                    <div class="input-group">
                                        <input class="form-control" data-validate="isNumber" type="text" name="specific_price" id="specific_price" value="{if isset($specific_price)}{$final_price_tax_excl|escape:'html':'UTF-8'}{else}0{/if}" maxlength="10" />
                                        <span class="input-group-addon">
                                            {$sign|escape:'html':'UTF-8'}
                                        </span>
                                    </div>
                                    <p class="help-block">
                                        {l s='Leave 0 if no offer. The offer price must be lower than the price.' mod='jmarketplace'}
                                    </p>
                                </div>  
                            {/if}
                            {if $show_tax == 1}
                                <div class="form-group">
                                    <label for="id_tax">
                                        {l s='Tax' mod='jmarketplace'}
                                    </label>
                                    <select id="id_tax" name="id_tax">
                                        <option value="0">
                                            {l s='no tax' mod='jmarketplace'}
                                        </option>
                                        {foreach from=$taxes item=tax}
                                            <option value="{$tax.id_tax_rules_group|intval}" data="{$tax.rate|floatval}"{if isset($product->id_tax_rules_group) && $product->id_tax_rules_group == $tax.id_tax_rules_group} selected="selected"{/if}>
                                                {$tax.name|escape:'html':'UTF-8'}
                                            </option>
                                        {/foreach}
                                    </select>
                                </div>
                            {/if}
                            <div class="form-group"{if $show_tax == 0}  style="display:none;"{/if}>
                                <label for="price">
                                    {l s='Price (tax incl.)' mod='jmarketplace'}
                                </label>
                                <div class="input-group">
                                    <input class="form-control" data-validate="isNumber" type="text" name="price_tax_incl" id="price_tax_incl" value="{$final_price_tax_incl|floatval}" readonly="readonly" />
                                    <span class="input-group-addon">
                                        {$sign|escape:'html':'UTF-8'}
                                    </span>
                                </div>
                            </div>
                            {if $show_commission == 1}
                                <div class="form-group">
                                    <label for="commission">
                                        {l s='Commission for you' mod='jmarketplace'}
                                    </label>
                                    <div class="input-group">
                                        <input class="form-control" data-validate="isNumber" type="text" name="commission" id="commission" value="{if $tax_commission == 1}{(($final_price_tax_incl * $seller_commission) / 100) - $fixed_commission|floatval}{else}{(($final_price_tax_excl * $seller_commission) / 100)  - $fixed_commission|floatval}{/if}" readonly="readonly" />
                                        <span class="input-group-addon">
                                            {$sign|escape:'html':'UTF-8'}
                                        </span>
                                    </div>
                                </div>
                            {/if}
                        {/if}
                        {if $show_on_sale == 1}
                            <div class="form-group">
                                <p class="checkbox">
                                    <input type="checkbox" value="1" id="on_sale" name="on_sale"{if $product->on_sale == 1} checked="checked"{/if}>
                                    <label for="on_sale">
                                        {l s='Display the "on sale" icon on the product page, and in the text found within the product listing.' mod='jmarketplace'}
                                    </label>
                                </p>
                            </div>
                        {/if}
                        {if $show_meta_keywords == 1 OR $show_meta_title == 1 OR $show_meta_desc == 1 OR $show_link_rewrite == 1}
                            <h4>
                                {l s='Search Engine Optimization' mod='jmarketplace'}
                            </h4>
                        {/if}
                        {if $show_meta_keywords == 1}
                            <div class="form-group">
                                <label for="meta_keywords">
                                    {l s='Meta keywords (Every keyword separate by coma, ex. key1, key2, key3...)' mod='jmarketplace'}
                                </label>
                                {foreach from=$languages item=language}
                                    <input{if $id_lang != $language.id_lang} style="display:none;"{/if} class="is_required validate form-control meta_keywords input_with_language lang_{$language.id_lang|intval}" data-validate="isName" type="text" id="meta_keywords_{$language.id_lang|intval}" name="meta_keywords_{$language.id_lang|intval}" value="{$product->meta_keywords[{$language.id_lang|intval}]|escape:'html':'UTF-8'}" maxlength="255" />
                                {/foreach} 
                            </div>
                        {/if}
                        {if $show_meta_title == 1}
                            <div class="form-group">
                                <label for="meta_title">
                                    {l s='Meta title' mod='jmarketplace'}
                                </label>
                                {foreach from=$languages item=language}
                                    <input{if $id_lang != $language.id_lang} style="display:none;"{/if} class="is_required validate form-control meta_title input_with_language lang_{$language.id_lang|intval}" data-validate="isName" type="text" id="meta_title_{$language.id_lang|intval}" name="meta_title_{$language.id_lang|intval}" value="{$product->meta_title[{$language.id_lang|intval}]|escape:'html':'UTF-8'}" maxlength="128" />
                                {/foreach} 
                            </div>
                        {/if}
                        {if $show_meta_desc == 1}
                            <div class="form-group">
                                <label for="meta_description">
                                    {l s='Meta description' mod='jmarketplace'}
                                </label>
                                {foreach from=$languages item=language}
                                    <input{if $id_lang != $language.id_lang} style="display:none;"{/if} class="is_required validate form-control meta_description input_with_language lang_{$language.id_lang|intval}" data-validate="isName" type="text" id="meta_description_{$language.id_lang|intval}" name="meta_description_{$language.id_lang|intval}" value="{$product->meta_description[{$language.id_lang|intval}]|escape:'html':'UTF-8'}" maxlength="255" />
                                {/foreach} 
                            </div>
                        {/if}
                        {if $show_link_rewrite == 1}
                            <div class="form-group">
                                <label for="link_rewrite">
                                    {l s='Friendly URL' mod='jmarketplace'}
                                </label>
                                {foreach from=$languages item=language}
                                    <input{if $id_lang != $language.id_lang} style="display:none;"{/if} class="is_required validate form-control link_rewrite input_with_language lang_{$language.id_lang|intval}" data-validate="isName" type="text" id="link_rewrite_{$language.id_lang|intval}" name="link_rewrite_{$language.id_lang|intval}" value="{$product->link_rewrite[{$language.id_lang|intval}]|escape:'html':'UTF-8'}" maxlength="128" />
                                {/foreach} 
                            </div>
                        {/if}
                        {if $show_categories == 1}
                            <div class="form-group">
                                <div class="category_search_block">
                                    <label for="search_tree_category">
                                        {l s='Categories' mod='jmarketplace'}
                                    </label>
                                    <input name="search_tree_category" id="search_tree_category" type="text" class="search_category" placeholder="{l s='Search category' mod='jmarketplace'}" autocomplete="off">
                                    <div id="category_suggestions"></div>    
                                    <div class="checkok"></div>    
                                </div>
                                {$categoryTree nofilter} {*This is HTML content*}
                            </div>
                            <p>{l s='This product is associated with' mod='jmarketplace'}:</strong> {$categories_string|escape:'html':'UTF-8'}</p>
                        {/if}
                        {if $show_categories == 1}
                            <div id="category_default" class="form-group">
                                <label for="id_category_default">
                                    {l s='Category default' mod='jmarketplace'}
                                </label>
                                <select id="id_category_default" name="id_category_default">
                                    {foreach from=$categories_selected item=category}
                                        <option value="{$category.id_category|intval}"{if ($category.id_category == $product->id_category_default)} selected="selected"{/if}>{$category.name|escape:'html':'UTF-8'}</option>
                                    {/foreach}
                                </select>
                            </div>
                        {/if}
                        {if $show_suppliers == 1}
                            <div class="form-group">
                                <label for="id_supplier">
                                    {l s='Supplier' mod='jmarketplace'}
                                </label>
                                <select name="id_supplier">
                                    <option value="0">
                                        {l s='-- Choose --' mod='jmarketplace'}
                                    </option>
                                    {foreach from=$suppliers item=supplier}
                                        <option value="{$supplier.id_supplier|intval}"{if $product->id_supplier == $supplier.id_supplier} selected="selected"{/if}>
                                            {$supplier.name|escape:'html':'UTF-8'}
                                        </option>
                                    {/foreach}
                                </select>
                            </div>
                        {/if}
                        {if $show_new_suppliers == 1}
                            <div class="form-group">
                                <a id="open_new_supplier" href="#">
                                    {l s='Add new supplier' mod='jmarketplace'}
                                </a>
                            </div>
                            <div id="content_new_supplier" class="form-group" style="display:none;">
                                <label for="new_supplier">
                                    {l s='New supplier' mod='jmarketplace'}
                                </label>
                                <input class="form-control" data-validate="isName" type="text" name="new_supplier" id="new_supplier" maxlength="64" />
                            </div>
                        {/if}
                        {if $show_manufacturers == 1}
                            <div class="form-group">
                                <label for="id_manufacturer">
                                    {l s='Manufacturer' mod='jmarketplace'}
                                </label>
                                <select name="id_manufacturer">
                                    <option value="0">
                                        {l s='-- Choose --' mod='jmarketplace'}
                                    </option>
                                    {foreach from=$manufacturers item=manufacturer}
                                        <option value="{$manufacturer.id_manufacturer|intval}"{if $product->id_manufacturer == $manufacturer.id_manufacturer} selected="selected"{/if}>
                                            {$manufacturer.name|escape:'html':'UTF-8'}
                                        </option>
                                    {/foreach}
                                </select>
                            </div>
                        {/if}
                        {if $show_new_manufacturers == 1}
                            <div class="form-group">
                                <a id="open_new_manufacturer" href="#">
                                    {l s='Add new manufacturer' mod='jmarketplace'}
                                </a>
                            </div>
                            <div id="content_new_manufacturer" class="form-group" style="display:none;">
                                <label for="new_manufacturer">
                                    {l s='New manufacturer' mod='jmarketplace'}
                                </label>
                                <input class="form-control" data-validate="isName" type="text" name="new_manufacturer" id="new_manufacturer" maxlength="64" />
                            </div>
                        {/if}
                        {if !$product->is_virtual}
                            <div id="shipping">
                                {if $show_width == 1}
                                    <div class="form-group">
                                        <label for="width">
                                            {l s='Width (cm)' mod='jmarketplace'} 
                                        </label>
                                        <input class="form-control" data-validate="isNumber" type="text" name="width" id="width" value="{$product->width|escape:'html':'UTF-8'}" maxlength="10" />
                                    </div>
                                {/if}
                                {if $show_height == 1}
                                    <div class="form-group">
                                        <label for="height">
                                            {l s='Height (cm)' mod='jmarketplace'} 
                                        </label>
                                        <input class="form-control" data-validate="isNumber" type="text" name="height" id="height" value="{$product->height|escape:'html':'UTF-8'}" maxlength="10" />
                                    </div>
                                {/if}
                                {if $show_depth == 1}
                                    <div class="form-group">
                                        <label for="depth">
                                            {l s='Depth (cm)' mod='jmarketplace'} 
                                        </label>
                                        <input class="form-control" data-validate="isNumber" type="text" name="depth" id="depth" value="{$product->depth|escape:'html':'UTF-8'}" maxlength="10" />
                                    </div>
                                {/if}
                                {if $show_weight == 1}
                                    <div class="form-group">
                                        <label for=weight">
                                            {l s='Weight (kg)' mod='jmarketplace'} 
                                        </label>
                                        <input class="form-control" data-validate="isNumber" type="text" name="weight" id="weight" value="{$product->weight|escape:'html':'UTF-8'}" maxlength="10" />
                                    </div>
                                {/if}

                                {if $show_shipping_product == 1}                   
                                    <h4>
                                        {l s='Select delivery method' mod='jmarketplace'}
                                    </h4>
                                    {if isset($carriers) AND $carriers}
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            {l s='Delivery service name' mod='jmarketplace'}
                                                        </th>
                                                        <th>
                                                            {l s='Delivery speed' mod='jmarketplace'}
                                                        </th>
                                                        <th>
                                                            {l s='Tick to enable for this product' mod='jmarketplace'}
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {foreach from=$carriers item=carrier}
                                                        <tr>
                                                            <td>
                                                                {$carrier.name|escape:'html':'UTF-8'}
                                                            </td>
                                                            <td>
                                                                {$carrier.delay|escape:'html':'UTF-8'} 
                                                                {if $carrier.is_free == 1}
                                                                    - {l s='Shipping free!' mod='jmarketplace'}
                                                                {/if}
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" name="carriers[]" value="{$carrier.id_reference|intval}"{if $carrier.checked == 1} checked="checked"{/if} />
                                                            </td>
                                                        </tr>
                                                    {/foreach}
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="form-group">
                                            <label for="additional_shipping_cost">
                                                {l s='Additional shipping cost' mod='jmarketplace'} 
                                            </label>
                                            <input class="form-control" type="text" name="additional_shipping_cost" value="{$product->additional_shipping_cost|escape:'html':'UTF-8'}" maxlength="10" />
                                        </div>
                                    {else}
                                         {if $show_manage_carriers == 1}
                                             <p>
                                                 {l s='First you must create at least one carrier.' mod='jmarketplace'} 
                                                 <a href="{$link->getModuleLink('jmarketplace', 'addcarrier', array(), true)|escape:'html':'UTF-8'}" target="_blank">
                                                     {l s='Create your first carrier now' mod='jmarketplace'}
                                                 </a>
                                             </p>
                                         {/if}
                                    {/if}  
                                {/if}
                            </div>
                        {/if}
                        {if $show_features == 1}     
                            <div id="features">          
                                <h4>
                                    {l s='Features' mod='jmarketplace'}
                                </h4>
                                {if isset($features) AND $features}
                                    {foreach from=$features item=feature}
                                        {if isset($feature.featureValues) AND $feature.featureValues}
                                            <div class="form-group">
                                                <label for="feature_value_{$feature.id_feature|intval}">
                                                    {$feature.name|escape:'html':'UTF-8'}
                                                </label>
                                                <select name="feature_value_{$feature.id_feature|intval}">
                                                    <option value="0">
                                                        {l s='-- Choose --' mod='jmarketplace'}
                                                    </option>
                                                    {foreach from=$feature.featureValues item=option}
                                                        <option value="{$option.id_feature_value|intval}"{if $feature.current_item == $option.id_feature_value} selected="selected"{/if}>
                                                            {$option.value|escape:'html':'UTF-8'}
                                                        </option>
                                                    {/foreach}
                                                </select>
                                            </div>
                                        {else}
                                            <div class="form-group" style="display:none;">
                                                <label for="feature_value_{$feature.id_feature|intval}">
                                                    {$feature.name|escape:'html':'UTF-8'}
                                                </label>
                                                {if isset($feature.val) AND $feature.val}
                                                    {foreach from=$feature.val item=val}
                                                        {if $feature.current_item == $val.id_feature_value}
                                                            <input{if $id_lang != $val.id_lang} style="display:none;"{/if} class="form-control features_{$feature.id_feature|intval} input_with_language lang_{$language.id_lang|intval}" data-validate="isName" type="text" name="feature_value_{$feature.id_feature|intval}_{$val.id_lang|intval}" id="feature_value_{$feature.id_feature|intval}_{$val.id_lang|intval}" value="{$val.value|escape:'html':'UTF-8'}" />
                                                        {/if}
                                                    {/foreach}
                                                {/if}
                                            </div>
                                        {/if}
                                    {/foreach}
                                {/if}      
                            </div>
                        {/if}
                        {if $show_attributes == 1 && !$product->is_virtual}  
                            <div id="combinations">
                                <h4>
                                    {l s='Attributes' mod='jmarketplace'}
                                </h4>
                                {if isset($attribute_groups) AND $attribute_groups}
                                    <div class="row" style="margin-bottom:15px;">
                                        <div class="form-group col-md-5">
                                            <label for="attribute_group">
                                                {l s='Attribute' mod='jmarketplace'}
                                            </label>
                                            <select id="attribute_group" name="attribute_group">
                                                <option value="0" selected="selected">
                                                    {l s='-- Choose --' mod='jmarketplace'}
                                                </option>
                                                {foreach from=$attribute_groups item=ag}
                                                    <option value="{$ag.id_attribute_group|intval}">
                                                        {$ag.name|escape:'html':'UTF-8'}
                                                    </option>
                                                {/foreach}
                                            </select>
                                        </div>
                                        <div class="form-group col-md-5">
                                            <label for="attribute">
                                                {l s='Value' mod='jmarketplace'}
                                            </label>
                                            <select id="attribute" name="attribute">
                                                <option value="0" selected="selected">
                                                    {l s='-- Choose attribute --' mod='jmarketplace'}
                                                </option>
                                                {foreach from=$first_options item=option}
                                                    <option value="{$option.id_attribute|intval}">
                                                        {$option.name|escape:'html':'UTF-8'}
                                                    </option>
                                                {/foreach}
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <button id="button_add_combination" onclick="add_attr();" class="btn btn-default btn-block" type="button">
                                                <i class="icon-plus-sign-alt"></i> 
                                                {l s='Add' mod='jmarketplace'}
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <select class="form-control col-lg-12" multiple="multiple" name="attribute_combination_list[]" id="product_att_list"></select>
                                        </div>

                                        <div class="form-group col-lg-12">
                                            <button  onclick="add_combination()" class="btn btn-default btn-block" type="button">
                                                <i class="icon-plus-sign-alt"></i> 
                                                {l s='Save combination' mod='jmarketplace'}
                                            </button>
                                        </div>

                                        <div class="form-group col-lg-12">
                                            <h4>
                                                {l s='Combinations' mod='jmarketplace'}
                                            </h4>
                                            <div class="table-responsive">
                                                <table class="table" id="table-combinations-list">
                                                    <thead>
                                                        <tr class="nodrag nodrop">
                                                            <th class="left">
                                                                <span class="title_box">
                                                                    {l s='Attribute - value' mod='jmarketplace'}
                                                                </span>
                                                            </th>
                                                            <th class="left">
                                                                <span class="title_box">
                                                                    {l s='Combination reference' mod='jmarketplace'}
                                                                </span>
                                                            </th>
                                                            <th class="left">
                                                                <span class="title_box">
                                                                    {l s='Impact price' mod='jmarketplace'}
                                                                </span>
                                                            </th>
                                                            <th class="left">
                                                                <span class="title_box">
                                                                    {l s='Impact weight' mod='jmarketplace'}
                                                                </span>
                                                            </th>
                                                            <th class="left">
                                                                <span class="title_box">
                                                                    {l s='Quantity' mod='jmarketplace'}
                                                                </span>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        {if isset($attributes) && $attributes}
                                                            {foreach from=$attributes item=attribute}
                                                                <tr id="combination_{$attribute.id_product_attribute|intval}" class="highlighted odd selected-line">
                                                                    <td class="left">
                                                                        {$attribute.attribute_designation|escape:'html':'UTF-8'}
                                                                    </td>
                                                                    <td class="left">
                                                                        <input type="text" class="form-control col-md-12" value="{$attribute.reference|escape:'html':'UTF-8'}" name="combination_reference[]"  maxlength="32">
                                                                    </td>
                                                                    <td class="left">
                                                                        <input type="text" class="form-control col-md-12" value="{$attribute.price|floatval}" name="combination_price[]">
                                                                    </td>
                                                                    <td class="left">
                                                                        <input type="text" class="form-control col-md-12" value="{$attribute.weight|floatval}" name="combination_weight[]">
                                                                    </td>
                                                                    <td class="left">
                                                                        <input type="text" class="form-control col-md-12" value="{$attribute.quantity|floatval}" name="combination_qty[]">
                                                                    </td>
                                                                    <td>
                                                                        <input type="hidden" class="form-control col-md-2" value="{$attribute.id_product_attribute|intval}" name="id_product_attributes[]">
                                                                        <input type="hidden" name="attributes[]" value="{$attribute.attribute_designation|escape:'html':'UTF-8'}" />
                                                                        <a class="edit btn btn-default" data="{$attribute.id_product_attribute|intval}" onclick="delete_combination(this)">
                                                                            <i class="icon-minus-sign-alt"></i> 
                                                                            {l s='Delete' mod='jmarketplace'}
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            {/foreach}
                                                        {/if}  
                                                    </tbody>
                                                </table> 
                                            </div>
                                        </div> 
                                    </div>
                                {/if} 
                                <div class="clear"></div>
                            </div>
                        {/if}
                        {if $show_quantity == 1}
                            <div class="form-group">
                                <label for="quantity">
                                    {l s='Quantity' mod='jmarketplace'} 
                                </label>
                                <input class="form-control" data-validate="isNumber" type="text" name="quantity" id="quantity" value="{$real_quantity|intval}" maxlength="10" />
                            </div>
                        {/if} 
                        {if $show_minimal_quantity == 1}
                            <div class="form-group">
                                <label for="minimal_quantity">
                                    {l s='Minimal quantity' mod='jmarketplace'}
                                </label>
                                <input class="form-control" data-validate="isNumber" type="text" name="minimal_quantity" id="quantity"{if isset($product->minimal_quantity)} value="{$product->minimal_quantity|intval}"{else} value="1"{/if} maxlength="10" />
                            </div>
                        {/if} 
                        {if $show_availability == 1}
                            <div class="form-group">
                                <label class="control-label">
                                    {l s='Availability preferences (Behavior when out of stock)' mod='jmarketplace'}
                                </label>
                                <div>
                                    <div class="radio">
                                        <label for="deny_orders">
                                            <input type="radio" value="0" id="deny_orders" name="out_of_stock"{if (isset($out_of_stock) AND $out_of_stock == 0)} checked="checked"{/if}>
                                            {l s='Deny orders' mod='jmarketplace'}
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label for="allow_orders">
                                            <input type="radio" value="1" id="allow_orders" name="out_of_stock"{if (isset($out_of_stock) AND $out_of_stock == 1)} checked="checked"{/if}>
                                            {l s='Allow orders' mod='jmarketplace'}
                                        </label>
                                    </div>     
                                    <div class="radio">
                                        <label for="default_behavior">
                                            <input type="radio" value="2" id="default_behavior" name="out_of_stock"{if (isset($out_of_stock) AND $out_of_stock == 2)} checked="checked"{/if}>
                                            {l s='Use default behavior (Deny orders)' mod='jmarketplace'}
                                        </label>
                                    </div>  
                                </div>    
                            </div> 
                        {/if}
                        {if $show_available_now == 1}
                            <div class="form-group">
                                <label for="available_now">
                                    {l s='Available now' mod='jmarketplace'}
                                </label>
                                {foreach from=$languages item=language}
                                    <input{if $id_lang != $language.id_lang} style="display:none;"{/if} class="is_required validate form-control product_name input_with_language lang_{$language.id_lang|intval}" type="text" id="available_now_{$language.id_lang|intval}" name="available_now_{$language.id_lang|intval}"{if isset($product->available_now[$language.id_lang|intval])} value="{$product->available_now[{$language.id_lang|intval}]|escape:'html':'UTF-8'}"{/if} maxlength="255" />
                                {/foreach} 
                            </div>
                        {/if} 
                        {if $show_available_later == 1}
                            <div class="form-group">
                                <label for="available_later">
                                    {l s='Available later' mod='jmarketplace'}
                                </label>
                                {foreach from=$languages item=language}
                                    <input{if $id_lang != $language.id_lang} style="display:none;"{/if} class="is_required validate form-control product_name input_with_language lang_{$language.id_lang|intval}" type="text" id="available_later_{$language.id_lang|intval}" name="available_later_{$language.id_lang|intval}"{if isset($product->available_later[$language.id_lang|intval])} value="{$product->available_later[{$language.id_lang|intval}]|escape:'html':'UTF-8'}"{/if} maxlength="255" />
                                {/foreach} 
                            </div>
                        {/if} 
                        {if $show_available_date == 1}
                            <div class="form-group">
                                <label for="available_date">
                                    {l s='Available date' mod='jmarketplace'}
                                </label>
                                <input class="form-control" type="text" name="available_date" id="available_date" value="{if isset($product->available_date)}{$product->available_date|escape:'html':'UTF-8'}{else}0000-00-00{/if}" maxlength="10" />
                            </div>
                        {/if} 
                        {if $show_images == 1}
                            <div class="form-group">
                                <label for="fileUpload">
                                    {l s='Images' mod='jmarketplace'}
                                </label>
                                <p>
                                    {l s='You can upload up to' mod='jmarketplace'} 
                                    {$max_images|intval} 
                                    {l s='images.' mod='jmarketplace'}
                                </p>
                                <p>
                                    {l s='The optimal size of the images is' mod='jmarketplace'} 
                                    {$max_dimensions|escape:'html':'UTF-8'}
                                </p>
                                <br/>
                                {if isset($images) AND count($images) > 0}
                                    {foreach from=$images item=image name=thumbnails}
                                        {if $smarty.foreach.thumbnails.iteration <= $max_images}
                                            {assign var=imageIds value="`$product->id`-`$image->id`"}
                                            {assign var=imageType value='thickbox_default'}
                                            <hr>
                                            <div class="row upload_image">
                                                <div id="contentUploadPreview{$smarty.foreach.thumbnails.iteration|intval}" class="col-xs-12 col-md-3" data="{$smarty.foreach.thumbnails.iteration|intval}">
                                                    <a href="{$link->getImageLink($product->link_rewrite[$id_lang], $imageIds, $imageType)|escape:'html':'UTF-8'}" class="fancybox">
                                                        <img class="img-responsive fancybox" id="uploadPreview{$smarty.foreach.thumbnails.iteration|intval}" src="{$link->getImageLink($product->link_rewrite[$id_lang], $imageIds, 'medium_default')|escape:'html':'UTF-8'}" title="{$image->legend[$id_lang]|escape:'html':'UTF-8'}" height="150" width="150" />
                                                    </a>
                                                    <a class="delete_product_image btn btn-default" href="#" data="{$image->id|intval}">
                                                        <i class="icon-trash fa fa-trash"></i> 
                                                        {l s='Delete' mod='jmarketplace'}
                                                    </a>
                                                </div>

                                                <div class="col-xs-12 col-md-9">
                                                    <div class="form-group">
                                                        <label for="images">
                                                            {l s='Image' mod='jmarketplace'} {$smarty.foreach.thumbnails.iteration|intval}
                                                            {if $image->cover == 1}
                                                                <i class="icon-check-sign icon-2x"></i> 
                                                                {l s='Cover image' mod='jmarketplace'}
                                                            {/if}
                                                        </label>
                                                        <input class="form-control not_uniform" id="uploadImage{$smarty.foreach.thumbnails.iteration|intval}" type="file" name="images[{$smarty.foreach.thumbnails.iteration|intval}]" onchange="previewImage({$smarty.foreach.thumbnails.iteration|intval});" />
                                                    </div>
                                                    <div class="form-group">             
                                                        <label for="legend">
                                                            {l s='Legend image' mod='jmarketplace'} {$smarty.foreach.thumbnails.iteration|intval}
                                                        </label>
                                                        {foreach from=$languages item=language}
                                                            <input{if $id_lang != $language.id_lang} style="display:none;"{/if} class="form-control input_with_language lang_{$language.id_lang|intval}" type="text" name="legend_{$smarty.foreach.thumbnails.iteration|intval}_{$language.id_lang|intval}" value="{$image->legend[$language.id_lang]|escape:'html':'UTF-8'}" maxlength="128" />
                                                        {/foreach} 
                                                    </div>
                                                </div>
                                            </div>
                                        {/if}
                                    {/foreach}
                                {/if}

                                {if count($images) < $max_images}
                                    {for $foo=count($images)+1 to $max_images}
                                        <hr>
                                        <div class="row upload_image">
                                            <div class="col-xs-12 col-md-3">
                                                <div class="preview">
                                                    <img class="img-responsive" id="uploadPreview{$foo|intval}" width="150" height="150" src="{$image_not_available|escape:'html':'UTF-8'}" />
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-md-9">
                                                <div class="form-group">
                                                    <label>
                                                        {l s='Image' mod='jmarketplace'} {$foo|intval}
                                                        {if $foo == 1}
                                                            <i class="icon-check-sign icon-2x"></i> 
                                                            {l s='Cover image' mod='jmarketplace'}
                                                        {/if}
                                                    </label>
                                                    <input class="form-control not_uniform" id="uploadImage{$foo|intval}" type="file" name="images[{$foo|intval}]" onchange="previewImage({$foo|intval});" />
                                                </div>
                                                <div class="form-group">             
                                                    <label for="legend">{l s='Legend image' mod='jmarketplace'} {$foo|intval}</label>
                                                    {foreach from=$languages item=language}
                                                        <input{if $id_lang != $language.id_lang} style="display:none;"{/if} class="form-control input_with_language lang_{$language.id_lang|intval}" type="text" name="legend_{$foo|intval}_{$language.id_lang|intval}" value="" maxlength="128" />
                                                    {/foreach} 
                                                </div>
                                            </div>
                                        </div>
                                    {/for} 
                                {/if}
                            </div>
                        {/if}
                        {if $show_virtual == 1}   
                            <div class="form-group" id="virtualproduct">          
                                <div id="virtual_file" class="form-group">
                                    <label for="virtual_file">
                                        {l s='Virtual file' mod='jmarketplace'}
                                    </label>
                                    <input class="form-control" type="file" name="virtual_file" />
                                    {if $is_virtual == 1 AND $display_filename != ''}
                                        <a href="{$form_edit|escape:'html':'UTF-8'}&key={$filename|escape:'html':'UTF-8'}&download" title="{l s='Download this product' mod='jmarketplace'}"> 
                                            <img src="{$modules_dir|escape:'html':'UTF-8'}jmarketplace/views/img/download_product.gif" class="icon" alt="{l s='Download product' mod='jmarketplace'}" />
                                            {$display_filename|escape:'html':'UTF-8'}
                                        </a>
                                    {/if}

                                    <p class="help-block">
                                        {if $is_virtual == 1 AND $display_filename == ''}
                                            {l s='You have not uploaded a virtual file for this product. ' mod='jmarketplace'}
                                        {/if}
                                        {l s='Upload a file from your computer' mod='jmarketplace'} 
                                        {$attachment_maximun_size|intval} 
                                        {l s='MB maximum.' mod='jmarketplace'}
                                    </p>
                                </div>
                                <div class="form-group hidden">
                                    <label for="filename">
                                        {l s='Filename' mod='jmarketplace'}
                                    </label>
                                    <input class="form-control" type="text" name="virtual_product_name" id="virtual_product_name" value="{if isset($virtual_product_name)}{$virtual_product_name|escape:'html':'UTF-8'}{/if}" maxlength="255" />
                                    <p class="help-block">
                                        {l s='The full filename with its extension (e.g. Book.pdf)' mod='jmarketplace'}
                                    </p>
                                </div>
                                <div class="form-group">
                                    <label for="virtual_product_nb_downloable">
                                        {l s='Number of allowed downloads' mod='jmarketplace'}
                                    </label>
                                    <input class="form-control" type="text" name="virtual_product_nb_downloable" id="virtual_product_nb_downloable" value="{if isset($virtual_product_nb_downloable)}{$virtual_product_nb_downloable|intval}{/if}" maxlength="10" />
                                    <p class="help-block">
                                        {l s='Number of downloads allowed per customer. Set to 0 for unlimited downloads.' mod='jmarketplace'}
                                    </p>
                                </div>
                                <div class="form-group">
                                    <label for="virtual_product_expiration_date">
                                        {l s='Expiration date' mod='jmarketplace'}
                                    </label>
                                    <input class="form-control" type="text" name="virtual_product_expiration_date" id="virtual_product_expiration_date" value="{if isset($virtual_product_expiration_date)}{$virtual_product_expiration_date|escape:'html':'UTF-8'}{/if}" maxlength="10" />
                                    <p class="help-block">
                                        {l s='If set, the file will not be downloadable after this date. Leave blank if you do not wish to attach an expiration date.' mod='jmarketplace'}
                                    </p>
                                </div>
                                <div class="form-group">
                                    <label for="virtual_product_nb_days">
                                        {l s='Number of days' mod='jmarketplace'}
                                    </label>
                                    <input class="form-control" type="text" name="virtual_product_nb_days" id="virtual_product_nb_days" value="{if isset($virtual_product_nb_days)}{$virtual_product_nb_days|intval}{else}0{/if}" maxlength="10" />
                                    <p class="help-block">
                                        {l s='Number of days this file can be accessed by customers. Set to zero for unlimited access.' mod='jmarketplace'}
                                    </p>
                                    <p>
                                        {l s='Important: If you want to edit the virtual product information indicated in that box, you must upload a virtual file again.' mod='jmarketplace'}
                                    </p>
                                </div>  
                            </div>
                        {/if}
                        {if $show_attachments == 1}
                            <div class="form-group" id="attachments">
                                <h4>
                                    {l s='Attachments' mod='jmarketplace'}
                                </h4>
                                <p>
                                    {l s='You can upload up to' mod='jmarketplace'} 
                                    {$max_attachments|intval} 
                                    {l s='attached files.' mod='jmarketplace'}
                                </p>
                                <p>
                                    {l s='To modify an attachment, you must upload the file.' mod='jmarketplace'}
                                </p>
                                {if isset($product_attachments)}
                                    {foreach from=$product_attachments item=attachment name=attachments}
                                        <hr>
                                        <div class="row">   
                                            {if isset($info_attachments)}
                                                <input type="hidden" name="id_attachment_{$smarty.foreach.attachments.iteration|intval}" value="{$info_attachments[{$smarty.foreach.attachments.iteration-1|intval}]->id|intval}" />
                                            {/if}
                                            <div class="col-xs-12">
                                                <div class="form-group">             
                                                    <label for="attachment_name">
                                                        {l s='Attachment name' mod='jmarketplace'} 
                                                        {$smarty.foreach.attachments.iteration|intval}
                                                    </label>
                                                    {foreach from=$languages item=language}
                                                        <input{if $id_lang != $language.id_lang} style="display:none;"{/if} class="is_required validate form-control input_with_language lang_{$language.id_lang|intval}" type="text" name="attachment_name_{$smarty.foreach.attachments.iteration|intval}_{$language.id_lang|intval}" value="{$info_attachments[{$smarty.foreach.attachments.iteration-1|intval}]->name[{$language.id_lang|intval}]|escape:'htmlall':'UTF-8'}" maxlength="32" />
                                                    {/foreach} 
                                                </div>
                                                <div class="form-group">             
                                                    <label for="attachment_description">
                                                        {l s='Attachment description' mod='jmarketplace'} 
                                                        {$smarty.foreach.attachments.iteration|intval}
                                                    </label>
                                                    {foreach from=$languages item=language}
                                                        <input{if $id_lang != $language.id_lang} style="display:none;"{/if} class="is_required validate form-control input_with_language lang_{$language.id_lang|intval}" type="text" name="attachment_description_{$smarty.foreach.attachments.iteration|intval}_{$language.id_lang|intval}"  value="{$info_attachments[{$smarty.foreach.attachments.iteration-1|intval}]->description[{$language.id_lang|intval}]|escape:'htmlall':'UTF-8'}" />
                                                    {/foreach}
                                                </div>
                                                <div class="form-group">
                                                    <label for="attachments">
                                                        {l s='Attachment' mod='jmarketplace'} 
                                                        {$smarty.foreach.attachments.iteration|intval}
                                                    </label>
                                                    <input class="form-control not_uniform" type="file" name="attachments[{$smarty.foreach.attachments.iteration|intval}]" />
                                                    <a href="{$link->getPageLink('attachment', true, NULL, "id_attachment={$attachment.id_attachment}")|escape:'html':'UTF-8'}">
                                                        <i class="icon-download fa fa-download"></i>
                                                        {$attachment.file_name|escape:'html':'UTF-8'} 
                                                        ({Tools::formatBytes($attachment.file_size, 2)|escape:'html':'UTF-8'})
                                                    </a>
                                                </div>
                                            </div>
                                        </div> 
                                    {/foreach} 
                                    {for $foo=count($product_attachments)+1 to $max_attachments}
                                        <hr>
                                        <div class="row">                                               
                                            <div class="col-xs-12">
                                                <div class="form-group">             
                                                    <label for="attachment_name">
                                                        {l s='Attachment name' mod='jmarketplace'} {$foo|intval}
                                                    </label>
                                                    {foreach from=$languages item=language}
                                                        <input{if $id_lang != $language.id_lang} style="display:none;"{/if} class="is_required validate form-control input_with_language lang_{$language.id_lang|intval}" type="text" name="attachment_name_{$foo|intval}_{$language.id_lang|intval}"{if isset($attachment_name_{$foo|intval}[$language.id_lang|intval])} value="{$attachment_name_{$foo|intval}_{$language.id_lang|intval}}"{/if} maxlength="32" />
                                                    {/foreach} 
                                                </div>
                                                <div class="form-group">             
                                                    <label for="attachment_description">
                                                        {l s='Attachment description' mod='jmarketplace'} {$foo|intval}
                                                    </label>
                                                    {foreach from=$languages item=language}
                                                        <input{if $id_lang != $language.id_lang} style="display:none;"{/if} class="is_required validate form-control input_with_language lang_{$language.id_lang|intval}" type="text" name="attachment_description_{$foo|intval}_{$language.id_lang|intval}"{if isset($attachment_description_{$foo|intval}_{$language.id_lang|intval})} value="{$attachment_description_{$foo|intval}[$language.id_lang|intval]}"{/if} maxlength="32" />
                                                    {/foreach}
                                                </div>
                                                <div class="form-group">
                                                    <label for="attachments">
                                                        {l s='Attachment' mod='jmarketplace'} {$foo|intval}
                                                    </label>
                                                    <input class="form-control not_uniform" type="file" name="attachments[{$foo|intval}]" />
                                                </div>
                                            </div>
                                        </div> 
                                    {/for}
                                {/if}
                            </div> 
                        {/if}
                        {hook h='displayMarketplaceFormAddProduct'}
                        <button type="submit" name="submitAddProduct" class="btn btn-default button button-medium submitAddProduct">
                            <span>
                                {l s='Save' mod='jmarketplace'}
                                <i class="icon-chevron-right right"></i>
                            </span>
                        </button>
                    </div>
                {/if}
            </form>
        </div>
        <ul class="footer_links clearfix">
            <li>
                <a class="btn btn-default button" href="{$link->getModuleLink('jmarketplace', 'selleraccount', array(), true)|escape:'html':'UTF-8'}">
                    <span>
                        <i class="icon-chevron-left fa fa-chevron-left"></i> 
                        {l s='Back to your seller account' mod='jmarketplace'}
                    </span>
                </a>
            </li>
            <li>
                <a class="btn btn-default button" href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}">
                    <span>
                        <i class="icon-chevron-left fa fa-chevron-left"></i> 
                        {l s='Back to your account' mod='jmarketplace'}
                    </span>
                </a>
            </li>
        </ul>
    </div>
</div>    
<script type="text/javascript">
var addproduct_controller_url = "{$link->getModuleLink('jmarketplace', 'addproduct', array(), true)|escape:'html':'UTF-8'}";
var editproduct_controller_url = "{$link->getModuleLink('jmarketplace', 'editproduct', array(), true)|escape:'html':'UTF-8'}";
var image_not_available = "{$image_not_available|escape:'html':'UTF-8'}";
var has_attributes = "{$has_attributes|intval}";
var confirmSelectedCategoryTree = "{l s='has been selected ok.' mod='jmarketplace'}";
var PS_REWRITING_SETTINGS = "{$PS_REWRITING_SETTINGS|intval}";
var tax_commission = "{$tax_commission|intval}";
</script>
{if $show_attributes == 1}   
<script type="text/javascript">
var modules_dir = "{$modules_dir|escape:'quotes':'UTF-8'}";
var token = "{$token|escape:'quotes':'UTF-8'}";
var errorSaveCombination = "{l s='You must select an option.' mod='jmarketplace'}";
var errorAttributeGroupEmpty = "{l s='You have not selected attribute group.' mod='jmarketplace'}";
var errorAttributeEmpty = "{l s='You have not selected attribute.' mod='jmarketplace'}";
var buttonDelete = "{l s='Delete' mod='jmarketplace'}";
var errorHasAttributes = "{l s='You cannot use combinations with a virtual product.' mod='jmarketplace'}";
</script>
{/if}