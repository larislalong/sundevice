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
	<h3><i class="icon icon-folder-open"></i> {l s='Export instantanné - Filtres' mod='isoorderdetailexport'}</h3>
	<p class="alert alert-info">
		{l s='Veuillez remplir ce formulaire pour configurer votre export, puis cliquer sur le bouton "exporter" pour télécharger le fichier.' mod='isoorderdetailexport'}
	</p>

	<form id="product-associations" class="panel product-tab" method="post">
		<div class="form-group clearfix clear">
			<label class="control-label col-lg-3" for="filter_by_date_start">
				<span class="label-tooltip" data-toggle="tooltip"
				title="{l s='yyyy-mm-dd : Format des dates attendues, merci de le respecter!'}">
					{l s='Filtrer par Période'}
				</span>
			</label>
			<div class="col-lg-4">
				<div class="input-group">
					<input class="datepicker" type="text" id="filter_by_date_start" name="filter[date_start]" placeholder="Date de début" autocomplete="off" />
					<span class="input-group-addon"><i class="icon-minus"></i></span>
					<input class="datepicker" type="text" id="filter_by_date_end" name="filter[date_end]" placeholder="Date de fin" autocomplete="off" />
					<span class="input-group-addon"><i class="icon-calendar"></i></span>
				</div>
			</div>
		</div>
		{if isset($customersList) && $customersList|count}
		<div class="form-group clearfix clear">
			<label class="control-label col-lg-3" for="filter_by_customers">{l s='Filtrer par Clients'}</label>
			<div class="col-lg-9">
				<select name="filter[customers][]" id="filter_by_customers" multiple="multiple" class="chosen">
					{foreach from=$customersList item=customer}
						<option value="{$customer.id_customer}">{$customer.firstname} {$customer.lastname}</option>
					{/foreach}
				</select>
			</div>
		</div>
		{/if}
		<div class="form-group clearfix clear">
			<label class="control-label col-lg-3" for="filter_by_products">{l s='Filtrer par Produit'}</label>
			<div class="col-lg-9">
				{if isset($productsList) && $productsList|count}
					<select id="filter_by_products" name="filter[products][]" multiple="multiple" class="chosen">
						{foreach from=$productsList item=product}
							<option value="{$product.id_product}">{$product.name}</option>
						{/foreach}
					</select>
					<br/>
				{/if}
			</div>
		</div>
		{if (isset($attributesList.grade) && $attributesList.grade|count) || (isset($attributesList.couleur) && $attributesList.couleur|count) || (isset($attributesList.capacite) && $attributesList.capacite|count)}
		<div class="form-group clearfix clear">
			<label class="control-label col-lg-3" for="filter_by_products">{l s='Filtrer par Attributs de produit'}</label>
			<div class="col-lg-9 row">
			{if isset($attributesList.grade) && $attributesList.grade|count}
				<div class="col-lg-3">
					<select id="filter_by_grade" name="filter[product_grade][]" multiple="multiple" class="chosen">
						<option value="">Grade</option>
						{foreach from=$attributesList.grade item=attr}
							<option value="{$attr.id_attribute}">{$attr.name}</option>
						{/foreach}
					</select>
				</div>
			{/if}
			{if isset($attributesList.couleur) && $attributesList.couleur|count}
				<div class="col-lg-3">
					<select id="filter_by_color" name="filter[product_color][]" multiple="multiple" class="chosen">
						<option value="">Couleur</option>
						{foreach from=$attributesList.couleur item=attr}
							<option value="{$attr.id_attribute}">{$attr.name}</option>
						{/foreach}
					</select>
				</div>
			{/if}
			{if isset($attributesList.capacite) && $attributesList.capacite|count}
				<div class="col-lg-3">
					<select id="filter_by_capacity" name="filter[product_capacity][]" multiple="multiple" class="chosen">
						<option value="">Capacité</option>
						{foreach from=$attributesList.capacite item=attr}
							<option value="{$attr.id_attribute}">{$attr.name}</option>
						{/foreach}
					</select>
				</div>
			{/if}
			</div>
		</div>
		{/if}
		<div class="panel-footer clearfix">
			<div class="panel pull-left" style="width:400px;">
				<h3><i class="icon icon-export"></i> {l s='Export - Commandes' mod='isoorderdetailexport'}</h3>
				<div class="panel-body clearfix clear">
					<button type="submit" name="exportAndDownload" class="btn btn-default"><i class="process-icon-download"></i> {l s='Export et Téléchargement'}</button>
					<button type="submit" name="exportAndMail" class="btn btn-default"><i class="process-icon-envelope"></i> {l s='Export et Email'}</button>
				</div>
			</div>
			<div class="panel pull-right" style="width:400px;">
				<h3><i class="icon icon-export"></i> {l s='Export - Commandes et détails' mod='isoorderdetailexport'}</h3>
				<div class="panel-body clearfix clear">
					<button type="submit" name="exportDetailsAndDownload" class="btn btn-default"><i class="process-icon-download"></i> {l s='Export et Téléchargement'}</button>
					<button type="submit" name="exportDetailsAndMail" class="btn btn-default"><i class="process-icon-envelope"></i> {l s='Export et Email'}</button>
				</div>
			</div>
		</div>
		<div class="clear clearfix"></div>
	</form>

</div>

<div class="panel">
	<h3><i class="icon icon-folder-open"></i> {l s='Export instantanné - Produits et déclinaisons' mod='isoorderdetailexport'}</h3>
	<div class="panel-body clearfix clear">
		<form id="product-declinaisons" class="product-tab" method="post">
			<button type="submit" name="exportProductAndMail" class="btn btn-default pull-left"><i class="process-icon-envelope"></i> {l s='Export des produits et Email'}</button>
		</form>
	</div>
</div>

<script type="text/javascript">
	var geocoder = new google.maps.Geocoder();
	var delivery_map, invoice_map;

	$(document).ready(function(){
		$('.datepicker').datetimepicker({
			prevText: '',
			nextText: '',
			dateFormat: 'yy-mm-dd',
			// Define a custom regional settings in order to use PrestaShop translation tools
			currentText: '{l s='Now' js=1}',
			closeText: '{l s='Done' js=1}',
			ampm: false,
			amNames: ['AM', 'A'],
			pmNames: ['PM', 'P'],
			timeFormat: 'hh:mm:ss tt',
			timeSuffix: '',
			timeOnlyTitle: '{l s='Choose Time' js=1}',
			timeText: '{l s='Time' js=1}',
			hourText: '{l s='Hour' js=1}',
			minuteText: '{l s='Minute' js=1}'
		});
	});
</script>
