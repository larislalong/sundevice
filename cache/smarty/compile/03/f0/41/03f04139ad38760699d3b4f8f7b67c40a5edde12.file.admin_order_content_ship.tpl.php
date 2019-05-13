<?php /* Smarty version Smarty-3.1.19, created on 2019-04-29 17:32:46
         compiled from "/home/sundevice/public_html/modules/paypalwithfee//views/templates/hook/admin_order_content_ship.tpl" */ ?>
<?php /*%%SmartyHeaderCode:5511836625cc7191e800585-20783271%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '03f04139ad38760699d3b4f8f7b67c40a5edde12' => 
    array (
      0 => '/home/sundevice/public_html/modules/paypalwithfee//views/templates/hook/admin_order_content_ship.tpl',
      1 => 1554927945,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5511836625cc7191e800585-20783271',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'ppwfv' => 0,
    'id_currency' => 0,
    'fee' => 0,
    'paypalwf' => 0,
    'invoices_collection_' => 0,
    'invoice_number_' => 0,
    'form_go_ppwf_generatepdf' => 0,
    'refund' => 0,
    'form_go_ppwf_refund' => 0,
    'max_refund' => 0,
    'ppwfmessage_ok' => 0,
    'ppwfmessage_error' => 0,
    'ref' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cc7191e84c136_66507517',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cc7191e84c136_66507517')) {function content_5cc7191e84c136_66507517($_smarty_tpl) {?>
<div class="tab-pane active" id="paypalwithfee">
    <h4 class="visible-print"><?php echo smartyTranslate(array('s'=>'Paypal with fee','mod'=>'paypalwithfee'),$_smarty_tpl);?>
 <span class="badge">(1)</span></h4>
    <div class="form-horizontal">
        <div class="table-responsive">
            <div class="ppwf">
                <?php if (!$_smarty_tpl->tpl_vars['ppwfv']->value) {?><?php echo smartyTranslate(array('s'=>'* The cost of paypal with fee, are added into shipping cost.','mod'=>'paypalwithfee'),$_smarty_tpl);?>
<?php }?>
                <hr/>
                <p><?php echo smartyTranslate(array('s'=>'Paypal fee:','mod'=>'paypalwithfee'),$_smarty_tpl);?>
 <strong><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['displayPrice'][0][0]->displayPriceSmarty(array('currency'=>$_smarty_tpl->tpl_vars['id_currency']->value,'price'=>mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['fee']->value['fee'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8')),$_smarty_tpl);?>
</strong></p>
                <?php if (Configuration::get('PPAL_CUSTOM_INVOICE')==1) {?><p><?php echo smartyTranslate(array('s'=>'Tax:','mod'=>'paypalwithfee'),$_smarty_tpl);?>
 <strong><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['fee']->value['tax_rate'], ENT_QUOTES, 'UTF-8', true);?>
 %</strong></p><?php }?>
                <p><?php echo smartyTranslate(array('s'=>'Paypal Transaction ID:','mod'=>'paypalwithfee'),$_smarty_tpl);?>
 <strong><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['fee']->value['transaction_id'], ENT_QUOTES, 'UTF-8', true);?>
</strong></p>
                <?php if (isset($_smarty_tpl->tpl_vars['paypalwf']->value['total_paypal'])&&$_smarty_tpl->tpl_vars['paypalwf']->value['total_paypal']>0) {?>
                    <p class="alert <?php if ($_smarty_tpl->tpl_vars['paypalwf']->value['total_amount']!=$_smarty_tpl->tpl_vars['paypalwf']->value['total_paypal']) {?>alert-danger<?php } else { ?> alert-success<?php }?>">
                        <?php echo smartyTranslate(array('s'=>'Prestashop payment','mod'=>'paypalwithfee'),$_smarty_tpl);?>
: <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['convertPrice'][0][0]->convertPrice(array('price'=>mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['paypalwf']->value['total_amount'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8')),$_smarty_tpl);?>
 - <?php echo smartyTranslate(array('s'=>'Paypal payment','mod'=>'paypalwithfee'),$_smarty_tpl);?>
: <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['convertPrice'][0][0]->convertPrice(array('price'=>mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['paypalwf']->value['total_paypal'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8')),$_smarty_tpl);?>

                    </p>
                <?php }?>
            </div>
        </div>
            
        <?php if (Configuration::get('PS_INVOICE')&&count($_smarty_tpl->tpl_vars['invoices_collection_']->value)&&$_smarty_tpl->tpl_vars['invoice_number_']->value&&!$_smarty_tpl->tpl_vars['ppwfv']->value) {?>
            <div class="well">
                <a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['form_go_ppwf_generatepdf']->value, ENT_QUOTES, 'UTF-8', true);?>
&submitAction=down_invoice_ppal" class="btn btn-default _blank" target="_blank">
                    <i class="icon-download"></i>
                    <?php echo smartyTranslate(array('s'=>'Download Invoice','mod'=>'paypalwithfee'),$_smarty_tpl);?>

                </a>
            </div>
        <?php }?>
    </div>
    <hr>
    <div id="paypalwithfee_refund">
        <h4 class="">
            <?php echo smartyTranslate(array('s'=>'Paypal with fee refund','mod'=>'paypalwithfee'),$_smarty_tpl);?>
 <span class="badge"><?php echo mb_convert_encoding(htmlspecialchars(count($_smarty_tpl->tpl_vars['refund']->value), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</span>
        </h4>
        <form id="paypalwithfee_refund_form" class="well" method="post" action="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['form_go_ppwf_refund']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" onSubmit="if (!confirm('<?php echo smartyTranslate(array('s'=>'This process not has turning back. Are you sure that do you want continue?','mod'=>'paypalwithfee'),$_smarty_tpl);?>
'))
                    return false;">
            <?php if (count($_smarty_tpl->tpl_vars['refund']->value)>0&&!empty($_smarty_tpl->tpl_vars['refund']->value)) {?>
                <p><?php echo smartyTranslate(array('s'=>'You can do a partial refund. Can not do a full refund after a partial refund.','mod'=>'paypalwithfee'),$_smarty_tpl);?>
</p>
                <label><input type='radio' id="ppwf_partial_refund" name='refund' value='0' checked> <span>Partial refund</span></label>
                <?php } else { ?>
                <p><?php echo smartyTranslate(array('s'=>'You can do a partial refund or full refund of paypal payment.','mod'=>'paypalwithfee'),$_smarty_tpl);?>
</p>
                <label><input type='radio' id="ppwf_total_refund" name='refund' value='1'> <span>Full refund</span></label><br/>
                <label><input type='radio' id="ppwf_partial_refund" name='refund' value='0' checked> <span>Partial refund</span></label>
                <?php }?>
            <div id="ppwf_refund_content">
                <label for="ppwf_refund_amount"><?php echo smartyTranslate(array('s'=>'Amount to refund:','mod'=>'paypalwithfee'),$_smarty_tpl);?>

                    <input type="text" id="ppwf_refund_amount" max="<?php if ($_smarty_tpl->tpl_vars['max_refund']->value) {?><?php echo sprintf('%.2f',$_smarty_tpl->tpl_vars['fee']->value['total_amount'])-mb_convert_encoding(htmlspecialchars(sprintf('%.2f',$_smarty_tpl->tpl_vars['max_refund']->value), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php } else { ?><?php echo mb_convert_encoding(htmlspecialchars(sprintf('%.2f',$_smarty_tpl->tpl_vars['fee']->value['total_amount']), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php }?>" name="ppwf_refund_amount"></label> <input class="btn btn-default" type="submit" name="ppwf_refund" value="<?php echo smartyTranslate(array('s'=>'Refund','mod'=>'paypalwithfee'),$_smarty_tpl);?>
"/>
            </div>
        </form>
        <?php if (isset($_smarty_tpl->tpl_vars['ppwfmessage_ok']->value)) {?><p class="alert alert-success"><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['ppwfmessage_ok']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</p><?php }?>
        <?php if (isset($_smarty_tpl->tpl_vars['ppwfmessage_error']->value)) {?><p class="alert alert-danger"><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['ppwfmessage_error']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</p><?php }?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th><span class="title_box "><?php echo smartyTranslate(array('s'=>'Date','mod'=>'paypalwithfee'),$_smarty_tpl);?>
</span></th>
                        <th><span class="title_box "><?php echo smartyTranslate(array('s'=>'Amount','mod'=>'paypalwithfee'),$_smarty_tpl);?>
</span></th>
                        <th><span class="title_box "><?php echo smartyTranslate(array('s'=>'Transaction ID','mod'=>'paypalwithfee'),$_smarty_tpl);?>
</span></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($_smarty_tpl->tpl_vars['refund']->value)>0&&!empty($_smarty_tpl->tpl_vars['refund']->value)) {?>
                        <?php  $_smarty_tpl->tpl_vars['ref'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['ref']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['refund']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['ref']->key => $_smarty_tpl->tpl_vars['ref']->value) {
$_smarty_tpl->tpl_vars['ref']->_loop = true;
?>
                            <tr>
                                <td><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['ref']->value['date'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</td>
                                <td><?php echo mb_convert_encoding(htmlspecialchars(sprintf("%.2f",$_smarty_tpl->tpl_vars['ref']->value['amount']), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</td>
                                <td><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['ref']->value['transaction_id'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</td>
                            </tr>
                        <?php } ?>
                    <?php }?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#shipping').removeClass('active');

        $(document).ready(function () {
            $('#paypalwithfee_refund input[name="refund"]').change(function () {
                var how_refund = $(this).val();
                if (how_refund == 1) {
                    $('#ppwf_refund_amount').val(parseFloat($('#ppwf_refund_amount').attr('max')).toFixed(2)).prop('disabled', true);
                } else {
                    $('#ppwf_refund_amount').val('').prop('disabled', false);
                }
            });

            $('#ppwf_refund_amount').change(function () {
                //$(this).val(parseFloat($("ppwf_refund_amount").val(),10).toFixed(2));
            });

        });
    });
</script><?php }} ?>
