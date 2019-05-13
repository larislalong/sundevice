<div id="social_block">
	<div class="inner">
		{if isset($facebook_url) && $facebook_url != ''}
			<a class="_blank" href="{$facebook_url|escape:html:'UTF-8'}">
				<i class="social_facebook"></i>
			</a>
		{/if}
		{if isset($twitter_url) && $twitter_url != ''}
			<a class="_blank" href="{$twitter_url|escape:html:'UTF-8'}">
				<i class="social_twitter"></i>
			</a>
		{/if}
		{if isset($rss_url) && $rss_url != ''}
			<a class="_blank" href="{$rss_url|escape:html:'UTF-8'}">
				<i class="social_rss"></i>
			</a>
		{/if}
		{if isset($youtube_url) && $youtube_url != ''}
			<a class="_blank" href="{$youtube_url|escape:html:'UTF-8'}">
				<i class="social_youtube"></i>
			</a>
		{/if}
		{if isset($google_plus_url) && $google_plus_url != ''}
			<a class="_blank" href="{$google_plus_url|escape:html:'UTF-8'}" rel="publisher">
				<i class="social_googleplus"></i>
			</a>
		{/if}
		{if isset($pinterest_url) && $pinterest_url != ''}
			<a class="_blank" href="{$pinterest_url|escape:html:'UTF-8'}">
				<i class="social_pinterest"></i>
			</a>
		{/if}
		{if isset($vimeo_url) && $vimeo_url != ''}
			<a class="_blank" href="{$vimeo_url|escape:html:'UTF-8'}">
				<i class="social_vimeo"></i>
			</a>
		{/if}
		{if isset($instagram_url) && $instagram_url != ''}
			<a class="_blank" href="{$instagram_url|escape:html:'UTF-8'}">
				<i class="social_instagram"></i>
			</a>
		{/if}
	</div>
</div>