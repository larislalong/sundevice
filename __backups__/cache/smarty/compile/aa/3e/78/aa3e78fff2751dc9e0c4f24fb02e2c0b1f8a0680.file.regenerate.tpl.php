<?php /* Smarty version Smarty-3.1.19, created on 2019-01-31 15:56:34
         compiled from "/home/sundevice/public_html/modules/blocklayeredcustom/views/templates/admin/regenerate.tpl" */ ?>
<?php /*%%SmartyHeaderCode:5053202575c530ca25fd280-67411557%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'aa3e78fff2751dc9e0c4f24fb02e2c0b1f8a0680' => 
    array (
      0 => '/home/sundevice/public_html/modules/blocklayeredcustom/views/templates/admin/regenerate.tpl',
      1 => 1525408906,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5053202575c530ca25fd280-67411557',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'ajaxModuleUrl' => 0,
    'products' => 0,
    'product' => 0,
    'first' => 0,
    'homeLink' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c530ca26325a5_83188597',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c530ca26325a5_83188597')) {function content_5c530ca26325a5_83188597($_smarty_tpl) {?><script type="text/javascript">
	var ajaxModuleUrl = "<?php echo strtr($_smarty_tpl->tpl_vars['ajaxModuleUrl']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
";
	var FROM_TEXT = "<?php echo smartyTranslate(array('s'=>'From','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>
";
	var TO_TEXT = "<?php echo smartyTranslate(array('s'=>'To','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>
";
	var SUCCESS_TEXT = "<?php echo smartyTranslate(array('s'=>'Price of combinations of this product are up to date','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>
";
	var autoStartDeprecated = true;
</script>
<div id="divNotifyGeneral"></div>
<div id="divRegenerateParent" class="panel">
	<h3><?php echo smartyTranslate(array('s'=>'Regeneration progress','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>
</h3>
	<div id="divAllAction" class="panel clearfix">
		<button type="button" class="btn btn-default btnStartAll">
			<i class="process-icon-cogs"></i><?php echo smartyTranslate(array('s'=>'Start All','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>

		</button>
		<button type="button" class="btn btn-default btnUpdate">
			<i class="process-icon-refresh"></i><?php echo smartyTranslate(array('s'=>'Update','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>

		</button>
		<button type="button" class="btn btn-default btnCancelAll" style="display:none;">
			<i class="process-icon-cancel"></i><?php echo smartyTranslate(array('s'=>'Cancel All','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>

		</button>
	</div>
	<div class="row">
		<div id="tabs">
			<div class="productTabs col-lg-3 col-md-4">
				<div class="tab list-group">
					<?php $_smarty_tpl->tpl_vars['first'] = new Smarty_variable(true, null, 0);?>
					<?php  $_smarty_tpl->tpl_vars['product'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['product']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['products']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['product']->key => $_smarty_tpl->tpl_vars['product']->value) {
$_smarty_tpl->tpl_vars['product']->_loop = true;
?>
					<a id="nav-product<?php echo intval($_smarty_tpl->tpl_vars['product']->value['id_product']);?>
" class="list-group-item nav-optiongroup<?php if ($_smarty_tpl->tpl_vars['first']->value) {?> active<?php }?><?php if ($_smarty_tpl->tpl_vars['product']->value['is_up_to_date']) {?> list-group-item-success<?php } else { ?> list-group-item-warning<?php }?>" href="#" title="<?php echo $_smarty_tpl->tpl_vars['product']->value['name'];?>
"><?php echo $_smarty_tpl->tpl_vars['product']->value['id_product'];?>
: <?php echo $_smarty_tpl->tpl_vars['product']->value['name'];?>
<span class="nav-loader-span" style="display:none;"><i class="icon-refresh icon-spin"></i></span></a>
					<?php $_smarty_tpl->tpl_vars['first'] = new Smarty_variable(false, null, 0);?>
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="form-horizontal col-lg-9 col-md-8">
			<div class="panel product-content-list">
				<?php $_smarty_tpl->tpl_vars['first'] = new Smarty_variable(true, null, 0);?>
				<?php  $_smarty_tpl->tpl_vars['product'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['product']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['products']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['product']->key => $_smarty_tpl->tpl_vars['product']->value) {
$_smarty_tpl->tpl_vars['product']->_loop = true;
?>
				<div id="div-product<?php echo intval($_smarty_tpl->tpl_vars['product']->value['id_product']);?>
" class="product-content-item nav-product<?php echo intval($_smarty_tpl->tpl_vars['product']->value['id_product']);?>
 tab-optiongroup nav-product" data-id_product="<?php echo intval($_smarty_tpl->tpl_vars['product']->value['id_product']);?>
" data-is_up_to_date="<?php echo intval($_smarty_tpl->tpl_vars['product']->value['is_up_to_date']);?>
" data-name="<?php echo $_smarty_tpl->tpl_vars['product']->value['name'];?>
" <?php if (!$_smarty_tpl->tpl_vars['first']->value) {?>style="display:none;"<?php }?>>
					<h3><?php echo $_smarty_tpl->tpl_vars['product']->value['id_product'];?>
: <?php echo $_smarty_tpl->tpl_vars['product']->value['name'];?>
</h3>
					<div class="div-content-warning" <?php if ($_smarty_tpl->tpl_vars['product']->value['is_up_to_date']) {?>style="display:none;"<?php }?>><div class="alert alert-warning"><?php echo smartyTranslate(array('s'=>'Price of combinations of this product are not up to date','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>
</div></div>
					<div class="div-content-buttons">
						<button type="button" class="btn btn-default btnRegenerate">
							<i class="icon-cogs"></i>  <?php echo smartyTranslate(array('s'=>'Regenerate','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>

						</button>
						<button type="button" class="btn btn-default btnCancel" style="display:none;">
							<i class="icon-stop"></i>  <?php echo smartyTranslate(array('s'=>'Cancel','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>

						</button>
						<button type="button" class="btn btn-default btnResume" style="display:none;">
							<i class="icon-play"></i>  <?php echo smartyTranslate(array('s'=>'Resume','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>

						</button>
						<button type="button" class="btn btn-default btnPause" style="display:none;">
							<i class="icon-pause"></i>  <?php echo smartyTranslate(array('s'=>'Pause','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>

						</button>
					</div>
					<div class="div-content-count" style="display:none;">
						<span class=""><?php echo smartyTranslate(array('s'=>'Combinations count','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>
: </span><span class="value"></span>
					</div>
					<div class="div-content-loader" style="display:none;">
						<div class="loading"><i class="icon-refresh icon-spin"></i><?php echo smartyTranslate(array('s'=>'Regenerating combinations of ','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>
 <?php echo $_smarty_tpl->tpl_vars['product']->value['name'];?>
 ...</div>
					</div>
					<ul class="div-content-log"></ul>
					
					<div class="div-content-success"></div>
					<div class="div-content-errors"></div>
				</div>
				<?php $_smarty_tpl->tpl_vars['first'] = new Smarty_variable(false, null, 0);?>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="panel-footer clearfix">
		<a href="<?php echo $_smarty_tpl->tpl_vars['homeLink']->value;?>
" class="btn btn-default pull-right">
			<i class="process-icon-back"></i><?php echo smartyTranslate(array('s'=>'Back to module configuration page','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>

		</a>
	</div>
</div><?php }} ?>
