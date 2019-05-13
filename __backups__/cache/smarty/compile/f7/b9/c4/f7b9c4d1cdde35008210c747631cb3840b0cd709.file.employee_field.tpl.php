<?php /* Smarty version Smarty-3.1.19, created on 2019-02-08 14:08:44
         compiled from "/home/sundevice/public_html/adminSunDevice/themes/default/template/controllers/logs/employee_field.tpl" */ ?>
<?php /*%%SmartyHeaderCode:17868408425c5d7f5c0f78f1-07895128%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f7b9c4d1cdde35008210c747631cb3840b0cd709' => 
    array (
      0 => '/home/sundevice/public_html/adminSunDevice/themes/default/template/controllers/logs/employee_field.tpl',
      1 => 1532346758,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17868408425c5d7f5c0f78f1-07895128',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'employee_image' => 0,
    'employee_name' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c5d7f5c12ff14_27454435',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c5d7f5c12ff14_27454435')) {function content_5c5d7f5c12ff14_27454435($_smarty_tpl) {?>
<span class="employee_avatar_small">
	<img class="imgm img-thumbnail" alt="" src="<?php echo $_smarty_tpl->tpl_vars['employee_image']->value;?>
" width="32" height="32" />
</span>
<?php echo $_smarty_tpl->tpl_vars['employee_name']->value;?>

<?php }} ?>
