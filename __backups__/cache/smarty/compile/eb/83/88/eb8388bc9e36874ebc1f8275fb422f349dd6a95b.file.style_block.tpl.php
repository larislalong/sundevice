<?php /* Smarty version Smarty-3.1.19, created on 2019-01-29 15:24:19
         compiled from "/home/sundevice/public_html/modules/menupro/views/templates/admin/style_block.tpl" */ ?>
<?php /*%%SmartyHeaderCode:9665157835c506213bdba29-96997646%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'eb8388bc9e36874ebc1f8275fb422f349dd6a95b' => 
    array (
      0 => '/home/sundevice/public_html/modules/menupro/views/templates/admin/style_block.tpl',
      1 => 1511549306,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9665157835c506213bdba29-96997646',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'usableStylesListConst' => 0,
    'menuType' => 0,
    'menuTypesConst' => 0,
    'secondaryMenuLevel' => 0,
    'propertiesFormContent' => 0,
    'ps_version' => 0,
    'MENUPRO_STYLE_NAME' => 0,
    'usableStylesList' => 0,
    'k' => 0,
    'MENUPRO_USABLE_STYLE' => 0,
    'usableStyleName' => 0,
    'MENUPRO_STYLE_ID' => 0,
    'MENUPRO_STYLE_MENU_LEVEL' => 0,
    'displayHtmlTemplate' => 0,
    'homeLink' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c506213c2d787_12960068',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c506213c2d787_12960068')) {function content_5c506213c2d787_12960068($_smarty_tpl) {?>
<script type="text/javascript">
	var usableStylesListConst = <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['json_encode'][0][0]->jsonEncode($_smarty_tpl->tpl_vars['usableStylesListConst']->value);?>
;
</script>
<input type="hidden" id="MENUPRO_STYLE_MENU_TYPE" value="<?php echo intval($_smarty_tpl->tpl_vars['menuType']->value);?>
">
<?php $_smarty_tpl->tpl_vars["usableStylesList"] = new Smarty_variable(array(), null, 0);?>
<?php ob_start();?><?php echo smartyTranslate(array('s'=>'Default','mod'=>'menupro'),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php $_smarty_tpl->createLocalArrayVariable('usableStylesList', null, 0);
$_smarty_tpl->tpl_vars['usableStylesList']->value[$_smarty_tpl->tpl_vars['usableStylesListConst']->value['DEFAULT']] = $_tmp1;?>
<?php ob_start();?><?php echo smartyTranslate(array('s'=>'Theme','mod'=>'menupro'),$_smarty_tpl);?>
<?php $_tmp2=ob_get_clean();?><?php $_smarty_tpl->createLocalArrayVariable('usableStylesList', null, 0);
$_smarty_tpl->tpl_vars['usableStylesList']->value[$_smarty_tpl->tpl_vars['usableStylesListConst']->value['THEME']] = $_tmp2;?>
<?php ob_start();?><?php echo smartyTranslate(array('s'=>'Customized','mod'=>'menupro'),$_smarty_tpl);?>
<?php $_tmp3=ob_get_clean();?><?php $_smarty_tpl->createLocalArrayVariable('usableStylesList', null, 0);
$_smarty_tpl->tpl_vars['usableStylesList']->value[$_smarty_tpl->tpl_vars['usableStylesListConst']->value['CUSTOMIZED']] = $_tmp3;?>
<?php ob_start();?><?php echo smartyTranslate(array('s'=>'Menu pro','mod'=>'menupro'),$_smarty_tpl);?>
<?php $_tmp4=ob_get_clean();?><?php $_smarty_tpl->createLocalArrayVariable('usableStylesList', null, 0);
$_smarty_tpl->tpl_vars['usableStylesList']->value[$_smarty_tpl->tpl_vars['usableStylesListConst']->value['MENU_PRO_LEVEL']] = $_tmp4;?>
<?php if ($_smarty_tpl->tpl_vars['menuType']->value==$_smarty_tpl->tpl_vars['menuTypesConst']->value['SECONDARY']) {?>
	<?php ob_start();?><?php echo smartyTranslate(array('s'=>'Nearest relative','mod'=>'menupro'),$_smarty_tpl);?>
<?php $_tmp5=ob_get_clean();?><?php $_smarty_tpl->createLocalArrayVariable('usableStylesList', null, 0);
$_smarty_tpl->tpl_vars['usableStylesList']->value[$_smarty_tpl->tpl_vars['usableStylesListConst']->value['NEAREST_RELATIVE']] = $_tmp5;?>
	<?php ob_start();?><?php echo smartyTranslate(array('s'=>'Current main menu','mod'=>'menupro'),$_smarty_tpl);?>
<?php $_tmp6=ob_get_clean();?><?php $_smarty_tpl->createLocalArrayVariable('usableStylesList', null, 0);
$_smarty_tpl->tpl_vars['usableStylesList']->value[$_smarty_tpl->tpl_vars['usableStylesListConst']->value['MAIN_MENU_LEVEL']] = $_tmp6;?>
	<?php if ($_smarty_tpl->tpl_vars['secondaryMenuLevel']->value>1) {?>
		<?php ob_start();?><?php echo smartyTranslate(array('s'=>'Current highest secondary menu','mod'=>'menupro'),$_smarty_tpl);?>
<?php $_tmp7=ob_get_clean();?><?php $_smarty_tpl->createLocalArrayVariable('usableStylesList', null, 0);
$_smarty_tpl->tpl_vars['usableStylesList']->value[$_smarty_tpl->tpl_vars['usableStylesListConst']->value['HIGHEST_SECONDARY_MENU_LEVEL']] = $_tmp7;?>
	<?php }?>
<?php }?>
<input type="hidden" id="MENUPRO_STYLE_PROPERTIES_LOADED" name="MENUPRO_STYLE_PROPERTIES_LOADED" value="<?php echo intval(isset($_smarty_tpl->tpl_vars['propertiesFormContent']->value));?>
">
<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?><div class="panel"><h3><?php } else { ?><fieldset><legend><?php }?>
	<?php echo smartyTranslate(array('s'=>'Style Sheet','mod'=>'menupro'),$_smarty_tpl);?>

<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?></h3><?php } else { ?></legend><?php }?>
	<div id="divStyleGetStyleNotify" style="display:none;"></div>
	<div id="divStyleEdition" style="display:none;">
		<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>
		<div class="form-group clearfix">
			<label class="control-label col-lg-4 col-md-4 col-sm-4 col-xs-4 mp-label" for="MENUPRO_STYLE_NAME">
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
			<input type="text" id="MENUPRO_STYLE_NAME" name="MENUPRO_STYLE_NAME" value="<?php if (isset($_smarty_tpl->tpl_vars['MENUPRO_STYLE_NAME']->value)) {?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['MENUPRO_STYLE_NAME']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php }?>">
		<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>
			</div>
		</div>
		<?php } else { ?>
		</div>
		<div class="clear"></div>
		<?php }?>
		
		<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>
		<div class="form-group clearfix">
			<label class="control-label col-lg-4 col-md-4 col-sm-4 col-xs-4 mp-label" for="MENUPRO_USABLE_STYLE">
		<?php } else { ?>
		<label>
		<?php }?>
		<?php echo smartyTranslate(array('s'=>'Usable style','mod'=>'menupro'),$_smarty_tpl);?>

		<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>
		</label>
		<?php } else { ?>
		</label>
		<div class="div-usable-style margin-form">
		<?php }?>
			<select id="MENUPRO_USABLE_STYLE" class="col-lg-5 col-md-5 col-sm-5 col-xs-5" name="MENUPRO_USABLE_STYLE">
				<?php  $_smarty_tpl->tpl_vars['usableStyleName'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['usableStyleName']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['usableStylesList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['usableStyleName']->key => $_smarty_tpl->tpl_vars['usableStyleName']->value) {
$_smarty_tpl->tpl_vars['usableStyleName']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['usableStyleName']->key;
?>
					<option value="<?php echo intval($_smarty_tpl->tpl_vars['k']->value);?>
" <?php if (isset($_smarty_tpl->tpl_vars['MENUPRO_USABLE_STYLE']->value)&&($_smarty_tpl->tpl_vars['MENUPRO_USABLE_STYLE']->value==$_smarty_tpl->tpl_vars['k']->value)) {?>selected<?php }?>><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['usableStyleName']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</option>
				<?php } ?>
			</select>
		<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>
		</div>
		<?php } else { ?>
		</div>
		<div class="clear"></div>
		<?php }?>
		
		<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>
		<div id="divUsableStyleName" class="form-group clearfix" style="display:none;">
			<label class="control-label col-lg-4 col-md-4 col-sm-4 col-xs-4 mp-label" for="txtUsableStyleName">
		<?php } else { ?>
		<div id="divUsableStyleName" style="display:none;">
		<label>
		<?php }?>
		<?php echo smartyTranslate(array('s'=>'Usable style name','mod'=>'menupro'),$_smarty_tpl);?>

		<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>
		</label>
		<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
		<?php } else { ?>
		</label>
		<div class="margin-form">
		<?php }?>
			<input type="text" id="txtUsableStyleName" value="" disabled>
		<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>
			</div>
		</div>
		<?php } else { ?>
		</div>
		</div>
		<div class="clear"></div>
		<?php }?>
		
		<input type="hidden" id="MENUPRO_STYLE_ID" name="MENUPRO_STYLE_ID" value="<?php echo intval($_smarty_tpl->tpl_vars['MENUPRO_STYLE_ID']->value);?>
">
		<input type="hidden" id="MENUPRO_STYLE_MENU_LEVEL" name="MENUPRO_STYLE_MENU_LEVEL" value="<?php echo intval($_smarty_tpl->tpl_vars['MENUPRO_STYLE_MENU_LEVEL']->value);?>
">
		<div class="divProperties">
			<?php if (isset($_smarty_tpl->tpl_vars['propertiesFormContent']->value)) {?><?php ob_start();?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['displayHtmlTemplate']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php $_tmp8=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ($_tmp8, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('htmlContent'=>$_smarty_tpl->tpl_vars['propertiesFormContent']->value), 0);?>
<?php }?>
		</div>
		<div class="<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>panel-footer<?php } else { ?>margin-form menupro toolbarBox<?php }?>">
			<button type="submit" value="1" id="btnSaveStyleMenu" name="submitSaveStyleMenu" class="<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>btn btn-default<?php } else { ?>mp-button<?php }?> pull-right btnSave">
				<i class="process-icon-save<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?> mp-icon<?php }?>"></i><?php echo smartyTranslate(array('s'=>'Save','mod'=>'menupro'),$_smarty_tpl);?>

			</button>
			<a href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['homeLink']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" class="<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>btn btn-default<?php } else { ?>mp-button<?php }?> pull-left btnCancel">
				<i class="process-icon-back<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?> mp-icon<?php }?>"></i><?php if ($_smarty_tpl->tpl_vars['menuType']->value==$_smarty_tpl->tpl_vars['menuTypesConst']->value['SECONDARY']) {?><?php echo smartyTranslate(array('s'=>'Quit','mod'=>'menupro'),$_smarty_tpl);?>
<?php } else { ?><?php echo smartyTranslate(array('s'=>'Back to list','mod'=>'menupro'),$_smarty_tpl);?>
<?php }?>
			</a>
			<button type="submit" id="btnSaveAndStayStyleMenu" class="<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>btn btn-default<?php } else { ?>mp-button<?php }?> pull-right btnSaveAndStayMenu btnSaveAndStay" name="staymainmemu">
				<i class="<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>process-icon-save<?php } else { ?>process-icon-save-and-stay mp-icon<?php }?>"></i><?php echo smartyTranslate(array('s'=>'Save and stay','mod'=>'menupro'),$_smarty_tpl);?>

			</button>
		</div>
	</div>
<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?></div><?php } else { ?></fieldset><?php }?><?php }} ?>
