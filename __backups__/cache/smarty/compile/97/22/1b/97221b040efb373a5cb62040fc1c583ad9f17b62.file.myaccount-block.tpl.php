<?php /* Smarty version Smarty-3.1.19, created on 2019-02-13 23:00:38
         compiled from "D:\wamp\www\projects\ps\sun-device.local\modules\jmarketplace\views\templates\hook\myaccount-block.tpl" */ ?>
<?php /*%%SmartyHeaderCode:178725c649386bdc573-72387248%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '97221b040efb373a5cb62040fc1c583ad9f17b62' => 
    array (
      0 => 'D:\\wamp\\www\\projects\\ps\\sun-device.local\\modules\\jmarketplace\\views\\templates\\hook\\myaccount-block.tpl',
      1 => 1534502030,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '178725c649386bdc573-72387248',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'is_seller' => 0,
    'customer_can_be_seller' => 0,
    'link' => 0,
    'is_active_seller' => 0,
    'show_contact' => 0,
    'show_seller_favorite' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c649386e6a2a8_90345718',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c649386e6a2a8_90345718')) {function content_5c649386e6a2a8_90345718($_smarty_tpl) {?>

<?php if (($_smarty_tpl->tpl_vars['is_seller']->value==0&&$_smarty_tpl->tpl_vars['customer_can_be_seller']->value)) {?>
    <li>
        <a class="link_open_seller_account" title="<?php echo smartyTranslate(array('s'=>'Create seller account','mod'=>'jmarketplace'),$_smarty_tpl);?>
" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getModuleLink('jmarketplace','addseller',array(),true), ENT_QUOTES, 'UTF-8', true);?>
">
            <?php echo smartyTranslate(array('s'=>'Create seller account','mod'=>'jmarketplace'),$_smarty_tpl);?>

        </a>
    </li>
<?php } elseif ($_smarty_tpl->tpl_vars['is_seller']->value==1&&$_smarty_tpl->tpl_vars['is_active_seller']->value==0) {?>
    <li>
        <a class="link_open_seller_account" href="#">
            <?php echo smartyTranslate(array('s'=>'Your seller account is pending approval.','mod'=>'jmarketplace'),$_smarty_tpl);?>

        </a>
    </li>
<?php } elseif ($_smarty_tpl->tpl_vars['is_seller']->value==1&&$_smarty_tpl->tpl_vars['is_active_seller']->value==1) {?>
    <li>
        <a class="link_open_seller_account" title="<?php echo smartyTranslate(array('s'=>'Your seller account','mod'=>'jmarketplace'),$_smarty_tpl);?>
" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getModuleLink('jmarketplace','selleraccount',array(),true), ENT_QUOTES, 'UTF-8', true);?>
">
            <?php echo smartyTranslate(array('s'=>'Seller account','mod'=>'jmarketplace'),$_smarty_tpl);?>

        </a>
    </li>
<?php }?>
<?php if (($_smarty_tpl->tpl_vars['show_contact']->value)) {?>
    <li>
        <a title="<?php echo smartyTranslate(array('s'=>'Seller messages','mod'=>'jmarketplace'),$_smarty_tpl);?>
" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getModuleLink('jmarketplace','contactseller',array(),true), ENT_QUOTES, 'UTF-8', true);?>
">
            <?php echo smartyTranslate(array('s'=>'Seller messages','mod'=>'jmarketplace'),$_smarty_tpl);?>

        </a>
    </li>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['show_seller_favorite']->value) {?>
    <li>
        <a title="<?php echo smartyTranslate(array('s'=>'Favorite sellers','mod'=>'jmarketplace'),$_smarty_tpl);?>
" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getModuleLink('jmarketplace','favoriteseller',array(),true), ENT_QUOTES, 'UTF-8', true);?>
">
            <?php echo smartyTranslate(array('s'=>'Favorite sellers','mod'=>'jmarketplace'),$_smarty_tpl);?>

        </a>
    </li>
<?php }?><?php }} ?>
