<?php /* Smarty version Smarty-3.1.19, created on 2019-05-07 21:57:56
         compiled from "/home/sundevice/preprod/modules/notarobot/views/templates/hook/header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:18014702675cd1e3443adf86-92809831%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '40697df25dd71723ecc6de786b9aff8c71360d94' => 
    array (
      0 => '/home/sundevice/preprod/modules/notarobot/views/templates/hook/header.tpl',
      1 => 1555942312,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18014702675cd1e3443adf86-92809831',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    're_align' => 0,
    'custom_css' => 0,
    'nar_lang' => 0,
    'nar_key' => 0,
    're_theme' => 0,
    're_size' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cd1e3443bb571_41677840',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cd1e3443bb571_41677840')) {function content_5cd1e3443bb571_41677840($_smarty_tpl) {?>

<style type="text/css">
    <?php if ($_smarty_tpl->tpl_vars['re_align']->value=='right') {?>
        #nar-gre {
            overflow: hidden;
        }
        #nar-gre > div {
            float: right;
        }
    <?php }?>
    <?php if ($_smarty_tpl->tpl_vars['custom_css']->value) {?>
        <?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['custom_css']->value);?>

    <?php }?>
</style>

<script src='https://www.google.com/recaptcha/api.js?onload=nar_onLoad&render=explicit<?php if ($_smarty_tpl->tpl_vars['nar_lang']->value) {?>&hl=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['nar_lang']->value, ENT_QUOTES, 'UTF-8', true);?>
<?php }?>' async defer></script>
<script type="text/javascript">
    var nar_selector = "#contact [name=submitMessage]";
    var $nar_elem = null;

    function nar_findReElement() {
        if (nar_selector && !$nar_elem) {
            var $nar_elem = $(nar_selector);

            if (!$nar_elem.length) {
                return null;
            }
        }

        return $nar_elem;
    }
</script>

<script type="text/javascript">
    var nar_recaptcha = '<div id="nar-gre" class="g-recaptcha" data-sitekey="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['nar_key']->value, ENT_QUOTES, 'UTF-8', true);?>
" data-theme="<?php if ($_smarty_tpl->tpl_vars['re_theme']->value=='dark') {?>dark<?php } else { ?>light<?php }?>" data-size="<?php if ($_smarty_tpl->tpl_vars['re_size']->value=='compact') {?>compact<?php } else { ?>normal<?php }?>"></div>';

    
        var nar_onLoad = function () {
            var $nar_elem = nar_findReElement();

            if ($nar_elem !== null) {
                $(nar_recaptcha).insertBefore($nar_elem);
                grecaptcha.render('nar-gre');
            }
        };
    
</script><?php }} ?>
