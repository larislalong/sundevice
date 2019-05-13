<?php /* Smarty version Smarty-3.1.19, created on 2019-01-31 15:56:29
         compiled from "/home/sundevice/public_html/modules/blocklayeredcustom/views/templates/admin/configure.tpl" */ ?>
<?php /*%%SmartyHeaderCode:16247159885c530c9d6e7df1-29230467%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ca3975ffe60ffb368d78091659cc2cc6c7e37742' => 
    array (
      0 => '/home/sundevice/public_html/modules/blocklayeredcustom/views/templates/admin/configure.tpl',
      1 => 1525408904,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16247159885c530c9d6e7df1-29230467',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'filterTypeDefinition' => 0,
    'priceRegenerationLink' => 0,
    'listBlockFilter' => 0,
    'blockFilter' => 0,
    'blockTypeFilters' => 0,
    'filterCode' => 0,
    'configurationFormContent' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c530c9d718864_71529006',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c530c9d718864_71529006')) {function content_5c530c9d718864_71529006($_smarty_tpl) {?>
<script type="text/javascript">
	var filterTypeDefinition = <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['json_encode'][0][0]->jsonEncode($_smarty_tpl->tpl_vars['filterTypeDefinition']->value);?>
;
</script>
<div class="panel clearfix">
	<a href="<?php echo $_smarty_tpl->tpl_vars['priceRegenerationLink']->value;?>
" class="btn btn-primary btn-lg">
		<i class="process-icon-cogs"></i><?php echo smartyTranslate(array('s'=>'Regenerate price index','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>

	</a>
</div>
<form action="" method="post" class="defaultForm form-horizontal" id="formBlockFilter">
	<input type="hidden" name="submitFilterBlockSave" value="1">
	<div class="panel">
		<h3><?php echo smartyTranslate(array('s'=>'Configuration of filter','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>
</h3>
		<div class="table-responsive-row clearfix">
			<table class="table table-blockFilter">
				<thead>
					<tr class="nodrag nodrop">
						<th class=""><span class="title_box"><?php echo smartyTranslate(array('s'=>'Filter name','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>
</span></th>
						<th class=""><span class="title_box"><?php echo smartyTranslate(array('s'=>'Active','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>
</span></th>
						<th class=""><span class="title_box"><?php echo smartyTranslate(array('s'=>'Filter type','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>
</span></th>
						<th class=""><span class="title_box"><?php echo smartyTranslate(array('s'=>'Position','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>
</span></th>
						<th class=""><span class="title_box"><?php echo smartyTranslate(array('s'=>'Multiple','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>
</span></th>
					</tr>
				</thead>
				<tbody>
					<?php  $_smarty_tpl->tpl_vars['blockFilter'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['blockFilter']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['listBlockFilter']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['blockFilter']->key => $_smarty_tpl->tpl_vars['blockFilter']->value) {
$_smarty_tpl->tpl_vars['blockFilter']->_loop = true;
?>
					<tr class="tr-blockFilter">		
						<td><?php echo $_smarty_tpl->tpl_vars['blockFilter']->value['label'];?>
</td>
						<td>
							<span class="switch prestashop-switch fixed-width-lg">
								<input type="radio" name="BLC_BLOCK_FILTER_ACTIVE<?php echo $_smarty_tpl->tpl_vars['blockFilter']->value['code'];?>
" id="BLC_BLOCK_FILTER_ACTIVE<?php echo $_smarty_tpl->tpl_vars['blockFilter']->value['code'];?>
_on" value="1" <?php if ($_smarty_tpl->tpl_vars['blockFilter']->value['active']) {?>checked="checked"<?php }?>>
								<label for="BLC_BLOCK_FILTER_ACTIVE<?php echo $_smarty_tpl->tpl_vars['blockFilter']->value['code'];?>
_on"><?php echo smartyTranslate(array('s'=>'Yes','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>
</label>
								<input type="radio" name="BLC_BLOCK_FILTER_ACTIVE<?php echo $_smarty_tpl->tpl_vars['blockFilter']->value['code'];?>
" id="BLC_BLOCK_FILTER_ACTIVE<?php echo $_smarty_tpl->tpl_vars['blockFilter']->value['code'];?>
_off" value="" <?php if (!$_smarty_tpl->tpl_vars['blockFilter']->value['active']) {?>checked="checked"<?php }?>>
								<label for="BLC_BLOCK_FILTER_ACTIVE<?php echo $_smarty_tpl->tpl_vars['blockFilter']->value['code'];?>
_off"><?php echo smartyTranslate(array('s'=>'No','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>
</label>
								<a class="slide-button btn"></a>
							</span>
						</td>
						<td>
							<select name="BLC_BLOCK_FILTER_FILTER_TYPE<?php echo $_smarty_tpl->tpl_vars['blockFilter']->value['code'];?>
" class="filter-type-field">
								<?php  $_smarty_tpl->tpl_vars['filterCode'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['filterCode']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['blockTypeFilters']->value[$_smarty_tpl->tpl_vars['blockFilter']->value['block_type']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['filterCode']->key => $_smarty_tpl->tpl_vars['filterCode']->value) {
$_smarty_tpl->tpl_vars['filterCode']->_loop = true;
?>
								<option value="<?php echo intval($_smarty_tpl->tpl_vars['filterCode']->value);?>
" <?php if ($_smarty_tpl->tpl_vars['blockFilter']->value['filter_type']==$_smarty_tpl->tpl_vars['filterCode']->value) {?>selected<?php }?>>
									<?php echo $_smarty_tpl->tpl_vars['filterTypeDefinition']->value[$_smarty_tpl->tpl_vars['filterCode']->value]['label'];?>

								</option>
								<?php } ?>
							</select>
						</td>
						<td>
							<input type="text" name="BLC_BLOCK_FILTER_POSITION<?php echo $_smarty_tpl->tpl_vars['blockFilter']->value['code'];?>
" id="" value="<?php echo intval($_smarty_tpl->tpl_vars['blockFilter']->value['position']);?>
">
						</td>
						<td class="block-multiple">
							<span class="switch prestashop-switch fixed-width-lg span-multiple" <?php if (!$_smarty_tpl->tpl_vars['filterTypeDefinition']->value[$_smarty_tpl->tpl_vars['blockFilter']->value['filter_type']]['configure_multiple']) {?>style="display:none;"<?php }?>>
								<input class="radio-on" type="radio" name="BLC_BLOCK_FILTER_MULTIPLE<?php echo $_smarty_tpl->tpl_vars['blockFilter']->value['code'];?>
" id="BLC_BLOCK_FILTER_MULTIPLE<?php echo $_smarty_tpl->tpl_vars['blockFilter']->value['code'];?>
_on" value="1" <?php if ($_smarty_tpl->tpl_vars['blockFilter']->value['multiple']) {?>checked="checked"<?php }?>>
								<label for="BLC_BLOCK_FILTER_MULTIPLE<?php echo $_smarty_tpl->tpl_vars['blockFilter']->value['code'];?>
_on"><?php echo smartyTranslate(array('s'=>'Yes','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>
</label>
								<input class="radio-off" type="radio" name="BLC_BLOCK_FILTER_MULTIPLE<?php echo $_smarty_tpl->tpl_vars['blockFilter']->value['code'];?>
" id="BLC_BLOCK_FILTER_MULTIPLE<?php echo $_smarty_tpl->tpl_vars['blockFilter']->value['code'];?>
_off" value="" <?php if (!$_smarty_tpl->tpl_vars['blockFilter']->value['multiple']) {?>checked="checked"<?php }?>>
								<label for="BLC_BLOCK_FILTER_MULTIPLE<?php echo $_smarty_tpl->tpl_vars['blockFilter']->value['code'];?>
_off"><?php echo smartyTranslate(array('s'=>'No','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>
</label>
								<a class="slide-button btn"></a>
							</span>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<div class="panel-footer">
			<button type="submit" value="1" name="submitblocklayeredcustomModule" class="btn btn-default pull-right">
				<i class="process-icon-save"></i><?php echo smartyTranslate(array('s'=>'Save','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>

			</button>
		</div>
	</div>
	<?php echo $_smarty_tpl->tpl_vars['configurationFormContent']->value;?>

</form><?php }} ?>
