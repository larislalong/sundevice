<?php /* Smarty version Smarty-3.1.19, created on 2019-04-29 16:36:45
         compiled from "/home/sundevice/public_html/themes/pos_ruby5/modules/blocknewproducts/blocknewproducts_home.tpl" */ ?>
<?php /*%%SmartyHeaderCode:20035073565cc70bfd8bec25-73884067%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '65f91c365a015c6c611c7da53c96779b5af04c43' => 
    array (
      0 => '/home/sundevice/public_html/themes/pos_ruby5/modules/blocknewproducts/blocknewproducts_home.tpl',
      1 => 1534502018,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20035073565cc70bfd8bec25-73884067',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'new_products' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cc70bfd8c43e9_72320495',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cc70bfd8c43e9_72320495')) {function content_5cc70bfd8c43e9_72320495($_smarty_tpl) {?>
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
