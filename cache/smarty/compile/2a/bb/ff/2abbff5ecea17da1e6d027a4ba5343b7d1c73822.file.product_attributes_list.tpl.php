<?php /* Smarty version Smarty-3.1.19, created on 2019-04-29 16:36:46
         compiled from "/home/sundevice/public_html/modules/blocklayeredcustom/views/templates/hook/product_attributes_list.tpl" */ ?>
<?php /*%%SmartyHeaderCode:6273464385cc70bfe19ba36-32197849%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
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
  'nocache_hash' => '6273464385cc70bfe19ba36-32197849',
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
  'unifunc' => 'content_5cc70bfe1a20b1_40657089',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cc70bfe1a20b1_40657089')) {function content_5cc70bfe1a20b1_40657089($_smarty_tpl) {?>
<input class="hasMoreCombinations" value="<?php echo intval($_smarty_tpl->tpl_vars['hasMoreCombinations']->value);?>
" type="hidden"/>

<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./product-list.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('products'=>$_smarty_tpl->tpl_vars['combinations']->value,'id'=>"home-block-product-list",'show_as_grid'=>true,'show_on_home'=>true), 0);?>
<?php }} ?>
