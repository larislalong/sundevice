{**
* 2015-2017 Crystals Services
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
*  @author    Crystals Services Sarl <contact@crystals-services.com>
*  @copyright 2015-2017 Crystals Services Sarl
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of Crystals Services Sarl
*}
<input class="hasMoreCombinations" value="{$hasMoreCombinations|intval}" type="hidden"/>
{*foreach $supportedLteNetwork as $modelLte}
	<div id="supportedLteNetwork{$modelLte.id_product|intval}_{$modelLte.id_attribute|intval}" style="display:none;">{$modelLte.content}</div>
{/foreach}
{foreach $combinations as $combination}
<div class="row quantity-item attributes-item{if isset($buyermember) && $buyermember} buyer-member{/if}" data-id-product-attribute="{$combination.id_product_attribute|intval}" data-id-product="{$combination.product->id|intval}">
	<div class="col-lg-3 attributes-image">
		<a class="product_img_link" href="{$combination.link|escape:'html':'UTF-8'}" title="{$combination.name|escape:'html':'UTF-8'}" itemprop="url">
			<img class="replace-2x img-responsive" 
			src="{$link->getImageLink($combination.product->link_rewrite, $combination.id_image, 'product_blocklayeredcustom')|escape:'html':'UTF-8'}" 
			alt="{if !empty($combination.legend)}{$combination.legend|escape:'html':'UTF-8'}{else}{$combination.name|escape:'html':'UTF-8'}{/if}" 
			title="{if !empty($combination.legend)}{$combination.legend|escape:'html':'UTF-8'}{else}{$combination.name|escape:'html':'UTF-8'}{/if}" itemprop="image" />
		</a>
        <a class="attributes_overlay" href="{$combination.link|escape:'html':'UTF-8'}" title="{$combination.name|escape:'html':'UTF-8'}" itemprop="url">
            <span class="text"><i class="fa fa-search-plus" aria-hidden="true"></i></span>
        </a>
	</div>
	<div class="col-lg-5 attributes-text">
		<div class="attributes-label">
			<a href="{$combination.link|escape:'html':'UTF-8'}" title="{$combination.name|escape:'html':'UTF-8'}" itemprop="url">
                <span class="attributes-product-name">{$combination.name}</span>
            </a>
            {l s='SKU' mod='blocklayeredcustom'} : <span class="attributes-product-reference">{$combination.reference}</span>
		</div>
		<div class="attributes-values">
			{foreach $combination.attributes as $attribute}
				<span class="value-item">{if $attribute.id_attribute_group == $modelIdAttributeGroup}<a href="#supportedLteNetwork{$combination.product->id|intval}_{$attribute.id_attribute}" class="modelLteNetwork-link">{$attribute.name}</a>{else}{$attribute.name}{/if}</span>
			{/foreach}
		</div>
	</div>
	<div class="col-lg-4 attributes-number">
        {if isset($buyermember) && $buyermember}
            {hook h = "displayMoreProductFilter" id_product_attribute=$combination.id_product_attribute}
        {else}
            {if $combination.product->show_price && !$PS_CATALOG_MODE}
            <div class="attributes-price">{convertPrice price=$combination.price|floatval}</div>
            <div class="attributes-add-cart">
                <div class="add-cart-quantity-updown">
                    <span class="add-cart-quantity">{$defaultAddCartQuantity|intval}</span>
                    <input type="text" min="1" class="add-cart-quantity" value="{$defaultAddCartQuantity|intval}" />
                    <span class="up_down">
                        <span class="icone_up up_button"><i class="fa fa-chevron-up" aria-hidden="true"></i></span>
                        <span class="icone_down down_button"><i class="fa fa-chevron-down" aria-hidden="true"></i></span>
                    </span>
                </div>
                <span class="add-cart-button"><i class="fa fa-shopping-cart" aria-hidden="true"></i>{l s='ADD' mod='blocklayeredcustom'}</span>
            </div>
             {/if}
            <div class="attributes-quantity {if $combination.quantity>0}in-stock{else}out-of-stock{/if}">
				{if $combination.quantity>0}
				<span class="quantity" data-quantity="{$combination.quantity}">{$combination.quantity}</span> <span class="quantity-text">{l s='Available' mod='blocklayeredcustom'}</span>
				{else}{l s='Out of stock' mod='blocklayeredcustom'}
				{/if}
			</div>
		{/if}
	</div>
</div>
{/foreach*}
{include file="$tpl_dir./product-list.tpl" products=$combinations id="home-block-product-list" show_as_grid=true show_on_home=true}