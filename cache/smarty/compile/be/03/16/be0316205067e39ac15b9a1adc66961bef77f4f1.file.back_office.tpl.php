<?php /* Smarty version Smarty-3.1.19, created on 2019-05-03 12:25:54
         compiled from "/home/sundevice/public_html/modules/paypal//views/templates/admin/back_office.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2124762095ccc17324e36c4-29021335%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'be0316205067e39ac15b9a1adc66961bef77f4f1' => 
    array (
      0 => '/home/sundevice/public_html/modules/paypal//views/templates/admin/back_office.tpl',
      1 => 1554370231,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2124762095ccc17324e36c4-29021335',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'hss_errors' => 0,
    'hss' => 0,
    'PayPal_logo' => 0,
    'PayPal_allowed_methods' => 0,
    'default_lang_iso' => 0,
    'PayPal_country' => 0,
    'PayPal_business' => 0,
    'PayPal_WPS' => 0,
    'PayPal_HSS' => 0,
    'PayPal_payment_method' => 0,
    'PayPal_PPP' => 0,
    'PayPal_ECS' => 0,
    'PayPal_PVZ' => 0,
    'PayPal_module_dir' => 0,
    'ps_ssl_active' => 0,
    'PayPal_braintree_enabled' => 0,
    'Braintree_Style' => 0,
    'Braintree_Message' => 0,
    'PayPal_api_username' => 0,
    'PayPal_api_password' => 0,
    'PayPal_api_signature' => 0,
    'PayPal_plus_client' => 0,
    'PayPal_plus_secret' => 0,
    'PayPal_plus_webprofile' => 0,
    'PayPal_api_business_account' => 0,
    'PayPal_integral_evolution_solution' => 0,
    'PayPal_integral_evolution_template' => 0,
    'PayPal_express_checkout_shortcut' => 0,
    'PayPal_in_context_checkout' => 0,
    'PayPal_in_context_checkout_merchant_id' => 0,
    'PayPal_login' => 0,
    'PayPal_login_client_id' => 0,
    'PayPal_login_secret' => 0,
    'PayPal_login_tpl' => 0,
    'PayPal_sandbox_mode' => 0,
    'PayPal_payment_capture' => 0,
    'PayPal_save_success' => 0,
    'PayPal_save_failure' => 0,
    'tls_link_ajax' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5ccc17325a97f3_65965282',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5ccc17325a97f3_65965282')) {function content_5ccc17325a97f3_65965282($_smarty_tpl) {?>

<div id="paypal-wrapper">
	<?php if (!empty($_smarty_tpl->tpl_vars['hss_errors']->value)) {?>
        <div style="background-color: red; color: white; font-weight: bolder; padding: 5px; margin-top: 10px;">
            <?php echo smartyTranslate(array('s'=>'Orders for following carts (id) could not be created because of email error','mod'=>'paypal'),$_smarty_tpl);?>

            <?php  $_smarty_tpl->tpl_vars['hss'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['hss']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['hss_errors']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['hss']->key => $_smarty_tpl->tpl_vars['hss']->value) {
$_smarty_tpl->tpl_vars['hss']->_loop = true;
?>
                <p><span style="background-color: black; padding: 5px;"><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['hss']->value['id_cart'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
 - <?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['hss']->value['email'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</span></p>
            <?php } ?>
            <?php echo smartyTranslate(array('s'=>'You must change the e-mail in the module configuration with the one displayed above','mod'=>'paypal'),$_smarty_tpl);?>

        </div>
	<?php }?>
	
	<div class="box half left">
		<?php if (isset($_smarty_tpl->tpl_vars['PayPal_logo']->value['LocalPayPalLogoLarge'])) {?>
			<img src="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['PayPal_logo']->value['LocalPayPalLogoLarge'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" alt="" style="margin-bottom: -5px" />
		<?php }?>
		<p id="paypal-slogan"><span class="dark"><?php echo smartyTranslate(array('s'=>'Leader in','mod'=>'paypal'),$_smarty_tpl);?>
</span> <span class="light"><?php echo smartyTranslate(array('s'=>'online payments','mod'=>'paypal'),$_smarty_tpl);?>
</span></p>
		<p><?php echo smartyTranslate(array('s'=>'Easy, secure, fast payments for your buyers.','mod'=>'paypal'),$_smarty_tpl);?>
</p>
	</div>

	<div class="box half right">
		<ul class="tick">
            <li><span class="paypal-bold"><?php echo smartyTranslate(array('s'=>'Get more buyers','mod'=>'paypal'),$_smarty_tpl);?>
</span><br /><?php echo smartyTranslate(array('s'=>'100 million-plus PayPal accounts worldwide','mod'=>'paypal'),$_smarty_tpl);?>
</li>
            <li><span class="paypal-bold"><?php echo smartyTranslate(array('s'=>'Access international buyers','mod'=>'paypal'),$_smarty_tpl);?>
</span><br /><?php echo smartyTranslate(array('s'=>'190 countries, 25 currencies','mod'=>'paypal'),$_smarty_tpl);?>
</li>
            <li><span class="paypal-bold"><?php echo smartyTranslate(array('s'=>'Reassure your buyers','mod'=>'paypal'),$_smarty_tpl);?>
</span><br /><?php echo smartyTranslate(array('s'=>'Buyers don\'t need to share their private data','mod'=>'paypal'),$_smarty_tpl);?>
</li>
            <li><span class="paypal-bold"><?php echo smartyTranslate(array('s'=>'Accept all major payment method','mod'=>'paypal'),$_smarty_tpl);?>
</span></li>
        </ul>
	</div>

	<div class="paypal-clear"></div>


	<?php if ($_smarty_tpl->tpl_vars['PayPal_allowed_methods']->value) {?>
		<?php if ($_smarty_tpl->tpl_vars['default_lang_iso']->value=='fr') {?>
			<div class="paypal-clear"></div><hr />
			<div class="box">
			<?php echo smartyTranslate(array('s'=>'Download the ','mod'=>'paypal'),$_smarty_tpl);?>
<a href="http://altfarm.mediaplex.com/ad/ck/3484-197941-8030-54"> <?php echo smartyTranslate(array('s'=>'Paypal Integration Guide','mod'=>'paypal'),$_smarty_tpl);?>
</a> <?php echo smartyTranslate(array('s'=>'on PrestaShop and follow the configuration step by step','mod'=>'paypal'),$_smarty_tpl);?>


			</div>
		<?php } else { ?>
			<div class="paypal-clear"></div><hr />
			<div class="box">
			<?php echo smartyTranslate(array('s'=>'Download the ','mod'=>'paypal'),$_smarty_tpl);?>
<a href="http://altfarm.mediaplex.com/ad/ck/3484-197941-8030-169"> <?php echo smartyTranslate(array('s'=>'Paypal Integration Guide','mod'=>'paypal'),$_smarty_tpl);?>
</a> <?php echo smartyTranslate(array('s'=>'on PrestaShop and follow the configuration step by step','mod'=>'paypal'),$_smarty_tpl);?>


			</div>
		<?php }?>
		<div class="paypal-clear"></div><hr>

		<form method="post" action="<?php echo mb_convert_encoding(htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" id="paypal_configuration">
			
			<div class="box">
				<div style="line-height: 18px;"><?php echo smartyTranslate(array('s'=>'Getting started with PayPal only takes 5 minutes','mod'=>'paypal'),$_smarty_tpl);?>
</div>
				<div style="line-height: 20px; margin-top: 8px">
					<div>
						<label><?php echo smartyTranslate(array('s'=>'Your country','mod'=>'paypal'),$_smarty_tpl);?>
 :
							<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['PayPal_country']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>

						</label>
					</div>

					<label><?php echo smartyTranslate(array('s'=>'You already have a PayPal business account','mod'=>'paypal'),$_smarty_tpl);?>
 ?</label>
					<input type="radio" name="business" id="paypal_business_account_no" value="0" <?php if ($_smarty_tpl->tpl_vars['PayPal_business']->value==0) {?>checked="checked"<?php }?> /> <label for="paypal_business_account_no"><?php echo smartyTranslate(array('s'=>'No','mod'=>'paypal'),$_smarty_tpl);?>
</label>
					<input type="radio" name="business" id="paypal_business_account_yes" value="1" style="margin-left: 14px" <?php if ($_smarty_tpl->tpl_vars['PayPal_business']->value==1) {?>checked="checked"<?php }?> /> <label for="paypal_business_account_yes"><?php echo smartyTranslate(array('s'=>'Yes','mod'=>'paypal'),$_smarty_tpl);?>
</label>
				</div>
			</div>

			<div class="paypal-clear"></div><hr />

			
			<div class="box">

				<div class="box right half" id="paypal-call-button">
					<div id="paypal-call" class="box right"><span style="font-weight: bold"><?php echo smartyTranslate(array('s'=>'Need help ?','mod'=>'paypal'),$_smarty_tpl);?>
</span> <a target="_blank" href="https://www.paypal.com/webapps/mpp/contact-us"><?php echo smartyTranslate(array('s'=>'Contact us','mod'=>'paypal'),$_smarty_tpl);?>
</a></div>
					<div id="paypal-call-foonote" class="box right paypal-clear"><?php echo smartyTranslate(array('s'=>' ','mod'=>'paypal'),$_smarty_tpl);?>
</div>
				</div>

				<span class="paypal-section">1</span> <h3 class="inline"><?php echo smartyTranslate(array('s'=>'Select your solution','mod'=>'paypal'),$_smarty_tpl);?>
</h3> <a href="<?php echo smartyTranslate(array('s'=>'https://altfarm.mediaplex.com/ad/ck/3484-148727-12439-23?ID=1','mod'=>'paypal'),$_smarty_tpl);?>
" target="_blank"><?php echo smartyTranslate(array('s'=>'Learn more','mod'=>'paypal'),$_smarty_tpl);?>
</a>

				<br /><br /><br />

				<?php if ((in_array($_smarty_tpl->tpl_vars['PayPal_WPS']->value,$_smarty_tpl->tpl_vars['PayPal_allowed_methods']->value)||in_array($_smarty_tpl->tpl_vars['PayPal_HSS']->value,$_smarty_tpl->tpl_vars['PayPal_allowed_methods']->value))) {?>
					<h4 class="inline"><?php echo smartyTranslate(array('s'=>'Need PayPal to process all your card payments ?','mod'=>'paypal'),$_smarty_tpl);?>
</h4> <img src="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['PayPal_logo']->value['BackOfficeCards'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" height="22px"/>
					<div class="paypal-clear"></div>
					<div class="form-block">
						<?php if ((in_array($_smarty_tpl->tpl_vars['PayPal_WPS']->value,$_smarty_tpl->tpl_vars['PayPal_allowed_methods']->value))) {?>
							
							<label for="paypal_payment_wps">
								<input type="radio" name="paypal_payment_method" id="paypal_payment_wps" value='<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['PayPal_WPS']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
' <?php if ($_smarty_tpl->tpl_vars['PayPal_payment_method']->value==$_smarty_tpl->tpl_vars['PayPal_WPS']->value) {?>checked="checked"<?php }?> />
								<?php echo smartyTranslate(array('s'=>'Choose','mod'=>'paypal'),$_smarty_tpl);?>
 <?php echo smartyTranslate(array('s'=>'Website Payments Standard','mod'=>'paypal'),$_smarty_tpl);?>

								<br />
								<span class="description"><?php echo smartyTranslate(array('s'=>'Start accepting payments immediately.','mod'=>'paypal'),$_smarty_tpl);?>
<br /><?php echo smartyTranslate(array('s'=>'No subscription fees, pay only when you get paid.','mod'=>'paypal'),$_smarty_tpl);?>
</span>
							</label>
						<?php }?>
                                                <div class="paypal-clear"></div>
						<?php if ((in_array($_smarty_tpl->tpl_vars['PayPal_HSS']->value,$_smarty_tpl->tpl_vars['PayPal_allowed_methods']->value))) {?>
							
							<br />
							<label for="paypal_payment_wpp">
								<input type="radio" name="paypal_payment_method" id="paypal_payment_wpp" value='<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['PayPal_HSS']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
' <?php if ($_smarty_tpl->tpl_vars['PayPal_payment_method']->value==$_smarty_tpl->tpl_vars['PayPal_HSS']->value) {?>checked="checked"<?php }?> />
								<?php echo smartyTranslate(array('s'=>'Choose','mod'=>'paypal'),$_smarty_tpl);?>
 <?php echo smartyTranslate(array('s'=>'Website Payments Pro','mod'=>'paypal'),$_smarty_tpl);?>
<br />
								<span class="description"><?php echo smartyTranslate(array('s'=>'A professional platform to accept payments through credit cards and PayPal account, covered by seller protection.','mod'=>'paypal'),$_smarty_tpl);?>
<br /><?php echo smartyTranslate(array('s'=>'Customized payments pages. Monthly subscription of 25€.','mod'=>'paypal'),$_smarty_tpl);?>
</span>
								<p class="toolbox"><?php echo smartyTranslate(array('s'=>'Click on the SAVE button only when PayPal has approved your subscription for this product. This process can take up to 3-5 days.','mod'=>'paypal'),$_smarty_tpl);?>
</p>
							</label>
						<?php }?>
                        <div class="paypal-clear"></div>
                        <?php if ((in_array($_smarty_tpl->tpl_vars['PayPal_PPP']->value,$_smarty_tpl->tpl_vars['PayPal_allowed_methods']->value))) {?>
							
							<br />
							<label for="paypal_payment_ppp">
								<input type="radio" name="paypal_payment_method" id="paypal_payment_ppp" value='<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['PayPal_PPP']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
' <?php if ($_smarty_tpl->tpl_vars['PayPal_payment_method']->value==$_smarty_tpl->tpl_vars['PayPal_PPP']->value) {?>checked="checked"<?php }?> />
								<?php echo smartyTranslate(array('s'=>'Choose','mod'=>'paypal'),$_smarty_tpl);?>
 <?php echo smartyTranslate(array('s'=>'PayPal Plus','mod'=>'paypal'),$_smarty_tpl);?>
<br />
								<span class="description"></span>
								<p class="toolbox"></p>
							</label>
						<?php }?>
					</div>
				<?php }?>
				<div class="paypal-clear"></div>
				<?php if ((in_array($_smarty_tpl->tpl_vars['PayPal_ECS']->value,$_smarty_tpl->tpl_vars['PayPal_allowed_methods']->value))) {?>
				<h4 class="inline"><?php echo smartyTranslate(array('s'=>'Need PayPal in addition to your existing card processor ?','mod'=>'paypal'),$_smarty_tpl);?>
</h4> <img src="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['PayPal_logo']->value['LocalPayPalMarkSmall'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" />
				<div class="form-block">
					
					<label for="paypal_payment_ecs">
						<input type="radio" name="paypal_payment_method" id="paypal_payment_ecs" value='<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['PayPal_ECS']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
' <?php if ($_smarty_tpl->tpl_vars['PayPal_payment_method']->value==$_smarty_tpl->tpl_vars['PayPal_ECS']->value) {?>checked="checked"<?php }?> />
						<?php echo smartyTranslate(array('s'=>'Choose','mod'=>'paypal'),$_smarty_tpl);?>
 <?php echo smartyTranslate(array('s'=>'Express Checkout','mod'=>'paypal'),$_smarty_tpl);?>
<br />
						<span class="description"><?php echo smartyTranslate(array('s'=>'Boost your online sales by 30%*.','mod'=>'paypal'),$_smarty_tpl);?>
</span>
					</label>
				</div>
				<?php }?>

                <?php if ((in_array($_smarty_tpl->tpl_vars['PayPal_PVZ']->value,$_smarty_tpl->tpl_vars['PayPal_allowed_methods']->value))) {?>
                    <?php if (version_compare(@constant('PHP_VERSION'),'5.4.0','<')) {?>
						<strong class="braintree_title_bo"><?php echo smartyTranslate(array('s'=>'Want to use Braintree as card processor ?','mod'=>'paypal'),$_smarty_tpl);?>
</strong> &nbsp;<a href="<?php echo smartyTranslate(array('s'=>'https://www.braintreepayments.com/','mod'=>'paypal'),$_smarty_tpl);?>
" target="_blank" class="braintree_link"><img src="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['PayPal_module_dir']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
/views/img/logos/BRAINTREE.png" class="braintree_logo"> &nbsp;&nbsp;&nbsp;<div class="bo_paypal_help">?</div></a><br/>
						<p id="error_version_php"><?php echo smartyTranslate(array('s'=>'You can\'t use braintree because your PHP version is too old (PHP 5.4 min)','mod'=>'paypal'),$_smarty_tpl);?>
</p>
                    <?php } elseif (!$_smarty_tpl->tpl_vars['ps_ssl_active']->value) {?>
						<strong class="braintree_title_bo"><?php echo smartyTranslate(array('s'=>'Want to use Braintree as card processor ?','mod'=>'paypal'),$_smarty_tpl);?>
</strong> &nbsp;<a href="<?php echo smartyTranslate(array('s'=>'https://www.braintreepayments.com/','mod'=>'paypal'),$_smarty_tpl);?>
" target="_blank" class="braintree_link"><img src="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['PayPal_module_dir']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
/views/img/logos/BRAINTREE.png" class="braintree_logo"> &nbsp;&nbsp;&nbsp;<div class="bo_paypal_help">?</div></a><br/>
						<p id="error_version_php"><?php echo smartyTranslate(array('s'=>'You can\'t use braintree because you haven\'t enabled https','mod'=>'paypal'),$_smarty_tpl);?>
</p>
					<?php } else { ?>
                    
                        <br />
                        <strong class="braintree_title_bo"><?php echo smartyTranslate(array('s'=>'Want to use Braintree as card processor ?','mod'=>'paypal'),$_smarty_tpl);?>
 <?php echo smartyTranslate(array('s'=>'(Euro only)','mod'=>'paypal'),$_smarty_tpl);?>
</strong> &nbsp;<a href="<?php echo smartyTranslate(array('s'=>'https://www.braintreepayments.com/','mod'=>'paypal'),$_smarty_tpl);?>
" target="_blank" class="braintree_link"><img src="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['PayPal_module_dir']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
/views/img/logos/BRAINTREE.png" class="braintree_logo"> &nbsp;&nbsp;&nbsp;<div class="bo_paypal_help">?</div></a><br/>

						<label for="braintree_enabled">
                            <input type="checkbox" name="braintree_enabled" id="braintree_enabled" value='<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['PayPal_PVZ']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
' <?php if ($_smarty_tpl->tpl_vars['PayPal_braintree_enabled']->value) {?>checked="checked"<?php }?> />
                            <?php echo smartyTranslate(array('s'=>'Choose','mod'=>'paypal'),$_smarty_tpl);?>
 <?php echo smartyTranslate(array('s'=>'Braintree','mod'=>'paypal'),$_smarty_tpl);?>
<br />
                            <span class="description"></span>
                            <!-- <p class="toolbox"></p> -->
                        </label>
                        <span id="braintree_message" style="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['Braintree_Style']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
"><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['Braintree_Message']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</span>
                        <div id="paypal_braintree">
							<?php echo $_smarty_tpl->getSubTemplate ('./button_braintree.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

						</div>
                    <?php }?>
                <?php }?>
				<hr />
			</div>



			

			
			<div class="box" id="account">

				<span class="paypal-section">2</span> <h3 class="inline"><?php echo smartyTranslate(array('s'=>'Apply or open your PayPal Business account','mod'=>'paypal'),$_smarty_tpl);?>
</h3>

				<br /><br />

				<div id="signup">
					
					<a href="<?php echo smartyTranslate(array('s'=>'https://altfarm.mediaplex.com/ad/ck/3484-148727-12439-23?ID=2','mod'=>'paypal'),$_smarty_tpl);?>
" target="_blank" class="paypal-button paypal-signup-button" id="paypal-signup-button-u1"><?php echo smartyTranslate(array('s'=>'Sign Up','mod'=>'paypal'),$_smarty_tpl);?>
</a>
					<a href="<?php echo smartyTranslate(array('s'=>'https://altfarm.mediaplex.com/ad/ck/3484-148727-12439-23?ID=4','mod'=>'paypal'),$_smarty_tpl);?>
" target="_blank" class="paypal-button paypal-signup-button" id="paypal-signup-button-u2"><?php echo smartyTranslate(array('s'=>'Subscribe','mod'=>'paypal'),$_smarty_tpl);?>
</a>
					<a href="<?php echo smartyTranslate(array('s'=>'https://altfarm.mediaplex.com/ad/ck/3484-148727-12439-23?ID=3','mod'=>'paypal'),$_smarty_tpl);?>
" target="_blank" class="paypal-button paypal-signup-button" id="paypal-signup-button-u3"><?php echo smartyTranslate(array('s'=>'Sign Up','mod'=>'paypal'),$_smarty_tpl);?>
</a>

					
					<a href="<?php echo smartyTranslate(array('s'=>'https://altfarm.mediaplex.com/ad/ck/3484-148727-12439-23?ID=4','mod'=>'paypal'),$_smarty_tpl);?>
#" target="_blank" class="paypal-button paypal-signup-button" id="paypal-signup-button-u5"><?php echo smartyTranslate(array('s'=>'Subscribe','mod'=>'paypal'),$_smarty_tpl);?>
</a>

					<br /><br />

					
					<span class="paypal-signup-content" id="paypal-signup-content-u1"><?php echo smartyTranslate(array('s'=>'Once your account is created, come back to this page in order to complete step 3.','mod'=>'paypal'),$_smarty_tpl);?>
</span>
					<span class="paypal-signup-content" id="paypal-signup-content-u2"><?php echo smartyTranslate(array('s'=>'Click on the SAVE button only when PayPal has approved your subscription for this product, otherwise you won\'t be able to process payment. This process can take up to 3-5 days.','mod'=>'paypal'),$_smarty_tpl);?>
</span>
					<span class="paypal-signup-content" id="paypal-signup-content-u3"><?php echo smartyTranslate(array('s'=>'Once your account is created, come back to this page in order to complete step 3.','mod'=>'paypal'),$_smarty_tpl);?>
</span>

					
					<span class="paypal-signup-content" id="paypal-signup-content-u5"><?php echo smartyTranslate(array('s'=>'Click on the SAVE button only when PayPal has approved your subscription for this product, otherwise you won\'t be able to process payment. This process can take up to 3-5 days.','mod'=>'paypal'),$_smarty_tpl);?>
<br/>
                    <?php echo smartyTranslate(array('s'=>'If your application for Website Payments Pro has already been approved by PayPal, please go directly to step 3','mod'=>'paypal'),$_smarty_tpl);?>
.</span>

				</div>

				<hr />

			</div>
			
			<div>
				<span class="paypal-section">3</span>
				<h3 class="inline"><?php echo smartyTranslate(array('s'=>'Test TLS & curl','mod'=>'paypal'),$_smarty_tpl);?>
</h3>
				<br /><br />
				<input type="hidden" id="security_token" value="<?php echo $_GET['token'];?>
" >
				<span style="cursor: pointer;display: inline-block;" id="test_ssl_submit"><b><?php echo smartyTranslate(array('s'=>'Test','mod'=>'paypal'),$_smarty_tpl);?>
</b></span>
				<div style="margin-top: 10px;" id="test_ssl_result"></div>
			</div>
			
			<div class="box paypal-disabled" id="credentials">
				<span class="paypal-section">4</span> <h3 class="inline"><?php echo smartyTranslate(array('s'=>'Process payments on your online shop','mod'=>'paypal'),$_smarty_tpl);?>
</h3>
				<br /><br />

				<div class="paypal-hide" id="configuration">
					

					<div id="standard-credentials">
						<h4><?php echo smartyTranslate(array('s'=>'Communicate your PayPal identification info to PrestaShop','mod'=>'paypal'),$_smarty_tpl);?>
</h4>

						<br />

						<a href="#" class="paypal-button" id="paypal-get-identification">
						<?php echo smartyTranslate(array('s'=>'Get my PayPal identification info','mod'=>'paypal'),$_smarty_tpl);?>
<p class="toolbox"><?php echo smartyTranslate(array('s'=>'After clicking on the “Get my PayPal identification info” button, enter your login and password in the pop up, copy your PayPal identification info from the pop up and paste them is the below fields.','mod'=>'paypal'),$_smarty_tpl);?>
</p>
						</a>

						<br /><br />

						<dl>
							<dt><label for="api_username"><?php echo smartyTranslate(array('s'=>'API username','mod'=>'paypal'),$_smarty_tpl);?>
 : </label></dt>
							<dd><input type='text' name="api_username" id="api_username" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['PayPal_api_username']->value, ENT_QUOTES, 'UTF-8', true);?>
" autocomplete="off" size="85"/></dd>
							<dt><label for="api_password"><?php echo smartyTranslate(array('s'=>'API password','mod'=>'paypal'),$_smarty_tpl);?>
 : </label></dt>
							<dd><input type='password' size="85" name="api_password" id="api_password" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['PayPal_api_password']->value, ENT_QUOTES, 'UTF-8', true);?>
" autocomplete="off" /></dd>
							<dt><label for="api_signature"><?php echo smartyTranslate(array('s'=>'API signature','mod'=>'paypal'),$_smarty_tpl);?>
 : </label></dt>
							<dd><input type='text' size="85" name="api_signature" id="api_signature" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['PayPal_api_signature']->value, ENT_QUOTES, 'UTF-8', true);?>
" autocomplete="off" /></dd>
						</dl>
						<div class="paypal-clear"></div>
						<span class="description"><?php echo smartyTranslate(array('s'=>'Please check once more that you pasted all the characters.','mod'=>'paypal'),$_smarty_tpl);?>
</span>
					</div>

					<div id="paypalplus-credentials">
						<h4><?php echo smartyTranslate(array('s'=>'Provide your PayPal API credentials to PrestaShop','mod'=>'paypal'),$_smarty_tpl);?>
</h4>

						<br />

						<dl>
							<dt><label for="client_id"><?php echo smartyTranslate(array('s'=>'Client ID','mod'=>'paypal'),$_smarty_tpl);?>
 : </label></dt>
							<dd><input type='text' name="client_id" id="client_id" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['PayPal_plus_client']->value, ENT_QUOTES, 'UTF-8', true);?>
" autocomplete="off" size="85"/></dd>
							<dt><label for="secret"><?php echo smartyTranslate(array('s'=>'Secret','mod'=>'paypal'),$_smarty_tpl);?>
 : </label></dt>
							<dd><input type='password' size="85" name="secret" id="secret" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['PayPal_plus_secret']->value, ENT_QUOTES, 'UTF-8', true);?>
" autocomplete="off" /></dd>

							<dt><label for="webprofile"><?php echo smartyTranslate(array('s'=>'Use personnalisation (uses your logo and your shop name on Paypal) :','mod'=>'paypal'),$_smarty_tpl);?>
</label></dt>
							<dd>
								<input type="radio" name="paypalplus_webprofile" value="1" id="paypal_plus_webprofile_yes" <?php if ($_smarty_tpl->tpl_vars['PayPal_plus_webprofile']->value) {?>checked="checked"<?php }?> /> <label for="paypal_plus_webprofile_yes"><?php echo smartyTranslate(array('s'=>'Yes','mod'=>'paypal'),$_smarty_tpl);?>
</label><br />
								<input type="radio" name="paypalplus_webprofile"  value="0" id="paypal_plus_webprofile_no" <?php if ($_smarty_tpl->tpl_vars['PayPal_plus_webprofile']->value=='0') {?>checked="checked"<?php }?> /> <label for="paypal_plus_webprofile_no"><?php echo smartyTranslate(array('s'=>'No','mod'=>'paypal'),$_smarty_tpl);?>
</label>
							</dd>
						</dl>
						<div class="paypal-clear"></div>
					</div>

					<div id="integral-credentials" class="paypal-hide">
						<h4><?php echo smartyTranslate(array('s'=>'Indicate the email you used when you signed up for a PayPal Business account','mod'=>'paypal'),$_smarty_tpl);?>
</h4>

						<br />

						<dl>
							<dt><label for="api_business_account"><?php echo smartyTranslate(array('s'=>'API business e-mail','mod'=>'paypal'),$_smarty_tpl);?>
 : </label></dt>
							<dd><input type='text' name="api_business_account" id="api_business_account" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['PayPal_api_business_account']->value, ENT_QUOTES, 'UTF-8', true);?>
" autocomplete="off" /></dd>
						</dl>
					</div>

					<div class="paypal-clear"></div>

					<h4><?php echo smartyTranslate(array('s'=>'To finalize setting up your PayPal account, you need to','mod'=>'paypal'),$_smarty_tpl);?>
 : </h4>
					<p><span class="paypal-bold">1.</span> <?php echo smartyTranslate(array('s'=>'Confirm your email address : check the email sent by PayPal when you created your account','mod'=>'paypal'),$_smarty_tpl);?>
</p>
					<p><span class="paypal-bold">2.</span> <?php echo smartyTranslate(array('s'=>'Link your PayPal account to a bank account or a credit card : log into your PayPal account and go to "My business setup"','mod'=>'paypal'),$_smarty_tpl);?>
</p>

					<h4><?php echo smartyTranslate(array('s'=>'Configuration options','mod'=>'paypal'),$_smarty_tpl);?>
</h4>
					<div id="integral_evolution_solution" class="paypal-hide">
						<p class="description">
							<?php echo smartyTranslate(array('s'=>'Choose the solution you want to use','mod'=>'paypal'),$_smarty_tpl);?>

						</p>
						<input type="radio" name="integral_evolution_solution" id="integral_evolution_solution_iframe" value="1" <?php if ($_smarty_tpl->tpl_vars['PayPal_integral_evolution_solution']->value==1) {?>checked="checked"<?php }?> /> <label for="integral_evolution_solution_iframe"><?php echo smartyTranslate(array('s'=>'Iframe','mod'=>'paypal'),$_smarty_tpl);?>
</label><br />
						<input type="radio" name="integral_evolution_solution" id="integral_evolution_solution_no_iframe" value="0" <?php if ($_smarty_tpl->tpl_vars['PayPal_integral_evolution_solution']->value==0) {?>checked="checked"<?php }?> /> <label for="integral_evolution_solution_no_iframe"><?php echo smartyTranslate(array('s'=>'Full page redirect','mod'=>'paypal'),$_smarty_tpl);?>
</label><br/>
						<div id="integral_evolution_template">
							<p class="description">
							<?php echo smartyTranslate(array('s'=>'Choose your template','mod'=>'paypal'),$_smarty_tpl);?>

							</p>
							<img src="../modules/paypal/views/img/template.png" alt=""><br/>
							<input type="radio" name="integral_evolution_template" id="integral_evolution_template_A" value="A" <?php if ($_smarty_tpl->tpl_vars['PayPal_integral_evolution_template']->value=="A") {?>checked="checked"<?php }?>  style="margin-left:60px"/> <label for="integral_evolution_template">A</label> &nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="integral_evolution_template" id="integral_evolution_template_B" value="B" <?php if ($_smarty_tpl->tpl_vars['PayPal_integral_evolution_template']->value=="B") {?>checked="checked"<?php }?> style="margin-left:80px"/> <label for="integral_evolution_template">B</label>&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="integral_evolution_template" id="integral_evolution_template_C" value="C" <?php if ($_smarty_tpl->tpl_vars['PayPal_integral_evolution_template']->value=="C") {?>checked="checked"<?php }?> style="margin-left:70px" /> <label for="integral_evolution_template">C</label>&nbsp;&nbsp;&nbsp;&nbsp;
						</div>


					</div>

					<div id="express_checkout_shortcut" class="paypal-hide">
						<p><?php echo smartyTranslate(array('s'=>'Use express checkout shortcut','mod'=>'paypal'),$_smarty_tpl);?>
</p>
						<p class="description"><?php echo smartyTranslate(array('s'=>'Offer your customers a 2-click payment option','mod'=>'paypal'),$_smarty_tpl);?>
</p>
						<input type="radio" name="express_checkout_shortcut" id="paypal_payment_ecs_no_shortcut" value="1" <?php if ($_smarty_tpl->tpl_vars['PayPal_express_checkout_shortcut']->value==1) {?>checked="checked"<?php }?> /> <label for="paypal_payment_ecs_no_shortcut"><?php echo smartyTranslate(array('s'=>'Yes','mod'=>'paypal'),$_smarty_tpl);?>
 (recommanded)</label><br />
						<input type="radio" name="express_checkout_shortcut" id="paypal_payment_ecs_shortcut" value="0" <?php if ($_smarty_tpl->tpl_vars['PayPal_express_checkout_shortcut']->value==0) {?>checked="checked"<?php }?> /> <label for="paypal_payment_ecs_shortcut"><?php echo smartyTranslate(array('s'=>'No','mod'=>'paypal'),$_smarty_tpl);?>
</label>
					</div>

					<div id="in_context_checkout" class="paypal-hide">
						<p><?php echo smartyTranslate(array('s'=>'Use PayPal In Context Checkout','mod'=>'paypal'),$_smarty_tpl);?>
</p>
						<p class="description"><?php echo smartyTranslate(array('s'=>'Make your client pay without leaving your website','mod'=>'paypal'),$_smarty_tpl);?>
</p>
						<input type="radio" name="in_context_checkout" id="paypal_payment_ecs_no_in_context_checkout" value="1" <?php if ($_smarty_tpl->tpl_vars['PayPal_in_context_checkout']->value==1) {?>checked="checked"<?php }?> /> <label for="paypal_payment_ecs_no_in_context_checkout"><?php echo smartyTranslate(array('s'=>'Yes','mod'=>'paypal'),$_smarty_tpl);?>
</label><br />
						<input type="radio" name="in_context_checkout" id="paypal_payment_ecs_in_context_checkout" value="0" <?php if ($_smarty_tpl->tpl_vars['PayPal_in_context_checkout']->value==0) {?>checked="checked"<?php }?> /> <label for="paypal_payment_ecs_in_context_checkout"><?php echo smartyTranslate(array('s'=>'No','mod'=>'paypal'),$_smarty_tpl);?>
</label>
						<p class="merchant_id">
							<label><?php echo smartyTranslate(array('s'=>'Merchant ID','mod'=>'paypal'),$_smarty_tpl);?>
</label>
							<input type="text" name="in_context_checkout_merchant_id" id="in_context_checkout_merchant_id" value="<?php if (isset($_smarty_tpl->tpl_vars['PayPal_in_context_checkout_merchant_id']->value)&&$_smarty_tpl->tpl_vars['PayPal_in_context_checkout_merchant_id']->value!='') {?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['PayPal_in_context_checkout_merchant_id']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php }?>" />
						</p>
					</div>

					<div>
						<p><?php echo smartyTranslate(array('s'=>'Use the PayPal Login functionnality','mod'=>'paypal'),$_smarty_tpl);?>
<?php if ($_smarty_tpl->tpl_vars['default_lang_iso']->value=='fr') {?><?php echo smartyTranslate(array('s'=>'(*see the ','mod'=>'paypal'),$_smarty_tpl);?>
 <a href="http://altfarm.mediaplex.com/ad/ck/3484-197941-8030-96"> <?php echo smartyTranslate(array('s'=>'integration guide','mod'=>'paypal'),$_smarty_tpl);?>
 </a> <?php echo smartyTranslate(array('s'=>'and follow the steps','mod'=>'paypal'),$_smarty_tpl);?>
)<?php } else { ?><?php echo smartyTranslate(array('s'=>'(*see the ','mod'=>'paypal'),$_smarty_tpl);?>
 <a href="http://altfarm.mediaplex.com/ad/ck/3484-197941-8030-170"> <?php echo smartyTranslate(array('s'=>'integration guide','mod'=>'paypal'),$_smarty_tpl);?>
 </a> <?php echo smartyTranslate(array('s'=>'and follow the steps','mod'=>'paypal'),$_smarty_tpl);?>
)<?php }?></p>
						<p class="description">
							<?php echo smartyTranslate(array('s'=>'This function allows to your clients to connect with their PayPal credentials to shorten the check out','mod'=>'paypal'),$_smarty_tpl);?>

						</p>
						<div id="paypal_login_yes_or_no" class="">
							<p class="description"></p>
							<input type="radio" name="paypal_login" id="paypal_login_yes" value="1" <?php if ($_smarty_tpl->tpl_vars['PayPal_login']->value==1) {?>checked="checked"<?php }?> /> <label for="paypal_login_yes"><?php echo smartyTranslate(array('s'=>'Yes','mod'=>'paypal'),$_smarty_tpl);?>
 </label><br />
							<input type="radio" name="paypal_login" id="paypal_login_no" value="0" <?php if ($_smarty_tpl->tpl_vars['PayPal_login']->value==0) {?>checked="checked"<?php }?> /> <label for="paypal_login_no"><?php echo smartyTranslate(array('s'=>'No','mod'=>'paypal'),$_smarty_tpl);?>
</label>
						</div>
						<div id="paypal_login_configuration"<?php if ($_smarty_tpl->tpl_vars['PayPal_login']->value==0) {?> style="display: none;"<?php }?>>
							<p>
								<?php echo smartyTranslate(array('s'=>'Fill in the informations of your PayPal account','mod'=>'paypal'),$_smarty_tpl);?>
.<?php if ($_smarty_tpl->tpl_vars['default_lang_iso']->value=='fr') {?>(* <?php echo smartyTranslate(array('s'=>'See','mod'=>'paypal'),$_smarty_tpl);?>
 <a href="http://altfarm.mediaplex.com/ad/ck/3484-197941-8030-96"><?php echo smartyTranslate(array('s'=>'Integration Guide','mod'=>'paypal'),$_smarty_tpl);?>
</a>)<?php }?>.
							</p>
							<dl>
								<dt>
									<?php echo smartyTranslate(array('s'=>'Client ID','mod'=>'paypal'),$_smarty_tpl);?>

								</dt>
								<dd>
									<input type="text" name="paypal_login_client_id" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['PayPal_login_client_id']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" autocomplete="off" size="85">
								</dd>
								<dt>
									<?php echo smartyTranslate(array('s'=>'Secret','mod'=>'paypal'),$_smarty_tpl);?>

								</dt>
								<dd>
									<input type="text" name="paypal_login_client_secret" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['PayPal_login_secret']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" autocomplete="off" size="85">
								</dd>

								<dt>
									<?php echo smartyTranslate(array('s'=>'Choose your template','mod'=>'paypal'),$_smarty_tpl);?>

									<p class="description" style="margin-top:-10px;">(<?php echo smartyTranslate(array('s'=>'Translated in your language','mod'=>'paypal'),$_smarty_tpl);?>
)</p>
								</dt>
								<dd>
									<input type="radio" name="paypal_login_client_template" id="paypal_login_client_template_paypal_blue" value="1"<?php if ($_smarty_tpl->tpl_vars['PayPal_login_tpl']->value==1) {?> checked<?php }?> />
									<label for="paypal_login_client_template_paypal_blue">
										<img src="../modules/paypal/views/img/paypal_login_blue.png" alt="">
									</label>
									<br />
									<input type="radio" name="paypal_login_client_template" id="paypal_login_client_template_neutral" value="2"<?php if ($_smarty_tpl->tpl_vars['PayPal_login_tpl']->value==2) {?> checked<?php }?> />
									<label for="paypal_login_client_template_neutral">
										<img src="../modules/paypal/views/img/paypal_login_grey.png" alt="">
									</label>
								</dd>
							</dl>


							<div class="paypal-clear"></div>
						</div>
					</div>


					<p><?php echo smartyTranslate(array('s'=>'Use Sand box','mod'=>'paypal'),$_smarty_tpl);?>
</p>
					<p class="description"><?php echo smartyTranslate(array('s'=>'Activate a test environment in your PayPal account (only if you are a developer).','mod'=>'paypal'),$_smarty_tpl);?>
 <a href="<?php echo smartyTranslate(array('s'=>'https://developer.paypal.com/','mod'=>'paypal'),$_smarty_tpl);?>
" target="_blank"><?php echo smartyTranslate(array('s'=>'Learn more','mod'=>'paypal'),$_smarty_tpl);?>
</a></p>
					<input type="radio" name="sandbox_mode" id="paypal_payment_live_mode" value="0" <?php if ($_smarty_tpl->tpl_vars['PayPal_sandbox_mode']->value==0) {?>checked="checked"<?php }?> /> <label for="paypal_payment_live_mode"><?php echo smartyTranslate(array('s'=>'Live mode','mod'=>'paypal'),$_smarty_tpl);?>
</label><br />
					<input type="radio" name="sandbox_mode" id="paypal_payment_test_mode" value="1" <?php if ($_smarty_tpl->tpl_vars['PayPal_sandbox_mode']->value==1) {?>checked="checked"<?php }?> /> <label for="paypal_payment_test_mode"><?php echo smartyTranslate(array('s'=>'Test mode','mod'=>'paypal'),$_smarty_tpl);?>
</label>

					<br />

					<p><?php echo smartyTranslate(array('s'=>'Payment type','mod'=>'paypal'),$_smarty_tpl);?>
</p>
					<p class="description"><?php echo smartyTranslate(array('s'=>'Choose your way of processing payments (automatically vs.manual authorization).','mod'=>'paypal'),$_smarty_tpl);?>
</p>
					<input type="radio" name="payment_capture" id="paypal_direct_sale" value="0" <?php if ($_smarty_tpl->tpl_vars['PayPal_payment_capture']->value==0) {?>checked="checked"<?php }?> /> <label for="paypal_direct_sale"><?php echo smartyTranslate(array('s'=>'Direct sales (recommended)','mod'=>'paypal'),$_smarty_tpl);?>
</label><br />
					<input type="radio" name="payment_capture" id="paypal_manual_capture" value="1" <?php if ($_smarty_tpl->tpl_vars['PayPal_payment_capture']->value==1) {?>checked="checked"<?php }?> /> <label for="paypal_manual_capture"><?php echo smartyTranslate(array('s'=>'Authorization/Manual capture (payment shipping)','mod'=>'paypal'),$_smarty_tpl);?>
</label>
					<div id="braintree-credentials" class="paypal-hide">
						
					</div>
					<div class="paypal-hide" id="braintree">
						
					</div>
					<div class="clear"></div>
					<br /><br />
				</div>

				<input type="hidden" name="submitPaypal" value="paypal_configuration" />
				<input type="submit" name="submitButton" value="<?php echo smartyTranslate(array('s'=>'Save','mod'=>'paypal'),$_smarty_tpl);?>
" id="paypal_submit" />

				<div class="box paypal-hide" id="paypal-test-mode-confirmation">
					<h3><?php echo smartyTranslate(array('s'=>'Activating the test mode implies that','mod'=>'paypal'),$_smarty_tpl);?>
 :</h3>
					<ul>
						<li><?php echo smartyTranslate(array('s'=>'You won\'t be able to accept payment','mod'=>'paypal'),$_smarty_tpl);?>
</li>
                        <li><?php echo smartyTranslate(array('s'=>'You will need to come back to the PayPal module page in order to complete the Step 3 before going live.','mod'=>'paypal'),$_smarty_tpl);?>
</li>
                        <li><?php echo smartyTranslate(array('s'=>'You\'ll need to create an account on the PayPal sandbox site','mod'=>'paypal'),$_smarty_tpl);?>
 (<a href="https://developer.paypal.com/" target="_blank"><?php echo smartyTranslate(array('s'=>'learn more','mod'=>'paypal'),$_smarty_tpl);?>
</a>)</li>
                        <li><?php echo smartyTranslate(array('s'=>'You\'ll need programming skills','mod'=>'paypal'),$_smarty_tpl);?>
</li>
					</ul>

					<h4><?php echo smartyTranslate(array('s'=>'Are you sure you want to activate the test mode ?','mod'=>'paypal'),$_smarty_tpl);?>
</h4>

					<div id="buttons">
						<button class="sandbox_confirm" value="0"><?php echo smartyTranslate(array('s'=>'No','mod'=>'paypal'),$_smarty_tpl);?>
</button>
						<button class="sandbox_confirm" value="1"><?php echo smartyTranslate(array('s'=>'Yes','mod'=>'paypal'),$_smarty_tpl);?>
</button>
					</div>
				</div>

				<?php if (isset($_smarty_tpl->tpl_vars['PayPal_save_success']->value)) {?>
				<div class="box paypal-hide" id="paypal-save-success">
					<h3><?php echo smartyTranslate(array('s'=>'Congratulation !','mod'=>'paypal'),$_smarty_tpl);?>
</h3>
					<?php if ($_smarty_tpl->tpl_vars['PayPal_sandbox_mode']->value==0) {?>
					<p><?php echo smartyTranslate(array('s'=>'You can now start accepting Payment  with PayPal.','mod'=>'paypal'),$_smarty_tpl);?>
</p>
					<?php } elseif ($_smarty_tpl->tpl_vars['PayPal_sandbox_mode']->value==1) {?>
					<p><?php echo smartyTranslate(array('s'=>'You can now start testing PayPal solutions. Don\'t forget to comeback to this page and activate the live mode in order to start accepting payements.','mod'=>'paypal'),$_smarty_tpl);?>
</p>
					<?php }?>
				</div>
				<?php }?>
				<?php if (isset($_smarty_tpl->tpl_vars['PayPal_save_failure']->value)) {?>
				<div class="box paypal-hide" id="paypal-save-failure">
					<h3><?php echo smartyTranslate(array('s'=>'Error !','mod'=>'paypal'),$_smarty_tpl);?>
</h3>
					<p><?php echo smartyTranslate(array('s'=>'You need to complete the PayPal identification Information in step 3 otherwise you won\'t be able to accept payment.','mod'=>'paypal'),$_smarty_tpl);?>
</p>
				</div>
				<?php }?>

				<div class="box paypal-hide" id="js-paypal-save-failure">
					<h3><?php echo smartyTranslate(array('s'=>'Error !','mod'=>'paypal'),$_smarty_tpl);?>
</h3>
					<p><?php echo smartyTranslate(array('s'=>'You need to complete the PayPal identification Information in step 3 otherwise you won\'t be able to accept payment.','mod'=>'paypal'),$_smarty_tpl);?>
</p>
				</div>

				<hr />
			</div>

		</form>

    <?php } else { ?>
		<div class="paypal-clear"></div><hr />
			<div class="box">
				<p><?php echo smartyTranslate(array('s'=>'Your country is not available for this module please go on Prestashop addons to see the different possibilities.','mod'=>'paypal'),$_smarty_tpl);?>
</p>
			</div>
			<hr />
		</div>

	<?php }?>

</div>
<script>
	var tlscurltest_url = '<?php echo addslashes($_smarty_tpl->tpl_vars['tls_link_ajax']->value);?>
';
</script>
<?php }} ?>
