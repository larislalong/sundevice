<?php /* Smarty version Smarty-3.1.19, created on 2019-01-29 15:25:04
         compiled from "/home/sundevice/public_html/modules/menupro/views/templates/admin/html_contents_block.tpl" */ ?>
<?php /*%%SmartyHeaderCode:10101371635c506240c321d6-27637685%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1a3c396e7daf4254fb31398669900b1d5a3de364' => 
    array (
      0 => '/home/sundevice/public_html/modules/menupro/views/templates/admin/html_contents_block.tpl',
      1 => 1511549306,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '10101371635c506240c321d6-27637685',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'ps_version' => 0,
    'displayHtmlTemplate' => 0,
    'htmlContentList' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c506240c5aaa9_55086219',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c506240c5aaa9_55086219')) {function content_5c506240c5aaa9_55086219($_smarty_tpl) {?>
<script type="text/javascript">
	var loaderGetEditionHtmlContentMessage = "<?php echo smartyTranslate(array('s'=>'Getting edition form...','mod'=>'menupro'),$_smarty_tpl);?>
";
	var loaderSaveHtmlContentMessage = "<?php echo smartyTranslate(array('s'=>'Saving html content...','mod'=>'menupro'),$_smarty_tpl);?>
";
	var loaderDeleteHtmlContentMessage = "<?php echo smartyTranslate(array('s'=>'deleting html content...','mod'=>'menupro'),$_smarty_tpl);?>
";
	var confirmDeleteHtmlContentMessage = "<?php echo smartyTranslate(array('s'=>'Are you sure you want to delete content','mod'=>'menupro'),$_smarty_tpl);?>
";
	var loaderStatusChangeHtmlContentMessage = "<?php echo smartyTranslate(array('s'=>'changing status html content...','mod'=>'menupro'),$_smarty_tpl);?>
";
</script>
<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?><div class="panel"><h3><?php } else { ?><fieldset><legend><?php }?>
	<?php echo smartyTranslate(array('s'=>'Html contents','mod'=>'menupro'),$_smarty_tpl);?>

<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?></h3><?php } else { ?></legend><?php }?>
	<div id="divHtmlContentNotify" style="display:none;"></div>
	<div class="row<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?><?php } else { ?> clearfix<?php }?>">
		<div id="divHtmlContentList" class="col-lg-5 col-md-5">
			<div id="divHtmlContentListDisabler" style="display:none;" class="div-disabler"></div>
			<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?><div id="form-menupro_html_content"><?php }?>
			<?php ob_start();?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['displayHtmlTemplate']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php $_tmp1=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ($_tmp1, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('htmlContent'=>$_smarty_tpl->tpl_vars['htmlContentList']->value), 0);?>

			<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?></div><?php }?>
		</div>
		<div id="divHtmlContentEdition" class="<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>col-lg-7 col-md-7<?php } else { ?>col-lg-12<?php }?>" style="display:none;">
			<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?><div class="panel" id="panelHtmlContentEditionNotify"><h3><?php } else { ?><fieldset id="panelHtmlContentEditionNotify"><legend><?php }?>
				<?php echo smartyTranslate(array('s'=>'Html content edition','mod'=>'menupro'),$_smarty_tpl);?>

			<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?></h3><?php } else { ?></legend><?php }?>
				<div id="divHtmlContentEditionNotify" style="display:none;"></div>
			<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?></div><?php } else { ?></fieldset><?php }?>
			<div id="divHtmlContentForm" style="display:none;"></div>
			<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?>
			<div id="divHtmlContentFormButtons" style="display:none;">
				<div class="margin-form menupro toolbarBox clearfix">
					<button type="button" class="pull-left mp-button btnCancel" name="cancelsecondarymemu" id="btnHtmlContentCancel">
						<i class="process-icon-cancel mp-icon"></i><?php echo smartyTranslate(array('s'=>'Cancel','mod'=>'menupro'),$_smarty_tpl);?>

					</button>
					<button type="button" name="savesecondarymemu" class="pull-right mp-button btnSave" id="btnHtmlContentSave">
						<i class="process-icon-save mp-icon"></i><?php echo smartyTranslate(array('s'=>'Save','mod'=>'menupro'),$_smarty_tpl);?>

					</button>
				</div>
			</div>
			<?php }?>
		</div>
	</div>
	<div class="<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>panel-footer<?php } else { ?>margin-form menupro toolbarBox clearfix<?php }?>">
		<button type="button" class="<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>btn btn-default<?php } else { ?>mp-button<?php }?> pull-right btnCancel" title="<?php echo smartyTranslate(array('s'=>'Quit','mod'=>'menupro'),$_smarty_tpl);?>
">
			<i class="process-icon-back<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?> mp-icon<?php }?>"></i><?php echo smartyTranslate(array('s'=>'Quit','mod'=>'menupro'),$_smarty_tpl);?>

		</button>
	</div>
<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?></div><?php } else { ?></fieldset><?php }?><?php }} ?>
