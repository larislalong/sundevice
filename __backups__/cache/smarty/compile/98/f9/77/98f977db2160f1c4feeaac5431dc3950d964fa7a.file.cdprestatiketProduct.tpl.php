<?php /* Smarty version Smarty-3.1.19, created on 2019-02-14 14:51:48
         compiled from "D:\wamp\www\projects\ps\sun-device.local\modules\cdprestatiket\views\templates\hook\cdprestatiketProduct.tpl" */ ?>
<?php /*%%SmartyHeaderCode:94415c6572745fb185-84762201%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '98f977db2160f1c4feeaac5431dc3950d964fa7a' => 
    array (
      0 => 'D:\\wamp\\www\\projects\\ps\\sun-device.local\\modules\\cdprestatiket\\views\\templates\\hook\\cdprestatiketProduct.tpl',
      1 => 1438692226,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '94415c6572745fb185-84762201',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'cdpt_controller11' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c657274655c64_67706477',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c657274655c64_67706477')) {function content_5c657274655c64_67706477($_smarty_tpl) {?>

<?php if ($_smarty_tpl->tpl_vars['cdpt_controller11']->value) {?>
	<a class="btn btn-primary btn-lg" href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['cdpt_controller11']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" title="<?php echo smartyTranslate(array('s'=>'Send a new Ticket','mod'=>'cdprestatiket'),$_smarty_tpl);?>
"><span><?php echo smartyTranslate(array('s'=>'Send a new Ticket','mod'=>'cdprestatiket'),$_smarty_tpl);?>
</span></a>
<?php }?><?php }} ?>
