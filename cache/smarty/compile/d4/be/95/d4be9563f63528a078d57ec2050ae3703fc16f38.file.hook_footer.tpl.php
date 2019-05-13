<?php /* Smarty version Smarty-3.1.19, created on 2019-04-29 16:36:45
         compiled from "/home/sundevice/public_html/modules/klavyio/hook_footer.tpl" */ ?>
<?php /*%%SmartyHeaderCode:20270667005cc70bfd96b189-59971252%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd4be9563f63528a078d57ec2050ae3703fc16f38' => 
    array (
      0 => '/home/sundevice/public_html/modules/klavyio/hook_footer.tpl',
      1 => 1555696287,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20270667005cc70bfd96b189-59971252',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cc70bfd96bc30_27649557',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cc70bfd96bc30_27649557')) {function content_5cc70bfd96bc30_27649557($_smarty_tpl) {?>
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
