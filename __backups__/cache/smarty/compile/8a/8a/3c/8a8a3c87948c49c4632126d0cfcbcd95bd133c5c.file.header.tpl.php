<?php /* Smarty version Smarty-3.1.19, created on 2019-01-29 11:26:57
         compiled from "/home/sundevice/public_html/modules/menupro/views/templates/hook/header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:12463963775c502a718418e0-70016911%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8a8a3c87948c49c4632126d0cfcbcd95bd133c5c' => 
    array (
      0 => '/home/sundevice/public_html/modules/menupro/views/templates/hook/header.tpl',
      1 => 1511549304,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12463963775c502a718418e0-70016911',
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
  'unifunc' => 'content_5c502a71890911_18851385',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c502a71890911_18851385')) {function content_5c502a71890911_18851385($_smarty_tpl) {?>
<script type="text/javascript">
	var ps_version = "<?php echo strtr($_smarty_tpl->tpl_vars['ps_version']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
";
</script>
<?php if (($_smarty_tpl->tpl_vars['ps_version']->value>='1.6')&&($_smarty_tpl->tpl_vars['ps_version']->value<'1.7')) {?>
	<?php ob_start();?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['addJsDef']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php $_tmp1=ob_get_clean();?><?php echo $_smarty_tpl->getSubTemplate ($_tmp1, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<?php }?><?php }} ?>
