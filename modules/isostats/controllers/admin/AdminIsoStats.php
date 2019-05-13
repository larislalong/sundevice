<?php
@ini_set('display_errors', 'on');
@error_reporting(E_ALL | E_STRICT);

class AdminIsoStatsController extends AdminController {

	public $module;

	public function __construct() {
		$this->table = 'order_detail';
		$this->className = 'IsoStats';
		$this->module = 'isostats';
		$this->lang = false;
		$this->bootstrap = true;
		$this->need_instance = 0;
		$this->context = Context::getContext();
        parent::__construct();
	}

    public function postProcess() {
        parent::postProcess();
    }
	public function initToolbar() {
		parent::initToolbar();
		// unset( $this->toolbar_btn['new'] );
	}

	public function renderList() {
		$this->context->smarty->assign('salesDetailsLink', $this->context->link->getAdminLink('AdminIsoStatsSalesDetails'));
		$this->context->smarty->assign('allStats', $this->getAllTotal());
		$this->context->smarty->assign('orderStats', $this->getOrdersTotalStats());
		
        $output = $this->context->smarty->fetch(dirname(__FILE__).'/../../views/templates/admin/isoStatsGeneral.tpl');
		
		// return parent::renderList();
		return $output;
	}

    public function getAllTotal() {
		$sql = 'SELECT d.*
			FROM sundev_order_detail d
			WHERE d.id_shop = '.(int)$this->context->shop->id.'
			ORDER BY d.id_order DESC';
		$ordersDetails = Db::getInstance()->executeS($sql);
		
		$totalStats = array(
			'price' => array(
				'all' => 0,
				'box' => 0,
				'iphone' => 0,
				'ipad' => 0,
				'samsung' => 0,
				'iwatch' => 0,
			),
			'count' => array(
				'all' => 0,
				'box' => 0,
				'iphone' => 0,
				'ipad' => 0,
				'samsung' => 0,
				'iwatch' => 0,
			),
		);
		
		$ipadCat 	= 15;
		$iphoneCat 	= 14;
		$iwatchCat 	= 16;
		$boxCat 	= 6;
		$samsungCat = 17;
		
		if(isset($ordersDetails) and !empty($ordersDetails)){
			foreach($ordersDetails as $detail){
				$currentProduct = new Product($detail['product_id']);
				$totalStats['price']['all'] += $detail['total_price_tax_incl'];
				$totalStats['count']['all'] += $detail['product_quantity'];
				switch($currentProduct->id_category_default){
					case $boxCat:{
						$totalStats['price']['box'] += $detail['total_price_tax_incl'];
						$totalStats['count']['box'] += $detail['product_quantity'];
						break;
					}
					case $iphoneCat:{
						$totalStats['price']['iphone'] += $detail['total_price_tax_incl'];
						$totalStats['count']['iphone'] += $detail['product_quantity'];
						break;
					}
					case $ipadCat:{
						$totalStats['price']['ipad'] += $detail['total_price_tax_incl'];
						$totalStats['count']['ipad'] += $detail['product_quantity'];
						break;
					}
					case $iwatchCat:{
						$totalStats['price']['iwatch'] += $detail['total_price_tax_incl'];
						$totalStats['count']['iwatch'] += $detail['product_quantity'];
						break;
					}
					case $samsungCat:{
						$totalStats['price']['samsung'] += $detail['total_price_tax_incl'];
						$totalStats['count']['samsung'] += $detail['product_quantity'];
						break;
					}
				}
			}
		}
        return $totalStats;
    }

    public function getOrdersTotalStats() {
		$sql = 'SELECT o.*
			FROM sundev_orders o
			WHERE o.id_shop = '.(int)$this->context->shop->id.' ';
		$ordersDetails = Db::getInstance()->executeS($sql);
		
		$totalStats = array(
			'status' => array(),
		);
		
		if(isset($ordersDetails) and !empty($ordersDetails)){
			foreach($ordersDetails as $detail){
				if(!isset($totalStats['status'][$detail['current_state']])){
					$currentStatus = new OrderState($detail['current_state']);
		// var_dump($currentStatus);die;
					$totalStats['status'][$detail['current_state']]['name'] = isset($currentStatus->name[1])?$currentStatus->name[1]:'Inconnu';
					$totalStats['status'][$detail['current_state']]['color'] = isset($currentStatus->color)?$currentStatus->color:'Inconnu';
					$totalStats['status'][$detail['current_state']]['total'] = 0;
					$totalStats['status'][$detail['current_state']]['shipping'] = 0;
				}
				$totalStats['status'][$detail['current_state']]['total'] += $detail['total_products'];
				$totalStats['status'][$detail['current_state']]['shipping'] += $detail['total_shipping'];
			}
		}
        return $totalStats;
    }

    public static function setOrderCurrency($echo)
    {
		// var_dump($this->context->currency->id);die;
        return Tools::displayPrice($echo, (int)$this->context->currency->id);
    }
}