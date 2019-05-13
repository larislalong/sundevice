<?php /* Smarty version Smarty-3.1.19, created on 2019-05-13 10:04:08
         compiled from "/home/sundevice/preprod/themes/pos_ruby5/modules/blocknewproducts/blocknewproducts_home.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1052898725cd1e3445d2925-27055740%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3cbbe83f586d106ffab48b48700d8a91c7b17710' => 
    array (
      0 => '/home/sundevice/preprod/themes/pos_ruby5/modules/blocknewproducts/blocknewproducts_home.tpl',
      1 => 1557734605,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1052898725cd1e3445d2925-27055740',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cd1e3445d9565_97623249',
  'variables' => 
  array (
    'new_products' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cd1e3445d9565_97623249')) {function content_5cd1e3445d9565_97623249($_smarty_tpl) {?>
<div id="blocknewproducts" class="blocknewproducts home-product-block col-xs-12 col-sm-6 col-lg-4">
<h4 class="block-title"><?php echo smartyTranslate(array('s'=>'Nouveaux Produits'),$_smarty_tpl);?>
</h4>
<?php if (isset($_smarty_tpl->tpl_vars['new_products']->value)&&$_smarty_tpl->tpl_vars['new_products']->value) {?>
	<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./product-list.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('products'=>$_smarty_tpl->tpl_vars['new_products']->value,'class'=>'blocknewproducts tab-pane','id'=>'blocknewproducts'), 0);?>

<?php } else { ?>
	<ul class="blocknewproducts">
		<li class="alert alert-info"><?php echo smartyTranslate(array('s'=>'No new products at this time.','mod'=>'blocknewproducts'),$_smarty_tpl);?>
</li>
	</ul>
<?php }?>
</div><?php }} ?>
