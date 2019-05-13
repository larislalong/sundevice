<?php /* Smarty version Smarty-3.1.19, created on 2019-01-29 11:26:58
         compiled from "/home/sundevice/public_html/modules/blocklayeredcustom/views/templates/hook/product_attributes_list.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2327565655c502a72660cc4-83222360%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2abbff5ecea17da1e6d027a4ba5343b7d1c73822' => 
    array (
      0 => '/home/sundevice/public_html/modules/blocklayeredcustom/views/templates/hook/product_attributes_list.tpl',
      1 => 1536815626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2327565655c502a72660cc4-83222360',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'hasMoreCombinations' => 0,
    'combinations' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c502a72668b23_45055033',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c502a72668b23_45055033')) {function content_5c502a72668b23_45055033($_smarty_tpl) {?>
<input class="hasMoreCombinations" value="<?php echo intval($_smarty_tpl->tpl_vars['hasMoreCombinations']->value);?>
" type="hidden"/>

<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./product-list.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('products'=>$_smarty_tpl->tpl_vars['combinations']->value,'id'=>"home-block-product-list",'show_as_grid'=>true,'show_on_home'=>true), 0);?>
<?php }} ?>
