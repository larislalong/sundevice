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

<div class="row seller_menu_container">
    <div class="col-lg-12">
        <a class="btn btn-secondary open_seller_menu" href="#">
            <span>
                <i class="icon-list fa fa-list"></i>
                {l s='Menu' mod='jmarketplace'}
            </span>
        </a>
    </div>
</div>
        
<div class="row">    
    <ul id="sellermenu"{if isset($tpl_name) AND $tpl_name == 'selleraccount'} style="display:block;"{/if}>
        <li class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
            <a title="{l s='Add product' mod='jmarketplace'}" href="{$link->getModuleLink('jmarketplace', 'addproduct', array(), true)|escape:'html':'UTF-8'}">
                <i class="icon-plus fa fa-plus"></i>
                <span>
                    {l s='Add product' mod='jmarketplace'}
                </span>
            </a>
        </li>  
        <li class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
            <a title="{l s='Products' mod='jmarketplace'}" href="{$link->getModuleLink('jmarketplace', 'sellerproducts', array(), true)|escape:'html':'UTF-8'}">
                <i class="icon-th-list fa fa-list"></i>
                <span>
                    {l s='Products' mod='jmarketplace'}
                </span>
            </a>
        </li> 
        {if $show_import_product == 1}
            <li class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
                <a title="{l s='Import and export products' mod='jmarketplace'}" href="{$link->getModuleLink('jmarketplace', 'csvproducts', array(), true)|escape:'html':'UTF-8'}">
                    <i class="icon-arrow-up  fa fa-arrow-up"></i>
                    <span>
                        {l s='Import and export products' mod='jmarketplace'}
                    </span>
                </a>
            </li>
        {/if}
        <li class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
            <a title="{l s='View my seller profile' mod='jmarketplace'}" href="{$seller_link|escape:'html':'UTF-8'}">
                <i class="icon-user fa fa-user"></i>
                <span>
                    {l s='Seller profile' mod='jmarketplace'}
                </span>
            </a>
        </li>
        {if $show_edit_seller_account == 1 }
            <li class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
                <a title="{l s='Edit your seller account' mod='jmarketplace'}" href="{$link->getModuleLink('jmarketplace', 'editseller', array(), true)|escape:'html':'UTF-8'}">
                    <i class="icon-user fa fa-edit"></i>
                    <span>
                        {l s='Edit seller account' mod='jmarketplace'}
                    </span>
                </a> 
            </li>
        {/if}
        {if $show_orders == 1}
            <li class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
                <a title="{l s='History commissions' mod='jmarketplace'}" href="{$link->getModuleLink('jmarketplace', 'sellerhistorycommissions', array(), true)|escape:'html':'UTF-8'}">
                    <i class="icon-list-ol fa fa-list"></i>
                    <span>
                        {l s='History commissions' mod='jmarketplace'}
                    </span>
                </a>
            </li>
        {/if}
        {if $show_manage_orders == 1}
            <li class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
                <a title="{l s='Manage Orders' mod='jmarketplace'}" href="{$link->getModuleLink('jmarketplace', 'orders', array(), true)|escape:'html':'UTF-8'}">
                    <i class="icon-money fa fa-money"></i>
                    <span>
                        {l s='Orders' mod='jmarketplace'}
                    </span>
                </a>
            </li>
        {/if}
        {if $show_manage_carriers == 1}
            <li class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
                <a title="{l s='Carriers and shipping cost' mod='jmarketplace'}" href="{$link->getModuleLink('jmarketplace', 'carriers', array(), true)|escape:'html':'UTF-8'}">
                    <i class="icon-truck fa fa-truck"></i>
                    <span>
                        {l s='Carriers' mod='jmarketplace'}
                    </span>
                </a>
            </li>
        {/if}     
        <li class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
            <a title="{l s='Payment' mod='jmarketplace'}" href="{$link->getModuleLink('jmarketplace', 'sellerpayment', array(), true)|escape:'html':'UTF-8'}">
                <i class="icon-credit-card fa fa-credit-card"></i>
                <span>
                    {l s='Payment' mod='jmarketplace'}
                </span>
            </a>
        </li>
        {if $show_contact == 1}
        <li class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
            <a title="{l s='Messages' mod='jmarketplace'}" href="{$link->getModuleLink('jmarketplace', 'sellermessages', array(), true)|escape:'html':'UTF-8'}">
                <i class="icon-envelope fa fa-envelope-o"></i>
                <span>
                    {l s='Messages' mod='jmarketplace'} 
                    ({$mesages_not_readed|intval})
                </span>
            </a>
        </li>
        {/if}
        {if $show_dashboard == 1}
            <li class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
                <a title="{l s='Dashboard' mod='jmarketplace'}" href="{$link->getModuleLink('jmarketplace', 'dashboard', array(), true)|escape:'html':'UTF-8'}">
                    <i class="icon-tachometer fa fa-tachometer"></i>
                    <span>
                        {l s='Dashboard' mod='jmarketplace'}
                    </span>
                </a>
            </li>
        {/if}
        {if $show_seller_invoice == 1}
            <li class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
                <a title="{l s='Withdraw money' mod='jmarketplace'}" href="{$link->getModuleLink('jmarketplace', 'sellerinvoice', array(), true)|escape:'html':'UTF-8'}">
                    <i class="icon-money fa fa-money"></i>
                    <span>
                        {l s='Withdraw money' mod='jmarketplace'}<br/>
                        (<strong>{$total_funds|escape:'html':'UTF-8'}</strong>)
                    </span>
                </a>
            </li>
        {/if}
        {hook h='displayMarketplaceMenu'}
    </ul>
</div>

{hook h='displayMarketplaceAfterMenu'}