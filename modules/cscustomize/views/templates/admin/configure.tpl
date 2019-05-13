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
	function changeTab (nav) {
		var id = nav.attr('id');
		$('.nav-optiongroup').removeClass('selected');
		nav.addClass('selected active');
		nav.siblings().removeClass('active');
		$('.tab-optiongroup').hide();
		$('.' + id).show();
	}
	function manageTabs () {
		$('div.productTabs').find('a.nav-optiongroup').click(function (event) {
			event.preventDefault();
			changeTab($(this));
		});
	}
	$(document).ready(function () {
		manageTabs ();
	});
</script>
<div class="row">
	<div id="tabs">
		<div class="productTabs col-lg-2 col-md-2">
			<div class="tab list-group">
				{$first = true}
				{foreach from=$listHtmlPerHook key=hook item=listContent}
				<a id="nav-{$hook|escape:'htmlall':'UTF-8'}" class="list-group-item nav-optiongroup{if $first} active{/if}" href="#" 
				title="{$hook|escape:'htmlall':'UTF-8'}' mod='menupro'}">
					{$hook|escape:'htmlall':'UTF-8'}
				</a>
				{$first = false}
				{/foreach}
			</div>
		</div>
	</div>
	<div class="form-horizontal col-lg-10 col-md-10">
		{$first = true}
		{foreach from=$listHtmlPerHook key=hook item=listContent}
		<div class="nav-{$hook|escape:'htmlall':'UTF-8'} tab-optiongroup" {if !$first}style="display: none"{/if}>
			{$listContent}
		</div>
		{$first = false}
		{/foreach}
	</div>
</div>