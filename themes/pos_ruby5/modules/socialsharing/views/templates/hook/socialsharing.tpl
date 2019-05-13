{if $PS_SC_TWITTER || $PS_SC_FACEBOOK || $PS_SC_GOOGLE || $PS_SC_PINTEREST}
	
	<p class="socialsharing_product list-inline no-print">
		<label class="fontcustom1">{l s='Share:' mod='socialsharing'}</label>
		{if $PS_SC_TWITTER}
			<button data-type="twitter" type="button" class="btn btn-default btn-twitter fontcustom1 social-sharing">
				<i class="icon-twitter"></i><span>{l s="Tweet" mod='socialsharing'}</span>
				<!-- <img src="{$link->getMediaLink("`$module_dir`img/twitter.gif")}" alt="Tweet" /> -->
			</button>
		{/if}
		{if $PS_SC_FACEBOOK}
			<button data-type="facebook" type="button" class="btn btn-default btn-facebook fontcustom1 social-sharing">
				<i class="icon-facebook"></i><span>{l s="Share" mod='socialsharing'}</span>
				<!-- <img src="{$link->getMediaLink("`$module_dir`img/facebook.gif")}" alt="Facebook Like" /> -->
			</button>
		{/if}
		{if $PS_SC_GOOGLE}
			<button data-type="google-plus" type="button" class="btn btn-default btn-google-plus fontcustom1 social-sharing">
				<i class="icon-google-plus"></i><span>{l s="Google+" mod='socialsharing'}</span>
				<!-- <img src="{$link->getMediaLink("`$module_dir`img/google.gif")}" alt="Google Plus" /> -->
			</button>
		{/if}
		{if $PS_SC_PINTEREST}
			<button data-type="pinterest" type="button" class="btn btn-default btn-pinterest fontcustom1 social-sharing">
				<i class="icon-pinterest"></i><span>{l s="Pinterest" mod='socialsharing'}</span>
				<!-- <img src="{$link->getMediaLink("`$module_dir`img/pinterest.gif")}" alt="Pinterest" /> -->
			</button>
		{/if}
	</p>
{/if}