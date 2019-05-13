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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class AdminSellerProductsController extends ModuleAdminController
{
    public function setMedia()
    {
        parent::setMedia();
        $this->context->controller->addJqueryPlugin('fancybox');
        $this->context->controller->addJS(_PS_MODULE_DIR_.'jmarketplace/views/js/back.js', 'all');
        if (Tools::isSubmit('addproduct')) {
            $this->context->controller->addjQueryPlugin(array('select2'));
            $this->context->controller->addJS(_PS_MODULE_DIR_.'jmarketplace/views/js/select2call.js');
        }
    }
    
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'product';
        $this->className = 'Product';
        $this->lang = true;
        $this->context = Context::getContext();
 
        parent::__construct();
        
        $this->fields_list = array(
            'id_product' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs',
                'orderby' => true,
                'search' => true,
                'havingFilter' => true,
            ),
            'product_name' => array(
                'title' => $this->l('Product name'),
                'orderby' => true,
                'search' => true,
                'havingFilter' => true,
            ),
            'seller_name' => array(
                'title' => $this->l('Seller name'),
                'orderby' => true,
                'search' => true,
                'havingFilter' => true,
            ),
            'date_add' => array(
                'title' => $this->l('Date add'),
                'type' => 'datetime',
                'orderby' => true,
                'search' => true,
                'havingFilter' => true,
            ),
            'date_upd' => array(
                'title' => $this->l('Date upd'),
                'type' => 'datetime',
                'orderby' => true,
                'search' => true,
                'havingFilter' => true,
            ),
            'active' => array(
                'title' => $this->l('Enabled'),
                'align' => 'center',
                'active' => 'status',
                'type' => 'bool',
                'class' => 'fixed-width-sm',
                'filter_key' => 'a!active',
                'orderby' => true,
                'search' => true,
                'havingFilter' => true,
            )
        );

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
                'icon' => 'icon-trash'
            )
        );
        
        $this->_select = '
            sp.id_seller_product, 
            b.name product_name, 
            s.name seller_name, 
            a.date_add, 
            a.active';

        $this->_join = '
            '.Shop::addSqlAssociation('product', 'a').' 
            LEFT JOIN '._DB_PREFIX_.'product_shop ps ON (ps.id_product = a.id_product AND ps.id_shop = '.(int)$this->context->shop->id.')
            LEFT JOIN '._DB_PREFIX_.'seller_product sp ON (sp.id_product = a.id_product)
            LEFT JOIN '._DB_PREFIX_.'seller s ON (s.id_seller = sp.id_seller_product)';

        $this->_where .= ' AND sp.id_seller_product != "" AND b.id_shop = '.(int)$this->context->shop->id;
    }

    public function renderList()
    {
        $this->addRowAction('view');
        //$this->addRowAction('edit');
        $this->addRowAction('delete');
        
        return parent::renderList();
    }
    
    public function renderForm()
    {
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Add association seller/product'),
                'icon' => 'icon-globe'
            ),
            'input' => array(
                array(
                    'type' => 'select',
                    'label' => $this->l('Seller'),
                    'name' => 'id_seller',
                    'required' => false,
                    'class'=> 'select2',
                    'options' => array(
                        'query' => Seller::getSellers($this->context->shop->id),
                        'id' => 'id_seller',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Product'),
                    'name' => 'id_product',
                    'required' => false,
                    'class'=> 'select2',
                    'options' => array(
                        'query' => SellerProduct::getSimpleProductsWithReference($this->context->language->id),
                        'id' => 'id_product',
                        'name' => 'refname',
                    )
                ),
            )
        );

        $this->fields_form['submit'] = array(
            'title' => $this->l('Save'),
        );

        return parent::renderForm();
    }

    public function postProcess()
    {
        if (!Tools::isSubmit('submitAddproduct')) {
            parent::postProcess();
        }
        
        if (Tools::getValue('submitFilterproduct')) {
            if (Tools::getValue('productFilter_id_product') != '') {
                $this->_where .= ' AND a.id_product = '.(int)Tools::getValue('productFilter_id_product');
            }
            
            if (Tools::getValue('productFilter_product_name') != '') {
                $this->_where .= ' AND b.name LIKE "%'.pSQL(Tools::getValue('productFilter_product_name')).'%"';
            }
            
            if (Tools::getValue('productFilter_seller_name') != '') {
                $this->_where .= ' AND s.name LIKE "%'.pSQL(Tools::getValue('productFilter_seller_name')).'%"';
            }
            
            if (Tools::getValue('productFilter_active') != '') {
                $this->_where .= ' AND a.active = '.(int)Tools::getValue('productFilter_active');
            }
            
            $arrayDateAdd = Tools::getValue('productFilter_date_add');
            if ($arrayDateAdd[0] != '' && $arrayDateAdd[1] != '') {
                $this->_where .= ' AND a.date_add BETWEEN "'.pSQL($arrayDateAdd[0]).'" AND "'.pSQL($arrayDateAdd[1]).'"';
            }
            
            $arrayDateUpd = Tools::getValue('productFilter_date_upd');
            if ($arrayDateUpd[0] != '' && $arrayDateUpd[1] != '') {
                $this->_where .= ' AND a.date_upd BETWEEN "'.pSQL($arrayDateUpd[0]).'" AND "'.pSQL($arrayDateUpd[1]).'"';
            }
        }
        
        $this->_orderBy = 'a.date_upd';
        $this->_orderWay = 'DESC';
        
        if (Tools::getValue('productOrderway')) {
            $this->_orderBy = pSQL(Tools::getValue('productOrderby'));
            $this->_orderWay = pSQL(Tools::getValue('productOrderway'));
        }
        
        if ($this->display == 'view') {
            $id_product = (int)Tools::getValue('id_product');
            $product = new Product($id_product, false, (int)$this->context->language->id, (int)$this->context->shop->id);
            $id_seller = Seller::getSellerByProduct($id_product);
            $seller = new Seller($id_seller);
            $this->context->smarty->assign('seller_name', $seller->name);
            
            if (Configuration::get('JMARKETPLACE_SHOW_IMAGES') == 1) {
                $images = $product->getImages((int)$this->context->language->id);
                $this->context->smarty->assign('images', $images);
            }
            
            if (Configuration::get('JMARKETPLACE_SHOW_TAX') == 1) {
                $taxRuleGroup = new TaxRulesGroup($product->id_tax_rules_group);
                $this->context->smarty->assign('tax_name', $taxRuleGroup->name);
            }
              
            if (Configuration::get('JMARKETPLACE_SHOW_CATEGORIES') == 1) {
                $categories = Product::getProductCategoriesFull($product->id);
                $categories_string = '';
                foreach ($categories as $c) {
                    $categories_string .= $c['name'].', ';
                }
                $categories_string = Tools::substr($categories_string, 0, -2);
                $this->context->smarty->assign(array(
                    'categories_string' => $categories_string,
                ));
            }
            
            if (Configuration::get('JMARKETPLACE_SHOW_SUPPLIERS') == 1) {
                $supplier_name = Supplier::getNameById($product->id_supplier);
                $this->context->smarty->assign('supplier_name', $supplier_name);
            }
            
            if (Configuration::get('JMARKETPLACE_SHOW_MANUFACTURERS') == 1) {
                $manufacturer_name = Manufacturer::getNameById($product->id_manufacturer);
                $this->context->smarty->assign('manufacturer_name', $manufacturer_name);
            }
            
            if (Configuration::get('JMARKETPLACE_SHOW_FEATURES') == 1) {
                $features = $product->getFrontFeatures($this->context->language->id);
                $this->context->smarty->assign('features', $features);
            }
            
            if (Configuration::get('JMARKETPLACE_SHOW_SHIP_PRODUCT') == 1) {
                $this->context->smarty->assign('carriers', $product->getCarriers());
            }
            
            if (Configuration::get('JMARKETPLACE_SHOW_ATTRIBUTES') == 1) {
                $attributes = $product->getAttributesResume($this->context->language->id);
                $this->context->smarty->assign('attributes', $attributes);
            }
            
            if (Configuration::get('JMARKETPLACE_SHOW_ATTACHMENTS') == 1) {
                $info_attachments = array();
                $product_attachments = Product::getAttachmentsStatic($this->context->language->id, $id_product);
                if (is_array($product_attachments) && count($product_attachments) > 0) {
                    $info_attachments = array();
                    foreach ($product_attachments as $pa) {
                        $attachment = new Attachment($pa['id_attachment']);
                        $info_attachments[] = $attachment;
                    }
                }
                
                $this->context->smarty->assign(array(
                    'max_attachments' => Configuration::get('JMARKETPLACE_MAX_ATTACHMENTS'),
                    'product_attachments' => $product_attachments,
                    'info_attachments' => $info_attachments
                ));
            }

            $this->context->smarty->assign(array(
                'show_reference' => Configuration::get('JMARKETPLACE_SHOW_REFERENCE'),
                'show_isbn' => Configuration::get('JMARKETPLACE_SHOW_ISBN'),
                'show_ean13' => Configuration::get('JMARKETPLACE_SHOW_EAN13'),
                'show_upc' => Configuration::get('JMARKETPLACE_SHOW_UPC'),
                'show_width' => Configuration::get('JMARKETPLACE_SHOW_WIDTH'),
                'show_height' => Configuration::get('JMARKETPLACE_SHOW_HEIGHT'),
                'show_depth' => Configuration::get('JMARKETPLACE_SHOW_DEPTH'),
                'show_weight' => Configuration::get('JMARKETPLACE_SHOW_WEIGHT'),
                'show_shipping_product' => Configuration::get('JMARKETPLACE_SHOW_SHIP_PRODUCT'),
                'show_available_order' => Configuration::get('JMARKETPLACE_SHOW_AVAILABLE_ORD'),
                'show_show_price' => Configuration::get('JMARKETPLACE_SHOW_SHOW_PRICE'),
                'show_online_only' => Configuration::get('JMARKETPLACE_SHOW_ONLINE_ONLY'),
                'show_condition' => Configuration::get('JMARKETPLACE_SHOW_CONDITION'),
                'show_pcondition' => Configuration::get('JMARKETPLACE_SHOW_PCONDITION'),
                'show_quantity' => Configuration::get('JMARKETPLACE_SHOW_QUANTITY'),
                'show_minimal_quantity' => Configuration::get('JMARKETPLACE_SHOW_MINIMAL_QTY'),
                'show_availability' => Configuration::get('JMARKETPLACE_SHOW_AVAILABILITY'),
                'show_available_now' => Configuration::get('JMARKETPLACE_SHOW_AVAILABLE_NOW'),
                'show_available_later' => Configuration::get('JMARKETPLACE_SHOW_AVAILABLE_LAT'),
                'show_available_date' => Configuration::get('JMARKETPLACE_SHOW_AVAILABLE_DATE'),
                'show_wholesale_price' => Configuration::get('JMARKETPLACE_SHOW_WHOLESALEPRICE'),
                'show_price' => Configuration::get('JMARKETPLACE_SHOW_PRICE'),
                'show_unit_price' => Configuration::get('JMARKETPLACE_SHOW_UNIT_PRICE'),
                'show_tax' => Configuration::get('JMARKETPLACE_SHOW_TAX'),
                'show_on_sale' => Configuration::get('JMARKETPLACE_SHOW_ON_SALE'),
                'show_desc_short' => Configuration::get('JMARKETPLACE_SHOW_DESC_SHORT'),
                'show_desc' => Configuration::get('JMARKETPLACE_SHOW_DESC'),
                'show_meta_keywords' => Configuration::get('JMARKETPLACE_SHOW_META_KEYWORDS'),
                'show_meta_title' => Configuration::get('JMARKETPLACE_SHOW_META_TITLE'),
                'show_meta_desc' => Configuration::get('JMARKETPLACE_SHOW_META_DESC'),
                'show_link_rewrite' => Configuration::get('JMARKETPLACE_SHOW_LINK_REWRITE'),
                'show_images' => Configuration::get('JMARKETPLACE_SHOW_IMAGES'),
                'max_images' => Configuration::get('JMARKETPLACE_MAX_IMAGES'),
                'show_suppliers' => Configuration::get('JMARKETPLACE_SHOW_SUPPLIERS'),
                'show_new_suppliers' => Configuration::get('JMARKETPLACE_NEW_SUPPLIERS'),
                'show_manufacturers' => Configuration::get('JMARKETPLACE_SHOW_MANUFACTURERS'),
                'show_new_manufacturers' => Configuration::get('JMARKETPLACE_NEW_MANUFACTURERS'),
                'show_categories' => Configuration::get('JMARKETPLACE_SHOW_CATEGORIES'),
                'show_features' => Configuration::get('JMARKETPLACE_SHOW_FEATURES'),
                'show_attributes' => Configuration::get('JMARKETPLACE_SHOW_ATTRIBUTES'),
                'show_attachments' => Configuration::get('JMARKETPLACE_SHOW_ATTACHMENTS'),
                'product' => $product,
                'real_quantity' => Product::getRealQuantity($product->id, 0, 0, $this->context->shop->id),
                'out_of_stock' => StockAvailable::outOfStock($product->id, $this->context->shop->id),
                'url_product' => 'index.php?tab=AdminProducts&id_product='.(int)$product->id.'&updateproduct&token='.Tools::getAdminToken('AdminProducts'.(int)Tab::getIdFromClassName('AdminProducts').(int)$this->context->employee->id),
                'token' => $this->token,
            ));
            
            if ($product->is_virtual == 1) {
                $id_product_download = ProductDownload::getIdFromIdProduct($product->id);
                $product_download = new ProductDownload($id_product_download);
                $filename = ProductDownload::getFilenameFromIdProduct($product->id);
                $display_filename = ProductDownload::getFilenameFromFilename($filename);

                $this->context->smarty->assign(array(
                    'filename' => $filename,
                    'url_download' => 'index.php?tab=AdminSellerProducts&id_product='.(int)$product->id.'&download&key='.$filename.'&token='.$this->token,
                    'product_hash' => $product_download->getHash(),
                    'display_filename' => $display_filename,
                    'product_download_link' => $product_download->getHtmlLink(),
                    'virtual_product_nb_downloable' => $product_download->nb_downloadable,
                    'virtual_product_expiration_date' => $product_download->date_expiration,
                    'virtual_product_nb_days' => $product_download->nb_days_accessible,
                ));
            }
        }
        
        if (Tools::isSubmit('download')) {
            $this->downloadProduct();
        }

        //enviar email producto borrada si procede
        
        if (Tools::isSubmit('statusproduct')) {
            $id_product = (int)Tools::getValue('id_product');
            $this->reportSellerProductStatusChange($id_product);
        } elseif (Tools::isSubmit('submitSellerProductRejection')) {
            $id_product = (int)Tools::getValue('id_product');
            $reasons = (string)Tools::getValue('reasons');
            $product = new Product($id_product);
            $product->active = 0;
            $product->update();
            $this->reportSellerProductStatusChange($id_product, $reasons);
            $this->confirmations[] = $this->module->l('The declination message has been sent correctly.', 'AdminSellerProductsController');
        } elseif (Tools::isSubmit('submitAddproduct')) {
            $id_seller = (int)Tools::getValue('id_seller');
            $id_product = (int)Tools::getValue('id_product');
            
            if (!SellerProduct::existAssociationSellerProduct($id_product)) {
                SellerProduct::associateSellerProduct($id_seller, $id_product);
                $this->confirmations[] = $this->module->l('The selected product has been associated correctly.', 'AdminSellerProductsController');
            } else {
                $this->errors[] = $this->module->l('This product is already associated with a seller.', 'AdminSellerProductsController');
            }
        } elseif (Tools::isSubmit('submitBulkenableSelectionproduct')) {
            //enable products selected
            $products_selected = Tools::getValue('productBox');
            foreach ($products_selected as $id_product) {
                $this->reportSellerProductStatusChange($id_product);
            }
        } elseif (Tools::isSubmit('submitBulkdisableSelectionproduct')) {
            //disable products selected
            $products_selected = Tools::getValue('productBox');
            foreach ($products_selected as $id_product) {
                $this->reportSellerProductStatusChange($id_product);
            }
        } elseif (Tools::isSubmit('submitBulkdeleteproduct')) {
            //delete products selected
            $products_selected = Tools::getValue('productBox');
            foreach ($products_selected as $id_product) {
                $id_seller = SellerProduct::isSellerProduct($id_product);
                SellerProduct::deleteSellerProduct($id_seller, $id_product);
            }
        }
    }
    
    public function downloadProduct()
    {
        $filename = ProductDownload::getFilenameFromIdProduct(Tools::getValue('id_product'));
        $display_filename = ProductDownload::getFilenameFromFilename($filename);

        $file = _PS_DOWNLOAD_DIR_.$filename;
        $filename = $display_filename;

        $mimeType = false;

        if (empty($mimeType)) {
            $bName = basename($filename);
            $bName = explode('.', $bName);
            $bName = Tools::strtolower($bName[count($bName) - 1]);

            $mimeTypes = array(
                'ez' => 'application/andrew-inset',
                'hqx' => 'application/mac-binhex40',
                'cpt' => 'application/mac-compactpro',
                'doc' => 'application/msword',
                'oda' => 'application/oda',
                'pdf' => 'application/pdf',
                'ai' => 'application/postscript',
                'eps' => 'application/postscript',
                'ps' => 'application/postscript',
                'smi' => 'application/smil',
                'smil' => 'application/smil',
                'wbxml' => 'application/vnd.wap.wbxml',
                'wmlc' => 'application/vnd.wap.wmlc',
                'wmlsc' => 'application/vnd.wap.wmlscriptc',
                'bcpio' => 'application/x-bcpio',
                'vcd' => 'application/x-cdlink',
                'pgn' => 'application/x-chess-pgn',
                'cpio' => 'application/x-cpio',
                'csh' => 'application/x-csh',
                'dcr' => 'application/x-director',
                'dir' => 'application/x-director',
                'dxr' => 'application/x-director',
                'dvi' => 'application/x-dvi',
                'spl' => 'application/x-futuresplash',
                'gtar' => 'application/x-gtar',
                'hdf' => 'application/x-hdf',
                'js' => 'application/x-javascript',
                'skp' => 'application/x-koan',
                'skd' => 'application/x-koan',
                'skt' => 'application/x-koan',
                'skm' => 'application/x-koan',
                'latex' => 'application/x-latex',
                'nc' => 'application/x-netcdf',
                'cdf' => 'application/x-netcdf',
                'sh' => 'application/x-sh',
                'shar' => 'application/x-shar',
                'swf' => 'application/x-shockwave-flash',
                'sit' => 'application/x-stuffit',
                'sv4cpio' => 'application/x-sv4cpio',
                'sv4crc' => 'application/x-sv4crc',
                'tar' => 'application/x-tar',
                'tcl' => 'application/x-tcl',
                'tex' => 'application/x-tex',
                'texinfo' => 'application/x-texinfo',
                'texi' => 'application/x-texinfo',
                't' => 'application/x-troff',
                'tr' => 'application/x-troff',
                'roff' => 'application/x-troff',
                'man' => 'application/x-troff-man',
                'me' => 'application/x-troff-me',
                'ms' => 'application/x-troff-ms',
                'ustar' => 'application/x-ustar',
                'src' => 'application/x-wais-source',
                'xhtml' => 'application/xhtml+xml',
                'xht' => 'application/xhtml+xml',
                'zip' => 'application/zip',
                'au' => 'audio/basic',
                'snd' => 'audio/basic',
                'mid' => 'audio/midi',
                'midi' => 'audio/midi',
                'kar' => 'audio/midi',
                'mpga' => 'audio/mpeg',
                'mp2' => 'audio/mpeg',
                'mp3' => 'audio/mpeg',
                'aif' => 'audio/x-aiff',
                'aiff' => 'audio/x-aiff',
                'aifc' => 'audio/x-aiff',
                'm3u' => 'audio/x-mpegurl',
                'ram' => 'audio/x-pn-realaudio',
                'rm' => 'audio/x-pn-realaudio',
                'rpm' => 'audio/x-pn-realaudio-plugin',
                'ra' => 'audio/x-realaudio',
                'wav' => 'audio/x-wav',
                'pdb' => 'chemical/x-pdb',
                'xyz' => 'chemical/x-xyz',
                'bmp' => 'image/bmp',
                'gif' => 'image/gif',
                'ief' => 'image/ief',
                'jpeg' => 'image/jpeg',
                'jpg' => 'image/jpeg',
                'jpe' => 'image/jpeg',
                'png' => 'image/png',
                'tiff' => 'image/tiff',
                'tif' => 'image/tif',
                'djvu' => 'image/vnd.djvu',
                'djv' => 'image/vnd.djvu',
                'wbmp' => 'image/vnd.wap.wbmp',
                'ras' => 'image/x-cmu-raster',
                'pnm' => 'image/x-portable-anymap',
                'pbm' => 'image/x-portable-bitmap',
                'pgm' => 'image/x-portable-graymap',
                'ppm' => 'image/x-portable-pixmap',
                'rgb' => 'image/x-rgb',
                'xbm' => 'image/x-xbitmap',
                'xpm' => 'image/x-xpixmap',
                'xwd' => 'image/x-windowdump',
                'igs' => 'model/iges',
                'iges' => 'model/iges',
                'msh' => 'model/mesh',
                'mesh' => 'model/mesh',
                'silo' => 'model/mesh',
                'wrl' => 'model/vrml',
                'vrml' => 'model/vrml',
                'css' => 'text/css',
                'html' => 'text/html',
                'htm' => 'text/html',
                'asc' => 'text/plain',
                'txt' => 'text/plain',
                'rtx' => 'text/richtext',
                'rtf' => 'text/rtf',
                'sgml' => 'text/sgml',
                'sgm' => 'text/sgml',
                'tsv' => 'text/tab-seperated-values',
                'wml' => 'text/vnd.wap.wml',
                'wmls' => 'text/vnd.wap.wmlscript',
                'etx' => 'text/x-setext',
                'xml' => 'text/xml',
                'xsl' => 'text/xml',
                'mpeg' => 'video/mpeg',
                'mpg' => 'video/mpeg',
                'mpe' => 'video/mpeg',
                'qt' => 'video/quicktime',
                'mov' => 'video/quicktime',
                'mxu' => 'video/vnd.mpegurl',
                'avi' => 'video/x-msvideo',
                'movie' => 'video/x-sgi-movie',
                'ice' => 'x-conference-xcooltalk'
            );

            if (isset($mimeTypes[$bName])) {
                $mimeType = $mimeTypes[$bName];
            } else {
                $mimeType = 'application/octet-stream';
            }
        }

        if (ob_get_level() && ob_get_length() > 0) {
            ob_end_clean();
        }

        /* Set headers for download */
        header('Content-Transfer-Encoding: binary');
        header('Content-Type: '.$mimeType);
        header('Content-Length: '.filesize($file));
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        //prevents max execution timeout, when reading large files
        @set_time_limit(0);
        $fp = fopen($file, 'rb');

        if ($fp && is_resource($fp)) {
            while (!feof($fp)) {
                echo fgets($fp, 16384);
            }
        }
        exit;
    }
    
    public function reportSellerProductStatusChange($id_product, $reasons = false)
    {
        if (Configuration::get('JMARKETPLACE_SEND_PRODUCT_ACTIVE')) {
            $product = new Product($id_product, false, (int)$this->context->language->id, (int)$this->context->shop->id);
            Search::indexation(Tools::link_rewrite($product->name), $product->id);

            $id_seller = SellerProduct::isSellerProduct($id_product);
            $seller = new Seller($id_seller);

            if (Configuration::get('JMARKETPLACE_SEND_PRODUCT_ACTIVE')) {
                $id_seller_email = false;
                $to = $seller->email;
                $to_name = $seller->name;
                $from = Configuration::get('PS_SHOP_EMAIL');
                $from_name = Configuration::get('PS_SHOP_NAME');
                $template = 'base';

                if ($product->active == 1) {
                    $reference = 'product-activated';
                    $id_seller_email = SellerEmail::getIdByReference($reference);
                } else {
                    $reference = 'product-desactivated';
                    $id_seller_email = SellerEmail::getIdByReference($reference);
                }

                if ($id_seller_email) {
                    $seller_email = new SellerEmail($id_seller_email, $seller->id_lang);
                    $vars = array("{shop_name}", "{seller_name}", "{product_name}", "{reasons}");
                    $values = array(Configuration::get('PS_SHOP_NAME'), $seller->name, $product->name, $reasons);
                    $subject_var = $seller_email->subject;
                    $subject_value = str_replace($vars, $values, $subject_var);
                    $content_var = $seller_email->content;
                    $content_value = str_replace($vars, $values, $content_var);

                    $template_vars = array(
                        '{content}' => $content_value,
                        '{shop_name}' => Configuration::get('PS_SHOP_NAME')
                    );

                    $iso = Language::getIsoById($seller->id_lang);

                    if (file_exists(dirname(__FILE__).'/../../mails/'.$iso.'/'.$template.'.txt') && file_exists(dirname(__FILE__).'/../../mails/'.$iso.'/'.$template.'.html')) {
                        Mail::Send(
                            $seller->id_lang,
                            $template,
                            $subject_value,
                            $template_vars,
                            $to,
                            $to_name,
                            $from,
                            $from_name,
                            null,
                            null,
                            dirname(__FILE__).'/../../mails/'
                        );
                    }
                }
            }
        }
    }
}
