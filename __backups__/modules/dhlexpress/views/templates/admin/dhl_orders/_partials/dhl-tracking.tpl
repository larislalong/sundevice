{*
* 2007-2018 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author     PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2018 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="row">
  <div class="col-lg-12">
    <div class="panel col-lg-12">
      <div class="panel-heading">
        {l s='Track your shipments' mod='dhlexpress'}
      </div>
      <div class="row">
        <div class="col-lg-12">
          <p>{l s='You can update trackings using the two following methods:' mod='dhlexpress'}</p>

          <p><span class="dhl-number">1. </span>{l s='Manually, by clicking the button below' mod='dhlexpress'}</p>
          <a href="{$dhl_update_tracking|escape:'html':'utf-8'}"
             class="btn btn-primary">{l s='Update tracking' mod='dhlexpress'}</a>

          <p class="dhl-or">{l s='-or-' mod='dhlexpress'}</p>

          <p><span
              class="dhl-number">2. </span>{l s='Automatically, ask your hosting provider or your administrator to setup a "Cron Task" to load the following URL at the time you would like:' mod='dhlexpress'}
          </p>
          {$dhl_tracking_url|escape:'html':'utf-8'}
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  {literal}
  $(document).ready(function () {

    $('.dhl-dropdown').click(function (e) {
      e.preventDefault();
      var idDhlOrder = parseInt($(this).attr('data-dhl-order'));
      var imgPath = {/literal}'{$dhl_img_path|escape:'html':'utf-8'}loading.gif'{literal};

      $('.dhl-shipment-detail').remove();
      $('<tr class="dhl-shipment-detail-loading">' +
        '<td colspan="7" style="padding: 0">' +
        '<div style="background: #fff;">' +
        '<img src="' + imgPath + '" class="dhl-loading dhl-loading-expand-order" />' +
        '</div>' +
        '</td>' +
        '</tr>').insertAfter($('*[data-dhl-order="' + idDhlOrder + '"]').closest('tr'));
      $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '{/literal}{$link->getAdminLink('AdminDhlOrders')|escape:'urldecode'}{literal}&ajax=1&action=expandDhlOrder',
        data: 'id_dhl_order=' + idDhlOrder,
        success: function (data) {
          if (data.errors !== true) {
            $('.dhl-shipment-detail-loading').remove();
            $('<tr class="dhl-shipment-detail">' +
              '<td colspan="7" style="padding: 0">' +
              '<div class="dhl-shipment-details-container">' + data.html + '</div>' +
              '</td>' +
              '</tr>').insertAfter($('*[data-dhl-order="' + idDhlOrder + '"]').closest('tr')).hide().show('slow');
          } else {
            $('.dhl-shipment-detail-loading').remove();
            showErrorMessage(data.message);
          }
        },
        error: function (data) {

        }
      });
    });
  });
  {/literal}
</script>
