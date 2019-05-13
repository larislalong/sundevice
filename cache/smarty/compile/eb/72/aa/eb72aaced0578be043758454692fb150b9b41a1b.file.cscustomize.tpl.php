<?php /* Smarty version Smarty-3.1.19, created on 2019-05-07 21:57:56
         compiled from "/home/sundevice/preprod/modules/cscustomize/views/templates/hook/cscustomize.tpl" */ ?>
<?php /*%%SmartyHeaderCode:11155298165cd1e34463e3b0-01578797%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'eb72aaced0578be043758454692fb150b9b41a1b' => 
    array (
      0 => '/home/sundevice/preprod/modules/cscustomize/views/templates/hook/cscustomize.tpl',
      1 => 1534502024,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11155298165cd1e34463e3b0-01578797',
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
  'unifunc' => 'content_5cd1e344649e18_69740614',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cd1e344649e18_69740614')) {function content_5cd1e344649e18_69740614($_smarty_tpl) {?><?php  $_smarty_tpl->tpl_vars['bloc'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['bloc']->_loop = false;
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
