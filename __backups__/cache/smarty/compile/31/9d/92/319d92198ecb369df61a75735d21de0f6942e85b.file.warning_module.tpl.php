<?php /* Smarty version Smarty-3.1.19, created on 2019-02-06 14:37:19
         compiled from "/home/sundevice/public_html/adminSunDevice/themes/default/template/controllers/modules/warning_module.tpl" */ ?>
<?php /*%%SmartyHeaderCode:13140227695c5ae30f488186-57304598%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '319d92198ecb369df61a75735d21de0f6942e85b' => 
    array (
      0 => '/home/sundevice/public_html/adminSunDevice/themes/default/template/controllers/modules/warning_module.tpl',
      1 => 1532346758,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '13140227695c5ae30f488186-57304598',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'module_link' => 0,
    'text' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c5ae30f497370_31776311',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c5ae30f497370_31776311')) {function content_5c5ae30f497370_31776311($_smarty_tpl) {?>
<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['module_link']->value, ENT_QUOTES, 'UTF-8', true);?>
"><?php echo $_smarty_tpl->tpl_vars['text']->value;?>
</a>
<?php }} ?>
