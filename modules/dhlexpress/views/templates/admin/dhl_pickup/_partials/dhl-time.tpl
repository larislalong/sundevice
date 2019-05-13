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
*  @author     PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2018 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="form-group">
  <label class="control-label col-lg-3 required" for="{block name="hour-id"}{/block}">
    {block name="time-label"}{/block}
  </label>
  <div class="col-lg-9">
    <select name="{block name="select-name"}{/block}_hour" id="{block name="hour-id"}{/block}" class="fixed-width-time">
      {for $i = 1 to 24}
        <option value="{if $i < 10}0{/if}{$i|intval}" {block name="default-hour-selected"}{/block}>
          {if $i < 10}0{/if}{$i|intval}
        </option>
      {/for}
    </select>
    <span>:</span>
    <select name="{block name="select-name"}{/block}_minute" id="{block name="minute-id"}{/block}" class="fixed-width-time">
      {for $i = 0 to 11}
        {assign var="minute" value=$i * 5}
        <option value="{if $minute < 10}0{/if}{$minute|intval}">
          {if $minute < 10}0{/if}{$minute|intval}
        </option>
      {/for}
    </select>
  </div>
</div>
