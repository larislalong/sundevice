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

class SellerComment extends ObjectModel
{
    public $id;
    public $id_seller;
    public $id_order;
    public $id_product;
    public $id_customer;
    public $id_guest;
    public $customer_name;
    public $order_reference;
    public $product_name;
    public $title;
    public $content;
    public $grade;
    public $validate = 0;
    public $deleted = 0;
    public $date_add;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'seller_comment',
        'primary' => 'id_seller_comment',
        'fields' => array(
            'id_seller' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_order' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'id_product' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'id_customer' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_guest' => array('type' => self::TYPE_INT),
            'customer_name' => array('type' => self::TYPE_STRING),
            'product_name' => array('type' => self::TYPE_STRING),
            'order_reference' => array('type' => self::TYPE_STRING),
            'title' => array('type' => self::TYPE_STRING),
            'content' => array('type' => self::TYPE_STRING, 'validate' => 'isMessage', 'size' => 65535, 'required' => true),
            'grade' => array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat'),
            'validate' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'deleted' => array('type' => self::TYPE_BOOL),
            'date_add' => array('type' => self::TYPE_DATE),
        )
    );

    protected $webserviceParameters = array(
        'objectMethods' => array(
            'add' => 'addWs',
            'update' => 'updateWs'
        ),
        'objectNodeNames' => 'seller_comments',
        'fields' => array(
            'id_seller' => array('xlink_resource' => 'sellers'),
            'id_customer' => array('xlink_resource' => 'customers'),
        ),
    );

    public function addWs($autodate = true, $null_values = false)
    {
        $success = $this->add($autodate, $null_values);
        return $success;
    }

    public function updateWs($null_values = false)
    {
        $success = parent::update($null_values);
        return $success;
    }

    /**
     * Get comments by IdSeller
     *
     * @return array Comments
     */
    public static function getBySeller($id_seller, $p = 1, $n = null, $id_customer = null)
    {
        if (!Validate::isUnsignedId($id_seller)) {
            return false;
        }
        
        $validate = Configuration::get('JMARKETPLACE_MODERATE_COMMENTS');
        $p = (int)$p;
        $n = (int)$n;
        
        if ($p <= 1) {
            $p = 1;
        }
        
        if ($n != null && $n <= 0) {
            $n = 5;
        }

        $cache_id = 'SellerComment::getBySeller_'.(int)$id_seller.'-'.(int)$p.'-'.(int)$n.'-'.(int)$id_customer.'-'.(bool)$validate;
        if (!Cache::isStored($cache_id)) {
            $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(
                'SELECT 
                    pc.`id_seller_comment`,
                    IF(c.id_customer, CONCAT(c.`firstname`, \' \',  LEFT(c.`lastname`, 1)), 
                    pc.customer_name) customer_name, 
                    pc.`content`, 
                    pc.`grade`, 
                    pc.`date_add`, 
                    pc.title, 
                    id_product,
                    product_name
                FROM `'._DB_PREFIX_.'seller_comment` pc
                LEFT JOIN `'._DB_PREFIX_.'customer` c ON c.`id_customer` = pc.`id_customer`
                WHERE pc.`id_seller` = '.(int)($id_seller).($validate == '1' ? ' AND pc.`validate` = 1' : '').'
                ORDER BY pc.`date_add` DESC
                '.($n ? 'LIMIT '.(int)(($p - 1) * $n).', '.(int)($n) : '')
            );
            Cache::store($cache_id, $result);
        }
        return Cache::retrieve($cache_id);
    }

    /**
     * Return customer's comment
     *
     * @return arrayComments
     */
    public static function getByCustomer($id_seller, $id_customer, $get_last = false, $id_guest = false)
    {
        $cache_id = 'SellerComment::getByCustomer_'.(int)$id_seller.'-'.(int)$id_customer.'-'.(bool)$get_last.'-'.(int)$id_guest;
        if (!Cache::isStored($cache_id)) {
            $results = Db::getInstance()->executeS(
                'SELECT *
                FROM `'._DB_PREFIX_.'seller_comment` pc
                WHERE pc.`id_seller` = '.(int)$id_seller.'
                AND '.(!$id_guest ? 'pc.`id_customer` = '.(int)$id_customer : 'pc.`id_guest` = '.(int)$id_guest).'
                ORDER BY pc.`date_add` DESC '
                .($get_last ? 'LIMIT 1' : '')
            );

            if ($get_last && count($results)) {
                $results = array_shift($results);
            }

            Cache::store($cache_id, $results);
        }
        return Cache::retrieve($cache_id);
    }

    /**
     * Get Grade By seller
     *
     * @return array Grades
     */
    public static function getGradeBySeller($id_seller, $id_lang)
    {
        if (!Validate::isUnsignedId($id_seller) || !Validate::isUnsignedId($id_lang)) {
            return false;
        }
            
        $validate = Configuration::get('JMARKETPLACE_MODERATE_COMMENTS');

        return (Db::getInstance()->executeS('
            SELECT pc.`id_seller_comment`, pcg.`grade`, pccl.`name`, pcc.`id_seller_comment_criterion`
            FROM `'._DB_PREFIX_.'seller_comment` pc
            LEFT JOIN `'._DB_PREFIX_.'seller_comment_grade` pcg ON (pcg.`id_seller_comment` = pc.`id_seller_comment`)
            LEFT JOIN `'._DB_PREFIX_.'seller_comment_criterion` pcc ON (pcc.`id_seller_comment_criterion` = pcg.`id_seller_comment_criterion`)
            LEFT JOIN `'._DB_PREFIX_.'seller_comment_criterion_lang` pccl ON (pccl.`id_seller_comment_criterion` = pcg.`id_seller_comment_criterion`)
            WHERE pc.`id_seller` = '.(int)$id_seller.'
            AND pccl.`id_lang` = '.(int)$id_lang.
            ($validate == '1' ? ' AND pc.`validate` = 1' : ''))
        );
    }

    public static function getRatings($id_seller)
    {
        $validate = Configuration::get('JMARKETPLACE_MODERATE_COMMENTS');

        $sql = 'SELECT (SUM(pc.`grade`) / COUNT(pc.`grade`)) AS avg,
                        MIN(pc.`grade`) AS min,
                        MAX(pc.`grade`) AS max
                FROM `'._DB_PREFIX_.'seller_comment` pc
                WHERE pc.`id_seller` = '.(int)$id_seller.'
                AND pc.`deleted` = 0'.
                ($validate == '1' ? ' AND pc.`validate` = 1' : '');

        return Db::getInstance()->getRow($sql);
    }

    public static function getAverageGrade($id_seller)
    {
        $validate = Configuration::get('JMARKETPLACE_MODERATE_COMMENTS');

        return Db::getInstance()->getRow(
            'SELECT (SUM(pc.`grade`) / COUNT(pc.`grade`)) AS grade
            FROM `'._DB_PREFIX_.'seller_comment` pc
            WHERE pc.`id_seller` = '.(int)$id_seller.'
            AND pc.`deleted` = 0'.
            ($validate == '1' ? ' AND pc.`validate` = 1' : '')
        );
    }
    
    public static function getNumRowsBySellerAndGrade($id_seller, $grade)
    {
        $validate = Configuration::get('JMARKETPLACE_MODERATE_COMMENTS');

        return Db::getInstance()->getValue(
            'SELECT COUNT(*)
            FROM `'._DB_PREFIX_.'seller_comment` pc
            WHERE pc.`id_seller` = '.(int)$id_seller.'
            AND FLOOR(grade) = '.(int)$grade.'
            AND pc.`deleted` = 0'.
            ($validate == '1' ? ' AND pc.`validate` = 1' : '')
        );
    }

    public static function getAveragesBySeller($id_seller, $id_lang)
    {
        /* Get all grades */
        $grades = SellerComment::getGradeBySeller((int)$id_seller, (int)$id_lang);
        $total = SellerComment::getGradedCommentNumber((int)$id_seller);
        
        if (!count($grades) || (!$total)) {
            return array();
        }

        /* Addition grades for each criterion */
        $criterionsGradeTotal = array();
        $count_grades = count($grades);
        
        for ($i = 0; $i < $count_grades; ++$i) {
            if (array_key_exists($grades[$i]['id_seller_comment_criterion'], $criterionsGradeTotal) === false) {
                $criterionsGradeTotal[$grades[$i]['id_seller_comment_criterion']] = (int)($grades[$i]['grade']);
            } else {
                $criterionsGradeTotal[$grades[$i]['id_seller_comment_criterion']] += (int)($grades[$i]['grade']);
            }
        }

        /* Finally compute the averages */
        $averages = array();
        
        foreach ($criterionsGradeTotal as $key => $criterionGradeTotal) {
            $averages[(int)($key)] = (int)($total) ? ((int)($criterionGradeTotal) / (int)($total)) : 0;
        }
        return $averages;
    }

    /**
     * Return number of comments and average grade by products
     *
     * @return array Info
     */
    public static function getCommentNumber($id_seller)
    {
        if (!Validate::isUnsignedId($id_seller)) {
            return false;
        }
        
        $validate = (int)Configuration::get('JMARKETPLACE_MODERATE_COMMENTS');
        
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue(
            'SELECT COUNT(`id_seller_comment`) AS "nbr"
            FROM `'._DB_PREFIX_.'seller_comment` pc
            WHERE `id_seller` = '.(int)($id_seller).($validate == '1' ? ' AND `validate` = 1' : '')
        );
    }

    /**
     * Return number of comments and average grade by products
     *
     * @return array Info
     */
    public static function getGradedCommentNumber($id_seller)
    {
        if (!Validate::isUnsignedId($id_seller)) {
            return false;
        }
        
        $validate = (int)Configuration::get('JMARKETPLACE_MODERATE_COMMENTS');

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow(
            'SELECT COUNT(pc.`id_seller`) AS nbr
            FROM `'._DB_PREFIX_.'seller_comment` pc
            WHERE `id_seller` = '.(int)($id_seller).($validate == '1' ? ' AND `validate` = 1' : '').'
            AND `grade` > 0'
        );
        
        return (int)($result['nbr']);
    }

    /**
     * Get comments by Validation
     *
     * @return array Comments
     */
    public static function getByValidate($validate = '0')
    {
        $sql  = 'SELECT 
                    pc.`id_seller_comment`, 
                    pc.`id_seller`, 
                    IF(c.id_customer, CONCAT(c.`firstname`, \' \',  c.`lastname`), pc.customer_name) customer_name, 
                    pc.`title`, 
                    pc.`content`, 
                    pc.`grade`, 
                    pc.`date_add`, 
                    s.`name`, 
                    pc.`validate`,
                    product_name,
                    order_reference
                FROM `'._DB_PREFIX_.'seller_comment` pc
                LEFT JOIN `'._DB_PREFIX_.'customer` c ON (c.`id_customer` = pc.`id_customer`)
                LEFT JOIN `'._DB_PREFIX_.'seller` s ON (s.`id_seller` = pc.`id_seller`)
                WHERE pc.`validate` = '.(int)$validate;

        $sql .= ' ORDER BY pc.`date_add` DESC';

        return (Db::getInstance()->executeS($sql));
    }

    /**
     * Get all comments
     *
     * @return array Comments
     */
    public static function getAll()
    {
        return Db::getInstance()->executeS(
            'SELECT sc.`id_seller_comment`, sc.`id_seller`, customer_name, sc.`content`, sc.`grade`, sc.`date_add`
            FROM `'._DB_PREFIX_.'seller_comment` sc
            ORDER BY sc.`date_add` DESC'
        );
    }

    /**
     * Validate a comment
     *
     * @return boolean succeed
     */
    public function validate($validate = '1')
    {
        if (!Validate::isUnsignedId($this->id)) {
            return false;
        }

        $success = Db::getInstance()->execute(
            'UPDATE `'._DB_PREFIX_.'seller_comment` SET
            `validate` = '.(int)$validate.'
            WHERE `id_seller_comment` = '.(int)$this->id
        );

        Hook::exec('actionObjectSellerCommentValidateAfter', array('object' => $this));
        return $success;
    }

    /**
     * Delete a comment, grade and report data
     *
     * @return boolean succeed
     */
    public function delete()
    {
        parent::delete();
        SellerComment::deleteGrades($this->id);
    }

    /**
     * Delete Grades
     *
     * @return boolean succeed
     */
    public static function deleteGrades($id_seller_comment)
    {
        if (!Validate::isUnsignedId($id_seller_comment)) {
            return false;
        }
        
        return Db::getInstance()->execute(
            'DELETE FROM `'._DB_PREFIX_.'seller_comment_grade`
            WHERE `id_seller_comment` = '.(int)$id_seller_comment
        );
    }

    /**
     * Comment already report
     *
     * @return boolean
     */
    public static function isAlreadyReport($id_seller_comment, $id_customer)
    {
        return (bool)Db::getInstance()->getValue('
            SELECT COUNT(*)
            FROM `'._DB_PREFIX_.'seller_comment_report`
            WHERE `id_customer` = '.(int)$id_customer.'
            AND `id_seller_comment` = '.(int)$id_seller_comment);
    }

    public static function isAlreadyComment($id_seller, $id_customer, $id_guest, $id_order, $id_product)
    {
        return Db::getInstance()->getValue(
            'SELECT COUNT(*)
            FROM `'._DB_PREFIX_.'seller_comment`
            WHERE `id_customer` = '.(int)$id_customer.'
            AND `id_guest` = '.(int)$id_guest.'
            AND `id_order` = '.(int)$id_order.'
            AND `id_product` = '.(int)$id_product.'
            AND `id_seller` = '.(int)$id_seller
        );
    }
}
