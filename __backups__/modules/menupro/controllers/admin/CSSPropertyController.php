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

include_once _PS_MODULE_DIR_ . 'menupro/classes/MpCSSPropertyMenu.php';
include_once _PS_MODULE_DIR_ . 'menupro/classes/MpCSSProperty.php';

class CSSPropertyController
{
    public $module;

    public $context;

    public $local_path;

    public function __construct($module, $context, $local_path)
    {
        $this->module = $module;
        $this->context = $context;
        $this->local_path = $local_path;
    }

    public function getForm($menuType, $styleType, $menuLevel, $idStyle, $list = null, $disableFields = false)
    {
        $paramList = ((isset($list) && (! empty($list))) ? $list : $this->getPropertiesList($styleType, $idStyle));
        $this->context->smarty->assign(array(
            'ps_version' => _PS_VERSION_,
            'cssProperties' => $paramList,
            'menuLevel' => $menuLevel,
            'menuType' => $menuType,
            'styleType' => $styleType,
            'idStyle' => $idStyle,
            'disableFields' => $disableFields
        ));
        return $this->context->smarty->fetch($this->local_path . 'views/templates/admin/properties_block.tpl');
    }

    public function getPropertiesLabels()
    {
        $list = array(
            'background-color' => $this->module->l('Background Color', __CLASS__),
            'color' => $this->module->l('Color', __CLASS__),
            'font-size' => $this->module->l('Font size', __CLASS__),
            'font-family' => $this->module->l('Font family', __CLASS__),
            'font-weight' => $this->module->l('Font weight', __CLASS__),
            'text-transform' => $this->module->l('Text Transform', __CLASS__),
            'text-decoration' => $this->module->l('Text Decoration', __CLASS__)
        );
        return $list;
    }

    public function getPropertiesSelectablesValuesLabels()
    {
        $list = array(
            'bolder' => $this->module->l('Bolder', __CLASS__),
            'lighter' => $this->module->l('Lighter', __CLASS__),
            'normal' => $this->module->l('Normal', __CLASS__),
            'inherit' => $this->module->l('Inherit', __CLASS__),
            'initial' => $this->module->l('Initial', __CLASS__),
            'bold' => $this->module->l('Bold', __CLASS__),
            'uppercase' => $this->module->l('Uppercase', __CLASS__),
            'capitalize' => $this->module->l('Capitalize', __CLASS__),
            'lowercase' => $this->module->l('Lowercase', __CLASS__),
            'none' => $this->module->l('None', __CLASS__),
            'blink' => $this->module->l('Blink', __CLASS__),
            'line-through' => $this->module->l('Line-through', __CLASS__),
            'overline' => $this->module->l('Overline', __CLASS__),
            'underline' => $this->module->l('Underline', __CLASS__)
        );
        return $list;
    }

    public function getPropertiesEventLabels()
    {
        $list = array(
            'hover' => $this->module->l('On Hover', __CLASS__),
            'active' => $this->module->l('Activated', __CLASS__)
        );
        return $list;
    }

    public function getValidationErrors($values)
    {
        $errors = array();
        if (empty($values['MENUPRO_PROPERTIES_COUNT']) ||
                (! is_numeric($values['MENUPRO_PROPERTIES_COUNT'])) ||
                ((int) $values['MENUPRO_PROPERTIES_COUNT'] <0)) {
            $errors[] = $this->module->l('Wrong property count', __CLASS__);
        }
        for ($i = 0; $i < (int) $values['MENUPRO_PROPERTIES_COUNT']; $i ++) {
            if (empty($values['properties'][$i]['id_menupro_css_property']) ||
                    (! is_numeric($values['properties'][$i]['id_menupro_css_property'])) ||
                    ((int) $values['properties'][$i]['id_menupro_css_property'] <0)) {
                $errors[] = $this->module->l('Wrong property id', __CLASS__);
            }
            if ((! is_numeric($values['properties'][$i]['usable_value'])) ||
                    ((int) $values['properties'][$i]['usable_value'] < 0)) {
                $errors[] = $this->module->l('Wrong property usable value', __CLASS__);
            }
            if (((int) $values['properties'][$i]['usable_value'] == MpCSSPropertyMenu::USABLE_VALUE_CUSTOMIZED) &&
                    (empty($values['properties'][$i]['value']))) {
                $errors[] = sprintf(
                    $this->module->l(
                        'If usable value is customized for property %s, you must enter a correct value',
                        __CLASS__
                    ),
                    $values['properties'][$i]['display_name']
                );
            }
        }
        return $errors;
    }

    public function getFormPostedValues()
    {
        $values = array(
            'MENUPRO_STYLE_TYPE' => Tools::getValue('MENUPRO_STYLE_TYPE'),
            'MENUPRO_ID_STYLE' => Tools::getValue('MENUPRO_ID_STYLE'),
            'MENUPRO_PROPERTIES_COUNT' => Tools::getValue('MENUPRO_PROPERTIES_COUNT')
        );
        $values['properties'] = array();
        for ($i = 0; $i < (int) $values['MENUPRO_PROPERTIES_COUNT']; $i ++) {
            $values['properties'][] = array(
                'id_menupro_css_property' => Tools::getValue('MENUPRO_PROPERTY_ID_' . $i),
                'usable_value' => Tools::getValue('MENUPRO_PROPERTY_USABLE_VALUE_' . $i),
                'value' => Tools::getValue('MENUPRO_PROPERTY_VALUE_' . $i),
                'display_name' => Tools::getValue('MENUPRO_PROPERTY_DISPLAY_NAME_' . $i),
                'default_value' => Tools::getValue('MENUPRO_PROPERTY_DEFAULT_VALUE_' . $i),
                'id_property_base' => Tools::getValue('MENUPRO_PROPERTY_ID_BASE_' . $i),
                'type' => Tools::getValue('MENUPRO_PROPERTY_TYPE_' . $i)
            );
        }
        return $values;
    }

    public function getPropertiesList($styleType, $idStyle)
    {
        $propertiesLabels = $this->getPropertiesLabels();
        $eventLabels = $this->getPropertiesEventLabels();
        $selectablesValuesLabels = $this->getPropertiesSelectablesValuesLabels();
        $childrenContainerLabel = $this->module->l('For submenus', __CLASS__);
        if (! empty($idStyle)) {
            return MpCSSPropertyMenu::getStylePropertiesWithInfos(
                $idStyle,
                $styleType,
                true,
                $propertiesLabels,
                $eventLabels,
                $selectablesValuesLabels,
                $childrenContainerLabel
            );
        } else {
            return MpCSSProperty::getAll(
                true,
                $propertiesLabels,
                $eventLabels,
                $selectablesValuesLabels,
                $childrenContainerLabel
            );
        }
    }

    public function processGetValue()
    {
        $result = array();
        $idProperty = Tools::getValue('MENUPRO_GET_PROPERTY_VALUE_ID_PROPERTY');
        $usableValue = (int) Tools::getValue('MENUPRO_GET_PROPERTY_VALUE_USABLE_VALUE');
        $menuType = (int) Tools::getValue('MENUPRO_GET_PROPERTY_VALUE_MENU_TYPE');
        $menuLevel = (int) Tools::getValue('MENUPRO_GET_PROPERTY_VALUE_MENU_LEVEL');
        $idMenu = (int) Tools::getValue('MENUPRO_GET_PROPERTY_VALUE_ID_MENU');
        if (empty($idProperty) || (! is_numeric($idProperty)) || ((int) $idProperty <= 0)) {
            $result['errors'][] = $this->module->l('Wrong property id', __CLASS__);
        }
        if (empty($result['errors'])) {
            $resultValue = MpCSSPropertyMenu::getValueFromUsable(
                $usableValue,
                $idProperty,
                $menuLevel,
                $menuType,
                $idMenu
            );
            $value = $resultValue['value'];
            switch ($resultValue['type_value']) {
                case MpCSSPropertyMenu::VALUE_NOT_ACCESSIBLE:
                    $value = $this->module->l('Value cannot be visible here', __CLASS__);
                    break;
                case MpCSSPropertyMenu::VALUE_NOT_YET_DEFINED:
                    $value = $this->module->l('Value is not yet defined', __CLASS__);
                    break;
                case MpCSSPropertyMenu::VALUE_WRONG_CONFIG:
                    $value = $this->module->l('Value will never be accessible', __CLASS__);
                    break;
            }
            $result['success']['value'] = $value;
            $result['success']['type_value'] = $resultValue['type_value'];
        }
        return $result;
    }
}
