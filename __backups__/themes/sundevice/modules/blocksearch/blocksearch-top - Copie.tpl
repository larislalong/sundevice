<!-- Block search module TOP -->
<div class="searchblock">
	<a class="icon_top current" href="javascript:void(0)" title="{l s='Menu'}">
		<i class="icon_search"></i>
	</a>
	<div id="search_block_top" class="toogle_content">
		<form id="searchbox" method="get" action="{$link->getPageLink('search', null, null, null, false, null, true)|escape:'html':'UTF-8'}" >
			<input type="hidden" name="controller" value="search" />
			<input type="hidden" name="orderby" value="position" />
			<input type="hidden" name="orderway" value="desc" />
			<input class="search_query form-control" type="text" id="search_query_top" name="search_query" placeholder="{l s='Search' mod='blocksearch'}" value="{$search_query|escape:'htmlall':'UTF-8'|stripslashes}" />
			<button type="submit" name="submit_search" class="btn btn-default button-search">
				<span>{l s='Search' mod='blocksearch'}</span>
			</button>
		</form>
	</div>
</div>
<!-- /Block search module TOP -->