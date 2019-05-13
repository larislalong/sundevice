<?php /* Smarty version Smarty-3.1.19, created on 2019-05-07 21:57:56
         compiled from "/home/sundevice/preprod/modules/blocklayeredcustom/views/templates/hook/product_attributes_list.tpl" */ ?>
<?php /*%%SmartyHeaderCode:474545095cd1e344f1e4f4-55981651%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a450571aa715c723dab36f981b3c2fbffd2e8d50' => 
    array (
      0 => '/home/sundevice/preprod/modules/blocklayeredcustom/views/templates/hook/product_attributes_list.tpl',
      1 => 1536815626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '474545095cd1e344f1e4f4-55981651',
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
  'unifunc' => 'content_5cd1e34500b725_77915698',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cd1e34500b725_77915698')) {function content_5cd1e34500b725_77915698($_smarty_tpl) {?>
<input class="hasMoreCombinations" value="<?php echo intval($_smarty_tpl->tpl_vars['hasMoreCombinations']->value);?>
" type="hidden"/>

<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./product-list.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('products'=>$_smarty_tpl->tpl_vars['combinations']->value,'id'=>"home-block-product-list",'show_as_grid'=>true,'show_on_home'=>true), 0);?>
<?php }} ?>
