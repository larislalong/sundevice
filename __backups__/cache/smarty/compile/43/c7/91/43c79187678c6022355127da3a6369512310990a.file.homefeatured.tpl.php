<?php /* Smarty version Smarty-3.1.19, created on 2019-01-29 11:26:57
         compiled from "/home/sundevice/public_html/themes/pos_ruby5/modules/homefeatured/homefeatured.tpl" */ ?>
<?php /*%%SmartyHeaderCode:15095859965c502a71e75e65-52498278%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '43c79187678c6022355127da3a6369512310990a' => 
    array (
      0 => '/home/sundevice/public_html/themes/pos_ruby5/modules/homefeatured/homefeatured.tpl',
      1 => 1534502020,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15095859965c502a71e75e65-52498278',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'products' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c502a71eaf0e8_43648025',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c502a71eaf0e8_43648025')) {function content_5c502a71eaf0e8_43648025($_smarty_tpl) {?>
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
