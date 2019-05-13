<?php /* Smarty version Smarty-3.1.19, created on 2019-01-29 11:26:57
         compiled from "/home/sundevice/public_html/modules/jmarketplace/views/templates/hook/product-list.tpl" */ ?>
<?php /*%%SmartyHeaderCode:8345646075c502a71d168f4-93119704%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b98686f5cc7c13d3fe8379e0750e7e0df63350c0' => 
    array (
      0 => '/home/sundevice/public_html/modules/jmarketplace/views/templates/hook/product-list.tpl',
      1 => 1534502030,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8345646075c502a71d168f4-93119704',
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
  'unifunc' => 'content_5c502a71d1e144_62271436',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c502a71d1e144_62271436')) {function content_5c502a71d1e144_62271436($_smarty_tpl) {?>

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
