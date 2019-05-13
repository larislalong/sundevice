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
{if $fee > 0}
    <table style="width: 50%;">
        <tr>
            <th style="background-color: #4d4d4d; color: #ffffff"><strong>{l s='Concept' mod='paypalwithfee'}</strong></th>
            <th style="background-color: #4d4d4d; color: #ffffff">{l s='Fee' mod='paypalwithfee'}</th>
        </tr>
        <tr>
            <td style="background-color: #dddddd;">{l s='PayPal with Fee' mod='paypalwithfee'}</td>
            <td style="background-color: #dddddd;">{displayPrice price=$fee|escape:'html'}</td>
        </tr>
        <tfoot>
            {l s='* Fee has been added into shipping cost.' mod='paypalwithfee'}
        </tfoot>
    </table>
{/if}