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

<div class="blc-slider" data-type="{$blockFilter.block_type}" data-format="{$blockFilter.format}" data-unit="{$blockFilter.unit}"
	data-min="{$blockFilter.selectables.min}" data-max="{$blockFilter.selectables.max}" data-unit="{$blockFilter.unit}" 
	data-values="{implode(',', $blockFilter.selectables.values)}">
	<label for="{$blockFilter.block_type}">
		{l s='Range:' mod='blocklayeredcustom'}
	</label> 
	<span id="layered_{$blockFilter.block_type}_range"></span>
	<div class="layered_slider_container">
		<div class="layered_slider" id="layered_{$blockFilter.block_type}_slider" data-type="{$blockFilter.block_type}" data-format="{$blockFilter.format}" data-unit="{$blockFilter.unit}"></div>
	</div>
</div>