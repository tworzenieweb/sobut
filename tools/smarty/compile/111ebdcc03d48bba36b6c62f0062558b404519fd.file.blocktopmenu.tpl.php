<?php /* Smarty version Smarty-3.1.11, created on 2013-02-08 21:07:51
         compiled from "/home/tworzenieweb/www/goldenbody/modules/blocktopmenu/blocktopmenu.tpl" */ ?>
<?php /*%%SmartyHeaderCode:214242095151155b1701bfd2-43515438%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '111ebdcc03d48bba36b6c62f0062558b404519fd' => 
    array (
      0 => '/home/tworzenieweb/www/goldenbody/modules/blocktopmenu/blocktopmenu.tpl',
      1 => 1358980928,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '214242095151155b1701bfd2-43515438',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MENU' => 0,
    'MENU_SEARCH' => 0,
    'this_path' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_51155b170497b5_82510937',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51155b170497b5_82510937')) {function content_51155b170497b5_82510937($_smarty_tpl) {?>        <?php if ($_smarty_tpl->tpl_vars['MENU']->value!=''){?>
        </div>
				<!-- Menu -->
        <div class="sf-contener">
          <ul class="sf-menu">
            <?php echo $_smarty_tpl->tpl_vars['MENU']->value;?>

            <?php if ($_smarty_tpl->tpl_vars['MENU_SEARCH']->value){?>
            <li class="sf-search noBack" style="float:right">
              <form id="searchbox" action="search.php" method="get">
                <input type="hidden" value="position" name="orderby"/>
                <input type="hidden" value="desc" name="orderway"/>
                <input type="text" name="search_query" value="<?php if (isset($_GET['search_query'])){?><?php echo $_GET['search_query'];?>
<?php }else{ ?><?php echo smartyTranslate(array('s'=>'search...','mod'=>'blocktopmenu'),$_smarty_tpl);?>
<?php }?>" onfocus="javascript:if(this.value=='<?php echo smartyTranslate(array('s'=>'search...','mod'=>'blocktopmenu'),$_smarty_tpl);?>
')this.value='';" onblur="javascript:if(this.value=='')this.value='<?php echo smartyTranslate(array('s'=>'search...','mod'=>'blocktopmenu'),$_smarty_tpl);?>
';" /><p></p>
              </form>
            </li>
            <?php }?>
          </ul>
        <script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['this_path']->value;?>
js/hoverIntent.js"></script>
        <script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['this_path']->value;?>
js/superfish-modified.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['this_path']->value;?>
css/superfish-modified.css" media="screen">
				<!--/ Menu -->
        <?php }?>	<?php }} ?>