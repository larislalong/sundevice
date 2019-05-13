<?php /* Smarty version Smarty-3.1.19, created on 2019-02-22 14:54:35
         compiled from "D:\wamp\www\projects\ps\sun-device.local\modules\blocklayeredcustom\views\templates\hook\product_attributes_list.tpl" */ ?>
<?php /*%%SmartyHeaderCode:239315c6fff1b1d3357-35534501%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e6febec1fe6748ef52b5b036697f05b762cd5410' => 
    array (
      0 => 'D:\\wamp\\www\\projects\\ps\\sun-device.local\\modules\\blocklayeredcustom\\views\\templates\\hook\\product_attributes_list.tpl',
      1 => 1536815626,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '239315c6fff1b1d3357-35534501',
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
  'unifunc' => 'content_5c6fff1b20db70_04420637',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c6fff1b20db70_04420637')) {function content_5c6fff1b20db70_04420637($_smarty_tpl) {?>
<input class="hasMoreCombinations" value="<?php echo intval($_smarty_tpl->tpl_vars['hasMoreCombinations']->value);?>
" type="hidden"/>

<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./product-list.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('products'=>$_smarty_tpl->tpl_vars['combinations']->value,'id'=>"home-block-product-list",'show_as_grid'=>true,'show_on_home'=>true), 0);?>
<?php }} ?>
