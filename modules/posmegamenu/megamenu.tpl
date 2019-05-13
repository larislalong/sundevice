<!-- Block categories module -->
{if $blockCategTree != ''}
	<div class="pt_custommenu_mobile hidden-sm hidden-md hidden-lg">
		<div class="navbar">
			<div id="navbar-inner" class="navbar-inner navbar-inactive">
				<a class="btn-navbar fontcustom1">{l s='Category' mod='posmegamenu'}</a>
				<ul id="pt_custommenu_itemmobile" class="tree {if $isDhtml}dhtml{/if}  mobilemenu nav-collapse collapse">
					{foreach from=$blockCategTree.children item=child name=blockCategTree}
						{if $smarty.foreach.blockCategTree.last}
							{include file="$branche_tpl_path" node=$child last='true'}
						{else}
							{include file="$branche_tpl_path" node=$child}
						{/if}
					{/foreach}
					    {if isset($cms_link)} 
							{foreach from=$cms_link item=cms_link1}
								<li>
									<a href="#" class="fontcustom1" title="Contains Subs">{$cms_link1.title}</a>
								</li>
							{/foreach}
						{/if}
						{if isset($cms_cate)} 
							{foreach from=$cms_cate item=cms_cate1}
								<li>
									<a href="{$cms_cate1.link}" class="fontcustom1" title="{$cms_cate1.title}">{$cms_cate1.title}</a>
								</li>
							{/foreach}
						{/if}
						{if isset($manufacture_link)} 
							{foreach from=$manufacture_link item=manufacture_link1}
								<li>
									<a href="{$manufacture_link1.link}" class="fontcustom1" title="{$manufacture_link1.title}">{$manufacture_link1.title}</a>
								</li>
							{/foreach}
						{/if}
						{if isset($supply_link)} 
							{foreach from=$supply_link item=supply_link1}
								<li>
									<a href="{$supply_link1.link}" class="fontcustom1" title="{$supply_link1.title}">{$supply_link1.title}</a>
								</li>
							{/foreach}
						{/if}
						{if isset($custom_link)} 
							{foreach from=$custom_link item=custom_link1}
								<li>
									<a href="{$custom_link1->link}" class="fontcustom1" title="{$custom_link1->title}">{$custom_link1.title}</a>
								</li>
							{/foreach}
						{/if}
						{if isset($product_link)} 
							{foreach from=$product_link item=product_link1}
								<li>
									<a href="{$product_link1->link}" class="fontcustom1" title="{$product_link1->title}">{$product_link1.title}</a>
								</li>
							{/foreach}
						{/if}
						{if isset($all_man_link)} {$all_man_link} {/if}
						{if isset($all_sup_link)} {$all_sup_link} {/if}
				</ul>
                                <script type="text/javascript">
                                // <![CDATA[
                                        // we hide the tree only if JavaScript is activated
                                        $('div#categories_block_left ul.dhtml').hide();
                                // ]]>
                                </script>
			</div>
		</div>
</div>
{/if}
<!-- /Block categories module -->
<div class="nav-container visible-desktop hidden-xs">
    <div id="pt_custommenu" class="pt_custommenu">
        {$megamenu}
    </div>
	<div class="clearfix"></div>
</div>
<script type="text/javascript">
//<![CDATA[
var CUSTOMMENU_POPUP_EFFECT = {$effect};
var CUSTOMMENU_POPUP_TOP_OFFSET = {$top_offset};
//]]>
</script>