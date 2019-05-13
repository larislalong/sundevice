<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * @author    Presta.Site
 * @copyright 2017 Presta.Site
 * @license   LICENSE.txt
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class NotaRobot extends Module
{
    protected $html;

    public $settings_prefix = 'NAR_';
    public $key;
    public $secret_key;
    public $re_theme;
    public $re_size;
    public $re_align;
    public $bl_email;
    public $custom_css;
    public $re_lang;

    public function __construct()
    {
        $this->name = 'notarobot';
        $this->tab = 'front_office_features';
        $this->version = '1.1.4';
        $this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.7.99.99');
        $this->author = 'Presta.Site';
        $this->bootstrap = true;

        parent::__construct();
        $this->loadSettings();

        $this->displayName = $this->l('Contact form anti-spam: reCAPTCHA and blacklist');
        $this->description = $this->l('Anti-spam module');
    }

    public function install()
    {
        if (!parent::install()
            || !$this->registerHook('header')
            || !$this->registerHook('createAccountForm')
            // || !$this->registerHook('displayCustomerIdentityForm')
            || !$this->registerHook('displayBackOfficeHeader')
            || !$this->registerHook('actionBeforeSubmitAccount')
        ) {
            return false;
        }

        //default values:
        foreach ($this->getSettings() as $item) {
            if ($item['type'] == 'html') {
                continue;
            }
            if (isset($item['default']) && Configuration::get($this->settings_prefix . $item['name']) === false) {
                Configuration::updateValue($this->settings_prefix . $item['name'], $item['default']);
            }
        }

        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }

        return true;
    }

    public function getContent()
    {
        // Check if this is an ajax call / PS1.5
        if ($this->getPSVersion() < 1.6 && Tools::getIsset('ajax')
            && Tools::getValue('ajax') && Tools::getValue('action')) {
            if (is_callable(array($this, 'ajaxProcess'.Tools::getValue('action')))) {
                call_user_func(array($this, 'ajaxProcess' . Tools::getValue('action')));
            }
            die();
        }

        $this->context->smarty->assign(array(
            'path' => $this->_path,
        ));

        $this->html = '';
        $this->html .= $this->context->smarty->fetch($this->local_path . 'views/templates/admin/dev_by.tpl');
        if (Configuration::get('PS_DISABLE_OVERRIDES')) {
            $this->html .= $this->l('Please enable overrides (Advanced Parameters >> Performance). It is required for proper module working.');
        }
        $this->html .= $this->postProcess();
        $this->html .= $this->renderForm();
        $this->html .= $this->renderProFeatures();

        return $this->html;
    }

    protected function postProcess()
    {
        $html = '';
        $settings_updated = false;

        if (Tools::isSubmit('submitModule')) {
            //saving settings:
            $settings = $this->getSettings();
            foreach ($settings as $item) {
                if ($item['type'] == 'html' || $item['type'] == 'checkbox'
                    || (isset($item['lang']) && $item['lang'] == true)) {
                    continue;
                }
                if (Tools::isSubmit($item['name'])) {
                    Configuration::updateValue(
                        $this->settings_prefix . $item['name'],
                        Tools::getValue($item['name']),
                        true
                    );
                    $settings_updated = true;
                }
            }

            //update lang fields:
            $languages = Language::getLanguages();
            foreach ($settings as $item) {
                if ($item['type'] == 'html' || $item['type'] == 'checkbox') {
                    continue;
                }
                $lang_value = array();
                foreach ($languages as $lang) {
                    if (Tools::isSubmit($item['name'] . '_' . $lang['id_lang'])) {
                        $lang_value[$lang['id_lang']] = Tools::getValue($item['name'] . '_' . $lang['id_lang']);
                        $settings_updated = true;
                    }
                }
                if (sizeof($lang_value)) {
                    Configuration::updateValue($this->settings_prefix . $item['name'], $lang_value, true);
                }
            }

            // update checkbox fields
            foreach ($settings as $item) {
                if ($item['type'] == 'checkbox') {
                    if (isset($item['getValues']) && $item['getValues'] && method_exists($this, $item['getValues'])) {
                        $all_values = $this->{$item['getValues']}();
                        $sentValues = array();
                        foreach ($all_values as $val) {
                            $isSubmit = Tools::isSubmit($item['name'] . '_' . $val['id']);
                            if ($isSubmit) {
                                $sentValues[] = $val['id'];
                            }
                        }
                        Configuration::updateValue($this->settings_prefix . $item['name'], implode(',', $sentValues));
                    }
                }
            }
        }

        $this->loadSettings();

        if ($settings_updated) {
            $html .= $this->displayConfirmation($this->l('Settings updated.'));
        }

        return $html;
    }

    protected function renderForm()
    {
        $field_forms = array(
            array(
                'form' => array(
                    'legend' => array(
                        'title' => $this->l('Settings'),
                        'icon' => 'icon-cogs'
                    ),
                    'description' => $this->context->smarty->fetch($this->local_path . 'views/templates/admin/get_recaptcha.tpl'),
                    'input' => $this->getSettings(),
                    'submit' => array(
                        'title' => $this->l('Save'),
                    )
                ),
            ),
        );

        foreach ($field_forms as &$field_form) {
            if ($this->getPSVersion() == 1.5) {
                $field_form['form']['submit']['class'] = 'button';
            }
        }

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang =
            Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ?
                Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') :
                0;
        $this->fields_form = array();

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) .
            '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => array(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        foreach ($this->getSettings() as $item) {
            if ($item['type'] == 'html') {
                continue;
            }
            if ($item['type'] == 'checkbox') {
                foreach ($this->getCheckboxValues($item['name']) as $checkboxValue) {
                    $helper->tpl_vars['fields_value'][$item['name'].'_'.$checkboxValue] = 1;
                }
            } else {
                $helper->tpl_vars['fields_value'][$item['name']] = Configuration::get(
                    $this->settings_prefix .
                    $item['name']
                );
                if ($item['name'] == 'CUSTOM_CSS') {
                    $helper->tpl_vars['fields_value'][$item['name']] = html_entity_decode(
                        Configuration::get($this->settings_prefix.$item['name'])
                    );
                }
            }
        }

        return $helper->generateForm($field_forms);
    }

    public function getSettings()
    {
        $settings = array(
            array(
                'type' => 'text',
                'name' => 'KEY',
                'label' => $this->l('reCAPTCHA key'),
                'default' => '',
                'validate' => 'isString',
            ),
            array(
                'type' => 'text',
                'name' => 'SECRET_KEY',
                'label' => $this->l('reCAPTCHA secret key'),
                'default' => '',
                'validate' => 'isString',
            ),
            array(
                'type' => 'select',
                'name' => 'RE_THEME',
                'label' => $this->l('reCAPTCHA theme'),
                'class' => 't',
                'options' => array(
                    'query' => array(
                        array(
                            'id_option' => 'light',
                            'name' => $this->l('Light'),
                        ),
                        array(
                            'id_option' => 'dark',
                            'name' => $this->l('Dark'),
                        )
                    ),
                    'id' => 'id_option',
                    'name' => 'name',
                ),
                'default' => 'light',
                'validate' => 'isString',
            ),
            array(
                'type' => 'select',
                'name' => 'RE_SIZE',
                'label' => $this->l('reCAPTCHA size'),
                'class' => 't',
                'options' => array(
                    'query' => array(
                        array(
                            'id_option' => 'normal',
                            'name' => $this->l('Normal'),
                        ),
                        array(
                            'id_option' => 'compact',
                            'name' => $this->l('Compact'),
                        )
                    ),
                    'id' => 'id_option',
                    'name' => 'name',
                ),
                'default' => 'light',
                'validate' => 'isString',
            ),
            array(
                'type' => 'select',
                'name' => 'RE_LANG',
                'label' => $this->l('reCAPTCHA language'),
                'class' => 't',
                'options' => array(
                    'query' => $this->getReLangOptions(),
                    'id' => 'id_option',
                    'name' => 'name',
                ),
                'default' => 'light',
                'validate' => 'isString',
            ),
            array(
                'type' => 'select',
                'name' => 'RE_ALIGN',
                'label' => $this->l('reCAPTCHA align'),
                'class' => 't',
                'options' => array(
                    'query' => array(
                        array('id_option' => 'left', 'name' => $this->l('Left')),
                        array('id_option' => 'right', 'name' => $this->l('Right')),
                    ),
                    'id' => 'id_option',
                    'name' => 'name',
                ),
                'default' => 'left',
                'validate' => 'isString',
            ),
            array(
                'type' => 'textarea',
                'name' => 'BL_EMAIL',
                'label' => $this->l('Blacklist email domains / addresses'),
                'hint' => $this->l('Block contact form messages from the listed email addresses or email domains.'),
                'desc' => $this->l('One by line. Examples: test.com, test@test.com'),
                'default' => '',
                'validate' => 'isString',
            ),
            array(
                'type' => 'textarea',
                'name' => 'CUSTOM_CSS',
                'label' => $this->l('Custom CSS:'),
                'hint' => $this->l('Add your styles directly in this field without editing files'),
                'validate' => 'isCleanHtml',
                'resize' => true,
                'cols' => '',
                'rows' => '',
            ),
        );

        if ($this->getPSVersion() < 1.6) {
            foreach ($settings as &$item) {
                $desc = isset($item['desc']) ? $item['desc'] : '';
                $hint = isset($item['hint']) ? $item['hint'] . '<br/>' : '';
                $item['desc'] = $hint . $desc;
                $item['hint'] = '';
            }
        }

        return $settings;
    }

    protected function loadSettings()
    {
        foreach ($this->getSettings() as $item) {
            if ($item['type'] == 'html') {
                continue;
            }
            $name = Tools::strtolower($item['name']);
            $this->$name = Configuration::get($this->settings_prefix . $item['name']);
        }
    }

    protected function getPSVersion($without_dots = false)
    {
        $ps_version = _PS_VERSION_;
        $ps_version = Tools::substr($ps_version, 0, 3);

        if ($without_dots) {
            $ps_version = str_replace('.', '', $ps_version);
        }

        return (float)$ps_version;
    }

    public function hookHeader()
    {
        if ($this->key && $this->secret_key) {
            $this->context->controller->addCSS(($this->_path) . 'views/css/front.css');
			
            $this->context->smarty->assign(array(
                'nar_key' => $this->key,
                'psv' => $this->getPSVersion(),
                're_theme' => $this->re_theme,
                're_size' => $this->re_size,
                're_align' => $this->re_align,
                'custom_css' => html_entity_decode($this->custom_css),
                'nar_lang' => $this->re_lang,
            ));

            return $this->display(__FILE__, 'header.tpl');
        }
    }
	
	public function hookCreateAccountForm($params)
	{
        if ($this->key && $this->secret_key) {
			
            $this->context->smarty->assign(array(
                'nar_key' => $this->key,
                'psv' => $this->getPSVersion(),
                're_theme' => $this->re_theme,
                're_size' => $this->re_size,
                're_align' => $this->re_align,
                'custom_css' => html_entity_decode($this->custom_css),
                'nar_lang' => $this->re_lang,
            ));

            return $this->display(__FILE__, 'registration.tpl');
        }
	}
	
	public function hookActionBeforeSubmitAccount($params){
		$nar_error = $this->checkRegistrationForm();
		// var_dump($nar_error);die('in');
		if ($nar_error) {
			$params['controller']->errors[] = $nar_error;
		}
		// die('out');
	}

    public function checkGRE()
    {
        if (!$this->secret_key || !$this->key) {
            return true;
        }

        try {
            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $data = array(
                'secret' => $this->secret_key,
                'response' => Tools::getValue('g-recaptcha-response'),
                'remoteip' => $_SERVER['REMOTE_ADDR']
            );

            $options = array(
                'http' => array(
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method' => 'POST',
                    'content' => http_build_query($data),
                    'timeout' => 15,
                )
            );

            $context = stream_context_create($options);
            $result = Tools::file_get_contents($url, false, $context);

            return json_decode($result)->success;
        } catch (Exception $e) {
            return null;
        }
    }

    public function checkNarEmailBlackList($email)
    {
        $blacklist = preg_split('/\r\n|[\r\n]/', $this->bl_email);
        if (is_array($blacklist)) {
            foreach ($blacklist as $bl_email) {
                if (stripos($email, $bl_email) !== false) {
                    return false;
                }
            }
        }

        return true;
    }

    public function checkContactForm()
    {
        $error = null;

        if (Tools::isSubmit('submitMessage') && !$this->checkGRE()) {
            $error = $this->l('Please confirm that you are not a robot');
        } elseif (Tools::isSubmit('submitMessage') && Tools::getValue('from')
            && !$this->checkNarEmailBlackList(Tools::getValue('from'))) {
            $error = $this->l('Your email address is blocked. Please use another email address.');
        }

        return $error;
    }

    public function checkRegistrationForm()
    {
        $error = null;

        if (!$this->checkGRE()) {
            $error = $this->l('Please confirm that you are not a robot');
        } elseif (Tools::getValue('email') && !$this->checkNarEmailBlackList(Tools::getValue('email'))) {
            $error = $this->l('Your email address is blocked. Please use another email address.');
        }

        return $error;
    }

    public function renderProFeatures()
    {
        $this->context->smarty->assign(array(
            'img_path' => $this->_path.'views/img/',
            'psv' => $this->getPSVersion(),
        ));
        return $this->context->smarty->fetch($this->local_path . 'views/templates/admin/pro_features.tpl');
    }

    public function hookDisplayBackOfficeHeader($params)
    {
        // check whether it's a module page
        if (Tools::getValue('configure') == $this->name) {
            $this->context->controller->addCSS(array(
                $this->_path.'views/css/admin.css',
            ));
            $this->context->controller->addJquery();
        }
    }

    public function getReLangs()
    {
        $langs = array(
            'Arabic' => 'ar',
            'Afrikaans' => 'af',
            'Amharic' => 'am',
            'Armenian' => 'hy',
            'Azerbaijani' => 'az',
            'Basque' => 'eu',
            'Bengali' => 'bn',
            'Bulgarian' => 'bg',
            'Catalan' => 'ca',
            'Chinese (Hong Kong)' => 'zh-HK',
            'Chinese (Simplified)' => 'zh-CN',
            'Chinese (Traditional)' => 'zh-TW',
            'Croatian' => 'hr',
            'Czech' => 'cs',
            'Danish' => 'da',
            'Dutch' => 'nl',
            'English (UK)' => 'en-GB',
            'English (US)' => 'en',
            'Estonian' => 'et',
            'Filipino' => 'fil',
            'Finnish' => 'fi',
            'French' => 'fr',
            'French (Canadian)' => 'fr-CA',
            'Galician' => 'gl',
            'Georgian' => 'ka',
            'German' => 'de',
            'German (Austria)' => 'de-AT',
            'German (Switzerland)' => 'de-CH',
            'Greek' => 'el',
            'Gujarati' => 'gu',
            'Hebrew' => 'iw',
            'Hindi' => 'hi',
            'Hungarain' => 'hu',
            'Icelandic' => 'is',
            'Indonesian' => 'id',
            'Italian' => 'it',
            'Japanese' => 'ja',
            'Kannada' => 'kn',
            'Korean' => 'ko',
            'Laothian' => 'lo',
            'Latvian' => 'lv',
            'Lithuanian' => 'lt',
            'Malay' => 'ms',
            'Malayalam' => 'ml',
            'Marathi' => 'mr',
            'Mongolian' => 'mn',
            'Norwegian' => 'no',
            'Persian' => 'fa',
            'Polish' => 'pl',
            'Portuguese' => 'pt',
            'Portuguese (Brazil)' => 'pt-BR',
            'Portuguese (Portugal)' => 'pt-PT',
            'Romanian' => 'ro',
            'Russian' => 'ru',
            'Serbian' => 'sr',
            'Sinhalese' => 'si',
            'Slovak' => 'sk',
            'Slovenian' => 'sl',
            'Spanish' => 'es',
            'Spanish (Latin America)' => 'es-419',
            'Swahili' => 'sw',
            'Swedish' => 'sv',
            'Tamil' => 'ta',
            'Telugu' => 'te',
            'Thai' => 'th',
            'Turkish' => 'tr',
            'Ukrainian' => 'uk',
            'Urdu' => 'ur',
            'Vietnamese' => 'vi',
            'Zulu' => 'zu',
        );

        return $langs;
    }
    public function getReLangOptions()
    {
        $langs = array(
            array(
                'id_option' => '',
                'name' => $this->l('Default user language'),
            ),
        );

        foreach ($this->getReLangs() as $lang => $iso) {
            $langs[] = array(
                'id_option' => $iso,
                'name' => $lang,
            );
        }

        return $langs;
    }
}
