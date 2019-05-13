<?php /* Smarty version Smarty-3.1.19, created on 2019-02-22 14:54:39
         compiled from "D:\wamp\www\projects\ps\sun-device.local\modules\menupro\views\templates\hook\footer_secondary_menu_mega.tpl" */ ?>
<?php /*%%SmartyHeaderCode:146715c6fff1fd64186-67938009%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '970825caee7ea669241fa4393d970bc4435ff142' => 
    array (
      0 => 'D:\\wamp\\www\\projects\\ps\\sun-device.local\\modules\\menupro\\views\\templates\\hook\\footer_secondary_menu_mega.tpl',
      1 => 1501665696,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '146715c6fff1fd64186-67938009',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'lastItems' => 0,
    'secondaryMenu' => 0,
    'htmlContentPositionsConst' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5c6fff1fe30958_09474583',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5c6fff1fe30958_09474583')) {function content_5c6fff1fe30958_09474583($_smarty_tpl) {?>

</li>
<?php if ($_smarty_tpl->tpl_vars['lastItems']->value) {?>
	<?php if ($_smarty_tpl->tpl_vars['secondaryMenu']->value['level']==2) {?>
			</ul>
				<?php if (isset($_smarty_tpl->tpl_vars['secondaryMenu']->value['html_contents'][$_smarty_tpl->tpl_vars['htmlContentPositionsConst']->value['RIGHT']])&&(!empty($_smarty_tpl->tpl_vars['secondaryMenu']->value['html_contents'][$_smarty_tpl->tpl_vars['htmlContentPositionsConst']->value['RIGHT']]))) {?>
				<div class="mp-image-right hidden-sm hidden-xs">
					<div class="mp-image-right_inner row">
						<div class="col-md-12">
							<?php echo $_smarty_tpl->tpl_vars['secondaryMenu']->value['html_contents'][$_smarty_tpl->tpl_vars['htmlContentPositionsConst']->value['RIGHT']];?>

						</div>
					</div>
				</div>
				<?php }?>
			</div>
			<?php if (isset($_smarty_tpl->tpl_vars['secondaryMenu']->value['html_contents'][$_smarty_tpl->tpl_vars['htmlContentPositionsConst']->value['DOWN']])&&(!empty($_smarty_tpl->tpl_vars['secondaryMenu']->value['html_contents'][$_smarty_tpl->tpl_vars['htmlContentPositionsConst']->value['DOWN']]))) {?>
			<div class="mp-image-bottom">
				<div class="mp-image-bottom_inner row">
					<div class="col-md-12">
						<?php echo $_smarty_tpl->tpl_vars['secondaryMenu']->value['html_contents'][$_smarty_tpl->tpl_vars['htmlContentPositionsConst']->value['DOWN']];?>

					</div>
				</div>
			</div>
			<?php }?>
		</div>
	<?php } else { ?>
		</ul>
	<?php }?>
<?php }?><?php }} ?>
