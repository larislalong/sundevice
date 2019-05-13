<?php
/**
* History:
*
* 1.0 - First version
* 1.4 - Possibility to add the function "display combinations" everywhere
* 1.5 - for prestashop 1.5, function smarty "display_combinations" become function "d_combinations"
* 1.5.1 - add the list on product page and possiblity to choicy the quantity for the customers
* 1.5.2 - correction bug in query SQL and javascript (minimal_quantity)
* 1.5.3 - correction bug when product have "no-tax"
* 1.5.4 - compatibility with module blocklayered and displaying a square instead of the image
* 1.5.5 - add buttons + and -, correct little bug for installation in the file product-list.tpl
* 1.5.6 - correction bug with blocklayered
* 1.5.7 - list in ajax and little optimisations
* 1.5.8 - correction bug
* 1.5.9 - optimisations (image default if no img for attribute)and add reduction and price stripped in the back office
* 1.5.9.1 - Correction bug (image not displayed if attribute have not color)
* 1.5.9.2 - i forget a {debug} in the file display_combinations.tpl - sorry
* 1.5.9.3 - Possiblity to choose ajax or not on the page category, correction bug with blocklayered
* 1.5.9.4 - debug url product when ajax selected
* 1.5.9.5 - debug : when _PS_VERSION_ < 1.5.5 and ajax not selected the file tpl is not load correctly
* 1.5.9.6 - debug display price with specific_price_output
* 1.5.9.7 - display message if the image size is > 200px in the BO, add the animation image product in the page category,
			change the src of the image in BO (if the combination's name contain "%" for example)
* 1.6 - version for prestashop 1.6 - valide for https://validator.prestashop.com
* 1.6.1 - correction for controller (like search), modification of the function updateConfigFile(),
			the file js/displaycombinations_categories.js and url of the file tpl for 1.5.x
* 1.6.2 - debug for compatibility with prestashop 1.5.x
* 1.6.3 - little debug when smartcache is disable on product-list.tpl
* 1.6.4 - add selector function
* 1.6.5 - debug SQL (problem quantity in multishop) and function zoom and function for hide the list of attributes
* 1.6.6 - add column unit price, add column message stock and warning, add background-color for 1 line on 2, change the gestion of stock
* 1.6.7 - code cleaning, add function for sorting out combinations
*
*  @author    Vincent MASSON <contact@coeos.pro>
*  @copyright Vincent MASSON <www.coeos.pro>
*  @license   http://www.coeos.pro/boutique/fr/content/3-conditions-generales-de-ventes
*/

header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');

header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

header('Location: ../');
exit;