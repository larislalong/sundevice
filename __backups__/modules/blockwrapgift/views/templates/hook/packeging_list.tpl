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
<div class="packaging_left packaging_block" id="divPackegingLeft">
	<div class="packaging_left_title">
		<span>{l s='Packaging' mod='blockwrapgift'}</span>
		<a href="#helpAvailablePackaging" class="packaging-help-link"><i class="fa fa-question-circle" aria-hidden="true"></i>{l s='Available packagings' mod='blockwrapgift'}</a>
	</div>
	<div class="clear"></div>
	<div class="packaging_attribute_list packaging_header_list">
		<ul>
		{$defaultChecked = false}
		{foreach from=$packeging_list item='packeging'}
			<li class="packaging_header_item packaging-item {if !$defaultChecked} selected{/if}" data-id="{$packeging.id_bwg_wrap_gift}">
				<span>{$packeging.name}</span>
			</li>
			{$defaultChecked = true}
		{/foreach}
		</ul>
	</div>
	<div class="clear"></div>
	<div class="packeging_content_list">
		{$defaultChecked = false}
		{foreach from=$packeging_list item='packeging'}
			<div class="row packaging_content_item {if $defaultChecked} unvisible{/if}" id="packaging_content_item{$packeging.id_bwg_wrap_gift}">
				<div class="col-sm-7 block-text">
					<div class="block-price">
						<span class="price">{convertPrice price=$packeging.price|floatval}</span>
					</div>
					<div class="clear"></div>
					<div class="block-description">
						{$packeging.description}
					</div>
				</div>
				<div class="col-sm-5 block-image">
					{if !empty($packeging.image)}
					<img alt="" src="{$image_folder}{$packeging.image}" />
					{/if}
				</div>
			</div>
			{$defaultChecked = true}
		{/foreach}
	</div>
	<div class="clear"></div>
</div>