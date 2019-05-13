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

{capture name=path}
    <a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}">
        {l s='My account' mod='jmarketplace'}
    </a>
    <span class="navigation-pipe">
        {$navigationPipe|escape:'html':'UTF-8'}
    </span>
    <span class="navigation_page">
        {l s='Create your seller account' mod='jmarketplace'}
    </span>
{/capture}

<div class="box">
    <h1 class="page-subheading">
        {l s='Create your seller account' mod='jmarketplace'}
    </h1>
    {if isset($confirmation) && $confirmation}
        <div class="row">
            <div class="col-lg-12">
                {if $moderate}
                    <div class="alert alert-success">
                        {l s='Your seller account has been successfully created. It is pending approval.' mod='jmarketplace'}
                    </div>
                {else}
                    <div class="alert alert-success">
                        {l s='Your seller account has been successfully created.' mod='jmarketplace'}
                    </div>
                {/if}
            </div>
        </div>
    {else}
        {if isset($errors) && $errors}
            {if isset($errors) && $errors}
                {include file="./errors.tpl"}
            {/if}
        {/if}
        <p class="info-title">
            {l s='Start selling your products.' mod='jmarketplace'}
        </p>
        <form action="{$link->getModuleLink('jmarketplace', 'addseller', array(), true)|escape:'html':'UTF-8'}" method="post" class="std" enctype="multipart/form-data">
            <fieldset>
                <div class="required form-group">
                    <label for="name" class="required">
                        {l s='Seller name' mod='jmarketplace'}
                    </label>
                    <input class="is_required validate form-control" type="text" id="name" name="name" value="{$customer_name|escape:'html':'UTF-8'}" maxlength="64" required />
                </div>
                {if $show_shop_name == 1}
                    <div class="form-group">
                        <label for="shop">
                            {l s='Shop name' mod='jmarketplace'}
                        </label>
                        <input class="form-control" type="text" name="shop" id="shop" maxlength="64"{if isset($seller_shop)} value="{$seller_shop|escape:'html':'UTF-8'}"{/if} />
                    </div>
                {/if}
                {if $show_cif == 1}
                    <div class="required form-group">
                        <label for="cif">
                            {l s='CIF/NIF' mod='jmarketplace'}
                        </label>
                        <input class="form-control" type="text" name="cif" id="cif" maxlength="16"{if isset($cif)} value="{$cif|escape:'html':'UTF-8'}"{/if} />
                    </div>
                {/if}
                <div class="required form-group">
                    <label for="email" class="required">
                        {l s='Seller email' mod='jmarketplace'}
                    </label>
                    <input class="is_required validate form-control" type="text" id="email" name="email" value="{$customer_email|escape:'html':'UTF-8'}" maxlength="64" required />
                </div>
                {if $show_language == 1}
                    <div class="form-group">
                        <label for="seller_lang">
                            {l s='Language' mod='jmarketplace'}
                        </label>
                        <select name="id_lang">
                            {foreach from=$languages item=language}
                                <option value="{$language.id_lang|intval}"{if $id_lang == $language.id_lang} selected="selected"{/if}>
                                    {$language.name|escape:'html':'UTF-8'}
                                </option>
                            {/foreach}
                        </select>
                    </div>
                {/if}
                {if $show_phone == 1}
                    <div class="required form-group">
                        <label for="phone">
                            {l s='Phone' mod='jmarketplace'}
                        </label>
                        <input class="form-control" type="text" name="phone" id="phone" maxlength="32"{if isset($phone)} value="{$phone|escape:'html':'UTF-8'}"{/if} />
                    </div>
                {/if}
                {if $show_fax == 1}
                    <div class="form-group">
                        <label for="fax">
                            {l s='Fax' mod='jmarketplace'}
                        </label>
                        <input class="form-control" type="text" name="fax" id="fax" maxlength="32"{if isset($fax)} value="{$fax|escape:'html':'UTF-8'}"{/if} />
                    </div>
                {/if}
                {if $show_address == 1}
                    <div class="form-group">
                        <label for="address">
                            {l s='Address' mod='jmarketplace'}
                        </label>
                        <input class="form-control" type="text" name="address" id="address" maxlength="128"{if isset($address)} value="{$address|escape:'html':'UTF-8'}"{/if} />
                    </div>
                {/if}
                {if $show_country == 1}
                    <div class="form-group">
                        <label>
                            {l s='Country' mod='jmarketplace'}
                        </label>
                        <select name="country">
                            <option value="">{l s='-- Choose --' mod='jmarketplace'}</option>
                            {foreach from=$countries item=country}
                                <option value="{$country.name|escape:'html':'UTF-8'}"{if isset($country_name) AND $country_name == $country.name} selected="selected"{/if}>
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
                        <input class="form-control" type="text" name="state" id="state" maxlength="128"{if isset($state)} value="{$state|escape:'html':'UTF-8'}"{/if} />
                    </div>
                {/if}
                {if $show_postcode == 1}
                    <div class="form-group">
                        <label for="postcode">
                            {l s='Postal Code' mod='jmarketplace'}
                        </label>
                        <input class="form-control" type="text" name="postcode" id="postcode" maxlength="12"{if isset($postcode)} value="{$postcode|escape:'html':'UTF-8'}"{/if} />
                    </div>
                {/if}
                {if $show_city == 1}
                    <div class="form-group">
                        <label for="city">
                            {l s='City' mod='jmarketplace'}
                        </label>
                        <input class="form-control" type="text" name="city" id="city" maxlength="128"{if isset($city)} value="{$city|escape:'html':'UTF-8'}"{/if} />
                    </div>
                {/if}
                {if $show_description == 1}
                    <div class="form-group">
                        <label for="description">
                            {l s='Description' mod='jmarketplace'}
                        </label>
                        <textarea name="description" id="description" cols="40" rows="7">{if isset($description)}{$description nofilter}{/if}</textarea>
                    </div>
                {/if}
                {if $show_logo == 1}    
                    <div class="form-group">
                        <label for="fileUpload">
                            {l s='Logo or photo' mod='jmarketplace'}
                        </label>
                        <input type="file" name="sellerImage" id="fileUpload" class="form-control" />
                    </div>
                {/if}    
                
                {hook h='displayMarketplaceFormAddSeller'}
                
                <div class="form-group">
                    {hook h='displayGDPRConsent' mod='psgdpr' id_module=$id_module}
                </div>
                
                {if $show_terms == 1}
                    <div class="form-group">
                        <label for="conditions">
                            {l s='Terms of service' mod='jmarketplace'}
                        </label>
                        <p class="checkbox">
                            <input type="checkbox" value="1" id="conditions" name="conditions">
                            <label for="conditions">
                                {l s='I agree to the terms of service and will adhere to them unconditionally.' mod='jmarketplace'}
                            </label>
                            <a rel="nofollow" class="iframe" href="{$cms_link|escape:'html':'UTF-8'}">
                                {l s='Read' mod='jmarketplace'} 
                                {$cms_name|escape:'html':'UTF-8'}
                            </a>
                        </p>
                    </div>
                {/if}
                        
                <div class="form-group">
                    <button type="submit" name="submitAddSeller" class="btn btn-default button button-medium">
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
        <a class="btn btn-default button button-small" href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}">
            <span>
                <i class="icon-chevron-left"></i> 
                {l s='Back to your account' mod='jmarketplace'}
            </span>
        </a>
    </li>
</ul>   