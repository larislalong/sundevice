<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require(dirname(__FILE__).'/../../config/config.inc.php');

if (!defined('_PS_VERSION_')) {
    exit;
}
require_once 'vendor/autoload.php';
include_once('xlsxwriter.class.php');
// require_once("classes/PHPMailer.php");

class IsoCronExport
{
    public function __construct()
    {
		$this->postExportOrders(1);
		sleep(1);
		$this->postExportOrdersDetails(1);
		sleep(1);
		$this->exportCombinaisonsToCSV(1);
		sleep(1);
		$this->postExportOrders(2);
		sleep(1);
		$this->postExportOrdersDetails(2);
		sleep(1);
		$this->exportCombinaisonsToCSV(2);
    }

    /**
     * Export orders and download xls file.
     */
    protected function postExportOrders($id_shop = 1)
    {
		$user_filters = Tools::getValue('filter',array());
		$where_filter = array();
		$filter_string= array();
		
		$date_start = date('Y-m-d H:i:s', strtotime('-1 day'));
		$where_filter[] = ' o.date_add >= "'.$date_start.'" ';
		$filter_string[] = 'Date de début: '.$date_start.'';
		if($id_shop == 1)
			$filter_string[] = 'SUN DEVICE EU';
		else
			$filter_string[] = 'SUN DEVICE US';
		
		$applied_filter  = implode(', ',$filter_string);
		// $limit_date  = date('Y-m-d 00:00:00', strtotime('-1 day'));
		$sql = 'SELECT CASE WHEN o.id_shop = 1 THEN "EU" ELSE "US" END AS Shop, o.id_order,o.reference AS order_reference, os.name AS order_state, 
			
			o.total_products AS total_order_amount,o.total_discounts AS total_order_disount,o.total_paid AS total_order_paid,o.total_shipping AS total_order_shipping,
			
			o.payment, CONCAT_WS(" ", g.lastname, g.firstname) AS customer, CONCAT_WS(" ", ad.address1, ad.address2, "Code Postal:", ad.postcode, ad.city, ad.other, "Telephone: ", ad.phone, "Mobile: ", ad.phone_mobile) AS delevery_address, CONCAT_WS(" ", ai.address1, ai.address2,"Code postal:",  ai.postcode, ai.city, ai.other, "Telephone: ", ai.phone, "Mobile: ", ai.phone_mobile) AS invoice_address, gl.name AS group_name, g.email, o.date_upd AS order_date_upd, o.date_add AS order_date_add, g.commercial
			FROM sundev_orders o
			LEFT JOIN sundev_address ad ON (o.id_address_delivery = ad.id_address)
			LEFT JOIN sundev_address ai ON (o.id_address_invoice = ai.id_address)
			LEFT JOIN sundev_customer g ON (o.id_customer = g.id_customer)
			LEFT JOIN sundev_group_lang gl ON (g.id_default_group = gl.id_group) AND gl.name LIKE "client%"
			LEFT JOIN sundev_order_state_lang os ON (o.current_state = os.id_order_state)
			WHERE os.id_lang = 1 '.(count($where_filter)?' AND '.implode(' AND ',$where_filter):'').'
			'.(isset($id_shop)?' AND o.id_shop = '.(int)($id_shop):'').'
			GROUP BY o.id_order
			ORDER BY o.id_order DESC';
			// var_dump($sql);die;
		$odersDetails = Db::getInstance()->executeS($sql);
		
		if($odersDetails){
			$this->exportAndMail($odersDetails,$applied_filter,$id_shop,false);
		}
    }
	
    protected function postExportOrdersDetails($id_shop = 1)
    {
		$user_filters = Tools::getValue('filter',array());
		$where_filter = array();
		$filter_string= array();
		
		$date_start = date('Y-m-d H:i:s', strtotime('-1 day'));
		$where_filter[] = ' o.date_add >= "'.$date_start.'" ';
		$filter_string[] = 'Date de début: '.$date_start.'';
		if($id_shop == 1)
			$filter_string[] = 'SUN DEVICE EU';
		else
			$filter_string[] = 'SUN DEVICE US';
		
		$applied_filter  = implode(', ',$filter_string);
		// $limit_date  = date('Y-m-d 00:00:00', strtotime('-1 day'));
		$sql = 'SELECT CASE WHEN o.id_shop = 1 THEN "EU" ELSE "US" END AS Shop, d.id_order,o.reference AS order_reference, os.name AS order_state, d.product_name, d.product_reference, d.product_price AS price, d.floor_price, d.unit_price_tax_incl AS discount, (d.product_price - d.unit_price_tax_incl) AS MontantRemise, d.product_quantity, d.total_price_tax_incl AS TotalVente, (d.total_price_tax_incl - (d.floor_price*d.product_quantity)) AS Marge, s.quantity AS stock_quantity, o.payment, o.date_upd, o.date_add, CONCAT_WS(" ", g.lastname, g.firstname) AS customer, g.id_customer, CONCAT_WS(" ", ad.address1, ad.address2, "Code Postal:", ad.postcode, ad.city, ad.other, "Telephone: ", ad.phone, "Mobile: ", ad.phone_mobile) AS delevery_address, CONCAT_WS(" ", ai.address1, ai.address2,"Code postal:",  ai.postcode, ai.city, ai.other, "Telephone: ", ai.phone, "Mobile: ", ai.phone_mobile) AS invoice_address, gl.name AS group_name, g.email, g.commercial
			FROM sundev_order_detail d
			LEFT JOIN sundev_orders o ON (d.id_order = o.id_order)
			LEFT JOIN sundev_address ad ON (o.id_address_delivery = ad.id_address)
			LEFT JOIN sundev_address ai ON (o.id_address_invoice = ai.id_address)
			LEFT JOIN sundev_stock_available s ON (d.product_attribute_id = s.id_product_attribute)
			LEFT JOIN sundev_product_attribute pa ON (d.product_attribute_id = pa.id_product_attribute)
			LEFT JOIN sundev_customer g ON (o.id_customer = g.id_customer)
			LEFT JOIN sundev_group_lang gl ON (g.id_default_group = gl.id_group) AND gl.name LIKE "client%"
			LEFT JOIN sundev_order_state_lang os ON (o.current_state = os.id_order_state)
			WHERE os.id_lang = 1 '.(count($where_filter)?' AND '.implode(' AND ',$where_filter):'').'
			'.(isset($id_shop)?' AND o.id_shop = '.(int)($id_shop):'').'
			GROUP BY gl.name, d.id_order, d.product_reference
			ORDER BY d.id_order DESC';
			// var_dump($sql);die;
		$odersDetails = Db::getInstance()->executeS($sql);
		
		if($odersDetails){
			$this->exportAndMail($odersDetails,$applied_filter,$id_shop);
		}
    }
	
	public function exportAndMail(array $odersDetails,$applied_filter,$id_shop = 1, $show_details = true){
		$email_cible = 'mobilcom.eric@gmail.com';
		// $email_cible = 'larislalong@hotmail.com';
		if($email_cible and isset($odersDetails) and !empty($odersDetails)){
			$targetDir = 'orders_exports';
			if(!is_dir($targetDir)){
				@mkdir($targetDir);
			}
			
			if($show_details)
				$filename_surfix = 'commandes+details-';
			else
				$filename_surfix = 'commandes-';
			
			if($id_shop == 1)
				$filename = 'sun-device-eu-'.$filename_surfix.date('Ymd-His').'.xlsx';
			else
				$filename = 'sun-device-us-'.$filename_surfix.date('Ymd-His').'.xlsx';
			
			$writer = new XLSXWriter();
			$headers = $odersDetails[0];
			foreach($headers as $key => &$header){
				$integerArray = array('id_order','product_quantity','id_customer','quantity_in_stock','stock_quantity');
				$priceArray = array('product_price','unit_price_tax_incl','total_price_tax_incl','discount_price','base_price','reduced_price','price','wholesale_price','wholesale_total_price','unit_price_diff','total_price_diff','floor_price','total_floor_price','total_shipping','total_products','total_order_disocunt','total_order_paid','total_order_amount','total_order_disount','total_order_shipping','MontantRemise','TotalVente','Marge','discount');
				if(in_array($key,$integerArray)){
					$header = 'integer';
				}elseif(in_array($key,$priceArray)){
					$header = 'price';
				}else{
					$header = 'string';
				}
			}
			$writer->writeSheetHeader('Sheet1', $headers);
			foreach ($odersDetails as $line) { 
				$writer->writeSheetRow('Sheet1', $line);
			}
			$writer->writeToFile($targetDir.'/'.$filename);
			
			$email = new PHPMailer();
			$email->CharSet = PHPMailer::CHARSET_UTF8;
			$email->From = 'admin@sun-device.com';
			$email->addReplyTo('no-reply@sun-device.com');
			$email->FromName = 'Admin Sun Device';
			if($id_shop == 1)
				$email->Subject = 'SUN DEVICE EU - ';
			else
				$email->Subject = 'SUN DEVICE US - ';
			
			if($show_details){
				$email->Subject .='Export des détails de commandes du '.date('d/m/Y H:i:s');
				if(!$applied_filter)
					$email->Body = 'Bonjour, trouvez ci-joint l\'export des détails de commandes passé sur sun device. Cordialement.';
				else
					$email->Body = 'Bonjour, trouvez ci-joint l\'export des détails de commandes passé sur sundevice avec les filtres suivant '.$applied_filter.'. Cordialement.';
			}else{
				$email->Subject .='Export des commandes du '.date('d/m/Y H:i:s');
				if(!$applied_filter)
					$email->Body = 'Bonjour, trouvez ci-joint l\'export des commandes passé sur sun device. Cordialement.';
				else
					$email->Body = 'Bonjour, trouvez ci-joint l\'export des commandes passé sur sundevice avec les filtres suivant '.$applied_filter.'. Cordialement.';
			}
			
			$email->AddAddress($email_cible);
			$email->AddCC('larislalong@gmail.com');

			$fullPath = $targetDir.'/'.$filename;
			$email->addAttachment($fullPath, $filename, 'base64', PHPMailer::_mime_types('xlsx'));
			if($email->Send()){
				echo '<div style="color:green;">'.$email->Subject.' : <b>Mail envoyé</b></div><hr/><hr/>';
			}else{
				echo '<div style="color:red;">'.$email->Subject.' : <b>Mail envoyé</b></div><hr/><hr/>';
			}
			
			@unlink($fullPath);
		}
	}
	
	public function exportCombinaisonsToCSV($idShop){
		$idShop = ($idShop == 2)?2:1;
		$combins = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT 
				pa.`id_product_attribute`,
				pa.`reference` as reference_sundevice,
				pa.`reference_smarter`,
				pl.`name`as model,
				pa.`idp` as idp,
				pas.`price`,
				pas.id_shop,
				IF(pas.id_shop = 1, "EU", "US") AS shop
			FROM `sundev_product_attribute` pa
			INNER JOIN `sundev_product_lang` pl ON (pl.`id_product` = pa.`id_product`)
			INNER JOIN `sundev_product_attribute_shop` pas ON (pa.id_product_attribute = pas.id_product_attribute)
			WHERE pl.id_lang = 1 AND pas.id_shop = '.$idShop.'
			GROUP BY pa.`idp`
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
				$combinLine['stock'] 	= 0;
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
				
				$attribs = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
					SELECT al.name 
					FROM `sundev_product_attribute_combination` pac 
					INNER JOIN `sundev_attribute_lang` al ON (pac.id_attribute = al.id_attribute)
					WHERE al.id_lang = 1 AND `id_product_attribute` = '.(int)$combin['id_product_attribute'].'
				');
				if(isset($attribs) and !empty($attribs)){
					foreach($attribs as $attrib){
				// var_dump($attrib);die;
						if(in_array($attrib['name'], array("A+","A","B","C"))){
							$combinLine['grade'] = 'Grade '.$attrib['name'];	
						}elseif((int)$attrib['name'] > 0){
							$combinLine['capacity'] = (int)$attrib['name'];	
						}else{
							$combinLine['color'] = $attrib['name'];	
						}
					}
				}
				$stock = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
					SELECT quantity 
					FROM `sundev_stock_available` 
					WHERE `id_product_attribute` = '.(int)$combin['id_product_attribute'].'
					AND id_shop = '.$idShop.'
				');
				
				$combinLine['stock'] = isset($stock['quantity']) ? (int)$stock['quantity'] : 0;
				
				$bestCombinsList[$combinLine['idp']] = $combinLine;
			}
		}
		
		// var_dump($bestCombinsList[33316]);die;
		if(isset($bestCombinsList) and !empty($bestCombinsList)){
			$odersDetails = array_values($bestCombinsList);
		}
		
		// $email_cible = Configuration::get('ISOORDERDETAILEXPORT_ACCOUNT_EMAIL', 'contact@sun-device.com');
		$email_cible = 'mobilcom.eric@gmail.com';
		// $email_cible = 'larislalong@gmail.com';
		if($email_cible and isset($odersDetails) and !empty($odersDetails)){
			$targetDir = 'orders_exports';
			$filename = 'sun-device-'.($idShop==2?'US':'EU').'-declinaisons_produits-'.date('Ymd-His').'.xlsx';
			
			if(!is_dir($targetDir)){
				@mkdir($targetDir);
			}
			
			$writer = new XLSXWriter();
			$headers = $odersDetails[0];
			foreach($headers as $key => &$header){
				$integerArray = array('idp','capacity','stock');
				$priceArray = array('price');
				if(in_array($key,$integerArray)){
					$header = 'integer';
				}elseif(in_array($key,$priceArray)){
					$header = 'price';
				}else{
					$header = 'string';
				}
			}
			// var_dump($headers);die;
			$writer->writeSheetHeader('Sheet1', $headers);
			foreach ($odersDetails as $line) { 
				$writer->writeSheetRow('Sheet1', $line);
			}
			$writer->writeToFile($targetDir.'/'.$filename);
			
			$email = new PHPMailer();
			$email->CharSet = PHPMailer::CHARSET_UTF8;
			$email->From = 'admin@sun-device.com';
			$email->addReplyTo('no-reply@sun-device.com');
			$email->FromName = 'Admin Sun Device '.($idShop==2?'US':'EU').'';
			$email->Subject ='Sun Device '.($idShop==2?'US':'EU').' - Export produits du '.date('d/m/Y');
			$email->Body = 'Bonjour, trouvez ci-joint l\'export des produits actuellement présents sur  sun device '.($idShop==2?'US':'EU').'. Cordialement.';
			$email->AddAddress($email_cible);
			$email->AddCC('larislalong@gmail.com');

			$fullPath = $targetDir.'/'.$filename;
			$email->addAttachment($fullPath, $filename, 'base64', PHPMailer::_mime_types('xlsx'));
			if($email->Send()){
				echo '<div style="color:green;">'.$email->Subject.' : <b>Mail envoyé</b></div><hr/><hr/>';
			}else{
				echo '<div style="color:red;">'.$email->Subject.' : <b>Mail envoyé</b></div><hr/><hr/>';
			}
			// var_dump($fullPath,$email->Send(),$email->ErrorInfo);die;
			
			@unlink($fullPath);
		}
	}
	
}

new IsoCronExport();
exit();