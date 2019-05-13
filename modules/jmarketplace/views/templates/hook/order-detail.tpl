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

<div class="box">
    <h3>{l s='Seller information' mod='jmarketplace'}</h3>
    <div class="table-responsive">
        <table id="order-products" class="table table-bordered">
            <thead class="thead-default">
                <tr>
                    <th>
                        {l s='Product' mod='jmarketplace'}
                    </th>
                    <th>
                        {l s='Seller' mod='jmarketplace'}
                    </th>
                    <th>
                        {l s='Quantity' mod='jmarketplace'}
                    </th>
                    <th>
                        {l s='Unit price' mod='jmarketplace'}
                    </th>
                    <th>
                        {l s='Total price' mod='jmarketplace'}
                    </th>
                </tr>
            </thead>
            <tbody>
            {foreach from=$products item=product}
                <tr>
                    <td>
                        <strong>
                            {$product.product_name|escape:'html':'UTF-8'}
                        </strong>
                        <br/>
                        {if $product.product_reference}
                            {l s='Reference' mod='jmarketplace'}: 
                            {$product.product_reference|escape:'html':'UTF-8'}
                            <br/>
                        {/if}
                    </td>
                    <td>
                        <ul>
                            {if $product.id_seller != 0}
                                <li>
                                    {$product.seller_name|escape:'html':'UTF-8'}
                                </li>
                                {if $show_seller_rating}
                                    <li class="average_rating clearfix">
                                        <a href="{$product.url_seller_comments|escape:'html':'UTF-8'}" title="{l s='Rate this purcase' mod='jmarketplace'}">
                                            {section name="i" start=0 loop=5 step=1}
                                                {if $product.seller_averageMiddle le $smarty.section.i.index}
                                                    <div class="star"></div>
                                                {else}
                                                    <div class="star star_on"></div>
                                                {/if}
                                            {/section}({$product.seller_averageTotal|intval})
                                        </a>
                                        <br/>
                                    </li>
                                {/if} 
                                {if $show_seller_profile}
                                    <li class="link_seller_profile"> 
                                        <a title="{l s='View seller profile' mod='jmarketplace'}" href="{$product.seller_link|escape:'html':'UTF-8'}">
                                            <i class="icon-user fa fa-user"></i> 
                                            {l s='View seller profile' mod='jmarketplace'}
                                        </a>
                                    </li>
                                {/if}
                                {if $show_contact_seller}
                                    <li class="link_contact_seller">
                                        <a title="{l s='Contact seller' mod='jmarketplace'}" href="{$product.url_contact_seller|escape:'html':'UTF-8'}">
                                            <i class="icon-comments fa fa-comment"></i> 
                                            {l s='Contact seller' mod='jmarketplace'}
                                        </a>
                                    </li>
                                {/if}
                                {if $show_seller_favorite}
                                    <li class="link_seller_favorite">
                                        <a title="{l s='Add to favorite seller' mod='jmarketplace'}" href="{$product.url_favorite_seller|escape:'html':'UTF-8'}">
                                            <i class="icon-heart fa fa-heart"></i> 
                                            {l s='Add to favorite seller' mod='jmarketplace'}
                                        </a>
                                    </li>
                                {/if}
                                <li class="link_seller_products">
                                    <a title="{l s='View more products of this seller' mod='jmarketplace'}" href="{$product.url_seller_products|escape:'html':'UTF-8'}">
                                        <i class="icon-list fa fa-list"></i> 
                                        {l s='View more products of this seller' mod='jmarketplace'}
                                    </a>
                                </li>
                            {else}
                                <li>
                                    {$product.shop_name|escape:'html':'UTF-8'}
                                </li>
                            {/if}
                        </ul>
                    </td>
                    <td>
                        {$product.product_quantity|intval}
                    </td>
                    <td class="text-xsright">
                        {$product.product_price|escape:'html':'UTF-8'}
                    </td>
                    <td class="text-xsright">
                        {$product.total_price|escape:'html':'UTF-8'}
                    </td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>