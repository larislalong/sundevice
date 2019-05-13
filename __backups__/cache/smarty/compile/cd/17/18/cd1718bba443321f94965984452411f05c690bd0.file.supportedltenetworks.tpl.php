<?php /* Smarty version Smarty-3.1.19, created on 2019-01-31 15:49:13
         compiled from "/home/sundevice/public_html/override/controllers/admin/templates/products/supportedltenetworks.tpl" */ ?>
<?php /*%%SmartyHeaderCode:10674203165c530ae935d583-04896843%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cd1718bba443321f94965984452411f05c690bd0' => 
    array (
      0 => '/home/sundevice/public_html/override/controllers/admin/templates/products/supportedltenetworks.tpl',
      1 => 1518187958,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '10674203165c530ae935d583-04896843',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'product' => 0,
    'preferencesLink' => 0,
    'productLTEList' => 0,
    'i' => 0,
    'productLTE' => 0,
    'link' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c530ae94c45d6_10506563',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c530ae94c45d6_10506563')) {function content_5c530ae94c45d6_10506563($_smarty_tpl) {?>

<?php if (isset($_smarty_tpl->tpl_vars['product']->value->id)) {?>
	<style type="text/css">
	
	#product-supportedltenetworks .lte_content{ padding-top: 20px;}
	#content #product-supportedltenetworks .lte_content h3{ margin: 0px;}
	#product-supportedltenetworks .table-productLTE table{ width: 100%;}
	#product-supportedltenetworks .table-productLTE .countries_dl {width: 490px; padding-top: 18px;}
	#product-supportedltenetworks .table-productLTE table th {width: 245px; vertical-align: top;  padding: 0px;}
	#product-supportedltenetworks .table-productLTE table th h3{
		margin: 0px 16px 0px 0px;
		border-bottom: 2px solid #333;
		text-align: left;
		color: #333;
		font-size: 16px;
		line-height: 1.5000;
		text-transform: capitalize;
	}
	#product-supportedltenetworks .table-productLTE .countries_dt {float: left;width: 290px;}
	#product-supportedltenetworks .table-productLTE .countries_dd {float: left; padding-bottom: 18px;}
	
	</style>
	<div id="product-supportedltenetworks" class="panel product-tab">
		<input type="hidden" class="productId" value="<?php echo $_smarty_tpl->tpl_vars['product']->value->id;?>
" />
		<input type="hidden" name="submitted_tabs[]" value="SupportedLTENetworks" />
		<h3><?php echo smartyTranslate(array('s'=>'Supported LTE Networks by model'),$_smarty_tpl);?>
</h3>
		<div class="form-group">
			<div class="alert alert-info">
				<?php echo smartyTranslate(array('s'=>'To edit identifier of the model attribute, '),$_smarty_tpl);?>
<a target="_blank" class="" href="<?php echo $_smarty_tpl->tpl_vars['preferencesLink']->value;?>
"><?php echo smartyTranslate(array('s'=>'Click here'),$_smarty_tpl);?>
</a>
			</div>
		</div>
		<div id="divProductLTENotify" style="display:none;"></div>
		<div class="form-group" id="productLTE-EditionForm" style="display:none;">
			
		</div>
		<div>
			<div id="divProductLTEListDisabler" style="display:none; position: absolute;width: 98%; height: 96%; background-color: #000; z-index: 9; opacity: 0.3;" class="div-disabler"></div>
			<div class="panel">
				<div class="panel-heading head-productLTE">
					<?php echo smartyTranslate(array('s'=>'Models'),$_smarty_tpl);?>
<span class="badge productLTE-count"><?php echo count($_smarty_tpl->tpl_vars['productLTEList']->value);?>
</span>
				</div>	
				<div class="table-responsive-row clearfix">
					<table class="table table-productLTE">
						<thead>
							<tr class="nodrag nodrop">
								<th class=""><span class="title_box"><?php echo smartyTranslate(array('s'=>'Model name'),$_smarty_tpl);?>
</span></th>
								<th class=""><span class="title_box"><?php echo smartyTranslate(array('s'=>'Content'),$_smarty_tpl);?>
</span></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_variable(1, null, 0);?>
							<?php  $_smarty_tpl->tpl_vars['productLTE'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['productLTE']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['productLTEList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['productLTE']->key => $_smarty_tpl->tpl_vars['productLTE']->value) {
$_smarty_tpl->tpl_vars['productLTE']->_loop = true;
?>
							<tr class="tr-productLTE <?php if ($_smarty_tpl->tpl_vars['i']->value%2!=0) {?>odd<?php }?>">
								<input type="hidden" name="" value="<?php echo intval($_smarty_tpl->tpl_vars['productLTE']->value['id_product_supported_lte']);?>
" class="td_id_product_supported_lte">
								<input type="hidden" name="" value="<?php echo intval($_smarty_tpl->tpl_vars['productLTE']->value['id_attribute']);?>
" class="td_id_attribute">
								<td><?php echo $_smarty_tpl->tpl_vars['productLTE']->value['attributeName'];?>
</td>
								<td class="lte_content"><?php echo $_smarty_tpl->tpl_vars['productLTE']->value['content'];?>
</td>
								<td class="text-right">
									<a href="#" class="btn btn-default productLTE-edit" title="<?php echo smartyTranslate(array('s'=>'Edit'),$_smarty_tpl);?>
"><i class="icon-pencil"></i><?php echo smartyTranslate(array('s'=>'Edit'),$_smarty_tpl);?>
</a>
								</td>
							</tr>
							<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_variable($_smarty_tpl->tpl_vars['i']->value+1, null, 0);?>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="panel-footer">
			<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getAdminLink('AdminProducts'), ENT_QUOTES, 'UTF-8', true);?>
<?php if (isset($_REQUEST['page'])&&$_REQUEST['page']>1) {?>&amp;submitFilterproduct=<?php echo intval($_REQUEST['page']);?>
<?php }?>" class="btn btn-default"><i class="process-icon-cancel"></i> <?php echo smartyTranslate(array('s'=>'Cancel'),$_smarty_tpl);?>
</a>
			<button type="submit" name="submitAddproduct" class="btn btn-default pull-right" disabled="disabled"><i class="process-icon-loading"></i> <?php echo smartyTranslate(array('s'=>'Save'),$_smarty_tpl);?>
</button>
			<button type="submit" name="submitAddproductAndStay" class="btn btn-default pull-right" disabled="disabled"><i class="process-icon-loading"></i> <?php echo smartyTranslate(array('s'=>'Save and stay'),$_smarty_tpl);?>
</button>
		</div>
	</div>
<?php }?>
<?php }} ?>
