<?php /* Smarty version Smarty-3.1.19, created on 2019-01-29 13:10:13
         compiled from "/home/sundevice/public_html/themes/pos_ruby5/modules/bankwire/views/templates/hook/payment.tpl" */ ?>
<?php /*%%SmartyHeaderCode:9189274725c5042a58ac676-51742997%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '050d35fc4cf24a7e8cf3f63c3d91861827dd4d99' => 
    array (
      0 => '/home/sundevice/public_html/themes/pos_ruby5/modules/bankwire/views/templates/hook/payment.tpl',
      1 => 1534502018,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9189274725c5042a58ac676-51742997',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'link' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c5042a58b3145_80402354',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c5042a58b3145_80402354')) {function content_5c5042a58b3145_80402354($_smarty_tpl) {?>
<div class="row">
	<div class="col-xs-12">
		<p class="payment_module">
			<a class="bankwire" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getModuleLink('bankwire','payment'), ENT_QUOTES, 'UTF-8', true);?>
" title="<?php echo smartyTranslate(array('s'=>'Pay by bank wire','mod'=>'bankwire'),$_smarty_tpl);?>
">
				<?php echo smartyTranslate(array('s'=>'Pay by bank wire','mod'=>'bankwire'),$_smarty_tpl);?>
 <span><?php echo smartyTranslate(array('s'=>'(order processing will be longer)','mod'=>'bankwire'),$_smarty_tpl);?>
</span>
			</a>
		</p>
	</div>
</div>
<?php }} ?>
