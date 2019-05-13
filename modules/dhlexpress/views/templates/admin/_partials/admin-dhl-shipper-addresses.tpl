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
    <label class="control-label col-lg-3" for="dhl-sender-address">
      {l s='Choose an address' mod='dhlexpress'}
    </label>

    <div class="col-lg-3">
      <select name="dhl_sender_address" class="col-lg-12" id="dhl-sender-address">
        {foreach $sender_addresses as $sender_address}
          <option value="{$sender_address.id_dhl_address|intval}" {if $sender_address.id_dhl_address == $default_sender_address}selected="selected"{/if}>{$sender_address.title|escape:'html':'utf-8'}{if $sender_address.id_dhl_address == $default_sender_address} {l s='(default)' mod='dhlexpress'} {/if}</option>
        {/foreach}
      </select>
    </div>
    <div class="col-lg-3">
      <span>{l s='Or' mod='dhlexpress'}&nbsp;</span>
      <a type="button" class="btn btn-default" href="{$update_dhl_addr_link|escape:'html':'utf-8'}&amp;addNewAddress&amp;redirectAfter={$smarty.server.REQUEST_URI|urlencode}">
        <i class="icon-plus-sign-alt"></i> {l s='Add a new add address' mod='dhlexpress'}
      </a>
    </div>
  </div>
  <div class="form-group">
    {foreach $sender_addresses as $sender_address}
      <div class="form-horizontal col-lg-offset-3 col-lg-4 dhl-sender-addresses"
           id="dhl-sender-address-{$sender_address.id_dhl_address|intval}" {if $sender_address.id_dhl_address != $default_sender_address}style="display: none"{/if}>
        <div class="panel">
          <div class="panel-heading">
            <i class="icon-envelope"></i> {$sender_address.title|escape:'html':'utf-8'}
          </div>
          <div class="form-wrapper form-wrapper-view">
            <p class="dhl-address-iso">{$sender_address.iso|escape:'html':'utf-8'}</p>
            <p class="dhl-address-company">{$sender_address.company_name|escape:'html':'utf-8'}</p>
            <p>
              {$sender_address.address1|escape:'html':'utf-8'}<br>
              {if $sender_address.address2}{$sender_address.address2|escape:'html':'utf-8'}<br>{/if}
              {if $sender_address.address3}{$sender_address.address3|escape:'html':'utf-8'}<br>{/if}
              {$sender_address.zipcode|escape:'html':'utf-8'}<br>
              {$sender_address.city|escape:'html':'utf-8'}<br>
              {if isset($sender_address.state)}{$sender_address.state|escape:'html':'utf-8'}<br>{/if}
              {$sender_address.country|escape:'html':'utf-8'}<br>
              {$sender_address.phone|escape:'html':'utf-8'}<br>
            </p>
            <p class="dhl-contact-title">{l s='Contact at this location: ' mod='dhlexpress'}</p>
            <p>
              {l s='Name: ' mod='dhlexpress'}{$sender_address.contact_name|escape:'html':'utf-8'}<br>
              {l s='Email: ' mod='dhlexpress'}{$sender_address.contact_email|escape:'html':'utf-8'}<br>
              {l s='Phone: ' mod='dhlexpress'}{$sender_address.contact_phone|escape:'html':'utf-8'}<br>
            </p>
          </div>
          <div class="panel-footer">
            <a href="{$update_dhl_addr_link|escape:'html':'utf-8'}&amp;addNewAddress&amp;id_dhl_address={$sender_address.id_dhl_address|intval}&amp;redirectAfter={$smarty.server.REQUEST_URI|urlencode}" class="btn btn-default">
              <i class="icon-edit"></i> {l s='Edit' mod='dhlexpress'}
            </a>
          </div>

        </div>
      </div>
    {/foreach}
  </div>
