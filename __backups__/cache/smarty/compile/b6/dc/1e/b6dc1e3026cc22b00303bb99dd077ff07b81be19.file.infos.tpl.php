<?php /* Smarty version Smarty-3.1.19, created on 2019-02-06 15:55:25
         compiled from "/home/sundevice/public_html/modules/bankwirecopie/views/templates/hook/infos.tpl" */ ?>
<?php /*%%SmartyHeaderCode:11556839575c5af55d3464d0-31427060%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b6dc1e3026cc22b00303bb99dd077ff07b81be19' => 
    array (
      0 => '/home/sundevice/public_html/modules/bankwirecopie/views/templates/hook/infos.tpl',
      1 => 1549464708,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11556839575c5af55d3464d0-31427060',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c5af55d358510_56456704',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c5af55d358510_56456704')) {function content_5c5af55d358510_56456704($_smarty_tpl) {?>

<div class="alert alert-info">
<img src="../modules/bankwirecopie/bankwirecopie.jpg" style="float:left; margin-right:15px;" width="86" height="49">
<p><strong><?php echo smartyTranslate(array('s'=>"This module allows you to accept secure payments by bank wire.",'mod'=>'bankwirecopie'),$_smarty_tpl);?>
</strong></p>
<p><?php echo smartyTranslate(array('s'=>"If the client chooses to pay by bank wire, the order's status will change to 'Waiting for Payment.'",'mod'=>'bankwirecopie'),$_smarty_tpl);?>
</p>
<p><?php echo smartyTranslate(array('s'=>"That said, you must manually confirm the order upon receiving the bank wire.",'mod'=>'bankwirecopie'),$_smarty_tpl);?>
</p>
</div>
<?php }} ?>
