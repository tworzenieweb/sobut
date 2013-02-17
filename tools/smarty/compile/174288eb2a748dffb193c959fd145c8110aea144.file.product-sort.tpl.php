<?php /* Smarty version Smarty-3.1.11, created on 2013-02-17 21:53:22
         compiled from "/home/tworzenieweb/www/goldenbody/themes/presta103/product-sort.tpl" */ ?>
<?php /*%%SmartyHeaderCode:202671736451214342d7a8f5-76228972%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '174288eb2a748dffb193c959fd145c8110aea144' => 
    array (
      0 => '/home/tworzenieweb/www/goldenbody/themes/presta103/product-sort.tpl',
      1 => 1361133669,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '202671736451214342d7a8f5-76228972',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'clorg' => 0,
    'orderby' => 0,
    'orderway' => 0,
    'category' => 0,
    'link' => 0,
    'manufacturer' => 0,
    'supplier' => 0,
    'request' => 0,
    'products' => 0,
    'manufacturers' => 0,
    'ignore' => 0,
    'manufacturer_sort' => 0,
    'orderbydefault' => 0,
    'orderwaydefault' => 0,
    'PS_CATALOG_MODE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_51214342e92e06_55025990',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51214342e92e06_55025990')) {function content_51214342e92e06_55025990($_smarty_tpl) {?><?php if (!is_callable('smarty_function_cycle')) include '/home/tworzenieweb/www/goldenbody/tools/smarty/plugins/function.cycle.php';
if (!is_callable('smarty_modifier_escape')) include '/home/tworzenieweb/www/goldenbody/tools/smarty/plugins/modifier.escape.php';
?><?php echo smarty_function_cycle(array('name'=>"clorg",'values'=>"prolog,epilog",'assign'=>"clorg"),$_smarty_tpl);?>
<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['lorg_tpldir']->value)."./modules/listorgridswitch/listorgridswitch.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('cycle'=>$_smarty_tpl->tpl_vars['clorg']->value), 0);?>



<?php if (isset($_smarty_tpl->tpl_vars['orderby']->value)&&isset($_smarty_tpl->tpl_vars['orderway']->value)){?>
<!-- Sort products -->
<?php if (isset($_GET['id_category'])&&$_GET['id_category']){?>
	<?php $_smarty_tpl->tpl_vars['request'] = new Smarty_variable($_smarty_tpl->tpl_vars['link']->value->getPaginationLink('category',$_smarty_tpl->tpl_vars['category']->value,false,true), null, 0);?>
<?php }elseif(isset($_GET['id_manufacturer'])&&$_GET['id_manufacturer']){?>
	<?php $_smarty_tpl->tpl_vars['request'] = new Smarty_variable($_smarty_tpl->tpl_vars['link']->value->getPaginationLink('manufacturer',$_smarty_tpl->tpl_vars['manufacturer']->value,false,true), null, 0);?>
<?php }elseif(isset($_GET['id_supplier'])&&$_GET['id_supplier']){?>
	<?php $_smarty_tpl->tpl_vars['request'] = new Smarty_variable($_smarty_tpl->tpl_vars['link']->value->getPaginationLink('supplier',$_smarty_tpl->tpl_vars['supplier']->value,false,true), null, 0);?>
<?php }else{ ?>
	<?php $_smarty_tpl->tpl_vars['request'] = new Smarty_variable($_smarty_tpl->tpl_vars['link']->value->getPaginationLink(false,false,false,true), null, 0);?>
<?php }?>

<script type="text/javascript">
//<![CDATA[

$(document).ready(function()
{
	$('#selectProductSort').change(function()
	{
		var requestSortProducts = '<?php echo $_smarty_tpl->tpl_vars['request']->value;?>
';
		var splitData = $(this).val().split(':');
		document.location.href = requestSortProducts + ((requestSortProducts.indexOf('?') < 0) ? '?' : '&') + 'orderby=' + splitData[0] + '&orderway=' + splitData[1];
	});
});
//]]>

</script>

<form id="productsSortForm" action="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['request']->value, 'htmlall', 'UTF-8');?>
">
    <div class="spacer10"></div>  
            <?php if ($_smarty_tpl->tpl_vars['products']->value>0&&isset($_smarty_tpl->tpl_vars['manufacturers']->value)&&!isset($_smarty_tpl->tpl_vars['ignore']->value)){?>         
            <div class="filterManufacturer">
                <label for="selectManufacturerSort"><?php echo smartyTranslate(array('s'=>'filtruj producenta'),$_smarty_tpl);?>
</label>
                <select id="selectManufacturerSort" onchange="document.location.href = $(this).val();">   
                <option value="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['link']->value->addSortDetails($_smarty_tpl->tpl_vars['request']->value,'manufacturer_name','0'), 'htmlall', 'UTF-8');?>
"><?php echo smartyTranslate(array('s'=>'--'),$_smarty_tpl);?>
</option>

                    
                    <?php  $_smarty_tpl->tpl_vars['manufacturer_sort'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['manufacturer_sort']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['manufacturers']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['manufacturer_sort']->key => $_smarty_tpl->tpl_vars['manufacturer_sort']->value){
$_smarty_tpl->tpl_vars['manufacturer_sort']->_loop = true;
?>
                        <option value="<?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['manufacturer_sort']->value['id_manufacturer'];?>
<?php $_tmp1=ob_get_clean();?><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['link']->value->addSortDetails($_smarty_tpl->tpl_vars['request']->value,'manufacturer_name',$_tmp1), 'htmlall', 'UTF-8');?>
" <?php if ($_smarty_tpl->tpl_vars['orderby']->value=='manufacturer_name'&&$_smarty_tpl->tpl_vars['orderway']->value==$_smarty_tpl->tpl_vars['manufacturer_sort']->value['id_manufacturer']){?>selected="selected"<?php }?>><?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['manufacturer_sort']->value['manufacturers'], 'htmlall', 'UTF-8');?>
</option>
                    <?php } ?>
                  

                </select>
        
        </div>
    <?php }?>
    <div class="sortProduct">
                <label for="selectProductSort"><?php echo smartyTranslate(array('s'=>'Sort by'),$_smarty_tpl);?>
</label>
		<select id="selectProductSort">
			<option value="<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['orderbydefault']->value, 'htmlall', 'UTF-8');?>
:<?php echo smarty_modifier_escape($_smarty_tpl->tpl_vars['orderwaydefault']->value, 'htmlall', 'UTF-8');?>
" <?php if ($_smarty_tpl->tpl_vars['orderby']->value==$_smarty_tpl->tpl_vars['orderbydefault']->value){?>selected="selected"<?php }?>><?php echo smartyTranslate(array('s'=>'--'),$_smarty_tpl);?>
</option>
			<?php if (!$_smarty_tpl->tpl_vars['PS_CATALOG_MODE']->value){?>
				<option value="price:asc" <?php if ($_smarty_tpl->tpl_vars['orderby']->value=='price'&&$_smarty_tpl->tpl_vars['orderway']->value=='asc'){?>selected="selected"<?php }?>><?php echo smartyTranslate(array('s'=>'Price: lowest first'),$_smarty_tpl);?>
</option>
				<option value="price:desc" <?php if ($_smarty_tpl->tpl_vars['orderby']->value=='price'&&$_smarty_tpl->tpl_vars['orderway']->value=='desc'){?>selected="selected"<?php }?>><?php echo smartyTranslate(array('s'=>'Price: highest first'),$_smarty_tpl);?>
</option>
			<?php }?>
			<option value="name:asc" <?php if ($_smarty_tpl->tpl_vars['orderby']->value=='name'&&$_smarty_tpl->tpl_vars['orderway']->value=='asc'){?>selected="selected"<?php }?>><?php echo smartyTranslate(array('s'=>'Product Name: A to Z'),$_smarty_tpl);?>
</option>
			<option value="name:desc" <?php if ($_smarty_tpl->tpl_vars['orderby']->value=='name'&&$_smarty_tpl->tpl_vars['orderway']->value=='desc'){?>selected="selected"<?php }?>><?php echo smartyTranslate(array('s'=>'Product Name: Z to A'),$_smarty_tpl);?>
</option>
			<?php if (!$_smarty_tpl->tpl_vars['PS_CATALOG_MODE']->value){?>
				<option value="quantity:desc" <?php if ($_smarty_tpl->tpl_vars['orderby']->value=='quantity'&&$_smarty_tpl->tpl_vars['orderway']->value=='desc'){?>selected="selected"<?php }?>><?php echo smartyTranslate(array('s'=>'In-stock first'),$_smarty_tpl);?>
</option>
			<?php }?>
		</select>
		
	</div>
                <div class="clear"></div>
</form>
<!-- /Sort products -->
<?php }?><?php }} ?>