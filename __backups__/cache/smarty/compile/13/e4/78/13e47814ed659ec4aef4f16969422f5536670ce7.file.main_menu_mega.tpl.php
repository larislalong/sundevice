<?php /* Smarty version Smarty-3.1.19, created on 2019-02-22 14:54:40
         compiled from "D:\wamp\www\projects\ps\sun-device.local\modules\menupro\views\templates\hook\main_menu_mega.tpl" */ ?>
<?php /*%%SmartyHeaderCode:76535c6fff202830c1-86344793%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '13e47814ed659ec4aef4f16969422f5536670ce7' => 
    array (
      0 => 'D:\\wamp\\www\\projects\\ps\\sun-device.local\\modules\\menupro\\views\\templates\\hook\\main_menu_mega.tpl',
      1 => 1501665696,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '76535c6fff202830c1-86344793',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'mainMenu' => 0,
    'secondaryMenuContent' => 0,
    'searchAction' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c6fff20329221_80892563',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c6fff20329221_80892563')) {function content_5c6fff20329221_80892563($_smarty_tpl) {?>

<div class="mp_mega_menu">
    <div class="menu-container container" <?php echo $_smarty_tpl->tpl_vars['mainMenu']->value['style']['no_event'];?>
>
        <div class="menu" <?php echo $_smarty_tpl->tpl_vars['mainMenu']->value['style']['no_event'];?>
>
            <div class="clear"></div>
				<a href="#" class="menu-mobile" <?php echo $_smarty_tpl->tpl_vars['mainMenu']->value['style']['no_event'];?>
><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['mainMenu']->value['name'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</a>
				<?php echo $_smarty_tpl->tpl_vars['secondaryMenuContent']->value;?>

			<div class="clear"></div>
		</div>
		<?php if ($_smarty_tpl->tpl_vars['mainMenu']->value['show_search_bar']) {?>
		<div class="search-box-outer">
			<div class="dropdown">
				<button class="search-box-btn dropdown-toggle" type="button" data-toggle="dropdown"></button>
				<ul class="dropdown-menu search-panel">
					<li class="panel-outer">
						<div class="form-container">
							<form method="post" action="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['searchAction']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" class="searchbox">
								<div class="form-group">
									<input class="search_query" type="search" name="search_query" value="" placeholder="<?php echo smartyTranslate(array('s'=>'Search...','mod'=>'menupro'),$_smarty_tpl);?>
"/>
									<button type="submit" class="search-btn"></button>
								</div>
							</form>
						</div>
					</li>
				</ul>
			</div>
		</div>
		<?php }?>
	</div>
</div><?php }} ?>
