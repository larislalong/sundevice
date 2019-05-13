{foreach from=$languages item=language}
	{if $languages|count > 1}
	<div class="translatable-field row lang-{$language.id_lang}">
		<div class="col-lg-9">
	{/if}
		{if $isTextArea}
		<textarea rows="10" id="{$input_name}_{$language.id_lang}_{$index}"
			class="{if isset($input_class)}{$input_class} {/if}"
			name="{$input_name}_{$language.id_lang}_{$index}"
			data-field-name="{$input_name}_{$language.id_lang}">{$input_value[$language.id_lang]|htmlentitiesUTF8|default:''}</textarea>
		{else}
		<input type="text"
			id="{$input_name}_{$language.id_lang}_{$index}"
			class="{if isset($input_class)}{$input_class} {/if}"
			name="{$input_name}_{$language.id_lang}_{$index}"
			data-field-name="{$input_name}_{$language.id_lang}"
			value="{$input_value[$language.id_lang]|htmlentitiesUTF8|default:''}"/>
		{/if}
	{if $languages|count > 1}
		</div>
		<div class="col-lg-2">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" tabindex="-1">
				{$language.iso_code}
				<span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				{foreach from=$languages item=language}
				<li>
					<a href="javascript:tabs_manager.allow_hide_other_languages = false;hideOtherLanguage({$language.id_lang});">{$language.name}</a>
				</li>
				{/foreach}
			</ul>
		</div>
	</div>
	{/if}
{/foreach}