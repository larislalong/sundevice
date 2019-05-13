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
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2018 PrestaShop SA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

/**
 * Class DhlAddress
 */
class DhlAddress extends ObjectModel
{
    /** @var int $id_dhl_address */
    public $id_dhl_address;

    /** @var int $id_country */
    public $id_country;

    /** @var int $id_state */
    public $id_state;

    /** @var string $contact_name */
    public $contact_name;

    /** @var string $contact_email */
    public $contact_email;

    /** @var string $contact_phone */
    public $contact_phone;

    /** @var string $company_name */
    public $company_name;

    /** @var string $vat_number */
    public $vat_number;

    /** @var string $account_import */
    public $account_import;

    /** @var string $account_export */
    public $account_export;

    /** @var string $account_duty */
    public $account_duty;

    /** @var string $address1 */
    public $address1;

    /** @var string $address2 */
    public $address2;

    /** @var string $address3 */
    public $address3;

    /** @var string $zipcode */
    public $zipcode;

    /** @var string $city */
    public $city;

    /** @var string $phone */
    public $phone;

    /** @var string $country */
    public $country;

    /** @var string $state */
    public $state;

    /** @var string $iso_country */
    public $iso_country;

    /** @var int $deleted */
    public $deleted;

    /** @var array */
    public static $definition = array(
        'table'   => 'dhl_address',
        'primary' => 'id_dhl_address',
        'fields'  => array(
            'id_country'     => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_state'       => array('type' => self::TYPE_INT, 'validate' => 'isNullOrUnsignedId', 'required' => true),
            'contact_name'   => array(
                'type'     => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'required' => true,
                'size'     => 35,
            ),
            'contact_email'  => array(
                'type'     => self::TYPE_STRING,
                'validate' => 'isEmail',
                'required' => true,
                'size'     => 50,
            ),
            'contact_phone'  => array(
                'type'     => self::TYPE_STRING,
                'validate' => 'isPhoneNumber',
                'required' => true,
                'size'     => 25,
            ),
            'company_name'   => array(
                'type'     => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'required' => true,
                'size'     => 35,
            ),
            'vat_number'     => array(
                'type'     => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'required' => true,
                'size'     => 35,
            ),
            'account_import' => array(
                'type'     => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'required' => false,
                'size'     => 12,
            ),
            'account_export' => array(
                'type'     => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'required' => true,
                'size'     => 12,
            ),
            'account_duty'   => array(
                'type'     => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'required' => false,
                'size'     => 12,
            ),
            'address1'       => array(
                'type'     => self::TYPE_STRING,
                'validate' => 'isAddress',
                'required' => true,
                'size'     => 35,
            ),
            'address2'       => array(
                'type'     => self::TYPE_STRING,
                'validate' => 'isAddress',
                'required' => false,
                'size'     => 35,
            ),
            'address3'       => array(
                'type'     => self::TYPE_STRING,
                'validate' => 'isAddress',
                'required' => false,
                'size'     => 35,
            ),
            'zipcode'        => array(
                'type'     => self::TYPE_STRING,
                'validate' => 'isPostCode',
                'required' => false,
                'size'     => 12,
            ),
            'city'           => array(
                'type'     => self::TYPE_STRING,
                'validate' => 'isCityName',
                'required' => true,
                'size'     => 35,
            ),
            'phone'          => array(
                'type'     => self::TYPE_STRING,
                'validate' => 'isPhoneNumber',
                'required' => true,
                'size'     => 25,
            ),
            'deleted'        => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
        ),
    );

    /**
     * DhlAddress constructor.
     * @param null $id_dhl_address
     * @param null $id_lang
     */
    public function __construct($id_dhl_address = null, $id_lang = null)
    {
        parent::__construct($id_dhl_address, $id_lang);
        if ($id_dhl_address) {
            $this->country = Country::getNameById(
                $id_lang ? $id_lang : Configuration::get('PS_LANG_DEFAULT'),
                $this->id_country
            );
            $this->iso_country = DHLTools::getIsoCountryById($this->id_country);
            if ($this->id_state) {
                $this->state = State::getNameById($this->id_state);
            }
        }

        return $this;
    }

    /**
     * @return bool
     * @throws PrestaShopException
     */
    public function delete()
    {
        $this->deleted = true;

        return $this->update();
    }

    /**
     * @param string       $field
     * @param string       $class
     * @param bool|true    $htmlentities
     * @param Context|null $context
     * @return string
     */
    public static function displayFieldName($field, $class = __CLASS__, $htmlentities = true, Context $context = null)
    {
        return Translate::getModuleTranslation('dhlexpress', $field, 'dhlexpress');
    }

    /**
     * @return array|false|mysqli_result|null|PDOStatement|resource
     * @throws PrestaShopDatabaseException
     */
    public static function getAddressList()
    {
        $dbQuery = new DbQuery();
        $dbQuery->select('da.*');
        $dbQuery->from(self::$definition['table'], 'da');
        $dbQuery->where('da.deleted=0');
        $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($dbQuery);
        if (!empty($results)) {
            foreach ($results as &$result) {
                $country = new Country((int) $result['id_country'], Context::getContext()->language->id);
                $result['country'] = $country->name;
                $result['iso'] = DhlTools::getIsoCountryById((int) $country->id);
                $result['title'] = $country->name.' - '.$result['city'];
                if ($result['id_state']) {
                    $state = new State((int) $result['id_state']);
                    $result['state'] = $state->name;
                }
            }
        }

        return $results;
    }

    /**
     * @return bool|DhlAddress
     */
    public static function getFirstAddress()
    {
        $dbQuery = new DbQuery();
        $dbQuery->select('da.id_dhl_address');
        $dbQuery->from(self::$definition['table'], 'da');
        $dbQuery->where('da.deleted=0');
        $dbQuery->orderBy('da.id_dhl_address ASC');
        $id = (int) Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($dbQuery);
        if (false === $id) {
            return false;
        } else {
            return new self($id);
        }
    }

    /**
     * @param int $idOriginCountry
     * @return mixed
     */
    public function getReturnShippingAccountNumber($idOriginCountry)
    {
        $idCountryOwner = (int) Configuration::get('DHL_ACCOUNT_OWNER_COUNTRY');
        if ($idCountryOwner === (int) $idOriginCountry) {
            return $this->account_export;
        } else {
            return $this->account_import;
        }
    }

    /**
     * @return mixed
     */
    public function getAccountNumber()
    {
        $idCountryOwner = (int) Configuration::get('DHL_ACCOUNT_OWNER_COUNTRY');
        if ($idCountryOwner === (int) $this->id_country) {
            return $this->account_export;
        } else {
            return $this->account_import;
        }
    }
}
