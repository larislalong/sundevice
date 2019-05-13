{**
* NOTICE OF LICENSE
*
* This file is licenced under the Software License Agreement.
* With the purchase or the installation of the software in your application
* you accept the licence agreement.
*
* @author    Presta.Site
* @copyright 2016 Presta.Site
* @license   LICENSE.txt
*}
<div id="nar-pro-features">
    {if $psv == 1.5}
        <br/><fieldset><legend>{l s='PRO features' mod='notarobot'} <a href="#" id="nar_show_pro">({l s='show' mod='notarobot'})</a></legend>
    {else}
        <div class="panel">
        <div class="panel-heading">
            <i class="icon-thumbs-o-up"></i> {l s='PRO features' mod='notarobot'} <a href="#" id="nar_show_pro">({l s='show' mod='notarobot'})</a>
        </div>
    {/if}
        <div class="form-wrapper">
            <div class="form-group" style="display: none;" id="nar_pro_features_content">
                {if $psv == 1.5}
                    {l s='Buy a PRO version of this module and get extra features' mod='notarobot'} (<a target="_blank" href="https://presta.site/psmodule?module=notarobot"><b>{l s='Contact form anti-spam: reCAPTCHA and blacklist PRO' mod='notarobot'}</b></a>):
                {else}
                    {l s='Buy a [1]PRO version[/1] of this module and get extra features:' tags=['<a target="_blank" href="https://presta.site/psmodule?module=notarobot">'] mod='notarobot'}
                {/if}
                <ul style="padding-top: 5px;">
                    <li>{l s='Invisible reCAPCTHA' mod='notarobot'}</li>
                    <li>{l s='Blacklist for message content' mod='notarobot'}</li>
                </ul>
            </div>
        </div>
    {if $psv == 1.5}
        </fieldset><br/>
    {else}
        </div>
    {/if}
    <script type="text/javascript">
        $(function () {
            $('#nar_show_pro').on('click', function (e) {
                $('#nar_pro_features_content').slideDown();
                $(this).remove();

                e.preventDefault();
            });
        });
    </script>
</div>
