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

<div class="tab-content panel collapse in dhl-shipment-details-table">
  <div>
    <table class="table dhl-package-table">
      <thead>
      <tr>
        <th><span class="title_box"><i class="icon-truck"></i> {l s='AWB Number' mod='dhlexpress'}</span></th>
        <th><span class="title_box"><i class="icon-info"></i> {l s='Last tracking status known' mod='dhlexpress'}</span>
        </th>
        <th><span class="title_box"><i class="icon-barcode"></i> {l s='Label' mod='dhlexpress'}</span></th>
        <th><span class="title_box"><i
              class="icon-file-text-o"></i> {l s='Commercial invoice' mod='dhlexpress'}</span></th>
        <th><span class="title_box"><i class="icon-barcode"></i> {l s='Return label' mod='dhlexpress'}</span></th>
      </tr>
      </thead>
      <tbody>
      {foreach key=k item=dhl_shipment from=$dhl_shipments}
        <tr>
          <td>{$dhl_shipment['awb_number']|escape:'html':'utf-8'}</td>
          <td>
            {$dhl_shipment['last_status_known']|escape:'html':'utf-8'}
            {if $dhl_shipment['last_status_update']}
              <br />
              ({$dhl_shipment['last_status_update']|escape:'html':'utf-8'})
            {/if}
          </td>
          <td>
            <div class="btn-group-action">
              <div class="btn-group pull-left">
                <a target="_blank"
                   href="{$link->getAdminLink('AdminDhlLabel')|urldecode}&id_dhl_label={$dhl_shipment['id_dhl_label']|intval}&action=downloadlabel"
                   title="{l s='View label' mod='dhlexpress'}"
                   class="edit btn btn-default">
                  <i class="icon-eye"></i> {l s='View label' mod='dhlexpress'}
                </a>
                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                  <i class="icon-caret-down"></i>&nbsp;
                </button>
                <ul class="dropdown-menu">
                  <li>
                    <a
                      href="{$dhl_shipment['tracking_url']|escape:'html':'utf-8'}"
                      target="_blank"
                      title="{l s='Track the shipment' mod='dhlexpress'}">
                      <i class="icon-truck"></i> {l s='Track the shipment' mod='dhlexpress'}
                    </a>
                  </li>
                  {if !$dhl_shipment['is_tracked']}
                  <li class="divider"></li>
                  <li>
                    <a
                      href="#"
                      title="{l s='Delete label' mod='dhlexpress'}"
                      data-id-dhl-label="{$dhl_shipment['id_dhl_label']|intval}"
                      class="delete dhl-delete-label">
                      <i class="icon-trash"></i> {l s='Delete label' mod='dhlexpress'}
                    </a>
                  </li>
                  {/if}
                </ul>
              </div>
            </div>
          </td>
          <td>
            {if $dhl_shipment['id_dhl_commercial_invoice']}
              <div class="btn-group-action">
                <div class="btn-group pull-left">
                  <a target="_blank"
                     href="{$link->getAdminLink('AdminDhlCommercialInvoice')|escape:'html':'utf-8'}&id_dhl_label={$dhl_shipment['id_dhl_label']|intval}&action=downloadinvoice"
                     title="{l s='View invoice' mod='dhlexpress'}" class="edit btn btn-default">
                    <i class="icon-eye"></i> {l s='View invoice' mod='dhlexpress'}
                  </a>
                  <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-caret-down"></i>&nbsp;
                  </button>
                  <ul class="dropdown-menu">
                    <li>
                      <a
                        href="#"
                        title="{l s='Delete invoice' mod='dhlexpress'}"
                        data-id-dhl-invoice="{$dhl_shipment['id_dhl_commercial_invoice']|intval}"
                        class="delete dhl-delete-invoice">
                        <i class="icon-trash"></i> {l s='Delete invoice' mod='dhlexpress'}
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            {else}
              <a href="{$link->getAdminLink('AdminDhlCommercialInvoice')|escape:'html':'utf-8'}&id_dhl_label={$dhl_shipment['id_dhl_label']|intval}&action=create"
                 title="{l s='Create DHL invoice' mod='dhlexpress'}"
                 class="edit btn btn-default">
                <i class="icon-plus-circle"></i> {l s='Create DHL invoice' mod='dhlexpress'}
              </a>
            {/if}
          </td>
          <td>
            {if $dhl_shipment['id_dhl_return_label']}
              <div class="btn-group-action">
                <div class="btn-group pull-left">
                  <a target="_blank"
                     href="{$link->getAdminLink('AdminDhlLabel')|urldecode}&id_dhl_label={$dhl_shipment['id_dhl_return_label']|intval}&action=downloadlabel"
                     title="{l s='View return label' mod='dhlexpress'}"
                     class="edit btn btn-default">
                    <i class="icon-eye"></i> {l s='View return label' mod='dhlexpress'}
                  </a>
                  <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-caret-down"></i>&nbsp;
                  </button>
                  <ul class="dropdown-menu">
                    <li>
                      <a
                        href="{$dhl_shipment['tracking_url']|escape:'html':'utf-8'}"
                        target="_blank"
                        title="{l s='Track the shipment' mod='dhlexpress'}">
                        <i class="icon-truck"></i> {l s='Track the shipment' mod='dhlexpress'}
                      </a>
                    </li>
                    <li class="divider"></li>
                    <li>
                      <a
                        href="#"
                        title="{l s='Delete label' mod='dhlexpress'}"
                        data-id-dhl-label="{$dhl_shipment['id_dhl_return_label']|intval}"
                        class="delete dhl-delete-label">
                        <i class="icon-trash"></i> {l s='Delete label' mod='dhlexpress'}
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            {else}
              <a href="{$link->getAdminLink('AdminDhlLabel')|urldecode}&id_dhl_label={$dhl_shipment['id_dhl_label']|intval}&action=createreturn"
                 title="{l s='Create return label' mod='dhlexpress'}"
                 class="edit btn btn-default">
                <i class="icon-plus-circle"></i> {l s='Create return label' mod='dhlexpress'}
              </a>
            {/if}
          </td>
        </tr>
        {foreachelse}
        <tr>
          <td align="center" colspan="5">
            {l s='Please create a first label' mod='dhlexpress'}
          </td>
        </tr>
      {/foreach}
      </tbody>
    </table>
  </div>
</div>
<script type="text/javascript">
  {literal}
  $('.dhl-delete-invoice').click(function(e){
    e.preventDefault();
    var idDhlInvoice = parseInt($(this).attr('data-id-dhl-invoice'));
    var shipmentDetail = $('.dhl-shipment-details-table');
    shipmentDetail.fadeTo('fast', 0.4);
    $.ajax({
      type: 'POST',
      dataType: 'json',
      url: '{/literal}{$link->getAdminLink('AdminDhlCommercialInvoice')|urldecode}{literal}&ajax=1&action=deleteInvoice',
      data: 'id_dhl_commercial_invoice=' + idDhlInvoice,
      success: function (data) {
        if (data.errors === false) {
          showSuccessMessage(data.message);
          $('.dhl-shipment-details-table-container').html(data.html);
          shipmentDetail.fadeTo('fast', 1);
        } else {
          showErrorMessage(data.message);
          shipmentDetail.fadeTo('fast', 1);
        }
      },
      error: function (data) {

      }
    });
  });

  $('.dhl-delete-label').click(function(e){
    e.preventDefault();
    var idDhlLabel = parseInt($(this).attr('data-id-dhl-label'));
    var shipmentDetail = $('.dhl-shipment-details-table');
    shipmentDetail.fadeTo('fast', 0.4);
    $.ajax({
      type: 'POST',
      dataType: 'json',
      url: '{/literal}{$link->getAdminLink('AdminDhlLabel')|urldecode}{literal}&ajax=1&action=deleteLabel',
      data: 'id_dhl_label=' + idDhlLabel,
      success: function (data) {
        if (data.errors === false) {
          showSuccessMessage(data.message);
          $('.dhl-shipment-details-table-container').html(data.html);
          shipmentDetail.fadeTo('fast', 1);
        } else {
          showErrorMessage(data.message);
          shipmentDetail.fadeTo('fast', 1);
        }
      },
      error: function (data) {

      }
    });
  });

  {/literal}
</script>
