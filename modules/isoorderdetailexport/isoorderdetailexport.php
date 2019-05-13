<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!defined('_PS_VERSION_')) {
    exit;
}
require_once 'vendor/autoload.php';
include_once('xlsxwriter.class.php');
// require_once("classes/PHPMailer.php");

class Isoorderdetailexport extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'isoorderdetailexport';
        $this->tab = 'export';
        $this->version = '1.0.0';
        $this->author = 'isoMora';
        $this->need_instance = 1;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Order Detail Export');
        $this->description = $this->l('Module d\'export des détails des commandes avec filtres');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }
	
    /**
     * @param string $className
     * @param string $menuName
     * @param int    $active
     * @return int
     */
    public function installTab($className, $menuName, $active)
    {
        $tab = new Tab();
        $tab->active = (int) $active;
        $tab->name = array();
        $tab->class_name = pSQL($className);
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] =
                isset($menuName[$lang['iso_code']]) ? pSQL($menuName[$lang['iso_code']]) : pSQL($menuName['en']);
        }
        $tab->id_parent = (int) Tab::getIdFromClassName('AdminParentOrders');
        $tab->module = pSQL($this->name);

        return $tab->add();
    }

    /**
     * @param string $className
     * @return bool
     */
    public function uninstallTab($className)
    {
        $idTab = (int) Tab::getIdFromClassName($className);
        if ($idTab) {
            $tab = new Tab($idTab);

            return $tab->delete();
        }

        return true;
    }
	
    public function install()
    {
        Configuration::updateValue('ISOORDERDETAILEXPORT_ACCOUNT_EMAIL', 'contact@sun-device.com');
		
		$className = 'AdminIsoOrderDetailsExport';
		$menuName = array('en' => 'Orders export', 'fr' => 'Export de commandes');
		$this->installTab($className, $menuName, 1);
		
        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader');
    }

    public function uninstall()
    {
        Configuration::deleteByName('ISOORDERDETAILEXPORT_ACCOUNT_EMAIL');
		
		$className = 'AdminIsoOrderDetailsExport';
		$this->uninstallTab($className);
		
        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitIsoorderdetailexportModule')) == true) {
            $this->postProcess();
        }
		
        if (((bool)Tools::isSubmit('exportDetailsAndMail')) == true) {
            $this->postExportOrdersDetails('exportAndMail');
        }elseif (((bool)Tools::isSubmit('exportDetailsAndDownload')) == true) {
            $this->postExportOrdersDetails('exportAndDownload');
        }elseif (((bool)Tools::isSubmit('exportAndMail')) == true) {
            $this->postExportOrders('exportAndMail');
        }elseif (((bool)Tools::isSubmit('exportAndDownload')) == true) {
            $this->postExportOrders('exportAndDownload');
        }elseif (((bool)Tools::isSubmit('exportProductAndMail')) == true) {
            $this->exportCombinaisonsToCSV(1);
            $this->exportCombinaisonsToCSV(2);
        }

        $this->context->smarty->assign('module_dir', $this->_path);
        $this->context->smarty->assign('customersList', Customer::getCustomers(true));
        $this->context->smarty->assign('productsList', Product::getSimpleProducts(1));
		$attributesListResult = Attribute::getAttributes(1,1);
		$attributesList = array('grade'=>array(),'couleur'=>array(),'capacite'=>array());
		if(!empty($attributesListResult)){
			foreach($attributesListResult as $attr){
				switch($attr['id_attribute_group']){
					case 3:{
						$attributesList['couleur'][] = $attr;
						break;
					}
					case 4:{
						$attributesList['capacite'][] = $attr;
						break;
					}
					case 5:{
						$attributesList['grade'][] = $attr;
						break;
					}
				}
			}
		}
        $this->context->smarty->assign('attributesList', $attributesList);

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $this->renderForm().$output;
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitIsoorderdetailexportModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
						'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-envelope"></i>',
                        'desc' => $this->l('Entrer l\'adresse mail destinataire des mails de la tâche cron, laisser vide pour désactiver la fonction cette fonctionnalité.'),
                        'name' => 'ISOORDERDETAILEXPORT_ACCOUNT_EMAIL',
                        'label' => $this->l('Email'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'ISOORDERDETAILEXPORT_ACCOUNT_EMAIL' => Configuration::get('ISOORDERDETAILEXPORT_ACCOUNT_EMAIL', 'contact@sun-device.com'),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
		
		
    }

    /**
     * Export orders and download xls file.
     */
    protected function postExportOrdersDetails($type = 'download')
    {
		$user_filters = Tools::getValue('filter',array());
		$where_filter = array();
		$filter_string= array();
		
		if(isset($user_filters['date_start']) and $user_filters['date_start'] != ''){
			$where_filter[] = ' o.date_add >= "'.$user_filters['date_start'].' 00:00:00" ';
			$filter_string[] = 'Date de début: '.$user_filters['date_start'].'';
		}
		if(isset($user_filters['date_end']) and $user_filters['date_end'] != ''){
			$where_filter[] = ' o.date_add <= "'.$user_filters['date_end'].' 23:59:59" ';
			$filter_string[] = 'Date de fin: '.$user_filters['date_end'].'';
		}
		if(isset($user_filters['customers']) and !empty($user_filters['customers'])){
			$where_filter[] = ' g.id_customer IN ('.implode(',',$user_filters['customers']).') ';
		}
		if(isset($user_filters['products']) and !empty($user_filters['products'])){
			$where_filter[] = ' d.product_id IN ('.implode(',',$user_filters['products']).') ';
		}
		if(isset($user_filters['product_grade']) and !empty($user_filters['product_grade'])){
			$where_filter[] = ' d.product_attribute_id IN (SELECT id_product_attribute FROM sundev_product_attribute_combination WHERE id_attribute IN ('.implode(',',$user_filters['product_grade']).')) ';
		}
		if(isset($user_filters['product_color']) and !empty($user_filters['product_color'])){
			$where_filter[] = ' d.product_attribute_id IN (SELECT id_product_attribute FROM sundev_product_attribute_combination WHERE id_attribute IN ('.implode(',',$user_filters['product_color']).')) ';
		}
		if(isset($user_filters['product_capacity']) and !empty($user_filters['product_capacity'])){
			$where_filter[] = ' d.product_attribute_id IN (SELECT id_product_attribute FROM sundev_product_attribute_combination WHERE id_attribute IN ('.implode(',',$user_filters['product_capacity']).')) ';
		}
		
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
			GROUP BY gl.name, d.id_order, d.product_reference
			ORDER BY d.id_order DESC';
			
			// var_dump($sql);die;
			
		$odersDetails = Db::getInstance()->executeS($sql);
		
		// var_dump($applied_filter);echo '<br>';
		// var_dump($odersDetails);die;
		if($odersDetails){
			// $odersDetailsTmp = $odersDetails;
			// foreach($odersDetailsTmp as $detail){
				// var_dump($detail);echo '<hr/>';
			// }
			// die;
			switch($type){
				case 'exportAndMail': {
					$this->exportAndMail($odersDetails,$applied_filter);
					break;
				}
				default: {
					$this->exportAndDownload($odersDetails);
				}
			}
		}
    }
	
    protected function postExportOrders($type = 'download')
    {
		$user_filters = Tools::getValue('filter',array());
		$where_filter = array();
		$filter_string= array();
		
		if(isset($user_filters['date_start']) and $user_filters['date_start'] != ''){
			$where_filter[] = ' o.date_add >= "'.$user_filters['date_start'].' 00:00:00" ';
			$filter_string[] = 'Date de début: '.$user_filters['date_start'].'';
		}
		if(isset($user_filters['date_end']) and $user_filters['date_end'] != ''){
			$where_filter[] = ' o.date_add <= "'.$user_filters['date_end'].' 23:59:59" ';
			$filter_string[] = 'Date de fin: '.$user_filters['date_end'].'';
		}
		if(isset($user_filters['customers']) and !empty($user_filters['customers'])){
			$where_filter[] = ' g.id_customer IN ('.implode(',',$user_filters['customers']).') ';
		}
		if(isset($user_filters['products']) and !empty($user_filters['products'])){
			$where_filter[] = ' d.product_id IN ('.implode(',',$user_filters['products']).') ';
		}
		if(isset($user_filters['product_grade']) and !empty($user_filters['product_grade'])){
			$where_filter[] = ' d.product_attribute_id IN (SELECT id_product_attribute FROM sundev_product_attribute_combination WHERE id_attribute IN ('.implode(',',$user_filters['product_grade']).')) ';
		}
		if(isset($user_filters['product_color']) and !empty($user_filters['product_color'])){
			$where_filter[] = ' d.product_attribute_id IN (SELECT id_product_attribute FROM sundev_product_attribute_combination WHERE id_attribute IN ('.implode(',',$user_filters['product_color']).')) ';
		}
		if(isset($user_filters['product_capacity']) and !empty($user_filters['product_capacity'])){
			$where_filter[] = ' d.product_attribute_id IN (SELECT id_product_attribute FROM sundev_product_attribute_combination WHERE id_attribute IN ('.implode(',',$user_filters['product_capacity']).')) ';
		}
		
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
			GROUP BY o.id_order
			ORDER BY o.id_order DESC';
			
			// var_dump($sql);die;
			
		$odersDetails = Db::getInstance()->executeS($sql);
		
		// var_dump($applied_filter);echo '<br>';
		// var_dump($odersDetails);die;
		if($odersDetails){
			// $odersDetailsTmp = $odersDetails;
			// foreach($odersDetailsTmp as $detail){
				// var_dump($detail);echo '<hr/>';
			// }
			// die;
			switch($type){
				case 'exportAndMail': {
					$this->exportAndMail($odersDetails,$applied_filter,0);
					break;
				}
				default: {
					$this->exportAndDownload($odersDetails,0);
				}
			}
		}
    }

    /**
     * Export products and download xls file.
     */
    protected function postExportProductss($type = 'download')
    {
		$sql = 'SELECT d.id_order,o.reference AS order_reference, os.name AS order_state, d.product_name, d.product_reference, d.product_price, d.unit_price_tax_excl AS discount_price, d.product_quantity, s.quantity AS stock_quantity, o.payment, o.date_upd, o.date_add, CONCAT_WS(" ", g.lastname, g.firstname) AS customer, g.id_customer, CONCAT_WS(" ", ad.address1, ad.address2, "Code Postal:", ad.postcode, ad.city, ad.other, "Telephone: ", ad.phone, "Mobile: ", ad.phone_mobile) AS delevery_address, CONCAT_WS(" ", ai.address1, ai.address2,"Code postal:",  ai.postcode, ai.city, ai.other, "Telephone: ", ai.phone, "Mobile: ", ai.phone_mobile) AS invoice_address, gl.name AS group_name, s.quantity AS quantity_in_stock, g.email
			FROM sundev_order_detail d
			LEFT JOIN sundev_orders o ON (d.id_order = o.id_order)
			LEFT JOIN sundev_address ad ON (o.id_address_delivery = ad.id_address)
			LEFT JOIN sundev_address ai ON (o.id_address_invoice = ai.id_address)
			LEFT JOIN sundev_stock_available s ON (d.product_attribute_id = s.id_product_attribute)
			LEFT JOIN sundev_customer g ON (o.id_customer = g.id_customer)
			LEFT JOIN sundev_group_lang gl ON (g.id_default_group = gl.id_group) AND gl.name LIKE "client%"
			LEFT JOIN sundev_order_state_lang os ON (o.current_state = os.id_order_state)
			WHERE os.id_lang = 1 '.(count($where_filter)?' AND '.implode(' AND ',$where_filter):'').'
			GROUP BY gl.name, d.id_order, d.product_reference
			ORDER BY d.id_order DESC';
			
		$odersDetails = Db::getInstance()->executeS($sql);
		
		// var_dump($applied_filter);echo '<br>';
		// var_dump($odersDetails);die;
		if($odersDetails){
			// $odersDetailsTmp = $odersDetails;
			// foreach($odersDetailsTmp as $detail){
				// var_dump($detail);echo '<hr/>';
			// }
			// die;
			switch($type){
				case 'exportAndMail': {
					$this->exportAndMail($odersDetails,$applied_filter);
					break;
				}
				default: {
					$this->exportAndDownload($odersDetails);
				}
			}
		}
    }
	
	public function exportCombinaisonsToCSV($idShop){
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
				if(in_array($combin['attribute'], array("A+","A","B","C"))){
					$combinLine['grade'] = 'Grade '.$combin['attribute'];	
				}elseif((int)$combin['attribute'] > 0){
					$combinLine['capacity'] = (int)$combin['attribute'];	
				}else{
					$combinLine['color'] = $combin['attribute'];	
				}
				$bestCombinsList[$combin['reference_sundevice']] = $combinLine;
			}
		}
		
		if(isset($bestCombinsList) and !empty($bestCombinsList)){
			// $filename = 'sun-device-'.($idShop==2?'US':'EU').'-declinaisons_produits-'.date('Ymd-His').'.csv';
			// $delimiter = ';';
			// $f = fopen('php://memory', 'w');
			// $hasHeader = false;
			// foreach ($bestCombinsList as $line) { 
				// if(!$hasHeader){
					// fputcsv($f, array_keys($line), $delimiter);
					// $hasHeader = true;
				// }
				// fputcsv($f, $line, $delimiter); 
			// }
			// fseek($f, 0);
			// header('Content-Type: application/csv');
			// header('Content-Disposition: attachment; filename="'.$filename.'";');
			// fpassthru($f);
			
			// exit();
			
			$odersDetails = array_values($bestCombinsList);
		}
		
		$email_cible = Configuration::get('ISOORDERDETAILEXPORT_ACCOUNT_EMAIL', 'contact@sun-device.com');
		// $email_cible = 'larislalong@gmail';
		if($email_cible and isset($odersDetails) and !empty($odersDetails)){
			$targetDir = 'orders_exports';
			$filename = 'sun-device-'.($idShop==2?'US':'EU').'-declinaisons_produits-'.date('Ymd-His').'.xlsx';
			
			if(!is_dir($targetDir)){
				@mkdir($targetDir);
			}
			
			$writer = new XLSXWriter();
			$headers = $odersDetails[0];
			foreach($headers as $key => &$header){
				$integerArray = array('idp','capacity');
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
			// var_dump($fullPath,$email->Send(),$email->ErrorInfo);die;
			
			@unlink($fullPath);
		}
	}
	
	public function exportAndMail(array $odersDetails,$applied_filter,$show_details = true){
		$email_cible = Configuration::get('ISOORDERDETAILEXPORT_ACCOUNT_EMAIL', 'contact@sun-device.com');
		// $email_cible = 'larislalong@gmail';
		if($email_cible and isset($odersDetails) and !empty($odersDetails)){
			$targetDir = 'orders_exports';
			$filename = 'sun-device-commandes+details-'.date('Ymd-His').'.xlsx';
			if(!$show_details)
				$filename = 'sun-device-commandes-'.date('Ymd-His').'.xlsx';
			
			if(!is_dir($targetDir)){
				@mkdir($targetDir);
			}
			
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
			$email->Subject ='Export des détails de commandes du '.date('d/m/Y');
			if($applied_filter)
				$email->Body = 'Bonjour, trouvez ci-joint l\'export des détails de commandes passées sur sun device. Cordialement.';
			else
				$email->Body = 'Bonjour, trouvez ci-joint l\'export des détails de commandes passées sur sundevice avec les filtres suivant '.$applied_filter.'. Cordialement.';
			
			if(!$show_details){
				$email->Subject ='Export des commandes du '.date('d/m/Y');
				if($applied_filter)
					$email->Body = 'Bonjour, trouvez ci-joint l\'export des commandes passées sur sun device. Cordialement.';
				else
					$email->Body = 'Bonjour, trouvez ci-joint l\'export des commandes passées sur sundevice avec les filtres suivant '.$applied_filter.'. Cordialement.';
			}
			
			$email->AddAddress($email_cible);
			$email->AddCC('larislalong@gmail.com');

			$fullPath = $targetDir.'/'.$filename;
			$email->addAttachment($fullPath, $filename, 'base64', PHPMailer::_mime_types('xlsx'));
			$email->Send();
			
			@unlink($fullPath);
		}
	}
	
	public function exportAndDownload(array $odersDetails, $show_details = true){
		if(isset($odersDetails) and !empty($odersDetails)){
			$targetDir = 'orders_exports';
			$filename = 'sun-device-commandes+details-'.date('Ymd-His').'.xlsx';
			if(!$show_details)
				$filename = 'sun-device-commandes-'.date('Ymd-His').'.xlsx';
			
			if(!is_dir($targetDir)){
				@mkdir($targetDir);
			}
			
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
			
			header("Cache-Control: public");
			header("Content-Description: File Transfer");
			header("Content-Disposition: attachment; filename=".$targetDir.'/'.$filename);
			header("Content-Type: application/zip");
			header("Content-Transfer-Encoding: binary");
			readfile($targetDir.'/'.$filename);
			
			@unlink($targetDir.'/'.$filename);
		}
	}

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }
}
