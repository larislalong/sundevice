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
	var displayStyleLevels = {$displayStyleLevels|intval};
	var displayHtmlContents = {$displayHtmlContents|intval};
</script>
{$headerTitle = {l s='Edit Secondary menu' mod='menupro'}}
{if $ps_version>='1.6'}
<div class="modal fade" id="modalEditSecondaryMenu" tabindex="-1" role="dialog" aria-labelledby="modalEditSecondaryMenuTitle" aria-hidden="true">
	<div class="modal-dialog modal-form-edition" role="document">
		<div class="modal-content">
			<div class="modal-header clearfix">
				<h5 class="modal-title pull-left" id="modalEditSecondaryMenuTitle">{$headerTitle|escape:'htmlall':'UTF-8'}</h5>
				<button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
{else}
<div id="dialogModalParent">
	<div id="modalEditSecondaryMenu" title="{$headerTitle|escape:'htmlall':'UTF-8'}">
		<div id="content">
{/if}
				<div id="divEditSecondaryMenuNotify" style="display:none;"></div>
				<div id="modalEditSecondaryMenuContent">
					<div class="row{if $ps_version<'1.6'} clearfix{/if}">
						<div class="productTabs col-lg-2 col-md-3">
							{if $ps_version>='1.6'}<div class="tab list-group">{else}<ul class="tab">{/if}
								{if $ps_version<'1.6'}<li class="tab-row">{/if}
								<a id="nav-information" class="list-group-item nav-optiongroup active" href="#" 
								title="{l s='Information' mod='menupro'}">
									{l s='Information' mod='menupro'}
								</a>
								{if $ps_version<'1.6'}</li>{/if}
								{if $ps_version<'1.6'}<li class="tab-row">{/if}
								<a id="nav-own-style" class="list-group-item nav-optiongroup" href="#" 
								title="{l s='Style Sheet' mod='menupro'}">
									{l s='Style Sheet' mod='menupro'}
								</a>
								{if $ps_version<'1.6'}</li>{/if}
								{if $displayStyleLevels}
								{if $ps_version<'1.6'}<li class="tab-row">{/if}
								<a id="nav-default-styles" class="list-group-item nav-optiongroup" href="#" 
								title="{l s='Styles Sheets for submenus' mod='menupro'}">
									{l s='Styles Sheets for submenus' mod='menupro'}
								</a>
								{if $ps_version<'1.6'}</li>{/if}
								{/if}
								{if $displayHtmlContents}
								{if $ps_version<'1.6'}<li class="tab-row">{/if}
								<a id="nav-html-contents" class="list-group-item nav-optiongroup" href="#" 
								title="{l s='Html contents' mod='menupro'}">
									{l s='Html contents' mod='menupro'}
								</a>
								{if $ps_version<'1.6'}</li>{/if}
								{/if}
							{if $ps_version>='1.6'}</div>{else}</ul>{/if}
						</div>
						<div class="form-horizontal col-lg-10 col-md-9{if $ps_version<'1.6'} clearfix{/if}">
							<div id="divInformationBlock" class="nav-information tab-optiongroup">
								{include file="{$displayHtmlTemplate|escape:'htmlall':'UTF-8'}" htmlContent=$secondaryMenuInformationFormContent}
								{if $ps_version<'1.6'}
								<div class="margin-form menupro toolbarBox clearfix">
									<button type="button" class="pull-left mp-button btnCancel" name="cancelsecondarymemu">
										<i class="process-icon-back mp-icon"></i>{l s='Quit' mod='menupro'}
									</button>
									<button type="button" name="savesecondarymemu" class="pull-right mp-button btnSave">
										<i class="process-icon-save mp-icon"></i>{l s='Save' mod='menupro'}
									</button>
									<button type="submit" class="pull-right mp-button btnSaveAndStay" name="staymainmemu">
										<i class="process-icon-save-and-stay mp-icon"></i>{l s='Save and stay' mod='menupro'}
									</button>
								</div>
								{/if}
							</div>
							<div class="nav-own-style tab-optiongroup" style="display: none;">
								{include file="{$displayHtmlTemplate|escape:'htmlall':'UTF-8'}" htmlContent=$secondaryMenuStyleFormContent}
							</div>
							{if $displayStyleLevels}
							<div class="nav-default-styles tab-optiongroup" style="display: none;">
								{include file="{$displayHtmlTemplate|escape:'htmlall':'UTF-8'}" htmlContent=$stylesLevelFormContent}
							</div>
							{/if}
							{if $displayHtmlContents}
							<div id="divHtmlContentBlock" class="nav-html-contents tab-optiongroup" style="display: none;">
								{include file="{$displayHtmlTemplate|escape:'htmlall':'UTF-8'}" htmlContent=$htmlContentFormContent}
							</div>
							{/if}
						</div>
					</div>
				</div>
{if $ps_version>='1.6'}
			</div>
		</div>
	</div>
</div>
{else}
		</div>
	</div>
</div>
{/if}