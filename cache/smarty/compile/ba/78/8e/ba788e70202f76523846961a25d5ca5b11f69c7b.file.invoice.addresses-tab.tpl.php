<?php /* Smarty version Smarty-3.1.19, created on 2019-04-29 16:45:25
         compiled from "/home/sundevice/public_html/pdf/invoice.addresses-tab.tpl" */ ?>
<?php /*%%SmartyHeaderCode:15182279055cc70e05e7eb55-77438499%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ba788e70202f76523846961a25d5ca5b11f69c7b' => 
    array (
      0 => '/home/sundevice/public_html/pdf/invoice.addresses-tab.tpl',
      1 => 1542122841,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15182279055cc70e05e7eb55-77438499',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'order_invoice' => 0,
    'shop_details' => 0,
    'delivery_address' => 0,
    'invoice_address' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cc70e05e857f1_55187061',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cc70e05e857f1_55187061')) {function content_5cc70e05e857f1_55187061($_smarty_tpl) {?>
<table id="addresses-tab" cellspacing="0" cellpadding="0">
	<tr>
		<td width="33%"><span class="bold"> </span><br/><br/>
			<?php if (isset($_smarty_tpl->tpl_vars['order_invoice']->value)) {?><?php echo $_smarty_tpl->tpl_vars['order_invoice']->value->shop_address;?>
<br/><?php echo $_smarty_tpl->tpl_vars['shop_details']->value;?>
<?php }?>
		</td>
		<td width="33%"><?php if ($_smarty_tpl->tpl_vars['delivery_address']->value) {?><span class="bold"><?php echo smartyTranslate(array('s'=>'Delivery Address','pdf'=>'true'),$_smarty_tpl);?>
</span><br/><br/>
				<?php echo $_smarty_tpl->tpl_vars['delivery_address']->value;?>

			<?php }?>
		</td>
		<td width="33%"><span class="bold"><?php echo smartyTranslate(array('s'=>'Billing Address','pdf'=>'true'),$_smarty_tpl);?>
</span><br/><br/>
				<?php echo $_smarty_tpl->tpl_vars['invoice_address']->value;?>

		</td>
	</tr>
</table>
<?php }} ?>
