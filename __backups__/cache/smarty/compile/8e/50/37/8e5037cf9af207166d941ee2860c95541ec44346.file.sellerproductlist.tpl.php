<?php /* Smarty version Smarty-3.1.19, created on 2019-01-30 13:04:16
         compiled from "/home/sundevice/public_html/modules/jmarketplace/views/templates/front/sellerproductlist.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1225109995c5192c08d6d86-75166722%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8e5037cf9af207166d941ee2860c95541ec44346' => 
    array (
      0 => '/home/sundevice/public_html/modules/jmarketplace/views/templates/front/sellerproductlist.tpl',
      1 => 1534502030,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1225109995c5192c08d6d86-75166722',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'seller_link' => 0,
    'seller' => 0,
    'navigationPipe' => 0,
    'products' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c5192c08fb132_90822533',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c5192c08fb132_90822533')) {function content_5c5192c08fb132_90822533($_smarty_tpl) {?>

<?php $_smarty_tpl->_capture_stack[0][] = array('path', null, null); ob_start(); ?>
    <a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['seller_link']->value, ENT_QUOTES, 'UTF-8', true);?>
">
        <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['seller']->value->name, ENT_QUOTES, 'UTF-8', true);?>

    </a>
    <span class="navigation-pipe">
        <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['navigationPipe']->value, ENT_QUOTES, 'UTF-8', true);?>

    </span>
    <span class="navigation_page">
        <?php echo smartyTranslate(array('s'=>'Products','mod'=>'jmarketplace'),$_smarty_tpl);?>

    </span>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<h1 class="page-heading">
    <?php echo smartyTranslate(array('s'=>'Products of','mod'=>'jmarketplace'),$_smarty_tpl);?>
 <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['seller']->value->name, ENT_QUOTES, 'UTF-8', true);?>

</h1>

<?php if (isset($_smarty_tpl->tpl_vars['products']->value)&&$_smarty_tpl->tpl_vars['products']->value) {?>
    <div class="content_sortPagiBar clearfix">
        <?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./product-sort.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        <?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./nbr-product-page.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        <div class="top-pagination-content clearfix">
            <?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./product-compare.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

            <?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./pagination.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        </div>
    </div>
    <?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./product-list.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('class'=>'tab-pane','id'=>'jmarketplace'), 0);?>

    <div class="content_sortPagiBar">
        <div class="bottom-pagination-content clearfix">
            <?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./product-compare.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('paginationId'=>'bottom'), 0);?>

            <?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./pagination.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('paginationId'=>'bottom'), 0);?>

        </div>
    </div>
<?php } else { ?>
    <ul class="tab-pane">
        <li class="alert alert-info">
            <?php echo smartyTranslate(array('s'=>'This seller have not products.','mod'=>'jmarketplace'),$_smarty_tpl);?>

        </li>
    </ul>
<?php }?><?php }} ?>
