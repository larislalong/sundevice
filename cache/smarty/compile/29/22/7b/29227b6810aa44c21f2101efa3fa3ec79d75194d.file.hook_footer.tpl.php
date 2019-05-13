<?php /* Smarty version Smarty-3.1.19, created on 2019-05-07 21:57:56
         compiled from "/home/sundevice/preprod/modules/klavyio/hook_footer.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1822639115cd1e34472fb55-86098706%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '29227b6810aa44c21f2101efa3fa3ec79d75194d' => 
    array (
      0 => '/home/sundevice/preprod/modules/klavyio/hook_footer.tpl',
      1 => 1555696287,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1822639115cd1e34472fb55-86098706',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cd1e344732982_94415079',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cd1e344732982_94415079')) {function content_5cd1e344732982_94415079($_smarty_tpl) {?>
<script>
  var _learnq = _learnq || [];
  _learnq.push(['account', '{$API_KEY}']);
  (function () {
    var b = document.createElement('script'); b.type = 'text/javascript'; b.async = true;
    b.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'a.klaviyo.com/media/js/analytics/analytics.js';
    var a = document.getElementsByTagName('script')[0]; a.parentNode.insertBefore(b, a);
  })();
</script>
<?php }} ?>
