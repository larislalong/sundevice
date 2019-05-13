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
<div class="row">
    <div id="seller_order" class="col-xs-12 col-md-7">
        <div class="panel">
            <h3>
                {l s='Order' mod='jmarketplace'} 
                <a href="{$url_order|escape:'html':'UTF-8'}">
                    "{$order->reference|escape:'html':'UTF-8'}" 
                    Nº{$order->id_order|intval}
                </a>
            </h3>
            {l s='Date of order' mod='jmarketplace'}: 
            <i class="icon-calendar fa fa-calendar"></i> 
            - {dateFormat date=$order->date_add full=0} 
            - <i class="icon-time fa fa-clock-o"></i> 
            {$order->date_add|escape:'htmlall':'UTF-8'|substr:11:5}
            <br/>
            {l s='Seller' mod='jmarketplace'}: 
            {$seller->name|escape:'html':'UTF-8'}
            <br/>
            {l s='Total weight' mod='jmarketplace'}: 
            {$total_weight|floatval} {$weight_unit|escape:'html':'UTF-8'}
            <br/>
            {l s='Total paid' mod='jmarketplace'}: 
            {if $tax_commission}
                <strong>
                    {$total_paid_tax_incl|escape:'html':'UTF-8'}
                </strong>
            {else}
                <strong>
                    {$total_paid_tax_excl|escape:'html':'UTF-8'}
                </strong>
            {/if}
            <br/><br/>

            <label>
                {l s='Order state' mod='jmarketplace'}
            </label>
            
            <div class="table-responsive">
                <table id="history-status" class="table history-status row-margin-bottom">
                    <tbody>
                        {foreach from=$order_state_history item=order_history name=order_history}
                            <tr>
                                <td style="background-color:{$order_history.color|escape:'html':'UTF-8'};width:20px;">
                                    <img src="../img/os/{$order_history.id_order_state|intval}.gif" alt="{$order_history.ostate_name|escape:'html':'UTF-8'}" width="16" height="16">
                                </td>
                                <td class="first_item" style="background-color:{$order_history.color|escape:'html':'UTF-8'};color:white;">
                                    {$order_history.ostate_name|escape:'html':'UTF-8'}
                                </td>
                                <td class="item" style="background-color:{$order_history.color|escape:'html':'UTF-8'};color:white;">
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
                <input type="hidden" name="id_order" value="{$order->id_order|intval}" />
                <div class="form-group">
                    <label>
                        {l s='Change order state' mod='jmarketplace'}
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
                        </span>
                    </button>
                </div>    
            </form>

            
        </div>
    </div>
    <div id="seller_order_customer" class="col-xs-12 col-md-5">            
        <div class="panel">
            <h3>
                {l s='Customer' mod='jmarketplace'}
                <a href="{$url_customer|escape:'html':'UTF-8'}">
                    {$customer->firstname|escape:'html':'UTF-8'} 
                    {$customer->lastname|escape:'html':'UTF-8'}
                </a>
            </h3>           
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
<div class="row">
    <div id="seller_order_products" class="col-md-12">
        <div class="panel">
            <h3>
                {l s='Products' mod='jmarketplace'}
            </h3>
            {if $products && count($products)}
                <div class="table-responsive">
                    <table id="order-list" class="table table-bordered footab">
                        <thead>
                            <tr>
                                <th class="first_item" data-sort-ignore="true">
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
                                        <br/>{l s='Reference' mod='jmarketplace'}: 
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
                                    {if $tax_commission == 1}
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
                                        {if $tax_commission == 1}
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
            <div class="panel-footer">
                <a class="btn btn-default" href="index.php?controller=AdminSellerOrders&amp;token={$token|escape:'html':'UTF-8'}">
                    <i class="icon-chevron-left fa fa-chevron-left"></i> 
                    {l s='Back to list' mod='jmarketplace'}
                </a>
            </div>   
        </div>
    </div>
</div>   
{/block}