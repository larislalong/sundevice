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

{if isset($bulk_label_step)}
  {if $bulk_label_step == 2}
    <a href="{$link->getAdminLink('AdminDhlBulkLabel')|escape:'urldecode'}" class="btn btn-primary">
      <i class="icon icon-chevron-left"></i>
      {l s='Back to orders selection' mod='dhlexpress'}
    </a>
    <br/>
    <br/>
  {/if}
  <div class="alert alert-info">
    {if $bulk_label_step == 1}
      <p><strong>{l s='Step 1 :' mod='dhlexpress'}</strong> {l s='This is the list of DHL shipments.' mod='dhlexpress'}
      </p>
      <p>{l s='Please select which orders you want to generate a label for.' mod='dhlexpress'}</p>
      <p>{l s='You\'ll be able to configure shipment on the next step.' mod='dhlexpress'}</p>
    {elseif $bulk_label_step == 2}
      <p>
        <strong>{l s='Step 2 :' mod='dhlexpress'}</strong> {l s='This is a resume of the shipments you\'re about to generate a label for.' mod='dhlexpress'}
      </p>
      <p>{l s='Please configure shipment options down below to generate the labels.' mod='dhlexpress'}</p>
      <p>{l s='Then, you\'ll be able to print each label one at a time or all labels zipped in a single file.' mod='dhlexpress'}</p>
    {/if}
  </div>
{/if}
