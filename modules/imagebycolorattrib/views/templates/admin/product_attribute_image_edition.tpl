{*
* 2007-2016 PrestaShop
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
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<div class="panel edition_content">
	<h3>{l s='Edition of images for color' mod='imagebycolorattrib'} {$attributeName}</h3>
	<div class="form-group">
		<label class="control-label col-lg-3">{l s='Images' mod='imagebycolorattrib'}</label>
		<div class="col-lg-9">
			{if $images|count}
			<ul class="list-inline ibca_block_image">
				{foreach from=$images key=k item=image}
				<li>
					<input class="image-item-field" type="checkbox" value="{$image.id_image}" id="image-item-field_{$image.id_image}" {if $image.isSelected}checked{/if}/>
					<label for="image-item-field_{$image.id_image}">
						<img class="img-thumbnail" src="{$smarty.const._THEME_PROD_DIR_}{$image.obj->getExistingImgPath()}-{$imageType}.jpg" alt="{$image.legend|escape:'html':'UTF-8'}" title="{$image.legend|escape:'html':'UTF-8'}" />
					</label>
				</li>
				{/foreach}
			</ul>
			{else}
				<div class="alert alert-warning">{l s='You must upload an image before you can select one for your combination.' mod='imagebycolorattrib'}</div>
			{/if}
		</div>
	</div>
	<div class="panel-footer">
		<button type="button" class="btn btn-default btnCancel"><i class="process-icon-cancel"></i> {l s='Cancel'}</button>
		<button type="button" class="btn btn-default pull-right btnSave"><i class="process-icon-save"></i> {l s='Save'}</button>
	</div>
</div>
<script type="text/javascript">
	hideOtherLanguage({$default_form_language});
</script>
