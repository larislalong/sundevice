<?php
/**
* 2007-2018 PrestaShop
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
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class SellerCommentCriterion extends ObjectModel
{
    public $id;
    public $name;
    public $active = true;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'seller_comment_criterion',
        'primary' => 'id_seller_comment_criterion',
        'multilang' => true,
        'fields' => array(
            'active' => array('type' => self::TYPE_BOOL),
            'name' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'required' => true, 'size' => 128),
        )
    );

    public function delete()
    {
        if (!parent::delete()) {
            return false;
        }

        return Db::getInstance()->execute('
            DELETE FROM `'._DB_PREFIX_.'seller_comment_grade`
            WHERE `id_seller_comment_criterion` = '.(int)$this->id);
    }

    /**
     * Add grade to a criterion
     *
     * @return boolean succeed
     */
    public function addGrade($id_seller_comment, $grade)
    {
        if (!Validate::isUnsignedId($id_seller_comment)) {
            die(Tools::displayError());
        }
        
        if ($grade < 0) {
            $grade = 0;
        } elseif ($grade > 10) {
            $grade = 10;
        }
        
        return (Db::getInstance()->execute(
            'INSERT INTO `'._DB_PREFIX_.'seller_comment_grade`
            (`id_seller_comment`, `id_seller_comment_criterion`, `grade`) VALUES(
            '.(int)($id_seller_comment).', '.(int)$this->id.', '.(int)($grade).')'
        ));
    }

    /**
     * Get criterion by Seller
     *
     * @return array Criterion
     */
    public static function getBySeller($id_seller, $id_lang)
    {
        if (!Validate::isUnsignedId($id_seller) || !Validate::isUnsignedId($id_lang)) {
            die(Tools::displayError());
        }

        $cache_id = 'SellerCommentCriterion::getBySeller_'.(int)$id_seller.'-'.(int)$id_lang;
        if (!Cache::isStored($cache_id)) {
            $result = Db::getInstance()->executeS(
                'SELECT pcc.`id_seller_comment_criterion`, s.`name`
                FROM `'._DB_PREFIX_.'seller_comment_criterion` pcc
                LEFT JOIN `'._DB_PREFIX_.'seller_comment_criterion_lang` pccl
                        ON (pcc.id_seller_comment_criterion = pccl.id_seller_comment_criterion)
                LEFT JOIN `'._DB_PREFIX_.'seller` s
                        ON (s.id_seller = '.(int)$id_seller.')
                WHERE pccl.`id_lang` = '.(int)($id_lang).'
                AND pcc.active = 1
                GROUP BY pcc.id_seller_comment_criterion'
            );
            Cache::store($cache_id, $result);
        }
        return Cache::retrieve($cache_id);
    }

    /**
     * Get Criterions
     *
     * @return array Criterions
     */
    public static function getCriterions($id_lang, $active = false)
    {
        if (!Validate::isUnsignedId($id_lang)) {
            die(Tools::displayError());
        }

        $sql = 'SELECT pcc.`id_seller_comment_criterion`, pccl.`name`, pcc.active
            FROM `'._DB_PREFIX_.'seller_comment_criterion` pcc
            JOIN `'._DB_PREFIX_.'seller_comment_criterion_lang` pccl ON (pcc.id_seller_comment_criterion = pccl.id_seller_comment_criterion)
            WHERE pccl.`id_lang` = '.(int)$id_lang.($active ? ' AND active = 1' : '').'
            ORDER BY pccl.`name` ASC';
        $criterions = Db::getInstance()->executeS($sql);

        return $criterions;
    }
}
