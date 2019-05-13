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

{if $errors}
  <div id="dhl-pickup-error" class="alert alert-danger">
    {foreach $description as $text}
      <p>{$text|escape:'html':'utf-8'}</p>
    {/foreach}
  </div>
{else}
  {if isset($alreadyRequested) && $alreadyRequested === true}
    <div id="dhl-pickup-already-requested">
      <div class="alert alert-warning">
        <p id="dhl-pickup-resume">{$pickup_resume|escape:'html':'utf-8'}</p>

        <p>{l s='However you are allowed to request exceptionnally a second pickup.' mod='dhlexpress'}</p>
      </div>
      <p>
        {l s='Do you confirm the pickup request?' mod='dhlexpress'}
        <button id="submit-dhl-pickup-force-cancel" type="submit" class="btn btn-primary">
          <i class="icon icon-times"></i> {l s='No' mod='dhlexpress'}
        </button>
        <button id="submit-dhl-pickup-force" type="submit" class="btn btn-small" name="submitDhlPickupForce">
          <i class="icon icon-check"></i> {l s='Yes' mod='dhlexpress'}
        </button>
      </p>
    </div>
  {else}
    <div id="dhl-pickup-success" class="alert alert-success">
      <p>{l s='Pickup request successfully sent.' mod='dhlexpress'}</p>

      <p>{l s='Please see the details below.' mod='dhlexpress'}</p>
    </div>
    <div id="dhl-document-download">
      <div id="dhl-pickup-details">
        <div class="dhl-picto-div">
          <p>{l s='Pickup details' mod='dhlexpress'}</p>
          <span>
            <b>{l s='Confirmation number:' mod='dhlexpress'}</b>
            <span id="dhl-confirmation-no">{$pickupDetails['ConfirmationNumber']|escape:'html':'utf-8'}</span>
          </span><br/>
          <span><b>{l s='Ready by time: ' mod='dhlexpress'}</b>
            <span id="dhl-ready-by-time">{$pickupDetails['ReadyByTime']|escape:'html':'utf-8'}</span>
          </span>
          <img class="dhl-picto" src="{$dhl_img_path|escape:'html':'utf-8'}dhl-van.png">
        </div>
      </div>
    </div>
  {/if}
{/if}
<script>
  {literal}

  /* Request a pickup */
  $('#submit-dhl-pickup-force').click(function (e) {
    var dhlPickupResult = $('.dhl-pickup-result');
    var dhlLoader = $('#dhl-pickup-loading');

    dhlLoader.css('display', 'block');
    $.ajax({
      type: 'POST',
      dataType: 'json',
      url: '{/literal}{$link->getAdminLink('AdminDhlPickup')|escape:'urldecode'}{literal}&ajax=1&action=requestDhlPickupForce',
      data: $('#pickup-request').serialize(),
      success: function (data) {
        dhlLoader.hide(200);
        dhlPickupResult.html(data.html).hide().show(400);
      }
    });
  });

  /* Request a pickup */
  $('#submit-dhl-pickup-force-cancel').click(function (e) {
    $('#dhl-pickup-already-requested').hide(200).html('');
  });

  {/literal}
</script>
