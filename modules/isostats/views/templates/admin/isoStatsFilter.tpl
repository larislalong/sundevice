{*
* 2007-2019 PrestaShop
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
*  @copyright 2007-2019 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<style>
	#ui-datepicker-div {
		z-index: 9999 !important;
	}
</style>

	<form id="isoStats_filter_form" method="post" enctype="multipart/form-data" class="form-horizontal">
		<div class="panel " id="isoStats_filter_fieldset">
			<div class="panel-heading"><i class="icon-filter"></i> {l s='Filtres isoStats' mod='isostats'}</div>
			<div class="form-wrapper">
				<div class="form-group">
					<div id="conf_id_PS_SHOP_NAME">
						<label class="control-label col-lg-3">
							<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="" data-html="true">
								Interval de date 
							</span>
						</label>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-addon">
									Du
								</div>
								<input type="text" name="isoStats_filter[date_deb]" class="datepicker" value="{if isset($date_deb)}{$date_deb}{/if}" autocomplete="off" />
								<div class="input-group-addon">
									<i class="icon-calendar-o"></i>
								</div>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="input-group">
								<div class="input-group-addon">
									Au
								</div>
								<input type="text" name="isoStats_filter[date_fin]" class="datepicker" value="{if isset($date_fin)}{$date_fin}{/if}" autocomplete="off" />
								<div class="input-group-addon">
									<i class="icon-calendar-o"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div><!-- /.form-wrapper -->
			<div class="panel-footer">
				<button type="submit" class="btn btn-default pull-right" name="submitOptionsstore"><i class="process-icon-refresh"></i> Filtrer</button>
			</div>
							
		</div>
	</form>
	
<script type="text/javascript">
{literal}

    $(function(){
            
            $('.datepicker').datepicker({
                prevText: '',
                nextText: '',
                dateFormat: 'yy-mm-dd'
            });
    });
    
{/literal}
</script>