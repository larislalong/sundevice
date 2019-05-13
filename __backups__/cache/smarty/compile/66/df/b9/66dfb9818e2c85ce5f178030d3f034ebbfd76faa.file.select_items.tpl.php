<?php /* Smarty version Smarty-3.1.19, created on 2019-01-29 15:24:51
         compiled from "/home/sundevice/public_html/modules/menupro/views/templates/admin/select_items.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1888722355c50623371a9e0-29316107%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '66dfb9818e2c85ce5f178030d3f034ebbfd76faa' => 
    array (
      0 => '/home/sundevice/public_html/modules/menupro/views/templates/admin/select_items.tpl',
      1 => 1511549306,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1888722355c50623371a9e0-29316107',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'btnSelectItemSaveText' => 0,
    'btnSelectItemCloseText' => 0,
    'ps_version' => 0,
    'availableSecondaryMenuType' => 0,
    'secondaryMenuType' => 0,
    'availableSecondaryMenuTypeConst' => 0,
    'searchMethodConst' => 0,
    'searchByName' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c5062337746c6_16942417',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c5062337746c6_16942417')) {function content_5c5062337746c6_16942417($_smarty_tpl) {?>

<?php ob_start();?><?php echo smartyTranslate(array('s'=>'Save items','mod'=>'menupro'),$_smarty_tpl);?>
<?php $_tmp9=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['btnSelectItemSaveText'] = new Smarty_variable($_tmp9, null, 0);?>
<?php ob_start();?><?php echo smartyTranslate(array('s'=>'Close','mod'=>'menupro'),$_smarty_tpl);?>
<?php $_tmp10=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['btnSelectItemCloseText'] = new Smarty_variable($_tmp10, null, 0);?>
<script type="text/javascript">
	var btnSelectItemSaveText = "<?php echo $_smarty_tpl->tpl_vars['btnSelectItemSaveText']->value;?>
";
	var btnSelectItemCloseText = "<?php echo $_smarty_tpl->tpl_vars['btnSelectItemCloseText']->value;?>
";
</script>
<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>
<div class="modal fade" id="modalSelectItems" tabindex="-1" role="dialog" aria-labelledby="modalSelectItemsTitle" aria-hidden="true">
	<div class="modal-dialog modal-form-edition" role="document">
		<div class="modal-content">
			<div class="modal-header clearfix">
				<h5 class="modal-title pull-left" id="modalSelectItemsTitle"><?php echo smartyTranslate(array('s'=>'Close','mod'=>'menupro'),$_smarty_tpl);?>
</h5>
				<button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
<?php } else { ?>
<div id="dialogModalParent">
	<div id="modalSelectItems" title="">
		<div id="content">
<?php }?>
				<div id="divSelectItemNotify" style="display:none;"></div>
				<div class="collapse-group <?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>panel-group<?php } else { ?><?php }?>" id="selectItemAccordion"<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?> role="tablist" aria-multiselectable="true"<?php }?>>
					<?php  $_smarty_tpl->tpl_vars['secondaryMenuType'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['secondaryMenuType']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['availableSecondaryMenuType']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['secondaryMenuType']->key => $_smarty_tpl->tpl_vars['secondaryMenuType']->value) {
$_smarty_tpl->tpl_vars['secondaryMenuType']->_loop = true;
?>
						<div class="collapse-item <?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>panel-mp panel panel-default<?php } else { ?><?php }?>" data-id="<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
">
							<div class="<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>panel-heading-mp panel-heading<?php } else { ?>with-icon<?php }?> collapse-head" role="tab" id="select-item-headingOne<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
">
								<div class="panel-title-mp panel-title">
									<a data-toggle="collapse" data-parent="#selectItemAccordion"
										role="button" aria-expanded="true" 
										aria-controls="select-item-collapseOne<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
"
										href="#select-item-collapseOne<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
" class="collapse-action link-head-mp" >
										<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?><span class="icon-plus"></span><?php }?>
										<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['secondaryMenuType']->value['name'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>

									</a>
								</div>
							</div>
							<div id="select-item-collapseOne<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
" class="panel-collapse collapse collapse-target" role="tabpanel" 
								aria-labelledby="select-item-headingOne<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
" data-id="<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
"
								data-empty-message="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['secondaryMenuType']->value['emptyMessage'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
">
								<div class="panel-body">
									<?php if ($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']!=$_smarty_tpl->tpl_vars['availableSecondaryMenuTypeConst']->value['CUSTOMISE']) {?>
										<div id="divItems_<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
">
											<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?><div class="clear"></div><?php }?>
											<ul class="nav nav-tabs">
												<li class="active"><a data-toggle="tab" href="#tabList_<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
"><?php echo smartyTranslate(array('s'=>'List','mod'=>'menupro'),$_smarty_tpl);?>
</a></li>
												<li><a data-toggle="tab" href="#tabSearch_<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
"><?php echo smartyTranslate(array('s'=>'Search','mod'=>'menupro'),$_smarty_tpl);?>
</a></li>
											</ul>
											<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?><div class="clear"></div><?php }?>
											<div class="tab-content">
												<div id="tabList_<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
" class="tab-pane fade in active">
													<div id="tabListContent_<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
" style="display:none;" class="<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?>clearfix<?php }?>">
														<?php if ($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']!=$_smarty_tpl->tpl_vars['availableSecondaryMenuTypeConst']->value['CATEGORY']) {?>
															<div id="divItemCurrentPage_<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
" style="display:none;">
																<div id="divItemCurrentPageContent_<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
" class="" style="display:none;"></div>
																<div id="divItemCurrentPageNotify_<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
" style="display:none;"></div>
															</div>
															<div id="divItemPagination_<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
" style="display:none;" class="pull-right">
																<div class="form-inline">
																	<div class="pagination  form-group">
																		<label class="control-label" for="selectItemGoTo_<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
"><?php echo smartyTranslate(array('s'=>'Go to','mod'=>'menupro'),$_smarty_tpl);?>
</label>
																		<select class="form-control" id="selectItemGoTo_<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
"></select>
																	</div>
																	<ul class="pagination form-group">
																		<li>
																			<a id="btnItemPaginatePrevious_<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
" href="#">
																				<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?><i class="icon-double-angle-left"></i><?php } else { ?>&lt;&lt;<?php }?>
																				<?php echo smartyTranslate(array('s'=>'Previous','mod'=>'menupro'),$_smarty_tpl);?>

																			</a>
																		</li>
																		<li>
																			<a id="btnItemPaginateScrollLeft_<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
" href="#">
																				<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?><i class="icon-angle-left"></i><?php } else { ?>&lt;<?php }?>
																				...
																			</a>
																		</li>
																	</ul>
																	<ul id="divPaginationItemsNumbers_<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
" class="pagination form-group"></ul>
																	<ul class="pagination form-group">
																		<li>
																			<a id="btnItemPaginateScrollRight_<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
" href="#">
																				...
																				<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?><i class="icon-angle-right"></i><?php } else { ?>&gt;<?php }?>
																			</a>
																		</li>
																		<li>
																			<a id="btnItemPaginateNext_<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
" href="#">
																				<?php echo smartyTranslate(array('s'=>'Next','mod'=>'menupro'),$_smarty_tpl);?>

																				<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?><i class="icon-double-angle-right"></i><?php } else { ?>&gt;&gt;<?php }?>
																			</a>
																		</li>
																	</ul>
																</div>
																<div class="form-inline mp-center-content">
																	<label class="control-label">
																		<?php echo smartyTranslate(array('s'=>'Page','mod'=>'menupro'),$_smarty_tpl);?>

																		<span id="lblCurrentPage_<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
"></span>
																		<?php echo smartyTranslate(array('s'=>'of','mod'=>'menupro'),$_smarty_tpl);?>

																		<span id="lblPagesCount_<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
"></span>
																	</label>
																</div>
															</div>
														<?php }?>
													</div>
													<div id="tabListNotify_<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
" style="display:none;"></div>
												</div>
												<div id="tabSearch_<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
" class="tab-pane fade">
													<div id="divSearchType_<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
" class="form-group clearfix">
														<label class="control-label col-lg-3 col-md-4 col-sm-5 col-xs-6 mp-label">
															<?php echo smartyTranslate(array('s'=>'Search by','mod'=>'menupro'),$_smarty_tpl);?>

														</label>
														<div class="col-lg-9 col-md-8 col-sm-7 col-xs-6">
															<label class="radio-inline">
																<?php if (isset($_smarty_tpl->tpl_vars['secondaryMenuType']->value['searchName'])) {?><?php $_smarty_tpl->tpl_vars['searchByName'] = new Smarty_variable($_smarty_tpl->tpl_vars['secondaryMenuType']->value['searchName'], null, 0);?><?php } else { ?><?php ob_start();?><?php echo smartyTranslate(array('s'=>'Name','mod'=>'menupro'),$_smarty_tpl);?>
<?php $_tmp11=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['searchByName'] = new Smarty_variable($_tmp11, null, 0);?><?php }?>
																<input type="radio" name="optionSearchBy_<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
" checked data-type="<?php echo intval($_smarty_tpl->tpl_vars['searchMethodConst']->value['BY_NAME']);?>
"  
																id="optionSearchByName_<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
" data-name="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['searchByName']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
">
																<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['searchByName']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>

															</label>
															<label class="radio-inline">
																<input type="radio" name="optionSearchBy_<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
" data-type="<?php echo intval($_smarty_tpl->tpl_vars['searchMethodConst']->value['BY_ID']);?>
" 
																id="optionSearchById_<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
" data-name="<?php echo smartyTranslate(array('s'=>'Id','mod'=>'menupro'),$_smarty_tpl);?>
">
																<?php echo smartyTranslate(array('s'=>'Id','mod'=>'menupro'),$_smarty_tpl);?>

															</label>
														</div>
													</div>
													<div id="divSpecifiedItems_<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
" class="form-group clearfix divSpecifiedItems">
														<label id="lblSearchInput_<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
" class="control-label col-lg-3 col-md-4 col-sm-5 col-xs-6 mp-label" for="itemAutocompleteInput_<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
">
															<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['searchByName']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>

														</label>
														<div class="col-lg-9 col-md-8 col-sm-7 col-xs-6">
															<div id="ajaxChooseItem_<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
">
																<div class="input-group">
																	<input class="input-for-enable" type="text" id="itemAutocompleteInput_<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
" />
																	<span class="input-group-addon"><i id="itemAutocompleteIcon_<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
" class="icon-search"></i></span>
																</div>
															</div>
															<div id="divSearchChosenItems_<?php echo intval($_smarty_tpl->tpl_vars['secondaryMenuType']->value['id']);?>
" class="divSearchChosenItems"></div>
														</div>
													</div>
												</div>
											</div>
										</div>
									<?php } else { ?>
										<div id="divCustomizeItem"  class="<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?>clearfix<?php }?>">
											<label class="checkbox-inline" for="ckbCustomizeItem">
												<input type="checkbox" id="ckbCustomizeItem">
												<?php echo smartyTranslate(array('s'=>'New element','mod'=>'menupro'),$_smarty_tpl);?>

											</label>
											<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?><div class="clear"></div><?php }?>
											<div id="divCustomizeItemQuantity">
												<label for="txtCustomizeItemQuantity"><?php echo smartyTranslate(array('s'=>'Quantity','mod'=>'menupro'),$_smarty_tpl);?>
</label>
												<input type="number" min="1" id="txtCustomizeItemQuantity" class="text" value="1">
												<button type="button" class="<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>btn btn-default<?php } else { ?><?php }?> button-plus" id="btnCustomizeItemPlus">
													<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?><span><i class="icon-plus"></i></span><?php } else { ?>+<?php }?>
												</button>
												<button type="button" class="<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>btn btn-default<?php } else { ?><?php }?> button-minus" id="btnCustomizeItemMinus">
													<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?><span><i class="icon-minus"></i></span><?php } else { ?>-<?php }?>
												</button>
											</div>
										</div>
									<?php }?>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $_smarty_tpl->tpl_vars['btnSelectItemCloseText']->value;?>
</button>
				<button type="button" id="btnSelectItems" class="btn btn-primary"><?php echo $_smarty_tpl->tpl_vars['btnSelectItemSaveText']->value;?>
</button>
			</div>
		</div>
	</div>
</div>
<?php } else { ?>
		</div>
	</div>
</div>
<?php }?><?php }} ?>
