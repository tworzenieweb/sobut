{if $page_name != manufacturer}
{if $cycle == "prolog"}
  <!-- listorgridswitch begin PROLOG  -->
  <div class="listorgridswitch{if isset($listorgridmode) AND $listorgridmode} lg_grid{/if}">
    <a href="#" class="switch_but{if isset($listorgridmode) AND $listorgridmode} lg_grid{/if}"></a>
	<span>{l s='Switch View' mod='listorgridswitch'}</span>
  </div>
  <div class="listorgridcanvas{if isset($listorgridmode) AND $listorgridmode} lg_grid{/if}">
  <!-- listorgridswitch end PROLOG  -->
{else}
  <!-- listorgridswitch begin EPILOG  -->
    <div style="clear: both"></div>{* force block size while contain floats *}
  </div>
  <!-- listorgridswitch end EPILOG  -->
{/if}
{/if}