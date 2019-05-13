<?php /* Smarty version Smarty-3.1.19, created on 2019-04-29 17:10:50
         compiled from "/home/sundevice/public_html/modules/blocklayeredcustom/views/templates/hook/others_sort_block.tpl" */ ?>
<?php /*%%SmartyHeaderCode:10507387315cc713fa103236-72551240%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '197eebb5001124c647f165c75d5a5c620fa51129' => 
    array (
      0 => '/home/sundevice/public_html/modules/blocklayeredcustom/views/templates/hook/others_sort_block.tpl',
      1 => 1536939581,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '10507387315cc713fa103236-72551240',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'othersSort' => 0,
    'selectedOrderColumnType' => 0,
    'columnType' => 0,
    'columnLabel' => 0,
    'selectedOrderWay' => 0,
    'orderWayConst' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cc713fa11d5c5_10514870',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cc713fa11d5c5_10514870')) {function content_5cc713fa11d5c5_10514870($_smarty_tpl) {?>
<?php  $_smarty_tpl->tpl_vars['columnLabel'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['columnLabel']->_loop = false;
 $_smarty_tpl->tpl_vars['columnType'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['othersSort']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['columnLabel']->key => $_smarty_tpl->tpl_vars['columnLabel']->value) {
$_smarty_tpl->tpl_vars['columnLabel']->_loop = true;
 $_smarty_tpl->tpl_vars['columnType']->value = $_smarty_tpl->tpl_vars['columnLabel']->key;
?>
<div  
	class="column-sort-item other-sort-item <?php if ($_smarty_tpl->tpl_vars['selectedOrderColumnType']->value==$_smarty_tpl->tpl_vars['columnType']->value) {?> active<?php }?>" 
	data-column-type="<?php echo intval($_smarty_tpl->tpl_vars['columnType']->value);?>
" data-id-column="0">
	<span class="attributes_label"><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['truncate'][0][0]->smarty_modifier_truncate(strip_tags($_smarty_tpl->tpl_vars['columnLabel']->value),10,'...',true);?>
</span>
	<span class="up_down_attr">
		<span class="icone_up_attr column-sort-trigger <?php if (($_smarty_tpl->tpl_vars['selectedOrderColumnType']->value==$_smarty_tpl->tpl_vars['columnType']->value)&&($_smarty_tpl->tpl_vars['selectedOrderWay']->value==$_smarty_tpl->tpl_vars['orderWayConst']->value['ORDER_WAY_ASC'])) {?> active<?php }?>" data-order-way="<?php echo $_smarty_tpl->tpl_vars['orderWayConst']->value['ORDER_WAY_ASC'];?>
">
			<i class="fa fa-caret-up" aria-hidden="true"></i>
		</span>
		<span class="icone_down_attr column-sort-trigger <?php if (($_smarty_tpl->tpl_vars['selectedOrderColumnType']->value==$_smarty_tpl->tpl_vars['columnType']->value)&&($_smarty_tpl->tpl_vars['selectedOrderWay']->value==$_smarty_tpl->tpl_vars['orderWayConst']->value['ORDER_WAY_DESC'])) {?> active<?php }?>" data-order-way="<?php echo $_smarty_tpl->tpl_vars['orderWayConst']->value['ORDER_WAY_DESC'];?>
">
			<i class="fa fa-caret-down" aria-hidden="true"></i>
		</span>
	</span>
</div>
<?php } ?><?php }} ?>
