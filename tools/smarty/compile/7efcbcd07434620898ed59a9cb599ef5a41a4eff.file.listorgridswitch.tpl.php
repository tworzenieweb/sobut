<?php /* Smarty version Smarty-3.1.11, created on 2013-02-17 21:53:22
         compiled from "/home/tworzenieweb/www/goldenbody/themes/presta103/modules/listorgridswitch/listorgridswitch.tpl" */ ?>
<?php /*%%SmartyHeaderCode:140687431351214342e982d5-10684404%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7efcbcd07434620898ed59a9cb599ef5a41a4eff' => 
    array (
      0 => '/home/tworzenieweb/www/goldenbody/themes/presta103/modules/listorgridswitch/listorgridswitch.tpl',
      1 => 1359759763,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '140687431351214342e982d5-10684404',
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
  'unifunc' => 'content_51214342ec4568_43809014',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51214342ec4568_43809014')) {function content_51214342ec4568_43809014($_smarty_tpl) {?><?php if ($_smarty_tpl->tpl_vars['page_name']->value!='manufacturer'){?>
<?php if ($_smarty_tpl->tpl_vars['cycle']->value=="prolog"){?>
  <!-- listorgridswitch begin PROLOG  -->
  <div class="listorgridswitch<?php if (isset($_smarty_tpl->tpl_vars['listorgridmode']->value)&&$_smarty_tpl->tpl_vars['listorgridmode']->value){?> lg_grid<?php }?>">
    <a href="#" class="switch_but<?php if (isset($_smarty_tpl->tpl_vars['listorgridmode']->value)&&$_smarty_tpl->tpl_vars['listorgridmode']->value){?> lg_grid<?php }?>"></a>
	<span><?php echo smartyTranslate(array('s'=>'Układ','mod'=>'listorgridswitch'),$_smarty_tpl);?>
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