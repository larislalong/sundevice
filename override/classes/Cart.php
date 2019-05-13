<?php
/*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
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
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

include_once _PS_MODULE_DIR_ . 'blockwrapgift/classes/BwgCartProduct.php';
class Cart extends CartCore
{
	public function getGiftWrappingPrice($with_taxes = true, $id_address = null)
    {
        $mod = Module::isInstalled('blockwrapgift') && Module::isEnabled('blockwrapgift');
        static $address = array();
        if($mod == true)
        {
			$products = $this->getProducts();
            $wrapping_fees = BwgCartProduct::getTotalPrice($this->id, $products);

			if ($wrapping_fees <= 0) {
				return $wrapping_fees;
			}

			if ($with_taxes) {
				if (Configuration::get('PS_ATCP_SHIPWRAP')) {
					// With PS_ATCP_SHIPWRAP, wrapping fee is by default tax included
					// so nothing to do here.
				} else {
					if (!isset($address[$this->id])) {
						if ($id_address === null) {
							$id_address = (int)$this->{Configuration::get('PS_TAX_ADDRESS_TYPE')};
						}
						try {
							$address[$this->id] = Address::initialize($id_address);
						} catch (Exception $e) {
							$address[$this->id] = new Address();
							$address[$this->id]->id_country = Configuration::get('PS_COUNTRY_DEFAULT');
						}
					}

					$tax_manager = TaxManagerFactory::getManager($address[$this->id], (int)Configuration::get('PS_GIFT_WRAPPING_TAX_RULES_GROUP'));
					$tax_calculator = $tax_manager->getTaxCalculator();
					$wrapping_fees = $tax_calculator->addTaxes($wrapping_fees);
				}
			} elseif (Configuration::get('PS_ATCP_SHIPWRAP')) {
				// With PS_ATCP_SHIPWRAP, wrapping fee is by default tax included, so we convert it
				// when asked for the pre tax price.
				$wrapping_fees = Tools::ps_round(
					$wrapping_fees / (1 + $this->getAverageProductsTaxRate()),
					_PS_PRICE_COMPUTE_PRECISION_
				);
			}

			return $wrapping_fees;
        }
        else
            return parent::getGiftWrappingPrice ($with_taxes, $id_address);
    }
	
	public function getOrderTotal($with_taxes = true, $type = Cart::BOTH, $products = null, $id_carrier = null, $use_cache = true)
	{
		$mod = Module::isInstalled('blockwrapgift') && Module::isEnabled('blockwrapgift');
		if($mod){
			$this->gift = 1;
		}
		return parent::getOrderTotal($with_taxes, $type, $products, $id_carrier, $use_cache);
	}
	
	public static function getIsolatedQuantities()
	{
		$result = array();
		$mod = Module::isInstalled('lonelystock') && Module::isEnabled('lonelystock');
		if($mod){
			$sql = 'SELECT SUM(ls.quantity) AS quantity, ls.id_product, ls.id_product_attribute FROM ' . _DB_PREFIX_ . 'lonelystocklog ls INNER JOIN ' . _DB_PREFIX_ .
			'cart_product cp ON cp.id_cart = ls.id_cart AND cp.id_product = ls.id_product AND cp.id_product_attribute = ls.id_product_attribute '.
			' GROUP BY ls.id_product, ls.id_product_attribute ';
			$result = Db::getInstance()->executeS($sql);
			return $result;
		}
		return $result;
	}

    public function checkQuantities($return_product = false)
    {
        if (Configuration::get('PS_CATALOG_MODE') && !defined('_PS_ADMIN_DIR_')) {
            return false;
        }

        foreach ($this->getProducts() as $product) {
            if (!$this->allow_seperated_package && !$product['allow_oosp'] && StockAvailable::dependsOnStock($product['id_product']) &&
                $product['advanced_stock_management'] && (bool)Context::getContext()->customer->isLogged() && ($delivery = $this->getDeliveryOption()) && !empty($delivery)) {
                $product['stock_quantity'] = StockManager::getStockByCarrier((int)$product['id_product'], (int)$product['id_product_attribute'], $delivery);
            }
            // if (!$product['active'] || !$product['available_for_order'] || (!$product['allow_oosp'] && ($product['stock_quantity'] < $product['cart_quantity'] && $product['quantity_available'] < $product['cart_quantity']))) {
            if (!$product['active'] || !$product['available_for_order'] || (!$product['allow_oosp'] && $product['stock_quantity'] < $product['cart_quantity'])) {
                return $return_product ? $product : false;
            }
        }

        return true;
    }
}
