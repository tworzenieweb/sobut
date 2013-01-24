<?php /* Smarty version Smarty-3.1.11, created on 2013-01-25 00:18:04
         compiled from "/home/tworzenieweb/www/goldenbody/modules/listorgridswitch/listorgridswitch.tpl" */ ?>
<?php /*%%SmartyHeaderCode:10719727325101c12cad5ac0-09693811%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '63977bd64d53b4d7501a8e69c6565e7445011ee9' => 
    array (
      0 => '/home/tworzenieweb/www/goldenbody/modules/listorgridswitch/listorgridswitch.tpl',
      1 => 1358980929,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '10719727325101c12cad5ac0-09693811',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'page_name' => 0,
    'cycle' => 0,
    'listorgridmode' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5101c12cb02745_25103190',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5101c12cb02745_25103190')) {function content_5101c12cb02745_25103190($_smarty_tpl) {?><?php if ($_smarty_tpl->tpl_vars['page_name']->value!='manufacturer'){?>
<?php if ($_smarty_tpl->tpl_vars['cycle']->value=="prolog"){?>
  <!-- listorgridswitch begin PROLOG  -->
  <div class="listorgridswitch<?php if (isset($_smarty_tpl->tpl_vars['listorgridmode']->value)&&$_smarty_tpl->tpl_vars['listorgridmode']->value){?> lg_grid<?php }?>">
    <a href="#" class="switch_but<?php if (isset($_smarty_tpl->tpl_vars['listorgridmode']->value)&&$_smarty_tpl->tpl_vars['listorgridmode']->value){?> lg_grid<?php }?>"></a>
	<span><?php echo smartyTranslate(array('s'=>'Switch View','mod'=>'listorgridswitch'),$_smarty_tpl);?>
</span>
  </div>
  <div class="listorgridcanvas<?php if (isset($_smarty_tpl->tpl_vars['listorgridmode']->value)&&$_smarty_tpl->tpl_vars['listorgridmode']->value){?> lg_grid<?php }?>">
  <!-- listorgridswitch end PROLOG  -->
<?php }else{ ?>
  <!-- listorgridswitch begin EPILOG  -->
    <div style="clear: both"></div>
  </div>
  <!-- listorgridswitch end EPILOG  -->
<?php }?>
<?php }?><?php }} ?>