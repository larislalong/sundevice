{*
* 2007-2018 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{capture name=path}
    <a href="{$seller_link|escape:'html':'UTF-8'}">
        {$seller->name|escape:'html':'UTF-8'}
    </a>
    <span class="navigation-pipe">
        {$navigationPipe|escape:'html':'UTF-8'}
    </span>
    <span class="navigation_page">
        {l s='Ratings and comments' mod='jmarketplace'}
    </span>
{/capture} 

{if isset($confirmation) && $confirmation}
    {if $moderate == 1}
        <p class="alert alert-success">
            {l s='Your comment has been successfully sent. It is pending approval.' mod='jmarketplace'}
        </p>
    {else}    
        <p class="alert alert-success">
            {l s='Your comment has been successfully sent.' mod='jmarketplace'}
        </p>
    {/if}
{else}
    {if isset($errors) && $errors}
        {include file="./errors.tpl"}
    {/if}
{/if}

<div class="box">
    <h1 class="page-heading">
        {l s='Ratings and comments' mod='jmarketplace'}
    </h1>
    {if isset($seller_comments) && $seller_comments}
        <div class="row">
            {if $show_logo}
                <div class="col-xs-12 col-sm-3 col-lg-2">
                    <img class="img-responsive img-fluid" src="{$photo|escape:'html':'UTF-8'}" />
                </div>
            {/if}
            <div class="{if $show_logo}col-xs-12 col-sm-9 col-lg-7{else}col-xs-12 col-lg-9{/if}">
                <div class="row">
                    {section name="i" loop=6 max=5 step=-1}
                        <div class="col-xs-12 col-sm-3 col-lg-3">
                            {if $smarty.section.i.index == 5}
                                <div class="star star_on"></div>
                                <div class="star star_on"></div>
                                <div class="star star_on"></div>
                                <div class="star star_on"></div>
                                <div class="star star_on"></div>
                            {else if $smarty.section.i.index == 4}
                                <div class="star star_on"></div>
                                <div class="star star_on"></div>
                                <div class="star star_on"></div>
                                <div class="star star_on"></div>
                                <div class="star"></div>
                            {else if $smarty.section.i.index == 3}
                                <div class="star star_on"></div>
                                <div class="star star_on"></div>
                                <div class="star star_on"></div>
                                <div class="star"></div>
                                <div class="star"></div>
                            {else if $smarty.section.i.index == 2}
                                <div class="star star_on"></div>
                                <div class="star star_on"></div>
                                <div class="star"></div>
                                <div class="star"></div>
                                <div class="star"></div>
                            {else if $smarty.section.i.index == 1}
                                <div class="star star_on"></div>
                                <div class="star"></div>
                                <div class="star"></div>
                                <div class="star"></div>
                                <div class="star"></div>
                            {/if}
                            ({$num_rows_grade[$smarty.section.i.index]|intval})
                        </div>
                        <div class="col-xs-12 col-sm-9 col-lg-9">
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width:{$per_rows_grade[$smarty.section.i.index]|floatval}%" aria-valuenow="{$per_rows_grade[$smarty.section.i.index]|floatval}" aria-valuemin="0" aria-valuemax="100">
                                    {if {$per_rows_grade[$smarty.section.i.index]} != 0}
                                        {$per_rows_grade[$smarty.section.i.index]|floatval}%
                                    {/if}
                                </div>
                            </div> 
                        </div>
                    {/section}  
                </div>  
            </div>
            <div class="col-xs-12 col-sm-12 col-lg-3">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="num_comments">
                                    {l s='Number of comments' mod='jmarketplace'}: 
                                    {$num_comments|intval}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="average_midle">
                                    {l s='Rating' mod='jmarketplace'}: 
                                    {$rating.avg|string_format:"%.2f"|floatval} 
                                    {l s='de 5' mod='jmarketplace'}
                                </div>
                            </div>
                        </div>
                        
                        {if isset($resum_grade) && $resum_grade}
                        <div class="row">
                            <div class="col-lg-12">
                                <p>
                                    {l s='Criterions' mod='jmarketplace'}
                                </p>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        {foreach from=$resum_grade item=criterion name=criteriongrade}
                                            <tr class="resum_criterion">
                                                <td>
                                                    {$criterion.name|escape:'html':'UTF-8'}
                                                </td>
                                                <td>
                                                    {section name="i" start=0 loop=5 step=1}
                                                        {if $criterion.grade le $smarty.section.i.index}
                                                            <div class="star"></div>
                                                        {else}
                                                            <div class="star star_on"></div>
                                                        {/if}
                                                    {/section}
                                                </td>                                        
                                            </tr>
                                        {/foreach}   
                                    </table>
                                </div>
                            </div>
                        </div>
                        {/if}
                    </div>
                </div>
            </div>
        </div>
 
        <h2 class="page-heading">
            {$num_comments|intval} 
            {l s='comments about' mod='jmarketplace'} 
            "{$seller->name|escape:'html':'UTF-8'}"
        </h2>
        <ul class="seller_comments row">
            {foreach from=$seller_comments item=comment name=sellercomments}
                <li class="col-md-12" itemprop="review" itemscope itemtype="https://schema.org/Review">
                    <div class="comment">
                        <div class="row">
                            <div class="comment_header col-md-12">
                                <div class="star_content clearfix"  itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
                                    {section name="i" start=0 loop=5 step=1}
                                        {if $comment.grade le $smarty.section.i.index}
                                            <div class="star"></div>
                                        {else}
                                            <div class="star star_on"></div>
                                        {/if}
                                    {/section}
                                    <meta itemprop="worstRating" content="0" />
                                    <meta itemprop="ratingValue" content="{$comment.grade|escape:'html':'UTF-8'}" />
                                    <meta itemprop="bestRating" content="5" />
                                </div>

                                <div itemprop="name" class="title_block">
                                    {$comment.title|escape:'html':'UTF-8'}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="comment_author_infos col-md-12">
                                <span>
                                    {l s='By' mod='jmarketplace'}
                                </span>
                                <span itemprop="author" class="author">
                                    {$comment.customer_name|escape:'html':'UTF-8'}
                                </span>
                                {if $comment.id_product != 0}
                                    <span>
                                        {l s='by purchasing' mod='jmarketplace'} 
                                        {$comment.product_name|escape:'html':'UTF-8'} 
                                    </span>
                                {/if}
                                <span>
                                    {l s='on' mod='jmarketplace'}
                                </span>
                                <meta itemprop="datePublished" content="{$comment.date_add|escape:'html':'UTF-8'|substr:0:10}" />
                                <span>
                                    {dateFormat date=$comment.date_add|escape:'html':'UTF-8' full=0}
                                </span>
                            </div>
                        </div>
                        <div class="row">    
                            <div class="comment_details col-md-12">
                                <p itemprop="reviewBody">
                                    {$comment.content|escape:'html':'UTF-8'|nl2br}
                                </p> 
                            </div>
                        </div>
                    </div>
                </li>
            {/foreach}
        </ul>
    {else}
        <p class="alert alert-info">
            {l s='There are not comments.' mod='jmarketplace'}
        </p>
    {/if}
    {if !$logged AND $allow_guests == 0}
        <p class="align_center">
            <a href="{$link->getPageLink('authentication')|escape:'htmlall':'UTF-8'}?back={$url_seller_comments|escape:'htmlall':'UTF-8'}#new_comment_form">
                {l s='Login to send a comment' mod='jmarketplace'}
            </a>
        </p>
    {else}   
        {if ($allow_guests == 1 OR ($allow_customer_bought == 1 AND (isset($products_bought_from_this_seller) AND count($products_bought_from_this_seller) > 0)))}
            <div id="new_comment_form">
                <form id="id_new_comment_form" action="{$url_seller_comments|escape:'htmlall':'UTF-8'}" method="post">
                    <input type="hidden" name="is_customer_bought" value="{$is_customer_bought|intval}"/>
                    <h2 class="page-subheading">
                        {l s='Write a review about' mod='jmarketplace'} 
                        "{$seller->name|escape:'html':'UTF-8'}"
                    </h2>
                    <div class="row">
                        <div class="new_comment_form_content col-lg-12">
                            {if $criterions|@count > 0}
                                <ul id="criterions_list">
                                    {foreach from=$criterions item='criterion'}
                                        <li>
                                            <label>
                                                {$criterion.name|escape:'html':'UTF-8'}:
                                            </label>
                                            <div class="star_content">
                                                <input name="criterion[{$criterion.id_seller_comment_criterion|intval}]" value="1" type="radio" class="star not_uniform"/> 
                                                <input name="criterion[{$criterion.id_seller_comment_criterion|intval}]" value="2" type="radio" class="star not_uniform"/> 
                                                <input name="criterion[{$criterion.id_seller_comment_criterion|intval}]" value="3" type="radio" class="star not_uniform"/> 
                                                <input name="criterion[{$criterion.id_seller_comment_criterion|intval}]" value="4" type="radio" class="star not_uniform" checked="checked"/> 
                                                <input name="criterion[{$criterion.id_seller_comment_criterion|intval}]" value="5" type="radio" class="star not_uniform"/> 
                                            </div>
                                            <div class="clearfix"></div>
                                        </li>
                                    {/foreach}
                                </ul>
                            {/if}

                            <div class="required form-group">
                                <label for="comment_title">
                                    {l s='Title:' mod='jmarketplace'} 
                                    <sup class="required">*</sup>
                                </label>
                                <input id="comment_title" name="title" type="text" value=""/>
                            </div>

                            <div class="required form-group">
                                <label for="content">
                                    {l s='Comment:' mod='jmarketplace'} 
                                    <sup class="required">*</sup>
                                </label>
                                <textarea id="content" name="content"></textarea>
                            </div>

                            {if isset($products_bought_from_this_seller) AND count($products_bought_from_this_seller) > 0}
                                <div class="required form-group">
                                    <label for="order">
                                        {l s='Order/Product' mod='jmarketplace'}
                                    </label>
                                    <select id="id_order_product" class="form-control" name="id_order_product">
                                        {foreach from=$products_bought_from_this_seller item=order}
                                            <option value="{$order.id_order_product|escape:'html':'UTF-8'}">
                                                {$order.reference|escape:'html':'UTF-8'} 
                                                - {$order.order_date_add|escape:'html':'UTF-8'}
                                                - {$order.product_name|escape:'html':'UTF-8'}
                                            </option>
                                        {/foreach}
                                    </select>
                                </div>
                            {/if}

                            {if $allow_guests == 1 && !$logged}
                                <label for="customer_name">
                                    {l s='Your name:' mod='jmarketplace'} 
                                    <sup class="required">*</sup>
                                </label>
                                <input name="customer_name" type="text" value=""/>
                            {/if}

                            <div id="new_comment_form_footer">
                                <p class="fl required">
                                    <sup>*</sup> 
                                    {l s='Required fields' mod='jmarketplace'}
                                </p>
                                <p class="fr">
                                    <button id="submitNewMessage" name="submitSellerComment" type="submit" class="btn button button-small">
                                        <span>
                                            {l s='Submit' mod='jmarketplace'}
                                        </span>
                                    </button>
                                </p>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        {else}
            <p class="alert alert-warning">
                {l s='To send a comment about this seller you must make a purchase.' mod='jmarketplace'}
                <a href="{$url_seller_products|escape:'html':'UTF-8'}" title="{l s='View products of' mod='jmarketplace'} {$seller->name|escape:'html':'UTF-8'}">
                    {l s='View products of' mod='jmarketplace'} 
                    "{$seller->name|escape:'html':'UTF-8'}"
                </a>
            </p>
        {/if}
    {/if}
</div>   