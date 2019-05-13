<?php /* Smarty version Smarty-3.1.19, created on 2019-04-29 16:45:25
         compiled from "/home/sundevice/public_html/pdf/invoice.note-tab.tpl" */ ?>
<?php /*%%SmartyHeaderCode:20775362595cc70e05f10ab9-60519335%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd822d1c1820b6c4e23984d1169c1f95b423cfe41' => 
    array (
      0 => '/home/sundevice/public_html/pdf/invoice.note-tab.tpl',
      1 => 1530115312,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20775362595cc70e05f10ab9-60519335',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'order_invoice' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cc70e05f152f3_11844727',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cc70e05f152f3_11844727')) {function content_5cc70e05f152f3_11844727($_smarty_tpl) {?>
<?php if (isset($_smarty_tpl->tpl_vars['order_invoice']->value->note)&&$_smarty_tpl->tpl_vars['order_invoice']->value->note) {?>
	<tr>
		<td colspan="12" height="10">&nbsp;</td>
	</tr>
	
	<tr>
		<td colspan="6" class="left">
			<table id="note-tab" style="width: 100%">
				<tr>
					<td class="grey"><?php echo smartyTranslate(array('s'=>'Note','pdf'=>'true'),$_smarty_tpl);?>
</td>
				</tr>
				<tr>
					<td class="note"><?php echo nl2br($_smarty_tpl->tpl_vars['order_invoice']->value->note);?>
</td>
				</tr>
			</table>
		</td>
		<td colspan="1">&nbsp;</td>
	</tr>
<?php }?>
<?php }} ?>
