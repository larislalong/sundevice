{*
* cdprestatiket :: Customer ticket Information of the product
*
* @author    contact@cleanpresta.com (www.cleanpresta.com)
* @copyright 2015 cleandev.net
* @license   You only can use module, nothing more!
*}

{if $cdpt_controller}

		<ul class="myaccount-link-list">
			<li><a href="{$cdpt_controller|escape:'htmlall':'UTF-8'}" title="{l s='My Ticket' mod='cdprestatiket'}"><i class="icon-building"></i><span>{l s='My Ticket' mod='cdprestatiket'}</span></a></li>
		</ul>
{/if}
