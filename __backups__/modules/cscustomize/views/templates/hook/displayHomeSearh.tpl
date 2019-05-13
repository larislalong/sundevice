<section>
    <div id="cscutomize-bloc-homesearch" class="cscustomize-block col-xs-12 col-sm-12">
        <div class="container">
            <h1><span>{l s='BIEN CHOISIR' mod='cscustomize'}</span>{l s='CES PRODUITS' mod='cscustomize'}</h1>
            <h2>{l s='TROUVER CES NOUVEAUX PRODUITS DU PREMIER COUP D\'ŒIL!' mod='cscustomize'}</h2>
            <div class="wrap-section">
            {foreach from=$listblocs item=bloc name=blocs}
                <div id="{if $bloc.id_block}{$bloc.id_block}{else}block-elt-id-{$bloc.id_cseditor}{/if}" class="col-lg-4 col-md-6 col-sm-12{if $bloc.class_block} {$bloc.class_block}{/if}">
                    <figure class="wrap-img"><img src="{$mod_img}img-c.png" alt="" class="img-responsive" /></figure>
                    {if $bloc.displaytitle}<h3>{$bloc.titleblock|escape:'html':'UTF-8'}</h3>{/if}
                    {if $bloc.secondtitle !=''}<h4>{$bloc.secondtitle|escape:'html':'UTF-8'}</h4>{/if}
                    <div class="rte">{$bloc.editortext}</div>
                    {if $bloc.linkblock !=''}<a href="{$bloc.linkblock|escape:'html':'UTF-8'}" title="{l s='En savoir plus' mod='cscustomize'}"> > {l s='En savoir plus' mod='cscustomize'}</a>{/if}
                </div>
                <div id="{if $bloc.id_block}{$bloc.id_block}{else}block-elt-id-{$bloc.id_cseditor}{/if}" class="col-lg-4 col-md-6 col-sm-12{if $bloc.class_block} {$bloc.class_block}{/if}">
                    <figure class="wrap-img"><img src="{$mod_img}img-c.png" alt="" class="img-responsive" /></figure>
                    {if $bloc.displaytitle}<h3>{$bloc.titleblock|escape:'html':'UTF-8'}</h3>{/if}
                    {if $bloc.secondtitle !=''}<h4>{$bloc.secondtitle|escape:'html':'UTF-8'}</h4>{/if}
                    <div class="rte">{$bloc.editortext}</div>
                    {if $bloc.linkblock !=''}<a href="{$bloc.linkblock|escape:'html':'UTF-8'}" title="{l s='En savoir plus' mod='cscustomize'}"> > {l s='En savoir plus' mod='cscustomize'}</a>{/if}
                </div>
                <div id="{if $bloc.id_block}{$bloc.id_block}{else}block-elt-id-{$bloc.id_cseditor}{/if}" class="col-lg-4 col-md-6 col-sm-12{if $bloc.class_block} {$bloc.class_block}{/if}">
                    <figure class="wrap-img"><img src="{$mod_img}img-c.png" alt="" class="img-responsive" /></figure>
                    {if $bloc.displaytitle}<h3>{$bloc.titleblock|escape:'html':'UTF-8'}</h3>{/if}
                    {if $bloc.secondtitle !=''}<h4>{$bloc.secondtitle|escape:'html':'UTF-8'}</h4>{/if}
                    <div class="rte">{$bloc.editortext}</div>
                    {if $bloc.linkblock !=''}<a href="{$bloc.linkblock|escape:'html':'UTF-8'}" title="{l s='En savoir plus' mod='cscustomize'}"> > {l s='En savoir plus' mod='cscustomize'}</a>{/if}
                </div>
                <div id="{if $bloc.id_block}{$bloc.id_block}{else}block-elt-id-{$bloc.id_cseditor}{/if}" class="col-lg-4 col-md-6 col-sm-12{if $bloc.class_block} {$bloc.class_block}{/if}">
                    <figure class="wrap-img"><img src="{$mod_img}img-c.png" alt="" class="img-responsive" /></figure>
                    {if $bloc.displaytitle}<h3>{$bloc.titleblock|escape:'html':'UTF-8'}</h3>{/if}
                    {if $bloc.secondtitle !=''}<h4>{$bloc.secondtitle|escape:'html':'UTF-8'}</h4>{/if}
                    <div class="rte">{$bloc.editortext}</div>
                    {if $bloc.linkblock !=''}<a href="{$bloc.linkblock|escape:'html':'UTF-8'}" title="{l s='En savoir plus' mod='cscustomize'}"> > {l s='En savoir plus' mod='cscustomize'}</a>{/if}
                </div>
                <div id="{if $bloc.id_block}{$bloc.id_block}{else}block-elt-id-{$bloc.id_cseditor}{/if}" class="col-lg-4 col-md-6 col-sm-12{if $bloc.class_block} {$bloc.class_block}{/if}">
                    <figure class="wrap-img"><img src="{$mod_img}img-c.png" alt="" class="img-responsive" /></figure>
                    {if $bloc.displaytitle}<h3>{$bloc.titleblock|escape:'html':'UTF-8'}</h3>{/if}
                    {if $bloc.secondtitle !=''}<h4>{$bloc.secondtitle|escape:'html':'UTF-8'}</h4>{/if}
                    <div class="rte">{$bloc.editortext}</div>
                    {if $bloc.linkblock !=''}<a href="{$bloc.linkblock|escape:'html':'UTF-8'}" title="{l s='En savoir plus' mod='cscustomize'}"> > {l s='En savoir plus' mod='cscustomize'}</a>{/if}
                </div>
                <div id="{if $bloc.id_block}{$bloc.id_block}{else}block-elt-id-{$bloc.id_cseditor}{/if}" class="col-lg-4 col-md-6 col-sm-12{if $bloc.class_block} {$bloc.class_block}{/if}">
                    <figure class="wrap-img"><img src="{$mod_img}img-c.png" alt="" class="img-responsive" /></figure>
                    {if $bloc.displaytitle}<h3>{$bloc.titleblock|escape:'html':'UTF-8'}</h3>{/if}
                    {if $bloc.secondtitle !=''}<h4>{$bloc.secondtitle|escape:'html':'UTF-8'}</h4>{/if}
                    <div class="rte">{$bloc.editortext}</div>
                    {if $bloc.linkblock !=''}<a href="{$bloc.linkblock|escape:'html':'UTF-8'}" title="{l s='En savoir plus' mod='cscustomize'}"> > {l s='En savoir plus' mod='cscustomize'}</a>{/if}
                </div>
            {/foreach}
            </div>
        </div>
    </div>
</section>