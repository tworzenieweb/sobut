<?php /* Smarty version Smarty-3.1.11, created on 2013-01-24 00:21:26
         compiled from "/home/tworzenieweb/www/goldenbody/modules/homecarousel/homecarousel.tpl" */ ?>
<?php /*%%SmartyHeaderCode:11691220295100707616b661-07993037%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '23516f02b2f3071e9c06ddf44d53ebbc131284cb' => 
    array (
      0 => '/home/tworzenieweb/www/goldenbody/modules/homecarousel/homecarousel.tpl',
      1 => 1358980929,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11691220295100707616b661-07993037',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'base_dir' => 0,
    'autoplay' => 0,
    'autoplayduration' => 0,
    'itemsvisible' => 0,
    'itemsscroll' => 0,
    'products' => 0,
    'product' => 0,
    'link' => 0,
    'homeSize' => 0,
    'displayname' => 0,
    'productLink' => 0,
    'displayprice' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5100707623f613_70493666',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5100707623f613_70493666')) {function content_5100707623f613_70493666($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_escape')) include '/home/tworzenieweb/www/goldenbody/tools/smarty/plugins/modifier.escape.php';
?><!--
  jCarousel library
-->
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['base_dir']->value;?>
modules/homecarousel/jcarousel/lib/jquery.jcarousel.pack.js"></script>
<!--
  jCarousel core stylesheet
-->
<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['base_dir']->value;?>
modules/homecarousel/jcarousel/lib/jquery.jcarousel.css" />
<!--
  jCarousel skin stylesheet
-->
<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['base_dir']->value;?>
modules/homecarousel/jcarousel/skins/tango/skin.css" />

<?php if ($_smarty_tpl->tpl_vars['autoplay']->value){?>
	<script type="text/javascript">
		var carousel_autoplay = <?php echo $_smarty_tpl->tpl_vars['autoplayduration']->value;?>
;
		var carousel_items_visible = <?php echo $_smarty_tpl->tpl_vars['itemsvisible']->value;?>
;
		var carousel_scroll = <?php echo $_smarty_tpl->tpl_vars['itemsscroll']->value;?>
;
	</script>
<?php }else{ ?>
	<script type="text/javascript">
		var carousel_autoplay = 0;
		var carousel_items_visible = <?php echo $_smarty_tpl->tpl_vars['itemsvisible']->value;?>
;
		var carousel_scroll = <?php echo $_smarty_tpl->tpl_vars['itemsscroll']->value;?>
;
	</script>
<?php }?>

<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['base_dir']->value;?>
modules/homecarousel/homecarousel.js"></script>
	
<!-- MODULE Home Featured Products -->
<?php if (isset($_smarty_tpl->tpl_vars['products']->value)&&$_smarty_tpl->tpl_vars['products']->value){?>
  <ul id="mycarousel" class="jcarousel-skin-tango">
	<?php  $_smarty_tpl->tpl_vars['product'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['product']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['products']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['product']->key => $_smarty_tpl->tpl_vars['product']->value){
$_smarty_tpl->tpl_vars['product']->_loop = true;
?>
		<?php $_smarty_tpl->tpl_vars['productLink'] = new Smarty_variable($_smarty_tpl->tpl_vars['link']->value->getProductLink($_smarty_tpl->tpl_vars['product']->value['id_product'],$_smarty_tpl->tpl_vars['product']->value['link_rewrite']), null, 0);?>
		<li>
			<a href="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['product']->value['link'], 'htmlall', 'UTF-8');?>
" class="product_img_link" title="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['product']->value['name'], 'htmlall', 'UTF-8');?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['link']->value->getImageLink($_smarty_tpl->tpl_vars['product']->value['link_rewrite'],$_smarty_tpl->tpl_vars['product']->value['id_image'],'home');?>
" alt="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['product']->value['legend'], 'htmlall', 'UTF-8');?>
" <?php if (isset($_smarty_tpl->tpl_vars['homeSize']->value)){?> width="<?php echo $_smarty_tpl->tpl_vars['homeSize']->value['width'];?>
" height="<?php echo $_smarty_tpl->tpl_vars['homeSize']->value['height'];?>
"<?php }?> /></a>	
			<?php if ($_smarty_tpl->tpl_vars['displayname']->value){?>		
				<h5><a href="<?php echo $_smarty_tpl->tpl_vars['productLink']->value;?>
" title="<?php echo $_smarty_tpl->tpl_vars['product']->value['name'];?>
"><?php echo smarty_modifier_truncate(smarty_modifier_escape($_smarty_tpl->tpl_vars['product']->value['name'], 'htmlall', 'UTF-8'),45);?>
</a></h5>													
			<?php }?>
			<?php if ($_smarty_tpl->tpl_vars['displayprice']->value){?>
				<p>					
					<span class="price"><?php echo Product::displayWtPrice(array('p'=>$_smarty_tpl->tpl_vars['product']->value['price']),$_smarty_tpl);?>
</span>					
				</p>
			<?php }?>
		</li>
	<?php } ?>
	</ul>
<?php }else{ ?>
	<p><?php echo smartyTranslate(array('s'=>'No products for carousel','mod'=>'homecarousel'),$_smarty_tpl);?>
</p>
<?php }?>
<!-- /MODULE Home Featured Products --><?php }} ?>