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

class MpTools
{
    /**
     * Verifie si un champs multilingue est vide
     *
     * @param array $values
     *            La liste des enregistrements
     * @param string $index
     *            La clé du champs
     * @return bool
     */
    public static function isMultilangFieldEmpty($values, $index)
    {
        $emptyField = true;
        foreach ($values[$index] as $value) {
            if ($value) {
                $emptyField = false;
                break;
            }
        }
        return $emptyField;
    }
    
    /**
     * Renseigne les valeurs vide d'un champs multilingue
     *
     * @param array $values
     *            Les valeurs du champs
     * @return array
     */
    public static function fillMultilangEmptyFields($values)
    {
        $defaultValue = ((isset($values[Configuration::get('PS_LANG_DEFAULT')]) &&
                (!empty($values[Configuration::get('PS_LANG_DEFAULT')]))) ?
                $values[Configuration::get('PS_LANG_DEFAULT')] : '');
        //Recherche d'une valeur non nulle
        if (empty($defaultValue)) {
            foreach ($values as $value) {
                if (!empty($value)) {
                    $defaultValue= $value;
                    break;
                }
            }
        }
        foreach ($values as $key => $value) {
            if (empty($value)) {
                $values[$key] = $defaultValue;
            }
        }
        return $values;
    }
}
