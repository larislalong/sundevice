<?php /* Smarty version Smarty-3.1.19, created on 2019-01-31 15:49:13
         compiled from "/home/sundevice/public_html/modules/imagebycolorattrib/views/templates/admin/product_attribute_image.tpl" */ ?>
<?php /*%%SmartyHeaderCode:13579456485c530ae90efea6-90484434%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9b0c21464089c9daec5fbd860fce265b0da8de59' => 
    array (
      0 => '/home/sundevice/public_html/modules/imagebycolorattrib/views/templates/admin/product_attribute_image.tpl',
      1 => 1518187446,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '13579456485c530ae90efea6-90484434',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'moduleLink' => 0,
    'idProduct' => 0,
    'attributesList' => 0,
    'data' => 0,
    'imageListTemplate' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c530ae9153318_91834699',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c530ae9153318_91834699')) {function content_5c530ae9153318_91834699($_smarty_tpl) {?>

<script type="text/javascript">
	var ibcaAjaxUrl = "<?php echo $_smarty_tpl->tpl_vars['moduleLink']->value;?>
";
	var LOADING_FORM_MESSAGE = "<?php echo smartyTranslate(array('s'=>'Loading form...','mod'=>'imagebycolorattrib'),$_smarty_tpl);?>
";
	var SAVING_MESSAGE = "<?php echo smartyTranslate(array('s'=>'Saving data...','mod'=>'imagebycolorattrib'),$_smarty_tpl);?>
";
	var ERROR_MESSAGE = "<?php echo smartyTranslate(array('s'=>'An error occured while connecting to server','mod'=>'imagebycolorattrib'),$_smarty_tpl);?>
";
</script>
<div id="product-imagesbyattribute" class="panel product-tab">
	<input type="hidden" class="productId" value="<?php echo $_smarty_tpl->tpl_vars['idProduct']->value;?>
" />
	<h3><?php echo smartyTranslate(array('s'=>'Image by color','mod'=>'imagebycolorattrib'),$_smarty_tpl);?>
</h3>
	<div class="form-group">
		<div class="alert alert-info">
			<?php echo smartyTranslate(array('s'=>'To edit identifier of the color attribute, ','mod'=>'imagebycolorattrib'),$_smarty_tpl);?>
<a target="_blank" class="" href="<?php echo $_smarty_tpl->tpl_vars['moduleLink']->value;?>
"><?php echo smartyTranslate(array('s'=>'Click here','mod'=>'imagebycolorattrib'),$_smarty_tpl);?>
</a>
		</div>
	</div>
	<div class="divNotify" style="display:none;"></div>
	<div class="form-group divEditionForm" style="display:none;">
		
	</div>
	<div>
		<div class="divListDisabler" style="display:none; position: absolute;width: 98%; height: 96%; background-color: #000; z-index: 9; opacity: 0.3;" class="div-disabler"></div>
		<div class="panel">
			<div class="panel-heading head-list">
				<?php echo smartyTranslate(array('s'=>'Colors'),$_smarty_tpl);?>
<span class="badge list-count"><?php echo count($_smarty_tpl->tpl_vars['attributesList']->value);?>
</span>
			</div>	
			<div class="table-responsive-row clearfix">
				<table class="table table-items">
					<thead>
						<tr class="nodrag nodrop">
							<th class=""><span class="title_box"><?php echo smartyTranslate(array('s'=>'Color name','mod'=>'imagebycolorattrib'),$_smarty_tpl);?>
</span></th>
							<?php if ('showImagesInList') {?>
							<th class=""><span class="title_box"><?php echo smartyTranslate(array('s'=>'Images','mod'=>'imagebycolorattrib'),$_smarty_tpl);?>
</span></th>
							<th class=""><span class="title_box"><?php echo smartyTranslate(array('s'=>'Total','mod'=>'imagebycolorattrib'),$_smarty_tpl);?>
</span></th>
							<?php }?>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php  $_smarty_tpl->tpl_vars['data'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['data']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['attributesList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['data']->key => $_smarty_tpl->tpl_vars['data']->value) {
$_smarty_tpl->tpl_vars['data']->_loop = true;
?>
						<tr class="tr-item">
							<input type="hidden" name="" value="<?php echo intval($_smarty_tpl->tpl_vars['data']->value['id_attribute']);?>
" class="td_id_attribute">
							<td><?php echo $_smarty_tpl->tpl_vars['data']->value['attributeName'];?>
</td>
							<?php if ('showImagesInList') {?>
							<td class="td_images">
								<?php ob_start();?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['imageListTemplate']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php $_tmp1=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ($_tmp1, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('images'=>$_smarty_tpl->tpl_vars['data']->value['images']), 0);?>

							</td>
							<td class="td_imageCount"><?php echo $_smarty_tpl->tpl_vars['data']->value['imagesCount'];?>
</td>
							<?php }?>
							<td class="text-right">
								<a href="#" class="btn btn-default item-edit" title="<?php echo smartyTranslate(array('s'=>'Edit','mod'=>'imagebycolorattrib'),$_smarty_tpl);?>
"><i class="icon-pencil"></i><?php echo smartyTranslate(array('s'=>'Edit','mod'=>'imagebycolorattrib'),$_smarty_tpl);?>
</a>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<?php }} ?>
