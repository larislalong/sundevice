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
 * Class HTMLTemplateDhlManifest
 */
class HTMLTemplateDhlManifest extends HTMLTemplate
{
    /** @var DhlManifest $dhlManifest */
    public $dhlManifest;

    /** @var Context $context */
    public $context;

    /**
     * HTMLTemplateDhlManifest constructor.
     * @param DhlManifest $object
     * @param Smarty      $smarty
     */
    public function __construct($object, Smarty $smarty)
    {
        $this->smarty = $smarty;
        $this->dhlManifest = $object;
        $this->context = Context::getContext();
        $this->shop = $this->context->shop;
    }

    /**
     * @return string
     * @throws Exception
     * @throws SmartyException
     */
    public function getHeader()
    {
        $idState = Configuration::get('PS_SHOP_STATE_ID');
        $stateName = $idState ? State::getNameById($idState) : false;
        $shopDetails = array(
            'name'    => Configuration::get('PS_SHOP_NAME'),
            'addr1'   => Configuration::get('PS_SHOP_ADDR1'),
            'addr2'   => Configuration::get('PS_SHOP_ADDR2'),
            'zipcode' => Configuration::get('PS_SHOP_CODE'),
            'city'    => Configuration::get('PS_SHOP_CITY'),
            'country' => Country::getNameById(
                $this->context->language->id,
                (int) Configuration::get('PS_SHOP_COUNTRY_ID')
            ),
            'state'   => $stateName,
        );
        $this->context->smarty->assign(
            array(
                'dhl_img_path' => _PS_MODULE_DIR_.$this->dhlManifest->moduleName.'/views/img/',
                'manifest_for' => $this->dhlManifest->type,
                'date'         => date('Y-m-d'),
                'shop_details' => $shopDetails,
            )
        );

        return $this->context->smarty->fetch(
            _PS_MODULE_DIR_.$this->dhlManifest->moduleName.'/views/templates/admin/dhl_manifest/pdf/header.tpl'
        );
    }

    /**
     * @return string
     * @throws Exception
     * @throws SmartyException
     */
    public function getFooter()
    {
        $nbPieces = 0;
        $totalWeight = 0;
        foreach ($this->dhlManifest->shippingDetails as $shippingDetail) {
            $nbPieces += $shippingDetail['total_pieces'];
            $totalWeight += $shippingDetail['total_weight'];
        }
        $this->context->smarty->assign(
            array(
                'weight_unit'  => DhlTools::getWeightUnit(),
                'nb_shipment'  => count($this->dhlManifest->shippingDetails),
                'nb_pieces'    => $nbPieces,
                'total_weight' => $totalWeight,
            )
        );

        return $this->context->smarty->fetch(
            _PS_MODULE_DIR_.$this->dhlManifest->moduleName.'/views/templates/admin/dhl_manifest/pdf/footer.tpl'
        );
    }

    /**
     * @return string
     */
    public function getPagination()
    {
        return parent::getPagination();
    }

    /**
     * @return string
     * @throws Exception
     * @throws SmartyException
     */
    public function getContent()
    {
        $this->context->smarty->assign(
            array(
                'shipping_details' => $this->dhlManifest->shippingDetails,
            )
        );

        return $this->context->smarty->fetch(
            _PS_MODULE_DIR_.$this->dhlManifest->moduleName.'/views/templates/admin/dhl_manifest/pdf/content.tpl'
        );
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return 'DhlManifest_'.date('YmdHis').'.pdf';
    }

    /**
     *
     */
    public function getBulkFilename()
    {
    }
}
