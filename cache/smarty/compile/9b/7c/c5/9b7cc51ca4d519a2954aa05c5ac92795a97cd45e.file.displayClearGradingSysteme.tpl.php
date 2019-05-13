<?php /* Smarty version Smarty-3.1.19, created on 2019-05-07 21:59:05
         compiled from "/home/sundevice/preprod/modules/cscustomize/views/templates/hook/displayClearGradingSysteme.tpl" */ ?>
<?php /*%%SmartyHeaderCode:19687562115cd1e3896e14c7-80218198%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9b7cc51ca4d519a2954aa05c5ac92795a97cd45e' => 
    array (
      0 => '/home/sundevice/preprod/modules/cscustomize/views/templates/hook/displayClearGradingSysteme.tpl',
      1 => 1534502024,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19687562115cd1e3896e14c7-80218198',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'listblocs' => 0,
    'base_dir' => 0,
    'item_value' => 0,
    'item_key' => 0,
    'id_element' => 0,
    'link' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5cd1e3896f6cb4_91621843',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cd1e3896f6cb4_91621843')) {function content_5cd1e3896f6cb4_91621843($_smarty_tpl) {?><div id="grading_systeme_link" class="block_graging_system_wrapper box-title">
	<div class="block_graging_system_container container main-parent">
		<div class="graging_system_description footer-block">
			<h3><?php echo smartyTranslate(array('s'=>'Clear grading systeme','mod'=>'cscustomize'),$_smarty_tpl);?>
</h3>
            <div class="toggle-footer">
			<p>
				<?php echo smartyTranslate(array('s'=>'We have created a simplified and clear cosmetic grading system
				that precisely defines the condition of each device.','mod'=>'cscustomize'),$_smarty_tpl);?>

			</p>
			
			<div class="graging_system_dropdown">
				  <div class="panel-group" id="accordion_graging_system">
					<?php  $_smarty_tpl->tpl_vars['item_value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item_value']->_loop = false;
 $_smarty_tpl->tpl_vars['item_key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['listblocs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['item_value']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['item_value']->key => $_smarty_tpl->tpl_vars['item_value']->value) {
$_smarty_tpl->tpl_vars['item_value']->_loop = true;
 $_smarty_tpl->tpl_vars['item_key']->value = $_smarty_tpl->tpl_vars['item_value']->key;
 $_smarty_tpl->tpl_vars['item_value']->index++;
 $_smarty_tpl->tpl_vars['item_value']->first = $_smarty_tpl->tpl_vars['item_value']->index === 0;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['grade']['first'] = $_smarty_tpl->tpl_vars['item_value']->first;
?>
					<div class="panel panel-default cs-item-parent" data-img-src="<?php echo $_smarty_tpl->tpl_vars['base_dir']->value;?>
modules/cscustomize/img/<?php echo $_smarty_tpl->tpl_vars['item_value']->value['nameimg'];?>
" data-img-desc="<?php echo $_smarty_tpl->tpl_vars['item_value']->value['secondtitle'];?>
">
					  <div class="panel-heading">
						<h4 class="panel-title">
						  <a class="<?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['grade']['first']) {?>active<?php }?>" data-toggle="collapse" data-parent="#accordion_graging_system" href="#graging_system_collapse<?php echo $_smarty_tpl->tpl_vars['item_key']->value;?>
">
							<span><?php echo $_smarty_tpl->tpl_vars['item_value']->value['titleblock'];?>
</span>
							<i class="fa fa-chevron-down" aria-hidden="true"></i>
						  </a>
						</h4>
					  </div>
					  <div id="graging_system_collapse<?php echo $_smarty_tpl->tpl_vars['item_key']->value;?>
" class="panel-collapse collapse <?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['grade']['first']) {?>in<?php }?>">
						<div class="panel-body"><?php echo $_smarty_tpl->tpl_vars['item_value']->value['editortext'];?>
</div>
					  </div>
					</div>
					<?php } ?>
				  </div> 
			</div>
            
            <div class="div-learn-more div-learn-more-left">
        		<a href="<?php if ((isset($_smarty_tpl->tpl_vars['id_element']->value)&&$_smarty_tpl->tpl_vars['id_element']->value)) {?><?php echo $_smarty_tpl->tpl_vars['link']->value->getCMSLink($_smarty_tpl->tpl_vars['id_element']->value);?>
<?php } else { ?>#<?php }?>" class="learn-more-action"><?php echo smartyTranslate(array('s'=>'Learn more','mod'=>'cscustomize'),$_smarty_tpl);?>
</a>
        	</div></div>
			
		</div>
		<div class="graging_system_img image-block toggle-footer">
			<?php  $_smarty_tpl->tpl_vars['item_value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item_value']->_loop = false;
 $_smarty_tpl->tpl_vars['item_key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['listblocs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['item_value']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['item_value']->key => $_smarty_tpl->tpl_vars['item_value']->value) {
$_smarty_tpl->tpl_vars['item_value']->_loop = true;
 $_smarty_tpl->tpl_vars['item_key']->value = $_smarty_tpl->tpl_vars['item_value']->key;
 $_smarty_tpl->tpl_vars['item_value']->index++;
 $_smarty_tpl->tpl_vars['item_value']->first = $_smarty_tpl->tpl_vars['item_value']->index === 0;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['grade_img']['first'] = $_smarty_tpl->tpl_vars['item_value']->first;
?>
			<p class="image-desc"><?php echo $_smarty_tpl->tpl_vars['item_value']->value['secondtitle'];?>
</p>
			<?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['grade_img']['first']) {?>
			<img class="img-responsive image-img" src="<?php echo $_smarty_tpl->tpl_vars['base_dir']->value;?>
modules/cscustomize/img/<?php echo $_smarty_tpl->tpl_vars['item_value']->value['nameimg'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['item_value']->value['secondtitle'];?>
"/>
			<?php }?>
			<?php break 1?>
			<?php } ?>
		</div>
	</div>
</div>
<div class="clear"></div><?php }} ?>
