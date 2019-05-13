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
        {l s='Export and import products' mod='jmarketplace'}
    </span>
{/capture}

{if isset($submitExport) && $submitExport}
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-success">
                {$num_products|intval} {l s='products have been exported.' mod='jmarketplace'} 
                <a href="{$modules_dir|escape:'html':'UTF-8'}jmarketplace/export/{$file|escape:'html':'UTF-8'}">
                    {l s='Download' mod='jmarketplace'}
                </a>
            </div>   
        </div>
    </div>
{/if}

{if isset($submitImport) && $submitImport}
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-success">
                {if $added > 0}
                    {$added|intval} 
                    {l s='products have been imported.' mod='jmarketplace'}
                    <br/> 
                {/if}
                {if $updated > 0}
                    {$updated|intval} 
                    {l s='products have been updated.' mod='jmarketplace'}
                    <br/>
                {/if}
                {if $invalid > 0}
                    <span style="color:red;">
                        {$invalid|intval} 
                        {l s='products have not been imported.' mod='jmarketplace'}
                    </span>
                    <br/>
                {/if}
                <p>
                    <a href="{$modules_dir|escape:'html':'UTF-8'}jmarketplace/log/{$id_seller|intval}/log.txt" target="_blank">
                        {l s='View log' mod='jmarketplace'}
                    </a>
                </p>
            </div> 
        </div>
    </div>
{/if}

{if isset($errors) && $errors}
    {include file="./errors.tpl"}
{/if}

{if $submitNextStep == 0}
    <div class="row">
        <div class="column col-xs-12 col-sm-12 col-md-3"{if $show_menu_options == 0} style="display:none;"{/if}>
            {hook h='displayMarketplaceWidget'}
        </div>
    
        <div class="col-xs-12{if $show_menu_options == 1} col-sm-12 col-md-6{else} col-sm-12 col-md-9{/if}">
            <div class="box">
                <h1 class="page-subheading">
                    {l s='Export products' mod='jmarketplace'}
                </h1>
                <p>
                    {l s='You can export your products in csv file.' mod='jmarketplace'}
                </p>
                <br/>

                <form action="{$link->getModuleLink('jmarketplace', 'csvproducts', array(), true)|escape:'html':'UTF-8'}" method="post" class="std">       
                    <div class="form-group">
                        <button type="submit" name="submitExport" class="btn btn-default button button-medium">
                            <span>
                                {l s='Export' mod='jmarketplace'} 
                                <i class="icon-chevron-right right"></i>
                            </span>
                        </button>
                    </div>
                </form>
                <hr>
                <h1 class="page-subheading">
                    {l s='Import products' mod='jmarketplace'}
                </h1>
                <p>
                    {l s='You can import their products in csv file. The csv file must have a specific format.' mod='jmarketplace'}
                </p>
                <br/>

                <form action="{$link->getModuleLink('jmarketplace', 'csvproducts', array(), true)|escape:'html':'UTF-8'}" method="post" class="std" enctype="multipart/form-data">   
                    <div class="form-group">
                        <label for="product_name_lang">
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
                        
                    <div class="form-group">
                        <label for="truncate">
                            {l s='Delete all products before import' mod='jmarketplace'}
                        </label>
                        <select name="truncate">
                            <option value="0" selected="selected">
                                {l s='No' mod='jmarketplace'}
                            </option>
                            <option value="1">
                                {l s='Yes' mod='jmarketplace'}
                            </option>
                        </select>
                    </div> 
                        
                    <div class="form-group">
                        <label for="match_ref">
                            {l s='Use product reference as key' mod='jmarketplace'}
                        </label>
                        <select name="match_ref">
                            <option value="0" selected="selected">
                                {l s='No' mod='jmarketplace'}
                            </option>
                            <option value="1">
                                {l s='Yes' mod='jmarketplace'}
                            </option>
                        </select>
                    </div> 

                    <div class="form-group">
                        <label for="fileUpload">
                            {l s='File CSV' mod='jmarketplace'}
                        </label>
                        <input type="file" name="file" id="fileUpload" class="form-control" />
                        <a href="{$url_example|escape:'html':'UTF-8'}">
                            {l s='View example' mod='jmarketplace'}
                        </a>
                    </div>   

                    <div class="form-group">
                        <button type="submit" name="submitNextStep" class="btn btn-default button button-medium">
                            <span>
                                {l s='Import' mod='jmarketplace'}
                                <i class="icon-chevron-right right"></i>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3">
            <div class="boxto">
                <h4>
                    {l s='Available fields' mod='jmarketplace'}
                </h4>
                {foreach from=$available_fields item=field}
                    {$field|escape:'html':'UTF-8'}<br/>
                {/foreach}
            </div>
        </div>
    </div>
{else}
    <div class="box">
        <h1 class="page-subheading">
            {l s='Identification data' mod='jmarketplace'}
        </h1>
        <div class="alert alert-info">
            {l s='Please match each column of your source CSV file to one of the destination columns.' mod='jmarketplace'}
        </div>

        <form action="{$link->getModuleLink('jmarketplace', 'csvproducts', array(), true)|escape:'html':'UTF-8'}" method="post" class="std">
            <input type="hidden" name="id_lang" value="{$id_lang|intval}" />
            <input type="hidden" name="truncate" value="{$truncate|intval}" />
            <input type="hidden" name="match_ref" value="{$match_ref|intval}" />
            <input type="hidden" name="filename" value="{$filename|escape:'html':'UTF-8'}" />
            <div class="table-responsive scroll_horizontal">
		<table class="table table-bordered">
                    <thead>
                        <tr>
                            {$header nofilter} {*This html content*}
                        </tr>
                    </thead>
                    <tbody>
                        {$first_rows nofilter} {*This html content*}
                    </tbody>
                </table>
            </div>
            
            <div class="form-group" style="margin-top: 15px;">
                <button type="submit" name="submitImport" class="btn btn-default button button-medium">
                    <span>
                        {l s='Import' mod='jmarketplace'}
                        <i class="icon-chevron-right right"></i>
                    </span>
                </button>
            </div>
        </form>
    </div>    
{/if}      
    
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