<?php /* Smarty version Smarty-3.1.19, created on 2019-04-30 02:19:18
         compiled from "/home/sundevice/public_html/themes/pos_ruby5/modules/bankwirecopie/views/templates/hook/payment.tpl" */ ?>
<?php /*%%SmartyHeaderCode:5484114565cc79486b5b2e8-94452734%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '285436f058a51f14085d203e48095291a9abc828' => 
    array (
      0 => '/home/sundevice/public_html/themes/pos_ruby5/modules/bankwirecopie/views/templates/hook/payment.tpl',
      1 => 1549466193,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5484114565cc79486b5b2e8-94452734',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'link' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cc79486b73062_49635831',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cc79486b73062_49635831')) {function content_5cc79486b73062_49635831($_smarty_tpl) {?>
<div class="row">
	<div class="col-xs-12">
		<p class="payment_module">
			<a class="bankwirecopie" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getModuleLink('bankwirecopie','payment'), ENT_QUOTES, 'UTF-8', true);?>
" title="<?php echo smartyTranslate(array('s'=>'Pay by bank wire','mod'=>'bankwirecopie'),$_smarty_tpl);?>
">
				<?php echo smartyTranslate(array('s'=>'Pay by bank wire','mod'=>'bankwirecopie'),$_smarty_tpl);?>
 <span><?php echo smartyTranslate(array('s'=>'(order processing will be longer)','mod'=>'bankwirecopie'),$_smarty_tpl);?>
</span>
			</a>
		</p>
	</div>
</div>
<?php }} ?>
