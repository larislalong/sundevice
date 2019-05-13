<?php /* Smarty version Smarty-3.1.19, created on 2019-05-03 12:24:50
         compiled from "/home/sundevice/public_html/modules/paypalwithfee/views/templates/admin/module_info.tpl" */ ?>
<?php /*%%SmartyHeaderCode:11189184255ccc16f27f4fb9-19419614%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '97e1f3e605beabdc8caee6bb9412e29dc06fc279' => 
    array (
      0 => '/home/sundevice/public_html/modules/paypalwithfee/views/templates/admin/module_info.tpl',
      1 => 1554927945,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11189184255ccc16f27f4fb9-19419614',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'module_name' => 0,
    'module_path' => 0,
    'module_lang' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5ccc16f2817ca3_52508495',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5ccc16f2817ca3_52508495')) {function content_5ccc16f2817ca3_52508495($_smarty_tpl) {?>
 
<div class='panel' id='modulesext'>
    <div class='panel-heading'><?php echo smartyTranslate(array('s'=>'About of','mod'=>'paypalwithfee'),$_smarty_tpl);?>
 <?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['module_name']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</div>
    <ul style='list-style: none;'>
        <li style='display:inline-block;float:left;margin-right:10px'><a target='_blank' title="<?php echo smartyTranslate(array('s'=>'Manual','mod'=>'paypalwithfee'),$_smarty_tpl);?>
" href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['module_path']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
readme/<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['module_name']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
_<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['module_lang']->value, ENT_QUOTES, 'UTF-8', true);?>
.pdf" style='display:block;position:relative;width:85px;height:98px;background:url(<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['module_path']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
views/img/moduleinfo/help.png)'><span style='position:absolute;top:65px;color: #fff;text-transform: uppercase;font-weight: bold;text-align: center;font-size: 11px;display: block;width: 100%;'><?php echo smartyTranslate(array('s'=>'Help','mod'=>'paypalwithfee'),$_smarty_tpl);?>
</span></a></li>
        <li style='display:inline-block;float:left;margin-right:7px'><a target='_blank' title="<?php echo smartyTranslate(array('s'=>'Support contact','mod'=>'paypalwithfee'),$_smarty_tpl);?>
" href='https://addons.prestashop.com/<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['module_lang']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
/Write-to-developper?id_product=11009' style='display:block;position:relative;width:85px;height:98px;background:url(<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['module_path']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
views/img/moduleinfo/support.png)'><span style='position:absolute;top:65px;color: #fff;text-transform: uppercase;font-weight: bold;text-align: center;font-size: 11px;display: block;width: 100%;'><?php echo smartyTranslate(array('s'=>'Support','mod'=>'paypalwithfee'),$_smarty_tpl);?>
</span></a></li>
        <li style='display:inline-block;float:left;margin-right:10px'><a target='_blank' title="<?php echo smartyTranslate(array('s'=>'Rate this module','mod'=>'paypalwithfee'),$_smarty_tpl);?>
" href='http://addons.prestashop.com/<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['module_lang']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
/ratings.php' style='display:block;position:relative;width:85px;height:98px;background:url(<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['module_path']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
views/img/moduleinfo/opinion.png)'><span style='position:absolute;top:65px;color: #fff;text-transform: uppercase;font-weight: bold;text-align: center;font-size: 11px;display: block;width: 100%;'><?php echo smartyTranslate(array('s'=>'Vote','mod'=>'paypalwithfee'),$_smarty_tpl);?>
</span></a></li>
        <li style='display:inline-block;float:left;margin-right:10px'><a target='_blank' title='4webs' href='http://addons.prestashop.com/es/14_4webs' style='display:block;position:relative;width:95px;height:98px;background:url(<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['module_path']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
views/img/moduleinfo/4webs.png)'></a></li>
        <li style='display:inline-block;'><a target='_blank' title="4webs Partner" href="http://addons.prestashop.com/es/14_4webs" style="display:block;position:relative;width:98px;height:98px;background:url(<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['module_path']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
views/img/moduleinfo/icon_platinum.png)"></a></li>
    </ul>
</div><?php }} ?>
