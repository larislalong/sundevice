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

<h3>Error!</h3>
<p style="border: 1px solid;margin: 10px 0px;padding:15px 10px 15px 15px;color: #D8000C;background-color: #FFBABA;">{l s='There was an error in the payment process by Paypal.' mod='paypalwithfee'}</p>
<p style="border: 1px solid;margin: 10px 0px;padding:15px 10px 15px 15px;color: #00529B;background-color: #BDE5F8;">{l s='Kindly try again or contact your store.' mod='paypalwithfee'}</p>
<pre>
    {$error_paypal|@print_r|escape:'htmlall':'UTF-8'}
</pre>


