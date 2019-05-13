<?php /* Smarty version Smarty-3.1.19, created on 2019-02-06 16:10:23
         compiled from "/home/sundevice/public_html/modules/bankwirecopie/views/templates/front/payment_execution.tpl" */ ?>
<?php /*%%SmartyHeaderCode:19206649495c5af8df4b2065-40921571%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a92fa2d58d92ce219c98177c013a40b4a5de865d' => 
    array (
      0 => '/home/sundevice/public_html/modules/bankwirecopie/views/templates/front/payment_execution.tpl',
      1 => 1549464708,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19206649495c5af8df4b2065-40921571',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'link' => 0,
    'navigationPipe' => 0,
    'nbProducts' => 0,
    'this_path_bw' => 0,
    'total' => 0,
    'use_taxes' => 0,
    'currencies' => 0,
    'currency' => 0,
    'cust_currency' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c5af8df4e4b82_98351381',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c5af8df4e4b82_98351381')) {function content_5c5af8df4e4b82_98351381($_smarty_tpl) {?>

<?php $_smarty_tpl->_capture_stack[0][] = array('path', null, null); ob_start(); ?>
	<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getPageLink('order',true,null,"step=3"), ENT_QUOTES, 'UTF-8', true);?>
" title="<?php echo smartyTranslate(array('s'=>'Go back to the Checkout','mod'=>'bankwirecopie'),$_smarty_tpl);?>
"><?php echo smartyTranslate(array('s'=>'Checkout','mod'=>'bankwirecopie'),$_smarty_tpl);?>
</a><span class="navigation-pipe"><?php echo $_smarty_tpl->tpl_vars['navigationPipe']->value;?>
</span><?php echo smartyTranslate(array('s'=>'Bank-wire payment','mod'=>'bankwirecopie'),$_smarty_tpl);?>

<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./breadcrumb.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>


<h2><?php echo smartyTranslate(array('s'=>'Order summary','mod'=>'bankwirecopie'),$_smarty_tpl);?>
</h2>

<?php $_smarty_tpl->tpl_vars['current_step'] = new Smarty_variable('payment', null, 0);?>
<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./order-steps.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>


<?php if ($_smarty_tpl->tpl_vars['nbProducts']->value<=0) {?>
	<p class="warning"><?php echo smartyTranslate(array('s'=>'Your shopping cart is empty.','mod'=>'bankwirecopie'),$_smarty_tpl);?>
</p>
<?php } else { ?>

<h3><?php echo smartyTranslate(array('s'=>'Bank-wire payment','mod'=>'bankwirecopie'),$_smarty_tpl);?>
</h3>
<form action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getModuleLink('bankwirecopie','validation',array(),true), ENT_QUOTES, 'UTF-8', true);?>
" method="post">
<p>
	<img src="<?php echo $_smarty_tpl->tpl_vars['this_path_bw']->value;?>
bankwirecopie.jpg" alt="<?php echo smartyTranslate(array('s'=>'Bank wire','mod'=>'bankwirecopie'),$_smarty_tpl);?>
" width="86" height="49" style="float:left; margin: 0px 10px 5px 0px;" />
	<?php echo smartyTranslate(array('s'=>'You have chosen to pay by bank wire.','mod'=>'bankwirecopie'),$_smarty_tpl);?>

	<br/><br />
	<?php echo smartyTranslate(array('s'=>'Here is a short summary of your order:','mod'=>'bankwirecopie'),$_smarty_tpl);?>

</p>
<p style="margin-top:20px;">
	- <?php echo smartyTranslate(array('s'=>'The total amount of your order is','mod'=>'bankwirecopie'),$_smarty_tpl);?>

	<span id="amount" class="price"><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0][0]->displayPriceSmarty(array('price'=>$_smarty_tpl->tpl_vars['total']->value),$_smarty_tpl);?>
</span>
	<?php if ($_smarty_tpl->tpl_vars['use_taxes']->value==1) {?>
    	<?php echo smartyTranslate(array('s'=>'(tax incl.)','mod'=>'bankwirecopie'),$_smarty_tpl);?>

    <?php }?>
</p>
<p>
	-
	<?php if (count($_smarty_tpl->tpl_vars['currencies']->value)>1) {?>
		<?php echo smartyTranslate(array('s'=>'We allow several currencies to be sent via bank wire.','mod'=>'bankwirecopie'),$_smarty_tpl);?>

		<br /><br />
		<?php echo smartyTranslate(array('s'=>'Choose one of the following:','mod'=>'bankwirecopie'),$_smarty_tpl);?>

		<select id="currency_payement" name="currency_payement" onchange="setCurrency($('#currency_payement').val());">
			<?php  $_smarty_tpl->tpl_vars['currency'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['currency']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['currencies']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['currency']->key => $_smarty_tpl->tpl_vars['currency']->value) {
$_smarty_tpl->tpl_vars['currency']->_loop = true;
?>
				<option value="<?php echo $_smarty_tpl->tpl_vars['currency']->value['id_currency'];?>
" <?php if ($_smarty_tpl->tpl_vars['currency']->value['id_currency']==$_smarty_tpl->tpl_vars['cust_currency']->value) {?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['currency']->value['name'];?>
</option>
			<?php } ?>
		</select>
	<?php } else { ?>
		<?php echo smartyTranslate(array('s'=>'We allow the following currency to be sent via bank wire:','mod'=>'bankwirecopie'),$_smarty_tpl);?>
&nbsp;<b><?php echo $_smarty_tpl->tpl_vars['currencies']->value[0]['name'];?>
</b>
		<input type="hidden" name="currency_payement" value="<?php echo $_smarty_tpl->tpl_vars['currencies']->value[0]['id_currency'];?>
" />
	<?php }?>
</p>
<p>
	<?php echo smartyTranslate(array('s'=>'Bank wire account information will be displayed on the next page.','mod'=>'bankwirecopie'),$_smarty_tpl);?>

	<br /><br />
	<b><?php echo smartyTranslate(array('s'=>'Please confirm your order by clicking "I confirm my order".','mod'=>'bankwirecopie'),$_smarty_tpl);?>
</b>
</p>
<p class="cart_navigation" id="cart_navigation">
	<input type="submit" value="<?php echo smartyTranslate(array('s'=>'I confirm my order','mod'=>'bankwirecopie'),$_smarty_tpl);?>
" class="exclusive_large" />
	<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getPageLink('order',true,null,"step=3"), ENT_QUOTES, 'UTF-8', true);?>
" class="button_large"><?php echo smartyTranslate(array('s'=>'Other payment methods','mod'=>'bankwirecopie'),$_smarty_tpl);?>
</a>
</p>
</form>
<?php }?>
<?php }} ?>
