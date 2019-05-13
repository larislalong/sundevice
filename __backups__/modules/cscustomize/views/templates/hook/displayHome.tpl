{foreach from=$listblocs item=bloc name=blocs}
<section id="{if $bloc.id_block}{$bloc.id_block}{else}block-elt-id-{$bloc.id_cseditor}{/if}">
    <div class="wrap-section{if $bloc.class_block} {$bloc.class_block}{/if}">
        <div class="container">
            <h1 {if !$bloc.displaytitle}class="displaynone"{/if}>{$bloc.titleblock|escape:'html':'UTF-8'}</h1>
             <div class="content-text toggle-footer">{$bloc.editortext}</div>
        </div>
    </div>
</section>
{/foreach}