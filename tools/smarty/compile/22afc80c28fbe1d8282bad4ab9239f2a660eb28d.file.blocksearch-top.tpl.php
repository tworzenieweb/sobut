<?php /* Smarty version Smarty-3.1.11, created on 2013-02-08 21:07:50
         compiled from "/home/tworzenieweb/www/goldenbody/modules/blocksearch/blocksearch-top.tpl" */ ?>
<?php /*%%SmartyHeaderCode:168179963251155b16edc661-02204096%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '22afc80c28fbe1d8282bad4ab9239f2a660eb28d' => 
    array (
      0 => '/home/tworzenieweb/www/goldenbody/modules/blocksearch/blocksearch-top.tpl',
      1 => 1345823312,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '168179963251155b16edc661-02204096',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'link' => 0,
    'ENT_QUOTES' => 0,
    'instantsearch' => 0,
    'search_ssl' => 0,
    'cookie' => 0,
    'ajaxsearch' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_51155b16f34002_22005326',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51155b16f34002_22005326')) {function content_51155b16f34002_22005326($_smarty_tpl) {?>

<!-- Block search module TOP -->
<div id="search_block_top">

	<form method="get" action="<?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('search.php');?>
" id="searchbox">
		<p>
			<label for="search_query_top"><!-- image on background --></label>
			<input type="hidden" name="orderby" value="position" />
			<input type="hidden" name="orderway" value="desc" />
			<input class="search_query" type="text" id="search_query_top" name="search_query" value="<?php if (isset($_GET['search_query'])){?><?php echo stripslashes(htmlentities($_GET['search_query'],$_smarty_tpl->tpl_vars['ENT_QUOTES']->value,'utf-8'));?>
<?php }?>" />
			<input type="submit" name="submit_search" value="<?php echo smartyTranslate(array('s'=>'Search','mod'=>'blocksearch'),$_smarty_tpl);?>
" class="button" />
	</p>
	</form>
</div>
<?php if ($_smarty_tpl->tpl_vars['instantsearch']->value){?>
	<script type="text/javascript">
	// <![CDATA[
		
		function tryToCloseInstantSearch() {
			if ($('#old_center_column').length > 0)
			{
				$('#center_column').remove();
				$('#old_center_column').attr('id', 'center_column');
				$('#center_column').show();
				return false;
			}
		}
		
		instantSearchQueries = new Array();
		function stopInstantSearchQueries(){
			for(i=0;i<instantSearchQueries.length;i++) {
				instantSearchQueries[i].abort();
			}
			instantSearchQueries = new Array();
		}
		
		$("#search_query_top").keyup(function(){
			if($(this).val().length > 0){
			
				stopInstantSearchQueries();
				instantSearchQuery = $.ajax({
				url: '<?php if ($_smarty_tpl->tpl_vars['search_ssl']->value==1){?><?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('search.php',true);?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('search.php');?>
<?php }?>',
				data: 'instantSearch=1&id_lang=<?php echo $_smarty_tpl->tpl_vars['cookie']->value->id_lang;?>
&q='+encodeURIComponent($(this).val()),
				dataType: 'html',
				success: function(data){
					if($("#search_query_top").val().length > 0)
					{
						tryToCloseInstantSearch();
						$('#center_column').attr('id', 'old_center_column');
						$('#old_center_column').after('<div id="center_column">'+data+'</div>');
						$('#old_center_column').hide();
						$("#instant_search_results a.close").click(function() {
							$("#search_query_top").val('');
							return tryToCloseInstantSearch();
						});
						return false;
					}
					else
						tryToCloseInstantSearch();
					}
				});
				instantSearchQueries.push(instantSearchQuery);
			}
			else
				tryToCloseInstantSearch();
		});
	// ]]>
	
	</script>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['ajaxsearch']->value){?>
	<script type="text/javascript">
	// <![CDATA[
	
		$('document').ready( function() {
			$("#search_query_top")
				.autocomplete(
					'<?php if ($_smarty_tpl->tpl_vars['search_ssl']->value==1){?><?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('search.php',true);?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['link']->value->getPageLink('search.php');?>
<?php }?>', {
						minChars: 3,
						max: 10,
						width: 500,
						selectFirst: false,
						scroll: false,
						dataType: "json",
						formatItem: function(data, i, max, value, term) {
							return value;
						},
						parse: function(data) {
							var mytab = new Array();
							for (var i = 0; i < data.length; i++)
								mytab[mytab.length] = { data: data[i], value: data[i].cname + ' > ' + data[i].pname };
							return mytab;
						},
						extraParams: {
							ajaxSearch: 1,
							id_lang: <?php echo $_smarty_tpl->tpl_vars['cookie']->value->id_lang;?>

						}
					}
				)
				.result(function(event, data, formatted) {
					$('#search_query_top').val(data.pname);
					document.location.href = data.product_link;
				})
		});
	
	// ]]>
	</script>
<?php }?>
<!-- /Block search module TOP --><?php }} ?>