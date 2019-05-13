<?php /* Smarty version Smarty-3.1.19, created on 2019-05-07 21:57:56
         compiled from "/home/sundevice/preprod/modules/blocklayeredcustom/views/templates/hook/attribute_groups_list.tpl" */ ?>
<?php /*%%SmartyHeaderCode:112525845cd1e344ed1770-84020591%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e06ff0a3b61fcfc9a7100db5de9a001119a49131' => 
    array (
      0 => '/home/sundevice/preprod/modules/blocklayeredcustom/views/templates/hook/attribute_groups_list.tpl',
      1 => 1525408906,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '112525845cd1e344ed1770-84020591',
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
  'unifunc' => 'content_5cd1e344ee0d68_81205255',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cd1e344ee0d68_81205255')) {function content_5cd1e344ee0d68_81205255($_smarty_tpl) {?>
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
