<?php /* Smarty version Smarty-3.1.19, created on 2019-02-06 16:01:13
         compiled from "/home/sundevice/public_html/modules/bankwirecopie/views/templates/hook/payment.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2872791445c5af6b99be711-41995637%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ea2e592370a11a325272b953346a288c81799ee8' => 
    array (
      0 => '/home/sundevice/public_html/modules/bankwirecopie/views/templates/hook/payment.tpl',
      1 => 1549464708,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2872791445c5af6b99be711-41995637',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'link' => 0,
    'this_path_bw' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c5af6b99d0711_00737652',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c5af6b99d0711_00737652')) {function content_5c5af6b99d0711_00737652($_smarty_tpl) {?>

<p class="payment_module">
	<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getModuleLink('bankwirecopie','payment'), ENT_QUOTES, 'UTF-8', true);?>
" title="<?php echo smartyTranslate(array('s'=>'Pay by bank wire','mod'=>'bankwirecopie'),$_smarty_tpl);?>
">
		<img src="<?php echo $_smarty_tpl->tpl_vars['this_path_bw']->value;?>
bankwirecopie.jpg" alt="<?php echo smartyTranslate(array('s'=>'Pay by bank wire','mod'=>'bankwirecopie'),$_smarty_tpl);?>
" width="86" height="49"/>
		<?php echo smartyTranslate(array('s'=>'Pay by bank wire','mod'=>'bankwirecopie'),$_smarty_tpl);?>
&nbsp;<span><?php echo smartyTranslate(array('s'=>'(order processing will be longer)','mod'=>'bankwirecopie'),$_smarty_tpl);?>
</span>
	</a>
</p><?php }} ?>
