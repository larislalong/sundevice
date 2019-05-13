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

<div class="mp_simple_menu">
    <div class="menu-container container" {$mainMenu.style.no_event nofilter}{* HTML, cannot escape*}>
        <div class="simple-menu" {$mainMenu.style.no_event nofilter}{* HTML, cannot escape*}>
            <div class="clear"></div>
				<a href="#" class="menu-mobile" {$mainMenu.style.no_event nofilter}{* HTML, cannot escape*}>{$mainMenu.name|escape:'htmlall':'UTF-8'}</a>
				{$secondaryMenuContent nofilter}{* HTML, cannot escape*}
			<div class="clear"></div>
		</div>
		{if $mainMenu.show_search_bar}
		<div class="search-box-outer">
			<div class="dropdown">
				<button class="search-box-btn dropdown-toggle" type="button" data-toggle="dropdown"></button>
				<ul class="dropdown-menu search-panel">
					<li class="panel-outer">
						<div class="form-container">
							<form method="post" action="{$searchAction|escape:'htmlall':'UTF-8'}" class="searchbox">
								<div class="form-group">
									<input class="search_query" type="search" name="search_query" value="" placeholder="{l s='Search...' mod='menupro'}"/>
									<button type="submit" class="search-btn"></button>
								</div>
							</form>
						</div>
					</li>
				</ul>
			</div>
		</div>
		{/if}
	</div>
</div>