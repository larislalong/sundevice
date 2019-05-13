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
    <a href="{$link->getModuleLink('jmarketplace', 'orders', array(), true)|escape:'html':'UTF-8'}">
        {l s='Your orders' mod='jmarketplace'}
    </a>
    <span class="navigation-pipe">
        {$navigationPipe|escape:'html':'UTF-8'}
    </span>
    <span class="navigation_page">
        {l s='Order' mod='jmarketplace'} 
        "{$order->reference|escape:'html':'UTF-8'}" 
        Nº{$order->id_order|intval}
    </span>
{/capture}

<div class="row">
    <div class="column col-xs-12 col-sm-12 col-lg-3"{if $show_menu_options == 0} style="display:none;"{/if}>
        {hook h='displayMarketplaceWidget'}
    </div>
    
    <div class="col-sm-12{if $show_menu_options == 1} col-lg-9{else} col-lg-12{/if}">
        <div class="row">
            <div class="col-md-12">
                <div class="boxes">
                    <h2 class="page-subheading">
                        {l s='Order' mod='jmarketplace'} 
                        "{$order->reference|escape:'html':'UTF-8'}" 
                        Nº{$order->id_order|intval}
                    </h2>
                    {l s='Date of order' mod='jmarketplace'}: 
                    <i class="icon-calendar fa fa-calendar"></i> 
                    - {dateFormat date=$order->date_add full=0} 
                    - <i class="icon-time fa fa-clock-o"></i> 
                    {$order->date_add|escape:'htmlall':'UTF-8'|substr:11:5}
                    <br/>
                    {*l s='Method of payment' mod='jmarketplace'}: {$order->payment|escape:'html':'UTF-8'*}
                    {l s='Total weight' mod='jmarketplace'}: 
                    {$total_weight|floatval} {$weight_unit|escape:'html':'UTF-8'}
                    <br/>
                    {l s='Total commission for you' mod='jmarketplace'}: 
                    {if $tax_commission}
                        <strong>
                            {$total_paid_tax_incl|escape:'html':'UTF-8'}
                        </strong>
                    {else}
                        <strong>
                            {$total_paid_tax_excl|escape:'html':'UTF-8'}
                        </strong>
                    {/if}
                    <br/>
                </div>
            </div>
        </div>    
        <div class="row">
            <div id="order" class="col-xs-12 col-md-7">
                <div class="boxes">
                    <h2 class="page-subheading">
                        {l s='Order' mod='jmarketplace'}
                    </h2>
                    <div class="table-responsive">
                        <table id="history-status" class="table table-bordered footab">
                            <tbody>
                                {foreach from=$order_state_history item=order_history name=order_history}
                                    <tr style="background-color:{$order_history.color|escape:'html':'UTF-8'};color:white;">
                                        <td class="first_item">
                                            {$order_history.ostate_name|escape:'html':'UTF-8'}
                                        </td>
                                        <td class="item">
                                            <i class="icon-calendar fa fa-calendar"></i> 
                                            - {dateFormat date=$order_history.date_add full=0} 
                                            - <i class="icon-time fa fa-clock-o"></i> 
                                            {$order_history.date_add|escape:'html':'UTF-8'|substr:11:5}
                                        </td>
                                    </tr>
                                {/foreach}
                            </tbody>
                        </table>
                    </div>
                    <form action="{$order_link|escape:'html':'UTF-8'}" method="post" class="std">
                        <div class="form-group">
                            <label for="id_order_state">
                                {l s='Order state' mod='jmarketplace'}
                            </label>
                            <select name="id_order_state">
                                {foreach from=$order_states item=order_state}
                                    <option value="{$order_state.id_order_state|intval}"{if isset($order->current_state) && $order->current_state == $order_state.id_order_state} selected="selected"{/if}>
                                        {$order_state.name|escape:'html':'UTF-8'}
                                    </option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" name="submitState" class="btn btn-default button button-medium">
                                <span>
                                    {l s='Change status' mod='jmarketplace'}
                                    <i class="icon-chevron-right right"></i>
                                </span>
                            </button>
                        </div>    
                    </form>
                </div>
            </div>
            <div class="col-xs-12 col-md-5">
                <div id="customer" class="boxes">
                    <h2 class="page-subheading">
                        {l s='Customer' mod='jmarketplace'}
                    </h2>
                    {l s='Name' mod='jmarketplace'}: 
                    {$customer_name|escape:'html':'UTF-8'}
                    <br/><br/>

                    <div class="row">
                        <div id="address_delivery" class="col-xs-12 col-md-6">
                            <strong>
                                {l s='Address delivery' mod='jmarketplace'}
                            </strong>
                            <br/>
                            {foreach from=$address_delivery item=concept}
                                {if $concept != ''}
                                    {$concept|escape:'html':'UTF-8'}<br/>
                                {/if}
                            {/foreach}
                        </div>

                        
                    </div>
                </div>
            </div>
        </div>
        {if $show_manage_carriers == 1}
            <div class="row">
                <div class="col-md-12">
                    <div id="tracking" class="boxes">
                        {if isset($errors) && $errors}
                            {include file="./errors.tpl"}
                        {/if}
                        <div class="table-responsive">
                            <h2 class="page-subheading">
                                {l s='Transport' mod='jmarketplace'}
                            </h2>
                            <form action="{$order_link|escape:'html':'UTF-8'}" method="post" class="std">
                                <div class="table-responsive">
                                    <table class="table" id="shipping_table">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <span class="title_box">
                                                        {l s='Date' mod='jmarketplace'}
                                                    </span>
                                                </th>
                                                <th>
                                                    <span class="title_box">
                                                        &nbsp;
                                                    </span>
                                                </th>
                                                <th>
                                                    <span class="title_box">
                                                        {l s='Carrier' mod='jmarketplace'}
                                                    </span>
                                                </th>
                                                <th>
                                                    <span class="title_box">
                                                        {l s='Weight' mod='jmarketplace'}
                                                    </span>
                                                </th>
                                                <th>
                                                    <span class="title_box">
                                                        {l s='Shipping cost' mod='jmarketplace'}
                                                    </span>
                                                </th>
                                                <th>
                                                    <span class="title_box">
                                                        {l s='Tracking number' mod='jmarketplace'}
                                                    </span>
                                                </th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {foreach from=$order_shipping item=line}
                                                <tr>
                                                    <td>
                                                        {dateFormat date=$line.date_add full=true}
                                                    </td>
                                                    <td>
                                                        &nbsp;
                                                    </td>
                                                    <td>
                                                        {$line.carrier_name|escape:'html':'UTF-8'}
                                                    </td>
                                                    <td class="weight">
                                                        {$line.weight|escape:'html':'UTF-8'|string_format:"%.3f"} 
                                                        {$ps_weight_unit|escape:'html':'UTF-8'}
                                                    </td>
                                                    <td class="center">
                                                        {if $order->getTaxCalculationMethod() == $smarty.const.PS_TAX_INC}
                                                            {$line.shipping_cost_tax_incl|escape:'html':'UTF-8'}
                                                        {else}
                                                            {$line.shipping_cost_tax_excl|escape:'html':'UTF-8'}
                                                        {/if}
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="id_order_carrier" value="{$line.id_order_carrier|intval}" />
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control" name="tracking_number" {if $line.url && $line.tracking_number}value="{$line.tracking_number|escape:'html':'UTF-8'}"{/if}>
                                                            <span class="input-group-btn">
                                                                <button type="submit"  name="submitShippingNumber" class="btn btn-info btn-flat">
                                                                    <i class="icon-truck fa fa-truck" title="{l s='Change shipping number' mod='jmarketplace'}"></i>
                                                                </button>
                                                            </span>
                                                        </div>
                                                        {if $line.url && $line.tracking_number}
                                                            <small id="emailHelp" class="form-text text-muted">
                                                                <a target="_blank" href="{$line.url|replace:'@':$line.tracking_number|escape:'html':'UTF-8'}">
                                                                    {$line.url|replace:'@':$line.tracking_number|escape:'html':'UTF-8'}
                                                                </a>
                                                            </small>
                                                        {/if}
                                                    </td>
                                                </tr>
                                            {/foreach}
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                        </div>  
                    </div>
                </div>
            </div>
         {/if}
        <div class="row">
            <div id="products" class="col-md-12">
                <div id="products" class="boxes">
                    <h2 class="page-subheading">
                        {l s='Products' mod='jmarketplace'}
                    </h2>
                    {if $products && count($products)}
                        <div class="table-responsive">
                            <table id="order-list" class="table table-bordered footab">
                                <thead>
                                    <tr>
                                        <th class="first_item">
                                            {l s='Product' mod='jmarketplace'}
                                        </th>
                                        <th class="item" style="text-align: right;">
                                            {l s='Sell Price' mod='jmarketplace'}
                                        </th>
                                        <th class="item" style="text-align: center;">
                                            {l s='Qty' mod='jmarketplace'}
                                        </th>
                                        <th class="item" style="text-align: center;">
                                            {l s='Commission' mod='jmarketplace'}
                                        </th>
                                        <th class="item" style="text-align: right;">
                                            {l s='Total' mod='jmarketplace'}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                {foreach from=$products item=product name=product}
                                    <tr>
                                        <td class="first_item">
                                            {$product.product_name|escape:'html':'UTF-8'}
                                            {if $product.product_reference != ''}
                                                <br/>
                                                {l s='Reference' mod='jmarketplace'}: 
                                                {$product.product_reference|escape:'html':'UTF-8'}
                                            {/if}
                                        </td>
                                        <td class="item" style="text-align: right;">
                                            {if $tax_commission}
                                                {$product.unit_price_tax_incl|escape:'html':'UTF-8'}
                                                {if $product.reduction_percent != 0}
                                                    (-{$product.reduction_percent|floatval}%)
                                                {/if}
                                            {else}
                                                {$product.unit_price_tax_excl|escape:'html':'UTF-8'}
                                                {if $product.reduction_percent != 0}
                                                    (-{$product.reduction_percent|floatval}%)
                                                {/if}
                                                {if $product.reduction_amount != 0}
                                                    (-{$product.reduction_amount_display|escape:'html':'UTF-8'})
                                                {/if}
                                            {/if}
                                        </td>
                                        <td class="item" style="text-align: center;">
                                            {$product.product_quantity|intval}
                                        </td>
                                        <td class="item" style="text-align: center;">
                                            {if $tax_commission}  
                                                {$product.unit_commission_tax_incl|escape:'html':'UTF-8'} 
                                            {else}
                                                {$product.unit_commission_tax_excl|escape:'html':'UTF-8'}
                                            {/if}
                                        </td>
                                        <td class="item" style="text-align: right;">
                                            {if $tax_commission}
                                                {$product.total_commission_tax_incl|escape:'html':'UTF-8'}
                                            {else}
                                                {$product.total_commission_tax_excl|escape:'html':'UTF-8'} 
                                            {/if}
                                        </td>
                                    </tr>
                                {/foreach}
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" style="text-align: right;">
                                            {l s='Total products' mod='jmarketplace'}
                                        </td>
                                        <td style="text-align: right;">
                                            {if $tax_commission}
                                                <strong>
                                                    {$total_products_tax_incl|escape:'html':'UTF-8'}
                                                </strong>
                                            {else}
                                                <strong>
                                                    {$total_products_tax_excl|escape:'html':'UTF-8'}
                                                </strong>
                                            {/if}
                                        </td>
                                    </tr>
                                    {if $order->total_discounts > 0}
                                        <tr>
                                            <td colspan="4" style="text-align: right;">
                                                {l s='Total discounts' mod='jmarketplace'}
                                            </td>
                                            <td style="text-align: right;">
                                                {if $tax_commission == 1}
                                                    <strong>
                                                        -{$total_discounts_tax_incl|escape:'html':'UTF-8'}
                                                    </strong>
                                                {else}
                                                    <strong>
                                                        -{$total_discounts_tax_excl|escape:'html':'UTF-8'}
                                                    </strong>
                                                {/if}
                                            </td>
                                        </tr>
                                    {/if}
                                    {if $total_shipping > 0}
                                        <tr>
                                            <td colspan="4" style="text-align: right;">
                                                {l s='Total shipping' mod='jmarketplace'}
                                            </td>
                                            <td style="text-align: right;">
                                                {if $tax_commission}
                                                    <strong>
                                                        {$total_shipping_tax_incl|escape:'html':'UTF-8'}
                                                    </strong>
                                                {else}
                                                    <strong>
                                                        {$total_shipping_tax_excl|escape:'html':'UTF-8'}
                                                    </strong>
                                                {/if}
                                            </td>
                                        </tr>
                                    {/if}
                                    {if $order->total_fixed_commission > 0}
                                        <tr>
                                            <td colspan="4" style="text-align: right;">
                                                {l s='Fixed commission' mod='jmarketplace'}
                                            </td>
                                            <td style="text-align: right;">
                                                <strong>
                                                    -{$fixed_commission|escape:'html':'UTF-8'}
                                                </strong>
                                            </td>
                                        </tr>
                                    {/if}
                                    <tr>
                                        <td colspan="4" style="text-align: right;">
                                            {l s='Total' mod='jmarketplace'}
                                        </td>
                                        <td style="text-align: right;">
                                            {if $tax_commission}
                                                <strong>
                                                    {$total_paid_tax_incl|escape:'html':'UTF-8'}
                                                </strong>
                                            {else}
                                                <strong>
                                                    {$total_paid_tax_excl|escape:'html':'UTF-8'}
                                                </strong>
                                            {/if}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    {/if}
                </div>
            </div>
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