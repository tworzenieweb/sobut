<!--
  jCarousel library
-->
<script type="text/javascript" src="{$base_dir}modules/homecarousel/jcarousel/lib/jquery.jcarousel.pack.js"></script>
<!--
  jCarousel core stylesheet
-->
<link rel="stylesheet" type="text/css" href="{$base_dir}modules/homecarousel/jcarousel/lib/jquery.jcarousel.css" />
<!--
  jCarousel skin stylesheet
-->
<link rel="stylesheet" type="text/css" href="{$base_dir}modules/homecarousel/jcarousel/skins/tango/skin.css" />

{if $autoplay}
	<script type="text/javascript">
		var carousel_autoplay = {$autoplayduration};
		var carousel_items_visible = {$itemsvisible};
		var carousel_scroll = {$itemsscroll};
	</script>
{else}
	<script type="text/javascript">
		var carousel_autoplay = 0;
		var carousel_items_visible = {$itemsvisible};
		var carousel_scroll = {$itemsscroll};
	</script>
{/if}

<script type="text/javascript" src="{$base_dir}modules/homecarousel/homecarousel.js"></script>
	
<!-- MODULE Home Featured Products -->
{if isset($products) AND $products}
  <ul id="mycarousel" class="jcarousel-skin-tango">
	{foreach from=$products item=product name=homeFeaturedProducts}
		{assign var='productLink' value=$link->getProductLink($product.id_product, $product.link_rewrite)}
		<li>
			<a href="{$product.link|escape:'htmlall':'UTF-8'}" class="product_img_link" title="{$product.name|escape:'htmlall':'UTF-8'}"><img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home')}" alt="{$product.legend|escape:'htmlall':'UTF-8'}" {if isset($homeSize)} width="{$homeSize.width}" height="{$homeSize.height}"{/if} /></a>	
			{if $displayname}		
				<h5><a href="{$productLink}" title="{$product.name}">{$product.name|escape:htmlall:'UTF-8'|truncate:45}</a></h5>													
			{/if}
			{if $displayprice}
				<p>					
					<span class="price">{displayWtPrice p=$product.price}</span>					
				</p>
			{/if}
		</li>
	{/foreach}
	</ul>
{else}
	<p>{l s='No products for carousel' mod='homecarousel'}</p>
{/if}
<!-- /MODULE Home Featured Products -->