<?php /*%%SmartyHeaderCode:21435631845b91576df26629-28531495%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '07a307af8d8ba2493e5b8c98bef9b5490ec3e6ab' => 
    array (
      0 => '/home/sundevice/public_html/themes/default-bootstrap/modules/blocksearch/blocksearch-top.tpl',
      1 => 1530115312,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '21435631845b91576df26629-28531495',
  'variables' => 
  array (
    'link' => 0,
    'search_query' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5b91576df34b42_06058712',
  'cache_lifetime' => 31536000,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5b91576df34b42_06058712')) {function content_5b91576df34b42_06058712($_smarty_tpl) {?><!-- Block search module TOP -->
<div id="search_block_top" class="col-sm-4 clearfix">
	<form id="searchbox" method="get" action="//www.sun-device.com/recherche" >
		<input type="hidden" name="controller" value="search" />
		<input type="hidden" name="orderby" value="position" />
		<input type="hidden" name="orderway" value="desc" />
		<input class="search_query form-control" type="text" id="search_query_top" name="search_query" placeholder="Rechercher" value="" />
		<button type="submit" name="submit_search" class="btn btn-default button-search">
			<span>Rechercher</span>
		</button>
	</form>
</div>
<!-- /Block search module TOP -->
<?php }} ?>
