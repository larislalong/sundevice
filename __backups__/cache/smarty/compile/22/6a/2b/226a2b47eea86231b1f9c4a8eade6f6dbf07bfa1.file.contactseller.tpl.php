<?php /* Smarty version Smarty-3.1.19, created on 2019-02-04 03:12:34
         compiled from "/home/sundevice/public_html/modules/jmarketplace/views/templates/front/contactseller.tpl" */ ?>
<?php /*%%SmartyHeaderCode:15147364305c579f925a75c4-17395808%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '226a2b47eea86231b1f9c4a8eade6f6dbf07bfa1' => 
    array (
      0 => '/home/sundevice/public_html/modules/jmarketplace/views/templates/front/contactseller.tpl',
      1 => 1534502030,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15147364305c579f925a75c4-17395808',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'link' => 0,
    'navigationPipe' => 0,
    'confirmation' => 0,
    'errors' => 0,
    'logged' => 0,
    'id_seller' => 0,
    'id_product' => 0,
    'incidences' => 0,
    'incidence' => 0,
    'message' => 0,
    'base_dir' => 0,
    'url_contact_seller' => 0,
    'num_orders' => 0,
    'orders_products' => 0,
    'order' => 0,
    'product' => 0,
    'seller' => 0,
    'PS_REWRITING_SETTINGS' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c579f9262c633_76117808',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c579f9262c633_76117808')) {function content_5c579f9262c633_76117808($_smarty_tpl) {?>

<?php $_smarty_tpl->_capture_stack[0][] = array('path', null, null); ob_start(); ?>
    <a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getPageLink('my-account',true), ENT_QUOTES, 'UTF-8', true);?>
">
        <?php echo smartyTranslate(array('s'=>'My account','mod'=>'jmarketplace'),$_smarty_tpl);?>

    </a>
    <span class="navigation-pipe">
        <?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['navigationPipe']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>

    </span>
    <span class="navigation_page">
        <?php echo smartyTranslate(array('s'=>'Messages','mod'=>'jmarketplace'),$_smarty_tpl);?>

    </span>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<?php if (isset($_smarty_tpl->tpl_vars['confirmation']->value)&&$_smarty_tpl->tpl_vars['confirmation']->value) {?>
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-success"><?php echo smartyTranslate(array('s'=>'Your issue has been successfully sent.','mod'=>'jmarketplace'),$_smarty_tpl);?>
</div>
        </div>
    </div>
<?php } else { ?>
    <?php if (isset($_smarty_tpl->tpl_vars['errors']->value)&&$_smarty_tpl->tpl_vars['errors']->value) {?>
        <?php echo $_smarty_tpl->getSubTemplate ("./errors.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

    <?php }?>
<?php }?>

<div class="box">
    <?php if ($_smarty_tpl->tpl_vars['logged']->value) {?>
        <?php if ($_smarty_tpl->tpl_vars['id_seller']->value==0&&$_smarty_tpl->tpl_vars['id_product']->value==0) {?>
            <?php if ($_smarty_tpl->tpl_vars['incidences']->value&&count($_smarty_tpl->tpl_vars['incidences']->value)) {?>
                <h1 class="page-subheading">
                    <?php echo smartyTranslate(array('s'=>'Messages','mod'=>'jmarketplace'),$_smarty_tpl);?>

                </h1>
                <div class="table-responsive">
                    <table id="order-list" class="table table-bordered footab">
                        <thead>
                            <tr>
                                <th class="first_item">
                                    <?php echo smartyTranslate(array('s'=>'Date add','mod'=>'jmarketplace'),$_smarty_tpl);?>

                                </th>
                                <th class="first_item">
                                    <?php echo smartyTranslate(array('s'=>'Date upd','mod'=>'jmarketplace'),$_smarty_tpl);?>

                                </th>
                                <th class="item">
                                    <?php echo smartyTranslate(array('s'=>'Incidence reference','mod'=>'jmarketplace'),$_smarty_tpl);?>

                                </th>
                                <th class="item_last">
                                    <?php echo smartyTranslate(array('s'=>'Order ID - Ref','mod'=>'jmarketplace'),$_smarty_tpl);?>

                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  $_smarty_tpl->tpl_vars['incidence'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['incidence']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['incidences']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['incidence']->key => $_smarty_tpl->tpl_vars['incidence']->value) {
$_smarty_tpl->tpl_vars['incidence']->_loop = true;
?>
                                <tr>
                                    <td class="first_item<?php if (($_smarty_tpl->tpl_vars['incidence']->value['messages_not_readed']>0)) {?> not_readed<?php }?>">
                                        <i class="icon-calendar fa fa-calendar"></i> 
                                        - <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['dateFormat'][0][0]->dateFormat(array('date'=>$_smarty_tpl->tpl_vars['incidence']->value['date_add'],'full'=>0),$_smarty_tpl);?>
 
                                        - <i class="icon-time fa fa-clock-o"></i> 
                                        <?php echo substr(mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['incidence']->value['date_add'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8'),11,5);?>

                                    </td>
                                    <td class="item<?php if (($_smarty_tpl->tpl_vars['incidence']->value['messages_not_readed']>0)) {?> not_readed<?php }?>">
                                        <i class="icon-calendar fa fa-calendar"></i> 
                                        - <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['dateFormat'][0][0]->dateFormat(array('date'=>$_smarty_tpl->tpl_vars['incidence']->value['date_upd'],'full'=>0),$_smarty_tpl);?>
 
                                        - <i class="icon-time fa fa-clock-o"></i> 
                                        <?php echo substr(mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['incidence']->value['date_upd'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8'),11,5);?>

                                    </td>
                                    <td class="item<?php if (($_smarty_tpl->tpl_vars['incidence']->value['messages_not_readed']>0)) {?> not_readed<?php }?>">
                                        <?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['incidence']->value['reference'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>

                                    </td>
                                    <?php if ($_smarty_tpl->tpl_vars['incidence']->value['id_order']==0) {?>
                                        <td class="item<?php if (($_smarty_tpl->tpl_vars['incidence']->value['messages_not_readed']>0)) {?> not_readed<?php }?>">
                                            <?php echo smartyTranslate(array('s'=>'No order','mod'=>'jmarketplace'),$_smarty_tpl);?>

                                        </td>
                                    <?php } else { ?>
                                        <td class="item<?php if (($_smarty_tpl->tpl_vars['incidence']->value['messages_not_readed']>0)) {?> not_readed<?php }?>">
                                            <?php echo intval($_smarty_tpl->tpl_vars['incidence']->value['id_order']);?>
 
                                            - <?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['incidence']->value['order_ref'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>

                                        </td>
                                    <?php }?>
                                    <td class="last_item">
                                        <a class="btn btn-xs open_incidence" data="<?php echo intval($_smarty_tpl->tpl_vars['incidence']->value['id_seller_incidence']);?>
" href="#">
                                            <i class="fa fa-eye"></i> 
                                            <?php echo smartyTranslate(array('s'=>'View','mod'=>'jmarketplace'),$_smarty_tpl);?>

                                        </a>
                                    </td> 
                                </tr>
                                <tr id="incidence_<?php echo intval($_smarty_tpl->tpl_vars['incidence']->value['id_seller_incidence']);?>
" style="display:none;">
                                    <td class="incidence_messages" colspan="7">
                                        <?php if ($_smarty_tpl->tpl_vars['incidence']->value['id_product']!=0) {?>
                                            <h3>
                                                <?php echo smartyTranslate(array('s'=>'Messages about','mod'=>'jmarketplace'),$_smarty_tpl);?>
 
                                                <?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['incidence']->value['product_name'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>

                                            </h3>
                                        <?php } else { ?>
                                            <h3>
                                                <?php echo smartyTranslate(array('s'=>'Messages','mod'=>'jmarketplace'),$_smarty_tpl);?>

                                            </h3>
                                        <?php }?>
                                        <?php  $_smarty_tpl->tpl_vars['message'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['message']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['incidence']->value['messages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['message']->key => $_smarty_tpl->tpl_vars['message']->value) {
$_smarty_tpl->tpl_vars['message']->_loop = true;
?>
                                            <div class="message<?php if ($_smarty_tpl->tpl_vars['message']->value['id_seller']!=0) {?> seller<?php } elseif ($_smarty_tpl->tpl_vars['message']->value['id_customer']!=0) {?> customer<?php } else { ?> employee<?php }?>">
                                                <div class="author">
                                                    <?php if ($_smarty_tpl->tpl_vars['message']->value['id_customer']!=0) {?>
                                                        <?php echo smartyTranslate(array('s'=>'Customer','mod'=>'jmarketplace'),$_smarty_tpl);?>
 
                                                        <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['message']->value['customer_firstname'], ENT_QUOTES, 'UTF-8', true);?>
 
                                                        <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['message']->value['customer_lastname'], ENT_QUOTES, 'UTF-8', true);?>

                                                    <?php } elseif ($_smarty_tpl->tpl_vars['message']->value['id_seller']!=0) {?>
                                                        <?php echo smartyTranslate(array('s'=>'Seller','mod'=>'jmarketplace'),$_smarty_tpl);?>
 
                                                        <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['message']->value['seller_name'], ENT_QUOTES, 'UTF-8', true);?>
 
                                                    <?php } else { ?>
                                                        <?php echo smartyTranslate(array('s'=>'Administrator','mod'=>'jmarketplace'),$_smarty_tpl);?>
 
                                                        <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['message']->value['employee_firstname'], ENT_QUOTES, 'UTF-8', true);?>
 
                                                        <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['message']->value['employee_lastname'], ENT_QUOTES, 'UTF-8', true);?>

                                                    <?php }?>
                                                    - <i class="icon-calendar fa fa-calendar"></i> 
                                                    - <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['dateFormat'][0][0]->dateFormat(array('date'=>$_smarty_tpl->tpl_vars['message']->value['date_add'],'full'=>0),$_smarty_tpl);?>
 
                                                    - <i class="icon-time fa fa-clock-o"></i> 
                                                    <?php echo substr(mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['message']->value['date_add'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8'),11,5);?>

                                                </div>
                                                <div class="description">
                                                    <?php echo nl2br($_smarty_tpl->tpl_vars['message']->value['description']);?>
 
                                                </div>
                                                <?php if ($_smarty_tpl->tpl_vars['message']->value['attachment']) {?>
                                                    <div class="attachment">
                                                        <a href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['base_dir']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
modules/jmarketplace/attachment/<?php echo intval($_smarty_tpl->tpl_vars['message']->value['id_seller_incidence']);?>
/<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['message']->value['attachment'], ENT_QUOTES, 'UTF-8', true);?>
" target="_blank">
                                                            <i class="fa fa-paperclip"></i> 
                                                            <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['message']->value['attachment'], ENT_QUOTES, 'UTF-8', true);?>

                                                        </a>
                                                    </div>
                                                <?php }?>
                                            </div>
                                        <?php } ?>    
                                        <form action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['url_contact_seller']->value, ENT_QUOTES, 'UTF-8', true);?>
" method="post" class="std" enctype="multipart/form-data">
                                            <input type="hidden" name="id_incidence" id="id_incidence" value="<?php echo intval($_smarty_tpl->tpl_vars['message']->value['id_seller_incidence']);?>
" />
                                            <fieldset>
                                                <div class="form-group">
                                                    <label for="description">
                                                        <?php echo smartyTranslate(array('s'=>'Add response','mod'=>'jmarketplace'),$_smarty_tpl);?>
 
                                                    </label>
                                                    <textarea class="form-control" name="description" cols="40" rows="7"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit" name="submitResponse" class="btn btn-default button button-medium">
                                                        <span>
                                                            <?php echo smartyTranslate(array('s'=>'Send','mod'=>'jmarketplace'),$_smarty_tpl);?>
 
                                                            <i class="icon-chevron-right right fa fa-chevron-right"></i>
                                                        </span>
                                                    </button>
                                                </div>
                                            </fieldset>
                                        </form>
                                    </td> 
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } else { ?>
                <p class="alert alert-info">
                    <?php echo smartyTranslate(array('s'=>'There are not messages.','mod'=>'jmarketplace'),$_smarty_tpl);?>

                </p>
            <?php }?>
        <?php }?>
        
        <div class="form_incidences" style="width:100%; display:block;">
            <form action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['url_contact_seller']->value, ENT_QUOTES, 'UTF-8', true);?>
" method="post" class="std" enctype="multipart/form-data">
                <fieldset>
                    <?php if (($_smarty_tpl->tpl_vars['num_orders']->value>0&&$_smarty_tpl->tpl_vars['id_seller']->value==0&&$_smarty_tpl->tpl_vars['id_product']->value==0)) {?>
                        <h1 class="page-subheading">
                            <?php echo smartyTranslate(array('s'=>'Add new message','mod'=>'jmarketplace'),$_smarty_tpl);?>

                        </h1>
                        <div class="required form-group">
                            <label for="order">
                                <?php echo smartyTranslate(array('s'=>'Order/Product','mod'=>'jmarketplace'),$_smarty_tpl);?>
 
                            </label>
                            <select id="id_order_product" class="form-control" name="id_order_product">
                                <?php  $_smarty_tpl->tpl_vars['order'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['order']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['orders_products']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['order']->key => $_smarty_tpl->tpl_vars['order']->value) {
$_smarty_tpl->tpl_vars['order']->_loop = true;
?>
                                    <option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['order']->value['id_order_product'], ENT_QUOTES, 'UTF-8', true);?>
">
                                        <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['order']->value['order_reference'], ENT_QUOTES, 'UTF-8', true);?>
 
                                        - <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['order']->value['order_date_add'], ENT_QUOTES, 'UTF-8', true);?>
 
                                        - <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['order']->value['product_name'], ENT_QUOTES, 'UTF-8', true);?>

                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="description">
                                <?php echo smartyTranslate(array('s'=>'Message','mod'=>'jmarketplace'),$_smarty_tpl);?>
 
                            </label>
                            <textarea class="form-control" name="description" cols="40" rows="7"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="fileUpload">
                                <?php echo smartyTranslate(array('s'=>'File attachment','mod'=>'jmarketplace'),$_smarty_tpl);?>

                            </label>
                            <input type="file" name="attachment" class="form-control" />
                        </div>
                        <div class="form-group">
                            <button type="submit" name="submitAddIncidence" class="btn btn-default button button-medium">
                                <span>
                                    <?php echo smartyTranslate(array('s'=>'Send','mod'=>'jmarketplace'),$_smarty_tpl);?>
 
                                    <i class="icon-chevron-right right fa fa-chevron-right"></i>
                                </span>
                            </button>
                        </div>
                    <?php } elseif ((isset($_smarty_tpl->tpl_vars['id_seller']->value))) {?>
                        <h1 class="page-subheading">
                            <?php if (($_smarty_tpl->tpl_vars['id_product']->value!=0)) {?>
                                <?php echo smartyTranslate(array('s'=>'Add new message','mod'=>'jmarketplace'),$_smarty_tpl);?>
 
                                <?php echo smartyTranslate(array('s'=>'about','mod'=>'jmarketplace'),$_smarty_tpl);?>
 
                                "<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['product']->value->name, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
"
                            <?php } elseif (($_smarty_tpl->tpl_vars['id_seller']->value!=0)) {?>
                                <?php echo smartyTranslate(array('s'=>'Send new message to','mod'=>'jmarketplace'),$_smarty_tpl);?>
 
                                "<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['seller']->value->name, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
"
                            <?php } else { ?>
                                <?php echo smartyTranslate(array('s'=>'Add new message','mod'=>'jmarketplace'),$_smarty_tpl);?>

                            <?php }?>
                        </h1>
                        
                        <input type="hidden" name="id_seller" value="<?php echo intval($_smarty_tpl->tpl_vars['id_seller']->value);?>
" />
                        <input type="hidden" name="id_product" value="<?php echo intval($_smarty_tpl->tpl_vars['id_product']->value);?>
" />
                        
                        <div class="form-group">
                            <label for="description">
                                <?php echo smartyTranslate(array('s'=>'Message','mod'=>'jmarketplace'),$_smarty_tpl);?>
 
                            </label>
                            <textarea class="form-control" name="description" cols="40" rows="7"></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" name="submitAddIncidence" class="btn btn-default button button-medium">
                                <span>
                                    <?php echo smartyTranslate(array('s'=>'Send','mod'=>'jmarketplace'),$_smarty_tpl);?>
 
                                    <i class="icon-chevron-right right fa fa-chevron-right"></i>
                                </span>
                            </button>
                        </div>
                    <?php }?>
                </fieldset>
            </form>
        </div>
    <?php } else { ?>
        <p class="alert alert-info">
            <?php echo smartyTranslate(array('s'=>'You must be logged in to contact the seller.','mod'=>'jmarketplace'),$_smarty_tpl);?>
 
            <a href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getPageLink('my-account',true), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
">
                <?php echo smartyTranslate(array('s'=>'Login or register','mod'=>'jmarketplace'),$_smarty_tpl);?>

            </a>
        </p>
    <?php }?>
</div>

<ul class="footer_links clearfix">
    <?php if (($_smarty_tpl->tpl_vars['id_product']->value!=0)) {?>
        <li>
            <a class="btn btn-default button button-small btn-secondary" href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getProductLink($_smarty_tpl->tpl_vars['id_product']->value), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
">
                <span>
                    <i class="icon-chevron-left fa fa-chevron-left"></i> 
                    <?php echo smartyTranslate(array('s'=>'Back to ','mod'=>'jmarketplace'),$_smarty_tpl);?>
 
                    <?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['product']->value->name, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>

                </span>
            </a>
        </li>
    <?php }?>
    <li>
        <a class="btn btn-default button btn-secondary" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getPageLink('my-account',true), ENT_QUOTES, 'UTF-8', true);?>
">
            <span>
                <i class="icon-chevron-left fa fa-chevron-left"></i> 
                <?php echo smartyTranslate(array('s'=>'Back to your account','mod'=>'jmarketplace'),$_smarty_tpl);?>

            </span>
        </a>
    </li>
</ul>         
<script type="text/javascript">
var sellermessages_controller_url = '<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getModuleLink('jmarketplace','contactseller',array(),true), ENT_QUOTES, 'UTF-8', true);?>
';
var PS_REWRITING_SETTINGS = "<?php echo intval($_smarty_tpl->tpl_vars['PS_REWRITING_SETTINGS']->value);?>
";
</script>          <?php }} ?>
