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

{extends file="helpers/view/view.tpl"}

{block name="override_tpl"}
    
<div id="sellerinformation" class="panel">
    <h3>
        {l s='Seller information' mod='jmarketplace'}
    </h3>
    <div class="row">
        <div class="col-md-2">
            {if $show_logo}
                <img class="img-responsive" style="border:1px solid #eee;" src="{$photo|escape:'html':'UTF-8'}" width="100%" />
            {/if}
        </div>
        <div class="col-md-10">
            <table class="table tableDnD">
                <thead>
                    <tr>
                        <th class="col-md-2"></th>
                        <th class="col-md-10"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong>
                                {l s='Seller name' mod='jmarketplace'}:
                            </strong>
                        </td>
                        <td>
                            <a href="{$url_seller_profile|escape:'html':'UTF-8'}" target="_blank">
                                {$seller->name|escape:'html':'UTF-8'}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>
                                {l s='Seller email' mod='jmarketplace'}:
                            </strong>
                        </td>
                        <td>
                            {$seller->email|escape:'html':'UTF-8'}
                        </td>
                    </tr>
                    {if $show_shop_name}
                        <tr>
                            <td>
                                <strong>
                                    {l s='Seller shop' mod='jmarketplace'}:
                                </strong>
                            </td>
                            <td>
                                {$seller->shop|escape:'html':'UTF-8'}
                            </td>
                        </tr>
                    {/if}
                    <tr>
                        <td>
                            <strong>
                                {l s='Seller commission' mod='jmarketplace'}:
                            </strong>
                        </td>
                        <td>
                            <a href="{$url_seller_commission|escape:'html':'UTF-8'}" title="{l s='Edit seller commission' mod='jmarketplace'}">
                                {$seller_commission|floatval}%
                            </a>    
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>
                                {l s='Customer name' mod='jmarketplace'}:
                            </strong>
                        </td>
                        <td>
                            <a target="_blank" href="{$url_customer|escape:'html':'UTF-8'}">
                                {$customer->firstname|escape:'html':'UTF-8'} 
                                {$customer->lastname|escape:'html':'UTF-8'}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>
                                {l s='Customer email' mod='jmarketplace'}:
                            </strong>
                        </td>
                        <td>
                            {$customer->email|escape:'html':'UTF-8'}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>
                                {l s='CIF/NIF' mod='jmarketplace'}:
                            </strong>
                        </td>
                        <td>
                            {$seller->cif|escape:'html':'UTF-8'}
                        </td>
                    </tr>
                    {if $show_language}
                        <tr>
                            <td>
                                <strong>
                                    {l s='Language' mod='jmarketplace'}:
                                </strong>
                            </td>
                            <td>
                                {$seller_lang->name|escape:'html':'UTF-8'}
                            </td>
                        </tr>
                    {/if}
                    {if $show_seller_rating}
                        <tr>
                            <td>
                                <strong>
                                    {l s='Average rating' mod='jmarketplace'}:
                                </strong>
                            </td>
                            <td>
                                <a target="_blank" href="{$url_seller_comments|escape:'html':'UTF-8'}" title="{l s='View comments about' mod='jmarketplace'} {$seller->name|escape:'html':'UTF-8'}">
                                    {$averageMiddle|intval}/5 {l s='of' mod='jmarketplace'} 
                                    {$averageTotal|intval} 
                                    {l s='ratings' mod='jmarketplace'}
                                </a>
                            </td>
                        </tr>
                    {/if}
                    <tr>
                        <td>
                            <strong>
                                {l s='Date add' mod='jmarketplace'}:
                            </strong>
                        </td>
                        <td>
                            {$seller->date_add|escape:'html':'UTF-8'}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>
                                {l s='Date update' mod='jmarketplace'}:
                            </strong>
                        </td>
                        <td>
                            {$seller->date_upd|escape:'html':'UTF-8'}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>
                                {l s='Status' mod='jmarketplace'}:
                            </strong>
                        </td>
                        <td>
                            {if $seller->active == 1}
                                {l s='Active' mod='jmarketplace'}
                            {else}
                                {l s='No active' mod='jmarketplace'}
                            {/if}
                        </td>
                    </tr>
                    {if $show_seller_favorite}
                        <tr>
                            <td>
                                <strong>
                                    {l s='Followers' mod='jmarketplace'}:
                                </strong>
                            </td>
                            <td>
                                {$followers|intval} 
                            </td>
                        </tr>
                    {/if}
                    {if $show_phone}
                        <tr>
                            <td>
                                <strong>
                                    {l s='Phone' mod='jmarketplace'}:
                                </strong>
                            </td>
                            <td>
                                {$seller->phone|escape:'html':'UTF-8'}
                            </td>
                        </tr>
                    {/if}
                    {if $show_fax}
                        <tr>
                            <td>
                                <strong>
                                    {l s='Fax' mod='jmarketplace'}:
                                </strong>
                            </td>
                            <td>
                                {$seller->fax|escape:'html':'UTF-8'}
                            </td>
                        </tr>
                    {/if}
                    {if $show_address}
                        <tr>
                            <td>
                                <strong>
                                    {l s='Address' mod='jmarketplace'}:
                                </strong>
                            </td>
                            <td>
                                {$seller->address|escape:'html':'UTF-8'}
                            </td>
                        </tr>
                    {/if}
                    {if $show_country}
                        <tr>
                            <td>
                                <strong>
                                    {l s='Country' mod='jmarketplace'}:
                                </strong>
                            </td>
                            <td>
                                {$seller->country|escape:'html':'UTF-8'}
                            </td>
                        </tr>
                    {/if}
                    {if $show_state}
                        <tr>
                            <td>
                                <strong>
                                    {l s='State' mod='jmarketplace'}:
                                </strong>
                            </td>
                            <td>
                                {$seller->state|escape:'html':'UTF-8'}
                            </td>
                        </tr>
                    {/if}
                    {if $show_postcode}
                        <tr>
                            <td>
                                <strong>
                                    {l s='Post code' mod='jmarketplace'}:
                                </strong>
                            </td>
                            <td>
                                {$seller->postcode|escape:'html':'UTF-8'}
                            </td>
                        </tr>
                    {/if}
                    {if $show_city}
                        <tr>
                            <td>
                                <strong>
                                    {l s='City' mod='jmarketplace'}:
                                </strong>
                            </td>
                            <td>
                                {$seller->city|escape:'html':'UTF-8'}
                            </td>
                        </tr>
                    {/if}
                    {if $show_description}
                        <tr>
                            <td>
                                <strong>
                                    {l s='Description' mod='jmarketplace'}:
                                </strong>
                            </td>
                            <td>
                                {$seller->description nofilter} {*This is HTML content*}
                            </td>
                        </tr>
                    {/if}
                    {if $show_meta_title}
                        <tr>
                            <td>
                                <strong>
                                    {l s='Meta title' mod='jmarketplace'}:
                                </strong>
                            </td>
                            <td>
                                {$seller->meta_title|escape:'html':'UTF-8'}
                            </td>
                        </tr>
                    {/if}
                    {if $show_meta_description}
                        <tr>
                            <td>
                                <strong>
                                    {l s='Meta description' mod='jmarketplace'}:
                                </strong>
                            </td>
                            <td>
                                {$seller->meta_description|escape:'html':'UTF-8'}
                            </td>
                        </tr>
                    {/if}
                    {if $show_meta_keywords}
                        <tr>
                            <td>
                                <strong>
                                    {l s='Meta keywords' mod='jmarketplace'}:
                                </strong>
                            </td>
                            <td>
                                {$seller->meta_keywords|escape:'html':'UTF-8'}
                            </td>
                        </tr>
                    {/if}
                    {hook h='displayMarketplaceAdminSeller'}
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel-footer">  
        <a class="btn btn-default" href="index.php?controller=AdminSellers&amp;id_seller={$seller->id|intval}&amp;statusseller&amp;token={$token|escape:'html':'UTF-8'}">
            {if $seller->active == 1}
                <i class="icon-remove"></i> 
                {l s='Desactivate' mod='jmarketplace'}
            {else}
                <i class="icon-check fa fa-check"></i> 
                {l s='Activate' mod='jmarketplace'}
            {/if}
        </a>
        {if $seller->active == 0}
            <a class="btn btn-default reasons_for_rejection" href="#rejection_form">
                <i class="icon-close fa fa-close"></i> 
                {l s='Reject' mod='jmarketplace'}
            </a>
            <div id="rejection_form" class="panel" style="display:none;">
                <h3>
                    {l s='Reasons for rejected'  mod='jmarketplace'}
                </h3>
                <form action="index.php?controller=AdminSellers&amp;id_seller={$seller->id|intval}&amp;rejected&amp;token={$token|escape:'html':'UTF-8'}" method="post">
                    <div class="form-group">
                        <label for="reasons">
                            {l s='Reasons' mod='jmarketplace'}
                        </label>
                        <textarea id="reasons" name="reasons" class="form-control" cols="40" rows="4"></textarea>   
                    </div>

                    <div class="form-group">
                        <button type="submit" name="submitSellerRejection" class="btn btn-default button button-medium">
                            <i class="icon-close fa fa-close"></i> 
                            {l s='Reject' mod='jmarketplace'}
                        </button>
                    </div>
                </form>
            </div>
        {/if}
        <a class="btn btn-default" href="index.php?controller=AdminSellers&amp;id_seller={$seller->id|intval}&amp;updateseller&amp;token={$token|escape:'html':'UTF-8'}">
            <i class="icon-edit fa fa-edit"></i> 
            {l s='Edit' mod='jmarketplace'}
        </a>
        <a class="btn btn-default" href="{$url_seller_profile|escape:'html':'UTF-8'}" target="_blank">
            <i class="icon-user fa fa-user"></i> 
            {l s='View seller profile' mod='jmarketplace'}
        </a>
        <a class="btn btn-default" href="{$url_seller_products|escape:'html':'UTF-8'}" target="_blank">
            <i class="icon-search fa fa-search"></i> 
            {l s='View all products' mod='jmarketplace'}
        </a>
        <a class="btn btn-default" href="index.php?controller=AdminSellers&amp;token={$token|escape:'html':'UTF-8'}">
            <i class="icon-close fa fa-close"></i> 
            {l s='Cancel' mod='jmarketplace'}
        </a>
    </div>
</div>
    
<div class="panel">
    <h3>
        {l s='Seller payment'  mod='jmarketplace'}
    </h3>
    {foreach from=$payments item=payment name=sellerpayments}
        <div class="form-group{if $payment.active == 0} hidden{/if}">
            {if $payment.payment == 'paypal'}
                <p>
                    {l s='This seller want receive your commissions with Paypal' mod='jmarketplace'}
                </p>
                <label for="{$payment.payment|escape:'html':'UTF-8'}">
                    {l s='Paypal account (Email)' mod='jmarketplace'}
                </label>
                <input class="form-control" type="text" name="{$payment.payment|escape:'html':'UTF-8'}" id="{$payment.payment|escape:'html':'UTF-8'}" value="{$payment.account|escape:'html':'UTF-8'}" readonly="readonly" />
            {else if $payment.payment == 'bankwire'}
                <p>
                    {l s='This seller want receive your commissions with Bankwire' mod='jmarketplace'}
                </p>
                <label for="{$payment.payment|escape:'html':'UTF-8'}">
                    {l s='Number account' mod='jmarketplace'}
                </label>
                <textarea id="{$payment.payment|escape:'html':'UTF-8'}" name="{$payment.payment|escape:'html':'UTF-8'}" class="form-control" cols="40" rows="4" readonly="readonly">
                    {$payment.account|escape:'html':'UTF-8'}
                </textarea>   
            {/if}
        </div>
    {/foreach}
</div>
<div class="panel">
    <h3>
        {l s='Products'  mod='jmarketplace'} 
        {if $products && count($products)}
            <span class="badge">
                {count($products)|intval}
            </span>
        {/if}
    </h3>
    {if $products && count($products)}
        <table id="order-list" class="table table-bordered footab">
            <thead>
                <tr>
                    <th class="first_item">
                        {l s='Image'  mod='jmarketplace'}
                    </th>
                    <th class="item">
                        {l s='Product name' mod='jmarketplace'}
                    </th>
                    <th class="item">
                        {l s='Date add' mod='jmarketplace'}
                    </th>
                    <th class="item">
                        {l s='Date update' mod='jmarketplace'}
                    </th>
                    <th class="item">
                        {l s='Status' mod='jmarketplace'}
                    </th>
                    <th class="item">
                        &nbsp;
                    </th>
                    <th class="last_item">
                        &nbsp;
                    </th>
                </tr>
            </thead>
            <tbody>
            {foreach from=$products item=product name=sellerproducts}
                <tr>
                    <td class="first_item">
                        {if $product.id_image}
                            <img itemprop="image" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'small_default')|escape:'html':'UTF-8'}" />
                        {else}
                            <img itemprop="image" src="{$img_prod_dir|escape:'html':'UTF-8'}{$lang_iso|escape:'html':'UTF-8'}-default-small_default.jpg" />
                        {/if}
                    </td>
                    <td class="item">
                        <a href="{$link->getProductLink($product.id_product)|escape:'html':'UTF-8'}">
                            {$product.name|escape:'html':'UTF-8'}
                        </a>
                    </td>
                    <td class="item">
                        {$product.date_add|escape:'html':'UTF-8'}
                    </td>
                    <td class="item">
                        {$product.date_upd|escape:'html':'UTF-8'}
                    </td>
                    <td class="item">
                        {if $product.active == 1}
                            {l s='Active' mod='jmarketplace'}
                        {else}
                            {l s='Pending approval' mod='jmarketplace'}
                        {/if}
                    </td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    {else}
        <p class="alert alert-info">
            {l s='There are not products.' mod='jmarketplace'}
        </p>
    {/if}
</div>
{/block}