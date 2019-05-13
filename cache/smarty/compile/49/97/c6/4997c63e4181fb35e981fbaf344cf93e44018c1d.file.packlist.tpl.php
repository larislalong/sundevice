<?php /* Smarty version Smarty-3.1.19, created on 2019-04-29 16:48:35
         compiled from "/home/sundevice/public_html/override/controllers/admin/templates/products/packlist.tpl" */ ?>
<?php /*%%SmartyHeaderCode:12828857365cc70ec3752a24-63333187%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4997c63e4181fb35e981fbaf344cf93e44018c1d' => 
    array (
      0 => '/home/sundevice/public_html/override/controllers/admin/templates/products/packlist.tpl',
      1 => 1518187958,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12828857365cc70ec3752a24-63333187',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'selectedPacks' => 0,
    'pack' => 0,
    'link' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cc70ec3766ce4_80238987',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cc70ec3766ce4_80238987')) {function content_5cc70ec3766ce4_80238987($_smarty_tpl) {?>
<div id="product-packlist" class="panel product-tab">
	<input type="hidden" name="submitted_tabs[]" value="PackList" />
	<h3><?php echo smartyTranslate(array('s'=>'Pack List'),$_smarty_tpl);?>
</h3>
	<div class="form-group clearfix">
		<label class="control-label col-lg-4 col-md-4 col-sm-4 col-xs-4" for="pack_autocomplete_input">
			<?php echo smartyTranslate(array('s'=>'Packs'),$_smarty_tpl);?>

		</label>
		<div class="col-lg-5 col-md-5 col-sm-6 col-xs-7">
			<div id="ajax_choose_category">
				<div class="input-group">
					<input class="" type="text" id="pack_autocomplete_input" />
					<span class="input-group-addon"><i class="icon-search"></i></span>
				</div>
			</div>
			<div id="divSelectedPack">
				<?php  $_smarty_tpl->tpl_vars['pack'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['pack']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['selectedPacks']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['pack']->key => $_smarty_tpl->tpl_vars['pack']->value) {
$_smarty_tpl->tpl_vars['pack']->_loop = true;
?>
				<div class="form-control-static parentDivDelPack">
					<input name="selectedPacks[]" class="inputSelectedPack" type="checkbox" value="<?php echo $_smarty_tpl->tpl_vars['pack']->value['id_pack'];?>
" checked  style="display:none;" />
					<button type="button" class="delPack btn btn-default" data-id-product="<?php echo $_smarty_tpl->tpl_vars['pack']->value['id_pack'];?>
">
						<i class="icon-remove text-danger"></i>
					</button>&nbsp;<?php echo $_smarty_tpl->tpl_vars['pack']->value['product']->name;?>
<label style="margin-left:50px;" for="radiopack_<?php echo $_smarty_tpl->tpl_vars['pack']->value['id_pack'];?>
"><?php echo smartyTranslate(array('s'=>'Default'),$_smarty_tpl);?>
<input id="radiopack_<?php echo $_smarty_tpl->tpl_vars['pack']->value['id_pack'];?>
" type="radio" name="default_pack" value="<?php echo $_smarty_tpl->tpl_vars['pack']->value['id_pack'];?>
" <?php if ($_smarty_tpl->tpl_vars['pack']->value['is_default']) {?>checked<?php }?> /></label>
				</div>
				<?php } ?>
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
<script type="text/javascript">
	$(document).ready(function(){
		$('#pack_autocomplete_input').autocomplete('ajax_products_list.php?exclude_packs=0&excludeVirtuals=0', {
			minChars: 1,
			autoFill: true,
			max: 20,
			matchContains: true,
			mustMatch: false,
			scroll: false,
			cacheLength: 0,
			formatItem: function (item) {
				return item[1] + ' - ' + item[0];
			}
		}).result(addProductPack);
		setPackToExclude();
		function addProductPack(event, data, formatted){
			if (data == null)
				return false;
			var productId = data[1];
			var productName = data[0];

			var divSelectedPack = $('#divSelectedPack');
			divSelectedPack.append( '<div class="form-control-static parentDivDelPack"><input name="selectedPacks[]" class="inputSelectedPack" type="checkbox" value="'+productId+
			'" checked  style="display:none;" /><button type="button" class="delPack btn btn-default" data-id-product="' + productId + 
			'"><i class="icon-remove text-danger"></i></button>&nbsp;' + productName + 
			'<label style="margin-left:50px;" for="radiopack_'+productId+'"><?php echo smartyTranslate(array('s'=>'Default'),$_smarty_tpl);?>
<input type="radio" name="default_pack" value="'+productId+'" id=radiopack_"'+productId+'" /></label></div>');
			$('#pack_autocomplete_input').val('');
			setPackToExclude();
			
		}
		
		function setPackToExclude(){
			var checked = [];
			checked.push(id_product);
			$('#divSelectedPack .inputSelectedPack').each(function ()
			{
				checked.push($(this).val());
			});
			$('#pack_autocomplete_input').setOptions({
				extraParams: {excludeIds: checked.join(',')}
			});
		}
		
		$('#divSelectedPack').delegate('.delPack', 'click', function () {
			$(this).closest('.parentDivDelPack').remove();
			setPackToExclude();
        });
	});
</script>
<?php }} ?>
