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

{include "../_partials/dhl-header.tpl"}

{block name="dhl-info-message"}{/block}
<form id="create-label"
      class="defaultForm form-horizontal dhl-form"
      action="{$currentIndex|escape:'html':'utf-8'}"
      method="post"
      enctype="multipart/form-data">
  {block name="dhl-hidden-input"}
    <input type="hidden" name="dhl_id_order" value="{$id_order|intval}"/>
    {if isset($return_label)}
      <input type="hidden" name="dhl_id_return_label" value="{$return_label|intval}"/>
    {/if}
  {/block}
  <input type="hidden" name="dhl_label_currency_iso" value="{$currency_iso|escape:'html':'utf-8'}"/>

  <div id="dhl-create-label">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel form-horizontal">
          <div class="panel-heading">
            <i class="icon-user"></i>
            {block name="dhl-form-title"}{l s='Generate a label' mod='dhlexpress'}{/block}
          </div>
          {if isset($return_label)}
            <h2>{l s='Customer address (depature address)' mod='dhlexpress'}</h2>
            {include "../_partials/admin-dhl-customer-address.tpl"}
          {else}
            <h2>{l s='Shipper address' mod='dhlexpress'}</h2>
            {include "../_partials/admin-dhl-shipper-addresses.tpl"}
          {/if}
          {if isset($return_label)}
            <h2>{l s='Consignee address' mod='dhlexpress'}</h2>
            {include "../_partials/admin-dhl-shipper-addresses.tpl"}
          {else}
            <h2>{l s='Customer address' mod='dhlexpress'}</h2>
            {block name="dhl-customer-address"}
              {include "../_partials/admin-dhl-customer-address.tpl"}
            {/block}
          {/if}
          <h2>{l s='Shipment details' mod='dhlexpress'}</h2>

          <div class="alert alert-info">
            {l s='Add packages to you shipment using pre-defined package type.' mod='dhlexpress'}
          </div>
          <div class="form-group">
            <label for="dhl-label-package-type" class="control-label col-lg-3">
              {l s='Use a package type' mod='dhlexpress'}
            </label>

            <div class="col-lg-3">
              <select name="dhl_label_package_type" class="col-lg-12" id="dhl-label-package-type">
                {foreach $package_types as $package_type}
                  <option value="{$package_type.id_dhl_package|intval}"
                          {if $package_type.id_dhl_package == $default_package_type}selected="selected"{/if}>{$package_type.name|escape:'html':'utf-8'}{if $package_type.id_dhl_package == $default_package_type}{l s=' (default)' mod='dhlexpress'}{/if}</option>
                {/foreach}
              </select>
            </div>
          </div>
          {foreach $package_types as $package_type}
            <div id="dhl-package-{$package_type.id_dhl_package|intval}" class="form-group dhl-packages"
                 {if $package_type.id_dhl_package != $default_package_type}style="display: none"{/if}>
              <div class="col-lg-4 col-lg-offset-3">
                <label class="control-label col-lg-3">{l s='Weight' mod='dhlexpress'}</label>

                <div class="input-group fixed-width-xs">
                  <input onchange="this.value = parseFloat(this.value.replace(/,/g, '.')) || 0" name="dhl_package_weight_{$package_type.id_dhl_package|intval}" type="text"
                         value="{$package_type.weight_value|floatval}" class="fixed-width-xs dhl-value-weight">
                  <span class="input-group-addon dhl-suffix-weight">{$weight_unit|escape:'html':'utf-8'}</span>
                </div>
                <label class="control-label col-lg-3">{l s='Length' mod='dhlexpress'}</label>

                <div class="input-group fixed-width-xs">
                  <input onchange="this.value = parseInt(this.value) || 0" name="dhl_package_length_{$package_type.id_dhl_package|intval}" type="text"
                         value="{$package_type.length_value|floatval}" class="fixed-width-xs dhl-value-length">
                  <span class="input-group-addon dhl-suffix-dimension">{$dimension_unit|escape:'html':'utf-8'}</span>
                </div>
                <label class="control-label col-lg-3">{l s='Width' mod='dhlexpress'}</label>

                <div class="input-group fixed-width-xs">
                  <input onchange="this.value = parseInt(this.value) || 0" name="dhl_package_width_{$package_type.id_dhl_package|intval}" type="text"
                         value="{$package_type.width_value|floatval}" class="fixed-width-xs dhl-value-width">
                  <span class="input-group-addon dhl-suffix-dimension">{$dimension_unit|escape:'html':'utf-8'}</span>
                </div>
                <label class="control-label col-lg-3">{l s='Depth' mod='dhlexpress'}</label>

                <div class="input-group fixed-width-xs">
                  <input onchange="this.value = parseInt(this.value) || 0" name="dhl_package_depth_{$package_type.id_dhl_package|intval}" type="text"
                         value="{$package_type.depth_value|floatval}" class="fixed-width-xs dhl-value-depth">
                  <span class="input-group-addon dhl-suffix-dimension">{$dimension_unit|escape:'html':'utf-8'}</span>
                </div>
                <div class="col-lg-offset-3">
                  <a class="btn btn-xl btn-primary dhl-add-package" data-package="{$package_type.id_dhl_package|intval}"
                     href="#" id="dhl-add-new-package-{$package_type.id_dhl_package|intval}">
                    <i class="icon-plus-sign"></i> {l s='Add this package' mod='dhlexpress'}</a>
                </div>
              </div>
            </div>
          {/foreach}
          <div class="tab-content panel collapse in">
            <div>
              <table class="table dhl-package-table">
                <thead>
                <tr>
                  <th><span class="title_box">{l s='Package type' mod='dhlexpress'}</span></th>
                  <th><span class="title_box">{l s='Weight' mod='dhlexpress'}</span></th>
                  <th><span class="title_box">{l s='Length' mod='dhlexpress'}</span></th>
                  <th><span class="title_box">{l s='Width' mod='dhlexpress'}</span></th>
                  <th><span class="title_box">{l s='Depth' mod='dhlexpress'}</span></th>
                  <th></th>
                </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
          <div class="form-group">
            <label class="required control-label col-lg-3" for="dhl-label-reference-id">
              {l s='Your Shipper ID' mod='dhlexpress'}
            </label>

            <div class="col-lg-3">
              <div class="input-group">
                <span id="dhl_reference_id_counter" class="input-group-addon">35</span>
                <input type="text"
                       name="dhl_reference_id"
                       id="dhl-label-reference-id"
                       value="{$shipper_id|escape:'html':'utf-8'}"
                       data-maxchar="35"
                       maxlength="35"
                >
              </div>
              <script type="text/javascript">
                $(document).ready(function () {
                  countDown($("#dhl-label-reference-id"), $("#dhl_reference_id_counter"));
                });
              </script>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-lg-3 required" for="dhl-label-contents">
              {l s='Description of the content' mod='dhlexpress'}
            </label>

            <div class="col-lg-3">
              <textarea name="dhl_label_contents"
                        id="dhl-label-contents"
                        class="textarea-autosize">{$default_shipment_content|escape:'html':'utf-8'}</textarea>
            </div>
          </div>

          <div class="form-group dhl-label-archive-doc">
            <label class="control-label col-lg-3">
              <span class="label-tooltip"
                    data-toggle="tooltip"
                    data-html="true"
                    title=""
                    data-original-title="{l s='Printing doc archive is mandatory for International shippings. Optional for Domestic & EU shippings' mod='dhlexpress'}">
                {l s='Print doc archive' mod='dhlexpress'}
              </span>
            </label>

            <div class="col-lg-9">
              <span class="switch prestashop-switch fixed-width-lg">
                <input class=""
                       type="radio"
                       name="dhl_print_doc_archive"
                       id="print-doc-archive_on"
                       value="1">
                <label for="print-doc-archive_on">{l s='Yes' mod='dhlexpress'}</label>
                <input class=""
                       type="radio"
                       name="dhl_print_doc_archive"
                       id="print-doc-archive_off"
                       value="0"
                       checked="checked">
                <label for="print-doc-archive_off">{l s='No' mod='dhlexpress'}</label>
                <a class="slide-button btn"></a>
              </span>
            </div>
          </div>

          <div class="form-group dhl-label-doc">
            <label class="control-label col-lg-3">
              {l s='This shipping has documents only' mod='dhlexpress'}
            </label>

            <div class="col-lg-9">
              <span class="switch prestashop-switch fixed-width-lg">
                <input class=""
                       type="radio"
                       name="dhl_sending_doc"
                       id="sending-doc_on"
                       value="1"
                       {if $dhl_sending_doc}checked="checked"{/if}>
                <label for="sending-doc_on">{l s='Yes' mod='dhlexpress'}</label>
                <input class=""
                       type="radio"
                       name="dhl_sending_doc"
                       id="sending-doc_off"
                       value="0"
                       {if !$dhl_sending_doc}checked="checked"{/if}>
                <label for="sending-doc_off">{l s='No' mod='dhlexpress'}</label>
                <a class="slide-button btn"></a>
              </span>
            </div>
          </div>

          <div class="form-group form-group-declared-value">
            <label class="control-label col-lg-3" for="dhl-label-declared-value">
              {l s='Declared value' mod='dhlexpress'}
            </label>

            <div class="col-lg-3">
              <div class="input-group input fixed-width-md">
                <span class="input-group-addon">{$currency_iso|escape:'html':'utf-8'}</span>
                <input type="text"
                       onchange="this.value = parseInt(this.value) || 0"
                       name="dhl_label_declared_value"
                       id="dhl-label-declared-value"
                       value=""
                       class="input fixed-width-sm">
              </div>
              {if isset($declared_value_with_taxes) && isset($declared_value_without_taxes)}
                <p class="help-block">
                  {l s='Total products (tax excl.):' mod='dhlexpress'} {$currency_iso|escape:'html':'utf-8'} {$declared_value_without_taxes|floatval|string_format:'%.2f'}
                  <br/>
                  {l s='Total products (tax incl.):' mod='dhlexpress'} {$currency_iso|escape:'html':'utf-8'} {$declared_value_with_taxes|floatval|string_format:'%.2f'}
                </p>
              {/if}
            </div>
          </div>
          <div class="form-group form-group-insured-value">
            <label class="control-label col-lg-3" for="dhl-label-insured-value">
              {l s='Insured value' mod='dhlexpress'}
            </label>

            <div class="col-lg-3">
              <div class="input-group input fixed-width-md">
                <span class="input-group-addon">{$currency_iso|escape:'html':'utf-8'}</span>
                <input type="text"
                       onchange="this.value = parseInt(this.value) || 0"
                       name="dhl_label_insured_value"
                       id="dhl-label-insured-value"
                       class="input fixed-width-sm">
              </div>
            </div>
          </div>
          <h2>{l s='Extracharges' mod='dhlexpress'}</h2>
          {include "./_partials/dhl-extracharges.tpl"}
          <div class="dhl-process-button">
            <button id="submit-dhl-label-prices" type="submit" class="btn btn-primary" name="submitDhlLabelPrices">
              <i class="process-icon- icon-arrow-circle-down"></i> {l s='Get available DHL services' mod='dhlexpress'}
            </button>
            <img src="{$dhl_img_path|escape:'html':'utf-8'}loading.gif" id="dhl-loading-price" style="display: none"
                 class="dhl-loading"/>
          </div>

          <div class="dhl-services-result"></div>

          <div id="dhl-generate-label-block" class="dhl-process-button" style="display: none;">
            <button id="submit-dhl-label-create" type="submit" class="btn btn-primary" name="submitDhlLabelCreate">
              <i class="process-icon- icon-barcode"></i> {l s='Generate the label' mod='dhlexpress'}
            </button>
            <img src="{$dhl_img_path|escape:'html':'utf-8'}loading.gif" id="dhl-loading-generate" style="display: none"
                 class="dhl-loading"/>
          </div>

          <div class="dhl-label-result"></div>

        </div>
      </div>
    </div>
  </div>
</form>
<script type="text/javascript">
  {literal}

  var vat_number = 0;

  /* Binding submit event */
  $('#create-label').submit(function (e) {
    return false;
  });

  /* Get availables prices and services */
  $('#submit-dhl-label-prices').click(function (e) {
    var dhlServicesResult = $('.dhl-services-result');
    var dhlLabelResult = $('.dhl-label-result');
    var dhlLabelGenerateBtn = $('#dhl-generate-label-block');

    dhlServicesResult.html('');
    dhlLabelResult.html('');
    dhlLabelGenerateBtn.hide();
    $('#dhl-loading-price').show();
    $.ajax({
      type: 'POST',
      dataType: 'json',
      url: '{/literal}{$link->getAdminLink('AdminDhlLabel')|escape:'urldecode'}{literal}&ajax=1&action=retrieveDhlService',
      data: $('#create-label').serialize(),
      success: function (data) {
        $('#dhl-loading-price').hide(200);
        dhlServicesResult.html(data.html).hide().show(400);
        if (data.errors !== true) {
          dhlLabelGenerateBtn.show(400);
        }
      }
    });
  });

  /* Generate the label */
  $('#submit-dhl-label-create').click(function (e) {
    var dhlLabelResult = $('.dhl-label-result');

    dhlLabelResult.html('');
    $('#dhl-loading-generate').show();
    $.ajax({
      type: 'POST',
      dataType: 'json',
      url: '{/literal}{$link->getAdminLink('AdminDhlLabel')|escape:'urldecode'}{literal}&ajax=1&action=generateFormLabel',
      data: $("#create-label").serialize(),
      success: function (data) {
        $('#dhl-loading-generate').hide(200);
        if (data.errors === false) {
          $('#submit-dhl-label-prices').hide();
          $('.dhl-services-result').html('');
          $('#submit-dhl-label-create').hide();
        }
        dhlLabelResult.html(data.html);
      },
    });
  });

  $('.dhl-add-package').click(function (e) {
    e.preventDefault();
    var idPackage = $(this).attr('data-package');
    var weight = $("input[name='dhl_package_weight_" + idPackage + "']").val();
    var width = $("input[name='dhl_package_width_" + idPackage + "']").val();
    var length = $("input[name='dhl_package_length_" + idPackage + "']").val();
    var depth = $("input[name='dhl_package_depth_" + idPackage + "']").val();
    $.ajax({
      type: 'POST',
      dataType: 'json',
      url: '{/literal}{$link->getAdminLink('AdminDhlLabel')|escape:'urldecode'}{literal}&ajax=1&action=addDhlPackage',
      data: 'id_dhl_package=' + idPackage + '&width=' + width + '&length=' + length + '&depth=' + depth + '&weight=' + weight,
      success: function (data) {
        if (data.errors !== true) {
          var dim = data.packageDetails.id + 'x' +
            data.packageDetails.weight + 'x' +
            data.packageDetails.length + 'x' +
            data.packageDetails.width + 'x' +
            data.packageDetails.depth;

          $('.dhl-package-table > tbody:last-child').append('<tr>' +
            '<td>' + data.packageDetails.name + '</td>' +
            '<td>' + data.packageDetails.weight + '</td>' +
            '<td>' + data.packageDetails.length + '</td>' +
            '<td>' + data.packageDetails.width + '</td>' +
            '<td>' + data.packageDetails.depth + '</td>' +
            '<td class="text-right"><a class="btn btn-default" href="#" onclick="dhlDeleteRow(event, this)"><i class="icon-trash"></i></a></td>' +
            '<input type="hidden" name="package_dimensions[]" value="' + dim + '" />' +
            '</tr>');

          $("input[name='dhl_package_weight_" + idPackage + "']").attr('value', data.init.weight);
          $("input[name='dhl_package_length_" + idPackage + "']").attr('value', data.init.length);
          $("input[name='dhl_package_depth_" + idPackage + "']").attr('value', data.init.depth);
          $("input[name='dhl_package_width_" + idPackage + "']").attr('value', data.init.width);
        }
      },
      error: function (data) {

      }
    });
  });

  {/literal}
</script>
