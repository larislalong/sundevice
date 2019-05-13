{*
* 2007-2019 PrestaShop
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
*  @copyright 2007-2019 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<style>
	.box-stats .title {
		color:#333;
		margin-bottom:10px;
		font-size: 14px;
	}
	.box-stats .subtitle {
		background-color:#333;
		width:auto;
		color:#fff !important;
		margin-bottom:10px;
		text-align:left !important;
		padding:7px !important;
		font-size: 12px;
		line-height: 18px;
	}
</style>
<div class="panel">
	<h3><i class="icon icon-credit-card"></i> {l s='isoStats' mod='isostats'}</h3>
	<div class="panel kpi-container">
		<div class="row">
			<div class="col-sm-6 col-lg-3">
				<div id="box-sales-count" data-toggle="tooltip" class="box-stats label-tooltip color1" data-original-title="">
					<div class="kpi-content">
						<i class="icon-truck"></i>
						<span class="title">Produits vendus (Nombre)</span>
						<span class="subtitle label">Tout<br>{$allStats.count.all}</span>
						<span class="subtitle label">Boîtes<br>{$allStats.count.box}</span>
						<span class="subtitle label">iPhone<br>{$allStats.count.iphone}</span>
						<span class="subtitle label">iPad<br>{$allStats.count.ipad}</span>
						<span class="subtitle label">iWatch<br>{$allStats.count.iwatch}</span>
						<span class="subtitle label">Samsung<br>{$allStats.count.samsung}</span>
					</div>
					
				</div>
			</div>
			<div class="col-sm-6 col-lg-3">
				<a style="display:block" href="{$salesDetailsLink|escape:'html':'UTF-8'}" id="box-sales-amount" data-toggle="tooltip" class="box-stats label-tooltip color2" data-original-title="">
					<div class="kpi-content">
						<i class="icon-money"></i>
						<span class="title">Produits vendus (Montant)</span>
						<span class="subtitle label color_field">Tout<br>{convertPrice price=$allStats.price.all}</span>
						<span class="subtitle label color_field">Boîtes<br>{convertPrice price=$allStats.price.box}</span>
						<span class="subtitle label color_field">iPhone<br>{convertPrice price=$allStats.price.iphone}</span>
						<span class="subtitle label color_field">iPad<br>{convertPrice price=$allStats.price.ipad}</span>
						<span class="subtitle label color_field">iWatch<br>{convertPrice price=$allStats.price.iwatch}</span>
						<span class="subtitle label color_field">Samsung<br>{convertPrice price=$allStats.price.samsung}</span>
					</div>
					
				</a>
			</div>
			<div class="col-sm-6 col-lg-3">
				<div id="box-order-state" data-toggle="tooltip" class="box-stats label-tooltip color3" data-original-title="">
					<div class="kpi-content">
						<i class="icon-archive"></i>
						<span class="title">État de commandes (Montant / Livraison)</span>
						{if isset($orderStats.status) && count($orderStats.status)}
							{foreach from=$orderStats.status item=status}
								<span class="subtitle label color_field" style="background-color:{$status.color};">{$status.name}<br>{convertPrice price=$status.total} / {convertPrice price=$status.shipping}</span>
							{/foreach}
						{/if}
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-lg-3">
				{*<div id="box-net-profit-visit" data-toggle="tooltip" class="box-stats label-tooltip color4" data-original-title="">
					<div class="kpi-content">
						<i class="icon-user"></i>
						<span class="title">Marge nette par visiteur</span>
						<span class="subtitle">30 jours<br></span>
					</div>
				</div>*}
			</div>
		</div>
	</div>
	<form>
	
	
		<div class="panel-footer">
			<a href="{$salesDetailsLink|escape:'html':'UTF-8'}" class="btn btn-default pull-right"><i class="icon-shopping-cart"></i> {l s='Détails des ventes'}</a>
		</div>
	</form>
</div>