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
<script type="text/javascript">
	var loaderGetEditionHtmlContentMessage = "{l s='Getting edition form...' mod='menupro'}";
	var loaderSaveHtmlContentMessage = "{l s='Saving html content...' mod='menupro'}";
	var loaderDeleteHtmlContentMessage = "{l s='deleting html content...' mod='menupro'}";
	var confirmDeleteHtmlContentMessage = "{l s='Are you sure you want to delete content' mod='menupro'}";
	var loaderStatusChangeHtmlContentMessage = "{l s='changing status html content...' mod='menupro'}";
</script>
{if $ps_version>='1.6'}<div class="panel"><h3>{else}<fieldset><legend>{/if}
	{l s='Html contents' mod='menupro'}
{if $ps_version>='1.6'}</h3>{else}</legend>{/if}
	<div id="divHtmlContentNotify" style="display:none;"></div>
	<div class="row{if $ps_version>='1.6'}{else} clearfix{/if}">
		<div id="divHtmlContentList" class="col-lg-5 col-md-5">
			<div id="divHtmlContentListDisabler" style="display:none;" class="div-disabler"></div>
			{if $ps_version<'1.6'}<div id="form-menupro_html_content">{/if}
			{include file="{$displayHtmlTemplate|escape:'htmlall':'UTF-8'}" htmlContent=$htmlContentList}
			{if $ps_version<'1.6'}</div>{/if}
		</div>
		<div id="divHtmlContentEdition" class="{if $ps_version>='1.6'}col-lg-7 col-md-7{else}col-lg-12{/if}" style="display:none;">
			{if $ps_version>='1.6'}<div class="panel" id="panelHtmlContentEditionNotify"><h3>{else}<fieldset id="panelHtmlContentEditionNotify"><legend>{/if}
				{l s='Html content edition' mod='menupro'}
			{if $ps_version>='1.6'}</h3>{else}</legend>{/if}
				<div id="divHtmlContentEditionNotify" style="display:none;"></div>
			{if $ps_version>='1.6'}</div>{else}</fieldset>{/if}
			<div id="divHtmlContentForm" style="display:none;"></div>
			{if $ps_version<'1.6'}
			<div id="divHtmlContentFormButtons" style="display:none;">
				<div class="margin-form menupro toolbarBox clearfix">
					<button type="button" class="pull-left mp-button btnCancel" name="cancelsecondarymemu" id="btnHtmlContentCancel">
						<i class="process-icon-cancel mp-icon"></i>{l s='Cancel' mod='menupro'}
					</button>
					<button type="button" name="savesecondarymemu" class="pull-right mp-button btnSave" id="btnHtmlContentSave">
						<i class="process-icon-save mp-icon"></i>{l s='Save' mod='menupro'}
					</button>
				</div>
			</div>
			{/if}
		</div>
	</div>
	<div class="{if $ps_version>='1.6'}panel-footer{else}margin-form menupro toolbarBox clearfix{/if}">
		<button type="button" class="{if $ps_version>='1.6'}btn btn-default{else}mp-button{/if} pull-right btnCancel" title="{l s='Quit' mod='menupro'}">
			<i class="process-icon-back{if $ps_version<'1.6'} mp-icon{/if}"></i>{l s='Quit' mod='menupro'}
		</button>
	</div>
{if $ps_version>='1.6'}</div>{else}</fieldset>{/if}