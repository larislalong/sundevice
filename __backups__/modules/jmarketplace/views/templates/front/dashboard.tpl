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
    <span class="navigation_page">
        {l s='Dashboard' mod='jmarketplace'}
    </span>
{/capture}

<div class="row">
    <div class="column col-xs-12 col-sm-12 col-lg-3"{if $show_menu_options == 0} style="display:none;"{/if}>
        {hook h='displayMarketplaceWidget'}
    </div>
    
    <div class="col-xs-12{if $show_menu_options == 1} col-lg-9{else} col-lg-12{/if}">
        <div class="box">
            <h1 class="page-heading">
                {l s='Dashboard' mod='jmarketplace'}
            </h1>

            <div class="row">
                <form action="{$link->getModuleLink('jmarketplace', 'dashboard', array(), true)|escape:'html':'UTF-8'}" method="post" class="std"> 
                    <div class="form-group col-lg-4">
                        <label for="from">
                            {l s='From' mod='jmarketplace'}
                        </label>
                        <input class="form-control datepicker" type="text" name="from" id="from" value="{if isset($from)}{$from|escape:'html':'UTF-8'}{else}0000-00-00{/if}" />
                    </div>
                    <div class="form-group col-lg-4">
                        <label for="to">
                            {l s='To' mod='jmarketplace'}
                        </label>
                        <input class="form-control datepicker" type="text" name="to" id="to" value="{if isset($to)}{$to|escape:'html':'UTF-8'}{else}0000-00-00{/if}" />
                    </div>
                    <div class="form-group col-lg-2" style="margin-top: 22px;">
                        <button type="submit" name="submitFilterDate" class="btn btn-primary">
                            <span>{l s='View' mod='jmarketplace'}</span>
                        </button>
                    </div> 
                </form>
            </div>
            
            <div id="dashtrends_toolbar" class="row">
                <dl class="col-xs-6 col-lg-2">
                    <dt>
                        {l s='Sales' mod='jmarketplace'}
                    </dt>
                    <dd class="data_value size_l">
                        <span id="sales_score">
                            {$sales|escape:'html':'UTF-8'}
                        </span>
                    </dd>
                </dl>
                <dl class="col-xs-6 col-lg-2">
                    <dt>
                        {l s='Products sold' mod='jmarketplace'}
                    </dt>
                    <dd class="data_value size_l">
                        <span id="orders_score">
                            {$num_products|intval}
                        </span>
                    </dd>
                </dl>
                <dl class="col-xs-6 col-lg-2">
                    <dt>
                        {l s='Orders' mod='jmarketplace'}
                    </dt>
                    <dd class="data_value size_l">
                        <span id="orders_score">
                            {$num_orders|intval}
                        </span>
                    </dd>
                </dl>
                <dl class="col-xs-6 col-lg-2">
                    <dt>
                        {l s='Cart value' mod='jmarketplace'}
                    </dt>
                    <dd class="data_value size_l">
                        <span id="cart_value_score">
                            {$cart_value|escape:'html':'UTF-8'}
                        </span>
                    </dd>
                </dl>
                <dl class="col-xs-6 col-lg-2 label-tooltip">
                    <dt>
                        {l s='Commission (%)' mod='jmarketplace'}
                    </dt>
                    <dd class="data_value size_l">
                        <span id="conversion_rate_score">
                            {$commission|floatval}
                        </span>
                    </dd>
                </dl>
                <dl class="col-xs-6 col-lg-2">
                    <dt>
                        {l s='Commissions' mod='jmarketplace'}
                    </dt>
                    <dd class="data_value size_l">
                        <span id="net_profits_score">
                            {$benefits|escape:'html':'UTF-8'}
                        </span>
                    </dd>
                </dl>
            </div>
                
            <div class="row">
                <div class="col-lg-12">
                    <canvas id="month_chart"></canvas>
                </div>
            </div>
        </div>
                
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-subheading">
                    {l s='Last orders' mod='jmarketplace'}
                </h1>
                {if $orders && count($orders)}
                    <div class="table-responsive">
                        <table id="order-list" class="table table-bordered footab">
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
                                        {l s='Total' mod='jmarketplace'}
                                    </th>
                                    <th class="item">
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
                                            {if $tax_commission}
                                                {$order.total_paid_tax_incl|escape:'html':'UTF-8'}
                                            {else}
                                                {$order.total_paid_tax_excl|escape:'html':'UTF-8'}
                                            {/if}
                                        </td>
                                        <td class="last_item">
                                            {$order.osname|escape:'html':'UTF-8'}
                                        </td>
                                        {if $show_manage_orders == 1}
                                            <td class="last_item text-center">
                                                <a class="btn btn-secondary btn-xs btn-view" title="{l s='View' mod='jmarketplace'}" href="{$order.link|escape:'html':'UTF-8'}">
                                                    <i class="icon-eye fa fa-eye"></i> 
                                                    {l s='View' mod='jmarketplace'}
                                                </a>
                                            </td>
                                        {/if}
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
<script src="{$chart_js|escape:'html':'UTF-8'}"></script>
<script type="text/javascript">
{literal}
var div_month_chart = document.getElementById("month_chart");
var month_chart = new Chart(div_month_chart, {
    type: 'line',
    data: {
        labels: [{/literal}{$chart_label nofilter}{literal}], //This is html content
        datasets: [
            {
                label: "{/literal}{l s='My commissions' mod='jmarketplace'}{literal} ({/literal}{$currency_iso_code|escape:'html':'UTF-8'}{literal})",
                fill: false,
                lineTension: 0.1,
                backgroundColor: "#428bca",
                borderColor: "#357ebd",
                borderCapStyle: 'butt',
                borderDash: [],
                borderDashOffset: 0.0,
                borderJoinStyle: 'miter',
                pointBorderColor: "#3276B1",
                pointBackgroundColor: "#3276B1",
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "#3276B1",
                pointHoverBorderColor: "#3276B1",
                pointHoverBorderWidth: 2,
                pointRadius: 1,
                pointHitRadius: 10,
                data: [{/literal}{$chart_data|escape:'html':'UTF-8'}{literal}],
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