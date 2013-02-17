<script type="text/javascript" src="{$module_dir}blocklangcurr.js"></script>
<!-- Block langcurr module -->
<div id="blocklangcurr">
	<form id="setLanguage" action="{$request_uri}" method="post">
    <select name="selectlanguage" id="selectlanguage" onchange="setLanguage(this.value)">    
		{foreach from=$languages key=k item=language name="languages"}
        <option value="{$language.id_lang}" {if $language.iso_code == $lang_iso} selected {/if} >{$language.name}</option>
		{/foreach}
        </select>
        <input type="hidden" name="id_lang" id="id_lang" value=""/>
        <input type="hidden" name="SubmitCurrency" value="" />
    </form>        

	<form id="setCurrency" action="{$request_uri}" method="post">
     <select onchange="setCurrency(this.value)">
        {foreach from=$currencies key=k item=f_currency}
        <option value="{$f_currency.id_currency}" {if $id_currency_cookie == $f_currency.id_currency} selected{/if}>
        {$f_currency.name}
        </option>
        {/foreach}
        </select>
        <input type="hidden" name="id_currency" id="id_currency" value=""/>
        <input type="hidden" name="SubmitCurrency" value="" />
	</form>        
</div>
<!-- /Block langcurr module -->