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

{block name="include"}{/block}

{block name="dhl-form"}
  <form id="create-invoice" class="defaultForm form-horizontal dhl-form" action="{$currentIndex|escape:'html':'utf-8'}"
        method="post" enctype="multipart/form-data">
    <input type="hidden" name="id_dhl_label" value="{$id_dhl_label|intval}"/>
    <input type="hidden" name="dhl_currency_iso" value="{$currency_iso|escape:'html':'utf-8'}"/>

    <div id="dhl-create-invoice">
      <div class="row">
        <div class="col-lg-12">
          <div class="panel form-horizontal">
            <div class="panel-heading">
              <i class="icon-user"></i>
              {l s='Generate an invoice' mod='dhlexpress'}
            </div>
            <h2>{l s='Shipper address' mod='dhlexpress'}</h2>
            {include "../_partials/admin-dhl-shipper-addresses.tpl"}
            <h2>{l s='Customer address' mod='dhlexpress'}</h2>
            {include "../_partials/admin-dhl-customer-address.tpl"}
            <h2>{l s='General informations' mod='dhlexpress'}</h2>

            <div class="form-group">
              <label class="control-label col-lg-3">
                {l s='AWB Number' mod='dhlexpress'}
              </label>

              <div class="col-lg-3">
                <p class="form-control-static">
                  <strong>{$awb_number|escape:'html':'utf-8'}</strong>
                  <input type="hidden" name="dhl_awb_number" value="{$awb_number|escape:'html':'utf-8'}"/>
                </p>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-lg-3 required" for="dhl-invoice-no">
                {l s='Invoice number' mod='dhlexpress'}
              </label>

              <div class="col-lg-3">
                <input type="text" value="{$awb_number|escape:'html':'utf-8'}" name="dhl_invoice_no"
                       id="dhl-invoice-no">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-lg-3 required" for="dhl-shipment-ref">
                {l s='Shipment reference' mod='dhlexpress'}
              </label>

              <div class="col-lg-3">
                <input type="text" value="{$awb_number|escape:'html':'utf-8'}" name="dhl_shipment_ref"
                       id="dhl-shipment-ref">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-lg-3" for="dhl-invoice-incoterms">
                {l s='Incoterms (2010)' mod='dhlexpress'}
              </label>

              <div class="col-lg-3">
                <select name="dhl_invoice_incoterms" class="col-lg-12" id="dhl-invoice-incoterms">
                  {foreach $incoterms as $code => $name}
                    <option value="{$code|escape:'html':'utf-8'}">{$code|escape:'html':'utf-8'}
                      ({$name|escape:'html':'utf-8'})
                    </option>
                  {/foreach}
                </select>
              </div>
            </div>
            <h2>{l s='Shipping content' mod='dhlexpress'}</h2>

            <div class="form-group">
              <label class="control-label col-lg-3 required" for="dhl-total-package">
                {l s='Number of package' mod='dhlexpress'}
              </label>

              <div class="col-lg-3">
                <input type="text" name="dhl_total_package" id="dhl-total-package"
                       class="input fixed-width-xs">
              </div>
            </div>

            <h4>{l s='Products detail' mod='dhlexpress'}</h4>

            <p>{l s='Please review the content of the shipping and make corrections if needed' mod='dhlexpress'}</p>

            <div class="tab-content panel collapse in">
              <div>
                <table class="table dhl-product-table">
                  <thead>
                  <tr>
                    <th><span class="title_box">{l s='SH Code' mod='dhlexpress'}</span></th>
                    <th><span class="title_box">{l s='Ref.' mod='dhlexpress'}</span></th>
                    <th><span class="title_box">{l s='Product name' mod='dhlexpress'}</span></th>
                    <th><span class="title_box">{l s='Origin country' mod='dhlexpress'}</span></th>
                    <th><span class="title_box">{l s='Quantity' mod='dhlexpress'}</span></th>
                    <th><span class="title_box">{l s='Weight' mod='dhlexpress'}</span></th>
                    <th><span class="title_box required">{l s='Unit price (tax excl.)' mod='dhlexpress'}</span></th>
                    <th><span class="title_box"></span></th>
                  </tr>
                  </thead>
                  <tbody>
                  {foreach $order_details as $order_detail}
                    <tr>
                      <td class="fixed-width-xl">
                        <input type="text"
                               value="{$default_hs_code|escape:'html':'utf-8'}"
                               name="dhl_od_hs_code_{$order_detail.id_order_detail|intval}"
                               id="dhl-od-hs-code-{$order_detail.id_order_detail|intval}"
                               class="input fixed-width-xl">
                      </td>
                      <td class="fixed-width-lg">{$order_detail.product_reference|escape:'html':'utf-8'}</td>
                      <td class="fixed-width-xl">
                        <input type="text"
                               name="dhl_od_pname_{$order_detail.id_order_detail|intval}"
                               id="dhl-od-pname-{$order_detail.id_order_detail|intval}"
                               value="{$order_detail.product_name|escape:'html':'utf-8'}">
                      </td>
                      <td class="fixed-width-xl">
                        <select name="dhl_od_country_{$order_detail.id_order_detail|intval}"
                                id="dhl-od-country-{$order_detail.id_order_detail|intval}">
                          {foreach $countries as $country}
                            <option value="{$country.id_country|intval}"
                                    {if $country.id_country == $default_country}selected="selected"{/if}>{$country.name|escape:'html':'utf-8'}</option>
                          {/foreach}
                        </select>
                      </td>
                      <td class="fixed-width-sm center">
                        <input type="text"
                               name="dhl_od_quantity_{$order_detail.id_order_detail|intval}"
                               id="dhl-od-quantity-{$order_detail.id_order_detail|intval}"
                               value="{$order_detail.product_quantity|intval}"
                               class="fixed-width-xs">
                      </td>
                      <td class="fixed-width-sm">
                        <div class="col-lg-3">
                          <div class="input-group fixed-width-sm">
                            <input name="dhl_od_weight_{$order_detail.id_order_detail|intval}"
                                   id="dhl-od-weight-{$order_detail.id_order_detail|intval}"
                                   type="text"
                                   value="{$order_detail.product_weight|floatval}"
                                   class="fixed-width-sm dhl-value-weight">
                            <span class="input-group-addon">{$weight_unit|escape:'html':'utf-8'}</span>
                          </div>
                        </div>
                      </td>
                      <td class="fixed-width-sm">
                        <div class="col-lg-3">
                          <div class="input-group fixed-width-sm">
                            <span class="input-group-addon">{$currency_iso|escape:'html':'utf-8'}</span>
                            <input name="dhl_od_price_{$order_detail.id_order_detail|intval}"
                                   id="dhl-od-price-{$order_detail.id_order_detail|intval}"
                                   type="text"
                                   value="{$order_detail.unit_price_tax_excl|floatval}"
                                   class="fixed-width-sm"/>
                          </div>
                        </div>
                      </td>
                      <td>
                        <a class="btn btn-default" href="#" onclick="dhlDeleteRow(event, this)"><i
                            class="icon-trash"></i></a>
                      </td>
                    </tr>
                  {/foreach}
                  </tbody>
                </table>
              </div>
            </div>
            <h4>{l s='Add additional products' mod='dhlexpress'}</h4>

            <p>{l s='You can add more products the list...' mod='dhlexpress'}</p>

            <div id="div-dhl-add-sup-product" class="tab-content collapse in dhl-product-sup-table">
              <div>
                <table class="table">
                  <thead>
                  <tr>
                    <th><span class="title_box">{l s='SH Code' mod='dhlexpress'}</span></th>
                    <th><span class="title_box">{l s='Product name' mod='dhlexpress'}</span></th>
                    <th><span class="title_box">{l s='Origin country' mod='dhlexpress'}</span></th>
                    <th><span class="title_box">{l s='Quantity' mod='dhlexpress'}</span></th>
                    <th><span class="title_box">{l s='Weight' mod='dhlexpress'}</span></th>
                    <th><span class="title_box required">{l s='Unit price (tax excl.)' mod='dhlexpress'}</span></th>
                    <th><span class="title_box"></span></th>
                  </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <input type="text"
                               value="{$default_hs_code|escape:'html':'utf-8'}"
                               name="dhl_supp_hs_code"
                               id="dhl-supp-hs-code"
                               class="input fixed-width-xl"/>
                      </td>
                      <td>
                        <input type="text" name="dhl_supp_pname" id="dhl-supp-pname" value=""/>
                      </td>
                      <td>
                        <select class="fixed-width-lg" name="dhl_supp_country" id="dhl-supp-country">
                          {foreach $countries as $country}
                            <option value="{$country.id_country|intval}" {if $country.id_country == $default_country}selected="selected"{/if}>
                              {$country.name|escape:'html':'utf-8'}
                            </option>
                          {/foreach}
                        </select>
                      </td>
                      <td class="center">
                        <input type="text" name="dhl_supp_quantity" id="dhl-supp-quantity" class="fixed-width-xs" />
                      </td>
                      <td>
                        <div class="col-lg-3">
                          <div class="input-group fixed-width-xs">
                            <input name="dhl_supp_weight" type="text" id="dhl-supp-weight" class="fixed-width-xs">
                            <span class="input-group-addon">{$weight_unit|escape:'html':'utf-8'}</span>
                          </div>
                        </div>
                      </td>
                      <td>
                        <div class="col-lg-3">
                          <div class="input-group fixed-width-xs">
                            <span class="input-group-addon">{$currency_iso|escape:'html':'utf-8'}</span>
                            <input name="dhl_supp_price" id="dhl-supp-price" type="text" class="fixed-width-sm"/>
                          </div>
                        </div>
                      </td>
                      <td>
                        <button type="submit"
                                class="btn btn-default"
                                id="submit-dhl-add-sup-product"
                                name="submitDhlAddSupProduct">
                          <i class="icon-plus-sign-alt"></i> {l s='Add' mod='dhlexpress'}
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="dhl-process-button">
              <button id="submit-dhl-invoice-download" type="submit" class="btn btn-primary"
                      name="submitDhlInvoiceDownload">
                <i class="process-icon- icon-file-text"></i> {l s='Generate the invoice' mod='dhlexpress'}
              </button>
            </div>

            <div class="dhl-invoice-result"></div>

          </div>
        </div>
      </div>
    </div>
  </form>
  <script type="text/javascript">
    {literal}

    var addI = 0;

    /* Binding submit event */
    $('#create-invoice').submit(function (e) {
      return false;
    });

    $('#submit-dhl-invoice-download').click(function (e) {
      var dhlInvoiceResult = $('.dhl-invoice-result');

      dhlInvoiceResult.html('');
      $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '{/literal}{$link->getAdminLink('AdminDhlCommercialInvoice')|escape:'urldecode'}{literal}&ajax=1&action=generateInvoice',
        data: $('#create-invoice').serialize(),
        success: function (data) {
          dhlInvoiceResult.html(data.html).hide().show(400);
        }
      });
    });

    $('#submit-dhl-add-sup-product').click(function (e) {
      $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '{/literal}{$link->getAdminLink('AdminDhlCommercialInvoice')|escape:'urldecode'}{literal}&ajax=1&action=addSupProduct',
        data: $('#create-invoice').serialize(),
        success: function (data) {
          if (data.errors !== true) {
            addI++;
            $('.dhl-product-table > tbody:last-child').append('<tr>' +
              '<td><input type="hidden" name="dhl_supp_hs_code_' + addI + '" value="' + data.productRow.shCode + '" />' + data.productRow.shCode + '</td>' +
              '<td>---</td>' +
              '<td><input type="hidden" name="dhl_supp_pname_' + addI + '" value="' + data.productRow.name + '" />' + data.productRow.name + '</td>' +
              '<td><input type="hidden" name="dhl_supp_country_' + addI + '" value="' + data.productRow.originCountry + '" />' + data.productRow.originCountry + '</td>' +
              '<td><input type="hidden" name="dhl_supp_quantity_' + addI + '" value="' + data.productRow.quantity + '" />' + data.productRow.quantity + '</td>' +
              '<td><input type="hidden" name="dhl_supp_weight_' + addI + '" value="' + data.productRow.weight + '" />' + data.productRow.weight + '</td>' +
              '<td><input type="hidden" name="dhl_supp_price_' + addI + '" value="' + data.productRow.unitPrice + '" />' + data.productRow.unitPrice + '</td>' +
              '<td class="text-right"><a class="btn btn-default" href="#" onclick="dhlDeleteRow(event, this)"><i class="icon-trash"></i></a></td>' +
              '</tr>');
          }
        }
      });
    });
    {/literal}
  </script>
{/block}
