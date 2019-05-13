<?php /* Smarty version Smarty-3.1.19, created on 2019-01-29 11:26:58
         compiled from "/home/sundevice/public_html/modules/blocklayeredcustom/views/templates/hook/filter_center.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2687060335c502a725bca18-44406292%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1e2dbc4b84d10ae7fa31b1b67a6de3bd3904736e' => 
    array (
      0 => '/home/sundevice/public_html/modules/blocklayeredcustom/views/templates/hook/filter_center.tpl',
      1 => 1536939589,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2687060335c502a725bca18-44406292',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'hasNoCombinations' => 0,
    'id_category' => 0,
    'category' => 0,
    'link' => 0,
    'buyermember' => 0,
    'templateFolder' => 0,
    'selectedOrderColumnType' => 0,
    'orderColumnTypeConst' => 0,
    'selectedOrderWay' => 0,
    'orderWayConst' => 0,
    'priceOrderWay' => 0,
    'priceOrderWayLabel' => 0,
    'priceOrderWayAsc' => 0,
    'priceOrderWayDesc' => 0,
    'selectedOrderColumn' => 0,
    'blcImgDir' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c502a72637051_97798027',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c502a72637051_97798027')) {function content_5c502a72637051_97798027($_smarty_tpl) {?>

<?php if ($_smarty_tpl->tpl_vars['hasNoCombinations']->value) {?>
	<?php echo smartyTranslate(array('s'=>'There are no combinations','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>

<?php } else { ?>
	<?php if ($_smarty_tpl->tpl_vars['id_category']->value>0) {?>
        <div id="infos-category" class="col-lg-12 cleafix">
            <div class="block_title_subtitle_img">
                <div class="block_title_subtitle">
            		<h1 class="title"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['category']->value->title, ENT_QUOTES, 'UTF-8', true);?>
</h1>
            		<h2 class="subtitle"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['category']->value->subtitle, ENT_QUOTES, 'UTF-8', true);?>
</h2>
                </div>
                <figure><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getCatImageLink($_smarty_tpl->tpl_vars['category']->value->link_rewrite,$_smarty_tpl->tpl_vars['category']->value->id_image,'category_default'), ENT_QUOTES, 'UTF-8', true);?>
" alt="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['category']->value->name, ENT_QUOTES, 'UTF-8', true);?>
" class="img-responsive" /></figure>
            </div>
            <div class="rte"><?php echo $_smarty_tpl->tpl_vars['category']->value->description;?>
</div>
        </div>
        
    <?php }?>
<div class="row attributes-filter-head<?php if ($_smarty_tpl->tpl_vars['buyermember']->value) {?> buyermenber-h<?php }?>">
	<div class="col-lg-3 products-block">
        <h3 class="filter-title1"><?php echo smartyTranslate(array('s'=>'Sort By :','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>
</h3>
	</div>
	<div class="col-lg-3 attributes-groups-block">
		<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['templateFolder']->value)."attribute_groups_list.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

	</div>
	
	<?php if (!$_smarty_tpl->tpl_vars['buyermember']->value) {?>
	<div class="col-lg-4 price-filter-block">
        <?php $_smarty_tpl->tpl_vars['priceOrderWayAsc'] = new Smarty_variable(false, null, 0);?>
		<?php $_smarty_tpl->tpl_vars['priceOrderWayDesc'] = new Smarty_variable(false, null, 0);?>
		<?php $_smarty_tpl->tpl_vars['priceOrderWay'] = new Smarty_variable(false, null, 0);?>
		<?php ob_start();?><?php echo smartyTranslate(array('s'=>'Price:','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>
<?php $_tmp13=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['priceOrderWayLabel'] = new Smarty_variable($_tmp13, null, 0);?>
		<?php if (($_smarty_tpl->tpl_vars['selectedOrderColumnType']->value==$_smarty_tpl->tpl_vars['orderColumnTypeConst']->value['ORDER_COLUMN_TYPE_PRICE'])&&($_smarty_tpl->tpl_vars['selectedOrderWay']->value==$_smarty_tpl->tpl_vars['orderWayConst']->value['ORDER_WAY_ASC'])) {?>
			<?php $_smarty_tpl->tpl_vars['priceOrderWayAsc'] = new Smarty_variable(true, null, 0);?>
			<?php $_smarty_tpl->tpl_vars['priceOrderWay'] = new Smarty_variable($_smarty_tpl->tpl_vars['orderWayConst']->value['ORDER_WAY_ASC'], null, 0);?>
			<?php ob_start();?><?php echo smartyTranslate(array('s'=>'Price: Low to high','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>
<?php $_tmp14=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['priceOrderWayLabel'] = new Smarty_variable($_tmp14, null, 0);?>
		<?php } elseif (($_smarty_tpl->tpl_vars['selectedOrderColumnType']->value==$_smarty_tpl->tpl_vars['orderColumnTypeConst']->value['ORDER_COLUMN_TYPE_PRICE'])&&($_smarty_tpl->tpl_vars['selectedOrderWay']->value==$_smarty_tpl->tpl_vars['orderWayConst']->value['ORDER_WAY_DESC'])) {?>
			<?php $_smarty_tpl->tpl_vars['priceOrderWayDesc'] = new Smarty_variable(true, null, 0);?>
			<?php $_smarty_tpl->tpl_vars['priceOrderWay'] = new Smarty_variable($_smarty_tpl->tpl_vars['orderWayConst']->value['ORDER_WAY_DESC'], null, 0);?>
			<?php ob_start();?><?php echo smartyTranslate(array('s'=>'Price: High to low','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>
<?php $_tmp15=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['priceOrderWayLabel'] = new Smarty_variable($_tmp15, null, 0);?>
		<?php }?>
        <div class="dropdown attribute-order field-attribute-order<?php if ($_smarty_tpl->tpl_vars['selectedOrderColumnType']->value==$_smarty_tpl->tpl_vars['orderColumnTypeConst']->value['ORDER_COLUMN_TYPE_PRICE']) {?> active<?php }?>" data-way-selected="<?php echo intval($_smarty_tpl->tpl_vars['priceOrderWay']->value);?>
">
          <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
			  <span class="selected-name"><?php echo $_smarty_tpl->tpl_vars['priceOrderWayLabel']->value;?>
</span>
              <i class="fa fa-angle-down"></i>
          </button>
		  
          <ul class="dropdown-menu">
		      <li><a href="#" data-name-order-way="<?php echo smartyTranslate(array('s'=>'Price:','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>
" data-way="<?php echo $_smarty_tpl->tpl_vars['orderWayConst']->value['ORDER_WAY_NONE'];?>
" class="order-item <?php if (!$_smarty_tpl->tpl_vars['priceOrderWayAsc']->value&&$_smarty_tpl->tpl_vars['priceOrderWayDesc']->value) {?>active<?php }?>"><?php echo smartyTranslate(array('s'=>'Price:','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>
</a></li>
              <li><a href="#" data-name-order-way="<?php echo smartyTranslate(array('s'=>'Price: Low to high','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>
" data-way="<?php echo $_smarty_tpl->tpl_vars['orderWayConst']->value['ORDER_WAY_ASC'];?>
" class="order-item <?php if ($_smarty_tpl->tpl_vars['priceOrderWayAsc']->value) {?>active<?php }?>"><?php echo smartyTranslate(array('s'=>'Price: Low to high','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>
</a></li>
              <li><a href="#" data-name-order-way="<?php echo smartyTranslate(array('s'=>'Price: High to low','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>
" data-way="<?php echo $_smarty_tpl->tpl_vars['orderWayConst']->value['ORDER_WAY_DESC'];?>
" class="order-item <?php if ($_smarty_tpl->tpl_vars['priceOrderWayDesc']->value) {?>active<?php }?>"><?php echo smartyTranslate(array('s'=>'Price: High to low','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>
</a></li>
          </ul>
        </div>
	</div>
	<?php }?>
	<input type="hidden" class="selected-order-way" value="<?php echo intval($_smarty_tpl->tpl_vars['selectedOrderWay']->value);?>
"/>
	<input type="hidden" class="selected-order-column" value="<?php echo intval($_smarty_tpl->tpl_vars['selectedOrderColumn']->value);?>
"/>
	<input type="hidden" class="selected-order-column-type" value="<?php echo intval($_smarty_tpl->tpl_vars['selectedOrderColumnType']->value);?>
"/>
</div>
<div class="attributes-filter-content">
	<div class="loading-attributes"  style="display:none;">
		<img src="<?php echo $_smarty_tpl->tpl_vars['blcImgDir']->value;?>
loader.gif" alt="">
		<br><?php echo smartyTranslate(array('s'=>'Loading...','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>

	</div>
	<div class="attributes-list quantity-conf" data-in_stock_class="in-stock" data-out_of_stock_class="out-of-stock" data-in_stock_text="<?php echo smartyTranslate(array('s'=>'Available','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>
" data-out_of_stock_text="<?php echo smartyTranslate(array('s'=>'Out of stock','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>
">
		<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['templateFolder']->value)."product_attributes_list.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

	</div>
	<div class="attributes-load-more">
		<a href="#" class="load-more-action"><i class="load-more-icon fa fa-refresh icone_refresh" aria-hidden="true"></i><?php echo smartyTranslate(array('s'=>'Load more products','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>
</a>
	</div>
</div>
<?php }?><?php }} ?>
