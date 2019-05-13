<?php
if (!defined('_PS_VERSION_'))
	exit;


class BlocknewsletterOverride extends Blocknewsletter{	
	public function hookblockPosition2($params)
	{
		return $this->hookDisplayLeftColumn($params);
	}
}