{**
* 2015-2017 Crystals Services
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
*  @author    Crystals Services Sarl <contact@crystals-services.com>
*  @copyright 2015-2017 Crystals Services Sarl
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of Crystals Services Sarl
*}
{foreach $groups as $group}
	<div  
		class="attributes-group-item{if $group.id_attribute_group == $selectedOrderColumn} active{/if}" 
		data-id-column="{$group.id_attribute_group|intval}">
        <span class="attributes_label">{$group.name|strip_tags:'UTF-8'|truncate:10:'...'}</span>
        <span class="up_down_attr">
            <span class="icone_up_attr attributes-sort-trigger {if ($group.id_attribute_group == $selectedOrderColumn) && ($selectedOrderWay==$orderWayConst.ORDER_WAY_ASC)} active{/if}" data-order-way="{$orderWayConst.ORDER_WAY_ASC}">
				<i class="fa fa-caret-up" aria-hidden="true"></i>
			</span>
            <span class="icone_down_attr attributes-sort-trigger {if ($group.id_attribute_group == $selectedOrderColumn) && ($selectedOrderWay==$orderWayConst.ORDER_WAY_DESC)} active{/if}" data-order-way="{$orderWayConst.ORDER_WAY_DESC}">
				<i class="fa fa-caret-down" aria-hidden="true"></i>
			</span>
        </span>
	</div>
{/foreach}