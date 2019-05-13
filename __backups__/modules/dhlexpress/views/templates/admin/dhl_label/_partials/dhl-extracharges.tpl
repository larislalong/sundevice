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

{foreach $dhl_extracharges as $extracharge}
  <div class="form-group
  {if $extracharge.id_dhl_extracharge == $extracharge_insurance} dhl-label-extracharge-insurance dhl-extracharge-non-doc
  {elseif $extracharge.id_dhl_extracharge == $extracharge_excepted} dhl-label-extracharge-excepted dhl-extracharge-non-doc
  {elseif $extracharge.id_dhl_extracharge == $extracharge_liability} dhl-label-extracharge-liability
  {else}dhl-extracharge-non-doc{/if}
  ">
    {if $extracharge.id_dhl_extracharge == $extracharge_dangerous}
    <p class="dhl-dangerous-extracharges">
      <i class="icon icon-warning"></i>
      <span>{l s='Below are extracharges for dangerous shipments. You need to have validation of DHL before using them. For that, please contact your commercial' mod='dhlexpress'}</span>
    </p>
    {/if}
    <label class="control-label col-lg-3">
              <span class="label-tooltip"
                    data-toggle="tooltip"
                    data-html="true"
                    title=""
                    data-original-title="{$extracharge.description|escape:'html':'utf-8'}"
              >
                {$extracharge.name|escape:'html':'utf-8'}
              </span>
    </label>
    <div class="col-lg-9">
              <span class="switch prestashop-switch fixed-width-lg">
                <input
                  {if $extracharge.id_dhl_extracharge == $extracharge_insurance}class="dhl-label-extracharge-insurance-on"
                  {elseif $extracharge.id_dhl_extracharge == $extracharge_excepted}class="dhl-label-extracharge-excepted-on"
                  {elseif $extracharge.id_dhl_extracharge == $extracharge_liability}class="dhl-label-extracharge-liability-on"{/if}
                  type="radio"
                  name="extracharge_{$extracharge.id_dhl_extracharge|intval}"
                  id="extracharge-{$extracharge.id_dhl_extracharge|intval}_on"
                  value="1"
                  {if $extracharge.active}checked="checked"{/if}>
                <label for="extracharge-{$extracharge.id_dhl_extracharge|intval}_on">{l s='Yes' mod='dhlexpress'}</label>
                <input
                  {if $extracharge.id_dhl_extracharge == $extracharge_insurance}class="dhl-label-extracharge-insurance-off"
                  {elseif $extracharge.id_dhl_extracharge == $extracharge_excepted}class="dhl-label-extracharge-excepted-off"
                  {elseif $extracharge.id_dhl_extracharge == $extracharge_excepted}class="dhl-label-extracharge-liability-off"{/if}
                  type="radio"
                  name="extracharge_{$extracharge.id_dhl_extracharge|intval}"
                  id="extracharge-{$extracharge.id_dhl_extracharge|intval}_off"
                  value="0"
                  {if !$extracharge.active}checked="checked"{/if}>
                <label for="extracharge-{$extracharge.id_dhl_extracharge|intval}_off">{l s='No' mod='dhlexpress'}</label>
                <a class="slide-button btn"></a>
              </span>
    </div>
  </div>
{/foreach}
