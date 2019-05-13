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
<div class="filter_selectables_from_to clearfix">
    <div class="col-xs-6 col-sm-12 col-lg-6 filter_selectables_from">
    	<label for="selectable_{$blockFilter.block_type}_min">{l s='From:' mod='blocklayeredcustom'}</label>
    	<input id="selectable_{$blockFilter.block_type}_min" name="selectable_{$blockFilter.block_type}_min" class="selectable-field selectable-field-input min" type="text" value="{$blockFilter.selectables.min}" data-default="{$blockFilter.selectables.min}"/>
    	<span class="">{$blockFilter.unit}</span>
    </div>
    <div class="col-xs-6 col-sm-12 col-lg-6 filter_selectables_to">
    	<label for="selectable_{$blockFilter.block_type}_max">{l s='To:' mod='blocklayeredcustom'}</label>
    	<input id="selectable_{$blockFilter.block_type}_max" name="selectable_{$blockFilter.block_type}_max" class="selectable-field selectable-field-input max" type="text" value="{$blockFilter.selectables.max}" data-default="{$blockFilter.selectables.max}"/>
    	<span class="">{$blockFilter.unit}</span>
    </div>
</div>