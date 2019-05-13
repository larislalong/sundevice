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
	var usableValuesConst = {$usableValuesConst|json_encode};
	var menuTypesConst = {$menuTypesConst|json_encode};
        var valueResultConst = {$valueResultConst|json_encode};
	var mainMenuTypeConst = {$mainMenuTypeConst|json_encode};
	var propertiesTypesConst = {$propertiesTypesConst|json_encode};
	var styleTypesConst = {$styleTypesConst|json_encode};
        var linkTypesConst = {$linkTypesConst|json_encode};
        var homeLinkList = {$homeLinkList|json_encode};
        var defaultModLanguage = {$defaultModLanguage|intval};
	var ajaxModuleUrl = "{$ajaxModuleUrl|escape:'javascript'}";
	var ps_version = "{$ps_version|escape:'javascript'}";
	var ajaxRequestErrorMessage = "{l s='An error occurred while connecting to server' mod='menupro'}";
	var loaderPropertiesMessage = "{l s='Retrieving properties...' mod='menupro'}";
	var noRecordMessage = "{l s='No records found' mod='menupro'}";
	var enabledMessage = "{l s='Enabled' mod='menupro'}";
	var disabledMessage = "{l s='Disabled' mod='menupro'}";
        var loaderPropertyValueMessage = "{l s='Searching for value...' mod='menupro'}";
        var loaderGetStyleMessage = "{l s='Searching for style...' mod='menupro'}";
        var linkBaseDescription = "{l s='Base URL' mod='menupro'}";
</script>
{if $ps_version<'1.6'}
<div id="dialogModalParent">
	<div id="dialogLoader">
		<div class="loader"></div>
		<div class="loader-text"></div>
	</div>
</div>
{/if}
{if $ps_version>='1.6'}<div class="panel"><h3><i class="icon icon-tags"></i> {else}<fieldset class="block-doc"><legend>{/if}
	{l s='Documentation' mod='menupro'}
{if $ps_version>='1.6'}</h3>{else}</legend>{/if}
	<p>
		&raquo; {l s='You can get a PDF documentation to configure this module' mod='menupro'} :
		<ul>
			<li><a href="{$englishDocLink|escape:'javascript'}" target="_blank">{l s='English' mod='menupro'}</a></li>
			<li><a href="{$frenchDocLink|escape:'javascript'}" target="_blank">{l s='French' mod='menupro'}</a></li>
		</ul>
	</p>
{if $ps_version>='1.6'}</div>{else}</fieldset>{/if}