{*
 * 2014 4webs
 *
 * DEVELOPED By 4webs.es Prestashop Platinum Partner
 *
 * @author    4webs
 * @copyright 4webs 2014
 * @license   4webs
 * @version 4.0.2
 * @category payment_gateways
 *}
<form id="paypal_payment_form1" action='{$rutacontroller|escape:'htmlall':'UTF-8'}' data-ajax='false' title="{l s='Pay with PayPal' mod='paypalwithfee'}" method="post">
    <input type="hidden" name="cancelURL" value="{$cancelURL|escape:'htmlall':'UTF-8'}" />
    <input type="hidden" name="returnURL" value="{$returnURL|escape:'htmlall':'UTF-8'}" />
    {if $fee > 0}
        <input type="hidden" name="text_fee" value="{l s='Fee' mod='paypalwithfee'}" />
    {/if}
</form>