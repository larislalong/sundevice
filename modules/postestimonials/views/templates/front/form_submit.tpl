{capture name=path}
{l s='Add new testimonial' mod='postestimonials'}
{/capture}
<div id="testimonials_block_center" class="block col-xs-12" xmlns="http://www.w3.org/1999/html">
  <h2 class="title_block_s">{l s='Add new testimonial' mod='postestimonials'}</h4>
  {if $errors}
    <div class="alert alert-danger">
      {foreach from=$errors item=error}
        <p>{$error}</p></br>
      {/foreach}
    </div>
  {/if}
  {if $success}<div class="alert alert-success">{$success}</div>{/if}
   <div class="col-xs-12 form-submit">
    <form name="fr_testimonial" id="fr_testimonial" action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
                 <span class="require">*</span>
          <label  class="col-sm-3 control-label">{l s='Your Name' mod='postestimonials'}:</label>
            <div class="col-sm-12">
                 <input id="name_post" name="name_post" type="text" value="{if isset($name_post)}{$name_post|escape:'html':'UTF-8'}{/if}{if $logged==1}{$cookie->customer_firstname|escape:'htmlall':'UTF-8'} {$cookie->customer_lastname|escape:'htmlall':'UTF-8'}{/if}" size="40" class="form-control grey validate"/>
                </div>
            </div>
         <div class="form-group">
                    <span class="require">*</span>
              <label  class="col-sm-3 control-label">{l s='Your Email' mod='postestimonials'}:</label>
              <div class="col-sm-12">
                 <p class="form-group">
                    <input id="email" name="email" type="text" size="40" class="form-control grey validate" data-validate="isEmail" value="{if isset($email)}{$email|escape:'html':'UTF-8'}{/if}{if $logged==1 }{$cookie->email|escape:'htmlall':'UTF-8'}{/if}" name="from" />
                 </p>
            </div>
          </div>

      <div class="form-group">
         <label class="col-sm-3 control-label">{l s='Company' mod='postestimonials'}:</label>
            <div class="col-sm-12">
            <input id="company" name="company" type="text" value="{if isset($company)}{$company|escape:'html':'UTF-8'}{/if}" size="40" class="form-control"/>
            </div>
        </div>
        <div class="form-group">
                <span class="require">*</span>
            <label class="col-sm-3 control-label">{l s='Address' mod='postestimonials'}:</label>
            <div class="col-sm-12">
                <input id="Address" name="address" type="text" value="{if isset($address)}{$address|escape:'html':'UTF-8'}{/if}" size="40" class="form-control"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" >{l s='Media Link' mod='postestimonials'}:</label>
            <div class="col-sm-12">
                <input id="media_link" name="media_link" type="text" value="{if isset($media_link)}{$media_link|escape:'html':'UTF-8'}{/if}" size="40" class="form-control"/>
            </div>
        </div>
        <div id="show_link"></div>
        <div class="form-group">
                <span class="require">*</span>
            <label class="col-sm-3 control-label">{l s='Multimedia' mod='postestimonials'}:</label>
            <div class="col-sm-12">
                <input id="media" name="media" type="file" value="" size="40" class="form-control"/>
            </div></div>
        <div class="form-group">
                <span class="require">*</span>
          <label class="col-sm-3 control-label">{l s='Content' mod='postestimonials'}:</label>
            <div class="col-sm-12">
                <textarea id="content" name="content" cols="50" rows="8" class="form-control">{if isset($content)}{$content|escape:'html':'UTF-8'|stripslashes}{/if}</textarea>
            </div></div>
        {if $captcha == 1}
            <div class="form-group">
            <label class="col-sm-3 control-label"><span class="require">*</span>{l s='Captcha' mod='postestimonials'}:</label>
            <div class="col-sm-5">
                <input name="captcha" type="text" value="" size="20" class="form-control"/>
            </div>
            <div class="col-sm-3">
                <img src="{$captcha_code}" alt="{l s='captcha' mod='postestimonials'}"/>
            </div></div>
        {/if}
        <div class="form-group">
            <div class="col-sm-5">
                <button id="submitNewTestimonial"  class="button btn btn-default button-medium" name="submitNewTestimonial" type="submit" value="{l s='Send Your Testimonial' mod='postestimonials'}"><span> {l s='Send' mod='postestimonials'} </span></button>
            </div>
            <label class="col-sm-3 control-label require"> {l s='* required field' mod='postestimonials'} </label>
        </div>
    </form>
  </div>
  <div class="clearfix"></div>
</div>
