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
    <a href="{$link->getModuleLink('jmarketplace', 'selleraccount')|escape:'html':'UTF-8'}">
        {l s='Your seller account' mod='jmarketplace'}
    </a>
    <span class="navigation-pipe">
        {$navigationPipe|escape:'html':'UTF-8'}
    </span>
    <span class="navigation_page">
        {l s='History commissions' mod='jmarketplace'}
    </span>
{/capture}

<div class="row">
    <div class="column col-xs-12 col-sm-12 col-lg-3"{if $show_menu_options == 0} style="display:none;"{/if}>
        {hook h='displayMarketplaceWidget'}
    </div>
    
    <div class="col-sm-12{if $show_menu_options == 1} col-lg-9{else} col-lg-12{/if}">
        <div class="box">
            <h1 class="page-subheading">
                {l s='Commissions Summary' mod='jmarketplace'}
            </h1>
            <div class="table-responsive">
                <table class="table table-hover">
                    <tr>
                        <td class="item">
                            <strong>
                                {l s='Paid commissions' mod='jmarketplace'}
                            </strong>
                        </td>
                        <td class="item" style="text-align:right;">
                            <strong>
                                {if $tax_commission == 1}
                                    {$total_paid_commission_tax_incl|escape:'html':'UTF-8'}
                                {else}
                                    {$total_paid_commission_tax_excl|escape:'html':'UTF-8'}
                                {/if}    
                            </strong>
                        </td>
                    </tr>
                    <tr>
                        <td class="item">
                            <strong>
                                {l s='Outstanding commissions' mod='jmarketplace'}
                            </strong>
                        </td>
                        <td class="item" style="text-align:right;">
                            <strong>
                                {if $tax_commission == 1}
                                    {$total_outstanding_commission_tax_incl|escape:'html':'UTF-8'}
                                {else}
                                    {$total_outstanding_commission_tax_excl|escape:'html':'UTF-8'}
                                {/if}    
                            </strong>
                        </td>
                    </tr>
                    <tr>
                        <td class="item">
                            <strong>
                                {l s='Canceled commissions' mod='jmarketplace'}
                            </strong>
                        </td>
                        <td class="item" style="text-align:right;">
                            <strong>
                                {if $tax_commission == 1}
                                    {$total_canceled_commission_tax_incl|escape:'html':'UTF-8'}
                                {else}
                                    {$total_canceled_commission_tax_excl|escape:'html':'UTF-8'}
                                {/if}    
                            </strong>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="box">            
            <h1 class="page-subheading">
                {l s='History commissions' mod='jmarketplace'}
            </h1>
            {if $orders && count($orders)}
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="first_item hidden-xs hidden-sm">
                                    {l s='Date add' mod='jmarketplace'}
                                </th>
                                <th class="item hidden-xs hidden-sm">
                                    {l s='Order ID' mod='jmarketplace'}
                                </th>
                                <th class="item">
                                    {l s='Reference Order' mod='jmarketplace'}
                                </th>
                                <th class="item hidden-xs hidden-sm">
                                    {l s='Product name' mod='jmarketplace'}
                                </th>
                                <th class="item hidden-xs hidden-sm">
                                    {l s='Price' mod='jmarketplace'}
                                </th>
                                <th class="item hidden-xs hidden-sm">
                                    {l s='Quantity' mod='jmarketplace'}
                                </th>
                                <th class="item">
                                    {l s='Unit Commission' mod='jmarketplace'}
                                </th>
                                <th class="item">
                                    {l s='Total Commission' mod='jmarketplace'}
                                </th>
                                <th class="last_item">
                                    {l s='State' mod='jmarketplace'}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        {foreach from=$orders item=order name=sellerorders}
                            <tr>
                                <td class="first_item hidden-xs hidden-sm">
                                    <i class="icon-calendar fa fa-calendar"></i> 
                                    - {dateFormat date=$order.date_add full=0} 
                                    - <i class="icon-time fa fa-clock-o"></i> 
                                    {$order.date_add|escape:'htmlall':'UTF-8'|substr:11:5}
                                </td>
                                <td class="item hidden-xs hidden-sm">
                                    {$order.id_order|intval}
                                </td>
                                <td class="item">
                                    {$order.reference|escape:'html':'UTF-8'}
                                </td>
                                <td class="item hidden-xs hidden-sm">
                                    {$order.product_name|escape:'html':'UTF-8'}
                                </td>
                                <td class="item hidden-xs hidden-sm">
                                    {if $tax_commission}
                                        {$order.price_tax_incl|escape:'html':'UTF-8'}
                                    {else}
                                        {$order.price_tax_excl|escape:'html':'UTF-8'}
                                    {/if}
                                </td>
                                <td class="item hidden-xs hidden-sm">
                                    {$order.quantity|intval}
                                </td>
                                <td class="item">
                                    {if $tax_commission}
                                        {$order.unit_commission_tax_incl|escape:'html':'UTF-8'}
                                    {else}
                                        {$order.unit_commission_tax_excl|escape:'html':'UTF-8'}
                                    {/if}
                                </td>
                                <td class="item">
                                    {if $tax_commission}  
                                        {$order.total_commission_tax_incl|escape:'html':'UTF-8'} 
                                    {else}
                                        {$order.total_commission_tax_excl|escape:'html':'UTF-8'}
                                    {/if}
                                </td>
                                <td class="last_item">
                                    {$order.state_name|escape:'html':'UTF-8'}
                                </td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                </div>
            {else}
                <p class="alert alert-info">
                    {l s='There are not orders.' mod='jmarketplace'}
                </p>
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