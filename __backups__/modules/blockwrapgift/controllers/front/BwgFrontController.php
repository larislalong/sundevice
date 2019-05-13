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
include_once _PS_MODULE_DIR_ . 'blockwrapgift/classes/BwgWrapGift.php';
class BwgFrontController
{
	public $module;

    public $context;

    public $local_path;

    public function __construct($module, $context, $local_path, $_path, $file)
    {
        $this->module = $module;
        $this->context = $context;
        $this->local_path = $local_path;
        $this->_path = $_path;
		$this->file = $file;
    }
	public function renderList()
    {
		if(!(int)Configuration::get('PS_GIFT_WRAPPING')){
			return;
		}
		$template = 'packeging_list';
		$cacheId = $this->getCacheId($template);
		$template.='.tpl';
		if (! $this->module->isCached($template, $cacheId)) {
			$list = BwgWrapGift::getAll($this->context->language->id, true);
			$this->context->smarty->assign(
				array(
					'packeging_list' => $list,
					'image_folder' => $this->module->getUrl().$this->module->wrapGiftImageFolder
				)
			);
			
		}
		return $this->module->display($this->file, $template, $cacheId);
    }
	public function getCacheId($identifier)
    {
        return $this->module->smartyGetCacheId($this->module->name . $this->context->currency->sign . $identifier);
    }
}
