<?php /* Smarty version Smarty-3.1.19, created on 2019-02-06 14:46:24
         compiled from "/home/sundevice/public_html/modules/bankwirecopy/payment.tpl" */ ?>
<?php /*%%SmartyHeaderCode:10047188655c5ae530dc8d84-18216637%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '42d8c27d711ba49728cb6a30168e415537ee39c3' => 
    array (
      0 => '/home/sundevice/public_html/modules/bankwirecopy/payment.tpl',
      1 => 1549459724,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '10047188655c5ae530dc8d84-18216637',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'this_path_ssl' => 0,
    'this_path' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c5ae530dd7469_33804089',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c5ae530dd7469_33804089')) {function content_5c5ae530dd7469_33804089($_smarty_tpl) {?><p class="payment_module">
	<a href="<?php echo $_smarty_tpl->tpl_vars['this_path_ssl']->value;?>
payment.php" title="<?php echo smartyTranslate(array('s'=>'Pay by bank wire','mod'=>'bankwirecopy'),$_smarty_tpl);?>
">
		<img src="<?php echo $_smarty_tpl->tpl_vars['this_path']->value;?>
bankwire.jpg" alt="<?php echo smartyTranslate(array('s'=>'Pay by bank wire','mod'=>'bankwirecopy'),$_smarty_tpl);?>
" />
		<?php echo smartyTranslate(array('s'=>'Pay by bank wire (order process will be longer)','mod'=>'bankwirecopy'),$_smarty_tpl);?>

	</a>
</p><?php }} ?>
