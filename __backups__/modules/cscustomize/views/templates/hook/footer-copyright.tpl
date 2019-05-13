{foreach from=$listblocs item=bloc name=blocs}
    <div class="block-elt {$bloc.hook} block-elt{$smarty.foreach.blocs.index}">
        <div class="content-text">{$bloc.editortext}</div>
    </div>
{/foreach}