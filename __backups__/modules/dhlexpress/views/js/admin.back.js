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

function dhlDeleteRow(e, t)
{
  e.preventDefault();
  $(e.target).closest('tr').remove();
}

function toggleInsuredDeclaredValues()
{
  if ($('.dhl-label-extracharge-insurance-on').prop('checked')) {
    $('.form-group-insured-value').show(400);
  } else {
    $('.form-group-insured-value').hide(200);
  }
}

function toggleExtracharges()
{
  if ($('#sending-doc_on').prop('checked')) {
    $('.dhl-extracharge-non-doc').hide(200);
    $('.form-group-declared-value').hide(200);
    $('.form-group-insured-value').hide(200);
    $('.form-group-excepted').hide(200);
    $('.dhl-label-extracharge-liability').show(400);
  } else {
    $('.dhl-extracharge-non-doc').show(400);
    $('.form-group-declared-value').show(400);
    $('.dhl-label-extracharge-liability').hide(400);
    toggleInsuredDeclaredValues();
  }
}

$(document).ready(function () {
  toggleExtracharges();
  toggleInsuredDeclaredValues();

  $('#dhl-sender-address').change(function () {
    var id = $(this).find('option:selected').attr('value');
    $('.dhl-sender-addresses').hide(200);
    $('#dhl-sender-address-' + id).show(400);
  });

  $('#dhl-label-package-type').change(function () {
    var id = $(this).find('option:selected').attr('value');
    $('.dhl-packages').hide(200);
    $('#dhl-package-' + id).show(400);
  });

  $('.dhl-label-extracharge-insurance span').live('click', function () {
    toggleInsuredDeclaredValues();
  });
  $('.dhl-label-doc span').live('click', function () {
    toggleExtracharges();
  });
});
