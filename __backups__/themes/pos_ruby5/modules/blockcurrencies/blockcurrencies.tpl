<!-- Block currencies module -->
{if count($currencies) > 1}
	<div id="currencies-block-top" class="loca_content">
		<span class="title_top fontcustom1">{l s='Currencies' mod='blockcurrencies'}</span>
		<form id="setCurrency" action="{$request_uri}" method="post">
			<ul id="first-currencies" class="currencies_ul">
				{foreach from=$currencies key=k item=f_currency}
					{if strpos($f_currency.name, '('|cat:$f_currency.iso_code:')') === false}
						{assign var="currency_name" value={l s='%s (%s)' sprintf=[$f_currency.name, $f_currency.iso_code]}}
					{else}
						{assign var="currency_name" value=$f_currency.name}
					{/if}
					<li {if $cookie->id_currency == $f_currency.id_currency}class="selected"{/if}>
						<a href="javascript:setCurrency({$f_currency.id_currency});" rel="nofollow" title="{$currency_name}">
							<span class="fontcustom1">{$currency_name}</span>
						</a>
					</li>
				{/foreach}
			</ul>
		</form>
	</div>
{/if}
<!-- /Block currencies module -->
