{*
* cdprestatiket :: Customer ticket Information of the product
*
* @author    contact@cleanpresta.com (www.cleanpresta.com)
* @copyright 2015 cleandev.net
* @license   You only can use module, nothing more!
*}

{capture name=path}{l s='Ticket-Messages' mod='cdprestatiket'}{/capture}

{if isset($confirmation)}
	<p class="alert alert-success">{l s='Your message has been successfully sent to our team.' mod='cdprestatiket'}</p>
{else}
	{include file="$tpl_dir./errors.tpl"}
{/if}

<div>
	<p><a class="btn btn-primary btn-lg cdpt-top-menu" href="{$cdpt_controller1|escape:'htmlall':'UTF-8'}" role="button">{l s='Send a new Ticket' mod='cdprestatiket'}</a></p>
<div><br /><br />
<br />
{if $cdpt_fields_message}
	<div class="panel panel-default">
		<!-- Default panel contents -->
		<div class="panel-heading cdpt_title">{l s='All the Messages' mod='cdprestatiket'}</div>

		<!-- Table -->
		<table class="table">
			<tr class="rows cdpt-top-table">
				<td class="col-xs-1 col-sm-1 col-lg-1">
					{l s='ID' mod='cdprestatiket'}
				</td>
				<td class="col-xs-1 col-sm-1 col-lg-1">
					{l s='Department' mod='cdprestatiket'}
				</td>
				<td class="col-xs-8 col-sm-7 col-lg-5">
					{l s='Last Message' mod='cdprestatiket'}
				</td>
				<td class="col-xs-1 col-sm-1 col-lg-1">
					{l s='Order ID' mod='cdprestatiket'}
				</td>
				<td class="col-xs-1 col-sm-1 col-lg-1">
					{l s='Product ID' mod='cdprestatiket'}
				</td>
				<td class="col-xs-1 col-sm-1 col-lg-1">
					{l s='Status' mod='cdprestatiket'}
				</td>
				<td class="col-xs-1 col-sm-1 col-lg-1">
					{l s='See All' mod='cdprestatiket'}
				</td>
			</tr>
			{assign var=cdpt_compt value=0}
			{foreach from=$cdpt_fields_message item=fieldMessege}
				<tr class="rows">
					<td class="col-xs-1 col-sm-1 col-lg-1">
						{$fieldMessege.ID|escape:'htmlall':'UTF-8'}
					</td>
					<td class="col-xs-1 col-sm-1 col-lg-1">
						{$fieldMessege.DEPT|escape:'htmlall':'UTF-8'}
					</td>
					<td class="col-xs-8 col-sm-7 col-lg-5">
						{$fieldMessege.message|escape:'htmlall':'UTF-8'}
					</td>
					<td class="col-xs-1 col-sm-1 col-lg-1">
						{$fieldMessege.id_order|escape:'htmlall':'UTF-8'}
					</td>
					<td class="col-xs-1 col-sm-1 col-lg-1">
						{$fieldMessege.id_product|escape:'htmlall':'UTF-8'}
					</td>
					<td class="col-xs-1 col-sm-1 col-lg-1">
						<i class="icon-circle" style="color: {if $fieldMessege.status == closed} red {else} green {/if}"></i>
					</td>
					<td class="col-xs-1 col-sm-1 col-lg-1">
						{assign var=cdpt_linkmessage1 value="cdptmessage_"|cat:$cdpt_compt}
						<form action="{$cdpt_controller10|escape:'html':'UTF-8'}" method="post">
							<input type="hidden" name="{$cdpt_linkmessage1}" id="{$cdpt_linkmessage1}" value="{$fieldMessege.IDM|escape:'htmlall':'UTF-8'}"/>
							<button type="submit" name="cdpt_submitAllMessage" id="cdpt_submitAllMessage" class="btn btn-primary btn-lg"><span>{l s='See All' mod='cdprestatiket'}</span></button>
						</form>
						{$cdpt_compt = $cdpt_compt + 1}
					</td>
				</tr>
			{/foreach}
		</table>
	</div>
	{if $cdpt_nbr_message > $cdpt_nbr_page}
		<div class="bx-controls-direction">
			<center>
				<h4>
					<table class="pagination">
						<th>
						{$p = 1}
						{for $var=1 to $cdpt_nbr_message step $cdpt_nbr_page}
							{assign var=cdpt_class value="btn btn-default"}
							{if $p == $cdpt_num_page}
								{assign var=cdpt_class value="btn btn-primary"}
							{/if}
							<td>
								{assign var=cdpt_page value="cdptpage_"|cat:$p}
								<form action="{$cdpt_controller10|escape:'html':'UTF-8'}" method="post">
									<input type="hidden" name="{$cdpt_page}" id="{$cdpt_page}" value="{$p|escape:'htmlall':'UTF-8'}"/>
									<button type="submit" name="cdpt_submitPage" id="lien" class="{$cdpt_class}">{$p|escape:'html':'UTF-8'}</button>
								</form>
							</td>
						{$p = $p + 1}
						{/for}
						</th>
					</table>
				</h4>
			</center>
		</div>
	{/if}
{/if}
