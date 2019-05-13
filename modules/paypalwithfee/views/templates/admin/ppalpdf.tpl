{*
 * 2014 4webs
 *
 * DEVELOPED By 4webs.es Prestashop Platinum Partner
 *
 * @author    4webs
 * @copyright 4webs 2014
 * @license   4webs
 * @version 4.0.2
 * @category payment_gateways
 *}
<!-- ADDRESSES -->
{$style_tab}{*HTML cannot escape*}
<table id="addresses-tab" cellspacing="0" cellpadding="0">
    <tr>
        <td width="33%"><span class="bold"> </span><br/><br/>
        {if isset($order_invoice)}{$order_invoice->shop_address }{*HTML cannot escape*}{/if}
    </td>
    <td width="33%">{if $delivery_address}<span class="bold">{l s='Delivery Address' pdf='true'}</span><br/><br/>
        {$delivery_address}{*HTML cannot escape*}
        {/if}
        </td>
        <td width="33%"><span class="bold">{l s='Billing Address'  pdf='true'}</span><br/><br/>
            {$invoice_address}{*HTML cannot escape*}
        </td>
    </tr>
</table>
<!-- / ADDRESSES -->
<div>&nbsp;</div>
<!-- SUMMARY -->
<table id="summary-tab" width="100%">
    <tr>
        <th class="header small" valign="middle">{l s='Invoice Number'  pdf='true'}</th>
        <th class="header small" valign="middle">{l s='Invoice Date'  pdf='true'}</th>
        <th class="header small" valign="middle">{l s='Order Reference'  pdf='true'}</th>
        <th class="header small" valign="middle">{l s='Order date'  pdf='true'}</th>
            {if $addresses.invoice->vat_number}
            <th class="header small" valign="middle">{l s='VAT Number'  pdf='true'}</th>
            {/if}
            {if $addresses.invoice->dni}
            <th class="header small" valign="middle">{l s='DNI'  pdf='true'}</th>
            {/if}
    </tr>
    <tr>
        <td class="center small white">{$title|escape:'html':'UTF-8'}</td>
        <td class="center small white">{dateFormat date=$order->invoice_date full=0|escape:'html':'UTF-8'}</td>
        <td class="center small white">{$order->getUniqReference()|escape:'html':'UTF-8'}</td>
        <td class="center small white">{dateFormat date=$order->date_add full=0|escape:'html':'UTF-8'}</td>
        {if $addresses.invoice->vat_number}
            <td class="center small white">
                {$addresses.invoice->vat_number|escape:'html':'UTF-8'}
            </td>
        {/if}
        {if $addresses.invoice->dni}
            <td class="center small white">
                {$addresses.invoice->dni|escape:'html':'UTF-8'}
            </td>
        {/if}
    </tr>
</table>

<!-- /SUMMARY -->          
<div>&nbsp;</div>                 
<!-- PRODUCTS -->
<table class="product" width="100%" cellpadding="4" cellspacing="0">
    <thead>
        <tr>
            <th class="product header small" width="{$layout.reference.width|escape:'html':'UTF-8'}%">{l s='Reference'  pdf='true'}</th>
            <th class="product header small" width="{$layout.product.width|escape:'html':'UTF-8'}%">{l s='Product'  pdf='true'}</th>
            <th class="product header small" width="{$layout.tax_code.width|escape:'html':'UTF-8'}%">{l s='Tax Rate'  pdf='true'}</th>
                {if isset($layout.before_discount)}
                <th class="product header small" width="{$layout.unit_price_tax_excl.width|escape:'html':'UTF-8'}%">{l s='Base price'  pdf='true'} <br/> {l s='(Tax excl.)'  pdf='true'}</th>
                {/if}
            <th class="product header-right small" width="{$layout.unit_price_tax_excl.width|escape:'html':'UTF-8'}%">{l s='Unit Price'  pdf='true'} <br/> {l s='(Tax excl.)'  pdf='true'}</th>
            <th class="product header small" width="{$layout.quantity.width|escape:'html':'UTF-8'}%">{l s='Qty'  pdf='true'}</th>
            <th class="product header-right small" width="{$layout.total_tax_excl.width|escape:'html':'UTF-8'}%">{l s='Total'  pdf='true'} <br/> {l s='(Tax excl.)'  pdf='true'}</th>
        </tr>
    </thead>
    <tbody>
        <!-- PRODUCTS -->
        {foreach $order_details as $order_detail}
            {cycle values=["color_line_even", "color_line_odd"] assign=bgcolor_class}
            <tr class="product {$bgcolor_class|escape:'html':'UTF-8'}">
                <td width="{$layout.reference.width|escape:'html':'UTF-8'}%" class="product center">
                    {$order_detail.product_reference|escape:'html':'UTF-8'}
                </td>
                <td width="{$layout.product.width|escape:'html':'UTF-8'}%" class="product left">
                    {if $display_product_images}
                        <table width="100%">
                            <tr>
                                <td width="15%">
                                    {if isset($order_detail.image) && $order_detail.image->id}
                                        {$order_detail.image_tag}{*HTML cannot escape*}
                                    {/if}
                                </td>
                                <td width="5%">&nbsp;</td>
                                <td width="80%">
                                    {$order_detail.product_name|escape:'html':'UTF-8'}
                                </td>
                            </tr>
                        </table>
                    {else}
                        {$order_detail.product_name|escape:'html':'UTF-8'}
                    {/if}
                </td>
                <td width="{$layout.tax_code.width|escape:'html':'UTF-8'}%" class="product center">
                    {$order_detail.order_detail_tax_label}{*HTML cannot escape*}
                </td>
                {if isset($layout.before_discount)}
                    {*before discount*}
                    <td width="{$layout.unit_price_tax_excl.width|escape:'html':'UTF-8'}%" class="product center">
                        {if isset($order_detail.unit_price_tax_excl_before_specific_price)}
                            {displayPrice currency=$order->id_currency price=$order_detail.unit_price_tax_excl_before_specific_price|escape:'html':'UTF-8'}
                        {else}
                            --
                        {/if}
                    </td>
                {/if}
                <td width="{$layout.unit_price_tax_excl.width|escape:'html':'UTF-8'}%" class="product right">
                    {*unit price*}
                    {displayPrice currency=$order->id_currency price=$order_detail.unit_price_tax_excl_including_ecotax|escape:'html':'UTF-8'}
                    {if $order_detail.ecotax_tax_excl > 0}
                        <br/>
                        <small>{{displayPrice currency=$order->id_currency price=$order_detail.ecotax_tax_excl}|string_format:{l s='ecotax: %s'  pdf='true'}|escape:'html':'UTF-8'}</small>
                    {/if}
                </td>
                <td width="{$layout.quantity.width|escape:'html':'UTF-8'}%" class="product center">
                    {$order_detail.product_quantity|escape:'html':'UTF-8'}
                </td>
                <td width="{$layout.total_tax_excl.width|escape:'html':'UTF-8'}%" class="product right">
                    {displayPrice currency=$order->id_currency price=$order_detail.total_price_tax_excl_including_ecotax|escape:'html':'UTF-8'}
                </td>
            </tr>
            {foreach $order_detail.customizedDatas as $customizationPerAddress}
                {foreach $customizationPerAddress as $customizationId => $customization}
                    <tr class="customization_data {$bgcolor_class|escape:'html':'UTF-8'}">
                        <td class="center"> &nbsp;</td>
                        <td>
                            {if isset($customization.datas[$smarty.const._CUSTOMIZE_TEXTFIELD_]) && count($customization.datas[$smarty.const._CUSTOMIZE_TEXTFIELD_]) > 0}
                                <table style="width: 100%;">
                                    {foreach $customization.datas[$smarty.const._CUSTOMIZE_TEXTFIELD_] as $customization_infos}
                                        <tr>
                                            <td style="width: 30%;">
                                                {$customization_infos.name|string_format:{l s='%s:'  pdf='true'}|escape:'html':'UTF-8'}
                                            </td>
                                            <td>{$customization_infos.value|escape:'html':'UTF-8'}</td>
                                        </tr>
                                    {/foreach}
                                </table>
                            {/if}
                            {if isset($customization.datas[$smarty.const._CUSTOMIZE_FILE_]) && count($customization.datas[$smarty.const._CUSTOMIZE_FILE_]) > 0}
                                <table style="width: 100%;">
                                    <tr>
                                        <td style="width: 70%;">{l s='image(s):'  pdf='true'}</td>
                                        <td>{count($customization.datas[$smarty.const._CUSTOMIZE_FILE_])|escape:'html':'UTF-8'}</td>
                                    </tr>
                                </table>
                            {/if}
                        </td>
                        <td class="center">
                            ({if $customization.quantity == 0}1{else}{$customization.quantity|escape:'html':'UTF-8'}{/if})
                        </td>

                        {assign var=end value=($layout._colCount-3)}
                        {for $var=0 to $end}
                            <td class="center">
                                --
                            </td>
                        {/for}
                    </tr>
                {/foreach}
            {/foreach}
        {/foreach}
        <!-- END PRODUCTS -->
        <!-- CART RULES -->
        {assign var="shipping_discount_tax_incl" value="0"}
        {foreach from=$cart_rules item=cart_rule name="cart_rules_loop"}
            {if $smarty.foreach.cart_rules_loop.first}
                <tr class="discount">
                    <th class="header" colspan="{$layout._colCount|escape:'html':'UTF-8'}">
                        {l s='Discounts'  pdf='true'}
                    </th>
                </tr>
            {/if}
            <tr class="discount">
                <td class="white right" colspan="{$layout._colCount - 1|escape:'html':'UTF-8'}">
                    {$cart_rule.name|escape:'html':'UTF-8'}
                </td>
                <td class="right white">
                    - {displayPrice currency=$order->id_currency price=$cart_rule.value_tax_excl|escape:'html':'UTF-8'}
                </td>
            </tr>
        {/foreach}
    </tbody>
</table>
<!-- /PRODUCTS -->
<div>&nbsp;</div>
<!-- TAX -->
<!--  TAX DETAILS -->
<table id="tax-tot" width="100%">
    <tbody>
        <tr>
            <td class="nopadding" colspan="7">
                {if $tax_exempt}
                    {l s='Exempt of VAT according to section 259B of the General Tax Code.'  pdf='true'}
                {elseif (isset($tax_breakdowns) && $tax_breakdowns)}
                    <table id="tax-tab" width="100%" style="float:left">
                        <thead>
                            <tr>
                                <th class="header small">{l s='Tax Detail'  pdf='true'}</th>
                                <th class="header small">{l s='Tax Rate'  pdf='true'}</th>
                                    {if $display_tax_bases_in_breakdowns}
                                    <th class="header small">{l s='Base price'  pdf='true'}</th>
                                    {/if}
                                <th class="header-right small">{l s='Total Tax'  pdf='true'}</th>
                            </tr>
                        </thead>
                        <tbody>
                            {assign var=has_line value=false}
                            {foreach $tax_breakdowns as $label => $bd}
                                {assign var=label_printed value=false}

                                {foreach $bd as $line}
                                    {if $line.rate == 0}
                                        {continue}
                                    {/if}
                                    {assign var=has_line value=true}
                                    <tr>
                                        <td class="white">
                                            {if !$label_printed}
                                                {if $label == 'product_tax'}
                                                    {l s='Products'  pdf='true'}
                                                {elseif $label == 'shipping_tax'}
                                                    {l s='Shipping'  pdf='true'}
                                                {elseif $label == 'ecotax_tax'}
                                                    {l s='Ecotax'  pdf='true'}
                                                {elseif $label == 'wrapping_tax'}
                                                    {l s='Wrapping'  pdf='true'}
                                                {/if}
                                                {assign var=label_printed value=true}
                                            {/if}
                                        </td>
                                        <td class="center white">
                                            {$line.rate|escape:'html':'UTF-8'} %
                                        </td>
                                        {if $display_tax_bases_in_breakdowns}
                                            <td class="right white">
                                                {if isset($is_order_slip) && $is_order_slip}- {/if}
                                                {if $label == 'shipping_tax' && is_array($fee) && $fee.fee > 0}
                                                    {displayPrice currency=$order->id_currency price=($line.total_tax_excl - $fee.fee)|escape:'html':'UTF-8'}
                                                {else}
                                                    {displayPrice currency=$order->id_currency price=$line.total_tax_excl|escape:'html':'UTF-8'}
                                                {/if}
                                            </td>
                                        {/if}
                                        <td class="right white">
                                            {if isset($is_order_slip) && $is_order_slip}- {/if}
                                            {displayPrice currency=$order->id_currency price=$line.total_amount|escape:'html':'UTF-8'}
                                        </td>
                                    </tr>
                                {/foreach}
                            {/foreach}
                            {if is_array($fee) && $fee.fee > 0}
                                <tr>
                                    <td class="white">
                                        {l s='Paypal'  pdf='true'}
                                    </td>
                                    <td class="center white">
                                        {$fee.tax_rate|escape:'html':'UTF-8'} %
                                    </td>
                                    {if $display_tax_bases_in_breakdowns}
                                    <td class="right white">
                                        {if $fee.tax_rate > 0}
                                            {displayPrice currency=$order->id_currency price=($fee.fee / ( 1 + (0.01 * $fee.tax_rate)))|escape:'html':'UTF-8'} 
                                        {else}
                                            {displayPrice currency=$order->id_currency price=($fee.fee)|escape:'html':'UTF-8'} 
                                        {/if}   
                                    </td>
                                    {/if}
                                    <td class="right white">
                                        {if $fee.tax_rate > 0}
                                            {displayPrice currency=$order->id_currency price=($fee.fee - ($fee.fee / (1 + (0.01 * $fee.tax_rate))))|escape:'html':'UTF-8'} 
                                        {else}
                                            -
                                        {/if}    
                                    </td>
                                </tr>
                            {/if}
                            {if !$has_line}
                                <tr>
                                    <td class="white center" colspan="{if $display_tax_bases_in_breakdowns}4{else}3{/if}">
                                        {l s='No taxes'  pdf='true'}
                                    </td>
                                </tr>
                            {/if}
                        </tbody>
                    </table>
                {/if}
            </td>
            <!--  / TAX DETAILS -->
            <!-- /TAX -->
            <!-- TOTAL -->
            <td class="nopadding" colspan="2"> </td>
            <td class="nopadding" colspan="6">
                <table id="total-tab" width="100%">
                    <tr>
                        <td class="grey" width="70%" align="right">
                            {l s='Total Products'  pdf='true'}
                        </td>
                        <td class="white" width="30%" align="right">
                            {displayPrice currency=$order->id_currency price=$footer.products_before_discounts_tax_excl|escape:'html':'UTF-8'}
                        </td>
                    </tr>
                    {if $footer.product_discounts_tax_excl > 0}
                        <tr>
                            <td class="grey" width="70%" align="right">
                                {l s='Total Discounts'  pdf='true'}
                            </td>
                            <td class="white" width="30%" align="right">
                                - {displayPrice currency=$order->id_currency price=$footer.product_discounts_tax_excl|escape:'html':'UTF-8'}
                            </td>
                        </tr>
                    {/if}
                    {if !$order->isVirtual()}
                        <tr>
                            <td class="grey" width="70%" align="right">
                                {l s='Shipping Cost'  pdf='true'}
                            </td>
                            <td class="white" width="30%" align="right">
                                {if $footer.shipping_tax_excl > 0}
                                    {if is_array($fee) && $fee.fee > 0}
                                        {displayPrice currency=$order->id_currency price=($footer.shipping_tax_excl - $fee.fee)}
                                    {else}
                                        {displayPrice currency=$order->id_currency price=$footer.shipping_tax_excl}
                                    {/if}
                                {else}
                                    {l s='Free Shipping'  pdf='true'}
                                {/if}
                            </td>
                        </tr>
                    {/if}
                    {if is_array($fee) && $fee.fee > 0}
                        <tr>
                            <td class="grey" width="70%" align="right">
                                {l s='Paypal'  pdf='true'}
                            </td>
                            <td class="white" width="30%" align="right">
                                {if $fee.tax_rate > 0}
                                    {displayPrice currency=$order->id_currency price=($fee.fee / ( 1 + (0.01 * $fee.tax_rate)))}
                                {else}
                                    {displayPrice currency=$order->id_currency price=$fee.fee}
                                {/if}
                            </td>
                        </tr>
                    {/if}
                    {if $footer.wrapping_tax_excl > 0}
                        <tr>
                            <td class="grey" align="right">
                                {l s='Wrapping Cost'  pdf='true'}
                            </td>
                            <td class="white" align="right">{displayPrice currency=$order->id_currency price=$footer.wrapping_tax_excl|escape:'html':'UTF-8'}</td>
                        </tr>
                    {/if}
                    <tr class="bold">
                        <td class="grey" align="right">
                            {l s='Total (Tax excl.)'  pdf='true'}
                        </td>
                        <td class="white" align="right">
                            {if is_array($fee) && $fee.fee > 0}
                                {if $fee.tax_rate > 0}
                                    {displayPrice currency=$order->id_currency price=(($footer.total_paid_tax_excl) + ($fee.fee / ( 1 + (0.01 * $fee.tax_rate))))}
                                {else}
                                    {displayPrice currency=$order->id_currency price=($footer.total_paid_tax_excl) + $fee.fee}
                                {/if}
                            {else}
                                {displayPrice currency=$order->id_currency price=$footer.total_paid_tax_excl}
                            {/if}
                        </td>
                    </tr>
                    {if $footer.total_taxes > 0}
                        <tr class="bold">
                            <td class="grey" align="right">
                                {l s='Total Tax'  pdf='true'}
                            </td>
                            <td class="white" align="right">
                                {if is_array($fee) && $fee.fee > 0}
                                    {if $fee.tax_rate > 0}
                                        {displayPrice currency=$order->id_currency price=(($footer.total_taxes - $fee.fee) + ($fee.fee - ($fee.fee / (1 + (0.01 * $fee.tax_rate)))))}
                                    {else}
                                        {displayPrice currency=$order->id_currency price=($footer.total_taxes - $fee.fee)}
                                    {/if}
                                {else}
                                    {displayPrice currency=$order->id_currency price=$footer.total_taxes}
                                {/if}
                            </td>
                        </tr>
                    {/if}
                    <tr class="bold big">
                        <td class="grey" align="right">
                            {l s='Total'  pdf='true'}
                        </td>
                        <td class="white" align="right">
                            {displayPrice currency=$order->id_currency price=$footer.total_paid_tax_incl|escape:'html':'UTF-8'}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </tbody>
</table>
<!-- /TOTAL -->
<div>&nbsp;</div>
<!-- PAYMENT-->
<table id="payment-tab" width="100%">
    <tr>
        <td class="payment center small grey bold" width="44%">{l s='Payment Method'  pdf='true'}</td>
        <td class="payment left white" width="56%">
            <table width="100%" border="0">
                {foreach from=$order_invoice->getOrderPaymentCollection() item=payment}
                    <tr>
                        <td class="right small">{$payment->payment_method|escape:'html':'UTF-8'}</td>
                        <td class="right small">{displayPrice currency=$payment->id_currency price=$payment->amount|escape:'html':'UTF-8'}</td>
                    </tr>
                {/foreach}
            </table>
        </td>
    </tr>
</table>
<!-- /PAYMENT-->
<div>&nbsp;</div>
<table>
    <tr>
        <td>
            <p>{$legal_free_text|escape:'html':'UTF-8'|nl2br}</p>
        </td>
    </tr>
</table>


