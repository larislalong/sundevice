<?php /* Smarty version Smarty-3.1.19, created on 2019-05-02 09:56:04
         compiled from "/home/sundevice/public_html/modules/menupro/views/templates/admin/header_tree_secondary.tpl" */ ?>
<?php /*%%SmartyHeaderCode:18221602315ccaa294995490-50453652%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '58f503d614580e9107e1e906cf4a51e0d1a7feac' => 
    array (
      0 => '/home/sundevice/public_html/modules/menupro/views/templates/admin/header_tree_secondary.tpl',
      1 => 1511549306,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18221602315ccaa294995490-50453652',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'firstItems' => 0,
    'ps_version' => 0,
    'secondaryMenu' => 0,
    'addSubmenusTitle' => 0,
    'editSubmenuTitle' => 0,
    'deleteSubmenuTitle' => 0,
    'enableMessage' => 0,
    'disableMessage' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5ccaa294a167a3_99762369',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5ccaa294a167a3_99762369')) {function content_5ccaa294a167a3_99762369($_smarty_tpl) {?>
<?php if ($_smarty_tpl->tpl_vars['firstItems']->value) {?>
<div class="collapse-group <?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>panel-group<?php } else { ?><?php }?>" id="accordionMenu<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenu']->value['parent_menu']);?>
" role="tablist" aria-multiselectable="true">
<?php }?>
	<div id="newMenuPanel<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenu']->value['id_menupro_secondary_menu']);?>
" class="collapse-item <?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>panel-mp panel panel-default<?php } else { ?>clearfix<?php }?> new-menu-panel">
		<div class="<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>panel-heading-mp panel-heading<?php } else { ?>clearfix<?php }?> collapse-head" role="tab" id="headingMenu<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenu']->value['id_menupro_secondary_menu']);?>
">
			<h4 class="panel-title col-lg-10 col-md-9 col-sm-8 col-xs-7 panel-title-mp">
				<a class="link-head-mp collapse-action" data-toggle="collapse" role="button" aria-expanded="true"  
				aria-controls="collapseMenu<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenu']->value['id_menupro_secondary_menu']);?>
"
				data-target="#collapseMenu<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenu']->value['id_menupro_secondary_menu']);?>
">
					<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['secondaryMenu']->value['name'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>

				</a>
			</h4>
			<div id="menuAction<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenu']->value['id_menupro_secondary_menu']);?>
" class="menu-action pull-right col-lg-2 col-md-3 col-sm-4 col-xs-5" 
			data-id="<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenu']->value['id_menupro_secondary_menu']);?>
"
				data-position="<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenu']->value['position']);?>
" data-level="<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenu']->value['level']);?>
"
				data-name="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['secondaryMenu']->value['name'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" data-item-type="<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenu']->value['item_type']);?>
">
				<a title="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['addSubmenusTitle']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" href="#" class="add-submenus pull-right">
					<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>
					<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['addSubmenusTitle']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" data-html="true" data-placement="top">
						<i class="process-icon-new"></i>
					</span>
					<?php } else { ?>
					<img src="../img/admin/add.gif" alt="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['addSubmenusTitle']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
">
					<?php }?>
				</a>
				<a title="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['editSubmenuTitle']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" href="#" class="edit-submenu pull-right">
					<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>
					<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['editSubmenuTitle']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" data-html="true" data-placement="top">
						<i class="process-icon-edit"></i>
					</span>
					<?php } else { ?>
					<img src="../img/admin/edit.gif" alt="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['editSubmenuTitle']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
">
					<?php }?>
				</a>
				<a title="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['deleteSubmenuTitle']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" href="#" class="delete-submenu pull-right">
					<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>
					<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['deleteSubmenuTitle']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" data-html="true" data-placement="top">
						<i class="process-icon-delete"></i>
					</span>
					<?php } else { ?>
					<img src="../img/admin/delete.gif" alt="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['deleteSubmenuTitle']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
">
					<?php }?>
				</a>
				<?php ob_start();?><?php echo smartyTranslate(array('s'=>'Enabled','mod'=>'menupro'),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['enableMessage'] = new Smarty_variable($_tmp1, null, 0);?>
				<?php ob_start();?><?php echo smartyTranslate(array('s'=>'Disabled','mod'=>'menupro'),$_smarty_tpl);?>
<?php $_tmp2=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['disableMessage'] = new Smarty_variable($_tmp2, null, 0);?>
				<div id="divMenuStatus<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenu']->value['id_menupro_secondary_menu']);?>
" class="status-change-submenu pull-right">
					<a class="mp-icon-status list-action-enable <?php if ($_smarty_tpl->tpl_vars['secondaryMenu']->value['active']) {?>action-enabled<?php } else { ?>action-disabled<?php }?>" href="#" 
					title="<?php if ($_smarty_tpl->tpl_vars['secondaryMenu']->value['active']) {?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['enableMessage']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php } else { ?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['disableMessage']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php }?>">
						<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>
						<span title="" data-toggle="tooltip" class="label-tooltip" 
						data-original-title="<?php if ($_smarty_tpl->tpl_vars['secondaryMenu']->value['active']) {?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['enableMessage']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php } else { ?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['disableMessage']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php }?>" 
						data-html="true" data-placement="top">
						<?php if ($_smarty_tpl->tpl_vars['secondaryMenu']->value['active']) {?>
							<i class="icon-check"></i>
						<?php } else { ?>
							<i class="icon-remove"></i>
						<?php }?>
						</span>
						<?php } else { ?>
						<img src="../img/admin/<?php if ($_smarty_tpl->tpl_vars['secondaryMenu']->value['active']) {?>enabled<?php } else { ?>disabled<?php }?>.gif" alt="<?php if ($_smarty_tpl->tpl_vars['secondaryMenu']->value['active']) {?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['enableMessage']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php } else { ?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['disableMessage']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php }?>">
						<?php }?>
					</a>
				</div>
			</div>
		</div>
		<div id="collapseMenu<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenu']->value['id_menupro_secondary_menu']);?>
" class="panel-collapse collapse collapse-target" 
		role="tabpanel" aria-labelledby="headingMenu<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenu']->value['id_menupro_secondary_menu']);?>
">
			<div id="newMenuPanelBody<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenu']->value['id_menupro_secondary_menu']);?>
" class="panel-body new-menu-panel-body"><?php }} ?>
