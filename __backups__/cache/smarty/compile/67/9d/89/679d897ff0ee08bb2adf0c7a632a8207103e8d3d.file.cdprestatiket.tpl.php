<?php /* Smarty version Smarty-3.1.19, created on 2019-01-29 11:27:03
         compiled from "/home/sundevice/public_html/modules/cdprestatiket/views/templates/hook/cdprestatiket.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2710046965c502a772455b5-07896652%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '679d897ff0ee08bb2adf0c7a632a8207103e8d3d' => 
    array (
      0 => '/home/sundevice/public_html/modules/cdprestatiket/views/templates/hook/cdprestatiket.tpl',
      1 => 1535710196,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2710046965c502a772455b5-07896652',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'cdpt_controller' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c502a7724dfc2_98835138',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c502a7724dfc2_98835138')) {function content_5c502a7724dfc2_98835138($_smarty_tpl) {?>

<?php if ($_smarty_tpl->tpl_vars['cdpt_controller']->value) {?>

		<ul class="myaccount-link-list">
			<li><a href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['cdpt_controller']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" title="<?php echo smartyTranslate(array('s'=>'My Ticket','mod'=>'cdprestatiket'),$_smarty_tpl);?>
"><i class="icon-building"></i><span><?php echo smartyTranslate(array('s'=>'My Ticket','mod'=>'cdprestatiket'),$_smarty_tpl);?>
</span></a></li>
		</ul>
<?php }?>
<?php }} ?>
