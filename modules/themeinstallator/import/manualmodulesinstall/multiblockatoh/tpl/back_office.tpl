
{*
Copyright (C) 2011-2012 phrasespot, phrasespot@gmail.com

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*}
<!-- multiblockatoh -->
{literal}
    <link type="text/css" rel="stylesheet" href="{/literal}{$module_dir}{literal}css/mbatoh.css" />
    <!--[if lte IE 7]>
    <style type="text/css">
        #multiblockatoh h2 a {position:relative; height:1%}
    </style>
    <![endif]-->
    <script type="text/javascript" src="{/literal}{$module_dir}{literal}js/expand.js"></script>
    <!--[if lte IE 6]>
    <script type="text/javascript">//<![CDATA[
        try {document.execCommand( "BackgroundImageCache", false, true);} catch(e) {/*ignore*/};
    //]]>
    </script>
    <![endif]-->
    <script type="text/javascript">//<![CDATA[
    $(function(){$("#multiblockatoh h2.expand").toggler();});
    $(function(){$("#multiblockatoh #block_hook").change(function(){
            var str = "Insert content in ";
            $("#multiblockatoh #block_hook option:selected").each(function(){
                str += $(this).text().toLowerCase();});
            $("#multiblockatoh #block_hook_help").text(str);}).trigger("change");});
    //]]>
    </script>
{/literal}
<div id="multiblockatoh">
    {if isset($submitResult)}
        {$submitResult}
    {/if}
    <h2 class="expand"><img class="icon" src="{$assignedVars.icons.module}" />{l s='Multi Block Arbitrary Text Or HTML' mod='multiblockatoh'}</h2>
    <div class="collapse shown">
        <p>{l s='This module allows you to insert arbitrary text or HTML content into almost 35 places at the front and back office.' mod='multiblockatoh'}</p>
        <p><strong>Version</strong>: {$assignedVars.version} - <a href="{$assignedVars.links.update}">Check for update</a></p>
    </div>
    <div>
        <div id="side-includes">
            {include file="{$assignedVars.includes.donateTpl}"}
            {include file="{$assignedVars.includes.contactTpl}"}
        </div>
        <form action="{$assignedVars.links.self}" method="post">
            <fieldset>
                <legend>{l s='Add or edit a block' mod='multiblockatoh'}</legend>
                <div class="clear">&nbsp;</div>
                {if empty($assignedVars.hook.remaining)}
                    <p>{l s='All available hooks are assigned a block. ' mod='multiblockatoh'}</p>
                    <p>You can use <a href="{$assignedVars.links.positionsTab}">Positions tab</a> to delete or unhook some and then try again.</p>
                {else}
                    <div>
                        <label>{l s='Block position' mod='multiblockatoh'}</label>
                        {if $assignedVars.hook.editing}
                            <input type="hidden" name="id-block" value="{$assignedVars.hook.editHook->id_mbatoh_block}" />
                            <input type="hidden" name="block_hook" value="{$assignedVars.hook.editHook->mbatoh_block_hook}" />
                            <input type="text" value="{$assignedVars.hook.editHook->mbatoh_block_hook}" disabled="disabled" />
                        {else}
                            <select id="block_hook" name="block_hook">
                                {foreach $assignedVars.hook.remaining as $hookName=>$niceName}
                                    <option value="{$hookName}">{$niceName}</option>
                                {/foreach}
                            </select>
                            <p class="small field-help" id="block_hook_help">&nbsp;</p>
                        {/if}
                    </div>
                    <div class="clear">&nbsp;</div>
                    <div>
                        <label>{l s='Block body' mod='multiblockatoh'}</label>
                        {foreach $assignedVars.lang.all as $lang}
                            <div id="block_body_wrapper_{$lang.id_lang}" name="block_body_wrapper_{$lang.id_lang}" {if $lang.id_lang == $assignedVars.lang.default}class="defaultlanguage"{else}class="otherlanguage"{/if}>
                                <textarea cols="75" rows="13" id="block_body_{$lang.id_lang}" name="block_body_{$lang.id_lang}">{if $assignedVars.hook.editing}{$assignedVars.hook.editHook->mbatoh_block_body[$lang.id_lang]}{/if}</textarea>
                            </div>
                        {/foreach}
                        {$assignedVars.lang.flags}
                        <p class="small field-help">{l s='Can be text or HTML, required field. No structure or styling is added.' mod='multiblockatoh'}</p>
                        <div class="clear">&nbsp;</div>
                    </div>
                    <input class="button" type="submit" id="save-block" name="save-block" value="{l s='Save' mod='multiblockatoh'}"></input>
                {/if}
            </fieldset>
        </form>
        {if $assignedVars.hook.hooked|count > 0}
            <div class="clear">&nbsp;</div>
            <fieldset>
                <legend>{l s='Used blocks' mod='multiblockatoh'}</legend>
                <table id="blocks-table" class="table">
                    <thead>
                        <tr class="center">
                            <th>{l s='Block identifier' mod='multiblockatoh'}</th>
                            <th>{l s='Block description' mod='multiblockatoh'}</th>
                            <th>{l s='Operations' mod='multiblockatoh'}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach $assignedVars.hook.hooked as $row}
                            <tr class="center {cycle values="alt_row,"}">
                                <td>{$row['mbatoh_block_hook']}</td>
                                <td>{$row['desc']}</td>
                                <td>
                                    <form action="{$assignedVars.links.self}" method="post">
                                        <input type="hidden" name="id-block" value="{$row['id_mbatoh_block']}" />
                                        <input class="button" name="edit-block" value="{l s='Edit' mod='multiblockatoh'}" type="submit" />
                                        &nbsp;<input class="button" name="delete-block" value="{l s='Delete' mod='multiblockatoh'}" type="submit" onclick="return confirm('{l s='Delete this block?' mod='multiblockatoh'}');" />
                                    </form>
                                </td>
                            </tr>
                        {/foreach}
                    </tbody>
                </table>
            </fieldset>
        {/if}
    </div>
    <div class="clear">&nbsp;</div>
    <h2 class="expand"><img class="icon" src="{$assignedVars.icons.usage}" />{l s='Usage' mod='multiblockatoh'}</h2>
    <div class="collapse">
        <h3>Adding a block</h3>
        <p>To insert a block, select the position into which you want to insert the block from the <strong>Block position</strong> drop-down field. Enter the content you want to appear in the selected position into the <strong>Block body</strong> field. If your shop is in more than one language and you want the displayed block to change with changing language, click on the flag, select the language from the expanding flag box and re-enter the content in that language (if you leave any language blank, the default language content will be displayed when blocks are viewed in the language that was left empty). Once you finish entering the content press the <strong>Save</strong> button. The block is now saved and should appear at its position.</p>
        <p>You can view <a href="http://sites.google.com/site/phrasespot/home/modules/insert-anywhere/screen-captures">screen captures</a> showing every position where a block can be added.</p>
        <h3>Removing/editing a block</h3>
        <p>Block already used will be displayed in <em>Used blocks</em> table (this table only appears when at least one block is used).Use the relevant button under <em>Operations</em> column to edit or delete a block.</p>
        <p><em>One word of caution</em>, do not try to modify the position of the blocks you created with <strong>LiveEdit</strong>, or using <strong>Transplant a module</strong> features. Prestashop assumes that each module has a single presence. If you use LiveEdit or Transplant, the behaviour of this module and created blocks is undefined.</p>
        <p>There can be only one block for each position.</p>
        <h3>Troubleshooting</h3>
        <p>If your block does not appear, or appears wrong, there are few things you can do/check.</p>
        <ul>
            <li>Did you select the position you thought you did from the drop-down field? Occupied positions do not show in the drop-down.</li>
            <li>Does the block you created show in <a href="{$assignedVars.links.positionsTab}">Positions tab</a>?</li>
            <li>Does your CSS styling clash with Prestashop's native CSS?</li>
            <li>If you entered HTML as content is your HTML free of errros?</li>
            <li>Did you try deleting and recreating the block?</li>
            <li>Did you try reseting the module?</li>
            <li>Did you try uninstalling and reinstalling the module?</li>
        </ul>
        <p>If are having a problem you cannot solve, you can contact me for help and I will try to help as the time permits.</p>
    </div>
    <h2 class="expand"><img class="icon" src="{$assignedVars.icons.examples}" />{l s='Examples' mod='multiblockatoh'}</h2>
    <div class="collapse">
        <p>You are only limited by your imagination and creativity. Below are some examples to get you started.</p>
        <hr />
        <div>
            <p>This example makes a standard prestashop block using some built-in styles as well as adding its own styles. The block is then moved up above the other right column blocks using <a href="{$assignedVars.links.positionsTab}">Back Office > Modules > Positions</a>.</p>
            <p><strong>Block position:</strong> Right column blocks</p>
            <pre class="left"><strong>Block body:</strong>
&lt;div class="block"&gt;
  &lt;h4&gt;&lt;img src="star.gif" /&gt;&nbsp;It just works!&lt;/h4&gt;
  &lt;div class="block_content"&gt;
    &lt;p style="padding:0.6em 0 0.5em 0;"&gt;Lorem ipsum dolor sit amet...&lt;p&gt;
      &lt;ul class="bullet"&gt;
        &lt;li&gt;Moe&lt;/li&gt;
        &lt;li&gt;Larry&lt;/li&gt;
        &lt;li&gt;Curly&lt;/li&gt;
      &lt;/ul&gt;
  &lt;/div&gt;
&lt;/div&gt;</pre>
            <img class="example1" src="{$assignedVars.links.examples.1}" />
            <div class="clear">&nbsp;</div>
        </div>
        <hr />
        <div>
            <p>This example makes a standard prestashop block using some built-in styles as well as adding its own styles. Google's chart API is used to make a QR code (hardlink) encoding shop URL that can be captured by mobile devices. The block is then moved up above the other left column blocks using <a href="{$assignedVars.links.positionsTab}">Back Office > Modules > Positions</a>.</p>
            <p><strong>Block position:</strong> Left column blocks</p>
            <pre class="left"><strong>Block body:</strong>
&lt;div class="block"&gt;
  &lt;h4&gt;&lt;img style="padding-right:5px" src="qr.gif"&gt;Mobile snap&lt;/h4&gt;
  &lt;div class="block_content"&gt;
    &lt;center&gt;&lt;img style="padding-top:5px;" src="https://chart.googleapis.com/chart?cht=qr&chs=100x100&chl=MEBKM:TITLE:Phrasespot;URL:http://sites.google.com/site/phrasespot/;;&choe=UTF-8&chld=M|0" /&gt;&lt;/center&gt;
  &lt;/div&gt;
&lt;/div&gt;</pre>
            <img class="example2" src="{$assignedVars.links.examples.2}" />
            <div class="clear">&nbsp;</div>
        </div>
        <hr />
        <div>
            <p>This example displays a maintenance notice at the top of each page. It adds a style element for its own styling.</p>
            <p><strong>Block position:</strong> Header of pages</p>
            <pre class="left"><strong>Block body:</strong>
&lt;style type="text/css"&gt;
  #maintenance-notice {
    background-color: yellow;
    margin: 0.5em auto;
    width: 950px;
    text-align: center;}
  #maintenance-notice strong {
    color:red;}
&lt;/style&gt;
&lt;p id="maintenance-notice"&gt;
  &lt;strong&gt;Maintenance notice:&lt;/strong&gt; We will be carrying out essential maintenance this Friday from 12 am.
  Expected duration two hours. Apologies for any inconvenience caused.
&lt;/p&gt;</pre>
            <img class="example3" src="{$assignedVars.links.examples.3}" />
            <div class="clear">&nbsp;</div>
        </div>
    </div>
    <h2 class="expand"><img class="icon" src="{$assignedVars.icons.license}" />{l s='License' mod='multiblockatoh'}</h2>
    <div class="collapse">
        <pre>{$assignedVars.links.license}</pre>
    </div>
    <h2 class="expand"><img class="icon" src="{$assignedVars.icons.bugs}" />{l s='Bugs' mod='multiblockatoh'}</h2>
    <div class="collapse">
        <p>Please report all problems/bugs to <a href="mailto:phrasespot@gmail.com?subject=module%20bug">author</a>. </p>
    </div>
    <h2 class="expand"><img class="icon" src="{$assignedVars.icons.credits}" />{l s='Credits' mod='multiblockatoh'}</h2>
    <div class="collapse">
        <p>{l s='Dutch translation by' mod='multiblockatoh'} <a href="http://www.prestashop.com/forums/user/29340-nostradamus/">nostradamus</a></p>
        <p>{l s='Russian and Ukrainian translations by' mod='multiblockatoh'} <a href="http://www.prestashop.com/forums/user/86117-ehtacl/">ethacl</a></p>
        <p>{l s='Module icons by' mod='multiblockatoh'} <a href="http://www.famfamfam.com/">Mark James</a></p>
        <p>{l s='jQuery expandAll plugin by' mod='multiblockatoh'} <a href="http://www.adipalaz.com/">Adriana Palazova</a>.</p>
    </div>
</div>
<!-- end multiblockatoh -->