<?php /* Smarty version Smarty-3.1.11, created on 2013-02-08 21:09:31
         compiled from "/home/tworzenieweb/www/goldenbody/themes/presta103/breadcrumb.tpl" */ ?>
<?php /*%%SmartyHeaderCode:6207851251155b7bd00236-42311853%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '410a86dab3ce028022a61719c6b32811414a57d3' => 
    array (
      0 => '/home/tworzenieweb/www/goldenbody/themes/presta103/breadcrumb.tpl',
      1 => 1358980903,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6207851251155b7bd00236-42311853',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'base_dir' => 0,
    'path' => 0,
    'navigationPipe' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_51155b7bd39679_78018573',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51155b7bd39679_78018573')) {function content_51155b7bd39679_78018573($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_escape')) include '/home/tworzenieweb/www/goldenbody/tools/smarty/plugins/modifier.escape.php';
?>

<!-- Breadcrumb -->
<?php if (isset(Smarty::$_smarty_vars['capture']['path'])){?><?php $_smarty_tpl->tpl_vars['path'] = new Smarty_variable(Smarty::$_smarty_vars['capture']['path'], null, 0);?><?php }?>
<div class="breadcrumb">
	<a href="<?php echo $_smarty_tpl->tpl_vars['base_dir']->value;?>
" title="<?php echo smartyTranslate(array('s'=>'return to'),$_smarty_tpl);?>
 <?php echo smartyTranslate(array('s'=>'Home'),$_smarty_tpl);?>
"><?php echo smartyTranslate(array('s'=>'Home'),$_smarty_tpl);?>
</a><?php if (isset($_smarty_tpl->tpl_vars['path']->value)&&$_smarty_tpl->tpl_vars['path']->value){?><span class="navigation-pipe"><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['navigationPipe']->value, 'html', 'UTF-8');?>
</span><?php if (!strpos($_smarty_tpl->tpl_vars['path']->value,'span')){?><span class="navigation_page"><?php echo $_smarty_tpl->tpl_vars['path']->value;?>
</span><?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['path']->value;?>
<?php }?><?php }?>
</div>
<!-- /Breadcrumb --><?php }} ?>