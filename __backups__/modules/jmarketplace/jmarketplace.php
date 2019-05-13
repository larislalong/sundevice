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

if (!defined('_PS_VERSION_')) {
    exit;
}
 
class Jmarketplace extends Module
{
    const INSTALL_SQL_FILE = 'install.sql';
    public $output;
    public $mime_types = array(
        'image/pjpeg',
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/tiff',
        'image/tif',
        'image/bmp',
        'text/comma-separated-values',
        'text/csv',
        'application/vnd.ms-excel',
        'application/force-download',
        'application/x-download',
        'application/octet-stream',
        'application/pdf',
        'text/plain',
        'application/excel',
        'application/vnd.msexcel',
        'text/anytext',
        'text/x-sql',
        'text/css',
        'text/richtext',
        'text/xml',
        'application/zip',
        'application/x-gzip',
        'application/x-compressed-zip',
        'application/x-zip-compressed',
        'application/msword',
        'application/rtf',
        'application/vnd.ms-excel',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
        'audio/mp3',
        'audio/mpeg',
        'audio/x-ms-wma',
        'video/x-ms-wmv',
        'video/mp4',
        'video/mpeg',
        'video/quicktime',
        'video/quicktime',
    );
    
    public function __construct()
    {
        $this->name = 'jmarketplace';
        $this->tab = 'market_place';
        $this->version = '5.0.0';
        $this->author = 'Jose Aguilar';
        $this->need_instance = 0;
        $this->bootstrap = true;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => '1.6.9.9');
        $this->module_key = "5116f4d7344d9fdd2e57b2bbc885852e";
        $this->author_address = '0x59Ec83d8050F28fFAf4E4E5e288114ac07F6B408';
        $this->controllers = array(
            'addproduct',
            'addseller',
            'contactseller',
            'editproduct',
            'editseller',
            'favoriteseller',
            'selleraccount',
            'sellercomments',
            'sellermessages',
            'orders',
            'sellerpayment',
            'sellerproductlist',
            'sellerproducts',
            'sellerprofile',
            'sellers',
            'dashboard',
            'sellerhistorycommissions',
            'carriers',
            'addcarrier',
            'editcarrier',
            'csvproducts',
            'sellerinvoice',
            'sellerinvoicehistory',
        );

        parent::__construct();

        $this->displayName = $this->l('JA Marketplace');
        $this->description = $this->l('Allow to your customers sell in your shop to exchange for a commission.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
        
        include_once dirname(__FILE__).'/classes/Seller.php';
        include_once dirname(__FILE__).'/classes/SellerProduct.php';
        include_once dirname(__FILE__).'/classes/SellerCommission.php';
        include_once dirname(__FILE__).'/classes/SellerCommissionHistory.php';
        include_once dirname(__FILE__).'/classes/SellerCommissionHistoryState.php';
        include_once dirname(__FILE__).'/classes/SellerOrder.php';
        include_once dirname(__FILE__).'/classes/SellerOrderDetail.php';
        include_once dirname(__FILE__).'/classes/SellerOrderHistory.php';
        include_once dirname(__FILE__).'/classes/SellerTransport.php';
        include_once dirname(__FILE__).'/classes/SellerPayment.php';
        include_once dirname(__FILE__).'/classes/SellerIncidence.php';
        include_once dirname(__FILE__).'/classes/SellerIncidenceMessage.php';
        include_once dirname(__FILE__).'/classes/SellerEmail.php';
        include_once dirname(__FILE__).'/classes/SellerComment.php';
        include_once dirname(__FILE__).'/classes/SellerCommentCriterion.php';
        include_once dirname(__FILE__).'/classes/SellerCategory.php';
        include_once dirname(__FILE__).'/classes/CategoryTree.php';
        include_once dirname(__FILE__).'/classes/Dashboard.php';
        include_once dirname(__FILE__).'/classes/CSVSellerProduct.php';
        include_once dirname(__FILE__).'/classes/CSVSellerProductLog.php';
        include_once dirname(__FILE__).'/classes/SellerTransferCommission.php';
        include_once dirname(__FILE__).'/classes/SellerTransferInvoice.php';
    }
  
    public function install()
    {
        //GENERAL SETTINGS
        Configuration::updateValue('JMARKETPLACE_MODERATE_SELLER', 1);
        Configuration::updateValue('JMARKETPLACE_MODERATE_PRODUCT', 1);
        Configuration::updateValue('JMARKETPLACE_CUSTOMER_GROUP_3', 1);
        Configuration::updateValue('JMARKETPLACE_COMMISIONS_ORDER', 0);
        Configuration::updateValue('JMARKETPLACE_COMMISIONS_STATE', 1);
        Configuration::updateValue('JMARKETPLACE_ORDER_STATE_2', 1);
        Configuration::updateValue('JMARKETPLACE_FIXED_COMMISSION', 0);
        Configuration::updateValue('JMARKETPLACE_VARIABLE_COMMISSION', 70);
        Configuration::updateValue('JMARKETPLACE_SHIPPING_COMMISSION', 0);
        Configuration::updateValue('JMARKETPLACE_TAX_COMMISSION', 0);
        Configuration::updateValue('JMARKETPLACE_CANCEL_COMMISSION_6', 1);
        Configuration::updateValue('JMARKETPLACE_CANCEL_COMMISSION_7', 1);
        Configuration::updateValue('JMARKETPLACE_CANCEL_COMMISSION_8', 1);
        Configuration::updateValue('JMARKETPLACE_SHOW_CONTACT', 1);
        Configuration::updateValue('JMARKETPLACE_SHOW_DASHBOARD', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_SELLER_INVOICE', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_MANAGE_ORDERS', 0);
        Configuration::updateValue('JMARKETPLACE_SELL_ORDER_STATE_2', 1);
        Configuration::updateValue('JMARKETPLACE_SELL_ORDER_STATE_4', 1);
        Configuration::updateValue('JMARKETPLACE_SELL_ORDER_STATE_5', 1);
        Configuration::updateValue('JMARKETPLACE_SELL_ORDER_STATE_6', 1);
        Configuration::updateValue('JMARKETPLACE_SHOW_MANAGE_CARRIER', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_PROFILE', 1);
        Configuration::updateValue('JMARKETPLACE_SHOW_ORDERS', 1);
        Configuration::updateValue('JMARKETPLACE_SHOW_EDIT_ACCOUNT', 1);
        Configuration::updateValue('JMARKETPLACE_SHOW_EDIT_PRODUCT', 1);
        Configuration::updateValue('JMARKETPLACE_SHOW_DELETE_PRODUCT', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_ACTIVE_PRODUCT', 0);
        Configuration::updateValue('JMARKETPLACE_SELLER_FAVORITE', 0);
        Configuration::updateValue('JMARKETPLACE_SELLER_RATING', 0);
        Configuration::updateValue('JMARKETPLACE_NEW_PRODUCTS', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_SELLER_PLIST', 1);
        Configuration::updateValue('JMARKETPLACE_SELLER_IMPORT_PROD', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_ORDER_DETAIL', 0);
        
        //SELLER REGISTRATION FORM
        Configuration::updateValue('JMARKETPLACE_SHOW_SHOP_NAME', 1);
        Configuration::updateValue('JMARKETPLACE_SHOW_LANGUAGE', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_CIF', 1);
        Configuration::updateValue('JMARKETPLACE_SHOW_PHONE', 1);
        Configuration::updateValue('JMARKETPLACE_SHOW_FAX', 1);
        Configuration::updateValue('JMARKETPLACE_SHOW_ADDRESS', 1);
        Configuration::updateValue('JMARKETPLACE_SHOW_COUNTRY', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_STATE', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_CITY', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_POSTAL_CODE', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_DESCRIPTION', 1);
        Configuration::updateValue('JMARKETPLACE_SHOW_MTA_DESCRIPTION', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_MTA_TITLE', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_MTA_KEYWORDS', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_LOGO', 1);
        
        //SELLER PROFILE
        Configuration::updateValue('JMARKETPLACE_SHOW_PSHOP_NAME', 1);
        Configuration::updateValue('JMARKETPLACE_SHOW_PLANGUAGE', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_PCIF', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_PEMAIL', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_PPHONE', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_PFAX', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_PADDRESS', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_PCOUNTRY', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_PSTATE', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_PCITY', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_PPOSTAL_CODE', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_PDESCRIPTION', 1);
        Configuration::updateValue('JMARKETPLACE_SHOW_PLOGO', 1);
        
        //SELLER PRODUCT
        Configuration::updateValue('JMARKETPLACE_SHOW_REFERENCE', 1);
        Configuration::updateValue('JMARKETPLACE_SHOW_ISBN', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_EAN13', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_UPC', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_WIDTH', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_HEIGHT', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_DEPTH', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_WEIGHT', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_SHIP_PRODUCT', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_CONDITION', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_PCONDITION', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_AVAILABLE_ORD', 1);
        Configuration::updateValue('JMARKETPLACE_SHOW_SHOW_PRICE', 1);
        Configuration::updateValue('JMARKETPLACE_SHOW_ONLINE_ONLY', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_QUANTITY', 1);
        Configuration::updateValue('JMARKETPLACE_SHOW_MINIMAL_QTY', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_AVAILABILITY', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_AVAILABLE_NOW', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_AVAILABLE_LAT', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_AVAILABLE_DATE', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_PRICE', 1);
        Configuration::updateValue('JMARKETPLACE_SHOW_WHOLESALEPRICE', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_OFFER_PRICE', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_UNIT_PRICE', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_TAX', 1);
        Configuration::updateValue('JMARKETPLACE_SHOW_COMMISSION', 1);
        Configuration::updateValue('JMARKETPLACE_SHOW_ON_SALE', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_DESC_SHORT', 1);
        Configuration::updateValue('JMARKETPLACE_SHOW_DESC', 1);
        Configuration::updateValue('JMARKETPLACE_SHOW_META_KEYWORDS', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_META_TITLE', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_META_DESC', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_LINK_REWRITE', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_IMAGES', 1);
        Configuration::updateValue('JMARKETPLACE_MAX_IMAGES', 3);
        Configuration::updateValue('JMARKETPLACE_SHOW_SUPPLIERS', 0);
        Configuration::updateValue('JMARKETPLACE_NEW_SUPPLIERS', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_MANUFACTURERS', 0);
        Configuration::updateValue('JMARKETPLACE_NEW_MANUFACTURERS', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_CATEGORIES', 1);
        Configuration::updateValue('JMARKETPLACE_SHOW_FEATURES', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_ATTRIBUTES', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_VIRTUAL', 0);
        Configuration::updateValue('JMARKETPLACE_SHOW_ATTACHMENTS', 0);
        Configuration::updateValue('JMARKETPLACE_MAX_ATTACHMENTS', 3);
        
        //SELLER PAYMENT
        Configuration::updateValue('JMARKETPLACE_PAYPAL', 1);
        Configuration::updateValue('JMARKETPLACE_BANKWIRE', 1);
        
        //EMAIL
        Configuration::updateValue('JMARKETPLACE_SEND_ADMIN', Configuration::get('PS_SHOP_EMAIL'));
        Configuration::updateValue('JMARKETPLACE_SEND_ADMIN_REGISTER', 1);
        Configuration::updateValue('JMARKETPLACE_SEND_ADMIN_PRODUCT', 1);
        Configuration::updateValue('JMARKETPLACE_SEND_SELLER_WELCOME', 1);
        Configuration::updateValue('JMARKETPLACE_SEND_SELLER_ACTIVE', 1);
        Configuration::updateValue('JMARKETPLACE_SEND_PRODUCT_ACTIVE', 1);
        Configuration::updateValue('JMARKETPLACE_SEND_PRODUCT_SOLD', 1);
        Configuration::updateValue('JMARKETPLACE_SEND_ORDER_CHANGED', 1);
        Configuration::updateValue('JMARKETPLACE_SEND_ADMIN_INCIDENCE', 1);
        
        //SELLER COMMENT
        Configuration::updateValue('JMARKETPLACE_MODERATE_COMMENTS', 1);
        Configuration::updateValue('JMARKETPLACE_ALLOW_GUEST_COMMENT', 0);
        Configuration::updateValue('JMARKETPLACE_COMMENT_BOUGHT', 1);
        Configuration::updateValue('JMARKETPLACE_SEND_COMMENT_SELLER', 1);
        Configuration::updateValue('JMARKETPLACE_SEND_COMMENT_ADMIN', 1);
        
        //THEME FRONT OFFICE
        Configuration::updateValue('JMARKETPLACE_THEME', 'default-bootstrap');
        Configuration::updateValue('JMARKETPLACE_TABS', 1);
        Configuration::updateValue('JMARKETPLACE_MENU_OPTIONS', 0);
        Configuration::updateValue('JMARKETPLACE_MENU_TOP', 1);
        Configuration::updateValue('JMARKETPLACE_CUSTOM_STYLES', '');
        
        $languages = Language::getLanguages(false);
        $values = array();
        foreach ($languages as $lang) {
            $values['JMARKETPLACE_MAIN_ROUTE'][(int)$lang['id_lang']] = 'jmarketplace';
            Configuration::updateValue('JMARKETPLACE_MAIN_ROUTE', $values['JMARKETPLACE_MAIN_ROUTE']);
        }

        $values = array();
        foreach ($languages as $lang) {
            $values['JMARKETPLACE_ROUTE_PRODUCTS'][(int)$lang['id_lang']] = 'products';
            Configuration::updateValue('JMARKETPLACE_ROUTE_PRODUCTS', $values['JMARKETPLACE_ROUTE_PRODUCTS']);
        }

        $values = array();
        foreach ($languages as $lang) {
            $values['JMARKETPLACE_ROUTE_COMMENTS'][(int)$lang['id_lang']] = 'comments';
            Configuration::updateValue('JMARKETPLACE_ROUTE_COMMENTS', $values['JMARKETPLACE_ROUTE_COMMENTS']);
        }
        
        $token = uniqid(rand(), true);
        Configuration::updateValue('JMARKETPLACE_TOKEN', $token);
        
        Configuration::updateValue('JMARKETPLACE_EARNINGS_FROM', date('Y').'-01-01');
        Configuration::updateValue('JMARKETPLACE_EARNINGS_TO', date('Y-m-d'));
        
        $menu_jmarketplace = array(
            'en' => 'JA MarketPlace',
            'es' => 'JA MarketPlace',
            'fr' => 'JA MarketPlace',
            'it' => 'JA MarketPlace',
            'de' => 'JA MarketPlace',
            'br' => 'JA MarketPlace',
        );

        $this->createTab('AdminJmarketplace', $menu_jmarketplace);
            
        $menu_jmarketplace_sellers = array(
            'en' => 'Sellers',
            'es' => 'Vendedores',
            'fr' => 'Vendeurs',
            'it' => 'Venditori',
            'de' => 'Verkaufer',
            'br' => 'Sellers',
        );

        $this->createTab('AdminSellers', $menu_jmarketplace_sellers, 'AdminJmarketplace');

        $menu_jmarketplace_seller_products = array(
            'en' => 'Seller Products',
            'es' => 'Productos de los vendedores',
            'fr' => 'Produits des vendeurs',
            'it' => 'Prodotti venditore',
            'de' => 'Produkte Verkaufer',
            'br' => 'Produtos de vendedores',
        );

        $this->createTab('AdminSellerProducts', $menu_jmarketplace_seller_products, 'AdminJmarketplace');

        $menu_jmarketplace_seller_commissions = array(
            'en' => 'Seller Commissions',
            'es' => 'Comisiones de los vendedores',
            'fr' => 'Commissions des vendeurs',
            'it' => 'Commissioni  dei venditori',
            'de' => 'Provisionen von Anbietern',
            'br' => 'Seller Commissions',
        );

        $this->createTab('AdminSellerCommissions', $menu_jmarketplace_seller_commissions, 'AdminJmarketplace');

        $menu_jmarketplace_seller_commission_history = array(
            'en' => 'Seller Commissions History',
            'es' => 'Historial de comisiones',
            'fr' => 'Historique de commissions',
            'it' => 'Precedenti delle commissioni',
            'de' => 'Geschichte Kommissionen',
            'br' => 'Seller Commissions History',
        );

        $this->createTab('AdminSellerCommissionsHistory', $menu_jmarketplace_seller_commission_history, 'AdminJmarketplace');

        $menu_jmarketplace_seller_commission_history_states = array(
            'en' => 'Seller Payment States',
            'es' => 'Estado de los pagos',
            'fr' => 'Etats des paiements',
            'it' => 'Stati dei pagamenti',
            'de' => 'Zahlungsstatus',
            'br' => 'Seller Payment States',
        );

        $this->createTab('AdminSellerCommissionsHistoryStates', $menu_jmarketplace_seller_commission_history_states, 'AdminJmarketplace');
        
        $menu_jmarketplace_seller_orders = array(
            'en' => 'Seller Orders',
            'es' => 'Pedidos a los vendedores',
            'fr' => 'Commandes aux vendeurs',
            'it' => 'Ordini ai venditori',
            'de' => 'Auftrage an Verkaufer',
            'br' => 'Seller Orders',
        );

        $this->createTab('AdminSellerOrders', $menu_jmarketplace_seller_orders, 'AdminJmarketplace');

        $menu_jmarketplace_seller_comments = array(
            'en' => 'Ratings and comments',
            'es' => 'Valoraciones y comentarios',
            'fr' => 'Notes et commentaires',
            'it' => 'Valutazioni e commenti',
            'de' => 'Bewertungen und Kommentare',
            'br' => 'Ratings and comments',
        );

        $this->createTab('AdminSellerComments', $menu_jmarketplace_seller_comments, 'AdminJmarketplace');

        $menu_jmarketplace_incidences = array(
            'en' => 'Messages',
            'es' => 'Mensajes',
            'fr' => 'Messages',
            'it' => 'Messaggi',
            'de' => 'Beitrage',
            'br' => 'Messages',
        );

        $this->createTab('AdminSellerIncidences', $menu_jmarketplace_incidences, 'AdminJmarketplace');

        $menu_jmarketplace_seller_emails = array(
            'en' => 'Emails',
            'es' => 'Emails',
            'fr' => 'Emails',
            'it' => 'Emails',
            'de' => 'E-Mails',
            'br' => 'Emails',
        );
        
        $this->createTab('AdminSellerEmails', $menu_jmarketplace_seller_emails, 'AdminJmarketplace');
        
        $menu_jmarketplace_seller_dashboard = array(
            'en' => 'Earnings',
            'es' => 'Ganancias',
            'fr' => 'Gains',
            'it' => 'Guadagni',
            'de' => 'Einnahmen',
            'br' => 'Ganhos',
        );

        $this->createTab('AdminSellerDashboard', $menu_jmarketplace_seller_dashboard, 'AdminJmarketplace');
        
        $menu_jmarketplace_seller_invoices = array(
            'en' => 'Transfer Requests',
            'es' => 'Solicitudes de transferencia',
            'fr' => 'Demandes de transfert',
            'it' => 'Richieste di trasferimento',
            'de' => 'Transfers',
            'br' => 'Transfer Requests',
        );
        
        $this->createTab('AdminSellerInvoices', $menu_jmarketplace_seller_invoices, 'AdminJmarketplace');
        
        $this->addQuickAccess();
        
        if (!parent::install() ||
                !$this->registerHook('displayHeader') ||
                !$this->registerHook('backOfficeHeader') ||
                !$this->registerHook('displayCustomerAccount') ||
                !$this->registerHook('displayMyAccountBlock') ||
                !$this->registerHook('displayMyAccountBlockfooter') ||
                !$this->registerHook('displayProductButtons') ||
                !$this->registerHook('displayProductListReviews') ||
                !$this->registerHook('displayTop') ||
                !$this->registerHook('displayFooter') ||
                !$this->registerHook('displayOrderDetail') ||
                !$this->registerHook('actionValidateOrder') ||
                !$this->registerHook('actionProductDelete') ||
                !$this->registerHook('actionOrderStatusPostUpdate') ||
                !$this->registerHook('adminOrder') ||
                !$this->registerHook('moduleRoutes') ||
                !$this->registerHook('registerGDPRConsent') ||
                !$this->registerHook('actionDeleteGDPRCustomer') ||
                !$this->registerHook('actionExportGDPRData') ||
                !$this->createImageFolder('sellers') ||
                !$this->createTables() ||
                !$this->addData() ||
                !$this->createHook('displayMarketplaceHeader') ||
                !$this->createHook('displayMarketplaceMenu') ||
                !$this->createHook('displayMarketplaceAfterMenu') ||
                !$this->createHook('displayMarketplaceWidget') ||
                !$this->createHook('displayMarketplaceMenuOptions') ||
                !$this->createHook('displayMarketplaceFooter') ||
                !$this->createHook('displayMarketplaceFormAddProduct') ||
                !$this->createHook('actionMarketplaceAfterAddProduct') ||
                !$this->createHook('actionMarketplaceBeforeAddProduct') ||
                !$this->createHook('displayMarketplaceFormAddSeller') ||
                !$this->createHook('displayMarketplaceHeaderProfile') ||
                !$this->createHook('displayMarketplaceFooterProfile') ||
                !$this->createHook('actionMarketplaceAfterAddSeller') ||
                !$this->createHook('actionMarketplaceBeforeAddSeller') ||
                !$this->createHook('actionMarketplaceAfterUpdateSeller') ||
                !$this->createHook('actionMarketplaceBeforeUpdateSeller') ||
                !$this->createHook('actionMarketplaceAfterUpdateProduct') ||
                !$this->createHook('actionMarketplaceBeforeUpdateProduct') ||
                !$this->createHook('actionMarketplaceSellerProducts') ||
                !$this->createHook('displayMarketplaceTableProfile') ||
                !$this->createHook('displayMarketplaceFormAddProductTab') ||
                !$this->createHook('displayMarketplaceFormAddProductTabContent') ||
                !$this->createHook('actionMarketplaceBeforeAddSellerCommission') ||
                !$this->createHook('actionMarketplaceAfterAddSellerCommission') ||
                !$this->createHook('actionMarketplaceBeforeAddSellerOrder') ||
                !$this->createHook('actionMarketplaceAfterAddSellerOrder') ||
                !$this->createHook('displayMarketplaceAdminSeller') ||
                !$this->createHook('displayMarketplaceAdminSellerProduct')) {
            return false;
        }
        return true;
    }
  
    public function uninstall()
    {
        //GENERAL SETTINGS
        Configuration::deleteByName('JMARKETPLACE_MODERATE_SELLER');
        Configuration::deleteByName('JMARKETPLACE_MODERATE_PRODUCT');
        
        $logged_groups = $this->getGroupsToSeller();
        foreach ($logged_groups as $group) {
            Configuration::deleteByName('JMARKETPLACE_CUSTOMER_GROUP_'.$group['id_group']);
        }
        
        Configuration::deleteByName('JMARKETPLACE_COMMISIONS_ORDER');
        Configuration::deleteByName('JMARKETPLACE_COMMISIONS_STATE');
        Configuration::deleteByName('JMARKETPLACE_VARIABLE_COMMISSION');
        Configuration::deleteByName('JMARKETPLACE_FIXED_COMMISSION');
        Configuration::deleteByName('JMARKETPLACE_SHIPPING_COMMISSION');
        Configuration::deleteByName('JMARKETPLACE_TAX_COMMISSION');
        
        $states = OrderState::getOrderStates($this->context->language->id);
        foreach ($states as $state) {
            Configuration::deleteByName('JMARKETPLACE_CANCEL_COMMISSION_'.$state['id_order_state']);
            Configuration::deleteByName('JMARKETPLACE_ORDER_STATE_'.$state['id_order_state']);
            Configuration::deleteByName('JMARKETPLACE_SELL_ORDER_STATE_'.$state['id_order_state']);
        }
        
        Configuration::deleteByName('JMARKETPLACE_SHOW_CONTACT');
        Configuration::deleteByName('JMARKETPLACE_SHOW_DASHBOARD');
        Configuration::deleteByName('JMARKETPLACE_SHOW_SELLER_INVOICE');
        Configuration::deleteByName('JMARKETPLACE_SHOW_MANAGE_ORDERS');
        Configuration::deleteByName('JMARKETPLACE_SHOW_MANAGE_CARRIER');
        Configuration::deleteByName('JMARKETPLACE_SHOW_PROFILE');
        Configuration::deleteByName('JMARKETPLACE_SHOW_ORDERS');
        Configuration::deleteByName('JMARKETPLACE_SHOW_EDIT_ACCOUNT');
        Configuration::deleteByName('JMARKETPLACE_SHOW_EDIT_PRODUCT');
        Configuration::deleteByName('JMARKETPLACE_SHOW_DELETE_PRODUCT');
        Configuration::deleteByName('JMARKETPLACE_SHOW_ACTIVE_PRODUCT');
        Configuration::deleteByName('JMARKETPLACE_SELLER_FAVORITE');
        Configuration::deleteByName('JMARKETPLACE_SHOW_SELLER_RATING');
        Configuration::deleteByName('JMARKETPLACE_NEW_PRODUCTS');
        Configuration::deleteByName('JMARKETPLACE_SHOW_SELLER_PLIST');
        Configuration::deleteByName('JMARKETPLACE_SELLER_IMPORT_PROD');
        
        //SELLER REGISTRATION FORM
        Configuration::deleteByName('JMARKETPLACE_SHOW_SHOP_NAME');
        Configuration::deleteByName('JMARKETPLACE_SHOW_LANGUAGE');
        Configuration::deleteByName('JMARKETPLACE_SHOW_CIF');
        Configuration::deleteByName('JMARKETPLACE_SHOW_PHONE');
        Configuration::deleteByName('JMARKETPLACE_SHOW_FAX');
        Configuration::deleteByName('JMARKETPLACE_SHOW_ADDRESS');
        Configuration::deleteByName('JMARKETPLACE_SHOW_COUNTRY');
        Configuration::deleteByName('JMARKETPLACE_SHOW_STATE');
        Configuration::deleteByName('JMARKETPLACE_SHOW_CITY');
        Configuration::deleteByName('JMARKETPLACE_SHOW_POSTAL_CODE');
        Configuration::deleteByName('JMARKETPLACE_SHOW_DESCRIPTION');
        Configuration::deleteByName('JMARKETPLACE_SHOW_MTA_DESCRIPTION');
        Configuration::deleteByName('JMARKETPLACE_SHOW_MTA_TITLE');
        Configuration::deleteByName('JMARKETPLACE_SHOW_MTA_KEYWORDS');
        Configuration::deleteByName('JMARKETPLACE_SHOW_LOGO');
        
        //SELLER PROFILE
        Configuration::deleteByName('JMARKETPLACE_SHOW_PSHOP_NAME');
        Configuration::deleteByName('JMARKETPLACE_SHOW_PLANGUAGE');
        Configuration::deleteByName('JMARKETPLACE_SHOW_PEMAIL');
        Configuration::deleteByName('JMARKETPLACE_SHOW_PCIF');
        Configuration::deleteByName('JMARKETPLACE_SHOW_PPHONE');
        Configuration::deleteByName('JMARKETPLACE_SHOW_PFAX');
        Configuration::deleteByName('JMARKETPLACE_SHOW_PADDRESS');
        Configuration::deleteByName('JMARKETPLACE_SHOW_PCOUNTRY');
        Configuration::deleteByName('JMARKETPLACE_SHOW_PSTATE');
        Configuration::deleteByName('JMARKETPLACE_SHOW_PCITY');
        Configuration::deleteByName('JMARKETPLACE_SHOW_PPOSTAL_CODE');
        Configuration::deleteByName('JMARKETPLACE_SHOW_PDESCRIPTION');
        Configuration::deleteByName('JMARKETPLACE_SHOW_PLOGO');
        
        //SELLER PRODUCT
        Configuration::deleteByName('JMARKETPLACE_SHOW_REFERENCE');
        Configuration::deleteByName('JMARKETPLACE_SHOW_ISBN');
        Configuration::deleteByName('JMARKETPLACE_SHOW_EAN13');
        Configuration::deleteByName('JMARKETPLACE_SHOW_UPC');
        Configuration::deleteByName('JMARKETPLACE_SHOW_WIDTH');
        Configuration::deleteByName('JMARKETPLACE_SHOW_HEIGHT');
        Configuration::deleteByName('JMARKETPLACE_SHOW_DEPTH');
        Configuration::deleteByName('JMARKETPLACE_SHOW_WEIGHT');
        Configuration::deleteByName('JMARKETPLACE_SHOW_SHIP_PRODUCT');
        Configuration::deleteByName('JMARKETPLACE_SHOW_AVAILABLE_ORD');
        Configuration::deleteByName('JMARKETPLACE_SHOW_SHOW_PRICE');
        Configuration::deleteByName('JMARKETPLACE_SHOW_ONLINE_ONLY');
        Configuration::deleteByName('JMARKETPLACE_SHOW_CONDITION');
        Configuration::deleteByName('JMARKETPLACE_SHOW_PCONDITION');
        Configuration::deleteByName('JMARKETPLACE_SHOW_QUANTITY');
        Configuration::deleteByName('JMARKETPLACE_SHOW_MINIMAL_QTY');
        Configuration::deleteByName('JMARKETPLACE_SHOW_AVAILABILITY');
        Configuration::deleteByName('JMARKETPLACE_SHOW_AVAILABLE_NOW');
        Configuration::deleteByName('JMARKETPLACE_SHOW_AVAILABLE_LAT');
        Configuration::deleteByName('JMARKETPLACE_SHOW_AVAILABLE_DATE');
        Configuration::deleteByName('JMARKETPLACE_SHOW_PRICE');
        Configuration::deleteByName('JMARKETPLACE_SHOW_WHOLESALEPRICE');
        Configuration::deleteByName('JMARKETPLACE_SHOW_OFFER_PRICE');
        Configuration::deleteByName('JMARKETPLACE_SHOW_UNIT_PRICE');
        Configuration::deleteByName('JMARKETPLACE_SHOW_TAX');
        Configuration::deleteByName('JMARKETPLACE_SHOW_COMMISSION');
        Configuration::deleteByName('JMARKETPLACE_SHOW_ON_SALE');
        Configuration::deleteByName('JMARKETPLACE_SHOW_DESC_SHORT');
        Configuration::deleteByName('JMARKETPLACE_SHOW_DESC');
        Configuration::deleteByName('JMARKETPLACE_SHOW_META_KEYWORDS');
        Configuration::deleteByName('JMARKETPLACE_SHOW_META_TITLE');
        Configuration::deleteByName('JMARKETPLACE_SHOW_LINK_REWRITE');
        Configuration::deleteByName('JMARKETPLACE_SHOW_META_DESC');
        Configuration::deleteByName('JMARKETPLACE_SHOW_IMAGES');
        Configuration::deleteByName('JMARKETPLACE_MAX_IMAGES');
        Configuration::deleteByName('JMARKETPLACE_SHOW_SUPPLIERS');
        Configuration::deleteByName('JMARKETPLACE_NEW_SUPPLIERS');
        Configuration::deleteByName('JMARKETPLACE_SHOW_MANUFACTURERS');
        Configuration::deleteByName('JMARKETPLACE_NEW_MANUFACTURERS');
        Configuration::deleteByName('JMARKETPLACE_SHOW_CATEGORIES');
        Configuration::deleteByName('JMARKETPLACE_SHOW_FEATURES');
        Configuration::deleteByName('JMARKETPLACE_SHOW_ATTRIBUTES');
        Configuration::deleteByName('JMARKETPLACE_SHOW_VIRTUAL');
        Configuration::deleteByName('JMARKETPLACE_SHOW_ATTACHMENTS');
        Configuration::deleteByName('JMARKETPLACE_MAX_ATTACHMENTS');
        
        //SELLER PAYMENT
        Configuration::deleteByName('JMARKETPLACE_PAYPAL');
        Configuration::deleteByName('JMARKETPLACE_BANKWIRE');
        
        //EMAIL
        Configuration::deleteByName('JMARKETPLACE_SEND_ADMIN');
        Configuration::deleteByName('JMARKETPLACE_SEND_ADMIN_REGISTER');
        Configuration::deleteByName('JMARKETPLACE_SEND_ADMIN_PRODUCT');
        Configuration::deleteByName('JMARKETPLACE_SEND_SELLER_WELCOME');
        Configuration::deleteByName('JMARKETPLACE_SEND_SELLER_ACTIVE');
        Configuration::deleteByName('JMARKETPLACE_SEND_PRODUCT_ACTIVE');
        Configuration::deleteByName('JMARKETPLACE_SEND_PRODUCT_SOLD');
        Configuration::deleteByName('JMARKETPLACE_SEND_ORDER_CHANGED');
        Configuration::deleteByName('JMARKETPLACE_SEND_ADMIN_INCIDENCE');
        
        //SELLER COMMENT
        Configuration::deleteByName('JMARKETPLACE_MODERATE_COMMENTS');
        Configuration::deleteByName('JMARKETPLACE_ALLOW_GUEST_COMMENT');
        Configuration::deleteByName('JMARKETPLACE_COMMENT_BOUGHT');
        Configuration::deleteByName('JMARKETPLACE_SEND_COMMENT_SELLER');
        Configuration::deleteByName('JMARKETPLACE_SEND_COMMENT_ADMIN');
        
        //THEME FRONT OFFICE
        Configuration::deleteByName('JMARKETPLACE_THEME');
        Configuration::deleteByName('JMARKETPLACE_TABS');
        Configuration::deleteByName('JMARKETPLACE_MENU_OPTIONS');
        Configuration::deleteByName('JMARKETPLACE_MENU_TOP');
        Configuration::deleteByName('JMARKETPLACE_CUSTOM_STYLES');
        
        Configuration::deleteByName('JMARKETPLACE_TOKEN');
        
        $this->deleteQuickAccess();
        
        Configuration::deleteByName('JMARKETPLACE_QUICK_ACCESS');
        Configuration::deleteByName('JMARKETPLACE_EARNINGS_FROM');
        Configuration::deleteByName('JMARKETPLACE_EARNINGS_TO');
        
        $this->deleteTab('AdminSellers');
        $this->deleteTab('AdminSellerProducts');
        $this->deleteTab('AdminSellerCommissions');
        $this->deleteTab('AdminSellerCommissionsHistory');
        $this->deleteTab('AdminSellerCommissionsHistoryStates');
        $this->deleteTab('AdminSellerOrders');
        $this->deleteTab('AdminSellerIncidences');
        $this->deleteTab('AdminSellerEmails');
        $this->deleteTab('AdminSellerDashboard');
        $this->deleteTab('AdminJmarketplace');

        if (!parent::uninstall() ||
            !$this->deleteTables() ||
            !Tools::deleteDirectory(_PS_IMG_DIR_.'sellers')) {
            return false;
        }
        return true;
    }
    
    public function createTables()
    {
        if (!file_exists(dirname(__FILE__) . '/' . self::INSTALL_SQL_FILE)) {
            return false;
        } elseif (!$sql = Tools::file_get_contents(dirname(__FILE__) . '/' . self::INSTALL_SQL_FILE)) {
            return false;
        }
        
        $sql = str_replace(array('PREFIX_', 'ENGINE_TYPE'), array(_DB_PREFIX_, _MYSQL_ENGINE_), $sql);
        $sql = preg_split("/;\s*[\r\n]+/", $sql);
        
        foreach ($sql as $query) {
            if ($query) {
                if (!Db::getInstance()->execute(trim($query))) {
                    return false;
                }
            }
        }
        return true;
    }
    
    public function addData()
    {
        $this->createStates();
        $this->addSellerCategories();
        $this->createEmails();
        
        return true;
    }
    
    public function createStates()
    {
        //states
        $state = new SellerCommissionHistoryState();
        
        $state->active = 1;
        $state->reference = 'pending';
        foreach (Language::getLanguages() as $lang) {
            if ($lang['iso_code'] == 'es' || $lang['iso_code'] == 'mx' || $lang['iso_code'] == 'co' || $lang['iso_code'] == 'cb' || $lang['iso_code'] == 'ar') {
                $state->name[$lang['id_lang']] = 'Pendiente';
            } else {
                $state->name[$lang['id_lang']] = 'Pending';
            }
        }
        $state->add();
        
        $state->active = 1;
        $state->reference = 'paid';
        foreach (Language::getLanguages() as $lang) {
            if ($lang['iso_code'] == 'es' || $lang['iso_code'] == 'mx' || $lang['iso_code'] == 'co' || $lang['iso_code'] == 'cb' || $lang['iso_code'] == 'ar') {
                $state->name[$lang['id_lang']] = 'Pagado';
            } else {
                $state->name[$lang['id_lang']] = 'Paid';
            }
        }
        $state->add();
        
        $state->active = 1;
        $state->reference = 'cancel';
        foreach (Language::getLanguages() as $lang) {
            if ($lang['iso_code'] == 'es' || $lang['iso_code'] == 'mx' || $lang['iso_code'] == 'co' || $lang['iso_code'] == 'cb' || $lang['iso_code'] == 'ar') {
                $state->name[$lang['id_lang']] = 'Cancelado';
            } else {
                $state->name[$lang['id_lang']] = 'Cancel';
            }
        }
        $state->add();
    }
    
    public function createEmails()
    {
        foreach (Language::getLanguages() as $lang) {
            if ($lang['iso_code'] == 'es' ||
                    $lang['iso_code'] == 'mx' ||
                    $lang['iso_code'] == 'co' ||
                    $lang['iso_code'] == 'cb' ||
                    $lang['iso_code'] == 'pe' ||
                    $lang['iso_code'] == 'ar') {
                $this->createEmailByIsoCode('es', $lang);
            } elseif ($lang['iso_code'] == 'fr') {
                $this->createEmailByIsoCode('fr', $lang);
            } elseif ($lang['iso_code'] == 'it') {
                $this->createEmailByIsoCode('it', $lang);
            } elseif ($lang['iso_code'] == 'de') {
                $this->createEmailByIsoCode('de', $lang);
            } else {
                $this->createEmailByIsoCode('en', $lang);
            }
        }
    }

    public function createEmailByIsoCode($iso_code, $lang, $line = false)
    {
        if (Configuration::get('PS_SSL_ENABLED') == 1) {
            $url_shop = Tools::getShopDomainSsl(true).__PS_BASE_URI__;
        } else {
            $url_shop = Tools::getShopDomain(true).__PS_BASE_URI__;
        }

        $handle = fopen(dirname(__FILE__).'/database/emails/'.$iso_code.'.csv', 'r');
        $row = 0;
        while ($data = fgetcsv($handle, 9999, ";")) {
            if (($line == false && $row > 0) || ($line != false && $row == $line)) {
                $id_seller_email = SellerEmail::getIdByReference($data[0]);
                if ($id_seller_email > 0) {
                    $seller_email = new SellerEmail($id_seller_email);
                    $seller_email->subject[$lang['id_lang']] = trim((string)$data[1]);
                    $seller_email->description[$lang['id_lang']] = trim((string)$data[2]);
                    $content = str_replace("{shop_url}", $url_shop, Tools::stripslashes(trim((string)$data[3])));
                    $seller_email->content[$lang['id_lang']] = $content;
                    $seller_email->update();
                } else {
                    $seller_email = new SellerEmail();
                    $seller_email->reference = trim((string)$data[0]);
                    $seller_email->subject[$lang['id_lang']] = trim((string)$data[1]);
                    $seller_email->description[$lang['id_lang']] = trim((string)$data[2]);
                    $content = str_replace("{shop_url}", $url_shop, Tools::stripslashes(trim((string)$data[3])));
                    $seller_email->content[$lang['id_lang']] = $content;
                    $seller_email->add();
                }
            }
            $row++;
        }
        fclose($handle);
    }
    
    public function addSellerCategories()
    {
        $data_seller_category = array();
        $categories = Category::getSimpleCategories(Context::getContext()->language->id);
        foreach ($categories as $category) {
            $data_seller_category[] = array(
                'id_category' => (int)$category['id_category'],
                'id_shop' => (int)Context::getContext()->shop->id,
            );
        }
        Db::getInstance()->insert('seller_category', $data_seller_category);
    }
    
    public function deleteTables()
    {
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'seller`');
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'seller_product`');
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'seller_incidence`');
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'seller_incidence_message`');
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'seller_payment`');
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'seller_commission`');
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'seller_commission_history`');
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'seller_commission_history_state`');
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'seller_commission_history_state_lang`');
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'seller_favorite`');
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'seller_category`');
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'seller_carrier`');
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'seller_email`');
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'seller_email_lang`');
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'seller_comment`');
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'seller_comment_criterion`');
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'seller_comment_criterion_lang`');
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'seller_comment_grade`');
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'seller_order`');
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'seller_order_detail`');
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'seller_order_history`');
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'seller_transfer_invoice`');
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'seller_transfer_commission`');
        return true;
    }
    
    public function createTab($class_name, $tab_name, $tab_parent_name = false)
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = $class_name;
        $tab->name = array();
        
        foreach (Language::getLanguages(true) as $lang) {
            switch ($lang['iso_code']) {
                case 'es':
                    $tab->name[$lang['id_lang']] = $tab_name['es'];
                    break;
                case 'co':
                    $tab->name[$lang['id_lang']] = $tab_name['es'];
                    break;
                case 'cb':
                    $tab->name[$lang['id_lang']] = $tab_name['es'];
                    break;
                case 'ar':
                    $tab->name[$lang['id_lang']] = $tab_name['es'];
                    break;
                case 'mx':
                    $tab->name[$lang['id_lang']] = $tab_name['es'];
                    break;
                case 'fr':
                    $tab->name[$lang['id_lang']] = $tab_name['fr'];
                    break;
                case 'it':
                    $tab->name[$lang['id_lang']] = $tab_name['it'];
                    break;
                case 'br':
                    $tab->name[$lang['id_lang']] = $tab_name['br'];
                    break;
                default:
                    $tab->name[$lang['id_lang']] = $tab_name['en'];
                    break;
            }
        }
        
        if ($tab_parent_name) {
            $tab->id_parent = (int)Tab::getIdFromClassName($tab_parent_name);
        } else {
            $tab->id_parent = 0;
        }

        $tab->module = $this->name;
        return $tab->add();
    }
    
    public function updateTab($class_name, $tab_name)
    {
        $tab = Tab::getInstanceFromClassName($class_name);
        
        foreach (Language::getLanguages(true) as $lang) {
            switch ($lang['iso_code']) {
                case 'co':
                    $tab->name[$lang['id_lang']] = $tab_name['es'];
                    break;
                case 'cb':
                    $tab->name[$lang['id_lang']] = $tab_name['es'];
                    break;
                case 'ar':
                    $tab->name[$lang['id_lang']] = $tab_name['es'];
                    break;
                case 'mx':
                    $tab->name[$lang['id_lang']] = $tab_name['es'];
                    break;
                case 'fr':
                    $tab->name[$lang['id_lang']] = $tab_name['fr'];
                    break;
                case 'it':
                    $tab->name[$lang['id_lang']] = $tab_name['it'];
                    break;
                case 'br':
                    $tab->name[$lang['id_lang']] = $tab_name['br'];
                    break;
                default:
                    $tab->name[$lang['id_lang']] = $tab_name['en'];
                    break;
            }
        }

        return $tab->update();
    }
    
    public function deleteTab($class_name)
    {
        $id_tab = (int)Tab::getIdFromClassName($class_name);
        if ($id_tab) {
            $tab = new Tab($id_tab);
            return $tab->delete();
        } else {
            return false;
        }
    }
    
    private function createImageFolder($imageFolderName)
    {
        if (!is_dir(_PS_IMG_DIR_.$imageFolderName)) {
            if (!mkdir(_PS_IMG_DIR_.$imageFolderName, 0755)) {
                return false;
            }
        }
        return true;
    }
    
    public function removeThemeColumnByPage($page)
    {
        $meta = Meta::getMetaByPage($page, Context::getContext()->language->id);
        return Db::getInstance()->Execute(
            'UPDATE `'._DB_PREFIX_.'theme_meta` SET `left_column` = 0 WHERE id_meta = '.(int)$meta['id_meta']
        );
    }
    
    public function createHook($name)
    {
        $hook = Hook::getIdByName($name);
        if (!$hook) {
            $hook = new Hook();
            $hook->name = $name;
            $hook->save();
        }

        return $this->registerHook($name);
    }
    
    public function addQuickAccess()
    {
        $quick_access = new QuickAccess();
        $quick_access->link = $this->context->link->getAdminLink('AdminModules').'&configure=jmarketplace';

        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            $quick_access->name[$lang['id_lang']] = 'JA Marketplace Settings';
        }

        $quick_access->new_window = '0';
        
        if ($quick_access->save()) {
            Configuration::updateValue('JMARKETPLACE_QUICK_ACCESS', $quick_access->id);
        }
    }
    
    public function deleteQuickAccess()
    {
        $quick_access = new QuickAccess(Configuration::get('JMARKETPLACE_QUICK_ACCESS'));
        $quick_access->delete();
    }
    
    public function addMetas()
    {
        $this->controllers = array(
            'addproduct',
            'addseller',
            'contactseller',
            'editproduct',
            'editseller',
            'favoriteseller',
            'sellermessages',
            'sellerorders',
            'sellerpayment',
            'sellerproducts',
            'sellers',
        );
    }
    
    public function postProcess()
    {
        $errors = array();
        
        if (Tools::isSubmit('submitGeneralSettings')) {
            $fixed_commission = Tools::getValue('JMARKETPLACE_FIXED_COMMISSION');
            $variable_commission = Tools::getValue('JMARKETPLACE_VARIABLE_COMMISSION');
            
            if (!Validate::isFloat($fixed_commission)) {
                $errors[] = $this->l('Invalid fixed commission value.');
            }
            
            if ($variable_commission < 0 || $variable_commission > 100 || !Validate::isFloat($variable_commission)) {
                $errors[] = $this->l('Invalid variable commission value.');
            }

            if (count($errors) == 0) {
                Configuration::updateValue('JMARKETPLACE_MODERATE_SELLER', Tools::getValue('JMARKETPLACE_MODERATE_SELLER'));
                Configuration::updateValue('JMARKETPLACE_MODERATE_PRODUCT', Tools::getValue('JMARKETPLACE_MODERATE_PRODUCT'));

                $logged_groups = $this->getGroupsToSeller();

                $selected_group = false;
                foreach ($logged_groups as $group) {
                    if (Tools::getValue('JMARKETPLACE_CUSTOMER_GROUP_'.$group['id_group'])) {
                        $selected_group = true;
                    }
                }

                if ($selected_group) {
                    foreach ($logged_groups as $group) {
                        if (Tools::getValue('JMARKETPLACE_CUSTOMER_GROUP_'.$group['id_group'])) {
                            Configuration::updateValue('JMARKETPLACE_CUSTOMER_GROUP_'.$group['id_group'], 1);
                        } else {
                            Configuration::updateValue('JMARKETPLACE_CUSTOMER_GROUP_'.$group['id_group'], 0);
                        }
                    }
                } else {
                    $errors[] = $this->l('You must select at least one group.');
                }

                $states = OrderState::getOrderStates($this->context->language->id);
                $selected_state = false;
                foreach ($states as $state) {
                    if (Tools::getValue('JMARKETPLACE_ORDER_STATE_'.$state['id_order_state'])) {
                        $selected_state = true;
                    }
                }

                if ($selected_state) {
                    foreach ($states as $state) {
                        if (Tools::getValue('JMARKETPLACE_ORDER_STATE_'.$state['id_order_state'])) {
                            Configuration::updateValue('JMARKETPLACE_ORDER_STATE_'.$state['id_order_state'], 1);
                        } else {
                            Configuration::updateValue('JMARKETPLACE_ORDER_STATE_'.$state['id_order_state'], 0);
                        }
                    }
                } else {
                    $errors[] = $this->l('You must select at least one order state for assign commissions.');
                }
                
                $selected_state = false;
                foreach ($states as $state) {
                    if (Tools::getValue('JMARKETPLACE_CANCEL_COMMISSION_'.$state['id_order_state'])) {
                        $selected_state = true;
                    }
                }

                if ($selected_state) {
                    foreach ($states as $state) {
                        if (Tools::getValue('JMARKETPLACE_CANCEL_COMMISSION_'.$state['id_order_state'])) {
                            Configuration::updateValue('JMARKETPLACE_CANCEL_COMMISSION_'.$state['id_order_state'], 1);
                        } else {
                            Configuration::updateValue('JMARKETPLACE_CANCEL_COMMISSION_'.$state['id_order_state'], 0);
                        }
                    }
                } else {
                    $errors[] = $this->l('You must select at least one order state for cancel commissions.');
                }
                
                $selected_state = false;
                foreach ($states as $state) {
                    if (Tools::getValue('JMARKETPLACE_SELL_ORDER_STATE_'.$state['id_order_state'])) {
                        $selected_state = true;
                    }
                }

                if ($selected_state) {
                    foreach ($states as $state) {
                        if (Tools::getValue('JMARKETPLACE_SELL_ORDER_STATE_'.$state['id_order_state'])) {
                            Configuration::updateValue('JMARKETPLACE_SELL_ORDER_STATE_'.$state['id_order_state'], 1);
                        } else {
                            Configuration::updateValue('JMARKETPLACE_SELL_ORDER_STATE_'.$state['id_order_state'], 0);
                        }
                    }
                } else {
                    $errors[] = $this->l('You must select at least one order state for seller orders.');
                }

                if (Tools::getValue('JMARKETPLACE_COMMISIONS_ORDER') == 1 && Configuration::get('JMARKETPLACE_COMMISIONS_ORDER') == 0) {
                    Configuration::updateValue('JMARKETPLACE_COMMISIONS_ORDER', Tools::getValue('JMARKETPLACE_COMMISIONS_ORDER'));
                    Configuration::updateValue('JMARKETPLACE_COMMISIONS_STATE', 0);
                } elseif (Tools::getValue('JMARKETPLACE_COMMISIONS_STATE') == 1 && Configuration::get('JMARKETPLACE_COMMISIONS_STATE') == 0) {
                    Configuration::updateValue('JMARKETPLACE_COMMISIONS_ORDER', 0);
                    Configuration::updateValue('JMARKETPLACE_COMMISIONS_STATE', Tools::getValue('JMARKETPLACE_COMMISIONS_STATE'));
                }

                //Configuration::updateValue('JMARKETPLACE_ORDER_STATE', Tools::getValue('JMARKETPLACE_ORDER_STATE'));
                Configuration::updateValue('JMARKETPLACE_FIXED_COMMISSION', $fixed_commission);
                Configuration::updateValue('JMARKETPLACE_VARIABLE_COMMISSION', $variable_commission);
                Configuration::updateValue('JMARKETPLACE_SHIPPING_COMMISSION', Tools::getValue('JMARKETPLACE_SHIPPING_COMMISSION'));
                Configuration::updateValue('JMARKETPLACE_TAX_COMMISSION', Tools::getValue('JMARKETPLACE_TAX_COMMISSION'));
                Configuration::updateValue('JMARKETPLACE_SELLER_IMPORT_PROD', Tools::getValue('JMARKETPLACE_SELLER_IMPORT_PROD'));
                Configuration::updateValue('JMARKETPLACE_SHOW_CONTACT', Tools::getValue('JMARKETPLACE_SHOW_CONTACT'));
                Configuration::updateValue('JMARKETPLACE_SHOW_DASHBOARD', Tools::getValue('JMARKETPLACE_SHOW_DASHBOARD'));
                Configuration::updateValue('JMARKETPLACE_SHOW_SELLER_INVOICE', Tools::getValue('JMARKETPLACE_SHOW_SELLER_INVOICE'));
                Configuration::updateValue('JMARKETPLACE_SHOW_MANAGE_ORDERS', Tools::getValue('JMARKETPLACE_SHOW_MANAGE_ORDERS'));
                Configuration::updateValue('JMARKETPLACE_SHOW_MANAGE_CARRIER', Tools::getValue('JMARKETPLACE_SHOW_MANAGE_CARRIER'));
                Configuration::updateValue('JMARKETPLACE_SHOW_PROFILE', Tools::getValue('JMARKETPLACE_SHOW_PROFILE'));
                Configuration::updateValue('JMARKETPLACE_SHOW_ORDERS', Tools::getValue('JMARKETPLACE_SHOW_ORDERS'));
                Configuration::updateValue('JMARKETPLACE_SHOW_EDIT_ACCOUNT', Tools::getValue('JMARKETPLACE_SHOW_EDIT_ACCOUNT'));
                Configuration::updateValue('JMARKETPLACE_SHOW_EDIT_PRODUCT', Tools::getValue('JMARKETPLACE_SHOW_EDIT_PRODUCT'));
                Configuration::updateValue('JMARKETPLACE_SHOW_DELETE_PRODUCT', Tools::getValue('JMARKETPLACE_SHOW_DELETE_PRODUCT'));
                Configuration::updateValue('JMARKETPLACE_SHOW_ACTIVE_PRODUCT', Tools::getValue('JMARKETPLACE_SHOW_ACTIVE_PRODUCT'));
                Configuration::updateValue('JMARKETPLACE_SELLER_FAVORITE', Tools::getValue('JMARKETPLACE_SELLER_FAVORITE'));
                Configuration::updateValue('JMARKETPLACE_SELLER_RATING', Tools::getValue('JMARKETPLACE_SELLER_RATING'));
                Configuration::updateValue('JMARKETPLACE_NEW_PRODUCTS', Tools::getValue('JMARKETPLACE_NEW_PRODUCTS'));
                Configuration::updateValue('JMARKETPLACE_SHOW_SELLER_PLIST', Tools::getValue('JMARKETPLACE_SHOW_SELLER_PLIST'));
                Configuration::updateValue('JMARKETPLACE_SHOW_ORDER_DETAIL', Tools::getValue('JMARKETPLACE_SHOW_ORDER_DETAIL'));

                if (Configuration::get('JMARKETPLACE_SHOW_MANAGE_CARRIER') == 1) {
                    Configuration::updateValue('PS_BLOCK_CART_AJAX', 0);
                }
                
                if (Configuration::get('JMARKETPLACE_SHOW_MANAGE_CARRIER') == 0) {
                    Configuration::updateValue('PS_BLOCK_CART_AJAX', 1);
                }

                if (Configuration::get('JMARKETPLACE_SHOW_MANAGE_CARRIER') == 1) {
                    Configuration::updateValue('JMARKETPLACE_SHOW_SHIP_PRODUCT', 1);
                }
            }
            
            if (isset($errors) && sizeof($errors)) {
                $this->output .= $this->displayError(implode('<br />', $errors));
            } else {
                $this->output .= $this->displayConfirmation($this->l('General settings updated ok.'));
            }
        }
        
        if (Tools::isSubmit('submitSellerAccountSettings')) {
            Configuration::updateValue('JMARKETPLACE_SHOW_SHOP_NAME', Tools::getValue('JMARKETPLACE_SHOW_SHOP_NAME'));
            Configuration::updateValue('JMARKETPLACE_SHOW_LANGUAGE', Tools::getValue('JMARKETPLACE_SHOW_LANGUAGE'));
            Configuration::updateValue('JMARKETPLACE_SHOW_CIF', Tools::getValue('JMARKETPLACE_SHOW_CIF'));
            Configuration::updateValue('JMARKETPLACE_SHOW_PHONE', Tools::getValue('JMARKETPLACE_SHOW_PHONE'));
            Configuration::updateValue('JMARKETPLACE_SHOW_FAX', Tools::getValue('JMARKETPLACE_SHOW_FAX'));
            Configuration::updateValue('JMARKETPLACE_SHOW_ADDRESS', Tools::getValue('JMARKETPLACE_SHOW_ADDRESS'));
            Configuration::updateValue('JMARKETPLACE_SHOW_COUNTRY', Tools::getValue('JMARKETPLACE_SHOW_COUNTRY'));
            Configuration::updateValue('JMARKETPLACE_SHOW_STATE', Tools::getValue('JMARKETPLACE_SHOW_STATE'));
            Configuration::updateValue('JMARKETPLACE_SHOW_CITY', Tools::getValue('JMARKETPLACE_SHOW_CITY'));
            Configuration::updateValue('JMARKETPLACE_SHOW_POSTAL_CODE', Tools::getValue('JMARKETPLACE_SHOW_POSTAL_CODE'));
            Configuration::updateValue('JMARKETPLACE_SHOW_DESCRIPTION', Tools::getValue('JMARKETPLACE_SHOW_DESCRIPTION'));
            Configuration::updateValue('JMARKETPLACE_SHOW_MTA_DESCRIPTION', Tools::getValue('JMARKETPLACE_SHOW_MTA_DESCRIPTION'));
            Configuration::updateValue('JMARKETPLACE_SHOW_MTA_TITLE', Tools::getValue('JMARKETPLACE_SHOW_MTA_TITLE'));
            Configuration::updateValue('JMARKETPLACE_SHOW_MTA_KEYWORDS', Tools::getValue('JMARKETPLACE_SHOW_MTA_KEYWORDS'));
            Configuration::updateValue('JMARKETPLACE_SHOW_LOGO', Tools::getValue('JMARKETPLACE_SHOW_LOGO'));
            Configuration::updateValue('JMARKETPLACE_SHOW_TERMS', Tools::getValue('JMARKETPLACE_SHOW_TERMS'));
            Configuration::updateValue('JMARKETPLACE_CMS_TERMS', Tools::getValue('JMARKETPLACE_CMS_TERMS'));
            $this->output .= $this->displayConfirmation($this->l('Seller account settings updated ok.'));
        }
        
        if (Tools::isSubmit('submitSellerProfileSettings')) {
            Configuration::updateValue('JMARKETPLACE_SHOW_PSHOP_NAME', Tools::getValue('JMARKETPLACE_SHOW_PSHOP_NAME'));
            Configuration::updateValue('JMARKETPLACE_SHOW_PLANGUAGE', Tools::getValue('JMARKETPLACE_SHOW_PLANGUAGE'));
            Configuration::updateValue('JMARKETPLACE_SHOW_PCIF', Tools::getValue('JMARKETPLACE_SHOW_PCIF'));
            Configuration::updateValue('JMARKETPLACE_SHOW_PEMAIL', Tools::getValue('JMARKETPLACE_SHOW_PEMAIL'));
            Configuration::updateValue('JMARKETPLACE_SHOW_PPHONE', Tools::getValue('JMARKETPLACE_SHOW_PPHONE'));
            Configuration::updateValue('JMARKETPLACE_SHOW_PFAX', Tools::getValue('JMARKETPLACE_SHOW_PFAX'));
            Configuration::updateValue('JMARKETPLACE_SHOW_PADDRESS', Tools::getValue('JMARKETPLACE_SHOW_PADDRESS'));
            Configuration::updateValue('JMARKETPLACE_SHOW_PCOUNTRY', Tools::getValue('JMARKETPLACE_SHOW_PCOUNTRY'));
            Configuration::updateValue('JMARKETPLACE_SHOW_PSTATE', Tools::getValue('JMARKETPLACE_SHOW_PSTATE'));
            Configuration::updateValue('JMARKETPLACE_SHOW_PCITY', Tools::getValue('JMARKETPLACE_SHOW_PCITY'));
            Configuration::updateValue('JMARKETPLACE_SHOW_PPOSTAL_CODE', Tools::getValue('JMARKETPLACE_SHOW_PPOSTAL_CODE'));
            Configuration::updateValue('JMARKETPLACE_SHOW_PDESCRIPTION', Tools::getValue('JMARKETPLACE_SHOW_PDESCRIPTION'));
            Configuration::updateValue('JMARKETPLACE_SHOW_PLOGO', Tools::getValue('JMARKETPLACE_SHOW_PLOGO'));
            $this->output .= $this->displayConfirmation($this->l('Seller profile settings updated ok.'));
        }
        
        if (Tools::isSubmit('submitSellerProductTabInformation')) {
            Configuration::updateValue('JMARKETPLACE_SHOW_REFERENCE', Tools::getValue('JMARKETPLACE_SHOW_REFERENCE'));
            Configuration::updateValue('JMARKETPLACE_SHOW_ISBN', Tools::getValue('JMARKETPLACE_SHOW_ISBN'));
            Configuration::updateValue('JMARKETPLACE_SHOW_EAN13', Tools::getValue('JMARKETPLACE_SHOW_EAN13'));
            Configuration::updateValue('JMARKETPLACE_SHOW_UPC', Tools::getValue('JMARKETPLACE_SHOW_UPC'));
            Configuration::updateValue('JMARKETPLACE_SHOW_CONDITION', Tools::getValue('JMARKETPLACE_SHOW_CONDITION'));
            Configuration::updateValue('JMARKETPLACE_SHOW_PCONDITION', Tools::getValue('JMARKETPLACE_SHOW_PCONDITION'));
            Configuration::updateValue('JMARKETPLACE_SHOW_AVAILABLE_ORD', Tools::getValue('JMARKETPLACE_SHOW_AVAILABLE_ORD'));
            Configuration::updateValue('JMARKETPLACE_SHOW_SHOW_PRICE', Tools::getValue('JMARKETPLACE_SHOW_SHOW_PRICE'));
            Configuration::updateValue('JMARKETPLACE_SHOW_ONLINE_ONLY', Tools::getValue('JMARKETPLACE_SHOW_ONLINE_ONLY'));
            Configuration::updateValue('JMARKETPLACE_SHOW_DESC_SHORT', Tools::getValue('JMARKETPLACE_SHOW_DESC_SHORT'));
            Configuration::updateValue('JMARKETPLACE_SHOW_DESC', Tools::getValue('JMARKETPLACE_SHOW_DESC'));
            $this->output .= $this->displayConfirmation($this->l('Seller product settings updated ok.'));
        }
        
        if (Tools::isSubmit('submitSellerProductTabPrices')) {
            Configuration::updateValue('JMARKETPLACE_SHOW_PRICE', Tools::getValue('JMARKETPLACE_SHOW_PRICE'));
            Configuration::updateValue('JMARKETPLACE_SHOW_WHOLESALEPRICE', Tools::getValue('JMARKETPLACE_SHOW_WHOLESALEPRICE'));
            Configuration::updateValue('JMARKETPLACE_SHOW_OFFER_PRICE', Tools::getValue('JMARKETPLACE_SHOW_OFFER_PRICE'));
            Configuration::updateValue('JMARKETPLACE_SHOW_UNIT_PRICE', Tools::getValue('JMARKETPLACE_SHOW_UNIT_PRICE'));
            Configuration::updateValue('JMARKETPLACE_SHOW_TAX', Tools::getValue('JMARKETPLACE_SHOW_TAX'));
            Configuration::updateValue('JMARKETPLACE_SHOW_COMMISSION', Tools::getValue('JMARKETPLACE_SHOW_COMMISSION'));
            Configuration::updateValue('JMARKETPLACE_SHOW_ON_SALE', Tools::getValue('JMARKETPLACE_SHOW_ON_SALE'));
            $this->output .= $this->displayConfirmation($this->l('Seller product settings updated ok.'));
        }
        
        if (Tools::isSubmit('submitSellerProductTabSeo')) {
            Configuration::updateValue('JMARKETPLACE_SHOW_META_KEYWORDS', Tools::getValue('JMARKETPLACE_SHOW_META_KEYWORDS'));
            Configuration::updateValue('JMARKETPLACE_SHOW_META_TITLE', Tools::getValue('JMARKETPLACE_SHOW_META_TITLE'));
            Configuration::updateValue('JMARKETPLACE_SHOW_META_DESC', Tools::getValue('JMARKETPLACE_SHOW_META_DESC'));
            Configuration::updateValue('JMARKETPLACE_SHOW_LINK_REWRITE', Tools::getValue('JMARKETPLACE_SHOW_LINK_REWRITE'));
            $this->output .= $this->displayConfirmation($this->l('Seller product settings updated ok.'));
        }
        
        if (Tools::isSubmit('submitSellerProductTabAssociations')) {
            Configuration::updateValue('JMARKETPLACE_SHOW_SUPPLIERS', Tools::getValue('JMARKETPLACE_SHOW_SUPPLIERS'));
            
            if (Tools::getValue('JMARKETPLACE_NEW_SUPPLIERS') == 1) {
                Configuration::updateValue('JMARKETPLACE_SHOW_SUPPLIERS', 1);
            }
            
            Configuration::updateValue('JMARKETPLACE_NEW_SUPPLIERS', Tools::getValue('JMARKETPLACE_NEW_SUPPLIERS'));
            Configuration::updateValue('JMARKETPLACE_SHOW_MANUFACTURERS', Tools::getValue('JMARKETPLACE_SHOW_MANUFACTURERS'));
            
            if (Tools::getValue('JMARKETPLACE_NEW_MANUFACTURERS') == 1) {
                Configuration::updateValue('JMARKETPLACE_SHOW_MANUFACTURERS', 1);
            }
            
            Configuration::updateValue('JMARKETPLACE_NEW_MANUFACTURERS', Tools::getValue('JMARKETPLACE_NEW_MANUFACTURERS'));
            Configuration::updateValue('JMARKETPLACE_SHOW_CATEGORIES', Tools::getValue('JMARKETPLACE_SHOW_CATEGORIES'));

            //selected categories
            if (Configuration::get('JMARKETPLACE_SHOW_CATEGORIES') == 1) {
                $selected_categories = Tools::getValue('categories');
                if (is_array($selected_categories) && count($selected_categories) > 0) {
                    SellerCategory::deleteSelectedCategories($this->context->shop->id);
                    foreach ($selected_categories as $sc) {
                        $seller_category = new SellerCategory();
                        $seller_category->id_category = (int)$sc;
                        $seller_category->id_shop = (int)$this->context->shop->id;
                        $seller_category->add();
                    }
                } else {
                    $errors[] = $this->l('You must select at least one category.');
                }
            }
            $this->output .= $this->displayConfirmation($this->l('Seller product settings updated ok.'));
        }
        
        if (Tools::isSubmit('submitSellerProductTabShipping')) {
            Configuration::updateValue('JMARKETPLACE_SHOW_WIDTH', Tools::getValue('JMARKETPLACE_SHOW_WIDTH'));
            Configuration::updateValue('JMARKETPLACE_SHOW_HEIGHT', Tools::getValue('JMARKETPLACE_SHOW_HEIGHT'));
            Configuration::updateValue('JMARKETPLACE_SHOW_DEPTH', Tools::getValue('JMARKETPLACE_SHOW_DEPTH'));
            Configuration::updateValue('JMARKETPLACE_SHOW_WEIGHT', Tools::getValue('JMARKETPLACE_SHOW_WEIGHT'));
            Configuration::updateValue('JMARKETPLACE_SHOW_SHIP_PRODUCT', Tools::getValue('JMARKETPLACE_SHOW_SHIP_PRODUCT'));
            $this->output .= $this->displayConfirmation($this->l('Seller product settings updated ok.'));
        }
        
        if (Tools::isSubmit('submitSellerProductTabCombinations')) {
            Configuration::updateValue('JMARKETPLACE_SHOW_ATTRIBUTES', Tools::getValue('JMARKETPLACE_SHOW_ATTRIBUTES'));
            $this->output .= $this->displayConfirmation($this->l('Seller product settings updated ok.'));
        }
        
        if (Tools::isSubmit('submitSellerProductTabQuantities')) {
            Configuration::updateValue('JMARKETPLACE_SHOW_QUANTITY', Tools::getValue('JMARKETPLACE_SHOW_QUANTITY'));
            Configuration::updateValue('JMARKETPLACE_SHOW_MINIMAL_QTY', Tools::getValue('JMARKETPLACE_SHOW_MINIMAL_QTY'));
            Configuration::updateValue('JMARKETPLACE_SHOW_AVAILABILITY', Tools::getValue('JMARKETPLACE_SHOW_AVAILABILITY'));
            Configuration::updateValue('JMARKETPLACE_SHOW_AVAILABLE_NOW', Tools::getValue('JMARKETPLACE_SHOW_AVAILABLE_NOW'));
            Configuration::updateValue('JMARKETPLACE_SHOW_AVAILABLE_LAT', Tools::getValue('JMARKETPLACE_SHOW_AVAILABLE_LAT'));
            Configuration::updateValue('JMARKETPLACE_SHOW_AVAILABLE_DATE', Tools::getValue('JMARKETPLACE_SHOW_AVAILABLE_DATE'));
            $this->output .= $this->displayConfirmation($this->l('Seller product settings updated ok.'));
        }
        
        if (Tools::isSubmit('submitSellerProductTabImages')) {
            $max_images = Tools::getValue('JMARKETPLACE_MAX_IMAGES');
            if (!$max_images || $max_images <= 0 || $max_images > 100 || !Validate::isInt($max_images)) {
                $errors[] = $this->l('Invalid max images value.');
            } else {
                Configuration::updateValue('JMARKETPLACE_SHOW_IMAGES', Tools::getValue('JMARKETPLACE_SHOW_IMAGES'));
                Configuration::updateValue('JMARKETPLACE_MAX_IMAGES', Tools::getValue('JMARKETPLACE_MAX_IMAGES'));
                $this->output .= $this->displayConfirmation($this->l('Seller product settings updated ok.'));
            }
        }
        
        if (Tools::isSubmit('submitSellerProductTabFeatures')) {
            Configuration::updateValue('JMARKETPLACE_SHOW_FEATURES', Tools::getValue('JMARKETPLACE_SHOW_FEATURES'));
            $this->output .= $this->displayConfirmation($this->l('Seller product settings updated ok.'));
        }
        
        if (Tools::isSubmit('submitSellerProductTabVirtual')) {
            Configuration::updateValue('JMARKETPLACE_SHOW_VIRTUAL', Tools::getValue('JMARKETPLACE_SHOW_VIRTUAL'));
            $this->output .= $this->displayConfirmation($this->l('Seller product settings updated ok.'));
        }
        
        if (Tools::isSubmit('submitSellerProductTabAttachments')) {
            $max_attachments = Tools::getValue('JMARKETPLACE_MAX_ATTACHMENTS');
            if (!$max_attachments || $max_attachments <= 0 || $max_attachments > 100 || !Validate::isInt($max_attachments)) {
                $errors[] = $this->l('Invalid max attachment value.');
            } else {
                Configuration::updateValue('JMARKETPLACE_SHOW_ATTACHMENTS', Tools::getValue('JMARKETPLACE_SHOW_ATTACHMENTS'));
                Configuration::updateValue('JMARKETPLACE_MAX_ATTACHMENTS', Tools::getValue('JMARKETPLACE_MAX_ATTACHMENTS'));
                $this->output .= $this->displayConfirmation($this->l('Seller product settings updated ok.'));
            }
        }
        
        if (isset($errors) && sizeof($errors)) {
            $this->output .= $this->displayError(implode('<br />', $errors));
        }
        
        if (Tools::isSubmit('submitEmailSettings')) {
            $email = Tools::getValue('JMARKETPLACE_SEND_ADMIN');
            if (Validate::isEmail($email)) {
                Configuration::updateValue('JMARKETPLACE_SEND_ADMIN', Tools::getValue('JMARKETPLACE_SEND_ADMIN'));
                Configuration::updateValue('JMARKETPLACE_SEND_ADMIN_REGISTER', Tools::getValue('JMARKETPLACE_SEND_ADMIN_REGISTER'));
                Configuration::updateValue('JMARKETPLACE_SEND_ADMIN_PRODUCT', Tools::getValue('JMARKETPLACE_SEND_ADMIN_PRODUCT'));
                Configuration::updateValue('JMARKETPLACE_SEND_ADMIN_REQUEST', Tools::getValue('JMARKETPLACE_SEND_ADMIN_REQUEST'));
                Configuration::updateValue('JMARKETPLACE_SEND_SELLER_WELCOME', Tools::getValue('JMARKETPLACE_SEND_SELLER_WELCOME'));
                Configuration::updateValue('JMARKETPLACE_SEND_SELLER_ACTIVE', Tools::getValue('JMARKETPLACE_SEND_SELLER_ACTIVE'));
                Configuration::updateValue('JMARKETPLACE_SEND_PRODUCT_ACTIVE', Tools::getValue('JMARKETPLACE_SEND_PRODUCT_ACTIVE'));
                Configuration::updateValue('JMARKETPLACE_SEND_PRODUCT_SOLD', Tools::getValue('JMARKETPLACE_SEND_PRODUCT_SOLD'));
                Configuration::updateValue('JMARKETPLACE_SEND_ORDER_CHANGED', Tools::getValue('JMARKETPLACE_SEND_ORDER_CHANGED'));
                Configuration::updateValue('JMARKETPLACE_SEND_ADMIN_INCIDENCE', Tools::getValue('JMARKETPLACE_SEND_ADMIN_INCIDENCE'));
                $this->output .= $this->displayConfirmation($this->l('Email settings updated ok.'));
            } else {
                $this->output .= $this->displayError($this->l('The email is incorrect.'));
            }
        }
        
        if (Tools::isSubmit('submitThemeSettings')) {
            Configuration::updateValue('JMARKETPLACE_THEME', Tools::getValue('JMARKETPLACE_THEME'));
            Configuration::updateValue('JMARKETPLACE_TABS', Tools::getValue('JMARKETPLACE_TABS'));
            Configuration::updateValue('JMARKETPLACE_MENU_TOP', Tools::getValue('JMARKETPLACE_MENU_TOP'));
            Configuration::updateValue('JMARKETPLACE_MENU_OPTIONS', Tools::getValue('JMARKETPLACE_MENU_OPTIONS'));
            Configuration::updateValue('JMARKETPLACE_CUSTOM_STYLES', Tools::getValue('JMARKETPLACE_CUSTOM_STYLES'));
            $this->output .= $this->displayConfirmation($this->l('Theme settings updated ok.'));
        }
        
        if (Tools::isSubmit('submitPayments')) {
            if (Tools::getValue('JMARKETPLACE_PAYPAL') == 0 && Tools::getValue('JMARKETPLACE_BANKWIRE') == 0) {
                $this->output .= $this->displayError($this->l('You must select a payment method.'));
            } else {
                Configuration::updateValue('JMARKETPLACE_PAYPAL', Tools::getValue('JMARKETPLACE_PAYPAL'));
                Configuration::updateValue('JMARKETPLACE_BANKWIRE', Tools::getValue('JMARKETPLACE_BANKWIRE'));
                $this->output .= $this->displayConfirmation($this->l('Seller payments updated ok.'));
            }
        }
        
        if (Tools::isSubmit('submitRouteSettings')) {
            $languages = Language::getLanguages(false);
            
            $values = array();
            foreach ($languages as $lang) {
                if (Tools::getValue('JMARKETPLACE_MAIN_ROUTE_'.$lang['id_lang']) != '') {
                    $values['JMARKETPLACE_MAIN_ROUTE'][$lang['id_lang']] = Tools::getValue('JMARKETPLACE_MAIN_ROUTE_'.$lang['id_lang']);
                } else {
                    $values['JMARKETPLACE_MAIN_ROUTE'][$lang['id_lang']] = 'jmarketplace';
                }
            }
            
            Configuration::updateValue('JMARKETPLACE_MAIN_ROUTE', $values['JMARKETPLACE_MAIN_ROUTE']);
            
            $values = array();
            foreach ($languages as $lang) {
                if (Tools::getValue('JMARKETPLACE_ROUTE_PRODUCTS_'.$lang['id_lang']) != '') {
                    $values['JMARKETPLACE_ROUTE_PRODUCTS'][$lang['id_lang']] = Tools::getValue('JMARKETPLACE_ROUTE_PRODUCTS_'.$lang['id_lang']);
                } else {
                    $values['JMARKETPLACE_ROUTE_PRODUCTS'][$lang['id_lang']] = 'products';
                }
            }
            
            Configuration::updateValue('JMARKETPLACE_ROUTE_PRODUCTS', $values['JMARKETPLACE_ROUTE_PRODUCTS']);
            
            $values = array();
            foreach ($languages as $lang) {
                if (Tools::getValue('JMARKETPLACE_ROUTE_COMMENTS_'.$lang['id_lang']) != '') {
                    $values['JMARKETPLACE_ROUTE_COMMENTS'][$lang['id_lang']] = Tools::getValue('JMARKETPLACE_ROUTE_COMMENTS_'.$lang['id_lang']);
                } else {
                    $values['JMARKETPLACE_ROUTE_COMMENTS'][$lang['id_lang']] = 'comments';
                }
            }
            
            Configuration::updateValue('JMARKETPLACE_ROUTE_COMMENTS', $values['JMARKETPLACE_ROUTE_COMMENTS']);
            
            $this->output .= $this->displayConfirmation($this->l('Routes updated ok.'));
        }
    }
    
    public function getContent()
    {
        $this->postProcess();
        
        $this->context->smarty->assign(array(
            'module_dir' => $this->_path,
            'displayName' => $this->displayName,
            'name' => $this->name,
            'author' => $this->author,
            'version' => $this->version,
            'description' => $this->description,
            'displayFormGeneralSettings' => $this->displayFormGeneralSettings(),
            'displayFormSellerAccountSettings' => $this->displayFormSellerAccountSettings(),
            'displayFormSellerProfileSettings' => $this->displayFormSellerProfileSettings(),
            'displayFormSellerProductSettings' => $this->displayFormSellerProductSettings(),
            'displayFormEmailSettings' => $this->displayFormEmailSettings(),
            'displayFormPayments' => $this->displayFormPayments(),
            'displayFormThemeSettings' => $this->displayFormThemeSettings(),
            'displayFormRouteSettings' => $this->displayFormRouteSettings(),
        ));
        $this->output .= $this->context->smarty->fetch($this->local_path.'views/templates/admin/content.tpl');
        
        return $this->output;
    }
    
    public function getGroupsToSeller()
    {
        $logged_groups = array();
        $groups = Group::getGroups($this->context->language->id);
        foreach ($groups as $group) {
            if ($group['id_group'] > 2) {
                $logged_groups[] = $group;
            }
        }
        return $logged_groups;
    }
    
    private function displayFormGeneralSettings()
    {
        $customer_groups_to_seller = $this->getGroupsToSeller();
        $states = OrderState::getOrderStates($this->context->language->id);
        
        $languages = Language::getLanguages(false);
        foreach ($languages as $k => $language) {
            $languages[$k]['is_default'] = (int)$language['id_lang'] == Configuration::get('PS_LANG_DEFAULT');
        }

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->languages = $languages;
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = true;
        $helper->toolbar_scroll = true;
        $helper->title = $this->displayName;
        $helper->submit_action = 'submitGeneralSettings';

        $this->fields_form[0]['form'] = array(
            'tinymce' => false,
            'legend' => array(
                'title' => $this->l('General settings')
            ),
            'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Moderate sellers'),
                    'name' => 'JMARKETPLACE_MODERATE_SELLER',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Moderate products'),
                    'name' => 'JMARKETPLACE_MODERATE_PRODUCT',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Fixed commission'),
                    'suffix' => $this->context->currency->sign,
                    'name' => 'JMARKETPLACE_FIXED_COMMISSION',
                    'desc' => $this->l('Fixed commission for each sale.'),
                    'required' => true,
                    'lang' => false,
                    'col' => 2,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Variable commission'),
                    'suffix' => '%',
                    'name' => 'JMARKETPLACE_VARIABLE_COMMISSION',
                    'desc' => $this->l('This percentage is applied to the total price of products sold. The seller collect').' '.Configuration::get('JMARKETPLACE_VARIABLE_COMMISSION').'% '.$this->l('of sale of your products. Values: 0-100'),
                    'required' => true,
                    'lang' => false,
                    'col' => 2,
                ),
                array(
                    'type' => 'checkbox',
                    'label' => $this->l('Customer group'),
                    'desc' => $this->l('Select group of customers who may be selling.'),
                    'name' => 'JMARKETPLACE_CUSTOMER_GROUP',
                    'values' => array(
                        'query' => $customer_groups_to_seller,
                        'id' => 'id_group',
                        'name' => 'name'
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Assign commissions when a customer places an order'),
                    'name' => 'JMARKETPLACE_COMMISIONS_ORDER',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'desc' => $this->l('Commissions are awarded when a customer places an order'),
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Assign commissions when an order status changes'),
                    'name' => 'JMARKETPLACE_COMMISIONS_STATE',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'desc' => $this->l('Commissions are awarded when an order status changes'),
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'checkbox',
                    'label' => $this->l('Order state'),
                    'desc' => $this->l('Select the order status to send notification to vendors when an order is changed to this state.'),
                    'name' => 'JMARKETPLACE_ORDER_STATE',
                    'values' => array(
                        'query' => $states,
                        'id' => 'id_order_state',
                        'name' => 'name'
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Seller assumes shipping'),
                    'name' => 'JMARKETPLACE_SHIPPING_COMMISSION',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'desc' => $this->l('The shipping cost is asssumed by seller.'),
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Seller assumes taxes'),
                    'name' => 'JMARKETPLACE_TAX_COMMISSION',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'desc' => $this->l('The seller pay taxes.'),
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'checkbox',
                    'label' => $this->l('Cancel commissions'),
                    'desc' => $this->l('Cancel commissions automatequely when the order changes state.'),
                    'name' => 'JMARKETPLACE_CANCEL_COMMISSION',
                    'values' => array(
                        'query' => $states,
                        'id' => 'id_order_state',
                        'name' => 'name'
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show contact seller in product page'),
                    'name' => 'JMARKETPLACE_SHOW_CONTACT',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show dashboard'),
                    'name' => 'JMARKETPLACE_SHOW_DASHBOARD',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show seller transfer request'),
                    'name' => 'JMARKETPLACE_SHOW_SELLER_INVOICE',
                    'desc' => $this->l('Seller can send a transfer request to collect your commissions.'),
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show seller profile'),
                    'name' => 'JMARKETPLACE_SHOW_PROFILE',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show orders and commissions to sellers'),
                    'name' => 'JMARKETPLACE_SHOW_ORDERS',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Allow to sellers manage your orders'),
                    'name' => 'JMARKETPLACE_SHOW_MANAGE_ORDERS',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'checkbox',
                    'label' => $this->l('Order state'),
                    'desc' => $this->l('Select the order status that the seller can select for your orders.'),
                    'name' => 'JMARKETPLACE_SELL_ORDER_STATE',
                    'values' => array(
                        'query' => $states,
                        'id' => 'id_order_state',
                        'name' => 'name'
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Allow to sellers manage your carriers and shipping cost'),
                    'name' => 'JMARKETPLACE_SHOW_MANAGE_CARRIER',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show edit sellers account'),
                    'name' => 'JMARKETPLACE_SHOW_EDIT_ACCOUNT',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Allow to sellers edit product'),
                    'name' => 'JMARKETPLACE_SHOW_EDIT_PRODUCT',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Allow to sellers delete product'),
                    'name' => 'JMARKETPLACE_SHOW_DELETE_PRODUCT',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Allow to sellers enable and disable your products'),
                    'name' => 'JMARKETPLACE_SHOW_ACTIVE_PRODUCT',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Allow to sellers import and export your products with csv file'),
                    'name' => 'JMARKETPLACE_SELLER_IMPORT_PROD',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Allow favorite seller'),
                    'name' => 'JMARKETPLACE_SELLER_FAVORITE',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'desc' => $this->l('Allow customers to add favorite sellers in your account (followers)'),
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Allow rating seller'),
                    'name' => 'JMARKETPLACE_SELLER_RATING',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'desc' => $this->l('Allow customers to add average grade of sellers.'),
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show new products in seller profile'),
                    'name' => 'JMARKETPLACE_NEW_PRODUCTS',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'desc' => $this->l('Allow show new products in sellers profile.'),
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show seller in product list'),
                    'name' => 'JMARKETPLACE_SHOW_SELLER_PLIST',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'desc' => $this->l('Allow show the seller in all product listings.'),
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show sellers in order history'),
                    'name' => 'JMARKETPLACE_SHOW_ORDER_DETAIL',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'desc' => $this->l('Show the sellers in order detail.'),
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
            ),
            'submit' => array(
                'name' => 'submitGeneralSettings',
                'title' => $this->l('Save'),
            ),
        );
        
        if ($customer_groups_to_seller) {
            foreach ($customer_groups_to_seller as $group) {
                if (Configuration::get('JMARKETPLACE_CUSTOMER_GROUP_'.$group['id_group']) == 1) {
                    $helper->fields_value['JMARKETPLACE_CUSTOMER_GROUP_'.$group['id_group']] = 1;
                } else {
                    $helper->fields_value['JMARKETPLACE_CUSTOMER_GROUP_'.$group['id_group']] = 0;
                }
            }
        }
        
        if ($states) {
            foreach ($states as $state) {
                if (Configuration::get('JMARKETPLACE_CANCEL_COMMISSION_'.$state['id_order_state']) == 1) {
                    $helper->fields_value['JMARKETPLACE_CANCEL_COMMISSION_'.$state['id_order_state']] = 1;
                } else {
                    $helper->fields_value['JMARKETPLACE_CANCEL_COMMISSION_'.$state['id_order_state']] = 0;
                }
                
                if (Configuration::get('JMARKETPLACE_ORDER_STATE_'.$state['id_order_state']) == 1) {
                    $helper->fields_value['JMARKETPLACE_ORDER_STATE_'.$state['id_order_state']] = 1;
                } else {
                    $helper->fields_value['JMARKETPLACE_ORDER_STATE_'.$state['id_order_state']] = 0;
                }
                
                if (Configuration::get('JMARKETPLACE_SELL_ORDER_STATE_'.$state['id_order_state']) == 1) {
                    $helper->fields_value['JMARKETPLACE_SELL_ORDER_STATE_'.$state['id_order_state']] = 1;
                } else {
                    $helper->fields_value['JMARKETPLACE_SELL_ORDER_STATE_'.$state['id_order_state']] = 0;
                }
            }
        }

        $helper->fields_value['JMARKETPLACE_MODERATE_SELLER'] = Configuration::get('JMARKETPLACE_MODERATE_SELLER');
        $helper->fields_value['JMARKETPLACE_MODERATE_PRODUCT'] = Configuration::get('JMARKETPLACE_MODERATE_PRODUCT');
        $helper->fields_value['JMARKETPLACE_COMMISIONS_ORDER'] = Configuration::get('JMARKETPLACE_COMMISIONS_ORDER');
        $helper->fields_value['JMARKETPLACE_COMMISIONS_STATE'] = Configuration::get('JMARKETPLACE_COMMISIONS_STATE');
        $helper->fields_value['JMARKETPLACE_FIXED_COMMISSION'] = Configuration::get('JMARKETPLACE_FIXED_COMMISSION');
        $helper->fields_value['JMARKETPLACE_VARIABLE_COMMISSION'] = Configuration::get('JMARKETPLACE_VARIABLE_COMMISSION');
        $helper->fields_value['JMARKETPLACE_SHIPPING_COMMISSION'] = Configuration::get('JMARKETPLACE_SHIPPING_COMMISSION');
        $helper->fields_value['JMARKETPLACE_TAX_COMMISSION'] = Configuration::get('JMARKETPLACE_TAX_COMMISSION');
        $helper->fields_value['JMARKETPLACE_SHOW_CONTACT'] = Configuration::get('JMARKETPLACE_SHOW_CONTACT');
        $helper->fields_value['JMARKETPLACE_SHOW_DASHBOARD'] = Configuration::get('JMARKETPLACE_SHOW_DASHBOARD');
        $helper->fields_value['JMARKETPLACE_SHOW_SELLER_INVOICE'] = Configuration::get('JMARKETPLACE_SHOW_SELLER_INVOICE');
        $helper->fields_value['JMARKETPLACE_SHOW_MANAGE_ORDERS'] = Configuration::get('JMARKETPLACE_SHOW_MANAGE_ORDERS');
        $helper->fields_value['JMARKETPLACE_SHOW_MANAGE_CARRIER'] = Configuration::get('JMARKETPLACE_SHOW_MANAGE_CARRIER');
        $helper->fields_value['JMARKETPLACE_SHOW_PROFILE'] = Configuration::get('JMARKETPLACE_SHOW_PROFILE');
        $helper->fields_value['JMARKETPLACE_SHOW_EDIT_ACCOUNT'] = Configuration::get('JMARKETPLACE_SHOW_EDIT_ACCOUNT');
        $helper->fields_value['JMARKETPLACE_SHOW_EDIT_PRODUCT'] = Configuration::get('JMARKETPLACE_SHOW_EDIT_PRODUCT');
        $helper->fields_value['JMARKETPLACE_SHOW_DELETE_PRODUCT'] = Configuration::get('JMARKETPLACE_SHOW_DELETE_PRODUCT');
        $helper->fields_value['JMARKETPLACE_SHOW_ACTIVE_PRODUCT'] = Configuration::get('JMARKETPLACE_SHOW_ACTIVE_PRODUCT');
        $helper->fields_value['JMARKETPLACE_SELLER_IMPORT_PROD'] = Configuration::get('JMARKETPLACE_SELLER_IMPORT_PROD');
        $helper->fields_value['JMARKETPLACE_SHOW_ORDERS'] = Configuration::get('JMARKETPLACE_SHOW_ORDERS');
        $helper->fields_value['JMARKETPLACE_SELLER_FAVORITE'] = Configuration::get('JMARKETPLACE_SELLER_FAVORITE');
        $helper->fields_value['JMARKETPLACE_SELLER_RATING'] = Configuration::get('JMARKETPLACE_SELLER_RATING');
        $helper->fields_value['JMARKETPLACE_NEW_PRODUCTS'] = Configuration::get('JMARKETPLACE_NEW_PRODUCTS');
        $helper->fields_value['JMARKETPLACE_SHOW_SELLER_PLIST'] = Configuration::get('JMARKETPLACE_SHOW_SELLER_PLIST');
        $helper->fields_value['JMARKETPLACE_SHOW_ORDER_DETAIL'] = Configuration::get('JMARKETPLACE_SHOW_ORDER_DETAIL');

        return $helper->generateForm($this->fields_form);
    }
    
    private function displayFormSellerAccountSettings()
    {
        $languages = Language::getLanguages(false);
        foreach ($languages as $k => $language) {
            $languages[$k]['is_default'] = (int)$language['id_lang'] == Configuration::get('PS_LANG_DEFAULT');
        }

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->languages = $languages;
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = true;
        $helper->toolbar_scroll = true;
        $helper->title = $this->displayName;
        $helper->submit_action = 'submitSellerAccountSettings';

        $this->fields_form[0]['form'] = array(
            'tinymce' => false,
            'legend' => array(
                'title' => $this->l('Available fields for seller registration form')
            ),
            'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show seller shop name'),
                    'name' => 'JMARKETPLACE_SHOW_SHOP_NAME',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show seller CIF/NIF'),
                    'name' => 'JMARKETPLACE_SHOW_CIF',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show seller language'),
                    'name' => 'JMARKETPLACE_SHOW_LANGUAGE',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show seller phone'),
                    'name' => 'JMARKETPLACE_SHOW_PHONE',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show seller fax'),
                    'name' => 'JMARKETPLACE_SHOW_FAX',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show seller address'),
                    'name' => 'JMARKETPLACE_SHOW_ADDRESS',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show seller country'),
                    'name' => 'JMARKETPLACE_SHOW_COUNTRY',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show seller state'),
                    'name' => 'JMARKETPLACE_SHOW_STATE',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show seller city'),
                    'name' => 'JMARKETPLACE_SHOW_CITY',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show seller postal code'),
                    'name' => 'JMARKETPLACE_SHOW_POSTAL_CODE',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show seller description'),
                    'name' => 'JMARKETPLACE_SHOW_DESCRIPTION',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show seller meta title'),
                    'name' => 'JMARKETPLACE_SHOW_MTA_TITLE',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show seller meta description'),
                    'name' => 'JMARKETPLACE_SHOW_MTA_DESCRIPTION',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show seller meta keywords'),
                    'name' => 'JMARKETPLACE_SHOW_MTA_KEYWORDS',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show seller logo'),
                    'name' => 'JMARKETPLACE_SHOW_LOGO',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show terms of service'),
                    'name' => 'JMARKETPLACE_SHOW_TERMS',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Page CMS Terms'),
                    'name' => 'JMARKETPLACE_CMS_TERMS',
                    'desc' => $this->l('Select page cms to terms of service to sellers.'),
                    'required' => false,
                    'options' => array(
                        'query' => CMS::getCMSPages($this->context->language->id),
                        'id' => 'id_cms',
                        'name' => 'meta_title'
                    )
                ),
            ),
            'submit' => array(
                'name' => 'submitSellerAccountSettings',
                'title' => $this->l('Save'),
            ),
        );

        $helper->fields_value['JMARKETPLACE_SHOW_SHOP_NAME'] = Configuration::get('JMARKETPLACE_SHOW_SHOP_NAME');
        $helper->fields_value['JMARKETPLACE_SHOW_CIF'] = Configuration::get('JMARKETPLACE_SHOW_CIF');
        $helper->fields_value['JMARKETPLACE_SHOW_LANGUAGE'] = Configuration::get('JMARKETPLACE_SHOW_LANGUAGE');
        $helper->fields_value['JMARKETPLACE_SHOW_PHONE'] = Configuration::get('JMARKETPLACE_SHOW_PHONE');
        $helper->fields_value['JMARKETPLACE_SHOW_FAX'] = Configuration::get('JMARKETPLACE_SHOW_FAX');
        $helper->fields_value['JMARKETPLACE_SHOW_ADDRESS'] = Configuration::get('JMARKETPLACE_SHOW_ADDRESS');
        $helper->fields_value['JMARKETPLACE_SHOW_COUNTRY'] = Configuration::get('JMARKETPLACE_SHOW_COUNTRY');
        $helper->fields_value['JMARKETPLACE_SHOW_STATE'] = Configuration::get('JMARKETPLACE_SHOW_STATE');
        $helper->fields_value['JMARKETPLACE_SHOW_CITY'] = Configuration::get('JMARKETPLACE_SHOW_CITY');
        $helper->fields_value['JMARKETPLACE_SHOW_POSTAL_CODE'] = Configuration::get('JMARKETPLACE_SHOW_POSTAL_CODE');
        $helper->fields_value['JMARKETPLACE_SHOW_DESCRIPTION'] = Configuration::get('JMARKETPLACE_SHOW_DESCRIPTION');
        $helper->fields_value['JMARKETPLACE_SHOW_MTA_TITLE'] = Configuration::get('JMARKETPLACE_SHOW_MTA_TITLE');
        $helper->fields_value['JMARKETPLACE_SHOW_MTA_DESCRIPTION'] = Configuration::get('JMARKETPLACE_SHOW_MTA_DESCRIPTION');
        $helper->fields_value['JMARKETPLACE_SHOW_MTA_KEYWORDS'] = Configuration::get('JMARKETPLACE_SHOW_MTA_KEYWORDS');
        $helper->fields_value['JMARKETPLACE_SHOW_LOGO'] = Configuration::get('JMARKETPLACE_SHOW_LOGO');
        $helper->fields_value['JMARKETPLACE_SHOW_TERMS'] = Configuration::get('JMARKETPLACE_SHOW_TERMS');
        $helper->fields_value['JMARKETPLACE_CMS_TERMS'] = Configuration::get('JMARKETPLACE_CMS_TERMS');

        return $helper->generateForm($this->fields_form);
    }
    
    private function displayFormSellerProfileSettings()
    {
        $languages = Language::getLanguages(false);
        foreach ($languages as $k => $language) {
            $languages[$k]['is_default'] = (int)$language['id_lang'] == Configuration::get('PS_LANG_DEFAULT');
        }

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->languages = $languages;
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = true;
        $helper->toolbar_scroll = true;
        $helper->title = $this->displayName;
        $helper->submit_action = 'submitSellerProfileSettings';

        $this->fields_form[0]['form'] = array(
            'tinymce' => false,
            'legend' => array(
                'title' => $this->l('Available fields for seller profile page')
            ),
            'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show seller shop name'),
                    'name' => 'JMARKETPLACE_SHOW_PSHOP_NAME',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show seller CIF/NIF'),
                    'name' => 'JMARKETPLACE_SHOW_PCIF',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show seller language'),
                    'name' => 'JMARKETPLACE_SHOW_PLANGUAGE',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show seller email'),
                    'name' => 'JMARKETPLACE_SHOW_PEMAIL',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show seller phone'),
                    'name' => 'JMARKETPLACE_SHOW_PPHONE',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show seller fax'),
                    'name' => 'JMARKETPLACE_SHOW_PFAX',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show seller address'),
                    'name' => 'JMARKETPLACE_SHOW_PADDRESS',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show seller country'),
                    'name' => 'JMARKETPLACE_SHOW_PCOUNTRY',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show seller state'),
                    'name' => 'JMARKETPLACE_SHOW_PSTATE',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show seller city'),
                    'name' => 'JMARKETPLACE_SHOW_PCITY',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show seller postal code'),
                    'name' => 'JMARKETPLACE_SHOW_PPOSTAL_CODE',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show seller description'),
                    'name' => 'JMARKETPLACE_SHOW_PDESCRIPTION',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show seller logo'),
                    'name' => 'JMARKETPLACE_SHOW_PLOGO',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
            ),
            'submit' => array(
                'name' => 'submitSellerProfileSettings',
                'title' => $this->l('Save'),
            ),
        );

        $helper->fields_value['JMARKETPLACE_SHOW_PSHOP_NAME'] = Configuration::get('JMARKETPLACE_SHOW_PSHOP_NAME');
        $helper->fields_value['JMARKETPLACE_SHOW_PCIF'] = Configuration::get('JMARKETPLACE_SHOW_PCIF');
        $helper->fields_value['JMARKETPLACE_SHOW_PLANGUAGE'] = Configuration::get('JMARKETPLACE_SHOW_PLANGUAGE');
        $helper->fields_value['JMARKETPLACE_SHOW_PEMAIL'] = Configuration::get('JMARKETPLACE_SHOW_PEMAIL');
        $helper->fields_value['JMARKETPLACE_SHOW_PPHONE'] = Configuration::get('JMARKETPLACE_SHOW_PPHONE');
        $helper->fields_value['JMARKETPLACE_SHOW_PFAX'] = Configuration::get('JMARKETPLACE_SHOW_PFAX');
        $helper->fields_value['JMARKETPLACE_SHOW_PADDRESS'] = Configuration::get('JMARKETPLACE_SHOW_PADDRESS');
        $helper->fields_value['JMARKETPLACE_SHOW_PCOUNTRY'] = Configuration::get('JMARKETPLACE_SHOW_PCOUNTRY');
        $helper->fields_value['JMARKETPLACE_SHOW_PSTATE'] = Configuration::get('JMARKETPLACE_SHOW_PSTATE');
        $helper->fields_value['JMARKETPLACE_SHOW_PCITY'] = Configuration::get('JMARKETPLACE_SHOW_PCITY');
        $helper->fields_value['JMARKETPLACE_SHOW_PPOSTAL_CODE'] = Configuration::get('JMARKETPLACE_SHOW_PPOSTAL_CODE');
        $helper->fields_value['JMARKETPLACE_SHOW_PDESCRIPTION'] = Configuration::get('JMARKETPLACE_SHOW_PDESCRIPTION');
        $helper->fields_value['JMARKETPLACE_SHOW_PLOGO'] = Configuration::get('JMARKETPLACE_SHOW_PLOGO');

        return $helper->generateForm($this->fields_form);
    }
    
    private function displayFormSellerProductInformation()
    {
        $languages = Language::getLanguages(false);
        foreach ($languages as $k => $language) {
            $languages[$k]['is_default'] = (int)$language['id_lang'] == Configuration::get('PS_LANG_DEFAULT');
        }

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->languages = $languages;
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = true;
        $helper->toolbar_scroll = true;
        $helper->title = $this->displayName;
        $helper->submit_action = 'submitSellerProductTabInformation';

        $this->fields_form[0]['form'] = array(
            'tinymce' => false,
            'legend' => array(
                'title' => $this->l('Tab information')
            ),
            'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show reference'),
                    'name' => 'JMARKETPLACE_SHOW_REFERENCE',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show ISBN'),
                    'desc' => $this->l('Only available in PrestaShop 1.7+'),
                    'name' => 'JMARKETPLACE_SHOW_ISBN',
                    'required' => false,
                    'disabled' => true,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show EAN13'),
                    'name' => 'JMARKETPLACE_SHOW_EAN13',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show UPC'),
                    'name' => 'JMARKETPLACE_SHOW_UPC',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show available for order'),
                    'name' => 'JMARKETPLACE_SHOW_AVAILABLE_ORD',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show display price'),
                    'name' => 'JMARKETPLACE_SHOW_SHOW_PRICE',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show online only'),
                    'name' => 'JMARKETPLACE_SHOW_ONLINE_ONLY',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show condition'),
                    'name' => 'JMARKETPLACE_SHOW_CONDITION',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show option "show condition in product page"'),
                    'desc' => $this->l('Only available in PrestaShop 1.7+'),
                    'name' => 'JMARKETPLACE_SHOW_PCONDITION',
                    'required' => false,
                    'disabled' => true,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show description short'),
                    'name' => 'JMARKETPLACE_SHOW_DESC_SHORT',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show description'),
                    'name' => 'JMARKETPLACE_SHOW_DESC',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
            ),
            'submit' => array(
                'name' => 'submitSellerProductTabInformation',
                'title' => $this->l('Save'),
            ),
        );

        $helper->fields_value['JMARKETPLACE_SHOW_REFERENCE'] = Configuration::get('JMARKETPLACE_SHOW_REFERENCE');
        $helper->fields_value['JMARKETPLACE_SHOW_ISBN'] = Configuration::get('JMARKETPLACE_SHOW_ISBN');
        $helper->fields_value['JMARKETPLACE_SHOW_EAN13'] = Configuration::get('JMARKETPLACE_SHOW_EAN13');
        $helper->fields_value['JMARKETPLACE_SHOW_UPC'] = Configuration::get('JMARKETPLACE_SHOW_UPC');
        $helper->fields_value['JMARKETPLACE_SHOW_AVAILABLE_ORD'] = Configuration::get('JMARKETPLACE_SHOW_AVAILABLE_ORD');
        $helper->fields_value['JMARKETPLACE_SHOW_SHOW_PRICE'] = Configuration::get('JMARKETPLACE_SHOW_SHOW_PRICE');
        $helper->fields_value['JMARKETPLACE_SHOW_ONLINE_ONLY'] = Configuration::get('JMARKETPLACE_SHOW_ONLINE_ONLY');
        $helper->fields_value['JMARKETPLACE_SHOW_CONDITION'] = Configuration::get('JMARKETPLACE_SHOW_CONDITION');
        $helper->fields_value['JMARKETPLACE_SHOW_PCONDITION'] = Configuration::get('JMARKETPLACE_SHOW_PCONDITION');
        $helper->fields_value['JMARKETPLACE_SHOW_DESC_SHORT'] = Configuration::get('JMARKETPLACE_SHOW_DESC_SHORT');
        $helper->fields_value['JMARKETPLACE_SHOW_DESC'] = Configuration::get('JMARKETPLACE_SHOW_DESC');

        return $helper->generateForm($this->fields_form);
    }
    
    private function displayFormSellerProductPrices()
    {
        $languages = Language::getLanguages(false);
        foreach ($languages as $k => $language) {
            $languages[$k]['is_default'] = (int)$language['id_lang'] == Configuration::get('PS_LANG_DEFAULT');
        }

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->languages = $languages;
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = true;
        $helper->toolbar_scroll = true;
        $helper->title = $this->displayName;
        $helper->submit_action = 'submitSellerProductTabPrices';

        $this->fields_form[0]['form'] = array(
            'tinymce' => false,
            'legend' => array(
                'title' => $this->l('Tab prices')
            ),
            'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show price'),
                    'name' => 'JMARKETPLACE_SHOW_PRICE',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show wholesale price'),
                    'name' => 'JMARKETPLACE_SHOW_WHOLESALEPRICE',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show offer price'),
                    'name' => 'JMARKETPLACE_SHOW_OFFER_PRICE',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show unit price'),
                    'name' => 'JMARKETPLACE_SHOW_UNIT_PRICE',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show tax'),
                    'name' => 'JMARKETPLACE_SHOW_TAX',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show commission'),
                    'name' => 'JMARKETPLACE_SHOW_COMMISSION',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show on sale'),
                    'name' => 'JMARKETPLACE_SHOW_ON_SALE',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
            ),
            'submit' => array(
                'name' => 'submitSellerProductTabPrices',
                'title' => $this->l('Save'),
            ),
        );

        $helper->fields_value['JMARKETPLACE_SHOW_PRICE'] = Configuration::get('JMARKETPLACE_SHOW_PRICE');
        $helper->fields_value['JMARKETPLACE_SHOW_WHOLESALEPRICE'] = Configuration::get('JMARKETPLACE_SHOW_WHOLESALEPRICE');
        $helper->fields_value['JMARKETPLACE_SHOW_OFFER_PRICE'] = Configuration::get('JMARKETPLACE_SHOW_OFFER_PRICE');
        $helper->fields_value['JMARKETPLACE_SHOW_UNIT_PRICE'] = Configuration::get('JMARKETPLACE_SHOW_UNIT_PRICE');
        $helper->fields_value['JMARKETPLACE_SHOW_TAX'] = Configuration::get('JMARKETPLACE_SHOW_TAX');
        $helper->fields_value['JMARKETPLACE_SHOW_COMMISSION'] = Configuration::get('JMARKETPLACE_SHOW_COMMISSION');
        $helper->fields_value['JMARKETPLACE_SHOW_ON_SALE'] = Configuration::get('JMARKETPLACE_SHOW_ON_SALE');

        return $helper->generateForm($this->fields_form);
    }
    
    private function displayFormSellerProductSEO()
    {
        $languages = Language::getLanguages(false);
        foreach ($languages as $k => $language) {
            $languages[$k]['is_default'] = (int)$language['id_lang'] == Configuration::get('PS_LANG_DEFAULT');
        }

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->languages = $languages;
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = true;
        $helper->toolbar_scroll = true;
        $helper->title = $this->displayName;
        $helper->submit_action = 'submitSellerProductTabSeo';

        $this->fields_form[0]['form'] = array(
            'tinymce' => false,
            'legend' => array(
                'title' => $this->l('Tab SEO')
            ),
            'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show meta keywords'),
                    'name' => 'JMARKETPLACE_SHOW_META_KEYWORDS',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show meta title'),
                    'name' => 'JMARKETPLACE_SHOW_META_TITLE',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show meta description'),
                    'name' => 'JMARKETPLACE_SHOW_META_DESC',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Friendly URL'),
                    'name' => 'JMARKETPLACE_SHOW_LINK_REWRITE',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
            ),
            'submit' => array(
                'name' => 'submitSellerProductTabSeo',
                'title' => $this->l('Save'),
            ),
        );

        $helper->fields_value['JMARKETPLACE_SHOW_META_KEYWORDS'] = Configuration::get('JMARKETPLACE_SHOW_META_KEYWORDS');
        $helper->fields_value['JMARKETPLACE_SHOW_META_TITLE'] = Configuration::get('JMARKETPLACE_SHOW_META_TITLE');
        $helper->fields_value['JMARKETPLACE_SHOW_META_DESC'] = Configuration::get('JMARKETPLACE_SHOW_META_DESC');
        $helper->fields_value['JMARKETPLACE_SHOW_LINK_REWRITE'] = Configuration::get('JMARKETPLACE_SHOW_LINK_REWRITE');

        return $helper->generateForm($this->fields_form);
    }
    
    private function displayFormSellerProductAssociations()
    {
        $selected_categories = SellerCategory::getSelectedCategories($this->context->shop->id);
        $finalCategories = array();
            
        foreach ($selected_categories as $category) {
            $finalCategories[] = $category['id_category'];
        }
        
        $languages = Language::getLanguages(false);
        foreach ($languages as $k => $language) {
            $languages[$k]['is_default'] = (int)$language['id_lang'] == Configuration::get('PS_LANG_DEFAULT');
        }

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->languages = $languages;
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = true;
        $helper->toolbar_scroll = true;
        $helper->title = $this->displayName;
        $helper->submit_action = 'submitSellerProductTabAssociations';

        $this->fields_form[0]['form'] = array(
            'tinymce' => false,
            'legend' => array(
                'title' => $this->l('Tab Associations')
            ),
            'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show select categories'),
                    'name' => 'JMARKETPLACE_SHOW_CATEGORIES',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                 array(
                    'type' => 'categories',
                    'label' => $this->l('Selected categories to sellers'),
                    'name' => 'categories',
                    'tree' => array(
                        'id' => 'categories',
                        'title' => $this->l('Choose categories where sellers can add their products.'),
                        'use_search' => false,
                        'use_checkbox' => true,
                        'selected_categories' => $finalCategories
                    )
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show select suppliers'),
                    'name' => 'JMARKETPLACE_SHOW_SUPPLIERS',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Add new suppliers'),
                    'name' => 'JMARKETPLACE_NEW_SUPPLIERS',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show select manufacturers'),
                    'name' => 'JMARKETPLACE_SHOW_MANUFACTURERS',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Add new manufacturers'),
                    'name' => 'JMARKETPLACE_NEW_MANUFACTURERS',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
            ),
            'submit' => array(
                'name' => 'submitSellerProductTabAssociations',
                'title' => $this->l('Save'),
            ),
        );

        $helper->fields_value['JMARKETPLACE_SHOW_CATEGORIES'] = Configuration::get('JMARKETPLACE_SHOW_CATEGORIES');
        $helper->fields_value['JMARKETPLACE_SHOW_SUPPLIERS'] = Configuration::get('JMARKETPLACE_SHOW_SUPPLIERS');
        $helper->fields_value['JMARKETPLACE_NEW_SUPPLIERS'] = Configuration::get('JMARKETPLACE_NEW_SUPPLIERS');
        $helper->fields_value['JMARKETPLACE_SHOW_MANUFACTURERS'] = Configuration::get('JMARKETPLACE_SHOW_MANUFACTURERS');
        $helper->fields_value['JMARKETPLACE_NEW_MANUFACTURERS'] = Configuration::get('JMARKETPLACE_NEW_MANUFACTURERS');

        return $helper->generateForm($this->fields_form);
    }
    
    private function displayFormSellerProductShipping()
    {
        $languages = Language::getLanguages(false);
        foreach ($languages as $k => $language) {
            $languages[$k]['is_default'] = (int)$language['id_lang'] == Configuration::get('PS_LANG_DEFAULT');
        }

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->languages = $languages;
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = true;
        $helper->toolbar_scroll = true;
        $helper->title = $this->displayName;
        $helper->submit_action = 'submitSellerProductTabShipping';

        $this->fields_form[0]['form'] = array(
            'tinymce' => false,
            'legend' => array(
                'title' => $this->l('Tab Shipping')
            ),
            'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show width'),
                    'name' => 'JMARKETPLACE_SHOW_WIDTH',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show depth'),
                    'name' => 'JMARKETPLACE_SHOW_DEPTH',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show height'),
                    'name' => 'JMARKETPLACE_SHOW_HEIGHT',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show weight'),
                    'name' => 'JMARKETPLACE_SHOW_WEIGHT',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show shipping by product'),
                    'name' => 'JMARKETPLACE_SHOW_SHIP_PRODUCT',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
            ),
            'submit' => array(
                'name' => 'submitSellerProductTabShipping',
                'title' => $this->l('Save'),
            ),
        );

        $helper->fields_value['JMARKETPLACE_SHOW_WIDTH'] = Configuration::get('JMARKETPLACE_SHOW_WIDTH');
        $helper->fields_value['JMARKETPLACE_SHOW_HEIGHT'] = Configuration::get('JMARKETPLACE_SHOW_HEIGHT');
        $helper->fields_value['JMARKETPLACE_SHOW_DEPTH'] = Configuration::get('JMARKETPLACE_SHOW_DEPTH');
        $helper->fields_value['JMARKETPLACE_SHOW_WEIGHT'] = Configuration::get('JMARKETPLACE_SHOW_WEIGHT');
        $helper->fields_value['JMARKETPLACE_SHOW_SHIP_PRODUCT'] = Configuration::get('JMARKETPLACE_SHOW_SHIP_PRODUCT');

        return $helper->generateForm($this->fields_form);
    }

    private function displayFormSellerProductCombinations()
    {
        $languages = Language::getLanguages(false);
        foreach ($languages as $k => $language) {
            $languages[$k]['is_default'] = (int)$language['id_lang'] == Configuration::get('PS_LANG_DEFAULT');
        }

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->languages = $languages;
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = true;
        $helper->toolbar_scroll = true;
        $helper->title = $this->displayName;
        $helper->submit_action = 'submitSellerProductTabCombinations';

        $this->fields_form[0]['form'] = array(
            'tinymce' => false,
            'legend' => array(
                'title' => $this->l('Tab Combinations')
            ),
            'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show attributes'),
                    'name' => 'JMARKETPLACE_SHOW_ATTRIBUTES',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
            ),
            'submit' => array(
                'name' => 'submitSellerProductTabFeatures',
                'title' => $this->l('Save'),
            ),
        );

        $helper->fields_value['JMARKETPLACE_SHOW_ATTRIBUTES'] = Configuration::get('JMARKETPLACE_SHOW_ATTRIBUTES');

        return $helper->generateForm($this->fields_form);
    }
    
    private function displayFormSellerProductQuantities()
    {
        $languages = Language::getLanguages(false);
        foreach ($languages as $k => $language) {
            $languages[$k]['is_default'] = (int)$language['id_lang'] == Configuration::get('PS_LANG_DEFAULT');
        }

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->languages = $languages;
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = true;
        $helper->toolbar_scroll = true;
        $helper->title = $this->displayName;
        $helper->submit_action = 'submitSellerProductTabQuantities';

        $this->fields_form[0]['form'] = array(
            'tinymce' => false,
            'legend' => array(
                'title' => $this->l('Tab Quantities')
            ),
            'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show quantity'),
                    'name' => 'JMARKETPLACE_SHOW_QUANTITY',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show minimal quantity'),
                    'name' => 'JMARKETPLACE_SHOW_MINIMAL_QTY',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show availability preferences'),
                    'name' => 'JMARKETPLACE_SHOW_AVAILABILITY',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show available now'),
                    'name' => 'JMARKETPLACE_SHOW_AVAILABLE_NOW',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show available later'),
                    'name' => 'JMARKETPLACE_SHOW_AVAILABLE_LAT',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show available date'),
                    'name' => 'JMARKETPLACE_SHOW_AVAILABLE_DATE',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
            ),
            'submit' => array(
                'name' => 'submitSellerProductTabQuantities',
                'title' => $this->l('Save'),
            ),
        );

        $helper->fields_value['JMARKETPLACE_SHOW_QUANTITY'] = Configuration::get('JMARKETPLACE_SHOW_QUANTITY');
        $helper->fields_value['JMARKETPLACE_SHOW_MINIMAL_QTY'] = Configuration::get('JMARKETPLACE_SHOW_MINIMAL_QTY');
        $helper->fields_value['JMARKETPLACE_SHOW_AVAILABILITY'] = Configuration::get('JMARKETPLACE_SHOW_AVAILABILITY');
        $helper->fields_value['JMARKETPLACE_SHOW_AVAILABLE_NOW'] = Configuration::get('JMARKETPLACE_SHOW_AVAILABLE_NOW');
        $helper->fields_value['JMARKETPLACE_SHOW_AVAILABLE_LAT'] = Configuration::get('JMARKETPLACE_SHOW_AVAILABLE_LAT');
        $helper->fields_value['JMARKETPLACE_SHOW_AVAILABLE_DATE'] = Configuration::get('JMARKETPLACE_SHOW_AVAILABLE_DATE');

        return $helper->generateForm($this->fields_form);
    }
    
    private function displayFormSellerProductImages()
    {
        $languages = Language::getLanguages(false);
        foreach ($languages as $k => $language) {
            $languages[$k]['is_default'] = (int)$language['id_lang'] == Configuration::get('PS_LANG_DEFAULT');
        }

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->languages = $languages;
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = true;
        $helper->toolbar_scroll = true;
        $helper->title = $this->displayName;
        $helper->submit_action = 'submitSellerProductTabImages';

        $this->fields_form[0]['form'] = array(
            'tinymce' => false,
            'legend' => array(
                'title' => $this->l('Tab Images')
            ),
            'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show images'),
                    'name' => 'JMARKETPLACE_SHOW_IMAGES',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Maximum number of images to upload'),
                    'name' => 'JMARKETPLACE_MAX_IMAGES',
                    'required' => false,
                    'lang' => false,
                    'col' => 2,
                ),
            ),
            'submit' => array(
                'name' => 'submitSellerProductTabImages',
                'title' => $this->l('Save'),
            ),
        );

        $helper->fields_value['JMARKETPLACE_SHOW_IMAGES'] = Configuration::get('JMARKETPLACE_SHOW_IMAGES');
        $helper->fields_value['JMARKETPLACE_MAX_IMAGES'] = Configuration::get('JMARKETPLACE_MAX_IMAGES');

        return $helper->generateForm($this->fields_form);
    }
    
    private function displayFormSellerProductFeatures()
    {
        $languages = Language::getLanguages(false);
        foreach ($languages as $k => $language) {
            $languages[$k]['is_default'] = (int)$language['id_lang'] == Configuration::get('PS_LANG_DEFAULT');
        }

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->languages = $languages;
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = true;
        $helper->toolbar_scroll = true;
        $helper->title = $this->displayName;
        $helper->submit_action = 'submitSellerProductTabFeatures';

        $this->fields_form[0]['form'] = array(
            'tinymce' => false,
            'legend' => array(
                'title' => $this->l('Tab Features')
            ),
            'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show features'),
                    'name' => 'JMARKETPLACE_SHOW_FEATURES',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
            ),
            'submit' => array(
                'name' => 'submitSellerProductTabFeatures',
                'title' => $this->l('Save'),
            ),
        );

        $helper->fields_value['JMARKETPLACE_SHOW_FEATURES'] = Configuration::get('JMARKETPLACE_SHOW_FEATURES');

        return $helper->generateForm($this->fields_form);
    }
    
    private function displayFormSellerProductVirtual()
    {
        $languages = Language::getLanguages(false);
        foreach ($languages as $k => $language) {
            $languages[$k]['is_default'] = (int)$language['id_lang'] == Configuration::get('PS_LANG_DEFAULT');
        }

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->languages = $languages;
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = true;
        $helper->toolbar_scroll = true;
        $helper->title = $this->displayName;
        $helper->submit_action = 'submitSellerProductTabVirtual';

        $this->fields_form[0]['form'] = array(
            'tinymce' => false,
            'legend' => array(
                'title' => $this->l('Tab Virtual product')
            ),
            'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show virtual product'),
                    'name' => 'JMARKETPLACE_SHOW_VIRTUAL',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
            ),
            'submit' => array(
                'name' => 'submitSellerProductTabVirtual',
                'title' => $this->l('Save'),
            ),
        );

        $helper->fields_value['JMARKETPLACE_SHOW_VIRTUAL'] = Configuration::get('JMARKETPLACE_SHOW_VIRTUAL');

        return $helper->generateForm($this->fields_form);
    }
    
    private function displayFormSellerProductAttachments()
    {
        $languages = Language::getLanguages(false);
        foreach ($languages as $k => $language) {
            $languages[$k]['is_default'] = (int)$language['id_lang'] == Configuration::get('PS_LANG_DEFAULT');
        }

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->languages = $languages;
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = true;
        $helper->toolbar_scroll = true;
        $helper->title = $this->displayName;
        $helper->submit_action = 'submitSellerProductTabAttachments';

        $this->fields_form[0]['form'] = array(
            'tinymce' => false,
            'legend' => array(
                'title' => $this->l('Tab Attachments')
            ),
            'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show attachments'),
                    'name' => 'JMARKETPLACE_SHOW_ATTACHMENTS',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Maximum number of attachments to upload'),
                    'name' => 'JMARKETPLACE_MAX_ATTACHMENTS',
                    'required' => false,
                    'lang' => false,
                    'col' => 2,
                ),
            ),
            'submit' => array(
                'name' => 'submitSellerProductTabAttachments',
                'title' => $this->l('Save'),
            ),
        );

        $helper->fields_value['JMARKETPLACE_SHOW_ATTACHMENTS'] = Configuration::get('JMARKETPLACE_SHOW_ATTACHMENTS');
        $helper->fields_value['JMARKETPLACE_MAX_ATTACHMENTS'] = Configuration::get('JMARKETPLACE_MAX_ATTACHMENTS');

        return $helper->generateForm($this->fields_form);
    }
    
    private function displayFormSellerProductSettings()
    {
        return $this->displayFormSellerProductInformation().
            $this->displayFormSellerProductPrices().
            $this->displayFormSellerProductSEO().
            $this->displayFormSellerProductAssociations().
            $this->displayFormSellerProductShipping().
            $this->displayFormSellerProductCombinations().
            $this->displayFormSellerProductQuantities().
            $this->displayFormSellerProductImages().
            $this->displayFormSellerProductFeatures().
            $this->displayFormSellerProductVirtual().
            $this->displayFormSellerProductAttachments();
    }
    
    private function displayFormEmailSettings()
    {
        $languages = Language::getLanguages(false);
        foreach ($languages as $k => $language) {
            $languages[$k]['is_default'] = (int)$language['id_lang'] == Configuration::get('PS_LANG_DEFAULT');
        }

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->languages = $languages;
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = true;
        $helper->toolbar_scroll = true;
        $helper->title = $this->displayName;
        $helper->submit_action = 'submitEmailSettings';

        $this->fields_form[0]['form'] = array(
            'tinymce' => false,
            'legend' => array(
                'title' => $this->l('Emails')
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Email'),
                    'name' => 'JMARKETPLACE_SEND_ADMIN',
                    'desc' => $this->l('This email receives all notifications from the marketplace.'),
                    'required' => false,
                    'lang' => false,
                    'col' => 6,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Send email to administrator when seller register'),
                    'name' => 'JMARKETPLACE_SEND_ADMIN_REGISTER',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Send email to admininstrator when selle add new product'),
                    'name' => 'JMARKETPLACE_SEND_ADMIN_PRODUCT',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Send email to admininstrator when a seller requests a commissions payment'),
                    'name' => 'JMARKETPLACE_SEND_ADMIN_REQUEST',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Send email to administrator (in copy) when customers and sellers send a message or incidence.'),
                    'name' => 'JMARKETPLACE_SEND_ADMIN_INCIDENCE',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Send email to administrator when seller change order status'),
                    'name' => 'JMARKETPLACE_SEND_ORDER_CHANGED',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Send welcome email to seller'),
                    'name' => 'JMARKETPLACE_SEND_SELLER_WELCOME',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Send email to seller when your account has been activated'),
                    'name' => 'JMARKETPLACE_SEND_SELLER_ACTIVE',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Send email to seller when your product has been activated'),
                    'name' => 'JMARKETPLACE_SEND_PRODUCT_ACTIVE',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Send email to seller when your product has been solded'),
                    'name' => 'JMARKETPLACE_SEND_PRODUCT_SOLD',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
            ),
            'submit' => array(
                'name' => 'submitEmailSettings',
                'title' => $this->l('Save'),
            ),
        );
        
        $helper->fields_value['JMARKETPLACE_SEND_ADMIN'] = Configuration::get('JMARKETPLACE_SEND_ADMIN');
        $helper->fields_value['JMARKETPLACE_SEND_ADMIN_REGISTER'] = Configuration::get('JMARKETPLACE_SEND_ADMIN_REGISTER');
        $helper->fields_value['JMARKETPLACE_SEND_ADMIN_PRODUCT'] = Configuration::get('JMARKETPLACE_SEND_ADMIN_PRODUCT');
        $helper->fields_value['JMARKETPLACE_SEND_ADMIN_REQUEST'] = Configuration::get('JMARKETPLACE_SEND_ADMIN_REQUEST');
        $helper->fields_value['JMARKETPLACE_SEND_SELLER_WELCOME'] = Configuration::get('JMARKETPLACE_SEND_SELLER_WELCOME');
        $helper->fields_value['JMARKETPLACE_SEND_SELLER_ACTIVE'] = Configuration::get('JMARKETPLACE_SEND_SELLER_ACTIVE');
        $helper->fields_value['JMARKETPLACE_SEND_PRODUCT_ACTIVE'] = Configuration::get('JMARKETPLACE_SEND_PRODUCT_ACTIVE');
        $helper->fields_value['JMARKETPLACE_SEND_PRODUCT_SOLD'] = Configuration::get('JMARKETPLACE_SEND_PRODUCT_SOLD');
        $helper->fields_value['JMARKETPLACE_SEND_ORDER_CHANGED'] = Configuration::get('JMARKETPLACE_SEND_ORDER_CHANGED');
        $helper->fields_value['JMARKETPLACE_SEND_ADMIN_INCIDENCE'] = Configuration::get('JMARKETPLACE_SEND_ADMIN_INCIDENCE');

        return $helper->generateForm($this->fields_form);
    }
    
    private function displayFormThemeSettings()
    {
        $languages = Language::getLanguages(false);
        foreach ($languages as $k => $language) {
            $languages[$k]['is_default'] = (int)$language['id_lang'] == Configuration::get('PS_LANG_DEFAULT');
        }

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->languages = $languages;
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = true;
        $helper->toolbar_scroll = true;
        $helper->title = $this->displayName;
        $helper->submit_action = 'submitThemeSettings';

        $this->fields_form[0]['form'] = array(
            'tinymce' => false,
            'legend' => array(
                'title' => $this->l('Front office theme')
            ),
            'input' => array(
                array(
                    'type' => 'radio',
                    'label' => $this->l('Technology used in your theme'),
                    'name' => 'JMARKETPLACE_THEME',
                    'required' => false,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'default',
                            'value' => 'default',
                            'label' => $this->l('Media Queries')
                        ),
                        array(
                            'id' => 'default-bootstrap',
                            'value' => 'default-bootstrap',
                            'label' => $this->l('Bootstrap 3.0+')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show tabs'),
                    'name' => 'JMARKETPLACE_TABS',
                    'desc' => $this->l('Page to add products and edit product has tabs.'),
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show top menu'),
                    'name' => 'JMARKETPLACE_MENU_TOP',
                    'desc' => $this->l('Show top menu of options on all page.'),
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show menu options'),
                    'name' => 'JMARKETPLACE_MENU_OPTIONS',
                    'desc' => $this->l('Show side menu of options on all page.'),
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Custom styles'),
                    'name' => 'JMARKETPLACE_CUSTOM_STYLES',
                    'desc' => $this->l('In this text field you can add custom style rules for your theme.'),
                    'required' => false,
                    'lang' => false,
                    'autoload_rte' => false,
                    'col' => 6,
                ),
            ),
            'submit' => array(
                'name' => 'submitThemeSettings',
                'title' => $this->l('Save'),
            ),
        );
        
        $helper->fields_value['JMARKETPLACE_THEME'] = Configuration::get('JMARKETPLACE_THEME');
        $helper->fields_value['JMARKETPLACE_TABS'] = Configuration::get('JMARKETPLACE_TABS');
        $helper->fields_value['JMARKETPLACE_MENU_TOP'] = Configuration::get('JMARKETPLACE_MENU_TOP');
        $helper->fields_value['JMARKETPLACE_MENU_OPTIONS'] = Configuration::get('JMARKETPLACE_MENU_OPTIONS');
        $helper->fields_value['JMARKETPLACE_CUSTOM_STYLES'] = Configuration::get('JMARKETPLACE_CUSTOM_STYLES');

        return $helper->generateForm($this->fields_form);
    }
    
    private function displayFormRouteSettings()
    {
        $url_shop = 'https://www.your-shop.com/';
        
        $languages = Language::getLanguages(false);
        foreach ($languages as $k => $language) {
            $languages[$k]['is_default'] = (int)$language['id_lang'] == Configuration::get('PS_LANG_DEFAULT');
        }

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->languages = $languages;
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = true;
        $helper->toolbar_scroll = true;
        $helper->title = $this->displayName;
        $helper->submit_action = 'submitRouteSettings';

        $this->fields_form[0]['form'] = array(
            'tinymce' => false,
            'legend' => array(
                'title' => $this->l('Dynamic routes settings')
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Main route'),
                    'name' => 'JMARKETPLACE_MAIN_ROUTE',
                    'desc' => $this->l('Exemple of a seller link:').' '.$url_shop.$this->context->language->iso_code.'/<strong>'.Configuration::get('JMARKETPLACE_MAIN_ROUTE', $this->context->language->id).'</strong>/1_demo-demo/',
                    'required' => false,
                    'lang' => true,
                    'col' => 6,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Route for seller products'),
                    'name' => 'JMARKETPLACE_ROUTE_PRODUCTS',
                    'desc' => $this->l('Example of link of the products of a seller:').' '.$url_shop.$this->context->language->iso_code.'/<strong>'.Configuration::get('JMARKETPLACE_MAIN_ROUTE', $this->context->language->id).'</strong>/1_demo-demo/<strong>'.Configuration::get('JMARKETPLACE_ROUTE_PRODUCTS', $this->context->language->id).'</strong>',
                    'required' => false,
                    'lang' => true,
                    'col' => 6,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Route for seller comments'),
                    'name' => 'JMARKETPLACE_ROUTE_COMMENTS',
                    'desc' => $this->l('Example of link of the comments of a seller:').' '.$url_shop.$this->context->language->iso_code.'/<strong>'.Configuration::get('JMARKETPLACE_MAIN_ROUTE', $this->context->language->id).'</strong>/1_demo-demo/<strong>'.Configuration::get('JMARKETPLACE_ROUTE_COMMENTS', $this->context->language->id).'</strong>',
                    'required' => false,
                    'lang' => true,
                    'col' => 6,
                ),
            ),
            'submit' => array(
                'name' => 'submitRouteSettings',
                'title' => $this->l('Save'),
            ),
        );
        
        foreach ($languages as $lang) {
            $helper->fields_value['JMARKETPLACE_MAIN_ROUTE'][$lang['id_lang']] = Tools::getValue('JMARKETPLACE_MAIN_ROUTE_'.$lang['id_lang'], Configuration::get('JMARKETPLACE_MAIN_ROUTE', $lang['id_lang']));
            $helper->fields_value['JMARKETPLACE_ROUTE_PRODUCTS'][$lang['id_lang']] = Tools::getValue('JMARKETPLACE_ROUTE_PRODUCTS_'.$lang['id_lang'], Configuration::get('JMARKETPLACE_ROUTE_PRODUCTS', $lang['id_lang']));
            $helper->fields_value['JMARKETPLACE_ROUTE_COMMENTS'][$lang['id_lang']] = Tools::getValue('JMARKETPLACE_ROUTE_COMMENTS_'.$lang['id_lang'], Configuration::get('JMARKETPLACE_ROUTE_COMMENTS', $lang['id_lang']));
        }
        
        $this->context->smarty->assign(array(
            'url_sellers' => $this->context->link->getModuleLink('jmarketplace', 'sellers', array(), true),
            'url_metas' => 'index.php?tab=AdminMeta&token='.Tools::getAdminToken('AdminMeta'.(int)Tab::getIdFromClassName('AdminMeta').(int)$this->context->employee->id),
        ));

        return $this->context->smarty->fetch($this->local_path.'views/templates/admin/routes.tpl').$helper->generateForm($this->fields_form);
    }
    
    private function displayFormPayments()
    {
        $languages = Language::getLanguages(false);
        foreach ($languages as $k => $language) {
            $languages[$k]['is_default'] = (int)$language['id_lang'] == Configuration::get('PS_LANG_DEFAULT');
        }

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->languages = $languages;
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = true;
        $helper->toolbar_scroll = true;
        $helper->title = $this->displayName;
        $helper->submit_action = 'submitPayments';

        $this->fields_form[0]['form'] = array(
            'tinymce' => false,
            'legend' => array(
                'title' => $this->l('Seller payment')
            ),
            'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Paypal'),
                    'name' => 'JMARKETPLACE_PAYPAL',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'desc' => $this->l('Pay your sellers with Paypal'),
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Bankwire'),
                    'name' => 'JMARKETPLACE_BANKWIRE',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'desc' => $this->l('Pay your sellers with Bankwire'),
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
            ),
            'submit' => array(
                'name' => 'submitPayments',
                'title' => $this->l('Save'),
            ),
        );
        
        $helper->fields_value['JMARKETPLACE_PAYPAL'] = Configuration::get('JMARKETPLACE_PAYPAL');
        $helper->fields_value['JMARKETPLACE_BANKWIRE'] = Configuration::get('JMARKETPLACE_BANKWIRE');

        return $helper->generateForm($this->fields_form);
    }
    
    public function hookDisplayCustomerAccount($params)
    {
        $customer_can_be_seller = false;
        $customer_groups = Customer::getGroupsStatic($this->context->cookie->id_customer);

        foreach ($customer_groups as $id_group) {
            if (Configuration::get('JMARKETPLACE_CUSTOMER_GROUP_'.$id_group) == 1) {
                $customer_can_be_seller = true;
            }
        }
        
        $this->context->smarty->assign(array(
            'is_seller' => Seller::isSeller($this->context->cookie->id_customer, $this->context->shop->id),
            'is_active_seller' => Seller::isActiveSellerByCustomer($this->context->cookie->id_customer),
            'customer_can_be_seller' => $customer_can_be_seller,
            'id_default_group' => $this->context->customer->id_default_group,
            'show_contact' => Configuration::get('JMARKETPLACE_SHOW_CONTACT'),
            'show_seller_favorite' => Configuration::get('JMARKETPLACE_SELLER_FAVORITE'),
            'ssl_enabled' => Configuration::get('PS_SSL_ENABLED')
        ));

        return $this->display(__FILE__, 'customer-account.tpl');
    }
    
    public function hookDisplayMyAccountBlock($params)
    {
        $customer_can_be_seller = false;
        $customer_groups = Customer::getGroupsStatic($this->context->cookie->id_customer);

        foreach ($customer_groups as $id_group) {
            if (Configuration::get('JMARKETPLACE_CUSTOMER_GROUP_'.$id_group) == 1) {
                $customer_can_be_seller = true;
            }
        }
        
        $this->context->smarty->assign(array(
            'is_seller' => Seller::isSeller($this->context->cookie->id_customer, $this->context->shop->id),
            'is_active_seller' => Seller::isActiveSellerByCustomer($this->context->cookie->id_customer),
            'customer_can_be_seller' => $customer_can_be_seller,
            'id_default_group' => $this->context->customer->id_default_group,
            'show_contact' => Configuration::get('JMARKETPLACE_SHOW_CONTACT'),
            'show_seller_favorite' => Configuration::get('JMARKETPLACE_SELLER_FAVORITE'),
            'ssl_enabled' => Configuration::get('PS_SSL_ENABLED')
        ));

        return $this->display(__FILE__, 'myaccount-block.tpl');
    }
    
    public function hookDisplayMyAccountBlockFooter($params)
    {
        return $this->hookDisplayMyAccountBlock($params);
    }
    
    public function hookDisplayProductButtons($params)
    {
        $id_product = (int)Tools::getValue('id_product');
        $id_seller = SellerProduct::isSellerProduct($id_product);
        
        if ($id_seller) {
            $seller = new Seller($id_seller);
            $params = array('id_seller' => $id_seller, 'id_product' => $id_product);
            $params_seller_profile = array('id_seller' => $seller->id, 'link_rewrite' => $seller->link_rewrite);
            $url_contact_seller = $this->context->link->getModuleLink('jmarketplace', 'contactseller', $params, true);
            $url_favorite_seller = $this->context->link->getModuleLink('jmarketplace', 'favoriteseller', $params, true);
            $url_seller_profile = Jmarketplace::getJmarketplaceLink('jmarketplace_seller_rule', $params_seller_profile);
            $url_seller_products = Jmarketplace::getJmarketplaceLink('jmarketplace_sellerproductlist_rule', $params_seller_profile);
            $url_seller_comments = Jmarketplace::getJmarketplaceLink('jmarketplace_sellercomments_rule', $params_seller_profile);

            $this->context->smarty->assign(array(
                'url_contact_seller' => $url_contact_seller,
                'seller_link' => $url_seller_profile,
                'url_seller_comments' => $url_seller_comments,
                'url_seller_products' => $url_seller_products,
                'url_favorite_seller' => $url_favorite_seller,
                'show_contact_seller' => Configuration::get('JMARKETPLACE_SHOW_CONTACT'),
                'show_seller_profile' => Configuration::get('JMARKETPLACE_SHOW_PROFILE'),
                'show_seller_favorite' => Configuration::get('JMARKETPLACE_SELLER_FAVORITE'),
                'show_seller_rating' => Configuration::get('JMARKETPLACE_SELLER_RATING'),
                'seller' => $seller,
            ));

            if (Configuration::get('JMARKETPLACE_SELLER_RATING')) {
                $average = SellerComment::getRatings($id_seller);
                $averageTotal = SellerComment::getCommentNumber($id_seller);

                $this->context->smarty->assign(array(
                    'averageTotal' => (int)$averageTotal,
                    'averageMiddle' => ceil($average['avg']),
                ));
            }
        }
        
        $this->context->smarty->assign(array(
            'is_product_seller' => $id_seller,
            'PS_REWRITING_SETTINGS' => Configuration::get('PS_REWRITING_SETTINGS'),
            'show_manage_carriers' => Configuration::get('JMARKETPLACE_SHOW_MANAGE_CARRIER'),
        ));
        
        return $this->display(__FILE__, 'product-buttons.tpl');
    }
    
    public function hookDisplayProductListReviews($params)
    {
        if (Configuration::get('JMARKETPLACE_SHOW_PROFILE') == 1) {
            $id_product = (int)$params['product']['id_product'];
            $id_seller = SellerProduct::isSellerProduct($id_product);

            if ($id_seller && Configuration::get('JMARKETPLACE_SHOW_SELLER_PLIST') == 1) {
                $seller = new Seller($id_seller);
                $params = array('id_seller' => $seller->id, 'link_rewrite' => $seller->link_rewrite);
                $url_seller_profile = $this->getJmarketplaceLink('jmarketplace_seller_rule', $params);

                $this->context->smarty->assign(array(
                    'seller' => $seller,
                    'seller_link' => $url_seller_profile,
                ));

                return $this->display(__FILE__, 'product-list.tpl');
            }
        }
    }
    
    public function hookDisplayMarketplaceHeader($params)
    {
        $id_seller = Seller::getSellerByCustomer($this->context->cookie->id_customer);
        $total_funds = 0;
        
        if (Configuration::get('JMARKETPLACE_SHOW_SELLER_INVOICE') == 1) {
            $orders = SellerTransferCommission::getCommissionHistoryBySeller($id_seller, (int)$this->context->language->id, (int)$this->context->shop->id);
            if (is_array($orders) && count($orders) > 0) {
                foreach ($orders as $o) {
                    $currency_from = new Currency($o['id_currency']);
                    if (SellerTransferCommission::isSellerTransferCommission($o['id_seller_commission_history']) == 0) {
                        if (Configuration::get('JMARKETPLACE_TAX_COMMISSION') == 1) {
                            $total_funds = $total_funds + Tools::convertPriceFull($o['total_commission_tax_incl'], $currency_from, $this->context->currency);
                        } else {
                            $total_funds = $total_funds + Tools::convertPriceFull($o['total_commission_tax_excl'], $currency_from, $this->context->currency);
                        }
                    }
                }
            }
        }
        
        $this->context->smarty->assign(array(
            'mesages_not_readed' => SellerIncidenceMessage::getNumMessagesNotReadedBySeller($id_seller),
            'total_funds' => Tools::displayPrice($total_funds, $this->context->currency->id)
        ));
        
        return $this->display(__FILE__, 'selleraccount-top.tpl');
    }
    
    public function hookDisplayMarketplaceWidget($params)
    {
        $id_seller = Seller::getSellerByCustomer($this->context->cookie->id_customer);
        $total_funds = 0;
        
        if (Configuration::get('JMARKETPLACE_SHOW_SELLER_INVOICE') == 1) {
            $orders = SellerTransferCommission::getCommissionHistoryBySeller($id_seller, (int)$this->context->language->id, (int)$this->context->shop->id);
            if (is_array($orders) && count($orders) > 0) {
                foreach ($orders as $o) {
                    $currency_from = new Currency($o['id_currency']);
                    if (SellerTransferCommission::isSellerTransferCommission($o['id_seller_commission_history']) == 0) {
                        if (Configuration::get('JMARKETPLACE_TAX_COMMISSION') == 1) {
                            $total_funds = $total_funds + Tools::convertPriceFull($o['total_commission_tax_incl'], $currency_from, $this->context->currency);
                        } else {
                            $total_funds = $total_funds + Tools::convertPriceFull($o['total_commission_tax_excl'], $currency_from, $this->context->currency);
                        }
                    }
                }
            }
        }
        
        $this->context->smarty->assign(array(
            'mesages_not_readed' => SellerIncidenceMessage::getNumMessagesNotReadedBySeller($id_seller),
            'total_funds' => Tools::displayPrice($total_funds, $this->context->currency->id)
        ));
        
        return $this->display(__FILE__, 'widget.tpl');
    }
    
    public function hookDisplayFooter($params)
    {
        if (Configuration::get('JMARKETPLACE_SHOW_MANAGE_CARRIER') == 1) {
            $this->context->smarty->assign('PS_REWRITING_SETTINGS', 1);
            return $this->display(__FILE__, 'footer.tpl');
        }
    }
    
    public function hookDisplayTop($params)
    {
        if (Configuration::get('JMARKETPLACE_CUSTOM_STYLES') != "") {
            $this->context->smarty->assign('custom_styles', Configuration::get('JMARKETPLACE_CUSTOM_STYLES'));
            return $this->display(__FILE__, 'top.tpl');
        }
    }
    
    public function sendCommission($params)
    {
        if (Validate::isLoadedObject($params['newOrderStatus'])) {
            $id_order = $params['id_order'];
        } else {
            $id_order = $params['order']->id;
        }
        
        $order = new Order($id_order);
        
        //mirar si el pedido tiene comisiones
        $order_has_commissions = SellerCommissionHistory::getCommissionHistoryByOrder($id_order, $this->context->language->id, $this->context->shop->id);
        if ($order_has_commissions) {
            //reactivar comisiones
            SellerCommissionHistory::changeStateCommissionsByOrder($id_order, 'pending');
        } else {
            $this->createCommissionsForProducts($order);
            $this->createSellerOrders($order);
        }
    }
    
    public function createCommissionsForProducts($order)
    {
        $products = $order->getProducts();
        foreach ($products as $p) {
            $id_seller = Seller::getSellerByProduct($p['product_id']);
            if ($id_seller) {
                $seller = new Seller($id_seller);
                if ($seller->active == 1) {
                    $commission = (float)SellerCommission::getCommissionBySeller($id_seller);
                    
                    $params = array('id_seller' => $id_seller, 'id_product' => $p['product_id']);
                    $commission_hooked = Hook::exec('actionMarketplaceBeforeAddSellerCommission', $params);
                    if (isset($commission_hooked) && $commission_hooked != false) {
                        $commission = $commission_hooked;
                    }
                    
                    $sch = new SellerCommissionHistory();
                    $sch->id_order = $order->id;
                    $sch->id_product = $p['product_id'];
                    $sch->product_name = $p['product_name'];
                    $sch->id_seller = $id_seller;
                    $sch->id_shop = $this->context->shop->id;
                    $sch->id_currency = $order->id_currency;
                    $sch->conversion_rate = $order->conversion_rate;
                    $sch->price_tax_excl = $p['unit_price_tax_excl'];
                    $sch->price_tax_incl = $p['unit_price_tax_incl'];
                    $sch->quantity = (int)$p['product_quantity'];
                    $sch->unit_commission_tax_incl = (float)($p['unit_price_tax_incl'] * $commission) / 100;
                    $sch->unit_commission_tax_excl = (float)($p['unit_price_tax_excl'] * $commission) / 100;
                    $sch->total_commission_tax_incl = (float)(($p['unit_price_tax_incl'] * $p['product_quantity']) * $commission) / 100;
                    $sch->total_commission_tax_excl = (float)(($p['unit_price_tax_excl'] * $p['product_quantity']) * $commission) / 100;
                    $sch->id_seller_commission_history_state = SellerCommissionHistoryState::getIdByReference('pending');
                    $sch->add();
                    
                    $params = array('id_seller' => $id_seller, 'id_product' => $p['product_id'], 'id_seller_commission_history' => $sch->id);
                    Hook::exec('actionMarketplaceAfterAddSellerCommission', $params);

                    $this->createFixedCommission($order, $id_seller);
                    $this->notifyNewOrderToSeller($order, $seller, $sch->id_product);
                }
            }
        }
        
        $this->createCommissionForDiscounts($order, $id_seller);
        $this->createCommissionForShipping($order, $id_seller);
    }
    
    public function createSellerOrders($order)
    {
        $products = $order->getProducts();
        $seller_order_products = array();
        foreach ($products as $p) {
            $id_seller = Seller::getSellerByProduct($p['product_id']);
            if ($id_seller) {
                $seller = new Seller($id_seller);
                if ($seller->active == 1) {
                    $commission = (float)SellerCommission::getCommissionBySeller($id_seller);
                    
                    $params = array('id_seller' => $id_seller, 'id_product' => $p['product_id']);
                    $commission_hooked = Hook::exec('actionMarketplaceBeforeAddSellerCommission', $params);
                    if (isset($commission_hooked) && $commission_hooked != false) {
                        $commission = $commission_hooked;
                    }

                    $seller_order_products[$id_seller][] = array(
                        'product_id' => $p['product_id'],
                        'product_attribute_id' => $p['product_attribute_id'],
                        'id_customization' => 0,
                        'product_name' => $p['product_name'],
                        'product_quantity' => $p['product_quantity'],
                        'product_price' => $p['product_price'],
                        'reduction_percent' => $p['reduction_percent'],
                        'reduction_amount' => $p['reduction_amount'],
                        'reduction_amount_tax_incl' => $p['reduction_amount_tax_incl'],
                        'reduction_amount_tax_excl' => $p['reduction_amount_tax_excl'],
                        'group_reduction' => $p['group_reduction'],
                        'product_ean13' => $p['product_ean13'],
                        'product_upc' => $p['product_upc'],
                        'product_isbn' => 0,
                        'product_reference' => $p['product_reference'],
                        'product_weight' => $p['product_weight'],
                        'tax_name' => $p['tax_name'],
                        'tax_rate' => $p['tax_rate'],
                        'tax_computation_method' => $p['tax_computation_method'],
                        'id_tax_rules_group' => $p['id_tax_rules_group'],
                        'ecotax' => $p['ecotax'],
                        'ecotax_tax_rate' => $p['ecotax_tax_rate'],
                        'discount_quantity_applied' => $p['discount_quantity_applied'],
                        'unit_price_tax_incl' => $p['unit_price_tax_incl'],
                        'unit_price_tax_excl' => $p['unit_price_tax_excl'],
                        'total_price_tax_incl' => $p['total_price_tax_incl'],
                        'total_price_tax_excl' => $p['total_price_tax_excl'],
                        'total_shipping_price_tax_excl' => $p['total_shipping_price_tax_excl'],
                        'total_shipping_price_tax_incl' => $p['total_shipping_price_tax_incl'],
                        'unit_commission_tax_excl' => ($p['unit_price_tax_excl'] * $commission) / 100,
                        'unit_commission_tax_incl' => ($p['unit_price_tax_incl'] * $commission) / 100,
                        'total_commission_tax_excl' => ($p['total_price_tax_excl'] * $commission) / 100,
                        'total_commission_tax_incl' => ($p['total_price_tax_incl'] * $commission) / 100,
                    );
                }
            }
        }
        
        if (is_array($seller_order_products) && count($seller_order_products) > 0) {
            foreach ($seller_order_products as $key => $products) {
                $seller_order = new SellerOrder();
                $seller_order->id_shop = $this->context->shop->id;
                $seller_order->id_order = $order->id;
                $seller_order->reference = $order->reference;
                $seller_order->id_seller = $key;
                $seller_order->id_customer = $order->id_customer;
                $seller_order->id_address_delivery = $order->id_address_delivery;
                $seller_order->current_state = $order->current_state;
                $seller_order->id_currency = $order->id_currency;
                $seller_order->conversion_rate = $order->conversion_rate;
                $seller_order->total_discounts = 0;
                $seller_order->total_discounts_tax_incl = 0;
                $seller_order->total_discounts_tax_excl = 0;
                $seller_order->total_paid = 0;
                $seller_order->total_paid_tax_incl = 0;
                $seller_order->total_paid_tax_excl = 0;
                $seller_order->total_products = 0;
                $seller_order->total_products_tax_incl = 0;
                $seller_order->total_products_tax_excl = 0;
                $seller_order->total_shipping = 0;
                $seller_order->total_shipping_tax_incl = 0;
                $seller_order->total_shipping_tax_excl = 0;
                $seller_order->total_wrapping = 0;
                $seller_order->total_wrapping_tax_incl = 0;
                $seller_order->total_wrapping_tax_excl = 0;
                $seller_order->total_fixed_commission = 0;
                $seller_order->total_fixed_commission_tax_incl = 0;
                $seller_order->total_fixed_commission_tax_excl = 0;
                $seller_order->add();
                
                if ($seller_order->id) {
                    foreach ($products as $p) {
                        $seller_order_detail = new SellerOrderDetail();
                        $seller_order_detail->id_seller_order = $seller_order->id;
                        $seller_order_detail->id_shop = $this->context->shop->id;
                        $seller_order_detail->product_id = $p['product_id'];
                        $seller_order_detail->product_attribute_id = $p['product_attribute_id'];
                        $seller_order_detail->id_customization = $p['id_customization'];
                        $seller_order_detail->product_name = $p['product_name'];
                        $seller_order_detail->product_quantity = $p['product_quantity'];
                        $seller_order_detail->product_price = $p['product_price'];
                        $seller_order_detail->reduction_percent = $p['reduction_percent'];
                        $seller_order_detail->reduction_amount = $p['reduction_amount'];
                        $seller_order_detail->reduction_amount_tax_incl = $p['reduction_amount_tax_incl'];
                        $seller_order_detail->reduction_amount_tax_excl = $p['reduction_amount_tax_excl'];
                        $seller_order_detail->group_reduction = $p['group_reduction'];
                        $seller_order_detail->product_ean13 = $p['product_ean13'];
                        $seller_order_detail->product_upc = $p['product_upc'];
                        $seller_order_detail->product_isbn = $p['product_isbn'];
                        $seller_order_detail->product_reference = $p['product_reference'];
                        $seller_order_detail->product_weight = $p['product_weight'];
                        $seller_order_detail->tax_name = $p['tax_name'];
                        $seller_order_detail->tax_rate = $p['tax_rate'];
                        $seller_order_detail->tax_computation_method = $p['tax_computation_method'];
                        $seller_order_detail->id_tax_rules_group = $p['id_tax_rules_group'];
                        $seller_order_detail->ecotax = $p['ecotax'];
                        $seller_order_detail->ecotax_tax_rate = $p['ecotax_tax_rate'];
                        $seller_order_detail->discount_quantity_applied = $p['discount_quantity_applied'];
                        $seller_order_detail->unit_price_tax_incl = $p['unit_price_tax_incl'];
                        $seller_order_detail->unit_price_tax_excl = $p['unit_price_tax_excl'];
                        $seller_order_detail->total_price_tax_incl = $p['total_price_tax_incl'];
                        $seller_order_detail->total_price_tax_excl = $p['total_price_tax_excl'];
                        $seller_order_detail->total_shipping_price_tax_excl = $p['total_shipping_price_tax_excl'];
                        $seller_order_detail->total_shipping_price_tax_incl = $p['total_shipping_price_tax_incl'];
                        $seller_order_detail->unit_commission_tax_excl = $p['unit_commission_tax_excl'];
                        $seller_order_detail->unit_commission_tax_incl = $p['unit_commission_tax_incl'];
                        $seller_order_detail->total_commission_tax_excl = $p['total_commission_tax_excl'];
                        $seller_order_detail->total_commission_tax_incl = $p['total_commission_tax_incl'];
                        $seller_order_detail->add();

                        $seller_order->total_paid = $seller_order->total_paid + $p['total_commission_tax_incl'];
                        $seller_order->total_paid_tax_incl = $seller_order->total_paid_tax_incl + $p['total_commission_tax_incl'];
                        $seller_order->total_paid_tax_excl = $seller_order->total_paid_tax_excl + $p['total_commission_tax_excl'];

                        if (Configuration::get('JMARKETPLACE_SHIPPING_COMMISSION') == 1 && Configuration::get('JMARKETPLACE_SHOW_MANAGE_CARRIER') == 0 && $p['total_shipping_price_tax_incl'] > 0) {
                            $seller_order->total_paid = $seller_order->total_paid + ($p['total_shipping_price_tax_incl'] * $p['product_quantity']);
                            $seller_order->total_paid_tax_incl = $seller_order->total_paid_tax_incl + ($p['total_shipping_price_tax_incl'] * $p['product_quantity']);
                            $seller_order->total_paid_tax_excl = $seller_order->total_paid_tax_excl + ($p['total_shipping_price_tax_excl'] * $p['product_quantity']);

                            $seller_order->total_shipping = $seller_order->total_shipping + ($p['total_shipping_price_tax_incl'] * $p['product_quantity']);
                            $seller_order->total_shipping_tax_incl = $seller_order->total_shipping_tax_incl + ($p['total_shipping_price_tax_incl'] * $p['product_quantity']);
                            $seller_order->total_shipping_tax_excl = $seller_order->total_shipping_tax_excl + ($p['total_shipping_price_tax_excl'] * $p['product_quantity']);
                        }

                        $seller_order->total_products = $seller_order->total_products + $p['total_commission_tax_incl'];
                        $seller_order->total_products_tax_incl = $seller_order->total_products_tax_incl + $p['total_commission_tax_incl'];
                        $seller_order->total_products_tax_excl = $seller_order->total_products_tax_excl + $p['total_commission_tax_excl'];
                    }

                    $seller_order->total_discounts = $order->total_discounts;
                    $seller_order->total_discounts_tax_incl = $order->total_discounts_tax_incl;
                    $seller_order->total_discounts_tax_excl = $order->total_discounts_tax_excl;

                    $seller_order->total_paid = $seller_order->total_paid - $seller_order->total_discounts;
                    $seller_order->total_paid_tax_incl = $seller_order->total_paid_tax_incl - $seller_order->total_discounts_tax_incl;
                    $seller_order->total_paid_tax_excl = $seller_order->total_paid_tax_excl - $seller_order->total_discounts_tax_excl;

                    if (Configuration::get('JMARKETPLACE_SHIPPING_COMMISSION') == 1 && Configuration::get('JMARKETPLACE_SHOW_MANAGE_CARRIER') == 1) {
                        $seller_order->total_shipping = $order->total_shipping;
                        $seller_order->total_shipping_tax_incl = $order->total_shipping_tax_incl;
                        $seller_order->total_shipping_tax_excl = $order->total_shipping_tax_excl;

                        $seller_order->total_paid = $seller_order->total_paid + $seller_order->total_shipping;
                        $seller_order->total_paid_tax_incl = $seller_order->total_paid_tax_incl + $seller_order->total_shipping_tax_incl;
                        $seller_order->total_paid_tax_excl = $seller_order->total_paid_tax_excl + $seller_order->total_shipping_tax_excl;
                    }

                    $seller_order->total_fixed_commission = Configuration::get('JMARKETPLACE_FIXED_COMMISSION');
                    $seller_order->total_fixed_commission_tax_incl = Configuration::get('JMARKETPLACE_FIXED_COMMISSION');
                    $seller_order->total_fixed_commission_tax_excl = Configuration::get('JMARKETPLACE_FIXED_COMMISSION');

                    $seller_order->total_paid = $seller_order->total_paid - $seller_order->total_fixed_commission;
                    $seller_order->total_paid_tax_incl = $seller_order->total_paid_tax_incl - $seller_order->total_fixed_commission_tax_incl;
                    $seller_order->total_paid_tax_excl = $seller_order->total_paid_tax_excl - $seller_order->total_fixed_commission_tax_excl;

                    $seller_order->total_wrapping = 0;
                    $seller_order->total_wrapping_tax_incl = 0;
                    $seller_order->total_wrapping_tax_excl = 0;

                    $seller_order->update();
                    
                    $params = array('id_seller' => $seller_order->id_seller, 'id_seller_order' => $seller_order->id);
                    Hook::exec('actionMarketplaceAfterAddSellerOrder', $params);

                    $seller_order_history = new SellerOrderHistory();
                    $seller_order_history->id_seller_order = $seller_order->id;
                    $seller_order_history->id_seller = $seller_order->id_seller;
                    $seller_order_history->id_order_state = $seller_order->current_state;
                    $seller_order_history->add();
                }
            }
        }
    }
    
    public function notifyNewOrderToSeller($order, $seller, $id_product)
    {
        if (Configuration::get('JMARKETPLACE_SEND_PRODUCT_SOLD') == 1) {
            $product = new Product($id_product, false, $seller->id_lang, $this->context->shop->id);
            $id_seller_email = false;
            $to = $seller->email;
            $to_name = $seller->name;
            $from = Configuration::get('PS_SHOP_EMAIL');
            $from_name = Configuration::get('PS_SHOP_NAME');
            $template = 'base';
            $reference = 'new-order';
            $id_seller_email = SellerEmail::getIdByReference($reference);

            if ($id_seller_email) {
                $seller_email = new SellerEmail($id_seller_email, $seller->id_lang);
                $vars = array("{shop_name}", "{seller_name}", "{product_name}", "{order_reference}");
                $values = array(Configuration::get('PS_SHOP_NAME'), $seller->name, $product->name, $order->reference);
                $subject_var = $seller_email->subject;
                $subject_value = str_replace($vars, $values, $subject_var);
                $content_var = $seller_email->content;
                $content_value = str_replace($vars, $values, $content_var);

                $template_vars = array(
                    '{content}' => $content_value,
                    '{shop_name}' => Configuration::get('PS_SHOP_NAME')
                );

                $iso = Language::getIsoById($seller->id_lang);

                if (file_exists(dirname(__FILE__).'/mails/'.$iso.'/'.$template.'.txt') && file_exists(dirname(__FILE__).'/mails/'.$iso.'/'.$template.'.html')) {
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
                        dirname(__FILE__).'/mails/'
                    );
                }
            }
        }
    }
    
    public function createCommissionForShipping($order, $id_seller)
    {
        if (Configuration::get('JMARKETPLACE_SHIPPING_COMMISSION') == 1 && Configuration::get('JMARKETPLACE_SHOW_MANAGE_CARRIER') == 1 && $order->total_shipping > 0) {
            $sch = new SellerCommissionHistory();
            $sch->id_order = $order->id;
            $sch->id_product = 0;
            $sch->product_name = $this->l('Shipping cost for').' '.$order->reference;
            $sch->id_seller = $id_seller;
            $sch->id_shop = $this->context->shop->id;
            $sch->id_currency = $order->id_currency;
            $sch->conversion_rate = $order->conversion_rate;
            $sch->price_tax_excl = $order->total_shipping_tax_excl;
            $sch->price_tax_incl = $order->total_shipping_tax_incl;
            $sch->quantity = 1;
            $sch->unit_commission_tax_excl = $order->total_shipping_tax_excl;
            $sch->unit_commission_tax_incl = $order->total_shipping_tax_incl;
            $sch->total_commission_tax_excl = $order->total_shipping_tax_excl;
            $sch->total_commission_tax_incl = $order->total_shipping_tax_incl;
            $sch->id_seller_commission_history_state = SellerCommissionHistoryState::getIdByReference('pending');
            $sch->add();
        } elseif (Configuration::get('JMARKETPLACE_SHIPPING_COMMISSION') == 1 && Configuration::get('JMARKETPLACE_SHOW_MANAGE_CARRIER') == 0 && $order->total_shipping > 0) {
            $products = $order->getProducts();
            $seller_order_products = array();
            foreach ($products as $p) {
                $id_seller = Seller::getSellerByProduct($p['product_id']);
                if ($id_seller) {
                    $seller = new Seller($id_seller);
                    if ($seller->active == 1) {
                        $seller_order_products[$id_seller][] = array(
                            'product_id' => $p['product_id'],
                            'product_quantity' => $p['product_quantity'],
                            'total_shipping_price_tax_excl' => $p['total_shipping_price_tax_excl'],
                            'total_shipping_price_tax_incl' => $p['total_shipping_price_tax_incl'],
                        );
                    }
                }
            }

            if (is_array($seller_order_products) && count($seller_order_products) > 0) {
                foreach ($seller_order_products as $key => $products) {
                    $total_shipping_tax_excl = 0;
                    $total_shipping_tax_incl = 0;
                    foreach ($products as $p) {
                        $total_shipping_tax_excl = $total_shipping_tax_excl + ($p['total_shipping_price_tax_excl'] * $p['product_quantity']);
                        $total_shipping_tax_incl = $total_shipping_tax_incl + ($p['total_shipping_price_tax_incl'] * $p['product_quantity']);
                    }
                    
                    $sch = new SellerCommissionHistory();
                    $sch->id_order = $order->id;
                    $sch->id_product = 0;
                    $sch->product_name = $this->l('Shipping cost for').' '.$order->reference;
                    $sch->id_seller = $key;
                    $sch->id_shop = $this->context->shop->id;
                    $sch->id_currency = $order->id_currency;
                    $sch->conversion_rate = $order->conversion_rate;
                    $sch->price_tax_excl = $total_shipping_tax_excl;
                    $sch->price_tax_incl = $total_shipping_tax_incl;
                    $sch->quantity = 1;
                    $sch->unit_commission_tax_excl = $total_shipping_tax_excl;
                    $sch->unit_commission_tax_incl = $total_shipping_tax_incl;
                    $sch->total_commission_tax_excl = $total_shipping_tax_excl;
                    $sch->total_commission_tax_incl = $total_shipping_tax_incl;
                    $sch->id_seller_commission_history_state = SellerCommissionHistoryState::getIdByReference('pending');
                    $sch->add();
                }
            }
        }
    }
    
    public function createFixedCommission($order, $id_seller)
    {
        if (Configuration::get('JMARKETPLACE_FIXED_COMMISSION') > 0) {
            if (!SellerCommissionHistory::getFixedCommissionOfSellerInOrder($id_seller, $order->id)) {
                $sch = new SellerCommissionHistory();
                $sch->id_order = $order->id;
                $sch->id_product = 0;
                $sch->product_name = $this->l('Fixed commission for sale').' '.$order->reference;
                $sch->id_seller = $id_seller;
                $sch->id_shop = $this->context->shop->id;
                $sch->id_currency = $order->id_currency;
                $sch->conversion_rate = $order->conversion_rate;
                $sch->price_tax_excl = -Configuration::get('JMARKETPLACE_FIXED_COMMISSION');
                $sch->price_tax_incl = -Configuration::get('JMARKETPLACE_FIXED_COMMISSION');
                $sch->quantity = 1;
                $sch->unit_commission_tax_excl = -Configuration::get('JMARKETPLACE_FIXED_COMMISSION');
                $sch->unit_commission_tax_incl = -Configuration::get('JMARKETPLACE_FIXED_COMMISSION');
                $sch->total_commission_tax_incl = -Configuration::get('JMARKETPLACE_FIXED_COMMISSION');
                $sch->total_commission_tax_excl = -Configuration::get('JMARKETPLACE_FIXED_COMMISSION');
                $sch->id_seller_commission_history_state = SellerCommissionHistoryState::getIdByReference('pending');
                $sch->add();
            }
        }
    }
    
    public function createCommissionForDiscounts($order, $id_seller)
    {
        if ($order->total_discounts > 0) {
            $sch = new SellerCommissionHistory();
            $sch->id_order = $order->id;
            $sch->id_product = 0;
            $sch->product_name = $this->l('Total discounts').' '.$order->reference;
            $sch->id_seller = $id_seller;
            $sch->id_shop = $this->context->shop->id;
            $sch->id_currency = $order->id_currency;
            $sch->conversion_rate = $order->conversion_rate;
            $sch->price_tax_excl = -$order->total_discounts_tax_excl;
            $sch->price_tax_incl = -$order->total_discounts_tax_incl;
            $sch->quantity = 1;
            $sch->unit_commission_tax_excl = -$order->total_discounts_tax_excl;
            $sch->unit_commission_tax_incl = -$order->total_discounts_tax_incl;
            $sch->total_commission_tax_excl = -$order->total_discounts_tax_excl;
            $sch->total_commission_tax_incl = -$order->total_discounts_tax_incl;
            $sch->id_seller_commission_history_state = SellerCommissionHistoryState::getIdByReference('pending');
            $sch->add();
        }
    }
    
    public function hookActionValidateOrder($params)
    {
        if (Configuration::get('JMARKETPLACE_COMMISIONS_ORDER') == 1) {
            $this->sendCommission($params);
        }
    }
    
    public function hookActionOrderStatusPostUpdate($params)
    {
        $states = OrderState::getOrderStates($this->context->language->id);
        
        $create_commissions = false;
        foreach ($states as $state) {
            if (Configuration::get('JMARKETPLACE_ORDER_STATE_'.$state['id_order_state']) == 1 && $params['newOrderStatus']->id == $state['id_order_state']) {
                $create_commissions = true;
            }
        }
        
        if ($create_commissions) {
            $this->sendCommission($params);
        } else {
            //update history commissions
            $cancel_commissions = false;
            foreach ($states as $state) {
                if (Configuration::get('JMARKETPLACE_CANCEL_COMMISSION_'.$state['id_order_state']) == 1 && $params['newOrderStatus']->id == $state['id_order_state']) {
                    $cancel_commissions = true;
                }
            }

            //si toca cancelar comisiones
            if ($cancel_commissions) {
                SellerCommissionHistory::changeStateCommissionsByOrder($params['id_order'], 'cancel');
                $id_seller_orders = SellerOrder::getIdSellerOrdersByOrder($params['id_order']);
                
                if (is_array($id_seller_orders) && count($id_seller_orders) > 0) {
                    foreach ($id_seller_orders as $so) {
                        $seller_order = new SellerOrder($so['id_seller_order']);
                        
                        //Create new SellerOrderHistory
                        $seller_order_history = new SellerOrderHistory();
                        $seller_order_history->id_seller_order = $seller_order->id;
                        $seller_order_history->id_seller = $seller_order->id_seller;
                        $seller_order_history->id_order_state = $params['newOrderStatus']->id;
                        $seller_order_history->add();

                        $seller_order->current_state = $params['newOrderStatus']->id;
                        $seller_order->update();
                    }
                }
            }
        }
    }
    
    public function hookActionProductDelete($params)
    {
        if (Tools::getValue('deleteproduct')) {
            $id_product = (int)Tools::getValue('id_product');
        } else {
            $id_product = (int)$params['id_product'];
        }
            
        Db::getInstance()->Execute(
            'DELETE FROM `'._DB_PREFIX_.'seller_product` 
            WHERE id_product = '.$id_product
        );
    }
    
    public function hookDisplayHeader($params)
    {
        $this->context->controller->addCSS($this->_path.'views/css/font-awesome.css', 'all');
        $this->context->controller->addCSS($this->_path.'views/css/jmarketplace.css', 'all');
        
        if (Configuration::get('JMARKETPLACE_THEME') == 'default') {
            $this->context->controller->addCSS($this->_path.'views/css/default.css', 'all');
        }
        
        $this->context->controller->addJS($this->_path.'views/js/front.js', 'all');
        
        if (Configuration::get('JMARKETPLACE_SHOW_MANAGE_CARRIER') == 1) {
            $this->context->controller->addJS($this->_path.'views/js/addsellerproductcart.js', 'all');
        }
    }

    public function hookBackOfficeHeader()
    {
        $this->context->controller->addCSS($this->_path.'views/css/back.css');
    }
    
    public function hookDisplayOrderDetail($params)
    {
        if (Configuration::get('JMARKETPLACE_SHOW_ORDER_DETAIL') == 1) {
            $order = new Order($params['order']->id);
            $products = $order->getProducts();
            foreach ($products as $key => $product) {
                $id_product = $product['product_id'];
                $id_seller = Seller::getSellerByProduct($id_product);
                $products[$key]['id_seller'] = $id_seller;

                if ($id_seller) {
                    $seller = new Seller($id_seller);
                    $products[$key]['seller_name'] = $seller->name;

                    $params = array('id_seller' => $id_seller, 'id_product' => $id_product);
                    $params_contact_seller = array('id_seller' => $id_seller);
                    $params_seller_profile = array('id_seller' => $seller->id, 'link_rewrite' => $seller->link_rewrite);

                    $products[$key]['seller_link'] = Jmarketplace::getJmarketplaceLink('jmarketplace_seller_rule', $params_seller_profile);
                    $products[$key]['url_seller_comments'] = Jmarketplace::getJmarketplaceLink('jmarketplace_sellercomments_rule', $params_seller_profile);
                    $products[$key]['url_contact_seller'] = $this->context->link->getModuleLink('jmarketplace', 'contactseller', $params_contact_seller, true);
                    $products[$key]['url_favorite_seller'] = $this->context->link->getModuleLink('jmarketplace', 'favoriteseller', $params, true);
                    $products[$key]['url_seller_products'] = Jmarketplace::getJmarketplaceLink('jmarketplace_sellerproductlist_rule', $params_seller_profile);

                    $products[$key]['product_price'] = Tools::displayPrice($product['product_price'], (int)$order->id_currency);
                    $products[$key]['total_price'] = Tools::displayPrice($product['total_price'], (int)$order->id_currency);

                    if (Configuration::get('JMARKETPLACE_SELLER_RATING')) {
                        $average = SellerComment::getRatings($id_seller);
                        $averageTotal = SellerComment::getCommentNumber($id_seller);

                        $products[$key]['seller_averageTotal'] = $averageTotal;
                        $products[$key]['seller_averageMiddle'] = ceil($average['avg']);
                    }
                } else {
                    $products[$key]['shop_name'] = Configuration::get('PS_SHOP_NAME');
                    $products[$key]['product_price'] = Tools::displayPrice($product['product_price'], (int)$order->id_currency);
                    $products[$key]['total_price'] = Tools::displayPrice($product['total_price'], (int)$order->id_currency);
                }
            }

            $this->context->smarty->assign(array(
                'show_contact_seller' => Configuration::get('JMARKETPLACE_SHOW_CONTACT'),
                'show_seller_profile' => Configuration::get('JMARKETPLACE_SHOW_PROFILE'),
                'show_seller_favorite' => Configuration::get('JMARKETPLACE_SELLER_FAVORITE'),
                'show_seller_rating' => Configuration::get('JMARKETPLACE_SELLER_RATING'),
                'products' => $products,
            ));

            return $this->display(__FILE__, 'order-detail.tpl');
        }
    }
    
    public function hookadminOrder($params)
    {
        $is_order_seller = false;
        $id_order = $params['id_order'];
        $order = new Order($id_order);
        $products = $order->getProducts();
        
        foreach ($products as $p) {
            if (SellerProduct::existAssociationSellerProduct($p['id_product'])) {
                $is_order_seller = true;
            }
        }
        
        $commissions = SellerCommissionHistory::getCommissionHistoryByOrder($id_order, $this->context->language->id, $this->context->shop->id);
        $total_commissions = SellerCommissionHistory::getTotalCommissionByOrder($id_order);

        echo '<div class="panel col-lg-12">';
        
        if (is_array($commissions)) {
            echo '<div class="panel-heading"><i class="icon-user"></i> '.$this->l('Information on the commissions to pay the seller').'</div>';
            echo '<div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th><span class="title_box">'.$this->l('Concept').'</span></th>
                                <th><span class="title_box">'.$this->l('Seller').'</span></th>
                                <th class="text-right"><span class="title_box">'.$this->l('Quantity').'</span></th>
                                <th class="text-right"><span class="title_box">'.$this->l('Price').'</span></th>
                                <th class="text-right"><span class="title_box">'.$this->l('Commission').'</span></th>
                                <th class="text-center"><span class="title_box">'.$this->l('Status').'</span></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>';
            foreach ($commissions as $c) {
                $seller = new Seller($c['id_seller']);
                
                if (Configuration::get('JMARKETPLACE_TAX_COMMISSION') == 1) {
                    $price = $c['price_tax_incl'];
                    $commission = $c['total_commission_tax_incl'];
                } else {
                    $price = $c['price_tax_excl'];
                    $commission = $c['total_commission_tax_excl'];
                }
                
                echo ' <tr>
                            <td>'.$c['product_name'].'</td>
                            <td>'.$seller->name.'</td>
                            <td class="text-right">'.$c['quantity'].'</td> 
                            <td class="text-right">'.Tools::displayPrice($price, (int)$c['id_currency']).'</td>   
                            <td class="text-right">'.Tools::displayPrice($commission, (int)$c['id_currency']).'</td>
                            <td class="text-center">'.$c['state_name'].'</td>
                            <td class="actions">
                                <a href="index.php?tab=AdminSellerCommissionsHistory&id_seller_commission_history='.(int)$c['id_seller_commission_history'].'&updateseller_commission_history&token='.Tools::getAdminToken('AdminSellerCommissionsHistory'.(int)Tab::getIdFromClassName('AdminSellerCommissionsHistory').(int)$this->context->employee->id).'" class="btn btn-default">
                                    <i class="icon-search"></i>
                                    Details
                                </a>
                            </td>
                        </tr>';
            }

            echo '</tbody>';
            
            echo '<tfoot>
                        <tr>
                            <th colspan="4"><span class="title_box">'.$this->l('Total').'</span></th>
                            <th class="text-right">'.Tools::displayPrice($total_commissions, (int)$order->id_currency).'</th>
                        </tr>
                    </tfoot>
                </table>
            </div>';
        } elseif ($is_order_seller) {
            echo '<div class="panel-heading"><i class="icon-user"></i> '.$this->l('Seller order information').'</div>';
            echo '<div class="table-responsive">
                   <table class="table">
                       <thead>
                           <tr>
                               <th><span class="title_box">'.$this->l('Concept').'</span></th>
                               <th><span class="title_box">'.$this->l('Seller').'</span></th>
                               <th class="text-right"><span class="title_box ">'.$this->l('Quantity').'</span></th>
                               <th class="text-right"><span class="title_box ">'.$this->l('Price').'</span></th> 
                               <th class="text-right"><span class="title_box">'.$this->l('Commission').'</span></th>
                           </tr>
                       </thead>
                       <tbody>';
            foreach ($products as $p) {
                $id_seller_product = SellerProduct::existAssociationSellerProduct($p['id_product']);
                $owner = Configuration::get('PS_SHOP_NAME');
                
                if (Configuration::get('JMARKETPLACE_TAX_COMMISSION') == 1) {
                    $unit_price = $p['unit_price_tax_incl'];
                } else {
                    $unit_price = $p['unit_price_tax_excl'];
                }
                
                $commission = $unit_price;
                if ($id_seller_product) {
                    $seller = new Seller($id_seller_product);
                    $owner = $seller->name;
                    
                    $commission = (((SellerCommission::getCommissionBySeller($seller->id) * $unit_price) / 100) * $p['product_quantity']) - Configuration::get('JMARKETPLACE_FIXED_COMMISSION');

                    $params = array('id_seller' => $id_seller_product, 'id_product' => $p['product_id']);
                    $commission_hooked = Hook::exec('actionMarketplaceBeforeAddSellerCommission', $params);
                    if (isset($commission_hooked) && $commission_hooked != false) {
                        $commission = ((($commission_hooked * $unit_price) / 100) * $p['product_quantity']);
                    }
                }

                echo ' <tr>
                            <td>'.$p['product_name'].'</td>
                            <td>'.$owner.'</td>
                            <td class="text-right">'.$p['product_quantity'].'</td> 
                            <td class="text-right">'.Tools::displayPrice($unit_price, (int)$order->id_currency).'</td>  
                            <td class="text-right">'.Tools::displayPrice($commission, (int)$order->id_currency).'</td>
                        </tr>';
            }
            
            echo '</tbody></table></div>';
        } else {
            echo '<div class="panel-heading"><i class="icon-user"></i> '.$this->l('Seller order information').'</div>';
            echo '<div class="alert alert-info">'.$this->l('This order is by no seller').'</div>';
        }
        
        echo '</div>';
        
        /* seller orders */
        $seller_orders = SellerOrder::getSellerOrdersByOrder($id_order, $this->context->language->id);
        if (is_array($seller_orders) && count($seller_orders) > 0) {
            echo '<div class="panel col-lg-12">';
            echo '<div class="panel-heading"><i class="icon-credit-cart"></i> '.$this->l('Sellers Orders').'</div>';
            echo '<div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th><span class="title_box">'.$this->l('ID').'</span></th>
                                <th><span class="title_box">'.$this->l('Reference').'</span></th>
                                <th><span class="title_box">'.$this->l('Seller').'</span></th>
                                <th class="text-right"><span class="title_box">'.$this->l('Total').'</span></th>
                                <th class="text-center"><span class="title_box">'.$this->l('Status').'</span></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>';
            foreach ($seller_orders as $so) {
                if (Configuration::get('JMARKETPLACE_TAX_COMMISSION') == 1) {
                    $total = $so['total_paid_tax_incl'];
                } else {
                    $total = $so['total_paid_tax_excl'];
                }
                
                echo ' <tr>
                            <td>'.$so['id_seller_order'].'</td>
                            <td>'.$so['reference'].'</td>
                            <td>'.$so['seller_name'].'</td>
                            <td class="text-right">'.Tools::displayPrice($total, (int)$so['id_currency']).'</td> 
                            <td class="text-center">'.$so['osname'].'</td>
                            <td class="actions">
                                <a href="index.php?tab=AdminSellerOrders&id_seller_order='.(int)$so['id_seller_order'].'&viewseller_order&token='.Tools::getAdminToken('AdminSellerOrders'.(int)Tab::getIdFromClassName('AdminSellerOrders').(int)$this->context->employee->id).'" class="btn btn-default">
                                    <i class="icon-search"></i>
                                    Details
                                </a>
                            </td>
                        </tr>';
            }

            echo '</tbody></table></div>';
            echo '</div>';
        }
    }
    
    public function hookModuleRoutes($params)
    {
        $routes = array(
            'jmarketplace_seller_rule' => array(
                'controller' => 'sellerprofile',
                'rule' => Configuration::get('JMARKETPLACE_MAIN_ROUTE', $this->context->language->id).'/{id_seller}_{link_rewrite}/',
                'keywords' => array(
                    'id_seller' =>  array('regexp' => '[_a-zA-Z0-9-\pL]*', 'param' => 'id_seller'),
                    'link_rewrite' => array('regexp' => '[_a-zA-Z0-9-\pL]*'),
                ),
                'params' => array(
                    'fc' => 'module',
                    'module' => 'jmarketplace',
                ),
            ),
            'jmarketplace_sellerproductlist_rule' => array(
                'controller' => 'sellerproductlist',
                'rule' => Configuration::get('JMARKETPLACE_MAIN_ROUTE', $this->context->language->id).'/{id_seller}_{link_rewrite}/'.Configuration::get('JMARKETPLACE_ROUTE_PRODUCTS', $this->context->language->id),
                'keywords' => array(
                    'id_seller' =>  array('regexp' => '[_a-zA-Z0-9-\pL]*', 'param' => 'id_seller'),
                    'link_rewrite' => array('regexp' => '[_a-zA-Z0-9-\pL]*'),
                ),
                'params' => array(
                    'fc' => 'module',
                    'module' => 'jmarketplace',
                ),
            ),
            'jmarketplace_sellercomments_rule' => array(
                'controller' => 'sellercomments',
                'rule' => Configuration::get('JMARKETPLACE_MAIN_ROUTE', $this->context->language->id).'/{id_seller}_{link_rewrite}/'.Configuration::get('JMARKETPLACE_ROUTE_COMMENTS', $this->context->language->id),
                'keywords' => array(
                    'id_seller' =>  array('regexp' => '[_a-zA-Z0-9-\pL]*', 'param' => 'id_seller'),
                    'link_rewrite' => array('regexp' => '[_a-zA-Z0-9-\pL]*'),
                ),
                'params' => array(
                    'fc' => 'module',
                    'module' => 'jmarketplace',
                ),
            ),
        );

        return $routes;
    }
    
    public static function getJmarketPlaceUrl()
    {
        $ssl_enable = Configuration::get('PS_SSL_ENABLED');
        $id_lang = (int)Context::getContext()->language->id;
        $id_shop = (int)Context::getContext()->shop->id;
        $rewrite_set = (int)Configuration::get('PS_REWRITING_SETTINGS');
        $ssl = null;
        static $force_ssl = null;
        
        if ($ssl === null) {
            if ($force_ssl === null) {
                $force_ssl = (Configuration::get('PS_SSL_ENABLED') && Configuration::get('PS_SSL_ENABLED_EVERYWHERE'));
            }
            $ssl = $force_ssl;
        }
        
        if (Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE') && $id_shop !== null) {
            $shop = new Shop($id_shop);
        } else {
            $shop = Context::getContext()->shop;
        }

        $base = (($ssl && $ssl_enable) ? 'https://'.$shop->domain_ssl : 'http://'.$shop->domain);
        $langUrl = Language::getIsoById($id_lang).'/';
        
        if ((!$rewrite_set && in_array($id_shop, array((int)Context::getContext()->shop->id,  null))) ||
                !Language::isMultiLanguageActivated($id_shop) ||
                !(int)Configuration::get('PS_REWRITING_SETTINGS', null, null, $id_shop)) {
            $langUrl = '';
        }

        return $base.$shop->getBaseURI().$langUrl;
    }
    
    public static function getJmarketplaceLink($rewrite = 'jmarketplace', $params = null, $id_lang = null)
    {
        $url = Jmarketplace::getJmarketPlaceUrl();
        $dispatcher = Dispatcher::getInstance();
        
        if ($params != null) {
            return $url.$dispatcher->createUrl($rewrite, $id_lang, $params);
        } else {
            return $url.$dispatcher->createUrl($rewrite);
        }
    }
    
    public function hookActionDeleteGDPRCustomer($customer)
    {
        if (is_array($customer)) {
            $id_seller = Seller::getSellerByCustomer($customer['id']);
            if ($id_seller != false) {
                $seller = new Seller($id_seller);
                $seller->delete();
                return json_encode(true);
            }
            return json_encode($this->l('Jmarketplace : Unable to delete seller data.'));
        }
    }
   
    public function hookActionExportGDPRData($customer)
    {
        if (is_array($customer)) {
            $seller_data = Seller::getDataSellerByCustomer($customer['id']);
            if ($seller_data != false) {
                return json_encode($seller_data);
            }
            return json_encode($this->l('Jmarketplace : Unable to export seller data.'));
        }
    }
}
