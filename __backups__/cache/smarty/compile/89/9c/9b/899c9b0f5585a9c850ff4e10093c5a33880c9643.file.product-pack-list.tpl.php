<?php /* Smarty version Smarty-3.1.19, created on 2019-01-29 11:42:45
         compiled from "/home/sundevice/public_html/themes/pos_ruby5/product-pack-list.tpl" */ ?>
<?php /*%%SmartyHeaderCode:3479205945c502e258e7df9-94092824%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '899c9b0f5585a9c850ff4e10093c5a33880c9643' => 
    array (
      0 => '/home/sundevice/public_html/themes/pos_ruby5/product-pack-list.tpl',
      1 => 1538510279,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3479205945c502e258e7df9-94092824',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'pack_list' => 0,
    'productPack' => 0,
    'priceDisplay' => 0,
    'PS_CATALOG_MODE' => 0,
    'productPricePack' => 0,
    'has_box' => 0,
    'available_packeging' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c502e258fec87_25991270',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c502e258fec87_25991270')) {function content_5c502e258fec87_25991270($_smarty_tpl) {?><div class="packaging_left packaging_block" id="divPackegingLeft">
    <?php $_smarty_tpl->tpl_vars['has_box'] = new Smarty_variable(false, null, 0);?>
    <?php  $_smarty_tpl->tpl_vars['productPack'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['productPack']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['pack_list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['productPack']->key => $_smarty_tpl->tpl_vars['productPack']->value) {
$_smarty_tpl->tpl_vars['productPack']->_loop = true;
?>
		<?php if ($_smarty_tpl->tpl_vars['productPack']->value['product']->active==1&&($_smarty_tpl->tpl_vars['productPack']->value['is_default']||count($_smarty_tpl->tpl_vars['pack_list']->value)==1)) {?>
			<?php $_smarty_tpl->tpl_vars['has_box'] = new Smarty_variable(true, null, 0);?>
			<?php if (!$_smarty_tpl->tpl_vars['priceDisplay']->value||$_smarty_tpl->tpl_vars['priceDisplay']->value==2) {?>
				<?php $_smarty_tpl->tpl_vars['productPricePack'] = new Smarty_variable($_smarty_tpl->tpl_vars['productPack']->value['product']->getPrice(true,@constant('NULL'),6), null, 0);?>
				<?php $_smarty_tpl->tpl_vars['productPriceWithoutReductionPack'] = new Smarty_variable($_smarty_tpl->tpl_vars['productPack']->value['product']->getPriceWithoutReduct(false,@constant('NULL')), null, 0);?>
			<?php } elseif ($_smarty_tpl->tpl_vars['priceDisplay']->value==1) {?>
				<?php $_smarty_tpl->tpl_vars['productPricePack'] = new Smarty_variable($_smarty_tpl->tpl_vars['productPack']->value['product']->getPrice(false,@constant('NULL'),6), null, 0);?>
				<?php $_smarty_tpl->tpl_vars['productPriceWithoutReductionPack'] = new Smarty_variable($_smarty_tpl->tpl_vars['productPack']->value['product']->getPriceWithoutReduct(true,@constant('NULL')), null, 0);?>
			<?php }?>
			
			<div>
				<input id="input_add_packaging" name="input_packaging" class="input_select_packaging" type="radio" value="1" checked />
				
				<label for="input_add_packaging"><?php echo smartyTranslate(array('s'=>'Ajouter un coffret'),$_smarty_tpl);?>
</label>
				<?php if ($_smarty_tpl->tpl_vars['productPack']->value['product']->show_price&&!$_smarty_tpl->tpl_vars['PS_CATALOG_MODE']->value) {?>
				<span  itemprop="price" class="price product-price"><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['convertPrice'][0][0]->convertPrice(array('price'=>$_smarty_tpl->tpl_vars['productPricePack']->value),$_smarty_tpl);?>
</span>
				<?php }?>
			</div>
			<?php break 1?>
		<?php }?>
	<?php } ?>
	<?php if ($_smarty_tpl->tpl_vars['has_box']->value) {?>
	<div>
		<input id="input_no_packaging" name="input_packaging" class="input_select_packaging" type="radio" value="0"/>
		<label for="input_no_packaging"><?php echo smartyTranslate(array('s'=>'Sans coffret'),$_smarty_tpl);?>
</label>
	</div>
	<?php }?>
</div>
<div id="helpAvailablePackaging" style="display:none;"><?php echo $_smarty_tpl->tpl_vars['available_packeging']->value;?>
</div><?php }} ?>
