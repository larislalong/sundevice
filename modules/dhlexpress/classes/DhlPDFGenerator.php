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
 * Class DhlPDFGenerator
 */
class DhlPDFGenerator extends PDFGenerator
{
    /** @var bool $isLastPage */
    public $isLastPage = false;

    /**
     *
     */
    public function Footer()
    {
        if ($this->isLastPage) {
            $this->writeHTML($this->footer);
        }
        $this->FontFamily = self::DEFAULT_FONT;
        $this->writeHTML($this->pagination);
    }

    /**
     * @param bool|false $resetmargins
     */
    public function lastPage($resetmargins = false)
    {
        $this->setPage($this->getNumPages(), $resetmargins);
        $this->isLastPage = true;
    }

    /**
     * Are commented :
     *  - SetMargins() method to set margins for each DHL pdf file in admin controllers.
     *  - SetFooterMargin() method to set footer height for each DHL pdf file in admin controllers.
     */
    public function writePage()
    {
        $this->SetHeaderMargin(5);
        // $this->SetFooterMargin(21);
        // $this->SetMargins(10, 40, 10);
        $this->AddPage();
        $this->writeHTML($this->content, true, false, true, false, '');
    }
}
