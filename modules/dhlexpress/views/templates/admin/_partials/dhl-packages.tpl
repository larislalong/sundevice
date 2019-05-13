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

<div class="form-group">
  <div class="col-lg-12">
    <a class="btn btn-xl btn-primary"
       href="{$link->getAdminLink('AdminModules')|escape:'html':'utf-8'}&configure=dhlexpress&addNewPackage"
       id="dhl-add-new-package">
      <i class="icon-plus-sign"></i> {l s='Add a new package' mod='dhlexpress'}</a>
  </div>
  <div class="clearfix"></div>
  {foreach $dhl_packages as $package}
    <div id="module_form" class="defaultForm form-horizontal col-lg-3">
      <div class="panel" id="fieldset_0">
        <div class="panel-heading">
          <i class="icon-archive"></i> {l s='Package name: ' mod='dhlexpress'}{$package.name|escape:'html':'utf-8'}
        </div>
        <div class="form-wrapper form-wrapper-view">
          <p>
            <b>{l s='Weight: ' mod='dhlexpress'}</b>{$package.weight_value|floatval} {$weight_unit|escape:'html':'utf-8'}
          </p>
          <p>
            <b>{l s='Length: ' mod='dhlexpress'}</b>{$package.length_value|floatval} {$dimension_unit|escape:'html':'utf-8'}
            <br/>
            <b>{l s='Width: ' mod='dhlexpress'}</b>{$package.width_value|floatval} {$dimension_unit|escape:'html':'utf-8'}
            <br/>
            <b>{l s='Depth: ' mod='dhlexpress'}</b>{$package.depth_value|floatval} {$dimension_unit|escape:'html':'utf-8'}
            <br/>
          </p>
        </div>
        <div class="panel-footer">
          <a type="button"
             href="{$link->getAdminLink('AdminModules')|escape:'html':'utf-8'}&configure=dhlexpress&addNewPackage&id_dhl_package={$package.id_dhl_package|intval}"
             class="btn btn-default pull-right">
            <i class="process-icon-edit"></i> {l s='Edit package' mod='dhlexpress'}
          </a>
          <a type="button"
             href="{$link->getAdminLink('AdminModules')|escape:'html':'utf-8'}&configure=dhlexpress&deletePackage&id_dhl_package={$package.id_dhl_package|intval}"
             class="btn btn-default pull-right">
            <i class="process-icon-trash icon-trash"></i> {l s='Delete package' mod='dhlexpress'}
          </a>
        </div>
      </div>
    </div>
    {foreachelse}
    <div id="dhl-no-addresses">
      <div class="alert alert-info">
        {l s='Please create your first package.' mod='dhlexpress'}
      </div>
    </div>
  {/foreach}
  <div class="clearfix"></div>
</div>
