<?php /* Smarty version Smarty-3.1.19, created on 2019-02-22 14:54:32
         compiled from "D:\wamp\www\projects\ps\sun-device.local\modules\posthemeoption\posthemeoption.tpl" */ ?>
<?php /*%%SmartyHeaderCode:316275c6fff18a9e057-28648673%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '777fe3d9dccd84f0ac9dc4cc1eef91bfb88a78b5' => 
    array (
      0 => 'D:\\wamp\\www\\projects\\ps\\sun-device.local\\modules\\posthemeoption\\posthemeoption.tpl',
      1 => 1534502030,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '316275c6fff18a9e057-28648673',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'titlefont' => 0,
    'linkfont' => 0,
    'fontnametitle' => 0,
    'fontnamelink' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c6fff18b36397_28195422',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c6fff18b36397_28195422')) {function content_5c6fff18b36397_28195422($_smarty_tpl) {?><?php if (isset($_smarty_tpl->tpl_vars['titlefont']->value)&&$_smarty_tpl->tpl_vars['titlefont']->value) {?> <?php echo html_entity_decode($_smarty_tpl->tpl_vars['titlefont']->value);?>
 <?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['linkfont']->value)&&$_smarty_tpl->tpl_vars['linkfont']->value) {?> <?php echo html_entity_decode($_smarty_tpl->tpl_vars['linkfont']->value);?>
 <?php }?>
<style type="text/css">
	
		<?php if (isset($_smarty_tpl->tpl_vars['titlefont']->value)&&$_smarty_tpl->tpl_vars['titlefont']->value) {?>
			body,
			h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6{
			font-family:<?php echo $_smarty_tpl->tpl_vars['fontnametitle']->value;?>
;
			} 
			.fontcustom1{
			font-family:<?php echo $_smarty_tpl->tpl_vars['fontnametitle']->value;?>
 !important;
			}
		<?php }?>
		<?php if (isset($_smarty_tpl->tpl_vars['linkfont']->value)&&$_smarty_tpl->tpl_vars['linkfont']->value) {?>
			body {
			font-family:<?php echo $_smarty_tpl->tpl_vars['fontnamelink']->value;?>
;
			}
			.fontcustom2{
			font-family:<?php echo $_smarty_tpl->tpl_vars['fontnamelink']->value;?>
 !important;
			}
		<?php }?>
	
	
</style><?php }} ?>
