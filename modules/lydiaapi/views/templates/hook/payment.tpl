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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="row">
	<div class="col-xs-12">
		<p class="payment_module" id="lydiaapi_payment_button">
			{if $cart->getOrderTotal() < 2}
				<a href="" style="background: url({$module_dir|escape:'htmlall':'UTF-8'}/lydia-logo.png) 15px 15px no-repeat #fbfbfb;padding-left: 200px;" title= alt="{l s='Payer via la plateforme Lydia' mod='lydiaapi'}">
					{l s='Minimum amount required in order to pay with my payment module:' mod='lydiaapi'} {convertPrice price=2}
				</a>
			{else}
				<a id="lydiaapi_btn" href="#" style="background: url({$module_dir|escape:'htmlall':'UTF-8'}/lydia-logo.png) 15px 15px no-repeat #fbfbfb;padding-left: 200px;" title= alt="{l s='Payer via la plateforme Lydia' mod='lydiaapi'}">
					{l s='Payer via la plateforme Lydia' mod='lydiaapi'}
				</a>
			{/if}
		</p>
	</div>
</div>
<form id="form_lydia_api" method="post" action="{$form_url}" style="display:none">
	{foreach $form_data as $key=>$value}
	<input type="hidden" value="{$value}" name="{$key}">
	{/foreach}
</form>
{literal}
<script type="text/javascript">
	$(document).ready(function() {
		$("#lydiaapi_btn").click(function(e){
			e.preventDefault();
			$("#form_lydia_api").submit();
		});
	});
</script>
{/literal}