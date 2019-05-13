<?php /*%%SmartyHeaderCode:311665b659cf73c63c0-92303436%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9a1e73343d476e73411c063cca9929ae466b5217' => 
    array (
      0 => 'D:\\wamp\\www\\projects\\ps\\sun-device.local\\themes\\pos_ruby5\\modules\\blocksearch\\blocksearch-top.tpl',
      1 => 1532693237,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '311665b659cf73c63c0-92303436',
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5b659db62dd090_82615444',
  'has_nocache_code' => false,
  'cache_lifetime' => 31536000,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5b659db62dd090_82615444')) {function content_5b659db62dd090_82615444($_smarty_tpl) {?><!-- Block search module TOP -->
<div class="searchblock">
	<a class="icon_top current" href="javascript:void(0)" title="Menu">
		<i class="icon_search"></i>
	</a>
	<div id="search_block_top" class="toogle_content">
		<form id="searchbox" method="get" action="//sundevice.ps.local/recherche" >
			<input type="hidden" name="controller" value="search" />
			<input type="hidden" name="orderby" value="position" />
			<input type="hidden" name="orderway" value="desc" />
			<input class="search_query form-control" type="text" id="search_query_top" name="search_query" placeholder="Rechercher" value="" />
			<button type="submit" name="submit_search" class="btn btn-default button-search">
				<span>Rechercher</span>
			</button>
		</form>
	</div>
</div>
<!-- /Block search module TOP --><?php }} ?>
