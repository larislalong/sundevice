<?php /* Smarty version Smarty-3.1.19, created on 2019-05-02 09:56:04
         compiled from "/home/sundevice/public_html/modules/menupro/views/templates/admin/secondary_menu_form.tpl" */ ?>
<?php /*%%SmartyHeaderCode:12647533755ccaa294aac2f2-69563180%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '66dbebee7a3e086cba8ab8aa90f7d9f22762a5c8' => 
    array (
      0 => '/home/sundevice/public_html/modules/menupro/views/templates/admin/secondary_menu_form.tpl',
      1 => 1511549306,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12647533755ccaa294aac2f2-69563180',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'mainMenu' => 0,
    'ITEMS_PER_PAGE' => 0,
    'availableSecondaryMenuTypeConst' => 0,
    'itemTypeParams' => 0,
    'imageDisplayTypeFolder' => 0,
    'searchMethodConst' => 0,
    'addSubmenusTitle' => 0,
    'editSubmenuTitle' => 0,
    'deleteSubmenuTitle' => 0,
    'btnSortReorganiseText' => 0,
    'btnSortCloseText' => 0,
    'ps_version' => 0,
    'displayHtmlTemplate' => 0,
    'menuTreeContent' => 0,
    'homeLink' => 0,
    'selectItemsTemplates' => 0,
    'headerTitle' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5ccaa294ae3d19_15807944',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5ccaa294ae3d19_15807944')) {function content_5ccaa294ae3d19_15807944($_smarty_tpl) {?>

<?php ob_start();?><?php echo smartyTranslate(array('s'=>'Reorganize','mod'=>'menupro'),$_smarty_tpl);?>
<?php $_tmp3=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['btnSortReorganiseText'] = new Smarty_variable($_tmp3, null, 0);?>
<?php ob_start();?><?php echo smartyTranslate(array('s'=>'Close','mod'=>'menupro'),$_smarty_tpl);?>
<?php $_tmp4=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['btnSortCloseText'] = new Smarty_variable($_tmp4, null, 0);?>
<script type="text/javascript">
	var mainMenu = <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['json_encode'][0][0]->jsonEncode($_smarty_tpl->tpl_vars['mainMenu']->value);?>
;
	const ITEMS_PER_PAGE = <?php echo intval($_smarty_tpl->tpl_vars['ITEMS_PER_PAGE']->value);?>
;
	var availableSecondaryMenuTypeConst = <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['json_encode'][0][0]->jsonEncode($_smarty_tpl->tpl_vars['availableSecondaryMenuTypeConst']->value);?>
;
	var itemTypeParams = <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['json_encode'][0][0]->jsonEncode($_smarty_tpl->tpl_vars['itemTypeParams']->value);?>
;
	var imageDisplayTypeFolder = "<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['imageDisplayTypeFolder']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
";
	var searchMethodConst = <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['json_encode'][0][0]->jsonEncode($_smarty_tpl->tpl_vars['searchMethodConst']->value);?>
;
	var modalSelecItemTitlePrefix = "<?php echo smartyTranslate(array('s'=>'Select items for','mod'=>'menupro'),$_smarty_tpl);?>
";
	var loaderInitializeAttachableItemListMessage = "<?php echo smartyTranslate(array('s'=>'List initialization...','mod'=>'menupro'),$_smarty_tpl);?>
";
	var loaderSecondaryMenuEditionFormMessage = "<?php echo smartyTranslate(array('s'=>'Loading form...','mod'=>'menupro'),$_smarty_tpl);?>
";
	var loaderDeleteSecondaryMenuMessage = "<?php echo smartyTranslate(array('s'=>'Deleting menu...','mod'=>'menupro'),$_smarty_tpl);?>
";
	var loaderStatusChangeSecondaryMenuMessage = "<?php echo smartyTranslate(array('s'=>'Changing menu status...','mod'=>'menupro'),$_smarty_tpl);?>
";
	var loaderSecondaryMenuEditionUpdateMessage = "<?php echo smartyTranslate(array('s'=>'Updating menu...','mod'=>'menupro'),$_smarty_tpl);?>
";
	var loaderSortMenuMessage = "<?php echo smartyTranslate(array('s'=>'Reorganizing the menu...','mod'=>'menupro'),$_smarty_tpl);?>
";
	var loaderInitializePageMessage = "<?php echo smartyTranslate(array('s'=>'Loading page','mod'=>'menupro'),$_smarty_tpl);?>
";
	var AddItemMessage = "<?php echo smartyTranslate(array('s'=>'Adding items...','mod'=>'menupro'),$_smarty_tpl);?>
";
	var addSubmenusTitle = "<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['addSubmenusTitle']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
";
	var editSubmenuTitle = "<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['editSubmenuTitle']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
";
	var deleteSubmenuTitle = "<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['deleteSubmenuTitle']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
";
	var confirmDeleteSecondaryMenuMessage = "<?php echo smartyTranslate(array('s'=>'Are you sure you want to delete menu','mod'=>'menupro'),$_smarty_tpl);?>
";
	var btnSortReorganiseText = "<?php echo $_smarty_tpl->tpl_vars['btnSortReorganiseText']->value;?>
";
	var btnSortCloseText = "<?php echo $_smarty_tpl->tpl_vars['btnSortCloseText']->value;?>
";
</script>
<div id="divSecondaryMenuNotify" style="display:none;"></div>
<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?><div class="panel"><div class="panel-heading"><?php } else { ?><fieldset class="sm-page-block clearfix"><legend class="header"><span class="sm-page-title"><?php }?>
	<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['mainMenu']->value['name'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>

	<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?></span><span class="sm-page-action menupro toolbarBox"><?php }?>
	<button type="button" value="1" id="btnMainMenuAddSubMenu" class="<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>btn btn-primary<?php } else { ?>mp-button<?php }?> pull-right btn-main-menu-add-new-submenu">
		<i class="<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>icon icon-plus-sign<?php } else { ?>process-icon-new mp-icon<?php }?>"></i><?php echo smartyTranslate(array('s'=>'Add elements','mod'=>'menupro'),$_smarty_tpl);?>

	</button>
	<button type="button" value="1" id="btnSortMenu" class="<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>btn btn-primary<?php } else { ?>mp-button<?php }?> pull-right btn-sort-menu">
		<i class="<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>icon-move<?php } else { ?>process-icon-move mp-icon<?php }?>"></i><?php echo smartyTranslate(array('s'=>'Reorganize the menu','mod'=>'menupro'),$_smarty_tpl);?>

	</button>
<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?></div><?php } else { ?></span></legend><?php }?>
	<div id="secondaryMenuTree" class="<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?>clearfix<?php }?>">
		<?php ob_start();?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['displayHtmlTemplate']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php $_tmp5=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ($_tmp5, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('htmlContent'=>$_smarty_tpl->tpl_vars['menuTreeContent']->value), 0);?>

	</div>
	<div class="<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>panel-footer<?php } else { ?>margin-form menupro toolbarBox clearfix<?php }?>">
		<a href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['homeLink']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" class="<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>btn btn-default<?php } else { ?>mp-button<?php }?> pull-right"  title="<?php echo smartyTranslate(array('s'=>'Back to list','mod'=>'menupro'),$_smarty_tpl);?>
">
			<i class="process-icon-back<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?> mp-icon<?php }?>"></i><?php echo smartyTranslate(array('s'=>'Back to list','mod'=>'menupro'),$_smarty_tpl);?>

		</a>
	</div>
<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?></div><?php } else { ?></fieldset><?php }?>
<?php ob_start();?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['selectItemsTemplates']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php $_tmp6=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ($_tmp6, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<div id="divSecondaryMenuEditionParent"></div>

<?php ob_start();?><?php echo smartyTranslate(array('s'=>'Reorganize ','mod'=>'menupro'),$_smarty_tpl);?>
<?php $_tmp7=ob_get_clean();?><?php ob_start();?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['mainMenu']->value['name'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php $_tmp8=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['headerTitle'] = new Smarty_variable($_tmp7." : ".$_tmp8, null, 0);?>
<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>
<div class="modal fade" id="modalSortSecondaryMenu" tabindex="-1" role="dialog" aria-labelledby="modalSortSecondaryMenuTitle" aria-hidden="true">
	<div class="modal-dialog modal-form-edition" role="document">
		<div class="modal-content">
			<div class="modal-header clearfix">
				<h5 class="modal-title pull-left" id="modalSortSecondaryMenuTitle"><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['headerTitle']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</h5>
				<button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
<?php } else { ?>
<div id="dialogModalParent">
	<div id="modalSortSecondaryMenu" title="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['headerTitle']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
">
		<div id="content">
<?php }?>
				<div id="divSortSecondaryMenuNotify" style="display:none;"></div>
				<div id="modalSortSecondaryMenuContent">
					
				</div>
<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $_smarty_tpl->tpl_vars['btnSortCloseText']->value;?>
</button>
				<button type="button" id="btnConfirmSort" class="btn btn-primary"><?php echo $_smarty_tpl->tpl_vars['btnSortReorganiseText']->value;?>
</button>
			</div>
		</div>
	</div>
</div>
<?php } else { ?>
		</div>
	</div>
</div>
<?php }?>
<?php }} ?>
