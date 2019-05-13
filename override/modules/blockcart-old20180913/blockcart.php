<?php
if (!defined('_PS_VERSION_'))
	exit;


class BlockCartOverride extends BlockCart{	
	public function hookblockPosition5($params)
	{
		if (Configuration::get('PS_CATALOG_MODE'))
			return;

		// @todo this variable seems not used
		$this->smarty->assign(array(
			'order_page' => (strpos($_SERVER['PHP_SELF'], 'order') !== false),
			'blockcart_top' => (isset($params['blockcart_top']) && $params['blockcart_top']) ? true : false,
		));
		$this->assignContentVars($params);
		return $this->display(__FILE__, 'blockcart1.tpl');
	}
}