<?php /* Smarty version Smarty-3.1.19, created on 2019-02-07 14:17:07
         compiled from "/home/sundevice/public_html/adminSunDevice/themes/default/template/controllers/shop/content.tpl" */ ?>
<?php /*%%SmartyHeaderCode:19103403805c5c2fd3136cd4-93985242%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd48724ad61fd8818159fd8bcc69b3a70f3cd4402' => 
    array (
      0 => '/home/sundevice/public_html/adminSunDevice/themes/default/template/controllers/shop/content.tpl',
      1 => 1532346758,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19103403805c5c2fd3136cd4-93985242',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'shops_tree' => 0,
    'content' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c5c2fd313aa84_84193118',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c5c2fd313aa84_84193118')) {function content_5c5c2fd313aa84_84193118($_smarty_tpl) {?>

<div class="row">
	<div class="col-lg-4">
		<?php echo $_smarty_tpl->tpl_vars['shops_tree']->value;?>

	</div>
	<div class="col-lg-8"><?php echo $_smarty_tpl->tpl_vars['content']->value;?>
</div>
</div>
<?php }} ?>
