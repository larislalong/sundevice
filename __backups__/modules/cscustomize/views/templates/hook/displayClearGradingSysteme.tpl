<div id="grading_systeme_link" class="block_graging_system_wrapper box-title">
	<div class="block_graging_system_container container main-parent">
		<div class="graging_system_description footer-block">
			<h3>{l s='Clear grading systeme' mod='cscustomize'}</h3>
            <div class="toggle-footer">
			<p>
				{l s='We have created a simplified and clear cosmetic grading system
				that precisely defines the condition of each device.' mod='cscustomize'}
			</p>
			
			<div class="graging_system_dropdown">
				  <div class="panel-group" id="accordion_graging_system">
					{foreach from=$listblocs key=item_key item=item_value name=grade}
					<div class="panel panel-default cs-item-parent" data-img-src="{$base_dir}modules/cscustomize/img/{$item_value.nameimg}" data-img-desc="{$item_value.secondtitle}">
					  <div class="panel-heading">
						<h4 class="panel-title">
						  <a class="{if $smarty.foreach.grade.first}active{/if}" data-toggle="collapse" data-parent="#accordion_graging_system" href="#graging_system_collapse{$item_key}">
							<span>{$item_value.titleblock}</span>
							<i class="fa fa-chevron-down" aria-hidden="true"></i>
						  </a>
						</h4>
					  </div>
					  <div id="graging_system_collapse{$item_key}" class="panel-collapse collapse {if $smarty.foreach.grade.first}in{/if}">
						<div class="panel-body">{$item_value.editortext}</div>
					  </div>
					</div>
					{/foreach}
				  </div> 
			</div>
            
            <div class="div-learn-more div-learn-more-left">
        		<a href="{if (isset($id_element) && $id_element)}{$link->getCMSLink($id_element)}{else}#{/if}" class="learn-more-action">{l s='Learn more' mod='cscustomize'}</a>
        	</div></div>
			
		</div>
		<div class="graging_system_img image-block toggle-footer">
			{foreach from=$listblocs key=item_key item=item_value name=grade_img}
			<p class="image-desc">{$item_value.secondtitle}</p>
			{if $smarty.foreach.grade_img.first}
			<img class="img-responsive image-img" src="{$base_dir}modules/cscustomize/img/{$item_value.nameimg}" alt="{$item_value.secondtitle}"/>
			{/if}
			{break}
			{/foreach}
		</div>
	</div>
</div>
<div class="clear"></div>