<?php /* Smarty version Smarty-3.1.19, created on 2019-05-07 18:18:50
         compiled from "/home/sundevice/preprod/adminSunDevice/themes/default/template/content.tpl" */ ?>
<?php /*%%SmartyHeaderCode:588458955cd1afea4b30e8-73089775%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4f2cae3b9871c3c3ef85f52bd8b3efe872bdc436' => 
    array (
      0 => '/home/sundevice/preprod/adminSunDevice/themes/default/template/content.tpl',
      1 => 1532346758,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '588458955cd1afea4b30e8-73089775',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'content' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cd1afea4b7b22_51181744',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cd1afea4b7b22_51181744')) {function content_5cd1afea4b7b22_51181744($_smarty_tpl) {?>
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
