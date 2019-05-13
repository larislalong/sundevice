{**
* 2015-2017 Crystals Services
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
*  @author    Crystals Services Sarl <contact@crystals-services.com>
*  @copyright 2015-2017 Crystals Services Sarl
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of Crystals Services Sarl
*}
{if !$ajaxLoad}
<div class="blc-filter-block-wrapper">
    <div class="blc-filter-block-container container">
        <div class="row blc-filter-block">
{/if}		
			<input type="hidden" class="input_category" value="{if isset($id_category)}{$id_category|intval}{/if}" />
            {if $ismobile}
            <div class="col-md-7 col-sm-7 col-xs-7 filter-left">
                <nav class="navbar navbar-inverse">
                    <div class="navbar-header">
                      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                      </button>
                      <a class="navbar-brand" href="#">{l s='Filters' mod='blocklayeredcustom'}</a>
                    </div>
                    <div class="collapse navbar-collapse" id="myNavbar">
                      {include file="{$templateFolder}filter_left.tpl"}
                    </div>
                </nav>
            </div>
            <div class="col-md-5 col-sm-5 col-xs-5 filter-left filter-featured">
                <button class="btn btn-primary btn-filter dropdown-toggle" type="button" data-toggle="dropdown">
                    <span class="selected-name">{l s='Featured' mod='blocklayeredcustom'}</span>
                    <i class="fa fa-angle-down"></i>
                </button>
                <div class="dropdown-menu">
                    {include file="{$templateFolder}attribute_groups_list.tpl"}
					{include file="{$templateFolder}others_sort_block.tpl"}
                </div>
            </div>
            <div class="col-lg-12  col-sm-12 col-xs-12 filter-center">
        		<div class="loading-product"  style="display:none;">
        			<img src="{$blcImgDir}loader.gif" alt="">
        			<br>{l s='Loading...' mod='blocklayeredcustom'}
        		</div>
        		<div class="filter-center-content">
					{include file="{$templateFolder}filter_center.tpl"}
				</div>
        	</div>
            {else}
        	<div class="col-lg-3 col-sm-12 col-xs-12 filter-left">
				{include file="{$templateFolder}filter_left.tpl"}
			</div>  
        	<div class="col-lg-9  col-sm-12 col-xs-12 filter-center">
        		<div class="loading-product"  style="display:none;">
        			<img src="{$blcImgDir}loader.gif" alt="">
        			<br>{l s='Loading...' mod='blocklayeredcustom'}
        		</div>
        		<div class="filter-center-content">
					{include file="{$templateFolder}filter_center.tpl"}
				</div>
        	</div>
            {/if}
{if !$ajaxLoad}
        </div>
    </div>
</div>
{/if}