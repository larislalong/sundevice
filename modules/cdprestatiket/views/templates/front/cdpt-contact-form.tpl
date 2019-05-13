{*
* cdprestatiket :: Customer ticket Information of the product
*
* @author    contact@cleanpresta.com (www.cleanpresta.com)
* @copyright 2015 cleandev.net
* @license   You only can use module, nothing more!
*}

{capture name=path}{l s='Ticket-Contact' mod='cdprestatiket'}{/capture}
<h1 class="page-heading bottom-indent">
    {l s='Ticket service' mod='cdprestatiket'} - {if isset($customerThread) && $customerThread}{l s='Your reply' mod='cdprestatiket'}{else}{l s='Contact us' mod='cdprestatiket'}{/if}
</h1>
{if isset($confirmation)}
	<p class="alert alert-success">{l s='Your message has been successfully sent to our team.' mod='cdprestatiket'}</p>
	<ul class="footer_links clearfix">
		<li>
            <a class="btn btn-default button button-small" href="{$cdpt_controller1|escape:'htmlall':'UTF-8'}">
                <span>
                    <i class="icon-chevron-left"></i>{l s='Ticket Messages' mod='cdprestatiket'}
                </span>
            </a>
        </li>
		<li>
            <a class="btn btn-default button button-small" href="{$base_dir|escape:'htmlall':'UTF-8'}">
                <span>
                    <i class="icon-chevron-left"></i>{l s='Home' mod='cdprestatiket'}
                </span>
            </a>
        </li>
	</ul>
{elseif isset($alreadySent)}
	<p class="alert alert-warning">{l s='Your message has already been sent.' mod='cdprestatiket'}</p>
	<ul class="footer_links clearfix">
		<li>
            <a class="btn btn-default button button-small" href="{$cdpt_controller1|escape:'htmlall':'UTF-8'}">
                <span>
                    <i class="icon-chevron-left"></i>{l s='Ticket Messages' mod='cdprestatiket'}
                </span>
            </a>
        </li>
		<li>
            <a class="btn btn-default button button-small" href="{$base_dir|escape:'htmlall':'UTF-8'}">
                <span>
                    <i class="icon-chevron-left"></i>{l s='Home' mod='cdprestatiket'}
                </span>
            </a>
        </li>
	</ul>
{else}
	{include file="$tpl_dir./errors.tpl"}
	<form action="{$cdpt_controller1|escape:'html':'UTF-8'}" method="post" class="contact-form-box" enctype="multipart/form-data">
		<fieldset>
        <h3 class="page-subheading">{l s='send a message' mod='cdprestatiket'}</h3>
        <div class="clearfix">
            <div class="col-xs-12 col-md-3">
                <div class="form-group selector1">
                    <label for="id_contact">{l s='Ticket Service' mod='cdprestatiket'}</label>
                {if isset($cdpt_SAV)}
                    <select id="id_contact" class="form-control" name="id_contact">
                        {foreach from=$contacts item=contact}
							{if $contact.id_contact == $cdpt_SAV}
								<option value="{$contact.id_contact|intval}" {if isset($smarty.request.id_contact) && $smarty.request.id_contact == $contact.id_contact}selected="selected"{/if}>{$contact.name|escape:'html':'UTF-8'}</option>
							{/if}
						{/foreach}
                    </select>
                </div>
                    <p id="desc_contact0" class="desc_contact">&nbsp;</p>
                    {foreach from=$contacts item=contact}
                        <p id="desc_contact{$contact.id_contact|intval}" class="desc_contact contact-title" style="display:none;">
                            <i class="icon-comment-alt"></i>{$contact.description|escape:'html':'UTF-8'}
                        </p>
                    {/foreach}
                {/if}
                <p class="form-group">
                    <label for="email">{l s='Email address' mod='cdprestatiket'}</label>
                    {if isset($customerThread.email)}
                        <input class="form-control grey" type="text" id="email" name="from" value="{$customerThread.email|escape:'html':'UTF-8'}" readonly="readonly" />
                    {else}
                        <input class="form-control grey validate" type="text" id="email" name="from" data-validate="isEmail" value="{$email|escape:'html':'UTF-8'}" readonly="readonly"/>
                    {/if}
                </p>
                {if !$PS_CATALOG_MODE}
                    {if (!isset($customerThread.id_order) || $customerThread.id_order > 0)}
                        <div class="form-group selector1">
                            <label>{l s='Order reference' mod='cdprestatiket'}</label>
                            {if !isset($customerThread.id_order) && isset($is_logged) && $is_logged}
                                <select name="id_order" class="form-control">
                                    <option value="0">{l s='-- Choose --' mod='cdprestatiket'}</option>
                                    {foreach from=$orderList item=order}
                                        <option value="{$order.value|intval}"{if $order.selected|intval} selected="selected"{/if}>{$order.label|escape:'html':'UTF-8'}</option>
                                    {/foreach}
                                </select>
                            {elseif !isset($customerThread.id_order) && empty($is_logged)}
                                <input class="form-control grey" type="text" name="id_order" id="id_order" value="{if isset($customerThread.id_order) && $customerThread.id_order|intval > 0}{$customerThread.id_order|intval}{else}{if isset($smarty.post.id_order) && !empty($smarty.post.id_order)}{$smarty.post.id_order|escape:'html':'UTF-8'}{/if}{/if}" />
                            {elseif $customerThread.id_order|intval > 0}
                                <input class="form-control grey" type="text" name="id_order" id="id_order" value="{$customerThread.id_order|intval}" readonly="readonly" />
                            {/if}
                        </div>
                    {/if}
                    {if isset($is_logged) && $is_logged}
                        <div class="form-group selector1">
                            <label class="unvisible">{l s='Product' mod='cdprestatiket'}</label>
                            {if !isset($customerThread.id_product)}
                                {foreach from=$orderedProductList key=id_order item=products name=products}
                                    <select name="id_product" id="{$id_order|escape:'htmlall':'UTF-8'}_order_products" class="unvisible product_select form-control"{if !$smarty.foreach.products.first} style="display:none;"{/if}{if !$smarty.foreach.products.first} disabled="disabled"{/if}>
                                        <option value="0">{l s='-- Choose --' mod='cdprestatiket'}</option>
                                        {foreach from=$products item=product}
                                            <option value="{$product.value|intval}">{$product.label|escape:'html':'UTF-8'}</option>
                                        {/foreach}
                                    </select>
                                {/foreach}
                            {elseif $customerThread.id_product > 0}
                                <input class="form-control grey" type="text" name="id_product" id="id_product" value="{$customerThread.id_product|intval}" readonly="readonly" />
                            {/if}
                        </div>
                    {/if}
                {/if}
                {if $fileupload == 1}
                    <p class="form-group">
                        <label for="fileUpload">{l s='Attach File' mod='cdprestatiket'}</label>
                        <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
                        <input type="file" name="fileUpload" id="fileUpload" class="form-control" />
                    </p>
                {/if}
            </div>
            <div class="col-xs-12 col-md-9">
                <div class="form-group">
                    <label for="message">{l s='Message' mod='cdprestatiket'}</label>
                    <textarea class="form-control" id="message" name="message">{if isset($message)}{$message|escape:'html':'UTF-8'|stripslashes}{/if}</textarea>
                </div>
            </div>
        </div>
        <div class="submit">
            <button type="submit" name="cdpt_submitMessage" id="cdpt_submitMessage" class="button btn btn-default button-medium"><span>{l s='Send' mod='cdprestatiket'}<i class="icon-chevron-right right"></i></span></button>
		</div>
	</fieldset>
</form>
{/if}
{addJsDefL name='contact_fileDefaultHtml'}{l s='No file selected' mod='cdprestatiket' js=1}{/addJsDefL}
{addJsDefL name='contact_fileButtonHtml'}{l s='Choose File' mod='cdprestatiket' js=1}{/addJsDefL}