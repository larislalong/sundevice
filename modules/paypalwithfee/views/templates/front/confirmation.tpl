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
<p>{l s='Your order on' mod='paypalwithfee'} 
    <span class="bold">{$shop_name|escape:'html':'UTF-8'}</span> 
    {l s='is complete.' mod='paypalwithfee'}
    <br /><br />
    {l s='You have chosen PayPal with Fee as a payment.' mod='paypalwithfee'}
    <br /><br /><span class="bold">{l s='Your order will be sent very soon.' mod='paypalwithfee'}</span>
    <br /><br />{l s='For any questions or for further information, please contact our.' mod='paypalwithfee'} 
    <a href="{$base_dir_ssl|escape:'html':'UTF-8'}contact-form.php">{l s='customer support' mod='paypalwithfee'}</a>
</p>