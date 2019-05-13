<?php /* Smarty version Smarty-3.1.19, created on 2019-02-22 14:54:32
         compiled from "D:\wamp\www\projects\ps\sun-device.local\modules\cscustomize\views\templates\hook\cscustomize.tpl" */ ?>
<?php /*%%SmartyHeaderCode:54145c6fff18ee63a6-63660511%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '013d7c416c071e5f9ba9cf72a128d17ec7bfbf8b' => 
    array (
      0 => 'D:\\wamp\\www\\projects\\ps\\sun-device.local\\modules\\cscustomize\\views\\templates\\hook\\cscustomize.tpl',
      1 => 1534502024,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '54145c6fff18ee63a6-63660511',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'listblocs' => 0,
    'bloc' => 0,
    'numbercol' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c6fff19027922_14595238',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c6fff19027922_14595238')) {function content_5c6fff19027922_14595238($_smarty_tpl) {?><?php  $_smarty_tpl->tpl_vars['bloc'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['bloc']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['listblocs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['blocs']['index']=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['bloc']->key => $_smarty_tpl->tpl_vars['bloc']->value) {
$_smarty_tpl->tpl_vars['bloc']->_loop = true;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['blocs']['index']++;
?>
    <div id="<?php echo $_smarty_tpl->tpl_vars['bloc']->value['id_block'];?>
" class="<?php echo $_smarty_tpl->tpl_vars['bloc']->value['class_block'];?>
 block-elt block-col-<?php echo $_smarty_tpl->tpl_vars['numbercol']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['bloc']->value['hook'];?>
 block-elt<?php echo $_smarty_tpl->getVariable('smarty')->value['foreach']['blocs']['index'];?>
 clearfix">
        <?php if ($_smarty_tpl->tpl_vars['bloc']->value['displaytitle']&&$_smarty_tpl->tpl_vars['bloc']->value['titleblock']!='') {?><h4><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['bloc']->value['titleblock'], ENT_QUOTES, 'UTF-8', true);?>
</h4><?php }?>
        <div class="content-text <?php if ($_smarty_tpl->tpl_vars['bloc']->value['hook']=='displayFooter') {?>toggle-footer<?php }?>"><?php echo $_smarty_tpl->tpl_vars['bloc']->value['editortext'];?>
</div>
    </div>
<?php } ?><?php }} ?>
