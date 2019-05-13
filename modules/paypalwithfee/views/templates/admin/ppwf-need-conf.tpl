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

<div id="paypal-with-fee-alert" class="alert alert-danger">
    <p><strong>{l s='Paypal with fee' mod='paypalwithfee'}:</strong>{l s='The tax rule has been changed recently. Please you must update and check it in paypalwithfee module.' mod='paypalwithfee'} <button id="ppwf-close" class='btn btn-default' onclick='removeElement("paypal-with-fee-alert")'>{l s='Close' mod='paypalwithfee'}</button></p>
</div>
<style>
    #paypal-with-fee-alert{
        position: fixed;
        bottom: 0%;
        z-index: 99999;
        margin-left: 0;
        margin-right: 0;
        text-align: center;
        left: 0;
        right: 0;
        background:#F2DEDE;
        color:#B64545;
        border:1px solid #EACCD1;
        padding:10px;
        border-radius:3px;
    }
</style>
<script type='text/javascript'>
function removeElement(elementId) {
    var element = document.getElementById(elementId);
    element.parentNode.removeChild(element);
}    
</script>