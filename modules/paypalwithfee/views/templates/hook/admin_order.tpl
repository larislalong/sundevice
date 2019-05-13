{*
* 2017 4webs
*
* DEVELOPED By 4webs Prestashop Platinum Partner
*
* @author    4webs
* @copyright 4webs 2017
* @version 4.0.2
* @category payment_gateways
* @license 4webs
*}

<div id="paypalwithfee_refund" class="panel">
    <div class="panel-heading">
        <i class="icon-paypal"></i>
        {l s='Paypal with fee refund' mod='paypalwithfee'} <span class="badge">{$refund|@count|escape:'htmlall':'UTF-8'}</span>
    </div>
    <form id="paypalwithfee_refund_form" class="well" method="post" action="{$form_go|escape:'htmlall':'UTF-8'}" onSubmit="if (!confirm('{l s='This process not has turning back. Are you sure that do you want continue?' mod='paypalwithfee'}'))
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
<script type="text/javascript">
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
</script>