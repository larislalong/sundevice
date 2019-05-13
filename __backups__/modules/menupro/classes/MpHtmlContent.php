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

class MpHtmlContent extends ObjectModel
{
    const POSITION_NONE = 0;

    const POSITION_LEFT = 1;

    const POSITION_RIGHT = 2;

    const POSITION_TOP = 3;

    const POSITION_DOWN = 4;

    public $id_menupro_html_content;

    public $id_menupro_secondary_menu;

    public $content;

    public $position;

    public $active;

    public static $definition = array(
        'table' => 'menupro_html_content',
        'multilang' => true,
        'primary' => 'id_menupro_html_content',
        'fields' => array(
            'position' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId'
            ),
            'id_menupro_secondary_menu' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId',
                'required' => true
            ),
            'content' => array(
                'type' => self::TYPE_HTML,
                'lang' => true,
                'validate' => 'isCleanHtml',
                'size' => 3999999999999
            ),
            'active' => array(
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool'
            )
        )
    );

    /**
     * Retourne les contenus d'un menu
     *
     * @param int $idMenu
     *            L'identifiant du menu
     * @param bool $onlyActive
     *            Permet de spécifier si l'on veut seleument ceux qui sont
     *            activés
     * @param int|bool $idLang
     *            L'identifiant de la langue
     *            (s'il la valeur est false, il n'y aura pas de jointure avec la
     *            table de langue)
     * @return array
     */
    public static function getAll($idMenu, $onlyActive = false, $idLang = false)
    {
        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . self::$definition['table'] . ' t ' .
                ((! empty($idLang)) ? ('LEFT JOIN ' . _DB_PREFIX_ . self::$definition['table'] .
                        '_lang tl ON (t.' . self::$definition['primary'] . ' = tl.' .
                self::$definition['primary'] . ' AND tl.id_lang = ' . (int) $idLang . ')') : '') .
                ' WHERE (id_menupro_secondary_menu=' . (int) $idMenu . ') AND (position <> ' .
                self::POSITION_NONE . ')' . ((! empty($onlyActive)) ? ' AND (t.active=1)' : '');
        $result = Db::getInstance()->executeS($sql);
        return $result;
    }

    /**
     * Retourne l'identifiant du contenu html d'un menu secondaire
     *
     * @param int $idMenu
     *            L'identifiant du menu
     * @return int
     */
    public static function getIdForSecondaryMenu($idMenu)
    {
        $result = Db::getInstance()->getValue('SELECT ' . self::$definition['primary'] . ' FROM ' . _DB_PREFIX_ .
                 self::$definition['table'] . ' t WHERE (id_menupro_secondary_menu=' . (int) $idMenu .
                 ') AND (position = ' . self::POSITION_NONE . ')');
        return (int) $result;
    }

    /**
     * Verifie si un autre contenu est déjà enregistré à une position
     *
     * @param int $idMenu
     *            L'identifiant du menu
     * @param int $position
     *            Position
     * @param int $idContent
     *            L'identifiant du contenu (cas de la modification)
     * @return bool
     */
    public static function isPositionExist($idMenu, $position, $idContent = 0)
    {
        $sql = 'SELECT COUNT(*) FROM ' . _DB_PREFIX_ . self::$definition['table'] .
        ' t WHERE (id_menupro_secondary_menu=' . (int) $idMenu . ') AND (position = ' . (int) $position . ')' .
        ((! empty($idContent)) ? (' AND (' .
        self::$definition['primary'] . ' <> ' . (int) $idContent . ')') : '');
        $result = (int) Db::getInstance()->getValue($sql);
        return $result > 0;
    }
}
