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

<div id="{$name|escape:'html':'UTF-8'}-content" class="clearfix">
    <div class="col-lg-2">
        <div class="list-group">
            <a href="#tab-1" class="list-group-item active" data-toggle="tab">
                <i class="icon icon-info-circle"></i> 
                {l s='Information' mod='jmarketplace'}
            </a>
            <a href="#tab-2" class="list-group-item" data-toggle="tab">
                <i class="icon icon-cogs"></i> 
                {l s='General settings' mod='jmarketplace'}
            </a>
            <a href="#tab-3" class="list-group-item" data-toggle="tab">
                <i class="icon icon-user"></i> 
                {l s='Seller account' mod='jmarketplace'}
            </a>
            <a href="#tab-4" class="list-group-item" data-toggle="tab">
                <i class="icon icon-list"></i> 
                {l s='Seller product' mod='jmarketplace'}
            </a>
            <a href="#tab-5" class="list-group-item" data-toggle="tab">
                <i class="icon icon-envelope-o"></i> 
                {l s='Emails' mod='jmarketplace'}
            </a>
            <a href="#tab-6" class="list-group-item" data-toggle="tab">
                <i class="icon icon-money"></i> 
                {l s='Seller payment' mod='jmarketplace'}
            </a>
            <a href="#tab-7" class="list-group-item" data-toggle="tab">
                <i class="icon icon-html5"></i> 
                {l s='Front office theme' mod='jmarketplace'}
            </a>
            <a href="#tab-8" class="list-group-item" data-toggle="tab">
                <i class="icon icon-search"></i> 
                {l s='Dynamic routes' mod='jmarketplace'}
            </a>
        </div>
    </div>
    <div class="tab-content col-lg-10">
        <div class="tab-pane active panel" id="tab-1">
            <h3>
                <i class="icon-info"></i> 
                {$displayName|escape:'html':'UTF-8'} 
                <span class="hidden-xs">
                    {l s='version' mod='jmarketplace'} 
                    {$version|escape:'html':'UTF-8'}
                </span>
            </h3>
            <p>
                <strong>{$description|escape:'html':'UTF-8'}</strong><br /><br/>
                {l s='Thank you very much for installing' mod='jmarketplace'} "{$displayName|escape:'html':'UTF-8'}"!
                <br /><br/>
            </p>
            <div class="panel-footer">
                <a class="btn btn-default" href="https://addons.prestashop.com/contact-form.php?id_product=18656" target="_blank">
                    <i class="icon-envelope"></i> 
                    <span class="visible-lg">
                        {l s='Contact' mod='jmarketplace'}
                    </span>
                </a>
                <a class="btn btn-default" href="https://addons.prestashop.com/en/2_community-developer?contributor=343376" target="_blank">
                    <i class="icon-eye-open"></i> 
                    <span class="visible-lg">
                        {l s='View more modules of' mod='jmarketplace'} {$author|escape:'html':'UTF-8'}
                    </span>
                </a>
                <a class="btn btn-default" href="https://addons.prestashop.com/en/ratings.php" target="_blank">
                    <i class="icon-star"></i> 
                    <span class="visible-lg">
                        {l s='Help us by qualifying this purchase and you get a discount' mod='jmarketplace'}
                    </span>
                </a>
                <a class="btn btn-default" href="{$module_dir|escape:'html':'UTF-8'}changelog.txt" target="_blank">
                    <i class="icon-bug"></i> 
                    <span class="visible-lg">
                        {l s='Changelog' mod='jmarketplace'}
                    </span>
                </a>
            </div>
        </div>
        <div class="tab-pane panel" id="tab-2">
            {$displayFormGeneralSettings nofilter} {*This is html content*}
        </div>
        <div class="tab-pane panel" id="tab-3">
            {$displayFormSellerAccountSettings nofilter} {*This is html content*}
            {$displayFormSellerProfileSettings nofilter} {*This is html content*}
        </div>
        <div class="tab-pane panel" id="tab-4">
            {$displayFormSellerProductSettings nofilter} {*This is html content*}
        </div>
        <div class="tab-pane panel" id="tab-5">
            {$displayFormEmailSettings nofilter} {*This is html content*}
        </div>
        <div class="tab-pane panel" id="tab-6">
            {$displayFormPayments nofilter} {*This is html content*}
        </div>
        <div class="tab-pane panel" id="tab-7">
            {$displayFormThemeSettings nofilter} {*This is html content*}
        </div>
        <div class="tab-pane panel" id="tab-8">
            {$displayFormRouteSettings nofilter} {*This is html content*}
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $(".list-group-item").on("click", function() {
        $(".list-group-item").removeClass("active");
        $(this).addClass("active");
    });
});
</script>