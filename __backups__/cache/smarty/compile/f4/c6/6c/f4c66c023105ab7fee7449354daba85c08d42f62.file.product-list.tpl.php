<?php /* Smarty version Smarty-3.1.19, created on 2019-02-22 14:54:35
         compiled from "D:\wamp\www\projects\ps\sun-device.local\modules\jmarketplace\views\templates\hook\product-list.tpl" */ ?>
<?php /*%%SmartyHeaderCode:276335c6fff1bdad758-91487388%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f4c66c023105ab7fee7449354daba85c08d42f62' => 
    array (
      0 => 'D:\\wamp\\www\\projects\\ps\\sun-device.local\\modules\\jmarketplace\\views\\templates\\hook\\product-list.tpl',
      1 => 1534502030,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '276335c6fff1bdad758-91487388',
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
  'unifunc' => 'content_5c6fff1bdfa248_31402855',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c6fff1bdfa248_31402855')) {function content_5c6fff1bdfa248_31402855($_smarty_tpl) {?>

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
