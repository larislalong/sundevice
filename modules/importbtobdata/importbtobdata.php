<?php
/**
* 2007-2018 PrestaShop
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}
@ini_set('max_execution_time', 0);
@ini_set('max_input_time', -1);

require_once dirname(__FILE__) . '/controllers/admin/importdataController.php';
require_once dirname(__FILE__) . '/classes/ImeiUpdate.php';
class Importbtobdata extends Module
{
    protected $config_form = false;
    protected static $current_line = 0;

    public function __construct()
    {
        $this->name = 'importbtobdata';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'FOZEU TAKOUDJOU Francis André';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Update B2B data in shop');
        $this->description = $this->l('this module help us to update data on shop from file send');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => '1.7');
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {

        return parent::install() &&
            $this->registerHook('actionUpdateMoreInfos') && 
            $this->registerHook('displayMoreProductList') && 
            $this->registerHook('displayMoreProductFilter') && 
            $this->registerHook('actionOrderStatusPostUpdate') && 
            $this->registerHook('displayMoreProduct');
    }

    public function uninstall()
    {
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
        if (((bool)Tools::isSubmit('submitImportbtobdataModule')) == true) {
            $this->postProcess();
        }
        $this->registerHook('actionUpdateMoreInfos');
        $this->registerHook('actionOrderStatusPostUpdate');

        $this->context->smarty->assign('module_dir', $this->_path);
        
        $output = date('d-m-Y H:i:s').'<p> CRON general import : */15 * * * * flock -n /tmp/importbtobdata.cronimport.lockfile php -q '.dirname(__FILE__).DIRECTORY_SEPARATOR.'cronimport.php</p>';
        $output .= date('d-m-Y H:i:s').'<p> CRON general upate infos to display : */10 * * * * flock -n /tmp/importbtobdata.cronimport.cronuserinfos php -q '.dirname(__FILE__).DIRECTORY_SEPARATOR.'cronuserinfos.php</p>';
        $output .= date('d-m-Y H:i:s').'<p> CRON general upate infos to display : */12 * * * * flock -n /tmp/importbtobdata.cronimport.cronupdateimei php -q '.dirname(__FILE__).DIRECTORY_SEPARATOR.'cronupdateimei.php</p>';

        $output .= $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');
		Tools::clearSmartyCache();

        return $output.$this->renderForm();
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
        $helper->submit_action = 'submitImportbtobdataModule';
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
                        'type' => 'switch',
                        'label' => $this->l('Update data'),
                        'name' => 'IMPORTBTOBDATA_UPDATE',
                        'is_bool' => true,
                        'desc' => $this->l('Update data in database'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
					array(
                        'type' => 'switch',
                        'label' => $this->l('Update more infos'),
                        'name' => 'IMPORTBTOBDATA_UPDATEMORE',
                        'is_bool' => true,
                        'desc' => $this->l('Update additional data in database'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'desc' => $this->l('ID default employee who use to sychronize stock'),
                        'name' => 'IMPORTBTOBDATA_EMPLOYEE_ID',
                        'label' => $this->l('ID'),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'desc' => $this->l('ID currency to reset exchange rate'),
                        'name' => 'IMPORTBTOBDATA_CURRENCY_ID',
                        'label' => $this->l('ID CURRENCY'),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'desc' => $this->l('ID customer group to display more information'),
                        'name' => 'IMPORTBTOBDATA_GROUP_ID',
                        'label' => $this->l('ID CUSTOMER GROUP'),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'desc' => $this->l('Path file'),
                        'name' => 'IMPORTBTOBDATA_FILE',
                        'label' => $this->l('Path file'),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'desc' => $this->l('Link file to update customer infos'),
                        'name' => 'IMPORTBTOBDATA_CUSTOMER_FILE',
                        'label' => $this->l('Path file'),
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
            'IMPORTBTOBDATA_UPDATE' => Configuration::get('IMPORTBTOBDATA_UPDATE'),
            'IMPORTBTOBDATA_FILE' => Configuration::get('IMPORTBTOBDATA_FILE', ''),
            'IMPORTBTOBDATA_EMPLOYEE_ID' => Configuration::get('IMPORTBTOBDATA_EMPLOYEE_ID', ''),
            'IMPORTBTOBDATA_CURRENCY_ID' => Configuration::get('IMPORTBTOBDATA_CURRENCY_ID', ''),
            'IMPORTBTOBDATA_GROUP_ID' => Configuration::get('IMPORTBTOBDATA_GROUP_ID', ''),
            'IMPORTBTOBDATA_CUSTOMER_FILE' => Configuration::get('IMPORTBTOBDATA_CUSTOMER_FILE', ''),
			'IMPORTBTOBDATA_UPDATEMORE' => Configuration::get('IMPORTBTOBDATA_UPDATEMORE'),
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
        if((bool)Tools::getValue('IMPORTBTOBDATA_UPDATE'))
            $this->updateAttribute();
		
		if((bool)Tools::getValue('IMPORTBTOBDATA_UPDATEMORE'))
            $this->cronUserinfos();
    }
    
    public function updateAttribute(){
        $this->cronImport();
    }
    
    public function writeLog($txt, $start=0) {
        
        $log_dir = _PS_MODULE_DIR_.$this->name.'/log';
        $log_file = $log_dir . DIRECTORY_SEPARATOR .'logs';
        
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
        $log = fopen( $log_dir . DIRECTORY_SEPARATOR .'logs', 'a+');
        
        fputs($log, $txt.date('Y-m-d H:i:s')."\r\n");
        fclose($log);
    }
    
    /**
     * generale import
     */
    public function cronImport()
    {
        $mimport = new ImportdataController();
        $mimport->combinaitionUpdates();
    }
    /**
     * generale import
     */
    public function cronUserinfos()
    {
        $mimport = new ImportdataController();
        $mimport->UsersInfosUpdates();
    }
    
    public function hookActionProductAttributeUpdate($params){
        //var_dump($params);
    }
    public function hookActionUpdateMoreInfos($params)
    {
        $infos = $params['infos'];
        $return = Db::getInstance()->update('product_attribute', array(
            'irland_stock' => (int)(isset($infos['ireland-stock'])?$infos['ireland-stock']:''),
            'us_stock'     => (int)(isset($infos['us-stock'])?$infos['us-stock']:''),
            'in_transit'        => (int)(isset($infos['in-transit'])?$infos['in-transit']:''),
            'in_order'        => (int)(isset($infos['in-order'])?$infos['in-order']:''),
            'timing'        => (int)(isset($infos['timing'])?$infos['timing']:''),
            /*'last_purchase_price'        => (float)(isset($infos['last-purchase-price-usd'])?$infos['last-purchase-price-usd']:''),
            'last_seven_day_sale'        => (float)(isset($infos['last-7-days-sales'])?$infos['last-7-days-sales']:''),
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
            'app'      => (float)(isset($infos['app-eur'])?$infos['app-eur']:'')*/
            
        ), 'id_product = '.$params['id_product'].' AND id_product_attribute = '.(int)$params['ipa']);

        //if(self::$current_line==5)
        //   die;
        //self::$current_line++;
    }
    public function hookDisplayMoreProduct($params)
    {
        $groupPro = Configuration::get('IMPORTBTOBDATA_GROUP_ID');
        if(isset($params['defaultipa']) && (int)$params['defaultipa']){
            $this->getMoreInfos($params['defaultipa']);
            return $this->display(__FILE__, 'productdetail.tpl', $this->getCacheId('productdetail'.$params['defaultipa']));
        }
    }
    public function hookDisplayMoreProductList($params)
    {
        return $this->display(__FILE__, 'productlist.tpl', $this->getCacheId('productlist'.$params['defaultipa']));
    }
    public function hookDisplayMoreProductFilter($params)
    {
        if(isset($params['id_product_attribute']) && (int)$params['id_product_attribute']) {
            $this->getMoreInfos($params['id_product_attribute']);
            return $this->display(__FILE__, 'productfilter.tpl', $this->getCacheId('productfilter'.$params['id_product_attribute']));
        }
        
    }
    
    private function getMoreInfos($ipa)
    {
        if(in_array(Configuration::get('IMPORTBTOBDATA_GROUP_ID'), $this->context->customer->getGroups())){
        $sql = 'SELECT * FROM `'.bqSQL(_DB_PREFIX_.'product_attribute').'` WHERE id_product_attribute='.(int)$ipa;
            $result = Db::getInstance()->getRow($sql);
            if(is_array($result)){
                $this->smarty->assign('moreinfos', $result);
            }
        }
    }
	private function hookActionOrderStatusPostUpdate($params)
    {
		if(!Configuration::get('PS_ORDER_STATE_AUTO_CHANGE') && ($params['newOrderStatus']->id == Configuration::get('PS_OS_DELIVERED'))){
			$order = new Order((int)$params['id_order']);
			ImeiUpdate::updateOrderImei($order->id, $order->reference, false);
		}
        Configuration::updateValue('PS_ORDER_STATE_AUTO_CHANGE', 0);
    }
	
	public function cronUpdateImei()
    {
        ImeiUpdate::updateAllImei();
    }

}
