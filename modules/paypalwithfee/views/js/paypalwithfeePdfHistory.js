/**
 * 2014 4webs
 *
 * DEVELOPED By 4webs.es Prestashop Platinum Partner
 *
 * @author    4webs
 * @copyright 4webs 2014
 * @license   4webs
 * @version 4.0.2
 * @category payment_gateways
 */

$(document).ready(function () {
    $('#order-list tr .history_invoice a').each(function () {
        href = $(this);
        id_order_ajax = $(this).attr("href").split("id_order=").pop();
        $.ajax({
            type: 'POST',
            headers: {"cache-control": "no-cache"},
            url: ppwf_ajax_url,
            async: false,
            data: 'ajax=true&action=IsOrderPpwf&id_order=' + id_order_ajax + '&token=' + static_token,
            dataType: "json",
            success: function (res) {
                if (res.is_ppwf)
                {
                    href.attr('href', res.href);
                }
            }
        });
    });
});
