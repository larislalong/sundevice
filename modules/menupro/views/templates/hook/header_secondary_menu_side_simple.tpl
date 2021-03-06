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

{if $firstItems}
	{if $secondaryMenu.level==2}
		<div class="simple-side-container">
			<div class="" {$secondaryMenu.parent_style_for_container nofilter}{* HTML, cannot escape*}>
	{else}
		<ul  class="{if $secondaryMenu.level>1}simple-side-container{/if}"  
		{$secondaryMenu.parent_style_for_container nofilter}{* HTML, cannot escape*}>
	{/if}
{else}
	{if $secondaryMenu.level==2}
		<div class="" 
			{$secondaryMenu.parent_style_for_container nofilter}{* HTML, cannot escape*}>
	{/if}
{/if}

	{if $secondaryMenu.level!=2}<li>{/if}
	{include file="{$displayElementTemplate|escape:'htmlall':'UTF-8'}" isFooterTemplate=false hasAdditionalClass=true additionalClass='mp-right'}
		{if (!$secondaryMenu.use_custom_content) && ($secondaryMenu.display_style!=$displayStylesConst.COMPLEX)}
			{if $secondaryMenu.has_children}
				<span class="mp-icon-side mp-icon-side-right ion-plus icon_side_simple"></span>
			{/if}
		{/if}