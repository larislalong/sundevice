<?php
/**
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
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2018 PrestaShop SA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

/**
 * Class DhlexpressCronlabelModuleFrontController
 */
class DhlexpressCronlabelModuleFrontController extends ModuleFrontController
{
    /**
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function postProcess()
    {
        require_once(dirname(__FILE__).'/../../classes/DhlLabel.php');
        require_once(dirname(__FILE__).'/../../classes/DhlCommercialInvoice.php');

        $secureKey = md5(_COOKIE_KEY_.Configuration::get('PS_SHOP_NAME'));
        if (!empty($secureKey) && Tools::getValue('secure_key') === $secureKey) {
            /**
             * We chose to delete labels that were created more than 6 months ago and to hard-code the value.
             * The script is ready to be used as a cron task.
             * You can update here or make the number of month manageable in BO.
             */
            $lifetime = 6;
            $dateTo = new DateTime('now');
            $dateTo->sub(new DateInterval('P'.(int) $lifetime.'M'));
            $labels = DhlLabel::getByDate($dateTo->format('Y-m-d'));
            foreach ($labels as $label) {
                $dhlLabel = new DhlLabel((int) $label['id_dhl_label']);
                if (!Validate::isLoadedObject($dhlLabel)) {
                    continue;
                }

                /* When we delete a label, we need to delete both related invoice and return label too. */
                $dhlCI = DhlCommercialInvoice::getByIdDhlLabel($dhlLabel->id);
                $dhlReturnLabel = $dhlLabel->getDhlReturnLabel();
                $dhlLabel->deleteLabel($dhlCI, $dhlReturnLabel);
            }
        } else {
            die();
        }
        die();
    }
}
