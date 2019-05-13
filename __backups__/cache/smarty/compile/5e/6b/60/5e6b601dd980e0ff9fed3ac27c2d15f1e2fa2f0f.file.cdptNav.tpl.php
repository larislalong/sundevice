<?php /* Smarty version Smarty-3.1.19, created on 2019-01-29 11:26:58
         compiled from "/home/sundevice/public_html/modules/cdprestatiket/views/templates/hook/cdptNav.tpl" */ ?>
<?php /*%%SmartyHeaderCode:8801706935c502a72719cf8-42914700%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5e6b601dd980e0ff9fed3ac27c2d15f1e2fa2f0f' => 
    array (
      0 => '/home/sundevice/public_html/modules/cdprestatiket/views/templates/hook/cdptNav.tpl',
      1 => 1438692232,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8801706935c502a72719cf8-42914700',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'cdpt_controller12' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c502a7272a962_50304618',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c502a7272a962_50304618')) {function content_5c502a7272a962_50304618($_smarty_tpl) {?>

<?php if ($_smarty_tpl->tpl_vars['cdpt_controller12']->value) {?>
	<div class="header_user_info"><a href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['cdpt_controller12']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" title="<?php echo smartyTranslate(array('s'=>'New Ticket','mod'=>'cdprestatiket'),$_smarty_tpl);?>
"><span><?php echo smartyTranslate(array('s'=>'New Ticket','mod'=>'cdprestatiket'),$_smarty_tpl);?>
</span></a></div>
<?php }?><?php }} ?>
