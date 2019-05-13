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

{if $ps_version >= 1.6}
    <div class="row">
        <div class="col-xs-12">
        {/if}    
        <p class="payment_module">
            <a href="javascript:$('#paypal_payment_ppwf').submit()" id="paypal_process_payment_ppwf" class="{if $ps_version >= 1.6}cash{/if}" title="{l s='Pay with PayPal' mod='paypalwithfee'}" style="{if $ps_version >= 1.6}background:url('{$path|escape:'htmlall':'UTF-8'}views/img/paypal-64.png')no-repeat 15px 15px #fbfbfb{/if}">
                {if $ps_version < 1.6}
                    <img src="{$path|escape:'htmlall':'UTF-8'}views/img/paypal-64.png"/>
                {/if}
                {l s='Pay with PayPal.' mod='paypalwithfee'}
                {if $fee > 0}
                    <span>
                        {l s='Fee:' mod='paypalwithfee'} 
                        {convertPrice price=$fee|escape:'htmlall':'UTF-8'} 
                    </span>
                {/if}
            </a>
        </p>
        <form id="paypal_payment_ppwf" action="{$path_controller|escape:'htmlall':'UTF-8'}" title="{l s='Pay with PayPal' mod='paypalwithfee'}" method="post" data-fee="{$fee|escape:'htmlall':'UTF-8'}" data-fee-formatted="{convertPrice price=$fee|escape:'htmlall':'UTF-8'}" data-total="{$total_ppwf|escape:'htmlall':'UTF-8'}" data-total-formatted="{convertPrice price=$total_ppwf|escape:'htmlall':'UTF-8'}">
            <input type="hidden" name="cancelURL" value="{$cancelURL|escape:'htmlall':'UTF-8'}" />
            <input type="hidden" name="returnURL" value="{$returnURL|escape:'htmlall':'UTF-8'}" />
        </form>
        {if $ps_version >= 1.6}
        </div>
    </div>
{/if} 

