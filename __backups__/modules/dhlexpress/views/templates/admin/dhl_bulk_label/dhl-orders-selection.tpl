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

<form class="form-horizontal"
      action="#"
      name="dhl_bulk_label_form"
      id="dhl-bulk-label-form"
      method="post"
      enctype="multipart/form-data">
  <div class="dhl-bulk-form-errors alert alert-danger" style="display: none;">

  </div>
  <div class="dhl-label-processing" style="display: none;">
    <h2>{l s='Please wait while labels are processing' mod='dhlexpress'} <img src="{$dhl_img_path|escape:'htmlall':'utf-8'}loading.gif"/></h2>
  </div>
  <div class="dhl-label-processing-done alert alert-success" style="display: none;">
    {l s='All labels have been processed' mod='dhlexpress'}
  </div>
  <button class="btn dhl-bulk-label-download" style="display: none" name="submitBulkLabelDownload">
    <i class="process-icon- icon-arrow-circle-o-down"></i>
    {l s='Download all labels successfully generated (single zip file)' mod='dhlexpress'}
  </button>
  <br/>
  <br/>
  <div class="tab-content panel collapse in">
    <div>
      <table class="table dhl-bulk-label-table">
        <thead>
        <tr>
          <th></th>
          <th><span class="title_box text-center">{l s='Reference' mod='dhlexpress'}</span></th>
          <th><span class="title_box text-center">{l s='Total weight' mod='dhlexpress'}</span></th>
          <th>
              <span class="title_box text-center">
                {l s='Number of' mod='dhlexpress'}<br/>
                {l s='product' mod='dhlexpress'}
              </span>
          </th>
          <th>
              <span class="title_box text-center">
                {l s='Total tax excl.' mod='dhlexpress'}<br/>
                {l s='(products only)' mod='dhlexpress'}
              </span>
          </th>
          <th><span class="title_box text-center">{l s='Country' mod='dhlexpress'}</span></th>
          <th><span class="title_box text-center">{l s='DHL Service' mod='dhlexpress'}</span></th>
          <th><span class="title_box text-center">{l s='Result' mod='dhlexpress'}</span></th>
        </tr>
        </thead>
        <tbody>
        {foreach $dhl_orders as $id_dhl_order => $dhl_order}
          <tr>
            <td><input type="checkbox" checked="checked" name="dhl_order_selected_{$id_dhl_order|intval}"/></td>
            <td class="text-center"><a
                href="{$link->getAdminLink('AdminOrders', true, [], ['vieworder' => true,'id_order' => {$dhl_order.id_order|intval}])|escape:'urldecode'}"
                target="_blank">{$dhl_order.reference|escape:'htmlall':'utf-8'}</a></td>
            <td class="text-center">{$dhl_order.weight|escape:'htmlall':'utf-8'}</td>
            <td class="text-center">{$dhl_order.product_count|intval}</td>
            <td class="text-center">{$dhl_order.total_product|escape:'htmlall':'utf-8'}</td>
            <td class="text-center">{$dhl_order.destination|escape:'htmlall':'utf-8'}</td>
            <td class="text-center">{$dhl_order.dhl_service|escape:'htmlall':'utf-8'}</td>
            <td class="text-center dhl_order_result dhl_order_result_{$id_dhl_order|intval}">--</td>
          </tr>
        {/foreach}
        </tbody>
      </table>
    </div>
  </div>
  <div class="dhl-bulk-label-config">
    <ps-panel icon="icon-cogs"
              header="{l s='Configure your shipments' mod='dhlexpress'}">
      <div class="row">
        <div class="col-lg-4">
          <p><strong>{l s='Package configuration' mod='dhlexpress'}</strong></p>
          <ps-switch colsd="sm-8"
                     colsw="sm-4"
                     class="dhl-use-order-weight-div"
                     name="dhl_use_order_weight"
                     label="{l s='Use weight of products in the orders' mod='dhlexpress'}"
                     yes="{l s='Yes' mod='dhlexpress'}"
                     no="{l s='No' mod='dhlexpress'}"
                     active="true"></ps-switch>
          <div class="form-group">
            <label for="dhl-label-package-type" class="control-label col-sm-4">
              {l s='Select package type' mod='dhlexpress'}
            </label>

            <div class="col-sm-7">
              <select name="dhl_package_type" class="col-lg-12" id="dhl-label-package-type">
                {foreach $package_types as $package_type}
                  <option value="{$package_type.id_dhl_package|intval}"
                          {if $package_type.id_dhl_package == $default_package_type}selected="selected"{/if}>
                    {$package_type.name|escape:'html':'utf-8'}{if $package_type.id_dhl_package == $default_package_type}{l s=' (default)' mod='dhlexpress'}{/if}
                  </option>
                {/foreach}
              </select>
            </div>
          </div>
          {foreach $package_types as $package_type}
            <div id="dhl-package-{$package_type.id_dhl_package|intval}" class="form-group dhl-packages"
                 {if $package_type.id_dhl_package != $default_package_type}style="display: none"{/if}>
              <div class="col-lg-12">
                <div class="dhl-weight-package-type">
                  <label class="control-label col-sm-4">{l s='Weight' mod='dhlexpress'}</label>

                  <div class="input-group fixed-width-xs">
                    <input onchange="this.value = parseFloat(this.value) || 0"
                           name="dhl_package_weight_{$package_type.id_dhl_package|intval}"
                           type="text"
                           value="{$package_type.weight_value|floatval}"
                           class="fixed-width-xs dhl-value-weight">
                    <span class="input-group-addon dhl-suffix-weight">{$weight_unit|escape:'html':'utf-8'}</span>
                  </div>
                </div>
                <label class="control-label col-sm-4">{l s='Length' mod='dhlexpress'}</label>

                <div class="input-group fixed-width-xs">
                  <input onchange="this.value = parseInt(this.value)"
                         name="dhl_package_length_{$package_type.id_dhl_package|intval}"
                         type="text"
                         value="{$package_type.length_value|floatval}"
                         class="fixed-width-xs dhl-value-length">
                  <span class="input-group-addon dhl-suffix-dimension">{$dimension_unit|escape:'html':'utf-8'}</span>
                </div>
                <label class="control-label col-sm-4">{l s='Width' mod='dhlexpress'}</label>

                <div class="input-group fixed-width-xs">
                  <input onchange="this.value = parseInt(this.value)"
                         name="dhl_package_width_{$package_type.id_dhl_package|intval}"
                         type="text"
                         value="{$package_type.width_value|floatval}"
                         class="fixed-width-xs dhl-value-width">
                  <span class="input-group-addon dhl-suffix-dimension">{$dimension_unit|escape:'html':'utf-8'}</span>
                </div>
                <label class="control-label col-sm-4">{l s='Depth' mod='dhlexpress'}</label>

                <div class="input-group fixed-width-xs">
                  <input onchange="this.value = parseInt(this.value)"
                         name="dhl_package_depth_{$package_type.id_dhl_package|intval}"
                         type="text"
                         value="{$package_type.depth_value|floatval}"
                         class="fixed-width-xs dhl-value-depth">
                  <span class="input-group-addon dhl-suffix-dimension">{$dimension_unit|escape:'html':'utf-8'}</span>
                </div>
              </div>
            </div>
          {/foreach}
          <ps-input-text name="dhl_contents"
                         class="dhl-contents"
                         label="{l s='Shipments contents' mod='dhlexpress'}"
                         size="10"
                         value="{$shipment_contents|escape:'html':'utf-8'}"
                         colsw="sm-4"
                         colsd="sm-8"
                         required-input="false"></ps-input-text>
        </div>
        <div class="col-lg-4">
          <p><strong>{l s='Declared value' mod='dhlexpress'}</strong></p>
          <ps-switch colsd="sm-8"
                     colsw="sm-4"
                     class="dhl-use-declared-value-div"
                     name="dhl_use_declared_value"
                     label="{l s='Use orders total tax excl.' mod='dhlexpress'}"
                     yes="{l s='Yes' mod='dhlexpress'}"
                     no="{l s='No' mod='dhlexpress'}"
                     active="true"></ps-switch>
          <ps-input-text name="dhl_declared_value"
                         class="dhl-declared-value-div"
                         label="{l s='Declared value' mod='dhlexpress'}"
                         size="10"
                         value=""
                         onchange="this.value = parseInt(this.value) || 0"
                         colsw="sm-4"
                         colsd="sm-8"
                         required-input="false"
                         prefix="{$iso_currency|escape:'html':'utf-8'}"
                         fixed-width="sm"></ps-input-text>
          <p><strong>{l s='Insurance' mod='dhlexpress'}</strong></p>
          <ps-switch colsd="sm-8"
                     colsw="sm-4"
                     class="dhl-insure-shipment-div"
                     name="dhl_insure_shipment"
                     label="{l s='Insure shipments' mod='dhlexpress'}"
                     yes="{l s='Yes' mod='dhlexpress'}"
                     no="{l s='No' mod='dhlexpress'}"
                     active="false"></ps-switch>
          <ps-input-text name="dhl_insured_value"
                         class="dhl-insured-value-div"
                         label="{l s='Insured value' mod='dhlexpress'}"
                         size="10"
                         onchange="this.value = parseInt(this.value) || 0"
                         colsw="sm-4"
                         colsd="sm-8"
                         value=""
                         required-input="false"
                         prefix="{$iso_currency|escape:'html':'utf-8'}"
                         fixed-width="sm"></ps-input-text>
          <p><strong>{l s='Doc Archive' mod='dhlexpress'}</strong></p>
          <ps-switch colsd="sm-8"
                     hint="{l s='Printing doc archive is mandatory for International shippings. Optional for Domestic & EU shippings' mod='dhlexpress'}"
                     colsw="sm-4"
                     name="dhl_print_doc_archive"
                     label="{l s='Print doc archive' mod='dhlexpress'}"
                     yes="{l s='Yes' mod='dhlexpress'}"
                     no="{l s='No' mod='dhlexpress'}"
                     active="false"></ps-switch>
        </div>
        <div class="col-lg-4">
          <p><strong>{l s='DHL Services' mod='dhlexpress'}</strong></p>
          <ps-switch colsd="sm-9" colsw="sm-3"
                     name="dhl_use_dhl_service"
                     label="{l s='Use services chosen by the customer' mod='dhlexpress'}"
                     yes="{l s='Yes' mod='dhlexpress'}"
                     no="{l s='No' mod='dhlexpress'}"
                     active="true"></ps-switch>
          <p><strong>{l s='Or configure them for each zone' mod='dhlexpress'}</strong></p>
          <ps-select colsw="sm-3" colsd="sm-9" label="France" name="dhl_services_domestic">
            {if isset($dhl_services['DOMESTIC'])}
              {foreach $dhl_services['DOMESTIC'] as $dhl_service}
                <option value="{$dhl_service.id_dhl_service|intval}">{$dhl_service.content_code|escape:'htmlall':'utf-8'} ({$dhl_service.name|escape:'htmlall':'utf-8'})</option>
              {/foreach}
            {/if}
          </ps-select>
          <ps-select colsw="sm-3" colsd="sm-9" label="Europe" name="dhl_services_europe">
            {if isset($dhl_services['EUROPE'])}
              {foreach $dhl_services['EUROPE'] as $dhl_service}
                <option value="{$dhl_service.id_dhl_service|intval}">{$dhl_service.content_code|escape:'htmlall':'utf-8'} ({$dhl_service.name|escape:'htmlall':'utf-8'})</option>
              {/foreach}
            {/if}
          </ps-select>
          <ps-select colsw="sm-3" colsd="sm-9" label="Monde" name="dhl_services_world">
            {if isset($dhl_services['WORLDWIDE'])}
              {foreach $dhl_services['WORLDWIDE'] as $dhl_service}
                <option value="{$dhl_service.id_dhl_service|intval}">{$dhl_service.content_code|escape:'htmlall':'utf-8'} ({$dhl_service.name|escape:'htmlall':'utf-8'})</option>
              {/foreach}
            {/if}
          </ps-select>
        </div>
      </div>
      <div>
        <a id="submit-bulk-label-generate"
           href="#"
           class="dhl-generate-labels btn btn-primary"
           name="submitBulkLabelGenerate">
          <i class="process-icon- icon-refresh"></i>
          {l s='Generate shipment labels' mod='dhlexpress'}
        </a>
      </div>
    </ps-panel>
  </div>
</form>

<div class="dhl-bulk-errors">
</div>

<script type="text/javascript">
  var dhlLoader = '{$dhl_img_path|escape:'htmlall':'utf-8'}loading.gif';
  var dhlBulkLabelForm;
  var success = [];

  {literal}

  function processDhlOrder(dhlOrders, success) {
    var idDhlOrder = parseInt(dhlOrders.pop());

    if (idDhlOrder) {
      var resultTd = $('.dhl_order_result_' + parseInt(idDhlOrder));
      resultTd.html('<img src="' + dhlLoader + '" style="height: 30px" />');
      $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '{/literal}{$link->getAdminLink('AdminDhlLabel')|escape:'urldecode'}{literal}&ajax=1&action=generateBulkLabel&id_dhl_order=' + parseInt(idDhlOrder),
        data: dhlBulkLabelForm
      }).done(function (data) {
        if (!data.errors) {
          resultTd.html(
            '<a ' +
            'href="{/literal}{$link->getAdminLink('AdminDhlLabel')|escape:'html':'utf-8'}&ajax=1&action=downloadlabel&id_dhl_label={literal}' + parseInt(data.id_dhl_label) + '">' +
            '<i class="icon icon-print"></i>' +
            ' ' + data.labelDetails.AirwayBillNumber +
            '</a>' +
            '<input type="hidden" name="dhl_labels_zip[]" value=' + parseInt(data.id_dhl_label) + ' />'
          );
          success.push(parseInt(idDhlOrder));
        } else {
          $('.dhl-bulk-errors').append(
            '<div id="error-modal-' + parseInt(idDhlOrder) + '" class="modal fade dhl-error-modal">' +
            '  <div class="modal-dialog">' +
            '    <div class="alert alert-danger clearfix">' +
            data.description +
            '      <button type="button" class="btn btn-default pull-right" data-dismiss="modal"><i class="icon-remove"></i>&nbsp;</button>' +
            '    </div>' +
            '  </div>' +
            '</div>');
          resultTd.html(
            '<a href="#" class="dhl-modal-error" data-id="error-modal-' + parseInt(idDhlOrder) + '" style="color: red;">' +
            '  <i class="icon icon-warning"></i> {/literal}{l s='Error - See details' mod='dhlexpress'}{literal}' +
            '</a>');
        }
        processDhlOrder(dhlOrders, success);
      }).fail(function (data) {

      });
    } else {
      if (success.length >= 2) {
        $('.dhl-bulk-label-download').show(400);
      }
      $('.dhl-label-processing').hide(200);
      $('.dhl-label-processing-done').show(400);
    }
  }

  $(document).on('click', '#submit-bulk-label-generate', function (e) {
    var dhlOrders = [];
    dhlBulkLabelForm = $('#dhl-bulk-label-form').serialize();
    $('.dhl-bulk-form-errors').hide(200);
    $('.dhl-label-processing').show(400);
    $.ajax({
      type: 'POST',
      dataType: 'json',
      url: '{/literal}{$link->getAdminLink('AdminDhlLabel')|escape:'urldecode'}{literal}&ajax=1&action=validateBulkLabelForm',
      data: dhlBulkLabelForm
    }).done(function (data) {
      if (data.errors) {
        $('.dhl-bulk-form-errors').text(data.description).show(400);
        $('.dhl-label-processing').hide(200);
      } else {
        $('.dhl-bulk-label-config').hide(200);
        $('.dhl-bulk-label-table input[type=checkbox]').attr('disabled', 'disabled');

        $('.dhl-bulk-label-table input[type=checkbox]:checked').each(function () {
          var idDhlOrder = parseInt(this.name.substr(19, this.name.length - 19)) || 0;
          $('.dhl_order_result_' + idDhlOrder).html('<i>{/literal}{l s='Queueing...' mod='dhlexpress'}{literal}</i>');
          dhlOrders.push(idDhlOrder);
        });
        dhlOrders.reverse();
        processDhlOrder(dhlOrders, success);
      }
    });
  });

  $(document).on('click', '.dhl-modal-error', function (e) {
    e.preventDefault();
    var id = $(this).attr('data-id');
    $('#' + id).modal('show');
  });
  {/literal}
</script>
