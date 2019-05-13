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

include_once _PS_MODULE_DIR_ . 'menupro/classes/MpSelectableValue.php';

class MpCSSProperty extends ObjectModel
{

    const PROPERTY_TYPE_OTHER = 0;

    const PROPERTY_TYPE_COLOR = 1;

    const PROPERTY_TYPE_SELECT = 2;

    const PROPERTY_TYPE_SELECT_EDITABLE = 3;

    const EVENT_NONE = 'no_event';

    const EVENT_HOVER = 'hover';

    const EVENT_ACTIVE = 'active';

    public $id_menupro_css_property;

    public $name;

    public $display_name;

    public $type;

    public $default_value;

    public $event;

    public $for_container;

    public $id_property_base;

    public static $definition = array(
        'table' => 'menupro_css_property',
        'primary' => 'id_menupro_css_property',
        'fields' => array(
            'name' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isCatalogName',
                'required' => true
            ),
            'display_name' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isCatalogName',
                'required' => true
            ),
            'type' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId'
            ),
            'default_value' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isCatalogName',
                'required' => true
            ),
            'event' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isCatalogName'
            ),
            'id_property_base' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId'
            ),
            'for_container' => array(
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool'
            )
        )
    );

    /**
     * Enregistrement des propriétés CSS configurables
     */
    public static function insertBaseProperty()
    {
        $property = new MpCSSProperty();
        $property->name = 'background-color';
        $property->display_name = 'Background Color';
        $property->default_value = "white";
        $property->type = self::PROPERTY_TYPE_COLOR;
        $property->event = self::EVENT_NONE;
        $property->for_container = false;
        $property->id_property_base = 0;
        $property->add();
        $idBase = $property->id;
        $property->id_property_base = 0;
        $property->name = 'background-color';
        $property->display_name = 'Background Color For children container';
        $property->default_value = "white";
        $property->for_container = true;
        $property->type = self::PROPERTY_TYPE_COLOR;
        $property->event = self::EVENT_NONE;
        $property->add();
        $property->name = 'background-color';
        $property->display_name = 'Background Color : HOVER';
        $property->default_value = "white";
        $property->type = self::PROPERTY_TYPE_COLOR;
        $property->event = self::EVENT_HOVER;
        $property->for_container = false;
        $property->id_property_base = $property->id;
        $property->add();
        $property->name = 'background-color';
        $property->display_name = 'Background Color : ACTIVATED';
        $property->default_value = "white";
        $property->type = self::PROPERTY_TYPE_COLOR;
        $property->event = self::EVENT_ACTIVE;
        $property->for_container = false;
        $property->id_property_base = $idBase;
        $property->add();
        
        $property->id_property_base = 0;
        $property->name = 'color';
        $property->display_name = 'Color';
        $property->default_value = "black";
        $property->type = self::PROPERTY_TYPE_COLOR;
        $property->event = self::EVENT_NONE;
        $property->id_property_base = $property->id;
        $property->add();
        $idBase = $property->id;
        $property->name = 'color';
        $property->display_name = 'Color : HOVER';
        $property->default_value = "black";
        $property->type = self::PROPERTY_TYPE_COLOR;
        $property->event = self::EVENT_HOVER;
        $property->id_property_base = $property->id;
        $property->add();
        $property->name = 'color';
        $property->display_name = 'Color : ACTIVATED';
        $property->default_value = "black";
        $property->type = self::PROPERTY_TYPE_COLOR;
        $property->event = self::EVENT_ACTIVE;
        $property->id_property_base = $idBase;
        $property->add();
        
        $property->id_property_base = 0;
        $property->name = 'font-size';
        $property->display_name = 'Font size';
        $property->default_value = "12px";
        $property->type = self::PROPERTY_TYPE_SELECT_EDITABLE;
        $property->event = self::EVENT_NONE;
        $property->add();
        MpSelectableValue::addNew($property->id, '8px');
        MpSelectableValue::addNew($property->id, '9px');
        MpSelectableValue::addNew($property->id, '10px');
        MpSelectableValue::addNew($property->id, '11px');
        MpSelectableValue::addNew($property->id, '12px');
        MpSelectableValue::addNew($property->id, '13px');
        MpSelectableValue::addNew($property->id, '14px');
        MpSelectableValue::addNew($property->id, '15px');
        MpSelectableValue::addNew($property->id, '16px');
        MpSelectableValue::addNew($property->id, '17px');
        MpSelectableValue::addNew($property->id, '18px');
        MpSelectableValue::addNew($property->id, '18px');
        MpSelectableValue::addNew($property->id, '19px');
        MpSelectableValue::addNew($property->id, '20px');
        
        $idBase = $property->id;
        $property->name = 'font-size';
        $property->display_name = 'Font size : HOVER';
        $property->default_value = "12px";
        $property->type = self::PROPERTY_TYPE_SELECT_EDITABLE;
        $property->event = self::EVENT_HOVER;
        $property->id_property_base = $property->id;
        $property->add();
        $property->name = 'font-size';
        $property->display_name = 'Font size : ACTIVATED';
        $property->default_value = "12px";
        $property->type = self::PROPERTY_TYPE_SELECT_EDITABLE;
        $property->event = self::EVENT_ACTIVE;
        $property->id_property_base = $idBase;
        $property->add();
        
        $property->id_property_base = 0;
        $property->name = 'font-family';
        $property->display_name = 'Font family';
        $property->default_value = 'Arial';
        $property->type = self::PROPERTY_TYPE_SELECT_EDITABLE;
        $property->event = self::EVENT_NONE;
        $property->add();
        MpSelectableValue::addNew($property->id, 'Arial');
        MpSelectableValue::addNew($property->id, 'monospace');
        MpSelectableValue::addNew($property->id, 'cursive');
        MpSelectableValue::addNew($property->id, 'sans-serif');
        MpSelectableValue::addNew($property->id, 'serif');
        MpSelectableValue::addNew($property->id, 'inherit');
        MpSelectableValue::addNew($property->id, 'initial');
        $idBase = $property->id;
        $property->name = 'font-family';
        $property->display_name = 'Font family : HOVER';
        $property->default_value = "Arial";
        $property->type = self::PROPERTY_TYPE_SELECT_EDITABLE;
        $property->event = self::EVENT_HOVER;
        $property->id_property_base = $property->id;
        $property->add();
        $property->name = 'font-family';
        $property->display_name = 'Font family : ACTIVATED';
        $property->default_value = "Arial";
        $property->type = self::PROPERTY_TYPE_SELECT_EDITABLE;
        $property->event = self::EVENT_ACTIVE;
        $property->id_property_base = $idBase;
        $property->add();
        
        $property->id_property_base = 0;
        $property->name = 'font-weight';
        $property->display_name = 'Font weight';
        $property->default_value = "bold";
        $property->type = self::PROPERTY_TYPE_SELECT_EDITABLE;
        $property->event = self::EVENT_NONE;
        $property->add();
        MpSelectableValue::addNew($property->id, '100');
        MpSelectableValue::addNew($property->id, '200');
        MpSelectableValue::addNew($property->id, '300');
        MpSelectableValue::addNew($property->id, '400');
        MpSelectableValue::addNew($property->id, '500');
        MpSelectableValue::addNew($property->id, '600');
        MpSelectableValue::addNew($property->id, '700');
        MpSelectableValue::addNew($property->id, '800');
        MpSelectableValue::addNew($property->id, '900');
        MpSelectableValue::addNew($property->id, 'bold');
        MpSelectableValue::addNew($property->id, 'bolder');
        MpSelectableValue::addNew($property->id, 'lighter');
        MpSelectableValue::addNew($property->id, 'normal');
        MpSelectableValue::addNew($property->id, 'inherit');
        MpSelectableValue::addNew($property->id, 'initial');
        $idBase = $property->id;
        $property->name = 'font-weight';
        $property->display_name = 'Font weight : HOVER';
        $property->default_value = "bold";
        $property->type = self::PROPERTY_TYPE_SELECT_EDITABLE;
        $property->event = self::EVENT_HOVER;
        $property->id_property_base = $property->id;
        $property->add();
        $property->name = 'font-weight';
        $property->display_name = 'Font weight : ACTIVATED';
        $property->default_value = "bold";
        $property->type = self::PROPERTY_TYPE_SELECT_EDITABLE;
        $property->event = self::EVENT_ACTIVE;
        $property->id_property_base = $idBase;
        $property->add();
        
        $property->id_property_base = 0;
        $property->name = 'text-transform';
        $property->display_name = 'Text Transform';
        $property->default_value = "uppercase";
        $property->type = self::PROPERTY_TYPE_SELECT;
        $property->event = self::EVENT_NONE;
        $property->add();
        MpSelectableValue::addNew($property->id, 'uppercase');
        MpSelectableValue::addNew($property->id, 'capitalize');
        MpSelectableValue::addNew($property->id, 'lowercase');
        MpSelectableValue::addNew($property->id, 'none');
        MpSelectableValue::addNew($property->id, 'inherit');
        MpSelectableValue::addNew($property->id, 'initial');
        $idBase = $property->id;
        $property->name = 'text-transform';
        $property->display_name = 'Text Transform : HOVER';
        $property->default_value = "uppercase";
        $property->type = self::PROPERTY_TYPE_SELECT;
        $property->event = self::EVENT_HOVER;
        $property->id_property_base = $property->id;
        $property->add();
        $property->name = 'text-transform';
        $property->display_name = 'Text Transform : ACTIVATED';
        $property->default_value = "uppercase";
        $property->type = self::PROPERTY_TYPE_SELECT;
        $property->event = self::EVENT_ACTIVE;
        $property->id_property_base = $idBase;
        $property->add();
        
        $property->id_property_base = 0;
        $property->name = 'text-decoration';
        $property->display_name = 'Text Decoration';
        $property->default_value = "none";
        $property->type = self::PROPERTY_TYPE_SELECT;
        $property->event = self::EVENT_NONE;
        $property->add();
        MpSelectableValue::addNew($property->id, 'none');
        MpSelectableValue::addNew($property->id, 'blink');
        MpSelectableValue::addNew($property->id, 'line-through');
        MpSelectableValue::addNew($property->id, 'overline');
        MpSelectableValue::addNew($property->id, 'underline');
        MpSelectableValue::addNew($property->id, 'inherit');
        MpSelectableValue::addNew($property->id, 'initial');
        $idBase = $property->id;
        $property->name = 'text-decoration';
        $property->display_name = 'Text Decoration : HOVER';
        $property->default_value = "none";
        $property->type = self::PROPERTY_TYPE_SELECT;
        $property->event = self::EVENT_HOVER;
        $property->id_property_base = $property->id;
        $property->add();
        $property->name = 'text-decoration';
        $property->display_name = 'Text Decoration : ACTIVATED';
        $property->default_value = "none";
        $property->type = self::PROPERTY_TYPE_SELECT;
        $property->event = self::EVENT_ACTIVE;
        $property->id_property_base = $idBase;
        $property->add();
    }

    /**
     * Retourne la liste des propriétés CSS
     *
     * @param bool $withSelectabe
     *            Permet de spécifier si l'on veut aussi recuperer les valeurs
     *            selectionnables de chaque proprietés
     * @param array $propertiesLabels
     *            Contient les libellés pour chaque propriété
     * @param array $eventLabels
     *            Contient les libellés pour chaque evènement
     * @param array $selectablesValuesLabels
     *            Contient les libellés pour chaque valeur selectionnable
     * @param string $childrenContainerLabel
     *            Contient le libellé pour "for children container"
     * @return array
     */
    public static function getAll($withSelectabe = false, $propertiesLabels = array(), $eventLabels = array(), $selectablesValuesLabels = array(), $childrenContainerLabel = null)
    {
        $sql  = 'SELECT * FROM ' . _DB_PREFIX_ . self::$definition['table'] . ' ORDER BY ' .
                self::$definition['primary'] . ' ASC';
        $result = Db::getInstance()->executeS($sql);
        if ($withSelectabe) {
            $result = self::addSelectableValuesToList(
                $result,
                array(),
                $propertiesLabels,
                $eventLabels,
                $selectablesValuesLabels,
                $childrenContainerLabel
            );
        }
        return $result;
    }

    /**
     * Ajoute les valeurs selectionnables à une liste et insert les libellés des
     * proprietés
     *
     * @param array $list
     *            Liste des proprietés
     * @param array $selectableValuesList
     *            Valeurs sélectionnables déjà récupérés
     * @param array $propertiesLabels
     *            Contient les libellés pour chaque propriété
     * @param array $eventLabels
     *            Contient les libellés pour chaque evènement
     * @param array $selectablesValuesLabels
     *            Contient les libellés pour chaque valeur selectionnable
     * @param string $childrenContainerLabel
     *            Contient le libellé pour "for children container"
     * @return array
     */
    public static function addSelectableValuesToList($list, $selectableValuesList = array(), $propertiesLabels = array(), $eventLabels = array(), $selectablesValuesLabels = array(), $childrenContainerLabel = null)
    {
        foreach ($list as $key => $property) {
            self::addSelectableToPropertyAndFixLabels(
                $selectableValuesList,
                $list[$key],
                $propertiesLabels,
                $eventLabels,
                $selectablesValuesLabels,
                $childrenContainerLabel
            );
        }
        return $list;
    }

    /**
     * Ajoute les valeurs selectionnables à une proprité et modifie son libellé
     *
     * @param array $selectableValuesList
     *            Valeurs sélectionnables déjà récupérés
     * @param array $property
     *            La proprieté
     * @param array $propertiesLabels
     *            Contient les libellés pour chaque propriété
     * @param array $eventLabels
     *            Contient les libellés pour chaque evènement
     * @param array $selectablesValuesLabels
     *            Contient les libellés pour chaque valeur selectionnable
     * @param string $childrenContainerLabel
     *            Contient le libellé pour "for children container"
     */
    public static function addSelectableToPropertyAndFixLabels(&$selectableValuesList, &$property, $propertiesLabels = array(), $eventLabels = array(), $selectablesValuesLabels = array(), $childrenContainerLabel = null)
    {
        $needToSetSelectablesValues = ($property['type'] == self::PROPERTY_TYPE_SELECT) || ($property['type'] ==
                 self::PROPERTY_TYPE_SELECT_EDITABLE);
        $idProperty = 0;
        if ($needToSetSelectablesValues) {
            $idProperty = ((empty($property['id_property_base'])) ?
                    $property['id_menupro_css_property'] : $property['id_property_base']);
            $idProperty = (int) $idProperty;
            if (! isset($selectableValuesList[$idProperty])) {
                $selectableValuesList[$idProperty] = MpSelectableValue::getAll($idProperty);
            }
        }
        if (! empty($propertiesLabels)) {
            if (isset($propertiesLabels[$property['name']])) {
                $property['display_name'] = $propertiesLabels[$property['name']];
            }
        }
        if ($childrenContainerLabel !== null) {
            if ($property['for_container']) {
                $property['display_name'] .= ' ' . $childrenContainerLabel;
            }
        }
        
        if (! empty($eventLabels)) {
            if ($property['event'] != self::EVENT_NONE) {
                if (isset($eventLabels[$property['event']])) {
                    $property['display_name'] .= ' : ' . $eventLabels[$property['event']];
                }
            }
        }
        if ($needToSetSelectablesValues && (! empty($selectablesValuesLabels))) {
            foreach ($selectableValuesList[$idProperty] as $key => $selectableValue) {
                if (isset($selectablesValuesLabels[$selectableValue['value']])) {
                    $selectableValuesList[$idProperty][$key]['display_name'] =
                    $selectablesValuesLabels[$selectableValue['value']];
                }
            }
        }
        if ($needToSetSelectablesValues) {
            $property['selectable_values'] = $selectableValuesList[$idProperty];
        }
    }

    /**
     * Retourne le nombre de proprietés
     *
     * @return int
     */
    public static function getPropertiesCount()
    {
        return (int) Db::getInstance()->getValue('SELECT COUNT(*) FROM ' . _DB_PREFIX_ . 'menupro_css_property');
    }

    /**
     * Retourne la valeur par défaut d'une propriété
     *
     * @param int $idProperty
     *            Identifiant de la proprieté
     * @return string
     */
    public static function getDefaultValueById($idProperty)
    {
        $property = new MpCSSProperty((int) $idProperty);
        return $property->default_value;
    }

    /**
     * Retourne une proprieté comme une chaine de caractère prêt à être utilisé
     *
     * @param string $propertName
     *            Nom de la proprieté
     * @param string $propertyValue
     *            Valeur de la proprieté
     * @param string $event
     *            Evenement associé à la proprieté
     * @return string
     */
    public static function getAsString($propertName, $propertyValue, $event)
    {
        if ($event == self::EVENT_HOVER) {
            return '\'' . $propertName . '\'' . ':\'' . $propertyValue . '\',';
        } else {
            return $propertName . ':' . $propertyValue . ';';
        }
    }
}
