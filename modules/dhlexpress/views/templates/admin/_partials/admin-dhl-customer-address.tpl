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
  <div class="form-horizontal col-lg-offset-3 col-lg-4" id="dhl-customer-part">
    <div class="panel">
      <div class="panel-heading">
        <i class="icon-envelope"></i> {$customer_address->alias|escape:'html':'utf-8'}
        <span class="pull-right">#{$customer_address->id_customer|intval}</span>
      </div>
      <div class="form-wrapper form-wrapper-view">
        <input type="hidden" name="dhl_customer_address" value="{$customer_address->id|intval}" />
        <p class="dhl-address-iso">{$customer_country_iso|escape:'html':'utf-8'}</p>
        <p class="dhl-address-company">{$customer_address->company|escape:'html':'utf-8'}</p>
        <p>
          {$customer_address->firstname|escape:'html':'utf-8'} {$customer_address->lastname|escape:'html':'utf-8'}<br />
          {$customer_address->address1|escape:'html':'utf-8'}<br>
          {if $customer_address->address2}{$customer_address->address2|escape:'html':'utf-8'}<br>{/if}
          {$customer_address->postcode|escape:'html':'utf-8'}<br>
          {$customer_address->city|escape:'html':'utf-8'}<br>
          {if isset($customer_address->state)}{$customer_address->state|escape:'html':'utf-8'}<br>{/if}
          {$customer_address->country|escape:'html':'utf-8'}<br>
          {if $customer_address->phone}{$customer_address->phone|escape:'html':'utf-8'}{else}{$customer_address->phone_mobile|escape:'html':'utf-8'}{/if}<br>
        </p>
      </div>
      <div class="panel-footer">
        <a href="{$update_addr_link|escape:'html':'utf-8'}&amp;back={$smarty.server.REQUEST_URI|urlencode}" class="btn btn-default">
          <i class="icon-edit"></i> {l s='Edit' mod='dhlexpress'}
        </a>
      </div>

    </div>
  </div>
</div>
