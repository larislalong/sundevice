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
        {l s='My account' mod='jmarketplace'}
    </a>
    <span class="navigation-pipe">
        {$navigationPipe|escape:'html':'UTF-8'}
    </span>
    <a href="{$link->getModuleLink('jmarketplace', 'selleraccount', array(), true)|escape:'html':'UTF-8'}">
        {l s='My seller account' mod='jmarketplace'}
    </a>
    <span class="navigation-pipe">
        {$navigationPipe|escape:'html':'UTF-8'}
    </span>
    <span class="navigation_page">
        {l s='Transfer history' mod='jmarketplace'}
    </span>
{/capture}

<div class="row">
    <div class="column col-xs-12 col-sm-12 col-lg-3"{if $show_menu_options == 0} style="display:none;"{/if}>
        {hook h='displayMarketplaceWidget'}
    </div>
    
    <div class="col-sm-12{if $show_menu_options == 1} col-lg-9{else} col-lg-12{/if}">
        <div class="box">
            <h1 class="page-subheading">
                {l s='Transfer history' mod='jmarketplace'}
            </h1>
            {if $transfer_funds && count($transfer_funds)}
                <div class="table-responsive">
                    <table id="order-list" class="table table-bordered footab">
                        <thead>
                            <tr>
                                <th class="first_item">
                                    {l s='Date of demand' mod='jmarketplace'}
                                </th>
                                <th class="item">
                                    {l s='Date of payment' mod='jmarketplace'}
                                </th>
                                <th class="item">
                                    {l s='Total' mod='jmarketplace'}
                                </th>
                                <th class="item">
                                    {l s='Status' mod='jmarketplace'}
                                </th>
                                <th class="last_item">
                                    {l s='Invoice' mod='jmarketplace'}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        {foreach from=$transfer_funds item=demand name=sellerdemand}
                            <tr>
                                <td class="first_item">
                                    <i class="icon-calendar fa fa-calendar"></i> 
                                    - {dateFormat date=$demand.date_add full=0} 
                                    - <i class="icon-time fa fa-clock-o"></i> 
                                    {$demand.date_add|escape:'htmlall':'UTF-8'|substr:11:5}
                                </td>
                                <td class="item">
                                    {if $demand.validate == 0}
                                        -
                                    {else}
                                        <i class="icon-calendar fa fa-calendar"></i> 
                                        - {dateFormat date=$demand.date_upd full=0} 
                                        - <i class="icon-time fa fa-clock-o"></i> 
                                        {$demand.date_upd|escape:'htmlall':'UTF-8'|substr:11:5}
                                    {/if}
                                </td>
                                <td class="item">
                                    {$demand.total|escape:'html':'UTF-8'}
                                </td>
                                <td class="last_item">
                                    {if $demand.validate == 0}
                                        {l s='Transfer pending' mod='jmarketplace'}
                                    {else}
                                        {l s='Transfer accepted' mod='jmarketplace'}
                                    {/if}
                                </td>
                                <td class="item">
                                    <a class="btn" href="{$demand.invoice|escape:'html':'UTF-8'}" target="_blank">
                                        <i class="icon-file fa fa-file"></i> 
                                        {l s='pdf' mod='jmarketplace'}
                                    <a>
                                </td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                </div>
            {else}
                <p class="alert alert-info">
                    {l s='There are not demands.' mod='jmarketplace'}
                </p>
            {/if}
        </div>
        <ul class="footer_links clearfix">
            <li>
                <a class="btn btn-default button" href="{$link->getModuleLink('jmarketplace', 'sellerinvoice', array(), true)|escape:'html':'UTF-8'}">
                    <i class="icon-money fa fa-money"></i> 
                    <span>{l s='Transfer funds' mod='jmarketplace'}</span>
                </a>
            </li>
        </ul> 
    </div>
</div>