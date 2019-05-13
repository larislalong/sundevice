<?php /* Smarty version Smarty-3.1.19, created on 2019-02-08 15:01:48
         compiled from "D:\wamp\www\projects\ps\sun-device.local\themes\pos_ruby5\modules\blockbestsellers\blockbestsellers-home.tpl" */ ?>
<?php /*%%SmartyHeaderCode:34565c5d8bcc6fb959-34518956%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c6dee1a93a5b8c759e8b42270c9af379a9f672a3' => 
    array (
      0 => 'D:\\wamp\\www\\projects\\ps\\sun-device.local\\themes\\pos_ruby5\\modules\\blockbestsellers\\blockbestsellers-home.tpl',
      1 => 1534502018,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '34565c5d8bcc6fb959-34518956',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'best_sellers' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c5d8bcc7aa518_79948449',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c5d8bcc7aa518_79948449')) {function content_5c5d8bcc7aa518_79948449($_smarty_tpl) {?>
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
