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
 * Class AdminDhlCommercialInvoiceController
 */
class AdminDhlCommercialInvoiceController extends ModuleAdminController
{
    /**
     * AdminDhlCommercialInvoiceController constructor.
     * @throws PrestaShopException
     */
    public function __construct()
    {
        require_once(dirname(__FILE__).'/../../api/loader.php');
        require_once(dirname(__FILE__).'/../../classes/DhlCommercialInvoice.php');
        require_once(dirname(__FILE__).'/../../classes/HTMLTemplateDhlInvoice.php');
        require_once(dirname(__FILE__).'/../../classes/DhlTools.php');
        require_once(dirname(__FILE__).'/../../classes/DhlOrder.php');
        require_once(dirname(__FILE__).'/../../classes/DhlAddress.php');
        require_once(dirname(__FILE__).'/../../classes/DhlPackage.php');
        require_once(dirname(__FILE__).'/../../classes/DhlLabel.php');
        require_once(dirname(__FILE__).'/../../classes/DhlService.php');
        require_once(dirname(__FILE__).'/../../classes/DhlExtracharge.php');
        require_once(dirname(__FILE__).'/../../classes/DhlPDFGenerator.php');

        $this->bootstrap = true;
        $this->context = Context::getContext();
        parent::__construct();
    }

    /**
     * @return bool
     * @throws Exception
     * @throws SmartyException
     */
    public function postProcess()
    {
        $idDhlLabel = (int) Tools::getValue('id_dhl_label');
        $action = Tools::getValue('action');
        $currentIndex = $this->context->link->getAdminLink('AdminDhlOrders');
        $this->context->smarty->assign(
            array(
                'currentIndex' => $currentIndex,
                'dhl_img_path' => $this->module->getPathUri().'views/img/',
            )
        );
        if ($action == 'create' && $idDhlLabel) {
            $dhlLabel = new DhlLabel((int) $idDhlLabel);
            if (!Validate::isLoadedObject($dhlLabel)) {
                $this->errors = $this->module->l('Dhl Label is not valid.', 'AdminDhlCommercialInvoice');

                return true;
            } else {
                $this->displayInvoiceForm($dhlLabel);
            }
        } elseif ($action == 'downloadinvoice' && $idDhlLabel) {
            $this->downloadInvoice($idDhlLabel);
        }

        return parent::postProcess();
    }

    /**
     * @param int $idDhlLabel
     */
    public function downloadInvoice($idDhlLabel)
    {
        $dhlCI = DhlCommercialInvoice::getByIdDhlLabel((int) $idDhlLabel);
        if (!Validate::isLoadedObject($dhlCI)) {
            return;
        }
        $data = base64_decode($dhlCI->pdf_string);
        header('Content-Type: application/pdf;base64,');
        die($data);
    }

    /**
     * @param DhlLabel $dhlLabel
     * @return bool
     * @throws Exception
     * @throws SmartyException
     */
    public function displayInvoiceForm(DhlLabel $dhlLabel)
    {
        $idOrder = (int) DhlLabel::getIdOrderByAWBNumber($dhlLabel->awb_number);
        $order = new Order((int) $idOrder);
        $dhlCI = DhlCommercialInvoice::getByIdDhlLabel($dhlLabel->id);
        if (Validate::isLoadedObject($dhlCI)) {
            $this->context->smarty->assign(
                array(
                    'errors'           => false,
                    'alreadyGenerated' => true,
                    'dhl_img_path'     => $this->module->getPathUri().'views/img/',
                    'link'             => $this->context->link,
                    'id_dhl_label'     => $dhlLabel->id,
                )
            );
            $header = $this->createTemplate('../_partials/dhl-header.tpl')->fetch();
            $content = $this->createTemplate('./_partials/dhl-invoice-result.tpl')->fetch();
            $this->content = $header.$content;

            return true;
        }
        $orderCurrencyIso = $order->id_currency;
        $currencyIso = new Currency((int) $orderCurrencyIso);
        $customerAddrDelivery = new Address((int) $order->id_address_delivery);
        $senderAddresses = DhlAddress::getAddressList();
        $updateDhlAddrLink = $this->context->link->getAdminLink('AdminModules').'&configure='.$this->module->name;
        $updateAddrLink = $this->context->link->getAdminLink('AdminAddresses');
        $updateAddrLink .= '&updateaddress&id_address='.$customerAddrDelivery->id;
        $defaultSenderAddrDelivery = (int) Configuration::get('DHL_DEFAULT_SENDER_ADDRESS');
        $incoterms = array(
            'DAP' => $this->module->l('Delivered At Place', 'AdminDhlCommercialInvoice'),
            'DDP' => $this->module->l('Delivered Duty Paid', 'AdminDhlCommercialInvoice'),
        );
        $exportationType = array(
            'P' => $this->module->l('Permanent', 'AdminDhlCommercialInvoice'),
            'T' => $this->module->l('Temporary', 'AdminDhlCommercialInvoice'),
            'R' => $this->module->l('Repair and Return', 'AdminDhlCommercialInvoice'),
        );
        $awbNumber = $dhlLabel->awb_number;
        $countries = Country::getCountries($this->context->language->id);
        $defaultCountry = Country::getByIso('FR');
        $orderDetails = OrderDetail::getList((int) $order->id);
        $this->context->smarty->assign(
            array(
                'smarty'                 => $this->context->smarty,
                'link'                   => $this->context->link,
                'id_dhl_label'           => $dhlLabel->id,
                'currency_iso'           => $currencyIso->iso_code,
                'weight_unit'            => DhlTools::getWeightUnit(),
                'sender_addresses'       => $senderAddresses,
                'default_sender_address' => $defaultSenderAddrDelivery,
                'update_dhl_addr_link'   => $updateDhlAddrLink,
                'update_addr_link'       => $updateAddrLink,
                'customer_address'       => $customerAddrDelivery,
                'customer_country_iso'   => DhlTools::getIsoCountryById((int) $customerAddrDelivery->id_country),
                'awb_number'             => $awbNumber,
                'incoterms'              => $incoterms,
                'exportation_type'       => $exportationType,
                'default_hs_code'        => Configuration::get('DHL_DEFAULT_HS_CODE'),
                'countries'              => $countries,
                'default_country'        => $defaultCountry,
                'order_details'          => $orderDetails,
            )
        );
        $this->content = $this->createTemplate('dhl_commercial_invoice.tpl')->fetch();

        return true;
    }

    /**
     *
     */
    public function ajaxProcessAddSupProduct()
    {
        $this->ajaxDie(
            Tools::jsonEncode(
                array(
                    'productRow' => array(
                        'shCode'        => Tools::safeOutput(Tools::getValue('dhl_supp_hs_code')),
                        'name'          => Tools::safeOutput(Tools::getValue('dhl_supp_pname')),
                        'originCountry' => Tools::safeOutput(
                            Country::getNameById(
                                $this->context->language->id,
                                (int) Tools::getValue('dhl_supp_country')
                            )
                        ),
                        'quantity'      => (int) Tools::getValue('dhl_supp_quantity'),
                        'weight'        => (float) Tools::getValue('dhl_supp_weight'),
                        'unitPrice'     => (float) Tools::getValue('dhl_supp_price'),
                    ),
                )
            )
        );
    }

    /**
     * @throws Exception
     * @throws PrestaShopException
     * @throws SmartyException
     */
    public function ajaxProcessGenerateInvoice()
    {
        $dhlLabel = new DhlLabel((int) Tools::getValue('id_dhl_label'));
        if (!Validate::isLoadedObject($dhlLabel)) {
            // @formatter:off
            $this->context->smarty->assign(
                array(
                    'errors'      => true,
                    'description' => $this->module->l('You must create a label before generating an invoice.', 'AdminDhlCommercialInvoice'),
                )
            );
            // @formatter:on
            $html = $this->createTemplate('_partials/dhl-invoice-result.tpl')->fetch();
            $return = array(
                'html' => $html,
            );
            $this->ajaxDie(Tools::jsonEncode($return));
        }
        $dhlOrder = new DhlOrder((int) $dhlLabel->id_dhl_order);
        if (!Validate::isLoadedObject($dhlOrder)) {
            $this->context->smarty->assign(
                array(
                    'errors'      => true,
                    'description' => $this->module->l('DHL order not valid.', 'AdminDhlCommercialInvoice'),
                )
            );
            $html = $this->createTemplate('_partials/dhl-invoice-result.tpl')->fetch();
            $return = array(
                'html' => $html,
            );
            $this->ajaxDie(Tools::jsonEncode($return));
        }
        $dhlCI = DhlCommercialInvoice::getByIdDhlLabel($dhlLabel->id);
        if (Validate::isLoadedObject($dhlCI)) {
            $this->context->smarty->assign(
                array(
                    'errors'           => false,
                    'alreadyGenerated' => true,
                    'description'      => $this->module->l('DHL order not valid.', 'AdminDhlCommercialInvoice'),
                    'link'             => $this->context->link,
                    'dhl_img_path'     => $this->module->getPathUri().'views/img/',
                    'id_dhl_label'     => $dhlLabel->id,
                )
            );
            $html = $this->createTemplate('_partials/dhl-invoice-result.tpl')->fetch();
            $return = array(
                'html' => $html,
            );
            $this->ajaxDie(Tools::jsonEncode($return));
        } else {
            $dhlCI = new DhlCommercialInvoice();
        }
        $requiredFields = array(
            'dhl_total_package',
        );
        foreach ($requiredFields as $requiredField) {
            if (!Tools::getValue($requiredField)) {
                // @formatter:off
                $this->context->smarty->assign(
                    array(
                        'errors'      => true,
                        'description' => $this->module->l('Please make sure you filled all required fields.', 'AdminDhlCommercialInvoice'),
                    )
                );
                // @formatter:on
                $html = $this->createTemplate('_partials/dhl-invoice-result.tpl')->fetch();
                $return = array(
                    'html' => $html,
                );
                $this->ajaxDie(Tools::jsonEncode($return));
            }
        }
        $idOrder = (int) $dhlOrder->id_order;
        $orderDetails = OrderDetail::getList((int) $idOrder);
        $order = new Order((int) $idOrder);
        $currency = new Currency((int) $order->id_currency);
        $productVars = array();
        $totalQuantity = 0;
        $totalDeclaredValue = 0;
        $totalWeight = 0;
        foreach ($orderDetails as $orderDetail) {
            if (Tools::isSubmit('dhl_od_country_'.(int) $orderDetail['id_order_detail'])) {
                $idCountry = Tools::getValue('dhl_od_country_'.(int) $orderDetail['id_order_detail']);
                $unitPrice = Tools::ps_round(Tools::getValue('dhl_od_price_'.(int) $orderDetail['id_order_detail']), 2);
                $qty = Tools::getValue('dhl_od_quantity_'.(int) $orderDetail['id_order_detail']);
                $weight = Tools::getValue('dhl_od_weight_'.(int) $orderDetail['id_order_detail']);
                $subtotalWeight = (float) $weight * (int) $qty;
                $totalPrice = (int) $qty * (float) $unitPrice;
                $productVars[] = array(
                    'product_name'         => Tools::getValue('dhl_od_pname_'.(int) $orderDetail['id_order_detail']),
                    'product_quantity'     => (int) $qty,
                    'unit_price_tax_excl'  => (float) $unitPrice,
                    'total_price_tax_excl' => (float) $totalPrice,
                    'product_weight'       => (float) $weight,
                    'subtotal_weight'      => (float) $subtotalWeight,
                    'origin'               => Country::getNameById($this->context->language->id, (int) $idCountry),
                    'commodity_code'       => Tools::getValue('dhl_od_hs_code_'.(int) $orderDetail['id_order_detail']),
                );
                $totalQuantity += (int) $qty;
                $totalDeclaredValue += (float) $totalPrice;
                $totalWeight += (float) $subtotalWeight;
            }
        }
        $posts = array_keys(Tools::getAllValues());
        foreach ($posts as $postKey) {
            if (Tools::substr($postKey, 0, 17) == 'dhl_supp_country_') {
                $id = (int) Tools::substr($postKey, 17);
                $country = Tools::getValue('dhl_supp_country_'.(int) $id);
                $unitPrice = Tools::ps_round(Tools::getValue('dhl_supp_price_'.(int) $id), 2);
                $qty = Tools::getValue('dhl_supp_quantity_'.(int) $id);
                $weight = Tools::getValue('dhl_supp_weight_'.(int) $id);
                $subtotalWeight = (float) $weight * (int) $qty;
                $totalPrice = (int) $qty * (float) $unitPrice;
                $productVars[] = array(
                    'product_name'         => Tools::getValue('dhl_supp_pname_'.(int) $id),
                    'product_quantity'     => (int) $qty,
                    'unit_price_tax_excl'  => (float) $unitPrice,
                    'total_price_tax_excl' => (float) $totalPrice,
                    'product_weight'       => (float) $weight,
                    'subtotal_weight'      => (float) $subtotalWeight,
                    'origin'               => $country,
                    'commodity_code'       => Tools::getValue('dhl_supp_hs_code_'.(int) $id),
                );
                $totalQuantity += (int) $qty;
                $totalDeclaredValue += (float) $totalPrice;
                $totalWeight += (float) $subtotalWeight;
            }
        }
        $dhlCI->moduleName = $this->module->name;
        $dhlCI->idOrder = $idOrder;
        $dhlCI->invoiceVars = array(
            'sender_details'    => new DhlAddress((int) Tools::getValue('dhl_sender_address')),
            'consignee_details' => new Address((int) Tools::getValue('dhl_customer_address')),
            'awb_number'        => $dhlLabel->awb_number,
            'declared_value'    => (float) $totalDeclaredValue,
            'total_package'     => Tools::getValue('dhl_total_package'),
            'total_weight'      => (float) $totalWeight,
            'incoterms'         => Tools::getValue('dhl_invoice_incoterms'),
            'total_quantity'    => $totalQuantity,
            'weight_unit'       => DhlTools::getWeightUnit(),
            'currency_code'     => $currency->iso_code,
        );
        $dhlCI->productVars = $productVars;
        $pdf = new PDF($dhlCI, 'DhlInvoice', $this->context->smarty);
        $pdf->pdf_renderer = new DhlPDFGenerator((bool) Configuration::get('PS_PDF_USE_CACHE'));
        $pdf->pdf_renderer->SetMargins(10, 10, 10);
        $pdf->pdf_renderer->SetFooterMargin(15);
        $pdfString = base64_encode($pdf->render(false));
        $dhlCI->id_dhl_label = $dhlLabel->id;
        $dhlCI->id_dhl_order = $dhlOrder->id;
        $dhlCI->pdf_string = $pdfString;
        if ($dhlCI->save()) {
            $this->context->smarty->assign(
                array(
                    'errors'           => false,
                    'alreadyGenerated' => false,
                    'link'             => $this->context->link,
                    'dhl_img_path'     => $this->module->getPathUri().'views/img/',
                    'id_dhl_label'     => $dhlLabel->id,
                )
            );
            $html = $this->createTemplate('_partials/dhl-invoice-result.tpl')->fetch();
            $return = array(
                'html' => $html,
            );
            $this->ajaxDie(Tools::jsonEncode($return));
        } else {
            $this->context->smarty->assign(
                array(
                    'errors'      => true,
                    'description' => $this->module->l('Cannot save DHL invoice locally.', 'AdminDhlCommercialInvoice'),
                )
            );
            $html = $this->createTemplate('_partials/dhl-invoice-result.tpl')->fetch();
            $return = array(
                'html' => $html,
            );
            $this->ajaxDie(Tools::jsonEncode($return));
        }
    }

    /**
     * @throws Exception
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws SmartyException
     */
    public function ajaxProcessDeleteInvoice()
    {
        /** @var Dhlexpress $module */
        $module = $this->module;
        $idDhlInvoice = Tools::getValue('id_dhl_commercial_invoice');
        $dhlCI = new DhlCommercialInvoice((int) $idDhlInvoice);
        if (!Validate::isLoadedObject($dhlCI)) {
            $this->ajaxDie(
                Tools::jsonEncode(
                    array(
                        'errors'  => true,
                        'message' => $module->l('Invoice not valid.', 'AdminDhlCommercialInvoice'),
                    )
                )
            );
        }
        $dhlOrder = new DhlOrder((int) $dhlCI->id_dhl_order);
        if (!Validate::isLoadedObject($dhlOrder)) {
            $this->ajaxDie(
                Tools::jsonEncode(
                    array(
                        'errors'  => true,
                        'message' => $module->l('Order not valid.', 'AdminDhlCommercialInvoice'),
                    )
                )
            );
        }
        if (!$dhlCI->delete()) {
            $this->ajaxDie(
                Tools::jsonEncode(
                    array(
                        'errors'  => true,
                        'message' => $module->l('Cannot delete invoice.', 'AdminDhlCommercialInvoice'),
                    )
                )
            );
        }
        $htmlTable = $module->getDhlShipmentDetailsTable($dhlOrder);
        $this->ajaxDie(
            Tools::jsonEncode(
                array(
                    'errors'  => false,
                    'message' => $module->l('Invoice deleted successfully', 'AdminDhlCommercialInvoice'),
                    'html'    => $htmlTable,
                )
            )
        );
    }
}
