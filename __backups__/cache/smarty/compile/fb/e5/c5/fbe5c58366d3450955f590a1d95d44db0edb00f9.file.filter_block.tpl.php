<?php /* Smarty version Smarty-3.1.19, created on 2019-02-16 05:05:29
         compiled from "D:\wamp\www\projects\ps\sun-device.local\themes\pos_ruby5\modules\blocklayeredcustom\views\templates\hook\filter_block.tpl" */ ?>
<?php /*%%SmartyHeaderCode:28395c678c095b7c94-28173563%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fbe5c58366d3450955f590a1d95d44db0edb00f9' => 
    array (
      0 => 'D:\\wamp\\www\\projects\\ps\\sun-device.local\\themes\\pos_ruby5\\modules\\blocklayeredcustom\\views\\templates\\hook\\filter_block.tpl',
      1 => 1550289918,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '28395c678c095b7c94-28173563',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'blfFrontAjaxUrl' => 0,
    'BLOCK_SEPARATOR' => 0,
    'FILTER_VALUES_SEPARATOR' => 0,
    'ajaxLoad' => 0,
    'templateFolder' => 0,
    'blcImgDir' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c678c09652037_19375595',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c678c09652037_19375595')) {function content_5c678c09652037_19375595($_smarty_tpl) {?>
<script type="text/javascript">
	var blfFrontAjaxUrl = "<?php echo $_smarty_tpl->tpl_vars['blfFrontAjaxUrl']->value;?>
";
	var BLOCK_SEPARATOR = "<?php echo $_smarty_tpl->tpl_vars['BLOCK_SEPARATOR']->value;?>
";
	var FILTER_VALUES_SEPARATOR = "<?php echo $_smarty_tpl->tpl_vars['FILTER_VALUES_SEPARATOR']->value;?>
";
</script>


<div class="lined-title">
	<h2><?php echo smartyTranslate(array('s'=>"Top des ventes"),$_smarty_tpl);?>
</h2>
</div>

<?php if (!$_smarty_tpl->tpl_vars['ajaxLoad']->value) {?>
<div class="blc-filter-block-wrapper">
    <div class="blc-filter-block-container container">
        <div class="row blc-filter-block">
<?php }?>
        	<div class="col-lg-3 filter-left">
				<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['templateFolder']->value)."filter_left.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

			</div>  
        	<div class="col-lg-9 filter-center">
        		<div class="loading-product"  style="display:none;">
        			<img src="<?php echo $_smarty_tpl->tpl_vars['blcImgDir']->value;?>
loader.gif" alt="">
        			<br><?php echo smartyTranslate(array('s'=>'Loading...','mod'=>'blocklayeredcustom'),$_smarty_tpl);?>

        		</div>
        		<div class="filter-center-content">
					<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['templateFolder']->value)."filter_center.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

				</div>
        	</div>
<?php if (!$_smarty_tpl->tpl_vars['ajaxLoad']->value) {?>
        </div>
    </div>
</div>
<?php }?><?php }} ?>
