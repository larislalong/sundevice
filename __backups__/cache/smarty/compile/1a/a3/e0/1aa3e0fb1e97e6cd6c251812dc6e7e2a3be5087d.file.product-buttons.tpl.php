<?php /* Smarty version Smarty-3.1.19, created on 2019-02-14 14:51:48
         compiled from "D:\wamp\www\projects\ps\sun-device.local\modules\jmarketplace\views\templates\hook\product-buttons.tpl" */ ?>
<?php /*%%SmartyHeaderCode:291905c6572746e4ac7-43577854%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1aa3e0fb1e97e6cd6c251812dc6e7e2a3be5087d' => 
    array (
      0 => 'D:\\wamp\\www\\projects\\ps\\sun-device.local\\modules\\jmarketplace\\views\\templates\\hook\\product-buttons.tpl',
      1 => 1534502030,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '291905c6572746e4ac7-43577854',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'is_product_seller' => 0,
    'seller' => 0,
    'show_seller_rating' => 0,
    'url_seller_comments' => 0,
    'averageMiddle' => 0,
    'averageTotal' => 0,
    'show_seller_profile' => 0,
    'seller_link' => 0,
    'show_contact_seller' => 0,
    'url_contact_seller' => 0,
    'show_seller_favorite' => 0,
    'url_favorite_seller' => 0,
    'url_seller_products' => 0,
    'show_manage_carriers' => 0,
    'link' => 0,
    'PS_REWRITING_SETTINGS' => 0,
    'addsellerproductcart_js' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c657274a9c1c8_01380072',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c657274a9c1c8_01380072')) {function content_5c657274a9c1c8_01380072($_smarty_tpl) {?>

<?php if ($_smarty_tpl->tpl_vars['is_product_seller']->value) {?>
    <div class="tabs">
        <h4 class="buttons_bottom_block"><?php echo smartyTranslate(array('s'=>'Information of seller','mod'=>'jmarketplace'),$_smarty_tpl);?>
</h4>
        <div class="seller_info">
            <span class="seller_name"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['seller']->value->name, ENT_QUOTES, 'UTF-8', true);?>
</span> 
            <?php if ($_smarty_tpl->tpl_vars['show_seller_rating']->value) {?>
                <div class="average_rating">
                    <a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['url_seller_comments']->value, ENT_QUOTES, 'UTF-8', true);?>
" title="<?php echo smartyTranslate(array('s'=>'View comments about','mod'=>'jmarketplace'),$_smarty_tpl);?>
 <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['seller']->value->name, ENT_QUOTES, 'UTF-8', true);?>
">
                        <?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']["i"])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']["i"]);
$_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['name'] = "i";
$_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['start'] = (int) 0;
$_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['loop'] = is_array($_loop=5) ? count($_loop) : max(0, (int) $_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['step'] = ((int) 1) == 0 ? 1 : (int) 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['loop'];
if ($_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['start'] < 0)
    $_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['start'] = max($_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['step'] > 0 ? 0 : -1, $_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['loop'] + $_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['start']);
else
    $_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['start'] = min($_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['step'] > 0 ? $_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['loop'] : $_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['loop']-1);
if ($_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['total'] = min(ceil(($_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['step'] > 0 ? $_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['loop'] - $_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['start'] : $_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['start']+1)/abs($_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['step'])), $_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['max']);
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']["i"]['total']);
?>
                            <?php if ($_smarty_tpl->tpl_vars['averageMiddle']->value<=$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']) {?>
                                <div class="star"></div>
                            <?php } else { ?>
                                <div class="star star_on"></div>
                            <?php }?>
                        <?php endfor; endif; ?>(<?php echo intval($_smarty_tpl->tpl_vars['averageTotal']->value);?>
)
                    </a>
                </div>
            <?php }?>
        </div>
        <div class="seller_links">
            <?php if ($_smarty_tpl->tpl_vars['show_seller_profile']->value) {?>
                <p class="link_seller_profile"> 
                    <a title="<?php echo smartyTranslate(array('s'=>'View seller profile','mod'=>'jmarketplace'),$_smarty_tpl);?>
" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['seller_link']->value, ENT_QUOTES, 'UTF-8', true);?>
">
                        <i class="icon-user fa fa-user"></i>  <?php echo smartyTranslate(array('s'=>'View seller profile','mod'=>'jmarketplace'),$_smarty_tpl);?>

                    </a>
                </p>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['show_contact_seller']->value) {?>
                <p class="link_contact_seller">
                    <a title="<?php echo smartyTranslate(array('s'=>'Contact seller','mod'=>'jmarketplace'),$_smarty_tpl);?>
" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['url_contact_seller']->value, ENT_QUOTES, 'UTF-8', true);?>
">
                        <i class="icon-comments fa fa-comment"></i> <?php echo smartyTranslate(array('s'=>'Contact seller','mod'=>'jmarketplace'),$_smarty_tpl);?>

                    </a>
                </p>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['show_seller_favorite']->value) {?>
                <p class="link_seller_favorite">
                    <a title="<?php echo smartyTranslate(array('s'=>'Add to favorite seller','mod'=>'jmarketplace'),$_smarty_tpl);?>
" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['url_favorite_seller']->value, ENT_QUOTES, 'UTF-8', true);?>
">
                        <i class="icon-heart fa fa-heart"></i> <?php echo smartyTranslate(array('s'=>'Add to favorite seller','mod'=>'jmarketplace'),$_smarty_tpl);?>

                    </a>
                </p>
            <?php }?>
            <p class="link_seller_products">
                <a title="<?php echo smartyTranslate(array('s'=>'View more products of this seller','mod'=>'jmarketplace'),$_smarty_tpl);?>
" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['url_seller_products']->value, ENT_QUOTES, 'UTF-8', true);?>
">
                    <i class="icon-list fa fa-list"></i> <?php echo smartyTranslate(array('s'=>'View more products of this seller','mod'=>'jmarketplace'),$_smarty_tpl);?>

                </a>
            </p>
        </div>
    </div>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['show_manage_carriers']->value==1) {?>
<script type="text/javascript">
var confirmDeleteProductsOtherSeller = "<?php echo smartyTranslate(array('s'=>'In your cart there are productos of other seller. Are you sure you want to add this product and delete the products you have in your cart?','mod'=>'jmarketplace'),$_smarty_tpl);?>
";
var confirm_controller_url = '<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getModuleLink('jmarketplace','addproductcartconfirm',array(),true), ENT_QUOTES, 'UTF-8', true);?>
';
var order_url = '<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getPageLink('order'), ENT_QUOTES, 'UTF-8', true);?>
';
var PS_REWRITING_SETTINGS = "<?php echo intval($_smarty_tpl->tpl_vars['PS_REWRITING_SETTINGS']->value);?>
";
</script>
<script type="text/javascript" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['addsellerproductcart_js']->value, ENT_QUOTES, 'UTF-8', true);?>
"></script>
<?php } else { ?>
<script type="text/javascript">
var PS_REWRITING_SETTINGS = "<?php echo intval($_smarty_tpl->tpl_vars['PS_REWRITING_SETTINGS']->value);?>
";
</script>
<?php }?><?php }} ?>
