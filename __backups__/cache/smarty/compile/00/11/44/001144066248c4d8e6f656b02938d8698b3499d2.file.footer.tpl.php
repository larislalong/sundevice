<?php /* Smarty version Smarty-3.1.19, created on 2019-02-22 14:54:40
         compiled from "D:\wamp\www\projects\ps\sun-device.local\themes\pos_ruby5\footer.tpl" */ ?>
<?php /*%%SmartyHeaderCode:70195c6fff206762e4-42572776%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '001144066248c4d8e6f656b02938d8698b3499d2' => 
    array (
      0 => 'D:\\wamp\\www\\projects\\ps\\sun-device.local\\themes\\pos_ruby5\\footer.tpl',
      1 => 1550763431,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '70195c6fff206762e4-42572776',
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
    'css_dir' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c6fff20772434_92616590',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c6fff20772434_92616590')) {function content_5c6fff20772434_92616590($_smarty_tpl) {?>
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
				
					<div id="sundevice_plus_footer"  class="sundevice_plus container">
						<ul class="row clearfix">
							<li class="col-lg-4 text-center">
								<div class="sundevice_plus-image">
									<img  class="img-responsive" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['css_dir']->value, ENT_QUOTES, 'UTF-8', true);?>
../img/new-images/1_sav-reactif.png" alt="<?php echo smartyTranslate(array('s'=>'SAV réactif'),$_smarty_tpl);?>
" />
								</div>
								<h5><?php echo smartyTranslate(array('s'=>'SAV réactif'),$_smarty_tpl);?>
</h5>
							</li>
							<li class="col-lg-4 text-center">
								<div class="sundevice_plus-image">
									<img  class="img-responsive" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['css_dir']->value, ENT_QUOTES, 'UTF-8', true);?>
../img/new-images/2_livraison-rapide.png" alt="<?php echo smartyTranslate(array('s'=>'Livraison rapide'),$_smarty_tpl);?>
" />
								</div>
								<h5><?php echo smartyTranslate(array('s'=>'Livraison rapide'),$_smarty_tpl);?>
</h5>
							</li>
							<li class="col-lg-4 text-center">
								<div class="sundevice_plus-image">
									<img  class="img-responsive" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['css_dir']->value, ENT_QUOTES, 'UTF-8', true);?>
../img/new-images/3_<?php echo smartyTranslate(array('s'=>'paiement-securise.png'),$_smarty_tpl);?>
" alt="<?php echo smartyTranslate(array('s'=>'Paiement sécurisé'),$_smarty_tpl);?>
" />
								</div>
								<h5><?php echo smartyTranslate(array('s'=>'Paiement sécurisé'),$_smarty_tpl);?>
</h5>
							</li>
							
						</ul>
					</div>
					
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
