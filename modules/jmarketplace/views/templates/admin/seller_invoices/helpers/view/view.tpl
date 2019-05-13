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
<div class="panel">
    <h3>
        {l s='Commissions in this transfer request' mod='jmarketplace'}
    </h3>
    <table class="table">
        <thead>
            <th>
                {l s='ID Order' mod='jmarketplace'}
            </th>
            <th>
                {l s='Order reference' mod='jmarketplace'}
            </th>
            <th>
                {l s='Seller name' mod='jmarketplace'}
            </th>
            <th>
                {l s='Concept' mod='jmarketplace'}
            </th>
            <th class="text-right">
                {l s='Price' mod='jmarketplace'}
            </th>
            <th class="text-right">
                {l s='Unit commission' mod='jmarketplace'}
            </th>
            <th class="text-right">
                {l s='Quantity' mod='jmarketplace'}
            </th>
            <th class="text-right">
                {l s='Total commission' mod='jmarketplace'}
            </th>
            <th class="text-center">
                {l s='Payment state' mod='jmarketplace'}
            </th>
            <th>
                {l s='Date add' mod='jmarketplace'}
            </th>
        </thead>
        <tbody>
            {foreach from=$commissions item=commission name=commissions}
                <tr>
                    <td>
                        {$commission.id_order|intval}
                    </td>
                    <td>
                        {$commission.reference|escape:'htmlall':'UTF-8'}
                    </td>
                    <td>
                        {$commission.seller_name|escape:'htmlall':'UTF-8'}
                    </td>
                    <td>
                        {$commission.product_name|escape:'htmlall':'UTF-8'}
                    </td>
                    {if $show_tax_commission == 1}
                        <td class="text-right">
                            {Tools::displayPrice($commission.price_tax_incl)|escape:'htmlall':'UTF-8'}
                        </td>
                    {else}
                        <td class="text-right">{Tools::displayPrice($commission.price_tax_excl)|escape:'htmlall':'UTF-8'}</td>
                    {/if}
                    {if $show_tax_commission == 1}
                        <td class="text-right">
                            {Tools::displayPrice($commission.unit_commission_tax_incl)|escape:'htmlall':'UTF-8'}
                        </td>
                    {else}
                        <td class="text-right">
                            {Tools::displayPrice($commission.unit_commission_tax_excl)|escape:'htmlall':'UTF-8'}
                        </td>
                    {/if}
                    <td class="text-right">
                        {$commission.quantity|intval}
                    </td>
                    {if $show_tax_commission == 1}
                        <td class="text-right">
                            {Tools::displayPrice($commission.total_commission_tax_incl)|escape:'htmlall':'UTF-8'}
                        </td>
                    {else}
                        <td class="text-right">
                            {Tools::displayPrice($commission.total_commission_tax_excl)|escape:'htmlall':'UTF-8'}
                        </td>
                    {/if}
                    <td class="text-center">
                        {$commission.state_name|escape:'htmlall':'UTF-8'}
                    </td>
                    <td>
                        {$commission.date_add|escape:'htmlall':'UTF-8'}
                    </td>
                </tr>
            {/foreach}
        </tbody>
    </table>
</div>
<div class="panel">
    <h3>
        {l s='Transfer Information'  mod='jmarketplace'}
    </h3>
    <form action="{$url_post|escape:'html':'UTF-8'}" method="post" class="std">   
        <input type="hidden" name="id_seller_transfer_invoice" value="{$seller_transfer_invoice->id|intval}" />
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="name">
                        {l s='Seller name' mod='jmarketplace'}
                    </label>
                    <input class="form-control" type="text" name="name" id="name" value="{$seller->name|escape:'htmlall':'UTF-8'}" readonly="readonly" />
                </div>
                <div class="form-group">
                    <label for="date_request">
                        {l s='Date request payment' mod='jmarketplace'}
                    </label>
                    <input class="form-control" type="text" name="date_request" id="date_request" value="{$seller_transfer_invoice->date_add|escape:'htmlall':'UTF-8'}" readonly="readonly" />
                </div>
                <div class="form-group">
                    <label for="total">
                        {l s='Total request payment' mod='jmarketplace'}
                    </label>
                    <input class="form-control" type="text" name="total" id="total" value="{$total_paid|escape:'htmlall':'UTF-8'}" readonly="readonly" />
                </div>
            </div>
            <div class="col-md-9">
                <div class="form-group">
                    <a href="{$pdf|escape:'htmlall':'UTF-8'}" title="{l s='Download invoice' mod='jmarketplace'}" target="_blank">
                        <img src="{$pdf_image|escape:'htmlall':'UTF-8'}" />
                    </a>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            {if $seller_transfer_invoice->validate == 0}
                <button type="submit" name="submitAcceptedPayment" class="btn btn-default button button-medium">
                    <span>
                        <i class="icon-check fa fa-check"></i> 
                        {l s='Accept payment' mod='jmarketplace'}
                    </span>
                </button>
            {/if}
            <a class="btn btn-default" href="index.php?controller=AdminSellerInvoices&amp;token={$token|escape:'html':'UTF-8'}">
                <i class="icon-close fa fa-close"></i> 
                {l s='Cancel' mod='jmarketplace'}
            </a>
        </div>
    </form>
</div>
{/block}