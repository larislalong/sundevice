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
<div id="sellerproduct" class="panel">
    <h3>{l s='Information of product' mod='jmarketplace'} {$product->name|escape:'html':'UTF-8'}</h3>
    <table class="table tableDnD">
        <thead>
            <tr>
                <th class="col-md-2"></th>
                <th class="col-md-10"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>
                        {l s='Seller name' mod='jmarketplace'}:
                    </strong>
                </td>
                <td>
                    {$seller_name|escape:'html':'UTF-8'}
                </td>
            </tr>
            <tr>
                <td>
                    <strong>
                        {l s='Status' mod='jmarketplace'}:
                    </strong>
                </td>
                <td>
                    {if $product->active == 1}
                        {l s='Active' mod='jmarketplace'}
                    {else}
                        {l s='No active' mod='jmarketplace'}
                    {/if}
                </td>
            </tr>
            <tr>
                <td>
                    <strong>
                        {l s='Date add' mod='jmarketplace'}:
                    </strong>
                </td>
                <td>
                    {$product->date_add|escape:'html':'UTF-8'}
                </td>
            </tr>
            <tr>
                <td>
                    <strong>
                        {l s='Date update' mod='jmarketplace'}:
                    </strong>
                </td>
                <td>{$product->date_upd|escape:'html':'UTF-8'}</td>
            </tr>
            {if $show_reference == 1}
                <tr>
                    <td>
                        <strong>
                            {l s='Reference' mod='jmarketplace'}:
                        </strong>
                    </td>
                    <td>
                        {$product->reference|escape:'html':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if $show_isbn == 1}
                <tr>
                    <td>
                        <strong>
                            {l s='ISBN' mod='jmarketplace'}:
                        </strong>
                    </td>
                    <td>
                        {$product->isbn|escape:'html':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if $show_ean13 == 1}
                <tr>
                    <td>
                        <strong>
                            {l s='Ean13' mod='jmarketplace'}:
                        </strong>
                    </td>
                    <td>
                        {$product->ean13|escape:'html':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if $show_upc == 1}
                <tr>
                    <td>
                        <strong>
                            {l s='UPC' mod='jmarketplace'}:
                        </strong>
                    </td>
                    <td>
                        {$product->upc|escape:'html':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if $show_available_order == 1}
                <tr>
                    <td>
                        <strong>
                            {l s='Available for order' mod='jmarketplace'}:
                        </strong>
                    </td>
                    <td>
                        {if $product->available_for_order == 1}
                            {l s='Yes' mod='jmarketplace'}
                        {else}
                            {l s='No' mod='jmarketplace'}
                        {/if}
                    </td>
                </tr>
            {/if}
            {if $show_show_price == 1}
                <tr>
                    <td>
                        <strong>
                            {l s='Show price' mod='jmarketplace'}:
                        </strong>
                    </td>
                    <td>
                        {if $product->show_price == 1}
                            {l s='Yes' mod='jmarketplace'}
                        {else}
                            {l s='No' mod='jmarketplace'}
                        {/if}
                    </td>
                </tr>
            {/if}
            {if $show_online_only == 1}
                <tr>
                    <td>
                        <strong>
                            {l s='Online only' mod='jmarketplace'}:
                        </strong>
                    </td>
                    <td>
                        {if $product->online_only == 1}
                            {l s='Yes' mod='jmarketplace'}
                        {else}
                            {l s='No' mod='jmarketplace'}
                        {/if}
                    </td>
                </tr>
            {/if}
            {if $show_condition == 1}
                <tr>
                    <td>
                        <strong>
                            {l s='Condition' mod='jmarketplace'}:
                        </strong>
                    </td>
                    <td>
                        {if $product->condition == 'new'}
                            {l s='New' mod='jmarketplace'}
                        {elseif $product->condition == 'used'}
                            {l s='Used' mod='jmarketplace'}
                        {else}
                            {l s='Refurbished' mod='jmarketplace'}
                        {/if}
                    </td>
                </tr>
            {/if}
            {if $show_pcondition == 1}
                <tr>
                    <td>
                        <strong>
                            {l s='Show condition on the product page' mod='jmarketplace'}:
                        </strong>
                    </td>
                    <td>
                        {if $product->show_condition == 1}
                            {l s='Yes' mod='jmarketplace'}
                        {else}
                            {l s='No' mod='jmarketplace'}
                        {/if}
                    </td>
                </tr>
            {/if}
            {if $show_desc_short == 1}
                <tr>
                    <td>
                        <strong>
                            {l s='Short description' mod='jmarketplace'}:
                        </strong>
                    </td>
                    <td>
                        {$product->description_short} {*This is HTML content*}
                    </td>
                </tr>
            {/if}
            {if $show_desc == 1}
                <tr>
                    <td>
                        <strong>
                            {l s='Description' mod='jmarketplace'}:
                        </strong>
                    </td>
                    <td>
                        {$product->description} {*This is HTML content*}
                    </td>
                </tr>
            {/if}
            {if $show_wholesale_price == 1}
                <tr>
                    <td>
                        <strong>
                            {l s='Wholesale price' mod='jmarketplace'}:
                        </strong>
                    </td>
                    <td>
                        {convertPrice price=$product->wholesale_price}
                    </td>
                </tr>
            {/if}
            {if $show_price == 1}
                <tr>
                    <td>
                        <strong>
                            {l s='Price (tax excl.)' mod='jmarketplace'}:
                        </strong>
                    </td>
                    <td>
                        {convertPrice price=$product->price}
                    </td>
                </tr>
            {/if}
            {if $show_unit_price == 1 AND $product->unit_price_ratio != 0}
                <tr>
                    <td>
                        <strong>
                            {l s='Unit price (tax excl.)' mod='jmarketplace'}:
                        </strong>
                    </td>
                    <td>
                        {convertPrice price=($product->price / $product->unit_price_ratio)} 
                        {$product->unity|escape:'html':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if $show_tax == 1}
                <tr>
                    <td>
                        <strong>
                            {l s='Tax' mod='jmarketplace'}:
                        </strong>
                    </td>
                    <td>
                        {$tax_name|escape:'html':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if $show_on_sale == 1}
                <tr>
                    <td>
                        <strong>
                            {l s='On sale' mod='jmarketplace'}:
                        </strong>
                    </td>
                    <td>
                        {if $product->on_sale == 1}
                            {l s='Yes' mod='jmarketplace'}
                        {else}
                            {l s='No' mod='jmarketplace'}
                        {/if}
                    </td>
                </tr>
            {/if}
            {if $show_meta_keywords == 1}
                <tr>
                    <td>
                        <strong>
                            {l s='Meta keywords' mod='jmarketplace'}:
                        </strong>
                    </td>
                    <td>
                        {$product->meta_keywords|escape:'html':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if $show_meta_title == 1}
                <tr>
                    <td>
                        <strong>
                            {l s='Meta title' mod='jmarketplace'}:
                        </strong>
                    </td>
                    <td>
                        {$product->meta_title|escape:'html':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if $show_meta_desc == 1}
                <tr>
                    <td>
                        <strong>
                            {l s='Meta description' mod='jmarketplace'}:
                        </strong>
                    </td>
                    <td>
                        {$product->meta_description|escape:'html':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if $show_link_rewrite == 1}
                <tr>
                    <td>
                        <strong>
                            {l s='Friendly URL' mod='jmarketplace'}:
                        </strong>
                    </td>
                    <td>
                        {$product->link_rewrite|escape:'html':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if $show_categories == 1}
                <tr>
                    <td>
                        <strong>
                            {l s='Categories' mod='jmarketplace'}:
                        </strong>
                    </td>
                    <td>
                        {$categories_string|escape:'html':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if $show_suppliers == 1}
                <tr>
                    <td>
                        <strong>
                            {l s='Supplier' mod='jmarketplace'}:
                        </strong>
                    </td>
                    <td>
                        {$supplier_name|escape:'html':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if $show_manufacturers == 1}
                <tr>
                    <td>
                        <strong>
                            {l s='Manufacturer' mod='jmarketplace'}:
                        </strong>
                    </td>
                    <td>
                        {$manufacturer_name|escape:'html':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if $product->is_virtual == 0}
                {if $show_width == 1}
                    <tr>
                        <td>
                            <strong>
                                {l s='Width' mod='jmarketplace'}:
                            </strong>
                        </td>
                        <td>
                            {$product->width|escape:'html':'UTF-8'}
                        </td>
                    </tr>
                {/if}
                {if $show_height == 1}
                    <tr>
                        <td>
                            <strong>
                                {l s='Height (cm)' mod='jmarketplace'}:
                            </strong>
                        </td>
                        <td>
                            {$product->height|escape:'html':'UTF-8'}
                        </td>
                    </tr>
                {/if}
                {if $show_depth == 1}
                    <tr>
                        <td>
                            <strong>
                                {l s='Depth (cm)' mod='jmarketplace'}:
                            </strong>
                        </td>
                        <td>
                            {$product->depth|escape:'html':'UTF-8'}
                        </td>
                    </tr>
                {/if}
                {if $show_weight == 1}
                    <tr>
                        <td>
                            <strong>
                                {l s='Weight (kg)' mod='jmarketplace'}:
                            </strong>
                        </td>
                        <td>
                            {$product->weight|escape:'html':'UTF-8'}
                        </td>
                    </tr>
                {/if}
                {if $show_shipping_product == 1} 
                    <tr>
                        <td>
                            <strong>
                                {l s='Carriers' mod='jmarketplace'}:
                            </strong>
                        </td>
                        <td>
                            {if isset($carriers) AND $carriers}
                                <ul style="margin:0px;padding-left:10px;">
                                    {foreach from=$carriers item=carrier}
                                        <li>
                                            {$carrier.name|escape:'html':'UTF-8'}
                                        </li>
                                    {/foreach}
                                </ul>
                             {/if}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>
                                {l s='Shipping cost by product' mod='jmarketplace'}:
                            </strong>
                        </td>
                        <td>
                            {$product->additional_shipping_cost|escape:'html':'UTF-8'}
                        </td>
                    </tr>
                {/if}
                {if $show_attributes == 1} 
                    {if isset($attributes) AND count($attributes) > 0} 
                        <tr>
                            <td>
                                <strong>
                                    {l s='Combinations' mod='jmarketplace'}:
                                </strong>
                            </td>
                            <td>
                                {if isset($attributes) && $attributes}
                                    <ul style="margin:0px;padding-left:10px;">
                                        {foreach from=$attributes item=attribute}
                                            <li>
                                                {$attribute.attribute_designation|escape:'html':'UTF-8'} 
                                                - {$attribute.quantity|intval} 
                                                {l s='units' mod='jmarketplace'}
                                            </li>
                                        {/foreach}
                                    </ul>
                                {/if}  
                            </td>
                        </tr>  
                    {/if}
                {/if}
            {else}
                <tr>
                    <td>
                        <strong>
                            {l s='Virtual file' mod='jmarketplace'}:
                        </strong>
                    </td>
                    <td>
                        <a href="{$url_download|escape:'html':'UTF-8'}" title="{l s='Download this product' mod='jmarketplace'}"> 
                           {$display_filename|escape:'html':'UTF-8'}
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>
                            {l s='Number of allowed downloads' mod='jmarketplace'}:
                        </strong>
                    </td>
                    <td>
                        {$virtual_product_nb_downloable|escape:'html':'UTF-8'}
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>
                            {l s='Expiration date' mod='jmarketplace'}:
                        </strong>
                    </td>
                    <td>
                        {$virtual_product_expiration_date|escape:'html':'UTF-8'}
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>
                            {l s='Number of days' mod='jmarketplace'}:
                        </strong>
                    </td>
                    <td>
                        {$virtual_product_nb_days|escape:'html':'UTF-8'}
                    </td>
                </tr>
            {/if}
            {if $show_quantity == 1 AND !isset($attributes)}
                <tr>
                    <td>
                        <strong>
                            {l s='Quantity' mod='jmarketplace'}:
                        </strong>
                    </td>
                    <td>
                        {$real_quantity|intval}
                    </td>
                </tr>
            {/if}  
            {if $show_minimal_quantity == 1}
                <tr>
                    <td>
                        <strong>
                            {l s='Minimal quantity' mod='jmarketplace'}:
                        </strong>
                    </td>
                    <td>
                        {$product->minimal_quantity|intval}
                    </td>
                </tr>
            {/if}
            {if $show_availability == 1}
                <tr>
                    <td>
                        <strong>
                            {l s='Availability preferences (Behavior when out of stock)' mod='jmarketplace'}:
                        </strong>
                    </td>
                    <td>
                        {if $out_of_stock == 0}
                            {l s='Deny orders' mod='jmarketplace'}
                        {else if $out_of_stock == 1}
                            {l s='Allow orders' mod='jmarketplace'}
                        {else}
                            {l s='Use default behavior (Deny orders)' mod='jmarketplace'}
                        {/if}
                    </td>
                </tr>
            {/if}
            {if $show_available_now == 1}
                <tr>
                    <td>
                        <strong>
                            {l s='Available now' mod='jmarketplace'}:
                        </strong>
                    </td>
                    <td>
                        {$product->available_now|escape:'html':'UTF-8'}
                    </td>
                </tr>
            {/if}  
            {if $show_available_later == 1}
                <tr>
                    <td>
                        <strong>
                            {l s='Available later' mod='jmarketplace'}:
                        </strong>
                    </td>
                    <td>
                        {$product->available_later|escape:'html':'UTF-8'}
                    </td>
                </tr>
            {/if}  
            {if $show_available_date == 1}
                <tr>
                    <td>
                        <strong>
                            {l s='Available date' mod='jmarketplace'}:
                        </strong>
                    </td>
                    <td>
                        {if $product->available_date != '0000-00-00'}
                            {$product->available_date|escape:'html':'UTF-8'}
                        {else}
                            {l s='Always' mod='jmarketplace'}
                        {/if}  
                    </td>
                </tr>
            {/if}  
            {if $show_images == 1}
                {if isset($images)}
                    <tr>
                        <td>
                            <strong>
                                {l s='Images' mod='jmarketplace'}:
                            </strong>
                        </td>
                        <td>
                            <ul class="thumbnails">
                                {foreach from=$images item=image name=thumbnails}
                                    {assign var=imageIds value="`$product->id`-`$image.id_image`"}
                                    {if !empty($image.legend)}
                                        {assign var=imageTitle value=$image.legend|escape:'html':'UTF-8'}
                                    {else}
                                        {assign var=imageTitle value=$product->name|escape:'html':'UTF-8'}
                                    {/if}
                                    <li id="thumbnail_{$image.id_image|intval}"{if $smarty.foreach.thumbnails.last} class="last"{/if}>
                                        <img class="img-responsive" src="{$link->getImageLink($product->link_rewrite, $imageIds, 'cart_default')|escape:'html':'UTF-8'}" title="{$imageTitle|escape:'htmlall':'UTF-8'}" />
                                    </li>
                                {/foreach}
                            </ul>
                        </td>
                    </tr>
                {/if}
            {/if}
            {if $show_features == 1}
                <tr>
                    <td>
                        <strong>
                            {l s='Features' mod='jmarketplace'}:
                        </strong>
                    </td>
                    <td>
                        {if isset($features) AND $features}
                            <ul style="margin:0px;padding-left:10px;">
                                {foreach from=$features item=feature}
                                    <li>
                                        <strong>
                                            {$feature.name|escape:'html':'UTF-8'}:
                                        </strong> 
                                        {$feature.value|escape:'html':'UTF-8'}
                                    </li>
                                {/foreach}
                            </ul>
                        {/if}  
                    </td>
                </tr>     
            {/if}
            {if $show_attachments == 1}
                <tr>
                    <td>
                        <strong>
                            {l s='Attachments' mod='jmarketplace'}:
                        </strong>
                    </td>
                    <td>
                        {if isset($product_attachments) AND $product_attachments}
                            <ul style="margin:0px;padding-left:10px;">
                                {foreach from=$product_attachments item=attachment name=attachments}
                                    <li>
                                        <a href="{$link->getPageLink('attachment', true, NULL, "id_attachment={$attachment.id_attachment}")|escape:'html':'UTF-8'}">
                                            <i class="icon-download fa fa-download"></i>
                                            {$attachment.file_name|escape:'html':'UTF-8'} 
                                            ({Tools::formatBytes($attachment.file_size, 2)|escape:'html':'UTF-8'})
                                        </a>
                                    </li>
                                {/foreach}
                            </ul>
                        {else}
                            {l s='There are not attachments.' mod='jmarketplace'}
                        {/if}
                    </td>
                </tr>
            {/if} 
            {hook h='displayMarketplaceAdminSellerProduct'}
        </tbody>
    </table>

    <div class="panel-footer">
        <a class="btn btn-default" href="index.php?controller=AdminSellerProducts&amp;id_product={$product->id|intval}&amp;statusproduct&amp;token={$token|escape:'html':'UTF-8'}">
            {if $product->active == 1}
                <i class="icon-remove"></i> 
                {l s='Desactivate' mod='jmarketplace'}
            {else}
                <i class="icon-check fa fa-check"></i> 
                {l s='Activate' mod='jmarketplace'}
            {/if}
        </a>
        {if $product->active == 0}
            <a class="btn btn-default reasons_for_rejection" href="#rejection_form">
                <i class="icon-close fa fa-close"></i> 
                {l s='Reject' mod='jmarketplace'}
            </a>
            <div id="rejection_form" class="panel" style="display:none;">
                <h3>
                    {l s='Reasons for rejected'  mod='jmarketplace'}
                </h3>
                <form action="index.php?controller=AdminSellerProducts&amp;id_product={$product->id|intval}&amp;rejected&amp;token={$token|escape:'html':'UTF-8'}" method="post">
                    <div class="form-group">
                        <label for="reasons">
                            {l s='Reasons' mod='jmarketplace'}
                        </label>
                        <textarea id="reasons" name="reasons" class="form-control" cols="40" rows="4"></textarea>   
                    </div>

                    <div class="form-group">
                        <button type="submit" name="submitSellerProductRejection" class="btn btn-default button button-medium">
                            <i class="icon-close fa fa-close"></i> 
                            {l s='Reject' mod='jmarketplace'}
                        </button>
                    </div>
                </form>
            </div>
        {/if}
        <a class="btn btn-default" href="{$url_product|escape:'html':'UTF-8'}">
            <i class="icon-edit fa fa-edit"></i> 
            {l s='Edit' mod='jmarketplace'}
        </a>
        <a class="btn btn-default" href="{$link->getProductLink($product->id)|escape:'html':'UTF-8'}" target="_blank">
            <i class="icon-search fa fa-search"></i> 
            {l s='View' mod='jmarketplace'}
        </a>
        <a class="btn btn-default" href="index.php?controller=AdminSellerProducts&amp;token={$token|escape:'html':'UTF-8'}">
            <i class="icon-close fa fa-close"></i> 
            {l s='Cancel' mod='jmarketplace'}
        </a>
    </div>   
</div>
{/block}