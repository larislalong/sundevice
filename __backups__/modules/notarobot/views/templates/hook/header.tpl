{*
* NOTICE OF LICENSE
*
* This file is licenced under the Software License Agreement.
* With the purchase or the installation of the software in your application
* you accept the licence agreement.
*
* @author    Presta.Site
* @copyright 2018 Presta.Site
* @license   LICENSE.txt
*}

<style type="text/css">
    {if $re_align == 'right'}
        #nar-gre {
            overflow: hidden;
        }
        #nar-gre > div {
            float: right;
        }
    {/if}
    {if $custom_css}
        {$custom_css|escape:'quotes':'UTF-8'}
    {/if}
</style>

<script src='https://www.google.com/recaptcha/api.js?onload=nar_onLoad&render=explicit{if $nar_lang}&hl={$nar_lang|escape:'html':'UTF-8'}{/if}' async defer></script>
<script type="text/javascript">
    var nar_selector = "#contact [name=submitMessage]";
    var $nar_elem = null;

    function nar_findReElement() {
        if (nar_selector && !$nar_elem) {
            var $nar_elem = $(nar_selector);

            if (!$nar_elem.length) {
                return null;
            }
        }

        return $nar_elem;
    }
</script>

<script type="text/javascript">
    var nar_recaptcha = '<div id="nar-gre" class="g-recaptcha" data-sitekey="{$nar_key|escape:'html':'UTF-8'}" data-theme="{if $re_theme == 'dark'}dark{else}light{/if}" data-size="{if $re_size == 'compact'}compact{else}normal{/if}"></div>';

    {literal}
        var nar_onLoad = function () {
            var $nar_elem = nar_findReElement();

            if ($nar_elem !== null) {
                $(nar_recaptcha).insertBefore($nar_elem);
                grecaptcha.render('nar-gre');
            }
        };
    {/literal}
</script>