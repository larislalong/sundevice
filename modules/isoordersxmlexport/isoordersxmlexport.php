<?php
/**
* 2007-2019 PrestaShop
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
*  @copyright 2007-2019 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Isoordersxmlexport extends Module
{
    protected $config_form = false;
	public $default_configs;

    public function __construct()
    {
        $this->name = 'isoordersxmlexport';
        $this->tab = 'administration';
        $this->version = '1.2.0';
        $this->author = 'GDT';
        $this->need_instance = 0;
		
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

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Orders XML export');
        $this->description = $this->l('Orders XML export module');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        Configuration::updateValue('ISOORDERSXMLEXPORT_MOD_CONFIG', serialize($this->default_configs));

        return parent::install();
    }

    public function uninstall()
    {
        Configuration::deleteByName('ISOORDERSXMLEXPORT_MOD_CONFIG');

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
        if (((bool)Tools::isSubmit('submitisoordersxmlexportModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        // $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $this->renderForm();
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
        $helper->submit_action = 'submitisoordersxmlexportModule';
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
		// var_dump($_SERVER);die;
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Configuration FTP'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Activer l\'envoi FTP'),
                        'name' => 'sendviaftp',
                        'is_bool' => true,
                        'desc' => $this->l('Activer l\'envoi des fichiers générés par FTP'),
                        'values' => array(
                            array(
                                'id' => 'sendviaftp_on',
                                'value' => true,
                                'label' => $this->l('OUI')
                            ),
                            array(
                                'id' => 'sendviaftp_off',
                                'value' => false,
                                'label' => $this->l('NON')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Activer l\'envoi SSL'),
                        'name' => 'ftp_sslcon',
                        'is_bool' => true,
                        'desc' => $this->l('Envoi des fichiers en mode sécurisé'),
                        'values' => array(
                            array(
                                'id' => 'ftp_sslcon_on',
                                'value' => true,
                                'label' => $this->l('OUI')
                            ),
                            array(
                                'id' => 'ftp_sslcon_off',
                                'value' => false,
                                'label' => $this->l('NON')
                            )
                        ),
                    ),
                    array(
                        'col' => 6,
                        'type' => 'text',
                        'name' => 'ftp_host',
                        'label' => $this->l('Url de l\'hôte FTP'),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'name' => 'ftp_port',
                        'label' => $this->l('Port de connexion'),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'name' => 'ftp_login',
                        'label' => $this->l('Login FTP'),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'name' => 'ftp_password',
                        'label' => $this->l('Mot de passe FTP'),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'name' => 'ftp_dirpath',
                        'label' => $this->l('Chemin du dossier cible'),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'name' => 'last_exec_date',
                        'label' => $this->l('Date de dernier export'),
                        'desc' => $this->l('Les commandes seront exportées à partir cette date'),
                    ),
                    array(
                        'col' => 2,
                        'type' => 'text',
                        'name' => 'last_file_numb',
                        'label' => $this->l('Dernier numéro de fichier'),
                        'desc' => $this->l('Les commandes exportées seront numérotées à partir ce numéro'),
                    ),
                    array(
                        'col' => 6,
                        'type' => 'text',
                        'name' => 'cat_in_store_2',
                        'label' => $this->l('Catégories du magasin 002'),
                        'desc' => $this->l('Entrer les IDs des catégories appartenants au magasin 002 séparés par des virgules ","'),
                    ),
                    array(
                        'type' => '',
                        'name' => '',
                        'label' => $this->l('Chemin du script d\'export à configurer comme tâche cron'),
                        'desc' => _PS_ROOT_DIR_.$this->_path.'iso-cron-ordersexport2xml.php',
                    ),
                    array(
                        'type' => '',
                        'name' => '',
                        'label' => $this->l('Url du script d\'export (Exécution manuelle)'),
                        'desc' => _PS_BASE_URL_.__PS_BASE_URI__.ltrim($this->_path,'/').'iso-cron-ordersexport2xml.php',
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
		$config = unserialize(Configuration::get('ISOORDERSXMLEXPORT_MOD_CONFIG', serialize($this->default_configs)));
		// var_dump($config);die;
        return array(
            'sendviaftp' 	=> $config['sendviaftp'],
			'ftp_host' 		=> $config['ftp_host'],
			'ftp_login' 	=> $config['ftp_login'],
			'ftp_password' 	=> $config['ftp_password'],
			'ftp_sslcon' 	=> $config['ftp_sslcon'],
			'ftp_dirpath' 	=> $config['ftp_dirpath'],
			'ftp_port' 		=> $config['ftp_port'],
			'last_exec_date'=> $config['last_exec_date'],
			'last_file_numb'=> $config['last_file_numb'],
			'cat_in_store_2'=> $config['cat_in_store_2'],
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();
		// var_dump($form_values);die;

		$configs = $this->default_configs;
        foreach (array_keys($form_values) as $key) {
            $configs[$key] = Tools::getValue($key);
        }
		Configuration::updateValue('ISOORDERSXMLEXPORT_MOD_CONFIG', serialize($configs));
    }
}
