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
    {if $seller_me}
        <a href="{$link->getModuleLink('jmarketplace', 'selleraccount', array(), true)|escape:'html':'UTF-8'}">
            {l s='Your seller account' mod='jmarketplace'}
        </a>
    {else}
        <a href="{$url_sellers|escape:'html':'UTF-8'}">
            {l s='Sellers' mod='jmarketplace'}
        </a>
    {/if}
    <span class="navigation-pipe">
        {$navigationPipe|escape:'html':'UTF-8'}
    </span>
    <span class="navigation_page">
        {$seller->name|escape:'html':'UTF-8'} 
    </span>
{/capture}

<div class="box">
    <h1 class="page-subheading">
        {$seller->name|escape:'html':'UTF-8'}
    </h1> 
    {hook h='displayMarketplaceHeaderProfile'}
    <div class="row">
        {if $show_logo}
            <div class="col-lg-3" style="margin-bottom: 15px;">
                <img class="img-responsive" src="{$photo|escape:'html':'UTF-8'}" width="240" height="auto" />
            </div>
        {/if}
        <div class="{if $show_logo}col-lg-9{else}col-lg-12{/if}">
            <div class="table-responsive">
                <table id="order-list" class="table table-bordered footab">
                    <tbody>
                        <tr>
                            <td>
                                {l s='Seller name' mod='jmarketplace'}
                            </td>
                            <td>
                                {$seller->name|escape:'html':'UTF-8'}
                            </td>
                        </tr>
                        {if $show_shop_name}
                            <tr>
                                <td>
                                    {l s='Seller shop' mod='jmarketplace'}
                                </td>
                                <td>
                                    {$seller->shop|escape:'html':'UTF-8'}
                                </td>
                            </tr>
                        {/if}
                        {if $show_cif}
                            <tr>
                                <td>
                                    {l s='CIF/NIF' mod='jmarketplace'}
                                </td>
                                <td>
                                    {$seller->cif|escape:'html':'UTF-8'}
                                </td>
                            </tr>
                        {/if}
                        {if $show_language}
                            <tr>
                                <td>
                                    {l s='Language' mod='jmarketplace'}
                                </td>
                                <td>
                                    {$seller_language|escape:'html':'UTF-8'}
                                </td>
                            </tr>
                        {/if}
                        {if $show_seller_rating}
                            <tr>
                                <td>
                                    {l s='Average rating' mod='jmarketplace'}
                                </td>
                                <td>
                                    <div class="average_rating buttons_bottom_block">
                                        <a href="{$url_seller_comments|escape:'html':'UTF-8'}" title="{l s='View comments about' mod='jmarketplace'} {$seller->name|escape:'html':'UTF-8'}">
                                            {section name="i" start=0 loop=5 step=1}
                                                {if $averageMiddle le $smarty.section.i.index}
                                                    <div class="star"></div>
                                                {else}
                                                    <div class="star star_on"></div>
                                                {/if}
                                            {/section}
                                            (<span id="average_total">{$averageTotal|intval}</span>)
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        {/if}
                        {if $show_seller_favorite}
                            <tr>
                                <td>
                                    {l s='Followers' mod='jmarketplace'}
                                </td>
                                <td>
                                    {$followers|intval} {l s='followers.' mod='jmarketplace'} 
                                </td>
                            </tr>
                        {/if}
                        {if $show_email}
                            <tr>
                                <td>
                                    {l s='Seller email' mod='jmarketplace'}
                                </td>
                                <td>
                                    <a href="mailto:{$seller->email|escape:'html':'UTF-8'}">
                                        {$seller->email|escape:'html':'UTF-8'}
                                    </a>
                                </td>
                            </tr>
                        {/if}
                        {if $show_phone}
                            <tr>
                                <td>
                                    {l s='Seller phone' mod='jmarketplace'}
                                </td>
                                <td>
                                    <a href="tel:{$seller->phone|escape:'html':'UTF-8'}">
                                        {$seller->phone|escape:'html':'UTF-8'}
                                    </a>
                                </td>
                            </tr>
                        {/if}
                        {if $show_fax}
                            <tr>
                                <td>
                                    {l s='Seller fax' mod='jmarketplace'}
                                </td>
                                <td>
                                    {$seller->fax|escape:'html':'UTF-8'}
                                </td>
                            </tr>
                        {/if}
                        {if $show_address}
                            <tr>
                                <td>
                                    {l s='Seller address' mod='jmarketplace'}
                                </td>
                                <td>
                                    {$seller->address|escape:'html':'UTF-8'}
                                </td>
                            </tr>
                        {/if}
                        {if $show_country}
                            <tr>
                                <td>
                                    {l s='Seller country' mod='jmarketplace'}
                                </td>
                                <td>
                                    {$seller->country|escape:'html':'UTF-8'}
                                </td>
                            </tr>
                        {/if}
                        {if $show_state}
                            <tr>
                                <td>
                                    {l s='Seller state' mod='jmarketplace'}
                                </td>
                                <td>
                                    {$seller->state|escape:'html':'UTF-8'}
                                </td>
                            </tr>
                        {/if}
                        {if $show_postcode}
                            <tr>
                                <td>
                                    {l s='Post code' mod='jmarketplace'}
                                </td>
                                <td>
                                    {$seller->postcode|escape:'html':'UTF-8'}
                                </td>
                            </tr>
                        {/if}
                        {if $show_city}
                            <tr>
                                <td>
                                    {l s='Seller city' mod='jmarketplace'}
                                </td>
                                <td>
                                    {$seller->city|escape:'html':'UTF-8'}
                                </td>
                            </tr>
                        {/if}
                        {if $show_description}
                            <tr>
                                <td>
                                    {l s='Seller description' mod='jmarketplace'}
                                </td>
                                <td>
                                    {$seller->description nofilter} {*This is HTML content*}
                                </td>
                            </tr>
                        {/if}
                        {hook h='displayMarketplaceTableProfile'}
                    </tbody>
                </table>
            </div>
            <p class="seller_profile_buttons">
                <a class="btn btn-xs" href="{$seller_products_link|escape:'html':'UTF-8'}">
                    <i class="icon-list fa fa-list"></i> 
                    {l s='View products of' mod='jmarketplace'} 
                    {$seller->name|escape:'html':'UTF-8'}
                </a>
                {if $show_edit_seller_account == 1 AND $seller_me}
                    <a class="btn btn-secondary btn-xs" title="{l s='Edit your seller account' mod='jmarketplace'}" href="{$link->getModuleLink('jmarketplace', 'editseller')|escape:'html':'UTF-8'}">
                        <i class="icon-pencil fa fa-pencil"></i> 
                        {l s='Edit your seller account' mod='jmarketplace'}
                      </a> 
                {/if}
                {if $show_contact}
                    <a class="btn btn-secondary btn-xs" title="{l s='Contact with' mod='jmarketplace'} {$seller->name|escape:'html':'UTF-8'}" href="{$url_contact_seller|escape:'html':'UTF-8'}">
                        <i class="icon-comment fa fa-comment"></i> 
                        {l s='Conctact' mod='jmarketplace'}
                    </a> 
                {/if}
                {if $show_seller_favorite AND !$seller_me}
                    <a class="btn btn-secondary btn-xs" title="{l s='Add to favorite seller' mod='jmarketplace'}" href="{$url_favorite_seller|escape:'html':'UTF-8'}">
                        <i class="icon-heart fa fa-heart"></i> 
                        {l s='Add to favorite seller' mod='jmarketplace'}
                    </a> 
                {/if}
            </p>
        </div>
    </div>
</div>

{if $show_new_products}                
    {if isset($products) && $products}
        <div class="box">
            <h1 class="page-heading">
                {l s='News of' mod='jmarketplace'} {$seller->name|escape:'html':'UTF-8'}
            </h1>
            {include file="./product-list.tpl" class='tab-pane' id='jmarketplace'}
        </div>
    {/if}
{/if}

{hook h='displayMarketplaceFooterProfile'}

{if $seller_me}
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
{else}
    <ul class="footer_links clearfix">
        <li>
            <a class="btn btn-default button button-small" href="javascript: history.go(-1)">
                <span>
                    <i class="icon-chevron-left fa fa-chevron-left"></i> 
                    {l s='Go back' mod='jmarketplace'}
                </span>
            </a>
        </li>
        <li>
            <a class="btn btn-default button button-small btn-secondary" href="{$base_dir|escape:'html':'UTF-8'}">
                <span>
                    <i class="icon-chevron-left fa fa-chevron-left"></i> 
                    {l s='Home' mod='jmarketplace'}
                </span>
            </a>
        </li>
    </ul>
{/if}                   