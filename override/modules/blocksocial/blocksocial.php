<?php
if (!defined('_PS_VERSION_'))
	exit;


class blocksocialOverride extends blocksocial{	
	public function hookblockFooter2($params)
	{
		return $this->hookDisplayFooter($params);
	}
}