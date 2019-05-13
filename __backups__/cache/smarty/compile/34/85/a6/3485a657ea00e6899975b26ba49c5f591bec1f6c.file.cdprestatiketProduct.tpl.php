<?php /* Smarty version Smarty-3.1.19, created on 2019-01-29 11:42:45
         compiled from "/home/sundevice/public_html/modules/cdprestatiket/views/templates/hook/cdprestatiketProduct.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1943370085c502e2559d4e7-90265536%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
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
  'nocache_hash' => '1943370085c502e2559d4e7-90265536',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'cdpt_controller11' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c502e255a3a56_89900041',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c502e255a3a56_89900041')) {function content_5c502e255a3a56_89900041($_smarty_tpl) {?>

<?php if ($_smarty_tpl->tpl_vars['cdpt_controller11']->value) {?>
	<a class="btn btn-primary btn-lg" href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['cdpt_controller11']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" title="<?php echo smartyTranslate(array('s'=>'Send a new Ticket','mod'=>'cdprestatiket'),$_smarty_tpl);?>
"><span><?php echo smartyTranslate(array('s'=>'Send a new Ticket','mod'=>'cdprestatiket'),$_smarty_tpl);?>
</span></a>
<?php }?><?php }} ?>
