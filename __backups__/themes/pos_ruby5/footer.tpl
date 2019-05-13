{*
* 2007-2016 PrestaShop
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{if !isset($content_only) || !$content_only}
					</div><!-- #center_column -->
					{if isset($right_column_size) && !empty($right_column_size)}
						<div id="right_column" class="col-xs-12 col-sm-{$right_column_size|intval} column">{$HOOK_RIGHT_COLUMN}</div>
					{/if}
					</div><!-- .row -->
				</div><!-- #columns -->
			</div><!-- .columns-container -->
			{hook h="brandSlider"}
			<!-- Footer -->
			<div class="footer-container">
				<footer id="footer">
					{if $page_name == 'index'}
						{if isset($HOOK_FOOTER)}{$HOOK_FOOTER}{/if}
						{*<div class="footer_center">
							<div class="container">
							</div>
						</div>*}
						{hook h="blockFooter1"}
					{/if}
				
					<div id="sundevice_plus_footer"  class="sundevice_plus container">
						<ul class="row clearfix">
							<li class="col-lg-4 text-center">
								<div class="sundevice_plus-image">
									<img  class="img-responsive" src="{$css_dir|escape:'html':'UTF-8'}../img/new-images/1_sav-reactif.png" alt="{l s='SAV réactif'}" />
								</div>
								<h5>{l s='SAV réactif'}</h5>
							</li>
							<li class="col-lg-4 text-center">
								<div class="sundevice_plus-image">
									<img  class="img-responsive" src="{$css_dir|escape:'html':'UTF-8'}../img/new-images/2_livraison-rapide.png" alt="{l s='Livraison rapide'}" />
								</div>
								<h5>{l s='Livraison rapide'}</h5>
							</li>
							<li class="col-lg-4 text-center">
								<div class="sundevice_plus-image">
									<img  class="img-responsive" src="{$css_dir|escape:'html':'UTF-8'}../img/new-images/3_{l s='paiement-securise.png'}" alt="{l s='Paiement sécurisé'}" />
								</div>
								<h5>{l s='Paiement sécurisé'}</h5>
							</li>
							{*<li class="col-lg-3 text-center">
								<div class="sundevice_plus-image">
									<img  class="img-responsive" src="{$css_dir|escape:'html':'UTF-8'}../img/new-images/4_droit-deretractation.png" alt="{l s='Droits de rétractation'}" />
								</div>
								<h5>{l s="Droits de rétractation"}</h5>
							</li>*}
						</ul>
					</div>
					
					{hook h="blockFooterExtra"}
					<div class="footer-bottom">
						<div class="container">
							<div class="row">
								
								
								<div class="col-lg-4 col-sm-12 text-left">
									{hook h="blockFooter4"}
								</div>
								<div class="col-lg-4 col-sm-12 text-center">
									{hook h="blockFooter2"}
								</div>
								<div class="col-lg-4 col-sm-12 text-right">
									{hook h="blockFooter3"}
								</div>
							</div>
						</div>
					</div>
				</footer>
			</div><!-- #footer -->
			<div class="back-top"><a href= "#" class="back-top-button hidden-xs"></a></div>
		</div><!-- #page_inner -->
		</div><!-- #page -->
{/if}
{include file="$tpl_dir./global.tpl"}
	</body>
</html>