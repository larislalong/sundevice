<?php /* Smarty version Smarty-3.1.19, created on 2019-04-29 16:37:52
         compiled from "/home/sundevice/public_html/modules/cdprestatiket/views/templates/hook/cdprestatiketProduct.tpl" */ ?>
<?php /*%%SmartyHeaderCode:8505262275cc70c401fcb39-80176325%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3485a657ea00e6899975b26ba49c5f591bec1f6c' => 
    array (
      0 => '/home/sundevice/public_html/modules/cdprestatiket/views/templates/hook/cdprestatiketProduct.tpl',
      1 => 1438692226,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8505262275cc70c401fcb39-80176325',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'cdpt_controller11' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cc70c40200da8_15147493',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cc70c40200da8_15147493')) {function content_5cc70c40200da8_15147493($_smarty_tpl) {?>

<?php if ($_smarty_tpl->tpl_vars['cdpt_controller11']->value) {?>
	<a class="btn btn-primary btn-lg" href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['cdpt_controller11']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" title="<?php echo smartyTranslate(array('s'=>'Send a new Ticket','mod'=>'cdprestatiket'),$_smarty_tpl);?>
"><span><?php echo smartyTranslate(array('s'=>'Send a new Ticket','mod'=>'cdprestatiket'),$_smarty_tpl);?>
</span></a>
<?php }?><?php }} ?>
