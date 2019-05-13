<?php /* Smarty version Smarty-3.1.19, created on 2019-04-29 16:36:46
         compiled from "/home/sundevice/public_html/modules/menupro/views/templates/hook/header_secondary_menu_mega.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4023579235cc70bfe3cc1f5-63407173%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0855670b4cfd3ed79e747db9e3ea580f420e4bde' => 
    array (
      0 => '/home/sundevice/public_html/modules/menupro/views/templates/hook/header_secondary_menu_mega.tpl',
      1 => 1501665696,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4023579235cc70bfe3cc1f5-63407173',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'firstItems' => 0,
    'secondaryMenu' => 0,
    'htmlContentPositionsConst' => 0,
    'displayElementTemplate' => 0,
    'displayStylesConst' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cc70bfe3e67f0_60827478',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cc70bfe3e67f0_60827478')) {function content_5cc70bfe3e67f0_60827478($_smarty_tpl) {?>

<?php if ($_smarty_tpl->tpl_vars['firstItems']->value) {?>
	<?php if ($_smarty_tpl->tpl_vars['secondaryMenu']->value['level']==2) {?>
		<div class="menu-sub-level-1" <?php echo $_smarty_tpl->tpl_vars['secondaryMenu']->value['parent_style_for_container'];?>
>
			<?php if (isset($_smarty_tpl->tpl_vars['secondaryMenu']->value['html_contents'][$_smarty_tpl->tpl_vars['htmlContentPositionsConst']->value['TOP']])&&(!empty($_smarty_tpl->tpl_vars['secondaryMenu']->value['html_contents'][$_smarty_tpl->tpl_vars['htmlContentPositionsConst']->value['TOP']]))) {?>
			<div class="mp-image-top">
				<div class="mp-image-top_inner row">
					<div class="col-md-12">
						<?php echo $_smarty_tpl->tpl_vars['secondaryMenu']->value['html_contents'][$_smarty_tpl->tpl_vars['htmlContentPositionsConst']->value['TOP']];?>

					</div>
				</div>
			</div>
			<?php }?>
			<div class="grig_items_mp">
				<?php if (isset($_smarty_tpl->tpl_vars['secondaryMenu']->value['html_contents'][$_smarty_tpl->tpl_vars['htmlContentPositionsConst']->value['LEFT']])&&(!empty($_smarty_tpl->tpl_vars['secondaryMenu']->value['html_contents'][$_smarty_tpl->tpl_vars['htmlContentPositionsConst']->value['LEFT']]))) {?>
				<div class="mp-image-left hidden-sm hidden-xs">
					<div class="mp-image-left_inner row">
						<div class="col-md-12">
							<?php echo $_smarty_tpl->tpl_vars['secondaryMenu']->value['html_contents'][$_smarty_tpl->tpl_vars['htmlContentPositionsConst']->value['LEFT']];?>

						</div>
					</div>
				</div>
				<?php }?>
				<ul <?php echo $_smarty_tpl->tpl_vars['secondaryMenu']->value['parent_style_for_container'];?>
>
	<?php } else { ?>
		<ul   
		<?php echo $_smarty_tpl->tpl_vars['secondaryMenu']->value['parent_style_for_container'];?>
>
	<?php }?>
<?php }?>

	<li class="<?php if (($_smarty_tpl->tpl_vars['secondaryMenu']->value['has_children'])&&($_smarty_tpl->tpl_vars['secondaryMenu']->value['level']==1)) {?>menu-dropdown-icon <?php } else { ?>normal-sub<?php }?>">
		<?php ob_start();?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['displayElementTemplate']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php $_tmp14=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ($_tmp14, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('isFooterTemplate'=>false,'hasAdditionalClass'=>false), 0);?>

		<?php if ((!$_smarty_tpl->tpl_vars['secondaryMenu']->value['use_custom_content'])&&($_smarty_tpl->tpl_vars['secondaryMenu']->value['display_style']!=$_smarty_tpl->tpl_vars['displayStylesConst']->value['COMPLEX'])) {?>
			<?php if ($_smarty_tpl->tpl_vars['secondaryMenu']->value['level']==2) {?><hr/><?php }?>
			<?php if (($_smarty_tpl->tpl_vars['secondaryMenu']->value['has_children'])&&($_smarty_tpl->tpl_vars['secondaryMenu']->value['level']==1)) {?>
				<span class="mp-icon-responsive mp-icon-plus"></span>
			<?php }?>
		<?php }?><?php }} ?>
