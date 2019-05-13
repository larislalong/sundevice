{*
* 2007-2016 PrestaShop
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{if $status == 'ok'}
	<p class="alert alert-success">{l s='Your order on %s is complete.' sprintf=$shop_name mod='bankwirecopie'}</p>
	<div class="box">
		{l s='Please send us a bank wire with' mod='bankwirecopie'}
		<br />- {l s='Amount' mod='bankwirecopie'} <span class="price"><strong>{$total_to_pay}</strong></span>
		<br />- {l s='Name of account owner' mod='bankwirecopie'}  <strong>{if $bankwirecopieOwner}{$bankwirecopieOwner}{else}___________{/if}</strong>
		<br />- {l s='Include these details' mod='bankwirecopie'}  <strong>{if $bankwirecopieDetails}{$bankwirecopieDetails}{else}___________{/if}</strong>
		<br />- {l s='Bank name' mod='bankwirecopie'}  <strong>{if $bankwirecopieAddress}{$bankwirecopieAddress}{else}___________{/if}</strong>
		{if !isset($reference)}
			<br />- {l s='Do not forget to insert your order number #%d in the subject of your bank wire.' sprintf=$id_order mod='bankwirecopie'}
		{else}
			<br />- {l s='Do not forget to insert your order reference %s in the subject of your bank wire.' sprintf=$reference mod='bankwirecopie'}
		{/if}		<br />{l s='An email has been sent with this information.' mod='bankwirecopie'}
		<br /> <strong>{l s='Your order will be sent as soon as we receive payment.' mod='bankwirecopie'}</strong>
		<br />{l s='If you have questions, comments or concerns, please contact our' mod='bankwirecopie'} <a href="{$link->getPageLink('contact', true)|escape:'html':'UTF-8'}">{l s='expert customer support team' mod='bankwirecopie'}</a>.
	</div>
{else}
	<p class="alert alert-warning">
		{l s='We noticed a problem with your order. If you think this is an error, feel free to contact our' mod='bankwirecopie'}
		<a href="{$link->getPageLink('contact', true)|escape:'html':'UTF-8'}">{l s='customer service department.' mod='bankwirecopie'}</a>.
	</p>
{/if}
