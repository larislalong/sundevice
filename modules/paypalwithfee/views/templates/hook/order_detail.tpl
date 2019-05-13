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
{if $custom_invoice}
    <script type='text/javascript'>
        $(document).ready(function () {
            $('#block-order-detail .info-order a').each(function () {
                var hrefs = $.trim(this.href);
                var textTofind = "pdf-invoice";

                if (hrefs.indexOf(textTofind) != -1) {
                    var id_order_pdf = $(this).attr("href").split("id_order=").pop();
                    $(this).attr("href", ppwf_ajax_url + '?id_order=' + id_order_pdf);
                }
            });
        });
    </script>
{/if}
<div class="ppwf">
    <p class="alert alert-info">{l s='* Paypal with fee payment method have a cost of' mod='paypalwithfee'}
        <strong> {displayPrice currency=$id_currency price=$fee|escape:'htmlall':'UTF-8'} </strong>{l s='that has been added into shipping cost.' mod='paypalwithfee'}
    </p>
</div>