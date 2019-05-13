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

<div class="form-group form-group-dhl-input-customer">
  <label class="control-label col-lg-3" for="dhl-label-customer">
    {l s='Search for a customer' mod='dhlexpress'}
  </label>

  <div class="col-lg-3">
    <input type="text"
           name="dhl_label_customer"
           id="dhl-label-customer"
           class="input fixed-width-xl"
           value=""
    >
  </div>
</div>
<div class="form-group" id="dhl-label-customer-research">
  <div class="row">
    <img src="{$dhl_img_path|escape:'html':'utf-8'}loading.gif"
         style="display: none;"
         class="dhl-loading-customers dhl-loading"/>

    <div class="col-lg-9 col-lg-offset-3" id="dhl-label-customer-results">

    </div>
  </div>
</div>
<div class="alert alert-warning dhl-address-warning" style="display: none;"></div>
<div class="dhl-label-inputs" style="display: none">
  <div class="form-group form-group-dhl-select-address">
    <label for="dhl-label-select-address" class="control-label col-lg-3">
      {l s='Select an address' mod='dhlexpress'}
    </label>

    <div class="col-lg-9">
      <select name="dhl_label_select_address" class="fixed-width-xl" id="dhl-label-select-address">
      </select>
    </div>
  </div>
  <div class="form-group">
    <label for="company_name" class="control-label col-lg-3 required">
      {l s='Company name' mod='dhlexpress'}
    </label>

    <div class="col-lg-3">
      <div class="input-group">
        <span id="company_name_counter" class="input-group-addon">35</span>
        <input type="text" name="company_name" id="company_name" value="" data-maxchar="35" maxlength="35">
      </div>
      <script type="text/javascript">
        $(document).ready(function () {
          countDown($("#company_name"), $("#company_name_counter"));
        });
      </script>
    </div>

  </div>
  <div class="form-group">
    <label for="person_name" class="control-label col-lg-3 required">
      {l s='Person name' mod='dhlexpress'}
    </label>

    <div class="col-lg-3">
      <div class="input-group">
        <span id="person_name_counter" class="input-group-addon">35</span>
        <input type="text" name="person_name" id="person_name" value="" data-maxchar="35" maxlength="35">
      </div>
      <script type="text/javascript">
        $(document).ready(function () {
          countDown($("#person_name"), $("#person_name_counter"));
        });
      </script>
    </div>

  </div>
  <div class="form-group">
    <label for="address1" class="control-label col-lg-3 required">
      {l s='Address 1' mod='dhlexpress'}
    </label>

    <div class="col-lg-3">
      <div class="input-group">
        <span id="address1_counter" class="input-group-addon">35</span>
        <input type="text" name="address1" id="address1" value="" class="" data-maxchar="35" maxlength="35">
      </div>
      <script type="text/javascript">
        $(document).ready(function () {
          countDown($("#address1"), $("#address1_counter"));
        });
      </script>
    </div>
  </div>
  <div class="form-group">
    <label for="address2" class="control-label col-lg-3">
      {l s='Address 2' mod='dhlexpress'}
    </label>

    <div class="col-lg-3">
      <div class="input-group">
        <span id="address2_counter" class="input-group-addon">35</span>
        <input type="text" name="address2" id="address2" value="" class="" data-maxchar="35" maxlength="35">
      </div>
      <script type="text/javascript">
        $(document).ready(function () {
          countDown($("#address2"), $("#address2_counter"));
        });
      </script>
    </div>
  </div>
  <div class="form-group">
    <label for="address3" class="control-label col-lg-3">
      {l s='Address 3' mod='dhlexpress'}
    </label>

    <div class="col-lg-3">
      <div class="input-group">
        <span id="address3_counter" class="input-group-addon">35</span>
        <input type="text" name="address3" id="address3" value="" class="" data-maxchar="35" maxlength="35">
      </div>
      <script type="text/javascript">
        $(document).ready(function () {
          countDown($("#address3"), $("#address3_counter"));
        });
      </script>
    </div>
  </div>
  <div class="form-group">
    <label for="zipcode" class="control-label col-lg-3">
      {l s='Zipcode' mod='dhlexpress'}
    </label>

    <div class="col-lg-3">
      <input type="text" name="zipcode" id="zipcode" value="" class="">
    </div>
  </div>
  <div class="form-group">
    <label for="city" class="control-label col-lg-3 required">
      {l s='City' mod='dhlexpress'}
    </label>

    <div class="col-lg-3">
      <div class="input-group">
        <span id="city_counter" class="input-group-addon">35</span>
        <input type="text" name="city" id="city" value="" class="" data-maxchar="35" maxlength="35">
      </div>
      <script type="text/javascript">
        $(document).ready(function () {
          countDown($("#city"), $("#city_counter"));
        });
      </script>
    </div>

  </div>
  <div class="form-group">
    <label for="id_country" class="control-label col-lg-3 required">
      {l s='Country' mod='dhlexpress'}
    </label>

    <div class="col-lg-9">
      <select name="id_country" class="fixed-width-xl" id="id_country">
        {foreach $countries as $country}
          <option value="{$country.id_country|intval}"
                  {if $country.id_country == $default_country}selected="selected"{/if}>{$country.name|escape:'html':'utf-8'}</option>
        {/foreach}
      </select>
    </div>
  </div>
  <div class="form-group" id="contains_states" style="display:none;">
    <label for="id_state" class="control-label col-lg-3">
      {l s='State name' mod='dhlexpress'}
    </label>

    <div class="col-lg-9">
      <select name="id_state" class=" fixed-width-xl" id="id_state">
      </select>
    </div>
  </div>

  <div class="form-group">
    <label for="phone" class="control-label col-lg-3 required">
      {l s='Phone' mod='dhlexpress'}
    </label>

    <div class="col-lg-3">
      <input type="text" name="phone" id="phone" value="" class="">
    </div>
  </div>

  <div class="form-group">
    <label for="email" class="control-label col-lg-3">
      {l s='Email' mod='dhlexpress'}
    </label>

    <div class="col-lg-3">
      <div class="input-group">
        <span id="email_counter" class="input-group-addon">50</span>
        <input type="text" name="email" id="email" value="" class="" data-maxchar="50" maxlength="50">
      </div>
      <script type="text/javascript">
          $(document).ready(function () {
              countDown($("#email"), $("#email_counter"));
          });
      </script>
    </div>
  </div>
</div>
<script type="text/javascript">
  {literal}
  var module_dir = '{/literal}{$smarty.const._MODULE_DIR_|escape:'html':'utf-8'}{literal}';
  var customerResearch = $('#dhl-label-customer-research');
  var selectAddress = $('#dhl-label-select-address');

  $(document).ready(function () {
    var idCountry = $('#id_country');

    if (idCountry && $('#id_state')) {
      ajaxStates(0);
      idCountry.change(function () {
        ajaxStates();
      });
    }
    $('#dhl-label-customer').typeWatch({
      captureLength: 3,
      highlight: true,
      wait: 100,
      callback: function () {
        searchCustomers();
      }
    });
  });

  customerResearch.on('click', 'button.setup-customer', function (e) {
    e.preventDefault();
    var idCustomer = parseInt($(this).attr('data-customer'));
    loadCustomerAddresses(idCustomer);
    $(this).removeClass('setup-customer').addClass('change-customer').html('<i class="icon-refresh"></i>&nbsp;{/literal}{l s="Change"}{literal}').blur();
    $(this).closest('.customerCard').addClass('selected-customer');
    $('.selected-customer .panel-heading').prepend('<i class="icon-ok text-success"></i>');
    $('.customerCard').not('.selected-customer').remove();
    $('.form-group-dhl-input-customer').hide();
  });

  customerResearch.on('click', 'button.change-customer', function (e) {
    e.preventDefault();
    $('.dhl-label-inputs').hide();
    $('.dhl-address-warning').hide();
    $('#dhl-label-select-address').html('');
    $('.form-group-dhl-input-customer').show();
    $(this).blur();
  });

  selectAddress.change(function () {
    var idAddress = $(this).find('option:selected').attr('value');
    $('.dhl-address-warning').hide();
    $.ajax({
      type: "POST",
      url: "{/literal}{$link->getAdminLink('AdminDhlLabel')|escape:'urldecode'}{literal}",
      async: true,
      dataType: "json",
      data: {
        ajax: "1",
        tab: "AdminDhlLabel",
        action: "loadAddress",
        idAddress: idAddress
      },
      success: function (res) {
        if (res.errors === false) {
          fillAddress(res.address);
        } else {
          var addressWarning = $('.dhl-address-warning');
          addressWarning.text(res.description);
          addressWarning.show();
          fillEmptyAddress();
          $('.dhl-label-inputs').show();
          if (res.noAddresses === true) {
            $('.form-group-dhl-select-address').hide();
          }
        }
      }
    });
  });


  function fillAddress(address) {
    var company_name = $('#company_name');
    var person_name = $('#person_name');
    var address1 = $('#address1');
    var address2 = $('#address2');
    var address3 = $('#address3');
    var city = $('#city');
    var email = $('#email');

    company_name.val(address.company_name);
    person_name.val(address.person_name);
    address1.val(address.address1);
    address2.val(address.address2);
    address3.val(address.address3);
    $('#zipcode').val(address.zipcode);
    city.val(address.city);
    $('#id_country').val(address.id_country).change();
    $('#phone').val(address.phone);
    email.val(address.email);
    countDown(company_name, $("#company_name_counter"));
    countDown(person_name, $("#person_name_counter"));
    countDown(address1, $("#address1_counter"));
    countDown(address2, $("#address2_counter"));
    countDown(address3, $("#address3_counter"));
    countDown(city, $("#city_counter"));
    countDown(email, $("#email_counter"));
    if (address.id_state) {
      $('#id_state').val(address.id_state).change();
      ajaxStates(address.id_state);
    }
  }

  function fillEmptyAddress() {
    $('#company_name').val('');
    $('#person_name').val('');
    $('#address1').val('');
    $('#address2').val('');
    $('#address3').val('');
    $('#zipcode').val('');
    $('#city').val('');
    $('#id_country').val({/literal}{$default_country|intval}{literal}).change();
    $('#phone').val('');
    $('#email').val('');
  }

  function loadCustomerAddresses(idCustomer) {
    $('.dhl-address-warning').hide();
    $.ajax({
      type: "POST",
      url: "{/literal}{$link->getAdminLink('AdminDhlLabel')|escape:'urldecode'}{literal}",
      async: true,
      dataType: "json",
      data: {
        ajax: "1",
        tab: "AdminDhlLabel",
        action: "loadAddresses",
        idCustomer: idCustomer
      },
      success: function (res) {
        if (res.errors === false) {
          $('.dhl-label-inputs').show();
          var addresses = res.addresses;
          var address = res.addresses[0];
          $.each(addresses, function () {
            var selectAddress = $('#dhl-label-select-address');
            $('.form-group-dhl-select-address').show();
            selectAddress.append($('<option>', {
              value: this.id_address,
              text: this.alias
            }));
            fillAddress(address);
          });
        } else {
          var addressWarning = $('.dhl-address-warning');
          addressWarning.text(res.description);
          addressWarning.show();
          fillEmptyAddress();
          $('.dhl-label-inputs').show();
          if (res.noAddresses === true) {
            $('.form-group-dhl-select-address').hide();
          }
        }
      }
    });
  }

  function searchCustomers() {
    $('.dhl-loading-customers').show();
    $.ajax({
      type: "POST",
      url: "{/literal}{$link->getAdminLink('AdminDhlLabel')|escape:'urldecode'}{literal}",
      async: true,
      dataType: "json",
      data: {
        ajax: "1",
        tab: "AdminDhlLabel",
        action: "searchCustomers",
        customer_search: $('#dhl-label-customer').val()
      },
      success: function (res) {
        if (res.found) {
          var html = '';
          $.each(res.customers, function () {
            html += '<div class="customerCard col-lg-4">';
            html += '<div class="panel">';
            html += '<div class="panel-heading">' + this.firstname + ' ' + this.lastname;
            html += '<span class="pull-right">#' + this.id_customer + '</span></div>';
            html += '<span>' + this.email + '</span><br/>';
            html += '<span class="text-muted">' + ((this.birthday != '0000-00-00') ? this.birthday : '') + '</span><br/>';
            html += '<div class="panel-footer">';
            html += '<button type="button" data-customer="' + this.id_customer + '" class="setup-customer btn btn-default pull-right"><i class="icon-arrow-right"></i> {/literal}{l s='Choose'}{literal}</button>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
          });
        }
        else {
          html = '<div class="alert alert-warning">{/literal}{l s='No customers found'}{literal}</div>';
        }
        $('.dhl-loading-customers').hide();
        $('#dhl-label-customer-results').html(html);
      }
    });
  }

  state_token = '{/literal}{getAdminToken tab='AdminStates'}{literal}';

  {/literal}
</script>
