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

include_once _PS_MODULE_DIR_ . 'menupro/classes/MpHtmlContent.php';
include_once _PS_MODULE_DIR_ . 'menupro/classes/MpTools.php';

class HtmlContentController
{
    public $module;

    public $context;

    public $local_path;

    public $propertyController;

    public function __construct($module, $context, $local_path)
    {
        $this->module = $module;
        $this->context = $context;
        $this->local_path = $local_path;
        $this->propertyController = new CSSPropertyController($module, $context, $local_path);
    }

    public function init($idMenu)
    {
        $this->context->smarty->assign(array(
            'displayHtmlTemplate' => $this->module->displayHtmlTemplate,
            'htmlContentList' => $this->renderList($idMenu),
            'idMenu' => $idMenu
        ));
        return $this->context->smarty->fetch($this->local_path . 'views/templates/admin/html_contents_block.tpl');
    }

    public function getForm()
    {
        $form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->module->l('Html content edition', __CLASS__)
                ),
                'tinymce' => true,
                'input' => array(
                    array(
                        'col' => 9,
                        'type' => 'select',
                        'label' => $this->module->l('Position', __CLASS__),
                        'name' => 'MENUPRO_HTML_CONTENT_POSITION',
                        'desc' => $this->module->l('Select a position', __CLASS__),
                        'required' => true,
                        'options' => array(
                            'query' => $this->getAvailablePositions(),
                            'id' => 'id',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'col' => 12,
                        'type' => 'textarea',
                        'label' => $this->module->l('Content', __CLASS__),
                        'lang' => true,
                        'name' => 'MENUPRO_HTML_CONTENT_CONTENT',
                        'rows' => 5,
                        'cols' => 40,
                        'class' => 'rte',
                        'required' => true,
                        'autoload_rte' => true
                    )
                ),
                'buttons' => array(
                    'btn_save' => array(
                        'name' => 'savesecondarymemu',
                        'id' => 'btnHtmlContentSave',
                        'type' => 'button',
                        'title' => $this->module->l('Save', __CLASS__),
                        'class' => 'btn btn-default pull-right',
                        'icon' => 'process-icon-save'
                    ),
                    'btn_cancel' => array(
                        'name' => 'cancelsecondarymemu',
                        'id' => 'btnHtmlContentCancel',
                        'type' => 'button',
                        'title' => $this->module->l('Cancel', __CLASS__),
                        'class' => 'btn btn-default pull-left',
                        'icon' => 'process-icon-cancel'
                    )
                )
            )
        );
        return $form;
    }

    public function processSave()
    {
        $result = array();
        $languages = Language::getLanguages(true);
        $values = $this->getFormPostedValues($languages);
        $result['errors'] = $this->getValidationErrors($values);
        if (empty($result['errors'])) {
            if ($values['MENUPRO_HTML_CONTENT_ID']) {
                $htmlContent = new MpHtmlContent((int) $values['MENUPRO_HTML_CONTENT_ID']);
            } else {
                $htmlContent = new MpHtmlContent();
                $htmlContent->active = true;
            }
            $htmlContent->id_menupro_secondary_menu = (int) $values['MENUPRO_HTML_CONTENT_MENU_ID'];
            $htmlContent->position = (int) $values['MENUPRO_HTML_CONTENT_POSITION'];
            $htmlContent->content = MpTools::fillMultilangEmptyFields($values['MENUPRO_HTML_CONTENT_CONTENT']);
            if ($htmlContent->save()) {
                if ($values['MENUPRO_HTML_CONTENT_ID']) {
                    $result['success']['message'] = $this->module->l('Html content updated successfully', __CLASS__);
                } else {
                    $result['success']['message'] = $this->module->l('Html content added successfully', __CLASS__);
                }
                $result['success']['id_content'] = $htmlContent->id;
                $result['success']['position'] = $this->getPositionNameId($htmlContent->position);
                $result['success']['active'] = $htmlContent->active;
                $this->module->clearAllTplCache();
            } else {
                $result['errors'][] = $this->module->l('An error occurred while saving html content', __CLASS__);
            }
        }
        return $result;
    }

    public function processDelete()
    {
        $result = array();
        $id = Tools::getValue('MENUPRO_HTML_CONTENT_ID');
        if (empty($id) || (! is_numeric($id)) || ((int) $id <= 0)) {
            $result['errors'][] = $this->module->l('Wrong id content', __CLASS__);
        } else {
            $htmlContent = new MpHtmlContent();
            $htmlContent->id = (int) $id;
            if ($htmlContent->delete()) {
                $result['success']['message'] = $this->module->l('Html content deleted successfully', __CLASS__);
            } else {
                $result['errors'][] = $this->module->l('An error occurred while deleting html content', __CLASS__);
            }
            $this->module->clearAllTplCache();
        }
        return $result;
    }

    public function processStatusChange()
    {
        $result = array();
        $id = Tools::getValue('MENUPRO_HTML_CONTENT_ID');
        if (empty($id) || (! is_numeric($id)) || ((int) $id <= 0)) {
            $result['errors'][] = $this->module->l('Wrong id content', __CLASS__);
        } else {
            $htmlContent = new MpHtmlContent((int) $id);
            if ($htmlContent->toggleStatus()) {
                $result['success']['message'] = $this->module->l('Html content status changed successfully', __CLASS__);
                $result['success']['active'] = $htmlContent->active;
                $this->module->clearAllTplCache();
            } else {
                $result['errors'][] = $this->module->l(
                    'An error occurred while changing html content status',
                    __CLASS__
                );
            }
        }
        return $result;
    }

    public function getValidationErrors($values)
    {
        $errors = array();
        if (MpTools::isMultilangFieldEmpty($values, 'MENUPRO_HTML_CONTENT_CONTENT')) {
            $errors[] = $this->module->l('Please Enter a content', __CLASS__);
        }
        if ((! is_numeric($values['MENUPRO_HTML_CONTENT_POSITION'])) ||
                ((int) $values['MENUPRO_HTML_CONTENT_POSITION'] <= 0)) {
            $errors[] = $this->module->l('Please select a correct position', __CLASS__);
        } else {
            $positionExist = MpHtmlContent::isPositionExist(
                $values['MENUPRO_HTML_CONTENT_MENU_ID'],
                $values['MENUPRO_HTML_CONTENT_POSITION'],
                $values['MENUPRO_HTML_CONTENT_ID']
            );
            if ($positionExist) {
                $errors[] = $this->module->l('A content is already save with this position', __CLASS__);
            }
        }
        if ((! is_numeric($values['MENUPRO_HTML_CONTENT_MENU_ID'])) ||
                ((int) $values['MENUPRO_HTML_CONTENT_MENU_ID'] <= 0)) {
            $errors[] = $this->module->l('Wrong menu id', __CLASS__);
        }
        if (! is_numeric($values['MENUPRO_HTML_CONTENT_ID'])) {
            $errors[] = $this->module->l('Wrong id html content', __CLASS__);
        }
        return $errors;
    }

    public function getFormPostedValues($languages)
    {
        $content = array();
        foreach ($languages as $language) {
            $language = (object) $language;
            if (Tools::getIsset('MENUPRO_HTML_CONTENT_CONTENT_' . $language->id_lang)) {
                $content[$language->id_lang] = Tools::getValue('MENUPRO_HTML_CONTENT_CONTENT_' . $language->id_lang);
            }
        }
        $values = array(
            'MENUPRO_HTML_CONTENT_CONTENT' => $content,
            'MENUPRO_HTML_CONTENT_POSITION' => Tools::getValue('MENUPRO_HTML_CONTENT_POSITION'),
            'MENUPRO_HTML_CONTENT_ID' => Tools::getValue('MENUPRO_HTML_CONTENT_ID'),
            'MENUPRO_HTML_CONTENT_MENU_ID' => Tools::getValue('MENUPRO_HTML_CONTENT_MENU_ID')
        );
        return $values;
    }

    public function renderList($idMenu)
    {
        $this->fields_list = array(
            'id_menupro_html_content' => array(
                'title' => $this->module->l('ID', __CLASS__),
                'type' => 'text',
                'search' => false,
                'orderby' => false
            ),
            'position' => array(
                'title' => $this->module->l('Position', __CLASS__),
                'type' => 'text',
                'search' => false,
                'orderby' => false
            ),
            'active' => array(
                'title' => $this->module->l('Status', __CLASS__),
                'active' => 'status',
                'type' => 'bool',
                'class' => 'fixed-width-xs',
                'align' => 'center',
                'search' => false,
                'orderby' => false
            )
        );
        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = false;
        $helper->identifier = MpHtmlContent::$definition['primary'];
        $helper->actions = array(
            'edit',
            'delete'
        );
        $helper->show_toolbar = true;
        $helper->toolbar_scroll = true;
        $helper->imageType = 'jpg';
        $helper->toolbar_btn['new'] = array(
            'href' => $this->module->getModuleHome() . '&action=add' . $this->module->name,
            'desc' => $this->module->l('Add new', __CLASS__)
        );
        $helper->title = $this->module->l('Html contents', __CLASS__);
        $helper->table = MpHtmlContent::$definition['table'];
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = $this->module->getModuleHome();
        $helper->no_link = true;
        
        $list = MpHtmlContent::getAll($idMenu);
        foreach ($list as $key => $value) {
            $list[$key]['position'] = $this->getPositionNameId($value['position']);
        }
        $helper->listTotal = count($list);
        
        return $helper->generateList($list, $this->fields_list);
    }

    public function getAvailablePositions()
    {
        $options = array(
            array(
                'id' => MpHtmlContent::POSITION_TOP,
                'name' => $this->module->l('Top', __CLASS__)
            ),
            array(
                'id' => MpHtmlContent::POSITION_RIGHT,
                'name' => $this->module->l('Right', __CLASS__)
            ),
            array(
                'id' => MpHtmlContent::POSITION_DOWN,
                'name' => $this->module->l('Footer', __CLASS__)
            ),
            array(
                'id' => MpHtmlContent::POSITION_LEFT,
                'name' => $this->module->l('Left', __CLASS__)
            )
        );
        return $options;
    }

    public function getPositionNameId($id)
    {
        $options = $this->getAvailablePositions();
        foreach ($options as $option) {
            if ($option['id'] == (int) $id) {
                return $option['name'];
            }
        }
        return 'none';
    }
}
