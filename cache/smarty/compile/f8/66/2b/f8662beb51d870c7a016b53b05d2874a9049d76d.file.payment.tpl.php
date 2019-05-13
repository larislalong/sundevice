<?php /* Smarty version Smarty-3.1.19, created on 2019-04-30 02:19:18
         compiled from "/home/sundevice/public_html/modules/paypalwithfee/views/templates/front/payment.tpl" */ ?>
<?php /*%%SmartyHeaderCode:5918954405cc79486bb3de7-23530019%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f8662beb51d870c7a016b53b05d2874a9049d76d' => 
    array (
      0 => '/home/sundevice/public_html/modules/paypalwithfee/views/templates/front/payment.tpl',
      1 => 1554927945,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5918954405cc79486bb3de7-23530019',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'ps_version' => 0,
    'path' => 0,
    'fee' => 0,
    'path_controller' => 0,
    'total_ppwf' => 0,
    'cancelURL' => 0,
    'returnURL' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cc79486bcee22_24941074',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cc79486bcee22_24941074')) {function content_5cc79486bcee22_24941074($_smarty_tpl) {?>

<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>=1.6) {?>
    <div class="row">
        <div class="col-xs-12">
        <?php }?>    
        <p class="payment_module">
            <a href="javascript:$('#paypal_payment_ppwf').submit()" id="paypal_process_payment_ppwf" class="<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>=1.6) {?>cash<?php }?>" title="<?php echo smartyTranslate(array('s'=>'Pay with PayPal','mod'=>'paypalwithfee'),$_smarty_tpl);?>
" style="<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>=1.6) {?>background:url('<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['path']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
views/img/paypal-64.png')no-repeat 15px 15px #fbfbfb<?php }?>">
                <?php if ($_smarty_tpl->tpl_vars['ps_version']->value<1.6) {?>
                    <img src="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['path']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
views/img/paypal-64.png"/>
                <?php }?>
                <?php echo smartyTranslate(array('s'=>'Pay with PayPal.','mod'=>'paypalwithfee'),$_smarty_tpl);?>

                <?php if ($_smarty_tpl->tpl_vars['fee']->value>0) {?>
                    <span>
                        <?php echo smartyTranslate(array('s'=>'Fee:','mod'=>'paypalwithfee'),$_smarty_tpl);?>
 
                        <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['convertPrice'][0][0]->convertPrice(array('price'=>mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['fee']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8')),$_smarty_tpl);?>
 
                    </span>
                <?php }?>
            </a>
        </p>
        <form id="paypal_payment_ppwf" action="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['path_controller']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" title="<?php echo smartyTranslate(array('s'=>'Pay with PayPal','mod'=>'paypalwithfee'),$_smarty_tpl);?>
" method="post" data-fee="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['fee']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" data-fee-formatted="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['convertPrice'][0][0]->convertPrice(array('price'=>mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['fee']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8')),$_smarty_tpl);?>
" data-total="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['total_ppwf']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" data-total-formatted="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['convertPrice'][0][0]->convertPrice(array('price'=>mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['total_ppwf']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8')),$_smarty_tpl);?>
">
            <input type="hidden" name="cancelURL" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['cancelURL']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" />
            <input type="hidden" name="returnURL" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['returnURL']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" />
        </form>
        <?php if ($_smarty_tpl->tpl_vars['ps_version']->value>=1.6) {?>
        </div>
    </div>
<?php }?> 

<?php }} ?>
