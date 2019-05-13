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
        {l s='Your products' mod='jmarketplace'}
    </span>
{/capture}

{if isset($confirmation) && $confirmation}
    <div class="row">
        <div class="col-lg-12">
            {if $moderate}
                <div class="alert alert-success">
                    {l s='Your product has been successfully saved. It is pending approval.' mod='jmarketplace'} 
                </div>
            {else}
                <div class="alert alert-success">
                    {l s='Your product has been successfully saved.' mod='jmarketplace'} 
                </div>
            {/if}
        </div>
    </div>
{/if}        

<div class="row">
    <div class="column col-xs-12 col-sm-12 col-lg-3"{if $show_menu_options == 0} style="display:none;"{/if}>
        {hook h='displayMarketplaceWidget'}
    </div>
    
    <div class="col-sm-12{if $show_menu_options == 1} col-lg-9{else} col-lg-12{/if}">    
        <div class="box">
            <h1 class="page-subheading">
                {l s='Your products' mod='jmarketplace'} ({$num_products|intval})
            </h1>
 
            {if isset($errors) && $errors}
                {include file="./errors.tpl"}
            {/if}
            
            <div class="btn-toolbar mb-2" role="toolbar">
                <div class="btn-group mr-2" role="group">
                    <a href="{$link->getModuleLink('jmarketplace', 'addproduct', array(), true)|escape:'html':'UTF-8'}" class="btn btn-default button">
                        <i class="icon-plus fa fa-plus"></i> 
                        <span>{l s='Add new product' mod='jmarketplace'}</span>
                    </a>
                </div>
                <div class="btn-group mr-2" role="group">
                    <a class="btn btn-default button open_search_box" href="#">
                        <i class="icon-search fa fa-search"></i> 
                        <span>{l s='Order and Search' mod='jmarketplace'}</span>
                    </a>
                </div>
            </div>
            
            <div class="box search_box">
                <hr>
                <div class="row">
                    <div class="col-xs-12 col-md-5 col-lg-8">
                        
                        <form action="{$link->getModuleLink('jmarketplace', 'sellerproducts', array(), true)|escape:'html':'UTF-8'}" method="post" class="std">
                            <div class="row">
                                <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-4">
                                    <label for="orderby">
                                        {l s='Order by' mod='jmarketplace'}
                                    </label>
                                    <select name="orderby">
                                        <option value="name">
                                            {l s='Product name' mod='jmarketplace'}
                                        </option>
                                        <option value="date_add"{if $order_by == 'date_add'} selected="selected"{/if}>
                                            {l s='Date add' mod='jmarketplace'}
                                        </option>
                                        <option value="date_upd"{if $order_by == 'date_upd'} selected="selected"{/if}>
                                            {l s='Date update' mod='jmarketplace'}
                                        </option>
                                        <option value="price"{if $order_by == 'price'} selected="selected"{/if}>
                                            {l s='Price' mod='jmarketplace'}
                                        </option>
                                        <option value="quantity"{if $order_by == 'quantity'} selected="selected"{/if}>
                                            {l s='Quantity' mod='jmarketplace'}
                                        </option>
                                        <option value="active"{if $order_by == 'active'} selected="selected"{/if}>
                                            {l s='Status' mod='jmarketplace'}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-4">
                                    <label for="orderway">
                                        {l s='Order way' mod='jmarketplace'}
                                    </label>
                                    <select name="orderway">
                                        <option value="desc"{if $order_way == 'desc'} selected="selected"{/if}>
                                            {l s='Descending' mod='jmarketplace'}
                                        </option>
                                        <option value="asc"{if $order_way == 'asc'} selected="selected"{/if}>
                                            {l s='Ascending' mod='jmarketplace'}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-4" style="margin-top: 28px;">
                                    <button type="submit" name="submitOrder" class="btn">
                                        <span>{l s='Order' mod='jmarketplace'}</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-xs-12 col-md-7 col-lg-4">
                        <form action="{$link->getModuleLink('jmarketplace', 'sellerproducts', array(), true)|escape:'html':'UTF-8'}" method="post" class="std">
                            <input type="hidden" name="orderby" value="{$order_by|escape:'html':'UTF-8'}" />
                            <input type="hidden" name="orderway" value="{$order_way|escape:'html':'UTF-8'}" />
                            <div class="input-group input-group-sm search-seller-products">
                                <input class="search_query form-control" type="text" name="search_query" id="search_seller_product_query"{if $search_query != ""} value="{$search_query|escape:'html':'UTF-8'}" {else} placeholder="{l s='Search' mod='jmarketplace'}"{/if}>
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-info btn-flat">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
                <hr>
            </div>
            
            {if $products && count($products) > 0}
                <form id="form-products" method="post" action="{$link->getModuleLink('jmarketplace', 'sellerproducts', array(), true)|escape:'html':'UTF-8'}">
                    <div class="table-responsive">
                        <table id="order-list" class="table table-hover footab">
                            <thead>
                                <tr>
                                    {if $show_active_product == 1 || $show_delete_product == 1}
                                        <th class="first_item text-center hidden-xs"></th>
                                    {/if}
                                    <th class="item text-center">
                                        {l s='Image' mod='jmarketplace'}
                                    </th>
                                    <th class="item">
                                        {l s='Product name' mod='jmarketplace'}
                                    </th>
                                    <th class="item">
                                        {l s='Price' mod='jmarketplace'}
                                    </th>
                                    <th class="item">
                                        {l s='Quantity' mod='jmarketplace'}
                                    </th>
                                    <th class="item text-center">
                                        {l s='Status' mod='jmarketplace'}
                                    </th>
                                    <th class="item text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                            {foreach from=$products item=product name=sellerproducts}
                                <tr>
                                    {if $show_active_product == 1 || $show_delete_product == 1}
                                        <td class="first_item text-center">
                                            <input name="productBox[]" value="{$product.id_product|intval}" class="ck not_uniform" type="checkbox">
                                        </td>
                                    {/if}
                                    <td class="item text-center">
                                        {if $product.id_image}
                                            <img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'small_default')|escape:'html':'UTF-8'}" />
                                        {else}
                                            <img src="{$img_prod_dir|escape:'html':'UTF-8'}{$lang_iso|escape:'html':'UTF-8'}-default-small_default.jpg" />
                                        {/if}
                                    </td>
                                    <td class="item">
                                        <strong>
                                            {$product.name|escape:'html':'UTF-8'}
                                        </strong>
                                        <br/>
                                        <small>
                                            {l s='Date add' mod='jmarketplace'}: 
                                            <i class="icon-calendar fa fa-calendar"></i> 
                                            - {dateFormat date=$product.date_add full=0} 
                                            - <i class="icon-time fa fa-clock-o"></i> 
                                            {$product.date_add|escape:'htmlall':'UTF-8'|substr:11:5}
                                        </small>
                                        <br/>
                                        <small>
                                            {l s='Date update' mod='jmarketplace'}: 
                                            <i class="icon-calendar fa fa-calendar"></i> 
                                            - {dateFormat date=$product.date_upd full=0} 
                                            - <i class="icon-time fa fa-clock-o"></i> 
                                            {$product.date_upd|escape:'htmlall':'UTF-8'|substr:11:5}
                                        </small>
                                    </td>
                                    <td class="item">
                                        {$product.final_price|escape:'htmlall':'UTF-8'}
                                    </td>
                                    <td class="item">
                                        {$product.real_quantity|intval} 
                                        {if $product.real_quantity == 1}
                                            {l s='unit' mod='jmarketplace'}
                                        {else}
                                            {l s='units' mod='jmarketplace'}
                                        {/if}
                                    </td>
                                    <td class="item text-center">
                                        {if $show_active_product == 1}
                                            {if $product.active == 1}
                                                <a href="{$product.active_product_link|escape:'html':'UTF-8'}">
                                                    <i class="icon-check fa fa-check" title="{l s='Disable' mod='jmarketplace'} {$product.name|escape:'html':'UTF-8'}"></i>
                                                </a>
                                            {else} 
                                                <a href="{$product.active_product_link|escape:'html':'UTF-8'}">
                                                    <i class="icon-times fa fa-times" title="{l s='Enable' mod='jmarketplace'} {$product.name|escape:'html':'UTF-8'}"></i>
                                                </a>
                                            {/if}
                                        {else}
                                            {if $product.active == 1}
                                                <i class="icon-check fa fa-check" title="{l s='Active' mod='jmarketplace'}"></i>
                                            {else} 
                                                <i class="icon-times fa fa-times" title="{l s='Pending approval' mod='jmarketplace'}"></i>
                                            {/if}
                                        {/if}
                                    </td>
                                    <td class="item text-center">
                                        <div class="btn-group" role="group">
                                            <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                {l s='Actions' mod='jmarketplace'} 
                                                <span class="caret"></span>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                <a class="dropdown-item" href="{$link->getProductLink($product.id_product)|escape:'html':'UTF-8'}" title="{l s='View' mod='jmarketplace'} {$product.name|escape:'html':'UTF-8'}" target="_blank">
                                                    <i class="icon-eye fa fa-eye"></i> 
                                                    {l s='View' mod='jmarketplace'}
                                                </a>
                                                {if $show_edit_product == 1}
                                                    <a class="dropdown-item" href="{$product.edit_product_link|escape:'html':'UTF-8'}" title="{l s='Edit' mod='jmarketplace'} {$product.name|escape:'html':'UTF-8'}">
                                                        <i class="icon-pencil fa fa-pencil"></i> 
                                                        {l s='Edit' mod='jmarketplace'}
                                                    </a>
                                                {/if}
                                                {if $show_delete_product == 1}
                                                    <a class="dropdown-item" href="{$product.delete_product_link|escape:'html':'UTF-8'}" title="{l s='Delete' mod='jmarketplace'} {$product.name|escape:'html':'UTF-8'}" onclick="return confirm('Are you sure want to delete this product?');">
                                                        <i class="icon-trash-o fa fa-trash-o"></i> 
                                                        {l s='Delete' mod='jmarketplace'}
                                                    </a>
                                                {/if}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            {/foreach}
                        </tbody>
                    </table>
                </div>

                <div class="row" style="margin-top:15px;">
                    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">
                        {if $show_active_product == 1 || $show_delete_product == 1}
                            <div class="btn-group" role="group">
                                <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {l s='Bulk actions' mod='jmarketplace'} <span class="caret"></span>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                    <a class="dropdown-item" id="check_all" href="#">
                                        <i class="icon-check-square fa fa-check-square"></i> 
                                        {l s='Select all' mod='jmarketplace'}
                                    </a>
                                    <a class="dropdown-item" id="uncheck_all" href="#">
                                        <i class="icon-square fa fa-square"></i> 
                                        {l s='Unselect all' mod='jmarketplace'}
                                    </a>
                                    {if $show_active_product == 1}
                                        <a class="bulk_all dropdown-item" id="submitBulkenableSelectionproduct" href="#">
                                            <i class="icon-check-square fa fa-check-square"></i> 
                                            {l s='Enable selection' mod='jmarketplace'}
                                        </a>
                                        <a class="bulk_all dropdown-item" id="submitBulkdisableSelectionproduct" href="#">
                                            <i class="icon-square fa fa-square"></i> 
                                            {l s='Disable selection' mod='jmarketplace'}
                                        </a>
                                    {/if}
                                    {if $show_delete_product == 1}
                                        <a class="bulk_all dropdown-item" id="submitBulkdeleteSelectionproduct" href="#">
                                            <i class="icon-trash-o fa fa-trash-o"></i> 
                                            {l s='Delete selected' mod='jmarketplace'}
                                        </a>
                                    {/if}
                                </div>
                            </div>
                         {/if}
                    </div>
                    {if $num_pages > 1}
                    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-8">
                        <div class="dataTables_paginate paging_simple_numbers pull-right">
                            <ul class="pagination">
                                {if $current_page <= $num_pages}
                                    <li class="paginate_button next">
                                        <a tabindex="0" data-dt-idx="{$current_page - 1|intval}" href="{$link->getModuleLink('jmarketplace', 'sellerproducts', array(), true)|escape:'html':'UTF-8'}?orderby={$order_by|escape:'html':'UTF-8'}&orderway={$order_way|escape:'html':'UTF-8'}&page={$current_page - 1|intval}">
                                            {l s='Previus' mod='jmarketplace'}
                                        </a>
                                    </li>
                                {/if}
                                {for $foo=1 to $num_pages}
                                    <li class="paginate_button{if $current_page == $foo} active{/if}">
                                        <a tabindex="0" data-dt-idx="{$foo|intval}" href="{$link->getModuleLink('jmarketplace', 'sellerproducts', array(), true)|escape:'html':'UTF-8'}?orderby={$order_by|escape:'html':'UTF-8'}&orderway={$order_way|escape:'html':'UTF-8'}&page={$foo|intval}">
                                            {$foo|intval}
                                        </a>
                                    </li>
                                {/for}  
                                {if $current_page < $num_pages}
                                    <li class="paginate_button next">
                                        <a tabindex="0" data-dt-idx="{$current_page + 1|intval}" href="{$link->getModuleLink('jmarketplace', 'sellerproducts', array(), true)|escape:'html':'UTF-8'}?orderby={$order_by|escape:'html':'UTF-8'}&orderway={$order_way|escape:'html':'UTF-8'}&page={$current_page + 1|intval}">
                                            {l s='Next' mod='jmarketplace'}
                                        </a>
                                    </li>
                                {/if}
                            </ul>
                        </div>
                    </div>
                    {/if}
                </div>
                </form>
            {else}
                <p class="alert alert-info">
                    {l s='There are not products.' mod='jmarketplace'}
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
<script type="text/javascript">
var confirmProductDelete = "{l s='Are you sure you want to delete your product?' mod='jmarketplace'}";
var PS_REWRITING_SETTINGS = "{$PS_REWRITING_SETTINGS|intval}";
</script>