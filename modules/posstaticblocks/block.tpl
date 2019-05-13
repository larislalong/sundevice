
     {foreach from=$staticblocks key=key item=block}
	  {if $block.active == 1}
		  <p class ="title_block"> {l s={$block.title} } </p>
		
	  {/if}
	  {$block.description}
	  {if $block.insert_module == 1}
		{$block.block_module}
	   {/if}
     {/foreach}