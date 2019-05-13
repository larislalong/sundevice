<?php /* Smarty version Smarty-3.1.19, created on 2019-05-02 09:55:55
         compiled from "/home/sundevice/public_html/modules/menupro/views/templates/admin/configure.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2438174915ccaa28bf40c99-95197696%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c7efa39a1bd118f196dcd54a099920fc0c6830fb' => 
    array (
      0 => '/home/sundevice/public_html/modules/menupro/views/templates/admin/configure.tpl',
      1 => 1511549306,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2438174915ccaa28bf40c99-95197696',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'usableValuesConst' => 0,
    'menuTypesConst' => 0,
    'valueResultConst' => 0,
    'mainMenuTypeConst' => 0,
    'propertiesTypesConst' => 0,
    'styleTypesConst' => 0,
    'linkTypesConst' => 0,
    'homeLinkList' => 0,
    'defaultModLanguage' => 0,
    'ajaxModuleUrl' => 0,
    'ps_version' => 0,
    'englishDocLink' => 0,
    'frenchDocLink' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5ccaa28c111af9_54296458',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5ccaa28c111af9_54296458')) {function content_5ccaa28c111af9_54296458($_smarty_tpl) {?>
<script type="text/javascript">
	var usableValuesConst = <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['json_encode'][0][0]->jsonEncode($_smarty_tpl->tpl_vars['usableValuesConst']->value);?>
;
	var menuTypesConst = <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['json_encode'][0][0]->jsonEncode($_smarty_tpl->tpl_vars['menuTypesConst']->value);?>
;
        var valueResultConst = <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['json_encode'][0][0]->jsonEncode($_smarty_tpl->tpl_vars['valueResultConst']->value);?>
;
	var mainMenuTypeConst = <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['json_encode'][0][0]->jsonEncode($_smarty_tpl->tpl_vars['mainMenuTypeConst']->value);?>
;
	var propertiesTypesConst = <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['json_encode'][0][0]->jsonEncode($_smarty_tpl->tpl_vars['propertiesTypesConst']->value);?>
;
	var styleTypesConst = <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['json_encode'][0][0]->jsonEncode($_smarty_tpl->tpl_vars['styleTypesConst']->value);?>
;
        var linkTypesConst = <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['json_encode'][0][0]->jsonEncode($_smarty_tpl->tpl_vars['linkTypesConst']->value);?>
;
        var homeLinkList = <?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['json_encode'][0][0]->jsonEncode($_smarty_tpl->tpl_vars['homeLinkList']->value);?>
;
        var defaultModLanguage = <?php echo intval($_smarty_tpl->tpl_vars['defaultModLanguage']->value);?>
;
	var ajaxModuleUrl = "<?php echo strtr($_smarty_tpl->tpl_vars['ajaxModuleUrl']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
";
	var ps_version = "<?php echo strtr($_smarty_tpl->tpl_vars['ps_version']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
";
	var ajaxRequestErrorMessage = "<?php echo smartyTranslate(array('s'=>'An error occurred while connecting to server','mod'=>'menupro'),$_smarty_tpl);?>
";
	var loaderPropertiesMessage = "<?php echo smartyTranslate(array('s'=>'Retrieving properties...','mod'=>'menupro'),$_smarty_tpl);?>
";
	var noRecordMessage = "<?php echo smartyTranslate(array('s'=>'No records found','mod'=>'menupro'),$_smarty_tpl);?>
";
	var enabledMessage = "<?php echo smartyTranslate(array('s'=>'Enabled','mod'=>'menupro'),$_smarty_tpl);?>
";
	var disabledMessage = "<?php echo smartyTranslate(array('s'=>'Disabled','mod'=>'menupro'),$_smarty_tpl);?>
";
        var loaderPropertyValueMessage = "<?php echo smartyTranslate(array('s'=>'Searching for value...','mod'=>'menupro'),$_smarty_tpl);?>
";
        var loaderGetStyleMessage = "<?php echo smartyTranslate(array('s'=>'Searching for style...','mod'=>'menupro'),$_smarty_tpl);?>
";
        var linkBaseDescription = "<?php echo smartyTranslate(array('s'=>'Base URL','mod'=>'menupro'),$_smarty_tpl);?>
";
</script>
<?php if ($_smarty_tpl->tpl_vars['ps_version']->value<'1.6') {?>
<div id="dialogModalParent">
	<div id="dialogLoader">
		<div class="loader"></div>
		<div class="loader-text"></div>
	</div>
</div>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?><div class="panel"><h3><i class="icon icon-tags"></i> <?php } else { ?><fieldset class="block-doc"><legend><?php }?>
	<?php echo smartyTranslate(array('s'=>'Documentation','mod'=>'menupro'),$_smarty_tpl);?>

<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?></h3><?php } else { ?></legend><?php }?>
	<p>
		&raquo; <?php echo smartyTranslate(array('s'=>'You can get a PDF documentation to configure this module','mod'=>'menupro'),$_smarty_tpl);?>
 :
		<ul>
			<li><a href="<?php echo strtr($_smarty_tpl->tpl_vars['englishDocLink']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
" target="_blank"><?php echo smartyTranslate(array('s'=>'English','mod'=>'menupro'),$_smarty_tpl);?>
</a></li>
			<li><a href="<?php echo strtr($_smarty_tpl->tpl_vars['frenchDocLink']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
" target="_blank"><?php echo smartyTranslate(array('s'=>'French','mod'=>'menupro'),$_smarty_tpl);?>
</a></li>
		</ul>
	</p>
<?php if ($_smarty_tpl->tpl_vars['ps_version']->value>='1.6') {?></div><?php } else { ?></fieldset><?php }?><?php }} ?>
