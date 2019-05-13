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
include_once  _PS_OVERRIDE_DIR_. 'classes/ProductPack.php';
class CartController extends CartControllerCore
{
    public function postProcess()
    {
        parent::postProcess();
        if (Tools::getIsset('getProductsInCart')) {
            $this->processGetProductsInCart();
        }
    }
    public function processGetProductsInCart()
    {
        $cart_products = $this->context->cart->getProducts();
        $this->ajaxDie(Tools::jsonEncode(array(
            'hasError' => false,
            'products' => $cart_products)
        ));
    }
	protected function getPackProductAttribute($idPack)
    {
		$product = new Product($idPack, true, $this->context->language->id);
		return $product->cache_default_attribute;
	}
	protected function getIdPack()
    {
		$idPack = (int) Tools::getValue('id_product_pack');
		if(!$idPack){
			$previousPack = ProductPack::getForCartProduct($this->context->cart->id, $this->id_product, $this->id_product_attribute);
			if(empty($previousPack)){
				$defaultPack = ProductPack::getDefault($this->id_product);
				if(!empty($defaultPack)){
					$idPack = $defaultPack['id_pack'];
				}
			}else{
				$idPack = $previousPack['id_product'];
			}
		}
		return $idPack;
	}
	protected function processChangeProductInCart($isPack = false)
    {
		// if($this->context->shop->id == 2){
			// parent::processChangeProductInCart();
			// return true;
		// }
		if (!$this->context->cart->id) {
			if (Context::getContext()->cookie->id_guest) {
				$guest = new Guest(Context::getContext()->cookie->id_guest);
				$this->context->cart->mobile_theme = $guest->mobile_theme;
			}
			$this->context->cart->add();
			if ($this->context->cart->id) {
				$this->context->cookie->id_cart = (int)$this->context->cart->id;
			}
		}
		if($isPack){
			$mode = 'add';
			$_POST['op'] = 'up';
		}else{
			$idProduct = $this->id_product;
			$idProductAttribute = $this->id_product_attribute;
			$product_add_pack = (int) Tools::getValue('product_add_pack');
		// var_dump($product_add_pack);die;
			$no_packaging = ($product_add_pack==2);
			$idPack = 0;
			$idOldPack = 0;
			$previousPack = array();
			$oldProductPack = ProductPack::getForCartProduct($this->context->cart->id, $this->id_product, $this->id_product_attribute);
			if(empty($oldProductPack) && (!$no_packaging)){
				$defaultPack = ProductPack::getDefault($this->id_product);
				if(!empty($defaultPack)){
					$idPack = $defaultPack['id_pack'];
				}
			}else{
				if(!$no_packaging){
					$idPack = $oldProductPack['id_product'];
				}
				$idOldPack = $oldProductPack['id_product'];
			}
			if($idOldPack){
				$previousPack = ProductPack::getExisting($this->context->cart->id, $idOldPack);
				//$previousPack = ProductPack::getForCartProduct($this->context->cart->id, $this->id_product, $this->id_product_attribute);
				$keyPack = $idProduct.'_'.$idProductAttribute;
				if(!empty($previousPack)){
					$idPackAttribute = $this->getPackProductAttribute($previousPack['id_product']);
					$this->id_product = $previousPack['id_product'];
					$this->id_product_attribute = $idPackAttribute;
					
					parent::processDeleteProductInCart();
					$this->id_product = $idProduct;
					$this->id_product_attribute = $idProductAttribute;
					if(isset($previousPack['products'][$keyPack])){
						unset($previousPack['products'][$keyPack]);
					}
				}
			}
			//$idPack = $this->getIdPack($previousPack);
			
			$mode = (Tools::getIsset('update') && $this->id_product) ? 'update' : 'add';
		}
        if ($this->qty == 0) {
            $this->errors[] = Tools::displayError('Null quantity.', !Tools::getValue('ajax'));
        } elseif (!$this->id_product) {
            $this->errors[] = Tools::displayError('Product not found', !Tools::getValue('ajax'));
        }

        $product = new Product($this->id_product, true, $this->context->language->id);
        if (!$product->id || !$product->active || !$product->checkAccess($this->context->cart->id_customer)) {
            $this->errors[] = Tools::displayError('This product is no longer available.', !Tools::getValue('ajax'));
            return;
        }

        $qty_to_check = $this->qty;
        $cart_products = $this->context->cart->getProducts();
		//$productPrevQuantity = 0;
		$setOtherQuantity= false;
		if(!$isPack && !empty($previousPack) && !empty($previousPack['products'])){
			$otherPackQuantity=0;
			$setOtherQuantity= true;
		}
        if (is_array($cart_products)) {
            foreach ($cart_products as $cart_product) {
                if ((!isset($this->id_product_attribute) || $cart_product['id_product_attribute'] == $this->id_product_attribute) &&
                    (isset($this->id_product) && $cart_product['id_product'] == $this->id_product)) {
                    $qty_to_check = $cart_product['cart_quantity'];
					//$productPrevQuantity = $cart_product['cart_quantity'];
                    if (Tools::getValue('op', 'up') == 'down') {
                        $qty_to_check -= $this->qty;
                    } else {
                        $qty_to_check += $this->qty;
                    }
					if(!$setOtherQuantity){
						break;
					}
                    
                }
				if($setOtherQuantity){
					$key = $cart_product['id_product'] .'_'.$cart_product['id_product_attribute'];
					if(isset($previousPack['products'][$key])){
						$otherPackQuantity += $cart_product['cart_quantity'];
					}
				}
            }
        }
		$productQuantity = $qty_to_check;
		
		if(!$isPack){
			$this->parent_products = array();
			if(!empty($previousPack) && !empty($previousPack['products']) && (empty($idPack)||($previousPack['id_product']!=$idPack))){
				$this->id_product = $previousPack['id_product'];
				$this->id_product_attribute = $idPackAttribute;
				/*$previousPack['quantity'] = (int)$previousPack['quantity'];
				$otherPackQuantity = $previousPack['quantity'] - $productPrevQuantity;*/
				$oldQuantity = $this->qty;
				$this->qty = $otherPackQuantity;
				
				$this->parent_products = $previousPack['products'];
				
				$this->processChangeProductInCart(true);
				
				$this->id_product = $idProduct;
				$this->id_product_attribute = $idProductAttribute;
				$this->qty = $oldQuantity;
			}elseif(!empty($previousPack) && !empty($previousPack['products']) && ($previousPack['id_product']==$idPack)){
				$productQuantity += $otherPackQuantity;
				$this->parent_products = $previousPack['products'];
			}
		}
        // Check product quantity availability
        if ($this->id_product_attribute) {
            if (!Product::isAvailableWhenOutOfStock($product->out_of_stock) && !Attribute::checkAttributeQty($this->id_product_attribute, $qty_to_check)) {
                $this->errors[] = Tools::displayError('There isn\'t enough product in stock.1', !Tools::getValue('ajax'));
            }
        } elseif ($product->hasAttributes()) {
            $minimumQuantity = ($product->out_of_stock == 2) ? !Configuration::get('PS_ORDER_OUT_OF_STOCK') : !$product->out_of_stock;
            $this->id_product_attribute = Product::getDefaultAttribute($product->id, $minimumQuantity);
            // @todo do something better than a redirect admin !!
            if (!$this->id_product_attribute) {
                Tools::redirectAdmin($this->context->link->getProductLink($product));
            } elseif (!Product::isAvailableWhenOutOfStock($product->out_of_stock) && !Attribute::checkAttributeQty($this->id_product_attribute, $qty_to_check)) {
                $this->errors[] = Tools::displayError('There isn\'t enough product in stock.2', !Tools::getValue('ajax'));
            }
        } elseif (!$product->checkQty($qty_to_check)) {
			// var_dump($product);die;
            $this->errors[] = Tools::displayError('There isn\'t enough product in stock.3', !Tools::getValue('ajax'));
        }

        // If no errors, process product addition
        if (!$this->errors && $mode == 'add') {
            // Add cart if no cart found
            if (!$this->context->cart->id) {
                if (Context::getContext()->cookie->id_guest) {
                    $guest = new Guest(Context::getContext()->cookie->id_guest);
                    $this->context->cart->mobile_theme = $guest->mobile_theme;
                }
                $this->context->cart->add();
                if ($this->context->cart->id) {
                    $this->context->cookie->id_cart = (int)$this->context->cart->id;
                }
            }

            // Check customizable fields
            if (!$product->hasAllRequiredCustomizableFields() && !$this->customization_id) {
                $this->errors[] = Tools::displayError('Please fill in all of the required fields, and then save your customizations.', !Tools::getValue('ajax'));
            }

            if (!$this->errors) {
                $cart_rules = $this->context->cart->getCartRules();
                $available_cart_rules = CartRule::getCustomerCartRules($this->context->language->id, (isset($this->context->customer->id) ? $this->context->customer->id : 0), true, true, true, $this->context->cart, false, true);
                $update_quantity = $this->context->cart->updateQty($this->qty, $this->id_product, $this->id_product_attribute, $this->customization_id, Tools::getValue('op', 'up'), $this->id_address_delivery);
                if ($update_quantity < 0) {
                    // If product has attribute, minimal quantity is set with minimal quantity of attribute
                    $minimal_quantity = ($this->id_product_attribute) ? Attribute::getAttributeMinimalQty($this->id_product_attribute) : $product->minimal_quantity;
                    $this->errors[] = sprintf(Tools::displayError('You must add %d minimum quantity', !Tools::getValue('ajax')), $minimal_quantity);
                } elseif (!$update_quantity) {
                    $this->errors[] = Tools::displayError('You already have the maximum quantity available for this product.', !Tools::getValue('ajax'));
                } elseif ((int)Tools::getValue('allow_refresh')) {
                    // If the cart rules has changed, we need to refresh the whole cart
                    $cart_rules2 = $this->context->cart->getCartRules();
                    if (count($cart_rules2) != count($cart_rules)) {
                        $this->ajax_refresh = true;
                    } elseif (count($cart_rules2)) {
                        $rule_list = array();
                        foreach ($cart_rules2 as $rule) {
                            $rule_list[] = $rule['id_cart_rule'];
                        }
                        foreach ($cart_rules as $rule) {
                            if (!in_array($rule['id_cart_rule'], $rule_list)) {
                                $this->ajax_refresh = true;
                                break;
                            }
                        }
                    } else {
                        $available_cart_rules2 = CartRule::getCustomerCartRules($this->context->language->id, (isset($this->context->customer->id) ? $this->context->customer->id : 0), true, true, true, $this->context->cart, false, true);
                        if (count($available_cart_rules2) != count($available_cart_rules)) {
                            $this->ajax_refresh = true;
                        } elseif (count($available_cart_rules2)) {
                            $rule_list = array();
                            foreach ($available_cart_rules2 as $rule) {
                                $rule_list[] = $rule['id_cart_rule'];
                            }
                            foreach ($cart_rules2 as $rule) {
                                if (!in_array($rule['id_cart_rule'], $rule_list)) {
                                    $this->ajax_refresh = true;
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }

        $removed = CartRule::autoRemoveFromCart();
        CartRule::autoAddToCart();
		if (count($removed) && (int)Tools::getValue('allow_refresh')) {
            $this->ajax_refresh = true;
        }
		if (!$this->errors && !$isPack && $idPack ) {
			$this->id_product = $idPack;
			$this->id_product_attribute = $this->getPackProductAttribute($idPack);
			$this->qty = $productQuantity;
			
			$this->parent_id_product = $idProduct;
			$this->parent_id_product_attribute = $idProductAttribute;
			$this->parent_products[] = array('id_product'=>$idProduct, 'id_product_attribute'=>$idProductAttribute);
			$this->processChangeProductInCart(true);
		}elseif(!$this->errors && $isPack ){
			ProductPack::updateCartProduct($this->context->cart->id, $this->id_product, $this->parent_products);
		}
    }
	
	protected function processDeleteProductInCart($isPack = false){
		parent::processDeleteProductInCart();
		$idProduct = $this->id_product;
		$idProductAttribute = $this->id_product_attribute;
		if(!$isPack){
			$previousPack = ProductPack::getForCartProduct($this->context->cart->id, $this->id_product, $this->id_product_attribute);
			if(!empty($previousPack)){
				$this->id_product = $previousPack['id_product'];
				$idPackAttribute = $this->getPackProductAttribute($previousPack['id_product']);
				$this->id_product_attribute = $idPackAttribute;
				unset($previousPack['products'][$idProduct.'_'.$idProductAttribute]);
				parent::processDeleteProductInCart();
			}
			
			
			if(!empty($previousPack) && !empty($previousPack['products'])){
				$cart_products = $this->context->cart->getProducts();
				$otherPackQuantity = 0;
				if (is_array($cart_products)) {
					foreach ($cart_products as $cart_product) {
						$key = $cart_product['id_product'] .'_'.$cart_product['id_product_attribute'];
						if(isset($previousPack['products'][$key])){
							$otherPackQuantity += $cart_product['cart_quantity'];
						}
					}
				}
				$this->id_product = $previousPack['id_product'];
				$this->id_product_attribute = $idPackAttribute;
				$this->parent_products = $previousPack['products'];
				$this->qty = $otherPackQuantity;
				$this->processChangeProductInCart(true);
			}
		}
	}
	
	public function displayAjaxRefreshCart()
    {
		if ($this->context->cart->id) {
			$this->context->cart->update();
		}
		$this->displayAjax();
	}
}
