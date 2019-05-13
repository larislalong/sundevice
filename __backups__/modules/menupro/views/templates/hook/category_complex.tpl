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

<div class="mp-category-complex">
	<div class="subcategory-image">
		<a href="{$link->getCategoryLink($complex_category->id_category, $complex_category->link_rewrite)|escape:'html':'UTF-8'}" title="{$complex_category->name|escape:'html':'UTF-8'}" class="img">
		{if $complex_category->id_image}
			<img class="replace-2x" src="{$link->getCatImageLink($complex_category->link_rewrite, $complex_category->id_image, 'category_default')|escape:'html':'UTF-8'}" alt="{$complex_category->name|escape:'html':'UTF-8'}"/>
		{else}
			<img class="replace-2x" src="{$img_cat_dir|escape:'html':'UTF-8'}{$lang_iso|escape:'html':'UTF-8'}-default-category_default.jpg" alt="{$complex_category->name|escape:'html':'UTF-8'}"/>
		{/if}
	</a>
	</div>
	<h5><a class="subcategory-name" href="{$link->getCategoryLink($complex_category->id_category, $complex_category->link_rewrite)|escape:'html':'UTF-8'}">{$complex_category->name|truncate:25:'...'|escape:'html':'UTF-8'}</a></h5>
	{if $complex_category->description}
		<div class="cat_desc">{$complex_category->description|escape:'javascript'}</div>
	{/if}
</div>