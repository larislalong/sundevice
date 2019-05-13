<?php /* Smarty version Smarty-3.1.19, created on 2019-05-01 12:32:45
         compiled from "/home/sundevice/public_html/modules/isoorderdetailexport/views/templates/admin/configure.tpl" */ ?>
<?php /*%%SmartyHeaderCode:14475980155cc76c2258d415-56677681%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a9267bbf45a6eb4fba84baedc3c5cba85788a56f' => 
    array (
      0 => '/home/sundevice/public_html/modules/isoorderdetailexport/views/templates/admin/configure.tpl',
      1 => 1556706756,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14475980155cc76c2258d415-56677681',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cc76c225b80d4_65374122',
  'variables' => 
  array (
    'customersList' => 0,
    'customer' => 0,
    'productsList' => 0,
    'product' => 0,
    'attributesList' => 0,
    'attr' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cc76c225b80d4_65374122')) {function content_5cc76c225b80d4_65374122($_smarty_tpl) {?>

<div class="panel">
	<h3><i class="icon icon-folder-open"></i> <?php echo smartyTranslate(array('s'=>'Export instantanné - Filtres','mod'=>'isoorderdetailexport'),$_smarty_tpl);?>
</h3>
	<p class="alert alert-info">
		<?php echo smartyTranslate(array('s'=>'Veuillez remplir ce formulaire pour configurer votre export, puis cliquer sur le bouton "exporter" pour télécharger le fichier.','mod'=>'isoorderdetailexport'),$_smarty_tpl);?>

	</p>

	<form id="product-associations" class="panel product-tab" method="post">
		<div class="form-group clearfix clear">
			<label class="control-label col-lg-3" for="filter_by_date_start">
				<span class="label-tooltip" data-toggle="tooltip"
				title="<?php echo smartyTranslate(array('s'=>'yyyy-mm-dd : Format des dates attendues, merci de le respecter!'),$_smarty_tpl);?>
">
					<?php echo smartyTranslate(array('s'=>'Filtrer par Période'),$_smarty_tpl);?>

				</span>
			</label>
			<div class="col-lg-4">
				<div class="input-group">
					<input class="datepicker" type="text" id="filter_by_date_start" name="filter[date_start]" placeholder="Date de début" autocomplete="off" />
					<span class="input-group-addon"><i class="icon-minus"></i></span>
					<input class="datepicker" type="text" id="filter_by_date_end" name="filter[date_end]" placeholder="Date de fin" autocomplete="off" />
					<span class="input-group-addon"><i class="icon-calendar"></i></span>
				</div>
			</div>
		</div>
		<?php if (isset($_smarty_tpl->tpl_vars['customersList']->value)&&count($_smarty_tpl->tpl_vars['customersList']->value)) {?>
		<div class="form-group clearfix clear">
			<label class="control-label col-lg-3" for="filter_by_customers"><?php echo smartyTranslate(array('s'=>'Filtrer par Clients'),$_smarty_tpl);?>
</label>
			<div class="col-lg-9">
				<select name="filter[customers][]" id="filter_by_customers" multiple="multiple" class="chosen">
					<?php  $_smarty_tpl->tpl_vars['customer'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['customer']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['customersList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['customer']->key => $_smarty_tpl->tpl_vars['customer']->value) {
$_smarty_tpl->tpl_vars['customer']->_loop = true;
?>
						<option value="<?php echo $_smarty_tpl->tpl_vars['customer']->value['id_customer'];?>
"><?php echo $_smarty_tpl->tpl_vars['customer']->value['firstname'];?>
 <?php echo $_smarty_tpl->tpl_vars['customer']->value['lastname'];?>
</option>
					<?php } ?>
				</select>
			</div>
		</div>
		<?php }?>
		<div class="form-group clearfix clear">
			<label class="control-label col-lg-3" for="filter_by_products"><?php echo smartyTranslate(array('s'=>'Filtrer par Produit'),$_smarty_tpl);?>
</label>
			<div class="col-lg-9">
				<?php if (isset($_smarty_tpl->tpl_vars['productsList']->value)&&count($_smarty_tpl->tpl_vars['productsList']->value)) {?>
					<select id="filter_by_products" name="filter[products][]" multiple="multiple" class="chosen">
						<?php  $_smarty_tpl->tpl_vars['product'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['product']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['productsList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['product']->key => $_smarty_tpl->tpl_vars['product']->value) {
$_smarty_tpl->tpl_vars['product']->_loop = true;
?>
							<option value="<?php echo $_smarty_tpl->tpl_vars['product']->value['id_product'];?>
"><?php echo $_smarty_tpl->tpl_vars['product']->value['name'];?>
</option>
						<?php } ?>
					</select>
					<br/>
				<?php }?>
			</div>
		</div>
		<?php if ((isset($_smarty_tpl->tpl_vars['attributesList']->value['grade'])&&count($_smarty_tpl->tpl_vars['attributesList']->value['grade']))||(isset($_smarty_tpl->tpl_vars['attributesList']->value['couleur'])&&count($_smarty_tpl->tpl_vars['attributesList']->value['couleur']))||(isset($_smarty_tpl->tpl_vars['attributesList']->value['capacite'])&&count($_smarty_tpl->tpl_vars['attributesList']->value['capacite']))) {?>
		<div class="form-group clearfix clear">
			<label class="control-label col-lg-3" for="filter_by_products"><?php echo smartyTranslate(array('s'=>'Filtrer par Attributs de produit'),$_smarty_tpl);?>
</label>
			<div class="col-lg-9 row">
			<?php if (isset($_smarty_tpl->tpl_vars['attributesList']->value['grade'])&&count($_smarty_tpl->tpl_vars['attributesList']->value['grade'])) {?>
				<div class="col-lg-3">
					<select id="filter_by_grade" name="filter[product_grade][]" multiple="multiple" class="chosen">
						<option value="">Grade</option>
						<?php  $_smarty_tpl->tpl_vars['attr'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['attr']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['attributesList']->value['grade']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['attr']->key => $_smarty_tpl->tpl_vars['attr']->value) {
$_smarty_tpl->tpl_vars['attr']->_loop = true;
?>
							<option value="<?php echo $_smarty_tpl->tpl_vars['attr']->value['id_attribute'];?>
"><?php echo $_smarty_tpl->tpl_vars['attr']->value['name'];?>
</option>
						<?php } ?>
					</select>
				</div>
			<?php }?>
			<?php if (isset($_smarty_tpl->tpl_vars['attributesList']->value['couleur'])&&count($_smarty_tpl->tpl_vars['attributesList']->value['couleur'])) {?>
				<div class="col-lg-3">
					<select id="filter_by_color" name="filter[product_color][]" multiple="multiple" class="chosen">
						<option value="">Couleur</option>
						<?php  $_smarty_tpl->tpl_vars['attr'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['attr']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['attributesList']->value['couleur']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['attr']->key => $_smarty_tpl->tpl_vars['attr']->value) {
$_smarty_tpl->tpl_vars['attr']->_loop = true;
?>
							<option value="<?php echo $_smarty_tpl->tpl_vars['attr']->value['id_attribute'];?>
"><?php echo $_smarty_tpl->tpl_vars['attr']->value['name'];?>
</option>
						<?php } ?>
					</select>
				</div>
			<?php }?>
			<?php if (isset($_smarty_tpl->tpl_vars['attributesList']->value['capacite'])&&count($_smarty_tpl->tpl_vars['attributesList']->value['capacite'])) {?>
				<div class="col-lg-3">
					<select id="filter_by_capacity" name="filter[product_capacity][]" multiple="multiple" class="chosen">
						<option value="">Capacité</option>
						<?php  $_smarty_tpl->tpl_vars['attr'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['attr']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['attributesList']->value['capacite']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['attr']->key => $_smarty_tpl->tpl_vars['attr']->value) {
$_smarty_tpl->tpl_vars['attr']->_loop = true;
?>
							<option value="<?php echo $_smarty_tpl->tpl_vars['attr']->value['id_attribute'];?>
"><?php echo $_smarty_tpl->tpl_vars['attr']->value['name'];?>
</option>
						<?php } ?>
					</select>
				</div>
			<?php }?>
			</div>
		</div>
		<?php }?>
		<div class="panel-footer clearfix">
			<div class="panel pull-left" style="width:400px;">
				<h3><i class="icon icon-export"></i> <?php echo smartyTranslate(array('s'=>'Export - Commandes','mod'=>'isoorderdetailexport'),$_smarty_tpl);?>
</h3>
				<div class="panel-body clearfix clear">
					<button type="submit" name="exportAndDownload" class="btn btn-default"><i class="process-icon-download"></i> <?php echo smartyTranslate(array('s'=>'Export et Téléchargement'),$_smarty_tpl);?>
</button>
					<button type="submit" name="exportAndMail" class="btn btn-default"><i class="process-icon-envelope"></i> <?php echo smartyTranslate(array('s'=>'Export et Email'),$_smarty_tpl);?>
</button>
				</div>
			</div>
			<div class="panel pull-right" style="width:400px;">
				<h3><i class="icon icon-export"></i> <?php echo smartyTranslate(array('s'=>'Export - Commandes et détails','mod'=>'isoorderdetailexport'),$_smarty_tpl);?>
</h3>
				<div class="panel-body clearfix clear">
					<button type="submit" name="exportDetailsAndDownload" class="btn btn-default"><i class="process-icon-download"></i> <?php echo smartyTranslate(array('s'=>'Export et Téléchargement'),$_smarty_tpl);?>
</button>
					<button type="submit" name="exportDetailsAndMail" class="btn btn-default"><i class="process-icon-envelope"></i> <?php echo smartyTranslate(array('s'=>'Export et Email'),$_smarty_tpl);?>
</button>
				</div>
			</div>
		</div>
		<div class="clear clearfix"></div>
	</form>

</div>

<div class="panel">
	<h3><i class="icon icon-folder-open"></i> <?php echo smartyTranslate(array('s'=>'Export instantanné - Produits et déclinaisons','mod'=>'isoorderdetailexport'),$_smarty_tpl);?>
</h3>
	<div class="panel-body clearfix clear">
		<form id="product-declinaisons" class="product-tab" method="post">
			<button type="submit" name="exportProductAndMail" class="btn btn-default pull-left"><i class="process-icon-envelope"></i> <?php echo smartyTranslate(array('s'=>'Export des produits et Email'),$_smarty_tpl);?>
</button>
		</form>
	</div>
</div>

<script type="text/javascript">
	var geocoder = new google.maps.Geocoder();
	var delivery_map, invoice_map;

	$(document).ready(function(){
		$('.datepicker').datetimepicker({
			prevText: '',
			nextText: '',
			dateFormat: 'yy-mm-dd',
			// Define a custom regional settings in order to use PrestaShop translation tools
			currentText: '<?php echo smartyTranslate(array('s'=>'Now','js'=>1),$_smarty_tpl);?>
',
			closeText: '<?php echo smartyTranslate(array('s'=>'Done','js'=>1),$_smarty_tpl);?>
',
			ampm: false,
			amNames: ['AM', 'A'],
			pmNames: ['PM', 'P'],
			timeFormat: 'hh:mm:ss tt',
			timeSuffix: '',
			timeOnlyTitle: '<?php echo smartyTranslate(array('s'=>'Choose Time','js'=>1),$_smarty_tpl);?>
',
			timeText: '<?php echo smartyTranslate(array('s'=>'Time','js'=>1),$_smarty_tpl);?>
',
			hourText: '<?php echo smartyTranslate(array('s'=>'Hour','js'=>1),$_smarty_tpl);?>
',
			minuteText: '<?php echo smartyTranslate(array('s'=>'Minute','js'=>1),$_smarty_tpl);?>
'
		});
	});
</script>
<?php }} ?>
