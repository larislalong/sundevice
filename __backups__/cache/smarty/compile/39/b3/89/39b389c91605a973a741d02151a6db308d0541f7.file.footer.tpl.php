<?php /* Smarty version Smarty-3.1.19, created on 2019-02-13 23:00:44
         compiled from "D:\wamp\www\projects\ps\sun-device.local\themes\sundevice\footer.tpl" */ ?>
<?php /*%%SmartyHeaderCode:168705c64938cd69272-46624040%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '39b389c91605a973a741d02151a6db308d0541f7' => 
    array (
      0 => 'D:\\wamp\\www\\projects\\ps\\sun-device.local\\themes\\sundevice\\footer.tpl',
      1 => 1550094316,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '168705c64938cd69272-46624040',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'content_only' => 0,
    'right_column_size' => 0,
    'HOOK_RIGHT_COLUMN' => 0,
    'page_name' => 0,
    'HOOK_FOOTER' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c64938ce36166_89017676',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c64938ce36166_89017676')) {function content_5c64938ce36166_89017676($_smarty_tpl) {?>
<?php if (!isset($_smarty_tpl->tpl_vars['content_only']->value)||!$_smarty_tpl->tpl_vars['content_only']->value) {?>
					</div><!-- #center_column -->
					<?php if (isset($_smarty_tpl->tpl_vars['right_column_size']->value)&&!empty($_smarty_tpl->tpl_vars['right_column_size']->value)) {?>
						<div id="right_column" class="col-xs-12 col-sm-<?php echo intval($_smarty_tpl->tpl_vars['right_column_size']->value);?>
 column"><?php echo $_smarty_tpl->tpl_vars['HOOK_RIGHT_COLUMN']->value;?>
</div>
					<?php }?>
					</div><!-- .row -->
				</div><!-- #columns -->
			</div><!-- .columns-container -->
			<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0][0]->smartyHook(array('h'=>"brandSlider"),$_smarty_tpl);?>

			<!-- Footer -->
			<div class="footer-container">
				<footer id="footer">
					<?php if ($_smarty_tpl->tpl_vars['page_name']->value=='index') {?>
						<?php if (isset($_smarty_tpl->tpl_vars['HOOK_FOOTER']->value)) {?><?php echo $_smarty_tpl->tpl_vars['HOOK_FOOTER']->value;?>
<?php }?>
						
						<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0][0]->smartyHook(array('h'=>"blockFooter1"),$_smarty_tpl);?>

					<?php }?>
					
					<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0][0]->smartyHook(array('h'=>"blockFooterExtra"),$_smarty_tpl);?>

					<div class="footer-bottom">
						<div class="container">
							<div class="row">
								
								
								<div class="col-lg-4 col-sm-12 text-left">
									<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0][0]->smartyHook(array('h'=>"blockFooter4"),$_smarty_tpl);?>

								</div>
								<div class="col-lg-4 col-sm-12 text-center">
									<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0][0]->smartyHook(array('h'=>"blockFooter2"),$_smarty_tpl);?>

								</div>
								<div class="col-lg-4 col-sm-12 text-right">
									<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0][0]->smartyHook(array('h'=>"blockFooter3"),$_smarty_tpl);?>

								</div>
							</div>
						</div>
					</div>
				</footer>
			</div><!-- #footer -->
			<div class="back-top"><a href= "#" class="back-top-button hidden-xs"></a></div>
		</div><!-- #page_inner -->
		</div><!-- #page -->
<?php }?>
<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./global.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

	</body>
</html><?php }} ?>
