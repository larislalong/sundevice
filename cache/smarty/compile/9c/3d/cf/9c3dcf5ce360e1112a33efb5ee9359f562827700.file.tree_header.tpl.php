<?php /* Smarty version Smarty-3.1.19, created on 2019-05-03 15:11:00
         compiled from "/home/sundevice/public_html/modules/posstaticfooter/views/templates/admin/helpers/tree/tree_header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:15881092135ccc3de4622638-22199099%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9c3dcf5ce360e1112a33efb5ee9359f562827700' => 
    array (
      0 => '/home/sundevice/public_html/modules/posstaticfooter/views/templates/admin/helpers/tree/tree_header.tpl',
      1 => 1534502030,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15881092135ccc3de4622638-22199099',
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
  'unifunc' => 'content_5ccc3de4627ec6_94990762',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5ccc3de4627ec6_94990762')) {function content_5ccc3de4627ec6_94990762($_smarty_tpl) {?>
<div class="tree-panel-heading-controls clearfix">
	<?php if (isset($_smarty_tpl->tpl_vars['title']->value)) {?><i class="icon-tag"></i>&nbsp;<?php echo smartyTranslate(array('s'=>$_smarty_tpl->tpl_vars['title']->value),$_smarty_tpl);?>
<?php }?>
	<?php if (isset($_smarty_tpl->tpl_vars['toolbar']->value)) {?><?php echo $_smarty_tpl->tpl_vars['toolbar']->value;?>
<?php }?>
</div><?php }} ?>
