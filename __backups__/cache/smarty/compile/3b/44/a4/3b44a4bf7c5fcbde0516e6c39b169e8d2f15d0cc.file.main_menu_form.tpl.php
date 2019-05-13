<?php /* Smarty version Smarty-3.1.19, created on 2019-01-29 15:24:19
         compiled from "/home/sundevice/public_html/modules/menupro/views/templates/admin/main_menu_form.tpl" */ ?>
<?php /*%%SmartyHeaderCode:6161277355c506213c4e863-93188537%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3b44a4bf7c5fcbde0516e6c39b169e8d2f15d0cc' => 
    array (
      0 => '/home/sundevice/public_html/modules/menupro/views/templates/admin/main_menu_form.tpl',
      1 => 1511549306,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6161277355c506213c4e863-93188537',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'defaultNumberMenuPerLine' => 0,
    'hookPreferences' => 0,
    'imageMenuTypeFolder' => 0,
    'id_menupro_main_menu' => 0,
    'ps_version' => 0,
    'currentNav' => 0,
    'formAction' => 0,
    'displayHtmlTemplate' => 0,
    'mainMenuInformationFormContent' => 0,
    'homeLink' => 0,
    'mainMenuStyleFormContent' => 0,
    'stylesLevelFormContent' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c506213c831d9_46513297',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c506213c831d9_46513297')) {function content_5c506213c831d9_46513297($_smarty_tpl) {?>
<script type="text/javascript">
	var defaultNumberMenuPerLineLabel = "<?php echo smartyTranslate(array('s'=>'Default','mod'=>'menupro'),$_smarty_tpl);?>
";
	var numberMenuPerLineValueWhenDefault = "<?php echo smartyTranslate(array('s'=>'Depending on the width of the line','mod'=>'menupro'),$_smarty_tpl);?>
";
	var defaultNumberMenuPerLineValue = "<?php echo intval($_smarty_tpl->tpl_vars['defaultNumberMenuPerLine']->value);?>
";
	var hookPreferences = <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['json_encode'][0][0]->jsonEncode($_smarty_tpl->tpl_vars['hookPreferences']->value);?>
;
	var imageMenuTypeFolder = "<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['imageMenuTypeFolder']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
";
</script>
<fieldset>
    <h2><?php if (isset($_smarty_tpl->tpl_vars['id_menupro_main_menu']->value)) {?><?php echo smartyTranslate(array('s'=>'Edit Main menu','mod'=>'menupro'),$_smarty_tpl);?>
<?php } else { ?><?php echo smartyTranslate(array('s'=>'Add new main menu','mod'=>'menupro'),$_smarty_tpl);?>
<?php }?></h2>
	<div class="row">
		<div id="tabs">
			<div class="productTabs col-lg-2 col-md-3">
				<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?><div class="tab list-group"><?php } else { ?><ul class="tab"><?php }?>
					<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?><li class="tab-row"><?php }?>
					<a id="nav-information" class="list-group-item nav-optiongroup<?php if ($_smarty_tpl->tpl_vars['currentNav']->value=='nav-information') {?> active<?php }?>" href="#" 
					title="<?php echo smartyTranslate(array('s'=>'Information','mod'=>'menupro'),$_smarty_tpl);?>
">
						<?php echo smartyTranslate(array('s'=>'Information','mod'=>'menupro'),$_smarty_tpl);?>

					</a>
					<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?></li><li class="tab-row"><?php }?>
					<a id="nav-own-style" class="list-group-item nav-optiongroup<?php if ($_smarty_tpl->tpl_vars['currentNav']->value=='nav-own-style') {?> active<?php }?>" href="#" 
					title="<?php echo smartyTranslate(array('s'=>'Style Sheet','mod'=>'menupro'),$_smarty_tpl);?>
">
						<?php echo smartyTranslate(array('s'=>'Style Sheet','mod'=>'menupro'),$_smarty_tpl);?>

					</a>
					<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?></li><li class="tab-row"><?php }?>
					<a id="nav-default-styles" class="list-group-item nav-optiongroup <?php if ($_smarty_tpl->tpl_vars['currentNav']->value=='nav-default-styles') {?> active<?php }?>" href="#" 
					title="<?php echo smartyTranslate(array('s'=>'Styles Sheets for submenus','mod'=>'menupro'),$_smarty_tpl);?>
">
						<?php echo smartyTranslate(array('s'=>'Styles Sheets for submenus','mod'=>'menupro'),$_smarty_tpl);?>

					</a>
					<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?></li><?php }?>
				<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?></div><?php } else { ?></ul><?php }?>
			</div>
		</div>
		<div class="form-horizontal col-lg-10 col-md-9">
			<div>
				<form id="formMainMenu" method="post" enctype="multipart/form-data" novalidate="" action="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['formAction']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
">
					<input type="hidden" id="id_menupro_main_menu" name="id_menupro_main_menu" 
					value="<?php if (isset($_smarty_tpl->tpl_vars['id_menupro_main_menu']->value)) {?><?php echo intval($_smarty_tpl->tpl_vars['id_menupro_main_menu']->value);?>
<?php } else { ?>0<?php }?>">
					
					<input type="hidden" id="txtCurrentNav" name="current_nav" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['currentNav']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
">
					<input type="hidden" id="submitSaveAndStay" name="submitSaveAndStay" value="0">
					<div class="nav-information tab-optiongroup" <?php if ($_smarty_tpl->tpl_vars['currentNav']->value!='nav-information') {?>style="display: none"<?php }?>>
						<?php ob_start();?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['displayHtmlTemplate']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php $_tmp9=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ($_tmp9, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('htmlContent'=>$_smarty_tpl->tpl_vars['mainMenuInformationFormContent']->value), 0);?>

						<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?>
						<div class="margin-form menupro toolbarBox clearfix">
							<a href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['homeLink']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" class="pull-left mp-button" title="<?php echo smartyTranslate(array('s'=>'Back to list','mod'=>'menupro'),$_smarty_tpl);?>
">
								<i class="process-icon-back mp-icon"></i><?php echo smartyTranslate(array('s'=>'Back to list','mod'=>'menupro'),$_smarty_tpl);?>

							</a>
							<button type="submit" name="" class="pull-right mp-button">
								<i class="process-icon-save mp-icon"></i><?php echo smartyTranslate(array('s'=>'Save','mod'=>'menupro'),$_smarty_tpl);?>

							</button>
							<button type="submit" class="btnSaveAndStayMenu pull-right mp-button" name="staymainmemu">
								<i class="process-icon-save-and-stay mp-icon"></i><?php echo smartyTranslate(array('s'=>'Save and stay','mod'=>'menupro'),$_smarty_tpl);?>

							</button>
						</div>
						<input type="hidden" name="submitMainMenuSave" value="1">
						<?php }?>
					</div>
					<div class="nav-own-style tab-optiongroup" <?php if ($_smarty_tpl->tpl_vars['currentNav']->value!='nav-own-style') {?>style="display: none"<?php }?>>
						<?php if (isset($_smarty_tpl->tpl_vars['id_menupro_main_menu']->value)) {?>
							<?php ob_start();?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['displayHtmlTemplate']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php $_tmp10=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ($_tmp10, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('htmlContent'=>$_smarty_tpl->tpl_vars['mainMenuStyleFormContent']->value), 0);?>

						<?php } else { ?>
							<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>
							<div class="alert alert-warning">
								<button type="button" class="close" data-dismiss="alert">×</button>
								<ul style="display:block;" id="seeMore">
									<li>
							<?php } else { ?><div class="warn"><?php }?>			
										<?php echo smartyTranslate(array('s'=>'You must register this main menu before managing its style.','mod'=>'menupro'),$_smarty_tpl);?>

							<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>			
									</li>
								</ul>
							</div>
							<?php } else { ?></div><?php }?>
						<?php }?>
					</div>
				</form>
				<div class="nav-default-styles tab-optiongroup" style="display: none">
					<?php if (isset($_smarty_tpl->tpl_vars['id_menupro_main_menu']->value)) {?>
						<?php ob_start();?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['displayHtmlTemplate']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php $_tmp11=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ($_tmp11, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('htmlContent'=>$_smarty_tpl->tpl_vars['stylesLevelFormContent']->value), 0);?>

					<?php } else { ?>
						<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>
						<div class="alert alert-warning">
							<button type="button" class="close" data-dismiss="alert">×</button>
							<ul style="display:block;" id="seeMore">
								<li>
						<?php } else { ?><div class="warn"><?php }?>
									<?php echo smartyTranslate(array('s'=>'You must register this main menu before managing its styles level.','mod'=>'menupro'),$_smarty_tpl);?>

						<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>			
									</li>
							</ul>
						</div>
						<?php } else { ?></div><?php }?>
					<?php }?>
				</div>
			</div>
		</div>
	</div>
</fieldset><?php }} ?>
