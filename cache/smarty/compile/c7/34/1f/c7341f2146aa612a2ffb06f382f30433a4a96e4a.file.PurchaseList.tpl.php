<?php /* Smarty version Smarty-3.1.19, created on 2019-04-29 23:39:46
         compiled from "/home/sundevice/public_html/modules/savemypurchases/views/templates/front/PurchaseList.tpl" */ ?>
<?php /*%%SmartyHeaderCode:18742769235cc76f22a51e42-66489340%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c7341f2146aa612a2ffb06f382f30433a4a96e4a' => 
    array (
      0 => '/home/sundevice/public_html/modules/savemypurchases/views/templates/front/PurchaseList.tpl',
      1 => 1534419426,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18742769235cc76f22a51e42-66489340',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'navigationPipe' => 0,
    'purchases' => 0,
    'i' => 0,
    'purchase' => 0,
    'product' => 0,
    'id_cart' => 0,
    'link' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cc76f22a76b89_34206399',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cc76f22a76b89_34206399')) {function content_5cc76f22a76b89_34206399($_smarty_tpl) {?><?php $_smarty_tpl->_capture_stack[0][] = array('path', null, null); ob_start(); ?>
    <span class="navigation-pipe">
        <?php echo $_smarty_tpl->tpl_vars['navigationPipe']->value;?>

    </span>
    <span class="navigation_page">
        <?php echo smartyTranslate(array('s'=>'Carts','mod'=>'savemypurchases'),$_smarty_tpl);?>

    </span>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<?php if (!empty($_smarty_tpl->tpl_vars['purchases']->value)) {?>
    <table class="table table-bordered footab">
        <thead>
                <th class="first_item" data-sort-ignore="true"><?php echo smartyTranslate(array('s'=>'Cart','mod'=>'savemypurchases'),$_smarty_tpl);?>
</th>
                <th data-sort-ignore="true" data-hide="" class="item"><?php echo smartyTranslate(array('s'=>'Created at','mod'=>'savemypurchases'),$_smarty_tpl);?>
</th>
                <th data-sort-ignore="true" data-hide="" class="item"><?php echo smartyTranslate(array('s'=>'Product(s) in cart','mod'=>'savemypurchases'),$_smarty_tpl);?>
</th>
                <th class="last_item text-center"><?php echo smartyTranslate(array('s'=>'Price','mod'=>'savemypurchases'),$_smarty_tpl);?>
</th>
                <th class="last_item text-center"><?php echo smartyTranslate(array('s'=>'Load','mod'=>'savemypurchases'),$_smarty_tpl);?>
</th>
        </thead>
        <tbody>
        <?php $_smarty_tpl->tpl_vars['i'] = new Smarty_variable(1, null, 0);?>
            <?php  $_smarty_tpl->tpl_vars['purchase'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['purchase']->_loop = false;
 $_smarty_tpl->tpl_vars['id_cart'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['purchases']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['purchase']->key => $_smarty_tpl->tpl_vars['purchase']->value) {
$_smarty_tpl->tpl_vars['purchase']->_loop = true;
 $_smarty_tpl->tpl_vars['id_cart']->value = $_smarty_tpl->tpl_vars['purchase']->key;
?>
                <tr>
                    <td><?php echo $_smarty_tpl->tpl_vars['i']->value;?>
</td>
                    <td><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['dateFormat'][0][0]->dateFormat(array('date'=>$_smarty_tpl->tpl_vars['purchase']->value['date']),$_smarty_tpl);?>
</td>
                    <td>
						<ul>
						<?php  $_smarty_tpl->tpl_vars['product'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['product']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['purchase']->value['products']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['product']->key => $_smarty_tpl->tpl_vars['product']->value) {
$_smarty_tpl->tpl_vars['product']->_loop = true;
?>
							<li><?php echo $_smarty_tpl->tpl_vars['product']->value;?>
</li>
						<?php } ?>
						</ul>
					</td>
                    <td>
                        <?php echo $_smarty_tpl->tpl_vars['purchase']->value['total'];?>

                    </td>
                    <td>
                        <a class="btn btn-default button button-small" href="<?php echo $_smarty_tpl->tpl_vars['link']->value->getModulelink('savemypurchases','purchases',array('load_cart'=>$_smarty_tpl->tpl_vars['id_cart']->value));?>
">
								<span>
									<?php echo smartyTranslate(array('s'=>'Load this cart','mod'=>'savemypurchases'),$_smarty_tpl);?>
<i class="icon-chevron-right right"></i>
								</span>
                        </a>
                    </td>
                </tr>
                <?php $_smarty_tpl->tpl_vars['i'] = new Smarty_variable($_smarty_tpl->tpl_vars['i']->value+1, null, 0);?>
            <?php } ?>
        </tbody>
        <tfoot>
            <th class="first_item" data-sort-ignore="true"><?php echo smartyTranslate(array('s'=>'Cart','mod'=>'savemypurchases'),$_smarty_tpl);?>
</th>
            <th data-sort-ignore="true" data-hide="" class="item"><?php echo smartyTranslate(array('s'=>'Created at','mod'=>'savemypurchases'),$_smarty_tpl);?>
</th>
            <th data-sort-ignore="true" data-hide="" class="item"><?php echo smartyTranslate(array('s'=>'Product(s) in cart','mod'=>'savemypurchases'),$_smarty_tpl);?>
</th>
            <th class="last_item text-center"><?php echo smartyTranslate(array('s'=>'Price','mod'=>'savemypurchases'),$_smarty_tpl);?>
</th>
            <th class="last_item text-center"><?php echo smartyTranslate(array('s'=>'Load','mod'=>'savemypurchases'),$_smarty_tpl);?>
</th>
        </tfoot>
    </table>

<?php } else { ?>
    <p class="alert alert-warning">
        <?php echo smartyTranslate(array('s'=>'You have nothing cart save','mod'=>'savemypurchases'),$_smarty_tpl);?>

    </p>
<?php }?><?php }} ?>
