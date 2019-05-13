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

<table style="width: 100%">
  <tr>
    <td height="45" style="width: 50%;">
      {l s='APPROVED BY CUSTOMER REPRESENTATIVE' mod='dhlexpress'}<br/><br />
      ______________________________________________
    </td>
    <td style="width: 50%;">
      {l s='DATE' mod='dhlexpress'}<br/><br />
      ______________________________________________
    </td>
  </tr>
  <tr style="height: 30px;">
    <td></td>
  </tr>
  <tr>
    <td style="width: 50%;">
      {l s='PICKED UP BY DHL REPRESENTATIVE' mod='dhlexpress'}<br/><br />
      ______________________________________________
    </td>
    <td style="width: 50%;">
      {l s='DATE' mod='dhlexpress'}<br/><br />
      ______________________________________________
    </td>
  </tr>
  <tr>
    <td height="40"></td>
  </tr>
  <tr style="font-size: 9pt;">
    <td style="width: 25%;">{l s='Number of Shipments' mod='dhlexpress'}<br/><br />{$nb_shipment|intval}
    </td>
    <td style="width: 20%;">{l s='Number of Pieces' mod='dhlexpress'}<br/><br />{$nb_pieces|intval}
    </td>
    <td style="width: 40%;">{l s='Enterred weight of all shipments (%s)' sprintf=$weight_unit|escape:'html':'utf-8' mod='dhlexpress'}<br/><br />{$total_weight|string_format:'%.2f'}
    </td>
  </tr>
</table>
