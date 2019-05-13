<?php /* Smarty version Smarty-3.1.19, created on 2019-05-07 21:59:05
         compiled from "/home/sundevice/preprod/modules/cdprestatiket/views/templates/hook/cdprestatiketProduct.tpl" */ ?>
<?php /*%%SmartyHeaderCode:21111614185cd1e38969a2a2-94305208%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c93034afcfe20d3d5a553b199927b3645ac1a993' => 
    array (
      0 => '/home/sundevice/preprod/modules/cdprestatiket/views/templates/hook/cdprestatiketProduct.tpl',
      1 => 1438692226,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '21111614185cd1e38969a2a2-94305208',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'cdpt_controller11' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cd1e38969fa58_60427596',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cd1e38969fa58_60427596')) {function content_5cd1e38969fa58_60427596($_smarty_tpl) {?>

<?php if ($_smarty_tpl->tpl_vars['cdpt_controller11']->value) {?>
	<a class="btn btn-primary btn-lg" href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['cdpt_controller11']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" title="<?php echo smartyTranslate(array('s'=>'Send a new Ticket','mod'=>'cdprestatiket'),$_smarty_tpl);?>
"><span><?php echo smartyTranslate(array('s'=>'Send a new Ticket','mod'=>'cdprestatiket'),$_smarty_tpl);?>
</span></a>
<?php }?><?php }} ?>
