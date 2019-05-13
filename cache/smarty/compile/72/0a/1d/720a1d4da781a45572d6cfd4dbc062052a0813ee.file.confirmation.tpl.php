<?php /* Smarty version Smarty-3.1.19, created on 2019-04-29 16:49:40
         compiled from "/home/sundevice/public_html/modules/lydiaapi/views/templates/hook/confirmation.tpl" */ ?>
<?php /*%%SmartyHeaderCode:5363916465cc70f04642367-28625853%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '720a1d4da781a45572d6cfd4dbc062052a0813ee' => 
    array (
      0 => '/home/sundevice/public_html/modules/lydiaapi/views/templates/hook/confirmation.tpl',
      1 => 1536678240,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5363916465cc70f04642367-28625853',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'status' => 0,
    'shop_name' => 0,
    'total' => 0,
    'reference' => 0,
    'link' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cc70f0465e295_80505268',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cc70f0465e295_80505268')) {function content_5cc70f0465e295_80505268($_smarty_tpl) {?>

<?php if ((isset($_smarty_tpl->tpl_vars['status']->value)==true)&&($_smarty_tpl->tpl_vars['status']->value=='ok')) {?>
<h3><?php echo smartyTranslate(array('s'=>'Your order on %s is complete.','sprintf'=>$_smarty_tpl->tpl_vars['shop_name']->value,'mod'=>'lydiaapi'),$_smarty_tpl);?>
</h3>
<p>
	<br />- <?php echo smartyTranslate(array('s'=>'Amount','mod'=>'lydiaapi'),$_smarty_tpl);?>
 : <span class="price"><strong><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['total']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</strong></span>
	<br />- <?php echo smartyTranslate(array('s'=>'Reference','mod'=>'lydiaapi'),$_smarty_tpl);?>
 : <span class="reference"><strong><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['reference']->value, ENT_QUOTES, 'UTF-8', true);?>
</strong></span>
	<br /><br /><?php echo smartyTranslate(array('s'=>'An email has been sent with this information.','mod'=>'lydiaapi'),$_smarty_tpl);?>

	<br /><br /><?php echo smartyTranslate(array('s'=>'If you have questions, comments or concerns, please contact our','mod'=>'lydiaapi'),$_smarty_tpl);?>
 <a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getPageLink('contact',true), ENT_QUOTES, 'UTF-8', true);?>
"><?php echo smartyTranslate(array('s'=>'expert customer support team.','mod'=>'lydiaapi'),$_smarty_tpl);?>
</a>
</p>
<?php } else { ?>
<h3><?php echo smartyTranslate(array('s'=>'Your order on %s has not been accepted.','sprintf'=>$_smarty_tpl->tpl_vars['shop_name']->value,'mod'=>'lydiaapi'),$_smarty_tpl);?>
</h3>
<p>
	<br />- <?php echo smartyTranslate(array('s'=>'Reference','mod'=>'lydiaapi'),$_smarty_tpl);?>
 <span class="reference"> <strong><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['reference']->value, ENT_QUOTES, 'UTF-8', true);?>
</strong></span>
	<br /><br /><?php echo smartyTranslate(array('s'=>'Please, try to order again.','mod'=>'lydiaapi'),$_smarty_tpl);?>

	<br /><br /><?php echo smartyTranslate(array('s'=>'If you have questions, comments or concerns, please contact our','mod'=>'lydiaapi'),$_smarty_tpl);?>
 <a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getPageLink('contact',true), ENT_QUOTES, 'UTF-8', true);?>
"><?php echo smartyTranslate(array('s'=>'expert customer support team.','mod'=>'lydiaapi'),$_smarty_tpl);?>
</a>
</p>
<?php }?>
<hr /><?php }} ?>
