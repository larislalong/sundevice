<?php /* Smarty version Smarty-3.1.19, created on 2019-02-15 10:23:55
         compiled from "D:\wamp\www\projects\ps\sun-device.local\modules\posstaticfooter\views\templates\admin\helpers\tree\tree_header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:37435c66852b724ac0-29250242%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6bf428565bc6c61e4f1914b8fb00fe427cdc124f' => 
    array (
      0 => 'D:\\wamp\\www\\projects\\ps\\sun-device.local\\modules\\posstaticfooter\\views\\templates\\admin\\helpers\\tree\\tree_header.tpl',
      1 => 1534502030,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '37435c66852b724ac0-29250242',
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
  'unifunc' => 'content_5c66852b764ba8_44391738',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c66852b764ba8_44391738')) {function content_5c66852b764ba8_44391738($_smarty_tpl) {?>
<div class="tree-panel-heading-controls clearfix">
	<?php if (isset($_smarty_tpl->tpl_vars['title']->value)) {?><i class="icon-tag"></i>&nbsp;<?php echo smartyTranslate(array('s'=>$_smarty_tpl->tpl_vars['title']->value),$_smarty_tpl);?>
<?php }?>
	<?php if (isset($_smarty_tpl->tpl_vars['toolbar']->value)) {?><?php echo $_smarty_tpl->tpl_vars['toolbar']->value;?>
<?php }?>
</div><?php }} ?>
