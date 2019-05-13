<?php /* Smarty version Smarty-3.1.19, created on 2019-04-29 16:45:25
         compiled from "/home/sundevice/public_html/pdf/invoice.shipping-tab.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4234074735cc70e05f3fea9-72951246%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b6c7a45226297b4f9805d3456668742e5c6eb667' => 
    array (
      0 => '/home/sundevice/public_html/pdf/invoice.shipping-tab.tpl',
      1 => 1530115312,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4234074735cc70e05f3fea9-72951246',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'carrier' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cc70e05f41f99_22587651',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cc70e05f41f99_22587651')) {function content_5cc70e05f41f99_22587651($_smarty_tpl) {?>
<table id="shipping-tab" width="100%">
	<tr>
		<td class="shipping center small grey bold" width="44%"><?php echo smartyTranslate(array('s'=>'Carrier','pdf'=>'true'),$_smarty_tpl);?>
</td>
		<td class="shipping center small white" width="56%"><?php echo $_smarty_tpl->tpl_vars['carrier']->value->name;?>
</td>
	</tr>
</table>
<?php }} ?>
