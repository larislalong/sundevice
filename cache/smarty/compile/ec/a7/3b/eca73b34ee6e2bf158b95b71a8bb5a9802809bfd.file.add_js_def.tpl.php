<?php /* Smarty version Smarty-3.1.19, created on 2019-05-07 21:57:56
         compiled from "/home/sundevice/preprod/modules/menupro/views/templates/hook/add_js_def.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4651896575cd1e34438ad66-38914602%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'eca73b34ee6e2bf158b95b71a8bb5a9802809bfd' => 
    array (
      0 => '/home/sundevice/preprod/modules/menupro/views/templates/hook/add_js_def.tpl',
      1 => 1511549304,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4651896575cd1e34438ad66-38914602',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'compared_products' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cd1e34438d720_50181048',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cd1e34438d720_50181048')) {function content_5cd1e34438d720_50181048($_smarty_tpl) {?>

<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['addJsDef'][0][0]->addJsDef(array('comparedProductsIds'=>$_smarty_tpl->tpl_vars['compared_products']->value),$_smarty_tpl);?>
<?php }} ?>
