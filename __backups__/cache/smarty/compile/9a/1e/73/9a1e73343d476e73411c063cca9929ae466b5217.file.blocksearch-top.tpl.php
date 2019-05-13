<?php /* Smarty version Smarty-3.1.19, created on 2019-02-22 14:54:37
         compiled from "D:\wamp\www\projects\ps\sun-device.local\themes\pos_ruby5\modules\blocksearch\blocksearch-top.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2435c6fff1dbc0e95-25789927%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9a1e73343d476e73411c063cca9929ae466b5217' => 
    array (
      0 => 'D:\\wamp\\www\\projects\\ps\\sun-device.local\\themes\\pos_ruby5\\modules\\blocksearch\\blocksearch-top.tpl',
      1 => 1550147775,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2435c6fff1dbc0e95-25789927',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'link' => 0,
    'search_query' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c6fff1dc0bd54_52501762',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c6fff1dc0bd54_52501762')) {function content_5c6fff1dc0bd54_52501762($_smarty_tpl) {?><!-- Block search module TOP -->
<div class="searchblock">
	<div id="search_block_top" class="toog_le_content">
		<form id="searchbox" method="get" action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getPageLink('search',null,null,null,false,null,true), ENT_QUOTES, 'UTF-8', true);?>
" >
			<input type="hidden" name="controller" value="search" />
			<input type="hidden" name="orderby" value="position" />
			<input type="hidden" name="orderway" value="desc" />
			<input class="search_query form-control" type="text" id="search_query_top" name="search_query" placeholder="<?php echo smartyTranslate(array('s'=>'Trouver votre produit reconditionné...','mod'=>'blocksearch'),$_smarty_tpl);?>
" value="<?php echo stripslashes(mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['search_query']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8'));?>
" />
			<button type="submit" name="submit_search" class="btn btn-default button-search">
				<span><?php echo smartyTranslate(array('s'=>'Trouver votre produit reconditionné...','mod'=>'blocksearch'),$_smarty_tpl);?>
</span>
			</button>
		</form>
	</div>
</div>
<!-- /Block search module TOP --><?php }} ?>
