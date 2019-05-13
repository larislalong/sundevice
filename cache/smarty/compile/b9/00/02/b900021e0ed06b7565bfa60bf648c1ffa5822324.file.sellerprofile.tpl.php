<?php /* Smarty version Smarty-3.1.19, created on 2019-04-30 02:59:24
         compiled from "/home/sundevice/public_html/modules/jmarketplace/views/templates/front/sellerprofile.tpl" */ ?>
<?php /*%%SmartyHeaderCode:16037898205cc79deca77363-12938619%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b900021e0ed06b7565bfa60bf648c1ffa5822324' => 
    array (
      0 => '/home/sundevice/public_html/modules/jmarketplace/views/templates/front/sellerprofile.tpl',
      1 => 1534502030,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16037898205cc79deca77363-12938619',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'seller_me' => 0,
    'link' => 0,
    'url_sellers' => 0,
    'navigationPipe' => 0,
    'seller' => 0,
    'show_logo' => 0,
    'photo' => 0,
    'show_shop_name' => 0,
    'show_cif' => 0,
    'show_language' => 0,
    'seller_language' => 0,
    'show_seller_rating' => 0,
    'url_seller_comments' => 0,
    'averageMiddle' => 0,
    'averageTotal' => 0,
    'show_seller_favorite' => 0,
    'followers' => 0,
    'show_email' => 0,
    'show_phone' => 0,
    'show_fax' => 0,
    'show_address' => 0,
    'show_country' => 0,
    'show_state' => 0,
    'show_postcode' => 0,
    'show_city' => 0,
    'show_description' => 0,
    'seller_products_link' => 0,
    'show_edit_seller_account' => 0,
    'show_contact' => 0,
    'url_contact_seller' => 0,
    'url_favorite_seller' => 0,
    'show_new_products' => 0,
    'products' => 0,
    'base_dir' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cc79decaf5eb1_40949640',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cc79decaf5eb1_40949640')) {function content_5cc79decaf5eb1_40949640($_smarty_tpl) {?>

<?php $_smarty_tpl->_capture_stack[0][] = array('path', null, null); ob_start(); ?>
    <?php if ($_smarty_tpl->tpl_vars['seller_me']->value) {?>
        <a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getModuleLink('jmarketplace','selleraccount',array(),true), ENT_QUOTES, 'UTF-8', true);?>
">
            <?php echo smartyTranslate(array('s'=>'Your seller account','mod'=>'jmarketplace'),$_smarty_tpl);?>

        </a>
    <?php } else { ?>
        <a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['url_sellers']->value, ENT_QUOTES, 'UTF-8', true);?>
">
            <?php echo smartyTranslate(array('s'=>'Sellers','mod'=>'jmarketplace'),$_smarty_tpl);?>

        </a>
    <?php }?>
    <span class="navigation-pipe">
        <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['navigationPipe']->value, ENT_QUOTES, 'UTF-8', true);?>

    </span>
    <span class="navigation_page">
        <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['seller']->value->name, ENT_QUOTES, 'UTF-8', true);?>
 
    </span>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<div class="box">
    <h1 class="page-subheading">
        <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['seller']->value->name, ENT_QUOTES, 'UTF-8', true);?>

    </h1> 
    <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0][0]->smartyHook(array('h'=>'displayMarketplaceHeaderProfile'),$_smarty_tpl);?>

    <div class="row">
        <?php if ($_smarty_tpl->tpl_vars['show_logo']->value) {?>
            <div class="col-lg-3" style="margin-bottom: 15px;">
                <img class="img-responsive" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['photo']->value, ENT_QUOTES, 'UTF-8', true);?>
" width="240" height="auto" />
            </div>
        <?php }?>
        <div class="<?php if ($_smarty_tpl->tpl_vars['show_logo']->value) {?>col-lg-9<?php } else { ?>col-lg-12<?php }?>">
            <div class="table-responsive">
                <table id="order-list" class="table table-bordered footab">
                    <tbody>
                        <tr>
                            <td>
                                <?php echo smartyTranslate(array('s'=>'Seller name','mod'=>'jmarketplace'),$_smarty_tpl);?>

                            </td>
                            <td>
                                <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['seller']->value->name, ENT_QUOTES, 'UTF-8', true);?>

                            </td>
                        </tr>
                        <?php if ($_smarty_tpl->tpl_vars['show_shop_name']->value) {?>
                            <tr>
                                <td>
                                    <?php echo smartyTranslate(array('s'=>'Seller shop','mod'=>'jmarketplace'),$_smarty_tpl);?>

                                </td>
                                <td>
                                    <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['seller']->value->shop, ENT_QUOTES, 'UTF-8', true);?>

                                </td>
                            </tr>
                        <?php }?>
                        <?php if ($_smarty_tpl->tpl_vars['show_cif']->value) {?>
                            <tr>
                                <td>
                                    <?php echo smartyTranslate(array('s'=>'CIF/NIF','mod'=>'jmarketplace'),$_smarty_tpl);?>

                                </td>
                                <td>
                                    <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['seller']->value->cif, ENT_QUOTES, 'UTF-8', true);?>

                                </td>
                            </tr>
                        <?php }?>
                        <?php if ($_smarty_tpl->tpl_vars['show_language']->value) {?>
                            <tr>
                                <td>
                                    <?php echo smartyTranslate(array('s'=>'Language','mod'=>'jmarketplace'),$_smarty_tpl);?>

                                </td>
                                <td>
                                    <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['seller_language']->value, ENT_QUOTES, 'UTF-8', true);?>

                                </td>
                            </tr>
                        <?php }?>
                        <?php if ($_smarty_tpl->tpl_vars['show_seller_rating']->value) {?>
                            <tr>
                                <td>
                                    <?php echo smartyTranslate(array('s'=>'Average rating','mod'=>'jmarketplace'),$_smarty_tpl);?>

                                </td>
                                <td>
                                    <div class="average_rating buttons_bottom_block">
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
                                            <?php endfor; endif; ?>
                                            (<span id="average_total"><?php echo intval($_smarty_tpl->tpl_vars['averageTotal']->value);?>
</span>)
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php }?>
                        <?php if ($_smarty_tpl->tpl_vars['show_seller_favorite']->value) {?>
                            <tr>
                                <td>
                                    <?php echo smartyTranslate(array('s'=>'Followers','mod'=>'jmarketplace'),$_smarty_tpl);?>

                                </td>
                                <td>
                                    <?php echo intval($_smarty_tpl->tpl_vars['followers']->value);?>
 <?php echo smartyTranslate(array('s'=>'followers.','mod'=>'jmarketplace'),$_smarty_tpl);?>
 
                                </td>
                            </tr>
                        <?php }?>
                        <?php if ($_smarty_tpl->tpl_vars['show_email']->value) {?>
                            <tr>
                                <td>
                                    <?php echo smartyTranslate(array('s'=>'Seller email','mod'=>'jmarketplace'),$_smarty_tpl);?>

                                </td>
                                <td>
                                    <a href="mailto:<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['seller']->value->email, ENT_QUOTES, 'UTF-8', true);?>
">
                                        <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['seller']->value->email, ENT_QUOTES, 'UTF-8', true);?>

                                    </a>
                                </td>
                            </tr>
                        <?php }?>
                        <?php if ($_smarty_tpl->tpl_vars['show_phone']->value) {?>
                            <tr>
                                <td>
                                    <?php echo smartyTranslate(array('s'=>'Seller phone','mod'=>'jmarketplace'),$_smarty_tpl);?>

                                </td>
                                <td>
                                    <a href="tel:<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['seller']->value->phone, ENT_QUOTES, 'UTF-8', true);?>
">
                                        <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['seller']->value->phone, ENT_QUOTES, 'UTF-8', true);?>

                                    </a>
                                </td>
                            </tr>
                        <?php }?>
                        <?php if ($_smarty_tpl->tpl_vars['show_fax']->value) {?>
                            <tr>
                                <td>
                                    <?php echo smartyTranslate(array('s'=>'Seller fax','mod'=>'jmarketplace'),$_smarty_tpl);?>

                                </td>
                                <td>
                                    <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['seller']->value->fax, ENT_QUOTES, 'UTF-8', true);?>

                                </td>
                            </tr>
                        <?php }?>
                        <?php if ($_smarty_tpl->tpl_vars['show_address']->value) {?>
                            <tr>
                                <td>
                                    <?php echo smartyTranslate(array('s'=>'Seller address','mod'=>'jmarketplace'),$_smarty_tpl);?>

                                </td>
                                <td>
                                    <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['seller']->value->address, ENT_QUOTES, 'UTF-8', true);?>

                                </td>
                            </tr>
                        <?php }?>
                        <?php if ($_smarty_tpl->tpl_vars['show_country']->value) {?>
                            <tr>
                                <td>
                                    <?php echo smartyTranslate(array('s'=>'Seller country','mod'=>'jmarketplace'),$_smarty_tpl);?>

                                </td>
                                <td>
                                    <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['seller']->value->country, ENT_QUOTES, 'UTF-8', true);?>

                                </td>
                            </tr>
                        <?php }?>
                        <?php if ($_smarty_tpl->tpl_vars['show_state']->value) {?>
                            <tr>
                                <td>
                                    <?php echo smartyTranslate(array('s'=>'Seller state','mod'=>'jmarketplace'),$_smarty_tpl);?>

                                </td>
                                <td>
                                    <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['seller']->value->state, ENT_QUOTES, 'UTF-8', true);?>

                                </td>
                            </tr>
                        <?php }?>
                        <?php if ($_smarty_tpl->tpl_vars['show_postcode']->value) {?>
                            <tr>
                                <td>
                                    <?php echo smartyTranslate(array('s'=>'Post code','mod'=>'jmarketplace'),$_smarty_tpl);?>

                                </td>
                                <td>
                                    <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['seller']->value->postcode, ENT_QUOTES, 'UTF-8', true);?>

                                </td>
                            </tr>
                        <?php }?>
                        <?php if ($_smarty_tpl->tpl_vars['show_city']->value) {?>
                            <tr>
                                <td>
                                    <?php echo smartyTranslate(array('s'=>'Seller city','mod'=>'jmarketplace'),$_smarty_tpl);?>

                                </td>
                                <td>
                                    <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['seller']->value->city, ENT_QUOTES, 'UTF-8', true);?>

                                </td>
                            </tr>
                        <?php }?>
                        <?php if ($_smarty_tpl->tpl_vars['show_description']->value) {?>
                            <tr>
                                <td>
                                    <?php echo smartyTranslate(array('s'=>'Seller description','mod'=>'jmarketplace'),$_smarty_tpl);?>

                                </td>
                                <td>
                                    <?php echo $_smarty_tpl->tpl_vars['seller']->value->description;?>
 
                                </td>
                            </tr>
                        <?php }?>
                        <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0][0]->smartyHook(array('h'=>'displayMarketplaceTableProfile'),$_smarty_tpl);?>

                    </tbody>
                </table>
            </div>
            <p class="seller_profile_buttons">
                <a class="btn btn-xs" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['seller_products_link']->value, ENT_QUOTES, 'UTF-8', true);?>
">
                    <i class="icon-list fa fa-list"></i> 
                    <?php echo smartyTranslate(array('s'=>'View products of','mod'=>'jmarketplace'),$_smarty_tpl);?>
 
                    <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['seller']->value->name, ENT_QUOTES, 'UTF-8', true);?>

                </a>
                <?php if ($_smarty_tpl->tpl_vars['show_edit_seller_account']->value==1&&$_smarty_tpl->tpl_vars['seller_me']->value) {?>
                    <a class="btn btn-secondary btn-xs" title="<?php echo smartyTranslate(array('s'=>'Edit your seller account','mod'=>'jmarketplace'),$_smarty_tpl);?>
" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getModuleLink('jmarketplace','editseller'), ENT_QUOTES, 'UTF-8', true);?>
">
                        <i class="icon-pencil fa fa-pencil"></i> 
                        <?php echo smartyTranslate(array('s'=>'Edit your seller account','mod'=>'jmarketplace'),$_smarty_tpl);?>

                      </a> 
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['show_contact']->value) {?>
                    <a class="btn btn-secondary btn-xs" title="<?php echo smartyTranslate(array('s'=>'Contact with','mod'=>'jmarketplace'),$_smarty_tpl);?>
 <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['seller']->value->name, ENT_QUOTES, 'UTF-8', true);?>
" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['url_contact_seller']->value, ENT_QUOTES, 'UTF-8', true);?>
">
                        <i class="icon-comment fa fa-comment"></i> 
                        <?php echo smartyTranslate(array('s'=>'Conctact','mod'=>'jmarketplace'),$_smarty_tpl);?>

                    </a> 
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['show_seller_favorite']->value&&!$_smarty_tpl->tpl_vars['seller_me']->value) {?>
                    <a class="btn btn-secondary btn-xs" title="<?php echo smartyTranslate(array('s'=>'Add to favorite seller','mod'=>'jmarketplace'),$_smarty_tpl);?>
" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['url_favorite_seller']->value, ENT_QUOTES, 'UTF-8', true);?>
">
                        <i class="icon-heart fa fa-heart"></i> 
                        <?php echo smartyTranslate(array('s'=>'Add to favorite seller','mod'=>'jmarketplace'),$_smarty_tpl);?>

                    </a> 
                <?php }?>
            </p>
        </div>
    </div>
</div>

<?php if ($_smarty_tpl->tpl_vars['show_new_products']->value) {?>                
    <?php if (isset($_smarty_tpl->tpl_vars['products']->value)&&$_smarty_tpl->tpl_vars['products']->value) {?>
        <div class="box">
            <h1 class="page-heading">
                <?php echo smartyTranslate(array('s'=>'News of','mod'=>'jmarketplace'),$_smarty_tpl);?>
 <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['seller']->value->name, ENT_QUOTES, 'UTF-8', true);?>

            </h1>
            <?php echo $_smarty_tpl->getSubTemplate ("./product-list.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('class'=>'tab-pane','id'=>'jmarketplace'), 0);?>

        </div>
    <?php }?>
<?php }?>

<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0][0]->smartyHook(array('h'=>'displayMarketplaceFooterProfile'),$_smarty_tpl);?>


<?php if ($_smarty_tpl->tpl_vars['seller_me']->value) {?>
    <ul class="footer_links clearfix">
        <li>
            <a class="btn btn-default button" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getModuleLink('jmarketplace','selleraccount',array(),true), ENT_QUOTES, 'UTF-8', true);?>
">
                <i class="icon-chevron-left fa fa-chevron-left"></i>
                <span>
                    <?php echo smartyTranslate(array('s'=>'Back to your seller account','mod'=>'jmarketplace'),$_smarty_tpl);?>

                </span>
            </a>
        </li>
        <li>
            <a class="btn btn-default button" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getPageLink('my-account',true), ENT_QUOTES, 'UTF-8', true);?>
">
                <i class="icon-chevron-left fa fa-chevron-left"></i>
                <span>
                    <?php echo smartyTranslate(array('s'=>'Back to your account','mod'=>'jmarketplace'),$_smarty_tpl);?>

                </span>
            </a>
        </li>
    </ul>
<?php } else { ?>
    <ul class="footer_links clearfix">
        <li>
            <a class="btn btn-default button button-small" href="javascript: history.go(-1)">
                <span>
                    <i class="icon-chevron-left fa fa-chevron-left"></i> 
                    <?php echo smartyTranslate(array('s'=>'Go back','mod'=>'jmarketplace'),$_smarty_tpl);?>

                </span>
            </a>
        </li>
        <li>
            <a class="btn btn-default button button-small btn-secondary" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['base_dir']->value, ENT_QUOTES, 'UTF-8', true);?>
">
                <span>
                    <i class="icon-chevron-left fa fa-chevron-left"></i> 
                    <?php echo smartyTranslate(array('s'=>'Home','mod'=>'jmarketplace'),$_smarty_tpl);?>

                </span>
            </a>
        </li>
    </ul>
<?php }?>                   <?php }} ?>
