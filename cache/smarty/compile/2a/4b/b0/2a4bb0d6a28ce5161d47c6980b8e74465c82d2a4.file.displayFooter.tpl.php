<?php /* Smarty version Smarty-3.1.19, created on 2019-04-29 16:36:45
         compiled from "/home/sundevice/public_html/modules/cscustomize/views/templates/hook/displayFooter.tpl" */ ?>
<?php /*%%SmartyHeaderCode:16637675495cc70bfd95ce35-89109899%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2a4bb0d6a28ce5161d47c6980b8e74465c82d2a4' => 
    array (
      0 => '/home/sundevice/public_html/modules/cscustomize/views/templates/hook/displayFooter.tpl',
      1 => 1534502024,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16637675495cc70bfd95ce35-89109899',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'listblocs' => 0,
    'bloc' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cc70bfd966154_41853825',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cc70bfd966154_41853825')) {function content_5cc70bfd966154_41853825($_smarty_tpl) {?><?php  $_smarty_tpl->tpl_vars['bloc'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['bloc']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['listblocs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['bloc']->key => $_smarty_tpl->tpl_vars['bloc']->value) {
$_smarty_tpl->tpl_vars['bloc']->_loop = true;
?>
<section id="<?php if ($_smarty_tpl->tpl_vars['bloc']->value['id_block']) {?><?php echo $_smarty_tpl->tpl_vars['bloc']->value['id_block'];?>
<?php } else { ?>block-elt-id-<?php echo $_smarty_tpl->tpl_vars['bloc']->value['id_cseditor'];?>
<?php }?>" class="footer-block col-xs-12 col-sm-4 clearfix">
    <div class="wrap-section<?php if ($_smarty_tpl->tpl_vars['bloc']->value['class_block']) {?> <?php echo $_smarty_tpl->tpl_vars['bloc']->value['class_block'];?>
<?php }?>">
        <h4 <?php if (!$_smarty_tpl->tpl_vars['bloc']->value['displaytitle']) {?>class="displaynone"<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['bloc']->value['titleblock'], ENT_QUOTES, 'UTF-8', true);?>
</h4>
        <div class="content-text toggle-footer"><?php echo $_smarty_tpl->tpl_vars['bloc']->value['editortext'];?>
</div>
    </div>
</section>
<?php } ?><?php }} ?>
