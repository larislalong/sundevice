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

include_once _PS_MODULE_DIR_ . 'menupro/classes/MpSecondaryMenu.php';
include_once _PS_MODULE_DIR_ . 'menupro/classes/MpHtmlContent.php';
include_once _PS_MODULE_DIR_ . 'menupro/classes/MpStyleMenu.php';
include_once _PS_MODULE_DIR_ . 'menupro/classes/MpTools.php';
include_once _PS_MODULE_DIR_ . 'menupro/classes/MpIcon.php';

class SecondaryMenuInformationController
{
    public $module;

    public function __construct($module, $context)
    {
        $this->module = $module;
        $this->context = $context;
    }

    /**
     * call a controller to submit form and retrieve possible errors
     *
     * @param MpSecondaryMenu $secondaryMenu
     *            the controller class name
     * @return array
     */
    public function getForm($secondaryMenu)
    {
        $form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->module->l('Information', __CLASS__)
                ),
                'input' => array(
                    array(
                        'col' => 6,
                        'type' => 'text',
                        'desc' => $this->module->l('Enter a valid name', __CLASS__),
                        'name' => 'MENUPRO_SECONDARY_MENU_NAME',
                        'label' => $this->module->l('Name', __CLASS__),
                        'required' => true,
                        'lang' => true
                    ),
                    array(
                        'col' => 6,
                        'type' => 'text',
                        'desc' => $this->module->l('Enter a valid title', __CLASS__),
                        'name' => 'MENUPRO_SECONDARY_MENU_TITLE',
                        'label' => $this->module->l('Title', __CLASS__),
                        'lang' => true
                    )
                
                )
            )
        );
        if (_PS_VERSION_>='1.6') {
            $form['form']['submit'] = array(
                'title' => $this->module->l('Save', __CLASS__)
            );
            $form['form']['buttons'] = array(
                'btn_save' => array(
                    'name' => 'savesecondarymemu',
                    'type' => 'button',
                    'title' => $this->module->l('Save', __CLASS__),
                    'class' => 'btn btn-default pull-right btnSave',
                    'icon' => 'process-icon-save'
                ),
                'save_and_stay' => array(
                    'name' => 'staysecondarymemu',
                    'type' => 'button',
                    'title' => $this->module->l('Save and stay', __CLASS__),
                    'class' => 'btn btn-default pull-right btnSaveAndStay',
                    'icon' => 'process-icon-save'
                ),
                'btn_cancel' => array(
                    'name' => 'cancelsecondarymemu',
                    'type' => 'button',
                    'title' => $this->module->l('Quit', __CLASS__),
                    'class' => 'btn btn-default pull-left btnCancel',
                    'icon' => 'process-icon-cancel'
                )
            );
        }
        if ($secondaryMenu->item_type == MpSecondaryMenu::MENU_TYPE_CUSTOMISE) {
            $form['form']['input'][] = array(
                'col' => 6,
                'type' => 'select',
                'label' => $this->module->l('Link type', __CLASS__),
                'name' => 'MENUPRO_SECONDARY_MENU_LINK_TYPE',
                'desc' => $this->module->l('Link type', __CLASS__),
                'required' => true,
                'options' => array(
                    'query' => array(
                        array(
                            'id' => MpSecondaryMenu::LINK_TYPE_INTERNAL,
                            'name' => $this->module->l('Internal', __CLASS__)
                        ),
                        array(
                            'id' => MpSecondaryMenu::LINK_TYPE_EXTERNAL,
                            'name' => $this->module->l('External', __CLASS__)
                        )
                    ),
                    'id' => 'id',
                    'name' => 'name'
                )
            );
            $form['form']['input'][] = array(
                'col' => 6,
                'type' => 'text',
                'desc' => $this->module->l('Enter a valid link', __CLASS__),
                'name' => 'MENUPRO_SECONDARY_MENU_LINK',
                'label' => $this->module->l('Link', __CLASS__),
                'required' => true,
                'lang' => true
            );
        }
        if (($secondaryMenu->item_type == MpSecondaryMenu::MENU_TYPE_PRODUCT) || ($secondaryMenu->item_type ==
                 MpSecondaryMenu::MENU_TYPE_CATEGORY)) {
            $form['form']['input'][] = array(
                'col' => 3,
                'type' => 'select',
                'label' => $this->module->l('Display type', __CLASS__),
                'name' => 'MENUPRO_SECONDARY_MENU_DISPLAY_STYLE',
                'desc' => $this->module->l('Display type', __CLASS__),
                'required' => true,
                'options' => array(
                    'query' => array(
                        array(
                            'id' => MpSecondaryMenu::DISPLAY_STYLE_SIMPLE,
                            'name' => $this->module->l('Simple', __CLASS__)
                        ),
                        array(
                            'id' => MpSecondaryMenu::DISPLAY_STYLE_COMPLEX,
                            'name' => $this->module->l('Full', __CLASS__)
                        )
                    ),
                    'id' => 'id',
                    'name' => 'name'
                )
            );
        }
        $form['form']['input'][] = array(
            'col' => 6,
            'type' => (_PS_VERSION_>='1.6')?'switch':'radio',
            'class' => (_PS_VERSION_>='1.6')?'':'t',
            'label' => $this->module->l('Clickable', __CLASS__),
            'name' => 'MENUPRO_SECONDARY_MENU_CLICKABLE',
            'is_bool' => true,
            'desc' => $this->module->l('Clickable', __CLASS__),
            'values' => array(
                array(
                    'id' => 'active_on',
                    'value' => true,
                    'label' => $this->module->l('Enabled', __CLASS__)
                ),
                array(
                    'id' => 'active_off',
                    'value' => false,
                    'label' => $this->module->l('Disabled', __CLASS__)
                )
            )
        );
        $form['form']['input'][] = array(
            'col' => 6,
            'type' => (_PS_VERSION_>='1.6')?'switch':'radio',
            'class' => (_PS_VERSION_>='1.6')?'':'t',
            'label' => $this->module->l('Open in a new tab', __CLASS__),
            'name' => 'MENUPRO_SECONDARY_MENU_OPEN_NEW_TAB',
            'is_bool' => true,
            'desc' => $this->module->l('Open in a new tab', __CLASS__),
            'values' => array(
                array(
                    'id' => 'active_on',
                    'value' => true,
                    'label' => $this->module->l('Enabled', __CLASS__)
                ),
                array(
                    'id' => 'active_off',
                    'value' => false,
                    'label' => $this->module->l('Disabled', __CLASS__)
                )
            )
        );
        if ($secondaryMenu->item_type == MpSecondaryMenu::MENU_TYPE_CATEGORY) {
            $form['form']['input'][] = array(
                'col' => 6,
                'type' => (_PS_VERSION_>='1.6')?'switch':'radio',
                'class' => (_PS_VERSION_>='1.6')?'':'t',
                'label' => $this->module->l('Associate all sub categories', __CLASS__),
                'name' => 'MENUPRO_SECONDARY_MENU_ASSOCIATE_ALL',
                'is_bool' => true,
                'desc' => $this->module->l('Associate all sub categories', __CLASS__),
                'values' => array(
                    array(
                        'id' => 'active_on',
                        'value' => true,
                        'label' => $this->module->l('Enabled', __CLASS__)
                    ),
                    array(
                        'id' => 'active_off',
                        'value' => false,
                        'label' => $this->module->l('Disabled', __CLASS__)
                    )
                )
            );
        }
        if ($secondaryMenu->item_type == MpSecondaryMenu::MENU_TYPE_CUSTOMISE) {
            $form['form']['input'][] = array(
                'col' => 6,
                'type' => (_PS_VERSION_>='1.6')?'switch':'radio',
                'class' => (_PS_VERSION_>='1.6')?'':'t',
                'label' => $this->module->l('Use custom content', __CLASS__),
                'name' => 'MENUPRO_SECONDARY_MENU_USE_CUSTOM_CONTENT',
                'is_bool' => true,
                'desc' => $this->module->l('Use custom content', __CLASS__),
                'values' => array(
                    array(
                        'id' => 'active_on',
                        'value' => true,
                        'label' => $this->module->l('Enabled', __CLASS__)
                    ),
                    array(
                        'id' => 'active_off',
                        'value' => false,
                        'label' => $this->module->l('Disabled', __CLASS__)
                    )
                )
            );
            $form['form']['tinymce'] = true;
            $form['form']['input'][] = array(
                'type' => 'textarea',
                'label' => $this->module->l('Custom content', __CLASS__),
                'lang' => true,
                'name' => 'MENUPRO_SECONDARY_MENU_CUSTOM_CONTENT',
                'rows' => 5,
                'cols' => 40,
                'class' => 'rte',
                'autoload_rte' => true
            );
        }
        $form['form']['input'][] = array(
            'col' => 6,
            'type' => 'text',
            'desc' => $this->module->l('Icon CSS class example : icon-envelope-alt', __CLASS__),
            'name' => 'MENUPRO_SECONDARY_MENU_ICON_CSS_CLASS',
            'label' => $this->module->l('Icon CSS class', __CLASS__)
        );
        $form['form']['input'][] = array(
            'col' => 6,
            'type' => (_PS_VERSION_>='1.6')?'switch':'radio',
            'class' => (_PS_VERSION_>='1.6')?'':'t',
            'label' => $this->module->l('Enabled', __CLASS__),
            'name' => 'MENUPRO_SECONDARY_MENU_ACTIVE',
            'is_bool' => true,
            'values' => array(
                array(
                    'id' => 'active_on',
                    'value' => true,
                    'label' => $this->module->l('Enabled', __CLASS__)
                ),
                array(
                    'id' => 'active_off',
                    'value' => false,
                    'label' => $this->module->l('Disabled', __CLASS__)
                )
            )
        );
        return $form;
    }

    public function getValidationErrors($values)
    {
        $errors = array();
        if (! is_numeric($values['MENUPRO_SECONDARY_MENU_ITEM_TYPE']) ||
                ((int) $values['MENUPRO_SECONDARY_MENU_ITEM_TYPE'] <= 0)) {
            $errors[] = $this->module->l('Wrong menu type', __CLASS__);
        }
        if (! is_numeric($values['MENUPRO_SECONDARY_MENU_ID']) || ((int) $values['MENUPRO_SECONDARY_MENU_ID'] <= 0)) {
            $errors[] = $this->module->l('Wrong menu id', __CLASS__);
        }
        if (MpTools::isMultilangFieldEmpty($values, 'MENUPRO_SECONDARY_MENU_NAME')) {
            $errors[] = $this->module->l('Please Enter a menu name', __CLASS__);
        }
        
        if ((int) $values['MENUPRO_SECONDARY_MENU_ITEM_TYPE'] == MpSecondaryMenu::MENU_TYPE_CUSTOMISE) {
            if (((bool) $values['MENUPRO_SECONDARY_MENU_CLICKABLE']) &&
                     (! (bool) $values['MENUPRO_SECONDARY_MENU_USE_CUSTOM_CONTENT']) &&
                    MpTools::isMultilangFieldEmpty($values, 'MENUPRO_SECONDARY_MENU_LINK')) {
                $errors[] = $this->module->l('Please Enter a menu link', __CLASS__);
            }
            if ((bool) $values['MENUPRO_SECONDARY_MENU_USE_CUSTOM_CONTENT']) {
                if (MpTools::isMultilangFieldEmpty($values, 'MENUPRO_SECONDARY_MENU_CUSTOM_CONTENT')) {
                    $errors[] = $this->module->l(
                        'If you select option use custom content you must enter a valid content',
                        __CLASS__
                    );
                }
            }
        }
        return $errors;
    }

    public function getFormPostedValues($languages)
    {
        $name = array();
        $title = array();
        $link = array();
        $customContent = array();
        foreach ($languages as $language) {
            $language = (object) $language;
            if (Tools::getIsset('MENUPRO_SECONDARY_MENU_NAME_' . $language->id_lang)) {
                $name[$language->id_lang] = Tools::getValue('MENUPRO_SECONDARY_MENU_NAME_' . $language->id_lang);
            }
            if (Tools::getIsset('MENUPRO_SECONDARY_MENU_TITLE_' . $language->id_lang)) {
                $title[$language->id_lang] = Tools::getValue('MENUPRO_SECONDARY_MENU_TITLE_' . $language->id_lang);
            }
            if (Tools::getIsset('MENUPRO_SECONDARY_MENU_LINK_' . $language->id_lang)) {
                $link[$language->id_lang] = Tools::getValue('MENUPRO_SECONDARY_MENU_LINK_' . $language->id_lang);
            }
            if (Tools::getIsset('MENUPRO_SECONDARY_MENU_CUSTOM_CONTENT_' . $language->id_lang)) {
                $customContent[$language->id_lang] = Tools::getValue('MENUPRO_SECONDARY_MENU_CUSTOM_CONTENT_' .
                         $language->id_lang);
            }
        }
        return array(
            'MENUPRO_SECONDARY_MENU_NAME' => $name,
            'MENUPRO_SECONDARY_MENU_TITLE' => $title,
            'MENUPRO_SECONDARY_MENU_LINK' => $link,
            'MENUPRO_SECONDARY_MENU_CUSTOM_CONTENT' => $customContent,
            'MENUPRO_SECONDARY_MENU_ID' => Tools::getValue('id_menupro_secondary_menu'),
            'MENUPRO_SECONDARY_MENU_ITEM_TYPE' => Tools::getValue('MENUPRO_SECONDARY_MENU_ITEM_TYPE'),
            'MENUPRO_SECONDARY_MENU_ICON_CSS_CLASS' => Tools::getValue('MENUPRO_SECONDARY_MENU_ICON_CSS_CLASS'),
            'MENUPRO_SECONDARY_MENU_LINK_TYPE' => Tools::getValue('MENUPRO_SECONDARY_MENU_LINK_TYPE'),
            'MENUPRO_SECONDARY_MENU_DISPLAY_STYLE' => Tools::getValue('MENUPRO_SECONDARY_MENU_DISPLAY_STYLE'),
            'MENUPRO_SECONDARY_MENU_CLICKABLE' => Tools::getValue('MENUPRO_SECONDARY_MENU_CLICKABLE'),
            'MENUPRO_SECONDARY_MENU_OPEN_NEW_TAB' => Tools::getValue('MENUPRO_SECONDARY_MENU_OPEN_NEW_TAB'),
            'MENUPRO_SECONDARY_MENU_USE_CUSTOM_CONTENT' => Tools::getValue('MENUPRO_SECONDARY_MENU_USE_CUSTOM_CONTENT'),
            'MENUPRO_SECONDARY_MENU_ASSOCIATE_ALL' => Tools::getValue('MENUPRO_SECONDARY_MENU_ASSOCIATE_ALL'),
            'MENUPRO_SECONDARY_MENU_ACTIVE' => Tools::getValue('MENUPRO_SECONDARY_MENU_ACTIVE')
        );
    }

    /**
     * call a controller to submit form and retrieve possible errors
     *
     * @param MpSecondaryMenu $secondaryMenu
     *            the controller class name
     * @return array
     */
    public function getSavedValues($secondaryMenu)
    {
        $values = array(
            'MENUPRO_SECONDARY_MENU_NAME' => $secondaryMenu->name,
            'MENUPRO_SECONDARY_MENU_TITLE' => $secondaryMenu->title,
            'MENUPRO_SECONDARY_MENU_LINK' => $secondaryMenu->link,
            'MENUPRO_SECONDARY_MENU_ICON_CSS_CLASS' => MpIcon::getCssClassForSecondaryMenu($secondaryMenu->id),
            'MENUPRO_SECONDARY_MENU_LINK_TYPE' => $secondaryMenu->link_type,
            'MENUPRO_SECONDARY_MENU_DISPLAY_STYLE' => $secondaryMenu->display_style,
            'MENUPRO_SECONDARY_MENU_CLICKABLE' => $secondaryMenu->clickable,
            'MENUPRO_SECONDARY_MENU_OPEN_NEW_TAB' => $secondaryMenu->new_tab,
            'MENUPRO_SECONDARY_MENU_USE_CUSTOM_CONTENT' => $secondaryMenu->use_custom_content,
            'MENUPRO_SECONDARY_MENU_ASSOCIATE_ALL' => $secondaryMenu->associate_all,
            'MENUPRO_SECONDARY_MENU_ACTIVE' => $secondaryMenu->active
        );
        if ($secondaryMenu->item_type == MpSecondaryMenu::MENU_TYPE_CUSTOMISE) {
            $idHtmlContent = MpHtmlContent::getIdForSecondaryMenu($secondaryMenu->id);
            if ($idHtmlContent) {
                $htmlContent = new MpHtmlContent($idHtmlContent);
                $values['MENUPRO_SECONDARY_MENU_CUSTOM_CONTENT'] = $htmlContent->content;
            }else{
                $values['MENUPRO_SECONDARY_MENU_CUSTOM_CONTENT'] = array();
                $languages = Language::getLanguages();
                foreach ($languages as $language) {
                    $values['MENUPRO_SECONDARY_MENU_CUSTOM_CONTENT'][$language['id_lang']] ='';
                }
            }
        }
        return $values;
    }

    /**
     * call a controller to submit form and retrieve possible errors
     *
     * @param MpSecondaryMenu $secondaryMenu
     *            the controller class name
     * @return array
     */
    public function processSave($values, $secondaryMenu)
    {
        $errors = $this->getValidationErrors($values);
        if (empty($errors)) {
            $secondaryMenu->name = MpTools::fillMultilangEmptyFields($values['MENUPRO_SECONDARY_MENU_NAME']);
            $secondaryMenu->active = (int) $values['MENUPRO_SECONDARY_MENU_ACTIVE'];
            if ($secondaryMenu->item_type == MpSecondaryMenu::MENU_TYPE_CUSTOMISE) {
                $secondaryMenu->link_type = $values['MENUPRO_SECONDARY_MENU_LINK_TYPE'];
                $secondaryMenu->link = MpTools::fillMultilangEmptyFields($values['MENUPRO_SECONDARY_MENU_LINK']);
                $secondaryMenu->use_custom_content = (bool) $values['MENUPRO_SECONDARY_MENU_USE_CUSTOM_CONTENT'];
                if ($secondaryMenu->use_custom_content) {
                    $idHtmlContent = MpHtmlContent::getIdForSecondaryMenu($secondaryMenu->id);
                    $htmlContent = new MpHtmlContent();
                    if (! empty($idHtmlContent)) {
                        $htmlContent->id = $idHtmlContent;
                    }
                    $htmlContent->id_menupro_secondary_menu = $secondaryMenu->id;
                    $htmlContent->position = MpHtmlContent::POSITION_NONE;
                    $htmlContent->content =
                    MpTools::fillMultilangEmptyFields($values['MENUPRO_SECONDARY_MENU_CUSTOM_CONTENT']);
                    if (! $htmlContent->save()) {
                        $errors[] = $this->module->l('An error occurred while saving customized content', __CLASS__);
                    }
                }
            } elseif (($secondaryMenu->item_type == MpSecondaryMenu::MENU_TYPE_PRODUCT) ||
                    ($secondaryMenu->item_type == MpSecondaryMenu::MENU_TYPE_CATEGORY)) {
                $secondaryMenu->display_style = $values['MENUPRO_SECONDARY_MENU_DISPLAY_STYLE'];
                if ($secondaryMenu->item_type == MpSecondaryMenu::MENU_TYPE_CATEGORY) {
                    $secondaryMenu->associate_all = $values['MENUPRO_SECONDARY_MENU_ASSOCIATE_ALL'];
                }
            }
            if (empty($errors)) {
                if (MpTools::isMultilangFieldEmpty($values, 'MENUPRO_SECONDARY_MENU_TITLE')) {
                    $secondaryMenu->title = $secondaryMenu->name;
                } else {
                    $secondaryMenu->title = $values['MENUPRO_SECONDARY_MENU_TITLE'];
                }
                if (empty($secondaryMenu->parent_menu)) {
                    $secondaryMenu->parent_menu = null;
                }
                if (empty($values['MENUPRO_SECONDARY_MENU_ICON_CSS_CLASS'])) {
                    $secondaryMenu->id_menupro_icon = null;
                    $idIcon = MpIcon::getIdForSecondaryMenu($secondaryMenu->id);
                    if (! empty($idIcon)) {
                        $icon = new MpIcon();
                        $icon->id = $idIcon;
                        if (! $icon->delete()) {
                            $errors[] = $this->module->l('An error occurred while clearing menu icon', __CLASS__);
                        }
                    }
                } else {
                    $idIcon = MpIcon::getIdForSecondaryMenu($secondaryMenu->id);
                    $icon = new MpIcon();
                    if (! empty($idIcon)) {
                        $icon->id = $idIcon;
                    }
                    $icon->id_menupro_secondary_menu = $secondaryMenu->id;
                    $icon->position = MpIcon::POSITION_LEFT;
                    $icon->css_class = $values['MENUPRO_SECONDARY_MENU_ICON_CSS_CLASS'];
                    if (! $icon->save()) {
                        $errors[] = $this->module->l('An error occurred while saving menu icon', __CLASS__);
                    }
                }
                if (empty($errors)) {
                    $secondaryMenu->clickable = (bool) $values['MENUPRO_SECONDARY_MENU_CLICKABLE'];
                    $secondaryMenu->new_tab = (bool) $values['MENUPRO_SECONDARY_MENU_OPEN_NEW_TAB'];
                    if (! $secondaryMenu->update(true)) {
                        $errors[] = $this->module->l('An error occurred while updating menu', __CLASS__);
                    }
                }
            }
        }
        return array(
            'errors' => $errors,
            'menu' => $secondaryMenu
        );
    }
}
