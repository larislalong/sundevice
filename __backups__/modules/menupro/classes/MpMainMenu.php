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

class MpMainMenu extends ObjectModel
{
    const MENU_TYPE_MEGA = 1;

    const MENU_TYPE_SIMPLE = 2;

    const MENU_TYPE_SIDE_SIMPLE = 3;

    const MENU_TYPE_LEFT_1 = 4;

    const MENU_TYPE_RIGHT_1 = 5;

    const MENU_TYPE_FOOTER_1 = 6;

    const ADD_SUCCESS_CODE = 1;

    const UPDATE_SUCCESS_CODE = 2;

    const DELETE_SUCCESS_CODE = 3;

    const STATUS_CHANGE_SUCCESS_CODE = 4;

    const BULK_DELETE_SUCCESS_CODE = 5;

    const BULK_ENABLE_SUCCESS_CODE = 6;

    const BULK_DISABLE_SUCCESS_CODE = 7;

    public $id_menupro_main_menu;

    public $name;

    public $hook;

    public $menu_type;

    public $number_menu_per_ligne;

    public $show_search_bar;

    public $active;

    public static $definition = array(
        'table' => 'menupro_main_menu',
        'multilang' => true,
        'primary' => 'id_menupro_main_menu',
        'fields' => array(
            'hook' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId',
                'required' => true
            ),
            'menu_type' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId',
                'required' => true
            ),
            'number_menu_per_ligne' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId',
                'required' => true
            ),
            'name' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isCatalogName',
                'size' => 50
            ),
            'show_search_bar' => array(
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool'
            ),
            'active' => array(
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool'
            )
        )
    );

    public static function getAll($idLang, $start = 0, $limit = 0, $order_by = 'id_menupro_main_menu', $order_way = 'DESC', $only_active = false, Context $context = null)
    {
        if (! $context) {
            $context = Context::getContext();
        }
        
        if ($order_by == 'id_menupro_main_menu' || $order_by == 'hook') {
            $order_by_prefix = 'mp';
        } elseif ($order_by == 'name') {
            $order_by_prefix = 'mpl';
        }
        $sql = 'SELECT mp.id_menupro_main_menu, mp.hook, mp.menu_type, mp.active, mpl.name FROM ' .
                _DB_PREFIX_ . 'menupro_main_menu mp' . ' LEFT JOIN ' . _DB_PREFIX_ .
                'menupro_main_menu_lang mpl ON mp.id_menupro_main_menu=mpl.id_menupro_main_menu AND mpl.id_lang=' .
                (int) $idLang . ($only_active ? ' WHERE mp.`active` = 1' : '') . ' ORDER BY ' .
                (isset($order_by_prefix) ? pSQL($order_by_prefix) .
                '.' : '') . '`' . pSQL($order_by) . '` ' . pSQL($order_way) . ($limit > 0 ? ' LIMIT ' . (int) $start .
                ',' . (int) $limit : '');
        $result = Db::getInstance()->executeS($sql);
        return $result;
    }

    public static function getMainMenuCount()
    {
        return (int) Db::getInstance()->getValue('SELECT COUNT(*) FROM ' . _DB_PREFIX_ . 'menupro_main_menu');
    }

    /**
     * Retourne les hooks greffé à un module
     *
     * @param int $idModule
     *            L'identifiant du module
     * @return array
     */
    public static function getModuleHook($idModule)
    {
        $result = Db::getInstance()->executeS('SELECT * FROM ' . _DB_PREFIX_ . 'hook_module hm' . ' INNER JOIN ' .
                 _DB_PREFIX_ . 'hook h ON (hm.id_hook=h.id_hook AND hm.id_module=' . (int) $idModule .
                 ') ORDER BY h.name ASC');
        return $result;
    }

    /**
     * Retourne les boutiques associées à un menu
     *
     * @param int $idMenu
     *            L'identifiant du menu
     * @return array
     */
    public static function getMPAssociatedShops($idMenu)
    {
        $list = array();
        $sql = 'SELECT id_shop FROM `' . _DB_PREFIX_ . self::$definition['table'] . '_shop` WHERE `' .
                 self::$definition['primary'] . '` = ' . (int) $idMenu;
        foreach (Db::getInstance()->executeS($sql) as $row) {
            $list[] = $row['id_shop'];
        }
        
        return $list;
    }

    /**
     * Retourne les noms des boutiques associées à un menu sous forme de chaine
     * de caractère
     *
     * @param int $idMenu
     *            L'identifiant du menu
     * @param int $idLang
     *            L'identifiant de la langue
     * @return array
     */
    public static function getShopNamesAssociated($idMenu, $idLang, $associatedIds = array())
    {
        if (empty($associatedIds)) {
            $result = self::getMPAssociatedShops($idMenu);
        } else {
            $result = $associatedIds;
        }
        
        $names = '';
        foreach ($result as $row) {
        	if (_PS_VERSION_>='1.6') {
        		$shop = new Shop((int) $row, $idLang);
        	}else{
        		$shop = new Shop((int) $row);
        	}
            if (! empty($names)) {
                $names .= ', ';
            }
            $names .= $shop->name;
        }
        return $names;
    }

    /**
     * Verifie si un menu existe pour un hook
     *
     * @param int $hook
     *            L'identifiant du hook
     * @param int $idMenu
     *            L'identifiant du menu
     * @param array $result
     *            Les menus crée pour ce hook
     * @param array $associatedShops
     *            Les boutiques associées
     * @return bool
     */
    public static function isMenuExistForHook($hook, $idMenu = 0, $result = array(), $associatedShops = array())
    {
        $associated = false;
        if (empty($result)) {
            $result = self::getMenuForHook($hook, $idMenu);
        }
        if (empty($associatedShops)) {
            $result = self::getMenuForHook($hook, $idMenu);
        }
        foreach ($result as $menu) {
            $mainMenu = new MpMainMenu();
            $mainMenu->id = $menu[self::$definition['primary']];
            if ($associated) {
                break;
            }
        }
        return $associated;
    }

    /**
     * Retourne le menu enregistré sur un hook
     *
     * @param int $hook
     *            L'identifiant du hook
     * @param int $idMenu
     *            L'identifiant du menu
     * @param bool $withAssociatedShop
     *            Permet de spécifier si pour chaque menu on recupère egalement
     *            ses boutique associées
     * @param int $idShop
     *            L'identifiant de la boutique
     * @param int|bool $idLang
     *            L'identifiant de la langue
     *            (s'il la valeur est false, il n'y aura pas de jointure avec la
     *            table de langue)
     * @return array
     */
    public static function getMenuForHook($hook, $idMenu = 0, $withAssociatedShop = false, $idShop = 0, $idLang = false)
    {
        if (empty($hook)) {
            return array();
        }
        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . self::$definition['table'] . ' t ' . (($idLang) ? ('LEFT JOIN ' .
             _DB_PREFIX_ . self::$definition['table'] . '_lang tl ON (t.' . self::$definition['primary'] . ' = tl.' .
             self::$definition['primary'] . ' AND tl.id_lang = ' . (int) $idLang . ')') : '') . ' WHERE hook=' .
             (int) $hook . ((! empty($idMenu)) ? ' AND id_menupro_main_menu <>' . (int) $idMenu : '');
        $result = Db::getInstance()->executeS($sql);
        if ($withAssociatedShop) {
            foreach ($result as $key => $value) {
                $result[$key]['shops'] = self::getMPAssociatedShops($value[self::$definition['primary']]);
            }
        }
        if (! empty($idShop)) {
            $items = $result;
            $result = array();
            foreach ($items as $key => $value) {
                $associatedShop = self::getMPAssociatedShops($value[self::$definition['primary']]);
                if (in_array($idShop, $associatedShop)) {
                    $result = $value;
                    break;
                }
            }
        }
        return $result;
    }

    /**
     * Retourne les preferences en fonction des hooks par exemple l'affichage ou
     * pas de la barre de recherche pour un hook,
     * les templates disponibles pour un hook, le suffix des fichier template,
     * le nom de l'image pour la previsualisation
     *
     * @return array
     */
    public static function getHookPreferences($menuTypeLables = array())
    {
        $hookLeftId = Hook::getIdByName('displayLeftColumn');
        $hookRightId = Hook::getIdByName('displayRightColumn');
        $hookFooterId = Hook::getIdByName('displayFooter');
        $sideMenuTypeList = array(
            self::MENU_TYPE_SIDE_SIMPLE => array(
                'file_suffix' => 'side_simple',
                'label' => (((! empty($menuTypeLables)) && isset($menuTypeLables[self::MENU_TYPE_SIDE_SIMPLE])) ?
                        $menuTypeLables[self::MENU_TYPE_SIDE_SIMPLE] : 'side_simple'),
                        'image_file_name' => 'side_simple.png'
            )
        );
        $rightMenuTypeList = array(
            self::MENU_TYPE_RIGHT_1 => array(
                'file_suffix' => 'right_1',
                'label' => (((! empty($menuTypeLables)) && isset($menuTypeLables[self::MENU_TYPE_RIGHT_1])) ?
                        $menuTypeLables[self::MENU_TYPE_RIGHT_1] : 'right_1'),
                        'image_file_name' => 'right_1.png'
            )
        );
        $leftMenuTypeList = array(
            self::MENU_TYPE_LEFT_1 => array(
                'file_suffix' => 'left_1',
                'label' => (((! empty($menuTypeLables)) && isset($menuTypeLables[self::MENU_TYPE_LEFT_1])) ?
                        $menuTypeLables[self::MENU_TYPE_LEFT_1] : 'left_1'),
                'image_file_name' => 'left_1.png'
            )
        );
        $footerMenuTypeList = array(
            self::MENU_TYPE_FOOTER_1 => array(
                'file_suffix' => 'footer_1',
                'label' => (((! empty($menuTypeLables)) && isset($menuTypeLables[self::MENU_TYPE_FOOTER_1])) ?
                        $menuTypeLables[self::MENU_TYPE_FOOTER_1] : 'footer_1'),
                'image_file_name' => 'footer_1.png'
            )
        
        );
        $defaultMenuTypeList = array(
            self::MENU_TYPE_MEGA => array(
                'file_suffix' => 'mega',
                'label' => ((! empty($menuTypeLables)) &&
                         isset($menuTypeLables[self::MENU_TYPE_MEGA])) ? $menuTypeLables[self::MENU_TYPE_MEGA] : 'mega',
                        'image_file_name' => 'mega.png'
            ),
            
            self::MENU_TYPE_SIMPLE => array(
                'file_suffix' => 'simple',
                'label' => (((! empty($menuTypeLables)) && isset($menuTypeLables[self::MENU_TYPE_SIMPLE])) ?
                        $menuTypeLables[self::MENU_TYPE_SIMPLE] : 'simple'),
                'image_file_name' => 'simple.png'
            )
        );
        $defaultMenuTypeList = self::mergeArray($defaultMenuTypeList, $sideMenuTypeList, $footerMenuTypeList);
        $defaultMenuTypeList = self::mergeArray($defaultMenuTypeList, $leftMenuTypeList, $rightMenuTypeList);
        $leftPreference = array(
            'need_search_bar' => false,
            'need_menu_type' => true,
            'preferred_menu_type' => self::MENU_TYPE_LEFT_1,
            'preferred_file_suffix' => 'left_1',
            'menu_type_list' => self::mergeArray($sideMenuTypeList, $leftMenuTypeList)
        );
        $rightPreference = array(
            'need_search_bar' => false,
            'need_menu_type' => true,
            'preferred_menu_type' => self::MENU_TYPE_RIGHT_1,
            'preferred_file_suffix' => 'right_1',
            'menu_type_list' => self::mergeArray($sideMenuTypeList, $rightMenuTypeList)
        );
        $footerPreference = array(
            'need_search_bar' => false,
            'need_menu_type' => true,
            'preferred_file_suffix' => 'footer_1',
            'preferred_menu_type' => self::MENU_TYPE_FOOTER_1,
            'menu_type_list' => $footerMenuTypeList
        );
        $defaultPreference = array(
            'need_search_bar' => true,
            'need_menu_type' => true,
            'preferred_menu_type' => self::MENU_TYPE_MEGA,
            'preferred_file_suffix' => 'mega',
            'menu_type_list' => $defaultMenuTypeList
        );
        return array(
            0 => $defaultPreference,
            (int) $hookLeftId => $leftPreference,
            (int) $hookRightId => $rightPreference,
            (int) $hookFooterId => $footerPreference
        
        );
    }

    public static function mergeArray($array1, $array2, $array3 = array())
    {
        foreach ($array2 as $key => $value) {
            $array1[$key] = $value;
        }
        foreach ($array3 as $key => $value) {
            $array1[$key] = $value;
        }
        return $array1;
    }
}
