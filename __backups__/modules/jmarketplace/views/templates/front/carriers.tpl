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
        {l s='Your shipping and carriers' mod='jmarketplace'}
    </span>
{/capture} 

<div class="row">
    <div class="column col-xs-12 col-sm-12 col-lg-3"{if $show_menu_options == 0} style="display:none;"{/if}>
        {hook h='displayMarketplaceWidget'}
    </div>
    
    <div class="col-sm-12{if $show_menu_options == 1} col-lg-9{else} col-lg-12{/if}">
        {if isset($errors) && $errors}
            {include file="./errors.tpl"}
        {/if}

        <div id="jsellershipping" class="box">
            <h1 class="page-subheading">
                {l s='Your shipping and carriers' mod='jmarketplace'}
                <div class="form-group pull-right">
                    <a href="{$link->getModuleLink('jmarketplace', 'addcarrier', array(), true)|escape:'html':'UTF-8'}" class="btn btn-default button button-small">
                        <span>
                            <i class="icon-plus fa fa-plus"></i> 
                            {l s='Add new carrier' mod='jmarketplace'}
                        </span>
                    </a>
                </div> 
            </h1>

            {if $carriers && count($carriers) > 0}
                <div class="table-responsive">
                    <table id="order-list" class="table table-bordered footab">
                        <thead>
                            <tr>
                                <th class="first_item">
                                    {l s='Image' mod='jmarketplace'}
                                </th>
                                <th class="item">
                                    {l s='Carrier name' mod='jmarketplace'}
                                </th>
                                <th class="item">
                                    {l s='Delay' mod='jmarketplace'}
                                </th>
                                <th class="item">
                                    {l s='Status' mod='jmarketplace'}
                                </th>
                                <th class="item">
                                    {l s='Actions' mod='jmarketplace'}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$carriers item=carrier name=sellercarriers}
                                <tr>
                                    <td class="first_item">
                                        {if isset($carrier.logo)}
                                            <img class="img-responsive" src="{$carrier.logo|escape:'html':'UTF-8'}" width="32" height="32" />
                                        {/if}
                                    </td>
                                    <td class="item">
                                        {$carrier.name|escape:'html':'UTF-8'}
                                    </td>
                                    <td class="item">
                                        {$carrier.delay|escape:'html':'UTF-8'}
                                    </td>
                                    <td class="item">
                                        {if $carrier.active == 1}
                                            <a href="{$carrier.desactivate_carrier_link|escape:'html':'UTF-8'}" title="{l s='Desactivate' mod='jmarketplace'}">
                                                <i class="icon-check fa fa-check"></i>
                                            </a>
                                        {else}
                                            <a href="{$carrier.activate_carrier_link|escape:'html':'UTF-8'}" title="{l s='Activate' mod='jmarketplace'}">
                                                <i class="icon-remove fa fa-remove"></i>
                                            </a>
                                        {/if}
                                    </td>
                                    <td class="item">
                                        <a class="btn btn-primary btn-xs btn-edit" href="{$carrier.edit_carrier_link|escape:'html':'UTF-8'}" title="{l s='Edit' mod='jmarketplace'}">
                                            <i class="icon-pencil fa fa-pencil"></i> 
                                            {l s='Edit' mod='jmarketplace'}
                                        </a>
                                        <a class="btn btn-primary btn-xs delete_product" href="{$carrier.delete_carrier_link|escape:'html':'UTF-8'}" title="{l s='Delete' mod='jmarketplace'}">
                                            <i class="icon-trash-o fa fa-trash-o"></i> 
                                            {l s='Delete' mod='jmarketplace'}
                                        </a>
                                    </td>
                                </tr>
                            {/foreach}
                        </tbody>
                    </table>
                </div>
            {else}
                <p class="alert alert-info">
                    {l s='There are not carriers.' mod='jmarketplace'}
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