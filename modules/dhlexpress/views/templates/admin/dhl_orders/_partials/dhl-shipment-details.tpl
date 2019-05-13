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

<div id="dhl-shipment-details">
  <div class="row dhl-shipment-details-buttons">
    <div class="col-lg-12">
      <h2>{l s='DHL Shipment details' mod='dhlexpress'}</h2>
      <a href="{$link->getAdminLink('AdminDhlLabel')|urldecode}&id_order={$id_order|intval}&action=create"
         title="{l s='Create label' mod='dhlexpress'}"
         class="edit btn btn-default">
        <i class="icon-plus-circle"></i> {l s='Create label' mod='dhlexpress'}
      </a>
      <a href="#"
         data-id-dhl-order="{$id_dhl_order|intval}"
         class="btn btn-primary dhl-update-tracking">
        <i class="icon-refresh"></i> {l s='Update tracking' mod='dhlexpress'}
      </a>
      <img src="{$dhl_img_path|escape:'html':'utf-8'}loading.gif" class="dhl-tracking dhl-loading-tracking" style="display: none;" />
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12 dhl-shipment-details-table-container">
      {$html_shipment_details_table}
    </div>
  </div>
</div>
<script>
  {literal}
  $('.dhl-update-tracking').click(function(e){
    e.preventDefault();
    var idDhlOrder = parseInt($(this).attr('data-id-dhl-order'));
    var shipmentDetail = $('.dhl-shipment-details-table');
    var dhlLoader = $('.dhl-loading-tracking');
    shipmentDetail.fadeTo('fast', 0.4);
    dhlLoader.show();
    $.ajax({
      type: 'POST',
      dataType: 'json',
      url: '{/literal}{$link->getAdminLink('AdminDhlOrders')|urldecode}{literal}&ajax=1&action=updateTrackingStatus',
      data: 'id_dhl_order=' + idDhlOrder,
      success: function (data) {

        if (data.errors === false) {
          showSuccessMessage(data.message);
          dhlLoader.hide();
          $('.dhl-shipment-details-table-container').html(data.html);
          shipmentDetail.fadeTo('fast', 1);
        } else {
          showErrorMessage(data.message);
          dhlLoader.hide();
          shipmentDetail.fadeTo('fast', 1);
        }
      },
      error: function (data) {

      }
    });
  });
{/literal}
</script>
