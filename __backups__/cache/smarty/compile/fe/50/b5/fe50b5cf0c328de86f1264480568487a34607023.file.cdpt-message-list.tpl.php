<?php /* Smarty version Smarty-3.1.19, created on 2019-02-01 19:10:22
         compiled from "/home/sundevice/public_html/modules/cdprestatiket/views/templates/front/cdpt-message-list.tpl" */ ?>
<?php /*%%SmartyHeaderCode:13320435695c548b8e4a4e56-65366228%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fe50b5cf0c328de86f1264480568487a34607023' => 
    array (
      0 => '/home/sundevice/public_html/modules/cdprestatiket/views/templates/front/cdpt-message-list.tpl',
      1 => 1439466326,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '13320435695c548b8e4a4e56-65366228',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'confirmation' => 0,
    'cdpt_messageID' => 0,
    'MessegeID' => 0,
    'cdpt_Names' => 0,
    'cdpt_controller1' => 0,
    'cdpt_email' => 0,
    'cdpt_IDmessageth' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c548b8e4cd755_21856878',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c548b8e4cd755_21856878')) {function content_5c548b8e4cd755_21856878($_smarty_tpl) {?>

<?php if (isset($_smarty_tpl->tpl_vars['confirmation']->value)) {?>
	<p class="alert alert-success"><?php echo smartyTranslate(array('s'=>'Your message has been successfully sent to our team.','mod'=>'cdprestatiket'),$_smarty_tpl);?>
</p>
<?php } else { ?>
	<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./errors.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<?php }?>

<?php if ($_smarty_tpl->tpl_vars['cdpt_messageID']->value) {?>
	<div class="rows">
	<?php  $_smarty_tpl->tpl_vars['MessegeID'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['MessegeID']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['cdpt_messageID']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['MessegeID']->key => $_smarty_tpl->tpl_vars['MessegeID']->value) {
$_smarty_tpl->tpl_vars['MessegeID']->_loop = true;
?>
		<?php if ($_smarty_tpl->tpl_vars['MessegeID']->value['IDE']==0) {?>
			<br/>
			<div class="rows">
				<div class="col-xs-12 col-sm-12 col-lg-12">
					<fieldset class="cdpt_fieldset">
						<legend><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['cdpt_Names']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</legend>
						<text><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['MessegeID']->value['message'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</text>
					</fieldset>
				</div>
			</div>
		<?php } else { ?>
		<br/>
			<div class="rows">
				<div class="col-xs-12 col-sm-12 col-lg-12">
					<fieldset class="cdpt_fieldset1">
						<legend style="text-align: right;"><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['MessegeID']->value['DEPT'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</legend>
						<text><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['MessegeID']->value['message'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</text>
					</fieldset>
				</div>
			</div>
		<?php }?>
	<?php } ?>
	</div>
	<br />
	<br />
<div class="rows">
	<form action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['cdpt_controller1']->value, ENT_QUOTES, 'UTF-8', true);?>
" method="post">
		<fieldset class="cdpt_fieldset">
			<legend style="text-align: center;"><?php echo smartyTranslate(array('s'=>'answer the message','mod'=>'cdprestatiket'),$_smarty_tpl);?>
</legend>
			<div class="">
                    <label for="message"><?php echo smartyTranslate(array('s'=>'Message','mod'=>'cdprestatiket'),$_smarty_tpl);?>
</label>
                    <textarea style="width: 700px; height: 100px;" id="message" name="message"></textarea>
            </div>
			
			<input type="hidden" name="id_contact" id="id_contact" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['cdpt_messageID']->value[0]['IDC'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" />
			<input type="hidden" name="id_customer_thread" id="id_customer_thread" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['cdpt_messageID']->value[0]['IDM'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" />
			<input type="hidden" id="email" name="from" data-validate="isEmail" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['cdpt_email']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
"/>
			<input type="hidden" name="id_order" id="id_order" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['cdpt_messageID']->value[0]['id_order'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
"/>
			<input type="hidden" name="id_product" id="id_product" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['cdpt_messageID']->value[0]['id_product'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
"/>
			<input type="hidden" name="id_messageth" id="id_messageth" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['cdpt_IDmessageth']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
"/>
			<br />
			<div class="submit">
				<button type="submit" name="cdpt_submitMessage" id="cdpt_submitMessage" class="button btn btn-default button-medium"><span><?php echo smartyTranslate(array('s'=>'Send','mod'=>'cdprestatiket'),$_smarty_tpl);?>
<i class="icon-chevron-right right"></i></span></button>
			</div>
		</fieldset>
	</form>
</div>
<?php }?><?php }} ?>
