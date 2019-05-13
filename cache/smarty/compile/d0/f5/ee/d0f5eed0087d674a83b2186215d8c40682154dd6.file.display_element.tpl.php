<?php /* Smarty version Smarty-3.1.19, created on 2019-05-07 21:57:57
         compiled from "/home/sundevice/preprod/modules/menupro/views/templates/hook/display_element.tpl" */ ?>
<?php /*%%SmartyHeaderCode:18019955845cd1e345325a39-91193075%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd0f5eed0087d674a83b2186215d8c40682154dd6' => 
    array (
      0 => '/home/sundevice/preprod/modules/menupro/views/templates/hook/display_element.tpl',
      1 => 1535784482,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18019955845cd1e345325a39-91193075',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'secondaryMenu' => 0,
    'displayStylesConst' => 0,
    'menuTypesConst' => 0,
    'ps_version' => 0,
    'productComplexTemplates' => 0,
    'complex_products' => 0,
    'categoryComplexTemplates' => 0,
    'complex_category' => 0,
    'isFooterTemplate' => 0,
    'hasAdditionalClass' => 0,
    'additionalClass' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cd1e345354342_68527798',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cd1e345354342_68527798')) {function content_5cd1e345354342_68527798($_smarty_tpl) {?>

<?php if ($_smarty_tpl->tpl_vars['secondaryMenu']->value['use_custom_content']) {?>
	<div class="mp-custom-content"><?php echo $_smarty_tpl->tpl_vars['secondaryMenu']->value['custom_content'];?>
</div>
<?php } else { ?>
	<?php if ($_smarty_tpl->tpl_vars['secondaryMenu']->value['display_style']==$_smarty_tpl->tpl_vars['displayStylesConst']->value['COMPLEX']) {?>
		<?php if ($_smarty_tpl->tpl_vars['secondaryMenu']->value['item_type']==$_smarty_tpl->tpl_vars['menuTypesConst']->value['PRODUCT']) {?>
			<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.7') {?>
				<?php ob_start();?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['productComplexTemplates']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php $_tmp15=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ($_tmp15, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

			<?php } else { ?>
				<div class="mp-product-complex">
					<?php echo $_smarty_tpl->getSubTemplate ('catalog/_partials/miniatures/product.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('product'=>$_smarty_tpl->tpl_vars['complex_products']->value), 0);?>

				</div>
			<?php }?>
		<?php } elseif ($_smarty_tpl->tpl_vars['secondaryMenu']->value['item_type']==$_smarty_tpl->tpl_vars['menuTypesConst']->value['CATEGORY']) {?>
			<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.7') {?>
				<?php ob_start();?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['categoryComplexTemplates']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php $_tmp16=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ($_tmp16, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

			<?php } else { ?>
				<div class="mp-category-complex">
					<?php echo $_smarty_tpl->getSubTemplate ('catalog/_partials/miniatures/category.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('category'=>$_smarty_tpl->tpl_vars['complex_category']->value), 0);?>

				</div>
			<?php }?>
		<?php }?>
	<?php } else { ?>
		<?php if ($_smarty_tpl->tpl_vars['isFooterTemplate']->value&&($_smarty_tpl->tpl_vars['secondaryMenu']->value['level']==1)) {?>
			<<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?>p<?php } elseif ($_smarty_tpl->tpl_vars['ps_version']->value<'1.7') {?>h4<?php } else { ?>h3<?php }?> class="mp-footer-menu-title <?php if (($_smarty_tpl->tpl_vars['ps_version']->value<'1.6')&&($_smarty_tpl->tpl_vars['ps_version']->value<'1.7')) {?>title_block<?php } elseif ($_smarty_tpl->tpl_vars['ps_version']->value>='1.7') {?>hidden-sm-down<?php }?>">
		<?php }?>
		<a href="<?php if ($_smarty_tpl->tpl_vars['secondaryMenu']->value['clickable']) {?><?php echo strtr($_smarty_tpl->tpl_vars['secondaryMenu']->value['link'], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
<?php } else { ?>javascript:void(0);<?php }?>" 
		title="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['secondaryMenu']->value['name'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
"
		class="<?php if ($_smarty_tpl->tpl_vars['hasAdditionalClass']->value) {?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['additionalClass']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php }?> mp-menu-link 
		<?php if ($_smarty_tpl->tpl_vars['secondaryMenu']->value['has_children']) {?> has-children<?php }?><?php if (!$_smarty_tpl->tpl_vars['secondaryMenu']->value['clickable']) {?> mp-not-clickable<?php } else { ?> mp-clickable<?php }?>" 
		<?php if ($_smarty_tpl->tpl_vars['secondaryMenu']->value['new_tab']) {?> target="_blank"<?php }?> 
		<?php echo $_smarty_tpl->tpl_vars['secondaryMenu']->value['style']['active'];?>
 <?php echo $_smarty_tpl->tpl_vars['secondaryMenu']->value['style']['reset_active'];?>
 
		<?php echo $_smarty_tpl->tpl_vars['secondaryMenu']->value['style']['no_event'];?>
 <?php echo $_smarty_tpl->tpl_vars['secondaryMenu']->value['style']['reset'];?>
 
		<?php echo $_smarty_tpl->tpl_vars['secondaryMenu']->value['style']['hover'];?>
>
		<?php if (!empty($_smarty_tpl->tpl_vars['secondaryMenu']->value['icon_css_class'])) {?>
			<i class="<?php echo mb_convert_encoding(htmlspecialchars(trim($_smarty_tpl->tpl_vars['secondaryMenu']->value['icon_css_class']), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
"></i>
		<?php }?>
		<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['secondaryMenu']->value['name'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>

		</a>
		<?php if ($_smarty_tpl->tpl_vars['isFooterTemplate']->value&&($_smarty_tpl->tpl_vars['secondaryMenu']->value['level']==1)) {?>
			</<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?>p<?php } elseif ($_smarty_tpl->tpl_vars['ps_version']->value<'1.7') {?>h4<?php } else { ?>h3<?php }?>>
		<?php }?>
	<?php }?>
<?php }?><?php }} ?>
