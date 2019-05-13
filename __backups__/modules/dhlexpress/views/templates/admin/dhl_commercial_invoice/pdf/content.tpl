{*
* 2007-2018 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author     PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2018 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<table style="border: 0.5px solid black; font-size: 7pt">
  <tr>
    <td style="height: 100px; border: 0.5px solid black" width="50%"><br/><br/>{l s='Shipper:' mod='dhlexpress'}
      <br/>{$invoice_vars.sender_details->company_name|escape:'html':'utf-8'|upper}
      <br/>{$invoice_vars.sender_details->contact_name|escape:'html':'utf-8'|upper}
      <br/>{$invoice_vars.sender_details->address1|escape:'html':'utf-8'|upper}
      <br/>{$invoice_vars.sender_details->address2|escape:'html':'utf-8'|upper}
      <br/>{$invoice_vars.sender_details->address3|escape:'html':'utf-8'|upper}
      <br/>{$invoice_vars.sender_details->city|escape:'html':'utf-8'|upper}
      {$invoice_vars.sender_details->zipcode|escape:'html':'utf-8'|upper} {$invoice_vars.sender_details->country|escape:'html':'utf-8'|upper}
      <br/>{l s='Phone: ' mod='dhlexpress'}{$invoice_vars.sender_details->contact_phone|escape:'html':'utf-8'}
      <br/>{l s='VAT / GST No.: ' mod='dhlexpress'}{$invoice_vars.sender_details->vat_number|escape:'html':'utf-8'|upper}
    </td>
    <td align="center" style="font-size: 18pt; background-color: #eeeeee; line-height: 17px; border: 0.5px solid black"
        width="50%">
      {l s='COMMERCIAL INVOICE' mod='dhlexpress'}
    </td>
  </tr>
  <tr>
    <td style="height: 99px; border: 0.5px solid black" rowspan="3" width="50%">
      <br/><br/>{l s='Receiver:' mod='dhlexpress'}
      <br/>{$invoice_vars.consignee_details->company|escape:'html':'utf-8'|upper}
      <br/>{$invoice_vars.consignee_details->firstname|escape:'html':'utf-8'|upper} {$invoice_vars.consignee_details->lastname|escape:'html':'utf-8'|upper}
      <br/>{$invoice_vars.consignee_details->address1|escape:'html':'utf-8'|upper}
      <br/>{$invoice_vars.consignee_details->address2|escape:'html':'utf-8'|upper}
      <br/>{$invoice_vars.consignee_details->city|escape:'html':'utf-8'|upper}
      {$invoice_vars.consignee_details->postcode|escape:'html':'utf-8'|upper} {$invoice_vars.consignee_details->country|escape:'html':'utf-8'|upper}
      <br/>
      {if $invoice_vars.consignee_details->phone}
        {l s='Phone: ' mod='dhlexpress'}{$invoice_vars.consignee_details->phone|escape:'html':'utf-8'}
      {/if}
      {if $invoice_vars.consignee_details->phone && $invoice_vars.consignee_details->phone_mobile} - {/if}
      {if $invoice_vars.consignee_details->phone_mobile}
        {l s='Phone (mobile): ' mod='dhlexpress'}{$invoice_vars.consignee_details->phone_mobile|escape:'html':'utf-8'}
      {/if}
      <br/>{l s='VAT / GST No.: ' mod='dhlexpress'}{$invoice_vars.consignee_details->vat_number|escape:'html':'utf-8'|upper}

    </td>
    <td style="height: 33px; border: 0.5px solid black" width="50%">
      <br/><br/>
      {l s='Date: ' mod='dhlexpress'}{$date|escape:'html':'utf-8'}
    </td>
  </tr>
  <tr>
    <td style="height: 33px; border: 0.5px solid black">
      <br/><br/>
      {l s='Invoice Number: ' mod='dhlexpress'}{$invoice_vars.awb_number|escape:'html':'utf-8'}
    </td>
  </tr>
  <tr>
    <td style="height: 33px; border: 0.5px solid black">
      <br/><br/>
      {l s='Shipment Reference: ' mod='dhlexpress'}{$invoice_vars.awb_number|escape:'html':'utf-8'}
    </td>
  </tr>
  <tr>
    <td rowspan="2" style="border: 0.5px solid black">
      <br/><br/>
      {l s='Bill to Third Party: ' mod='dhlexpress'}
    </td>
    <td style="height: 45px; border: 0.5px solid black">
      <br/><br/>
      {l s='Comments: ' mod='dhlexpress'}
    </td>
  </tr>
  <tr>
    <td style="height: 35px; border: 0.5px solid black">
      <br/><br/>
      {l s='Airway Bill Number: ' mod='dhlexpress'}<span
        style="font-size: 13pt">{$invoice_vars.awb_number|escape:'html':'utf-8'}</span>
    </td>
  </tr>
</table>
<table style="border: 0.5px solid black; font-size: 7pt; height: 300px;">
  <tr style="text-align: center;">
    <td height="30px" width="5%" style="border: 0.5px solid black;"></td>
    <td width="27%"
        style="border: 0.5px solid black; line-height: 12px">{l s='Description of goods' mod='dhlexpress'}</td>
    <td width="5%" style="border: 0.5px solid black; line-height: 12px">{l s='Qty' mod='dhlexpress'}</td>
    <td width="5%" style="border: 0.5px solid black; line-height: 12px">{l s='UOM' mod='dhlexpress'}</td>
    <td width="10%" style="border: 0.5px solid black; line-height: 6px">{l s='Commodity Code' mod='dhlexpress'}</td>
    <td width="9%" style="border: 0.5px solid black; line-height: 12px">{l s='Unit Value' mod='dhlexpress'}</td>
    <td width="8%" style="border: 0.5px solid black; line-height: 6px">{l s='Subtotal Value' mod='dhlexpress'}</td>
    <td width="8%" style="border: 0.5px solid black; line-height: 6px">{l s='Unit Net Weight' mod='dhlexpress'}</td>
    <td width="8%" style="border: 0.5px solid black; line-height: 6px">{l s='Subtotal Weight' mod='dhlexpress'}</td>
    <td width="15%" style="border: 0.5px solid black; line-height: 12px">{l s='Country of Origin' mod='dhlexpress'}</td>
  </tr>

  {if count($order_details) <= 8}
    {assign var=lim value=7}
  {else}
    {assign var=lim value=19}
  {/if}
  {for $i = 0 to $lim}
    {if isset($order_details) && isset($order_details[$i])}
      <tr>
        <td height="28px" width="5%" style="text-align: center; border-left: 0.5px solid black; border-right: 0.5px solid black;">{$i + 1|intval}</td>
        <td width="27%" style="border-left: 0.5px solid black; border-right: 0.5px solid black;">{$order_details[$i].product_name|escape:'html':'utf-8'|upper}</td>
        <td width="5%" style="border-left: 0.5px solid black; border-right: 0.5px solid black; text-align: center;">{$order_details[$i].product_quantity|intval}</td>
        <td width="5%" style="border-left: 0.5px solid black; border-right: 0.5px solid black; text-align: center;">{$invoice_vars.weight_unit|escape:'html':'utf-8'|upper}</td>
        <td width="10%" style="border-left: 0.5px solid black; border-right: 0.5px solid black; text-align: center;">{$order_details[$i].commodity_code|escape:'html':'utf-8'}</td>
        <td width="9%" style="border-left: 0.5px solid black; border-right: 0.5px solid black; text-align: center;">{$order_details[$i].unit_price_tax_excl|string_format:'%.2f'}</td>
        <td width="8%" style="border-left: 0.5px solid black; border-right: 0.5px solid black; text-align: center;">{$order_details[$i].total_price_tax_excl|string_format:'%.2f'}</td>
        <td width="8%" style="border-left: 0.5px solid black; border-right: 0.5px solid black; text-align: center;">{$order_details[$i].product_weight|string_format:'%.2f'}</td>
        <td width="8%" style="border-left: 0.5px solid black; border-right: 0.5px solid black; text-align: center;">{$order_details[$i].subtotal_weight|string_format:'%.2f'}</td>
        <td width="15%" style="border-left: 0.5px solid black; border-right: 0.5px solid black;">{$order_details[$i].origin|escape:'html':'utf-8'|upper}</td>
      </tr>
    {else}
      <tr>
        <td height="28px" width="5%" style="border-left: 0.5px solid black; border-right: 0.5px solid black;"></td>
        <td width="27%" style="border-left: 0.5px solid black; border-right: 0.5px solid black;"></td>
        <td width="5%" style="border-left: 0.5px solid black; border-right: 0.5px solid black;"></td>
        <td width="5%" style="border-left: 0.5px solid black; border-right: 0.5px solid black;"></td>
        <td width="10%" style="border-left: 0.5px solid black; border-right: 0.5px solid black;"></td>
        <td width="9%" style="border-left: 0.5px solid black; border-right: 0.5px solid black;"></td>
        <td width="8%" style="border-left: 0.5px solid black; border-right: 0.5px solid black;"></td>
        <td width="8%" style="border-left: 0.5px solid black; border-right: 0.5px solid black;"></td>
        <td width="8%" style="border-left: 0.5px solid black; border-right: 0.5px solid black;"></td>
        <td width="15%" style="border-left: 0.5px solid black; border-right: 0.5px solid black;"></td>
      </tr>
    {/if}
  {/for}

  <tr>
    <td colspan="3" style="background-color: black;"></td>
    <td colspan="4" style="height: 25px; border: 0.5px solid black;">
      <br/><br/>{l s='Total Declared Value: ' mod='dhlexpress'}{$invoice_vars.declared_value|string_format:'%.2f'} {$invoice_vars.currency_code|escape:'html':'utf-8'|upper}</td>
    <td colspan="3" style="height: 25px; border: 0.5px solid black;">
      <br/><br/>{l s='Total Gross Weight: ' mod='dhlexpress'}{$invoice_vars.total_weight|string_format:'%.2f'} {$invoice_vars.weight_unit|escape:'html':'utf-8'|upper}</td>
  </tr>
  <tr>
    <td colspan="3" style="background-color: black;"></td>
    <td colspan="4" style="height: 25px; border: 0.5px solid black;">
      <br/><br/>{l s='Total Pieces: ' mod='dhlexpress'}{$invoice_vars.total_quantity|intval}
    </td>
    <td colspan="3" style="height: 25px; border: 0.5px solid black;">
      <br/><br/>{l s='Total Packages: ' mod='dhlexpress'}{$invoice_vars.total_package|intval}
    </td>
  </tr>
</table>
<br /><br />
<table style="font-size: 8pt">
  <tr>
    <td height="15px">{l s='Payer of GST / VAT: ' mod='dhlexpress'}</td>
    <td>{l s='Receiver' mod='dhlexpress'}</td>
    <td>{l s='Currency code: ' mod='dhlexpress'}</td>
    <td>{$invoice_vars.currency_code|escape:'html':'utf-8'|upper}</td>
  </tr>
  <tr>
    <td height="15px">{l s='Type of export: ' mod='dhlexpress'}</td>
    <td>{l s='Permanent' mod='dhlexpress'}</td>
    <td>{l s='Incoterm: ' mod='dhlexpress'}</td>
    <td>{$invoice_vars.incoterms|escape:'html':'utf-8'}</td>
  </tr>
  <tr>
    <td height="15px">{l s='Terms of Payment: ' mod='dhlexpress'}</td>
    <td>...</td>
  </tr>
  <tr>
    <td colspan="4" height="40px" style="line-height: 6px">{l s='1/ We hereby certify that the information of this invoice is true and correct and that the contents of this shipment are as stated above.' mod='dhlexpress'}</td>
  </tr>
  <tr>
    <td height="15px">{l s='Signature: ' mod='dhlexpress'}</td>
    <td>_________________</td>
  </tr>
  <tr>
    <td height="15px">{l s='Position in Company: ' mod='dhlexpress'}</td>
    <td></td>
  </tr>
  <tr>
    <td height="15px">{l s='Shipping Consultant: ' mod='dhlexpress'}</td>
    <td>_________________</td>
    <td>{l s='Company Stamp: ' mod='dhlexpress'}</td>
    <td></td>
  </tr>
</table>
