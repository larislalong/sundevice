<?php /* Smarty version Smarty-3.1.19, created on 2019-02-22 14:54:32
         compiled from "D:\wamp\www\projects\ps\sun-device.local\modules\menupro\views\templates\hook\header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:93205c6fff18cbd888-43010281%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b2fc42be0533b338eaa1c90edc31a76adc001b70' => 
    array (
      0 => 'D:\\wamp\\www\\projects\\ps\\sun-device.local\\modules\\menupro\\views\\templates\\hook\\header.tpl',
      1 => 1511549304,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '93205c6fff18cbd888-43010281',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'ps_version' => 0,
    'addJsDef' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c6fff18cfe682_76872685',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c6fff18cfe682_76872685')) {function content_5c6fff18cfe682_76872685($_smarty_tpl) {?>
<script type="text/javascript">
	var ps_version = "<?php echo strtr($_smarty_tpl->tpl_vars['ps_version']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
";
</script>
<?php if (($_smarty_tpl->tpl_vars['ps_version']->value>='1.6')&&($_smarty_tpl->tpl_vars['ps_version']->value<'1.7')) {?>
	<?php ob_start();?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['addJsDef']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php $_tmp1=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ($_tmp1, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<?php }?><?php }} ?>
