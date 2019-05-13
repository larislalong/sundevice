<?php /* Smarty version Smarty-3.1.19, created on 2019-02-22 14:54:34
         compiled from "D:\wamp\www\projects\ps\sun-device.local\modules\blocklayeredcustom\views\templates\hook\selectables_slider.tpl" */ ?>
<?php /*%%SmartyHeaderCode:313275c6fff1a365943-39504624%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c390b75482f8992bae425a39f8a6278cd60ac953' => 
    array (
      0 => 'D:\\wamp\\www\\projects\\ps\\sun-device.local\\modules\\blocklayeredcustom\\views\\templates\\hook\\selectables_slider.tpl',
      1 => 1525408914,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '313275c6fff1a365943-39504624',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'blockFilter' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c6fff1a3f0cd3_73004273',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c6fff1a3f0cd3_73004273')) {function content_5c6fff1a3f0cd3_73004273($_smarty_tpl) {?>

<div class="blc-slider" data-type="<?php echo $_smarty_tpl->tpl_vars['blockFilter']->value['block_type'];?>
" data-format="<?php echo $_smarty_tpl->tpl_vars['blockFilter']->value['format'];?>
" data-unit="<?php echo $_smarty_tpl->tpl_vars['blockFilter']->value['unit'];?>
"
	data-min="<?php echo $_smarty_tpl->tpl_vars['blockFilter']->value['selectables']['min'];?>
" data-max="<?php echo $_smarty_tpl->tpl_vars['blockFilter']->value['selectables']['max'];?>
" data-unit="<?php echo $_smarty_tpl->tpl_vars['blockFilter']->value['unit'];?>
" 
	data-values="<?php echo implode(',',$_smarty_tpl->tpl_vars['blockFilter']->value['selectables']['values']);?>
">
	<label for="<?php echo $_smarty_tpl->tpl_vars['blockFilter']->value['block_type'];?>
">
		<?php echo smartyTranslate(array('s'=>'Range:','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>

	</label> 
	<span id="layered_<?php echo $_smarty_tpl->tpl_vars['blockFilter']->value['block_type'];?>
_range"></span>
	<div class="layered_slider_container">
		<div class="layered_slider" id="layered_<?php echo $_smarty_tpl->tpl_vars['blockFilter']->value['block_type'];?>
_slider" data-type="<?php echo $_smarty_tpl->tpl_vars['blockFilter']->value['block_type'];?>
" data-format="<?php echo $_smarty_tpl->tpl_vars['blockFilter']->value['format'];?>
" data-unit="<?php echo $_smarty_tpl->tpl_vars['blockFilter']->value['unit'];?>
"></div>
	</div>
</div><?php }} ?>
