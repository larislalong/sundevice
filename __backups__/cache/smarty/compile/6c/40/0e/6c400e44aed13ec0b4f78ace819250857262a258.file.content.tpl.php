<?php /* Smarty version Smarty-3.1.19, created on 2019-01-29 11:39:38
         compiled from "/home/sundevice/public_html/adminSunDevice/themes/default/template/content.tpl" */ ?>
<?php /*%%SmartyHeaderCode:19993058015c502d6a80a8e6-39890900%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
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
  'nocache_hash' => '19993058015c502d6a80a8e6-39890900',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'content' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c502d6a867334_74601577',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c502d6a867334_74601577')) {function content_5c502d6a867334_74601577($_smarty_tpl) {?>
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
