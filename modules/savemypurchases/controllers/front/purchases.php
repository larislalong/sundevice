<?php
/*
* 2007-2016 PrestaShop
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
class SavemypurchasesPurchasesModuleFrontController extends ModuleFrontController
{
    private $purchasesLink;
    protected $context;
    public $ssl = true;

    /**
     * purchasesSavemypurchasesModuleFrontController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->context = Context::getContext();
        $this->customer = $this->context->customer->id;
        $this->purchasesLink = $this->context->link->getModulelink('savemypurchases','purchases', []);
    }

    /**
     * initialisation
     */
    public function init(){
        $this->bootstrap = true;
        $this->display_column_left = false;
        parent::init();
    }

    public function initContent(){
        parent::initContent();

        if(Tools::getValue('load_cart')){
            $this->loadCart(Tools::getValue('load_cart'));
        }

        $purchases = $this->getMyPurchases();

        if($purchases){
            $this->context->smarty->assign('purchases', $purchases);
        }

        $this->setTemplate('PurchaseList.tpl');
    }

    public function setMedia(){
        parent::setMedia();
    }

    private function getMyPurchases()
	{
        $id_customer = $this->customer;
		$id_lang = (int)$this->context->language->id;
        $saveCart = array();
        if(!empty($id_customer)){
            $sql = new DbQuery();
            $sql->select("oc.*, c.date_add, pl.id_product, pl.name, cp.quantity")
                ->from('os_cart', 'oc')
                ->innerJoin('cart', 'c', 'c.id_cart=oc.id_cart')
                ->innerJoin('cart_product', 'cp', 'c.id_cart=cp.id_cart')
                ->innerJoin('product_lang', 'pl', 'pl.id_product=cp.id_product AND pl.id_lang='.$id_lang)
                ->where('oc.id_customer='.$id_customer)
            ;

			if(($rows = Db::getInstance()->executeS($sql))){
				foreach($rows as $row){
				    if (!isset($saveCart[$row['id_cart']])){
                        $saveCart[$row['id_cart']]['id_cart'] = $row['id_cart'];
                        $saveCart[$row['id_cart']]['date'] = $row['date_add'];
                        $saveCart[$row['id_cart']]['total'] = Cart::getTotalCart($row['id_cart']);
                    }

					$saveCart[$row['id_cart']]['products'][$row['id_product']] = $row['quantity'].' X '.$row['name'];
				}
			}
        }
        return $saveCart;
    }

    private function loadCart($id_cart)
    {
        // On charge le panier
		$this->context->cart = new Cart($id_cart); 
		$this->context->cookie->id_cart = $this->context->cart->id; 
		CartRule::autoAddToCart($this->context);
		$this->context->cookie->write();
		
		//on charge la zone du panier
		
		if (Configuration::get('PS_ORDER_PROCESS_TYPE') == 1) {
			Tools::redirect('index.php?controller=order-opc');
		}
		Tools::redirect('index.php?controller=order');
				
        //Tools::redirect($this->purchasesLink);
    }


}
