<?php /* Smarty version Smarty-3.1.19, created on 2019-04-29 16:36:45
         compiled from "/home/sundevice/public_html/modules/blocklayeredcustom/views/templates/hook/filter_left.tpl" */ ?>
<?php /*%%SmartyHeaderCode:7310635545cc70bfda66a21-97417236%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '92afea98f38d49a4e90de324d9f6873f2bd302da' => 
    array (
      0 => '/home/sundevice/public_html/modules/blocklayeredcustom/views/templates/hook/filter_left.tpl',
      1 => 1536861400,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7310635545cc70bfda66a21-97417236',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'hasFiltersSelected' => 0,
    'selectedFilters' => 0,
    'selectedFilter' => 0,
    'listBlockFilter' => 0,
    'blockFilter' => 0,
    'maxFilterItems' => 0,
    'showSeeMore' => 0,
    'templateFolder' => 0,
    'filterTypeDefinition' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cc70bfda86790_89147877',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cc70bfda86790_89147877')) {function content_5cc70bfda86790_89147877($_smarty_tpl) {?>
<form action="" method="post" class="" id="formFilterLeft">
	<?php if ($_smarty_tpl->tpl_vars['hasFiltersSelected']->value) {?>
	<div class="block-selected-filters">
		<div class="selected-filter-title"><?php echo smartyTranslate(array('s'=>'Selected filters','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>
</div>
		<div class="selected-filters-list">
		<?php  $_smarty_tpl->tpl_vars['selectedFilter'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['selectedFilter']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['selectedFilters']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['selectedFilter']->key => $_smarty_tpl->tpl_vars['selectedFilter']->value) {
$_smarty_tpl->tpl_vars['selectedFilter']->_loop = true;
?>
			<div class="selected-filters-item" data-id-block="<?php echo $_smarty_tpl->tpl_vars['selectedFilter']->value['idBlock'];?>
" data-block-type="<?php echo $_smarty_tpl->tpl_vars['selectedFilter']->value['blockType'];?>
" data-selecteds="<?php echo $_smarty_tpl->tpl_vars['selectedFilter']->value['values'];?>
">
                <a class="selected-filters-item-remove" href="#" title="<?php echo smartyTranslate(array('s'=>'Remove','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>
">
					<i class="fa fa-times-circle" aria-hidden="true"></i>
				</a>
				<span class="selected-filters-item-title"><?php echo $_smarty_tpl->tpl_vars['selectedFilter']->value['label'];?>
: <?php echo $_smarty_tpl->tpl_vars['selectedFilter']->value['names'];?>
</span>
			</div>
		<?php } ?>
		</div>
		<div class="selected-filters-reset">
			<a class="btn-reset-all" href="#"><i class="fa fa-times-circle" aria-hidden="true"></i><span><?php echo smartyTranslate(array('s'=>'Reset all','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>
</span></a>
		</div>
	</div>
	<?php }?>
	<div class="filter_item_inner">
        <h3 class="filter-title1"><?php echo smartyTranslate(array('s'=>'Filters','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>
</h3>
		<?php  $_smarty_tpl->tpl_vars['blockFilter'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['blockFilter']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['listBlockFilter']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['blockFilter']->key => $_smarty_tpl->tpl_vars['blockFilter']->value) {
$_smarty_tpl->tpl_vars['blockFilter']->_loop = true;
?>
		<div id="filter-item<?php echo intval($_smarty_tpl->tpl_vars['blockFilter']->value['id_blc_filter_block']);?>
" class="filter-item clearfix" data-id-block="<?php echo intval($_smarty_tpl->tpl_vars['blockFilter']->value['id_blc_filter_block']);?>
" data-block-type="<?php echo intval($_smarty_tpl->tpl_vars['blockFilter']->value['block_type']);?>
" data-filter-type="<?php echo intval($_smarty_tpl->tpl_vars['blockFilter']->value['filter_type']);?>
">
			<div class="filter-head">
				<span class="filter-title"><?php echo $_smarty_tpl->tpl_vars['blockFilter']->value['label'];?>
</span>
				<span class="filter-icon close"><i class="fa fa-plus" aria-hidden="true"></i></span>
			</div>
			<div class="filter-content close">
				<?php $_smarty_tpl->tpl_vars['showSeeMore'] = new Smarty_variable(0, null, 0);?>
				<?php if (isset($_smarty_tpl->tpl_vars['blockFilter']->value['show_see_more'])&&$_smarty_tpl->tpl_vars['blockFilter']->value['show_see_more']&&($_smarty_tpl->tpl_vars['maxFilterItems']->value>0)&&($_smarty_tpl->tpl_vars['blockFilter']->value['selectables_count']>0)&&($_smarty_tpl->tpl_vars['maxFilterItems']->value<$_smarty_tpl->tpl_vars['blockFilter']->value['selectables_count'])) {?>
					<?php $_smarty_tpl->tpl_vars['showSeeMore'] = new Smarty_variable(1, null, 0);?>
				<?php }?>
				<div class="filter-selectables" data-filter-type="<?php echo intval($_smarty_tpl->tpl_vars['blockFilter']->value['filter_type']);?>
" data-multiple="<?php echo intval($_smarty_tpl->tpl_vars['blockFilter']->value['multiple']);?>
" 
					data-is-color="<?php if (isset($_smarty_tpl->tpl_vars['blockFilter']->value['value_type'])&&($_smarty_tpl->tpl_vars['blockFilter']->value['value_type']=='color')) {?>1<?php } else { ?>0<?php }?>" 
					data-show-see-more="<?php echo intval($_smarty_tpl->tpl_vars['showSeeMore']->value);?>
" <?php if ($_smarty_tpl->tpl_vars['showSeeMore']->value) {?>data-selectables-count="<?php echo intval($_smarty_tpl->tpl_vars['blockFilter']->value['selectables_count']);?>
"<?php }?>>
					<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['templateFolder']->value)."selectables_".((string)$_smarty_tpl->tpl_vars['filterTypeDefinition']->value[$_smarty_tpl->tpl_vars['blockFilter']->value['filter_type']]['tpl_suffix']).".tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

					<?php if ($_smarty_tpl->tpl_vars['showSeeMore']->value) {?>
					<div class="div-see-more">
						<a class="btn-see-more" href="#"><?php echo smartyTranslate(array('s'=>'See more','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>
</a>
					</div>
					<?php }?>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
</form><?php }} ?>
