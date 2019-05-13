<?php /* Smarty version Smarty-3.1.19, created on 2019-02-07 15:30:41
         compiled from "/home/sundevice/public_html/modules/blocklayeredcustom/views/templates/admin/regenerate_redirect.tpl" */ ?>
<?php /*%%SmartyHeaderCode:3826010215c5c4111211132-26811124%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '335d1f102083ddbf4f7834e42f363603240beff3' => 
    array (
      0 => '/home/sundevice/public_html/modules/blocklayeredcustom/views/templates/admin/regenerate_redirect.tpl',
      1 => 1525408906,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3826010215c5c4111211132-26811124',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'priceRegenerationLink' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c5c411121dc16_68578540',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c5c411121dc16_68578540')) {function content_5c5c411121dc16_68578540($_smarty_tpl) {?><script type="text/javascript">
	var blcRegenerateLink = "<?php echo $_smarty_tpl->tpl_vars['priceRegenerationLink']->value;?>
";
	$(document).ready(function () {
		window.open(blcRegenerateLink, "_blank");
	});
</script><?php }} ?>
