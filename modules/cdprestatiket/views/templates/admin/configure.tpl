{*
* 2013 - 2015 CleanDev
*
* NOTICE OF LICENSE
*
* This file is proprietary and can not be copied and/or distributed
* without the express permission of CleanDev
*
* @author    CleanPresta : www.cleanpresta.com <contact@cleanpresta.com>
* @copyright 2013 - 2015 CleanDev.net
* @license   You only can use module, nothing more!
*}

<style>
  /*.ui-tabs-vertical { width: 100%; }
  .ui-tabs-vertical .ui-tabs-nav { /*float: left; width: 24%;*/ }
  .ui-tabs-vertical .ui-tabs-nav li { clear: left; width: 100%; border-bottom-width: 1px !important; border-right-width: 0 !important; margin: 0 -1px .2em 0; }
  .ui-tabs-vertical .ui-tabs-nav li a { display:block; }
  .ui-tabs-vertical .ui-tabs-nav li.ui-tabs-active { padding-bottom: 0; padding-right: .1em; border-right-width: 1px; }
  .ui-tabs-vertical .ui-tabs-panel { padding: 10px; }*/
</style>


{if $notice}
<div class="">{$notice}</div>
{/if}
<div class="">
	<div id="tabs" style="border:none;padding:10px">
		<ul class="col-xs-12 col-sm-2 col-md-2" style="background: #FFF;"> 
			<li class="col-xs-12"><a class="col-xs-12" href="#documentation">{l s='Documentation' mod='cdprestatiket'}</a></li>
			
			{if $tabConfig && $tabConfig|@count>0}
				{foreach from=$tabConfig item='conf' key=i}
					<li class="col-xs-12"><a class="col-xs-12" href="#{$conf.id}">{$conf.title|escape:'htmlall':'UTF-8'}</a></li>
				{/foreach}
			{/if}
			<li class="col-xs-12"><a class="col-xs-12" href="#contact">{l s='Contact-us' mod='cdprestatiket'}</a></li> 
			
			<li class="col-xs-12" style="margin-top:30px"><a class="col-xs-12 list-group-item" data-original-title="" title=""><i class="icon-info"></i> {l s='Version' mod='cdprestatiket'} : {$version|escape:'htmlall':'UTF-8'}</a><li>
			
		</ul>
		
		<div id="documentation" class="col-xs-12 col-sm-10 col-md-10 tab-pane">
			<div class="panel">
				<div class="panel-heading"><i class="icon icon-book">&nbsp;&nbsp;</i> {l s='Documentation' mod='cdprestatiket'}</div>
				<div class="form-wrapper">
					<p>{$description|escape:'htmlall':'UTF-8'}</p>
					
					{if $readme}
					<div class="media">
						<a class="pull-left" target="_blank" href="{$readme|escape:'htmlall':'UTF-8'}" data-original-title="" title="">
							<img height="32" width="32" class="media-object" src="{$module_dir|escape:'htmlall':'UTF-8'}/views/img/pdf.png" alt="" title="">
						</a>
						<div class="media-body">
							{l s='Attached you will find the documentation for this module. Do not hesitate to consult in order to properly configure the module.' mod='cdprestatiket'}
						</div>
					</div>
					{/if}
					
					{if $change_log}
					<hr>
					<p style="font-size: 15px;font-weight: bold;">{l s='Change Log' mod='cdprestatiket'}</p>
					<div style="padding:10px;height:100px;border:1px dotted #f6f6f6;overflow-y:scroll;">
						{$change_log}
					</div>
					{/if}
				</div> 
			</div>
		</div>
	  
		{if $tabConfig && $tabConfig|@count>0}
			{foreach from=$tabConfig item='conf' key=i}
				<div id="{$conf.id|escape:'htmlall':'UTF-8'}" class="col-xs-12 col-sm-10 col-md-10 tab-pane">
					{$conf.content}
				</div>
			{/foreach}
		{/if}
		
		<div id="contact" class="col-xs-12 col-sm-10 col-md-10 tab-pane">
			<div class="panel">
				<div class="panel-heading"><i class="icon icon-question-sign">&nbsp;&nbsp;</i>{l s='Contact-us' mod='cdprestatiket'}</a></div>
				<div class="form-wrapper">
					<p>{l s='CleanPresta Ecommerce/PrestaShop department of CleanDev' mod='cdprestatiket'}</p><hr>
					{l s='Contact and Support' mod='cdprestatiket'} : <a target="_blank" href="{$addon_ratting|escape:'htmlall':'UTF-8'}">{l s='click here' mod='cdprestatiket'}</a>
				</div> 
			</div>
		</div>
	</div>
	
	<div class="panel alert-success" style="text-align:center;background-color:#ddf0de;font-size: 14px;">
		<p>{l s='You are satisfied with your module, Encourage us' mod='cdprestatiket' mod='cdprestatiket'} : <a target="_blank" href="http://addons.prestashop.com/ratings.php">{l s='Please note this module on PrestaShop Addons, giving it 5 stars' mod='cdprestatiket'}</a></p>
		<p>{l s='If you are not satisfied' mod='cdprestatiket'} : <a target="_blank" href="{$addon_ratting|escape:'htmlall':'UTF-8'}">{l s='we will be pleased to hear from you' mod='cdprestatiket'}</a></p>
	</div>
	
	{if $features && $features.module|@count>0}
	<div class="panel">
		<div class="panel-heading"><i class="icon icon-signal">&nbsp;&nbsp;</i>{l s='Please do not forget' mod='cdprestatiket'}</a></div>
		<ul class="form-wrapper" style="padding:0"> 
			{foreach from=$features.module item='feature' key=i}
				<li class="col-md-12" style="margin-bottom:10px;border-bottom:1px dotted;list-style: none;padding:5px 0">
					<div class="col-xs-12 col-sm-4 col-md-4"><a href="{$feature.addon}" target="_blank">{$feature.name|escape:'htmlall':'UTF-8'}</a></div>
					{if !empty($feature.description)}<div class="col-xs-12 col-sm-8 col-md-8">{$feature.description|escape:'htmlall':'UTF-8'}</div>{/if}
					<div style="clear:both"></div>
				</li>
			{/foreach}
		</ul> 
		<div style="clear:both"></div>
	</div>
	{/if}
</div>

<script type="text/javascript">
// <![CDATA[
	$(document).ready(function($) {
		/*$("#tabs").tabs({
			activate: function (e, ui) { 
				$.cookie('selected-tab', ui.newTab.index(), { path: '/' }); 
			}, 
			active: $.cookie('selected-tab')
		});*/
	});
	
	$( "#tabs").tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
    $( "#tabs li").removeClass( "ui-corner-top" ).addClass("ui-corner-left" );
// ]]>
</script>