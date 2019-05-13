<?php /* Smarty version Smarty-3.1.19, created on 2019-05-02 18:37:27
         compiled from "/home/sundevice/public_html/modules/isostats/views/templates/admin/isoStatsGeneral.tpl" */ ?>
<?php /*%%SmartyHeaderCode:7852447385ccb1cc7aeff86-79517371%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd6410e45209097388767935a56c467055737eebc' => 
    array (
      0 => '/home/sundevice/public_html/modules/isostats/views/templates/admin/isoStatsGeneral.tpl',
      1 => 1554900463,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7852447385ccb1cc7aeff86-79517371',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'allStats' => 0,
    'salesDetailsLink' => 0,
    'orderStats' => 0,
    'status' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5ccb1cc7b182b7_95343727',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5ccb1cc7b182b7_95343727')) {function content_5ccb1cc7b182b7_95343727($_smarty_tpl) {?>

<style>
	.box-stats .title {
		color:#333;
		margin-bottom:10px;
		font-size: 14px;
	}
	.box-stats .subtitle {
		background-color:#333;
		width:auto;
		color:#fff !important;
		margin-bottom:10px;
		text-align:left !important;
		padding:7px !important;
		font-size: 12px;
		line-height: 18px;
	}
</style>
<div class="panel">
	<h3><i class="icon icon-credit-card"></i> <?php echo smartyTranslate(array('s'=>'isoStats','mod'=>'isostats'),$_smarty_tpl);?>
</h3>
	<div class="panel kpi-container">
		<div class="row">
			<div class="col-sm-6 col-lg-3">
				<div id="box-sales-count" data-toggle="tooltip" class="box-stats label-tooltip color1" data-original-title="">
					<div class="kpi-content">
						<i class="icon-truck"></i>
						<span class="title">Produits vendus (Nombre)</span>
						<span class="subtitle label">Tout<br><?php echo $_smarty_tpl->tpl_vars['allStats']->value['count']['all'];?>
</span>
						<span class="subtitle label">Boîtes<br><?php echo $_smarty_tpl->tpl_vars['allStats']->value['count']['box'];?>
</span>
						<span class="subtitle label">iPhone<br><?php echo $_smarty_tpl->tpl_vars['allStats']->value['count']['iphone'];?>
</span>
						<span class="subtitle label">iPad<br><?php echo $_smarty_tpl->tpl_vars['allStats']->value['count']['ipad'];?>
</span>
						<span class="subtitle label">iWatch<br><?php echo $_smarty_tpl->tpl_vars['allStats']->value['count']['iwatch'];?>
</span>
						<span class="subtitle label">Samsung<br><?php echo $_smarty_tpl->tpl_vars['allStats']->value['count']['samsung'];?>
</span>
					</div>
					
				</div>
			</div>
			<div class="col-sm-6 col-lg-3">
				<a style="display:block" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['salesDetailsLink']->value, ENT_QUOTES, 'UTF-8', true);?>
" id="box-sales-amount" data-toggle="tooltip" class="box-stats label-tooltip color2" data-original-title="">
					<div class="kpi-content">
						<i class="icon-money"></i>
						<span class="title">Produits vendus (Montant)</span>
						<span class="subtitle label color_field">Tout<br><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['convertPrice'][0][0]->convertPrice(array('price'=>$_smarty_tpl->tpl_vars['allStats']->value['price']['all']),$_smarty_tpl);?>
</span>
						<span class="subtitle label color_field">Boîtes<br><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['convertPrice'][0][0]->convertPrice(array('price'=>$_smarty_tpl->tpl_vars['allStats']->value['price']['box']),$_smarty_tpl);?>
</span>
						<span class="subtitle label color_field">iPhone<br><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['convertPrice'][0][0]->convertPrice(array('price'=>$_smarty_tpl->tpl_vars['allStats']->value['price']['iphone']),$_smarty_tpl);?>
</span>
						<span class="subtitle label color_field">iPad<br><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['convertPrice'][0][0]->convertPrice(array('price'=>$_smarty_tpl->tpl_vars['allStats']->value['price']['ipad']),$_smarty_tpl);?>
</span>
						<span class="subtitle label color_field">iWatch<br><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['convertPrice'][0][0]->convertPrice(array('price'=>$_smarty_tpl->tpl_vars['allStats']->value['price']['iwatch']),$_smarty_tpl);?>
</span>
						<span class="subtitle label color_field">Samsung<br><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['convertPrice'][0][0]->convertPrice(array('price'=>$_smarty_tpl->tpl_vars['allStats']->value['price']['samsung']),$_smarty_tpl);?>
</span>
					</div>
					
				</a>
			</div>
			<div class="col-sm-6 col-lg-3">
				<div id="box-order-state" data-toggle="tooltip" class="box-stats label-tooltip color3" data-original-title="">
					<div class="kpi-content">
						<i class="icon-archive"></i>
						<span class="title">État de commandes (Montant / Livraison)</span>
						<?php if (isset($_smarty_tpl->tpl_vars['orderStats']->value['status'])&&count($_smarty_tpl->tpl_vars['orderStats']->value['status'])) {?>
							<?php  $_smarty_tpl->tpl_vars['status'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['status']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['orderStats']->value['status']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['status']->key => $_smarty_tpl->tpl_vars['status']->value) {
$_smarty_tpl->tpl_vars['status']->_loop = true;
?>
								<span class="subtitle label color_field" style="background-color:<?php echo $_smarty_tpl->tpl_vars['status']->value['color'];?>
;"><?php echo $_smarty_tpl->tpl_vars['status']->value['name'];?>
<br><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['convertPrice'][0][0]->convertPrice(array('price'=>$_smarty_tpl->tpl_vars['status']->value['total']),$_smarty_tpl);?>
 / <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['convertPrice'][0][0]->convertPrice(array('price'=>$_smarty_tpl->tpl_vars['status']->value['shipping']),$_smarty_tpl);?>
</span>
							<?php } ?>
						<?php }?>
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-lg-3">
				
			</div>
		</div>
	</div>
	<form>
	
	
		<div class="panel-footer">
			<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['salesDetailsLink']->value, ENT_QUOTES, 'UTF-8', true);?>
" class="btn btn-default pull-right"><i class="icon-shopping-cart"></i> <?php echo smartyTranslate(array('s'=>'Détails des ventes'),$_smarty_tpl);?>
</a>
		</div>
	</form>
</div><?php }} ?>
