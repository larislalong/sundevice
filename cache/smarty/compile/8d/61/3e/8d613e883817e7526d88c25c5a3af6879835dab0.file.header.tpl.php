<?php /* Smarty version Smarty-3.1.19, created on 2019-05-07 21:57:56
         compiled from "/home/sundevice/preprod/modules/menupro/views/templates/hook/header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:11981689845cd1e34437a174-29026695%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8d613e883817e7526d88c25c5a3af6879835dab0' => 
    array (
      0 => '/home/sundevice/preprod/modules/menupro/views/templates/hook/header.tpl',
      1 => 1511549304,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11981689845cd1e34437a174-29026695',
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
  'unifunc' => 'content_5cd1e344387138_19692715',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cd1e344387138_19692715')) {function content_5cd1e344387138_19692715($_smarty_tpl) {?>
<script type="text/javascript">
	var ps_version = "<?php echo strtr($_smarty_tpl->tpl_vars['ps_version']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
";
</script>
<?php if (($_smarty_tpl->tpl_vars['ps_version']->value>='1.6')&&($_smarty_tpl->tpl_vars['ps_version']->value<'1.7')) {?>
	<?php ob_start();?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['addJsDef']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php $_tmp1=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ($_tmp1, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<?php }?><?php }} ?>
