<?php /* Smarty version Smarty-3.1.19, created on 2019-01-29 15:25:04
         compiled from "/home/sundevice/public_html/modules/menupro/views/templates/admin/secondary_menu_edition_form.tpl" */ ?>
<?php /*%%SmartyHeaderCode:21318778805c506240c62a14-61676670%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '520dc14fcbaeeddde1ec90132d13026c64aea6a9' => 
    array (
      0 => '/home/sundevice/public_html/modules/menupro/views/templates/admin/secondary_menu_edition_form.tpl',
      1 => 1511549306,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '21318778805c506240c62a14-61676670',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'displayStyleLevels' => 0,
    'displayHtmlContents' => 0,
    'ps_version' => 0,
    'headerTitle' => 0,
    'displayHtmlTemplate' => 0,
    'secondaryMenuInformationFormContent' => 0,
    'secondaryMenuStyleFormContent' => 0,
    'stylesLevelFormContent' => 0,
    'htmlContentFormContent' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c506240c91756_43878475',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c506240c91756_43878475')) {function content_5c506240c91756_43878475($_smarty_tpl) {?>
<script type="text/javascript">
	var displayStyleLevels = <?php echo intval($_smarty_tpl->tpl_vars['displayStyleLevels']->value);?>
;
	var displayHtmlContents = <?php echo intval($_smarty_tpl->tpl_vars['displayHtmlContents']->value);?>
;
</script>
<?php ob_start();?><?php echo smartyTranslate(array('s'=>'Edit Secondary menu','mod'=>'menupro'),$_smarty_tpl);?>
<?php $_tmp2=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['headerTitle'] = new Smarty_variable($_tmp2, null, 0);?>
<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>
<div class="modal fade" id="modalEditSecondaryMenu" tabindex="-1" role="dialog" aria-labelledby="modalEditSecondaryMenuTitle" aria-hidden="true">
	<div class="modal-dialog modal-form-edition" role="document">
		<div class="modal-content">
			<div class="modal-header clearfix">
				<h5 class="modal-title pull-left" id="modalEditSecondaryMenuTitle"><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['headerTitle']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</h5>
				<button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
<?php } else { ?>
<div id="dialogModalParent">
	<div id="modalEditSecondaryMenu" title="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['headerTitle']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
">
		<div id="content">
<?php }?>
				<div id="divEditSecondaryMenuNotify" style="display:none;"></div>
				<div id="modalEditSecondaryMenuContent">
					<div class="row<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?> clearfix<?php }?>">
						<div class="productTabs col-lg-2 col-md-3">
							<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?><div class="tab list-group"><?php } else { ?><ul class="tab"><?php }?>
								<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?><li class="tab-row"><?php }?>
								<a id="nav-information" class="list-group-item nav-optiongroup active" href="#" 
								title="<?php echo smartyTranslate(array('s'=>'Information','mod'=>'menupro'),$_smarty_tpl);?>
">
									<?php echo smartyTranslate(array('s'=>'Information','mod'=>'menupro'),$_smarty_tpl);?>

								</a>
								<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?></li><?php }?>
								<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?><li class="tab-row"><?php }?>
								<a id="nav-own-style" class="list-group-item nav-optiongroup" href="#" 
								title="<?php echo smartyTranslate(array('s'=>'Style Sheet','mod'=>'menupro'),$_smarty_tpl);?>
">
									<?php echo smartyTranslate(array('s'=>'Style Sheet','mod'=>'menupro'),$_smarty_tpl);?>

								</a>
								<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?></li><?php }?>
								<?php if ($_smarty_tpl->tpl_vars['displayStyleLevels']->value) {?>
								<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?><li class="tab-row"><?php }?>
								<a id="nav-default-styles" class="list-group-item nav-optiongroup" href="#" 
								title="<?php echo smartyTranslate(array('s'=>'Styles Sheets for submenus','mod'=>'menupro'),$_smarty_tpl);?>
">
									<?php echo smartyTranslate(array('s'=>'Styles Sheets for submenus','mod'=>'menupro'),$_smarty_tpl);?>

								</a>
								<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?></li><?php }?>
								<?php }?>
								<?php if ($_smarty_tpl->tpl_vars['displayHtmlContents']->value) {?>
								<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?><li class="tab-row"><?php }?>
								<a id="nav-html-contents" class="list-group-item nav-optiongroup" href="#" 
								title="<?php echo smartyTranslate(array('s'=>'Html contents','mod'=>'menupro'),$_smarty_tpl);?>
">
									<?php echo smartyTranslate(array('s'=>'Html contents','mod'=>'menupro'),$_smarty_tpl);?>

								</a>
								<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?></li><?php }?>
								<?php }?>
							<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?></div><?php } else { ?></ul><?php }?>
						</div>
						<div class="form-horizontal col-lg-10 col-md-9<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?> clearfix<?php }?>">
							<div id="divInformationBlock" class="nav-information tab-optiongroup">
								<?php ob_start();?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['displayHtmlTemplate']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php $_tmp3=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ($_tmp3, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('htmlContent'=>$_smarty_tpl->tpl_vars['secondaryMenuInformationFormContent']->value), 0);?>

								<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?>
								<div class="margin-form menupro toolbarBox clearfix">
									<button type="button" class="pull-left mp-button btnCancel" name="cancelsecondarymemu">
										<i class="process-icon-back mp-icon"></i><?php echo smartyTranslate(array('s'=>'Quit','mod'=>'menupro'),$_smarty_tpl);?>

									</button>
									<button type="button" name="savesecondarymemu" class="pull-right mp-button btnSave">
										<i class="process-icon-save mp-icon"></i><?php echo smartyTranslate(array('s'=>'Save','mod'=>'menupro'),$_smarty_tpl);?>

									</button>
									<button type="submit" class="pull-right mp-button btnSaveAndStay" name="staymainmemu">
										<i class="process-icon-save-and-stay mp-icon"></i><?php echo smartyTranslate(array('s'=>'Save and stay','mod'=>'menupro'),$_smarty_tpl);?>

									</button>
								</div>
								<?php }?>
							</div>
							<div class="nav-own-style tab-optiongroup" style="display: none;">
								<?php ob_start();?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['displayHtmlTemplate']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php $_tmp4=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ($_tmp4, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('htmlContent'=>$_smarty_tpl->tpl_vars['secondaryMenuStyleFormContent']->value), 0);?>

							</div>
							<?php if ($_smarty_tpl->tpl_vars['displayStyleLevels']->value) {?>
							<div class="nav-default-styles tab-optiongroup" style="display: none;">
								<?php ob_start();?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['displayHtmlTemplate']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php $_tmp5=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ($_tmp5, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('htmlContent'=>$_smarty_tpl->tpl_vars['stylesLevelFormContent']->value), 0);?>

							</div>
							<?php }?>
							<?php if ($_smarty_tpl->tpl_vars['displayHtmlContents']->value) {?>
							<div id="divHtmlContentBlock" class="nav-html-contents tab-optiongroup" style="display: none;">
								<?php ob_start();?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['displayHtmlTemplate']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php $_tmp6=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ($_tmp6, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('htmlContent'=>$_smarty_tpl->tpl_vars['htmlContentFormContent']->value), 0);?>

							</div>
							<?php }?>
						</div>
					</div>
				</div>
<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>
			</div>
		</div>
	</div>
</div>
<?php } else { ?>
		</div>
	</div>
</div>
<?php }?><?php }} ?>
