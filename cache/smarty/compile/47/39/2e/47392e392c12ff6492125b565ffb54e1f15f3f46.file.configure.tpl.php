<?php /* Smarty version Smarty-3.1.19, created on 2019-05-03 15:12:33
         compiled from "/home/sundevice/public_html/modules/cscustomize/views/templates/admin/configure.tpl" */ ?>
<?php /*%%SmartyHeaderCode:14545858455ccc3e4188d356-70651172%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '47392e392c12ff6492125b565ffb54e1f15f3f46' => 
    array (
      0 => '/home/sundevice/public_html/modules/cscustomize/views/templates/admin/configure.tpl',
      1 => 1534502024,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14545858455ccc3e4188d356-70651172',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'listHtmlPerHook' => 0,
    'hook' => 0,
    'first' => 0,
    'listContent' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5ccc3e418a15a8_26851685',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5ccc3e418a15a8_26851685')) {function content_5ccc3e418a15a8_26851685($_smarty_tpl) {?>
<script type="text/javascript">
	function changeTab (nav) {
		var id = nav.attr('id');
		$('.nav-optiongroup').removeClass('selected');
		nav.addClass('selected active');
		nav.siblings().removeClass('active');
		$('.tab-optiongroup').hide();
		$('.' + id).show();
	}
	function manageTabs () {
		$('div.productTabs').find('a.nav-optiongroup').click(function (event) {
			event.preventDefault();
			changeTab($(this));
		});
	}
	$(document).ready(function () {
		manageTabs ();
	});
</script>
<div class="row">
	<div id="tabs">
		<div class="productTabs col-lg-2 col-md-2">
			<div class="tab list-group">
				<?php $_smarty_tpl->tpl_vars['first'] = new Smarty_variable(true, null, 0);?>
				<?php  $_smarty_tpl->tpl_vars['listContent'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['listContent']->_loop = false;
 $_smarty_tpl->tpl_vars['hook'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['listHtmlPerHook']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['listContent']->key => $_smarty_tpl->tpl_vars['listContent']->value) {
$_smarty_tpl->tpl_vars['listContent']->_loop = true;
 $_smarty_tpl->tpl_vars['hook']->value = $_smarty_tpl->tpl_vars['listContent']->key;
?>
				<a id="nav-<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['hook']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" class="list-group-item nav-optiongroup<?php if ($_smarty_tpl->tpl_vars['first']->value) {?> active<?php }?>" href="#" 
				title="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['hook']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
' mod='menupro'}">
					<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['hook']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>

				</a>
				<?php $_smarty_tpl->tpl_vars['first'] = new Smarty_variable(false, null, 0);?>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="form-horizontal col-lg-10 col-md-10">
		<?php $_smarty_tpl->tpl_vars['first'] = new Smarty_variable(true, null, 0);?>
		<?php  $_smarty_tpl->tpl_vars['listContent'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['listContent']->_loop = false;
 $_smarty_tpl->tpl_vars['hook'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['listHtmlPerHook']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['listContent']->key => $_smarty_tpl->tpl_vars['listContent']->value) {
$_smarty_tpl->tpl_vars['listContent']->_loop = true;
 $_smarty_tpl->tpl_vars['hook']->value = $_smarty_tpl->tpl_vars['listContent']->key;
?>
		<div class="nav-<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['hook']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
 tab-optiongroup" <?php if (!$_smarty_tpl->tpl_vars['first']->value) {?>style="display: none"<?php }?>>
			<?php echo $_smarty_tpl->tpl_vars['listContent']->value;?>

		</div>
		<?php $_smarty_tpl->tpl_vars['first'] = new Smarty_variable(false, null, 0);?>
		<?php } ?>
	</div>
</div><?php }} ?>
