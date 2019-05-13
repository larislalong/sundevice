<?php /* Smarty version Smarty-3.1.19, created on 2019-04-29 16:37:52
         compiled from "/home/sundevice/public_html/modules/cscustomize/views/templates/hook/displayPackeging.tpl" */ ?>
<?php /*%%SmartyHeaderCode:17084104365cc70c40492891-48633832%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f6b74e9c4afa1102d1c0db0c324fc5d147966af9' => 
    array (
      0 => '/home/sundevice/public_html/modules/cscustomize/views/templates/hook/displayPackeging.tpl',
      1 => 1534502024,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17084104365cc70c40492891-48633832',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'listblocs' => 0,
    'base_dir' => 0,
    'item_value' => 0,
    'index' => 0,
    'item_key' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cc70c404a6313_06651917',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cc70c404a6313_06651917')) {function content_5cc70c404a6313_06651917($_smarty_tpl) {?><div id="packaging_link" class="block_packaging_wrapper">
	<div class="block_packaging_container container main-parent">
		<div class="packaging_description">
			<h3><?php echo smartyTranslate(array('s'=>'Packaging','mod'=>'cscustomize'),$_smarty_tpl);?>
</h3>
			<p>
				<?php echo smartyTranslate(array('s'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
				eiusmod tempor incididunt ut labore et dolore magna aliqua.','mod'=>'cscustomize'),$_smarty_tpl);?>

			</p>
			
			<div class="packaging_dropdown">
				  <div class="panel-group" id="accordion_packading">
				  <?php $_smarty_tpl->tpl_vars['index'] = new Smarty_variable(1, null, 0);?>
				  <?php  $_smarty_tpl->tpl_vars['item_value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item_value']->_loop = false;
 $_smarty_tpl->tpl_vars['item_key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['listblocs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item_value']->key => $_smarty_tpl->tpl_vars['item_value']->value) {
$_smarty_tpl->tpl_vars['item_value']->_loop = true;
 $_smarty_tpl->tpl_vars['item_key']->value = $_smarty_tpl->tpl_vars['item_value']->key;
?>
					<div class="panel panel-default cs-item-parent" data-img-src="<?php echo $_smarty_tpl->tpl_vars['base_dir']->value;?>
modules/cscustomize/img/<?php echo $_smarty_tpl->tpl_vars['item_value']->value['nameimg'];?>
" data-img-desc="<?php echo $_smarty_tpl->tpl_vars['item_value']->value['secondtitle'];?>
">
					  <div class="panel-heading">
						<h4 class="panel-title">
						  <a class="<?php if ($_smarty_tpl->tpl_vars['index']->value==2) {?>active<?php }?>" data-toggle="collapse" data-parent="#accordion_packading" href="#packaging_collapse<?php echo $_smarty_tpl->tpl_vars['item_key']->value;?>
">
							<span><?php echo $_smarty_tpl->tpl_vars['item_value']->value['titleblock'];?>
</span>
							<i class="fa fa-chevron-down" aria-hidden="true"></i>
						  </a>
						</h4>
					  </div>
					  <div id="packaging_collapse<?php echo $_smarty_tpl->tpl_vars['item_key']->value;?>
" class="panel-collapse collapse <?php if ($_smarty_tpl->tpl_vars['index']->value==2) {?>in<?php }?>">
						<div class="panel-body"><?php echo $_smarty_tpl->tpl_vars['item_value']->value['editortext'];?>
</div>
					  </div>
					</div>
					<?php $_smarty_tpl->tpl_vars['index'] = new Smarty_variable($_smarty_tpl->tpl_vars['index']->value+1, null, 0);?>
					<?php } ?>
				  </div> 
			</div>
            
            <div class="div-learn-more div-learn-more-left">
        		<a href="#" class="learn-more-action"><?php echo smartyTranslate(array('s'=>'Learn more','mod'=>'cscustomize'),$_smarty_tpl);?>
</a>
        	</div>
			
		</div>
		<div class="packaging_img image-block">
			<?php $_smarty_tpl->tpl_vars['index'] = new Smarty_variable(1, null, 0);?>
			<?php  $_smarty_tpl->tpl_vars['item_value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item_value']->_loop = false;
 $_smarty_tpl->tpl_vars['item_key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['listblocs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item_value']->key => $_smarty_tpl->tpl_vars['item_value']->value) {
$_smarty_tpl->tpl_vars['item_value']->_loop = true;
 $_smarty_tpl->tpl_vars['item_key']->value = $_smarty_tpl->tpl_vars['item_value']->key;
?>
			<?php if ($_smarty_tpl->tpl_vars['index']->value==2) {?>
			<img class="img-responsive image-img" src="<?php echo $_smarty_tpl->tpl_vars['base_dir']->value;?>
modules/cscustomize/img/<?php echo $_smarty_tpl->tpl_vars['item_value']->value['nameimg'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['item_value']->value['secondtitle'];?>
"/>
			<?php break 1?>
			<?php }?>
			<?php $_smarty_tpl->tpl_vars['index'] = new Smarty_variable($_smarty_tpl->tpl_vars['index']->value+1, null, 0);?>
			<?php } ?>
		</div>
	</div>
</div>
<div class="clear"></div><?php }} ?>
