<?php /* Smarty version Smarty-3.1.19, created on 2019-04-29 17:32:46
         compiled from "/home/sundevice/public_html/modules/paypalwithfee/views/templates/hook/admin_order_tab_ship.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1705173595cc7191e85ac86-24003134%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1747caf83ba0ce9f141a3df44c806f7ed3a7b581' => 
    array (
      0 => '/home/sundevice/public_html/modules/paypalwithfee/views/templates/hook/admin_order_tab_ship.tpl',
      1 => 1554927945,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1705173595cc7191e85ac86-24003134',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cc7191e85c5f7_91616716',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cc7191e85c5f7_91616716')) {function content_5cc7191e85c5f7_91616716($_smarty_tpl) {?>
<li class="active">
    <a href="#paypalwithfee">
        <?php echo smartyTranslate(array('s'=>'Paypal with fee','mod'=>'paypalwithfee'),$_smarty_tpl);?>

        <span class="badge">1</span>	
    </a>
</li>
<script type="text/javascript">
    $(document).ready(function () {
        $('#myTab li').removeClass('active');
        $('#myTab li').first().addClass('active');
    });
</script><?php }} ?>
