{*
* cdprestatiket :: Customer ticket Information of the product
*
* @author    contact@cleanpresta.com (www.cleanpresta.com)
* @copyright 2015 cleandev.net
* @license   You only can use module, nothing more!
*}

{if isset($confirmation)}
	<p class="alert alert-success">{l s='Your message has been successfully sent to our team.' mod='cdprestatiket'}</p>
{else}
	{include file="$tpl_dir./errors.tpl"}
{/if}

{if $cdpt_messageID}
	<div class="rows">
	{foreach from=$cdpt_messageID item=MessegeID}
		{if $MessegeID.IDE == 0}
			<br/>
			<div class="rows">
				<div class="col-xs-12 col-sm-12 col-lg-12">
					<fieldset class="cdpt_fieldset">
						<legend>{$cdpt_Names|escape:'htmlall':'UTF-8'}</legend>
						<text>{$MessegeID.message|escape:'htmlall':'UTF-8'}</text>
					</fieldset>
				</div>
			</div>
		{else}
		<br/>
			<div class="rows">
				<div class="col-xs-12 col-sm-12 col-lg-12">
					<fieldset class="cdpt_fieldset1">
						<legend style="text-align: right;">{$MessegeID.DEPT|escape:'htmlall':'UTF-8'}</legend>
						<text>{$MessegeID.message|escape:'htmlall':'UTF-8'}</text>
					</fieldset>
				</div>
			</div>
		{/if}
	{/foreach}
	</div>
	<br />
	<br />
<div class="rows">
	<form action="{$cdpt_controller1|escape:'html':'UTF-8'}" method="post">
		<fieldset class="cdpt_fieldset">
			<legend style="text-align: center;">{l s='answer the message' mod='cdprestatiket'}</legend>
			<div class="">
                    <label for="message">{l s='Message' mod='cdprestatiket'}</label>
                    <textarea style="width: 700px; height: 100px;" id="message" name="message"></textarea>
            </div>
			
			<input type="hidden" name="id_contact" id="id_contact" value="{$cdpt_messageID[0].IDC|escape:'htmlall':'UTF-8'}" />
			<input type="hidden" name="id_customer_thread" id="id_customer_thread" value="{$cdpt_messageID[0].IDM|escape:'htmlall':'UTF-8'}" />
			<input type="hidden" id="email" name="from" data-validate="isEmail" value="{$cdpt_email|escape:'htmlall':'UTF-8'}"/>
			<input type="hidden" name="id_order" id="id_order" value="{$cdpt_messageID[0].id_order|escape:'htmlall':'UTF-8'}"/>
			<input type="hidden" name="id_product" id="id_product" value="{$cdpt_messageID[0].id_product|escape:'htmlall':'UTF-8'}"/>
			<input type="hidden" name="id_messageth" id="id_messageth" value="{$cdpt_IDmessageth|escape:'htmlall':'UTF-8'}"/>
			<br />
			<div class="submit">
				<button type="submit" name="cdpt_submitMessage" id="cdpt_submitMessage" class="button btn btn-default button-medium"><span>{l s='Send' mod='cdprestatiket'}<i class="icon-chevron-right right"></i></span></button>
			</div>
		</fieldset>
	</form>
</div>
{/if}