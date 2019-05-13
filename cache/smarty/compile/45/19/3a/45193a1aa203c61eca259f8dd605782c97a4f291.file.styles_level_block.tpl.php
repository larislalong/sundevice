<?php /* Smarty version Smarty-3.1.19, created on 2019-05-02 09:55:56
         compiled from "/home/sundevice/public_html/modules/menupro/views/templates/admin/styles_level_block.tpl" */ ?>
<?php /*%%SmartyHeaderCode:9036194345ccaa28c141f81-70022038%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '45193a1aa203c61eca259f8dd605782c97a4f291' => 
    array (
      0 => '/home/sundevice/public_html/modules/menupro/views/templates/admin/styles_level_block.tpl',
      1 => 1511549306,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9036194345ccaa28c141f81-70022038',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'menuType' => 0,
    'idMenu' => 0,
    'menuTypesConst' => 0,
    'ps_version' => 0,
    'headerTitle' => 0,
    'displayHtmlTemplate' => 0,
    'styleLevelList' => 0,
    'i' => 0,
    'homeLink' => 0,
    'backTitle' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5ccaa28c188fb4_74266716',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5ccaa28c188fb4_74266716')) {function content_5ccaa28c188fb4_74266716($_smarty_tpl) {?>
<input type="hidden" id="MENUPRO_STYLES_LEVEL_MENU_TYPE" value="<?php echo intval($_smarty_tpl->tpl_vars['menuType']->value);?>
">
<input type="hidden" id="MENUPRO_STYLES_LEVEL_MENU_ID" value="<?php if (isset($_smarty_tpl->tpl_vars['idMenu']->value)) {?><?php echo intval($_smarty_tpl->tpl_vars['idMenu']->value);?>
<?php } else { ?>0<?php }?>">
<script type="text/javascript">
	var loaderSaveStyleLevelMessage = "<?php echo smartyTranslate(array('s'=>'Saving style level...','mod'=>'menupro'),$_smarty_tpl);?>
";
	var loaderDeleteStyleLevelMessage = "<?php echo smartyTranslate(array('s'=>'deleting style level...','mod'=>'menupro'),$_smarty_tpl);?>
";
	var loaderDuplicateStyleLevelMessage = "<?php echo smartyTranslate(array('s'=>'duplicating style level...','mod'=>'menupro'),$_smarty_tpl);?>
";
	var confirmDeleteStyleLevelMessage = "<?php echo smartyTranslate(array('s'=>'Are you sure you want to delete style','mod'=>'menupro'),$_smarty_tpl);?>
";
	var updateButtonText = "<?php echo smartyTranslate(array('s'=>'Edit','mod'=>'menupro'),$_smarty_tpl);?>
";
	var deleteButtonText = "<?php echo smartyTranslate(array('s'=>'Delete','mod'=>'menupro'),$_smarty_tpl);?>
";
	var duplicateButtonText = "<?php echo smartyTranslate(array('s'=>'Duplicate','mod'=>'menupro'),$_smarty_tpl);?>
";
</script>
<?php $_smarty_tpl->tpl_vars["headerTitle"] = new Smarty_variable('', null, 0);?>
<?php if ($_smarty_tpl->tpl_vars['menuType']->value==$_smarty_tpl->tpl_vars['menuTypesConst']->value['NONE']) {?>
	<?php ob_start();?><?php echo smartyTranslate(array('s'=>'Different Style Sheets','mod'=>'menupro'),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['headerTitle'] = new Smarty_variable($_tmp1, null, 0);?>
<?php } elseif ($_smarty_tpl->tpl_vars['menuType']->value==$_smarty_tpl->tpl_vars['menuTypesConst']->value['MAIN']) {?>
	<?php ob_start();?><?php echo smartyTranslate(array('s'=>'Styles Sheets for submenus','mod'=>'menupro'),$_smarty_tpl);?>
<?php $_tmp2=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['headerTitle'] = new Smarty_variable($_tmp2, null, 0);?>
	<?php ob_start();?><?php echo smartyTranslate(array('s'=>'Back to list','mod'=>'menupro'),$_smarty_tpl);?>
<?php $_tmp3=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['backTitle'] = new Smarty_variable($_tmp3, null, 0);?>
<?php } else { ?>
	<?php ob_start();?><?php echo smartyTranslate(array('s'=>'Styles Sheets for submenus','mod'=>'menupro'),$_smarty_tpl);?>
<?php $_tmp4=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['headerTitle'] = new Smarty_variable($_tmp4, null, 0);?>
	<?php $_smarty_tpl->tpl_vars['homeLink'] = new Smarty_variable('#', null, 0);?>
	<?php ob_start();?><?php echo smartyTranslate(array('s'=>'Quit','mod'=>'menupro'),$_smarty_tpl);?>
<?php $_tmp5=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['backTitle'] = new Smarty_variable($_tmp5, null, 0);?>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?><div class="panel"><h3><?php } else { ?><fieldset><legend><?php }?>
	<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['headerTitle']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>

<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?></h3><?php } else { ?></legend><?php }?>
	<div id="divStyleLevelNotify" style="display:none;">
	</div>
	<div class="row">
		<div id="divStyleLevelList" class="col-lg-5 col-md-5">
			<div id="divStyleLevelListDisabler" style="display:none;" class="div-disabler"></div>
			<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?><div id="form-menupro_default_style"><?php }?>
			<?php ob_start();?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['displayHtmlTemplate']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php $_tmp6=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ($_tmp6, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('htmlContent'=>$_smarty_tpl->tpl_vars['styleLevelList']->value), 0);?>

			<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?></div><?php }?>
		</div>
		<div id="divStyleLevelEdition" class="<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>col-lg-7 col-md-7<?php } else { ?>col-lg-6 col-md-6<?php }?>" style="display:none;">
			<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?><div class="panel"><h3><?php } else { ?><fieldset><legend><?php }?>
				<?php echo smartyTranslate(array('s'=>'Style edition','mod'=>'menupro'),$_smarty_tpl);?>

				<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?></h3><?php } else { ?></legend><?php }?>
				<div id="divStyleLevelEditionContent">
					<div class="form-group clearfix">
						<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>
						<div class="form-group clearfix">
							<label class="control-label col-lg-4 col-md-4 col-sm-4 col-xs-4 mp-label" for="MENUPRO_STYLES_LEVEL_NAME">
						<?php } else { ?>
						<label>
						<?php }?>
								<?php echo smartyTranslate(array('s'=>'Name','mod'=>'menupro'),$_smarty_tpl);?>

						<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>
						</label>
						<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
						<?php } else { ?>
						</label>
						<div class="margin-form">
						<?php }?>
								<input type="text" id="MENUPRO_STYLES_LEVEL_NAME" value="" class="">
							
						<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>
							</div>
						</div>
						<?php } else { ?>
						</div>
						<div class="clear"></div>
						<?php }?>
						
						<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>
						<div class="form-group clearfix">
							<label class="control-label col-lg-4 col-md-4 col-sm-4 col-xs-4 mp-label" for="MENUPRO_STYLES_LEVEL_MENU_LEVEL">
						<?php } else { ?>
						<label>
						<?php }?>
								<?php echo smartyTranslate(array('s'=>'Menu level','mod'=>'menupro'),$_smarty_tpl);?>

						<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>
						</label>
						<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 input-group">
						<?php } else { ?>
						</label>
						<div class="margin-form">
						<?php }?>
								<input type="text" id="MENUPRO_STYLES_LEVEL_MENU_LEVEL" value="" class="">
							
						<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>
								<span class="input-group-addon dropdown-toggle dropdown-toggle-split caret" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i class="icon-caret-down"></i>
								</span>
								<ul class="dropdown-menu mp-dropdown-menu">
									<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;$_smarty_tpl->tpl_vars['i']->step = 1;$_smarty_tpl->tpl_vars['i']->total = (int) ceil(($_smarty_tpl->tpl_vars['i']->step > 0 ? 5+1 - (0) : 0-(5)+1)/abs($_smarty_tpl->tpl_vars['i']->step));
if ($_smarty_tpl->tpl_vars['i']->total > 0) {
for ($_smarty_tpl->tpl_vars['i']->value = 0, $_smarty_tpl->tpl_vars['i']->iteration = 1;$_smarty_tpl->tpl_vars['i']->iteration <= $_smarty_tpl->tpl_vars['i']->total;$_smarty_tpl->tpl_vars['i']->value += $_smarty_tpl->tpl_vars['i']->step, $_smarty_tpl->tpl_vars['i']->iteration++) {
$_smarty_tpl->tpl_vars['i']->first = $_smarty_tpl->tpl_vars['i']->iteration == 1;$_smarty_tpl->tpl_vars['i']->last = $_smarty_tpl->tpl_vars['i']->iteration == $_smarty_tpl->tpl_vars['i']->total;?>
										<li>
											<a class="dropdown-item level-dropdown-item" data-value="<?php echo intval($_smarty_tpl->tpl_vars['i']->value);?>
" href="#">
												<?php echo intval($_smarty_tpl->tpl_vars['i']->value);?>

											</a>
										</li>
									<?php }} ?>
								</ul>
							</div>
						</div>
						<?php } else { ?>
							<span class="dropdown">
								<button type="button" class="dropbtn"></button>
								<span class="dropdown-content">
								<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;$_smarty_tpl->tpl_vars['i']->step = 1;$_smarty_tpl->tpl_vars['i']->total = (int) ceil(($_smarty_tpl->tpl_vars['i']->step > 0 ? 5+1 - (0) : 0-(5)+1)/abs($_smarty_tpl->tpl_vars['i']->step));
if ($_smarty_tpl->tpl_vars['i']->total > 0) {
for ($_smarty_tpl->tpl_vars['i']->value = 0, $_smarty_tpl->tpl_vars['i']->iteration = 1;$_smarty_tpl->tpl_vars['i']->iteration <= $_smarty_tpl->tpl_vars['i']->total;$_smarty_tpl->tpl_vars['i']->value += $_smarty_tpl->tpl_vars['i']->step, $_smarty_tpl->tpl_vars['i']->iteration++) {
$_smarty_tpl->tpl_vars['i']->first = $_smarty_tpl->tpl_vars['i']->iteration == 1;$_smarty_tpl->tpl_vars['i']->last = $_smarty_tpl->tpl_vars['i']->iteration == $_smarty_tpl->tpl_vars['i']->total;?>
									<a class="dropdown-item level-dropdown-item" data-value="<?php echo intval($_smarty_tpl->tpl_vars['i']->value);?>
" href="#">
										<?php echo intval($_smarty_tpl->tpl_vars['i']->value);?>

									</a>
								<?php }} ?>
								</span>
							</span>
						</div>
						<div class="clear"></div>
						<?php }?>
						<input type="hidden" id="MENUPRO_STYLES_LEVEL_ID" value="" class="">
						<div class="divProperties">
						</div>
					</div>
					<div class="<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>panel-footer<?php } else { ?>margin-form menupro toolbarBox<?php }?>">
						<button type="button" value="1" id="btnStyleLevelSave" name="" class="<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>btn btn-default<?php } else { ?>mp-button<?php }?> pull-right">
							<i class="process-icon-save<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?> mp-icon<?php }?>"></i><?php echo smartyTranslate(array('s'=>'Save','mod'=>'menupro'),$_smarty_tpl);?>

						</button>
						<button id="btnStyleLevelCancel" type="button" class="<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>btn btn-default<?php } else { ?>mp-button<?php }?> pull-left" title="<?php echo smartyTranslate(array('s'=>'Cancel','mod'=>'menupro'),$_smarty_tpl);?>
">
							<i class="<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>process-icon-cancel<?php } else { ?>process-icon-cancel mp-icon<?php }?>"></i><?php echo smartyTranslate(array('s'=>'Cancel','mod'=>'menupro'),$_smarty_tpl);?>

						</button>
					</div>
				</div>
			<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?></div><?php } else { ?></fieldset><?php }?>
		</div>
	</div>
	<?php if ($_smarty_tpl->tpl_vars['menuType']->value!=$_smarty_tpl->tpl_vars['menuTypesConst']->value['NONE']) {?>
		<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?>
		<div class="clear"></div>
		<?php }?>
		<div class="<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>panel-footer<?php } else { ?>margin-form menupro toolbarBox clearfix<?php }?>">
			<a href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['homeLink']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" class="<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>btn btn-default<?php } else { ?>mp-button<?php }?> pull-right btnCancel" title="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['backTitle']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
">
				<i class="process-icon-back<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?> mp-icon<?php }?>"></i><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['backTitle']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>

			</a>
		</div>
	<?php }?>
<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?></div><?php } else { ?></fieldset><?php }?><?php }} ?>
