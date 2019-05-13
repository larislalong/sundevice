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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="panel">
    <h3>
        {l s='Routes information' mod='jmarketplace'}
    </h3>
    <p>
        {l s='This module adds 2 dynamic and personalized links for market sellers. In this case we are talking about the profile page of the seller and the page of seller\'s products. In this section you can configure the route for seller profile and the specific route for products.' mod='jmarketplace'}<br /><br/>
    </p>
    <p>
        {l s='The pages that are inside the seller\'s account can also be configured the SEO and URL for all sellers in' mod='jmarketplace'} 
        <a href="{$url_metas|escape:'html':'UTF-8'}">
            {l s='SEO & URLS.' mod='jmarketplace'}
        </a>
    </p>
    <div class="panel-footer">
        <a class="btn btn-default" href="{$url_sellers|escape:'html':'UTF-8'}" target="_blank">
            <i class="icon-users"></i> 
            <span class="visible-lg">
                {l s='View sellers page' mod='jmarketplace'}
            </span>
        </a>
    </div>
</div>