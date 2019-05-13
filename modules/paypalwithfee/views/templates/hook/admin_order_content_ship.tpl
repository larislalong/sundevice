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
<div class="tab-pane active" id="paypalwithfee">
    <h4 class="visible-print">{l s='Paypal with fee' mod='paypalwithfee'} <span class="badge">(1)</span></h4>
    <div class="form-horizontal">
        <div class="table-responsive">
            <div class="ppwf">
                {if !$ppwfv}{l s='* The cost of paypal with fee, are added into shipping cost.' mod='paypalwithfee'}{/if}
                <hr/>
                <p>{l s='Paypal fee:' mod='paypalwithfee'} <strong>{displayPrice currency=$id_currency price=$fee['fee']|escape:'htmlall':'UTF-8'}</strong></p>
                {if Configuration::get('PPAL_CUSTOM_INVOICE') == 1}<p>{l s='Tax:' mod='paypalwithfee'} <strong>{$fee['tax_rate']|escape:'html':'UTF-8'} %</strong></p>{/if}
                <p>{l s='Paypal Transaction ID:' mod='paypalwithfee'} <strong>{$fee['transaction_id']|escape:'html':'UTF-8'}</strong></p>
                {if isset($paypalwf.total_paypal) && $paypalwf.total_paypal > 0}{*from ppwf 3.5*}
                    <p class="alert {if $paypalwf.total_amount <> $paypalwf.total_paypal}alert-danger{else} alert-success{/if}">
                        {l s='Prestashop payment' mod='paypalwithfee'}: {convertPrice price=$paypalwf.total_amount|escape:'htmlall':'UTF-8'} - {l s='Paypal payment' mod='paypalwithfee'}: {convertPrice price=$paypalwf.total_paypal|escape:'htmlall':'UTF-8'}
                    </p>
                {/if}
            </div>
        </div>
            
        {if Configuration::get('PS_INVOICE') && count($invoices_collection_) && $invoice_number_ && !$ppwfv}
            <div class="well">
                <a href="{$form_go_ppwf_generatepdf|escape:'html':'UTF-8'}&submitAction=down_invoice_ppal" class="btn btn-default _blank" target="_blank">
                    <i class="icon-download"></i>
                    {l s='Download Invoice' mod='paypalwithfee'}
                </a>
            </div>
        {/if}
    </div>
    <hr>
    <div id="paypalwithfee_refund">
        <h4 class="">
            {l s='Paypal with fee refund' mod='paypalwithfee'} <span class="badge">{$refund|@count|escape:'htmlall':'UTF-8'}</span>
        </h4>
        <form id="paypalwithfee_refund_form" class="well" method="post" action="{$form_go_ppwf_refund|escape:'htmlall':'UTF-8'}" onSubmit="if (!confirm('{l s='This process not has turning back. Are you sure that do you want continue?' mod='paypalwithfee'}'))
                    return false;">
            {if $refund|@count > 0 && !empty($refund)}
                <p>{l s='You can do a partial refund. Can not do a full refund after a partial refund.' mod='paypalwithfee'}</p>
                <label><input type='radio' id="ppwf_partial_refund" name='refund' value='0' checked> <span>Partial refund</span></label>
                {else}
                <p>{l s='You can do a partial refund or full refund of paypal payment.' mod='paypalwithfee'}</p>
                <label><input type='radio' id="ppwf_total_refund" name='refund' value='1'> <span>Full refund</span></label><br/>
                <label><input type='radio' id="ppwf_partial_refund" name='refund' value='0' checked> <span>Partial refund</span></label>
                {/if}
            <div id="ppwf_refund_content">
                <label for="ppwf_refund_amount">{l s='Amount to refund:' mod='paypalwithfee'}
                    <input type="text" id="ppwf_refund_amount" max="{if $max_refund}{$fee.total_amount|string_format:'%.2f' - $max_refund|string_format:'%.2f'|escape:'htmlall':'UTF-8'}{else}{$fee.total_amount|string_format:'%.2f'|escape:'htmlall':'UTF-8'}{/if}" name="ppwf_refund_amount"></label> <input class="btn btn-default" type="submit" name="ppwf_refund" value="{l s='Refund' mod='paypalwithfee'}"/>
            </div>
        </form>
        {if isset($ppwfmessage_ok)}<p class="alert alert-success">{$ppwfmessage_ok|escape:'htmlall':'UTF-8'}</p>{/if}
        {if isset($ppwfmessage_error)}<p class="alert alert-danger">{$ppwfmessage_error|escape:'htmlall':'UTF-8'}</p>{/if}
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th><span class="title_box ">{l s='Date' mod='paypalwithfee'}</span></th>
                        <th><span class="title_box ">{l s='Amount' mod='paypalwithfee'}</span></th>
                        <th><span class="title_box ">{l s='Transaction ID' mod='paypalwithfee'}</span></th>
                    </tr>
                </thead>
                <tbody>
                    {if $refund|@count > 0 && !empty($refund)}
                        {foreach from=$refund item=ref}
                            <tr>
                                <td>{$ref.date|escape:'htmlall':'UTF-8'}</td>
                                <td>{$ref.amount|string_format:"%.2f"|escape:'htmlall':'UTF-8'}</td>
                                <td>{$ref.transaction_id|escape:'htmlall':'UTF-8'}</td>
                            </tr>
                        {/foreach}
                    {/if}
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#shipping').removeClass('active');

        $(document).ready(function () {
            $('#paypalwithfee_refund input[name="refund"]').change(function () {
                var how_refund = $(this).val();
                if (how_refund == 1) {
                    $('#ppwf_refund_amount').val(parseFloat($('#ppwf_refund_amount').attr('max')).toFixed(2)).prop('disabled', true);
                } else {
                    $('#ppwf_refund_amount').val('').prop('disabled', false);
                }
            });

            $('#ppwf_refund_amount').change(function () {
                //$(this).val(parseFloat($("ppwf_refund_amount").val(),10).toFixed(2));
            });

        });
    });
</script>