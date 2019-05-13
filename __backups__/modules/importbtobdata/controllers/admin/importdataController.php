<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of importdata
 *
 * @author FRANCIS FOZEU
 */
require_once dirname(__FILE__) . '/../../libs/simplexlsx.class.php';
class ImportdataController  extends AdminImportController {
    //put your code here
    public static $current_product;
    public static $header_file;
    public static $header_infos;
    public $name = 'importbtobdata';
    public static $exchange_rate = 0;
    public static $current_line = 0;
    public static $prefixe_ref = 0;


    public function combinaitionUpdates()
    {
        $default_language = Configuration::get('PS_LANG_DEFAULT');

        $this->receiveTab();
        $handle = $this->openCsvFile2(Configuration::get('IMPORTBTOBDATA_FILE'));
		if(!$handle) {
			$this->writeLog('Le fichier n\' a pas pu être téléchargé.');
			exit;
		}
		
        AdminImportController::setLocale();

        $shop_is_feature_active = Shop::isFeatureActive();
        $tab_warehouse = array('ireland-stock' => 2, 'us-stock' => 4);
        //Pour des raisons de performance, on sait que nous sommes dans un cas uni boutique alors one annule certains traitement dans la boucle
         if (!$shop_is_feature_active) {
            $info_shop = 1;
        } else{
            $info_shop = implode(',', Shop::getContextListShopID());
        }
         // Get shops for each attributes
        $info_shop = explode(',', $info_shop);
        $id_shop_list = array();
        foreach ($info_shop as $shop) {
            if (!empty($shop)) {
                $id_shop_list[] = $shop;
            }
        }
		//Nom du fichier de log à créer
		$file_log = 'log_stock_'.time();
        $employee = new Employee(Configuration::get('IMPORTBTOBDATA_EMPLOYEE_ID'), $this->context->language->id);
        $this->writeLog('Begin import stock ', $file_log);
		$prodCount = 0;
		$prodLimit = 100;
        for ($current_line = 0; $line = fgetcsv($handle, MAX_LINE_SIZE, $this->separator); $current_line++) {
			if((++$prodCount)%$prodLimit == 0){
				sleep(0.05);
				echo '<hr/>PAUSE<hr/>';//die;
			}
            //var_dump($line);die;
            if($current_line == 0) {// on fabrique l'entête
                
                array_walk($line, function(&$value, $key){
                    $value = strtolower(str_replace(array(' ', '/'), array('-', '_'), trim($value)));
                });
                self::$header_file = $line;
				$this->writeLog('Debug line for header ', json_encode(self::$header_file));
            }else {//on commence la mise à jour
                $info = array_combine(self::$header_file, $line);
				if(isset($_GET['checkDev'])){
					// var_dump($info);die;
				}
                //var_dump($info['reference']);
                if($info['reference'] != ''){ //Nous on recherche le produit et la déclinaisons
                    $this->prof_flag('Debut line '.$current_line);
                    $attribute_combinations = Product::getProductAttributeByRef($info['reference']);
                    
                    if (is_array($attribute_combinations) && count($attribute_combinations)) {
                        $tabref = explode('-', $info['reference']);
                        if (self::$prefixe_ref == $tabref['0']) {
                            $product = self::$current_product;
                        } else {
                            $product = new Product((int)$attribute_combinations['id_product'], false, $default_language);
                            self::$current_product = $product;
                            if (self::$exchange_rate == 0 && (float)$info['currency'] != 0) {
                                self::$exchange_rate = (float)$info['currency'];
                            }
                            self::$prefixe_ref = $tabref['0'];
                        }

                        /* Gestion de la dependance des stocks et entrepot*/
                        if(!isset($info['advanced_stock_management']))
                            $info['advanced_stock_management'] = 1;
                        if(!isset($info['depends_on_stock']) && $info['advanced_stock_management'])
                            $info['depends_on_stock'] = 1;
                        if(!isset($info['warehouse']) && $info['advanced_stock_management'])
                            $info['warehouse'] = 1;
                        

                        $id_image = array();
                        // inits attribute
                        $id_product_attribute = $attribute_combinations['id_product_attribute'];
                        $id_product_attribute_update = false;

                        $info['minimal_quantity'] = isset($info['minimal_quantity']) && $info['minimal_quantity'] ? (int)$info['minimal_quantity'] : 1;

                        $info['wholesale_price'] = str_replace(',', '.', $info['wholesale_price']);
                        $info['price'] = str_replace(',', '.', $info['price_fr']);
                        $info['ecotax'] = str_replace(',', '.', $info['ecotax']);
                        $info['weight'] = str_replace(',', '.', $info['weight']);
                        $info['available_date'] = (isset($info['available_date']) && Validate::isDate($info['available_date'])) ? $info['available_date'] : null;
                        $info['ean13'] = (isset($info['ean13'])?$info['ean13']: '');
                        $info['upc'] = (isset($info['upc'])?$info['upc']: '');
						
                        //ici on met à jour la déclinaision
                        $return = $product->updateAttribute(
                            $id_product_attribute,
                            (isset($attribute_combinations['deactivate_price_update']) and $attribute_combinations['deactivate_price_update']) ? $attribute_combinations['wholesale_price'] : (float)$info['wholesale_price'],
                            // (float)$info['wholesale_price'],
                            (isset($attribute_combinations['deactivate_price_update']) and $attribute_combinations['deactivate_price_update']) ? $attribute_combinations['price'] : (float)$info['price'],
                            // (float)$info['price'],
                            (float)$info['weight'],
                            0,
                            (Configuration::get('PS_USE_ECOTAX') ? (float)$info['ecotax'] : 0),
                            $id_image,
                            strval($info['reference']),
                            strval($info['ean13']),
                            (int)$attribute_combinations['default_on'],
                            0,
                            strval($info['upc']),
                            (int)$info['minimal_quantity'],
                            $info['available_date'],
                            null,
                            $id_shop_list
                        );
                        // if($return){
							$info['floor_price'] = (isset($info['last-purchase-price-in-usd']) and floatval($info['last-purchase-price-in-usd']) > 0) ? floatval((floatval(str_replace(',','.',$info['last-purchase-price-in-usd'])) + 10) / 1.13) : 0;
							
							$info['usp'] = (isset($info['last-purchase-price-in-usd']) and floatval($info['last-purchase-price-in-usd']) > 0) ? floatval(str_replace(',','.',$info['last-purchase-price-in-usd'])) : 0;
							
							$info['floor_price_us'] = (isset($info['last-purchase-price-in-usd']) and floatval($info['last-purchase-price-in-usd']) > 0) ? floatval(str_replace(',','.',$info['last-purchase-price-in-usd'])) + 10 : 0;
							
							// $us_price_split = explode('.',$us_price);
							// $us_price = (isset($us_price_split[1]) and intval($us_price_split[1]) > 0) ? $us_price_split[0]+1 : $us_price_split[0];
							// $sql = 'UPDATE `' . _DB_PREFIX_ . 'product_shop` SET `price` = 0 WHERE `id_shop` = 2 AND id_product = '.(int)$product->id;
							if(!isset($attribute_combinations['deactivate_price_update']) or !$attribute_combinations['deactivate_price_update']){
								$us_price = floatval((floatval($info['floor_price_us']))/0.85);
								$sql = 'UPDATE `' . _DB_PREFIX_ . 'product_attribute_shop` SET `price` = '.$us_price.' WHERE `id_shop` = 2 AND id_product_attribute = '.(int)$id_product_attribute;
								Db::getInstance()->execute($sql);
								
								$ue_price = floatval((floatval($info['floor_price']))/0.84);
								$ue_price = (intval($ue_price) == $ue_price) ? intval($ue_price) : intval($ue_price + 1);
								$sql = 'UPDATE `' . _DB_PREFIX_ . 'product_attribute` SET `price` = '.$ue_price.' WHERE id_product_attribute = '.(int)$id_product_attribute;
								$sql = 'UPDATE `' . _DB_PREFIX_ . 'product_attribute_shop` SET `price` = '.$ue_price.' WHERE `id_shop` = 1 AND id_product_attribute = '.(int)$id_product_attribute;
								Db::getInstance()->execute($sql);
							}
                            $this->UpdateMoreInfos($id_product_attribute, $info);
                            //Hook::exec('actionUpdateMoreInfos', array('ipa' => (int)$id_product_attribute, 'id_product' => (int)$product->id, 'infos' => $info));   
							
							if(isset($_GET['checkDev'])){
								// var_dump($info);echo '<hr/>';
								// var_dump($sql);die;
							}else{
								var_dump($info);echo '<br/><br/>';
								var_dump($us_price);echo '<hr/>';
							}
                        // }
                        $id_product_attribute_update = true;

                        /*On commence la gestion du stock*/
                            
                        // Check if warehouse exists
                        if (isset($info['warehouse']) && $info['warehouse']) {
                            {
                                //on boucle sur les entrepôts
                                foreach ($tab_warehouse as $key => $house){
                                    $info['warehouse'] = $tab_warehouse[$key];
                                    if (Warehouse::exists($info['warehouse'])) {
                                        $warehouse_location_entity = new WarehouseProductLocation();
                                        $warehouse_location_entity->id_product = $product->id;
                                        $warehouse_location_entity->id_product_attribute = $id_product_attribute;
                                        $warehouse_location_entity->id_warehouse = $info['warehouse'];
                                        if (WarehouseProductLocation::getProductLocation($product->id, $id_product_attribute, $info['warehouse']) !== false) {
                                            $warehouse_location_entity->update();
                                        } else {
                                            $warehouse_location_entity->save();
                                        }
                                        StockAvailable::synchronize($product->id);
                                    }
                                }
                                
                            }
                        }
						
                        // stock available
                        if (isset($info['depends_on_stock'])) {
                            if ($info['depends_on_stock'] != 0 && $info['depends_on_stock'] != 1) {
                                $this->warnings[] = sprintf(Tools::displayError('Incorrect value for depends on stock for product %1$s '), $product->name[$default_language]);
                            } elseif ((!$info['advanced_stock_management'] || $info['advanced_stock_management'] == 0) && $info['depends_on_stock'] == 1) {
                                $this->warnings[] = sprintf(Tools::displayError('Advanced stock management is not enabled, cannot set depends on stock %1$s '), $product->name[$default_language]);
                            } else {
                                StockAvailable::setProductDependsOnStock($product->id, $info['depends_on_stock'], null, $id_product_attribute);
                            }

                            // This code allows us to set qty and disable depends on stock
                            //if (isset($info['quantity']) && (int)$info['quantity']) {
                            // if depends on stock and quantity, add quantity to stock
                            if ($info['depends_on_stock'] == 1) {
                                $stock_manager = StockManagerFactory::getManager();
                                $price = str_replace(',', '.', $info['wholesale_price']);
                                if ($price == 0) {
                                    $price = 0.000001;
                                }
                                $price = round(floatval($price), 6);
                                /*On met à jour le stock en fonction des entrepôts. On va utiliser la colone stock usa et stock irland
                                 * Tout d'abord puisque c'est un mouvement, on doit supprimer le stock existant.
                                 */
                                foreach ($tab_warehouse as $key => $house){
                                    // $info['warehouse'] = $tab_warehouse[$key];
                                    $warehouse = new Warehouse($house);
                                    //récupération du stock existant
                                    $stockline = $stock_manager->getProductPhysicalQuantities((int)$product->id, $id_product_attribute, $warehouse->id );
                                    //On vide tout le stock présent
                                    if ($stockline > 0 || $stockline < 0) {//on supprime d'abord tout ce qu'on en stock avant d'ajouter
                                        $stock_manager->removeProduct(
                                            $product->id,
                                            $id_product_attribute,
                                            $warehouse,
                                            $stockline,
                                            2,
                                            true,
                                            null,
                                            0,
                                            $employee
                                        );
                                    }
                                    //on met à jour le stock courant en fonction de la quantité dans chaque entrepot
                                    if($house == 2 or $house == 4){
										$id_shop = ($house == 2 ? 1 : 2);
                                        $qty = $this->getStock($info, $id_shop);
										echo 'qty = ';var_dump($qty);echo '<br/>';
										echo 'id_shop = ';var_dump($id_shop);echo '<br/>';
										echo 'info = ';var_dump($info);echo '<hr/>';
                                        if (($qty > 0) && $stock_manager->addProduct((int)$product->id, $id_product_attribute, $warehouse, $qty, 1, $price, true, null, $employee)) {
                                            StockAvailable::synchronize((int)$product->id);
                                        }
										if($house == 4){
											$sql = 'UPDATE `' . _DB_PREFIX_ . 'stock_available` SET `quantity` = '.$qty.' WHERE `id_shop` = 2 AND id_product_attribute = '.(int)$id_product_attribute;
											Db::getInstance()->execute($sql);
										}
                                    }
                                }

                            } else {
                                if ($shop_is_feature_active) {
                                    foreach ($id_shop_list as $shop) {
                                        StockAvailable::setQuantity((int)$product->id, $id_product_attribute, $this->getStock($info,(int)$shop), (int)$shop);
                                    }
                                } else {
                                    StockAvailable::setQuantity((int)$product->id, $id_product_attribute, $this->getStock($info,$this->context->shop->id), $this->context->shop->id);
                                }
                            }
                            //}
                        }
                        // if not depends_on_stock set, use normal qty
                        else {

                            if ($shop_is_feature_active) {
                                foreach ($id_shop_list as $shop) {
                                    StockAvailable::setQuantity((int)$product->id, $id_product_attribute, $this->getStock($info, (int)$shop), (int)$shop);
                                }
                            } else {
                                StockAvailable::setQuantity((int)$product->id, $id_product_attribute, $this->getStock($info, $this->context->shop->id), $this->context->shop->id);
                            }
                        }
                        //}
                        
                    } else {
                        
                    }
                }
                
            }
			$this->writeLog($current_line.' lines already import ', $file_log);
            /*if(fmod($current_line, 100) == 0){
                $this->writeLog($current_line.' lines already import ', $file_log);
                sleep(5);
            }*/
            //if($current_line > 55)
            //    goto ENDSCRIPT;
        }
        //ENDSCRIPT:
        /*on met à jour la dévise si le taux d'échange varie */
        if (self::$exchange_rate != 0) {
            $currency = new Currency(Configuration::get('IMPORTBTOBDATA_CURRENCY_ID'), $this->context->language->id);
            $currency->conversion_rate = self::$exchange_rate;
            if (is_object($currency) && $currency->id > 0) {
                $currency->save();
                $this->writeLog('Devise mise à jour: nouveau taux euro-dollar '.self::$exchange_rate, $file_log);
            } else {
                $this->writeLog('La devise n\'existe pas ', $file_log);
            }
        }
        
        $this->closeCsvFile($handle);
        /*On met à jour le taux d'exchange*/
        
    }
    private function UpdateMoreInfos($ipa, $infos)
    {
		if($this->context->shop->id == 1)
			$qty = (int)(isset($infos['ireland-stock'])?$infos['ireland-stock']:0);
		else
			$qty = (int)(isset($infos['us-stock'])?$infos['us-stock']:0);
        $qty += (int)(isset($infos['b2b-stock'])?$infos['b2b-stock']:0);
        $return = Db::getInstance()->update('product_attribute', array(
            'irland_stock' => (int)(isset($infos['ireland-stock'])?$infos['ireland-stock']:''),
            'us_stock'     => (int)(isset($infos['us-stock'])?$infos['us-stock']:''),
            'in_transit'        => (int)(isset($infos['in-transit'])?$infos['in-transit']:''),
            'in_order'        => (int)(isset($infos['in-order'])?$infos['in-order']:''),
            'timing'        => (int)(isset($infos['timing'])?$infos['timing']:''),
            'ready_to_ship'        => $qty,
            'b2b_stock'     => (int)(isset($infos['b2b-stock'])?$infos['b2b-stock']:''),
            'floor_price'     => (float)(isset($infos['floor_price'])?$infos['floor_price']:''),
            'floor_price_us'     => (float)(isset($infos['floor_price_us'])?$infos['floor_price_us']:''),
            'usp'     => (float)(isset($infos['usp'])?$infos['usp']:''),
            
        ), 'id_product_attribute = '.(int)$ipa);
    }
    
    public static function getPath($file = '')
    {
        $sep = DIRECTORY_SEPARATOR;
        return realpath(dirname(__FILE__).$sep.'..'.$sep.'..'.$sep.'import'.$sep.$file);
    }
    
    protected function openCsvFile2($file)
    {
        $handle = fopen($file, 'r');
        return $handle;
    }
    public function writeLog($txt, $namefile='logs', $start=0) {
        
        $log_dir = _PS_MODULE_DIR_.$this->name.'/log';
        $log_file = $log_dir . DIRECTORY_SEPARATOR .$namefile;
        
        if ( !is_dir($log_dir) ) {
            if ( !mkdir($log_dir, 0777, true) ) {
                $this->warning = sprintf( $this->l("An error occured while creating directory %s"), $log_dir );
                return false;
            } else {
                // Copy index file into directory
                //Tools::copy( $log_dir . DIRECTORY_SEPARATOR . 'index.php', $filename);
            }
        } elseif ($start && Tools::file_exists_no_cache($log_file)) {
            @unlink($log_file);
        }
        $log = fopen( $log_file, 'a+');
        
        fputs($log, $txt.' '.date('Y-m-d H:i:s').' '.microtime(true)."\r\n");
        fclose($log);
    }
    
    
    
    public function UsersInfosUpdates()
    {
		//Nom du fichier de log à créer
		$file_log = 'log_other';
        $this->writeLog('Début traitement des infos suppémentaires', $file_log);
        $this->receiveTab();
        
        $handle = $this->openCsvFile2(Configuration::get('IMPORTBTOBDATA_CUSTOMER_FILE'));
		$nber_line = 0;
		
        for ($current_line = 0; $line = fgetcsv($handle, MAX_LINE_SIZE, $this->separator); $current_line++) {
            
            if($current_line == 0) {// on fabrique l'entête
                //var_dump($line)
                array_walk($line, function(&$value, $key){
                    $value = strtolower(str_replace(array(' ', '/'), array('-', '_'), trim($value)));
                });
                self::$header_infos = $line;
				//var_dump($line);die;
				$this->writeLog('Entête des infos suppémentaires traitées', $file_log);
            }else {//on commence la mise à jour
                $infos = array_combine(self::$header_infos, $line);
                $attribute_combinations = Product::getProductAttributeByRef($infos['reference']);
                if (is_array($attribute_combinations) && count($attribute_combinations)) {
                    $return = Db::getInstance()->update('product_attribute', array(
                        'last_purchase_price'        => (float)(isset($infos['last-purchase-price-usd'])?$infos['last-purchase-price-usd']:''),
                        'last_seven_day_sale'        => (float)(isset($infos['last-7-days-of-sales'])?$infos['last-7-days-of-sales']:''),
                        'to_order'      => (float)(isset($infos['to-order'])?$infos['to-order']:''),
                        'last_month_sale'      => (float)(isset($infos['last-month-sales'])?$infos['last-month-sales']:''),
                        'current_month_sale'      => (float)(isset($infos['current-month-sales'])?$infos['current-month-sales']:''),
                        'current_month_trend'      => (float)(isset($infos['current-month-trend'])?$infos['current-month-trend']:''),
                        'average'      => (float)(isset($infos['average_day'])?$infos['average_day']:''),
                        'inventory_day'      => (float)(isset($infos['days-of-inventory'])?$infos['days-of-inventory']:''),
                        'last_seven_day_inventory'      => (float)(isset($infos['last-7-days-of-inventory'])?$infos['last-7-days-of-inventory']:''),
                        'average_basket'      => (float)(isset($infos['average-basket-eur'])?$infos['average-basket-eur']:''),
                        'back_orders'      => (float)(isset($infos['back-orders'])?$infos['back-orders']:''),
                        'idp'      => (float)(isset($infos['idp'])?$infos['idp']:''),
                        'app'      => (float)(isset($infos['app-eur'])?$infos['app-eur']:''),
                        'processing_usa'      => (int)(isset($infos['processing-usa'])?$infos['processing-usa']:''),
                        'received_usa'      => (int)(isset($infos['received-usa'])?$infos['received-usa']:''),
						'inbatch_usa'      => (int)(isset($infos['inbatch-usa'])?$infos['inbatch-usa']:0),
						'date_upd'      => date('Y-m-d H:i:s'),

                    ), 'id_product_attribute = '.(int)$attribute_combinations['id_product_attribute']);
					
                }
            }
        }
        $this->closeCsvFile($handle);
        $this->writeLog('Fin traitement des infos suppémentaires : '.$nber_line.' lignes traitées', $file_log);
		Tools::clearSmartyCache();
    }
    
    function prof_flag($str)
    {
        global $prof_timing, $prof_names;
        $prof_timing[] = microtime(true);
        $prof_names[] = $str.' ';
    }

    // Call this when you're done and want to see the results
    function prof_print()
    {
        global $prof_timing, $prof_names;
        $size = count($prof_timing);
        for($i=0;$i<$size - 1; $i++)
        {
            print_r($prof_names[$i]);
            print_r(sprintf("%f", $prof_timing[$i+1]-$prof_timing[$i])."\r\n");
        }
        print_r($prof_names[$size-1]);
        $prof_timing = $prof_names = array();
    }
    
    private function getStock($infos, $shop = 1)
    {
        //var_dump($infos);die;
        $stock = 0;
        //$stock += (int)(isset($infos['in-transit'])?$infos['in-transit']:0);
        //$stock += (int)(isset($infos['in-order'])?$infos['in-order']:0);
		if($shop == 1){		
			$stock += (int)(isset($infos['ireland-stock'])?$infos['ireland-stock']:0);
		}else{
			$stock += (int)(isset($infos['us-stock'])?$infos['us-stock']:0);
		}
        //$stock += (int)(isset($infos['b2b-stock'])?$infos['b2b-stock']:0);
        return $stock;
    }
}
