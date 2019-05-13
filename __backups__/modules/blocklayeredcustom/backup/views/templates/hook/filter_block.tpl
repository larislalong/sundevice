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
<script type="text/javascript">
	var blfFrontAjaxUrl = "{$blfFrontAjaxUrl}";
	var BLOCK_SEPARATOR = "{$BLOCK_SEPARATOR}";
	var FILTER_VALUES_SEPARATOR = "{$FILTER_VALUES_SEPARATOR}";
</script>
{if !$ajaxLoad}
<div class="blc-filter-block-wrapper">
    <div class="blc-filter-block-container container">
        <div class="row blc-filter-block">
{/if}
        	<div class="col-lg-3 filter-left">
				{include file="{$templateFolder}filter_left.tpl"}
			</div>  
        	<div class="col-lg-9 filter-center">
        		<div class="loading-product"  style="display:none;">
        			<img src="{$blcImgDir}loader.gif" alt="">
        			<br>{l s='Loading...' mod='blocklayeredcustom'}
        		</div>
        		<div class="filter-center-content">
					{include file="{$templateFolder}filter_center.tpl"}
				</div>
        	</div>
{if !$ajaxLoad}
        </div>
    </div>
</div>
{/if}