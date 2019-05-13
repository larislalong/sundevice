<?php /* Smarty version Smarty-3.1.19, created on 2019-05-07 21:57:56
         compiled from "/home/sundevice/preprod/modules/blocklayeredcustom/views/templates/hook/filter_block.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1755471465cd1e3448364c6-99093444%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '975c329d1a04d4317be0f0a7dee05956fc9dc126' => 
    array (
      0 => '/home/sundevice/preprod/modules/blocklayeredcustom/views/templates/hook/filter_block.tpl',
      1 => 1525408906,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1755471465cd1e3448364c6-99093444',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'ajaxLoad' => 0,
    'id_category' => 0,
    'ismobile' => 0,
    'templateFolder' => 0,
    'blcImgDir' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cd1e344847ba0_61825637',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cd1e344847ba0_61825637')) {function content_5cd1e344847ba0_61825637($_smarty_tpl) {?>
<?php if (!$_smarty_tpl->tpl_vars['ajaxLoad']->value) {?>
<div class="blc-filter-block-wrapper">
    <div class="blc-filter-block-container container">
        <div class="row blc-filter-block">
<?php }?>		
			<input type="hidden" class="input_category" value="<?php if (isset($_smarty_tpl->tpl_vars['id_category']->value)) {?><?php echo intval($_smarty_tpl->tpl_vars['id_category']->value);?>
<?php }?>" />
            <?php if ($_smarty_tpl->tpl_vars['ismobile']->value) {?>
            <div class="col-md-7 col-sm-7 col-xs-7 filter-left">
                <nav class="navbar navbar-inverse">
                    <div class="navbar-header">
                      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                      </button>
                      <a class="navbar-brand" href="#"><?php echo smartyTranslate(array('s'=>'Filters','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>
</a>
                    </div>
                    <div class="collapse navbar-collapse" id="myNavbar">
                      <?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['templateFolder']->value)."filter_left.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

                    </div>
                </nav>
            </div>
            <div class="col-md-5 col-sm-5 col-xs-5 filter-left filter-featured">
                <button class="btn btn-primary btn-filter dropdown-toggle" type="button" data-toggle="dropdown">
                    <span class="selected-name"><?php echo smartyTranslate(array('s'=>'Featured','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>
</span>
                    <i class="fa fa-angle-down"></i>
                </button>
                <div class="dropdown-menu">
                    <?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['templateFolder']->value)."attribute_groups_list.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

					<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['templateFolder']->value)."others_sort_block.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

                </div>
            </div>
            <div class="col-lg-12  col-sm-12 col-xs-12 filter-center">
        		<div class="loading-product"  style="display:none;">
        			<img src="<?php echo $_smarty_tpl->tpl_vars['blcImgDir']->value;?>
loader.gif" alt="">
        			<br><?php echo smartyTranslate(array('s'=>'Loading...','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>

        		</div>
        		<div class="filter-center-content">
					<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['templateFolder']->value)."filter_center.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

				</div>
        	</div>
            <?php } else { ?>
        	<div class="col-lg-3 col-sm-12 col-xs-12 filter-left">
				<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['templateFolder']->value)."filter_left.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

			</div>  
        	<div class="col-lg-9  col-sm-12 col-xs-12 filter-center">
        		<div class="loading-product"  style="display:none;">
        			<img src="<?php echo $_smarty_tpl->tpl_vars['blcImgDir']->value;?>
loader.gif" alt="">
        			<br><?php echo smartyTranslate(array('s'=>'Loading...','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>

        		</div>
        		<div class="filter-center-content">
					<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['templateFolder']->value)."filter_center.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

				</div>
        	</div>
            <?php }?>
<?php if (!$_smarty_tpl->tpl_vars['ajaxLoad']->value) {?>
        </div>
    </div>
</div>
<?php }?><?php }} ?>
