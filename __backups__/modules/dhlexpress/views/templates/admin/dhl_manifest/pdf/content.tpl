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

<table style="border: 0.5px solid black;">
  <tr style="font-size: 9pt">
    <td style="border: 0.5px solid black">{l s='AIRWAY BILL NUMBER' mod='dhlexpress'}</td>
    <td style="border: 0.5px solid black">{l s='CONSIGNEE NAME' mod='dhlexpress'}</td>
    <td style="border: 0.5px solid black">{l s='CONSIGNEE CITY / COUNTRY' mod='dhlexpress'}</td>
    <td style="border: 0.5px solid black">{l s='PIECES / SHIPMENT' mod='dhlexpress'}</td>
    <td style="border: 0.5px solid black">{l s='TOTAL WEIGHT / SHIPMENT' mod='dhlexpress'}</td>
    <td style="border: 0.5px solid black">{l s='CONTENTS OF SHIPMENT' mod='dhlexpress'}</td>
    <td style="border: 0.5px solid black">{l s='SHIPPER REFERENCE' mod='dhlexpress'}</td>
  </tr>
  {foreach $shipping_details as $shipping_detail}
  <tr style="font-size: 7pt; height: 50px">
    <td style="text-align: center; height: 80px; border: 0.5px solid black"><br /><br />{$shipping_detail.awb_number|escape:'html':'utf-8'}</td>
    <td style="border: 0.5px solid black"><br /><br />{$shipping_detail.consignee_contact|escape:'html':'utf-8'}</td>
    <td style="border: 0.5px solid black"><br /><br />{$shipping_detail.consignee_destination|escape:'html':'utf-8'}</td>
    <td style="text-align: center; border: 0.5px solid black"><br /><br />{$shipping_detail.total_pieces|escape:'html':'utf-8'}</td>
    <td style="text-align: center; border: 0.5px solid black"><br /><br />{$shipping_detail.total_weight|escape:'html':'utf-8'}</td>
    <td style="border: 0.5px solid black"><br /><br />{$shipping_detail.piece_contents|escape:'html':'utf-8'}</td>
    <td style="text-align: center; border: 0.5px solid black"><br /><br />{$shipping_detail.shipper_reference|escape:'html':'utf-8'}</td>
  </tr>
  {/foreach}
</table>
