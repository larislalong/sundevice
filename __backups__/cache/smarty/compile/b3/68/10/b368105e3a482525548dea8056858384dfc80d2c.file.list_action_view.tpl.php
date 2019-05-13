<?php /* Smarty version Smarty-3.1.19, created on 2019-02-14 15:26:25
         compiled from "D:\wamp\www\projects\ps\sun-device.local\adminSunDevice\themes\default\template\helpers\list\list_action_view.tpl" */ ?>
<?php /*%%SmartyHeaderCode:186775c657a91d2c1e3-34540376%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b368105e3a482525548dea8056858384dfc80d2c' => 
    array (
      0 => 'D:\\wamp\\www\\projects\\ps\\sun-device.local\\adminSunDevice\\themes\\default\\template\\helpers\\list\\list_action_view.tpl',
      1 => 1532346758,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '186775c657a91d2c1e3-34540376',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'href' => 0,
    'action' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c657a91df34a9_31655272',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c657a91df34a9_31655272')) {function content_5c657a91df34a9_31655272($_smarty_tpl) {?>
<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['href']->value, ENT_QUOTES, 'UTF-8', true);?>
" title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['action']->value, ENT_QUOTES, 'UTF-8', true);?>
" >
	<i class="icon-search-plus"></i> <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['action']->value, ENT_QUOTES, 'UTF-8', true);?>

</a>
<?php }} ?>
