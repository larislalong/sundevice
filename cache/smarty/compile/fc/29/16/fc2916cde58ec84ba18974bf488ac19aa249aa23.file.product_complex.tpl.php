<?php /* Smarty version Smarty-3.1.19, created on 2019-05-07 21:57:57
         compiled from "/home/sundevice/preprod/modules/menupro/views/templates/hook/product_complex.tpl" */ ?>
<?php /*%%SmartyHeaderCode:8975182855cd1e3453ece70-76554409%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fc2916cde58ec84ba18974bf488ac19aa249aa23' => 
    array (
      0 => '/home/sundevice/preprod/modules/menupro/views/templates/hook/product_complex.tpl',
      1 => 1536062028,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8975182855cd1e3453ece70-76554409',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'ps_version' => 0,
    'complex_products' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cd1e3453f9511_67402976',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cd1e3453f9511_67402976')) {function content_5cd1e3453f9511_67402976($_smarty_tpl) {?>

<div class="mp-product-complex <?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?><?php } else { ?>mp-product-complex-15<?php }?>">
	<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./product-list.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('products'=>$_smarty_tpl->tpl_vars['complex_products']->value,'show_as_grid'=>1,'show_on_home'=>0,'show_in_menu'=>1), 0);?>

</div><?php }} ?>
