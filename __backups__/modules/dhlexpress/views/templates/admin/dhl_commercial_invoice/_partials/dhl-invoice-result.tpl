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

{if $errors}
  <div id="dhl-label-error" class="alert alert-danger">
    {foreach $description as $text}
      <p>{$text|escape:'html':'utf-8'}</p>
    {/foreach}
  </div>
{else}
  {if isset($alreadyGenerated) && $alreadyGenerated === true}
    <div class="alert alert-warning">
      <p>{l s='You already generated the invoice for this label.' mod='dhlexpress'}</p>
      <p>
        {l s='If you want to generate a new one, please delete this invoice first on' mod='dhlexpress'}
        <a href="{$link->getAdminLink('AdminDhlOrders')|escape:'html':'utf-8'}">{l s ='DHL Orders' mod='dhlexpress'}</a>
      </p>
    </div>
  {/if}
  <div id="dhl-document-download">
    <div class="dhl-picto-div">
      <p>
        <a target="_blank" href="{$link->getAdminLink('AdminDhlCommercialInvoice')|escape:'html':'utf-8'}&ajax=1&action=downloadinvoice&id_dhl_label={$id_dhl_label|intval}">
          {l s='Download the invoice' mod='dhlexpress'}
        </a>
      </p>
      <img class="dhl-picto" src="{$dhl_img_path|escape:'html':'utf-8'}invoice.png">
    </div>
  </div>
{/if}
