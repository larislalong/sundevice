{*
* 2007-2016 PrestaShop
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<div id="homefeatured" class="homefeatured home-product-block col-xs-12 col-sm-6 col-lg-8">
<h4 class="block-title">{l s='Produits Phares'}</h4>
{if isset($products) && $products}
	{include file="$tpl_dir./product-list.tpl" class='homefeatured tab-pane' id='homefeatured' has_big_item=true}
{else}
	<ul class="homefeatured">
		<li class="alert alert-info">{l s='No featured products at this time.' mod='homefeatured'}</li>
	</ul>
{/if}
</div>