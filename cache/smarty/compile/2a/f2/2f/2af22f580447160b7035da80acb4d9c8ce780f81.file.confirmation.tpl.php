<?php /* Smarty version Smarty-3.1.19, created on 2019-05-01 21:24:10
         compiled from "/home/sundevice/public_html/modules/paypalwithfee/views/templates/front/confirmation.tpl" */ ?>
<?php /*%%SmartyHeaderCode:11422515615cc9f25a6554f4-68712063%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2af22f580447160b7035da80acb4d9c8ce780f81' => 
    array (
      0 => '/home/sundevice/public_html/modules/paypalwithfee/views/templates/front/confirmation.tpl',
      1 => 1554927945,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11422515615cc9f25a6554f4-68712063',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'shop_name' => 0,
    'base_dir_ssl' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cc9f25a6692e9_73951787',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cc9f25a6692e9_73951787')) {function content_5cc9f25a6692e9_73951787($_smarty_tpl) {?>
<p><?php echo smartyTranslate(array('s'=>'Your order on','mod'=>'paypalwithfee'),$_smarty_tpl);?>
 
    <span class="bold"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['shop_name']->value, ENT_QUOTES, 'UTF-8', true);?>
</span> 
    <?php echo smartyTranslate(array('s'=>'is complete.','mod'=>'paypalwithfee'),$_smarty_tpl);?>

    <br /><br />
    <?php echo smartyTranslate(array('s'=>'You have chosen PayPal with Fee as a payment.','mod'=>'paypalwithfee'),$_smarty_tpl);?>

    <br /><br /><span class="bold"><?php echo smartyTranslate(array('s'=>'Your order will be sent very soon.','mod'=>'paypalwithfee'),$_smarty_tpl);?>
</span>
    <br /><br /><?php echo smartyTranslate(array('s'=>'For any questions or for further information, please contact our.','mod'=>'paypalwithfee'),$_smarty_tpl);?>
 
    <a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['base_dir_ssl']->value, ENT_QUOTES, 'UTF-8', true);?>
contact-form.php"><?php echo smartyTranslate(array('s'=>'customer support','mod'=>'paypalwithfee'),$_smarty_tpl);?>
</a>
</p><?php }} ?>
