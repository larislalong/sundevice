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

<form id="pickup-request"
      class="defaultForm form-horizontal dhl-form"
      action="{$currentIndex|escape:'html':'utf-8'}"
      method="post"
      enctype="multipart/form-data">
  <div class="row">
    <div class="col-lg-12">
      <div class="panel form-horizontal">
        <div class="panel-heading">
          <i class="icon-user"></i>
          {l s='Request a pickup' mod='dhlexpress'}
        </div>

        <h2>{l s='Pickup address' mod='dhlexpress'}</h2>
        {include "../_partials/admin-dhl-shipper-addresses.tpl"}

        {include "./_partials/dhl-pickup-closing-time.tpl"}

        <div class="form-group">
          <label class="control-label col-lg-3 required" for="dhl-pickup-location">
            {l s='Location' mod='dhlexpress'}
          </label>

          <div class="col-lg-3">
            <div class="input-group">
              <span id="dhl_pickup_location_counter" class="input-group-addon">35</span>
              <input type="text"
                     name="dhl_pickup_location"
                     id="dhl-pickup-location"
                     class="input fixed-width-xl"
                     data-maxchar="35"
                     maxlength="35"
                     value="{l s='Reception' mod='dhlexpress'}"
              >
            </div>
            <p class="help-block">
              {l s='(e.g. Reception, Front desk)' mod='dhlexpress'}
            </p>
            <script type="text/javascript">
              $(document).ready(function () {
                countDown($("#dhl-pickup-location"), $("#dhl_pickup_location_counter"));
              });
            </script>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-lg-3 required" for="dhl-pickup-contact">
            {l s='Contact at pickup point' mod='dhlexpress'}
          </label>

          <div class="col-lg-3">
            <div class="input-group">
              <span id="dhl_pickup_contact_counter" class="input-group-addon">35</span>
              <input type="text"
                     name="dhl_pickup_contact"
                     id="dhl-pickup-contact"
                     class="input fixed-width-xl"
                     data-maxchar="35"
                     maxlength="35"
                     value="{$default_sender_contact|escape:'html':'utf-8'}">
            </div>
            <script type="text/javascript">
              $(document).ready(function () {
                countDown($("#dhl-pickup-contact"), $("#dhl_pickup_contact_counter"));
              });
            </script>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-lg-3 required" for="dhl-pickup-phone">
            {l s='Phone' mod='dhlexpress'}
          </label>

          <div class="col-lg-3">
            <input type="text" name="dhl_pickup_phone" id="dhl-pickup-phone"
                   class="input fixed-width-xl" value="{$default_sender_phone|escape:'html':'utf-8'}">
          </div>
        </div>

        <h2>{l s='Pickup details' mod='dhlexpress'}</h2>

        <div class="form-group required">
          <label class="control-label col-lg-3 required" for="dhl-pickup-date">
            {l s='Pickup date' mod='dhlexpress'}
          </label>

          <div class="input-group col-lg-2">
            <input id="dhl-pickup-date"
                   type="text"
                   data-hex="true"
                   class="datepicker"
                   name="dhl_pickup_date"
                   value="">
            <span class="input-group-addon">
              <i class="icon-calendar-empty"></i>
            </span>
          </div>
        </div>
        {include "./_partials/dhl-pickup-time.tpl"}
        <div class="form-group">
          <label class="control-label col-lg-3 required" for="dhl-pickup-weight">
            {l s='Total weight' mod='dhlexpress'}
          </label>

          <div class="col-lg-3">
            <div class="input-group fixed-width-xs">
              <input name="dhl_pickup_weight" type="text" value="" id="dhl-pickup-weight"
                     class="fixed-width-xs dhl-value-weight">
              <span class="input-group-addon">{$weight_unit|escape:'html':'utf-8'}</span>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-lg-3 required" for="dhl-pickup-packages">
            {l s='Number of parcels' mod='dhlexpress'}
          </label>

          <div class="col-lg-3">
            <input name="dhl_pickup_packages" type="text" value="" id="dhl-pickup-packages"
                   class="fixed-width-xs">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-lg-3 required" for="dhl-pickup-instructions">
            {l s='Special instructions' mod='dhlexpress'}
          </label>

          <div class="col-lg-3">
            <div class="input-group">
              <span id="dhl_pickup_instructions_counter" class="input-group-addon">75</span>
              <textarea name="dhl_pickup_instructions" id="dhl-pickup-instructions"
                        class="textarea-autosize" data-maxchar="75" maxlength="75"></textarea>
            </div>
            <script type="text/javascript">
              $(document).ready(function () {
                countDown($("#dhl-pickup-instructions"), $("#dhl_pickup_instructions_counter"));
              });
            </script>
          </div>
        </div>
        <div class="dhl-process-button">
          <button id="submit-dhl-pickup" type="submit" class="btn btn-primary" name="submitDhlPickup">
            <i class="process-icon- icon-arrow-circle-down"></i> {l s='Request a pickup' mod='dhlexpress'}
          </button>
          <img src="{$dhl_img_path|escape:'html':'utf-8'}loading.gif" id="dhl-pickup-loading" style="display: none"
               class="dhl-pickup-loading"/>
        </div>

        <div class="dhl-pickup-result"></div>

      </div>
    </div>
  </div>
</form>
<script>
  $(document).ready(function () {
    $('.datepicker').datepicker({
      prevText: '',
      nextText: '',
      dateFormat: 'yy-mm-dd',
      minDate: 0,
      beforeShowDay: $.datepicker.noWeekends
    });
  });

  {literal}

  /* Binding submit event */
  $('#pickup-request').submit(function (e) {
    return false;
  });

  /* Request a pickup */
  $('#submit-dhl-pickup').click(function (e) {
    var dhlPickupResult = $('.dhl-pickup-result');
    var dhlLoader = $('#dhl-pickup-loading');

    dhlPickupResult.html('');
    dhlLoader.attr('style', 'display: block; margin: 0 auto; width: 60px; padding-top: 5px;');
    $.ajax({
      type: 'POST',
      dataType: 'json',
      url: '{/literal}{$link->getAdminLink('AdminDhlPickup')|escape:'urldecode'}{literal}&ajax=1&action=requestDhlPickup',
      data: $('#pickup-request').serialize(),
      success: function (data) {
        dhlLoader.hide(200);
        dhlPickupResult.html(data.html).hide().show(400);
      }
    });
  });

  {/literal}
</script>
