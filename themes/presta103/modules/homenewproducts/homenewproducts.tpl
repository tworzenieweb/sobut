<!-- MODULE Home Featured Products -->
<div id="featured-products_block_center" class="block products_block">
	<h4>{l s='Nowości' od='homenewproducts'}<span class="see_all"><a href="{$link->getPageLink('new-products.php')}" title="{l s='Pokaż wszystkie' od='homenewproducts'}">{l s='Pokaż wszystkie' od='homenewproducts'}</a></span></h4>
    {if isset($new_products) AND $new_products}  
        <div class="block_content">

    		<ul >
    		{foreach from=$new_products item=product name=newProducts}
				<li class="ajax_block_product {if $smarty.foreach.newProducts.first}first_item{elseif $smarty.foreach.newProducts.last}last_item{else}item{/if} {if $smarty.foreach.newProducts.iteration%$nbItemsPerLine == 0}last_item_of_line{elseif $smarty.foreach.newProducts.iteration%$nbItemsPerLine == 1}clear{/if} {if $smarty.foreach.newProducts.iteration > ($smarty.foreach.newProducts.total - ($smarty.foreach.newProducts.total % $nbItemsPerLine))}last_line{/if}">
                                        <a href="{$product.link}" title="{$product.name|escape:html:'UTF-8'}" class="product_image"><img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home')}" height="{$homeSize.height}" width="{$homeSize.width}" alt="{$product.name|escape:html:'UTF-8'}" /></a>
					<h5><a href="{$product.link}" title="{$product.name|truncate:32:'...'|escape:'htmlall':'UTF-8'}">{$product.name|truncate:50:'...'|escape:'htmlall':'UTF-8'}</a></h5>
					<div class="product_desc">
                                            <a href="{$product.link}" title="{l s='More' mod='homefeatured'}">{$product.description_short|strip_tags|truncate:115:'...'}</a></div>
                                            
					
                                        
                                        
						{if $product.show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
                                                    <p class="price_container">
                                                    <span class="price">{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}</span>
                                                    </p>
                                               {else}<div style="height:21px;"></div>{/if}
                                                    
                                                <div class="featured-products-buttons">
						
						{if ($product.id_product_attribute == 0 OR (isset($add_prod_display) AND ($add_prod_display == 1))) AND $product.available_for_order AND !isset($restricted_country_mode) AND $product.minimal_quantity == 1 AND $product.customizable != 2 AND !$PS_CATALOG_MODE}
							{if ($product.quantity > 0 OR $product.allow_oosp)}
							<a class="exclusive ajax_add_to_cart_button" rel="ajax_id_product_{$product.id_product}" href="{$link->getPageLink('cart.php')}?qty=1&amp;id_product={$product.id_product}&amp;token={$static_token}&amp;add" title="{l s='Add to cart' mod='homefeatured'}">{l s='Add to cart' mod='homenewproducts'}</a>
							{else}
							<span class="exclusive">{l s='Add to cart' mod='homenewproducts'}</span>
							{/if}
						{else}
							<div style="height:23px;"></div>
						{/if}
                                      </div>
                </li>
    		{/foreach}
    		</ul>	
                <div class="clear"></div>
        </div>
	{else}
		<p>{l s='No new products at this time' mod='blocknewproducts'}</p>    
	{/if}
</div>
<!-- /MODULE Home Featured Products -->
