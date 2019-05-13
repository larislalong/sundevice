<?php /* Smarty version Smarty-3.1.19, created on 2019-04-29 16:36:46
         compiled from "/home/sundevice/public_html/modules/menupro/views/templates/hook/footer_secondary_menu_mega.tpl" */ ?>
<?php /*%%SmartyHeaderCode:15790559425cc70bfe422838-48327313%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4a2af07435bf59090e2fa4642f0aaaf3461380fc' => 
    array (
      0 => '/home/sundevice/public_html/modules/menupro/views/templates/hook/footer_secondary_menu_mega.tpl',
      1 => 1501665696,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15790559425cc70bfe422838-48327313',
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
  'unifunc' => 'content_5cc70bfe42f7d0_46689389',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cc70bfe42f7d0_46689389')) {function content_5cc70bfe42f7d0_46689389($_smarty_tpl) {?>

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
