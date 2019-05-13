<?php /* Smarty version Smarty-3.1.19, created on 2019-02-13 22:47:59
         compiled from "D:\wamp\www\projects\ps\sun-device.local\adminSunDevice\themes\default\template\helpers\tree\tree_header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:317365c64908f21d5d2-60630407%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b1b32ca3294c8bfb472d7a2d93fc17aefeedbfb9' => 
    array (
      0 => 'D:\\wamp\\www\\projects\\ps\\sun-device.local\\adminSunDevice\\themes\\default\\template\\helpers\\tree\\tree_header.tpl',
      1 => 1532346758,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '317365c64908f21d5d2-60630407',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'toolbar' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c64908f257c25_65480901',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c64908f257c25_65480901')) {function content_5c64908f257c25_65480901($_smarty_tpl) {?>
<div class="tree-panel-heading-controls clearfix">
	<?php if (isset($_smarty_tpl->tpl_vars['title']->value)) {?><i class="icon-tag"></i>&nbsp;<?php echo smartyTranslate(array('s'=>$_smarty_tpl->tpl_vars['title']->value),$_smarty_tpl);?>
<?php }?>
	<?php if (isset($_smarty_tpl->tpl_vars['toolbar']->value)) {?><?php echo $_smarty_tpl->tpl_vars['toolbar']->value;?>
<?php }?>
</div>
<?php }} ?>
