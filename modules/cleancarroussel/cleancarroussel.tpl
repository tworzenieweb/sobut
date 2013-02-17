<!-- Module Editorial -->
<script>
	{literal}
		var vars = {
			timeOut : {/literal}{$changeSpeed}{literal}
		}
		
		$(document).ready(function() {
			$('#cleancaroussel_wrap').s3Slider(vars);
		});
	{/literal}
</script>
{assign var="outerHeight" value=$height+20}
<div id="cleancaroussel_wrap" style="width:{$width}px;height:{$outerHeight}px">
	<ul class="sliderContent" style="width:{$width}px;height:{$height}px;">
    	{foreach from=$destaques item=destaque name=destaques}
            {if $destaque.logo}
            	{assign var=current value=$smarty.foreach.destaques.index}
                <li class="sliderImage">
					<a href="{$destaque.logo_link}" title="{$destaque.logo_title}">
						<img src="{$this_path}carroussel_{$current}.jpg" alt="{$destaque.logo_title}" />
					</a>
                </li> <!-- Fim do #destaque1 -->
            {/if}
        {/foreach}
	</ul>
	<ul	class="sliderMenu">
	    {foreach from=$destaques item=destaque name=destaques}
			{if $destaque.logo}
                {assign var=current value=$smarty.foreach.destaques.index}
                <li>
                    <a href="#" title="{$destaque.logo_title}" id="slidermenuitem{$current}"></a>
                </li>
			{/if}
		{/foreach}
    </ul>
</div>
<div class="clear"></div>
<!-- Fim do #editorial_block_center -->
