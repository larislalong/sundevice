<?php /* Smarty version Smarty-3.1.19, created on 2019-04-29 16:43:00
         compiled from "/home/sundevice/public_html/adminSunDevice/themes/default/template/content.tpl" */ ?>
<?php /*%%SmartyHeaderCode:8426848535cc70d74c435d0-77420800%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6c400e44aed13ec0b4f78ace819250857262a258' => 
    array (
      0 => '/home/sundevice/public_html/adminSunDevice/themes/default/template/content.tpl',
      1 => 1532346758,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8426848535cc70d74c435d0-77420800',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'content' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cc70d74c45da8_26781724',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cc70d74c45da8_26781724')) {function content_5cc70d74c45da8_26781724($_smarty_tpl) {?>
<div id="ajax_confirmation" class="alert alert-success hide"></div>

<div id="ajaxBox" style="display:none"></div>


<div class="row">
	<div class="col-lg-12">
		<?php if (isset($_smarty_tpl->tpl_vars['content']->value)) {?>
			<?php echo $_smarty_tpl->tpl_vars['content']->value;?>

		<?php }?>
	</div>
</div>
<?php }} ?>
