<?php
class Validate extends ValidateCore
{
    /**
     * Validate SIRET Code
     *
     * @param string $siret SIRET Code
     * @return bool Return true if is valid
     */
    public static function isSiret($siret)
    {
		$id_shop = (int)Context::getContext()->shop->id;
		if($id_shop == 2){
			return true;
		}
		
        if (Tools::strlen($siret) != 14) {
            return false;
        }
        $sum = 0;
        for ($i = 0; $i != 14; $i++) {
            $tmp = ((($i + 1) % 2) + 1) * intval($siret[$i]);
            if ($tmp >= 10) {
                $tmp -= 9;
            }
            $sum += $tmp;
        }
        return ($sum % 10 === 0);
    }
}
