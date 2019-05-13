{if is_array($wrapgift) && count($wrapgift)}
    <p>{l s='packaging :' mod='blockwrapgift'} {$wrapgift.name|escape:'html':'UTF-8'}</p>
{/if}