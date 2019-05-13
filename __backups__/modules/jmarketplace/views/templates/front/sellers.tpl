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
    <span class="navigation_page">
        {l s='Sellers' mod='jmarketplace'}
    </span>
{/capture}

<div class="box">
    <h1 class="page-heading">
        {l s='Sellers in Marketplace' mod='jmarketplace'}
    </h1>
    {if isset($sellers) && count($sellers) > 0}
        <ul class="seller_list grid row">
        {foreach from=$sellers item=seller name=jmarketplaceSellers}
            <li class="block_seller col-xs-12 col-sm-6 col-md-3">
                {if $show_logo}
                    <div class="seller_image">
                        <a href="{$seller.url|escape:'html':'UTF-8'}">
                            <img class="img-responsive" src="{$seller.photo|escape:'html':'UTF-8'}" width="180" height="200" />
                        </a>
                    </div>
                {/if}
                {if $show_seller_rating}
                    <div class="average_rating buttons_bottom_block">
                        {section name="i" start=0 loop=5 step=1}
                            {if $seller.averageMiddle le $smarty.section.i.index}
                                <div class="star"></div>
                            {else}
                                <div class="star star_on"></div>
                            {/if}
                        {/section}({$seller.averageTotal})
                    </div>
                {/if}
                <div class="seller_name">
                    <a href="{$seller.url|escape:'html':'UTF-8'}">
                        {$seller.name|escape:'html':'UTF-8'}
                    </a>
                </div>
            </li>
        {/foreach}
        </ul>
    {else}
        <p class="alert alert-info">
            {l s='There are not sellers.' mod='jmarketplace'}
        </p>
    {/if}
</div>
