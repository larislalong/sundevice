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
        {l s='Your favorite sellers' mod='jmarketplace'}
    </span>
{/capture}

<div class="box">
    <h1 class="page-subheading">{l s='Your favorite sellers' mod='jmarketplace'}</h1>
    {if $favorite_sellers && count($favorite_sellers)}
        <div class="table-responsive">
            <table id="order-list" class="table table-bordered footab">
                <thead>
                    <tr>
                        <th class="first_item">
                            {l s='Seller name' mod='jmarketplace'}
                        </th>
                        <th class="item"></th>
                    </tr>
                </thead>
                <tbody>
                {foreach from=$favorite_sellers item=favorite_seller name=favorite_sellers}
                    {$params.id_seller = $favorite_seller.id_seller} 
                    {$params.link_rewrite = $favorite_seller.link_rewrite}
                    <tr>
                        <td class="first_item">
                            {$favorite_seller.name|escape:'html':'UTF-8'}
                        </td>
                        <td class="item">
                            <a class="btn btn-secondary" href="{jmarketplace::getJmarketplaceLink('jmarketplace_seller_rule',$params)|escape:'html':'UTF-8'}">
                                <i class="icon-user fa fa-user"></i> 
                                {l s='View profile' mod='jmarketplace'}
                            </a>
                            <a class="btn btn-secondary" href="{jmarketplace::getJmarketplaceLink('jmarketplace_sellerproductlist_rule',$params)|escape:'html':'UTF-8'}">
                                <i class="icon-list fa fa-list"></i> 
                                {l s='View products' mod='jmarketplace'}
                            </a>
                            <a class="btn btn-secondary" href="{$link->getModuleLink('jmarketplace', 'contactseller')|escape:'html':'UTF-8'}?id_seller={$favorite_seller.id_seller|intval}">
                                <i class="icon-comments fa fa-comments"></i> 
                                {l s='Contact' mod='jmarketplace'}
                            </a>
                            <a class="btn btn-secondary" href="{$link->getModuleLink('jmarketplace', 'favoriteseller')|escape:'html':'UTF-8'}?delete={$favorite_seller.id_seller|intval}">
                                <i class="icon-minus-sign fa fa-remove"></i> 
                                {l s='Delete' mod='jmarketplace'}
                            </a>
                        </td>
                    </tr>
                {/foreach}
                </tbody>
            </table>
        </div>
    {else}
        <p class="alert alert-info">
            {l s='There is not favorite sellers.' mod='jmarketplace'}
        </p>
    {/if}
</div>

<ul class="footer_links clearfix">
    <li>
        <a class="btn btn-default button" href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}">
            <span>
                <i class="icon-chevron-left fa fa-chevron-left"></i> 
                {l s='Back to your account' mod='jmarketplace'}
            </span>
        </a>
    </li>
</ul>  