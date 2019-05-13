<?php /* Smarty version Smarty-3.1.19, created on 2019-05-13 10:04:09
         compiled from "/home/sundevice/preprod/themes/pos_ruby5/modules/blocksearch/blocksearch-top.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1220115985cd1e34519cc72-07296159%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a66f73cffe049659648c0001fa15b7693e9cee3f' => 
    array (
      0 => '/home/sundevice/preprod/themes/pos_ruby5/modules/blocksearch/blocksearch-top.tpl',
      1 => 1557734604,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1220115985cd1e34519cc72-07296159',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cd1e3451bf2c5_36632053',
  'variables' => 
  array (
    'link' => 0,
    'search_query' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cd1e3451bf2c5_36632053')) {function content_5cd1e3451bf2c5_36632053($_smarty_tpl) {?><!-- Block search module TOP -->
<div class="searchblock">
	<div id="search_block_top" class="toog_le_content">
		<form id="searchbox" method="get" action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getPageLink('search',null,null,null,false,null,true), ENT_QUOTES, 'UTF-8', true);?>
" >
			<input type="hidden" name="controller" value="search" />
			<input type="hidden" name="orderby" value="position" />
			<input type="hidden" name="orderway" value="desc" />
			<input class="search_query form-control" type="text" id="search_query_top" name="search_query" placeholder="<?php echo smartyTranslate(array('s'=>'Trouver votre produit d\'occasion...','mod'=>'blocksearch'),$_smarty_tpl);?>
" value="<?php echo stripslashes(mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['search_query']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8'));?>
" />
			<button type="submit" name="submit_search" class="btn btn-default button-search">
				<span><?php echo smartyTranslate(array('s'=>'Trouver votre produit d\'occasion...','mod'=>'blocksearch'),$_smarty_tpl);?>
</span>
			</button>
		</form>
	</div>
</div>
<!-- /Block search module TOP --><?php }} ?>
