<?php /* Smarty version Smarty-3.1.19, created on 2019-04-29 16:36:46
         compiled from "/home/sundevice/public_html/modules/menupro/views/templates/hook/main_menu_mega.tpl" */ ?>
<?php /*%%SmartyHeaderCode:12368052815cc70bfe583600-14933797%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '11c8ed6d3243337ab9d3b7aea4ee96dac8d1757d' => 
    array (
      0 => '/home/sundevice/public_html/modules/menupro/views/templates/hook/main_menu_mega.tpl',
      1 => 1501665696,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12368052815cc70bfe583600-14933797',
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
  'unifunc' => 'content_5cc70bfe58c9c0_08521816',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cc70bfe58c9c0_08521816')) {function content_5cc70bfe58c9c0_08521816($_smarty_tpl) {?>

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
