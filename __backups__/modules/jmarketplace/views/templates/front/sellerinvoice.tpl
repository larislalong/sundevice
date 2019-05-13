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
        {l s='Withdraw money' mod='jmarketplace'}
    </span>
{/capture}

{if isset($confirmation) && $confirmation}
    <div class="row">
        <div class="col-lg-12">
            <p class="alert alert-success">
                {l s='Your request has been successfully sent.' mod='jmarketplace'} 
            </p>
        </div>
    </div>
{/if}

{if isset($errors) && $errors && $ps_version != '1.7'}
    {include file="./errors.tpl"}
{/if}

<div class="row">
    <div class="column col-xs-12 col-sm-12 col-lg-3"{if $show_menu_options == 0} style="display:none;"{/if}>
        {hook h='displayMarketplaceWidget'}
    </div>
    
    <div class="col-sm-12{if $show_menu_options == 1} col-lg-9{else} col-lg-12{/if}">
        <div class="box">
            <h1 class="page-subheading">
                {l s='Withdraw money' mod='jmarketplace'} 
                (<strong>{$total_funds|escape:'html':'UTF-8'}</strong>)
            </h1>
            <p>
                {l s='Collect your money by selecting the desired commissions and attaching an invoice to:' mod='jmarketplace'}
            </p>
            <h2>
                {l s='Invoice payment information' mod='jmarketplace'}
            </h2>
            <div class="marketplace_data_invoice">
                {$shop_name|escape:'html':'UTF-8'}<br/>
                {$shop_address|escape:'html':'UTF-8'}<br/>
                {$shop_code|escape:'html':'UTF-8'} {$shop_city|escape:'html':'UTF-8'}<br/>
                {if $shop_country != false AND $shop_state != false}
                    {$shop_state|escape:'html':'UTF-8'}, 
                    {$shop_country|escape:'html':'UTF-8'}
                    <br/>
                {/if}
                {$shop_details|escape:'html':'UTF-8'}<br/>
            </div>

            <p>
                {l s='Method of payment:' mod='jmarketplace'}
                <strong>
                    {if $active_payment == 'bankwire'}
                        {l s='Bankwire' mod='jmarketplace'}
                    {else}
                        {l s='PayPal' mod='jmarketplace'}
                    {/if}
                </strong>
                <a class="btn btn-default button button-small" href="{$link->getModuleLink('jmarketplace', 'sellerpayment', array(), true)|escape:'html':'UTF-8'}">
                    <span>
                        <i class="icon-exchange fa fa-exchange"></i> 
                        {l s='Change' mod='jmarketplace'}
                    </span>
                </a>
            </p>
        </div>
        <div class="box">
            {if $orders && count($orders)}
                <h2>
                    {l s='Your commissions pending payment' mod='jmarketplace'}
                </h2>
                <form action="{$link->getModuleLink('jmarketplace', 'sellerinvoice', array(), true)|escape:'html':'UTF-8'}" method="post" class="std" enctype="multipart/form-data">
                    <div class="table-responsive">
                        <table id="sellerfunds-list" class="table table-bordered footab">
                            <thead>
                                <tr>
                                    <th class="first_item"></th>
                                    <th class="item hidden-xs hidden-sm">
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
                                        {l s='Price (tax excl.)' mod='jmarketplace'}
                                    </th>
                                    <th class="item hidden-xs hidden-sm">
                                        {l s='Quantity' mod='jmarketplace'}
                                    </th>
                                    <th class="item">
                                        {l s='Commission' mod='jmarketplace'}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            {foreach from=$orders item=order name=sellerorders}
                                <tr>
                                    <td class="first_item">
                                        <input type="checkbox" name="commissions[]" value="{$order.id_seller_commission_history|intval}" data="{$order.commission|floatval}" />
                                    </td>
                                    <td class="item hidden-xs hidden-sm">
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
                                        {$order.price|escape:'html':'UTF-8'}
                                    </td>
                                    <td class="item hidden-xs hidden-sm">
                                        {$order.quantity|intval}
                                    </td>
                                    <td class="item">
                                        {$order.total_commission|escape:'html':'UTF-8'}
                                    </td>
                                </tr>
                            {/foreach}
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="first_item hidden-xs hidden-sm" colspan="7">
                                        <strong>
                                            {l s='Total invoice' mod='jmarketplace'}
                                        </strong>
                                    </td>
                                    <td class="last_item hidden-xs hidden-sm">
                                        <strong>
                                            <span id="total_invoice" data="0">
                                                {$initial_price|escape:'html':'UTF-8'}
                                            </span>
                                        </strong>
                                        <input type="hidden" name="total_invoice" value="0" />
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="form-group" style="margin-top:15px;">
                        <label for="fileUpload">
                            {l s='Invoice in PDF' mod='jmarketplace'}
                        </label>
                        <input type="file" name="sellerInvoice" id="sellerInvoice" class="form-control" />
                    </div>
                    <div class="form-group">
                        <button type="submit" name="submitInvoice" class="btn btn-default button button-medium">
                            <span>
                                {l s='Send' mod='jmarketplace'}
                                <i class="icon-chevron-right right"></i>
                            </span>
                        </button>
                    </div>
                </form>
            {else}
                <p class="alert alert-info">
                    {l s='There are not pending commissions.' mod='jmarketplace'}
                </p>
            {/if}
        </div>
        <ul class="footer_links clearfix">
            <li>
                <a class="btn btn-default button btn-secondary" href="{$link->getModuleLink('jmarketplace', 'sellerinvoicehistory', array(), true)|escape:'html':'UTF-8'}">
                    <i class="icon-history fa fa-history"></i> 
                    <span>{l s='Transfer history' mod='jmarketplace'}</span>
                </a>
            </li> 
        </ul>
    </div>
</div>
<script type="text/javascript">
var sign = "{$sign|escape:'quotes':'UTF-8'}";
</script>