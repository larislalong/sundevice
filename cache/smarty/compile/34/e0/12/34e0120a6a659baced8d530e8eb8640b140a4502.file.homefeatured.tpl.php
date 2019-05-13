<?php /* Smarty version Smarty-3.1.19, created on 2019-05-13 10:04:08
         compiled from "/home/sundevice/preprod/themes/pos_ruby5/modules/homefeatured/homefeatured.tpl" */ ?>
<?php /*%%SmartyHeaderCode:16708465665cd1e344521f01-38616358%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '34e0120a6a659baced8d530e8eb8640b140a4502' => 
    array (
      0 => '/home/sundevice/preprod/themes/pos_ruby5/modules/homefeatured/homefeatured.tpl',
      1 => 1557734604,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16708465665cd1e344521f01-38616358',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cd1e34452dcd6_26727472',
  'variables' => 
  array (
    'products' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cd1e34452dcd6_26727472')) {function content_5cd1e34452dcd6_26727472($_smarty_tpl) {?>
<div id="homefeatured" class="homefeatured home-product-block col-xs-12 col-sm-6 col-lg-8">
<h4 class="block-title"><?php echo smartyTranslate(array('s'=>'Produits Phares'),$_smarty_tpl);?>
</h4>
<?php if (isset($_smarty_tpl->tpl_vars['products']->value)&&$_smarty_tpl->tpl_vars['products']->value) {?>
	<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./product-list.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('class'=>'homefeatured tab-pane','id'=>'homefeatured','has_big_item'=>true), 0);?>

<?php } else { ?>
	<ul class="homefeatured">
		<li class="alert alert-info"><?php echo smartyTranslate(array('s'=>'No featured products at this time.','mod'=>'homefeatured'),$_smarty_tpl);?>
</li>
	</ul>
<?php }?>
</div><?php }} ?>
