<?php /* Smarty version Smarty-3.1.19, created on 2019-02-08 15:00:46
         compiled from "D:\wamp\www\projects\ps\sun-device.local\modules\blocklayeredcustom\views\templates\admin\regenerate_redirect.tpl" */ ?>
<?php /*%%SmartyHeaderCode:66285c5d8b8eb6bae8-68735528%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '00a1dfcc72300d7b1854215758ad2648b5c51110' => 
    array (
      0 => 'D:\\wamp\\www\\projects\\ps\\sun-device.local\\modules\\blocklayeredcustom\\views\\templates\\admin\\regenerate_redirect.tpl',
      1 => 1525408906,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '66285c5d8b8eb6bae8-68735528',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'priceRegenerationLink' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c5d8b8ebbf197_93724661',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c5d8b8ebbf197_93724661')) {function content_5c5d8b8ebbf197_93724661($_smarty_tpl) {?><script type="text/javascript">
	var blcRegenerateLink = "<?php echo $_smarty_tpl->tpl_vars['priceRegenerationLink']->value;?>
";
	$(document).ready(function () {
		window.open(blcRegenerateLink, "_blank");
	});
</script><?php }} ?>
