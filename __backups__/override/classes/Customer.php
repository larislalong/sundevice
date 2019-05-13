<?php
class Customer extends CustomerCore
{
    public function add($autodate = true, $null_values = true)
    {
        $this->active = false;
		return parent::add($autodate, $null_values);
    }

}
