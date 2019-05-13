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

class MpSelectableValue extends ObjectModel
{
    public $id_menupro_selectable_value;

    public $id_menupro_css_property;

    public $value;

    public $display_name;

    public static $definition = array(
        'table' => 'menupro_selectable_value',
        'primary' => 'id_menupro_selectable_value',
        'fields' => array(
            'display_name' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isCatalogName',
                'required' => true
            ),
            'id_menupro_css_property' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId',
                'required' => true
            ),
            'value' => array(
                'type' => self::TYPE_STRING
            )
        )
    );

    /**
     * Retourne les valeurs selectionnables d'une proprieté
     *
     * @return array
     */
    public static function getAll($idProperty)
    {
        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . self::$definition['table'] . ' WHERE id_menupro_css_property=' .
                 (int) $idProperty . ' ORDER BY ' . self::$definition['primary'] . ' ASC';
        $result = Db::getInstance()->executeS($sql);
        return $result;
    }

    /**
     * Ajoute une nouvelle valeur selectionnable
     *
     * @param int $idProperty
     *            L'identifiant de la propriété
     * @param string $idProperty
     *            L'identifiant de la propriété
     * @param string $displayName
     *            La valeur affichable à l'utilisateur
     * @return bool
     */
    public static function addNew($idProperty, $value, $displayName = '')
    {
        $selectable = new MpSelectableValue();
        $selectable->id_menupro_css_property = (int) $idProperty;
        $selectable->value = $value;
        $selectable->display_name = (empty($displayName) ? $selectable->value : $displayName);
        return $selectable->add();
    }
}
