<?php /* Smarty version Smarty-3.1.19, created on 2019-01-29 11:26:58
         compiled from "/home/sundevice/public_html/modules/blocklayeredcustom/views/templates/hook/selectables_checkbox.tpl" */ ?>
<?php /*%%SmartyHeaderCode:8502898075c502a721e73b3-88876116%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '606279b37990f5f47e7597608bc9787e0852263f' => 
    array (
      0 => '/home/sundevice/public_html/modules/blocklayeredcustom/views/templates/hook/selectables_checkbox.tpl',
      1 => 1525408910,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8502898075c502a721e73b3-88876116',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'blockFilter' => 0,
    'maxFilterItems' => 0,
    'i' => 0,
    'selectables' => 0,
    'showProductQuantity' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c502a721feaf8_82443722',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c502a721feaf8_82443722')) {function content_5c502a721feaf8_82443722($_smarty_tpl) {?>

<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_variable(1, null, 0);?>
<?php  $_smarty_tpl->tpl_vars['selectables'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['selectables']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['blockFilter']->value['selectables']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['selectables']->key => $_smarty_tpl->tpl_vars['selectables']->value) {
$_smarty_tpl->tpl_vars['selectables']->_loop = true;
?>
<div class="selectable-item<?php if (($_smarty_tpl->tpl_vars['maxFilterItems']->value>0)&&($_smarty_tpl->tpl_vars['i']->value>$_smarty_tpl->tpl_vars['maxFilterItems']->value)) {?> unvisible<?php }?>">
	<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_variable($_smarty_tpl->tpl_vars['i']->value+1, null, 0);?>
	<input id="selectable_<?php echo $_smarty_tpl->tpl_vars['blockFilter']->value['block_type'];?>
_<?php echo $_smarty_tpl->tpl_vars['selectables']->value['value'];?>
" class="selectable-field selectable-field-checkbox" type="checkbox" value="<?php echo $_smarty_tpl->tpl_vars['selectables']->value['value'];?>
" <?php if (in_array($_smarty_tpl->tpl_vars['selectables']->value['value'],$_smarty_tpl->tpl_vars['blockFilter']->value['selecteds'])) {?>checked<?php }?>/>
	<label for="selectable_<?php echo $_smarty_tpl->tpl_vars['blockFilter']->value['block_type'];?>
_<?php echo $_smarty_tpl->tpl_vars['selectables']->value['value'];?>
"><?php echo $_smarty_tpl->tpl_vars['selectables']->value['title'];?>
<?php if (isset($_smarty_tpl->tpl_vars['selectables']->value['count'])&&$_smarty_tpl->tpl_vars['showProductQuantity']->value) {?> (<?php echo intval($_smarty_tpl->tpl_vars['selectables']->value['count']);?>
)<?php }?></label>
</div>
<?php } ?><?php }} ?>
