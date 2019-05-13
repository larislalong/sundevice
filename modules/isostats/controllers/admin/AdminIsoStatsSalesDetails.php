<?php
@ini_set('display_errors', 'on');
@error_reporting(E_ALL | E_STRICT);

class AdminIsoStatsSalesDetailsController extends AdminController {

	public $module;

	public function __construct() {
		$this->table = 'order_detail';
		$this->className = 'IsoStats';
		$this->module = 'isostats';
		$this->lang = false;
		$this->bootstrap = true;
		$this->need_instance = 0;	

		$this->fields_list = array(
			'id_order_detail' => array(
				'title' => $this->l('ID'),
				'align' => 'center',
				'width' => 25,
				'type' => 'text'
			),
			'order_ref' => array(
				'title' => $this->l('Commande'),
				'width' => 'auto',
				'type' => 'text',
				'orderby' => true,
				'search' => true,
                'havingFilter' => true,
			),
			'client' => array(
				'title' => $this->l('Client'),
				'width' => 'auto',
				'type' => 'text',
				'orderby' => true,
				'search' => true,
                'havingFilter' => true,
			),
			'product_name' => array(
				'title' => $this->l('Produit'),
				'width' => 'auto',
				'type' => 'text',
				'orderby' => true,
				'search' => true
			),
			'product_price' => array(
				'title' => $this->l('Prix U. Vendu'),
                'align' => 'text-right',
                'type' => 'price',
                'currency' => true,
                'callback' => 'setOrderCurrency',
                'badge_success' => true
			),
			'original_product_price' => array(
				'title' => $this->l('Prix U. Réel'),
                'align' => 'text-right',
                'type' => 'price',
                'currency' => true,
                'callback' => 'setOrderCurrency',
                'badge_success' => true
			),
			// 'discount_price' => array(
                // 'title' => $this->l('Discount'),
                // 'align' => 'text-right',
                // 'type' => 'price',
                // 'currency' => true,
                // 'callback' => 'setOrderCurrency',
                // 'badge_success' => true,
                // 'havingFilter' => true,
			// ),
			'product_quantity' => array(
				'title' => $this->l('Qte'),
                'align' => 'text-right',
				'type' => 'text',
				'orderby' => true,
				'search' => true
			),
			'total_price' => array(
                'title' => $this->l('Total'),
                'align' => 'text-right',
                'type' => 'price',
                'currency' => true,
                'callback' => 'setOrderCurrency',
                'badge_success' => true,
                'havingFilter' => true,
			),
		);
		
		$this->_join = 'LEFT JOIN '._DB_PREFIX_.'orders o ON (a.id_order = o.id_order)';

		$this->_defaultOrderBy = 'a.id_order_detail';
		$this->_defaultOrderWay = 'DESC';

		$this->context = Context::getContext();

        $this->shopLinkType = 'shop';
        // $this->shopShareDatas = Shop::SHARE_CUSTOMER;
		
		$this->fields_list['date_add'] = array(
			'title' => $this->l('Commandé le'),
			'width' => 'auto',
			'type' => 'date',
			'orderby' => true,
			'search' => true
		);

        parent::__construct();

		$this->_select = '
			(a.product_quantity*a.product_price) AS total_price,(a.original_product_price-a.product_price) AS discount_price,o.reference AS order_ref,o.date_add, (
				SELECT CONCAT(c.firstname,\' \',c.lastname)
				FROM '._DB_PREFIX_.'customer c
				WHERE o.id_customer = c.id_customer
			) AS client
		';
	}

    public function postProcess() {
        parent::postProcess();
    }
	public function initToolbar() {
		parent::initToolbar();
		// unset($this->toolbar_btn['new']);
	}

	public function renderList() {
		$isoFilters = Tools::getValue('isoStats_filter', array());
		$this->_where = '';
		if(!empty($isoFilters)){
			if(isset($isoFilters['date_deb']) and $isoFilters['date_deb'] != ''){
				$this->context->smarty->assign('date_deb', $isoFilters['date_deb']);
				$this->_where .= ' AND o.date_add >= "'.$isoFilters['date_deb'].'" ';
			}
			if(isset($isoFilters['date_fin']) and $isoFilters['date_fin'] != ''){
				$this->context->smarty->assign('date_fin', $isoFilters['date_fin']);
				$this->_where .= ' AND o.date_add <= "'.$isoFilters['date_fin'].'" ';
			}
		}
		
        $output = $this->context->smarty->fetch(dirname(__FILE__).'/../../views/templates/admin/isoStatsFilter.tpl');
		return $output.parent::renderList();
	}

    public static function setOrderCurrency($echo, $tr) {
        return Tools::displayPrice($echo, (int)$this->context->currency->id);
    }
}