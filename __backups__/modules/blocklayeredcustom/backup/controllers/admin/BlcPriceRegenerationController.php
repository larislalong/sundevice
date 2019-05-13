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

include_once _PS_MODULE_DIR_ . 'blocklayeredcustom/classes/BlcProductIndex.php';
include_once _PS_MODULE_DIR_ . 'blocklayeredcustom/classes/BlcProductAttributePrice.php';

class BlcPriceRegenerationController
{
    public $module;
    public $context;
    public $local_path;

    public function __construct($module, $context, $local_path, $_path)
    {
        $this->module = $module;
        $this->context = $context;
        $this->local_path = $local_path;
        $this->_path = $_path;
		$this->combinationLimit = 10;
    }

    public function init()
    {
		$operationContent = '';
		$listProduct = BlcProductIndex::getAllProducts(true, $this->context->language->id, $this->context->shop->id);
		$this->context->smarty->assign(array(
				'products' => $listProduct,
				'homeLink' => $this->module->getModuleHome(),
				'ajaxModuleUrl' => $this->module->getModuleHome(),
			)
		);
		
		$operationContent.= $this->context->smarty->fetch($this->local_path . 'views/templates/admin/regenerate.tpl');
        return $operationContent;
    }
	
	
	public function processInitRegenerateAll()
    {
		BlcProductIndex::setDeprecated();
		$this->ajaxDie(Tools::jsonEncode(array(
            'hasError' => false,
			)
        ));
    }
	
	public function processInitRegenerate()
    {
		$this->module->clearAllTplCache();
		$idProduct = (int)Tools::getValue('idProduct');
		BlcProductIndex::setDeprecated($idProduct);
		$count = BlcProductAttributePrice::getCombinationsCount($idProduct);
		$this->ajaxDie(Tools::jsonEncode(array(
            'hasError' => false,
            'count' => $count,
            'combinationLimit' => $this->combinationLimit,
			)
        ));
    }
	
	public function processRegenerate()
    {
		$page = (int)Tools::getValue('page');
		$count = (int)Tools::getValue('count');
		$idProduct = (int)Tools::getValue('idProduct');
		$start = 0;
		if(empty($this->combinationLimit)){
			$hasMore = false;
		}else{
			$start = ($page-1)*$this->combinationLimit;
			$hasMore = ($start+$this->combinationLimit)<$count;
		}
		BlcProductAttributePrice::indexProductPrices($idProduct, $start, $this->combinationLimit);
		if(!$hasMore){
			BlcProductIndex::setUpToDate($idProduct);
		}
		$this->ajaxDie(Tools::jsonEncode(array(
            'hasError' => false,
            'hasMore' => $hasMore,
			)
        ));
    }
	
	public function ajaxDie($content)
    {
		die($content);
    }
}
