<?php /* Smarty version Smarty-3.1.19, created on 2019-04-30 09:22:26
         compiled from "/home/sundevice/public_html/modules/cdprestatiket/views/templates/front/cdptListingMessage.tpl" */ ?>
<?php /*%%SmartyHeaderCode:13113808015cc7f7b21ed695-40576220%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '971ecf9ad171ce7bb9bc5034aeeae0e5210893ce' => 
    array (
      0 => '/home/sundevice/public_html/modules/cdprestatiket/views/templates/front/cdptListingMessage.tpl',
      1 => 1535710476,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '13113808015cc7f7b21ed695-40576220',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'confirmation' => 0,
    'cdpt_controller1' => 0,
    'cdpt_fields_message' => 0,
    'fieldMessege' => 0,
    'cdpt_compt' => 0,
    'cdpt_controller10' => 0,
    'cdpt_linkmessage1' => 0,
    'cdpt_nbr_message' => 0,
    'cdpt_nbr_page' => 0,
    'p' => 0,
    'cdpt_num_page' => 0,
    'cdpt_page' => 0,
    'cdpt_class' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cc7f7b2291e14_34228000',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cc7f7b2291e14_34228000')) {function content_5cc7f7b2291e14_34228000($_smarty_tpl) {?>

<?php $_smarty_tpl->_capture_stack[0][] = array('path', null, null); ob_start(); ?><?php echo smartyTranslate(array('s'=>'Ticket-Messages','mod'=>'cdprestatiket'),$_smarty_tpl);?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<?php if (isset($_smarty_tpl->tpl_vars['confirmation']->value)) {?>
	<p class="alert alert-success"><?php echo smartyTranslate(array('s'=>'Your message has been successfully sent to our team.','mod'=>'cdprestatiket'),$_smarty_tpl);?>
</p>
<?php } else { ?>
	<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./errors.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<?php }?>

<div>
	<p><a class="btn btn-primary btn-lg cdpt-top-menu" href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['cdpt_controller1']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" role="button"><?php echo smartyTranslate(array('s'=>'Send a new Ticket','mod'=>'cdprestatiket'),$_smarty_tpl);?>
</a></p>
<div><br /><br />
<br />
<?php if ($_smarty_tpl->tpl_vars['cdpt_fields_message']->value) {?>
	<div class="panel panel-default">
		<!-- Default panel contents -->
		<div class="panel-heading cdpt_title"><?php echo smartyTranslate(array('s'=>'All the Messages','mod'=>'cdprestatiket'),$_smarty_tpl);?>
</div>

		<!-- Table -->
		<table class="table">
			<tr class="rows cdpt-top-table">
				<td class="col-xs-1 col-sm-1 col-lg-1">
					<?php echo smartyTranslate(array('s'=>'ID','mod'=>'cdprestatiket'),$_smarty_tpl);?>

				</td>
				<td class="col-xs-1 col-sm-1 col-lg-1">
					<?php echo smartyTranslate(array('s'=>'Department','mod'=>'cdprestatiket'),$_smarty_tpl);?>

				</td>
				<td class="col-xs-8 col-sm-7 col-lg-5">
					<?php echo smartyTranslate(array('s'=>'Last Message','mod'=>'cdprestatiket'),$_smarty_tpl);?>

				</td>
				<td class="col-xs-1 col-sm-1 col-lg-1">
					<?php echo smartyTranslate(array('s'=>'Order ID','mod'=>'cdprestatiket'),$_smarty_tpl);?>

				</td>
				<td class="col-xs-1 col-sm-1 col-lg-1">
					<?php echo smartyTranslate(array('s'=>'Product ID','mod'=>'cdprestatiket'),$_smarty_tpl);?>

				</td>
				<td class="col-xs-1 col-sm-1 col-lg-1">
					<?php echo smartyTranslate(array('s'=>'Status','mod'=>'cdprestatiket'),$_smarty_tpl);?>

				</td>
				<td class="col-xs-1 col-sm-1 col-lg-1">
					<?php echo smartyTranslate(array('s'=>'See All','mod'=>'cdprestatiket'),$_smarty_tpl);?>

				</td>
			</tr>
			<?php $_smarty_tpl->tpl_vars['cdpt_compt'] = new Smarty_variable(0, null, 0);?>
			<?php  $_smarty_tpl->tpl_vars['fieldMessege'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['fieldMessege']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['cdpt_fields_message']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['fieldMessege']->key => $_smarty_tpl->tpl_vars['fieldMessege']->value) {
$_smarty_tpl->tpl_vars['fieldMessege']->_loop = true;
?>
				<tr class="rows">
					<td class="col-xs-1 col-sm-1 col-lg-1">
						<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['fieldMessege']->value['ID'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>

					</td>
					<td class="col-xs-1 col-sm-1 col-lg-1">
						<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['fieldMessege']->value['DEPT'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>

					</td>
					<td class="col-xs-8 col-sm-7 col-lg-5">
						<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['fieldMessege']->value['message'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>

					</td>
					<td class="col-xs-1 col-sm-1 col-lg-1">
						<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['fieldMessege']->value['id_order'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>

					</td>
					<td class="col-xs-1 col-sm-1 col-lg-1">
						<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['fieldMessege']->value['id_product'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>

					</td>
					<td class="col-xs-1 col-sm-1 col-lg-1">
						<i class="icon-circle" style="color: <?php if ($_smarty_tpl->tpl_vars['fieldMessege']->value['status']=='closed') {?> red <?php } else { ?> green <?php }?>"></i>
					</td>
					<td class="col-xs-1 col-sm-1 col-lg-1">
						<?php $_smarty_tpl->tpl_vars['cdpt_linkmessage1'] = new Smarty_variable(("cdptmessage_").($_smarty_tpl->tpl_vars['cdpt_compt']->value), null, 0);?>
						<form action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['cdpt_controller10']->value, ENT_QUOTES, 'UTF-8', true);?>
" method="post">
							<input type="hidden" name="<?php echo $_smarty_tpl->tpl_vars['cdpt_linkmessage1']->value;?>
" id="<?php echo $_smarty_tpl->tpl_vars['cdpt_linkmessage1']->value;?>
" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['fieldMessege']->value['IDM'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
"/>
							<button type="submit" name="cdpt_submitAllMessage" id="cdpt_submitAllMessage" class="btn btn-primary btn-lg"><span><?php echo smartyTranslate(array('s'=>'See All','mod'=>'cdprestatiket'),$_smarty_tpl);?>
</span></button>
						</form>
						<?php $_smarty_tpl->tpl_vars['cdpt_compt'] = new Smarty_variable($_smarty_tpl->tpl_vars['cdpt_compt']->value+1, null, 0);?>
					</td>
				</tr>
			<?php } ?>
		</table>
	</div>
	<?php if ($_smarty_tpl->tpl_vars['cdpt_nbr_message']->value>$_smarty_tpl->tpl_vars['cdpt_nbr_page']->value) {?>
		<div class="bx-controls-direction">
			<center>
				<h4>
					<table class="pagination">
						<th>
						<?php $_smarty_tpl->tpl_vars['p'] = new Smarty_variable(1, null, 0);?>
						<?php $_smarty_tpl->tpl_vars['var'] = new Smarty_Variable;$_smarty_tpl->tpl_vars['var']->step = $_smarty_tpl->tpl_vars['cdpt_nbr_page']->value;$_smarty_tpl->tpl_vars['var']->total = (int) ceil(($_smarty_tpl->tpl_vars['var']->step > 0 ? $_smarty_tpl->tpl_vars['cdpt_nbr_message']->value+1 - (1) : 1-($_smarty_tpl->tpl_vars['cdpt_nbr_message']->value)+1)/abs($_smarty_tpl->tpl_vars['var']->step));
if ($_smarty_tpl->tpl_vars['var']->total > 0) {
for ($_smarty_tpl->tpl_vars['var']->value = 1, $_smarty_tpl->tpl_vars['var']->iteration = 1;$_smarty_tpl->tpl_vars['var']->iteration <= $_smarty_tpl->tpl_vars['var']->total;$_smarty_tpl->tpl_vars['var']->value += $_smarty_tpl->tpl_vars['var']->step, $_smarty_tpl->tpl_vars['var']->iteration++) {
$_smarty_tpl->tpl_vars['var']->first = $_smarty_tpl->tpl_vars['var']->iteration == 1;$_smarty_tpl->tpl_vars['var']->last = $_smarty_tpl->tpl_vars['var']->iteration == $_smarty_tpl->tpl_vars['var']->total;?>
							<?php $_smarty_tpl->tpl_vars['cdpt_class'] = new Smarty_variable("btn btn-default", null, 0);?>
							<?php if ($_smarty_tpl->tpl_vars['p']->value==$_smarty_tpl->tpl_vars['cdpt_num_page']->value) {?>
								<?php $_smarty_tpl->tpl_vars['cdpt_class'] = new Smarty_variable("btn btn-primary", null, 0);?>
							<?php }?>
							<td>
								<?php $_smarty_tpl->tpl_vars['cdpt_page'] = new Smarty_variable(("cdptpage_").($_smarty_tpl->tpl_vars['p']->value), null, 0);?>
								<form action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['cdpt_controller10']->value, ENT_QUOTES, 'UTF-8', true);?>
" method="post">
									<input type="hidden" name="<?php echo $_smarty_tpl->tpl_vars['cdpt_page']->value;?>
" id="<?php echo $_smarty_tpl->tpl_vars['cdpt_page']->value;?>
" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['p']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
"/>
									<button type="submit" name="cdpt_submitPage" id="lien" class="<?php echo $_smarty_tpl->tpl_vars['cdpt_class']->value;?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['p']->value, ENT_QUOTES, 'UTF-8', true);?>
</button>
								</form>
							</td>
						<?php $_smarty_tpl->tpl_vars['p'] = new Smarty_variable($_smarty_tpl->tpl_vars['p']->value+1, null, 0);?>
						<?php }} ?>
						</th>
					</table>
				</h4>
			</center>
		</div>
	<?php }?>
<?php }?>
<?php }} ?>
