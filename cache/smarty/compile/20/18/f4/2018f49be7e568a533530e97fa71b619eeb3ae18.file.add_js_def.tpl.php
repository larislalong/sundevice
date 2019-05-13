<?php /* Smarty version Smarty-3.1.19, created on 2019-04-29 16:36:45
         compiled from "/home/sundevice/public_html/modules/menupro/views/templates/hook/add_js_def.tpl" */ ?>
<?php /*%%SmartyHeaderCode:7859934825cc70bfd704c92-81830892%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2018f49be7e568a533530e97fa71b619eeb3ae18' => 
    array (
      0 => '/home/sundevice/public_html/modules/menupro/views/templates/hook/add_js_def.tpl',
      1 => 1511549304,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7859934825cc70bfd704c92-81830892',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'compared_products' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cc70bfd705f71_76801398',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cc70bfd705f71_76801398')) {function content_5cc70bfd705f71_76801398($_smarty_tpl) {?>

<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['addJsDef'][0][0]->addJsDef(array('comparedProductsIds'=>$_smarty_tpl->tpl_vars['compared_products']->value),$_smarty_tpl);?>
<?php }} ?>
