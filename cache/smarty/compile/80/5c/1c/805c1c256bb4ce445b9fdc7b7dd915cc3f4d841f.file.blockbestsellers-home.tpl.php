<?php /* Smarty version Smarty-3.1.19, created on 2019-05-13 10:04:08
         compiled from "/home/sundevice/preprod/themes/pos_ruby5/modules/blockbestsellers/blockbestsellers-home.tpl" */ ?>
<?php /*%%SmartyHeaderCode:9368619305cd1e3445f9de4-54365548%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '805c1c256bb4ce445b9fdc7b7dd915cc3f4d841f' => 
    array (
      0 => '/home/sundevice/preprod/themes/pos_ruby5/modules/blockbestsellers/blockbestsellers-home.tpl',
      1 => 1557734603,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9368619305cd1e3445f9de4-54365548',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cd1e344600be9_86398741',
  'variables' => 
  array (
    'best_sellers' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cd1e344600be9_86398741')) {function content_5cd1e344600be9_86398741($_smarty_tpl) {?>
<div id="blockbestsellers" class="blockbestsellers home-product-block col-xs-12 col-sm-6 col-lg-8">
<h4 class="block-title"><?php echo smartyTranslate(array('s'=>'Meilleures Ventes'),$_smarty_tpl);?>
</h4>
<?php if (isset($_smarty_tpl->tpl_vars['best_sellers']->value)&&$_smarty_tpl->tpl_vars['best_sellers']->value) {?>
	<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./product-list.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('products'=>$_smarty_tpl->tpl_vars['best_sellers']->value,'class'=>'blockbestsellers tab-pane','id'=>'blockbestsellers','has_big_item'=>true), 0);?>

<?php } else { ?>
	<ul class="blockbestsellers">
		<li class="alert alert-info"><?php echo smartyTranslate(array('s'=>'No best sellers at this time.','mod'=>'blockbestsellers'),$_smarty_tpl);?>
</li>
	</ul>
<?php }?>
</div>
<?php }} ?>
