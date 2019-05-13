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

{include "./_partials/dhl-header.tpl"}

<div id="bo-dhlexpress">
  <div class="form-wrapper">
    <ul class="nav nav-tabs">
      <li {if $active == 'intro'}class="active"{/if}><a href="#intro"
                                                        data-toggle="tab">{l s='Introduction' mod='dhlexpress'}</a></li>
      <li {if $active == 'account'}class="active"{/if}><a href="#account"
                                                          data-toggle="tab">{l s='DHL Account' mod='dhlexpress'}</a>
      </li>
      <li {if $active == 'fo'}class="active"{/if}><a href="#fo"
                                                     data-toggle="tab">{l s='Front-office configuration' mod='dhlexpress'}</a>
      </li>
      <li {if $active == 'bo'}class="active"{/if}><a href="#bo"
                                                     data-toggle="tab">{l s='Back-office configuration' mod='dhlexpress'}</a>
      </li>
      <li {if $active == 'addresses'}class="active"{/if}><a href="#addresses"
                                                            data-toggle="tab">{l s='My addresses' mod='dhlexpress'}</a>
      </li>
      <li {if $active == 'packages'}class="active"{/if}><a href="#packages"
                                                           data-toggle="tab">{l s='My packages' mod='dhlexpress'}</a>
      </li>
    </ul>
    <div class="tab-content panel">
      <div id="intro" class="tab-pane {if $active == 'intro'}active{/if}">
        <div class="form-group" data-tab-id="intro">
          <div class="row dhl-background">
            <div class="col-lg-8 col-md-6">
              <h1>{l s='DHL Express' mod='dhlexpress'}</h1>

              <p>{l s='Offer the Services of the best logistician for international Express delivery door-to-door!' mod='dhlexpress'}</p>

              <p class="dhl-steps">
                <span class="list-number">1. </span>
                {l s='Already registered as DHL customer or subscribe here' mod='dhlexpress'}
                <a target="_blank"
                   href="{l s='http://www.dhlecommerce.fr/prestashop/en/contact.html' mod='dhlexpress'}"
                   class="btn btn-xl btn-primary btn-dhl-left">
                  {l s='Get a DHL account' mod='dhlexpress'}
                </a>
              </p>

              <p class="dhl-steps">
                <span class="list-number">2. </span>
                {l s='Synchronize your PrestaShop store with DHL services thanks to this DHL Module, by simply filling your account ID & password' mod='dhlexpress'}
              </p>

              <p class="dhl-steps">
                <span class="list-number">3. </span>
                {l s='Accelerate your international growth by offering your DHL prices to your customers in 1-click!' mod='dhlexpress'}
              </p>
              <ul class="dhl-services-list">
                <li>> {l s='Delivery before 9AM' mod='dhlexpress'}</li>
                <li>> {l s='Delivery before 12PM' mod='dhlexpress'}</li>
                <li>> {l s='Delivery before 6PM' mod='dhlexpress'}</li>
                <li>> {l s='Economy delivery within 2-4 days' mod='dhlexpress'}</li>
              </ul>
              <p class="dhl-steps">
                <span class="list-number">4. </span>
                {l s='Automate your task to save time' mod='dhlexpress'}
              </p>
              <ul class="dhl-tasks">
                <li>
                  > {l s='Label edition integrated to your order management interface.' mod='dhlexpress'}
                </li>
                <li>
                  > {l s='Ready-to-print commercial invoice to facilitate customs procedures.' mod='dhlexpress'}
                </li>
                <li>
                  > {l s='Print your manifest from your PrestaShop back-office.' mod='dhlexpress'}
                </li>
                <li>
                  > {l s='Request a pick up.' mod='dhlexpress'}
                </li>
                <li>
                  > {l s='Or edit a return label for your customers... and many other integrated services!' mod='dhlexpress'}
                </li>
              </ul>
              <p>{l s='In few words, DHL module is the development of your business in France & internationally through the experience of DHL integrated within PrestaShop.' mod='dhlexpress'}</p>
            </div>
            <div class="col-lg-4 col-md-6">
              <p class="video-header">{l s='Enjoy 40 years of experience' mod='dhlexpress'}</p>

              <div class="video-wrapper">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/4jmftUX3iRY" frameborder="0"
                        allowfullscreen></iframe>
              </div>
              <a target="_blank"
                 href="{l s='http://www.dhlecommerce.fr/prestashop/en/contact.html' mod='dhlexpress'}"
                 class="btn btn-xl btn-primary btn-dhl">
                {l s='Get a DHL account' mod='dhlexpress'}
              </a>
            </div>
          </div>
        </div>
      </div>
      <div id="account" class="tab-pane {if $active == 'account'}active{/if}">
        <div class="alert alert-info">{l s='Please fill in your credentials to use the module' mod='dhlexpress'}</div>
        {$accountSettings}
        <div class="clearfix"></div>
      </div>
      <div id="fo" class="tab-pane {if $active == 'fo'}active{/if}">
        <div class="form-group" data-tab-id="fo">
          {$frontOfficeSettings}
        </div>
      </div>
      <div id="bo" class="tab-pane {if $active == 'bo'}active{/if}">
        <div class="form-group" data-tab-id="bo">
          {$backOfficeSettings}
        </div>
      </div>
      <div id="addresses" class="tab-pane {if $active == 'addresses'}active{/if}">
        {if isset($addNewAddress)}
          {$newAddressForm}
        {else}
          {include file='./_partials/dhl-addresses.tpl'}
        {/if}
      </div>
      <div id="packages" class="tab-pane {if $active == 'packages'}active{/if}">
        {if isset($addNewPackage)}
          {$newPackageForm}
        {else}
          {include file='./_partials/dhl-packages.tpl'}
        {/if}
      </div>
    </div>
  </div>
</div>
