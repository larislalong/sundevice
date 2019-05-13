<?php /* Smarty version Smarty-3.1.19, created on 2019-05-07 21:57:56
         compiled from "/home/sundevice/preprod/modules/blocklayeredcustom/views/templates/hook/selectables_slider.tpl" */ ?>
<?php /*%%SmartyHeaderCode:12221311375cd1e34488f098-78849265%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '81e873134de992ae804bd2af30f3f63e26422795' => 
    array (
      0 => '/home/sundevice/preprod/modules/blocklayeredcustom/views/templates/hook/selectables_slider.tpl',
      1 => 1525408914,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12221311375cd1e34488f098-78849265',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'blockFilter' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cd1e34489b1f3_68969712',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cd1e34489b1f3_68969712')) {function content_5cd1e34489b1f3_68969712($_smarty_tpl) {?>

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
