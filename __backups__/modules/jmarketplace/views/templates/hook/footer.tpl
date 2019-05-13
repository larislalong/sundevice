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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<script type="text/javascript">
var confirmDeleteProductsOtherSeller = "{l s='In your cart there are productos of other seller. Are you sure you want to add this product and delete the products you have in your cart?' mod='jmarketplace'}";
var confirm_controller_url = '{$link->getModuleLink('jmarketplace', 'addproductcartconfirm', array(), true)|escape:'html':'UTF-8'}';
var order_url = '{$link->getPageLink('order')|escape:'html':'UTF-8'}';
var PS_REWRITING_SETTINGS = "{$PS_REWRITING_SETTINGS|intval}";
</script>