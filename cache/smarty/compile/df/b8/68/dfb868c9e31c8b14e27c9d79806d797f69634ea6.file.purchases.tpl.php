<?php /* Smarty version Smarty-3.1.19, created on 2019-04-29 16:38:07
         compiled from "/home/sundevice/public_html/modules/savemypurchases/views/templates/hook/purchases.tpl" */ ?>
<?php /*%%SmartyHeaderCode:13243782135cc70c4f37a8b1-73550879%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'dfb868c9e31c8b14e27c9d79806d797f69634ea6' => 
    array (
      0 => '/home/sundevice/public_html/modules/savemypurchases/views/templates/hook/purchases.tpl',
      1 => 1534419426,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '13243782135cc70c4f37a8b1-73550879',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'vx_link' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cc70c4f383ba3_10636732',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cc70c4f383ba3_10636732')) {function content_5cc70c4f383ba3_10636732($_smarty_tpl) {?><div class="btn-group os_purchases">
    <a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['vx_link']->value, ENT_QUOTES, 'UTF-8', true);?>
<?php if (strpos($_smarty_tpl->tpl_vars['vx_link']->value,'?')) {?>&<?php } else { ?>?<?php }?>vx_save_my_purchases=save" style="display: inline-block; margin: 5px 0; padding:  10px;" class="button button-medium"><?php echo smartyTranslate(array('s'=>'Save my current basket','mod'=>'savemypurchases'),$_smarty_tpl);?>
</a>
   
</div><?php }} ?>
