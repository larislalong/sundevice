{capture name=path}
    <span class="navigation-pipe">
        {$navigationPipe}
    </span>
    <span class="navigation_page">
        {l s='Carts' mod='savemypurchases'}
    </span>
{/capture}

{if !empty($purchases)}
    <table class="table table-bordered footab">
        <thead>
                <th class="first_item" data-sort-ignore="true">{l s='Cart' mod='savemypurchases'}</th>
                <th data-sort-ignore="true" data-hide="" class="item">{l s='Created at' mod='savemypurchases'}</th>
                <th data-sort-ignore="true" data-hide="" class="item">{l s='Product(s) in cart' mod='savemypurchases'}</th>
                <th class="last_item text-center">{l s='Price' mod='savemypurchases'}</th>
                <th class="last_item text-center">{l s='Load' mod='savemypurchases'}</th>
        </thead>
        <tbody>
        {assign var='i' value=1}
            {foreach from=$purchases item=purchase key=id_cart}
                <tr>
                    <td>{$i}</td>
                    <td>{dateFormat date=$purchase.date}</td>
                    <td>
						<ul>
						{foreach from=$purchase.products item=product}
							<li>{$product}</li>
						{/foreach}
						</ul>
					</td>
                    <td>
                        {$purchase.total}
                    </td>
                    <td>
                        <a class="btn btn-default button button-small" href="{$link->getModulelink('savemypurchases','purchases', ['load_cart'=>$id_cart])}">
								<span>
									{l s='Load this cart' mod='savemypurchases'}<i class="icon-chevron-right right"></i>
								</span>
                        </a>
                    </td>
                </tr>
                {assign var='i' value=$i+1}
            {/foreach}
        </tbody>
        <tfoot>
            <th class="first_item" data-sort-ignore="true">{l s='Cart' mod='savemypurchases'}</th>
            <th data-sort-ignore="true" data-hide="" class="item">{l s='Created at' mod='savemypurchases'}</th>
            <th data-sort-ignore="true" data-hide="" class="item">{l s='Product(s) in cart' mod='savemypurchases'}</th>
            <th class="last_item text-center">{l s='Price' mod='savemypurchases'}</th>
            <th class="last_item text-center">{l s='Load' mod='savemypurchases'}</th>
        </tfoot>
    </table>

{else}
    <p class="alert alert-warning">
        {l s='You have nothing cart save' mod='savemypurchases'}
    </p>
{/if}