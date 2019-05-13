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

{if $is_product_seller}
    <div class="tabs">
        <h4 class="buttons_bottom_block">{l s='Information of seller' mod='jmarketplace'}</h4>
        <div class="seller_info">
            <span class="seller_name">{$seller->name|escape:'html':'UTF-8'}</span> 
            {if $show_seller_rating}
                <div class="average_rating">
                    <a href="{$url_seller_comments|escape:'html':'UTF-8'}" title="{l s='View comments about' mod='jmarketplace'} {$seller->name|escape:'html':'UTF-8'}">
                        {section name="i" start=0 loop=5 step=1}
                            {if $averageMiddle le $smarty.section.i.index}
                                <div class="star"></div>
                            {else}
                                <div class="star star_on"></div>
                            {/if}
                        {/section}({$averageTotal|intval})
                    </a>
                </div>
            {/if}
        </div>
        <div class="seller_links">
            {if $show_seller_profile}
                <p class="link_seller_profile"> 
                    <a title="{l s='View seller profile' mod='jmarketplace'}" href="{$seller_link|escape:'html':'UTF-8'}">
                        <i class="icon-user fa fa-user"></i>  {l s='View seller profile' mod='jmarketplace'}
                    </a>
                </p>
            {/if}
            {if $show_contact_seller}
                <p class="link_contact_seller">
                    <a title="{l s='Contact seller' mod='jmarketplace'}" href="{$url_contact_seller|escape:'html':'UTF-8'}">
                        <i class="icon-comments fa fa-comment"></i> {l s='Contact seller' mod='jmarketplace'}
                    </a>
                </p>
            {/if}
            {if $show_seller_favorite}
                <p class="link_seller_favorite">
                    <a title="{l s='Add to favorite seller' mod='jmarketplace'}" href="{$url_favorite_seller|escape:'html':'UTF-8'}">
                        <i class="icon-heart fa fa-heart"></i> {l s='Add to favorite seller' mod='jmarketplace'}
                    </a>
                </p>
            {/if}
            <p class="link_seller_products">
                <a title="{l s='View more products of this seller' mod='jmarketplace'}" href="{$url_seller_products|escape:'html':'UTF-8'}">
                    <i class="icon-list fa fa-list"></i> {l s='View more products of this seller' mod='jmarketplace'}
                </a>
            </p>
        </div>
    </div>
{/if}
{if $show_manage_carriers == 1}
<script type="text/javascript">
var confirmDeleteProductsOtherSeller = "{l s='In your cart there are productos of other seller. Are you sure you want to add this product and delete the products you have in your cart?' mod='jmarketplace'}";
var confirm_controller_url = '{$link->getModuleLink('jmarketplace', 'addproductcartconfirm', array(), true)|escape:'html':'UTF-8'}';
var order_url = '{$link->getPageLink('order')|escape:'html':'UTF-8'}';
var PS_REWRITING_SETTINGS = "{$PS_REWRITING_SETTINGS|intval}";
</script>
<script type="text/javascript" src="{$addsellerproductcart_js|escape:'html':'UTF-8'}"></script>
{else}
<script type="text/javascript">
var PS_REWRITING_SETTINGS = "{$PS_REWRITING_SETTINGS|intval}";
</script>
{/if}