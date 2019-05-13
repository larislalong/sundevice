<?php /* Smarty version Smarty-3.1.19, created on 2019-02-22 14:54:35
         compiled from "D:\wamp\www\projects\ps\sun-device.local\modules\blocklayeredcustom\views\templates\hook\attribute_groups_list.tpl" */ ?>
<?php /*%%SmartyHeaderCode:289385c6fff1b018848-66912680%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a0c55ef991570f819a2ef2822e466e50f6c7a390' => 
    array (
      0 => 'D:\\wamp\\www\\projects\\ps\\sun-device.local\\modules\\blocklayeredcustom\\views\\templates\\hook\\attribute_groups_list.tpl',
      1 => 1525408906,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '289385c6fff1b018848-66912680',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'groups' => 0,
    'group' => 0,
    'selectedOrderColumn' => 0,
    'orderColumnTypeConst' => 0,
    'selectedOrderWay' => 0,
    'orderWayConst' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c6fff1b0f7775_13335299',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c6fff1b0f7775_13335299')) {function content_5c6fff1b0f7775_13335299($_smarty_tpl) {?>
<?php  $_smarty_tpl->tpl_vars['group'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['group']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['groups']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['group']->key => $_smarty_tpl->tpl_vars['group']->value) {
$_smarty_tpl->tpl_vars['group']->_loop = true;
?>
	<div  
		class="attributes-group-item column-sort-item <?php if ($_smarty_tpl->tpl_vars['group']->value['id_attribute_group']==$_smarty_tpl->tpl_vars['selectedOrderColumn']->value) {?> active<?php }?>" 
		data-id-column="<?php echo intval($_smarty_tpl->tpl_vars['group']->value['id_attribute_group']);?>
" data-column-type="<?php echo intval($_smarty_tpl->tpl_vars['orderColumnTypeConst']->value['ORDER_COLUMN_TYPE_ATTRIBUTE_GROUP']);?>
">
        <span class="attributes_label"><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['truncate'][0][0]->smarty_modifier_truncate(strip_tags($_smarty_tpl->tpl_vars['group']->value['name']),10,'...',true);?>
</span>
        <span class="up_down_attr">
            <span class="icone_up_attr column-sort-trigger attributes-sort-trigger <?php if (($_smarty_tpl->tpl_vars['group']->value['id_attribute_group']==$_smarty_tpl->tpl_vars['selectedOrderColumn']->value)&&($_smarty_tpl->tpl_vars['selectedOrderWay']->value==$_smarty_tpl->tpl_vars['orderWayConst']->value['ORDER_WAY_ASC'])) {?> active<?php }?>" data-order-way="<?php echo $_smarty_tpl->tpl_vars['orderWayConst']->value['ORDER_WAY_ASC'];?>
">
				<i class="fa fa-caret-up" aria-hidden="true"></i>
			</span>
            <span class="icone_down_attr column-sort-trigger attributes-sort-trigger <?php if (($_smarty_tpl->tpl_vars['group']->value['id_attribute_group']==$_smarty_tpl->tpl_vars['selectedOrderColumn']->value)&&($_smarty_tpl->tpl_vars['selectedOrderWay']->value==$_smarty_tpl->tpl_vars['orderWayConst']->value['ORDER_WAY_DESC'])) {?> active<?php }?>" data-order-way="<?php echo $_smarty_tpl->tpl_vars['orderWayConst']->value['ORDER_WAY_DESC'];?>
">
				<i class="fa fa-caret-down" aria-hidden="true"></i>
			</span>
        </span>
	</div>
<?php } ?><?php }} ?>
