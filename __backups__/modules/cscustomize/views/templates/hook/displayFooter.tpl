{foreach from=$listblocs item=bloc name=blocs}
<section id="{if $bloc.id_block}{$bloc.id_block}{else}block-elt-id-{$bloc.id_cseditor}{/if}" class="footer-block col-xs-12 col-sm-4 clearfix">
    <div class="wrap-section{if $bloc.class_block} {$bloc.class_block}{/if}">
        <h4 {if !$bloc.displaytitle}class="displaynone"{/if}>{$bloc.titleblock|escape:'html':'UTF-8'}</h4>
        <div class="content-text toggle-footer">{$bloc.editortext}</div>
    </div>
</section>
{/foreach}