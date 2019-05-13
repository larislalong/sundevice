<?php
/*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

/**
 * @property Configuration $object
 */
class AdminPreferencesController extends AdminPreferencesControllerCore
{
    public function __construct()
    {
        parent::__construct();
        $this->fields_options['general']['fields']['PS_CART_LIFE_TIME'] = array(
            'title' => $this->l('Cart life time'),
            'desc' => $this->l('Time after which the cart will be cleared in case of inactivity. Expressed in minutes'),
            'validation' => 'isUnsignedInt',
            'cast' => 'intval',
            'type' => 'text',
            'class' => 'fixed-width-xxl'
        );
		$this->fields_options['general']['fields']['PS_CART_REFRESH_SHOW_TIME'] = array(
            'title' => $this->l('Popup refresh cart time'),
            'desc' => $this->l('Time remaining to cart before showing refresh popup. Expressed in second'),
            'validation' => 'isUnsignedInt',
            'cast' => 'intval',
            'type' => 'text',
            'class' => 'fixed-width-xxl'
        );
        $this->fields_options['general']['fields']['PS_ID_MODEl_ATTRIBUTE_GROUP'] = array(
            'title' => $this->l('Model attribute id'),
            'desc' => $this->l('ID of the model attribute'),
            'validation' => 'isUnsignedInt',
            'cast' => 'intval',
            'type' => 'text',
            'class' => 'fixed-width-xxl'
        );
        $this->fields_options['general']['fields']['PS_ID_GRADE_ATTRIBUTE_GROUP'] = array(
            'title' => $this->l('Grade attribute id'),
            'desc' => $this->l('ID of the grade attribute'),
            'validation' => 'isUnsignedInt',
            'cast' => 'intval',
            'type' => 'text',
            'class' => 'fixed-width-xxl'
        );
		$this->fields_options['general']['fields']['PS_ID_STORE_CONTACT'] = array(
            'title' => $this->l('Store attribute id'),
            'desc' => $this->l('ID of the store that will be used as commercial contact'),
            'validation' => 'isUnsignedInt',
            'cast' => 'intval',
            'type' => 'text',
            'class' => 'fixed-width-xxl'
        );
		
		$this->fields_options['general']['fields']['PS_ACCOUNTING_NUMBER'] = array(
            'title' => $this->l('Account number'),
            'desc' => $this->l('Bank Account number'),
            'cast' => 'strval',
            'type' => 'text',
            'class' => 'fixed-width-xxl'
        );
		$this->fields_options['general']['fields']['PS_ROUTING_NUMBER'] = array(
            'title' => $this->l('Routing number'),
            'desc' => $this->l('Bank Routing number'),
            'cast' => 'strval',
            'type' => 'text',
            'class' => 'fixed-width-xxl'
        );
    }
}
