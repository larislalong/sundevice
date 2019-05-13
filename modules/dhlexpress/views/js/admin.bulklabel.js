/*
 * 2007-2018 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
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
 *  @author PrestaShop SA <contact@prestashop.com>
 *  @copyright  2007-2018 PrestaShop SA
 *  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

function toggleInsuredValue() {
  if ($('#dhl_insure_shipment_on').prop('checked')) {
    $('.dhl-insured-value-div').css({'display':'block'}).animate({'opacity':'+=1'}, 400);
  } else {
    $('.dhl-insured-value-div').css({'display':'none'}).animate({'opacity':'-=1'});
  }
}

function toggleDeclaredValue() {
  if ($('#dhl_use_declared_value_on').prop('checked')) {
    $('.dhl-declared-value-div').css({'display':'none'}).animate({'opacity':'-=1'});
  } else {
    $('.dhl-declared-value-div').css({'display':'block'}).animate({'opacity':'+=1'}, 400);
  }
}

function toggleWeight()
{
  if ($('#dhl_use_order_weight_on').prop('checked')) {
    $('.dhl-weight-package-type').css({'display':'none'}).animate({'opacity':'-=1'});
  } else {
    $('.dhl-weight-package-type').css({'display':'block'}).animate({'opacity':'+=1'}, 400);
  }
}

$(document).ready(function () {
  toggleWeight();
  toggleDeclaredValue();
  toggleInsuredValue();
});

$('.dhl-use-order-weight-div span').live('click', function () {
  toggleWeight();
});

$('.dhl-use-declared-value-div span').live('click', function () {
  toggleDeclaredValue();
});

$('.dhl-insure-shipment-div span').live('click', function () {
  toggleInsuredValue();
});
