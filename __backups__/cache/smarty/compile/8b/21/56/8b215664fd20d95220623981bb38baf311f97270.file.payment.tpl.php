<?php /* Smarty version Smarty-3.1.19, created on 2019-01-29 13:10:13
         compiled from "/home/sundevice/public_html/modules/lydiaapi/views/templates/hook/payment.tpl" */ ?>
<?php /*%%SmartyHeaderCode:14840436755c5042a58e14d0-36950274%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8b215664fd20d95220623981bb38baf311f97270' => 
    array (
      0 => '/home/sundevice/public_html/modules/lydiaapi/views/templates/hook/payment.tpl',
      1 => 1536857993,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14840436755c5042a58e14d0-36950274',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'cart' => 0,
    'module_dir' => 0,
    'form_url' => 0,
    'form_data' => 0,
    'value' => 0,
    'key' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c5042a58edf38_28893700',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c5042a58edf38_28893700')) {function content_5c5042a58edf38_28893700($_smarty_tpl) {?>

<div class="row">
	<div class="col-xs-12">
		<p class="payment_module" id="lydiaapi_payment_button">
			<?php if ($_smarty_tpl->tpl_vars['cart']->value->getOrderTotal()<2) {?>
				<a href="" style="background: url(<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['module_dir']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
/lydia-logo.png) 15px 15px no-repeat #fbfbfb;padding-left: 200px;" title= alt="<?php echo smartyTranslate(array('s'=>'Payer via la plateforme Lydia','mod'=>'lydiaapi'),$_smarty_tpl);?>
">
					<?php echo smartyTranslate(array('s'=>'Minimum amount required in order to pay with my payment module:','mod'=>'lydiaapi'),$_smarty_tpl);?>
 <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['convertPrice'][0][0]->convertPrice(array('price'=>2),$_smarty_tpl);?>

				</a>
			<?php } else { ?>
				<a id="lydiaapi_btn" href="#" style="background: url(<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['module_dir']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
/lydia-logo.png) 15px 15px no-repeat #fbfbfb;padding-left: 200px;" title= alt="<?php echo smartyTranslate(array('s'=>'Payer via la plateforme Lydia','mod'=>'lydiaapi'),$_smarty_tpl);?>
">
					<?php echo smartyTranslate(array('s'=>'Payer via la plateforme Lydia','mod'=>'lydiaapi'),$_smarty_tpl);?>

				</a>
			<?php }?>
		</p>
	</div>
</div>
<form id="form_lydia_api" method="post" action="<?php echo $_smarty_tpl->tpl_vars['form_url']->value;?>
" style="display:none">
	<?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['form_data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value) {
$_smarty_tpl->tpl_vars['value']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['value']->key;
?>
	<input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['value']->value;?>
" name="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
">
	<?php } ?>
</form>

<script type="text/javascript">
	$(document).ready(function() {
		$("#lydiaapi_btn").click(function(e){
			e.preventDefault();
			$("#form_lydia_api").submit();
		});
	});
</script>
<?php }} ?>
