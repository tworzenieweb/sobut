<?php /* Smarty version Smarty-3.1.11, created on 2013-02-17 21:52:44
         compiled from "/home/tworzenieweb/www/goldenbody/themes/presta103/footer.tpl" */ ?>
<?php /*%%SmartyHeaderCode:10665622785121431ca43617-58979704%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3ee81962a7a6814d37baab978027ecceebf96070' => 
    array (
      0 => '/home/tworzenieweb/www/goldenbody/themes/presta103/footer.tpl',
      1 => 1358980903,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '10665622785121431ca43617-58979704',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'content_only' => 0,
    'HOOK_RIGHT_COLUMN' => 0,
    'HOOK_FOOTER' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5121431ca50756_44128664',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5121431ca50756_44128664')) {function content_5121431ca50756_44128664($_smarty_tpl) {?>

		<?php if (!$_smarty_tpl->tpl_vars['content_only']->value){?>
				</div>

<!-- Right -->
				<div id="right_column" class="column">
					<?php echo $_smarty_tpl->tpl_vars['HOOK_RIGHT_COLUMN']->value;?>

				</div>
			</div>

<!-- Footer -->
			<div id="footer"><?php echo $_smarty_tpl->tpl_vars['HOOK_FOOTER']->value;?>
</div>
		</div>
	<?php }?>
	</body>
</html>
<?php }} ?>