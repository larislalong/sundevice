{*
* 2007-2014 PrestaShop
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{capture name=path}{l s='My account'}{/capture}


<div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default " role="navigation" style="margin-bottom: 0">

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav " id="side-menu">



            <li><a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}" title="{l s='Account information'}">{l s='Dashboard'}</a></li>

            <li><a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}" title="{l s='Account information'}">{l s='Account information'}</a></li>
						{if $has_customer_an_address}
            <li><a href="{$link->getPageLink('address', true)|escape:'html':'UTF-8'}" title="{l s='Add my first address'}">{l s='Add my first address'}</a></li>
            {/if}
            <li><a href="{$link->getPageLink('history', true)|escape:'html':'UTF-8'}" title="{l s='Orders'}">{l s='Order history and details'}</a></li>
            {if $returnAllowed}
                <li><a href="{$link->getPageLink('order-follow', true)|escape:'html':'UTF-8'}" title="{l s='Merchandise returns'}">{l s='My merchandise returns'}</a></li>

            {/if}
            <li><a href="{$link->getPageLink('order-slip', true)|escape:'html':'UTF-8'}" title="{l s='Credit slips'}">{l s='My credit slips'}</a></li>
            <li>
                <a href="{$link->getModulelink('savemypurchases','purchases', [])|escape:'htmlall':'UTF-8'}"
                title="{l s='My registered baskets' mod='savemypurchases'}">
                    <i class="icon-edit-sign"></i>
                    <span>{l s='My registered baskets' mod='savemypurchases'}</span>
                </a>
            </li>
            <li><a href="{$link->getPageLink('addresses', true)|escape:'html':'UTF-8'}" title="{l s='Addresses'}">{l s='My addresses'}</a></li>
            <li><a href="{$link->getPageLink('identity', true)|escape:'html':'UTF-8'}" title="{l s='Information'}">{l s='My personal information'}</a></li>
            {if ($is_seller == 0 AND $customer_can_be_seller)}
                <li>
                    <a class="open_seller_account" title="{l s='Create seller account' mod='jmarketplace'}" href="{$link->getModuleLink('jmarketplace', 'addseller', array(), true)|escape:'html':'UTF-8'}">
                        <i class="icon-user fa fa-user"></i>
                        <span>{l s='Create seller account' mod='jmarketplace'}</span>
                    </a>
                </li>
            {else if $is_seller == 1 AND $is_active_seller == 0}
                <li>
                    <a class="open_seller_account" href="#">
                        <i class="icon-user fa fa-user"></i>
                        <span>{l s='Your seller account is pending approval.' mod='jmarketplace'}</span>
                    </a>
                </li>
            {else if $is_seller == 1 AND $is_active_seller == 1}
                <li>
                    <a class="open_seller_account" title="{l s='Your seller account' mod='jmarketplace'}" href="{$link->getModuleLink('jmarketplace', 'selleraccount', array(), true)|escape:'html':'UTF-8'}">
                        <i class="icon-user fa fa-user"></i>
                        <span>{l s='Seller account' mod='jmarketplace'}</span>
                    </a>
                </li>
            {/if}
            {if ($show_contact)}
                <li>
                    <a title="{l s='Seller messages' mod='jmarketplace'}" href="{$link->getModuleLink('jmarketplace', 'contactseller', array(), true)|escape:'html':'UTF-8'}">
                        <i class="icon-envelope fa fa-envelope-o"></i>
                        <span>{l s='Seller messages' mod='jmarketplace'}</span>
                    </a>
                </li>
            {/if}
            {if $show_seller_favorite}
                <li>
                    <a title="{l s='Favorite sellers' mod='jmarketplace'}" href="{$link->getModuleLink('jmarketplace', 'favoriteseller', array(), true)|escape:'html':'UTF-8'}">
                        <i class="icon-heart fa fa-heart"></i>
                        <span>{l s='Favorite sellers' mod='jmarketplace'}</span>
                    </a>
                </li>
            {/if}

{if $voucherAllowed || isset($HOOK_CUSTOMER_ACCOUNT) && $HOOK_CUSTOMER_ACCOUNT !=''}
            {if $voucherAllowed}
                <li><a href="{$link->getPageLink('discount', true)|escape:'html':'UTF-8'}" title="{l s='Vouchers'}">{l s='My vouchers'}</a></li>
            {/if}
            {$HOOK_CUSTOMER_ACCOUNT}
{/if}

                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">



                </div>
                {if $page_name=='order-slip'}

                      {include file="$tpl_dir./order-slip.tpl"}
                {elseif $page_name=='address'}

                      {include file="$tpl_dir./address.tpl"}
                {elseif $page_name=='addresses'}
                      {include file="$tpl_dir./addresses.tpl"}
                {elseif $page_name=='history'}
                      {include file="$tpl_dir./history.tpl"}
                {elseif $page_name=='identity'}
                      {include file="$tpl_dir./identity.tpl"}
                {else}


                <h1 class="page-heading">{l s='My Dashboard'}</h1>
      {if isset($account_created)}

      	<p class="alert alert-success">
      		{l s='Your account has been created.'}
      	</p>
        <p class="info-account">{l s='Welcome to your account. Here you can manage all of your personal information and orders. '}</p>
                  {/if}
                <div class="well">
                    <h4>DataTables Usage Information</h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.<a target="_blank" href="https://datatables.net/">https://datatables.net/</a>.</p>
                    <a class="btn btn-default btn-lg btn-block" target="_blank" href="https://datatables.net/">{l s='ADD NEW LOCATION'}</a>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        {include file="$tpl_dir./history.tpl"}
                  <div class="row">
      <div class="col-lg-12">
          <div class="panel panel-default">
              <div class="panel-heading">
                  {l s='Account Information'}
              </div>
              <!-- /.panel-heading -->
              <div class="panel-body">
                  <!-- Nav tabs -->
                  <ul class="nav nav-tabs">
                      <li class="active"><a href="#contact" data-toggle="tab">{l s='Contact information'}</a>
                      </li>
                      <li><a href="#news" data-toggle="tab">{l s='Newsletter'}</a>
                      </li>
                      <li><a href="#billing_adress" data-toggle="tab">{l s='default Billing Address'}</a>
                      </li>
                      <li><a href="#shipping_adress" data-toggle="tab">{l s='default Shipping address'}</a>
                      </li>
                  </ul>

                  <!-- Tab panes -->
                  <div class="tab-content">
                      <div class="tab-pane fade in active" id="contact">
                          <h4>{l s='Contact information'}</h4>
                          <p>{l s='Name:'}         {$cus_firstname}  {$cus_lastname}  </p>
                          <p>{l s='Email:'}  {$cus_email}</p> <br/><br/>
                          <a href="{$link->getPageLink('identity', true)|escape:'html':'UTF-8'}" title="{l s='Information'}">{l s='Edit'}</a>
                      </div>
                      <div class="tab-pane fade" id="news">
                          <h4>{l s='Newsletter'}</h4>
                          {if isset($cus_newsletter) && $cus_newsletter==1}
                          <p>{$cus_email}</p>
                          {else}
                          <p>{l s='You are currently not subscribed to any newsletter.'}</p>
                          {/if}
                      </div>
                      <div class="tab-pane fade" id="billing_adress">
                          <h4>{l s='default Billing Address'}</h4>
                          <div class=cls_bloc_address>
                          {foreach $addressList as $address}
                            {if $address@iteration<=2}
                            <div>
                                 <p>{$address.firstname} {$address.lastname}</p>
                                 <p>{$address.address1}</p>
                                 <p>{$address.postcode}</p>
                                 <p>{$address.city}</p>
                                 <p>{$address.country}</p>
                           </div>
                           {/if}

                          {/foreach}
                          </div>
                      </div>

                      <div class="tab-pane fade" id="shipping_adress">
                          <h4>{l s='default Shipping address'}</h4>
                          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                      </div>
                  </div>
              </div>
              <!-- /.panel-body -->
          </div>
          <!-- /.panel -->
      </div>
      <!-- /.col-lg-6 -->

  </div>
  <!-- /.row -->

          </div>
      {/if}

        <!-- /#page-wrapper -->
    </div>
    </div>
    <!-- /#wrapper -->
