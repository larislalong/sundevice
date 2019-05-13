<?php
/**
* 2007-2019 PrestaShop
*
* NOTICE OF LICENSE
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2019 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

error_reporting(E_ALL);
ini_set('display_errors', '1');

require(dirname(__FILE__).'/../../config/config.inc.php');

if (!defined('_PS_VERSION_')) {
    exit;
}

class IsoCronOrdersExport
{
	public $exportConfigs;
	public $default_configs;
    public function __construct()
    {
		// $module = new Isoordersxmlexport();
        $this->default_configs = array(
			'sendviaftp' 	=> false,
			'ftp_host' 		=> '',
			'ftp_login' 	=> '',
			'ftp_password' 	=> '',
			'ftp_sslcon' 	=> false,
			'ftp_dirpath' 	=> '/',
			'ftp_port' 		=> '21',
			'last_exec_date'=> date('Y-m-d H:i:s', strtotime('-1 days')),
			'last_file_numb'=> 0,
			'cat_in_store_2'=> '',
		);
		// var_dump($module->getConfigFormValues());die;
		if(Configuration::get('ISOORDERSXMLEXPORT_MOD_CONFIG')){
			$this->exportConfigs = unserialize(Configuration::get('ISOORDERSXMLEXPORT_MOD_CONFIG', serialize($this->default_configs)));
			// var_dump($this->exportConfigs);die;
			$this->postExportOrders();
		}else{
			die('Erreur: Les configurations du module ne peuvent être récupérées.'); 
		}
		
    }
	
	public function sendFileViaFtp($source_path,$filename){
		if ((int)$this->exportConfigs['sendviaftp']) {
			$server = $this->exportConfigs['ftp_host'];
			$user = $this->exportConfigs['ftp_login'];
			$pwd = $this->exportConfigs['ftp_password'];
			$port = $this->exportConfigs['ftp_port'];
			$sslcon = $this->exportConfigs['ftp_sslcon'];
			$path = $this->exportConfigs['ftp_dirpath'];
			
			if ($sslcon && !function_exists('ftp_ssl_connect')) {
				$sslcon = 0;
			}
			if ($sslcon) {
				if (!$conn_id = @ftp_ssl_connect($server, $port)) {
					die('Failed to connect to SFTP ');
				}
			} elseif (!$conn_id = ftp_connect($server, $port)) {
				die('Failed to connect to FTP ');
			}

			// Identification avec un nom d'utilisateur et un mot de passe
			if (!ftp_login($conn_id, $user, $pwd)) {
				die('Failed to log in to FTP ');
			}

			ftp_pasv($conn_id, true);
			
			if (!is_dir("ftp://$user:$pwd@$server".$path)){
				ftp_mkdir($conn_id, $path);
			}

			if (!ftp_put($conn_id, $path.$filename, $source_path.$filename, FTP_ASCII)) {
				die("An error occured while loading file $filename\n");
			}

			// Fermeture de la connexion
			ftp_close($conn_id);
		}
	}
	
    public function postExportOrders()
    {
		$where_filter = array();
		
		$mag2cats   = $this->exportConfigs['cat_in_store_2'];
		$mag2catsArray = explode(',',$mag2cats);
		
		$date_start = $this->exportConfigs['last_exec_date'];
		$this->exportConfigs['last_exec_date'] = date('Y-m-d H:i:s');
		$where_filter[] = ' o.date_add >= "'.$date_start.'" ';
		
		// $limit_date  = date('Y-m-d 00:00:00', strtotime('-1 day'));
		
		$sql = 'SELECT o.* FROM ' . _DB_PREFIX_ . 'orders o
			'.(count($where_filter)?' WHERE '.implode(' AND ',$where_filter):'').' ';
		
		$orders = Db::getInstance()->executeS($sql);
		// var_dump($sql);die;
		
		$orderForXml = array();
		if(isset($orders) and !empty($orders)){
			foreach($orders as $order){
				$productsXmlArray = array('001'=>'','002'=>'');
				$ordersXml = '<?xml version="1.0" encoding="utf-8"?>';
				$ordersXml .= '	<DSTPV_Albarans xmlns="http://tempuri.org/DataSet1.xsd">';
				$ordersXml .= '		<TPV_Albarans>';
				
				$orderObj = new Order($order['id_order']);
				$orderCustomer = $orderObj->getCustomer();
				$addressObj = new Address($orderObj->id_address_delivery);
				$currencyObj = new Currency($order['id_currency']);
				$countryObj = new Country($addressObj->id_country);
				$orderForXml = array(
					'magatzem' 				=> '#|#',
					'Codi' 					=> $order['reference'],
					// 'Codi' 					=> str_pad($order['id_order'],10,'0',STR_PAD_LEFT),
					// 'Data' 					=> date('c',strtotime($order['date_add'])),
					// 'Hora' 					=> date('c',strtotime($order['date_add'])),
					'DataHora' 				=> strtotime($order['date_add']),
					'CodiClient' 			=> $orderCustomer->id,
					'ClientNom' 			=> trim($orderCustomer->firstname.' '.$orderCustomer->lastname),
					'ClientNomComercial'	=> trim($orderCustomer->company)?trim($orderCustomer->company):trim($orderCustomer->firstname.' '.$orderCustomer->lastname),
					'ClientDireccio' 		=> trim($addressObj->address1.' '.$addressObj->address2),
					'ClientCodiPais' 		=> $countryObj->iso_code,
					'ClientNomPais' 		=> $addressObj->country,
					'ClientCodiPostal' 		=> $addressObj->postcode,
					'ClientPoblacio' 		=> $addressObj->city,
					'ClientCIF' 			=> '',
					'ClientTelefon1' 		=> $addressObj->phone?$addressObj->phone:$addressObj->phone_mobile,
					'ClientEMail' 			=> $orderCustomer->email,
					'ClientCodiProvincia' 	=> '',
					'ClientProvincia' 		=> '',
					'CodiDivisa' 			=> $currencyObj->iso_code_num,
					'Total' 				=> number_format($order['total_products'],2,',',''),
					'TotalDivEmpresa' 		=> '',
					'FormaPagament' 		=> $order['payment'],
					'DataPagament' 			=> date('d/m/Y H:i:s',strtotime($order['invoice_date'])),
					'ImportPagat' 			=> number_format($order['total_paid'],2,',',''),
					'ImportPagatDivEmp' 	=> '',
				);
				foreach($orderForXml as $key => $entry){
					$ordersXml .= '		<'.$key.'>'.$entry.'</'.$key.'>';
				}
				$ordersXml .= '		</TPV_Albarans>';
				
				$orderDetails = $orderObj->getProductsDetail();
				if(isset($orderDetails) and !empty($orderDetails)){
					$count001 = 1;
					$count002 = 1;
					$productsXml = array();
					$ordersXmlTmp = '';
					foreach($orderDetails as $detail){
						$ordersXmlTmp = '		<TPV_Albarans_Linies>';
						$productsXml = array(
							// 'Codi' 				=> $detail['product_id'],
							'Codi' 				=> $orderForXml['Codi'],
							'Linia' 			=> '-'.(in_array($detail['id_category_default'],$mag2catsArray)?$count002++:$count001++),
							'CodiArticle' 		=> $detail['product_reference'],
							'ArticleNom' 		=> trim($detail['product_name']),
							'Quantitat' 		=> (int)$detail['product_quantity'],
							'PreuBase' 			=> number_format($detail['unit_price_tax_excl'],2,',',''),
							'PreuNetIva' 		=> number_format($detail['unit_price_tax_incl'],2,',',''),
							'ImportSenseIva' 	=> number_format($detail['unit_price_tax_excl'],2,',',''),
							'PercentageIVA' 	=> number_format($detail['tax_rate'],2,',',''),
							'Import' 			=> number_format($detail['total_price_tax_incl'],2,',',''),
							'ImportDivEmpresa' 	=> number_format($detail['total_price_tax_incl'],2,',',''),
						);
						foreach($productsXml as $key => $entry){
							$ordersXmlTmp .= '			<'.$key.'>'.$entry.'</'.$key.'>';
						}
						
						if(in_array($detail['id_category_default'],$mag2catsArray)){
							// $ordersXmlTmp 				.= '			<magatzem>002</magatzem>';
							$productsXmlArray['002'] 	.= $ordersXmlTmp;
							$productsXmlArray['002'] 	.= '		</TPV_Albarans_Linies>';
						}else{
							// $ordersXmlTmp 				.= '			<magatzem>001</magatzem>';
							$productsXmlArray['001'] 	.= $ordersXmlTmp;
							$productsXmlArray['001'] 	.= '		</TPV_Albarans_Linies>';
						}
					}
				}
				
		
				if($productsXmlArray['001']){
					$productsXmlArray['001'] = str_replace('#|#','001',$ordersXml).$productsXmlArray['001'].'	</DSTPV_Albarans>';
					$filename = '/WEBtoG2_Venda_'.str_pad(++$this->exportConfigs['last_file_numb'],10,'0',STR_PAD_LEFT).'.xml';
					$this->generateXmlFile($productsXmlArray['001'],$filename);
					// var_dump($productsXmlArray['001']);echo '<hr><hr>';
				}
				
				if($productsXmlArray['002']){
					$productsXmlArray['002'] = str_replace('#|#','002',$ordersXml).$productsXmlArray['002'].'	</DSTPV_Albarans>';
					$filename = '/WEBtoG2_Venda_'.str_pad(++$this->exportConfigs['last_file_numb'],10,'0',STR_PAD_LEFT).'.xml';
					$this->generateXmlFile($productsXmlArray['002'],$filename);
					// var_dump($productsXmlArray['002']);echo '<hr><hr>';
				}
			}
			Configuration::updateValue('ISOORDERSXMLEXPORT_MOD_CONFIG', serialize($this->exportConfigs));
		}
		echo '<hr/><hr/><p style="font-weight:bold;text-align:center;">Export terminé!!</p>';
		exit();
    }
	
	public function generateXmlFile($ordersXml,$filename){
		$targetDir = _PS_ROOT_DIR_.'/order-xml-exports';
		if(!is_dir($targetDir)){
			@mkdir($targetDir);
		}
		
		$currentFilename = $targetDir.$filename;
		
		$dom = new DOMDocument;
		$dom->preserveWhiteSpace = FALSE;
		$dom->loadXML($ordersXml);

		//Save XML as a file
		if($dom->save($currentFilename)){
			$this->sendFileViaFtp($targetDir.'/',$filename);
			echo '<p style="color:green;">Fichier '.$currentFilename.' ajouté.</p><hr/>';
		}else
			echo '<p style="color:red;">Fichier '.$currentFilename.' echec ajout.</p><hr/>';
		// var_dump($dom->save($currentFilename));die;
		
		// die('test terminé');
	}
}

new IsoCronOrdersExport();
exit();
