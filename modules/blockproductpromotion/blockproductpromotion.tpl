<!-- MODULE Block product promotion -->

<div class="block product_promotion">

    <h4>
        <a href="{$link->getProductLink($product->id, $product->link_rewrite, $product->category_rewrite)}">
            {$product->name|escape:html:'UTF-8'|truncate:22:'...'}
        </a>
    </h4>

    <div class="block_content">

        <a href="{$link->getProductLink($product->id, $product->link_rewrite, $product->category_rewrite)}">
            <img src="{$link->getImageLink($product->link_rewrite, $product->cover, 'home')}"
                alt="{$product->legend|escape:html:'UTF-8'}" />
        </a>

        <p>
            <a href="{$link->getProductLink($product->id, $product->link_rewrite, $product->category_rewrite)}">
                {$product->description_short}
            </a>
        </p>

    </div>

</div>

<!-- /MODULE Block product promotion -->
