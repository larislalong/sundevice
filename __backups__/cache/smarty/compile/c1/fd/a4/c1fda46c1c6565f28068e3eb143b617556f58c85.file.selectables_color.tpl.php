<?php /* Smarty version Smarty-3.1.19, created on 2019-02-22 14:54:34
         compiled from "D:\wamp\www\projects\ps\sun-device.local\modules\blocklayeredcustom\views\templates\hook\selectables_color.tpl" */ ?>
<?php /*%%SmartyHeaderCode:155325c6fff1a4eeee0-57254633%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c1fda46c1c6565f28068e3eb143b617556f58c85' => 
    array (
      0 => 'D:\\wamp\\www\\projects\\ps\\sun-device.local\\modules\\blocklayeredcustom\\views\\templates\\hook\\selectables_color.tpl',
      1 => 1536861463,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '155325c6fff1a4eeee0-57254633',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'blockFilter' => 0,
    'img_ps_dir' => 0,
    'selectables' => 0,
    'maxFilterItems' => 0,
    'i' => 0,
    'attr_img' => 0,
    'showProductQuantity' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c6fff1a5fcee8_97036150',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c6fff1a5fcee8_97036150')) {function content_5c6fff1a5fcee8_97036150($_smarty_tpl) {?>
<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_variable(1, null, 0);?>
<?php  $_smarty_tpl->tpl_vars['selectables'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['selectables']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['blockFilter']->value['selectables']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['selectables']->key => $_smarty_tpl->tpl_vars['selectables']->value) {
$_smarty_tpl->tpl_vars['selectables']->_loop = true;
?>
<?php $_smarty_tpl->tpl_vars['attr_img'] = new Smarty_variable(($_smarty_tpl->tpl_vars['img_ps_dir']->value).('co/').($_smarty_tpl->tpl_vars['selectables']->value['id_attribute']).('.jpg'), null, 0);?>
<div class="selectable-item<?php if (in_array($_smarty_tpl->tpl_vars['selectables']->value['value'],$_smarty_tpl->tpl_vars['blockFilter']->value['selecteds'])) {?> selected<?php }?><?php if (($_smarty_tpl->tpl_vars['maxFilterItems']->value>0)&&($_smarty_tpl->tpl_vars['i']->value>$_smarty_tpl->tpl_vars['maxFilterItems']->value)) {?> unvisible<?php }?>">
	<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_variable($_smarty_tpl->tpl_vars['i']->value+1, null, 0);?>
	<input id="selectable_<?php echo $_smarty_tpl->tpl_vars['blockFilter']->value['block_type'];?>
_<?php echo $_smarty_tpl->tpl_vars['selectables']->value['value'];?>
" class="selectable-field selectable-field-color" type="button" value="<?php echo $_smarty_tpl->tpl_vars['selectables']->value['value'];?>
" style="background-color:<?php echo $_smarty_tpl->tpl_vars['selectables']->value['color'];?>
;color:<?php echo $_smarty_tpl->tpl_vars['selectables']->value['color'];?>
;<?php if (file_get_contents($_smarty_tpl->tpl_vars['attr_img']->value)) {?>background-image:url('<?php echo $_smarty_tpl->tpl_vars['attr_img']->value;?>
');background-repeat:no-repeat;background-size:100%;font-size:0;background-position-y: -2px;<?php }?>"/>
	<label for="selectable_<?php echo $_smarty_tpl->tpl_vars['blockFilter']->value['block_type'];?>
_<?php echo $_smarty_tpl->tpl_vars['selectables']->value['value'];?>
"><?php echo $_smarty_tpl->tpl_vars['selectables']->value['title'];?>
 <?php if (isset($_smarty_tpl->tpl_vars['selectables']->value['count'])&&$_smarty_tpl->tpl_vars['showProductQuantity']->value) {?> (<?php echo intval($_smarty_tpl->tpl_vars['selectables']->value['count']);?>
)<?php }?></label>
</div>
<?php } ?><?php }} ?>
