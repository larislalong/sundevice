<?php
/**
 * 2015-2017 Crystals Services
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
 * needs please refer to http://www.crystals-services.com/ for more information.
 *
 * @author Crystals Services Sarl <contact@crystals-services.com>
 * @copyright 2015-2017 Crystals Services Sarl
 * @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 *          International Registered Trademark & Property of Crystals Services Sarl
 */

if (! defined('_PS_VERSION_')) {
    exit();
}

// ini_set('memory_limit', '4024M');

include_once _PS_MODULE_DIR_ . 'blocklayeredcustom/classes/BlcFilterBlock.php';
include_once _PS_MODULE_DIR_ . 'blocklayeredcustom/classes/BlcProductIndex.php';
include_once _PS_MODULE_DIR_ . 'blocklayeredcustom/controllers/admin/BlcAdminController.php';
include_once _PS_MODULE_DIR_ . 'blocklayeredcustom/controllers/front/BlcFrontController.php';
include_once _PS_MODULE_DIR_ . 'blocklayeredcustom/controllers/both/BlcDefinitionController.php';

class Blocklayeredcustom extends Module
{
    protected $config_form = false;

    public $adminController;
	public $definition;

    public function __construct()
    {
        $this->name = 'blocklayeredcustom';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Crystals Services Sarl';
        $this->need_instance = 0;
        $this->bootstrap = true;
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);  
		$this->module_key = '9cbb840a4aa3d10ca775233ea423edcf';
        
        parent::__construct();
        
        $this->displayName = $this->l('Block Layered Custom');
        $this->description = $this->l('this module help you to display combinaison in front office.');
		
        $this->definition = new BlcDefinitionController($this, $this->context, $this->local_path, $this->_path);
        $this->adminController = new BlcAdminController($this, $this->context, $this->local_path, $this->_path);
		$this->frontController = new BlcFrontController($this, $this->context, $this->local_path, $this->_path, __FILE__);
		
		//sun-device.com?custom_action=update-reference
		if(Tools::getValue('custom_action') == 'update-reference'){
			$this->updateCombinaisonsREF();
		}
		//sun-device.com?custom_action=import-idp
		if(Tools::getValue('custom_action') == 'import-idp'){
			$this->importIdpFromCSV();
		}
		//sun-device.com?custom_action=update-ref
		if(Tools::getValue('custom_action') == 'update-ref'){
			$this->updateReferencesFromCSV();
		}
		//sun-device.com?custom_action=export-reference
		if(Tools::getValue('custom_action') == 'export-reference'){
			$this->exportCombinaisonsToCSV();
		}
		//sun-device.com?custom_action=import-floor-prices
		if(Tools::getValue('custom_action') == 'import-floor-prices'){
			$this->importFloorPricesFromCSV();
		}
    }
	
	public function updateCombinaisonsREF(){
		$all_combins = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT
				pa.id_product_attribute, p.id_product, p.reference as product_ref,pal.name AS attr_name
			FROM '._DB_PREFIX_.'product_attribute pa
			INNER JOIN '._DB_PREFIX_.'product p ON (p.id_product = pa.id_product)
			LEFT JOIN '._DB_PREFIX_.'product_attribute_combination pac ON (pa.id_product_attribute = pac.id_product_attribute)
			LEFT JOIN '._DB_PREFIX_.'attribute_lang pal ON (pac.id_attribute = pal.id_attribute)
			WHERE p.reference <> "" AND pal.name IN ("A+","A","B","C","New") ');
		$count = 0;
		// var_dump($all_combins);die; 
		foreach($all_combins as $combin){
			$grade = '';
			if($combin['attr_name'] == 'New'){
				$grade = 'CPO';
			}elseif($combin['attr_name'] == 'A+'){
				$grade = 'PRE';
			}elseif($combin['attr_name'] == 'A'){
				$grade = 'MED';
			}elseif($combin['attr_name'] == 'B'){
				$grade = 'BAS';
			}else{
				$grade = 'ECO';
			}
			
			$ref = $combin['product_ref'].'-'.$grade.$combin['id_product_attribute'];
			$sql = 'UPDATE `sundev_product_attribute` SET `reference` = "'.$ref.'" WHERE `sundev_product_attribute`.`id_product_attribute` = '.intval($combin['id_product_attribute']).';';
			
			var_dump($combin);
			echo '<br/>';
			var_dump($sql);
			echo '<br/>';
			var_dump($ref);
			echo '<br/>';
			var_dump(Db::getInstance()->execute($sql));
			echo '<br/>';
			
			$count++;
			if($count % 50 == 0){
				echo 'allready '.$count.' combinations... <hr/>';
				sleep(1);
			}
		var_dump($count);
		}
		die('Combinations update OK!');
	}
	
	public function exportCombinaisonsToCSV(){
		$idShop = Tools::getValue('id_shop', 1);
		$idShop = ($idShop == 2)?2:1;
		$combins = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT 
				pa.`reference` as reference_sundevice,
				pa.`reference_smarter`,
				pl.`name`as model,
				pa.`idp` as idp,
				pal.`name` as attribute,
				pas.`price`,
				pas.id_shop,
				IF(pas.id_shop = 1, "EU", "US") AS shop
			FROM `sundev_product_attribute` pa
			INNER JOIN `sundev_product_lang` pl ON (pl.`id_product` = pa.`id_product`)
			INNER JOIN `sundev_product_attribute_combination` pac ON (pa.id_product_attribute = pac.id_product_attribute)
			INNER JOIN `sundev_product_attribute_shop` pas ON (pa.id_product_attribute = pas.id_product_attribute)
			LEFT JOIN `sundev_attribute_lang` pal ON (pac.id_attribute = pal.id_attribute)
			WHERE pl.id_lang = 1 AND  pal.id_lang = 1 AND pas.id_shop = '.$idShop.'
			ORDER BY pl.`name` ASC
		');
		
		// var_dump($combins);die;
		
		$bestCombinsList = array();
		if(isset($combins) and !empty($combins)){
				$combinLine = array();
				$combinLine['reference_smarter']= '';
				$combinLine['reference_sundevice']= '';
				$combinLine['idp'] 		= '';
				$combinLine['model'] 	= '';
				$combinLine['capacity'] = '';
				$combinLine['color'] 	= '';
				$combinLine['grade'] 	= '';
				$combinLine['price'] 	= 0;
				$combinLine['shop'] 	= '';
			foreach($combins as $combin){
				if(!$combin['idp']){
					continue;
				}
				// var_dump($combin);die;
				$combinLine['reference_smarter'] = $combin['reference_smarter'];
				$combinLine['reference_sundevice'] = $combin['reference_sundevice'];
				$combinLine['idp'] = $combin['idp'];
				$combinLine['model'] = $combin['model'];
				$combinLine['price'] = floatval($combin['price']);
				$combinLine['shop'] = $combin['shop'];
				if(in_array($combin['attribute'], array("A+","A","B","C","New"))){
					$combinLine['grade'] = 'Grade '.$combin['attribute'];	
				}elseif((int)$combin['attribute'] > 0){
					$combinLine['capacity'] = (int)$combin['attribute'];	
				}else{
					$combinLine['color'] = $combin['attribute'];	
				}
				$bestCombinsList[$combin['reference_sundevice']] = $combinLine;
			}
		}
		
		// $boxes = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			// SELECT 
				// p.`reference` as reference_sundevice,
				// pl.`name`as model
			// FROM `sundev_product` p
			// INNER JOIN `sundev_product_lang` pl ON (pl.`id_product` = p.`id_product`)
			// WHERE pl.id_lang = 1 AND  p.id_category_default = 6
		// ');
		// var_dump($boxes[0]);die;
		// if(isset($boxes) and !empty($boxes)){
				// $boxeLine = array();
				// $boxeLine['reference_smarter']= '';
				// $boxeLine['reference_sundevice']= '';
				// $boxeLine['idp'] 		= 0;
				// $boxeLine['model'] 	= '';
				// $boxeLine['capacity'] = '';
				// $boxeLine['color'] 	= '';
				// $boxeLine['grade'] 	= '';
			// foreach($boxes as $box){
				// $boxeLine['reference_sundevice'] = $box['reference_sundevice'];
				// $boxeLine['model'] = $box['model'];
				// $bestCombinsList[$box['reference_sundevice']] = $boxeLine;
			// }
		// }
		
		// var_dump($bestCombinsList);die;
		if(isset($bestCombinsList) and !empty($bestCombinsList)){
			$filename = 'sun-device-'.($idShop==2?'US':'EU').'-declinaisons_produits-'.date('Ymd-His').'.csv';
			$delimiter = ';';
			$f = fopen('php://memory', 'w');
			$hasHeader = false;
			foreach ($bestCombinsList as $line) { 
				if(!$hasHeader){
					fputcsv($f, array_keys($line), $delimiter);
					$hasHeader = true;
				}
				fputcsv($f, $line, $delimiter); 
			}
			fseek($f, 0);
			header('Content-Type: application/csv');
			header('Content-Disposition: attachment; filename="'.$filename.'";');
			fpassthru($f);
			
			exit();
		}
	}

	public function importIdpFromCSV($saveToo = false){
		$file = fopen(dirname(__FILE__).'/matchingsundevice.csv', 'r');
		$fullArrayFromCSV = array();
		$count = 0;
		$idpKeys = 2;
		$sundevKeys = 1;
		$modelKeys = 3;
		$capKeys = 4;
		$colKeys = 5;
		$graKeys = 6;
		while (($line = fgetcsv($file,4072, ';')) !== FALSE) {
			$count++;
			$lineAsArray = $line;
			if($count == 1 or !$lineAsArray[$modelKeys]){
				continue;
			}
			$arrayKey = $lineAsArray[0];
			$arrayKeySplit = explode('-',$arrayKey);
			if(is_numeric($arrayKeySplit[0])){
				$lineIndex = str_replace(' ','_',strtolower($arrayKeySplit[0].'-'.$lineAsArray[$capKeys].'-'.$lineAsArray[$colKeys].'-'.$lineAsArray[$graKeys]));
				
				while(isset($fullArrayFromCSV[$arrayKeySplit[0]][$lineIndex]) and $fullArrayFromCSV[$arrayKeySplit[0]][$lineIndex] != ''){
					$lineIndex = $lineIndex.'-bis';
				}
				$fullArrayFromCSV[$arrayKeySplit[0]][$lineIndex] = $lineAsArray;
			}
			// if($count == 5){
				// var_dump($fullArrayFromCSV);die;
			// }
		}
		fclose($file);
		
		if(!empty($fullArrayFromCSV)){
					// var_dump($fullArrayFromCSV);die;
			$countProduct = 0;
			foreach($fullArrayFromCSV as $ref => $prdtArray){
				$bestCombinsList = array();
				$combins = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
					SELECT 
						pa.`reference` as sun_device_ref,
						pl.`name`as model,
						pa.`idp` as idp,
						pal.`name` as attribute
					FROM `sundev_product_attribute` pa
					INNER JOIN `sundev_product_lang` pl ON (pl.`id_product` = pa.`id_product`)
					INNER JOIN `sundev_product_attribute_combination` pac ON (pa.id_product_attribute = pac.id_product_attribute)
					LEFT JOIN `sundev_attribute_lang` pal ON (pac.id_attribute = pal.id_attribute)
					WHERE pl.id_lang = 1 AND pal.id_lang = 1 AND pa.`reference` LIKE "'.$ref.'-%" 
				');
				if(isset($combins) and !empty($combins)){
					$combinLine = array();
					$combinLine['reference_smarter']= '';
					$combinLine['reference_sun_device']= '';
					$combinLine['model'] 	= '';
					$combinLine['idp'] 		= '';
					$combinLine['capacity'] = '';
					$combinLine['color'] 	= '';
					$combinLine['grade'] 	= '';
					foreach($combins as $combin){
						$combinLine['reference_sun_device'] = $combin['sun_device_ref'];
						$combinLine['model'] = $combin['model'];
						$combinLine['idp'] = $combin['idp'];
						if(in_array($combin['attribute'], array("A+","A","B","C","New"))){
							switch(strtolower($combin['attribute'])){
								case 'a+':{
									$combinLine['grade'] = 'a';
									break;
								}
								case 'a':{
									$combinLine['grade'] = 'b';
									break;
								}
								case 'b':{
									$combinLine['grade'] = 'c';
									break;
								}
								case 'c':{
									$combinLine['grade'] = 'd';
									break;
								}
								default:{
									$combinLine['grade'] = $combin['attribute'];
									break;
								}
							}
						}elseif((int)$combin['attribute'] > 0){
							$combinLine['capacity'] = (int)$combin['attribute'];	
						}else{
							$combinLine['color'] = $combin['attribute'];	
						}
						$bestCombinsList[$combin['sun_device_ref']] = $combinLine;
					}
					if(isset($prdtArray) and !empty($prdtArray) and !empty($bestCombinsList)){
						$savedKeys = array();
						foreach($bestCombinsList as $key => $bestCombin){
							$cuurentCombinIndex = str_replace(' ','_',strtolower($ref.'-'.$bestCombin['capacity'].'-'.$bestCombin['color'].'-'.$bestCombin['grade']));
							while(in_array($cuurentCombinIndex,$savedKeys)){
								$cuurentCombinIndex = $cuurentCombinIndex.'-bis';
							}
							if(isset($prdtArray[$cuurentCombinIndex][$idpKeys])){
								
								var_dump('N° = '.++$countProduct);echo '<br>';
								var_dump('index = '.$cuurentCombinIndex);echo '<br>';
								var_dump('Model = '.$prdtArray[$cuurentCombinIndex][$modelKeys]);echo '<br>';
								var_dump('idp = '.$prdtArray[$cuurentCombinIndex][$idpKeys]);echo '<br>';
								var_dump('reference_smarter = '.$prdtArray[$cuurentCombinIndex][0]);echo '<br>';
								var_dump('WHERE `reference` = "'.$key);echo '<br>';
								
								var_dump(Db::getInstance()->execute('
									UPDATE sundev_product_attribute
									SET 
										active = 1, 
										idp = "'.$prdtArray[$cuurentCombinIndex][$idpKeys].'", 
										reference_smarter = "'.$prdtArray[$cuurentCombinIndex][0].'"
									WHERE `reference` = "'.$key.'"; 
								'));echo '<br><hr><br>';
							}
							$savedKeys[] = $cuurentCombinIndex;
						}
					}
					// var_dump($bestCombinsList);die;
				}
			}
		}
		exit();
	}

	public function updateReferencesFromCSV($saveToo = false){
		$file = fopen(dirname(__FILE__).'/To_match_Sundevice.csv', 'r');
		$fullArrayFromCSV = array();
		$count = 0;
		// var_dump($file);die;
		// $sundevKeys = 5;
		$smarterKeys = 5;
		$idpKeys = 0;
		$modelKeys = 1;
		$capKeys = 2;
		$colKeys = 3;
		$graKeys = 4;
		while (($line = fgetcsv($file,4072, ';')) !== FALSE) {
			$count++;
			$lineAsArray = $line;
			if($count == 1 or !$lineAsArray[$modelKeys]){
				continue;
			}
			$arrayKey = $lineAsArray[$smarterKeys];
			$arrayKeySplit = explode('-',$arrayKey);
			if(is_numeric($arrayKeySplit[0])){
				$lineIndex = str_replace(' ','_',strtolower($arrayKeySplit[0].'-'.$lineAsArray[$capKeys].'-'.$lineAsArray[$colKeys].'-'.str_replace('Grade ','',$lineAsArray[$graKeys])));
				
				
				while(isset($fullArrayFromCSV[$arrayKeySplit[0]][$lineIndex]) and $fullArrayFromCSV[$arrayKeySplit[0]][$lineIndex] != ''){
					$lineIndex = $lineIndex.'-bis';
				}
				$fullArrayFromCSV[$arrayKeySplit[0]][$lineIndex] = $lineAsArray;
				// var_dump($fullArrayFromCSV);die;
			}
			// if($count == 3)
				// var_dump($fullArrayFromCSV);die;
		}
		fclose($file);
		
		// die('klk');
		if(!empty($fullArrayFromCSV)){
					// var_dump($fullArrayFromCSV);die;
			foreach($fullArrayFromCSV as $ref => $prdtArray){
				$bestCombinsList = array();
				$combins = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
					SELECT 
						pa.`id_product_attribute`,
						pa.`reference` as sun_device_ref,
						pl.`name`as model,
						pa.`idp` as idp,
						pal.`name` as attribute
					FROM `sundev_product_attribute` pa
					INNER JOIN `sundev_product_lang` pl ON (pl.`id_product` = pa.`id_product`)
					INNER JOIN `sundev_product_attribute_combination` pac ON (pa.id_product_attribute = pac.id_product_attribute)
					LEFT JOIN `sundev_attribute_lang` pal ON (pac.id_attribute = pal.id_attribute)
					WHERE pl.id_lang = 1 AND pal.id_lang = 1 AND pa.`reference` LIKE "'.$ref.'-%" 
				');
				if(isset($combins) and !empty($combins)){
					$combinLine = array();
					$combinLine['reference_smarter']= '';
					$combinLine['reference_sun_device']= '';
					$combinLine['model'] 	= '';
					$combinLine['idp'] 		= '';
					$combinLine['capacity'] = '';
					$combinLine['color'] 	= '';
					$combinLine['grade'] 	= '';
					foreach($combins as $combin){
						$combinLine['id'] = $combin['id_product_attribute'];
						$combinLine['reference_sun_device'] = $combin['sun_device_ref'];
						$combinLine['model'] = $combin['model'];
						$combinLine['idp'] = $combin['idp'];
						if(in_array($combin['attribute'], array("A+","A","B","C","New"))){
							$combinLine['grade'] = $combin['attribute'];	
						}elseif((int)$combin['attribute'] > 0){
							$combinLine['capacity'] = (int)$combin['attribute'];	
						}else{
							$combinLine['color'] = $combin['attribute'];	
						}
						$bestCombinsList[$combin['sun_device_ref']] = $combinLine;
					}
					if(isset($prdtArray) and !empty($prdtArray) and !empty($bestCombinsList)){
						$savedKeys = array();
						foreach($bestCombinsList as $key => $bestCombin){
							$cuurentCombinIndex = str_replace(' ','_',strtolower($ref.'-'.$bestCombin['capacity'].'-'.$bestCombin['color'].'-'.$bestCombin['grade']));
							while(in_array($cuurentCombinIndex,$savedKeys)){
								$cuurentCombinIndex = $cuurentCombinIndex.'-bis';
							}
							if(isset($prdtArray[$cuurentCombinIndex])){
								
								var_dump('index = '.$cuurentCombinIndex);echo '<br>';
								// var_dump($prdtArray[$cuurentCombinIndex]);echo '<hr>';
								// var_dump($bestCombin);echo '<hr>';
								var_dump('idp = '.$prdtArray[$cuurentCombinIndex][$idpKeys]);echo '<br>';
								var_dump('reference = '.$prdtArray[$cuurentCombinIndex][0]);echo '<br>';
								var_dump('reference_smarter = '.$prdtArray[$cuurentCombinIndex][$smarterKeys]);echo '<br>';
								var_dump('WHERE `reference` = "'.$key);echo '<br><br><hr><hr><br>';
								
								Db::getInstance()->execute('
									UPDATE sundev_product_attribute
									SET 
										idp = "'.$prdtArray[$cuurentCombinIndex][$idpKeys].'", 
										reference_smarter = "'.$prdtArray[$cuurentCombinIndex][$smarterKeys].'"
									WHERE `id_product_attribute` = "'.$bestCombin['id'].'"; 
								');
							}
							$savedKeys[] = $cuurentCombinIndex;
						}
					}
					// var_dump($bestCombinsList);die;
				}
			}
		}
		exit();
	}

	public function importFloorPricesFromCSV($saveToo = false){
		$file = fopen(dirname(__FILE__).'/floor-prices-201811131949.csv', 'r');
		$fullArrayFromCSV = array();
		$count = 0;
		
		$idpKeys = 0;
		$priKeys = 6;
		
		while (($line = fgetcsv($file,4072, ';')) !== FALSE) {
			$count++;
			$lineAsArray = $line;
			if($count == 1 or !$lineAsArray[$idpKeys]){
				continue;
			}
			$floorPrice = floatval($lineAsArray[$priKeys]);
			$productIdp = $lineAsArray[$idpKeys];
			
			Db::getInstance()->execute('
				UPDATE sundev_product_attribute
				SET floor_price = '.$floorPrice.' 
				WHERE `idp` = "'.$productIdp.'";
			');
			
			echo ($count+' == floor_price = '.$floorPrice.' for `idp` = "'.$productIdp.'";<hr/><hr/>');
		}
		
		fclose($file);
		exit();
	}

    public function install()
    {
		$actionHooks = array('Attribute', 'AttributeGroup', 'Product', 'Manufacturer', 'Combination', 'Carrier', 'Tax', 'TaxRule', 'TaxRulesGroup', 'Currency');
        if (! parent::install() ||
                ! $this->registerHook('header') ||
                ! $this->registerHook('backOfficeHeader') ||
                ! $this->registerHook('displayHomeBlocklayeredcustom')
                /*! $this->registerHook('displayHome')||*/
				
				/*! $this->registerHook('actionObjectAttributeAddAfter') ||
                ! $this->registerHook('actionObjectAttributeUpdateAfter') ||
                ! $this->registerHook('actionObjectAttributeDeleteAfter') ||
				
				! $this->registerHook('actionObjectAttributeGroupAddAfter') ||
				! $this->registerHook('actionObjectAttributeGroupUpdateAfter') ||
                ! $this->registerHook('actionObjectAttributeGroupDeleteAfter') ||
				
                ! $this->registerHook('actionObjectProductAddAfter') ||
                ! $this->registerHook('actionObjectProductUpdateAfter') ||
                ! $this->registerHook('actionObjectProductDeleteAfter') ||
				
				! $this->registerHook('actionObjectManufacturerAddAfter') ||
				! $this->registerHook('actionObjectManufacturerUpdateAfter') ||
                ! $this->registerHook('actionObjectManufacturerDeleteAfter') ||
				
				! $this->registerHook('actionObjectCombinationAddAfter') ||
				! $this->registerHook('actionObjectCombinationUpdateAfter') ||
                ! $this->registerHook('actionObjectCombinationDeleteAfter') ||
				
				! $this->registerHook('actionObjectCarrierAddAfter') ||
				! $this->registerHook('actionObjectCarrierUpdateAfter') ||
                ! $this->registerHook('actionObjectCarrierDeleteAfter')*/) {
            return false;
        }
		foreach($actionHooks as $className){
			if(!$this->registerHook('actionObject'.$className.'AddAfter') || !$this->registerHook('actionObject'.$className.'UpdateAfter')||
			!$this->registerHook('actionObject'.$className.'DeleteAfter')){
				return false;
			}
		}
        require_once(dirname(__FILE__) . '/sql/install.php');
		BlcFilterBlock::insertBaseBlocks();
		BlcProductIndex::insertProductsIndex();
		Configuration::updateValue('BLC_USE_TAX_TO_FILTER_PRICE', 1);
		Configuration::updateValue('BLC_USE_ROUNDING_TO_FILTER_PRICE', 1);
		Configuration::updateValue('BLC_HIDE_FILTER_WHEN_NO_PRODUCT', 1);
		Configuration::updateValue('BLC_SHOW_PRODUCT_NUMBER', 1);
		Configuration::updateValue('BLC_DEFAULT_ADD_TO_CART_NUMBER', 0);
		Configuration::updateValue('BLC_PRICE_DEPRECATED', 1);
		Configuration::updateValue('BLC_PRICE_INDEX_AUTO_UPDATE', 1);
		Configuration::updateValue('BLC_MAX_PRODUCT_ATTRIBUTES_PER_LOAD', 10);
        return true;
    }

    public function uninstall()
    {
        if (! parent::uninstall()) {
            return false;
        }
        require_once(dirname(__FILE__) . '/sql/uninstall.php');
		Configuration::deleteByName('BLC_USE_TAX_TO_FILTER_PRICE');
		Configuration::deleteByName('BLC_USE_ROUNDING_TO_FILTER_PRICE');
		Configuration::deleteByName('BLC_HIDE_FILTER_WHEN_NO_PRODUCT');
		Configuration::deleteByName('BLC_SHOW_PRODUCT_NUMBER');
		Configuration::deleteByName('BLC_DEFAULT_ADD_TO_CART_NUMBER');
		Configuration::deleteByName('BLC_MAX_PRODUCT_ATTRIBUTES_PER_LOAD');
		Configuration::deleteByName('BLC_PRICE_DEPRECATED');
        return true;
    }
	
	public function ajaxProcessInitRegenerateAll()
    {
        $this->adminController->regenerationController->processInitRegenerateAll();
    }
	
	public function ajaxProcessInitRegenerate()
    {
        $this->adminController->regenerationController->processInitRegenerate();
    }
	
	public function ajaxProcessRegenerate()
    {
        $this->adminController->regenerationController->processRegenerate();
    }


    public function backToModuleHome($aditionalParameter = '')
    {
        Tools::redirectAdmin($this->getModuleHome() . $aditionalParameter);
    }

    public function getModuleHome()
    {
        return $this->context->link->getAdminLink('AdminModules') . '&configure=' . $this->name . '&module_name=' .
                 $this->name;
    }
	
	public function getRegeneratePriceLink()
    {
        return $this->getModuleHome().'&price_regeneration=1';
    }

    public function getContent()
    {
        return $this->adminController->init();
    }

    public function hookBackOfficeHeader()
    {
        return $this->adminController->includeBOCssJs();
    }

    public function hookHeader()
    {
		if($this->context->controller->php_self=='index'){
			$this->context->controller->addJQueryUI('ui.slider');
			$this->context->controller->addJS($this->_path . '/views/js/selectable_manager.js');
			$this->context->controller->addJS($this->_path . '/views/js/attribute_manager.js');
			$this->context->controller->addJS($this->_path . '/views/js/front.js');
			$this->context->controller->addCSS($this->_path . '/views/css/front.css');
		}
    }

    public function hookDisplayHome()
    {
        return $this->frontController->renderFilter();
    }
	public function hookDisplayHomeBlocklayeredcustom()
    {
        return $this->hookDisplayHome();
    }
	public function getUrl()
    {
        return Tools::getShopDomainSsl(true) . __PS_BASE_URI__ . 'modules/' . $this->name . '/';
    }
	
	public function smartyClearCache($template, $cache_id = null, $compile_id = null)
    {
        return $this->_clearCache($template, $cache_id, $compile_id);
    }

    public function smartyGetCacheId($name = null)
    {
        return $this->getCacheId($name);
    }

    public function clearAllTplCache()
    {
        $this->smartyClearCache('filter_block.tpl');
        $this->smartyClearCache('filter_center.tpl');
        $this->smartyClearCache('filter_left.tpl');
		
        $this->smartyClearCache('attribute_groups_list.tpl');
        $this->smartyClearCache('product_attributes_list.tpl');
    }
	
	public function onPriceImpacterChange($idProduct = 0)
    {
        $this->clearAllTplCache();
		BlcProductIndex::setDeprecated($idProduct);
    }
	
	public function hookActionObjectCarrierAddAfter($params)
    {
        $this->clearAllTplCache();
    }
	public function hookActionObjectCarrierUpdateAfter($params)
    {
        $this->clearAllTplCache();
    }
	public function hookActionObjectCarrierDeleteAfter($params)
    {
        $this->clearAllTplCache();
    }
	
	public function hookActionObjectProductAddAfter($params)
    {
		BlcProductIndex::addNew($params['object']->id);
        $this->onPriceImpacterChange($params['object']->id);
    }
	public function hookActionObjectProductUpdateAfter($params)
    {
        $this->onPriceImpacterChange($params['object']->id);
    }
	public function hookActionObjectProductDeleteAfter($params)
    {
        $this->clearAllTplCache();
    }
	
	public function hookActionObjectManufacturerAddAfter($params)
    {
        $this->clearAllTplCache();
    }
	public function hookActionObjectManufacturerUpdateAfter($params)
    {
        $this->clearAllTplCache();
    }
	public function hookActionObjectManufacturerDeleteAfter($params)
    {
        $this->clearAllTplCache();
    }
	
	public function hookActionObjectCombinationAddAfter($params)
    {
        $this->onPriceImpacterChange($params['object']->id_product);
    }
	public function hookActionObjectCombinationUpdateAfter($params)
    {
        $this->onPriceImpacterChange($params['object']->id_product);
    }
	public function hookActionObjectCombinationDeleteAfter($params)
    {
        $this->clearAllTplCache();
    }
	
	public function hookActionObjectAttributeAddAfter($params)
    {
        $this->clearAllTplCache();
    }
	public function hookActionObjectAttributeUpdateAfter($params)
    {
        $this->clearAllTplCache();
    }
	public function hookActionObjectAttributeDeleteAfter($params)
    {
        $this->clearAllTplCache();
    }
	
	public function hookActionObjectAttributeGroupAddAfter($params)
    {
        $this->clearAllTplCache();
    }
	public function hookActionObjectAttributeGroupUpdateAfter($params)
    {
        $this->clearAllTplCache();
    }
	public function hookActionObjectAttributeGroupDeleteAfter($params)
    {
        $this->clearAllTplCache();
    }
	
	public function hookActionObjectTaxAddAfter($params)
    {
        $this->onPriceImpacterChange();
    }
	public function hookActionObjectTaxUpdateAfter($params)
    {
        $this->onPriceImpacterChange();
    }
	public function hookActionObjectTaxDeleteAfter($params)
    {
        $this->onPriceImpacterChange();
    }
	
	public function hookActionObjectTaxRuleAddAfter($params)
    {
        $this->onPriceImpacterChange();
    }
	public function hookActionObjectTaxRuleUpdateAfter($params)
    {
        $this->onPriceImpacterChange();
    }
	public function hookActionObjectTaxRuleDeleteAfter($params)
    {
        $this->onPriceImpacterChange();
    }
	
	public function hookActionObjectTaxRulesGroupAddAfter($params)
    {
        $this->onPriceImpacterChange();
    }
	public function hookActionObjectTaxRulesGroupUpdateAfter($params)
    {
        $this->onPriceImpacterChange();
    }
	public function hookActionObjectTaxRulesGroupDeleteAfter($params)
    {
        $this->clearAllTplCache();
    }
	
	public function hookActionObjectCurrencyAddAfter($params)
    {
        $this->onPriceImpacterChange();
    }
	public function hookActionObjectCurrencyUpdateAfter($params)
    {
        $this->onPriceImpacterChange();
    }
	public function hookActionObjectCurrencyDeleteAfter($params)
    {
        $this->clearAllTplCache();
    }
}
