{foreach from=$listblocs item=bloc name=blocs}
    <div id="{$bloc.id_block}" class="{$bloc.class_block} block-elt block-col-{$numbercol} {$bloc.hook} block-elt{$smarty.foreach.blocs.index} clearfix">
        {if $bloc.displaytitle && $bloc.titleblock != ''}<h4>{$bloc.titleblock|escape:'html':'UTF-8'}</h4>{/if}
        <div class="content-text {if $bloc.hook == 'displayFooter'}toggle-footer{/if}">{$bloc.editortext}</div>
    </div>
{/foreach}