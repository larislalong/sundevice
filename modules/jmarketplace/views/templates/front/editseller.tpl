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
    <span class="navigation_page">
        {l s='Edit your seller account' mod='jmarketplace'}
    </span>
{/capture}

<div class="row">
    <div class="column col-xs-12 col-sm-12 col-lg-3"{if $show_menu_options == 0} style="display:none;"{/if}>
        {hook h='displayMarketplaceWidget'}
    </div>
    
    <div class="col-sm-12{if $show_menu_options == 1} col-lg-9{else} col-lg-12{/if}">
        <div class="box">
            <h1 class="page-subheading">
                {l s='Edit your seller account' mod='jmarketplace'}
            </h1>
            {if isset($confirmation) && $confirmation}
                {if $moderate}
                    <p class="alert alert-success">
                        {l s='Your seller account has been successfully edited. It is pending approval.' mod='jmarketplace'}
                    </p>
                {else}
                    <p class="alert alert-success">
                        {l s='Your seller account has been successfully edited.' mod='jmarketplace'}
                    </p>
                {/if}
            {else}
                {if isset($errors) && $errors}
                    {include file="./errors.tpl"}
                {/if}
                <p class="info-title">
                    {l s='Edit your seller account.' mod='jmarketplace'}
                </p>
                <form action="{$link->getModuleLink('jmarketplace', 'editseller', array(), true)|escape:'html':'UTF-8'}" method="post" class="std" enctype="multipart/form-data">
                    <fieldset>
                        <div class="required form-group">
                            <label for="name" class="required">
                                {l s='Seller name' mod='jmarketplace'}
                            </label>
                            <input class="is_required validate form-control" type="text" id="name" name="name" value="{$seller->name|escape:'html':'UTF-8'}" maxlength="64" required />
                        </div>
                        {if $show_shop_name == 1}
                            <div class="form-group">
                                <label for="shop">
                                    {l s='Shop name' mod='jmarketplace'}
                                </label>
                                <input class="form-control" type="text" name="shop" id="shop" value="{$seller->shop|escape:'html':'UTF-8'}" maxlength="64" />
                            </div>
                        {/if}
                        {if $show_cif == 1}
                            <div class="required form-group">
                                <label for="cif">
                                    {l s='CIF/NIF' mod='jmarketplace'}
                                </label>
                                <input class="form-control" type="text" name="cif" id="cif" value="{$seller->cif|escape:'html':'UTF-8'}" maxlength="16" />
                            </div>
                        {/if}
                        <div class="required form-group">
                            <label for="email" class="required">
                                {l s='Seller email' mod='jmarketplace'}
                            </label>
                            <input class="is_required validate form-control" type="text" id="email" name="email" value="{$seller->email|escape:'html':'UTF-8'}" maxlength="64" required />
                        </div>
                        {if $show_language == 1}
                            <div class="form-group">
                                <label for="seller_lang">
                                    {l s='Language' mod='jmarketplace'}
                                </label>
                                <select name="id_lang">
                                    {foreach from=$languages item=language}
                                        <option value="{$language.id_lang|intval}"{if $seller->id_lang == $language.id_lang} selected="selected"{/if}>{$language.name|escape:'html':'UTF-8'} </option>
                                    {/foreach}
                                </select>
                            </div>
                        {/if}
                        {if $show_phone == 1}
                            <div class="required form-group">
                                <label for="phone">
                                    {l s='Phone' mod='jmarketplace'}
                                </label>
                                <input class="form-control" type="text" name="phone" id="phone" value="{$seller->phone|escape:'html':'UTF-8'}" maxlength="32" />
                            </div>
                        {/if}
                        {if $show_fax == 1}
                            <div class="form-group">
                                <label for="fax">
                                    {l s='Fax' mod='jmarketplace'}
                                </label>
                                <input class="form-control" type="text" name="fax" id="fax" value="{$seller->fax|escape:'html':'UTF-8'}" maxlength="32" />
                            </div>
                        {/if}
                        {if $show_address == 1}
                            <div class="form-group">
                                <label for="address">
                                    {l s='Address' mod='jmarketplace'}
                                </label>
                                <input class="form-control" type="text" name="address" id="address" value="{$seller->address|escape:'html':'UTF-8'}" maxlength="128" />
                            </div>
                        {/if}
                        {if $show_country == 1}
                            <div class="form-group">
                                <label for="country">
                                    {l s='Country' mod='jmarketplace'}
                                </label>
                                <select name="country">
                                    <option value="">
                                        {l s='-- Choose --' mod='jmarketplace'}
                                    </option>
                                    {foreach from=$countries item=country}
                                        <option value="{$country.name|escape:'html':'UTF-8'}" {if $country.name == $seller->country} selected="selected"{/if}>
                                            {$country.name|escape:'html':'UTF-8'}
                                        </option>
                                    {/foreach}
                                </select>
                            </div>
                        {/if}
                        {if $show_state == 1}
                            <div class="form-group">
                                <label for="state">
                                    {l s='State' mod='jmarketplace'}
                                </label>
                                <input class="form-control" type="text" name="state" id="state" value="{$seller->state|escape:'html':'UTF-8'}" maxlength="128" />
                            </div>
                        {/if}
                        {if $show_postcode == 1}
                            <div class="form-group">
                                <label for="postcode">
                                    {l s='Postal Code' mod='jmarketplace'}
                                </label>
                                <input class="form-control" type="text" name="postcode" id="postcode" value="{$seller->postcode|escape:'html':'UTF-8'}" maxlength="12" />
                            </div>
                        {/if}
                        {if $show_city == 1}
                            <div class="form-group">
                                <label for="city">
                                    {l s='City' mod='jmarketplace'}
                                </label>
                                <input class="form-control" type="text" name="city" id="city" value="{$seller->city|escape:'html':'UTF-8'}" maxlength="128" />
                            </div>
                        {/if}
                        {if $show_description == 1}
                            <div class="form-group">
                                <label for="description">
                                    {l s='Description' mod='jmarketplace'}
                                </label>
                                <textarea name="description" id="description" cols="40" rows="7">{$seller->description} {*This is HTML content*}</textarea>
                            </div>
                        {/if}
                        {if $show_meta_title == 1}
                            <div class="form-group">
                                <label for="meta_title">
                                    {l s='Meta title' mod='jmarketplace'}
                                </label>
                                <input class="form-control" type="text" name="meta_title" id="meta_title" value="{$seller->meta_title|escape:'html':'UTF-8'}" maxlength="70" />
                            </div>
                        {/if}
                        {if $show_meta_description == 1}
                            <div class="form-group">
                                <label for="meta_description">
                                    {l s='Meta description' mod='jmarketplace'}
                                </label>
                                <input class="form-control" type="text" name="meta_description" id="meta_description" value="{$seller->meta_description|escape:'html':'UTF-8'}" maxlength="160" />
                            </div>
                        {/if}
                        {if $show_meta_keywords == 1}
                            <div class="form-group">
                                <label for="meta_keywords">
                                    {l s='Meta keywords' mod='jmarketplace'}
                                </label>
                                <input class="form-control" type="text" name="meta_keywords" id="meta_keywords" value="{$seller->meta_keywords|escape:'html':'UTF-8'}" maxlength="64" />
                            </div>
                        {/if}
                        {if $show_logo == 1}    
                            <div class="form-group">
                                <label for="fileUpload">
                                    {l s='Logo or photo' mod='jmarketplace'}
                                </label>
                                <input type="file" name="sellerImage" id="fileUpload" class="form-control" />
                                {if isset($photo)}
                                    <p>
                                        <img src="{$img_ps_dir|escape:'html':'UTF-8'}sellers/{$photo|escape:'html':'UTF-8'}" width="70" height="80" />
                                    </p>
                                {/if}
                            </div>
                        {/if}  
                        {hook h='displayMarketplaceFormAddSeller'}
                        
                        <div class="form-group">
                            {hook h='displayGDPRConsent' mod='psgdpr' id_module=$id_module}
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" name="submitEditSeller" class="btn btn-default button button-medium">
                                <span>
                                    {l s='Save' mod='jmarketplace'}
                                    <i class="icon-chevron-right right"></i>
                                </span>
                            </button>
                        </div>
                    </fieldset>
                </form>
            {/if}
        </div>
        <ul class="footer_links clearfix">
            <li>
                <a class="btn btn-default button" href="{$link->getModuleLink('jmarketplace', 'selleraccount', array(), true)|escape:'html':'UTF-8'}">
                    <i class="icon-chevron-left fa fa-chevron-left"></i>
                    <span>
                        {l s='Back to your seller account' mod='jmarketplace'}
                    </span>
                </a>
            </li>
            <li>
                <a class="btn btn-default button" href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}">
                    <i class="icon-chevron-left fa fa-chevron-left"></i>
                    <span>
                        {l s='Back to your account' mod='jmarketplace'}
                    </span>
                </a>
            </li>
        </ul>
    </div>
</div>   