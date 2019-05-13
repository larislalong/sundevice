<script type="text/javascript">
    $(document).ready(function(){
        $('a.alltestimoninal')
                .attr('rel', 'media-gallery')
                .fancybox({
                    openEffect : 'none',
                    closeEffect : 'none',
                    prevEffect : 'none',
                    nextEffect : 'none',
                    arrows : false,
                    helpers : {
                        media : {},
                        buttons : {}
                    }
                });
    });
</script>
{capture name=path}
 {l s='Testimonial' mod='postestimonials'}
{/capture}
<div class="content-testimonials block">
		<h2 class="all_test_title">
			{l s='Testimonial' mod='postestimonials'}
			<a class="button btn btn-default button-small submit_test" href="{$link->getModuleLink('postestimonials','views',['show' => 'form'])}">{l s='Submit Testimonial' mod='postestimonials'}</a>
			<div class="clearfix"></div>
		</h2>
    {if $id != 0}
        <h3>{l s='Current testimonial' mod='postestimonials'}</h3>
    {/if}
    {foreach $testimoninals as $alltestimoninal}
        {if $alltestimoninal.content !='' }
        <div class="postestimoninals">
            {if ($alltestimoninal.media_type)!=''}
                {if in_array($alltestimoninal.media_type,$image_type)}
                    <div class="img-content" >
                        <a class="alltestimoninal" href="{$img_ps_dir}{$name}/{$alltestimoninal.media}">
                        <img src="{$img_ps_dir}{$name}/{$alltestimoninal.media}" class="image-alltestimoninal" ></a>
                    </div>
                {/if}
                {if in_array($alltestimoninal.media_type,$video_type)}
                    <div class="img-content">
                        <video  controls class="image-alltestimoninal">
                            <source src="{$img_ps_dir}{$name}/{$alltestimoninal.media}" type="video/mp4" />
                        </video>
                    </div>
                {/if}
                {if $alltestimoninal.media==null && $alltestimoninal.media_type=='youtube'}
                    <div class="img-content">
                        <iframe class="image-alltestimoninal" src="http://www.youtube.com/embed/{$alltestimoninal.media_link_id}" frameborder="0" allowfullscreen></iframe>
                    </div>
                {/if}
                {if $alltestimoninal.media==null && $alltestimoninal.media_type=='vimeo'}
                    <div class="img-content">
                        <iframe  class="image-alltestimoninal" src="//player.vimeo.com/video/{$alltestimoninal.media_link_id}?badge=0&amp;color=e3dede" width="220" height="115" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    </div>
                {/if}
            {/if}
            <div class="alltestimonial-content">
                “{$alltestimoninal.content}“
            </div>
            <div class="info-user">
                <span class="username">  {$alltestimoninal.name_post} </span>
                <br>
                <span class="date-add"> {$alltestimoninal.date_add}</span> - <i><b>{$alltestimoninal.email}</b></i> -</b>{$alltestimoninal.company}</b>
            </div>
            <div style="clear:both;"></div>
        </div>
        {/if}
    {/foreach}

    {if isset($page_other_testimonials) && $page_other_testimonials}
        <h3>{l s='Other testimonials' mod='postestimonials'}</h3>
        {foreach $page_other_testimonials as $alltestimoninal}
        <div class="postestimoninals">
            {if ($alltestimoninal.media_type)!=''}
            {if in_array($alltestimoninal.media_type,$image_type)}
                    <div class="img-content" >
                    <a class="alltestimoninal" href="{$img_ps_dir}{$name}/{$alltestimoninal.media}">
                    <img src="{$img_ps_dir}{$name}/{$alltestimoninal.media}" class="image-alltestimoninal" ></a>
                    </div>
             {/if}
                {if in_array($alltestimoninal.media_type,$video_type)}
                    <div class="img-content">
                        <video  controls class="image-alltestimoninal">
                        <source src="{$img_ps_dir}{$name}/{$alltestimoninal.media}" type="video/mp4" />
                        </video>
                    </div>
              {/if}
            {if $alltestimoninal.media==null && $alltestimoninal.media_type=='youtube'}
                <div class="img-content">
                    <iframe class="image-alltestimoninal" src="http://www.youtube.com/embed/{$alltestimoninal.media_link_id}" frameborder="0" allowfullscreen></iframe>
                </div>
            {/if}
            {if $alltestimoninal.media==null && $alltestimoninal.media_type=='vimeo'}
                <div class="img-content">
                    <iframe  class="image-alltestimoninal" src="//player.vimeo.com/video/{$alltestimoninal.media_link_id}?badge=0&amp;color=e3dede" width="220" height="115" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                </div>
            {/if}
            {/if}
              <div class="alltestimonial-content">
                  “{$alltestimoninal.content}“
              </div>

            <div class="info-user">
              <span class="username">  {$alltestimoninal.name_post} </span>
                <br>
               <span class="date-add"> {$alltestimoninal.date_add}</span> - <i><b>{$alltestimoninal.email}</b></i> - <b>{$alltestimoninal.company}</b>
            </div>
            <div style="clear:both;"></div>
        </div>
         <br>
       {/foreach}
    {/if}
    <div id="pagination" class="pagination">
        {if $nbpagination < $alltestimoninals|@count}
            <ul class="pagination">
                {if $page != 1}
                    {assign var='p_previous' value=$page-1}
                    <li id="pagination_previous">
                        <a href="{testimonialpaginationlink p=$p_previous n=$nbpagination}" title="{l s='Previous' mod='testimoninals'}" rel="nofollow">&laquo;&nbsp;{l s='Previous' mod='postestimonials'}</a>
                    </li>
                {else}
                    <li id="pagination_previous" class="disabled"><span>&laquo;&nbsp;{l s='Previous' mod='postestimonials'}</span></li>
                {/if}
                {if $page > 2}
                    <li><a href="{testimonialpaginationlink p='1' n=$nbpagination}" rel="nofollow">1</a></li>
                    {if $page > 3}
                        <li class="truncate">...</li>
                    {/if}
                {/if}
                {section name=pagination start=$page-1 loop=$page+2 step=1}
                    {if $page == $smarty.section.pagination.index}
                        <li class="current"><span>{$page|escape:'htmlall':'UTF-8'}</span></li>
                    {elseif $smarty.section.pagination.index > 0 && $alltestimoninals|@count+$nbpagination > ($smarty.section.pagination.index)*($nbpagination)}
                        <li><a href="{testimonialpaginationlink p = $smarty.section.pagination.index n=$nbpagination}">{$smarty.section.pagination.index|escape:'htmlall':'UTF-8'}</a></li>
                    {/if}
                {/section}
                {if $max_page-$page > 1}
                    {if $max_page-$page > 2}
                        <li class="truncate">...</li>
                    {/if}
                    <li><a href="{testimonialpaginationlink p=$max_page n=$nbpagination}">{$max_page}</a></li>
                {/if}
                {if $alltestimoninals|@count > $page * $nbpagination}
                    {assign var='p_next' value=$page+1}
                    <li id="pagination_next"><a href="{testimonialpaginationlink p=$p_next n=$nbpagination}" title="Next" rel="nofollow">{l s='Next' mod='postestimonials'}&nbsp;&raquo;</a></li>
                {else}
                    <li id="pagination_next" class="disabled"><span>{l s='Next' mod='postestimonials'}&nbsp;&raquo;</span></li>
                {/if}
            </ul>
        {/if}
        {if $alltestimoninals|@count > 10}
            <form action="{$pagination_link}" method="get" class="pagination">
                <p>
                    <input type="submit" class="button_mini" value="{l s='OK' mod='postestimonials'}" />
                    <label for="nb_item">{l s='items:' mod='testimoninals'}</label>
                    <select name="n" id="nb_item">
                        {foreach from=$nArray item=nValue}
                            {if $nValue <= $alltestimoninals|@count}
                                <option value="{$nValue|escape:'htmlall':'UTF-8'}" {if $nbpagination == $nValue}selected="selected"{/if}>{$nValue|escape:'htmlall':'UTF-8'}</option>
                            {/if}
                        {/foreach}
                    </select>
                    <input type="hidden" name="p" value="1" />
                </p>
            </form>
        {/if}
    </div>
</div>

