<?php
/**
* 2007-2018 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
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
*  @copyright  2007-2018 PrestaShop SA
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Upgrade to 2.1.4
 *
 * - Add column for label format
 * - Rename column for label blob
 * - Set default format for previously generated labels
 *
 * @param $module
 * @return bool
 */
function upgrade_module_2_1_4($module)
{
    $query = Db::getInstance()
               ->execute('ALTER TABLE `'._DB_PREFIX_.'dhl_label` ADD COLUMN `label_format` VARCHAR(4) NULL AFTER `return_label`');
    $query &= Db::getInstance()
                ->execute('ALTER TABLE `'._DB_PREFIX_.'dhl_label` CHANGE `pdf_string` `label_string` LONGBLOB  NULL');
    $format = Configuration::get('DHL_LABEL_TYPE');
    switch ($format) {
        case 'zpl264':
            $labelFormat = 'zpl';
            break;
        case 'epl264':
            $labelFormat = 'epl';
            break;
        case 'pdfa4':
        case 'pdf64':
        default:
            $labelFormat = 'pdf';
            break;
    }
    $query &= Db::getInstance()
                ->execute('UPDATE `'._DB_PREFIX_.'dhl_label` SET `label_format`="'.pSQL($labelFormat).'" WHERE 1');

    return $query;
}
