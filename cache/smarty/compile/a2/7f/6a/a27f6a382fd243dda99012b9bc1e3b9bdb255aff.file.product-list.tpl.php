<?php /* Smarty version Smarty-3.1.19, created on 2019-05-07 21:57:56
         compiled from "/home/sundevice/preprod/modules/jmarketplace/views/templates/hook/product-list.tpl" */ ?>
<?php /*%%SmartyHeaderCode:5179535985cd1e344617190-59004603%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a27f6a382fd243dda99012b9bc1e3b9bdb255aff' => 
    array (
      0 => '/home/sundevice/preprod/modules/jmarketplace/views/templates/hook/product-list.tpl',
      1 => 1534502030,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5179535985cd1e344617190-59004603',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'seller' => 0,
    'seller_link' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cd1e34461e486_10011580',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cd1e34461e486_10011580')) {function content_5cd1e34461e486_10011580($_smarty_tpl) {?>

<?php if (isset($_smarty_tpl->tpl_vars['seller']->value)&&$_smarty_tpl->tpl_vars['seller']->value) {?>
    <p class="seller_name">
        <a title="<?php echo smartyTranslate(array('s'=>'View seller profile','mod'=>'jmarketplace'),$_smarty_tpl);?>
" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['seller_link']->value, ENT_QUOTES, 'UTF-8', true);?>
">
            <i class="icon-user fa fa-user"></i> 
            <?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['seller']->value->name, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>

        </a>
    </p>
<?php }?><?php }} ?>
