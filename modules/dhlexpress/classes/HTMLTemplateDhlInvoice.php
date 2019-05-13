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
 * Class HTMLTemplateDhlInvoice
 */
class HTMLTemplateDhlInvoice extends HTMLTemplate
{
    /** @var DhlCommercialInvoice $dhlInvoice */
    public $dhlInvoice;

    /** @var Context $context */
    public $context;

    /**
     * HTMLTemplateDhlInvoice constructor.
     * @param DhlCommercialInvoice $object
     * @param Smarty               $smarty
     */
    public function __construct($object, Smarty $smarty)
    {
        $this->smarty = $smarty;
        $this->dhlInvoice = $object;
        $this->context = Context::getContext();
        $this->shop = $this->context->shop;
    }

    /**
     * @return string
     */
    public function getHeader()
    {
        return '';
    }

    /**
     * @return string
     */
    public function getFooter()
    {
        return '';
    }

    /**
     * @return string
     */
    public function getPagination()
    {
        return parent::getPagination();
    }

    /**
     * @return string
     * @throws Exception
     * @throws SmartyException
     */
    public function getContent()
    {
        $this->context->smarty->assign(
            array(
                'date'          => date('Y-m-d'),
                'invoice_vars'  => $this->dhlInvoice->invoiceVars,
                'order_details' => $this->dhlInvoice->productVars,
            )
        );
        $tpl = _PS_MODULE_DIR_.$this->dhlInvoice->moduleName;
        $tpl .= '/views/templates/admin/dhl_commercial_invoice/pdf/content.tpl';

        return $this->context->smarty->fetch($tpl);
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return 'DhlCommercialInvoice_Order_'.$this->dhlInvoice->idOrder.'.pdf';
    }

    /**
     *
     */
    public function getBulkFilename()
    {
    }
}
