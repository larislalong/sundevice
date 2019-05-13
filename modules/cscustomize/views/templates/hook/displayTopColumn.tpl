<div class="clearfix"></div>
<section>
    <div id="cscutomize-bloc-topcolumn" class="cscustomize-block col-xs-12 col-sm-12">
        <div class="container">
        {foreach from=$listblocs item=bloc name=blocs}
            <div class="topcolumn-block col-xs-12 col-sm-12 block-elt block-col-{$numbercol} {$bloc.hook} block-elt{$smarty.foreach.blocs.index} clearfix">
                <h4 {if !$bloc.displaytitle}class="displaynone"{/if}>{$bloc.titleblock|escape:'html':'UTF-8'}</h4>
                <div class="content-text">{$bloc.editortext}</div>
            </div>
        {/foreach}
        </div>
    </div>
</section>