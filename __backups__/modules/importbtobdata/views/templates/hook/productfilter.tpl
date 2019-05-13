{if isset($moreinfos)}
<div class="moreinfos moreinfos-filter">
    <table class="table">
        <tr>
            <td>
                <ul>
                    <li><span class="label">{l s='Last purchase price' mod='importbtobdata'}</span>: <span class="value-item">${$moreinfos.last_purchase_price}</span></li>
                    <li><span class="label">{l s='App' mod='importbtobdata'}</span>: <span class="value-item">{convertPrice price=$moreinfos.app}</span></li>
                    <li><span class="label">{l s='To orders' mod='importbtobdata'}</span>: <span class="value-item">{$moreinfos.to_order|intval}</span></li>
                    <li><span class="label">{l s='Last month sales' mod='importbtobdata'}</span>: <span class="value-item">{$moreinfos.last_month_sale|intval}</span></li>
                    <li><span class="label">{l s='Current month sales' mod='importbtobdata'}</span>: <span class="value-item">{$moreinfos.current_month_sale|intval}</span></li>
                    <li><span class="label">{l s='Current month trend' mod='importbtobdata'}</span>: <span class="value-item">{$moreinfos.current_month_trend|intval}</span></li>
                    <li><span class="label">{l s='B2B Stock' mod='importbtobdata'}</span>: <span class="value-item">{$moreinfos.b2b_stock|intval}</span></li>
					<li><span class="label">{l s='In Batch USA' mod='importbtobdata'}</span>: <span class="value-item">{$moreinfos.inbatch_usa|intval}</span></li>
                </ul>
            </td>
            <td>
                <ul>
                    <li><span class="label">{l s='Average/day' mod='importbtobdata'}</span>: <span class="value-item">{$moreinfos.average|floatval}</span></li>
                    <li><span class="label">{l s='Days of inventory' mod='importbtobdata'}</span>: <span class="value-item">{$moreinfos.inventory_day|floatval}</span></li>
                    <li><span class="label">{l s='Last 7 days of sales' mod='importbtobdata'}</span>: <span class="value-item">{$moreinfos.last_seven_day_sale|intval}</span></li>
                    <li><span class="label">{l s='Average basket' mod='importbtobdata'}</span>: <span class="value-item">{convertPrice price=$moreinfos.average_basket}</span></li>
                    <li><span class="label">{l s='back orders' mod='importbtobdata'}</span>: <span class="value-item">{$moreinfos.back_orders|intval}</span></li>
                    <li><span class="label">{l s='Processing USA' mod='importbtobdata'}</span>: <span class="value-item">{$moreinfos.processing_usa|intval}</span></li>
                    <li><span class="label">{l s='Received USA' mod='importbtobdata'}</span>: <span class="value-item">{$moreinfos.received_usa|intval}</span></li>
					<li><span class="label">{l s='IDP' mod='importbtobdata'}</span>: <span class="value-item">{$moreinfos.idp}</span></li>
                </ul>
            </td>
        </tr>
    </table>
    
</div>
{/if}