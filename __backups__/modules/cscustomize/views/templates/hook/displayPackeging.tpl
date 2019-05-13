<div id="packaging_link" class="block_packaging_wrapper">
	<div class="block_packaging_container container main-parent">
		<div class="packaging_description">
			<h3>{l s='Packaging' mod='cscustomize'}</h3>
			<p>
				{l s='Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
				eiusmod tempor incididunt ut labore et dolore magna aliqua.' mod='cscustomize'}
			</p>
			
			<div class="packaging_dropdown">
				  <div class="panel-group" id="accordion_packading">
				  {$index=1}
				  {foreach from=$listblocs key=item_key item=item_value name=packaging}
					<div class="panel panel-default cs-item-parent" data-img-src="{$base_dir}modules/cscustomize/img/{$item_value.nameimg}" data-img-desc="{$item_value.secondtitle}">
					  <div class="panel-heading">
						<h4 class="panel-title">
						  <a class="{if $index==2}active{/if}" data-toggle="collapse" data-parent="#accordion_packading" href="#packaging_collapse{$item_key}">
							<span>{$item_value.titleblock}</span>
							<i class="fa fa-chevron-down" aria-hidden="true"></i>
						  </a>
						</h4>
					  </div>
					  <div id="packaging_collapse{$item_key}" class="panel-collapse collapse {if $index==2}in{/if}">
						<div class="panel-body">{$item_value.editortext}</div>
					  </div>
					</div>
					{$index=$index+1}
					{/foreach}
				  </div> 
			</div>
            
            <div class="div-learn-more div-learn-more-left">
        		<a href="#" class="learn-more-action">{l s='Learn more' mod='cscustomize'}</a>
        	</div>
			
		</div>
		<div class="packaging_img image-block">
			{$index=1}
			{foreach from=$listblocs key=item_key item=item_value name=packaging_img}
			{if $index==2}
			<img class="img-responsive image-img" src="{$base_dir}modules/cscustomize/img/{$item_value.nameimg}" alt="{$item_value.secondtitle}"/>
			{break}
			{/if}
			{$index=$index+1}
			{/foreach}
		</div>
	</div>
</div>
<div class="clear"></div>