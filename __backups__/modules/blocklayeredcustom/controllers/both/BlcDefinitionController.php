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

include_once _PS_MODULE_DIR_ . 'blocklayeredcustom/classes/BlcAttributeGroup.php';
include_once _PS_MODULE_DIR_ . 'blocklayeredcustom/classes/BlcFilterBlock.php';

class BlcDefinitionController
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
		$this->setDefinitions();
    }
	
	public function setDefinitions()
    {
		$this->blockTypeDefinition = array(
			BlcFilterBlock::BLOCK_TYPE_MANUFACTURER => array(
				'label' => $this->module->l('Brand', __CLASS__)
			),
			BlcFilterBlock::BLOCK_TYPE_PRODUCT => array(
				'label' => $this->module->l('Model', __CLASS__)
			),
			BlcFilterBlock::BLOCK_TYPE_CARRIER => array(
				'label' => $this->module->l('Carrier', __CLASS__)
			),
			BlcFilterBlock::BLOCK_TYPE_PRICE => array(
				'label' => $this->module->l('Price', __CLASS__)
			),
			BlcFilterBlock::BLOCK_TYPE_STOCK => array(
				'label' => $this->module->l('Stock', __CLASS__)
			),
			BlcFilterBlock::BLOCK_TYPE_STATUS => array(
				'label' => $this->module->l('Status', __CLASS__)
			)
		);
		$this->filterTypeDefinition = array(
			BlcFilterBlock::FILTER_TYPE_RADIO => array(
				'label' => $this->module->l('Radio', __CLASS__),
				'configure_multiple' => false,
				'tpl_suffix' => 'radio'
			),
			BlcFilterBlock::FILTER_TYPE_CHECKBOX => array(
				'label' => $this->module->l('Checkbox', __CLASS__),
				'configure_multiple' => false,
				'tpl_suffix' => 'checkbox'
			),
			BlcFilterBlock::FILTER_TYPE_DROPDOWN_LIST => array(
				'label' => $this->module->l('Dropdown list', __CLASS__),
				'configure_multiple' => true,
				'tpl_suffix' => 'dropdown_list'
			),
			BlcFilterBlock::FILTER_TYPE_SLIDER => array(
				'label' => $this->module->l('Slider', __CLASS__),
				'configure_multiple' => false,
				'tpl_suffix' => 'slider'
			),
			BlcFilterBlock::FILTER_TYPE_INPUTS => array(
				'label' => $this->module->l('Inputs', __CLASS__),
				'configure_multiple' => false,
				'tpl_suffix' => 'inputs'
			),
			BlcFilterBlock::FILTER_TYPE_COLOR => array(
				'label' => $this->module->l('Color', __CLASS__),
				'configure_multiple' => true,
				'tpl_suffix' => 'color'
			)
		);
		$this->defaultTypeFilters = array(BlcFilterBlock::FILTER_TYPE_CHECKBOX, BlcFilterBlock::FILTER_TYPE_DROPDOWN_LIST, BlcFilterBlock::FILTER_TYPE_RADIO);
		$this->attributeGroupTypeFilters = $this->defaultTypeFilters;
		$this->attributeGroupTypeFilters[] = BlcFilterBlock::FILTER_TYPE_COLOR;
		$this->blockTypeFilters = array(
			BlcFilterBlock::BLOCK_TYPE_MANUFACTURER => $this->defaultTypeFilters,
			BlcFilterBlock::BLOCK_TYPE_PRODUCT => $this->defaultTypeFilters,
			BlcFilterBlock::BLOCK_TYPE_CARRIER => $this->defaultTypeFilters,
			BlcFilterBlock::BLOCK_TYPE_STOCK => $this->defaultTypeFilters,
			BlcFilterBlock::BLOCK_TYPE_STATUS => $this->defaultTypeFilters,
			BlcFilterBlock::BLOCK_TYPE_PRICE => array(BlcFilterBlock::FILTER_TYPE_SLIDER, BlcFilterBlock::FILTER_TYPE_INPUTS),
			BlcFilterBlock::BLOCK_TYPE_ATTRIBUTE_GROUP => $this->attributeGroupTypeFilters
		);
		
		$this->filterTypeConst = array(
			'FILTER_TYPE_RADIO' => BlcFilterBlock::FILTER_TYPE_RADIO,
			'FILTER_TYPE_CHECKBOX' => BlcFilterBlock::FILTER_TYPE_CHECKBOX,
			'FILTER_TYPE_DROPDOWN_LIST' => BlcFilterBlock::FILTER_TYPE_DROPDOWN_LIST,
			'FILTER_TYPE_SLIDER' => BlcFilterBlock::FILTER_TYPE_SLIDER,
			'FILTER_TYPE_INPUTS' => BlcFilterBlock::FILTER_TYPE_INPUTS,
			'FILTER_TYPE_COLOR' => BlcFilterBlock::FILTER_TYPE_COLOR
		);
	}
}
