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

{extends file="./dhl_label.tpl"}

{block name="dhl-info-message"}
  <div class="alert alert-info">
    <p>
      {l s='If you want to create a label related to an existing order, please navigate to ' mod='dhlexpress'}
      <a href="{$link->getAdminLink('AdminDhlOrders')|escape:'html':'utf-8'}">{l s='DHL Orders' mod='dhlexpress'}</a>
    </p>
  </div>
{/block}

{block name="dhl-hidden-input"}
  <input type="hidden" name="dhl_free_label" value="1" />
{/block}

{block name="dhl-form-title"}
  {l s='Generate a free label' mod='dhlexpress'}
{/block}

{block name="dhl-customer-address"}
  {include file="./_partials/dhl-customer-address-form.tpl"}
{/block}

{block name="dhl-shipping-details"}{/block}

{block name="label-download-link"}{/block}
