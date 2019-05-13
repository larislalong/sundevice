{*
* 2007-2017 PrestaShop
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
*  @copyright 2007-2017 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<script type="text/javascript">
	var ibcaAjaxUrl = "{$moduleLink}";
	var LOADING_FORM_MESSAGE = "{l s='Loading form...' mod='imagebycolorattrib'}";
	var SAVING_MESSAGE = "{l s='Saving data...' mod='imagebycolorattrib'}";
	var ERROR_MESSAGE = "{l s='An error occured while connecting to server' mod='imagebycolorattrib'}";
</script>
<div id="product-imagesbyattribute" class="panel product-tab">
	<input type="hidden" class="productId" value="{$idProduct}" />
	<h3>{l s='Image by color' mod='imagebycolorattrib'}</h3>
	<div class="form-group">
		<div class="alert alert-info">
			{l s='To edit identifier of the color attribute, ' mod='imagebycolorattrib'}<a target="_blank" class="" href="{$moduleLink}">{l s='Click here' mod='imagebycolorattrib'}</a>
		</div>
	</div>
	<div class="divNotify" style="display:none;"></div>
	<div class="form-group divEditionForm" style="display:none;">
		
	</div>
	<div>
		<div class="divListDisabler" style="display:none; position: absolute;width: 98%; height: 96%; background-color: #000; z-index: 9; opacity: 0.3;" class="div-disabler"></div>
		<div class="panel">
			<div class="panel-heading head-list">
				{l s='Colors'}<span class="badge list-count">{count($attributesList)}</span>
			</div>	
			<div class="table-responsive-row clearfix">
				<table class="table table-items">
					<thead>
						<tr class="nodrag nodrop">
							<th class=""><span class="title_box">{l s='Color name' mod='imagebycolorattrib'}</span></th>
							{if showImagesInList}
							<th class=""><span class="title_box">{l s='Images' mod='imagebycolorattrib'}</span></th>
							<th class=""><span class="title_box">{l s='Total' mod='imagebycolorattrib'}</span></th>
							{/if}
							<th></th>
						</tr>
					</thead>
					<tbody>
						{foreach $attributesList as $data}
						<tr class="tr-item">
							<input type="hidden" name="" value="{$data.id_attribute|intval}" class="td_id_attribute">
							<td>{$data.attributeName}</td>
							{if showImagesInList}
							<td class="td_images">
								{include file="{$imageListTemplate|escape:'htmlall':'UTF-8'}" images=$data.images}
							</td>
							<td class="td_imageCount">{$data.imagesCount}</td>
							{/if}
							<td class="text-right">
								<a href="#" class="btn btn-default item-edit" title="{l s='Edit' mod='imagebycolorattrib'}"><i class="icon-pencil"></i>{l s='Edit' mod='imagebycolorattrib'}</a>
							</td>
						</tr>
						{/foreach}
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

