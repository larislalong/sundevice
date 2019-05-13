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
    <div style="-webkit-text-size-adjust:none;background-color:#fff;width:650px;font-family:Open-sans, sans-serif;color:#555454;font-size:13px;line-height:18px;margin:auto" >
        <table class="table table-mail" style="width:100%;margin-top:10px;-moz-box-shadow:0 0 5px #afafaf;-webkit-box-shadow:0 0 5px #afafaf;-o-box-shadow:0 0 5px #afafaf;box-shadow:0 0 5px #afafaf;filter:progid:DXImageTransform.Microsoft.Shadow(color=#afafaf,Direction=134,Strength=5)">
            <tbody>
                <tr>
                    <th style="background-color: #4d4d4d; color: #ffffff"><strong>{l s='Concept' mod='paypalwithfee'}</strong></th>
                    <th style="background-color: #4d4d4d; color: #ffffff">{l s='Fee' mod='paypalwithfee'}</th>
                </tr>
                <tr>
                    <td style="background-color: #dddddd;">{l s='PayPal with Fee' mod='paypalwithfee'}</td>
                    <td style="background-color: #dddddd;">{displayPrice price=$fee|escape:'html'}</td>
                </tr>
            </tbody>
            <tfoot>
                {l s='* Fee has been added into shipping cost.' mod='paypalwithfee'}
            </tfoot>
            </tr>
        </table>
    </div>
{/if}        