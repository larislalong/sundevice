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
  <div class="col-lg-12">
    <a class="btn btn-xl btn-primary"
       href="{$link->getAdminLink('AdminModules')|escape:'html':'utf-8'}&configure=dhlexpress&addNewAddress"
       id="dhl-add-new-address">
      <i class="icon-plus-sign"></i> {l s='Add a new address' mod='dhlexpress'}</a>
  </div>
  <div class="clearfix"></div>
  {foreach $dhl_addresses as $addr}
    <div id="module_form" class="defaultForm form-horizontal col-lg-4">
      <div class="panel" id="fieldset_0">
        <div class="panel-heading">
          <i class="icon-envelope"></i> {$addr.iso|escape:'html':'utf-8'} - {$addr.city|escape:'html':'utf-8'}
        </div>
        <div class="form-wrapper form-wrapper-view">
          <p class="dhl-address-iso">{$addr.iso|escape:'html':'utf-8'}</p>
          <p class="dhl-company-name">{$addr.company_name|escape:'html':'utf-8'}</p>
          <p>
            {$addr.address1|escape:'html':'utf-8'}<br>
            {if $addr.address2}{$addr.address2|escape:'html':'utf-8'}<br>{/if}
            {if $addr.address3}{$addr.address3|escape:'html':'utf-8'}<br>{/if}
            {$addr.zipcode|escape:'html':'utf-8'}<br>
            {$addr.city|escape:'html':'utf-8'}<br>
            {if isset($addr.state)}{$addr.state|escape:'html':'utf-8'}<br>{/if}
            {$addr.country|escape:'html':'utf-8'}<br>
            {$addr.phone|escape:'html':'utf-8'}<br>
          </p>
          <p class="dhl-contact-title">{l s='Contact at this location: ' mod='dhlexpress'}</p>
          <p>
            {l s='Name: ' mod='dhlexpress'}{$addr.contact_name|escape:'html':'utf-8'}<br>
            {l s='Email: ' mod='dhlexpress'}{$addr.contact_email|escape:'html':'utf-8'}<br>
            {l s='Phone: ' mod='dhlexpress'}{$addr.contact_phone|escape:'html':'utf-8'}<br>
          </p>
        </div>
        <div class="panel-footer">
          <a type="button"
             href="{$link->getAdminLink('AdminModules')|escape:'html':'utf-8'}&configure=dhlexpress&addNewAddress&id_dhl_address={$addr.id_dhl_address|intval}"
             class="btn btn-default pull-right">
            <i class="process-icon-edit"></i> {l s='Edit address' mod='dhlexpress'}
          </a>
          <a type="button"
             href="{$link->getAdminLink('AdminModules')|escape:'html':'utf-8'}&configure=dhlexpress&deleteAddress&id_dhl_address={$addr.id_dhl_address|intval}"
             class="btn btn-default pull-right">
            <i class="process-icon-trash icon-trash"></i> {l s='Delete address' mod='dhlexpress'}
          </a>
        </div>
      </div>
    </div>
    {foreachelse}
    <div id="dhl-no-addresses">
      <div class="alert alert-info">
        {l s='Please create your first address.' mod='dhlexpress'}
      </div>
    </div>
  {/foreach}
  <div class="clearfix"></div>
</div>
