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
<div class="panele">
    {if isset($confirmation) && $confirmation}
        <p class="alert alert-success">
            {l s='Your response has been successfully sent.' mod='jmarketplace'}
        </p>
    {/if}
    <table id="incidence-list" class="table table-bordered footab">
        <tr>
            <td class="first_item">
                {l s='Reference' mod='jmarketplace'}
            </td>
            <td class="item">
                {$incidence->reference|escape:'html':'UTF-8'}
            </td>
        </tr>
        {if $order_reference}
            <tr>
                <td class="first_item">
                    {l s='Order Reference' mod='jmarketplace'}
                </td>
                <td class="item">
                    <a href="{$url_order|escape:'html':'UTF-8'}">
                        {$order_reference|escape:'html':'UTF-8'}
                    </a>
                </td>
            </tr>
        {/if}
        {if $seller}
            <tr>
                <td class="first_item">
                    {l s='Seller name' mod='jmarketplace'}
                </td>
                <td class="item">
                    <a href="{$url_seller|escape:'html':'UTF-8'}">
                        {$seller->name|escape:'html':'UTF-8'}
                    </a>
                </td>
            </tr>
        {/if}
        {if $customer}
            <tr>
                <td class="first_item">
                    {l s='Customer name' mod='jmarketplace'}
                </td>
                <td class="item">
                    <a href="{$url_customer|escape:'html':'UTF-8'}">
                        {$customer->firstname|escape:'html':'UTF-8'} 
                        {$customer->lastname|escape:'html':'UTF-8'}
                    </a>
                </td>
            </tr>
        {/if}
        {if $product_name}
            <tr>
                <td class="first_item">
                    {l s='Product name' mod='jmarketplace'}
                </td>
                <td class="item">
                    <a href="{$url_product|escape:'html':'UTF-8'}">
                        {$product_name|escape:'html':'UTF-8'}
                    </a>
                </td>
            </tr>
        {/if}
        <tr>
            <td class="first_item">
                {l s='Date add' mod='jmarketplace'}
            </td>
            <td class="item">
                <i class="icon-calendar fa fa-calendar"></i> 
                - {dateFormat date=$incidence->date_add full=0} 
                - <i class="icon-time fa fa-clock-o"></i> 
                {$incidence->date_add|escape:'htmlall':'UTF-8'|substr:11:5}
            </td>
        </tr>
        <tr>
            <td class="first_item">
                {l s='Date updated' mod='jmarketplace'}
            </td>
            <td class="item">
                <i class="icon-calendar fa fa-calendar"></i> 
                - {dateFormat date=$incidence->date_upd full=0} 
                - <i class="icon-time fa fa-clock-o"></i> 
                {$incidence->date_upd|escape:'htmlall':'UTF-8'|substr:11:5}
            </td>
        </tr>
    </table>
    <br/>
</div>
<div class="panel">
    <h3>
        {l s='Messages' mod='jmarketplace'} 
        <span class="badge">
            {count($messages)|intval}
        </span>
    </h3>
    
    {if $messages && count($messages)}
        {foreach from=$messages item=message name=messages}
            <div class="panel">
                <div class="message{if $message.id_seller == 0} customer{else} employee{/if}">
                    <h3 class="author">
                        {if $message.id_customer != 0}
                            {l s='Customer' mod='jmarketplace'} 
                            {$message.customer_firstname|escape:'html':'UTF-8'} 
                            {$message.customer_lastname|escape:'html':'UTF-8'}
                        {else if $message.id_seller != 0}
                            {l s='Seller' mod='jmarketplace'} 
                            {$message.seller_name|escape:'html':'UTF-8'} 
                        {else}
                            {l s='Administrator' mod='jmarketplace'} 
                            {$message.employee_firstname|escape:'html':'UTF-8'} 
                            {$message.employee_lastname|escape:'html':'UTF-8'}
                        {/if}
                        - <i class="icon-calendar"></i> 
                        - {dateFormat date=$message.date_add full=0} 
                        - <i class="icon-time"></i> 
                        {$message.date_add|escape:'htmlall':'UTF-8'|substr:11:5}
                    </h3>
                    <div class="description">
                        {$message.description|escape:'html':'UTF-8'|nl2br}
                    </div>
                    {if $message.attachment}
                        <div class="panel-footer">
                            <a href="{$attachment_dir|escape:'html':'UTF-8'}{$message.id_seller_incidence|escape:'html':'UTF-8'}/{$message.attachment|escape:'html':'UTF-8'}" target="_blank">
                                <i class="icon-paperclip fa fa-paperclip"></i> 
                                {$message.attachment|escape:'html':'UTF-8'}
                            </a>
                        </div>
                    {/if}
                </div>
            </div>
        {/foreach}
        <div class="panel">
            <form action="{$url_post|escape:'html':'UTF-8'}" method="post" class="std" enctype="multipart/form-data">  
                <fieldset>                 
                    <div class="form-group">
                        <label for="description">
                            {l s='Add response' mod='jmarketplace'} 
                        </label>
                        <textarea class="form-control" name="description" cols="40" rows="7"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="fileUpload">
                            {l s='File attachment' mod='jmarketplace'}
                        </label>
                        <input type="file" name="attachment" class="form-control" />
                    </div>
                    <div class="panel-footer">
                        <button class="btn btn-default pull-right" name="submitResponse" id="incidence_state_form_submit_btn" value="1" type="submit">
                            <i class="process-icon-save"></i>
                            {l s='Send' mod='jmarketplace'}
                        </button>
                    </div>
                </fieldset>
            </form>
        </div>
    {else}
        <p class="alert alert-info">
            {l s='There is not messages.' mod='jmarketplace'}
        </p>
    {/if}
</div>
{/block}