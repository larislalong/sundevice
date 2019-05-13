{*
* cdprestatiket :: Customer ticket Information of the product
*
* @author    contact@cleanpresta.com (www.cleanpresta.com)
* @copyright 2015 cleandev.net
* @license   You only can use module, nothing more!
*}

{capture name=path}{l s='Ticket-Contact' mod='cdprestatiket'}{/capture}

{if isset($confirmation)}
	<p class="alert alert-success">{l s='Your message has been successfully sent to our team.' mod='cdprestatiket'}</p>
	<ul class="footer_links clearfix">
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
<h1 class="page-heading bottom-indent">
    {l s='Ticket service' mod='cdprestatiket'}{l s='Contact us' mod='cdprestatiket'}
</h1>

	<form action="{$cdpt_controller11|escape:'html':'UTF-8'}" method="post" class="contact-form-box" enctype="multipart/form-data">
		<fieldset>
        <h3 class="page-subheading">{l s='send a message' mod='cdprestatiket'}</h3>
        <div class="clearfix">
            <div class="col-xs-12 col-md-3">
               
                <p class="form-group">
                    <label for="lastname">{l s='Lastname' mod='cdprestatiket'}</label>
                    <input class="form-control grey validate" type="text" id="lastname" name="lastname" value="{$cdpt_customer_infos.lastname|escape:'html':'UTF-8'}" />
                </p>
				<p class="form-group">
                    <label for="firstname">{l s='Firstname' mod='cdprestatiket'}</label>
                    <input class="form-control grey validate" type="text" id="firstname" name="firstname" value="{$cdpt_customer_infos.firstname|escape:'html':'UTF-8'}" />
                </p>
				<p class="form-group">
                    <label for="email">{l s='Email address' mod='cdprestatiket'}</label>
                    <input class="form-control grey validate" type="text" id="email" name="email" data-validate="isEmail" value="{$cdpt_customer_infos.email|escape:'html':'UTF-8'}" />
                </p>
                
                    <p class="form-group">
                        <label for="fileUpload">{l s='Attach File' mod='cdprestatiket'}</label>
                        <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
                        <input type="file" name="fileUpload" id="fileUpload" class="form-control" />
                    </p>
            </div>
			
			<div class="form-group selector1">
				<input type="hidden" name="id_product" id="id_product" value="{$product_infos.id|escape:'htmlall':'UTF-8'}" />
            </div>
				
            <div class="col-xs-12 col-md-9">
                <div class="form-group">
                    <label for="message">{l s='Message' mod='cdprestatiket'}</label>
                    <textarea class="form-control" id="message" name="message">{if isset($message)}{$message|escape:'html':'UTF-8'|stripslashes}{/if}</textarea>
                </div>
            </div>
        </div>
        <div class="submit">
            <button type="submit" name="cdpt_submitMessageHook" id="cdpt_submitMessageHook" class="button btn btn-default button-medium"><span>{l s='Send' mod='cdprestatiket'}<i class="icon-chevron-right right"></i></span></button>
		</div>
	</fieldset>
</form>
{/if}
{addJsDefL name='contact_fileDefaultHtml'}{l s='No file selected' mod='cdprestatiket' js=1}{/addJsDefL}
{addJsDefL name='contact_fileButtonHtml'}{l s='Choose File' mod='cdprestatiket' js=1}{/addJsDefL}