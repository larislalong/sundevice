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

    $('#PPAL_CUSTOM_INVOICE_on').click(function () {
        $('#PPAL_TAX_FEE').removeAttr('disabled');
    });

    $('#PPAL_CUSTOM_INVOICE_off').click(function () {
        $('#PPAL_TAX_FEE').attr('disabled', 'disabled');
    });

});

