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
        {l s='Your payment' mod='jmarketplace'}
    </span>
{/capture}

{if isset($confirmation) && $confirmation}
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-success">
                {l s='Your payment settings has been edited ok.' mod='jmarketplace'} 
            </div>
        </div>
    </div>
{else}
    {if isset($errors) && $errors}
        {include file="./errors.tpl"}
    {/if}
{/if}

<div class="row">
    <div class="column col-xs-12 col-sm-12 col-lg-3"{if $show_menu_options == 0} style="display:none;"{/if}>
        {hook h='displayMarketplaceWidget'}
    </div>
    
    <div class="col-sm-12{if $show_menu_options == 1} col-lg-9{else} col-lg-12{/if}">
        <div class="box">
            <h1 class="page-subheading">
                {l s='Your payment' mod='jmarketplace'}
            </h1>
            {if $payments && count($payments)}
                <form action="{$link->getModuleLink('jmarketplace', 'sellerpayment', array(), true)|escape:'html':'UTF-8'}" method="post" class="std">
                    <div class="form-group">
                        <label class="control-label">
                            {l s='Select method of payment' mod='jmarketplace'}
                        </label>
                        <div>
                            {foreach from=$payments item=payment name=sellerpayments}
                                {if ($payment.payment == 'paypal' AND $show_paypal) OR ($payment.payment == 'bankwire' AND $show_bankwire)}
                                    <div class="radio">
                                        <label for="active_{$payment.payment|escape:'html':'UTF-8'}">
                                            <input type="radio"{if $payment.active == 1} checked="checked"{/if} value="1" id="active_{$payment.payment|escape:'html':'UTF-8'}" name="active_{$payment.payment|escape:'html':'UTF-8'}">
                                            {if $payment.payment == 'paypal'}
                                                {l s='PayPal' mod='jmarketplace'}
                                            {else if $payment.payment == 'bankwire'}
                                                {l s='Bankwire' mod='jmarketplace'}
                                            {/if}
                                        </label>
                                    </div>  
                                {/if}
                            {/foreach}     
                        </div>    
                    </div> 
                    
                    {foreach from=$payments item=payment name=sellerpayments}
                        {if ($payment.payment == 'paypal' AND $show_paypal) OR ($payment.payment == 'bankwire' AND $show_bankwire)}
                            <div id="content_{$payment.payment|escape:'html':'UTF-8'}" class="form-group{if $payment.active == 0} hidden{/if}">
                                {if $payment.payment == 'paypal'}
                                    <label for="{$payment.payment|escape:'html':'UTF-8'}">
                                        {l s='Configure paypal account (Email)' mod='jmarketplace'}
                                    </label>
                                    <input class="form-control" type="text" name="{$payment.payment|escape:'html':'UTF-8'}" id="{$payment.payment|escape:'html':'UTF-8'}" value="{$payment.account|escape:'html':'UTF-8'}" />
                                {else if $payment.payment == 'bankwire'}
                                    <label for="{$payment.payment|escape:'html':'UTF-8'}">
                                        {l s='Enter your number account' mod='jmarketplace'}
                                    </label>
                                    <textarea id="{$payment.payment|escape:'html':'UTF-8'}" name="{$payment.payment|escape:'html':'UTF-8'}" class="form-control" cols="40" rows="4">{$payment.account|escape:'html':'UTF-8'}</textarea>   
                                {/if}
                            </div>
                        {/if}
                    {/foreach}
                    <div class="form-group">
                            <button type="submit" name="submitPayment" class="btn btn-default button button-medium">
                                <span>
                                    {l s='Save' mod='jmarketplace'}
                                    <i class="icon-chevron-right right"></i>
                                </span>
                            </button>
                        </div>
                    </fieldset>
                </form>
            {else}
                <p class="alert alert-info">
                    {l s='There is not method of payment.' mod='jmarketplace'}
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