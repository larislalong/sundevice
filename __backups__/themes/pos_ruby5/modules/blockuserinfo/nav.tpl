<div class="header_user_info">
	<a href="{$link->getPageLink($order_process, true)|escape:'html'}" title="{l s='View my shopping cart' mod='blockuserinfo'}" rel="nofollow" class="fontcustom1">
		{l s='My Cart' mod='blockuserinfo'}
	</a>
	{*<a href="{$link->getPageLink('products-comparison')|escape:'html':'UTF-8'}" title="{l s='Compare' mod='blockuserinfo'}" rel="nofollow" class="fontcustom1 bt_compare{if isset($paginationId)}_{$paginationId}{/if}">
		{l s='Compare' mod='blockuserinfo'}
	</a>
	<a href="{$link->getModuleLink('blockwishlist', 'mywishlist', array(), true)|escape:'html':'UTF-8'}" title="{l s='My wishlists' mod='blockuserinfo'}" rel="nofollow" class="fontcustom1">
		{l s='Wishlist' mod='blockuserinfo'}
	</a>
	<a href="{$link->getPageLink('$order_process', true)|escape:'html':'UTF-8'}" title="{l s='Check out' mod='blockuserinfo'}" rel="nofollow" class="fontcustom1">
		{l s='Check out' mod='blockuserinfo'}
	</a>*}
	<a class="account fontcustom1" href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}" title="{l s='View my customer account' mod='blockuserinfo'}" rel="nofollow">{l s='My account' mod='blockuserinfo'}</a>
	{if $is_logged}
		<a class="logout login fontcustom1" href="{$link->getPageLink('index', true, NULL, "mylogout")|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Sign out' mod='blockuserinfo'}">
			{l s='Sign out' mod='blockuserinfo'}
		</a>
	{else}
		<a class="login fontcustom1" href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Sign in' mod='blockuserinfo'}">
			{l s='Sign in' mod='blockuserinfo'}
		</a>
	{/if}
</div>