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
        {l s='Earnings with Marketplace in' mod='jmarketplace'} 
        <strong>
            {$range_dates|escape:'html':'UTF-8'}
        </strong>
    </h3>
    <div class="row">
        <form action="{$url_form|escape:'html':'UTF-8'}" method="post" class="std"> 
            <div class="form-group col-md-3">
                <label for="from">
                    {l s='From' mod='jmarketplace'}
                </label>
                <input class="form-control datepicker" type="text" name="from" id="from" value="{if isset($from)}{$from|escape:'html':'UTF-8'}{else}0000-00-00{/if}" />
            </div>
            <div class="form-group col-md-3">
                <label for="from">
                    {l s='To' mod='jmarketplace'}
                </label>
                <input class="form-control datepicker" type="text" name="to" id="to" value="{if isset($to)}{$to|escape:'html':'UTF-8'}{else}0000-00-00{/if}" />
            </div>
            <div class="form-group col-md-2" style="margin-top: 22px;">
                <button type="submit" name="submitFilterDate" class="btn btn-primary">
                    <span>
                        {l s='View' mod='jmarketplace'}
                    </span>
                </button>
            </div> 
        </form>
    </div>
    <div class="row">
        <div class="col-lg-4 col-xs-12">
            <div class="small-box bg-blue">
                <div class="inner">
                    <h3>
                        {convertPrice price=$total_entries_for_admin}
                    </h3>
                    {if $tax_commission == 1}  
                        <h5>
                            {l s='Tax incl.' mod='jmarketplace'}
                        </h5>
                    {else}
                        <h5>
                            {l s='Tax excl.' mod='jmarketplace'}
                        </h5>
                    {/if}   
                    <p>
                        {l s='Incomes' mod='jmarketplace'}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-xs-12">
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>
                        {convertPrice price=$total_spends_for_admin}
                    </h3>
                    {if $tax_commission == 1} 
                        <h5>
                            {l s='Tax incl.' mod='jmarketplace'}
                        </h5>
                    {else}
                        <h5>
                            {l s='Tax excl.' mod='jmarketplace'}
                        </h5>
                    {/if}    
                    <p>
                        {l s='Expenses (Commissions to sellers)' mod='jmarketplace'}
                    </p>
                </div>
             </div>
        </div>
        <div class="col-lg-4 col-xs-12">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>
                        {convertPrice price=$benefit_for_admin}
                    </h3>
                    {if $tax_commission == 1}
                        <h5>
                            {l s='Tax incl.' mod='jmarketplace'}
                        </h5>
                    {else}
                        <h5>
                            {l s='Tax excl.' mod='jmarketplace'}
                        </h5>
                    {/if}   
                    <p>
                        {l s='Benefit' mod='jmarketplace'}
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="margin-bottom:15px;">
        <section class="col-lg-12 connectedSortable">
            <h2>
                {l s='Incomes, expenses and benefits with marketplace in' mod='jmarketplace'} 
                {$range_dates|escape:'html':'UTF-8'}
            </h2>
            <div class="nav-tabs-custom">
                <div class="tab-content no-padding">
                    <canvas id="gpyg" style="width: 310px; height: 400px;"></canvas>
                </div>
            </div>
        </section>
    </div>
    {if isset($best_sellers) && $best_sellers}
        <div class="row">
            <section class="col-lg-12 connectedSortable">
                <h2>
                    {l s='Best sellers in' mod='jmarketplace'} {$range_dates|escape:'html':'UTF-8'}
                </h2>
                <div class="nav-tabs-custom">
                    <div class="tab-content no-padding">
                        <div class="best_sales">
                            <table id="order-list" class="table table-bordered footab">
                                <thead>
                                    <tr>
                                        <th class="first_item">
                                            <strong>
                                                {l s='Seller name' mod='jmarketplace'}
                                            </strong>
                                        </th>
                                        <th class="item">
                                            <strong>
                                                {l s='Seller shop' mod='jmarketplace'}
                                            </strong>
                                        </th>
                                        <th class="item text-right">
                                            <strong>
                                                {l s='Earnings for seller' mod='jmarketplace'}
                                            </strong>
                                        </th>
                                        <th class="item text-right">
                                            <strong>
                                                {l s='Earnings for you' mod='jmarketplace'}
                                            </strong>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {foreach from=$best_sellers item=seller}
                                        <tr>
                                            <td>
                                                {$seller.name|escape:'html':'UTF-8'}
                                            </td>
                                            <td>
                                                {$seller.shop|escape:'html':'UTF-8'}
                                            </td>
                                            <td class="text-right">
                                                {convertPrice price=$seller.commissions}
                                            </td>
                                            <td class="text-right">
                                                {convertPrice price=$seller.admin_commissions}
                                            </td>
                                        </tr>
                                    {/foreach}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    {/if}
</div>
<script type="text/javascript">
{literal}
var div_gpyg = document.getElementById("gpyg");
var gpyg_chart = new Chart(div_gpyg, {
    type: 'line',
    data: {
        labels: [{/literal}{$labels nofilter}{literal}], //This is HTML content
        datasets: [
            {
                label: "{/literal}{l s='Incomes' mod='jmarketplace'}{literal} ({/literal}{$sign|escape:'html':'UTF-8'}{literal})",
                fill: false,
                lineTension: 0.1,
                backgroundColor: "#4170CF",
                borderColor: "#3A65BA",
                borderCapStyle: 'butt',
                borderDash: [],
                borderDashOffset: 0.0,
                borderJoinStyle: 'miter',
                pointBorderColor: "#3A65BA",
                pointBackgroundColor: "#3A65BA",
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "#3276B1",
                pointHoverBorderColor: "#3276B1",
                pointHoverBorderWidth: 2,
                pointRadius: 1,
                pointHitRadius: 10,
                data: [{/literal}{$entry_string nofilter}{literal}], //This is HTML content
            },
            {
                label: "{/literal}{l s='Expenses' mod='jmarketplace'}{literal} ({/literal}{$sign|escape:'html':'UTF-8'}{literal})",
                fill: false,
                lineTension: 0.1,
                backgroundColor: "#DC3912",
                borderColor: "#C63310",
                borderCapStyle: 'butt',
                borderDash: [],
                borderDashOffset: 0.0,
                borderJoinStyle: 'miter',
                pointBorderColor: "#C63310",
                pointBackgroundColor: "#C63310",
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "#3276B1",
                pointHoverBorderColor: "#3276B1",
                pointHoverBorderWidth: 2,
                pointRadius: 1,
                pointHitRadius: 10,
                data: [{/literal}{$spend_string nofilter}{literal}], //This is HTML content
            },
            {
                label: "{/literal}{l s='Benefits' mod='jmarketplace'}{literal} ({/literal}{$sign|escape:'html':'UTF-8'}{literal})", //This is HTML content
                fill: false,
                lineTension: 0.1,
                backgroundColor: "#00A65A",
                borderColor: "#009551",
                borderCapStyle: 'butt',
                borderDash: [],
                borderDashOffset: 0.0,
                borderJoinStyle: 'miter',
                pointBorderColor: "#3A65BA",
                pointBackgroundColor: "#3A65BA",
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "#3276B1",
                pointHoverBorderColor: "#3276B1",
                pointHoverBorderWidth: 2,
                pointRadius: 1,
                pointHitRadius: 10,
                data: [{/literal}{$benefit_string nofilter}{literal}], //This is HTML content
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
    }
});
{/literal}
</script>
{/block}