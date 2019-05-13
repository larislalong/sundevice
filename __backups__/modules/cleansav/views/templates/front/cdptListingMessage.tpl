
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
            <li><a href="{$link->getPageLink('addresses', true)|escape:'html':'UTF-8'}" title="{l s='Addresses'}">{l s='My addresses'}</a></li>
            <li><a href="{$link->getPageLink('identity', true)|escape:'html':'UTF-8'}" title="{l s='Information'}">{l s='My personal information'}</a></li>
            <li><a href="{$link->getModuleLink('cleansav')|escape:'html':'UTF-8'}" title="{l s='Support Ticket/ Help Desk'}">{l s='Support Ticket/ Help Desk'}</a></li>

{if $voucherAllowed || isset($HOOK_CUSTOMER_ACCOUNT) && $HOOK_CUSTOMER_ACCOUNT !=''}
            {if $voucherAllowed}
                <li><a href="{$link->getPageLink('discount', true)|escape:'html':'UTF-8'}" title="{l s='Vouchers'}">{l s='My vouchers'}</a></li>
            {/if}
            {$HOOK_CUSTOMER_ACCOUNT}href="{$link->getModuleLink('prcustomeropinion')}
{/if}

                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
					{*
					* cleansav :: Customer ticket Information of the product
					*
					* @author    contact@cleanpresta.com (www.cleanpresta.com)
					* @copyright 2015 cleandev.net
					* @license   You only can use module, nothing more!
					*}

					{capture name=path}{l s='Ticket-Messages' mod='cleansav'}{/capture}

					{if isset($confirmation)}
						<p class="alert alert-success">{l s='Your message has been successfully sent to our team.' mod='cleansav'}</p>
					{else}
						{include file="$tpl_dir./errors.tpl"}
					{/if}

					<div>
						<p><a class="btn btn-primary btn-lg cdpt-top-menu" href="{$cdpt_controller1|escape:'htmlall':'UTF-8'}" role="button">{l s='Send a new Ticket' mod='cleansav'}</a></p>
					<div><br /><br />
					<br />
					{if $cdpt_fields_message}
						<div class="panel panel-default">
							<!-- Default panel contents -->
							<div class="panel-heading cdpt_title">{l s='All the Messages' mod='cleansav'}</div>

							<!-- Table -->
							<table class="table">
								<tr class="rows cdpt-top-table">
									<td class="col-xs-1 col-sm-1 col-lg-1">
										{l s='ID' mod='cleansav'}
									</td>
									<td class="col-xs-1 col-sm-1 col-lg-1">
										{l s='Department' mod='cleansav'}
									</td>
									<td class="col-xs-8 col-sm-7 col-lg-5">
										{l s='Last Message' mod='cleansav'}
									</td>
									<td class="col-xs-1 col-sm-1 col-lg-1">
										{l s='Order ID' mod='cleansav'}
									</td>
									<td class="col-xs-1 col-sm-1 col-lg-1">
										{l s='Product ID' mod='cleansav'}
									</td>
									<td class="col-xs-1 col-sm-1 col-lg-1">
										{l s='Status' mod='cleansav'}
									</td>
									<td class="col-xs-1 col-sm-1 col-lg-1">
										{l s='See All' mod='cleansav'}
									</td>
								</tr>
								{assign var=cdpt_compt value=0}
								{foreach from=$cdpt_fields_message item=fieldMessege}
									<tr class="rows">
										<td class="col-xs-1 col-sm-1 col-lg-1">
											{$fieldMessege.ID|escape:'htmlall':'UTF-8'}
										</td>
										<td class="col-xs-1 col-sm-1 col-lg-1">
											{$fieldMessege.DEPT|escape:'htmlall':'UTF-8'}
										</td>
										<td class="col-xs-8 col-sm-7 col-lg-5">
											{$fieldMessege.message|escape:'htmlall':'UTF-8'}
										</td>
										<td class="col-xs-1 col-sm-1 col-lg-1">
											{$fieldMessege.id_order|escape:'htmlall':'UTF-8'}
										</td>
										<td class="col-xs-1 col-sm-1 col-lg-1">
											{$fieldMessege.id_product|escape:'htmlall':'UTF-8'}
										</td>
										<td class="col-xs-1 col-sm-1 col-lg-1">
											<i class="icon-circle" style="color: {if $fieldMessege.status == closed} red {else} green {/if}"></i>
										</td>
										<td class="col-xs-1 col-sm-1 col-lg-1">
											{assign var=cdpt_linkmessage1 value="cdptmessage_"|cat:$cdpt_compt}
											<form action="{$cdpt_controller10|escape:'html':'UTF-8'}" method="post">
												<input type="hidden" name="{$cdpt_linkmessage1}" id="{$cdpt_linkmessage1}" value="{$fieldMessege.IDM|escape:'htmlall':'UTF-8'}"/>
												<button type="submit" name="cdpt_submitAllMessage" id="cdpt_submitAllMessage" class="btn btn-primary btn-lg"><span>{l s='See All' mod='cleansav'}</span></button>
											</form>
											{$cdpt_compt = $cdpt_compt + 1}
										</td>
									</tr>
								{/foreach}
							</table>
						</div>
						{if $cdpt_nbr_message > $cdpt_nbr_page}
							<div class="bx-controls-direction">
								<center>
									<h4>
										<table class="pagination">
											<th>
											{$p = 1}
											{for $var=1 to $cdpt_nbr_message step $cdpt_nbr_page}
												{assign var=cdpt_class value="btn btn-default"}
												{if $p == $cdpt_num_page}
													{assign var=cdpt_class value="btn btn-primary"}
												{/if}
												<td>
													{assign var=cdpt_page value="cdptpage_"|cat:$p}
													<form action="{$cdpt_controller10|escape:'html':'UTF-8'}" method="post">
														<input type="hidden" name="{$cdpt_page}" id="{$cdpt_page}" value="{$p|escape:'htmlall':'UTF-8'}"/>
														<button type="submit" name="cdpt_submitPage" id="lien" class="{$cdpt_class}">{$p|escape:'html':'UTF-8'}</button>
													</form>
												</td>
											{$p = $p + 1}
											{/for}
											</th>
										</table>
									</h4>
								</center>
							</div>
						{/if}
					{/if}

        <!-- /#page-wrapper -->
    </div>
    </div>
    <!-- /#wrapper -->
