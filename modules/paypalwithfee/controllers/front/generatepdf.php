<?php
/**
 * 2014 4webs
 *
 * DEVELOPED By 4webs.es Prestashop Platinum Partner
 *
 * @author    4webs
 * @copyright 4webs 2014
 * @license   4webs
 * @version 4.0.2
 * @category payment_gateways
 */

class PayPalwithFeeGeneratepdfModuleFrontController extends ModuleFrontControllerCore {

    public $php_self = 'generatepdfppwf';
    public $ssl = true;

    public function init() {

        if (Tools::getValue('action') == 'IsOrderPpwf' && Tools::getValue('ajax')) {
            $this->ajaxProcessIsOrderPpwf();
        }

        $id_order = Tools::getValue('id_order');
        $order = new Order($id_order);

        $paypalwithfee = new PayPalwithFee();

        if (version_compare(_PS_VERSION_, '1.6', '>='))
            $this->generatePpwfPDF($order,$id_order);
        else
            $this->generatePpwfPDF15($order);
    }

    protected function generatePpwfPDF($order,$id_order) {
        $id_order_invoice = $order->invoice_number;
        $invoice = $this->getInvoiceByNumber($id_order,$id_order_invoice);
        $html_template_invoice = new HTMLTemplateInvoice($invoice, Context::getContext()->smarty);
        $id_lang = Context::getContext()->language->id;
        $html_template_invoice->title = $invoice->getInvoiceNumberFormatted($id_lang, (int) $order->id_shop);
        $template = $html_template_invoice;
        $pdf = new PDFGenerator((bool) Configuration::get('PS_PDF_USE_CACHE'), 'P');

        $fee = $this->getFeeData($order->id);

        $content = $this->getPdfData($html_template_invoice, $order, $fee,$id_order);

        $pdf->createHeader($template->getHeader());
        $pdf->createContent($content);
        $pdf->createFooter($template->getFooter());
        $pdf->writePage();

        unset($template);
        return $pdf->render($id_order_invoice . '.pdf', true);
    }

    protected function generatePpwfPDF15($order) {
        $id_order_invoice = $order->invoice_number;
        $invoice = new OrderInvoice($id_order_invoice);
        $html_template_invoice = new HTMLTemplateInvoice($invoice, Context::getContext()->smarty);
        $id_lang = Context::getContext()->language->id;
        $html_template_invoice->title = $invoice->getInvoiceNumberFormatted($id_lang, (int) $order->id_shop);
        $template = $html_template_invoice;
        $pdf = new PDFGenerator((bool) Configuration::get('PS_PDF_USE_CACHE'), 'P');

        $fee = $this->getFeeData($order->id);

        $content = $this->getPDfData15($html_template_invoice, $order, $fee);

        $pdf->createHeader($template->getHeader());
        $pdf->createFooter($template->getFooter());
        $pdf->createContent($content);
        $pdf->writePage();

        unset($template);
        ob_end_clean();
        return $pdf->render($id_order_invoice . '.pdf', true);
    }

    public function getPdfData15($html_template_invoice, $order, $fee) {
        $invoice_address = new Address((int) $order->id_address_invoice);
        $formatted_invoice_address = AddressFormat::generateAddress($invoice_address, array(), '<br />', ' ');
        $formatted_delivery_address = '';

        if ($order->id_address_delivery != $order->id_address_invoice) {
            $delivery_address = new Address((int) $order->id_address_delivery);
            $formatted_delivery_address = AddressFormat::generateAddress($delivery_address, array(), '<br />', ' ');
        }

        $customer = new Customer((int) $order->id_customer);
        $id_order_invoice = $html_template_invoice->order->invoice_number;
        $order_invoice = new OrderInvoice($id_order_invoice);

        $smarty = array(
            'order' => $order,
            'order_details' => $order_invoice->getProducts(),
            'cart_rules' => $order->getCartRules($order_invoice->id),
            'delivery_address' => $formatted_delivery_address,
            'invoice_address' => $formatted_invoice_address,
            'tax_excluded_display' => Group::getPriceDisplayMethod($customer->id_default_group),
            'tax_tab' => $this->getTaxTabContent($order, $order_invoice, $html_template_invoice, $fee),
            'customer' => $customer,
            'fee' => $fee,
        );

        $html_template_invoice->smarty->assign($smarty);
        return $html_template_invoice->smarty->fetch(_PS_MODULE_DIR_ . '/paypalwithfee/views/templates/admin/ppalpdf15.tpl');
    }

    public function getTaxTabContent($order, $order_invoice, $html_template_invoice, $fee) {
        $invoice_address = new Address((int) $order->id_address_invoice);
        $tax_exempt = Configuration::get('VATNUMBER_MANAGEMENT') && !empty($invoice_address->vat_number) && $invoice_address->id_country != Configuration::get('VATNUMBER_COUNTRY');

        $html_template_invoice->smarty->assign(array(
            'tax_exempt' => $tax_exempt,
            'use_one_after_another_method' => $order_invoice->useOneAfterAnotherTaxComputationMethod(),
            'product_tax_breakdown' => $order_invoice->getProductTaxesBreakdown(),
            'shipping_tax_breakdown' => $order_invoice->getShippingTaxesBreakdown($order),
            'ecotax_tax_breakdown' => $order_invoice->getEcoTaxTaxesBreakdown(),
            'wrapping_tax_breakdown' => $order_invoice->getWrappingTaxesBreakdown(),
            'order' => $order,
            'order_invoice' => $order_invoice,
            'fee' => $fee
        ));

        return $html_template_invoice->smarty->fetch(_PS_MODULE_DIR_ . '/paypalwithfee/views/templates/admin/invoice-tax15.tpl');
    }

    public function getPdfData($html_template_invoice, $order, $fee,$id_order) {
        $invoiceAddressPatternRules = Tools::jsonDecode(Configuration::get('PS_INVCE_INVOICE_ADDR_RULES'), true);
        $deliveryAddressPatternRules = Tools::jsonDecode(Configuration::get('PS_INVCE_DELIVERY_ADDR_RULES'), true);

        $invoice_address = new Address((int) $html_template_invoice->order->id_address_invoice);
        $formatted_invoice_address = AddressFormat::generateAddress($invoice_address, array(), '<br />', ' ');
        $formatted_delivery_address = '';

        $delivery_address = null;
        $delivery_address = new Address((int) $html_template_invoice->order->id_address_delivery);
        $formatted_delivery_address = AddressFormat::generateAddress($delivery_address, array(), '<br />', ' ');

        $customer = new Customer((int) $html_template_invoice->order->id_customer);
        $id_order_invoice = $html_template_invoice->order->invoice_number;
        $order_invoice = $this->getInvoiceByNumber($id_order,$id_order_invoice);
        // header informations
        $this->date = Tools::displayDate($order_invoice->date_add);

        $id_lang = Context::getContext()->language->id;
        $this->title = $order_invoice->getInvoiceNumberFormatted($id_lang, (int) $order->id_shop);

        $this->shop = new Shop((int) $order->id_shop);

        $order_details = $order_invoice->getProducts();

        $has_discount = false;
        foreach ($order_details as $id => &$order_detail) {
            if ($order_detail['reduction_amount_tax_excl'] > 0) {
                $has_discount = true;
                $order_detail['unit_price_tax_excl_before_specific_price'] = $order_detail['unit_price_tax_excl_including_ecotax'] + $order_detail['reduction_amount_tax_excl'];
            } elseif ($order_detail['reduction_percent'] > 0) {
                $has_discount = true;
                $order_detail['unit_price_tax_excl_before_specific_price'] = (100 * $order_detail['unit_price_tax_excl_including_ecotax']) / (100 - $order_detail['reduction_percent']);
            }

            if (version_compare(_PS_VERSION_, '1.6.1.0', '<')) {
                $taxes = Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'order_detail_tax` WHERE `id_order_detail` = ' . (int) $id);
            } else {
                $taxes = OrderDetail::getTaxListStatic($id);
            }

            $tax_temp = array();
            foreach ($taxes as $tax) {
                $obj = new Tax($tax['id_tax']);
                $tax_temp[] = sprintf(('%1$s%2$s%%'), ($obj->rate + 0), '&nbsp;');
            }

            $order_detail['order_detail_tax'] = $taxes;
            $order_detail['order_detail_tax_label'] = implode(', ', $tax_temp);
        }
        unset($tax_temp);
        unset($order_detail);

        if (Configuration::get('PS_PDF_IMG_INVOICE')) {
            foreach ($order_details as &$order_detail) {
                if ($order_detail['image'] != null) {
                    $name = 'product_mini_' . (int) $order_detail['product_id'] . (isset($order_detail['product_attribute_id']) ? '_' . (int) $order_detail['product_attribute_id'] : '') . '.jpg';
                    $path = _PS_PROD_IMG_DIR_ . $order_detail['image']->getExistingImgPath() . '.jpg';

                    $order_detail['image_tag'] = preg_replace(
                            '/\.*' . preg_quote(__PS_BASE_URI__, '/') . '/', _PS_ROOT_DIR_ . DIRECTORY_SEPARATOR, ImageManager::thumbnail($path, $name, 45, 'jpg', false), 1
                    );

                    if (file_exists(_PS_TMP_IMG_DIR_ . $name)) {
                        $order_detail['image_size'] = getimagesize(_PS_TMP_IMG_DIR_ . $name);
                    } else {
                        $order_detail['image_size'] = false;
                    }
                }
            }
            unset($order_detail); // don't overwrite the last order_detail later
        }

        $cart_rules = $html_template_invoice->order->getCartRules($order_invoice->id);
        $free_shipping = false;
        foreach ($cart_rules as $key => $cart_rule) {
            if ($cart_rule['free_shipping']) {
                $free_shipping = true;
                /**
                 * Adjust cart rule value to remove the amount of the shipping.
                 * We're not interested in displaying the shipping discount as it is already shown as "Free Shipping".
                 */
                $cart_rules[$key]['value_tax_excl'] -= $order_invoice->total_shipping_tax_excl;
                $cart_rules[$key]['value'] -= $order_invoice->total_shipping_tax_incl;

                /**
                 * Don't display cart rules that are only about free shipping and don't create
                 * a discount on products.
                 */
                if ($cart_rules[$key]['value'] == 0) {
                    unset($cart_rules[$key]);
                }
            }
        }

        $product_taxes = 0;
        foreach ($order_invoice->getProductTaxesBreakdown($html_template_invoice->order) as $details) {
            $product_taxes += $details['total_amount'];
        }

        $product_discounts_tax_excl = $order_invoice->total_discount_tax_excl;
        $product_discounts_tax_incl = $order_invoice->total_discount_tax_incl;
        if ($free_shipping) {
            $product_discounts_tax_excl -= $order_invoice->total_shipping_tax_excl;
            $product_discounts_tax_incl -= $order_invoice->total_shipping_tax_incl;
        }

        $products_after_discounts_tax_excl = $order_invoice->total_products - $product_discounts_tax_excl;
        $products_after_discounts_tax_incl = $order_invoice->total_products_wt - $product_discounts_tax_incl;

        $shipping_tax_excl = $free_shipping ? 0 : $order_invoice->total_shipping_tax_excl;
        $shipping_tax_incl = $free_shipping ? 0 : $order_invoice->total_shipping_tax_incl;
        $shipping_taxes = $shipping_tax_incl - $shipping_tax_excl;

        $wrapping_taxes = $order_invoice->total_wrapping_tax_incl - $order_invoice->total_wrapping_tax_excl;

        $total_taxes = $order_invoice->total_paid_tax_incl - $order_invoice->total_paid_tax_excl;

        $footer = array(
            'products_before_discounts_tax_excl' => $order_invoice->total_products,
            'product_discounts_tax_excl' => $product_discounts_tax_excl,
            'products_after_discounts_tax_excl' => $products_after_discounts_tax_excl,
            'products_before_discounts_tax_incl' => $order_invoice->total_products_wt,
            'product_discounts_tax_incl' => $product_discounts_tax_incl,
            'products_after_discounts_tax_incl' => $products_after_discounts_tax_incl,
            'product_taxes' => $product_taxes,
            'shipping_tax_excl' => $shipping_tax_excl,
            'shipping_taxes' => $shipping_taxes,
            'shipping_tax_incl' => $shipping_tax_incl,
            'wrapping_tax_excl' => $order_invoice->total_wrapping_tax_excl,
            'wrapping_taxes' => $wrapping_taxes,
            'wrapping_tax_incl' => $order_invoice->total_wrapping_tax_incl,
            'ecotax_taxes' => $total_taxes - $product_taxes - $wrapping_taxes - $shipping_taxes,
            'total_taxes' => $total_taxes,
            'total_paid_tax_excl' => $order_invoice->total_paid_tax_excl,
            'total_paid_tax_incl' => $order_invoice->total_paid_tax_incl
        );


        if (version_compare(_PS_VERSION_, '1.6.1.0', '>='))
            foreach ($footer as $key => $value) {
                $footer[$key] = Tools::ps_round($value, _PS_PRICE_COMPUTE_PRECISION_, $html_template_invoice->order->round_mode);
            }



        /**
         * Need the $round_mode for the tests.
         */
        $round_type = null;
        if (version_compare(_PS_VERSION_, '1.6.1.0', '<')) {
            //$round_type = Configuration::get('PS_PRICE_ROUND_MODE');
            $round_type = 'line';
        } else {
            switch ($order->round_type) {
                case Order::ROUND_TOTAL:
                    $round_type = 'total';
                    break;
                case Order::ROUND_LINE:
                    $round_type = 'line';
                    break;
                case Order::ROUND_ITEM:
                    $round_type = 'item';
                    break;
                default:
                    $round_type = 'line';
                    break;
            }
        }

        $display_product_images = Configuration::get('PS_PDF_IMG_INVOICE');
        $tax_excluded_display = Group::getPriceDisplayMethod($customer->id_default_group);

        $legal_free_text = Hook::exec('displayInvoiceLegalFreeText', array('order' => $html_template_invoice->order));
        if (!$legal_free_text) {
            $legal_free_text = Configuration::get('PS_INVOICE_LEGAL_FREE_TEXT', (int) Context::getContext()->language->id, null, (int) $html_template_invoice->order->id_shop);
        }
        $layout = $this->computeLayout(array('has_discount' => $has_discount));



        /*         * calculate taxes * */
        $address_tax = new Address((int) $order->{Configuration::get('PS_TAX_ADDRESS_TYPE')});
        $tax_exempt = Configuration::get('VATNUMBER_MANAGEMENT') && !empty($address_tax->vat_number) && $address_tax->id_country != Configuration::get('VATNUMBER_COUNTRY');
        $carrier = new Carrier($html_template_invoice->order->id_carrier);


        $breakdowns = array(
            'product_tax' => $order_invoice->getProductTaxesBreakdown($html_template_invoice->order),
            'shipping_tax' => $order_invoice->getShippingTaxesBreakdown($html_template_invoice->order),
            'ecotax_tax' => $order_invoice->getEcoTaxTaxesBreakdown(),
            'wrapping_tax' => $order_invoice->getWrappingTaxesBreakdown(),
        );

        foreach ($breakdowns as $type => $bd) {
            if (version_compare(_PS_VERSION_, '1.6.1.0', '<')) {
                foreach ($bd as $key => $item) {
                    $breakdowns[$type][$item['name']]['rate'] = $bd[$key]['name'];
                }
            }
            if (empty($bd)) {
                unset($breakdowns[$type]);
            }
        }

        if (empty($breakdowns)) {
            $breakdowns = false;
        }

        if (isset($breakdowns['product_tax'])) {
            foreach ($breakdowns['product_tax'] as &$bd) {
                $bd['total_tax_excl'] = $bd['total_price_tax_excl'];
            }
        }

        if (isset($breakdowns['ecotax_tax'])) {
            foreach ($breakdowns['ecotax_tax'] as &$bd) {
                $bd['total_tax_excl'] = $bd['ecotax_tax_excl'];
                $bd['total_amount'] = $bd['ecotax_tax_incl'] - $bd['ecotax_tax_excl'];
            }
        }


        $tax_breakdowns = $breakdowns;

        /*         * end taxes * */

        if (version_compare(_PS_VERSION_, '1.6.1.0', '<')) {
            foreach ($order_details as $id => &$order_detail) {

                $order_detail['unit_price_tax_excl_before_specific_price'] = $order_detail['original_product_price'];
                $order_detail['unit_price_tax_excl_including_ecotax'] = $order_detail['unit_price_tax_excl'];
                $order_detail['total_price_tax_incl_including_ecotax'] = $order_detail['total_price_tax_incl'];
                $order_detail['unit_price_tax_incl_including_ecotax'] = $order_detail['unit_price_tax_incl'];
                $order_detail['total_price_tax_excl_including_ecotax'] = $order_detail['total_price_tax_excl'];
            }
        }


        $smarty = array(
            'order_invoice' => $order_invoice,
            'order' => $html_template_invoice->order,
            'order_details' => $order_details,
            'cart_rules' => $cart_rules,
            'tax_excluded_display' => $tax_excluded_display,
            'title' => $this->title,
            'display_product_images' => $display_product_images,
            'date' => $html_template_invoice->date,
            'addresses' => array('invoice' => $invoice_address, 'delivery' => $delivery_address),
            'delivery_address' => $formatted_delivery_address,
            'invoice_address' => $formatted_invoice_address,
            'footer' => $footer,
            'ps_price_compute_precision' => _PS_PRICE_COMPUTE_PRECISION_,
            'round_type' => $round_type,
            'legal_free_text' => $legal_free_text,
            'layout' => $layout,
            'customer' => $customer,
            'style_tab' => $html_template_invoice->smarty->fetch(_PS_MODULE_DIR_ . '/paypalwithfee/views/templates/admin/style-tab.tpl'),
            'tax_exempt' => $tax_exempt,
            'use_one_after_another_method' => $order_invoice->useOneAfterAnotherTaxComputationMethod(),
            'display_tax_bases_in_breakdowns' => version_compare(_PS_VERSION_, '1.6.1.0', '<') ? !$order_invoice->useOneAfterAnotherTaxComputationMethod() : $order_invoice->displayTaxBasesInProductTaxesBreakdown(),
            'product_tax_breakdown' => $order_invoice->getProductTaxesBreakdown($html_template_invoice->order),
            'shipping_tax_breakdown' => $order_invoice->getShippingTaxesBreakdown($html_template_invoice->order),
            'ecotax_tax_breakdown' => $order_invoice->getEcoTaxTaxesBreakdown(),
            'wrapping_tax_breakdown' => $order_invoice->getWrappingTaxesBreakdown(),
            'tax_breakdowns' => $tax_breakdowns,
            'carrier' => $carrier,
            'fee' => $fee,
        );

        $html_template_invoice->smarty->assign($smarty);
        return $html_template_invoice->smarty->fetch(_PS_MODULE_DIR_ . '/paypalwithfee/views/templates/admin/ppalpdf.tpl');
    }

    protected function computeLayout($params) {
        $layout = array(
            'reference' => array(
                'width' => 15,
            ),
            'product' => array(
                'width' => 40,
            ),
            'quantity' => array(
                'width' => 8,
            ),
            'tax_code' => array(
                'width' => 8,
            ),
            'unit_price_tax_excl' => array(
                'width' => 0,
            ),
            'total_tax_excl' => array(
                'width' => 0,
            )
        );

        if (isset($params['has_discount']) && $params['has_discount']) {
            $layout['before_discount'] = array('width' => 0);
            $layout['product']['width'] -= 7;
            $layout['reference']['width'] -= 3;
        }

        $total_width = 0;
        $free_columns_count = 0;
        foreach ($layout as $data) {
            if ($data['width'] === 0) {
                ++$free_columns_count;
            }

            $total_width += $data['width'];
        }

        $delta = 100 - $total_width;

        foreach ($layout as $row => $data) {
            if ($data['width'] === 0) {
                $layout[$row]['width'] = $delta / $free_columns_count;
            }
        }

        $layout['_colCount'] = count($layout);

        return $layout;
    }

    protected function getFeeData($id_order) {
        $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'ppwf_order` WHERE `id_order`=' . (int) $id_order;
        return Db::getInstance()->getRow($sql);
    }

    public function ajaxProcessIsOrderPpwf() {
        $id_order = Tools::getValue('id_order');
        $order = new Order($id_order);
        if ($order->module == $this->module->name) {
            if (version_compare(_PS_VERSION_, '1.6', '<')) {
                echo Tools::jsonEncode(array(
                    'is_ppwf' => true,
                    'id_order' => $id_order,
                    'href' => 'index.php?fc=module&module=paypalwithfee&controller=generatepdf&id_order=' . $id_order,
                ));
                die();
            } else {
                $this->ajaxDie(Tools::jsonEncode(array(
                            'is_ppwf' => true,
                            'id_order' => $id_order,
                            'href' => 'index.php?fc=module&module=paypalwithfee&controller=generatepdf&id_order=' . $id_order,
                )));
            }
        }
        if (version_compare(_PS_VERSION_, '1.6', '<')) {
            echo Tools::jsonEncode(array(
                'is_ppwf' => false,
                'id_order' => $id_order
            ));
            die();
        } else {
            $this->ajaxDie(Tools::jsonEncode(array(
                        'is_ppwf' => false,
                        'id_order' => $id_order
            )));
        }
    }
    
    private function getInvoiceByNumber($id_order,$id_invoice)
    {
        if (is_numeric($id_invoice)) {
            $id_invoice = (int)$id_invoice;
        } elseif (is_string($id_invoice)) {
            $matches = array();
            if (preg_match('/^(?:'.Configuration::get('PS_INVOICE_PREFIX', Context::getContext()->language->id).')\s*([0-9]+)$/i', $id_invoice, $matches)) {
                $id_invoice = $matches[1];
            }
        }
        if (!$id_invoice) {
            return false;
        }

        $id_order_invoice = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue(
            'SELECT `id_order_invoice` FROM `'._DB_PREFIX_.'order_invoice` WHERE number = '.(int)$id_invoice.' AND `id_order`='.(int)$id_order);


        return ($id_order_invoice ? new OrderInvoice($id_order_invoice) : false);
    }

}
